<?php
include("admin_header.php");
xoops_cp_header();
?>

<?php


include("../include/common.php");
loginCheck('Admin');

global $action, $id, $cur_page, $edit, $lang, $conn, $config;

include("$config[template_path]/admin_top.html");


if ($delete != "")
	{
	global $conn;
	$sql_delete = make_db_safe($delete);
	
	// delete the user
	$sql = "DELETE FROM " . $config[table_prefix] . "UserDB WHERE ID = $sql_delete";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
		
	// delete all the elements associated with the user
	$sql = "DELETE FROM " . $config[table_prefix] . "UserDBElements WHERE user_id = $sql_delete";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
	
	// delete all the listings associated with a user
	$sql = "DELETE FROM " . $config[table_prefix] . "listingsDB WHERE (user_ID = $sql_delete)";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
	
	// delete all the elements associated with a user	
	$sql = "DELETE FROM " . $config[table_prefix] . "listingsDBElements WHERE (user_id = $sql_delete)";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
	
	// delete all the favorites associated with a user	
	$sql = "DELETE FROM " . $config[table_prefix] . "userFavoriteListings WHERE (user_id = $sql_delete)";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
	
	// delete all the saved searches associated with a user	
	$sql = "DELETE FROM " . $config[table_prefix] . "userSavedSearches WHERE (user_id = $sql_delete)";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
	
	// now get all the images associated with a user's listings
	$sql = "SELECT file_name, thumb_file_name FROM " . $config[table_prefix] . "listingsImages WHERE (user_id = $sql_delete)";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
	
	// so, you've got 'em... it's time to unlink those bad boys...
	while (!$recordSet->EOF)
		{
		$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
		$file_name = make_db_unsafe ($recordSet->fields[file_name]);
		// get rid of those darned things...
		if (!unlink("$config[listings_upload_path]/$file_name")) die("$lang[alert_site_admin]");
		if ($file_name != $thumb_file_name)
			{
			if (!unlink("$config[listings_upload_path]/$thumb_file_name")) die("$lang[alert_site_admin]");
			}
		$recordSet->MoveNext();
		}
		
	// it's time to do the same for all the images associated with the user himself
	$sql = "SELECT file_name, thumb_file_name FROM " . $config[table_prefix] . "userImages WHERE (user_id = $sql_delete)";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);

	while (!$recordSet->EOF)
	{
		$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
		$file_name = make_db_unsafe ($recordSet->fields[file_name]);
		// get rid of those darned things...
		if (!unlink("$config[user_upload_path]/$file_name")) die("$lang[alert_site_admin]");
		if ($file_name != $thumb_file_name)
		{
			if (!unlink("$config[user_upload_path]/$thumb_file_name")) die("$lang[alert_site_admin]");
		}
		$recordSet->MoveNext();
	}
	// delete all the saved images associated with a user from userImages   
	$sql = "DELETE FROM " . $config[table_prefix] . "userImages WHERE (user_id = $sql_delete)";
	$recordSet = $conn->Execute($sql);
	if ($recordSet === false) log_error($sql);
	// that's it... we're done. (More complicated than one might think, eh?)
	
	log_action ("$lang[log_deleted_user]: $delete");
	echo "<p>$lang[user_editor_user_number] '$delete' $lang[has_been_deleted]</p>";
	
	}

