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

namespace TJC\Processor;

// This is the HTML Processor class.
// The Processor class is used to save content to the database and 
class HTML
{
	protected $html_str;
	protected $html_str_obj;
	protected $html_filename;
	protected $user_id;
	protected $html_src_obj;
	protected $sources = array();

	// This is the main processing function.
	protected function processor($body, $filename, $content_type) {
		$md5 = md5($body);

		// Check to see if source exists or not.
		if(source_exists($md5)) {
			$source = \ORM::for_table('source')->where('md5', $md5)->find_one();
		} else {
			// Parse $content_type for any extra javascript cruft.
			$extra = strpos($content_type, ';');
			if($extra !== FALSE)
				$content_type = substr($content_type, 0, $extra);

			// LOG THIS CRAP.
			$log = \ORM::for_table('logging')->create();
			$log->set_expr('timestamp', "DateTime('now')");
			$log->filename = $filename;
			$log->data = 'Content-type: ' . $content_type;
			$log->save();

			if(is_null($content_type)) {
				$content_type = 'text/plain';
			}

			$source = \ORM::for_table('source')->create();
			$source->set(source_array($filename, $body, $this->user_id, trim(trim($content_type, ';'))));
			$source->set_expr('submitted', "DateTime('now')");
			$source->save();
		}

		// Add a check to see if the source link exists already.
		$exists = \ORM::for_table('source_link')->where(array('html_id' => $this->html_src_obj->id(),
															 'source_id' => $source->id()))->count();

		if($exists == 0) {
			// Save source link.
			// Setup the link between sources.
			$source_link = \ORM::for_table('source_link')->create();
			$source_link->html_id = $this->html_src_obj->id();
			$source_link->source_id = $source->id();
			$source_link->save();
		}

		return $source;
	}

	protected function processUrl($url) {
		if(!is_null(parse_url($url, PHP_URL_HOST))) {
			$req = \Requests::get($url);
			return $this->processor($req->body, $req->url, $req->headers['content-type']);
		}
	}

	protected function processInline($body, $content_type) {
		return $this->processor($body, 'inline', $content_type);
	}

	protected function processStylesheets() {
		// Grab Link/Stylesheets.
		$links = $this->html_str_obj->find('link');

		foreach($links as $link) {
			if($link->rel != 'stylesheet')
				continue;

			$this->sources[] = $this->processUrl($link->href);
		}
		return;
	}

	protected function processStyles() {
		// Grab all styles.
		$styles = $this->html_str_obj->find('style');

		foreach($styles as $style) {
			if(!empty($style->innertext)) {
				$this->sources[] = $this->processInline($style->innertext, 'text/css');
			}
		}
	}

	protected function processJavascript() {
		$js = $this->html_str_obj->find('script');

		foreach($js as $script) {
			if(isset($script->src)) {
				// Parse url.
				$this->sources[] = $this->processUrl($script->src);
			} elseif (!empty($script->innertext)) {
				$this->sources[] = $this->processInline($script->innertext, 'text/javascript');
			}
		}

		return;
	}

	public function __construct($html_str, $html_filename, $user_id = 1) {
		// The HTML String must be compared to the one in the database.
		$this->setHtmlStr($html_str);
		$this->setHtmlFilename($html_filename);
		$this->user_id = $user_id;

		$this->html_str_obj = new \simple_html_dom();
		$this->html_str_obj->load($this->html_str);

		return $this;
	}
	
	public function getHtmlObj() {
		return $this->html_str_obj;
	}
	
	public function getHtmlStr() {
		return $this->html_str;
	}

	public function setHtmlStr($html) {
		$this->html_str = $html;
		return $this;
	}
	
	public function getHtmlFilename() {
		return $this->html_filename;
	}
	
	public function setHtmlFilename($filename) {
		$this->html_filename = $filename;
		return $this;
	}

	public function getSourceObj() {
		return $this->html_src_obj;
	}

	public function process() {
		$app = \Slim\Slim::getInstance(); // Getting the app for passing messages to the main app.

		// We first check to see if the content already exists in the database.
		$md5 = md5($this->html_str);
		if(source_exists($md5)) {
			$source = \ORM::for_table('source')->where('md5', $md5)->find_one();
			$app->flash('message', 'Duplicate HTML Code Exists in the database. Using the one submitted on ' . $source->submitted);
		} else {
			// We save the source to make sure that it DOES exist in the database.
			$source = \ORM::for_table('source')->create();
			$source->set(source_array($this->html_filename, $this->html_str, $this->user_id));
			$source->set_expr('submitted', "DateTime('now')");
			$source->save();
		}

		$this->html_src_obj = $source;

		// Now we need to process the javascript and css information.
		$this->processStylesheets();
		$this->processStyles();
		$this->processJavascript();

		return $source;
	}

	public function injectJavascript($js_code, $filename = 'inject') {
		return $this->processor($js_code, $filename, 'text/javascript');
	}

	public function injectCss($css_code, $filename = 'inject') {
		return $this->processor($css_code, $filename, 'text/css');
	}
}