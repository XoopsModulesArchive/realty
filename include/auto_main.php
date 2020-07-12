<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* 		Open-Realty/Auto Modification © RealtyOne outback web creations		 */
/*			Page Based on Open-Realty 1.2.0 Unreleased © RealtyOne			 */
/* 	 Overall content based on Open-Realty © Ryan Bonham transparent tech	 */
/*	This mod and all attached files remain under the Open-Realty gpl Licence */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
global $config, $conn, $lang;

//the following configuration settings can be moved into common.php if wanted.
//they should be placed as a match to the corrosponding listing entrys
	$config['moderate_autos'] = "no";
	$config['max_auto_uploads'] = 7; // max # of pics for a given listing
	$config['max_auto_upload_size'] = '100000'; // (in bytes)
	$config['max_auto_upload_width'] = 700; // max width (in pixels)
	$config['auto_upload_path'] = $config['basepath'].'/images/auto_photos'; // leave off the trailing slash
	$config['auto_view_images_path'] = $config['baseurl'].'/images/auto_photos';
	$config['days_until_autos_expire'] = '365'; // Autos should be active for this number of days
	$config['email_notification_of_new_autos'] = 'yes'; // should the site admin receive an email notification if someone adds a listing? yes or no

//Language definitions for the Auto side of the site.
//these can be placed in your language file if you prefer.
global $listingID, $sender, $listing_title, $sender_view, $listing_url;
global $sender_time, $sender_phone, $sender_email, $comment;
global $autoID, $auto_title, $auto_url;

$lang['autos'] = "Vehicles";
$lang['auto'] = "Vehicle";
$lang['you_may_now_edit_your_auto'] = "You may now edit your Vehicle";
$lang['return_to_auto'] = "Return to Vehicle";
$lang['this_auto_has_been_viewed'] = "This Vehicle has been viewed";
$lang['featured_autos'] = "Featured Vehicles";
$lang['this_auto_is_not_yet_active'] = "This auto is not yet active";
$lang['this_auto_is_not_active'] = "This Vehicle is not active";
$lang['search_autos'] = "Search Vehicles";
$lang['Browse_All_autos'] = "Browse All Vehicles";

//SAVE SEARCH AND FAVORITES autoS LANGUAGE
$lang['auto_added_to_favorites'] = "The Vehicle has been added to your favorites list";
$lang['auto_already_in_favorites'] = "This Vehicle is already in your favorites list";
$lang['no_auto_in_favorites'] = "You have not added any Vehicles to your favorites yet";
$lang['favorite_autos'] = "Favorite Vehicles";
$lang['auto_deleted_from_favorites'] = "The Vehicle has been deleted from the favorites list";
$lang['new_auto_notify'] = "New Vehicle added to ";
$lang['new_auto_notify_long'] = "A new Vehicle has been added to your search ";
$lang['click_on_link_to_view_auto'] = "Please click on the link below to view the Vehicle:";

//MENU CHOICES -- CONNECTED TO THE USER TEMPLATE
$lang['menu_user_browse_autos'] = "Browse Vehicles";
$lang['menu_favorite_autos'] = "Favorite Vehicles";
$lang['menu_search_autos'] = "Search Vehicles";
$lang['auto_saved_searches'] = "Vehicle Searches";

//MENU CHOICE -- CONNECTED TO THE ADMIN TEMPLATE
$lang['admin_menu_edit_autos'] = "Edit Vehicles";
$lang['admin_menu_add_a_auto'] ="Add a Vehicle";
$lang['admin_menu_edit_my_edit_my_autos'] = "Edit My Vehicles";
$lang['admin_menu_edit_autos_template'] = "Edit Vehicle Template";
$lang['admin_menu_list_favorites'] = "Favorite Vehicles";
							
//USER EDITOR
$lang['user_editor_feature_autos'] = "Feature Vehicles";

//TEMPLATE EDITOR
$lang['admin_template_editor_autos_form_element'] = "Vehicle form element";
$lang['admin_template_editor_autos_name'] = "Vehicle Form Editor";

//NEW autoS
$lang['admin_new_auto_created'] = "Your Vehicle has been created";
$lang['admin_new_auto'] = "New Vehicle";
//language variables
$lang['admin_inactive_auto_queue'] = "InActive Vehicles";
$lang['admin_inactive_listings_queue'] = "InActive Listings";
$lang['admin_activate_listing'] = "Activate";
$lang['admin_listing_expired'] = "Expired On";
$lang['admin_listing_expires'] = "Expires On";

//autoS EDITOR
$lang['admin_autos_editor_auto_number'] = "Vehicle Number";
$lang['admin_autos_editor_modify_auto'] = "Modify Vehicle ";
$lang['admin_autos_editor_delete_auto'] = "Delete Vehicle ";
$lang['admin_autos_editor_featured'] = "Featured Vehicle ";
$lang['return_to_editing_auto'] = "Return to Editing Vehicle ";
$lang['autos_editor'] = "Vehicle  Editor";
$lang['admin_expired_auto_queue'] = "Expired Vehicles";
$lang['admin_auto_expired'] = "Vehicle Expired On";
$lang['admin_auto_expires'] = "Vehicle Will Expire On";
$lang['admin_auto_listing'] = "Activate Vehicle";

//LOGGING
$lang['log_created_auto'] = "Created Vehicle ";
$lang['log_updated_auto'] = "Updated Vehicle ";
$lang['log_deleted_auto'] = "Deleted Vehicle ";
$lang['log_updated_auto_image'] = "Updated Vehicle Image";
$lang['log_deleted_auto_image'] = "Deleted Vehicle Image";
$lang['log_uploaded_auto_image'] = "Uploaded Vehicle Image";
$lang['log_updated_auto_form_element'] = "Updated a Vehicle Form Element";
$lang['log_deleted_auto_form_element'] = "Deleted a Vehicle Form Element";
$lang['log_made_new_auto_form_element'] = "Made a New Vehicle Form Element";

//email_vehicle
$lang['email_auto_default_subject'] = "Vehicle from $sender";
$lang['email_auto_default_message'] = "Your friend, $sender, has sent along the following link:\r\n".$config['baseurl']."/autoview.php?autoID=".$autoID."\r\n\r\n".$comment;
$lang['email_auto_sent'] = "The vehicle has been sent to";
$lang['email_auto_send_vehicle_to_friend'] = "Send vehicle $auto to a friend...";

