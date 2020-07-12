<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* 		Open-Realty/Auto Modification © RealtyOne outback web creations		 */
/*			Page Based on Open-Realty 1.2.0 Unreleased © RealtyOne			 */
/* 	 Overall content based on Open-Realty © Ryan Bonham transparent tech	 */
/*	This mod and all attached files remain under the Open-Realty gpl Licence */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
include("../include/common.php");
loginCheck('Admin');
global $lang, $action, $id, $cur_page, $edit, $conn, $config;
global $activatelisting, $activateauto, $deletelisting, $deleteauto;

if ($activatelisting != "")
	{
	$sql_activate = make_db_safe($activatelisting);
global $conn,$config,$lang,$style;
$sql="UPDATE ".$config['table_prefix']."listingsDB SET active='yes' WHERE (ID = $sql_activate)";
$recordSet = $conn->Execute($sql);
if ($recordSet === false) log_error($sql);
} 
if ($activateauto != "")
	{
	$sql_activate = make_db_safe($activateauto);
global $conn,$config,$lang,$style;
$sql="UPDATE ".$config['table_prefix']."autodb SET active='yes' WHERE (ID = $sql_activate)";
$recordSet = $conn->Execute($sql);
if ($recordSet === false) log_error($sql);
} 
	if ($deletelisting != "")
	{
		// delete a listing
		$sql_delete = make_db_safe($deletelisting);
		$sql = "DELETE FROM " . $config['table_prefix'] . "listingsDB WHERE (ID = $sql_delete)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
				
		$sql = "DELETE FROM " . $config['table_prefix'] . "listingsDBElements WHERE (listing_id = $sql_delete)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		
		echo "<p>$lang[admin_listings_editor_listing_number] '$delete' $lang[has_been_deleted]</p>";
		log_action ("$lang[log_deleted_listing] $delete");
	}
	if ($deleteauto != "")
	{
		// delete a vehicle
		$sql_delete = make_db_safe($deleteauto);
		$sql = "DELETE FROM " . $config['table_prefix'] . "autodb WHERE (ID = $sql_delete)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
				
		$sql = "DELETE FROM " . $config['table_prefix'] . "autodbelements WHERE (auto_id = $sql_delete)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}
		
		echo "<p>$lang[admin_listings_editor_listing_number] '$delete' $lang[has_been_deleted]</p>";
		log_action ("$lang[log_deleted_listing] $delete");
	}//end delete auto
	
	// show all the listings inactive listings
	echo "<h3>$lang[admin_inactive_listings_queue]</h3>";
	
	// grab the number of listings from the db
	$sql = "SELECT ID, Title, notes, expiration, active FROM " . $config['table_prefix'] . "listingsDB WHERE active <> 'yes' ORDER BY ID ASC";
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
	$num_rows = $recordSet->RecordCount();
	
	next_prev($num_rows, $cur_page, ""); // put in the next/previous stuff
	
	// build the string to select a certain number of listings per page
	$limit_str = $cur_page * $config['listings_per_page'];
	$recordSet = $conn->SelectLimit($sql, $config['listings_per_page'], $limit_str );
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
		$title = make_db_unsafe ($recordSet->fields['Title']);
		$notes = make_db_unsafe ($recordSet->fields['notes']);
		$active = make_db_unsafe ($recordSet->fields['active']);
		$formatted_expiration = $recordSet->UserTimeStamp($recordSet->fields['expiration'],'D M j Y');
		$ID = $recordSet->fields['ID'];
		
		?>
		<table border="<?php echo $style['admin_listing_border'] ?>" cellspacing="<?php echo $style['admin_listing_cellspacing'] ?>" cellpadding="<?php echo $style['admin_listing_cellpadding'] ?>" width="<?php echo $style['admin_table_width'] ?>" class="form_main">
		<?php
		echo "<tr><td align=\"right\" width=\"200\" class=\"row1_$count\"><B><span class=\"adminListingLeft_$count\">$lang[admin_listings_editor_listing_number]: $ID</b></span></td><td class=\"row2_$count\">$lang[admin_listing_expires]:$formatted_expiration</td><td align=\"center\" class=\"row2_$count\" width=\"310\"> <B> <a href=\"listings_edit.php?edit=$ID\">$lang[admin_listings_editor_modify_listing]</a></b></td><td width=120 align=middle class=\"row2_$count\"><a href=\"$PHP_SELF?deletelisting=$ID\" onClick=\"return confirmDelete()\">$lang[admin_listings_editor_delete_listing]</a></td></tr>";	
		echo "<tr><td align=\"center\" valign=\"middle\" class=\"row3_$count\">$title";
		echo "</td><td colspan=\"2\" class=\"row3_$count\">$notes";
		if ($config['use_expiration'] == "yes")
		{
			echo "<br><br><b>$lang[expiration]</b>: $formatted_expiration";
		}
		if ($active != "yes")
		{
			echo "<br><font color=\"red\">$lang[this_listing_is_not_active]</font></b>";
		}
		echo "</td>";
		echo "<td class=\"row3_$count\" align=\"center\"><a href=\"$PHP_SELF?activatelisting=$ID\">$lang[admin_activate_listing]</a></td></tr></table><br><br>\r\n\r\n";
		$recordSet->MoveNext();
	} // end while

	?>


	<P><hr>
	</P>
	<?php
	// show all the listings inactive listings
	echo "<h3>$lang[admin_inactive_auto_queue]</h3>";
	
	// grab the number of listings from the db
	$sql = "SELECT ID, Title, notes, expiration, active FROM " . $config['table_prefix'] . "autodb WHERE active <> 'yes' ORDER BY ID ASC";
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
	$num_rows = $recordSet->RecordCount();
	
	next_prev($num_rows, $cur_page, ""); // put in the next/previous stuff
	
	// build the string to select a certain number of listings per page
	$limit_str = $cur_page * $config['listings_per_page'];
	$recordSet = $conn->SelectLimit($sql, $config['listings_per_page'], $limit_str );
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
		$title = make_db_unsafe ($recordSet->fields['Title']);
		$notes = make_db_unsafe ($recordSet->fields['notes']);
		$active = make_db_unsafe ($recordSet->fields['active']);
		$formatted_expiration = $recordSet->UserTimeStamp($recordSet->fields['expiration'],'D M j Y');
		$ID = $recordSet->fields['ID'];
		
		?>
		<table border="<?php echo $style['admin_listing_border'] ?>" cellspacing="<?php echo $style['admin_listing_cellspacing'] ?>" cellpadding="<?php echo $style['admin_listing_cellpadding'] ?>" width="<?php echo $style['admin_table_width'] ?>" class="form_main">
		<?php
		echo "<tr><td align=\"right\" width=\"200\" class=\"row1_$count\"><B><span class=\"adminListingLeft_$count\">$lang[admin_listings_editor_listing_number]: $ID</b></span></td><td class=\"row2_$count\">$lang[admin_listing_expires]:$formatted_expiration</td><td align=\"center\" class=\"row2_$count\" width=\"310\"> <B> <a href=\"auto_edit.php?edit=$ID\">$lang[admin_listings_editor_modify_listing]</a></b></td><td width=120 align=middle class=\"row2_$count\"><a href=\"$PHP_SELF?deleteauto=$ID\" onClick=\"return confirmDelete()\">$lang[admin_listings_editor_delete_listing]</a></td></tr>";	
		echo "<tr><td align=\"center\" valign=\"middle\" class=\"row3_$count\">$title";
		echo "</td><td colspan=\"2\" class=\"row3_$count\">$notes";
		if ($config['use_expiration'] == "yes")
		{
			echo "<br><br><b>$lang[expiration]</b>: $formatted_expiration";
		}
		if ($active != "yes")
		{
			echo "<br><font color=\"red\">$lang[this_listing_is_not_active]</font></b>";
		}
		echo "</td>";
		echo "<td class=\"row3_$count\" align=\"center\"><a href=\"$PHP_SELF?activateauto=$ID\">$lang[admin_activate_listing]</a></td></tr></table><br><br>\r\n\r\n";
		$recordSet->MoveNext();
	} // end while

	?>


	<P>
	</P>

	<?php
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
?>