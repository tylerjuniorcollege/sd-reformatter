<div class="container">
	<form class="form" method="POST" role="form" class="form-horizontal">
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Javascript Code</h3>
				</div>
				<div class="panel-body" style="padding-top:0px; padding-bottom: 0px;">
					<div class="row">
						<div id="jseditor" class="col-md-12" style="height:250px;"></div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="pull-right">
						<input type="text" name="jsfilename" class="form-control" placeholder="filename.js" />
					</div>
					<label class="checkbox pull-right" style="margin-right:15px;">
						<input type="checkbox" name="jsfile" value="1" /> Insert as a File
					</label>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">CSS Code</h3>
				</div>
				<div class="panel-body" style="padding-top:0px; padding-bottom: 0px;">
					<div class="row">
						<div id="csseditor" class="col-md-12" style="height:250px;"></div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="pull-right">
						<input type="text" name="cssfilename" class="form-control" placeholder="filename.css" />
					</div>
					<label class="checkbox pull-right" style="margin-right:15px;">
						<input type="checkbox" name="cssfile" value="1" /> Insert as a File
					</label>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-1 col-md-offset-11">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</form>
</div>
<?php
	$app = \Slim\Slim::getInstance();

$editorCode = <<<JS
	var jsEditor = ace.edit("jseditor");
		jsEditor.setTheme("ace/theme/dreamweaver");
		jsEditor.getSession().setMode("ace/mode/javascript");

	var cssEditor = ace.edit("csseditor");
		cssEditor.setTheme("ace/theme/dreamweaver");
		cssEditor.getSession().setMode("ace/mode/css");
JS;

	$app->view->appendJavascriptFile('//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js')
			  ->appendJavascript($editorCode);
/*
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
<script type="text/javascript">
	var jsEditor = ace.edit("jseditor");
		jsEditor.setTheme("ace/theme/dreamweaver");
		jsEditor.getSession().setMode("ace/mode/javascript");

	var cssEditor = ace.edit("csseditor");
		cssEditor.setTheme("ace/theme/dreamweaver");
		cssEditor.getSession().setMode("ace/mode/css");
</script>
*/ ?>