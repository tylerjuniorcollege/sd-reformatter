<?php
use TJC\Parser\HTML as HTMLParser;
?>
<div class="container">
	<?php if(isset($flash['message'])): ?>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-warning alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<strong>Processor Message:</strong> <?=$flash['message']; ?> &nbsp;<a href="/remove/<?= $data['source']->id; ?>" class="btn btn-default">Remove Old Version and Start Over</a>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<div class="row">
		<div class="col-md-12 page-header">
			<h3>Parser Results</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped table-condensed">
				<colgroup>
					<col class="col-md-1">
					<col class="col-md-11">
				</colgroup>
				<thead>
					<tr>
						<th><span class="glyphicon glyphicon-ok"></span></th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($data['results'] as $key => $val): ?>
					<?= display_stats($key, $val); ?>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 page-header">
			<h3>Download Parsed HTML</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-condensed table-striped">
				<colgroup>
					<col class="col-md-2">
					<col class="col-md-10">
				</colgroup>
				<thead>
					<tr>
						<th>Download Link</th>
						<th>Filename</th>
					</tr>
				</thead>
				<tbody>
					<?= display_download($data['source']->id); ?>
					<?php foreach($data['sources'] as $id => $source): ?>
						<?= display_download($source->source_id); ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>