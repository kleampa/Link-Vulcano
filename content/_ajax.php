<?php
switch($_GET['op']) {

	//add new website
	case'add_website':
	$url = $db->escape($_POST['url']);
	$op = new Websites();
	$add = $op->addWebsite($url);
	if($add) {
		echo'<div class="alert alert-success"><p>Website added!</p><a href="slots/add?id_website='.$add.'" class="btn btn-success btn-mini"><i class="icon-tag icon-white"></i> Is time to create a slot</a></div>';
	}
	else {
		echo'<div class="alert alert-danger">Website already exists!</div>';
	}
	break;
	
	//delete website
	case'delete_website':
	$id = intval($_GET['id_website']);
	$op = new Websites();
	$op->deleteWebsite($id);
	header("location:/websites");
	break;
	
	//delete slot
	case'delete_slot':
	$id = intval($_GET['id_slot']);
	$op = new Websites();
	$op->deleteSlot($id);
	header("location:/slots");
	break;
	
	//find slots for dropdown
	case'slots':
	$id_website = intval($_POST['id_website']);
	$rows = $db->get_results("SELECT id,name FROM slots WHERE id_website='$id_website' ORDER BY name ASC");
	if($db->num_rows == 0) {
		echo'<option value="">No slots</option>';
	}
	else {
		echo'<option value="">Select a slot</option>';
		foreach($rows AS $row) {
			echo'<option value="'.$row->id.'">'.$row->name.'</option>';
		}
	}
	break;
	
	//delete link
	case'delete_link':
	$id = intval($_GET['id_link']);
	$op = new Websites();
	$op->deleteLink($id);
	header("location:/links");
	break;
	
	//change status
	case'change_status':
	$id = intval($_POST['id_link']);
	$active = intval($_POST['active']);
	$op = new Websites();
	$op->changeLinkStatus($id,$active);
	break;
	
	//remove link from slot
	case'remove_link_slot':
	$id = intval($_GET['id']);
	$link = $db->get_row("SELECT id_link FROM link_slot WHERE id='$id'");
	$op = new Websites();
	$op->removeLinkSlot($id);
	header("location:/links/edit?id_link={$link->id_link}&show=slots");
	break;
	
	//generate .php connectr for a website
	case'download_connector':
	$id = intval($_GET['id']);
	$website = $db->get_row("SELECT * FROM websites WHERE id='$id'");
	$original = file_get_contents($path.'/includes/wrapper.txt');
	$file = str_replace('#website_name#',$website->url,$original);
	$file = str_replace('#token#',$website->token,$file);
	$file = str_replace('#api_url#',$absolute_url.'api',$file);
	$file = str_replace('#cache_file#','lv_cache.txt',$file);
	header('Content-disposition: attachment; filename='.$website->token.'.php');
	header('Content-type: text/plain');
	echo $file;
	break;
	
	//check if php code was installed
	case'check_code':
	$id = intval($_POST['id_website']);
	$op = new Websites();
	echo $op->checkCode($id);
	break;
	
	//force update links on website
	case'force_update':
	$id = intval($_POST['id_website']);
	$op = new Websites();
	echo $op->forceUpdate($id);
	break;
	
	//end session and redirect to index
	case'logout':
	unset($_SESSION['logged']);
	header("location:/");
	break;
	
}

die();
?>