if ($action == "update_user")
	{
	if ($user_email == "")
		{
		echo "<p>$lang[user_editor_need_email_address]</p>";
		echo "<FORM><INPUT TYPE=\"BUTTON\" VALUE=\"$lang[back_button_text]\" onClick=\"history.back()\"></FORM>";
		} // end else
	else
		{
		global $pass_the_form;
		if ($edit_isAgent == "yes")
		  $pass_the_form = validateForm(agentFormElements);
		else
		  $pass_the_form = validateForm(memberFormElements);
		
		if ($pass_the_form == "No")
			{
			// if we're not going to pass it, tell that they forgot to fill in one of the fields
			echo "<p>$lang[required_fields_not_filled]</p>";
			}
	
		if ($pass_the_form == "Yes")
			{
			$sql_edit =  make_db_safe($edit);
			$sql_user_email = make_db_safe($user_email);
			if ($edit_user_pass != "")
				{
				$md5_user_pass = md5($edit_user_pass);
				$sql_user_pass = make_db_safe($md5_user_pass);
				$sql = "UPDATE " . $config[table_prefix] . "UserDB SET emailAddress = $sql_user_email, user_password = $sql_user_pass, last_modified = ".$conn->DBTimeStamp(time())." WHERE ID = $sql_edit";
				}
			else
				{
				$sql = "UPDATE " . $config[table_prefix] . "UserDB SET emailAddress = $sql_user_email, last_modified = ".$conn->DBTimeStamp(time())." WHERE ID = $sql_edit";
				}
			$recordSet = $conn->Execute($sql);
				if ($recordSet === false) log_error($sql);
			if ($admin_privs == "yes")
				{
				$sql_edit_active =  make_db_safe($edit_active);
				$sql_edit_isAgent =  make_db_safe($edit_isAgent);
				$sql_edit_isAdmin =  make_db_safe($edit_isAdmin);
				$sql_edit_canEditForms =  make_db_safe($edit_canEditForms);
				$sql_edit_canFeatureListings =  make_db_safe($edit_canFeatureListings);
				$sql_edit_canViewLogs =  make_db_safe($edit_canViewLogs);
				$sql_edit_canModerate = make_db_safe($edit_canModerate);
				$sql = "UPDATE " . $config[table_prefix] . "UserDB SET isAdmin = $sql_edit_isAdmin,";
				$sql .= "active = $sql_edit_active,";
				$sql .= "isAgent = $sql_edit_isAgent,";
				$sql .= "canEditForms = $sql_edit_canEditForms,";
				$sql .= "canFeatureListings = $sql_edit_canFeatureListings,";
				$sql .= "canViewLogs = $sql_edit_canViewLogs,";
				$sql .= "canModerate = $sql_edit_canModerate";
				$sql .= "WHERE ID = $sql_edit";
				$recordSet = $conn->Execute($sql);
					if ($recordSet === false) log_error($sql);
				} // end ($admin_privs == "yes")
				
			$message = updateUserData($userID);
			if ($message == "success")
				{
				log_action ("$lang[log_updated_user]: $edit");
				echo "<p>$lang[user_editor_user_number] $edit $lang[has_been_updated] </p>";
				} // end if
			else
				{
				echo "<p>$lang[alert_site_admin]</p>";
				} // end else
			} // end if $pass_the_form == "Yes"
		
		} // end else
		
	
	} // end if $action == "update_user"
	
