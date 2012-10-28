<?php
$title = 'Add new link';
$javascript_code = "var active_menu = '/links';";
?>
<!-- Content area -->
<h3>Add new link</h3>
<a href="/links" class="btn btn-small"><i class="icon-circle-arrow-left"></i> Back to links</a>

<br class="clearfix"/><br/>

<?php
if(isset($_POST['ok'])) {

	$op = new Websites();

	$url = $db->escape($_POST['url']);
	$anchor = $db->escape($_POST['anchor']);
	$target = $db->escape($_POST['target']);
	$nofollow = $db->escape($_POST['nofollow']);
	
	if($url == "" OR $anchor == "") {
		echo'<div class="alert alert-danger">URL and Anchor are required!</div>';
	}
	elseif($op->ifLinkExists($url)) {
		echo'<div class="alert alert-danger">This link already exists!</div>';
	}
	else {
		$add = $op->addLink($url,$anchor,$target,$nofollow);
		echo'<div class="alert alert-success"><p>Link added!</p><a href="links/edit?id_link='.$add.'&show=slots" class="btn btn-success btn-mini"><i class="icon-tag icon-white"></i> Now you must add this link in slots!</a></div>';
		
		$url = '';
		$anchor = '';
		$target = '';
		$nofollow = '';
	}
	
}
else {
	$url = "";
	$anchor = "";
	$target = "";
	$nofollow = "";
}
?>

<!-- Add link form -->
<form method="post" action="">
	<table class="table table-bordered">
		<tr>
			<td width="100">URL:</td>
			<td><input type="text" class="input-xlarge" placeholder="eg: http://www.example.com" value="<?=$url?>" name="url"/></td>
		</tr>
		<tr>
			<td>Anchor:</td>
			<td><input type="text" class="input-xlarge" placeholder="Website name" value="<?=$anchor?>" name="anchor"/></td>
		</tr>
		<tr>
			<td valign="top">Options</td>
			<td>
				<label class="checkbox">
				  <input type="checkbox" value="_blank" name="target">
				  open in new window
				</label>
				<label class="checkbox">
				  <input type="checkbox" value="1" name="nofollow">
				  nofollow tag
				</label>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td><button type="submit" class="btn btn-primary" name="ok">Add new link</button></td>
		</tr>
	</table>
</form>
	
<?php
$content = ob_get_clean();
ob_end_clean();
?>