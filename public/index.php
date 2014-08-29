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

	ORM::configure('sqlite:../data/database.db');

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
			$app->flash('file-error', upload_error($_FILES['fileupload']['error']));
			$app->redirect('/');
		} elseif($_FILES['fileupload']['type'] != 'text/html') {
			$app->flash('file-error', 'File is incorrect type. Given: ' . $_FILES['fileupload']['type']);
			$app->redirect('/');
		} 
		// This means that the application has determined that the submitting file is text/html

		// Check to see if the information has been submitted before ... THIS IS WHY WE MD5 THINGS!!!!
		$md5 = md5_file($_FILES['fileupload']['tmp_name']);
		if(source_exists($md5)) {
			$source = ORM::for_table('source')->where('md5', $md5)->find_one();
			$app->flash('message', 'Duplicate File Submitted. Using Previously Submitted File.');
		} else {
			// NOW, we need to store it ...
			$source = ORM::for_table('source')->create();
			$source->set(source_array($_FILES['fileupload']['name'], file_get_contents($_FILES['fileupload']['tmp_name'])));
			$source->set_expr('submitted', "DateTime('now')");
			$source->save();
		}

		$app->redirect($app->urlFor('parser', array('id' => $source->id())));
	});

	$app->post('/url', function() use ($app) {
		$req = Requests::get($app->request->post('url'));
		if($req->headers['content-type'] != 'text/html') {
			$app->flash('url-error', 'URL Must direct to an HTML Page. Given: ' . $req->headers['content-type']);
			$app->redirect('/');
		}

		// md5 the result and then either submit or redirect to parser.
		$md5 = md5($req->body);
		if(source_exists($md5)) {
			$source = ORM::for_table('source')->where('md5', $md5)->find_one();
			$app->flash('message', 'Duplicate URL Submitted. Using Previously Submitted URL.');
		} else {
			$source = ORM::for_table('source')->create();
			$source->set(source_array($req->url, $req->body));
			$source->set_expr('submitted', "DateTime('now')");
			$source->save();
		}

		$app->redirect($app->urlFor('parser', array('id' => $source->id())));
	});

	$app->get('/parser/:id', function($id) use($app) {

	})->name('parser');

	$app->get('/results/:id', function($id) use($app) {

	})->name('results');

	$app->run();