if ($edit == "")
	{
	echo "<h3>$lang[user_editor_edit_users]</h3>";
	// find the number of users
	$sql="SELECT * FROM " . $config[table_prefix] . "UserDB WHERE isAgent = '$edit_isAgent' ORDER BY id";
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$recordSet = $conn->Execute($sql);
	if ($recordSet === false) log_error($sql);
	$num_rows = $recordSet->RecordCount();
	
	next_prev($num_rows, $cur_page, "edit_isAgent=$edit_isAgent&"); // put in the next/previous stuff
	
	// build the string to select a certain number of users per page
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$limit_str = $cur_page * $config[listings_per_page];
	$recordSet = $conn->SelectLimit($sql, $config[listings_per_page], $limit_str );
		if ($recordSet === false) log_error($sql);
	
	$count = 0;
	echo "<br><br>";
	while (!$recordSet->EOF)
		{
		
		// alternate the colors
		if ($count == 0)
			{
			$count = $count +1;
			}
		else
			{
			$count = 0;
			}
		
		//strip slashes so input appears correctly
		$edit_ID = $recordSet->fields[ID];
		$edit_user_name = make_db_unsafe ($recordSet->fields[user_name]);
		$edit_emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
		$edit_Comments = make_db_unsafe ($recordSet->fields[Comments]);
		$edit_active = $recordSet->fields[active];
		$edit_isAgent = $recordSet->fields[isAgent];
		$edit_isAdmin = $recordSet->fields[isAdmin];
		$edit_canEditForms = $recordSet->fields[canEditForms];
		$edit_canFeatureListings = $recordSet->fields[canFeatureListings];
		$edit_canViewLogs = $recordSet->fields[canViewLogs];
		$edit_canModerate = $recordSet->fields[canModerate];
		?>
		<table border="<? echo $style[admin_listing_border] ?>" cellspacing="<? echo $style[admin_listing_cellspacing] ?>" cellpadding="<? echo $style[admin_listing_cellpadding] ?>" width="<? echo $style[admin_table_width] ?>" class="form_main">
		<?
		echo "<tr><td align=\"right\" width=\"200\" class=\"row1_$count\"><span class=\"adminListingLeft_$count\"><B>$lang[user_editor_user_number]: $edit_ID</b></span></td><td align=\"center\" class=\"row2_$count\" width=\"310\"> <B> <a href=\"$PHP_SELF?edit=$edit_ID&edit_isAgent=$edit_isAgent\">$lang[user_editor_modify_user] </a></b></td><td width=\"120\" align=\"middle\" class=\"row2_$count\"><a href=\"$PHP_SELF?delete=$edit_ID&edit_isAgent=$edit_isAgent\" onClick=\"return confirmDelete()\">$lang[user_editor_delete_user]</a></td></tr>";	
		echo "<tr><td align=\"center\" valign=\"middle\" class=\"row3_$count\">$edit_user_name";
		echo "</td><td class=\"row3_$count\">$edit_Comments</td>";
		echo "<td class=\"row3_$count\" width=\"200\">$lang[user_editor_active]: $edit_active<br>$lang[user_editor_isAgent]: $edit_isAgent<br>$lang[user_editor_isAdmin]: $edit_isAdmin";
	    echo "<br>$lang[user_editor_form_edit]: $edit_canEditForms";
		echo "<br>$lang[user_editor_feature_listings]: $edit_canFeatureListings";
		echo "<br>$lang[user_editor_view_logs]: $edit_canViewLogs";
		
			
		echo "</td></tr></table><br><br>\r\n\r\n";
		$recordSet->MoveNext();
		} // end while
	
	
	
	} // end if edit == ""
