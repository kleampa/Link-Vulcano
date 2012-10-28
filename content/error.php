<?php
$title = 'Error';
?>
<div class="alert alert-error">
	<p>
		<b>Ooops, page not found!</b>
	</p>
	<a href="javascript:history.back(-1);" class="btn btn-small"><i class="icon-circle-arrow-left"></i> Back to previous page</a>
</div>
<?php
$content = ob_get_clean();
ob_end_clean();
?>