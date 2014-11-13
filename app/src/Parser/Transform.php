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

// This is the main parser for the Transform.xsl file. Right now, it only removes opening and closing <!CDATA[]> tags.
class Transform extends ParserAbstract
{

}