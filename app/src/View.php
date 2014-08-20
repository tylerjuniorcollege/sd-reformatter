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

namespace TJC;

use \Slim\View as SlimView;

class View extends SlimView {
	protected $layout = null;
	protected $layoutData = array();

	public function setLayout($template) {
		$layout = $this->getTemplatePathname($template);
		if(!is_file($layout)) {
			throw new \RuntimeException("Layout file `$template` does not exist.");
		} else {
			$this->layout = $template;
		}
	}

	public function getLayoutData() {
		return $this->layoutData;
	}

	public function setLayoutData($data, $value = null) {
		if(is_array($data)) {
			$this->layoutData = array_merge($this->layoutData, $data);
		} elseif(!is_null($value)) {
			$this->layoutData[$data] = $value;
		}
	}

	public function render($template, $data = null) {
		if(!is_null($this->layout)) { // Render the layout!!
			$this->setLayoutData('content', parent::render($template, $data));

			return parent::render($this->layout, $this->layoutData);
		} else {
			return parent::render($template, $data);
		}
	}
}