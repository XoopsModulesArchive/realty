<?phpinclude("../../mainfile.php");include("../../header.php");OpenTable();	
?>
<?php
	include("include/common.php");
	include("$config[template_path]/user_top.html");
	global $conn, $lang, $config, $HTTP_GET_VARS;
	$debug_GET = True;
	$guidestring = "";
	$guidestring_with_sort = "";
	
	// Save GET
	foreach ($_GET as $k => $v)
	{
		if ($v && $k != 'cur_page' && $k != 'PHPSESSID' && $k != 'sortby' && $k != 'sorttype' && $k != 'imagesOnly')
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

	$SESSION_ID = session_id();

	$sql = "drop table IF EXISTS " . $config[table_prefix] . $SESSION_ID . "temp";
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
	$sql = "CREATE TABLE " . $config[table_prefix] . $SESSION_ID . "temp SELECT " . $config[table_prefix] . "listingsDB.ID, " . $config[table_prefix] . "listingsDB.Title, " . $config[table_prefix] . "listingsDB.user_ID, " . $config[table_prefix] . "listingsDBElements.field_name, " . $config[table_prefix] . "listingsDBElements.field_value FROM " . $config[table_prefix] . "listingsDB, " . $config[table_prefix] . "listingsDBElements WHERE (" . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID) AND ";
	if ($config[use_expiration] == "yes")
	{
		$sql .= "(" . $config[table_prefix] . "listingsDB.expiration > ".$conn->DBDate(time()).") AND ";
	}
	$sql .= "(" . $config[table_prefix] . "listingsDB.active = 'yes')";

	$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
	// Create Index on temporary table to speed up searching
	if ($config[manage_index_permissions] == 'Yes')
	{
		//Host Supports Creating Indexes, so create some to speed up searching.
		$sql = "create index idx_listingid on " . $config[table_prefix] . $SESSION_ID . "temp (ID)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		$sql = "create index idx_listingname on " . $config[table_prefix] . $SESSION_ID . "temp (field_name(10))";
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
			$guidestring_with_sort .= "$ElementIndexValue=$ElementContents&amp;";
		}
		elseif ($ElementIndexValue == "sorttype")
		{
				$guidestring_with_sort .= "$ElementIndexValue=$ElementContents&amp;";
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
				$sql = "DELETE FROM " . $config[table_prefix] . $SESSION_ID . "temp WHERE User_ID <> $ElementContents";
				$recordSet = $conn->Execute($sql);

		}
		elseif ($ElementIndexValue == "imagesOnly")
		{
			$guidestring .= "$ElementIndexValue=$ElementContents&amp;";
			if ($ElementContents == "yes")
			{
				$whilecount = 0;
				$delete_string = "DELETE FROM " . $config[table_prefix] . $SESSION_ID . "temp WHERE (1=1)";
				// the 1=1 is a dumb sql trick to deal with the code below ... it works, but you can ignore it
				$sql = "SELECT " . $config[table_prefix] . $SESSION_ID . "temp.ID, COUNT(" . $config[table_prefix] . "listingsImages.file_name) AS imageCount FROM " . $config[table_prefix] . "listingsImages," . $config[table_prefix] . $SESSION_ID . "temp WHERE (" . $config[table_prefix] . "listingsImages.listing_id = " . $config[table_prefix] . $SESSION_ID . "temp.ID) GROUP BY " . $config[table_prefix] . "listingsImages.listing_id";
				$recordSet = $conn->Execute($sql);
					if ($recordSet === false)
					{
						log_error($sql);
					}
				while (!$recordSet->EOF)
				{
					$whilecount = $whilecount + 1;
					$listingID = $recordSet->fields[ID];
					$imageCount = $recordSet->fields[imageCount];
					$delete_string .= " AND ";
					$delete_string .= "(ID <> $listingID)";
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
			//echo $ElementIndexValue.': array found<br>';
			//echo count($ElementContents).'Elements found in array<br>';
			$skip = False;
			foreach($ElementContents as $a)
			{
				if (!$a)
				{
					$skip = True;
				}
				//echo '|'.$a.'|';
			}
			if ($skip == True)
			{
				continue;
			}

			reset($ElementContents);
			echo '<br><br>';
			$sql_ElementIndexValue = make_db_safe($ElementIndexValue);

			// Arrays can happen for two reasons:  1. multi options like zip code
			// 2. multi options like home features.  Check the db to see which
			// type of field this is and process accordingly
			$r = $conn->getOne("select search_type from " . $config[table_prefix] . "listingsFormElements where field_name = ".$sql_ElementIndexValue);
			if (($r == 'optionlist') || ($r == 'fcheckbox'))
			{
				$recordSet = $conn->Execute($sql);
				// Delete all records that don't have any field name by this name
				$sql = "select count(t2.field_name) as cnt, t1.id as id from " . $config[table_prefix] . $SESSION_ID . "temp t1 left join " . $config[table_prefix] . "listingsDBElements t2 on t1.id = t2.listing_id and t1.field_name = $sql_ElementIndexValue group by t1.id";
				//$sql = " select count(t2.field_name) as cnt, t1.id as id from " . $config[table_prefix] . $SESSION_ID . "temp2 t1 left join " . $config[table_prefix] . "listingsDBElements t2 on t1.id = t2.listing_id group by t1.id";
				$res = $conn->Execute($sql);
				while (!$res->EOF)
				{
					// Check for no field
					if ($res->fields['cnt'] == 0)
					{
						$conn->execute("delete from " . $config[table_prefix] . $SESSION_ID . "temp where id = " . $res->fields['id']);
					}
					else
					{
						// for each value, delete those records that don't match it
						$value = $conn->getOne("select field_value from " . $config[table_prefix] . $SESSION_ID . "temp where id = " . $res->fields['id'] . " and field_name = $sql_ElementIndexValue");
						$delete = 1;
						
						foreach ($ElementContents as $e)
						{
							if (!strstr($value, $e)) 
							{
								$conn->execute("delete from " . $config[table_prefix] . $SESSION_ID . "temp where id = " . $res->fields['id']);
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
				$select_statement = "SELECT ID FROM " . $config[table_prefix] . $SESSION_ID . "temp WHERE ( (field_name=$sql_ElementIndexValue) AND ";
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
					$save_ID = $recordSet->fields[ID];
					$save_array[] = "$save_ID";
					$recordSet->MoveNext();
				} // end while
				$num_to_delete = $recordSet->RecordCount();

				// now, delete everything that we don't want...
				if ($num_to_delete > 0)
				{
					$delete_string = "DELETE FROM " . $config[table_prefix] . $SESSION_ID . "temp WHERE ";
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
					$delete_string = "DELETE FROM " . $config[table_prefix] . $SESSION_ID . "temp";
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
					$sql = "select id, field_value as v from " . $config[table_prefix] . $SESSION_ID . "temp where field_name = '$col'";
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
					$sql = "delete from " . $config[table_prefix] . $SESSION_ID . "temp where id in (" . implode(',', $del_id) . ")";
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
					$sql = "select id, field_value as v from " . $config[table_prefix] . $SESSION_ID . "temp where field_name = '$col'";
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
					$sql = "delete from " . $config[table_prefix] . $SESSION_ID . "temp where id in (" . implode(',', $del_id) . ")";
					if (sizeof($del_id))
					{
						$conn->execute($sql);
					}
					continue;
				}
			}

		if (!$ElementContents) continue;
			$ElementIndexValue = make_db_safe($ElementIndexValue);
			$select_statement = "SELECT ID FROM " . $config[table_prefix] . $SESSION_ID . "temp WHERE ( (field_name = $ElementIndexValue) AND (field_value = $ElementContents) )";
			$recordSet = $conn->Execute($select_statement);
				if ($recordSet === false)
				{
					log_error($select_statement);
				}
			$save_array = array();
			while (!$recordSet->EOF)
			{
				$save_ID = $recordSet->fields[ID];
				$save_array[] = "$save_ID";
				$recordSet->MoveNext();
			} // end while
			$num_to_delete = $recordSet->RecordCount();
			if ($num_to_delete > 0)
			{
				$delete_string = "DELETE FROM " . $config[table_prefix] . $SESSION_ID . "temp WHERE ";
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
				$delete_string = "DELETE FROM " . $config[table_prefix] . $SESSION_ID . "temp";
				$recordSet = $conn->Execute($delete_string);
					if ($recordSet === false)
					{
						log_error($delete_string);
					}
			} // end elseif ($num_to_delete = 0)

		} // end else
	} // end while


		// this is the main SQL that grabs the listings
		// basic sort by title..
		if ($sortby == "")
		{
			$sortby = make_db_extra_safe(price);
			$sort_text = "WHERE (field_name = 'price')";
			$order_text = "ORDER BY field_value +0 ASC";
		}
		elseif ($sortby == "listingname")
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

		$sql = "SELECT * from " . $config[table_prefix] . $SESSION_ID . "temp $sort_text GROUP BY ID $order_text";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		
		$num_rows = $recordSet->RecordCount();
		if ($num_rows > 0)
		{
			if ($sortby == "")
						{
							$sorttypestring = "";
						}
						else
						{
							$sorttypestring = "sorttype=$sorttype&amp;";
						}
echo "<table width=\"100%\" border=\"0\">";
echo "<tr><td align=\"center\" width=\"100%\"><b>Sort Listings By:</b> <a href=\"$PHP_SELF?sortby=price&amp;$sorttypestring$guidestring\">Price</a> | <a href=\"$PHP_SELF?sortby=beds&amp;$sorttypestring$guidestring\">Bedrooms</a> | <a href=\"$PHP_SELF?sortby=city&amp;$sorttypestring$guidestring\">City</a> | <a href=\"$PHP_SELF?sortby=zip&amp;$sorttypestring$guidestring\">Zip Code</a></td></tr>";

echo "<tr><td align=\"center\" width=\"100%\">";

			// build the string to select a certain number of listings per page
			$limit_str = $cur_page * $config[listings_per_page];
			$resultRecordSet = $conn->SelectLimit($sql, $config[listings_per_page], $limit_str );
				if ($resultRecordSet === false)
				{
					log_error($sql);
				}

			?>

					 <?php
						if  (($sortby == "listingname") ||($sortby == "")) 
						{
							$sorttypestring = "sorttype=$sorttype&amp;";
						}
					?>
				<?php
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

					$current_ID = $resultRecordSet->fields[ID];

					// grab the listing's image
					$sql2 = "SELECT thumb_file_name FROM " . $config[table_prefix] . "listingsImages WHERE listing_id = $current_ID ORDER BY rank";
					$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
					if ($recordSet2 === false)
					{
						log_error($sql2);
					}
					$num_images = $recordSet2->RecordCount();
					if ($num_images == 0)
					{
							echo "<center><table class=\"search_row_$count\" border=\"3\" cellspacing=\"0\" cellpadding=\"0\" width=\"580\" bordercolorlight=\"#C0C0C0\" bordercolordark=\"#808080\" bordercolor=\"#FFFFFF\" style=\"border-collapse: collapse\"><tr><td>";
							echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"580\" class=\"form_main\" style=\"border-collapse: collapse\"><tr><td rowspan=\"2\" align=\"left\" width=\"104\">";
							echo "<a href=\"listingview.php?listingID=$current_ID\"><img src=\"images/nophoto.gif\" border=\"1\" alt=\"Sorry, No Photo Available\"></a></td>";
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
							echo "<center><table class=\"search_row_$count\" border=\"3\" cellspacing=\"0\" cellpadding=\"0\" width=\"580\" bordercolorlight=\"#C0C0C0\" bordercolordark=\"#808080\" bordercolor=\"#FFFFFF\" style=\"border-collapse: collapse\"><tr><td>";
							echo "<table class=\"search_row_$count\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"580\" style=\"border-collapse: collapse\"><tr><td class=\"search_row_$count\" rowspan=\"2\" align=\"left\" width=\"5%\">";
							echo "<a href=\"listingview.php?listingID=$current_ID\"><img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" border=\"1\" alt=\"$thumb_file_name\"></a></td>";
						} // end if ($thumb_file_name != "")
						$recordSet2->MoveNext();
					} // end while

					// grab the rest of the listing's data
					$sql2 = "SELECT " . $config[table_prefix] . "listingsDBElements.field_value, " . $config[table_prefix] . "listingsFormElements.field_name FROM " . $config[table_prefix] . "listingsDBElements, " . $config[table_prefix] . "listingsFormElements WHERE ((" . $config[table_prefix] . "listingsDBElements.listing_id = $current_ID) AND (" . $config[table_prefix] . "listingsDBElements.field_name = " . $config[table_prefix] . "listingsFormElements.field_name)) ORDER BY " . $config[table_prefix] . "listingsFormElements.rank";
					$recordSet2 = $conn->Execute($sql2);
						if ($recordSet2 === false)
						{
							log_error($sql2);
						}
					while (!$recordSet2->EOF)
					{
								if ($recordSet2->fields[field_name] == "price") { $price = $recordSet2->fields[field_value]; };		
								if ($recordSet2->fields[field_name] == "baths") { $baths = $recordSet2->fields[field_value]; };
								if ($recordSet2->fields[field_name] == "beds") { $beds = $recordSet2->fields[field_value]; };
								if ($recordSet2->fields[field_name] == "address") { $address = $recordSet2->fields[field_value]; };
								if ($recordSet2->fields[field_name] == "city") { $city = $recordSet2->fields[field_value]; };
								if ($recordSet2->fields[field_name] == "state") { $state = $recordSet2->fields[field_value]; };
								if ($recordSet2->fields[field_name] == "zip") { $zip = $recordSet2->fields[field_value]; };
								if ($recordSet2->fields[field_name] == "sq_feet") { $sq_feet = $recordSet2->fields[field_value]; };
								if ($recordSet2->fields[field_name] == "full_desc") { $full_desc = $recordSet2->fields[field_value]; };
								$priceformatted = international_num_format($price);
								$preview = substr($full_desc, 0, 100);
						$recordSet2->MoveNext();
					} // end while

								echo "<td class=\"search_row_$count\" align=\"left\" width=\"50%\" bordercolorlight=\"#FFFFFF\" bordercolordark=\"#FFFFFF\" valign=\"top\"><p align=\"left\"><a href=\"listingview.php?listingID=$current_ID\"><font face=\"Arial,Helvetica,sans-serif\" size=\"2\"><b>$address</b></font></a><br><font face=\"Arial,Helvetica,sans-serif\" size=\"2\">$city, $state $zip</font>";
								echo "<td class=\"search_row_$count\" rowspan=\"2\" align=\"right\" width=\"45%\" valign=\"top\"><p align=\"right\"><font face=\"Arial,Helvetica,sans-serif\" size=\"4\">";
								Print("<b>".money_formats($priceformatted)."</b></font><br>");
								echo "<font face=\"Arial,Helvetica,sans-serif\" size=\"2\">$beds Beds, $baths Baths<br>$sq_feet Sq. Ft.</font>";
								echo "<br></tr>";
								echo "<tr><td class=\"search_row_$count\" align=\"left\" width=\"50%\" bordercolorlight=\"#FFFFFF\" bordercolordark=\"#FFFFFF\" valign=\"top\">";
								//Space for additional information in cell that appears below the Address, City & State
								echo "</td></tr>";
								echo "<tr><td class=\"search_row_$count\" align=\"left\" width=\"580\" colspan=\"3\"><font size=\"1\">$preview";
					
					echo "... </font><b><font size=\"1\"><a href=\"listingview.php?listingID=$current_ID\">More Info.</a></font></b></td></tr>";
					echo "</table>";
					echo "</td></tr></table><br>";
					$resultRecordSet->MoveNext();
					} // end while

					echo "</center></center></td></tr></table>";
					echo "<center>";
					next_prev($num_rows, $cur_page, $guidestring_with_sort); // put in the next/previous stuff
					echo "</center><br><br>";
					
include "footer.php"; 


					$sql = "drop table IF EXISTS " . $config[table_prefix] . $SESSION_ID . "temp";
					$recordSet = $conn->Execute($sql);
					if (!$recordSet) log_error($sql);
					?>
<?php
	} // end if ($num_rows > 0)
	else
	{
		echo "<p>$lang[search_no_results]</p>";
	}

?>
<?CloseTable();include("../../footer.php");?>