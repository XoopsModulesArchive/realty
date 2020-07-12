<?phpinclude("../../mainfile.php");include("../../header.php");OpenTable();	
?>

<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* 		Open-Realty/Auto Modification © RealtyOne outback web creations		 */
/*			Page Based on Open-Realty 1.2.0 Unreleased © RealtyOne			 */
/* 	 Overall content based on Open-Realty © Ryan Bonham transparent tech	 */
/*	This mod and all attached files remain under the Open-Realty gpl Licence */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	include("include/common.php");
	include("$config[template_path]/user_top.html");
	global $conn, $config, $type;
	
	if ($type == "auto")
	{
		$sql_imageID = make_db_safe($imageID);
		// get the image data
		$sql = "SELECT caption, file_name, description, auto_id FROM " . $config['table_prefix'] . "autoimages WHERE (ID = $sql_imageID)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
				
		while (!$recordSet->EOF)
		{
			$caption = make_db_unsafe ($recordSet->fields['caption']);
			$file_name = make_db_unsafe ($recordSet->fields['file_name']);
			$description = make_db_unsafe ($recordSet->fields['description']);
			$auto_id = make_db_unsafe ($recordSet->fields['auto_id']);
			$recordSet->MoveNext();
		}
		echo '
		<table border="'.$style['form_border'].'" cellspacing="'.$style['form_cellspacing'].'" cellpadding="'.$style['form_cellpadding'].'" width="'.$style['admin_table_width'].'" class="form_main" align="center">
		<tr>
			<td class="row_main">
				<h3>';
				if ($caption != ""){echo $caption.' -';}
				echo '<a href="'.$config['baseurl'].'/autoview.php?autoID='.$auto_id.'">'.$lang['return_to_listing'].'</a></h3>
				<center>
				<img src="'.$config['auto_view_images_path'].'/'.$file_name.'" alt="'.$caption.'" border="1">
				</center>
				<br>
				'.$description.'
			</td>
		</tr>
 		</table>';
		
	} // end if ($type == "auto")
	elseif ($type == "userimage")
	{
		$sql_imageID = make_db_safe($imageID);
		// get the image data
		$sql = "SELECT caption, file_name, description, user_id FROM " . $config['table_prefix'] . "userImages WHERE (ID = $sql_imageID)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
				
		while (!$recordSet->EOF)
		{
			$caption = make_db_unsafe ($recordSet->fields['caption']);
			$file_name = make_db_unsafe ($recordSet->fields['file_name']);
			$description = make_db_unsafe ($recordSet->fields['description']);
			$user_id = $recordSet->fields['user_id'];
			$recordSet->MoveNext();
		}
		echo '
		<table border="'.$style['form_border'].'" cellspacing="'.$style['form_cellspacing'].'" cellpadding="'.$style['form_cellpadding'].'" width="'.$style['admin_table_width'].'" class="form_main" align="center">
		<tr>
			<td class="row_main">
				<h3>';
				if ($caption != ""){echo $caption.' -';}
				echo '<a href="'.$config['baseurl'].'/userview.php?user='.$user_id.'">'.$lang['return_to_listing'].'</a></h3>
				<center>
				<img src="'.$config['user_view_images_path'].'/'.$file_name.'" alt="'.$caption.'" border="1">
				</center>
				<br>
				'.$description.'
			</td>
		</tr>
 		</table>';
		
	} // end if ($type == "auto")
	include("$config[template_path]/user_bottom.html");
?>
<?CloseTable();include("../../footer.php");?>