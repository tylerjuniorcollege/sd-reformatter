<?php // Parser Options.
	$options = array();
	foreach(\TJC\Parser\HTML::options() as $option => $disp) {
		$options[] = sprintf('<div class="checkbox"><label class="checkbox"><input type="checkbox" name="outputoptions[%s]" value="1" checked="checked">%s</label></div>', $option, $disp);
	}
?>
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
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="settingsHeading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-target="#settingsCollapse" href="#settingsCollapse" aria-expanded="true" aria-controls="settingsCollapse">Settings</a>
							<a data-toggle="collapse" data-target="#settingsCollapse" href="#settingsCollapse" aria-expanded="true" aria-controls="settingsCollapse" class="pull-right"><span class="glyphicon glyphicon-chevron-down"></span></a>
						</h4>
					</div>
					<div id="settingsCollapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="settingsHeading">
						<div class="panel-body">
							<div class="form-horizontal row">
								<div class="form-group col-md-6">
									<label for="outfilename" class="col-md-4 label-control">Output Filename</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="outfilename" placeholder="output.html" />
									</div>
								</div>
								<div class="form-group col-md-6 pull-right">
									<label class="checkbox-inline"><input type="checkbox" name="changeassetdir" value="1" data-toggle="collapse" data-target=".assetdircollapse" aria-expanded="false" aria-controls="assetdircollapse" /> Change Asset Directories</label>
									<label class="checkbox-inline"><input type="checkbox" name="compressassets" value="1" /> Compress Assets</label>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="form-horizontal row">
								<div class="form-group col-md-6 assetdircollapse collapse">
									<label for="scriptsdir" class="col-md-4 label-control">Scripts Directory</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="scriptsdir" value="scripts/" />
									</div>
								</div>
								<div class="form-group col-md-6 assetdircollapse collapse">
									<label for="styledir" class="col-md-4 label-control">Styles Directory</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="styledir" value="styles/" />
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-target="#outputSettings" href="#outputSettings" aria-expanded="false" aria-controls="outputSettings">Output Options</a>
										<a data-toggle="collapse" data-target="#outputSettings" href="#outputSettings" aria-expanded="false" aria-controls="outputSettings" class="pull-right"><span class="glyphicon glyphicon-chevron-down"></span></a>
									</h4>
								</div>
								<div id="outputSettings" class="panel-collapse collapse">
									<div class="panel-body">
										<?= implode($options); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-1 col-md-offset-11">
				
			</div>
		</div>
	</form>
</div>