<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Tyler Junior College">

		<title>SoftDocs Formatter</title>

		<?= implode($data['css']['rendered'], "\n\t\t"); ?>

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
			  		<ul class="nav navbar-nav pull-right">
			  			<?= navbar_tools($data) ?>
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
	<?= implode($data['js']['rendered'], "\n\t"); ?>
	</body>
</html>