//appointment mod
$lang['appointment_send_header'] = "Arrange To View ";
$lang['appointment_your_name'] = "Your Name";
$lang['appointment_your_email'] = "Your Email";
$lang['appointment_your_phone'] = "Your Phone";
$lang['appointment_your_address'] = "Your Address";
$lang['appointment_listing_title'] = "Listing Title";
$lang['appointment_auto_title'] = "Vehicle Title";
$lang['appointment_your_view_date'] = "View Date";
$lang['appointment_listing_url_text'] = "Listing URL";
$lang['appointment_auto_url_text'] = "Vehicle URL";
$lang['appointment_listing_url'] = "$config[baseurl]/listingview.php?listingID=$listingID";
$lang['appointment_auto_url'] = "$config[baseurl]/autoview.php?autoID=$autoID";
$lang['appointment_your_message'] = "Your Message";
$lang['appointment_send'] = "Request Viewing";
$lang['appointment_your_view_time'] = "Suitable Time";
$lang['appointment_agents_email'] = "Agents Email";
$lang['appointment_listing_siteid'] = "Site ID";
$lang['appointment_sent'] = "The following Message was sent to";
$lang['appointment_default_subject'] = "Appointment Arangements";
$lang['appointment_default_message_listing'] = "\n$sender has been visiting the site and would like to arrange a viewing of $listing_title (# $listingID) for $sender_view at $sender_time.\n\nPlease contact $sender to verify the apointment on Phone Number $sender_phone or By Email at $sender_email.\n\n$sender also sent this extra message \n$comment\n\nYou can view the listing in question at this url\n$listing_url\n\n";
$lang['appointment_default_message_auto'] = "\n$sender has been visiting the site and would like to arrange a viewing of $auto_title (# $autoID) for $sender_view at $sender_time.\n\nPlease contact $sender to verify the apointment on Phone Number $sender_phone or By Email at $sender_email.\n\n$sender also sent this extra message \n$comment\n\nYou can view the vehicle in question at this url\n$auto_url\n\n";
//Failure Warnings
$lang['appointment_provide_email'] = "Failed to provide email";
$lang['appointment_enter_name'] = "Failed to provide name";
$lang['appointment_enter_email_address'] = "Failed to provide email address";
$lang['appointment_enter_phone'] = "Failed to provide phone";
$lang['appointment_enter_address'] = "Failed to provide address";
$lang['appointment_enter_view_date'] = "Failed to provide view_date";
$lang['appointment_enter_view_time'] = "Failed to provide view_time";
// end language code

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function browse_all_autos()
	{
		global $conn, $config, $lang;
		$sql = "SELECT ".$config['table_prefix']."autodb.Title FROM ".$config['table_prefix']."autodb WHERE active = 'yes'";
		if ($config['use_expiration'] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$num_autos = $recordSet->RecordCount();
		echo '<a href="auto_browse.php">'.$lang['Browse_All_autos'].' ('.$num_autos.')</a>';
	} // end function browse_all_listings
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function updateAutoData ($auto_id, $owner)
	{
		// UPDATES THE AUTO INFORMATION
		global $conn, $lang, $config;
		$sql_auto_id = make_db_safe($auto_id);
		$sql = "DELETE FROM ".$config['table_prefix']."autodbelements WHERE auto_id = $sql_auto_id";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		global $HTTP_POST_VARS;
		reset ($HTTP_POST_VARS);
		while (list($ElementIndexValue, $ElementContents) = each($HTTP_POST_VARS))
		{
			// first, ignore all the stuff that's been taken care of above
			if ($ElementIndexValue == "title")
			{
				// do nothing
			}
			elseif ($ElementIndexValue == "notes")
			{
				// do nothing
			}
			elseif ($ElementIndexValue == "action")
			{
				// do nothing
			}
			elseif ($ElementIndexValue == "PHPSESSID")
			{
				// do nothing
			}
			elseif ($ElementIndexValue == "edit")
			{
				// do nothing
			}
			elseif ($ElementIndexValue == "edit_active")
			{
				// do nothing
			}
			elseif ($ElementIndexValue == "edit_expiration")
			{
				// do nothing
			}
			elseif ($ElementIndexValue == "featured")
			{
				// do nothing
			}
			// this is currently set up to handle two feature lists
			// it could easily handle more...
			// just write handlers for 'em
			elseif (is_array($ElementContents))
			{
				// deal with checkboxes & multiple selects elements
				$feature_insert = "";

				while (list($featureValue, $feature_item) = each ($ElementContents))
				{
					$feature_insert = "$feature_insert||$feature_item";
				} // end while

				// now remove the first two characters
				$feature_insert_length = strlen($feature_insert);
				$feature_insert_length = $feature_insert_length - 2;
				$feature_insert = substr($feature_insert, 2, $feature_insert_length);
				$sql_ElementIndexValue = make_db_safe($ElementIndexValue);
				$sql_feature_insert = make_db_safe($feature_insert);
				$sql_owner = make_db_safe($owner);
				$sql = "INSERT INTO ".$config['table_prefix']."autodbelements (field_name, field_value, auto_id, user_id) VALUES ($sql_ElementIndexValue, $sql_feature_insert, $sql_auto_id, $sql_owner)";
				$recordSet = $conn->Execute($sql);
				if ($recordSet == false)
				{
				log_error($sql);
				}
			} // end elseif
			else
			{
				// process the form
				$sql_ElementIndexValue = make_db_safe($ElementIndexValue);
				$sql_ElementContents = make_db_safe($ElementContents);
				$sql_auto_id = make_db_safe($auto_id);
				$sql_owner = make_db_safe($owner);
				$sql = "INSERT INTO ".$config['table_prefix']."autodbelements (field_name, field_value, auto_id, user_id) VALUES ($sql_ElementIndexValue, $sql_ElementContents, $sql_auto_id, $sql_owner)";
				$recordSet = $conn->Execute($sql);
				if ($recordSet == false)
				{
					log_error($sql);
				}
			} // end else
		} // end while
		return "success";
	} // end function updateautoData
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
	function handleautoUpload($type,$edit,$owner)
	{
		// deals with incoming uploads
		global $HTTP_POST_FILES, $config, $conn, $lang, $userID, $max_upload, $caption;
		if (is_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name']))
		{
			$realname = strtolower($HTTP_POST_FILES['userfile']['name']);
			$filename = $HTTP_POST_FILES['userfile']['tmp_name'];

			print "<!-- $filename was uploaded successfully -->";
			$filetype = $HTTP_POST_FILES['userfile']['type'];
			print "<!-- type is $filetype -->";
			// checking the filetype to make sure it's what we had in mind
			$pass_the_upload = "true";
			if (!in_array($HTTP_POST_FILES['userfile']['type'],$config['allowed_upload_types']))
			{
				$pass_the_upload = "$realname $lang[upload_is_an_invalid_file_type]: $filetype";
			}

			// check size
			$filesize=$HTTP_POST_FILES['userfile']['size'];
			if ($max_upload!=0 && $filesize>$config['max_upload'])
			{
				$pass_the_upload = "$lang[upload_too_large].";
			}
			// check file name
		if (!ereg("^[-0-9A-Za-z._]+[-0-9A-Za-z.]+[A-Za-z]{2,3}$", $realname)) 
		{
        echo "<p>InValid Email Address</p>";
		echo "<FORM><INPUT TYPE=\"BUTTON\" VALUE=\"$lang[back_button_text]\" onClick=\"history.back()\"></FORM>";
    	}

			// check file extensions
			$extension = substr(strrchr($realname,"."),1);
			// invalid extension
			if (!in_array($extension,$config['allowed_upload_extensions']))
			{
				$pass_the_upload = "$lang[upload_invalid_extension] ($extension).";
			}


			//security error
			if (strstr($HTTP_POST_FILES['userfile']['name'],"..")!="")
			{
				$pass_the_upload = "$lang[upload_security_violation]!";
			}


			//make sure the file hasn't already been uploaded...
			if ($type == "autos")
			{
				$save_name = "$edit"."_"."$realname";
				$sql = "SELECT file_name FROM ".$config['table_prefix']."autoimages WHERE file_name = '$save_name'";
			}
			 

			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$num = $recordSet->RecordCount();
			if ($num > 0)
			{
				$pass_the_upload = "$lang[file_exists]!";
			}

			if ($pass_the_upload == "true")
			{
				// the upload has passed the tests!
				if ($type == "autos")
				{
					// if it's a auto pic we're dealing with...
					$check_size="";
					// move the file so we can check the width
					move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'],"$config[auto_upload_path]/$save_name");
					$imagesize = filesize("$config[auto_upload_path]/$save_name");
					if ($imagesize == "" || $imagesize > $config['max_auto_upload_size'])
					{
						$check_size="$lang[upload_too_large].";
						if(!unlink("$config[auto_upload_path]/$save_name"))
						{
							DIE ("Can't delete the file!");
						}
						DIE ("$check_size");
					}
	//removed some user stuff here so we need a few closers
				}
						}	move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'],"$config[auto_upload_path]/$save_name");

					// check width
					$check_width="";
					$imagedata = GetImageSize("$config[auto_upload_path]/$save_name");
					$imagewidth = $imagedata[0];
					$imageheight = $imagedata[1];
					if ($imagewidth == "" || $imagewidth < 2 || $imagewidth > $config['max_auto_upload_width'])
					{
						$check_width = "$lang[upload_too_wide].";
						if (!unlink("$config[auto_upload_path]/$save_name"))
						{
							DIE ("Can't delete the file!");
						}
					}
					if ($check_width == "")
					{
						// assuming the image passes the width check...
						$thumb_name = $save_name; // by default -- no difference... unless...
						if ($config['make_thumbnail'] == "yes")
						{
							// if the option to make a thumbnail is activated...
							include ("$config[path_to_thumbnailer]");
							$thumb_name = make_thumb ($save_name, $config['auto_upload_path']);
						} // end if $config[make_thumbnail] == "yes"
						$caption = make_db_safe($caption);
						$sql = "INSERT INTO ".$config['table_prefix']."autoimages (auto_id, user_id, file_name, thumb_file_name) VALUES ('$edit', '$owner', '$save_name', '$thumb_name')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false)
						{
							log_error($sql);
						}
						log_action ("$lang[log_uploaded_auto_image] $save_name");
					} // end if ($check_width != "")
				} // end if $type == "autos"
	else
		{
			echo $lang['upload_attack'].': filename' .
			$HTTP_POST_FILES['userfile']['name'].'';
		}
	} // end function handleotherUpload
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
	function checkautoActive($autoID)
	{
		// checks whether a given auto is active
		global $conn, $lang, $userID, $admin_privs, $config;
		$show_auto = "yes";
		$sql_autoID = make_db_safe($autoID);
		$sql = "SELECT active, user_ID FROM ".$config['table_prefix']."autodb WHERE ID = $sql_autoID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$is_active = $recordSet->fields['active'];
			$user_ID = $recordSet->fields['user_ID'];
			$recordSet->MoveNext();
		} // end while
		if ($is_active != "yes")
		{
			// if the auto isn't active
			if ($userID != $user_ID || $admin_privs != "yes")
			{
				// if this isn't a specific user's auto or the user
				// isn't an admin
				echo $lang['this_auto_is_not_yet_active'];
				$show_auto = "no";
			} // end if ($userID != $user_ID || $admin_privs != "yes")
		} // end if ($is_active != "yes")
		if ($config['use_expiration'] == "yes")
		{
			$sql = "SELECT expiration FROM ".$config['table_prefix']."autodb WHERE ((ID = $sql_autoID) AND (".$config['table_prefix']."autodb.expiration > ".$conn->DBDate(time())."))";
			$recordSet = $conn->Execute($sql);
				if ($recordSet === false) log_error($sql);
			$num = $recordSet->RecordCount();
			if ($num == 0)
			{
				if ($userID != $user_ID || $admin_privs != "yes")
				{
					// if this isn't a specific user's auto or the user
					// isn't an admin
					echo $lang['this_auto_is_not_yet_active'];
					$show_auto = "no";
				} // end if ($userID != $user_ID || $admin_privs != "yes")
			} // end if($num == 0)
		} // end if ($config[use_expiration] == "yes")

		return $show_auto;
	} // end function checkautoActive
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
	function getMainautoData($autoID)
	{
		// get the main data for a given auto
		global $conn, $config, $lang, $auto_Title;
		global $auto_user_ID, $auto_user_name;
		$autoID = make_db_extra_safe($autoID);
		$sql = "SELECT ".$config['table_prefix']."autodb.user_ID, ".$config['table_prefix']."autodb.Title, ".$config['table_prefix']."autodb.expiration, ".$config['table_prefix']."UserDB.user_name FROM ".$config['table_prefix']."autodb, ".$config['table_prefix']."UserDB WHERE ((".$config['table_prefix']."autodb.ID = $autoID) AND (".$config['table_prefix']."UserDB.ID = ".$config['table_prefix']."autodb.user_ID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main autos data
		while (!$recordSet->EOF)
		{
			$auto_user_ID = make_db_unsafe ($recordSet->fields['user_ID']);
			$auto_Title = make_db_unsafe ($recordSet->fields['Title']);
			$auto_expiration = make_db_unsafe ($recordSet->fields['expiration']);
			$auto_user_name = make_db_unsafe ($recordSet->fields['user_name']);
			$recordSet->MoveNext();
		} // end while
		echo '<h3>'.$auto_Title.'</h3>
		<h4>'.$lang['listed_by'].' <a href="'.$config['baseurl'].'/userview.php?user='.$auto_user_ID.'">'.$auto_user_name.'</a></h4>';
	} // function getMainAutoData
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
	function renderSingleautoItemRaw($autoID, $name)
	{
		// renders a single item without any fancy formatting or anything.
		// useful if you need to plug a variable into something else...

		global $conn, $config;
		$autoID = make_db_extra_safe($autoID);
		$name = make_db_extra_safe($name);
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autoformelements WHERE ((".$config['table_prefix']."autodbelements.auto_id = $autoID) AND (".$config['table_prefix']."autoformelements.field_name = ".$config['table_prefix']."autodbelements.field_name) AND (".$config['table_prefix']."autodbelements.field_name = $name))";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields['field_value']);
			echo $field_value;
			$recordSet->MoveNext();
		}
	} // end renderSingleautoItemRaw($autoID, $name)
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
	function renderautoTemplateAreaNoCaption($templateArea, $autoID)
	{
		// renders all the elements in a given template area on the auto pages
		// this time without the corresponding captions
		global $conn, $config;
		$autoID = make_db_extra_safe($autoID);
		$templateArea = make_db_extra_safe($templateArea);
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autoformelements.field_type, ".$config['table_prefix']."autoformelements.field_caption FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autoformelements WHERE ((".$config['table_prefix']."autodbelements.auto_id = $autoID) AND (".$config['table_prefix']."autoformelements.field_name = ".$config['table_prefix']."autodbelements.field_name) AND (".$config['table_prefix']."autoformelements.location = $templateArea)) ORDER BY ".$config['table_prefix']."autoformelements.rank ASC";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields['field_value']);
			$field_type = make_db_unsafe ($recordSet->fields['field_type']);
			$field_caption = make_db_unsafe ($recordSet->fields['field_caption']);
			if ($field_value != "")
			{
				if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
				{
					// handle field types with multiple options
					$feature_index_list = explode("||", $field_value);
					while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
					{

						echo "$feature_list_item<br>";
					} // end while
				} // end if field type is a multiple type
				elseif ($field_type == "price")
				{
					$money_amount = international_num_format($field_value);
					echo "<br><b>$field_caption</b>: ".money_formats($money_amount);
				} // end elseif
				elseif ($field_type == "number")
				{
					echo "<br><b>$field_caption</b>: ".international_num_format($field_value,0);
				} // end elseif
				elseif ($field_type == "url")
				{
					echo "<br><a href=\"$field_value\" target=\"_new\">$field_value</a>";
				}
				elseif ($field_type == "email")
				{
					echo "<br><a href=\"mailto:$field_value\">$field_value</a>";
				}
				elseif ($field_type == "text" OR $field_type == "textarea")
				{
					if ($config['add_linefeeds'] == "yes")
					{
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					echo "<br>$field_value";
				}
				else
				{
					echo "<br>$field_value";
				} // end else

			} // end if ($field_value != "")

			$recordSet->MoveNext();
		} // end while
	} // end renderTemplateAreaNoCaption
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	function renderautoimages($autoID)
	{
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$autoID = make_db_extra_safe($autoID);
		$sql = "SELECT ID, caption, file_name, thumb_file_name FROM ".$config['table_prefix']."autoimages WHERE (auto_id = $autoID) ORDER BY rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
			echo "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";

			echo "<b>$lang[images]</b><br><hr width=\"75%\">";
			while (!$recordSet->EOF)
			{
				$caption = make_db_unsafe ($recordSet->fields['caption']);
				$thumb_file_name = make_db_unsafe ($recordSet->fields['thumb_file_name']);
				$file_name = make_db_unsafe ($recordSet->fields['file_name']);
				$imageID = make_db_unsafe ($recordSet->fields['ID']);

				// gotta grab the image size
				$imagedata = GetImageSize("$config[auto_upload_path]/$thumb_file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$shrinkage = $config['thumbnail_width']/$imagewidth;
				$displaywidth = $imagewidth * $shrinkage;
				$displayheight = $imageheight * $shrinkage;

				echo "<a href=\"$config[baseurl]/viewautoimage.php?imageID=$imageID&amp;type=auto\"> ";

				echo "<img src=\"$config[auto_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$thumb_file_name\"></a><br> ";
				echo "<b>$caption</b><br><br>";
				$recordSet->MoveNext();
			} // end while
			echo "</td>";
		} // end if ($num_images > 0)
	} // end function renderAutosImages
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
	function renderautoTemplateArea($templateArea, $autoID)
	{
		// renders all the elements in a given template area on the auto pages
		global $conn, $config;
		$autoID = make_db_extra_safe($autoID);
		$templateArea = make_db_extra_safe($templateArea);
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autoformelements.field_type, ".$config['table_prefix']."autoformelements.field_caption FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autoformelements WHERE ((".$config['table_prefix']."autodbelements.auto_id = $autoID) AND (".$config['table_prefix']."autoformelements.field_name = ".$config['table_prefix']."autodbelements.field_name) AND (".$config['table_prefix']."autoformelements.location = $templateArea)) ORDER BY ".$config['table_prefix']."autoformelements.rank ASC";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields['field_value']);
			$field_type = make_db_unsafe ($recordSet->fields['field_type']);
			$field_caption = make_db_unsafe ($recordSet->fields['field_caption']);
			if ($field_value != "")
			{
				if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
				{
					// handle field types with multiple options
					echo "<b>$field_caption</b><br>";
					$feature_index_list = explode("||", $field_value);
					while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
					{

						echo "$feature_list_item<br>";
					} // end while
				} // end if field type is a multiple type
				elseif ($field_type == "price")
				{
					$money_amount = international_num_format($field_value);
					echo "<br><b>$field_caption</b>: ".money_formats($money_amount);
				} // end elseif
				elseif ($field_type == "number")
				{
					echo "<br><b>$field_caption</b>: ".international_num_format($field_value,0);
				} // end elseif
				elseif ($field_type == "url")
				{
					echo "<br><b>$field_caption</b>: <a href=\"$field_value\" target=\"_new\">$field_value</a>";
				}
				elseif ($field_type == "email")
				{
					echo "<br><b>$field_caption</b>: <a href=\"mailto:$field_value\">$field_value</a>";
				}
				elseif ($field_type == "text" OR $field_type == "textarea")
				{
					if ($config['add_linefeeds'] == "yes")
					{
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					echo "<br><b>$field_caption</b>: $field_value";
				}
				else
				{
					echo "<br><b>$field_caption</b>: $field_value";
				} // end else
			} // end if ($field_value != "")
			$recordSet->MoveNext();
		} // end while
	} // end renderTemplateArea
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	function userautos($user)
	{
		// produces the rest of the autos for users

		global $conn, $lang, $config;
		$user = make_db_extra_safe($user);
		echo "<b>Other Vehicles from this user:</b><ul>";
		$sql = "SELECT ID, Title FROM ".$config['table_prefix']."autodb WHERE user_ID = $user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$ID = $recordSet->fields['ID'];
			$Title = make_db_unsafe ($recordSet->fields['Title']);
			echo "<li> <a href=\"$config[baseurl]/autoview.php?autoID=$ID\">$Title</a></li>";
			$recordSet->MoveNext();
		}
		echo "</ul>";
	} // end function userAutos
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
function getautoEmail($autoID)
	{
		// get the email address for the person who posted a listing
		global $conn, $lang, $config;
		$autoID = make_db_extra_safe($autoID);
		$sql = "SELECT ".$config['table_prefix']."UserDB.emailAddress FROM ".$config['table_prefix']."autodb, ".$config['table_prefix']."UserDB WHERE ((".$config['table_prefix']."autodb.ID = $autoID) AND (".$config['table_prefix']."UserDB.ID = ".$config['table_prefix']."autodb.user_ID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		// return the email address
		while (!$recordSet->EOF)
		{
			$auto_emailAddress = make_db_unsafe ($recordSet->fields['emailAddress']);
			$recordSet->MoveNext();
		} // end while
		echo "<b>$lang[user_email]:</b> <a href=\"mailto:$auto_emailAddress\">$auto_emailAddress</a><br>";
	} // getautoEmail($autoID)
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
	function autohitcount($autoID)
	{
		// counts hits to a given listing
		global $conn, $lang, $config;
		$autoID = make_db_extra_safe($autoID);
		$sql = "UPDATE ".$config['table_prefix']."autodb SET hitcount=hitcount+1 WHERE ID=$autoID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$sql = "SELECT hitcount FROM ".$config['table_prefix']."autodb WHERE ID=$autoID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$hitcount = $recordSet->fields['hitcount'];
			echo "$lang[this_listing_has_been_viewed] <b>$hitcount</b> $lang[times].";
			$recordSet->MoveNext();
		} // end while
	} // end function hitcount
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
	function renderUserPicOnautoPage($autoID)
	{
		if ($autoID != "")
		{
			// grabs the information for a given user
			// and displays it on a listings page

			global $conn, $config, $lang;

			$autoID = make_db_extra_safe($autoID);
			$sql = "SELECT ".$config['table_prefix']."UserDB.ID, ".$config['table_prefix']."UserDB.user_name FROM ".$config['table_prefix']."autodb, ".$config['table_prefix']."UserDB WHERE ((".$config['table_prefix']."autodb.ID = $autoID) AND (".$config['table_prefix']."UserDB.ID = ".$config['table_prefix']."autodb.user_ID))";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}

			// get main listings data
			while (!$recordSet->EOF)
			{
				$auto_user_ID = make_db_unsafe ($recordSet->fields['ID']);
				$auto_user_name = make_db_unsafe ($recordSet->fields['user_name']);
				$recordSet->MoveNext();
			} // end while

			$user = $auto_user_ID;
			// grab the images
			$sql = "SELECT ID, caption, file_name, thumb_file_name FROM ".$config['table_prefix']."userImages WHERE (user_id = $user) ORDER BY rank";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$num_images = $recordSet->RecordCount();
			if ($num_images > 0)
			{
				echo "<table><td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
					$caption = make_db_unsafe ($recordSet->fields['caption']);
					$thumb_file_name = make_db_unsafe ($recordSet->fields['thumb_file_name']);
					$file_name = make_db_unsafe ($recordSet->fields['file_name']);
					$imageID = make_db_unsafe ($recordSet->fields['ID']);

					// gotta grab the image size
					$imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
					$imagewidth = $imagedata[0];
					$imageheight = $imagedata[1];
					$shrinkage = $config['thumbnail_width']/$imagewidth;
					$displaywidth = $imagewidth * $shrinkage;
					$displayheight = $imageheight * $shrinkage;

					echo "<a href=\"$config[baseurl]/viewimage.php?imageID=$imageID&type=userimage\"> ";
					echo "<img src=\"$config[user_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a><br> ";
					echo "<b>$caption</b><br><br>";
				echo "</td></table>";
			} // end ($num_images > 0)
		}
	}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
