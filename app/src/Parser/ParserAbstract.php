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

abstract class ParserAbstract {
	protected $document;
	protected $altered_document;

	public function __construct($document) {
		$this->document = $document;

		return $this;
	}

	public function getAlteredDoc() {
		return $this->altered_document;
	}

	public function getDocument() {
		return $this->document;
	}

	abstract function parse();
	abstract function getParsed();
}