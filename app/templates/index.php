<div class="container">
	<div class="row">
		<div class="col-md-12 well">
			<p>Welcome to the SD HTML Formatter. This application is used to help you format your source files for inclusion in to SoftDocs.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form class="form" method="post" action="/html" enctype="multipart/form-data">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Upload HTML Document</h3>
					</div>
					<div class="panel-body"><?php if(isset($flash['file-error'])): ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<strong>File Upload Error:</strong> <?=$flash['file-error']; ?>
						</div><?php endif; ?>
						<div class="fileinput fileinput-new input-group" data-provides="fileinput">
							<div class="form-control" data-trigger="fileinput">
								<i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
							</div>
							<span class="input-group-addon btn btn-default btn-file">
								<span class="fileinput-new">Select file</span>
								<span class="fileinput-exists">Change</span>
								<input type="file" name="fileupload">
							</span>
							<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
						</div>
					</div>
					<div class="panel-footer">
						<button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>
						<div class="clearfix"></div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form method="post" action="/url" class="form">
				<div class="panel panel-success">
					<div class="panel-heading">
						<h3 class="panel-title">Grab Form from URL</h3>
					</div>
					<div class="panel-body"><?php if(isset($flash['url-error'])): ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<strong>URL Error:</strong> <?=$flash['url-error']; ?>
						</div><?php endif; ?>
						<input class="form-control" type="url" name="url" placeholder="URL String Here ..." />
					</div>
					<div class="panel-footer">
						<button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>
						<div class="clearfix"></div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>