function renderUserInfoOnautoPage($autoID)
	{
		if ($autoID != "")
		{
			// grabs the information for a given user
			// and displays it on a listings page

			global $conn, $config, $lang;

			$autoID = make_db_extra_safe($autoID);
			$sql = "SELECT ".$config['table_prefix']."UserDB.ID, ".$config['table_prefix']."UserDB.user_name FROM ".$config['table_prefix']."autodb, ".$config['table_prefix']."UserDB WHERE ((".$config['table_prefix']."autodb.ID = $autoID) AND (".$config['table_prefix']."UserDB.ID = ".$config['table_prefix']."autodb.user_ID))";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}

			// get main auto data
			while (!$recordSet->EOF)
			{
				$auto_user_ID = make_db_unsafe ($recordSet->fields['ID']);
				$auto_user_name = make_db_unsafe ($recordSet->fields['user_name']);
				$recordSet->MoveNext();
			} // end while
			echo '<b>'.$lang['listed_by'].' <a href="'.$config['baseurl'].'/userview.php?user='.$auto_user_ID.'">'.$auto_user_name.'</a></b>';

			if ($auto_user_ID != "")
			{
				$sql = "SELECT ".$config['table_prefix']."UserDBElements.field_value, ".$config['table_prefix']."agentFormElements.field_type, ".$config['table_prefix']."agentFormElements.field_caption FROM ".$config['table_prefix']."UserDBElements, ".$config['table_prefix']."agentFormElements WHERE ((".$config['table_prefix']."UserDBElements.user_id = $auto_user_ID) AND (".$config['table_prefix']."UserDBElements.field_name = ".$config['table_prefix']."agentFormElements.field_name)) ORDER BY ".$config['table_prefix']."agentFormElements.rank ASC";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false)
				{
					log_error($sql);
				}
				while (!$recordSet->EOF)
				{
					$field_value = make_db_unsafe ($recordSet->fields['field_value']);
					$field_type = make_db_unsafe ($recordSet->fields['field_type']);
					$field_caption = make_db_unsafe ($recordSet->fields['field_caption']);
					if ($field_value != "")
					{

						if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
						{
							// handle field types with multiple options
							echo "<b>$field_caption</b><br>";
							$feature_index_list = explode("||", $field_value);
							while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
							{
								echo "$feature_list_item<br>";
							} // end while
						} // end if field type is a multiple type

						elseif ($field_type == "price")
						{
							$money_amount = international_num_format($field_value);
				echo '<br><b>'.$field_caption.'</b>: '.money_formats($money_amount);
						} // end elseif
						elseif ($field_type == "number")
						{
				echo '<br><b>'.$field_caption.'</b>: '.international_num_format($field_value);
						} // end elseif
						elseif ($field_type == "url")
						{
				echo '<br><b>'.$field_caption.'</b>: <a href="'.$field_value.'" target="_new">'.$field_value.'</a>';
						}
						elseif ($field_type == "email")
						{
				echo '<br><b>'.$field_caption.'</b>: <a href="mailto:'.$field_value.'">'.$field_value.'</a>';
						}
						else
						{
							if ($config['add_linefeeds'] == "yes")
							{
								$field_value = nl2br($field_value); //replace returns with <br />
							} // end if
				echo '<br><b>'.$field_caption.'</b>: '.$field_value;
						} // end else

					} // end if ($field_value != "")

						$recordSet->MoveNext();
				} // end while
			} // end if ($auto_user_ID != "")
		} // end ($autoID != "")
	} // end renderUserInfo
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																			*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */	
	function renderFeaturedautosVertical($num_of_autos)
	{
		echo "<table><tr>";
		// shows the images connected to a given image

		global $conn, $lang, $config, $style, $autoID;
		// grab the images
		$autoID = make_db_extra_safe($autoID);
		$sql = "SELECT ID, Title FROM ".$config['table_prefix']."autodb WHERE (featured = 'yes')";
		$recordSet = $conn->SelectLimit($sql, $num_of_autos, 0 );
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$returned_num_autos = $recordSet->RecordCount();
		if ($returned_num_autos > 0)
			{
			echo "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
			echo "<b>$lang[featured_autos]</b><br><hr width=\"75%\">";
			while (!$recordSet->EOF)
				{
					$Title = make_db_unsafe ($recordSet->fields['Title']);
					$ID = make_db_unsafe ($recordSet->fields['ID']);

					$sql2 = "SELECT thumb_file_name FROM ".$config['table_prefix']."autoimages WHERE (auto_id = $ID) ORDER BY rank";
					$recordSet2 = $conn->SelectLimit($sql2, 1, 0 );
					if ($recordSet2 === false)
					{
						log_error($sql);
					}
					while (!$recordSet2->EOF)
					{
						$thumb_file_name = make_db_unsafe ($recordSet2->fields['thumb_file_name']);

						// gotta grab the image size
						$imagedata = GetImageSize("$config[auto_upload_path]/$thumb_file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$shrinkage = $config['thumbnail_width']/$imagewidth;
						$displaywidth = $imagewidth * $shrinkage;
						$displayheight = $imageheight * $shrinkage;

						echo '<a href="'.$config['baseurl'].'/autoview.php?autoID='.$ID.'">
						<img src="'.$config['auto_view_images_path'].'/'.$thumb_file_name.'" height="'.$displayheight.'" width="'.$displaywidth.'" alt="'.$lang['click_to_learn_more'].'">
							<br> 
						<b>'.$Title.'</b></a><br><br>';
						
						$recordSet2->MoveNext();
					} // end while
					$recordSet->MoveNext();
				} // end while
			echo "</td>";
		} // end if ($num_images > 0)
		echo "</tr></table>";
	} // end function renderFeaturedautosVertical
	
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	/*									SEARCH BOXES								*/
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	function autosearch_select ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a multiple choice select box for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\"><select name=\"$browse_field_name"."[]\" size=\"5\" multiple>";
		if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autodb.ID, count(field_value) AS num_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autodb WHERE ".$config['table_prefix']."autodbelements.field_name = '$browse_field_name' AND ".$config['table_prefix']."autodb.active = 'yes' AND ".$config['table_prefix']."autodbelements.auto_id = ".$config['table_prefix']."autodb.ID ". $rental_str;
		if ($config['use_expiration'] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY ".$config['table_prefix']."autodbelements.field_value ORDER BY ".$config['table_prefix']."autodbelements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields['field_value']);
			$num_type = $recordSet->fields[num_type];
			echo "<option  value=\"$field_output\">$field_output ($num_type)</option>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_select

	function autosearch_select_vert ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a multiple choice select box for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\"><select name=\"$browse_field_name"."[]\" size=\"5\" multiple>";
				if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autodb.ID, count(field_value) AS num_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autodb WHERE ".$config['table_prefix']."autodbelements.field_name = '$browse_field_name' AND ".$config['table_prefix']."autodb.active = 'yes' AND ".$config['table_prefix']."autodbelements.auto_id = ".$config['table_prefix']."autodb.ID ".$rental_str;
		if ($config['use_expiration'] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY ".$config['table_prefix']."autodbelements.field_value ORDER BY ".$config['table_prefix']."autodbelements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields['field_value']);
			$num_type = $recordSet->fields[num_type];
			echo "<option value=\"$field_output\">$field_output ($num_type)";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr></table>";
		} // end function searchbox_select_vert

	function autosearch_pulldown ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\"><select name=\"$browse_field_name\"><option></option>";
				if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autodb.ID, count(field_value) AS num_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autodb WHERE ".$config['table_prefix']."autodbelements.field_name = '$browse_field_name' AND ".$config['table_prefix']."autodb.active = 'yes' AND ".$config['table_prefix']."autodbelements.field_value <> '' AND ".$config['table_prefix']."autodbelements.auto_id = ".$config['table_prefix']."autodb.ID ".$rental_str;
		if ($config['use_expiration'] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY ".$config['table_prefix']."autodbelements.field_value ORDER BY ".$config['table_prefix']."autodbelements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields['field_value']);
			$num_type = $recordSet->fields[num_type];
			echo "<option  value=\"$field_output\">$field_output ($num_type)";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function autosearch_pulldown

	function autosearch_pulldown_vert ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\"><select name=\"$browse_field_name\"><option></option>";
				if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autodb.ID, count(field_value) AS num_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autodb WHERE ".$config['table_prefix']."autodbelements.field_name = '$browse_field_name' AND ".$config['table_prefix']."autodb.active = 'yes' AND ".$config['table_prefix']."autodbelements.field_value <> '' AND ".$config['table_prefix']."autodbelements.auto_id = ".$config['table_prefix']."autodb.ID ".$rental_str;
		if ($config['use_expiration'] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY ".$config['table_prefix']."autodbelements.field_value ORDER BY ".$config['table_prefix']."autodbelements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields['field_value']);
			$num_type = $recordSet->fields[num_type];
			echo "<option value=\"$field_output\">$field_output ($num_type)";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function autosearch_pulldown_vert


	function autosearch_checkbox ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a series of checkboxes for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\">";
				if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autodb.ID, count(field_value) AS num_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autodb WHERE ".$config['table_prefix']."autodbelements.field_name = '$browse_field_name' AND ".$config['table_prefix']."autodb.active = 'yes' AND ".$config['table_prefix']."autodbelements.auto_id = ".$config['table_prefix']."autodb.ID ".$rental_str;
		if ($config['use_expiration'] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY ".$config['table_prefix']."autodbelements.field_value ORDER BY ".$config['table_prefix']."autodbelements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields['field_value']);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"checkbox\" name=\"$browse_field_name"."[]\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function autosearch_checkbox

	function autosearch_checkbox_vert ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a series of checkboxes for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\">";
				if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autodb.ID, count(field_value) AS num_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autodb WHERE ".$config['table_prefix']."autodbelements.field_name = '$browse_field_name' AND ".$config['table_prefix']."autodb.active = 'yes' AND ".$config['table_prefix']."autodbelements.auto_id = ".$config['table_prefix']."autodb.ID ".$rental_str;
		if ($config['use_expiration'] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY ".$config['table_prefix']."autodbelements.field_value ORDER BY ".$config['table_prefix']."autodbelements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields['field_value']);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"checkbox\" name=\"$browse_field_name"."[]\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr></table>";
	} // end function autosearch_checkbox_vert

	function autosearch_option ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\">";
		if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autodb.ID, count(field_value) AS num_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autodb WHERE ".$config['table_prefix']."autodbelements.field_name = '$browse_field_name' AND ".$config['table_prefix']."autodb.active = 'yes' AND ".$config['table_prefix']."autodbelements.auto_id = ".$config['table_prefix']."autodb.ID ".$rental_str;
		if ($config['use_expiration'] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY ".$config['table_prefix']."autodbelements.field_value ORDER BY ".$config['table_prefix']."autodbelements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields['field_value']);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"radio\" name=\"$browse_field_name\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function autosearch_option


	function autosearch_option_vert ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\">";
				if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang[rental]."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autodb.ID, count(field_value) AS num_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autodb WHERE ".$config['table_prefix']."autodbelements.field_name = '$browse_field_name' AND ".$config['table_prefix']."autodb.active = 'yes' AND ".$config['table_prefix']."autodbelements.auto_id = ".$config['table_prefix']."autodb.ID ".$rental_str;
		if ($config['use_expiration'] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY ".$config['table_prefix']."autodbelements.field_value ORDER BY ".$config['table_prefix']."autodbelements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields['field_value']);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"radio\" name=\"$browse_field_name\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr></table>";
	} // end function autosearch_option_vert

	function autosearch_minmax ($browse_caption, $browse_field_name, $rental = "no")
   {
      $minmax_start_time = getmicrotime();
      // builds a min/max combo box
      // to let users search by
      global $conn, $config, $lang;
      echo '<tr><td align="right"><b>'.$browse_caption.'</b> ';
      echo '</td><td align="left">';
      $sql = "SELECT field_type, search_step FROM ".$config['table_prefix']."autoformelements WHERE field_name = '$browse_field_name'";
      $rsStepLookup = $conn->Execute($sql);
      if ($rsStepLookup === false)
      {
         log_error($sql);
      }
      // Get max, min and step
      $step = $rsStepLookup->fields['search_step'];
      $field_type = $rsStepLookup->fields['field_type'];
      if ($field_type == 'price')
      {
         $price = True;
      }
      else
      {
         $price = False;
      }
      $sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value <> '".$lang['rental']."'";
      $recordSet = $conn->Execute($sql);
      if ($recordSet === false)
      {
         log_error($sql);
      }
      $rental_str = ' AND ' . $config['table_prefix'] . 'autodb.ID IN (';
      $count = 0;

      while (!$recordSet->EOF)
      {
            if ($count != 0)
            $rental_str .= ', ';
            $rental_str .= $recordSet->fields['auto_id'];
            $recordSet->MoveNext(); 
            $count++;   
      }
      $rental_str .= ') ';
      $max = $conn->GetOne("select min(field_value +0) from ".$config['table_prefix']."autodbelements INNER JOIN ".$config['table_prefix']."autodb ON ".$config['table_prefix']."autodbelements.auto_id=".$config['table_prefix']."autodb.ID where field_name = '$browse_field_name'".$rental_str);
      
      //$max = $conn->GetOne("select min(field_value +0) from " . $config[table_prefix] . "autodbelements, " . $config[table_prefix] . "autodb where field_name = '$browse_field_name'".$rental_str);
      if ($price == True)
      {
         $min = round($conn->GetOne("select max(field_value +0) from ".$config['table_prefix']."autodbelements INNER JOIN ".$config['table_prefix']."autodb ON ".$config['table_prefix']."autodbelements.auto_id=".$config['table_prefix']."autodb.ID where field_name = '$browse_field_name'".$rental_str), -3);

         //$min = round($conn->GetOne("select max(field_value +0) from " . $config[table_prefix] . "autodbelements, " . $config[table_prefix] . "autodb where field_name = '$browse_field_name'".$rental_str), -3);
      }
      else
      {
         $min = $conn->GetOne("select max(field_value +0) from ".$config['table_prefix']."autodbelements INNER JOIN ".$config['table_prefix']."autodb ON ".$config['table_prefix']."autodbelements.auto_id=".$config['table_prefix']."autodb.ID where field_name = '$browse_field_name'");
         //$min = $conn->GetOne("select max(field_value +0) from " . $config[table_prefix] . "autodbelements, " . $config[table_prefix] . "autodb where field_name = '$browse_field_name'".$rental_str);
      }
      if ($min > $max)
      {
         $temp = $min;
         $min = $max;
         $max = $temp;
      }
      //$max = $max + $step;
      echo ''.$lang['from'].' <select name="'.$browse_field_name.'-min">';
      $options = '<option></option>';
      if ($price == True)
      {
         for ($i = $min; $i <= $max; $i += $step)
         {
            $z = international_num_format($i);
            $z = money_formats($z);
            if ($i + $step > $max)
            {
               $i = $max;
               $z = international_num_format($i);
               $z = money_formats($z);
               $options .= '<option value='.$i.'>'.$z.'</option>';
            }
            else
            {
               $options .= '<option value='.$i.'>'.$z.'</option>';
            }
         }
      }
      else
      {
         for ($i = $min; $i <= $max; $i += $step)
         {
            if ($i + $step > $max)
            {
               $i = $max;
               $options .= '<option>'.$i.'</option>';
            }
            else
            {
               $options .= '<option>'.$i.'</option>';
            }
         }
      }
      $options .= '</select>';
      echo $options;
      echo ' '.$lang['to'].' <select name="'.$browse_field_name.'-max">'.$options;
      echo '</td></tr>';
   } // end function 



	function autosearch_daterange ($caption, $field, $rental = "no")
	{
		global $conn, $config, $lang;
		static $js_added;
		if (!$js_added)
		{
			// add date
			echo '<script src="date.js"></script>';
			$js_added = true;
		}
		echo "<tr><td align=\"right\"><b>$caption</b></td>\n\t<td align=\"left\">";
		echo "$lang[from] <input type=\"text\" name=\"{$field}-mindate\" onKeyUp=\"Javascript: dateMask(this,event);\"> <BR>$lang[to]
			<input type=\"text\" name=\"{$field}-maxdate\" onKeyUp=\"Javascript: dateMask(this,event);\">";
		echo "\n\t</td>\n</tr>\n";
	}// end autosearch_daterange

	function autosearch_optionlist ($caption, $field, $rental = "no")
	{
		global $conn, $config, $lang;
		// start the row
		echo "<tr><td align=\"right\"><b>$caption</b></td>";
		echo "<td align=\"left\">";
		if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		echo '<select name="'.$field.'[]" multiple size=6>';
		$r = $conn->getOne("select f.field_elements from ".$config['table_prefix']."autoformelements f where field_name = '$field'".$rental_str);
		foreach (explode('||', $r) as $f) 
		{
			$f = htmlspecialchars($f);
			echo "<option>$f</option>";
		}
		echo "</select></td></tr>";
	}// end autosearch_optionlist

	function autosearch_fcheckbox ($caption, $field, $rental = "no")
	{
		global $conn, $config, $lang;
		// start the row
		echo "<tr>";
		echo "<td align=\"right\"><b>$caption</b></td>";
		echo "<td align=\"left\"><table>";
		echo "<tr><td align=\"left\">";
		if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$r = $conn->getOne("select f.field_elements from ".$config['table_prefix']."autoformelements f where field_name = '$field'".$rental_str);
		foreach (explode('||', $r) as $f) 
		{
			$f = htmlspecialchars($f);
			echo "<input type=\"checkbox\" name=\"{$field}[]\" value=\"$f\">$f<br>";
		}
		echo "</td></tr></table></td></tr>";
	}//end autosearch_fcheckbox

	function autosearch_fpulldown ($caption, $field, $rental = "no")
	{
		global $conn, $config, $lang;
		// start the row
		echo "<tr>";
		echo "<td align=\"left\">";
		echo "<table><tr><td align=\"center\" valign=\"top\"><b>$caption</b></td></tr>";
		echo "<tr><td align=\"left\">";
		if ($rental == "yes")
		{
			$sql = "SELECT auto_id FROM ".$config['table_prefix']."autodbelements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND ".$config['table_prefix']."autodb.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields['auto_id'];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		echo '<select name="'.$field.'[]">';
		$r = $conn->getOne("select f.field_elements from ".$config['table_prefix']."autoformelements f where field_name = '$field'".$rental_str);
		foreach (explode('||', $r) as $f) 
		{
			$f = htmlspecialchars($f);
			echo "<option>$f</option>";
		}
		echo "</select></td></tr></table></td></tr>";
	} // end autosearch_fpulldown
	?>
