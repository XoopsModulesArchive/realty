<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>
<?php
//error_reporting(E_ALL);
	include("include/common.php");
	include("$config[template_path]/user_top.html");
	global $conn, $config, $lang;

	
	if ($listingID != "")

	{

		if ($action == "mail")

		{
			#We need to make the email with the information here#
			$sender_email_message = "$lang[sender_email_message]";
			$verifymessage = "$lang[appointment_default_message]";
			$message = "$lang[appointment_email_message]";

			$message = stripslashes($message);

			$header = "From: ".$sender." <".$sender_email.">\r\n";
			$header .= "X-Sender: $config[admin_email]\r\n";
			$header .= "Return-Path: $config[admin_email]\r\n";

			#The mail has been sent to the site admin#
			mail($sender_email, "Your appointment will be scheduled with $listingagent with $config[company_name]", $sender_email_message, $header);
			#The mail has been sent to the sender#
			mail("$config[admin_email]", "Your appointment will be scheduled with $listingagent", $message, $header);

			$temp = mail($to, $lang[appointment_default_subject], $message, $header) or print "<h3>Could not send mail.</h3>"; 


			//if ($temp = true)
			//{
			#The mail has been sent so we will add this to the count#
//$sql = "UPDATE ".$config['table_prefix']."listingsDB SET views=views+1 WHERE ID='$listingID'";
				//$recordSet = $conn->Execute($sql);
						//if ($recordSet === false)
							//{
								//log_error($sql);
							//}
			//}//end count it
			
				echo "<P>$lang[appointment_sent] $to.</p>";
				echo "<p>$verifymessage</p>";
				echo "<p><a href=\"listingview.php?listingID=$listingID\">Return to listing</a></p>   ";



		}//end if

		else

		{
		function appointment_listing_name($listingID)
	{
		// get the main data for a given listing
		global $conn, $config, $lang;
		$listingID = make_db_extra_safe($listingID);
		$sql = "SELECT " . $config['table_prefix'] . "listingsDB.user_ID, " . $config['table_prefix'] . "listingsDB.Title, " . $config['table_prefix'] . "listingsDB.expiration, " . $config['table_prefix'] . "UserDB.user_name FROM " . $config['table_prefix'] . "listingsDB, " . $config['table_prefix'] . "UserDB WHERE ((" . $config['table_prefix'] . "listingsDB.ID = $listingID) AND (" . $config['table_prefix'] . "UserDB.ID = " . $config['table_prefix'] . "listingsDB.user_ID))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false)
		{
			log_error($sql);
		}

		// get main listings data
		while (!$recordSet->EOF)
		{
			$listing_user_ID = make_db_unsafe ($recordSet->fields['user_ID']);
			$listing_Title = make_db_unsafe ($recordSet->fields['Title']);
			$listing_expiration = make_db_unsafe ($recordSet->fields['expiration']);
			$listing_user_name = make_db_unsafe ($recordSet->fields['user_name']);
			$recordSet->MoveNext();
		} // end while
			echo "$listing_Title";
		
	} // function getMainListingData
	
	
	function appointmentemail($listingID)
	{
		global $conn, $lang, $config;
		$sql = "SELECT " . $config['table_prefix'] . "UserDB.emailAddress FROM " . $config['table_prefix'] . "listingsDB, " . $config['table_prefix'] . "UserDB WHERE ((" . $config['table_prefix'] . "listingsDB.ID = $listingID) AND (" . $config['table_prefix'] . "UserDB.ID = " . $config['table_prefix'] . "listingsDB.user_ID))";
$recordSet = $conn->Execute($sql);

		if ($recordSet === false)
		{
			log_error($sql);
		}
		
		// return the email address
		while (!$recordSet->EOF)
		{
			$listing_emailAddress = make_db_unsafe ($recordSet->fields['emailAddress']);
echo "$listing_emailAddress";
			$recordSet->MoveNext();
		} // end while
		
		}
	
			
	function appointmentagent($listingID)
	{
		global $conn, $lang, $config;
		$sql = "SELECT " . $config['table_prefix'] . "UserDB.user_name FROM " . $config['table_prefix'] . "listingsDB, " . $config['table_prefix'] . "UserDB WHERE ((" . $config['table_prefix'] . "listingsDB.ID = $listingID) AND (" . $config['table_prefix'] . "UserDB.ID = " . $config['table_prefix'] . "listingsDB.user_ID))";
$recordSet = $conn->Execute($sql);

		if ($recordSet === false)
		{
			log_error($sql);
		}
		
		// return the email address
		while (!$recordSet->EOF)
		{
			$listing_agent = make_db_unsafe ($recordSet->fields['user_name']);
echo "$listing_agent";
			$recordSet->MoveNext();
		} // end while
		
		}
	?>

<link rel="stylesheet" type="text/css" href="<?php echo $config[baseurl] ?>/spiffy/spiffyCal_v2_1.css">
<script language="JavaScript" src="<?php echo $config[baseurl] ?>/spiffy/spiffyCal_v2_1.js"></script>
		
<script language="javascript">

	var cal1=new ctlSpiffyCalendarBox("cal1", "viewit", "sender_view","btnDate1","",scBTNMODE_CALBTN);
</script> 
<div id="spiffycalendar" class="text">&nbsp;</div> 

<?php
		echo "<h3>$lang[appointment_send_header]&quot;";
appointment_listing_name($listingID);
echo "&quot; <br>with ";
appointmentagent($listingID);
echo " </h3> ";
echo "<div align=\"center\"><INPUT TYPE= \"button\" NAME= \"back\" VALUE=\" $lang[return_to_listing] number $listingID\" onClick= \"history.go(-1)\" ></div>";
echo "<form name=\"viewit\" action=\"appointment.php\" method=\"post\"> ";
echo "<table border=\"0\" cellpadding=2 cellspacing=0>";?>

<input type="hidden" name="to" value="<? appointmentemail($listingID) ?>">

	<?		echo "<tr><td width=\"120\" align=center>$lang[appointment_your_name]:</td><td align=left><input size=\"50\" type=text name=\"sender\"></td></tr>";

			echo "<tr><td width=\"120\" align=center>$lang[appointment_your_email]:</td><td align=left><input size=\"50\" type=text name=\"sender_email\"></td></tr>";

echo "<tr><td width=\"120\" align=center>$lang[appointment_your_phone]:</td><td align=left><input size=\"50\" type=text name=\"sender_phone\"></td></tr>";

// echo "<tr><td width=\"120\" align=center>$lang[appointment_your_address]:</td><td align=left><input size=\"50\" type=text name=\"sender_address\"></td></tr>";

// echo "<tr><td width=\"120\" align=center>$lang[appointment_listing_title]:</td><td align=left><input size=\"30\" type=hidden readonly name=\"listing_title\" value=\"";
// appointment_listing_name($listingID);
// echo "\">&nbsp;$lang[appointment_listing_siteid]:&nbsp;&nbsp;<input readonly size=\"3\" type=hidden name=\"listing_siteid\" value=\"$listingID\"></td></tr>";
echo "<input type=\"hidden\" name=\"listing_siteid\" value=\"$listingID\">";
// echo "<tr><td width=\"120\" align=center>$lang[appointment_your_view_date]:</td><td align=left><input size=\"20\" type=text name=\"sender_view\" value=\"\">&nbsp;"; 
echo "<tr><td width=\"120\" align=center>$lang[appointment_your_view_date]:</td><td align=left>"; ?>
<script language="javascript">cal1.writeControl();</script></td></tr>
<?php echo "<tr><td width=\"120\" align=center>$lang[appointment_your_view_time]:</td><td align=left><select name=\"sender_time\" size=\"1\"><option value=-->--<option value=8am>8am<option value=9am>9am<option value=10am>10am<option value=11am>11am<option value=12pm>12pm<option value=1pm>1pm<option value=2pm>2pm<option value=3pm>3pm<option value=4pm>4pm<option value=5pm>5pm<option value=6pm>6pm</select></td></tr>";

// echo "<tr><td width=\"120\" align=center>$lang[appointment_listing_url_text]:</td><td align=left><input readonly size=\"50\" type=text name=\"listing_url\" value=\"$lang[appointment_listing_url]\"></td></tr>";
echo "<input type=\"hidden\" name=\"listing_url\" value=\"$lang[appointment_listing_url]\">";

			echo "<tr><td width=\"120\" align=center>$lang[appointment_your_message]:</td><td align=left><textarea name=\"comment\" cols=52 rows=4></textarea></td></tr>";
			echo "<input type=\"hidden\" name=\"action\" value=\"mail\">";
			echo "<input type=\"hidden\" name=\"listingID\" value=\"$listingID\">"; ?>
<input type="hidden" name="listing_title" value="<? appointment_listing_name($listingID) ?>">
<input type="hidden" name="listingagent" value="<? appointmentagent($listingID) ?>">
		<?php	echo "<input type=\"hidden\" name=\"listingusername\" value=\"$listing_user_name\">";
			echo "<tr><td></td><td align=\"middle\"><input type=\"submit\" value=\"$lang[appointment_send]\"></td></tr>";
			echo "</table></form>";
echo "<br><br><br><//----------Please do not remove-----------//><center>Original Software - Open-Reality By: <a href='http://www.jonroig.com' target='_blank'>Jon Roig</a> & <a href='http://www.open-realty.org' target='_blank'>Ryan Bonham (Open-Realty.org)</a><br>Scripting for E-xoops <a href='http://www.liquidgfx.com' target='_blank'>Liquid GFX Inc</a></center>";
			
			
				} // end else action != mail

	} // end ($listingID != "")

	else

	{

		echo "<h3>You must have something to email!</h3>";

	}


?>
<?
CloseTable();
include("../../footer.php");
?>
