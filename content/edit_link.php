<?php
$id = intval($_GET['id_link']);
$title = 'Edit link';
$javascript_code = "var active_menu = '/links';";

$link = $db->get_row("SELECT * FROM links WHERE id='$id'");
if($db->num_rows == 0) { header("location:/links"); }
?>
<!-- Content area -->
<h3>Edit link</h3>
<a href="/links" class="btn btn-small"><i class="icon-circle-arrow-left"></i> Back to links</a>

<br class="clearfix"/><br/>

<?php
//save edit
if(isset($_POST['ok'])) {
	$url = $db->escape($_POST['url']);
	$anchor = $db->escape($_POST['anchor']);
	$target = $db->escape($_POST['target']);
	$nofollow = $db->escape($_POST['nofollow']);
	
	if($url == "" OR $anchor == "") {
		echo'<div class="alert alert-danger">URL and Anchor are required!</div>';
	}
	else {
		$op = new Websites();
		$op->editLink($id,$url,$anchor,$target,$nofollow);
		echo'<div class="alert alert-success">Link saved!</div>';
		$link = $db->get_row("SELECT * FROM links WHERE id='$id'");
	}
}

//add link in slot
if(isset($_POST['addinslot'])) {
	$id_slot = intval($_POST['id_slot']);
	
	if($id_slot == "") {
		echo'<div class="alert alert-danger">You must select a slot!</div>';
	}
	else {
		$op = new Websites();
		$op->addLinkSlot($id,$id_slot);
		echo'<div class="alert alert-success">Link added! Is not visible immediately because of cache function!</div>';
	}
}

echo'
<script type="text/javascript">
	$(document).ready(function() {';
	if($_GET['show'] == "slots") { echo'$("a[href=#where]").trigger("click");'; }
	if($link->target == "_blank") { echo"$('input[name=target]').attr('checked',true);"; }
	if($link->nofollow == "1") { echo"$('input[name=nofollow]').attr('checked',true);"; }
	echo"$('input[name=url]').val('{$link->url}');";
	echo"$('input[name=anchor]').val('{$link->anchor}');";
echo'});
</script>';
?>

<!-- Edit link form -->
<ul class="nav nav-tabs">
  <li class="active"><a href="#link" data-toggle="tab">Link information</a></li>
  <li><a href="#where" data-toggle="tab">Where is visible</a></li>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="link">
		<form method="post" action="">
			<table class="table table-bordered">
				<tr>
					<td width="100">URL:</td>
					<td><input type="text" class="input-xlarge" placeholder="eg: http://www.example.com" name="url"/></td>
				</tr>
				<tr>
					<td>Anchor:</td>
					<td><input type="text" class="input-xlarge" placeholder="Website name" name="anchor"/></td>
				</tr>
				<tr>
					<td valign="top">Options</td>
					<td>
						<label class="checkbox">
						  <input type="checkbox" value="_blank" name="target">
						  open in new window
						</label>
						<label class="checkbox">
						  <input type="checkbox" value="1" name="nofollow">
						  nofollow tag
						</label>
					</td>
				</tr>
				
				<tr>
					<td></td>
					<td><button type="submit" class="btn btn-primary" name="ok">Save</button></td>
				</tr>
			</table>
		</form>
	</div>
	<div class="tab-pane" id="where">
		
		<div class="well well-small">
			<p>Select website/slot where do you want to be visible <i><?=$link->url?></i></p>
			<form class="form-search" action="" method="post" id="search_link" style="margin-bottom:0px;">
				<select name="id_website">
					<option value="">Select a website</option>
					<?php
					$websites = $db->get_results("SELECT id,url FROM websites ORDER BY url ASC");
					foreach($websites AS $website) {
						$sel = ($id_website == $website->id) ? ' selected' : '';
						echo'<option value="'.$website->id.'"'.$sel.'>'.$website->url.'</option>';
					}
					?>
				</select>
				
				<select name="id_slot" style="display:none;"></select>
				<button type="submit" class="btn" name="addinslot">Add in slot</button>
			</form>
		</div>
		
		<p><b><?=$link->url?> is visible on:</b></p>
		<?php
		$rows = $db->get_results("SELECT id,id_slot FROM link_slot WHERE id_link='$id'");
		if($db->num_rows == 0) {
			echo'<div class="alert alert-danger">Not visible in any slots!</div>';
		}
		else {
			echo'
			<table class="table table-hover table-bordered">
				<tr>
					<th>Website</th>
					<th>Slot</th>
					<th width="120">Remove</th>
				</tr>';
			foreach($rows AS $row) {
				$slot = $db->get_row("SELECT name,id_website FROM slots WHERE id='$row->id_slot'");
				$website = $db->get_row("SELECT url FROM websites WHERE id='$slot->id_website'");
				
				echo'
				<tr>
					<td><a href="'.$website->url.'" target="_blank">'.$website->url.'</a></td>
					<td>'.$slot->name.'</td>
					<td><a href="links/remove_link_slot?id='.$row->id.'" class="btn btn-danger btn-small" onclick="return confirm(\'Are you sure?\');"><i class="icon-trash icon-white"></i> Remove</a></td>
				</tr>';
			}
			echo'</table>';
		}
		?>
	</div>
</div>

<?php
$content = ob_get_clean();
ob_end_clean();
?>