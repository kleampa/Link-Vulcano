<?php
ob_start();

//include dependencies
include("includes/init.php");

//initialize mysql connection
$config = new General();
$db = $config->sql_connect();

//check if logged
if($_GET['act'] != "login" AND $_GET['act'] != "api" AND !$config->isLogged()) {
	header("location:/login");
}

//include the file (template) requested
if(isset($_GET['act'])) {
	$cale = 'content/'.$_GET['act'].'.php';
	if(file_exists($cale)) { include($cale); }
	else { include("content/error.php"); }
}
else { include("content/default.php"); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=$absolute_url?>"/>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<meta name="author" content="Dragoi Ciprian (www.cipy.ro) @ Hackathon Competition">

	<!--Favicon -->
	<link rel="icon" type="image/ico" href="static/img/favicon.ico" />
	
	<!-- Styles -->
	<link href="static/css/bootstrap.css" rel="stylesheet">
	<link href="static/css/style.css" rel="stylesheet">

	<!-- Javascript files -->
	<script type="text/javascript" src="static/js/jquery.js"></script>
	<script type="text/javascript" src="static/js/bootstrap.js"></script>
	<script type="text/javascript" src="static/js/library.js"></script>
	<script type="text/javascript">
		<?=$javascript_code?>
	</script>
</head>

<body>

<?php if($_GET['act'] != "login") { ?>
<!-- Header -->
<div class="navbar">
	<div class="navbar-inner">
		<div class="container" style="width: auto;">
			<a class="brand" href="/">LinkVulcano.com</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li><a href="/websites"><i class="icon-globe icon-white"></i> Websites</a></li>
					<li><a href="/slots"><i class="icon-tags icon-white"></i> Slots</a></li>
					<li><a href="/links"><i class="icon-bookmark icon-white"></i> Links</a></li>
				</ul>
				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> Settings <b class="caret"></b></a>
						<ul class="dropdown-menu">
						<li><a href="/account"><i class="icon-lock"></i> Login credentials</a></li>
						<li><a href="/logout"><i class="icon-off"></i> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<?php
//show template
echo $content;
?>

</body>
</html>
