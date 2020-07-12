<?php
include("admin_header.php");
xoops_cp_header();
?>

<?php


	include("../include/common.php");

	loginCheck('User');

	global $action, $id, $cur_page, $lang, $conn, $config, $edit, $pic, $submit;

	include("$config[template_path]/admin_top.html");
		
		
		if ($action == "update_pic")
		{
			if ($admin_privs == "yes")
			{
				$sql = "UPDATE " . $config[table_prefix] . "userImages SET caption = '$caption', description = '$description', rank = '$rank' WHERE ((user_id = '$edit') AND (file_name = '$pic'))";
			}
			else
			{
				$sql = "UPDATE " . $config[table_prefix] . "userImages SET caption = '$caption', description = '$description', rank = '$rank' WHERE ((file_name = '$pic') AND (user_id = '$userID'))";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) 
			{
				log_error($sql);
			}
			log_action ("$lang[log_updated_listing_image] $pic");
			echo "<p>$lang[image] '$pic' $lang[has_been_updated]</p>";
		}
			
		if ($action == "delete_pic")
		{
			// get the data for the pic being deleted
			if ($admin_privs == "yes")
			{
				$sql = "SELECT file_name, thumb_file_name FROM " . $config[table_prefix] . "userImages WHERE ((user_id = '$edit') AND (ID = '$pic_id'))";
			}
			else
			{
				$sql = "SELECT file_name, thumb_file_name FROM " . $config[table_prefix] . "userImages WHERE ((ID = '$pic_id') AND (user_id = '$userID'))";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
				
			while (!$recordSet->EOF)
			{
				$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
				$file_name = make_db_unsafe ($recordSet->fields[file_name]);
				$recordSet->MoveNext();
			} // end while
			
			// delete from the db
			if ($admin_privs == "yes")
			{
				$sql = "DELETE FROM " . $config[table_prefix] . "userImages WHERE ((user_id  = '$edit') AND (file_name = '$file_name'))";
			}
			else
			{
				$sql = "DELETE FROM " . $config[table_prefix] . "userImages WHERE ((file_name = '$file_name') AND (user_id = '$userID'))";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			
			// delete the files themselves
			// on widows, required php 4.11 or better (I think)
			if (!unlink("$config[user_upload_path]/$file_name"))
			{
				die("$lang[alert_site_admin]");
			}
			if (substr("$thumb_file_name", -3) == "jpg" || substr("$thumb_file_name", -3) == "png")
			{
				if (!unlink("$config[user_upload_path]/$thumb_file_name"))
				{
					die("$lang[alert_site_admin]");
				}
			}
			
			log_action ("$lang[log_deleted_user_image] $file_name");
			echo "<p>$lang[image] '$file_name' $lang[has_been_deleted]</p>";
		}
		
		
			
		if ($action == "upload")
		{
			if ($admin_privs == "yes")
			{
				
				handleUpload("user",'', $edit);
			}
			else
			{
				handleUpload("user",'', $userID);
			}
		} // end if $action == "upload"
	?>
		
		<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
		<tr><td colspan="2" class="row_main"><h3><?php echo $lang[edit_images]?> -- 
	<?php
		if ($admin_privs == "yes")
		{
			echo "<a href=\"user_edit.php?edit=$edit\">";
		}
		else
		{
			echo "<a href=\"edit_my_account.php?edit=$edit\">";
		}
		echo $lang[return_to_editing_listing];
	?>
		</a></h3></td></tr>
	<?php
		if ($admin_privs == "yes")
		{
			$sql = "SELECT ID, caption, file_name, thumb_file_name, description, rank FROM " . $config[table_prefix] . "userImages WHERE (user_id = '$edit') ORDER BY rank";
		}
		else
		{
			$sql = "SELECT ID, caption, file_name, thumb_file_name, description, rank FROM " . $config[table_prefix] . "userImages WHERE ((user_id = '$userID')) ORDER BY rank";
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
					
		$num_images = $recordSet->RecordCount();
		
		$count = 0;
		while (!$recordSet->EOF)
		{
			$pic_id = $recordSet->fields[ID];
			$rank = $recordSet->fields[rank];
			$caption = make_db_unsafe ($recordSet->fields[caption]);
			$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
			$file_name = make_db_unsafe ($recordSet->fields[file_name]);
			$description = make_db_unsafe ($recordSet->fields[description]);
				
			// gotta grab the image size
			$imagedata = GetImageSize("$config[user_upload_path]/$file_name");
			$imagewidth = $imagedata[0];
			$imageheight = $imagedata[1];
			$shrinkage = $config[thumbnail_width]/$imagewidth;
			$displaywidth = $imagewidth * $shrinkage;
			$displayheight = $imageheight * $shrinkage;
			$filesize = filesize("$config[user_upload_path]/$file_name");
			$filesize = $filesize/1000; // to get k
			
			// now grab the thumbnail data
			$thumb_imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
			$thumb_imagewidth = $thumb_imagedata[0];
			$thumb_imageheight = $thumb_imagedata[1];
			$thumb_filesize = filesize("$config[user_upload_path]/$thumb_file_name");
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
			echo "<img src=\"$config[user_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" border=\"1\"> ";
			echo "<br>$lang[width]=$thumb_imagewidth<br>$lang[height]=$thumb_imageheight<br>$lang[size]=$thumb_filesize"."k<br>";
			echo "<p align=\"center\"><a href=\"$PHP_SELF?action=delete_pic&edit=$edit&pic_id=$pic_id\" onClick=\"return confirmDelete()\">Delete</p>";
			echo "</td><td align=\"center\" class=\"image_row_$count\"><img src=\"$config[user_view_images_path]/$file_name\" border=\"1\">";
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
		if ($num_images < $config[max_user_uploads])
		{
	?>
		
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td colspan="2">
						<h3><?php echo $lang[upload_a_picture] ?></h3>
					</td>

				</tr>
				<tr>
					<td width="150">&nbsp;</td>
					<td>
						<form enctype="multipart/form-data" action="<?php echo $PHP_SELF ?>" method="post">
						<input type="hidden" name="action" value="upload">
						<input type="hidden" name="edit" value="<?php echo $edit ?>">
						<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_user_upload_size ?>">
						<b><?php echo $lang[upload_send_this_file] ?>: </b><input name="userfile" type="file"><br>
					
						<input type="submit" value="<?php echo $lang[upload_send_file] ?>">
						</form>
					</td>
				</tr>
			</table>
<?
include ("../footer.php"); 
?>
	<?php
		} // end if $num_images <= $config[max_user_uploads]
		?>


<?php

	$conn->Close(); // close the db connection
?>
<?
xoops_cp_footer();
include("../../../footer.php");
?>