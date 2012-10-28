<?php
$title = 'Connector';
$javascript_code = "var active_menu = '/websites';";

$id = intval($_GET['id_website']);
$website = $db->get_row("SELECT * FROM websites WHERE id='$id'");
if($db->num_rows == 0) { header("location:/websites"); }
?>
<h3>Connector for <i><?=$website->url?></i></h3>
<p>This connector is actually a php file which must be downloaded and uploaded on your website, so as to be accessible to the next adress:</p>
<pre><?=$website->url?>/<?=$website->token?>.php</pre>
<br/>
<a href="/download_connector?id=<?=$id?>" class="btn btn-large btn-success" onclick="$(this).remove();"><i class="icon-download-alt icon-white"></i> Click here to download the connector</a>
<a href="javascript:;" class="btn btn-large btn-inverse" onclick="checkCode(<?=$website->id?>);"><i class="icon-download-alt icon-white"></i> Click here to test installation</a>

<br class="clearfix"/><br/>
<div id="check_results" style="display:none;"><img src="static/img/loading.gif" alt=""/></div>

<div class="well">
<b>Attention!</b><br/>
This is just first step of installation. After you create slots, you must insert the unique code for slots in area where do you want to be displayed on your website.<br/>
You can get these codes from "Slots" page.
</div>

<?php
$content = ob_get_clean();
ob_end_clean();
?>