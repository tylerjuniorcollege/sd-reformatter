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
	
</div>