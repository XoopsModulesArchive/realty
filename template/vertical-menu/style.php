<?php
	// style defs
	// generic style by jon roig (jon@jonroig.com)

	$style = array();


	// THE STYLE FOR ALL THE FORMS
	$style['form_cellpadding'] = "3";
	$style['form_cellspacing'] = "0";
	$style['form_border'] = "0";


	// ADMIN STYLES
	$style['admin_table_width'] = "580";
	$style['image_column_width'] = "120";

	$style['admin_listing_cellpadding'] = "3";
	$style['admin_listing_cellspacing'] = "3";
	$style['admin_listing_border'] = "1";


	// USER PAGE RENDERING STYLES
	$style['left_right_table_width'] = "530";
	$style['left_right_table_cellpadding'] = "0";
	$style['left_right_table_cellspacing'] = "0";
	$style['left_right_table_border'] = "0";

	$style['feature_table_width'] = "530";
	$style['feature_table_cellpadding'] = "0";
	$style['feature_table_cellspacing'] = "0";
	$style['feature_table_border'] = "0";

	// RENDER THE USERS PAGE ELEMENTS
	function getAllUsersData()
	{
		// grabs the main info for a given user
		global $conn, $lang, $config;

		$user = make_db_extra_safe($user);
		$sql = "SELECT user_name, emailAddress, ID FROM " . $config[table_prefix] . "UserDB where isAdmin = 'No' and isAgent = 'Yes' order by user_name";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main listings data
		?>
			<table border="0" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main" align="center">
		<?php
		while (!$recordSet->EOF)
		{
			$name = make_db_unsafe ($recordSet->fields[user_name]);
			$userID = make_db_unsafe ($recordSet->fields[ID]);
			$emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
			?>
				<tr>
					<td>
						<?php
							renderUserImages($userID);
						?>
					<td>
					<td>
						<?php
							echo"<br><h3><a href=\"$config[baseurl]/userview.php?user=$userID\">$name</a></h3>";
							echo"<p><b>Email:</b> <a href=\"mailto:$emailAddress\">$emailAddress</a>";
							renderUserInfo($userID);
							echo"</p>";
						?>
					</td>
				</tr>
			<?php
			$recordSet->MoveNext();
		} // end while
		echo"</table>";
	} // function getAllUsersData

	// RENDER THE LISTINGS PAGE ELEMENTS
	function renderListingsMainImage($listingID)
	{
		// shows the main image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT ID, caption, file_name FROM " . $config[table_prefix] . "listingsImages WHERE (listing_id = $listingID) ORDER BY rank LIMIT 0,1";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
			while (!$recordSet->EOF)
			{
				$caption = make_db_unsafe ($recordSet->fields[caption]);
				$file_name = make_db_unsafe ($recordSet->fields[file_name]);
				// $imageID = make_db_unsafe ($recordSet->fields[ID]);

				// gotta grab the image size
				$imagedata = GetImageSize("$config[listings_upload_path]/$file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$shrinkage = $config[img_width]/$imagewidth;
				// $displaywidth = $imagewidth * $shrinkage;
				// $displayheight = $imageheight * $shrinkage;
				$displaywidth = $imagewidth;
				$displayheight = $imageheight;

				// echo "<center><img src=\"$config[listings_view_images_path]/$file_name\" height=\"$displayheight\" width=\"$displaywidth\"></center><br> ";
				echo "<center><img src=\"$config[listings_view_images_path]/$file_name\" height=\"325\" width=\"400\"></center><br> ";
				if ($caption) 
				{
					echo "<b>$caption</b><br>";
				}
				$recordSet->MoveNext();
			} // end while
		} // end if ($num_images > 0)
	} // end function renderListingsMainImage 

	function renderListingsImages($listingID)
	{
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT ID, caption, file_name, thumb_file_name FROM " . $config[table_prefix] . "listingsImages WHERE (listing_id = $listingID) ORDER BY rank";
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
				$caption = make_db_unsafe ($recordSet->fields[caption]);
				$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
				$file_name = make_db_unsafe ($recordSet->fields[file_name]);
				$imageID = make_db_unsafe ($recordSet->fields[ID]);

				// gotta grab the image size
				$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$shrinkage = $config[thumbnail_width]/$imagewidth;
				$displaywidth = $imagewidth * $shrinkage;
				$displayheight = $imageheight * $shrinkage;

				echo "<a href=\"viewimage.php?imageID=$imageID&amp;type=listing\"> ";

				echo "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$thumb_file_name\"></a><br> ";
				echo "<b>$caption</b><br><br>";
				$recordSet->MoveNext();
			} // end while
			echo "</td>";
		} // end if ($num_images > 0)
	} // end function renderListingsImages

  

