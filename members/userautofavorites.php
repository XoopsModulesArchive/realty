<?php
include("../../../mainfile.php");
include("../../../header.php");
OpenTable();
?>

<?php
include("../include/common.php");
global $config, $conn, $lang, $action;
loginCheckVisitor('User');
include("$config[template_path]/user_top.html");

	if (!$action)
		{
		global $autoID;
			if ($autoID == "")
				{
				echo "<a href=\"$config[baseurl]/index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
				}	
				
				
			elseif ($autoID != "")
				{
				  make_db_safe($userID);
				  make_db_safe($autoID);
			
				  $sql = "SELECT * FROM ".$config['table_prefix']."userfavoriteauto WHERE user_ID = $userID AND auto_ID = $autoID";
				  $recordSet = $conn->Execute($sql);
				  if ($recordSet === false)
				    log_error($sql);
				  $num_columns = $recordSet->RecordCount();
				  if ($num_columns == 0)
				    {
				      $sql = "INSERT INTO ".$config['table_prefix']."userfavoriteauto (user_ID, auto_ID) VALUES ($userID, $autoID)";
				  
				      $recordSet = $conn->Execute($sql);
				      if ($recordSet === false)
					log_error($sql);
				      else
					echo "<br>$lang[listing_added_to_favorites]";
				    }
				  else 
				    {
				      echo "<br>$lang[listing_already_in_favorites]";
				    }
			
			
				}
	}//end action add
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																				*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

