<?php
$token = $db->escape($_GET['token']);
$op = new Websites();
$id_website = $op->checkToken($token);
if($id_website) {

	switch($_GET['op']) {
		case'getLinks':
		$return = array();
		$slots = $db->get_results("SELECT id,slug FROM slots WHERE id_website='$id_website'");
		if($db->num_rows == 0) {}
		else {
			foreach($slots AS $slot) {
				$return[$slot->slug] = array();
				
				$links = $db->get_results("SELECT l.* FROM link_slot ls LEFT JOIN links l ON l.id=ls.id_link WHERE ls.id_slot='$slot->id' AND l.active=1");
				if($db->num_rows == 0) {}
				else {
					foreach($links AS $link) {
						$return[$slot->slug][] = 
							array(
								'url'=>$link->url,
								'anchor'=>$link->anchor,
								'target'=>($link->target == "") ? '_self' : $link->target,
								'nofollow'=>($link->nofollow == "") ? '0' : 1
							);
					}
				}
			}
			
			echo json_encode($return);
		}
		break;
	}
	
}
else {
	//wrong token
}
die();
?>