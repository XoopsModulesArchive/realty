<?php
include("../../../mainfile.php");
include("../../../header.php");
OpenTable();
?>

<?php


include("../include/common.php");

loginCheckVisitor('User');

echo "<h3>$lang[favorite_listings]</h3>";

make_db_safe($userID);
make_db_safe($listingID);

$sql = "SELECT listing_ID FROM " . $config[table_prefix] . "userFavoriteListings WHERE user_ID = $userID";
$recordSet = $conn->Execute($sql);
if ($recordSet == false)log_error($sql);
$num_columns = $recordSet->RecordCount();

if ($num_columns == 0)
{
  echo "$lang[no_listing_in_favorites]<br><br>";
}
else {

  $recordNum = 0;
  while (!$recordSet->EOF)
    {
     
      if ($recordNum == 0)
	$listings .= $recordSet->fields[listing_ID];
      else
	$listings .= ", " . $recordSet->fields[listing_ID];
     
      $recordNum++;
      $recordSet->MoveNext();

    }

  //  header('Location: listingsearch.php?$listings');
  //  exit();

  $sql = "drop table IF EXISTS " . $config[table_prefix] . "temp";
  $recordSet = $conn->Execute($sql);
  if ($recordSet === false) log_error($sql);
  $sql = "CREATE TABLE " . $config[table_prefix] . "temp SELECT " . $config[table_prefix] . "listingsDB.ID, " . $config[table_prefix] . "listingsDB.Title, " . $config[table_prefix] . "listingsDBElements.field_name, " . $config[table_prefix] . "listingsDBElements.field_value FROM " . $config[table_prefix] . "listingsDB, " . $config[table_prefix] . "listingsDBElements WHERE " . $config[table_prefix] . "listingsDB.ID IN ($listings) AND " . $config[table_prefix] . "listingsDB.ID = " . $config[table_prefix] . "listingsDBElements.listing_ID";

  $recordSet = $conn->Execute($sql);
  if ($recordSet === false) log_error($sql);

	// this is the main SQL that grabs the listings
	// basic sort by title..
	if ($sortby == "" OR $sortby == "listingname")
		{
		$sort_text = "";
		$order_text = "ORDER BY Title ASC";
		}
	elseif ($sortby == "price")
		{
		$sortby = make_db_extra_safe($sortby);
		$sort_text = "WHERE (field_name = $sortby)";
		$order_text = "ORDER BY field_value +0 DESC";
		}
	else
		{
		$sortby = make_db_extra_safe($sortby);
		$sort_text = "WHERE (field_name = $sortby)";
		$order_text = "ORDER BY field_value ASC";
		}
	
	//	$guidestring_with_sort = $guidestring_with_sort.$guidestring;



	$sql = "SELECT * from " . $config[table_prefix] . "temp $sort_text GROUP BY ID $order_text";
	$recordSet = $conn->Execute($sql);
	if ($recordSet === false) log_error($sql);
				
	$num_rows = $recordSet->RecordCount();

	if ($num_rows > 0)
		{
		next_prev($num_rows, $cur_page, $guidestring_with_sort); // put in the next/previous stuff
	
		// build the string to select a certain number of listings per page
		$limit_str = $cur_page * $config[listings_per_page];
		$resultRecordSet = $conn->SelectLimit($sql, $config[listings_per_page], $limit_str );
			if ($resultRecordSet === false) log_error($sql);
	
		?>

		<table border="<? echo $style[form_border] ?>" cellspacing="<? echo $style[form_cellspacing] ?>" cellpadding="<? echo $style[form_cellpadding] ?>" width="<? echo $style[admin_table_width] ?>" class="form_main" align="center">
			<tr>
				<td><b><a href="<?echo $php_self ?>?sortby=listingname&<? echo $guidestring ?>"><? echo $lang[admin_listings_editor_title] ?></a></b></td>
				<?
				// grab browsable fields
				$sql = "SELECT field_caption, field_name FROM " . $config[table_prefix] . "listingsFormElements WHERE (display_on_browse = 'Yes') AND (field_type <> 'textarea') ORDER BY rank";
				$recordSet = $conn->Execute($sql);
				$num_columns = $recordSet->RecordCount();
				while (!$recordSet->EOF)
					{
					$field_caption = make_db_unsafe ($recordSet->fields[field_caption]);
					$field_name = make_db_unsafe ($recordSet->fields[field_name]);
					echo "<td align=\"center\"><b><a href=\"$PHP_SELF?sortby=$field_name&$guidestring\">$field_caption</a></b></td>";
					$recordSet->MoveNext();
					} // end while
				$num_columns = $num_columns + 1; // add one for the image
				?>
			</tr>
			<tr>
				<td colspan="<? echo $num_columns ?>">
					<hr>
				</td>
			</tr>
		
			<?		
			$count = 0;
			while (!$resultRecordSet->EOF)
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
				
				$Title = make_db_unsafe ($resultRecordSet->fields[Title]);
				$current_ID = $resultRecordSet->fields[ID];
				echo "<tr><td align=\"left\" class=\"search_row_$count\" colspan=\"$num_columns\"><b><a href=\"../listingview.php?listingID=$current_ID\">$Title</a></b></td></tr>";
				echo "<tr>";
				
				// grab the listing's image
				$sql2 = "SELECT thumb_file_name FROM " . $config[table_prefix] . "listingsImages WHERE listing_id = $current_ID ORDER BY rank";
				$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
					if ($recordSet2 === false) log_error($sql2);
				$num_images = $recordSet2->RecordCount();
				if ($num_images == 0)
					{
					if ($config[show_no_photo] == "yes")
						{
						echo "<td class=\"search_row_$count\" align=\"center\"><a href=\"../listingview.php?listingID=$current_ID\"><img src=\"../images/nophoto.gif\" border=\"1\"></a></td>";
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
						// gotta grab the image size
						$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$shrinkage = $config[thumbnail_width]/$imagewidth;
						$displaywidth = $imagewidth * $shrinkage;
						$displayheight = $imageheight * $shrinkage;
						echo "<td class=\"search_row_$count\" align=\"center\"><a href=\"../listingview.php?listingID=$current_ID\">";
						echo "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a></td>";
						} // end if ($thumb_file_name != "")
					$recordSet2->MoveNext();
					} // end while
				
				// grab the rest of the listing's data
				$sql2 = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_caption, " . $config[table_prefix] . "listingsFormElements.field_type FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $current_ID) AND (" . $config[table_prefix] . "listingsFormElements.display_on_browse = 'Yes') AND (" . $config[table_prefix] . "listingsFormElements.field_type <> 'textarea') AND (" . $config[table_prefix] . "listingsDBElements.field_name = " . $config[table_prefix] . "listingsFormElements.field_name)) ORDER BY " . $config[table_prefix] . "listingsFormElements.rank";
				$recordSet2 = $conn->Execute($sql2);
					if ($recordSet2 === false) log_error($sql2);
				while (!$recordSet2->EOF)
					{
					$field_value = make_db_unsafe ($recordSet2->fields[field_value]);
					$field_type = make_db_unsafe ($recordSet2->fields[field_type]);
					$field_caption = make_db_unsafe ($recordSet2->fields[field_caption]);
					echo "<td align=\"center\" valign=\"top\" class=\"search_row_$count\"><span class=\"smallBold\"><br>$field_caption</span><br>";
					
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
						//$field_value = ereg_replace('[^0-9]', '', $field_value);
						//echo "$config[money_sign]".number_format($field_value, 2, '.', ',');
						$money_amount = international_num_format($field_value);
						echo money_formats($money_amount);
						} // end elseif
					elseif ($field_type == "number")
						{
						echo international_num_format($field_value);
						} // end elseif
					elseif ($field_type == "url")
						{
						echo "<a href=\"$field_value\" target=\"_new\">$field_value</a>";
						}
					elseif ($field_type == "email")
						{
						echo "<a href=\"mailto:$field_value\">$field_value</a>";
						}
					else
						{
						echo "$field_value";
						} // end else
					
					echo "</td>";
					$recordSet2->MoveNext();
					} // end while
				
				
				echo "</tr>";
				// deal with text areas, like descriptions
				$sql2 = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_type, " . $config[table_prefix] . "listingsDB.user_ID FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements, " . $config[table_prefix] . "listingsDB WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $current_ID) AND (" . $config[table_prefix] . "listingsFormElements.display_on_browse = 'Yes') AND (" . $config[table_prefix] . "listingsFormElements.field_type = 'textarea') AND (" . $config[table_prefix] . "listingsDBElements.field_name = " . $config[table_prefix] . "listingsFormElements.field_name) AND (" . $config[table_prefix] . "listingsDB.ID = $current_ID)) ORDER BY " . $config[table_prefix] . "listingsFormElements.rank";
				$recordSet2 = $conn->Execute($sql2);
					if ($recordSet2 === false) log_error($sql2);
				while (!$recordSet2->EOF)
					{
					$field_value = make_db_unsafe ($recordSet2->fields[field_value]);
					$field_caption = make_db_unsafe ($recordSet2->fields[field_caption]);
					$user_ID = make_db_unsafe ($recordSet2->fields[user_ID]);
					$user = $user_ID;
					echo "<tr><td colspan=\"$num_columns\" class=\"search_row_$count\"><p class=\"note\">$field_value ";
					getUserLink($user);
					echo "</p></td></tr>";
//					echo "<tr><td colspan=\"$num_columns\" class=\"search_row_$count\">$userLink</td></tr>";
					$recordSet2->MoveNext();
					} // end while
				
				echo "<tr><td colspan=\"$num_columns\" class=\"search_row_$count\"><div class=\"note\"><a href=\"deletefromfavorites.php?listingID=$current_ID\" onClick=\"return confirmDelete()\">$lang[delete_from_favorites]</a></td></tr>";				
				$resultRecordSet->MoveNext();

				} // end while
				
				
			?>
			
		
	</table>

<br>

	<?
		  
	} // end if ($num_rows > 0)




}


?>
<?
CloseTable();
include("../../../footer.php");
?>