else
	{
	// first, grab the user's main info
	global $conn;
	
	?>
	<table border="<? echo $style[form_border] ?>" cellspacing="<? echo $style[form_cellspacing] ?>" cellpadding="<? echo $style[form_cellpadding] ?>" width="<? echo $style[admin_table_width] ?>" class="form_main">
	
	<?
	echo "<td colspan=\"2\" class=\"row_main\"><h3>$lang[user_editor_modify_user]</h3></td></tr>";
 	?>
	<tr>
		<td width="<? echo $style[image_column_width] ?>" valign="top" align="center" class="row_main">
			<b><? echo $lang[images] ?></b>
			<br>
			<hr width="75%">
			<a href="edit_user_images.php?edit=<? echo $edit ?>"><? echo $lang[edit_images] ?></a><br><br>
			<?

			$sql = "SELECT caption, file_name, thumb_file_name FROM " . $config[table_prefix] . "userImages WHERE user_id = '$edit'";
			$recordSet = $conn->Execute($sql);
				if ($recordSet === false) log_error($sql);
				
			$num_images = $recordSet->RecordCount();
				
			while (!$recordSet->EOF)
				{
				$caption = make_db_unsafe ($recordSet->fields[caption]);
				$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
				$file_name = make_db_unsafe ($recordSet->fields[file_name]);
				
				// gotta grab the image size
				$imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$shrinkage = $config[thumbnail_width]/$imagewidth;
				$displaywidth = $imagewidth * $shrinkage;
				$displayheight = $imageheight * $shrinkage;
					
				echo "<a href=\"$config[user_view_images_path]/$file_name\" target=\"_thumb\"> ";
					
				echo "<img src=\"$config[user_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a><br> ";
				echo "<b>$caption</b><br><br>";
				$recordSet->MoveNext();
				} // end while
			?>
			</td>
			<td valign="top" class="row_main">
	
	

	
	<?
	$sql = "SELECT * FROM " . $config[table_prefix] . "UserDB WHERE ID = '$edit'";
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$recordSet = $conn->Execute($sql);
	if ($recordSet === false) log_error($sql);
	while (!$recordSet->EOF)
		{
		// collect up the main DB's various fields
		$edit_user_name = make_db_unsafe ($recordSet->fields[user_name]);
		$edit_emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
		$edit_comments = make_db_unsafe ($recordSet->fields[Comments]);
		$edit_password = make_db_unsafe ($recordSet->fields[user_password]);
		$edit_active = $recordSet->fields[active];
		$edit_isAgent = $recordSet->fields[isAgent];
		$edit_isAdmin = $recordSet->fields[isAdmin];
		$edit_canEditForms = $recordSet->fields[canEditForms];
		$edit_canViewLogs = $recordSet->fields[canViewLogs];
		$edit_canModerate = $recordSet->fields[canModerate];
		$edit_canFeatureListings = $recordSet->fields[canFeatureListings];
		$last_modified = $recordSet->UserTimeStamp($recordSet->fields[last_modified],'D M j G:i:s T Y');
		$recordSet->MoveNext();
		} // end while
	
	// now, display all that stuff
	echo "<form name=\"updateUser\" action=\"$PHP_SELF\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"update_user\">";
	echo "<input type=\"hidden\" name=\"edit\" value=\"$edit\">";
	echo "<table border=\"$style[form_border]\" cellspacing=\"$style[form_cellspacing]\" cellpadding=\"$style[form_cellpadding]\">";
	echo "<tr><td align=right><b>$lang[user_name]:</b></td><td align=left>$edit_user_name</td></tr>";
	echo "<tr><td align=\"right\" class=\"row_main\"><b>$lang[last_modifed]:</b></td><td align=\"left\">$last_modified</td></tr>";
	echo "<tr><td align=right><b>$lang[user_password]: <font color=\"red\">*</font></b></td><td align=left> <input type=\"text\" name=\"edit_user_pass\" value=\"\"> <div class=\"small\">($lang[leave_blank_if_you_do_not_want_to_change])</div></td></tr>";
	echo "<tr><td align=right><b>$lang[user_email]: <font color=\"red\">*</font></b></td><td align=left> <input type=\"text\" name=\"user_email\" value=\"$edit_emailAddress\"> ";
	if ($admin_privs == "yes")
		{
		// if the user is an admin, they can set additional properties about a given user

		// is the user active?
		echo "<tr><td align=right><b>$lang[user_editor_active]: </b></td>";
		echo "<td align=left><select name=\"edit_active\" size=\"1\"><option value=\"$edit_active\">$edit_active<option value=\"\">-----<option value=\"yes\">yes<option value=\"no\">no</select></td></tr>";	
		
		// is the user an agent?
		echo "<tr><td align=right><b>$lang[user_editor_isAgent]: </b></td>";
		echo "<td align=left>$edit_isAgent</td></tr>";	
		echo "<input type=\"hidden\" name=\"edit_isAgent\" value=\"" . $edit_isAgent . "\">";
	
		
		// is the user an administrator?
		echo "<tr><td align=right><b>$lang[user_editor_isAdmin]: </b></td>";
		echo "<td align=left><select name=\"edit_isAdmin\" size=\"1\"><option value=\"$edit_canEditForms\">$edit_isAdmin<option value=\"\">-----<option value=\"yes\">yes<option value=\"no\">no</select></td></tr>";	
		
		// can they edit forms?
		echo "<tr><td align=right><b>$lang[user_editor_can_edit_forms]: </b></td>";
		echo "<td align=left><select name=\"edit_canEditForms\" size=\"1\"><option value=\"$edit_canEditForms\">$edit_canEditForms<option value=\"\">-----<option value=\"yes\">yes<option value=\"no\">no</select></td></tr>";	
		
		// can they view logs?
		echo "<tr><td align=right><b>$lang[user_editor_view_logs]: </b></td>";
		echo "<td align=left><select name=\"edit_canViewLogs\" size=\"1\"><option value=\"$edit_canViewLogs\">$edit_canViewLogs<option value=\"\">-----<option value=\"yes\">yes<option value=\"no\">no</select></td></tr>";	
		
		// can they moderate incoming listings?
		echo "<tr><td align=right><b>$lang[user_editor_moderator]: </b></td>";
		echo "<td align=left><select name=\"edit_canModerate\" size=\"1\"><option value=\"$edit_canModerate\">$edit_canModerate<option value=\"\">-----<option value=\"yes\">yes<option value=\"no\">no</select></td></tr>";	
		
		// can they feature listings?
		echo "<tr><td align=right><b>$lang[user_editor_feature_listings]: </b></td>";
		echo "<td align=left><select name=\"edit_canFeatureListings\" size=\"1\"><option value=\"$edit_canFeatureListings\">$edit_canFeatureListings<option value=\"\">-----<option value=\"yes\">yes<option value=\"no\">no</select></td></tr>";	
		
		}
		
	// now grab miscellenous debris
	if ($edit_isAgent == "yes")
	{
		$sql = "SELECT f.field_name, db.field_value, f.field_type, f.rank, f.field_caption, f.default_text, f.required, f.field_elements FROM " . $config[table_prefix] . "agentFormElements f left join " . $config[table_prefix] . "UserDBElements db on db.field_name = f.field_name and db.user_id = '$edit' ORDER BY f.rank";
		//$sql = "SELECT " . $config[table_prefix] . "UserDBElements.field_name, " . $config[table_prefix] . "UserDBElements.field_value, " . $config[table_prefix] . "agentFormElements.field_type, " . $config[table_prefix] . "agentFormElements.rank, " . $config[table_prefix] . "agentFormElements.field_caption, " . $config[table_prefix] . "agentFormElements.default_text, " . $config[table_prefix] . "agentFormElements.required, " . $config[table_prefix] . "agentFormElements.field_elements FROM " . $config[table_prefix] . "UserDBElements, " . $config[table_prefix] . "agentFormElements WHERE ((" . $config[table_prefix] . "UserDBElements.user_id = '$edit') AND (" . $config[table_prefix] . "UserDBElements.field_name = " . $config[table_prefix] . "agentFormElements.field_name)) ORDER BY " . $config[table_prefix] . "agentFormElements.rank";
	}
	else
	{
		$sql = "SELECT f.field_name, db.field_value, f.field_type, f.rank, f.field_caption, f.default_text, f.required, f.field_elements FROM " . $config[table_prefix] . "memberFormElements f left join " . $config[table_prefix] . "UserDBElements db on db.field_name = f.field_name and db.user_id = '$edit' ORDER BY f.rank";
		//$sql = "SELECT " . $config[table_prefix] . "UserDBElements.field_name, " . $config[table_prefix] . "UserDBElements.field_value, " . $config[table_prefix] . "memberFormElements.field_type, " . $config[table_prefix] . "memberFormElements.rank, " . $config[table_prefix] . "memberFormElements.field_caption, " . $config[table_prefix] . "memberFormElements.default_text, " . $config[table_prefix] . "memberFormElements.required, " . $config[table_prefix] . "memberFormElements.field_elements FROM " . $config[table_prefix] . "UserDBElements, " . $config[table_prefix] . "memberFormElements WHERE ((" . $config[table_prefix] . "UserDBElements.user_id = '$edit') AND (" . $config[table_prefix] . "UserDBElements.field_name = " . $config[table_prefix] . "memberFormElements.field_name)) ORDER BY " . $config[table_prefix] . "memberFormElements.rank";
	}
	$recordSet = $conn->Execute($sql);
	if ($recordSet === false) log_error($sql);
	while (!$recordSet->EOF)
		{
		$field_name = make_db_unsafe ($recordSet->fields[field_name]);
		$field_value = make_db_unsafe ($recordSet->fields[field_value]);
		$field_type = make_db_unsafe ($recordSet->fields[field_type]);
		$field_caption = make_db_unsafe($recordSet->fields[field_caption]);
		$default_text = make_db_unsafe($recordSet->fields[default_text]);
		$field_elements = make_db_unsafe($recordSet->fields[field_elements]);
		$required = make_db_unsafe($recordSet->fields[required]);
		
		// pass the data to the function
		renderExistingFormElement($field_type, $field_name, $field_value, $field_caption, $default_text, $required, $field_elements);
		$recordSet->MoveNext();
		} // end while
	
	echo "<tr><td colspan=\"2\" align=\"center\">$lang[required_form_text]</td></tr>";

	echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"$lang[update_button]\"></td></tr></table></form>";
	
	} // end if
?>
</td></tr></table>

<?
include ("../footer.php"); 
?>
<P>
</P>

<?
$conn->Close(); // close the db connection
?>
<?
xoops_cp_footer();
include("../../../footer.php");
?>