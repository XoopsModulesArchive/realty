<?php
include("admin_header.php");
xoops_cp_header();
?>

<?php

	include("../include/common.php");
	loginCheck('Admin');
	global $action, $id, $cur_page, $edit, $conn, $config;
	include("$config[template_path]/admin_top.html");

	if ($delete != "")
	{
		// delete a listing
		$sql_delete = make_db_safe($delete);
		// delete a listing
		$sql = "DELETE FROM " . $config[table_prefix] . "listingsDB WHERE (ID = $sql_delete)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// delete all the elements associated with a listing
		$sql = "DELETE FROM " . $config[table_prefix] . "listingsDBElements WHERE (listing_id = $sql_delete)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) 
		{
			log_error($sql);
		}

		// now get all the images associated with an listing
		$sql = "SELECT file_name, thumb_file_name FROM " . $config[table_prefix] . "listingsImages WHERE (listing_id = $sql_delete)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// so, you've got 'em... it's time to unlink those bad boys...
		while (!$recordSet->EOF)
		{
			$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
			$file_name = make_db_unsafe ($recordSet->fields[file_name]);
			// get rid of those darned things...
			if (!unlink("$config[listings_upload_path]/$file_name"))
			{
				die("$lang[alert_site_admin]");
			}
			if ($file_name != $thumb_file_name)
			{
				if (!unlink("$config[listings_upload_path]/$thumb_file_name")) die("$lang[alert_site_admin]");
			}
			$recordSet->MoveNext();
		}

		// for the grand finale, we're going to remove the db records of 'em as well...
		$sql = "DELETE FROM " . $config[table_prefix] . "listingsImages WHERE (listing_id = $sql_delete)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		echo "<p>$lang[admin_listings_editor_listing_number] '$delete' $lang[has_been_deleted]</p>";
		log_action ("$lang[log_deleted_listing] $delete");
	}

	if ($action == "update_listing")
	{
		// update the listing
		// update a listing
		if ($title== "")
		{
			// if the title is blank
			echo "<p>$lang[admin_new_listing_enter_a_title]</p>";
		} // end if

		else
		{
			// if the title isn't blank....
			global $HTTP_POST_VARS, $userID, $pass_the_form;
			$pass_the_form = validateForm(listingsFormElements); // valifates the form
			if ($pass_the_form == "No")
			{
				// if we're not going to pass it, tell that they forgot to fill in one of the fields
				echo "<p>$lang[required_fields_not_filled]</p>";
			}

			if ($pass_the_form == "Yes")
			{
				// so, the form is validated
				$sql_title = make_db_safe($title);
				$sql_mlsimport = make_db_safe($mlsimport);
				$sql_notes = make_db_safe($notes);
				$sql_edit = make_db_safe($edit);
				$sql_owner = make_db_safe($owner);


				// update the listing data
				$sql = "UPDATE " . $config[table_prefix] . "listingsDB SET user_ID = $sql_owner, ";
				if ($featureListings == "yes")
				{
					// if the user can feature properties
					$sql_featured = make_db_safe($featured);
					$sql .= "featured = $sql_featured, ";
				} // end if ($featureListings == "yes")
				if ($admin_privs == "yes")
				{
					// if the user can feature properties
					$sql_active = make_db_safe($edit_active);
					$sql .= "active = $sql_active, ";
				} // end if ($admin_privs == "yes")
				if ($admin_privs == "yes" and $config[use_expiration] = "yes")
				{
					//$date_array = explode("-",$edit_expiration);
					//$exp_text = implode(",",$date_array);
					//$expiration_date  = mktime (0,0,0,$exp_text);
					$expiration_date = strtotime($edit_expiration);
					$sql .= "expiration = ".$conn->DBDate($expiration_date).",";
				}
				$sql .= "title = $sql_title, mlsimport = $sql_mlsimport, notes = $sql_notes, last_modified = ".$conn->DBTimeStamp(time())." WHERE (ID = $sql_edit)";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) 
				{
					log_error($sql);
				}

				//update the image data (in case the owner has changed)
				$sql = "UPDATE " . $config[table_prefix] . "listingsImages SET user_id = $sql_owner WHERE (listing_id = $sql_edit)";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) 
				{
					log_error($sql);
				}

				$message = updateListingsData($edit, $owner);
				if ($message == "success")
				{
					echo "<p>$lang[admin_listings_editor_listing_number] $edit $lang[has_been_updated] </p>";
					log_action ("$lang[log_updated_listing] $edit");
				} // end if
				else
				{
					echo "<p>$lang[alert_site_admin]</p>";
				} // end else
			} // end if $pass_the_form == "Yes"
		} // end else
	} // end if $action == "update listing"

	if ($edit != "")
	{
		// first, grab the listings's main info
		$sql = "SELECT ID, title, notes, user_ID, last_modified, featured, active, mlsimport, expiration FROM " . $config[table_prefix] . "listingsDB WHERE (ID = '$edit')";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			// collect up the main DB's various fields
			$listing_ID = make_db_unsafe ($recordSet->fields[ID]);
			$edit_title = make_db_unsafe ($recordSet->fields[title]);
			$edit_notes = make_db_unsafe ($recordSet->fields[notes]);
			$edit_mlsimport = make_db_unsafe ($recordSet->fields[mlsimport]);
			$edit_owner = $recordSet->fields[user_ID];
			$last_modified = $recordSet->UserTimeStamp($recordSet->fields[last_modified],'D M j G:i:s T Y');
			$edit_featured = $recordSet->fields[featured];
			$edit_active = $recordSet->fields[active];
			$expiration = $recordSet->UserTimeStamp($recordSet->fields[expiration],'Y-m-d');
			$formatted_expiration = $recordSet->UserTimeStamp($recordSet->fields[expiration],'D M j Y');

			$recordSet->MoveNext();
		} // end while

		// now, display all that stuff
	?>
		<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
			<tr>
				<td colspan="2" class="row_main">
					<h3><?php echo "$lang[admin_listings_editor_modify_listing]"?> (<a href="<?php echo "$config[baseurl]"."/listingview.php?listingID=$listing_ID";?>" target="_preview"><?php echo $lang[preview] ?></a>)</h3>
				<td>
			</tr>
			<tr>
				<td width="<?php echo $style[image_column_width] ?>" valign="top" align="center" class="row_main">
					<b><?php echo $lang[images] ?></b>
					<br>
					<hr width="75%">
