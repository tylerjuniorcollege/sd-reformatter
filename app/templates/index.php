<div class="container">
	<div class="row">
		<div class="col-md-12">
			<!-- This is the new home for the alerts in the system -->
			<?php alert_view($flash); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 page-header">
			<h3>Welcome to the SD HTML Formatter.</h3>
		</div>
	</div>
	<form class="form" method="post" action="/submit" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Upload HTML Document</h3>
					</div>
					<div class="panel-body">
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
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-success">
					<div class="panel-heading">
						<h3 class="panel-title">Grab Form from URL</h3>
					</div>
					<div class="panel-body">
						<input class="form-control" type="url" name="url" placeholder="URL String Here ..." />
					</div>
				</div>
			</div>
		</div>
		<button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>
		<div class="clearfix"></div>
	</form>
</div>