<?php
include("admin_header.php");
xoops_cp_header();
?>


<?php

include("../include/common.php");

loginCheck('User');

global $action, $id, $cur_page, $lang, $conn, $config, $edit, $pic, $submit;

include("$config[template_path]/admin_top.html");
	// does this person have access to these autos?
	if ($admin_privs != "yes")
		{
		$sql_edit = make_db_safe($edit);
		$sql = "SELECT user_ID FROM ".$config['table_prefix']."autodb WHERE (ID = $sql_edit)";
		$recordSet = $conn->Execute($sql);
			if ($recordSet === false) log_error($sql);
		while (!$recordSet->EOF)
			{
			$owner = $recordSet->fields['user_ID'];
			$recordSet->MoveNext();
			}
		if ($userID != $owner)
			{
			die ("$lang[priv_failure]");
			}
		} // end priv check
	
	if ($action == "update_pic")
		{
		$sql_edit = make_db_safe($edit);
		$sql_caption = make_db_safe($caption);
		$sql_description = make_db_safe($description);
		$sql_rank = make_db_safe($rank);
		$sql_pic = make_db_safe($pic);
		
		if ($admin_privs == "yes")
			{
			$sql = "UPDATE ".$config['table_prefix']."autoimages SET caption = $sql_caption, description = $sql_description, rank = $sql_rank WHERE ((auto_id = $sql_edit) AND (file_name = $sql_pic))";
			}
		else
			{
			$sql = "UPDATE ".$config['table_prefix']."autoimages SET caption = $sql_caption, description = $sql_description, rank = $sql_rank WHERE ((auto_id = $sql_edit) AND (file_name = $sql_pic) AND (user_id = $userID))";
			}
		$recordSet = $conn->Execute($sql);
			if ($recordSet === false) log_error($sql);
		echo "<p>$lang[image] '$pic' $lang[has_been_updated]</p>";
		log_action ("$lang[log_updated_auto_image] $pic");
		}
		
	if ($action == "delete_pic")
		{
		// get the data for the pic being deleted
		
		$sql_edit = make_db_safe($edit);
		$sql_pic_id = make_db_safe($pic_id);
		
		if ($admin_privs == "yes")
			{
			$sql = "SELECT file_name, thumb_file_name FROM ".$config['table_prefix']."autoimages WHERE ((auto_id = $sql_edit) AND (ID = $pic_id))";
			}
		else
			{
			$sql = "SELECT file_name, thumb_file_name FROM ".$config['table_prefix']."autoimages WHERE ((auto_id = $edit) AND (ID = $pic_id) AND (user_id = $userID))";
			}
		$recordSet = $conn->Execute($sql);
			if ($recordSet === false) log_error($sql);
			
		while (!$recordSet->EOF)
			{
			$thumb_file_name = make_db_unsafe ($recordSet->fields['thumb_file_name']);
			$file_name = make_db_unsafe ($recordSet->fields['file_name']);
			$recordSet->MoveNext();
			} // end while
		
		// delete from the db
		if ($admin_privs == "yes")
			{
			$sql = "DELETE FROM ".$config['table_prefix']."autoimages WHERE ((auto_id = $sql_edit) AND (file_name = '$file_name'))";
			}
		else
			{
			$sql = "DELETE FROM ".$config['table_prefix']."autoimages WHERE ((auto_id = $sql_edit) AND (file_name = '$file_name') AND (user_id = '$userID'))";
			}
		$recordSet = $conn->Execute($sql);
			if ($recordSet === false) log_error($sql);
		
		// delete the files themselves
		// on widows, required php 4.11 or better (I think)
	
		if (!unlink("$config[auto_upload_path]/$file_name")) die("$lang[alert_site_admin]");
		if ($file_name != $thumb_file_name)
			{
			if (!unlink("$config[auto_upload_path]/$thumb_file_name")) die("$lang[alert_site_admin]");
			}
		log_action ("$lang[log_deleted_auto_image] $file_name");
		echo "<p>$lang[image] '$file_name' $lang[has_been_deleted]</p>";
		
		}
	
	
		
	if ($action == "upload")
		{
		if ($admin_privs == "yes")
			{
			// get the owner of the auto
			$sql = "SELECT user_ID FROM ".$config['table_prefix']."autodb WHERE (ID = '$edit')";
			$recordSet = $conn->Execute($sql);
				if ($recordSet === false) log_error($sql);
			while (!$recordSet->EOF)
				{
				$owner = $recordSet->fields['user_ID'];
				$recordSet->MoveNext();
				}
			handleautoUpload("autos",$edit, $owner);
			}
		else
			{
			handleautoUpload("autos",$edit, $userID);
			}
		} // end if $action == "upload"
	?>
	
	<table border="<?php echo $style['form_border'] ?>" cellspacing="<?php echo $style['form_cellspacing'] ?>" cellpadding="<?php echo $style['form_cellpadding'] ?>" width="<?php echo $style['admin_table_width'] ?>" class="form_main">

	<tr><td colspan="2" class="row_main"><h3><?php echo $lang['edit_images']?> -- 
	<?php
	if ($admin_privs == "yes")
		{
		echo "<a href=\"auto_edit.php?edit=$edit\">";
		}
	else
		{
		echo "<a href=\"edit_my_auto.php?edit=$edit\">";
		}
	echo $lang['return_to_editing_auto'];
	?>
	</a></h3></td></tr>
	<?php
	if ($admin_privs == "yes")
		{
		$sql = "SELECT ID, caption, file_name, thumb_file_name, description, rank FROM ".$config['table_prefix']."autoimages WHERE (auto_id = '$edit') ORDER BY rank";
		}
	else
		{
		$sql = "SELECT ID, caption, file_name, thumb_file_name, description, rank FROM ".$config['table_prefix']."autoimages WHERE ((auto_id = '$edit') AND (user_id = '$userID')) ORDER BY rank";
		}
	$recordSet = $conn->Execute($sql);
	if ($recordSet === false) log_error($sql);
				
	$num_images = $recordSet->RecordCount();
	
	$count = 0;
	while (!$recordSet->EOF)
		{
		$pic_id = $recordSet->fields['ID'];
		$rank = $recordSet->fields['rank'];
		$caption = make_db_unsafe ($recordSet->fields['caption']);
		$thumb_file_name = make_db_unsafe ($recordSet->fields['thumb_file_name']);
		$file_name = make_db_unsafe ($recordSet->fields['file_name']);
		$description = make_db_unsafe ($recordSet->fields['description']);
			
		// gotta grab the image size
		$imagedata = GetImageSize("$config[auto_upload_path]/$file_name");
		$imagewidth = $imagedata[0];
		$imageheight = $imagedata[1];
		$shrinkage = $config['thumbnail_width']/$imagewidth;
		$displaywidth = $imagewidth * $shrinkage;
		$displayheight = $imageheight * $shrinkage;
		$filesize = filesize("$config[auto_upload_path]/$file_name");
		$filesize = $filesize/1000; // to get k
		
		// now grab the thumbnail data
		$thumb_imagedata = GetImageSize("$config[auto_upload_path]/$thumb_file_name");
		$thumb_imagewidth = $thumb_imagedata[0];
		$thumb_imageheight = $thumb_imagedata[1];
		$thumb_filesize = filesize("$config[auto_upload_path]/$thumb_file_name");
		$thumb_filesize = $thumb_filesize/1000;
		
		// alternate the colors
		if ($count == 0)
			{
			$count = $count +1;
			}
		else
			{
			$count = 0;
			}
			
		
		echo "<tr class=\"image_row_$count\"><td valign=\"top\" class=\"image_row_$count\" width=\"150\"><b>$file_name</b><br>$lang[width]=$imagewidth<br>$lang[height]=$imageheight<br>$lang[size]=$filesize"."k<br>";
		echo "<br>$lang[thumbnail]:<br>";
		echo "<img src=\"$config[auto_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" border=\"1\"> ";
		echo "<br>$lang[width]=$thumb_imagewidth<br>$lang[height]=$thumb_imageheight<br>$lang[size]=$thumb_filesize"."k<br>";
		echo "<p align=\"center\"><a href=\"$PHP_SELF?action=delete_pic&edit=$edit&pic_id=$pic_id\" onClick=\"return confirmDelete()\">Delete</p>";
		echo "</td><td align=\"center\" class=\"image_row_$count\"><img src=\"$config[auto_view_images_path]/$file_name\" border=\"1\">";
		echo "</tr><tr><td align=\"center\" class=\"image_row_$count\" colspan=\"2\"><form action=\"$PHP_SELF\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"edit\" value=\"$edit\">";
		echo "<input type=\"hidden\" name=\"action\" value=\"update_pic\">";
		echo "<input type=\"hidden\" name=\"pic\" value=\"$file_name\">";
		
		echo "<table border=\"0\">";
		echo "<tr><td align=\"right\" class=\"image_row_$count\"><b>$lang[admin_template_editor_field_rank]:</b></td><td align=\"left\"><input type=\"text\" name=\"rank\" value=\"$rank\"><div class=\"small\">$lang[upload_rank_explanation]</div></td></tr>";
		echo "<tr><td align=\"right\" class=\"image_row_$count\"><b>$lang[upload_caption]:</b></td><td align=\"left\"><input type=\"text\" name=\"caption\" value=\"$caption\"></td></tr>";
		echo "<tr><td align=\"right\" class=\"image_row_$count\"><b>$lang[upload_description]:</b><td align=\"left\"><textarea name=\"description\" rows=\"6\" cols=\"40\">$description</textarea></td></tr>";
		echo "<tr><td align=\"center\"  class=\"image_row_$count\" colspan=\"2\"><input type=\"submit\" value=\"Update\">";
		echo "</form></td></tr>";
		echo "</table>";
		
		
		echo "</td></tr><tr><td colspan=\"2\"><hr></td></tr>";
		$recordSet->MoveNext();
		} // end while
	
	echo "</table>";
	if ($num_images < $config['max_auto_uploads'])
		{
	?>
	
		<table border=0 cellspacing=0 cellpadding=0>
			<tr>
				<td colspan="2">
					<h3><?php echo $lang['upload_a_picture'] ?></h3>
				</td>

			</tr>
			<tr>
				<td width="150">&nbsp;</td>
				<td>
					<form enctype="multipart/form-data" action="<?php echo $PHP_SELF ?>" method="post">
					<input type="hidden" name="action" value="upload">
					<input type="hidden" name="edit" value="<?php echo $edit ?>">
					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_auto_upload_size ?>">
					<b><?php echo $lang['upload_send_this_file'] ?>: </b><input name="userfile" type="file"><br>
				
					<input type="submit" value="<?php echo $lang['upload_send_file'] ?>">
					</form>
				</td>
			</tr>
		</table>
	<?php
		} // end if $num_images <= $config[max_user_uploads]
	?>


<?php
include("$config[template_path]/admin_bottom.html");
$conn->Close(); // close the db connection
?>