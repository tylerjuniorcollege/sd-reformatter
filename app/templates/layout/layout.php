<?php
  // Add CSS and Javascript files on the fly ...
	$css = array(
		array(
			'file' => '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'
		),
		array(
			'inline' => "body { padding-top: 60px; }"
		),
		array(
			'file' => '//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css'
		),
		/* array(
			'file' => '//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/lumen/bootstrap.min.css'
		) */
	);

	$js = array(
		array(
			'file' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'
		),
		array(
			'file' => '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'
		),
		array(
			'file' => '//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js'
		)
	);
  
	if(isset($data['css']) && is_array($data['css'])) {
		$data['css'] = array_merge($css, $data['css']);
  	} elseif (!isset($data['css'])) {
		$data['css'] = $css;
  	}

  	if(isset($data['js']) && is_array($data['js'])) {
		$data['js'] = array_merge($js, $data['js']);
  	} elseif (!isset($data['js'])) {
		$data['js'] = $js;
  	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Tyler Junior College">

		<title>SoftDocs Formatter</title>

<?php
	foreach($data['css'] as $arr) {
		switch(current(array_keys($arr))) {
	  		case 'file':
				echo "\t\t<link href=\"{$arr['file']}\" rel=\"stylesheet\">\n";
				break;

	  		case 'inline':
				echo "\t\t<style type-\"text/css\">{$arr['inline']}</style>\n";
				break;
		}
  	}
?>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
	  		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>

		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
			  		<a class="navbar-brand" href="/">SDFormatter</a>
				</div>
				<div class="collapse navbar-collapse">
			  		<ul class="nav navbar-nav">
			  		</ul>
				</div><!--/.nav-collapse -->
		  	</div>
		</div>
<?= $data['content']; ?>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<p>&copy; 2014 Tyler Junior College</p>
				</div>
		  	</div>
		</div>
<?php
	foreach($data['js'] as $arr) {
		echo "\t\t<script type=\"text/javascript\"";
		switch(current(array_keys($arr))) {
	  		case 'file':
				echo "src=\"{$arr['file']}\">";
				break;

	  		case 'inline':
				echo ">{$arr['inline']}";
				break;
		}
		echo "</script>\n";
  	}
?>
	</body>
</html>
