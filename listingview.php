<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>

<?php
	
	include("include/common.php");
	include("$config[template_path]/user_top.html");
		
	if ($listingID == "")
	{
		echo "<a href=\"index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
	}	
		
		
	elseif ($listingID != "")
	{
		// first, check to see whether the listing is currently active
		$show_listing = checkActive($listingID);
		if ($show_listing == "yes")
		{
			?>
				<SCRIPT Language="JAVASCRIPT"> function imgchange(name){if(document.images){document.main.src = "images/listing_photos/" + name;  } else { document.main.src = "images/nophoto.gif"; }}</SCRIPT>
				<!-- This Script opens a new window it is used by the mortgage calc. -->
				<script type="text/javascript">
				<!--
				function open_window(url)
				{
					cwin = window.open(url,"attach","width=350,height=400,toolbar=no,resizable=yes");
				}
				-->
				</script>
		<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main" align="center" >
			<tr>
				<td colspan="2" class="row_main">
					<?php getMainListingData($listingID); ?>
				
					<h4>
					<?php renderTemplateAreaNoCaption(headline,$listingID); ?>
					
					</h4>
				</td>
			</tr>
			<tr>
				
					<?php
					renderListingsImagesJava($listingID)
					?>


				<td class="row_main">
					
					<table width="<?php echo $style[left_right_table_width] ?>" cellpadding="<?php echo $style[left_right_table_cellpadding] ?>" cellspacing="<?php echo $style[left_right_table_cellspacing] ?>" border="<?php echo $style[left_right_table_border] ?>">
						<tr>
							<td align="left" class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(top_left,$listingID); ?>
								
								
							</td>
							<td align="right" class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(top_right,$listingID); ?>
								
								
							</td>

						</tr>
					</table>
					<br>

					<table width="98%">
						<tr>

						<td align="center" width="100%" colspan="2"><?php renderListingsMainImageJava($listingID) ?></td>

						</tr>
<td class="row_main">
					<?php
					rendervtourlink($listingID)
					?><br><br>
<?php renderTemplateArea(center,$listingID); ?>
</td>
						<tr>
							<td align="left" class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(top_left,$listingID); ?>


					</table>
<br><br><hr>
					
					
					
					<table width="<?php echo $style[left_right_table_width] ?>" cellpadding="<?php echo $style[left_right_table_cellpadding] ?>" cellspacing="<?php echo $style[left_right_table_cellspacing] ?>" border="<?php echo $style[left_right_table_border] ?>">
						<tr>
							<td align="left" class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(feature1,$listingID); ?>
							</td>
							<td align="right" class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(feature2,$listingID); ?>
							</td>
						</tr>
					</table>
					
				<br><a href="appointment.php?listingID=<?php echo $listingID ?>"><b>Schedule an Appointment</b></a>				<br><a href="javascript:open_window('calc.php?price=<? renderSingleListingItemRaw($listingID, "price") ?>')"><b>Mortgage Calculator</b></a>
				<br><a href="members/addtofavorites.php?listingID=<? echo $listingID; ?>"><b>Add to favorites</b></a>
				<br><a href="email_listing.php?listingID=<?php echo $listingID ?>"><b>Email This Listing to a Friend</b></a>
				<br><?php makeYahooMap($listingID, "address", "city", "postcode") ?>
				<table width="<?php echo $style[left_right_table_width] ?>" cellpadding="<?php echo $style[left_right_table_cellpadding] ?>" cellspacing="<?php echo $style[left_right_table_cellspacing] ?>" border="<?php echo $style[left_right_table_border] ?>">
						<tr>
							<td align="left" class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(bottom_left,$listingID); ?>
							</td>
							<td align="right" class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(bottom_right,$listingID); ?>
							</td>
						</tr>
					</table>
				<br><br>
				<hr size="1" width="75%">
				<?php renderUserInfoOnListingsPage($listingID) ?>
				<br>
				<?php getListingEmail($listingID) ?>
				<br><br>
				<hr size="1" width="75%">
				<?php hitcount($listingID) ?>
				
				</td>
			</tr>
	 	</table><?
include ("footer.php"); 
?>
		<?php
		} // end if ($show_listing == "yes")
	} // end elseif ($listingID != "")
	
?>
<?
CloseTable();
include("../../footer.php");
?>
