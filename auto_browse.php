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
	global $conn, $lang, $config, $HTTP_GET_VARS;
	global $sortby, $sorttype, $cur_page;
	$debug_GET = True;
	$guidestring = "";
	$guidestring_with_sort = "";
	// Save GET
	foreach ($_GET as $k => $v)
	{
		if ($v && $k != 'cur_page' && $k != 'PHPSESSID' && $k != 'sortby')
		{
			if (is_array($v))
			{
				foreach ($v as $vitem)
				{
				$guidestring .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
				}
			}
			else
			{
				$guidestring .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
			}
		}
	}

	// START BY SETTING UP THE TABLE OF ALL POSSIBLE LISTINGS
	// while this may seem crazy at first, it actually is reasonably efficient, especially
	// considering the limitations of mysql and the lack of subqueries.
	// basically, it works by the process of elimination...

	$sql = "drop table IF EXISTS ".$config['table_prefix']."auto_temp";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
	$sql = "CREATE TABLE ".$config['table_prefix']."auto_temp SELECT ".$config['table_prefix']."autodb.ID, ".$config['table_prefix']."autodb.Title, ".$config['table_prefix']."autodb.user_ID, ".$config['table_prefix']."autodbelements.field_name, ".$config['table_prefix']."autodbelements.field_value FROM ".$config['table_prefix']."autodb, ".$config['table_prefix']."autodbelements WHERE (".$config['table_prefix']."autodbelements.auto_id = ".$config['table_prefix']."autodb.ID) AND ";
	if ($config['use_expiration'] == "yes")
	{
		$sql .= "(".$config['table_prefix']."autodb.expiration > ".$conn->DBDate(time()).") AND ";
	}
	$sql .= "(".$config['table_prefix']."autodb.active = 'yes')";

	$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
	// Create Index on temporary table to speed up searching
	if ($config['manage_index_permissions'] == 'Yes')
	{
		//Host Supports Creating Indexes, so create some to speed up searching.
		$sql = "create index idx_autoid on ".$config['table_prefix']."auto_temp (ID)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$sql = "create index idx_autoname on ".$config['table_prefix']."auto_temp (field_name(10))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
	}
	reset ($HTTP_GET_VARS);
	foreach ($_GET as $ElementIndexValue => $ElementContents) {
		if ($ElementIndexValue == "sortby")
		{
			$guidestring_with_sort = "$ElementIndexValue=$ElementContents";
		}
		elseif ($ElementIndexValue == "sorttype")
		{
				$guidestring_with_sort = "$ElementIndexValue=$ElementContents&amp;";
		}
		elseif ($ElementIndexValue == "cur_page")
		{
			// do nothing
		}
		elseif ($ElementIndexValue == "PHPSESSID")
		{
			// do nothing
		}
		elseif ($ElementIndexValue == "user_ID")
		{
				$sql = "DELETE FROM ".$config['table_prefix']."auto_temp WHERE User_ID <> $ElementContents";
				$recordSet = $conn->Execute($sql);

		}
		elseif ($ElementIndexValue == "imagesOnly")
		{
			$guidestring .= "$ElementIndexValue=$ElementContents&amp;";
			if ($ElementContents == "yes")
			{
				$whilecount = 0;
				$delete_string = "DELETE FROM ".$config['table_prefix']."auto_temp WHERE (1=1)";
				// the 1=1 is a dumb sql trick to deal with the code below ... it works, but you can ignore it
				$sql = "SELECT ".$config['table_prefix']."auto_temp.ID, COUNT(".$config['table_prefix']."autoimages.file_name) AS imageCount FROM ".$config['table_prefix']."autoimages,".$config['table_prefix']."auto_temp WHERE (".$config['table_prefix']."autoimages.auto_id = ".$config['table_prefix']."auto_temp.ID) GROUP BY ".$config['table_prefix']."autoimages.auto_id";
				$recordSet = $conn->Execute($sql);
					if ($recordSet === false)
					{
						log_error($sql);
					}
				while (!$recordSet->EOF)
				{
					$whilecount = $whilecount + 1;
					$autoID = $recordSet->fields[ID];
					$imageCount = $recordSet->fields[imageCount];
					$delete_string .= " AND ";
					$delete_string .= "(ID <> $autoID)";
					$recordSet->MoveNext();
				} // end while
				$recordSet = $conn->Execute($delete_string);
					if ($recordSet === false)
					{
						log_error($delete_string);
					}
			}

		} // end elseif ($ElementIndexValue == "imagesOnly")
		elseif (is_array($ElementContents))
		{
			$sql_ElementIndexValue = make_db_safe($ElementIndexValue);

			// Arrays can happen for two reasons:  1. multi options like zip code
			// 2. multi options like home features.  Check the db to see which
			// type of field this is and process accordingly
			$r = $conn->getOne("select search_type from ".$config['table_prefix']."autoformelements where field_name = ".$sql_ElementIndexValue);
			if (($r == 'optionlist') || ($r == 'fcheckbox'))
			{
				$recordSet = $conn->Execute($sql);
				// Delete all records that don't have any field name by this name
				$sql = "select count(t2.field_name) as cnt, t1.id as id from ".$config['table_prefix']."auto_temp t1 left join ".$config['table_prefix']."autodbelements t2 on t1.id = t2.auto_id and t1.field_name = $sql_ElementIndexValue group by t1.id";
				//$sql = " select count(t2.field_name) as cnt, t1.id as id from ".$config['table_prefix']."temp2 t1 left join ".$config['table_prefix']."autosDBElements t2 on t1.id = t2.auto_id group by t1.id";
				$res = $conn->Execute($sql);
				while (!$res->EOF)
				{
					// Check for no field
					if ($res->fields['cnt'] == 0)
					{
						$conn->execute("delete from ".$config['table_prefix']."auto_temp where id = " . $res->fields['id']);
					}
					else
					{
						// for each value, delete those records that don't match it
						$value = $conn->getOne("select field_value from ".$config['table_prefix']."auto_temp where id = " . $res->fields['id'] . " and field_name = $sql_ElementIndexValue");
						$delete = 1;
						
						foreach ($ElementContents as $e)
						{
							if (!strstr($value, $e)) 
							{
								$conn->execute("delete from ".$config['table_prefix']."auto_temp where id = " . $res->fields['id']);
							}
						}
					}
					$res->moveNext();
				}
			}
			else
			{
				// first, we need to see if there's anything that'll meet the criteria
				$whilecountTwo = 0;
				$select_statement = "SELECT ID FROM ".$config['table_prefix']."auto_temp WHERE ( (field_name=$sql_ElementIndexValue) AND ";
				while (list($featureValue, $feature_item) = each ($ElementContents))
				{
					//$guidestring .= "&amp;".($ElementIndexValue)."%5B%5D=".urlencode($feature_item)."&amp;";
					//$guidestring .= urlencode($featureValue)."%5B%5D=".urlencode($feature_item)."&";
					$whilecountTwo = $whilecountTwo + 1;
					if ($whilecountTwo > 1)
					{
						$select_statement .= " OR ";
					}
					$sql_feature_item = make_db_safe($feature_item);
					$select_statement .= "(field_value = $sql_feature_item)";
				}
				$select_statement .= ")";
				$recordSet = $conn->Execute($select_statement);
				if ($recordSet === false)
				{
					log_error($select_statement);
				}
				$save_array = array();
				while (!$recordSet->EOF)
				{
					$save_ID = $recordSet->fields['ID'];
					$save_array[] = "$save_ID";
					$recordSet->MoveNext();
				} // end while
				$num_to_delete = $recordSet->RecordCount();

				// now, delete everything that we don't want...
				if ($num_to_delete > 0)
				{
					$delete_string = "DELETE FROM ".$config['table_prefix']."auto_temp WHERE ";
					while (list($IndexValue,$ElementContents) = each($save_array))
					{
						if ($IndexValue > 0)
						{
							$delete_string .= " AND ";
						}
						$sql_ElementContents = make_db_safe($ElementContents);
						$delete_string .= "(ID <> $sql_ElementContents)";
					} // end while


					$recordSet = $conn->Execute($delete_string);
						if ($recordSet === false)
						{
							log_error($delete_string);
						}
				} // ($num_to_delete > 0)
				// if there's nothing that matches, delete all the other possibilities...
				elseif ($num_to_delete == 0)
				{
					$delete_string = "DELETE FROM ".$config['table_prefix']."auto_temp";
					$recordSet = $conn->Execute($delete_string);
						if ($recordSet === false)
						{
							log_error($delete_string);
						}
				} // end elseif ($num_to_delete = 0)
			} // end optionlist check
		} // end elseif (is_array($ElementContents))
		else
		{
				// Don't process empty searches
				if (!$ElementContents) continue;

				$val = $ElementContents;
			$ElementContents = make_db_safe($ElementContents);
				// Check for min/max values
				$l3 = substr($ElementIndexValue, strlen($ElementIndexValue) - 3);
				if ($l3 == 'min' OR $l3 == 'max')
				{
					$col = strtok($ElementIndexValue, '-');
					// Because mysql 3.x doesn't have cast(), we must retrieve all records then filter - yuck
					$sql = "select id, field_value as v from ".$config['table_prefix']."auto_temp where field_name = '$col'";
					$rs = $conn->Execute($sql);
					$del_id = array();
					while (!$rs->EOF) {
						if ($l3 == 'min' AND $val)
						{
							if ($rs->fields['v'] < $val)
							{
								$del_id[] = $rs->fields['id'];
							}
						}
						if ($l3 == 'max' AND $val)
						{
							if ($rs->fields['v'] > $val)
							{
								$del_id[] = $rs->fields['id'];
							}
						}
						$rs->MoveNext();
					}
					$sql = "delete from ".$config['table_prefix']."auto_temp where id in (" . implode(',', $del_id) . ")";
					if (sizeof($del_id))
					{
						$conn->execute($sql);
					}
					continue;
				}

			// Check for min/max dates
			$l7 = substr($ElementIndexValue, strlen($ElementIndexValue) - 7);
			if ($l7 == 'mindate' OR $l7 == 'maxdate')
			{
				if (($time = strtotime($val)) > 1)
				{
					$col = strtok($ElementIndexValue, '-');
					// Because mysql 3.x doesn't have cast(), we must retrieve all records then filter - yuck
					$sql = "select id, field_value as v from ".$config['table_prefix']."auto_temp where field_name = '$col'";
					$rs = $conn->Execute($sql);
					$del_id = array();
					while (!$rs->EOF)
					{
						$db_time = strtotime($rs->fields['v']);
						if ($l7 == 'mindate' AND $val)
						{
							if ($db_time < $time)
							{
								$del_id[] = $rs->fields['id'];
							}
						}
						if ($l7 == 'maxdate' AND $val)
						{
							if ($db_time > $time)
							{
								$del_id[] = $rs->fields['id'];
							}
						}
						if ($db_time < 1 or !$val)
						{
							$del_id[] = $rs->fields['id'];
						}
						$rs->MoveNext();
					}
					$sql = "delete from ".$config['table_prefix']."auto_temp where id in (" . implode(',', $del_id) . ")";
					if (sizeof($del_id))
					{
						$conn->execute($sql);
					}
					continue;
			}
		}

		if (!$ElementContents) continue;
			$ElementIndexValue = make_db_safe($ElementIndexValue);
			$select_statement = "SELECT ID FROM ".$config['table_prefix']."auto_temp WHERE ( (field_name = $ElementIndexValue) AND (field_value = $ElementContents) )";
			$recordSet = $conn->Execute($select_statement);
				if ($recordSet === false)
				{
					log_error($select_statement);
				}
			$save_array = array();
			while (!$recordSet->EOF)
			{
				$save_ID = $recordSet->fields['ID'];
				$save_array[] = "$save_ID";
				$recordSet->MoveNext();
			} // end while
			$num_to_delete = $recordSet->RecordCount();
			if ($num_to_delete > 0)
			{
				$delete_string = "DELETE FROM ".$config['table_prefix']."auto_temp WHERE ";
				while (list($IndexValue,$ElementContents) = each($save_array))
				{
					if ($IndexValue > 0)
					{
						$delete_string .= " AND ";
					}
					$delete_string .= "(ID <> $ElementContents)";
				}
				$recordSet = $conn->Execute($delete_string);
					if ($recordSet === false)
					{
						log_error($delete_string);
					}
			} // end ($num_to_delete > 0)
			elseif ($num_to_delete == 0)
			{
				$delete_string = "DELETE FROM ".$config['table_prefix']."auto_temp";
				$recordSet = $conn->Execute($delete_string);
					if ($recordSet === false)
					{
						log_error($delete_string);
					}
			} // end elseif ($num_to_delete = 0)

		} // end else
	} // end while


		// this is the main SQL that grabs the autos
		// basic sort by title..
		if ($sortby == "")
		{
			$sort_text = "";
			$order_text = "ORDER BY ID DESC";
		}
		elseif ($sortby == "autoname")
		{
			$sort_text = "";
			$order_text = "ORDER BY Title $sorttype";
		}
			// BEGIN NEW CODE
			elseif ($sortby == "price")
		{
			$sortby = make_db_extra_safe($sortby);
			$sort_text = "WHERE (field_name = $sortby)";
			$order_text = "ORDER BY field_value +0 $sorttype";
		}
			// END NEW CODE
		else
		{
			$sortby = make_db_extra_safe($sortby);
			$sort_text = "WHERE (field_name = $sortby)";
			$order_text = "ORDER BY field_value $sorttype";
		}
		if (($sorttype == "") || ($sorttype == "ASC"))
		{
			$sorttype = "DESC";
		}
		else
		{
			$sorttype = "ASC";
		}

		$guidestring_with_sort = $guidestring_with_sort.$guidestring;

		$sql = "SELECT * from ".$config['table_prefix']."auto_temp $sort_text GROUP BY ID $order_text";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		
		$num_rows = $recordSet->RecordCount();
		if ($num_rows > 0)
		{

			echo '<a href="'.$config['baseurl'].'/members/userautosearch.php?'.$guidestring.'">'.$lang['save_this_search'].'</a><BR>';
			next_prev($num_rows, $cur_page, $guidestring_with_sort); // put in the next/previous stuff

			// build the string to select a certain number of autos per page
			$limit_str = $cur_page * $config['listings_per_page'];
			$resultRecordSet = $conn->SelectLimit($sql, $config['listings_per_page'], $limit_str );
				if ($resultRecordSet === false)
				{
					log_error($sql);
				}

			echo '

			<table border="'.$style['form_border'].'" cellspacing="'.$style['form_cellspacing'].'" cellpadding="'.$style['form_cellpadding'].'" width="'.$style['admin_table_width'].'" class="form_main" align="center">
				<tr>';
					 
						if  (($sortby == "autoname") ||($sortby == "")) 
						{
							$sorttypestring = "sorttype=$sorttype&amp;";
						}
					?>
						
					<?php
					// grab browsable fields
					$sql = "SELECT field_caption, field_name FROM ".$config['table_prefix']."autoformelements WHERE (display_on_browse = 'Yes') AND (field_type <> 'textarea') ORDER BY rank";
					$recordSet = $conn->Execute($sql);
					$num_columns = $recordSet->RecordCount();
					// Add Title as a search link
					$field_caption = "Title";
					$field_name = "autoname";
					if ($sortby != $field_name)
					{
						$sorttypestring = "";
					}
					else
					{
						$sorttypestring = "sorttype=$sorttype&amp;";
					}
						echo '<td align="center"><b><a href="'.$_SERVER['PHP_SELF'].'?sortby='.$field_name.'&amp;'.$sorttypestring.''.$guidestring.'">'.$field_caption.'</a></b></td>';

					while (!$recordSet->EOF)
					{
						$field_caption = make_db_unsafe ($recordSet->fields['field_caption']);
						$field_name = make_db_unsafe ($recordSet->fields['field_name']);
						if ($sortby != "'$field_name'")
						{
							$sorttypestring = "";
						}
						else
						{
							$sorttypestring = "sorttype=$sorttype&amp;";
						}
						echo '<td align="center"><b><a href="'.$_SERVER['PHP_SELF'].'?sortby='.$field_name.'&amp;'.$sorttypestring.''.$guidestring.'">'.$field_caption.'</a></b></td>';
						$recordSet->MoveNext();
					} // end while
					$num_columns = $num_columns + 1; // add one for the image
					echo '
				</tr>
				<tr>
					<td colspan="'.$num_columns.'">
						<hr>
					</td>
				</tr>';

				
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
					echo '<tr><td align="left" class="search_row_'.$count.'" colspan="'.$num_columns.'"><b><a href="'.$config['baseurl'].'/autoview.php?autoID='.$current_ID.'">'.$Title.'</a></b></td></tr>
					<tr>';

					// grab the auto's image
					$sql2 = "SELECT thumb_file_name FROM ".$config['table_prefix']."autoimages WHERE auto_id = $current_ID ORDER BY rank";
					$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
					if ($recordSet2 === false)
					{
						log_error($sql2);
					}
					$num_images = $recordSet2->RecordCount();
					if ($num_images == 0)
					{
						if ($config['show_no_photo'] == "yes")
						{
							//echo "<td class=\"search_row_$count\" align=\"center\"><img src=\"images/nophoto.gif\" border=\"1\" alt=\"no photo\"></td>";
							echo '<td class="search_row_'.$count.'" align="center"><a href="'.$config['baseurl'].'/autoview.php?autoID='.$current_ID.'"><img src="'.$config['baseurl'].'/images/nophoto.gif" border="1" alt="no photo"></a></td>';
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
							echo '<td class="search_row_'.$count.'" align="center"><a href="'.$config['baseurl'].'/autoview.php?autoID='.$current_ID.'">
							<img src="'.$config['auto_view_images_path'].'/'.$thumb_file_name.'" height="'.$displayheight.'" width="'.$displaywidth.'" alt="'.$thumb_file_name.'"></a></td>';
						} // end if ($thumb_file_name != "")
						$recordSet2->MoveNext();
					} // end while

					// grab the rest of the auto's data
					$sql2 = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autoformelements.field_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autoformelements WHERE ((".$config['table_prefix']."autodbelements.auto_id = $current_ID) AND (".$config['table_prefix']."autoformelements.display_on_browse = 'Yes') AND (".$config['table_prefix']."autoformelements.field_type <> 'textarea') AND (".$config['table_prefix']."autodbelements.field_name = ".$config['table_prefix']."autoformelements.field_name)) ORDER BY ".$config['table_prefix']."autoformelements.rank";
					$recordSet2 = $conn->Execute($sql2);
						if ($recordSet2 === false)
						{
							log_error($sql2);
						}
					while (!$recordSet2->EOF)
					{
						$field_value = make_db_unsafe ($recordSet2->fields['field_value']);
						$field_type = make_db_unsafe ($recordSet2->fields['field_type']);
						echo "<td align=\"center\" class=\"search_row_$count\">";

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
					$sql2 = "SELECT ".$config['table_prefix']."autodbelements.field_value, ".$config['table_prefix']."autoformelements.field_type FROM ".$config['table_prefix']."autodbelements, ".$config['table_prefix']."autoformelements WHERE ((".$config['table_prefix']."autodbelements.auto_id = $current_ID) AND (".$config['table_prefix']."autoformelements.display_on_browse = 'Yes') AND (".$config['table_prefix']."autoformelements.field_type = 'textarea') AND (".$config['table_prefix']."autodbelements.field_name = ".$config['table_prefix']."autoformelements.field_name)) ORDER BY ".$config['table_prefix']."autoformelements.rank";
					$recordSet2 = $conn->Execute($sql2);
						if ($recordSet2 === false)
						{
							log_error($sql2);
						}
					while (!$recordSet2->EOF)
					{
						$field_value = make_db_unsafe ($recordSet2->fields['field_value']);
						$field_caption = make_db_unsafe ($recordSet2->fields['field_caption']);
						echo "<tr><td colspan=\"$num_columns\" class=\"search_row_$count\">$field_value</td></tr>";
						$recordSet2->MoveNext();
					} // end while


					$resultRecordSet->MoveNext();
				} // end while


				?>


		</table>

		<?php
	} // end if ($num_rows > 0)
	else
	{
		echo "<p>$lang[search_no_results]</p>";
	}

?>
<?CloseTable();include("../../footer.php");?>