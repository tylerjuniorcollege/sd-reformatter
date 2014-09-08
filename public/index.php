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

		$processor = new TJC\Processor\Html(file_get_contents($_FILES['fileupload']['tmp_name']), $_FILES['fileupload']['name']);
		$source = $processor->process();

		$app->redirect($app->urlFor('parser', array('id' => $source->id())));
	});

	$app->post('/url', function() use ($app) {
		$req = Requests::get($app->request->post('url'));
		if($req->headers['content-type'] != 'text/html') {
			$app->flash('url-error', 'URL Must direct to an HTML Page. Given: ' . $req->headers['content-type']);
			$app->redirect('/');
		}

		$processor = new TJC\Processor\Html($req->body, $req->url);
		$source = $processor->process();

		$app->redirect($app->urlFor('parser', array('id' => $source->id())));
	});

	$app->get('/review/:id', function($id) use($app) {

	})->name('review');

	// The parser checks the HTML Based on the requirements for the form.
	$app->get('/parser/:id', function($id) use($app) {
		// Grab Current Source
		$source = ORM::for_table('source')->find_one($id);

		$app->render('parser.php', array('source' => $source));
	})->name('parser');

	$app->get('/results/:id', function($id) use($app) {

	})->name('results');

	$app->get('/remove/:id', function($id) use($app) {
		$source = ORM::for_table('source')->find_one($id);
		$fmr_source = $source->id();
		$fmr_filename = $source->filename;
		$source->delete();
		// Remove all of the sub source files

		ORM::for_table('source_link')->where_equal('source_id', $fmr_source)->delete_many();

		// Redirect to front page.
		$app->flash('delete-action', $fmr_filename . ' has been deleted from the database.');
		$app->redirect('/');
	})->name('remove');

	$app->run();