<?php
$title = 'Welcome to LinkVulcano';
?>
<div class="well">
	<p><b>LinkVulcano</b> is a web application to help webmasters with one or much more websites, to manage "blocks links" on their websites.</p>
	<pre><i>Block link = a part from website where ussualy are links to partners,friends or sites of the same owner.</i></pre>
	<br/>
	<p>In old fashioned way, if you want to add a new link on all your websites, you should enter in every website code and wrote a line like this:</p>
	<pre><?=htmlentities('<a href="http://www.website.com">My partner</a>')?></pre>
	<br/>
	<p>Well, with <b>LinkVulcano</b> you can do all these jobs in just 30 seconds.</p>
	<br/>
	<p>
		<b>How?</b>
		<ol>
			<li>Register your websites in <b>LinkVulcano</b></li>
			<li>Configure unlimited "blocks links" (in application are named <i>slots</i>)</li>
			<li>Add the links you want to put on your websites. (you can choose to add a "nofollow" tag or to open in new window)</li>
			<li>Asociate links with slots</li>
			<li><b>That's it!</b> Now everytime you add a new link or delete one in <b>LinkVulcano</b>,  will be modified on your websites without your intervention.</li>
		</ol>
	</p>
	<br/>
	<p>
		<b>How it works?</b></br>
		You must include in your website a code like this:
	</p>
	<pre><?php echo htmlentities('<?php
require_once("4534fdgt3io534p.php");
$LV = new LinkVulcano();
echo $LV->getLinks("slot-SLUG","html"); //slot-SLUG is unique for every slot
?>'); ?></pre>
	<p>
		which generate in your website source code , a code like this:
	</p>
	<pre><?=htmlentities('
<ul>
	<li><a href="http://www.partner1.com" title="Partner1" target="_blank" rel="nofollow">Partner 1</li>
	<li><a href="http://www.partner2.com" title="Partner2" target="_self">Partner 2</li>
	<li><a href="http://www.partner3.com" title="Partner3" target="_self" rel="nofollow">Partner 3</li>
</ul>')?>
	</pre>
	<p>or (for advanced PHP coders) a PHP object to display links how do you want:</p>
	
	<pre>
Array
(
	[0] => stdClass Object
		(
			[url] => http://www.partner1.com
			[anchor] => Partner1
			[target] => _self
			[nofollow] => 1
		)
)
	</pre>
	<a class="btn btn-large btn-success" href="/websites"><i class="icon-plus icon-white"></i> Start now</a>
</div>
<?php
$content = ob_get_clean();
ob_end_clean();
?>