<a href="edit_vtour_images.php?edit=<?php echo $edit ?>"><?php echo $lang[edit_vtour] ?></a>
					<hr width="75%">
					<a href="edit_listings_images.php?edit=<?php echo $edit ?>"><?php echo $lang[edit_images] ?></a><br><br>
	<?php
					$sql = "SELECT caption, file_name, thumb_file_name FROM " . $config[table_prefix] . "listingsImages WHERE (listing_id = '$edit')";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) 
					{
						log_error($sql);
					}

					$num_images = $recordSet->RecordCount();

					while (!$recordSet->EOF)
					{
						$caption = make_db_unsafe ($recordSet->fields[caption]);
						$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
						$file_name = make_db_unsafe ($recordSet->fields[file_name]);

						// gotta grab the image size
						$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$shrinkage = $config[thumbnail_width]/$imagewidth;
						$displaywidth = $imagewidth * $shrinkage;
						$displayheight = $imageheight * $shrinkage;

						echo "<a href=\"$config[listings_view_images_path]/$file_name\" target=\"_thumb\"> ";

						echo "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a><br> ";
						echo "<b>$caption</b><br><br>";
						$recordSet->MoveNext();
					} // end while
	?>
				</td>
				<td class="row_main">

				<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>">
					<form name="update_listing" action="<?php echo "$PHP_SELF";?>" method="post">
					<input type="hidden" name="action" value="update_listing">
					<input type="hidden" name="edit" value="<?php echo $edit ?>">


					<tr>
						<td align="right"><b><?php echo $lang[admin_listings_editor_title] ?>: <font color="red">*</font></b></td>
						<td align="left"> <input type="text" name="title" value="<?php echo $edit_title ?>"></td></tr>

	<?php	
					if ($featureListings == "yes")
					{
	?>
						<tr><td align="right"><b><?php echo $lang[admin_listings_editor_featured] ?>:</b></td><td align="left">
						<select name="featured" size="1">
							<option value="<?php echo $edit_featured ?>"><?php echo $edit_featured ?>
							<option value="">-----
							<option value="yes">yes
							<option value="no">no
						</select>
	<?php
					} // end if ($featureListings == "yes")
					if ($admin_privs == "yes")
					{
						?>
							<tr><td align="right"><b><?php echo $lang[admin_listings_active] ?>:</b></td><td align="left">
							<select name="edit_active" size="1">
								<option value="<?php echo $edit_active ?>"><?php echo $edit_active ?>
								<option value="">-----
								<option value="yes">yes
								<option value="no">no
							</select>
						<?php
					} // end if ($featureListings == "yes")
					if ($admin_privs == "yes" and $config[use_expiration] = "yes")
					{
						?>
							<tr><td align="right" class="row_main"><b><?php echo $lang[expiration] ?>:</b></td><td align="left"><input type="text" name="edit_expiration" value="<?php echo $expiration ?>">(Y-M-D)</td></tr>

						<?php
					} // end if ($admin_privs == "yes" and $config[use_expiration] = "yes")
						?>
						<tr><td align="right"><b><? echo $lang[admin_listings_editor_mlsimport]  ?>:</b></td><td align="left">
					<select name="mlsimport" size="1">
						<option value="<? echo $edit_mlsimport ?>"><? echo $edit_mlsimport ?>
						<option value="">-----
						<option value="yes">yes
						<option value="no">no
					</select>
					
					<tr><td align="right" class="row_main"><b><?php echo $lang[last_modifed] ?>:</b></td><td align="left"> <?php echo $last_modified ?></td></tr>

			<tr><td align="right"><b><?php echo $lang[user_editor_user_number] ?>:</b></td>
               <td align="left" class="row_main"><select name="owner" size="1">
			<?php
				// find the name of the agent listed as ID in $edit_owner
				$sql="SELECT user_name FROM " . $config[table_prefix] . "UserDB WHERE (ID = '$edit_owner')";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false)
				{
					log_error($sql);
				}

				//strip slashes so input appears correctly
				$edit_owner_name = make_db_unsafe ($recordSet->fields[user_name]);
				echo "<option value=\"$edit_owner\">$edit_owner_name</option>";
				// fill list with names of all agents
				$sql="SELECT ID, user_name FROM " . $config[table_prefix] . "UserDB where isAgent = 'yes' ORDER BY user_name";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false)
				{
					log_error($sql);
				}
				while (!$recordSet->EOF)
				{
					//strip slashes so input appears correctly
					$agent_ID = $recordSet->fields[ID];
					$agent_name = make_db_unsafe ($recordSet->fields[user_name]);
					echo "<option value=\"$agent_ID\">$agent_name</option>";
					$recordSet->MoveNext();
				}
				echo "</select></TD>";
			?>
               </tr>
					<tr><td align="right"><b><?php echo $lang[admin_listings_editor_notes] ?>:</b><br><div class="small">(<?php echo $lang[admin_listings_editor_notes_note] ?>)</div></td><td align="left"> <textarea name="notes" rows="6" cols="40"><?php echo $edit_notes ?></textarea></td></tr>

		<?php
	//	$sql = "SELECT listingsDBElements.field_name, listingsDBElements.field_value, listingsFormElements.field_type, listingsFormElements.field_caption, listingsFormElements.default_text, listingsFormElements.field_elements, listingsFormElements.required FROM listingsDBElements, listingsFormElements WHERE ((listingsDBElements.listing_id = '$edit') AND (listingsDBElements.field_name = listingsFormElements.field_name)) ORDER BY rank";
		$sql ="SELECT f.field_name, db.field_value, f.field_type, f.field_caption, f.default_text, f.field_elements, f.required FROM " . $config[table_prefix] . "listingsFormElements f left join " . $config[table_prefix] . "listingsDBElements db on db.field_name = f.field_name and db.listing_id = '$edit' ORDER BY rank";
		echo '<script src="../date.js"></script>';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
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
		}
		echo "<tr><td colspan=\"2\" align=\"center\">$lang[required_form_text]</td></tr>";

		echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"$lang[update_button]\"></td></tr></table></form>";

		?>
		</td></tr></table>
		<?php
	} // end if $edit != ""
	if ($edit == "")
	{
		// show all the listings
		echo "<h3>$lang[listings_editor]</h3>";
		echo "<table align=\"center\"><tr><td align=\"center\">Enter the Site ID Number Of the Listing You Want To Edit<form action=\"$config[baseurl]/admin/listings_edit.php?edit=$id\" method=\"get\" name=\"idform\"><input name=\"edit\" type=\"text\" id=\"listingID\" size=\"10\">&nbsp;&nbsp;<input type=\"submit\" value=\"EDIT LISTING\" class=\"button\" /></form></td></tr></table><br>";

		// grab the number of listings from the db
		$sql = "SELECT " . $config[table_prefix] . "listingsDB.ID, " . $config[table_prefix] . "listingsDB.Title, " . $config[table_prefix] . "listingsDB.mlsimport, " . $config[table_prefix] . "listingsDB.notes, " . $config[table_prefix] . "listingsDB.expiration, " . $config[table_prefix] . "listingsDB.active, " . $config[table_prefix] . "UserDB.emailAddress as email FROM " . $config[table_prefix] . "listingsDB, " . $config[table_prefix] . "UserDB where " . $config[table_prefix] . "listingsDB.user_ID = " . $config[table_prefix] . "UserDB.ID ORDER BY " . $config[table_prefix] . "listingsDB.ID ASC";
		//$sql = "SELECT listingsDB.ID, listingsDB.title, listingsDB.notes, listingsDB.expiration, listingsDB.active, userDB.emailAddress as email FROM listingsDB, userDB where user_ID = userDB.ID ORDER BY listingsDB.ID ASC";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) 
		{
			log_error($sql);
		}
		$num_rows = $recordSet->RecordCount();

		next_prev($num_rows, $cur_page, ""); // put in the next/previous stuff

		// build the string to select a certain number of listings per page
		$limit_str = $cur_page * $config[listings_per_page];
		$recordSet = $conn->SelectLimit($sql, $config[listings_per_page], $limit_str );
		if ($recordSet === false)
		{
			log_error($sql);
		}

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
			$title = make_db_unsafe ($recordSet->fields[Title]);
			$notes = make_db_unsafe ($recordSet->fields[notes]);
			$active = make_db_unsafe ($recordSet->fields[active]);
			$mlsimport = make_db_unsafe ($recordSet->fields[mlsimport]);
			$email = make_db_unsafe ($recordSet->fields[email]);
			$formatted_expiration = $recordSet->UserTimeStamp($recordSet->fields[expiration],'D M j Y');
			$ID = $recordSet->fields[ID];

		?>
			<table border="<?php echo $style[admin_listing_border] ?>" cellspacing="<?php echo $style[admin_listing_cellspacing] ?>" cellpadding="<?php echo $style[admin_listing_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
		<?php
			echo "<tr><td align=\"right\" width=\"200\" class=\"row1_$count\"><B><span class=\"adminListingLeft_$count\">$lang[admin_listings_editor_listing_number]: $ID</b></span></td><td align=\"center\" class=\"row2_$count\" width=\"310\"> <B> <a href=\"$PHP_SELF?edit=$ID\">$lang[admin_listings_editor_modify_listing]</a></b></td><td width=120 align=middle class=\"row2_$count\"><a href=\"$PHP_SELF?delete=$ID\" onClick=\"return confirmDelete()\">$lang[admin_listings_editor_delete_listing]</a></td></tr>";
			echo "<tr><td align=\"center\" valign=\"middle\" class=\"row3_$count\">$title";
			echo "</td><td class=\"row3_$count\">$notes";
			if ($config[use_expiration] == "yes")
			{
				echo "<br><br><b>$lang[expiration]</b>: $formatted_expiration";
			}
			if ($active != "yes")
			{
				echo "<br><font color=\"red\">$lang[this_listing_is_not_active]</font></b>";
			}
			echo "</td>";
			echo "<td class=\"row3_$count\">Agent: <a href=\"mailto:$email\">$email</a> </td></tr></table><br><br>\r\n\r\n";

include ("../footer.php"); 

			$recordSet->MoveNext();
		} // end while
	} // end if $edit == ""
	?>


	<P>
	</P>

<?php
	$conn->Close(); // close the db connection
?>
<?
xoops_cp_footer();
include("../../../footer.php");
?>