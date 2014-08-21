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

	include '../vendor/autoload.php';

	$app = new \Slim\Slim(array(
			//'debug' => true,
			'view' => new TJC\View(),
			'templates.path' => '../app/templates',
			'whoops.editor' => 'sublime'
		)
	);

	$app->add(new \Slim\Middleware\SessionCookie);
	$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

	$app->view->setLayout('layout/layout.php');

	$app->get('/', function() use ($app) {
		$app->render('index.php', array());
	});

	$app->get('/about', function() use ($app) {
		$app->render('about.php', array());
	});

	$app->post('/html', function() use ($app) {
		if($_FILES['fileupload']['error'] != UPLOAD_ERR_OK) {
			switch($_FILES['fileupload']['error']) {
				case UPLOAD_ERR_INI_SIZE:
					$message = 'The Uploaded File is bigger than ' . ini_get('upload_max_filesize');
					break;

				case UPLOAD_ERR_FORM_SIZE:
					$message = 'The Uploaded File is bigger than the form specified.';
					break;

				case UPLOAD_ERR_PARTIAL:
					$message = 'The file was partially uploaded. Please Try Again.';
					break;

				case UPLOAD_ERR_NO_FILE:
					$message = 'No File was Uploaded.';
					break;

				case UPLOAD_ERR_NO_TMP_DIR:
					$message = 'Configuration Error. No Temporary Folder was found.';
					break;

				case UPLOAD_ERR_CANT_WRITE:
					$message = "Can't write file to disk.";
					break;

				case UPLOAD_ERR_EXTENSION:
					$message = 'A PHP Extension blocked the upload.';
					break;
			}
			$app->flash('file-error', $message);
			$app->redirect('/');
		} elseif($_FILES['fileupload']['type'] != 'text/html') {
			$app->flash('file-error', 'File is incorrect type. Given: ' . $_FILES['fileupload']['type']);
			$app->redirect('/');
		} 
		// This means that the application has determined that the submitting file is text/html
		// NOW, we need to store it ...

		$file_contents = file_get_contents($_FILES['fileupload']['tmp_name']);

		var_dump($file_contents);
	});

	$app->post('/url', function() use ($app) {
		$req = Requests::get($app->request->post('url'));
		var_dump($req->headers['content-type']);
	});

	$app->get('/parser/:id', function($id) use($app) {

	});

	$app->run();