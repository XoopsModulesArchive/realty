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
				<h3>Real Estate & Auto Listing Engine w/ Virtual Tours <?php echo $config[version]; ?></h3>
					<li>Appointment Schdeuler - Send an Agent an E-mail when someone want to see their Listing</ul>
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