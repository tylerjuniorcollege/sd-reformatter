<div class="controller">
	<div class="row">
		<div class="col-md-12 page-header">
			<h3>Upload transform.xsl for <?= $data['filename']; ?></h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<form class="form" method="POST" action="" enctype="multipart/form-data">
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
				<button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>