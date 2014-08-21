<?php
	// Simple Database Creation Tool

	include '../vendor/autoload.php';

	$database = 'sqlite:../data/database.db';

	$pdo = new PDO($database) or die('Could not create Database.');

	$query = 'CREATE TABLE files;';

	$pdo->query($query);