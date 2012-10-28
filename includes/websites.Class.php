<?php
class Websites extends General {

	private $db;
	private $path;
	
	public function __construct() {
		global $path;
		$this->path = $path;
		$this->db = parent::sql_connect();
	}
	
	
	//generate website token
	private function websiteToken($url) {
		return md5($url);
	}
	
	//check if a token is valid
	public function checkToken($token) {
		$row = $this->db->get_row("SELECT id FROM websites WHERE token='$token'");
		if($this->db->num_rows == 0) {
			return false;
		}
		else {
			return $row->id;
		}
	}
	
	//add website
	public function addWebsite($url) {
		$row = $this->db->get_row("SELECT id FROM websites WHERE url='$url'");
		if($this->db->num_rows == 0) {
			$token = $this->websiteToken($url);
			$this->db->query("INSERT INTO websites SET url='$url',token='$token',date_created=NOW(),connected='0'");
			return $this->db->insert_id;
		}
		else {
			return false;
		}
	}
	
	//delete website
	public function deleteWebsite($id) {
		$this->db->query("DELETE FROM websites WHERE id='$id'");
		$slots = $this->db->get_results("SELECT id FROM slots WHERE id_website='$id'");
		if($this->db->num_rows > 0) {
			foreach($slots AS $slot) {
				$this->deleteSlot($slot->id);
			}	
		}
	}
	
	//add slot
	public function addSlot($id_website,$name) {
		$slug = parent::slug($name);
		$slug = $this->uniqueSlotSlug($slug);
		$this->db->query("INSERT INTO slots SET id_website='$id_website',name='$name',slug='$slug',date_created=NOW()");
		return $this->db->insert_id;
	}
	
	//check if slot slug is unique
	private function uniqueSlotSlug($slug,$count=1) {
		$row = $this->db->get_var("SELECT id FROM slots WHERE slug='$slug'");
		if($row == 0) {
			return $slug;
		}
		else {
			$extra = $count++;
			$slug.='-'.$extra;
			return $this->uniqueSlotSlug($slug,$extra);
		}
	}
	
	//delete slot
	public function deleteSlot($id) {
		$this->db->query("DELETE FROM slots WHERE id='$id'");
		$this->db->query("DELETE FROM link_slot WHERE id_slot='$id'");
	}
	
	//check if link exists
	public function ifLinkExists($url) {
		$row = $this->db->get_var("SELECT COUNT(id) FROM links WHERE url='$url'");
		if($row == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	//add link
	public function addLink($url,$anchor,$target,$nofollow) {
		$this->db->query("INSERT INTO links SET url='$url',anchor='$anchor',target='$target',nofollow='$nofollow',active=1,date_created=NOW()");
		return $this->db->insert_id;
	}
	
	//edit link
	public function editLink($id,$url,$anchor,$target,$nofollow) {
		$this->db->query("UPDATE links SET url='$url',anchor='$anchor',target='$target',nofollow='$nofollow' WHERE id='$id'");
	}
	
	//delete link
	public function deleteLink($id) {
		$this->db->query("DELETE FROM links WHERE id='$id'");
		$this->db->query("DELETE FROM link_slot WHERE id_link='$id'");
	}	
	
	//change status
	public function changeLinkStatus($id,$active) {
		$this->db->query("UPDATE links SET active='$active' WHERE id='$id'");
	}
	
	//add link in slot
	public function addLinkSlot($id_link,$id_slot) {
		$this->db->query("INSERT INTO link_slot SET id_link='$id_link',id_slot='$id_slot'");
	}
	
	//remove link from slot
	public function removeLinkSlot($id) {
		$this->db->query("DELETE FROM link_slot WHERE id='$id'");
	}
	
	//check if cod was installed
	public function checkCode($id_website) {
		$website = $this->db->get_row("SELECT url,token FROM websites WHERE id='$id_website'");
		if($this->db->num_rows == 0) {
			$return='<div class="alert alert-danger">Intern problem. Try later!</div>';
		}
		else {
			$url = $website->url.'/'.$website->token.'.php?op=check&token='.$website->token;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10); //how much seconds can wait response from server
			$output = curl_exec($ch);
			$info = curl_getinfo($ch);
			$error = htmlentities(curl_error($ch));
			curl_close($ch);
			
			if(!$output) {
				$return='<div class="alert alert-danger">Connection error: '.$error.'</div>';
				$this->db->query("UPDATE websites SET connected=0 WHERE id='$id_website'");
			}
			elseif($info['http_code'] != "200") {
				$return='<div class="alert alert-danger">File is not uploaded!</div>';
				$this->db->query("UPDATE websites SET connected=0 WHERE id='$id_website'");
			}
			elseif($output == "true") {
				$return='<div class="alert alert-success">You installed the code correctly! Now you can use LinkVulcano at maximum capacity!</div>';
				$this->db->query("UPDATE websites SET connected=1 WHERE id='$id_website'");
			}
			else {
				$return='<div class="alert alert-danger">'.$output.'</div>';
				$this->db->query("UPDATE websites SET connected=0 WHERE id='$id_website'");
			}
		}
		
		return $return;
	}
	
	//force update
	public function forceUpdate($id_website) {
		$website = $this->db->get_row("SELECT url,token FROM websites WHERE id='$id_website'");
		if($this->db->num_rows == 0) {
			$return='<div class="alert alert-danger">Intern problem. Try later!</div>';
		}
		else {
			$url = $website->url.'/'.$website->token.'.php?op=update&token='.$website->token;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10); //how much seconds can wait response from server
			$output = curl_exec($ch);
			$info = curl_getinfo($ch);
			$error = htmlentities(curl_error($ch));
			curl_close($ch);
			
			if($info['http_code'] != "200") {
				$return='<p class="alert alert-danger">It seems you did not install the connector! <a class="btn btn-mini btn-primary" href="/connector?id_website='.$id_website.'">Click here!</a></p>';
				$this->db->query("UPDATE websites SET connected=0 WHERE id='$id_website'");
			}
			else {
				$return='<div class="alert alert-success">All links/slots was updated!</div>';
			}
		}
		
		return $return;
	}
}
?>
