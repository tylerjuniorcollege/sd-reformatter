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
			'view' => new \TJC\ViewLayout(),
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

	$app->post('/submit', function() use ($app) {
		if($_FILES['fileupload']['error'] === UPLOAD_ERR_NO_FILE && !empty($app->request->post('url'))) {
			// URL instead of file ...
			$req = Requests::get($app->request->post('url'));

			$content_type = $req->headers['content-type'];
			$error_type = 'url-error';

			// We want to change the output file name ...

		} elseif($_FILES['fileupload']['error'] === UPLOAD_ERR_OK) {
			// File uploaded ...
			$content_type = $_FILES['fileupload']['type'];
			$error_type = 'file-error';

		}

		if($_FILES['fileupload']['error'] != UPLOAD_ERR_OK && empty($app->request->post('url'))) {
			$app->flash('file-error', upload_error($_FILES['fileupload']['error']));
			$app->redirect('/');
		}

		if($content_type != 'text/html') {
			$app->flash($error_type, 'File is incorrect type. Given: ' . $content_type);
			$app->redirect('/');
		} 
		// This means that the application has determined that the submitting file is text/html

		$processor = new TJC\Processor\Html(file_get_contents($_FILES['fileupload']['tmp_name']), $_FILES['fileupload']['name']);
		$source = $processor->process();

		$app->redirect($app->urlFor('parser', array('id' => $source->id()))); 
	});

	$app->post('/url', function() use ($app) {

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

		// Send to the parser and send the results to the renderer.
		$parser = new TJC\Parser\HTML($source->content);

		// This is a read only parse. It gathers the stats of the parse, and then  
		$results = $parser->parse(null);

		$sources = ORM::for_table('source_link')->where('html_id', $source->id)->find_many();

		// Adding in the Tools Dropdown.
		$app->view->setLayoutData('tools', array('edit' => $source->id, 
												 'divider1' => 'divider',
												 'display' => $source->id,
												 'displayparsed' => $source->id,
												 'divider2' => 'divider',
												 'transform' => $source->id,
												 'divider3' => 'divider',
												 'downloadzip' => $source->id
												));

		$app->render('parser.php', array('results' => $results, 'source' => $source, 'sources' => $sources));
	})->name('parser');

	/* $app->post('/results/:id', function($id) use($app) {

	})->name('results'); */

	$app->map('/transform/:id', function($id) use($app) {
		// Check to see if it's a POST
		if($app->request->isPost()) {
			// Parse and Send as a Download.
			$processor = new TJC\Processor\Tranform();
			$source = $processor->process();

			$app->redirect($app->urlFor('download', array('id' => $source->id())));
			return;
		}
		$html_source = ORM::for_table('source')->find_one($id);
		$source_type = ORM::for_table('source_type')->find_one($html_source->source_type);
		$type = $source_type->type;
		$filename = parsed_filename($html_source->filename, $type);

		$app->render('transform.php', array('filename' => $filename));
	})->via('GET', 'POST')->name('transform');

	$app->get('/display/:id', function($id) use($app) {
		$source = ORM::for_table('source')->find_one($id);

		echo $source->content;
		die;
	})->name('display');

	$app->get('/display/parsed/:id', function($id) use($app) {
		// Grab Current Source
		$source = ORM::for_table('source')->find_one($id);

		// Send to the parser and send the results to the renderer.
		$parser = new TJC\Parser\HTML($source->content);

		// This is a read only parse. It gathers the stats of the parse, and then  
		$parser->parse(null);

		echo $parser->getParsed();
		die;
	})->name('displayparsed');

	$app->get('/download/:id', function($id) use($app) {
		// This will download based on the parsed version of the html file.
		$source = ORM::for_table('source')->find_one($id);

		// Now, we check to see if it's an HTML file or an Asset
		if($source->source_type == 1) { // Lets do the parsing and then send the file to the browser to download.
			$parser = new TJC\Parser\HTML($source->content);
			$parser->parse(null);
			$content = $parser->getParsed();
		} else {
			// This is other content to be downloaded.
			$content = $source->content;
		}

		$source_type = ORM::for_table('source_type')->find_one($source->source_type);
		$type = $source_type->type;
		$filename = parsed_filename($source->filename, $type);
		
		header("Content-type: $type");
		header("Content-Disposition:attachment;filename=\"$filename\"");

		echo $content;
		exit;
	})->name('download');

	$app->get('/download/zip/:id', function($id) use($app) {

	})->name('downloadzip');

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