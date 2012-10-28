<?php
class General extends ezSQL_mysql {

	private $db;
	private $path;
	
	public function __construct() {
		$this->path = $path;
	}
	
	//connect to mysql
	public function sql_connect() {
		global $mysql_user;
		global $mysql_password;
		global $mysql_db;
		global $mysql_host;
        $this->db =  new ezSQL_mysql($mysql_user, $mysql_password, $mysql_db, $mysql_host);
		return $this->db;
    } 
    
	//generate slub
	public function slug($string) {
		$string = addslashes(trim(strip_tags($string)));
		$old_pattern = array("/[^a-zA-Z0-9]/", "/-+/", "/-$/");
		$new_pattern = array("-", "-", "");
		$final = strtolower(preg_replace($old_pattern, $new_pattern , $string));
		$final = substr($final,0,50);
		return $final;
	}
	
	//pages for pagination
	public function paginate($page,$pages,$link,$limit) {
		$arr = array();
		$paginate = array();		
		$prev  = max($pagie-1,1);
		$next = min($page+1,$pages);
		
		if($page == $prev) { $prev = ''; } else { $prev = ''.$link.'page='.$prev.'';}
		if($page == $next) { $next = ''; } else {$next = ''.$link.'page='.$next.'';}
				
		
		if($page - 5 > 0) { $min = $page - 5; } else { $min = 1; }
		if($page +5  > $pages) { $max = $pages; } else { $max = $page+5; }
		
		for($i=$min;$i<=$max;$i++) {
			$paginate[$i]=''.$link.'page='.$i.'';
		}
		
		$arr['prev'] = $prev;
		$arr['paginate'] = $paginate;
		$arr['next'] = $next;
		
		return $arr;
	}
	
	//check if is logged
	public function isLogged() {
		if(isset($_SESSION['logged']) AND ($_SESSION['logged'] != "")) {
			return true;
		}
		else {
			return false;
		}
	}
	
	//login
	public function login($user,$password) {
		$password = md5($password);
		$row = $this->db->get_row("SELECT id FROM admin WHERE user='$user' AND password='$password'");
		if($this->db->num_rows == 0) {
			return false;
		}
		else {
			$_SESSION['logged'] = 1;
			return true;
		}
	}
	
	//change password
	public function changePassword($old,$new) {
		$old = md5($old);
		$new = md5($new);
		$row = $this->db->get_row("SELECT password FROM admin");
		if($old != $row->password) {
			return false;
		}
		else {
			$this->db->query("UPDATE admin SET password='$new'");
			return true;
		}
	}
	
}
?>