function renderSingleListingItem($listingID, $name)
	{
		// renders a single item on the listings page
		// includes the caption

		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$name = make_db_extra_safe($name);
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_type, " . $config[table_prefix] . "listingsFormElements.field_caption FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $listingID) AND (" . $config[table_prefix] . "listingsFormElements.field_name = " . $config[table_prefix] . "listingsDBElements.field_name) AND (" . $config[table_prefix] . "listingsDBElements.field_name = $name))";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
			if ($field_value != "")
			{

				if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox")
				{
					// handle field types with multiple options
					echo "<br><b>$field_caption</b>";
					$feature_index_list = explode("||", $field_value);
					while (list($feature_list_Value, $feature_list_item) = each ($feature_index_list))
					{

						echo "<br>$feature_list_item";
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
					if ($config[add_linefeeds] == "yes")
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
	} // end renderSingleListingItem

	function renderSingleListingItemRaw($listingID, $name)
	{
		// renders a single item without any fancy formatting or anything.
		// useful if you need to plug a variable into something else...

		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$name = make_db_extra_safe($name);
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $listingID) AND (" . $config[table_prefix] . "listingsFormElements.field_name = " . $config[table_prefix] . "listingsDBElements.field_name) AND (" . $config[table_prefix] . "listingsDBElements.field_name = $name))";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			echo $field_value;
			$recordSet->MoveNext();
		}
	} // end renderSingleListingItemRaw($listingID, $name)

	function renderSingleListingItemNoCaption($listingID, $name)
	{
		// renders a single item on the listings page
		// this time, without a caption, though...

		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$name = make_db_extra_safe($name);
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $listingID) AND (" . $config[table_prefix] . "listingsFormElements.field_name = " . $config[table_prefix] . "listingsDBElements.field_name) AND (" . $config[table_prefix] . "listingsDBElements.field_name = $name))";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
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
					$Rentalsql = "SELECT field_value FROM " . $config[table_prefix] . "listingsDBElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $listingID) and (field_name = 'type'))";
					$RentalrecordSet = $conn->Execute($Rentalsql);
					if ($RentalrecordSet === false)
					{
						log_error($Rentalsql);
					}
					$prop_type = make_db_unsafe ($RentalrecordSet->fields[field_value]);
					if ($prop_type == 'Rental')
					{
						$money_amount = international_num_format($field_value);
						echo "<br><b>$field_caption $lang[per_week]</b>: ".money_formats($money_amount);
					}
					else
					{
					$money_amount = international_num_format($field_value);
					echo "<br><b>$field_caption</b>: ".money_formats($money_amount);
					}
				} // end elseif
				elseif ($field_type == "number")
				{
					echo "<br><b>$field_caption</b>: ".international_num_format($field_value,0);
				} // end elseif
				elseif ($field_type == "url")
				{
					echo "<a href=\"$field_value\" target=\"_new\">$field_value</a>";
				}
				elseif ($field_type == "email")
				{
					echo "<a href=\"mailto:$field_value\">$field_value</a>";
				}
				elseif ($field_type == "text" OR $field_type == "textarea")
				{
					if ($config[add_linefeeds] == "yes")
					{
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					echo $field_value;
				}
				else
				{
					echo "$field_value";
				} // end else
			} // end if ($field_value != "")
			$recordSet->MoveNext();
		} // end while
	} // end renderSingleListingItemNoCaption


	function renderTemplateArea($templateArea, $listingID)
	{
		// renders all the elements in a given template area on the listing pages
		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$templateArea = make_db_extra_safe($templateArea);
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_type, " . $config[table_prefix] . "listingsFormElements.field_caption FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $listingID) AND (" . $config[table_prefix] . "listingsFormElements.field_name = " . $config[table_prefix] . "listingsDBElements.field_name) AND (" . $config[table_prefix] . "listingsFormElements.location = $templateArea)) ORDER BY " . $config[table_prefix] . "listingsFormElements.rank ASC";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
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
					if ($config[add_linefeeds] == "yes")
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





	function renderTemplateAreaNoCaption($templateArea, $listingID)
	{
		// renders all the elements in a given template area on the listing pages
		// this time without the corresponding captions
		global $conn, $config;
		$listingID = make_db_extra_safe($listingID);
		$templateArea = make_db_extra_safe($templateArea);
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_type, " . $config[table_prefix] . "listingsFormElements.field_caption FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $listingID) AND (" . $config[table_prefix] . "listingsFormElements.field_name = " . $config[table_prefix] . "listingsDBElements.field_name) AND (" . $config[table_prefix] . "listingsFormElements.location = $templateArea)) ORDER BY " . $config[table_prefix] . "listingsFormElements.rank ASC";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
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
					if ($config[add_linefeeds] == "yes")
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


	function getMainListingData($listingID)
	{
		// get the main data for a given listing
		global $conn, $config, $lang;
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT " . $config[table_prefix] . "listingsDB.user_ID, " . $config[table_prefix] . "listingsDB.Title, " . $config[table_prefix] . "listingsDB.expiration, " . $config[table_prefix] . "UserDB.user_name FROM " . $config[table_prefix] . "listingsDB, " . $config[table_prefix] . "UserDB WHERE ((" . $config[table_prefix] . "listingsDB.ID = $listingID) AND (" . $config[table_prefix] . "UserDB.ID = " . $config[table_prefix] . "listingsDB.user_ID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main listings data
		while (!$recordSet->EOF)
		{
			$listing_user_ID = make_db_unsafe ($recordSet->fields[user_ID]);
			$listing_Title = make_db_unsafe ($recordSet->fields[Title]);
			$listing_expiration = make_db_unsafe ($recordSet->fields[Title]);
			$listing_user_name = make_db_unsafe ($recordSet->fields[user_name]);
			$recordSet->MoveNext();
		} // end while
		echo "<h3>$listing_Title</h3>";
		echo "<h4>$lang[listed_by] <a href=\"userview.php?user=$listing_user_ID\">$listing_user_name</a></h4>";
	} // function getMainListingData

	function getListingEmail($listingID)
	{
		// get the email address for the person who posted a listing
		global $conn, $lang, $config;
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT " . $config[table_prefix] . "UserDB.emailAddress FROM " . $config[table_prefix] . "listingsDB, " . $config[table_prefix] . "UserDB WHERE ((" . $config[table_prefix] . "listingsDB.ID = $listingID) AND (" . $config[table_prefix] . "UserDB.ID = " . $config[table_prefix] . "listingsDB.user_ID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		// return the email address
		while (!$recordSet->EOF)
		{
			$listing_emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
			$recordSet->MoveNext();
		} // end while
		echo "<b>$lang[user_email]:</b> <a href=\"mailto:$listing_emailAddress\">$listing_emailAddress</a><br>";
	} // function getMainListingData

	function hitcount($listingID)
	{
		// counts hits to a given listing
		global $conn, $lang, $config;
		$listingID = make_db_extra_safe($listingID);
		$sql = "UPDATE " . $config[table_prefix] . "listingsDB SET hitcount=hitcount+1 WHERE ID=$listingID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$sql = "SELECT hitcount FROM " . $config[table_prefix] . "listingsDB WHERE ID=$listingID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$hitcount = $recordSet->fields[hitcount];
			echo "$lang[this_listing_has_been_viewed] <b>$hitcount</b> $lang[times].";
			$recordSet->MoveNext();
		} // end while
	} // end function hitcount

	function renderUserPicOnListingsPage($listingID)
	{
		if ($listingID != "")
		{
			// grabs the information for a given user
			// and displays it on a listings page

			global $conn, $config, $lang;

			$listingID = make_db_extra_safe($listingID);
			$sql = "SELECT " . $config[table_prefix] . "UserDB.ID, " . $config[table_prefix] . "UserDB.user_name FROM " . $config[table_prefix] . "listingsDB, " . $config[table_prefix] . "UserDB WHERE ((" . $config[table_prefix] . "listingsDB.ID = $listingID) AND (" . $config[table_prefix] . "UserDB.ID = " . $config[table_prefix] . "listingsDB.user_ID))";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}

			// get main listings data
			while (!$recordSet->EOF)
			{
				$listing_user_ID = make_db_unsafe ($recordSet->fields[ID]);
				$listing_user_name = make_db_unsafe ($recordSet->fields[user_name]);
				$recordSet->MoveNext();
			} // end while

			$user = $listing_user_ID;
			// grab the images
			$sql = "SELECT ID, caption, file_name, thumb_file_name FROM " . $config[table_prefix] . "userImages WHERE (user_id = $user) ORDER BY rank";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$num_images = $recordSet->RecordCount();
			if ($num_images > 0)
			{
				echo "<table><td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
					$caption = make_db_unsafe ($recordSet->fields[caption]);
					$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
					$file_name = make_db_unsafe ($recordSet->fields[file_name]);
					$imageID = make_db_unsafe ($recordSet->fields[ID]);

					// gotta grab the image size
					$imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
					$imagewidth = $imagedata[0];
					$imageheight = $imagedata[1];
					$shrinkage = $config[thumbnail_width]/$imagewidth;
					$displaywidth = $imagewidth * $shrinkage;
					$displayheight = $imageheight * $shrinkage;

					echo "<a href=\"viewimage.php?imageID=$imageID&type=userimage\"> ";
					echo "<img src=\"$config[user_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a><br> ";
					echo "<b>$caption</b><br><br>";
				echo "</td></table>";
			} // end ($num_images > 0)
		}
	}

	function renderUserInfoOnListingsPage($listingID)
	{
		if ($listingID != "")
		{
			// grabs the information for a given user
			// and displays it on a listings page

			global $conn, $config, $lang;

			$listingID = make_db_extra_safe($listingID);
			$sql = "SELECT " . $config[table_prefix] . "UserDB.ID, " . $config[table_prefix] . "UserDB.user_name FROM " . $config[table_prefix] . "listingsDB, " . $config[table_prefix] . "UserDB WHERE ((" . $config[table_prefix] . "listingsDB.ID = $listingID) AND (" . $config[table_prefix] . "UserDB.ID = " . $config[table_prefix] . "listingsDB.user_ID))";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}

			// get main listings data
			while (!$recordSet->EOF)
			{
				$listing_user_ID = make_db_unsafe ($recordSet->fields[ID]);
				$listing_user_name = make_db_unsafe ($recordSet->fields[user_name]);
				$recordSet->MoveNext();
			} // end while
			echo "<b>$lang[listed_by] <a href=\"userview.php?user=$listing_user_ID\">$listing_user_name</a></b>";

			if ($listing_user_ID != "")
			{
				$sql = "SELECT " . $config[table_prefix] . "UserDBElements.field_value, " . $config[table_prefix] . "agentFormElements.field_type, " . $config[table_prefix] . "agentFormElements.field_caption FROM " . $config[table_prefix] . "UserDBElements, " . $config[table_prefix] . "agentFormElements WHERE ((" . $config[table_prefix] . "UserDBElements.user_id = $listing_user_ID) AND (" . $config[table_prefix] . "UserDBElements.field_name = " . $config[table_prefix] . "agentFormElements.field_name)) ORDER BY " . $config[table_prefix] . "agentFormElements.rank ASC";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false)
				{
					log_error($sql);
				}
				while (!$recordSet->EOF)
				{
					$field_value = make_db_unsafe ($recordSet->fields[field_value]);
					$field_type = make_db_unsafe ($recordSet->fields[field_type]);
					$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
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
							echo "<br><b>$field_caption</b>: ".international_num_format($field_value);
						} // end elseif
						elseif ($field_type == "url")
						{
						echo "<br><b>$field_caption</b>: <a href=\"$field_value\" target=\"_new\">$field_value</a>";
						}
						elseif ($field_type == "email")
						{
							echo "<br><b>$field_caption</b>: <a href=\"mailto:$field_value\">$field_value</a>";
						}
						else
						{
							if ($config[add_linefeeds] == "yes")
							{
								$field_value = nl2br($field_value); //replace returns with <br />
							} // end if
							echo "<br><b>$field_caption</b>: $field_value";
						} // end else

					} // end if ($field_value != "")

						$recordSet->MoveNext();
				} // end while
			} // end if ($listing_user_ID != "")
		} // end ($listingID != "")
	} // end renderUserInfo



	function renderFeaturedListingsVertical($num_of_listings)
	{
		echo "<table><tr>";
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT ID, Title FROM " . $config[table_prefix] . "listingsDB WHERE (featured = 'yes')";
		$recordSet = $conn->SelectLimit($sql, $num_of_listings, 0 );
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$returned_num_listings = $recordSet->RecordCount();
		if ($returned_num_listings > 0)
			{
			echo "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
			echo "<b>$lang[featured_listings]</b><br><hr width=\"75%\">";
			while (!$recordSet->EOF)
				{
					$Title = make_db_unsafe ($recordSet->fields[Title]);
					$ID = make_db_unsafe ($recordSet->fields[ID]);

					$sql2 = "SELECT thumb_file_name FROM " . $config[table_prefix] . "listingsImages WHERE (listing_id = $ID) ORDER BY rank";
					$recordSet2 = $conn->SelectLimit($sql2, 1, 0 );
					if ($recordSet2 === false)
					{
						log_error($sql);
					}
					while (!$recordSet2->EOF)
					{
						$thumb_file_name = make_db_unsafe ($recordSet2->fields[thumb_file_name]);

						// gotta grab the image size
						$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$shrinkage = $config[thumbnail_width]/$imagewidth;
						$displaywidth = $imagewidth * $shrinkage;
						$displayheight = $imageheight * $shrinkage;

						echo "<a href=\"listingview.php?listingID=$ID\"> ";

						echo "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$lang[click_to_learn_more]\"><br> ";
						echo "<b>$Title</b></a><br><br>";
						$recordSet2->MoveNext();
					} // end while
					$recordSet->MoveNext();
				} // end while
			echo "</td>";
		} // end if ($num_images > 0)
		echo "</tr></table>";
	} // end function renderFeaturedListingsVertical



	// RENDER THE USER PAGE ELEMENTS


	function renderUserImages($user)
	{
		// grabs the listings for a given user
		global $conn, $lang, $config, $style;
		$user = make_db_extra_safe($user);
		// grab the images
		$sql = "SELECT ID, caption, file_name, thumb_file_name FROM " . $config[table_prefix] . "userImages WHERE (user_id = $user) ORDER BY rank";
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
				$caption = make_db_unsafe ($recordSet->fields[caption]);
				$thumb_file_name = make_db_unsafe ($recordSet->fields[thumb_file_name]);
				$file_name = make_db_unsafe ($recordSet->fields[file_name]);
				$imageID = make_db_unsafe ($recordSet->fields[ID]);

				// gotta grab the image size
				$imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$shrinkage = $config[thumbnail_width]/$imagewidth;
				$displaywidth = $imagewidth * $shrinkage;
				$displayheight = $imageheight * $shrinkage;

				echo "<a href=\"viewimage.php?imageID=$imageID&type=userimage\"> ";
				echo "<img src=\"$config[user_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a><br> ";
				echo "<b>$caption</b><br><br>";
				$recordSet->MoveNext();
			} 
			echo "</td>";
		}
		else
		{
			echo "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
			echo "<b>$lang[images]</b><br><hr width=\"75%\">";
			echo "<img src=\"images/nophoto.gif\"><br>";
			echo "</td>";
		}// end ($num_images > 0)
	} // end function renderUserImages



	function renderUserInfo($user)
	{
		// grabs the information for a given user
		global $conn, $config;
		$user = make_db_extra_safe($user);
		$sql = "SELECT isAgent FROM " . $config[table_prefix] . "UserDB where ID = $user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$edit_isAgent = make_db_unsafe ($recordSet->fields[isAgent]);
		if ($edit_isAgent == "yes")
		{
			$formDB = 'agentFormElements';
		}
		else
		{
			$formDB = 'memberFormElements';
		}
		$sql = "SELECT " . $config[table_prefix] . "UserDBElements.field_value, " . $config[table_prefix] . "$formDB.field_type, " . $config[table_prefix] . "$formDB.field_caption FROM " . $config[table_prefix] . "UserDBElements, " . $config[table_prefix] . "$formDB WHERE ((" . $config[table_prefix] . "UserDBElements.user_id = $user) AND (" . $config[table_prefix] . "UserDBElements.field_name = " . $config[table_prefix] . "$formDB.field_name)) ORDER BY " . $config[table_prefix] . "$formDB.rank ASC";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_value = make_db_unsafe ($recordSet->fields[field_value]);
			$field_type = make_db_unsafe ($recordSet->fields[field_type]);
			$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
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
				} // end if field type is a multiple typ
				elseif ($field_type == "price")
				{
					$money_amount = international_num_format($field_value);
					echo "<br><b>$field_caption</b>: ".money_formats($money_amount);
				} // end elseif
				elseif ($field_type == "number")
				{
					echo "<br><b>$field_caption</b>: ".international_num_format($field_value);
				} // end elseif
				elseif ($field_type == "url")
				{
					echo "<br><b>$field_caption</b>: <a href=\"$field_value\" target=\"_new\">$field_value</a>";
				}
				elseif ($field_type == "email")
				{
					echo "<br><b>$field_caption</b>: <a href=\"mailto:$field_value\">$field_value</a>";
				}
				else
				{
					if ($config[add_linefeeds] == "yes")
					{
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					echo "<br><b>$field_caption</b>: $field_value";
				} // end else

			} // end if ($field_value != "")

			$recordSet->MoveNext();
		} // end while
	} // end renderUserInfo




	function getMainUserData($user)
	{
		// grabs the main info for a given user

		global $conn, $lang, $config;

		$user = make_db_extra_safe($user);
		$sql = "SELECT user_name, emailAddress FROM " . $config[table_prefix] . "UserDB WHERE (ID = $user)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main listings data
		while (!$recordSet->EOF)
		{
			$name = make_db_unsafe ($recordSet->fields[user_name]);
			$emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
			$recordSet->MoveNext();
		} // end while
		echo "<h3>$name</h3>";
	} // function getMainListingData

	function getUserEmail($user)
	{
		// grabs the main info for a given user

		global $conn, $lang, $config;

		$user = make_db_extra_safe($user);
		$sql = "SELECT emailAddress FROM " . $config[table_prefix] . "UserDB WHERE (ID = $user)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main listings data
		while (!$recordSet->EOF)
		{
			$emailAddress = make_db_unsafe ($recordSet->fields[emailAddress]);
			$recordSet->MoveNext();
		} // end while
		echo "<b>$lang[user_email]:</b> <a href=\"mailto:$emailAddress\">$emailAddress</a>";
	} // function getUserEmail
	
	function getUserLink($user)
	{
		// grabs the main info for a given user
		
		global $conn, $lang;
		
		$user = make_db_extra_safe($user);
		$sql = "SELECT user_name, isAgent, ID FROM ".$config['table_prefix']."UserDB WHERE (ID = $user)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		
		// get main listings data
		while (!$recordSet->EOF)
		{
			$user_name = make_db_unsafe ($recordSet->fields[user_name]);
			$isAgent = make_db_unsafe ($recordSet->fields[isAgent]);
			$ID = make_db_unsafe ($recordSet->fields[ID]);
			$recordSet->MoveNext();
		} // end while

		if ($isAgent != "no")
		{
			echo "<b>$lang[listed_by] <a href=\"userview.php?user=$ID\">$user_name</a></b>";
		}
	} // function getUserLink

	function userHitcount($user)
	{
		// hit counter for user listings

		global $conn, $lang, $config;
		$user = make_db_extra_safe($user);
		$sql = "UPDATE " . $config[table_prefix] . "UserDB SET hitcount=hitcount+1 WHERE ID=$user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$sql = "SELECT hitcount FROM " . $config[table_prefix] . "UserDB WHERE ID=$user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$hitcount = $recordSet->fields[hitcount];
			echo "$lang[this_user_has_been_viewed] <b>$hitcount</b> $lang[times].";
			$recordSet->MoveNext();
		} // end while
	} // end function userHitcount



	function userListings($user)
	{
		// produces the rest of the listings for users

		global $conn, $lang, $config;
		$user = make_db_extra_safe($user);
		echo "<b>Other listings from this user:</b><ul>";
		$sql = "SELECT ID, Title FROM " . $config[table_prefix] . "listingsDB WHERE user_ID = $user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$ID = $recordSet->fields[ID];
			$Title = make_db_unsafe ($recordSet->fields[Title]);
			echo "<li> <a href=\"listingview.php?listingID=$ID\">$Title</a></li>";
			$recordSet->MoveNext();
		}
		echo "</ul>";
	} // end function userListings





	// BROWSING PAGE ELEMENTS
	function browse_all_rentals()
	{
		global $conn, $config, $lang;
		$sql = "SELECT " . $config[table_prefix] . "listingsDB.Title FROM " . $config[table_prefix] . "listingsDB, " . $config[table_prefix] . "listingsDBElements WHERE (" . $config[table_prefix] . "listingsDB.ID = " . $config[table_prefix] . "listingsDBElements.listing_id) AND (" . $config[table_prefix] . "listingsDB.active = 'yes') AND (" . $config[table_prefix] . "listingsDBElements.field_name='type') AND (" . $config[table_prefix] . "listingsDBElements.field_value = '".$lang['rental']."')";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$num_listings = $recordSet->RecordCount();
		echo "<a href=\"listing_browse.php?type=Rental\">".$lang['browse_all_rentals']." ($num_listings)</a>";
	} // end function browse_all_listings

	function browse_all_listings()
	{
		global $conn, $config;
		$sql = "SELECT " . $config[table_prefix] . "listingsDB.Title FROM " . $config[table_prefix] . "listingsDB WHERE active = 'yes'";
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$num_listings = $recordSet->RecordCount();
		echo "<a href=\"listing_browse.php\">Browse All Listings ($num_listings)</a>";
	} // end function browse_all_listings

	function searchbox_select ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a multiple choice select box for any given item you want

		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\"><select name=\"$browse_field_name"."[]\" size=\"5\" multiple>";
		if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsDB.ID, count(field_value) AS num_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB WHERE " . $config[table_prefix] . "listingsDBElements.field_name = '$browse_field_name' AND " . $config[table_prefix] . "listingsDB.active = 'yes' AND " . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID ". $rental_str;
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY " . $config[table_prefix] . "listingsDBElements.field_value ORDER BY " . $config[table_prefix] . "listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<option  value=\"$field_output\">$field_output ($num_type)</option>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_select

	function searchbox_select_vert ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a multiple choice select box for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\"><select name=\"$browse_field_name"."[]\" size=\"5\" multiple>";
				if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsDB.ID, count(field_value) AS num_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB WHERE " . $config[table_prefix] . "listingsDBElements.field_name = '$browse_field_name' AND " . $config[table_prefix] . "listingsDB.active = 'yes' AND " . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID ".$rental_str;
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY " . $config[table_prefix] . "listingsDBElements.field_value ORDER BY " . $config[table_prefix] . "listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<option value=\"$field_output\">$field_output ($num_type)";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr></table>";
		} // end function searchbox_select_vert

	function searchbox_pulldown ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\"><select name=\"$browse_field_name\"><option></option>";
				if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsDB.ID, count(field_value) AS num_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB WHERE " . $config[table_prefix] . "listingsDBElements.field_name = '$browse_field_name' AND " . $config[table_prefix] . "listingsDB.active = 'yes' AND " . $config[table_prefix] . "listingsDBElements.field_value <> '' AND " . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID ".$rental_str;
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY " . $config[table_prefix] . "listingsDBElements.field_value ORDER BY " . $config[table_prefix] . "listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<option  value=\"$field_output\">$field_output ($num_type)";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_pulldown

	function searchbox_pulldown_vert ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\"><select name=\"$browse_field_name\"><option></option>";
				if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsDB.ID, count(field_value) AS num_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB WHERE " . $config[table_prefix] . "listingsDBElements.field_name = '$browse_field_name' AND " . $config[table_prefix] . "listingsDB.active = 'yes' AND " . $config[table_prefix] . "listingsDBElements.field_value <> '' AND " . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID ".$rental_str;
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY " . $config[table_prefix] . "listingsDBElements.field_value ORDER BY " . $config[table_prefix] . "listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<option value=\"$field_output\">$field_output ($num_type)";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_pulldown_vert


	function searchbox_checkbox ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a series of checkboxes for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\">";
				if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsDB.ID, count(field_value) AS num_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB WHERE " . $config[table_prefix] . "listingsDBElements.field_name = '$browse_field_name' AND " . $config[table_prefix] . "listingsDB.active = 'yes' AND " . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID ".$rental_str;
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY " . $config[table_prefix] . "listingsDBElements.field_value ORDER BY " . $config[table_prefix] . "listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"checkbox\" name=\"$browse_field_name"."[]\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_checkbox

	function searchbox_checkbox_vert ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a series of checkboxes for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\">";
				if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsDB.ID, count(field_value) AS num_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB WHERE " . $config[table_prefix] . "listingsDBElements.field_name = '$browse_field_name' AND " . $config[table_prefix] . "listingsDB.active = 'yes' AND " . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID ".$rental_str;
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY " . $config[table_prefix] . "listingsDBElements.field_value ORDER BY " . $config[table_prefix] . "listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"checkbox\" name=\"$browse_field_name"."[]\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr></table>";
	} // end function searchbox_checkbox_vert

	function searchbox_option ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption</b></td>";
		echo "<td align=\"left\">";
		if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsDB.ID, count(field_value) AS num_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB WHERE " . $config[table_prefix] . "listingsDBElements.field_name = '$browse_field_name' AND " . $config[table_prefix] . "listingsDB.active = 'yes' AND " . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID ".$rental_str;
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY " . $config[table_prefix] . "listingsDBElements.field_value ORDER BY " . $config[table_prefix] . "listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"radio\" name=\"$browse_field_name\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr>";
	} // end function searchbox_option


	function searchbox_option_vert ($browse_caption, $browse_field_name, $rental = "no")
	{
		// builds a pulldown menu for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		echo "<table><tr><td align=\"center\"><b>$browse_caption</b></td></tr>";
		echo "<tr><td align=\"center\">";
				if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang[rental]."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsDB.ID, count(field_value) AS num_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB WHERE " . $config[table_prefix] . "listingsDBElements.field_name = '$browse_field_name' AND " . $config[table_prefix] . "listingsDB.active = 'yes' AND " . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID ".$rental_str;
		if ($config[use_expiration] == "yes")
		{
			$sql .= " AND expiration > ".$conn->DBDate(time());
		}
		$sql .= "GROUP BY " . $config[table_prefix] . "listingsDBElements.field_value ORDER BY " . $config[table_prefix] . "listingsDBElements.field_value";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$field_output = make_db_unsafe ($recordSet->fields[field_value]);
			$num_type = $recordSet->fields[num_type];
			echo "<input type=\"radio\" name=\"$browse_field_name\" value=\"$field_output\">$field_output ($num_type)<br>";
			$recordSet->MoveNext();
		} // end while
		echo "</select></td></tr></table>";
	} // end function searchbox_option_vert

	function searchbox_minmax ($browse_caption, $browse_field_name, $rental = "no")
	{
		$minmax_start_time = getmicrotime();
		// builds a min/max combo box
		// to let users search by
		global $conn, $config, $lang;
		echo '<tr><td align="right"><b>'.$browse_caption.'</b> ';
		echo '</td><td align="left">';
		$sql = "SELECT field_type, search_step FROM " . $config['table_prefix'] . "listingsFormElements WHERE field_name = '$browse_field_name'";
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
		$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value <> '".$lang[rental]."'";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$rental_str = ' AND ' . $config['table_prefix'] . 'listingsDB.ID IN (';
		$count = 0;

		while (!$recordSet->EOF)
		{
				if ($count != 0)
				$rental_str .= ', ';
				$rental_str .= $recordSet->fields[listing_id];
				$recordSet->MoveNext();  

				$count++;   
		}
		$rental_str .= ') ';
		$max = $conn->GetOne("select min(field_value +0) from " . $config[table_prefix] . "listingsDBElements INNER JOIN " . $config[table_prefix] . "listingsDB ON " . $config[table_prefix] . "listingsDBElements.listing_id=" . $config[table_prefix] . "listingsDB.ID where field_name = '$browse_field_name'".$rental_str);
		
		//$max = $conn->GetOne("select min(field_value +0) from " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB where field_name = '$browse_field_name'".$rental_str);
		if ($price == True)
		{
			$min = round($conn->GetOne("select max(field_value +0) from " . $config[table_prefix] . "listingsDBElements INNER JOIN " . $config[table_prefix] . "listingsDB ON " . $config[table_prefix] . "listingsDBElements.listing_id=" . $config[table_prefix] . "listingsDB.ID where field_name = '$browse_field_name'".$rental_str), -3);

			//$min = round($conn->GetOne("select max(field_value +0) from " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB where field_name = '$browse_field_name'".$rental_str), -3);
		}
		else
		{
			$min = $conn->GetOne("select max(field_value +0) from " . $config[table_prefix] . "listingsDBElements INNER JOIN " . $config[table_prefix] . "listingsDB ON " . $config[table_prefix] . "listingsDBElements.listing_id=" . $config[table_prefix] . "listingsDB.ID where field_name = '$browse_field_name'");
			//$min = $conn->GetOne("select max(field_value +0) from " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsDB where field_name = '$browse_field_name'".$rental_str);
		}
		if ($min > $max) 
		{
			$temp = $min;
			$min = $max;
			$max = $temp;
		}
		//$max = $max + $step;
		echo 'from <select name="'.$browse_field_name.'-min">';
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
		echo ' to <select name="'.$browse_field_name.'-max">'.$options;
		echo '</td></tr>';
	} // end function

	function searchbox_daterange ($caption, $field, $rental = "no")
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
		echo "from <input type=\"text\" name=\"{$field}-mindate\" onKeyUp=\"Javascript: dateMask(this,event);\"> <BR>to
			<input type=\"text\" name=\"{$field}-maxdate\" onKeyUp=\"Javascript: dateMask(this,event);\">";
		echo "\n\t</td>\n</tr>\n";
	}

	function searchbox_optionlist ($caption, $field, $rental = "no")
	{
		global $conn, $config, $lang;
		// start the row
		echo "<tr><td align=\"right\"><b>$caption</b></td>";
		echo "<td align=\"left\">";
		if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		echo '<select name="'.$field.'[]" multiple size=6>';
		$r = $conn->getOne("select f.field_elements from " . $config[table_prefix] . "listingsFormElements f where field_name = '$field'".$rental_str);
		foreach (explode('||', $r) as $f) 
		{
			$f = htmlspecialchars($f);
			echo "<option>$f</option>";
		}
		echo "</select></td></tr>";
	}

	function searchbox_fcheckbox ($caption, $field, $rental = "no")
	{
		global $conn, $config, $lang;
		// start the row
		echo "<tr>";
		echo "<td align=\"right\"><b>$caption</b></td>";
		echo "<td align=\"left\"><table>";
		echo "<tr><td align=\"left\">";
		if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		$r = $conn->getOne("select f.field_elements from " . $config[table_prefix] . "listingsFormElements f where field_name = '$field'".$rental_str);
		foreach (explode('||', $r) as $f) 
		{
			$f = htmlspecialchars($f);
			echo "<input type=\"checkbox\" name=\"{$field}[]\" value=\"$f\">$f<br>";
		}
		echo "</td></tr></table></td></tr>";
	}

	function searchbox_fpulldown ($caption, $field, $rental = "no")
	{
		global $conn, $config, $lang;
		// start the row
		echo "<tr>";
		echo "<td align=\"left\">";
		echo "<table><tr><td align=\"center\" valign=\"top\"><b>$caption</b></td></tr>";
		echo "<tr><td align=\"left\">";
		if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		echo '<select name="'.$field.'[]">';
		$r = $conn->getOne("select f.field_elements from " . $config[table_prefix] . "listingsFormElements f where field_name = '$field'".$rental_str);
		foreach (explode('||', $r) as $f) 
		{
			$f = htmlspecialchars($f);
			echo "<option>$f</option>";
		}
		echo "</select></td></tr></table></td></tr>";
	}
	function searchbox_agentdropdown($rental = "no")
	{
		global $conn, $config, $lang;
		// start the row
		echo "\n<tr>";
		echo "\n<td align=\"right\">";
		echo "\n<b>$lang[search_by_agent]</b></td><td align=\"left\">";
		if ($rental == "yes")
		{
			$sql = "SELECT listing_id FROM " . $config[table_prefix] . "listingsDBElements WHERE field_name = 'type' AND field_value = '".$lang['rental']."'";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false)
			{
				log_error($sql);
			}
			$rental_str = " AND " . $config[table_prefix] . "listingsDB.ID IN (";
			$count = 0;

			while (!$recordSet->EOF)
			{
					if ($count != 0)
					$rental_str .= ", ";
					$rental_str .= $recordSet->fields[listing_id];
					$recordSet->MoveNext();  
					$count++;   
			}
			$rental_str .= ") ";
		}
		echo "\n<select name=\"user_ID\">";
		echo "\n<option value=''>$lang[Any_Agent]</option>";
		$sql = "select f.ID, f.user_name from " . $config[table_prefix] . "UserDB f where isAgent = 'yes' and isAdmin = 'no'".$rental_str;
		//echo $sql;
		$recordSet = $conn->Execute($sql);
		while (!$recordSet->EOF)
		{
			$id = $recordSet->fields['ID'];
			$user_name = $recordSet->fields['user_name'];
			echo '<option value="'.$id.'">'.$user_name.'</option>';
			$recordSet->MoveNext();
		}
		echo "\n</select></td></tr>\n";
	}
	function latestListings($num_of_listings)
	{
		// builds a list of X number of latest listings
		global $conn, $config;
		echo "<ul>";
		$sql = "SELECT ID, Title FROM " . $config[table_prefix] . "listingsDB WHERE (" . $config[table_prefix] . "listingsDB.active = 'yes') ORDER BY creation_date DESC";
		$recordSet = $conn->SelectLimit($sql, $num_of_listings, 0);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$ID = make_db_unsafe ($recordSet->fields[ID]);
			$Title = make_db_unsafe ($recordSet->fields[Title]);
			echo "<li> <a href=\"listingview.php?listingID=$ID\">$Title</a>";
			$recordSet->MoveNext();
		} // end while
		echo "</ul>";
	} // end function latestListings
	
	function rental_minmax ($browse_caption, $browse_field_name)
	{
		// builds a min/max combo box
		// to let users search by
		global $conn, $config, $lang;
		echo "<tr><td align=\"right\"><b>$browse_caption $lang[per_week]</b></td>";
		echo "<td align=\"left\">";

			// Get max, min and step
			$step =$config[rental_step];
			$max = $config[max_rental_price];
			$min = $config[min_rental_price];
		//	echo "min $min max $max step $step";
			if ($min > $max)
			{
				$temp = $min;
				$min = $max;
				$max = $temp;
			}
			echo "from <select name=\"{$browse_field_name}-min\">\n";
			$options = "<option></option>\n";
			for ($i = $min; $i <= $max; $i += $step)
			{
				$options .= "\t\t<option>$i</option>\n";
			}
			echo $options . '</select>';
			echo " to <select name=\"{$browse_field_name}-max\">$options";
			echo "\n\t</td>\n</tr>\n";
	} // end function
	function renderFeaturedListingsHorizontal($num_of_listings)
	{
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		//$seed=srand((double) microtime()*1000000);
		$sql = "SELECT ID, Title FROM " . $config[table_prefix] . "listingsDB WHERE (featured = 'yes') ORDER BY RAND()";
		$recordSet = $conn->SelectLimit($sql, $num_of_listings, 0 );
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$returned_num_listings = $recordSet->RecordCount();
		if ($returned_num_listings > 0)
		{

			print "<table border='0' width=\"50%\" cellpadding=\"1\" cellspacing=\"1\" align=\"center\">";
			$numcols = 3;
			$count = 1;
			print "<tr>";

			while (!$recordSet->EOF)
			{
				$Title = make_db_unsafe ($recordSet->fields[Title]);
				$ID = make_db_unsafe ($recordSet->fields[ID]);
				// GRAB THE LISTINGS IMAGE
				$sql2 = "SELECT thumb_file_name FROM " . $config[table_prefix] . "listingsImages WHERE listing_id = $ID ORDER BY rank";
				$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
				if ($recordSet2 === false)
				{
					log_error($sql2);
				}
				$num_images = $recordSet2->RecordCount();
				if ($num_images == 0)
				{
					if ($config[show_no_photo] == "yes")
					{
						echo "<td class=\"search_row_$count\" align=\"left\"><img src=\"images/nophoto.gif\" border=\"1\"></td>";
					}
					else
					{
						echo "<td class=\"search_row_$count\">&nbsp;</td>";
					}
				}
				while (!$recordSet2->EOF)
				{
					$thumb_file_name = make_db_unsafe ($recordSet2->fields[thumb_file_name]);
					if ($thumb_file_name != "")
					{

						// GOTTA GRAB THE IMAGE SIZE
						$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$shrinkage = $config[thumbnail_width]/$imagewidth;
						$displaywidth = $imagewidth * $shrinkage;
						$displayheight = $imageheight * $shrinkage;

						// $sql2 = "SELECT thumb_file_name FROM listingsImages WHERE (listing_id = $ID) ORDER BY rank";
						// $recordSet2 = $conn->SelectLimit($sql2, 1, 0 );
						// if ($recordSet2 === false) log_error($sql);
						// {
						// while (!$recordSet2->EOF)
						// {
						// $thumb_file_name = make_db_unsafe ($recordSet2->fields[thumb_file_name]);
						//
						// // gotta grab the image size
						// $imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						// $imagewidth = $imagedata[0];
						// $imageheight = $imagedata[1];
						// $shrinkage = $config[thumbnail_width]/$imagewidth;
						// $displaywidth = $imagewidth * $shrinkage;
						// $displayheight = $imageheight * $shrinkage;
					} // end if
					$recordSet2->MoveNext();
				} // end while
				$recordSet->MoveNext();
				if ($count % $numcols ==0)
				{
					print "<td align=\"center\"><a href=\"listingview.php?listingID=$ID\">";
					print "<div align=\"center\"> <br><img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$lang[click_to_learn_more]\" border=1><br>";
					echo "<b>$Title</b></a></div></td></tr><tr>";
				}
				else
				{
					print "<td align=\"center\"><a href=\"listingview.php?listingID=$ID\"> ";
					print "<div align=\"center\"><br>
					<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$lang[click_to_learn_more]\" border=1><br>";
					echo "<b>$Title</b></a></div></td>";
				} //end if
				$count++;
			} // end while
			print "</tr></table>";
		} //end if
	} // end function renderFeaturedListingsHorizontal 

