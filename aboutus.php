
<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>

<?php


	include("include/common.php");
include("$config[template_path]/user_top.html");
	
	
?>

	<table border="<? echo $style[admin_listing_border] ?>" cellspacing="<? echo $style[admin_listing_cellspacing] ?>" cellpadding="<? echo $style[admin_listing_cellpadding] ?>" width="<? echo $style[admin_table_width] ?>" class="form_main">
		<tr>
			<td valign="top">
				<? renderFeaturedListingsVertical(4); ?>
			</td>
			<td>
			<h3>Hello and Welcome!</h3>
			<p>Real Estate Listing Engine 2.1 w/ Virtual Tours is a free, open source real estate listing manager. Intended to be both easy to install and easy to administer, Real Estate Listing Engine 2.1 w/ Virtual Tours uses PHP to drive a mySQL backend, thus creating a tool which is fast and flexible.</p>
				<p>While the basic version of Real Estate Listing Engine 2.1 w/ Virtual Tours can be downloaded for free, I'm always available to customize it for your specific needs. Feel free to <a href="mailto:ryan@Real Estate Listing Engine 2.1 w/ Virtual Tours.org">contact me</a>.</p>



			</td>
		</tr>
	</table><br><br><br>
<//----------Please do not remove-----------//><center>Original Software - Open-Reality By: <a href='http://www.jonroig.com' target='_blank'>Jon Roig</a> & <a href='http://www.Real Estate Listing Engine 2.1 w/ Virtual Tours.org' target='_blank'>Ryan Bonham (Open-Reality.org)</a><br>Scripting for E-xoops <a href='http://www.liquidgfx.com' target='_blank'>Liquid GFX Inc</a></center>

<?



	
?>
<?
CloseTable();
include("../../footer.php");
?>