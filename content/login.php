<?php
$title = 'Login';
?>
<div class="well">

	<p><b>LinkVulcano login</b></p>

	<?php
	if(isset($_POST['ok'])) {
		$user = $db->escape($_POST['user']);
		$password = $db->escape($_POST['password']);
		
		if($user == "" OR $password == "") {
			echo'<div class="alert alert-danger">Empty fields!</div>';
		}
		else {
			$login = $config->login($user,$password);
			if(!$login) {
				echo'<div class="alert alert-danger">Invalid credentials!</div>';
			}
			else {
				header("location:/");
			}
		}
	}
	?>
	
	<form class="form-inline" method="post" action="">
		<div class="control-group">
			<div class="controls">
				<input type="text" id="inputEmail" placeholder="Username" name="user">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
			<input type="password" id="inputPassword" placeholder="Password" name="password">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn" name="ok">Sign in</button>
			</div>
		</div>
	</form>
</div>
<?php
$content = ob_get_clean();
ob_end_clean();
?>