function rendervtourlink($listingID)
	{
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT ID, file_name, rank FROM " . $config[table_prefix] . "vTourImages WHERE (listing_id = $listingID) ORDER BY rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
		//echo "<center><b><a href=\"javascript:openpopup()\">Click Here for Virtual Tour</a></b></center>";
		echo "<center><a href=\"javascript:openpopup()\"><img border=\"0\" src=\"$config[baseurl]/images/vtourbutton.jpg\" alt=\"Click Here For Online Virtual Tour\"></a></center>";
		} // end if ($num_images > 0)
	} // end function rendervtourlink

function renderautovtourlink($autoID)
	{
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$autoID = make_db_extra_safe($autoID);
		$sql = "SELECT ID, file_name, rank FROM " . $config[table_prefix] . "autovTourImages WHERE (auto_id = $autoID) ORDER BY rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
		//echo "<center><b><a href=\"javascript:openautopopup()\">Click Here for Virtual Tour</a></b></center>";
		echo "<center><a href=\"javascript:openautopopup()\"><img border=\"0\" src=\"$config[baseurl]/images/vtourbutton.jpg\" alt=\"Click Here For Online Virtual Tour\"></a></center>";
		} // end if ($num_images > 0)
	} // end function renderautovtourlink


function renderListingsMainImageJava($listingID)
	{
		// shows the main image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT ID, caption, file_name FROM " . $config['table_prefix'] . "listingsImages WHERE (listing_id = $listingID) ORDER BY rank LIMIT 0,1";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
			while (!$recordSet->EOF)
			{
				$file_name = make_db_unsafe ($recordSet->fields['file_name']);
				echo "<center><img src=\"$config[listings_view_images_path]/$file_name\" width=\"440\" name=\"main\"></center>";
			$recordSet->MoveNext();
			} // end while
		} // end if ($num_images > 0)
	} // end function renderListingsMainImageJava

		function renderListingsImagesJava($listingID)
	{
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT ID, caption, file_name, thumb_file_name FROM " . $config['table_prefix'] . "listingsImages WHERE (listing_id = $listingID) ORDER BY rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
			echo "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";

			while (!$recordSet->EOF)
			{
				$caption = make_db_unsafe ($recordSet->fields['caption']);
				$thumb_file_name = make_db_unsafe ($recordSet->fields['thumb_file_name']);
				$file_name = make_db_unsafe ($recordSet->fields['file_name']);
				$imageID = make_db_unsafe ($recordSet->fields['ID']);

				// gotta grab the image size
				$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$shrinkage = $config['thumbnail_width']/$imagewidth;
				$displaywidth = $imagewidth * $shrinkage;
				$displayheight = $imageheight * $shrinkage;

				echo "<a href=\"javascript:imgchange('$file_name')\"> ";
				echo "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$thumb_file_name\"></a><br> ";
				echo "<b>$caption</b><br><br>";
				$recordSet->MoveNext();
			} // end while
			echo "</td>";
		} // end if ($num_images > 0)
	} // end function renderListingsImagesJava


