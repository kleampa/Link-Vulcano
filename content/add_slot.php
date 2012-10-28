<?php
$title = 'Add slot';
$javascript_code = "var active_menu = '/slots';";
?>
<!-- Content area -->
<h3>Create a new slot</h3>
<a href="/slots" class="btn btn-small"><i class="icon-circle-arrow-left"></i> Back to slots</a>

<br class="clearfix"/><br/>

<?php
if(isset($_POST['ok'])) {
	$name = $db->escape($_POST['name']);
	$id_website = $db->escape($_POST['id_website']);
	
	if($name == "" OR $id_website == "") {
		echo'<div class="alert alert-danger">All fields are required!</div>';
	}
	else {
	
		$op = new Websites();
		$add = $op->addSlot($id_website,$name);
		
		echo'<div class="alert alert-success"><p>Slot created!</p><a href="links/add?id_slot='.$add.'" class="btn btn-success btn-mini"><i class="icon-tag icon-white"></i> Is time to add a link</a></div>';
		
		$name = '';
		$id_website = '';
	}
	
}
else {
	$name = "";
	$id_website = intval($_GET['id_website']);
}
?>

<!-- Add slot form -->
<form method="post" action="">
	<table class="table table-bordered">
		<tr>
			<td width="100">Name:</td>
			<td><input type="text" class="input-xlarge" placeholder="eg: Footer" value="<?=$name?>" name="name"/></td>
		</tr>
		<tr>
			<td>Website:</td>
			<td>
				<select name="id_website">
					<option value="">Select a website</option>
					<?php
					$websites = $db->get_results("SELECT id,url FROM websites ORDER BY url ASC");
					foreach($websites AS $website) {
						$sel = ($id_website == $website->id) ? ' selected' : '';
						echo'<option value="'.$website->id.'"'.$sel.'>'.$website->url.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><button type="submit" class="btn btn-primary" name="ok">Create slot</button></td>
		</tr>
	</table>
</form>
	
<?php
$content = ob_get_clean();
ob_end_clean();
?>