if ($action == "list")
	{
	global $autoID, $auto_id, $autos, $sortby, $cur_page, $guidestring_with_sort, $guidestring;
			make_db_safe($userID);
		make_db_safe($autoID);
		
		$sql = "SELECT auto_ID FROM ".$config['table_prefix']."userfavoriteauto WHERE user_ID = $userID";
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
			$autos .= $recordSet->fields['auto_ID'];
		      else
			$autos .= ", " . $recordSet->fields['auto_ID'];
		     
		      $recordNum++;
		      $recordSet->MoveNext();
		
		    }
		
		  //  header('Location: autosearch.php?$autos');
		  //  exit();
		
		  $sql = "drop table IF EXISTS ".$config['table_prefix']."auto_temp";
		  $recordSet = $conn->Execute($sql);
		  if ($recordSet === false) log_error($sql);
		  $sql = "CREATE TABLE ".$config['table_prefix']."auto_temp SELECT ".$config['table_prefix']."autodb.ID, ".$config['table_prefix']."autodb.Title, ".$config['table_prefix']."autodbelements.field_name, ".$config['table_prefix']."autodbelements.field_value FROM ".$config['table_prefix']."autodb, ".$config['table_prefix']."autodbelements WHERE ".$config['table_prefix']."autodb.ID IN ($autos) AND ".$config['table_prefix']."autodb.ID = ".$config['table_prefix']."autodbelements.auto_ID";
		
		  $recordSet = $conn->Execute($sql);
		  if ($recordSet === false) log_error($sql);
		
			// this is the main SQL that grabs the autos
			// basic sort by title..
			if ($sortby == "" OR $sortby == "autoname")
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
		
		
		
			$sql = "SELECT * from ".$config['table_prefix']."auto_temp $sort_text GROUP BY ID $order_text";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) log_error($sql);
						
			$num_rows = $recordSet->RecordCount();
		
			if ($num_rows > 0)
				{
				next_prev($num_rows, $cur_page, $guidestring_with_sort); // put in the next/previous stuff
			
				// build the string to select a certain number of autos per page
				$limit_str = $cur_page * $config['listings_per_page'];
				$resultRecordSet = $conn->SelectLimit($sql, $config['listings_per_page'], $limit_str );
					if ($resultRecordSet === false) log_error($sql);
			
				?>
		
				<table border="<? echo $style['form_border'] ?>" cellspacing="<? echo $style['form_cellspacing'] ?>" cellpadding="<? echo $style['form_cellpadding'] ?>" width="<? echo $style['admin_table_width'] ?>" class="form_main" align="center">
					<tr>
						<td><b><a href="<?=$_SERVER['PHP_SELF']?>?sortby=autoname&<? echo $guidestring ?>"><? echo $lang['admin_listings_editor_title'] ?></a></b></td>
						<?
						// grab browsable fields
						$sql = "SELECT field_caption, field_name FROM ".$config['table_prefix']."autoformelements WHERE (display_on_browse = 'Yes') AND (field_type <> 'textarea') ORDER BY rank";
						$recordSet = $conn->Execute($sql);
						$num_columns = $recordSet->RecordCount();
						while (!$recordSet->EOF)
							{
							$field_caption = make_db_unsafe ($recordSet->fields['field_caption']);
							$field_name = make_db_unsafe ($recordSet->fields['field_name']);
							echo "<td align=\"center\"><b><a href=\"$_SERVER[PHP_SELF]?action=list&amp;sortby=$field_name&$guidestring\">$field_caption</a></b></td>";
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
						
						$Title = make_db_unsafe ($resultRecordSet->fields['Title']);
						$current_ID = $resultRecordSet->fields['ID'];
						echo "<tr><td align=\"left\" class=\"search_row_$count\" colspan=\"$num_columns\"><b><a href=\"$config[baseurl]/autoview.php?autoID=$current_ID\">$Title</a></b></td></tr>";
						echo "<tr>";
						
						// grab the auto's image
						$sql2 = "SELECT thumb_file_name FROM ".$config['table_prefix']."autoimages WHERE auto_id = $current_ID ORDER BY rank";
						$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
							if ($recordSet2 === false) log_error($sql2);
						$num_images = $recordSet2->RecordCount();
						if ($num_images == 0)
							{
							if ($config[show_no_photo] == "yes")
								{
								echo "<td class=\"search_row_$count\" align=\"center\"><a href=\"$config[baseurl]/autoview.php?autoID=$current_ID\"><img src=\"$config[baseurl]/images/nophoto.gif\" border=\"1\"></a></td>";
								}
							else
								{
								echo "<td class=\"search_row_$count\">&nbsp;</td>";
								}
							}
						while (!$recordSet2->EOF)
							{
							$thumb_file_name = make_db_unsafe ($recordSet2->fields['thumb_file_name']);
							if ($thumb_file_name != "")
								{
								// gotta grab the image size
								$imagedata = GetImageSize("$config[auto_upload_path]/$thumb_file_name");
								$imagewidth = $imagedata[0];
								$imageheight = $imagedata[1];
								$shrinkage = $config['thumbnail_width']/$imagewidth;
								$displaywidth = $imagewidth * $shrinkage;
								$displayheight = $imageheight * $shrinkage;
								echo "<td class=\"search_row_$count\" align=\"center\"><a href=\"$config[baseurl]/autoview.php?autoID=$current_ID\">";
								echo "<img src=\"$config[auto_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a></td>";
								} // end if ($thumb_file_name != "")
							$recordSet2->MoveNext();
							} // end while
						
						// grab the rest of the auto's data
						$sql2 = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autoformelements.field_caption, ".$config['table_prefix']."autoformelements.field_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autoformelements WHERE ((".$config['table_prefix']."autodbelements.auto_id = $current_ID) AND (".$config['table_prefix']."autoformelements.display_on_browse = 'Yes') AND (".$config['table_prefix']."autoformelements.field_type <> 'textarea') AND (".$config['table_prefix']."autodbelements.field_name = ".$config['table_prefix']."autoformelements.field_name)) ORDER BY ".$config['table_prefix']."autoformelements.rank";
						$recordSet2 = $conn->Execute($sql2);
							if ($recordSet2 === false) log_error($sql2);
						while (!$recordSet2->EOF)
							{
							$field_value = make_db_unsafe ($recordSet2->fields['field_value']);
							$field_type = make_db_unsafe ($recordSet2->fields['field_type']);
							$field_caption = make_db_unsafe ($recordSet2->fields['field_caption']);
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
						$sql2 = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autoformelements.field_type, ".$config['table_prefix']."autodb.user_ID FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autoformelements, ".$config['table_prefix']."autodb WHERE ((".$config['table_prefix']."autodbelements.auto_id = $current_ID) AND (".$config['table_prefix']."autoformelements.display_on_browse = 'Yes') AND (".$config['table_prefix']."autoformelements.field_type = 'textarea') AND (".$config['table_prefix']."autodbelements.field_name = ".$config['table_prefix']."autoformelements.field_name) AND (".$config['table_prefix']."autodb.ID = $current_ID)) ORDER BY ".$config['table_prefix']."autoformelements.rank";
						$recordSet2 = $conn->Execute($sql2);
							if ($recordSet2 === false) log_error($sql2);
						while (!$recordSet2->EOF)
							{
							$field_value = make_db_unsafe ($recordSet2->fields['field_value']);
							$field_caption = make_db_unsafe ($recordSet2->fields['field_caption']);
							$user_ID = make_db_unsafe ($recordSet2->fields['user_ID']);
							$user = $user_ID;
							echo "<tr><td colspan=\"$num_columns\" class=\"search_row_$count\"><p class=\"note\">$field_value ";
							getUserLink($user);
							echo "</p></td></tr>";
		//					echo "<tr><td colspan=\"$num_columns\" class=\"search_row_$count\">$userLink</td></tr>";
							$recordSet2->MoveNext();
							} // end while
						
						echo "<tr><td colspan=\"$num_columns\" class=\"search_row_$count\"><div class=\"note\"><a href=\"$_SERVER[PHP_SELF]?action=delete&amp;autoID=$current_ID\" onClick=\"return confirmDelete()\">$lang[delete_from_favorites]</a></td></tr>";				
						$resultRecordSet->MoveNext();
		
						} // end while
						
						
					?>
					
				
			</table>
		
		<br>
		
			<?
		  
			} // end if ($num_rows > 0)
		
		
		
		
		}

	}//end action list
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																				*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	if ($action == "delete")
		{
				if ($autoID == "")
			{
			  echo "<a href=\"$config[baseurl]/index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
			}	
			
			
			elseif ($autoID != "")
			{
			  make_db_safe($userID);
			  make_db_safe($autoID);
			  
			  $sql = "DELETE FROM ".$config['table_prefix']."userfavoriteauto WHERE user_ID = $userID AND auto_ID = $autoID";
			  $recordSet = $conn->Execute($sql);
			  if ($recordSet === false) log_error($sql);
			  echo "<br>$lang[listing_deleted_from_favorites]";  
			}
	
		}// end action delete

?>
<?
CloseTable();
include("../../../footer.php");
?>