function makeYahooMap($listingID, $address_field, $city_field, $state_field)
	{
		// renders a link to yahoo maps on the page

		global $conn, $config;
		$sql_listingID = make_db_extra_safe($listingID);
		$sql_address_field = make_db_safe($address_field);
		$sql_city_field = make_db_safe($city_field);
		$sql_state_field = make_db_safe($state_field);
		// get address
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_type, " . $config[table_prefix] . "listingsFormElements.field_caption FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $sql_listingID) AND (" . $config[table_prefix] . "listingsFormElements.field_name = " . $config[table_prefix] . "listingsDBElements.field_name) AND (" . $config[table_prefix] . "listingsDBElements.field_name = $sql_address_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$yahoo_address = make_db_unsafe ($recordSet->fields[field_value]);
			$recordSet->MoveNext();

		} // end while
		// get city
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_type, " . $config[table_prefix] . "listingsFormElements.field_caption FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $sql_listingID) AND (" . $config[table_prefix] . "listingsFormElements.field_name = " . $config[table_prefix] . "listingsDBElements.field_name) AND (" . $config[table_prefix] . "listingsDBElements.field_name = $sql_city_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$yahoo_city = make_db_unsafe ($recordSet->fields[field_value]);
			$recordSet->MoveNext();
		} // end while
		// get state
		$sql = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_type, " . $config[table_prefix] . "listingsFormElements.field_caption FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $sql_listingID) AND (" . $config[table_prefix] . "listingsFormElements.field_name = " . $config[table_prefix] . "listingsDBElements.field_name) AND (" . $config[table_prefix] . "listingsDBElements.field_name = $sql_state_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		while (!$recordSet->EOF)
		{
			$yahoo_state = make_db_unsafe ($recordSet->fields[field_value]);
			$recordSet->MoveNext();
		} // end while
		$yahoo_string = "Pyt=Tmap&amp;addr=$yahoo_address&amp;csz=$yahoo_city,$yahoo_state&amp;Get+Map=Get+Map";
		echo "<a href=\"http://maps.yahoo.com/py/maps.py?$yahoo_string\" target=\"_map\"><b>View a map of the area</b></a>";
	} // end makeYahooMap





