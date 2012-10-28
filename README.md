Link-Vulcano
============
Manage links blocks from several websites in one place.

How to install
============
Upload dump.sql
Edit includes/init.php with your mysql credentials

Default login credentials (can be changed after login)
============
User: admin
Password: admin

# About
LinkVulcano is a web application to help webmasters with one or much more websites, to manage "blocks links" on their websites.

    Block link = a part from website where ussualy are links to partners,friends or sites of the same owner.

In old fashioned way, if you want to add a new link on all your websites, you should enter in every website code and wrote a line like this:

    <a href="http://www.website.com">My partner</a>

Well, with LinkVulcano you can do all these jobs in just 30 seconds.

**How?**

 1. Register your websites in
    LinkVulcano
 2. Configure unlimited "blocks links"
    (in application are named slots)
 3. Add the links you want to put on
    your websites. (you can choose to
    add a "nofollow" tag or to open in
    new window)
 4. Asociate links with slots

That's it! Now everytime you add a new link or delete one in LinkVulcano, will be modified on your websites without your intervention.

**How it works?**

You must include in your website a code like this:

    <?php
    require_once("4534fdgt3io534p.php");
    $LV = new LinkVulcano();
    echo $LV->getLinks("slot-SLUG","html"); //slot-SLUG is unique for every slot
    ?>

which generate in your website source code , a code like this:

    <ul>
    	<li><a href="http://www.partner1.com" title="Partner1" target="_blank" rel="nofollow">Partner 1</li>
    	<li><a href="http://www.partner2.com" title="Partner2" target="_self">Partner 2</li>
    	<li><a href="http://www.partner3.com" title="Partner3" target="_self" rel="nofollow">Partner 3</li>
    </ul>	

or (for advanced PHP coders) a PHP object to display links how do you want:

 1. Array ( 	[0] => stdClass Object 		(
    			[url] => http://www.partner1.com
    			[anchor] => Partner1 			[target]
    => _self 			[nofollow] => 1 		) )

	