<?php
	/**
	 * SD-Formatter (maybe renamed ... who knows)
	 *
	 * (c) Copyright 2014 Tyler Junior College
	 * See LICENSE file for License details
	 *
	 * Purpose: A simple mini web application to make sure HTML is formatted for the SoftDocs system.
	 * 			Uses Slim Framework and Idiorm.
	 * Requirements: PHP 5.3+ and SQLite for simple user/database stores.
	 **/

	function upload_error($error_code) {
		switch($error_code) {
			case UPLOAD_ERR_INI_SIZE:
				return 'The Uploaded File is bigger than ' . ini_get('upload_max_filesize');
				break;

			case UPLOAD_ERR_FORM_SIZE:
				return 'The Uploaded File is bigger than the form specified.';
				break;

			case UPLOAD_ERR_PARTIAL:
				return 'The file was partially uploaded. Please Try Again.';
				break;

			case UPLOAD_ERR_NO_FILE:
				return 'No File was Uploaded.';
				break;

			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Configuration Error. No Temporary Folder was found.';
				break;

			case UPLOAD_ERR_CANT_WRITE:
				return "Can't write file to disk.";
				break;

			case UPLOAD_ERR_EXTENSION:
				return 'A PHP Extension blocked the upload.';
				break;
		}
	}

	function content_type($content_type) {
		return ORM::for_table('source_type')->select('id')->where('type', $content_type)->find_one();
	}

	function source_array($filename, $content, $user_id = 1, $type = 'text/html') {
		$source_type_id = content_type($type);
		return array(
			'uniqid'   	  => uniqid(),
			'md5'	   	  => md5($content),
			'user_id'  	  => $user_id,
			'filename' 	  => $filename,
			'content'  	  => $content,
			'source_type' => $source_type_id->id
		);
	}

	function source_exists($md5_str, $filename = null) {
		$where = array(array('md5' => $md5_str));
		if(!is_null($filename)) {
			$where[] = array('filename' => $filename);
		}

		return (ORM::for_table('source')->where_any_is($where)->count() > 0);
	}

	function source_settings($source_id, $postvars) {
		$settings = ORM::for_table('source_settings')->create();
		$settings->set(array('source_id' => $source_id,
							 'output_options' => serialize($postvars['outputoptions'])));

		foreach($postvars as $id => $val) {
			switch($id) {
				case 'compressassets':
					$settings->asset_compression = 1;
					break;

				case 'injectassets':
					$settings->injectassets = 1;
					break;

				case 'changeassetdir':
					$settings->script_dir = $postvars['scriptsdir'];
					$settings->style_dir = $postvars['styledir'];
					break;
			}
		}

		$settings->save();
		return $settings;
	}

	function alert_view($flash) {
		$alert_html = '<div class="alert alert-%s alert-dismissible" role="alert">'
				    . '<button type="button" class="close" data-dismiss="alert">'
				    . '<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>'
				    . '</button>'
				    . '<strong>%s</strong> %s'
				    . '</div>';
		foreach($flash as $type => $alert) {
			switch($type) {
				case 'url-error':
				case 'file-error':
					printf($alert_html, 'danger', ($type == 'url-error' ? 'URL' : 'File Upload') . ' Error:', $alert);
					break;

				case 'delete-action':
					printf($alert_html, 'warning', 'File Deleted', $alert);
					break;

				case 'parser-error':
					printf($alert_html, 'danger', 'Parser Error', $alert);
					break;

				default:
					printf($alert_html, 'warning', '', $alert);
					break;
			}
		}
	}

	function parsed_filename($filename, $source_type) {
		$parsed = parse_url($filename);

		$filename = basename($parsed['path']);
		$info = pathinfo($filename);

		// If the filename doesn't include the extension we should add it.
		switch($source_type) {
			case 'text/html':
				$ext = 'html';
				break;

			case 'text/css':
				$ext = 'css';
				break;

			case 'text/javascript':
			case 'application/x-javascript':
			case 'application/javascript':
				$ext = 'js';
				break;
		}

		if(!isset($info['extension'])) {
			$filename .= '.' . $ext;
		} elseif(empty($info['extension'])) {
			$filename .= $ext;
		}

		return $filename;
	}

	function display_stats($stat_type, $stat_amount, $pass = TRUE) {
		if($pass === TRUE) {
			$passfail = '<span class="glyphicon glyphicon-ok text-success"></span>';
		} elseif($pass === FALSE) {
			$passfail = '<span class="glyphicon glyphicon-remove text-danger></span>';
		}

		$constant = '\TJC\Parser\HTML::DISP_' . strtoupper($stat_type);

		return sprintf('<tr>
			<td class="passfail">%s</td>
			<td>%s: %s</td>
			</tr>', $passfail, constant($constant), $stat_amount);
	}

	function display_download($id) {
		$app = \Slim\Slim::getInstance();

		$source = ORM::for_table('source')->find_one($id);

		if($source->filename == 'inline') {
			return; // Remove all instances of the inline script for download at the current moment.
		}

		$source_type = ORM::for_table('source_type')->find_one($source->source_type);

		return sprintf('<tr> 
			<td><a href="%s">Download</a></td>
			<td>%s</td>
			</tr>', clean_link('download', $source->id), parsed_filename($source->filename, $source_type->type));
	}

	function clean_link($url_name, $id) {
		$app = \Slim\Slim::getInstance();

		return $app->urlFor($url_name, array('id' => $id));
	}

	function navbar_tools($data_arr) {
		if(!isset($data_arr['tools']) || empty($data_arr['tools'])) { // If the array doesn't exist OR if it's empty, then kick it back without the tools link.
			return;
		}

		$clean_titles = function($tool) {
			switch($tool) {
				case 'edit':
					return "Edit";
					break;
				case 'display':
					return "View Original HTML";
					break;
				case 'displayparsed':
					return "View Parsed HTML";
					break;
				case 'transform':
					return "Clean Transform.xsl";
					break;
				case 'downloadzip':
					return "Download Zipped HTML";
					break;
			}	
		};

		$tool_arr = array();

		foreach($data_arr['tools'] as $tool => $id) {
			if(substr($tool, 0, 7)  == 'divider') {
				$tool_arr[] = '<li class="divider"></li>';
				continue;
			}
			$tool_arr[] = sprintf('<li><a href="%s">%s</a></li>', clean_link($tool, $id), $clean_titles($tool));
		}

// Icky HEREDOC stuff to make this string all super nice
$dropdown = <<<HTML
<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Tools <span class="caret"></span></a>
	<ul class="dropdown-menu pull-right" role="menu">
	%s
	</ul>
</li>
HTML;

		return sprintf($dropdown, implode($tool_arr));
	}
