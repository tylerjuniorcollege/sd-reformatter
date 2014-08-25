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

	}

	function source_array($filename, $content, $user_id = 1, $type = 'text/html') {
		$source_type_id = ORM::for_table('source_type')->select('id')->where('type', $type)->find_one();
		return array(
			'uniqid'   	  => uniqid(),
			'md5'	   	  => md5($content),
			'user_id'  	  => $user_id,
			'filename' 	  => $filename,
			'content'  	  => $content,
			'source_type' => $source_type_id->id
		);
	}

	function source_exists($md5_str) {
		return (ORM::for_table('source')->where('md5', $md5_str)->count() > 0);
	}