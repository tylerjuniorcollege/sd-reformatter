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

	protected function processUrl($url) {
		$req = Requests::get($url);
		$md5 = md5($req->body);
		$source_link = ORM::for_table('source_link')->create();
		$source_link->html_id = $this->html_src_obj->id();
		if(source_exists($md5)) {
			$source = ORM::for_table('source')->where('md5', $md5)->find_one();
		} else {
			// Parsing Content Type to remove any extra stuff.
			$extra = strpos($req->headers['content_type'], ';');
			if($extra !== FALSE)
				$content_type = substr($req->headers['content_type'], 0, $extra);
			else
				$content_type = $req->headers['content_type'];

			$source = ORM::for_table('source')->create();
			$source->set(source_array($req->url, $req->body, $this->user_id, $content_type));
			$source->set_expr('submitted', "DateTime('now')");
			$source->save();
		}

		// Link the source to html.
		$source_link->source_id = $source_id;
		$source_link->save();

		return $source;
	}

	protected function processStylesheets() {
		// Grab Link/Stylesheets.
		$links = $this->html_str_obj->find('link');

		foreach($links as $link) {
			if(is_null(parse_url($link->href, PHP_URL_HOST))) {
				// This means that this is a file that needs to be uploaded ... skip for now.
			} else { // This means that there is an internet host in the href.
				$this->sources[] = $this->processUrl($link->href);
			}
		}

		return;
	}

	public function __construct($html_str, $html_filename, $user_id = 1) {
		// The HTML String must be compared to the one in the database.
		$this->setHtmlStr($html_str);
		$this->setHtmlFilename($html_filename);
		$this->user_id = $user_id;

		$this->html_str_obj = new simple_html_dom();
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
			$source = ORM::for_table('source')->where('md5', $md5)->find_one();
			$app->flash('message', 'Duplicate HTML Code Exists in the database. Using the one submitted on ' . $source->submitted);
		} else {
			// We save the source to make sure that it DOES exist in the database.
			$source = ORM::for_table('source')->create();
			$source->set(source_array($this->html_filename, $this->html_str, $this->user_id));
			$source->set_expr('submitted', "DateTime('now')");
			$source->save();
		}

		$this->html_src_obj = $source;
	}
}