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

namespace TJC\Parser;

class HTML
{
	const NBSP = 'nbsp';
	const UNIQUEINPUT = 'uniqueinput';
	const READONLY = 'readonly';
	const EMPTYTAGS = 'emptytags';
	protected $document;
	protected $altered_document;
	protected $doc_object;

	// Submit a string of the HTML document to the class. This will then allow for text replacement to happen.
	public function __construct($document) {
		$this->document = $document;

		return $this;
	}

	public function replace_nbsp() {
		$this->altered_document = str_replace('&nbsp;', ' ', $this->document, $count);
		return $count;
	}

	public function parse_unique_input() {
		$names = array();
		$dupe_names = array();
		//$counter = 0; // This is incremented when there are duplicate names in the document.
		foreach($this->doc_object->find('input') as $input) {
			if(!in_array($input->name, $names)) {
				$names[] = $input->name;
			} else { // This does already exist.
				if(!array_key_exists($input->name, $dupe_names) &&
				   ($input->type != 'radio' || $input->type != 'checkbox')) { // This only exists to suppress any T_NOTICE errors.
					$dupe_names[$input->name] = 0;
				}
				$dupe_names[$input->name] += 1;
			}
		}

		return $dupe_names;
	}

	// This is only done near the end of the file generation. Before saving the final document.
	public function replace_readonly() {
		$count = 0;
		foreach($this->doc_object->find('input[readonly]') as $input) {
			$input->readonly = 'readonly';
			$count++;
		}
		return $count;
	}

	// Parse is the simple function that runs all the automation cleanup code. 
	// Passing an argument like "nonbsp" or "noreadonly" will stop that action from being run.
	public function parse() {
		$args = func_get_args();

		$counters = array();
		$results = array();

		if(!in_array(self::NBSP, $args)) {
			$counters[self::NBSP] = $this->replace_nbsp();
		}

		// Now we put the document in the parsing object.
		$this->doc_object = new \simple_html_dom();
		$this->doc_object->load($this->altered_document);

		// Check for unique names foreach input element.
		if(!in_array(self::UNIQUEINPUT, $args)) {
			$results[self::UNIQUEINPUT] = $this->parse_unique_input();
			$counters[self::UNIQUEINPUT] = array_sum($results['unique_input']);
		}


		// Now we run the readonly reformat.
		if(!in_array(self::READONLY, $args)) {
			$counters[self::READONLY] = $this->replace_readonly();
		}

		if(!in_array(self::EMPTYTAGS, $args)) {
			// Add in the counter for the empty tags.
			$counters[self::EMPTYTAGS] = 0;
			$this->doc_object->set_callback(function($element) use (&$counters) {
				// This will input html comments in to any element that is "empty".
				if(empty($element->innertext)) {
					$element->innertext = '<!-- -->';
					$counters[self::EMPTYTAGS]++;
				}
			});
		}


	}
}