function renderautoMainImageJava($autoID)
	{
		// shows the main image

		global $conn, $lang, $config, $style;
		// grab the images
		$autoID = make_db_extra_safe($autoID);
		$sql = "SELECT ID, caption, file_name FROM " . $config['table_prefix'] . "autoimages WHERE (auto_id = $autoID) ORDER BY rank LIMIT 0,1";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
			while (!$recordSet->EOF)
			{
				$file_name = make_db_unsafe ($recordSet->fields['file_name']);
				echo "<center><img src=\"$config[auto_view_images_path]/$file_name\" width=\"440\" name=\"main\"></center>";
			$recordSet->MoveNext();
			} // end while
		} // end if ($num_images > 0)
	} // end function renderautoMainImageJava

		function renderautoImagesJava($autoID)
	{
		// shows the images connected to a given image

		global $conn, $lang, $config, $style;
		// grab the images
		$autoID = make_db_extra_safe($autoID);
		$sql = "SELECT ID, caption, file_name, thumb_file_name FROM " . $config['table_prefix'] . "autoimages WHERE (auto_id = $autoID) ORDER BY rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		$num_images = $recordSet->RecordCount();
		if ($num_images > 0)
		{
			echo "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";

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

				echo "<a href=\"javascript:imgchange('$file_name')\"> ";
				echo "<img src=\"$config[auto_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$thumb_file_name\"></a><br> ";
				echo "<b>$caption</b><br><br>";
				$recordSet->MoveNext();
			} // end while
			echo "</td>";
		} // end if ($num_images > 0)
	} // end function renderautoImagesJava

?>