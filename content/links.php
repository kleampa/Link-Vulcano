<?php
$title = 'Links';
$javascript_code = "var active_menu = '/links';";

$page = (intval($_GET['page']) == 0) ? 1 : intval($_GET['page']);
$query = $db->escape($_GET['query']);
$id_slot = $db->escape($_GET['id_slot']);
$id_website = $db->escape($_GET['id_website']);
$limit = 10;
$q = '';
$link_paginate = '/links?';
if($query != "") {
	$q.=" AND l.url LIKE '%{$query}%'";
	$link_paginate.="query={$query}&";
}
if($id_website != "") {
	$q.=" AND w.id='$id_website'";
	$link_paginate.="id_website={$id_website}&";
}
if($id_slot != "") {
	$q.=" AND ls.id_slot='$id_slot'";
	$link_paginate.="id_slot={$id_slot}&";
}
$limitvalue = $page*$limit-($limit); 
$from = $limitvalue; $to = $limitvalue+$limit;
?>
<!-- Content area -->
<h3>Links</h3>
<a href="links/add" class="btn btn-success"><i class="icon-plus icon-white"></i> New link</a>

<br class="clearfix"/><br/>

<form class="form-search" action="" method="get" id="search_link">
	<input type="text" class="input-long" value="<?=$query?>" name="query" placeholder="Search by URL"/>
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
	
	<select name="id_slot" style="display:none;"></select>
	<button type="submit" class="btn">Search link</button>
</form>

<!-- List slots -->
<?php
$rows = $db->get_results("SELECT DISTINCT(l.id),l.* FROM links l LEFT JOIN link_slot ls ON ls.id_link=l.id LEFT JOIN slots s ON s.id=ls.id_slot LEFT JOIN websites w ON w.id=s.id_website WHERE 1=1$q ORDER BY l.id DESC LIMIT $limitvalue,$limit");
$results = $db->get_var("SELECT COUNT(DISTINCT(l.id)) FROM links l LEFT JOIN link_slot ls ON ls.id_link=l.id LEFT JOIN slots s ON s.id=ls.id_slot LEFT JOIN websites w ON w.id=s.id_website WHERE 1=1$q");
$to = ($to > $results) ? $results : $to;
$pages = ceil($results/$limit);

if($results == 0) {
	echo'
	<div class="alert alert-danger">
		No links!
	</div>';
}
else {
	echo'
	<table class="table table-hover table-bordered">
	<tr>
		<th>URL</th>
		<th>Anchor</th>
		<th width="60">Status</th>
		<th width="150">Visible in</th>
		<th width="150">Options</th>
	</tr>';
	foreach($rows AS $link) {
		
		$slots = $db->get_var("SELECT COUNT(id) FROM link_slot WHERE id_link='$link->id'");
		$status = ($link->active == 1) ? '<a href="javascript:;" class="btn btn-mini btn-danger" onclick="change_status('.$link->id.',$(this));"><i class="icon-pause icon-white"></i></a>' : '<a href="javascript:;" class="btn btn-mini btn-success" onclick="change_status('.$link->id.',$(this));"><i class="icon-play icon-white"></i></a>';
	
		echo'<tr>
				<td><a href="'.$link->url.'">'.$link->url.'</a></td>
				<td>'.$link->anchor.'</td>
				<td>'.$status.'</td>
				<td><a href="/links/edit?id_link='.$link->id.'&show=slots" class="btn"><i class="icon-tag"></i> '.$slots.' slots</a></td>
				<td>
					<div class="btn-group">
						<a class="btn" href="javascript:;"><i class="icon-wrench"></i> Settings</a>
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:;"><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="/links/edit?id_link='.$link->id.'"><i class="icon-bookmark"></i> Edit link</a></li>
							<li class="divider"></li>
							<li><a href="/links/delete?id_link='.$link->id.'" onclick="return confirm(\'Are you sure?\');"><i class="icon-trash"></i> Remove link</a></li>
						</ul>
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