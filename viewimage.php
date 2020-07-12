<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>


<?php


	include("include/common.php");
	include("$config[template_path]/user_top.html");
	
	global $conn, $config, $type;
	
	if ($type == "listing")
	{
		$sql_imageID = make_db_safe($imageID);
		// get the image data
		$sql = "SELECT caption, file_name, description, listing_id FROM " . $config[table_prefix] . "listingsImages WHERE (ID = $sql_imageID)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
				
		while (!$recordSet->EOF)
		{
			$caption = make_db_unsafe ($recordSet->fields[caption]);
			$file_name = make_db_unsafe ($recordSet->fields[file_name]);
			$description = make_db_unsafe ($recordSet->fields[description]);
			$listing_id = make_db_unsafe ($recordSet->fields[listing_id]);
			$recordSet->MoveNext();
		}
		?>
		<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main" align="center">
		<tr>
			<td class="row_main">
				<h3><?if ($caption != ""){echo "$caption - ";} ?><a href="listingview.php?listingID=<? echo $listing_id ?>"><? echo $lang[return_to_listing] ?></a></h3>
				<center>
				<img src="<?php echo "$config[listings_view_images_path]/$file_name" ?>" alt="<?php echo "$caption"?>" border="1">
				</center>
				<br>
				<?php echo $description ?>
			</td>
		</tr>
 		</table>
		<?php
	} // end if ($type == "listing")
	elseif ($type == "userimage")
	{
		$sql_imageID = make_db_safe($imageID);
		// get the image data
		$sql = "SELECT caption, file_name, description, user_id FROM " . $config[table_prefix] . "userImages WHERE (ID = $sql_imageID)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
				
		while (!$recordSet->EOF)
		{
			$caption = make_db_unsafe ($recordSet->fields[caption]);
			$file_name = make_db_unsafe ($recordSet->fields[file_name]);
			$description = make_db_unsafe ($recordSet->fields[description]);
			$user_id = $recordSet->fields[user_id];
			$recordSet->MoveNext();
		}
		?>
		<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main" align="center">
		<tr>
			<td class="row_main">
				<h3><?if ($caption != ""){echo "$caption - ";} ?><a href="userview.php?user=<? echo $user_id ?>"><? echo $lang[return_to_listing] ?></a></h3>
				<center>
				<img src="<?php echo "$config[user_view_images_path]/$file_name" ?>" alt="<?php echo "$caption"?>" border="1">
				</center>
				<br>
				<?php echo $description ?>
			</td>
		</tr>
 		</table>

<?
include ("footer.php"); 
?>
		<?php
	} // end if ($type == "listing")
	
?>

<?
CloseTable();
include("../../footer.php");
?>
