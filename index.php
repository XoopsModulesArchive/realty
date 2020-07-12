<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>

<?php

	include("include/common.php");
	include("$config[template_path]/user_top.html");
?>
	<table border="<?php echo $style[admin_listing_border] ?>" cellspacing="<?php echo $style[admin_listing_cellspacing] ?>" cellpadding="<?php echo $style[admin_listing_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
<tr>
			<td valign="top">
				<?php renderFeaturedListingsVertical(4); ?>
				<?php renderFeaturedautosVertical(4); ?>

			</td>
			<td>
				<h3>Real Estate & Auto Listing Engine w/ Virtual Tours <?php echo $config[version]; ?></h3>				<center>				<?php browse_all_listings() ?> | <a href="listingsearch.php">Search Listings</a>				</center>		<p>Real Estate Listing Engine w/ Virtual Tours. Intended to be both easy to install and easy to administer, Real Estate Listing Engine w/ Virtual Tours uses PHP to drive a mySQL backend, thus creating a tool which is fast and flexible. Thanks to <a href="http://www.jonroig.com" target="_blank">Jon Roig</a> & <a href="http://www.open-realty.org" target="_blank">Ryan Bonham</a> for making a great program & making it free!</p>		<h3>Features:</h3>		<ul>					<li>Allows visitors to look through your real estate listings 24 hours a day, 7 days a week, 365.25 days a year.					<li>Easily keep your property listings updated -- no HTML coding required to add, delete, or modify listings.					<li>Built-in image manager -- upload photos via your web browser, either when creating new listings or modifying an existing one. If photos are not uploaded for a property, a &quot;photo not available&quot; image will be automatically displayed for the listing.					<li>Automatically thumbnails -- smaller versions of your images are automatically created through the GD library.					<li>Automagically interfaces with Yahoo Maps -- potential customers will always know exactly where to find your property.					<li>Flexible database backend -- compatible with mysql through the ADODB abstraction layer.					<li>Secure -- no one but you can change your listings.					<li>Viral Marketing -- visitors can email listings to their friends right from Real Estate Listing Engine w/ Virtual Tours.					<li>Easy to installation -- just edit a single configuration file and install through E-xoops.<li>Showcase specific properties -- built-in featured listing manager allows you to place special offerings right on your front page.					<li>Flexible search -- browse properties according to whatever criteria you like.					<li>Easy setup -- configurator tool makes Real Estate Listing Engine w/ Virtual Tours 3.0 easy to install.					<li>Configurable forms -- Don't like the form choices included in Real Estate Listing Engine w/ Virtual Tours? Change them to meet your needs!					<li>Template system -- produce a sophisticated site without any knowledge of PHP					<li>Supports CSS stylesheets.					<li>Virtual Tours -- Show many rooms of the house like they are there!
					<li>Appointment Schdeuler - Send an Agent an E-mail when someone want to see their Listing</ul>				<p>Of course, Real Estate Listing Engine w/ Virtual Tours is written entirely in PHP, which makes it easy to customize and revise to match the look of your website.</p>				<p><img src="images/equalhousing.gif"><br>
						</td>
		</tr>
	</table>
<?
include ("footer.php"); 
?>

<?
CloseTable();
include("../../footer.php");
?>
