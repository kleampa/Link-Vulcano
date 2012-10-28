<?php
session_start();
error_reporting(0);

$path = $_SERVER['DOCUMENT_ROOT'];
$absolute_url = 'http://'.$_SERVER['HTTP_HOST'].'/';
$title = '';

//start edit here
$mysql_user = '';
$mysql_password = '';
$mysql_db = '';
$mysql_host = '';
//stop edit here

function __autoload($class_name) {
	global $path;
    include("{$path}/includes/".strtolower($class_name).".Class.php");
}
?>