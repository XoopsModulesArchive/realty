<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>
<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* 		Open-Realty/Auto Modification © RealtyOne outback web creations		 */
/*			Page Based on Open-Realty 1.2.0 Unreleased © RealtyOne			 */
/* 	 Overall content based on Open-Realty © Ryan Bonham transparent tech	 */
/*	This mod and all attached files remain under the Open-Realty gpl Licence */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	include("include/common.php");
	global $config, $lang, $conn;
	include("$config[template_path]/user_top.html");
	if ($autoID == "")
	{
		echo "<a href=\"index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
	}	
		
		
	elseif ($autoID != "")
	{
		// first, check to see whether the auto is currently active
		$show_auto = checkautoActive($autoID);
		if ($show_auto == "yes")
		{
			?>
<SCRIPT Language="JAVASCRIPT"> function imgchange(name){if(document.images){document.main.src = "images/auto_photos/" + name;  } else { document.main.src = "images/nophoto.gif"; }}</SCRIPT>

				<!-- This Script opens a new window it is used by the vehicle calc. -->
				<script type="text/javascript">
				<!--
				function open_window(url)
				{
					cwin = window.open(url,"attach","width=350,height=300,toolbar=no,resizable=yes");
				}
				-->
				</script>
		<table border="<?php echo $style['form_border'] ?>" cellspacing="<?php echo $style['form_cellspacing'] ?>" cellpadding="<?php echo $style['form_cellpadding'] ?>" width="<?php echo $style['admin_table_width'] ?>" class="form_main" align="center" >
			<tr>
				<td colspan="2" class="row_main">
					<?php getMainautoData($autoID); ?>
				
					<h4>
					<?php renderautoTemplateAreaNoCaption('headline',$autoID); ?>
					
					</h4>
				</td>
			</tr>
			<tr>
				
					<?php
					renderautoImagesJava($autoID)
					?>

				<td class="row_main">
				<table width="<?php echo $style['left_right_table_width'] ?>" cellpadding="<?php echo $style['left_right_table_cellpadding'] ?>" cellspacing="<?php echo $style['left_right_table_cellspacing'] ?>" border="<?php echo $style['left_right_table_border'] ?>">
						<tr>
						<td align="center" width="100%" colspan="2"><?php renderautoMainImageJava($autoID) ?><BR><?php renderautovtourlink($autoID) ?><BR></td>
						</tr>
						<tr>
							<td align="left" class="row_main" width="50%" valign="top">
								<?php renderautoTemplateArea(top_left,$autoID); ?>
								
								
							</td>
							<td align="right" class="row_main" width="50%" valign="top">
								<?php renderautoTemplateArea('top_right',$autoID); ?>
								
								
							</td>
						</tr>
					</table>
					<br>
					<table width="98%">
						<tr>
							<td valign="top">
							<?php renderautoTemplateArea('center',$autoID); ?>
							<br><br>
							</td>
						</tr>
					</table>
					
					
					
					<table width="<?php echo $style['left_right_table_width'] ?>" cellpadding="<?php echo $style['left_right_table_cellpadding'] ?>" cellspacing="<?php echo $style['left_right_table_cellspacing'] ?>" border="<?php echo $style['left_right_table_border'] ?>">
						<tr>
							<td align="left" class="row_main" width="50%" valign="top">
								<?php renderautoTemplateArea('feature1',$autoID); ?>
							</td>
							<td align="right" class="row_main" width="50%" valign="top">
								<?php renderautoTemplateArea('feature2',$autoID); ?>
							</td>
						</tr>
					</table>
					
				<br><br><a href="javascript:open_window('autocalc.php?price=<? renderSingleautoItemRaw($autoID, "price") ?>')"><b>Loan Calculator</b></a>
				<br><a href="members/userautofavorites.php?autoID=<? echo $autoID; ?>"><b>Add to favorites</b></a>
				<br><a href="auto_appointment.php?type=auto&amp;autoID=<? echo $autoID; ?>"><b>Arrange to View</b></a>
				<br><a href="autoview.php?autoID=<?php echo $autoID ?>&amp;printer_friendly=yes"><b>Printer Friendly Version of This Page</b></a>
				<br><a href="email_listing.php?type=auto&amp;autoID=<?php echo $autoID ?>"><b>Email This Auto to a Friend</b></a>
				
				<table width="<?php echo $style['left_right_table_width'] ?>" cellpadding="<?php echo $style['left_right_table_cellpadding'] ?>" cellspacing="<?php echo $style['left_right_table_cellspacing'] ?>" border="<?php echo $style['left_right_table_border'] ?>">
						<tr>
							<td align="left" class="row_main" width="50%" valign="top">
								<?php renderautoTemplateArea('bottom_left',$autoID); ?>
							</td>
							<td align="right" class="row_main" width="50%" valign="top">
								<?php renderautoTemplateArea('bottom_right',$autoID); ?>
							</td>
						</tr>
					</table>
				<br><br>
				<hr size="1" width="75%">
				<?php renderUserInfoOnautoPage($autoID) ?>
				<br>
				<?php getautoEmail($autoID) ?>
				<br><br>
				<hr size="1" width="75%">
				<?php autohitcount($autoID) ?>
				
				</td>
			</tr>
	 	</table>
		
		<?php
		} // end if ($show_auto == "yes")
	} // end elseif ($autoID != "")
	
?><?CloseTable();include("../../footer.php");?>