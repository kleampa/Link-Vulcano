<?php
$title = 'Slots';
$javascript_code = "var active_menu = '/slots';";

$page = (intval($_GET['page']) == 0) ? 1 : intval($_GET['page']);
$query = $db->escape($_GET['query']);
$id_website = $db->escape($_GET['id_website']);
$limit = 10;
$q = '';
$link_paginate = '/slots?';
if($query != "") {
	$q.=" AND url LIKE '%{$query}%'";
	$link_paginate.="query={$query}&";
}
if($id_website != "") {
	$q.=" AND id_website='$id_website'";
	$link_paginate.="id_website={$id_website}&";
}
$limitvalue = $page*$limit-($limit); 
$from = $limitvalue; $to = $limitvalue+$limit;
?>
<!-- Content area -->
<h3>Slots</h3>
<a href="slots/add" class="btn btn-success"><i class="icon-plus icon-white"></i> New slot</a>

<br class="clearfix"/><br/>

<!-- List slots -->
<form class="form-search" action="" method="get">
	<input type="text" class="input-long" value="<?=$query?>" name="query" placeholder="Search by name"/>
	<select name="id_website">
		<option value="">Search by website</option>
		<?php
		$websites = $db->get_results("SELECT id,url FROM websites ORDER BY url ASC");
		foreach($websites AS $website) {
			$sel = ($id_website == $website->id) ? ' selected' : '';
			echo'<option value="'.$website->id.'"'.$sel.'>'.$website->url.'</option>';
		}
		?>
	</select>
	<button type="submit" class="btn">Search slot</button>
</form>
<?php
$rows = $db->get_results("SELECT id,id_website,name,slug FROM slots WHERE 1=1$q ORDER BY id DESC LIMIT $limitvalue,$limit");
$results = $db->get_var("SELECT COUNT(id) FROM slots WHERE 1=1$q");
$to = ($to > $results) ? $results : $to;
$pages = ceil($results/$limit);

if($results == 0) {
	echo'
	<div class="alert alert-danger">
		No slots!
	</div>';
}
else {
	
	echo'
	<table class="table table-hover table-bordered">
	<tr>
		<th>Name</th>
		<th>Website</th>
		<th>Slug</th>
		<th width="100">Links</th>
		<th width="150">Options</th>
	</tr>';
	foreach($rows AS $slot) {
		
		$links = $db->get_var("SELECT COUNT(id) FROM link_slot WHERE id_slot='$slot->id'");
		$website = $db->get_row("SELECT url,token,connected FROM websites WHERE id='$slot->id_website'");
	
		$code = htmlentities('<?php
require_once("'.$website->token.'.php");
$op = new LinkVulcano();
echo $op->getLinks("'.$slot->slug.'","html");
?>');
		$connected = ($website->connected == 0) ? '<p class="alert alert-danger">It seems you did not install the connector! <a class="btn btn-mini btn-primary" href="/connector?id_website='.$slot->id_website.'" target=
		_blank">Click here!</a></p>' : '';
	
		echo'<tr>
				<td>'.$slot->name.'</td>
				<td>'.$website->url.'</td>
				<td><span class="label">'.$slot->slug.'</span></td>
				<td><a href="/links?id_slot='.$slot->id.'" class="btn"><i class="icon-bookmark"></i> '.$links.' links</a></td>
				<td>
					<div class="btn-group">
						<a class="btn" href="javascript:;"><i class="icon-wrench"></i> Settings</a>
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:;"><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="/links/add?id_slot='.$slot->id.'"><i class="icon-bookmark"></i> Add link</a></li>
							<li><a href="#code_'.$slot->id.'" data-toggle="modal"><i class="icon-barcode"></i> Get code</a></li>
							<li class="divider"></li>
							<li><a href="/slots/delete?id_slot='.$slot->id.'" onclick="return confirm(\'Are you sure?\');"><i class="icon-trash"></i> Remove slot</a></li>
						</ul>
					</div>
					
					<div class="modal" id="code_'.$slot->id.'" tabindex="-1" role="dialog" aria-labelledby="add_websiteLabel" aria-hidden="true" style="display:none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="add_websiteLabel">Get the code</h3>
							</div>
							<div class="modal-body">
								<p>Add this in website where do you want to display the slot.</p>
								<pre>'.$code.'</pre>
								'.$connected.'
							</div>
							<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
						</div>
					</div>
					
				</td>
			</tr>';
	}
	echo'</table>';
	
	// pagination
	if($pages > 1) {
		echo'<div class="pagination">
		<ul>';
		$pag = $config->paginate($page,$pages,$link_paginate,$limit);
		
		if($pag['prev'] != "") {
			echo'<li><a href="'.$pag['prev'].'">Prev</a></li>'; 
		}
		
		foreach($pag['paginate'] AS $p=>$link_p) {
			$active = ($p == $page) ? ' class="active"' : '';
			echo'<li'.$active.'><a href="'.$link_paginate.'page='.$p.'">'.$p.'</a></li>';
		}
		
		if($pag['next'] != "") { 
			echo'<li><a href="'.$pag['next'].'">Next</a></li>'; 
		}
		
		echo'</ul></div>';
	}
	else {}

}
?>
<?php
$content = ob_get_clean();
ob_end_clean();
?>