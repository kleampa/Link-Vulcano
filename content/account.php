<?php
$title = 'Account';
?>

<h3>Change password</h3>

<?php
if(isset($_POST['ok'])) {
	$old_password = $db->escape($_POST['old_password']);
	$new_password = $db->escape($_POST['new_password']);
	
	if($old_password == "" OR $new_password == "") {
		echo'<div class="alert alert-danger">All fields are required!</div>';
	}
	else {
	
		if(!$config->changePassword($old_password,$new_password)) {
			echo'<div class="alert alert-danger">Old password incorrect!</div>';
		}
		else {
			echo'<div class="alert alert-success">Password changed!</div>';
		}
		
	}
	
}
?>

<!-- Change password form -->
<form class="form-inline" method="post" action="">
		<div class="control-group">
			<div class="controls">
				<input type="password" placeholder="Old password" name="old_password">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="password" placeholder="New password" name="new_password">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary" name="ok">Change password</button>
			</div>
		</div>
	</form>
<?php
$content = ob_get_clean();
ob_end_clean();
?>