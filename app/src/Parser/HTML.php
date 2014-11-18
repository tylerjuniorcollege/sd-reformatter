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
use TJC\Parser\ParserAbstract;

class HTML extends ParserAbstract
{
	const NBSP = 'nbsp';
	const DISP_NBSP = 'Non-Breaking Space';
	const UNIQUEINPUT = 'uniqueinput';
	const DISP_UNIQUEINPUT = 'Has Unique Input Names';
	const READONLY = 'readonly';
	const DISP_READONLY = 'Read Only Replacements';
	const EMPTYTAGS = 'emptytags';
	const DISP_EMPTYTAGS = 'Number of Empty Tags Updated';
	const UNIQUEVALUES  = 'uniquevalues';
	const DISP_UNIQUEVALUES = 'Number of Non-Unique Values';
	const LABELMATCH = 'labelmatch';
	const DISP_LABELMATCH = 'Number of Labels that Match';
	const CLEANVALUES = 'cleanvalues';
	const DISP_CLEANVALUES = 'Number of Clean Values';
	const SUBMITBUTTON = 'submitbutton';
	const DISP_SUBMITBUTTON = 'Submit Button Exists/Removed';

	protected $doc_object;

	public static function options() {
		return array(self::NBSP => self::DISP_NBSP,
					 self::UNIQUEINPUT => self::DISP_UNIQUEINPUT,
					 self::READONLY => self::DISP_READONLY,
					 self::EMPTYTAGS => self::DISP_EMPTYTAGS,
					 self::UNIQUEVALUES => self::DISP_UNIQUEVALUES,
					 self::LABELMATCH => self::DISP_LABELMATCH,
					 self::CLEANVALUES => self::DISP_CLEANVALUES,
					 self::SUBMITBUTTON => self::DISP_SUBMITBUTTON);
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
				if(($input->type != 'radio') && ($input->type != 'checkbox')) {
					if(!array_key_exists($input->name, $dupe_names)) {
						$dupe_names[$input->name] = 0;
					} else {
						$dupe_names[$input->name] += 1;
					}
				}
			}
		}

		return $dupe_names;
	}

	// This will see if there are unique values for CHECKBOXES AND RADIO ELEMENTS ONLY! Do not use this for
	// anything else.
	/* public function unique_values() {
		//$results = array();
		$values = array();
		foreach($this->doc_object->find('input[type=checkbox], input[type=radio]') as $input) {
			// Get name for the input:
			if(!array_key_exists($input->name, $values)) {
				$values[$input->name] = array();
			}

			if(!array_key_exists($input->value, $values[$input->name])) {
				$values[$input->name][$input->value] = 0;
			}
			$values[$input->name][$input->value]++;
		}

		
	}
	*/ // Removing this for version 0.1 ... will release an update with it later.

	// Removing the submit button.
	public function remove_submit() {
		$submit = $this->doc_object->find('button[type=submit], input[type=submit]');
		
		if(count($submit) > 0) {
			foreach($submit as $sub) {
				$sub->outertext = '';
			}
			return count($submit);
		} else
			return 0;
	}


	// This will find all inputs and see if there are any labels that match. IF there are more than 1 label,
	// then we need to flag these.
	public function match_labels() {
		$results = array();
		// GET ALL INPUTS.
		foreach($this->doc_object->find('input') as $input) {
			if($label_count = count($this->doc_object->find('label[for='.$input->id.']')) > 1) {
				$results[$input->id] = $label_count;
			}
		}
		return $results;
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
		// Replacing simple_html_dom with phpQuery.

		// Check for unique names foreach input element.
		if(!in_array(self::UNIQUEINPUT, $args)) {
			$results[self::UNIQUEINPUT] = $this->parse_unique_input();
			$counters[self::UNIQUEINPUT] = array_sum($results[self::UNIQUEINPUT]);
		}

		if(!in_array(self::LABELMATCH, $args)) {
			$results[self::LABELMATCH] = $this->match_labels();
			$counters[self::LABELMATCH] = count($results[self::LABELMATCH]);
		}

		/* if(!in_array(self::UNIQUEVALUES, $args)) {

		} */

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

		if(!in_array(self::SUBMITBUTTON, $args)) {
			$counters[self::SUBMITBUTTON] = $this->remove_submit();
		}

		return $counters;
	}

	public function getParsed() {
		return $this->doc_object->save();
	}
}