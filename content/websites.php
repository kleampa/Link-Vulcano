<?php
$title = 'Websites';
$javascript_code = "var active_menu = '/websites';";

$page = (intval($_GET['page']) == 0) ? 1 : intval($_GET['page']);
$query = $db->escape($_GET['query']);
$limit = 10;
$q= ($query != "") ? " AND url LIKE '%{$query}%'" : "";
$link_paginate = '/websites?';
$limitvalue = $page*$limit-($limit); 
$from = $limitvalue; $to = $limitvalue+$limit;
?>
<!-- Content area -->
<h3>Websites</h3>
<a href="#add_website" role="button" class="btn btn-success" data-toggle="modal"><i class="icon-plus icon-white"></i> New website</a>

<!-- Add new website modal -->
<div class="modal" id="add_website" tabindex="-1" role="dialog" aria-labelledby="add_websiteLabel" aria-hidden="true" style="display:none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="add_websiteLabel">Add new website</h3>
		</div>
		<div class="modal-body">
			<form class="form-search" onsubmit="return add_website();">
			  <input type="text" placeholder="Enter URL" style="width:435px;" name="url"/> <button type="submit" class="btn btn-primary">Submit</button>
			  <span class="help-block">eg: http://www.example.com</span>
			</form>
		</div>
		<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<br class="clearfix"/><br/>

<!-- List websites -->
<?php
$rows = $db->get_results("SELECT id,url,connected FROM websites WHERE 1=1$q ORDER BY id DESC LIMIT $limitvalue,$limit");
$results = $db->get_var("SELECT COUNT(id) FROM websites WHERE 1=1$q");
$to = ($to > $results) ? $results : $to;
$pages = ceil($results/$limit);

if($results == 0) {
	echo'
	<div class="alert alert-danger">
		No websites!
	</div>';
}
else {
	echo'
	<form class="form-search" action="" method="get">
		<input type="text" class="input-long" value="'.$query.'" name="query" placeholder="Search by URL"/>
		<button type="submit" class="btn">Search website</button>
	</form>
	
	<table class="table table-hover table-bordered">
	<tr>
		<th>URL</th>
		<th width="250">Status</th>
		<th width="100">Slots</th>
		<th width="100">Links</th>
		<th width="150">Options</th>
	</tr>';
	foreach($rows AS $website) {
		
		$connected = ($website->connected == 1) ? '<span class="label label-success">Connected</span>' : '<a href="/connector?id_website='.$website->id.'" class="btn btn-small btn-primary">Connect now!</a>';
		$slots = $db->get_var("SELECT COUNT(id) FROM slots WHERE id_website='$website->id'");
		$links = $db->get_var("SELECT COUNT(l.id) FROM link_slot l LEFT JOIN slots s ON s.id=l.id_slot LEFT JOIN websites w ON s.id_website=w.id WHERE s.id_website='$website->id'");
	
		$connected_modal = ($website->connected == 0) ? '<p class="alert alert-danger">It seems you did not install the connector! <a class="btn btn-mini btn-primary" href="/connector?id_website='.$website->id.'" target=
		_blank">Click here!</a></p>' : '<a href="javascript:;" class="btn btn-success" onclick="forceUpdate('.$website->id.');"><i class="icon-refresh icon-white"></i> Update links immediately</a>';
	
		echo'<tr>
				<td><a href="'.$website->url.'" target="_blank">'.$website->url.'</a> </td>
				<td>'.$connected.'</td>
				<td><a href="/slots?id_website='.$website->id.'" class="btn"><i class="icon-tag"></i> '.$slots.' slots</a></td>
				<td><a href="/links?id_website='.$website->id.'" class="btn"><i class="icon-bookmark"></i> '.$links.' links</a></td>
				<td>
					<div class="btn-group">
						<a class="btn" href="javascript:;"><i class="icon-wrench"></i> Settings</a>
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:;"><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="/links/add?id_website='.$website->id.'"><i class="icon-bookmark"></i> Add link</a></li>
							<li><a href="/slots/add?id_website='.$website->id.'"><i class="icon-tag"></i> Create slot</a></li>
							<li><a href="#force_'.$website->id.'" data-toggle="modal"><i class="icon-refresh"></i> Force update</a></li>
							<li><a href="/connector?id_website='.$website->id.'"><i class="icon-download-alt"></i> PHP connector</a></li>
							<li class="divider"></li>
							<li><a href="/websites/delete?id_website='.$website->id.'" onclick="return confirm(\'Are you sure?\');"><i class="icon-trash"></i> Remove website</a></li>
						</ul>
					</div>
					
					<div class="modal" id="force_'.$website->id.'" tabindex="-1" role="dialog" aria-labelledby="add_websiteLabel" aria-hidden="true" style="display:none;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="add_websiteLabel">Update links NOW</h3>
							</div>
							<div class="modal-body">
								<p>In ussualy way, links are refreshed at every 4 hours. You can force immediately this update by clicking the button below:</p>
								'.$connected_modal.'
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