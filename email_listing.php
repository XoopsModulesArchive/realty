<?php
include("../../mainfile.php");
include("../../header.php");
OpenTable();
?>
<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* 		Open-Realty/Auto Modification � RealtyOne outback web creations		 */
/*			Page Based on Open-Realty 1.2.0 Unreleased � RealtyOne			 */
/* 	 Overall content based on Open-Realty � Ryan Bonham transparent tech	 */
/*	This mod and all attached files remain under the Open-Realty gpl Licence */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	include("include/common.php");
	include("$config[template_path]/user_top.html");
	global $conn, $config, $lang, $type, $auto, $listing, $action;
//language info for this page
$lang['email_auto_default_subject'] = "Vehicle from $sender";
$lang['email_auto_default_message'] = "Your friend, $sender, has sent along the following link:\r\n".$config['baseurl']."/autoview.php?autoID=".$autoID."\r\n\r\n".$comment;
$lang['email_auto_sent'] = "The vehicle has been sent to";
$lang['email_auto_send_vehicle_to_friend'] = "Send vehicle $auto to a friend...";
	
if (!$type||$type == ""||$type == "listing")
	{
	if ($listingID != "")

	{

		if ($action == "mail")

		{

			if ($to == "")
			{
				echo ("<h3>$lang[email_listing_provide_email]</h3> ");
				include("$config[template_path]/user_bottom.html");
				exit;
			}

			if ($sender == "")
			{
				echo ("<h3>$lang[email_listing_enter_name]</h3>");
				include("$config[template_path]/user_bottom.html");
				exit;
			}

			if ($sender_email == "")
			{
				echo ("<h3>$lang[email_listing_enter_email_address]</h3>");
				include("$config[template_path]/user_bottom.html");
				exit;
			}

			$message = $lang[email_listing_default_message];

			$message = stripslashes($message);

			$header = "From: ".$sender." <".$sender_email.">\r\n";
			$header .= "X-Sender: $config[admin_email]\r\n";
			$header .= "Return-Path: $config[admin_email]\r\n";

			$temp = mail($to, $lang[email_listing_default_subject], $message, $header) or print "<h3>Could not send mail.</h3>"; 

			if ($temp = true)
			{
				echo "$lang[email_listing_sent] $to.<P><a href=\"listingview.php?listingID=$listingID\">Return to listing</a>   ";
			}

		}

		else

		{
			echo "<h3>$lang[email_listing_send_listing_to_friend]</h3>";
			echo "<form name=\"mailman\" action=\"email_listing.php\" method=\"post\"> ";
			echo "<table border=\"0\" cellpadding=2 cellspacing=0>";
			echo "<tr><td width=\"120\" align=center>$lang[email_listing_friend_email]:</td><td align=left><input type=text name=\"to\"></td></tr>";
			echo "<tr><td width=\"120\" align=center>$lang[email_listing_your_name]:</td><td align=left><input type=text name=\"sender\"></td></tr>";
			echo "<tr><td width=\"120\" align=center>$lang[email_listing_your_email]:</td><td align=left><input type=text name=\"sender_email\"></td></tr>";

			echo "<tr><td width=\"120\" align=center><b>$lang[email_listing_your_message]:</b></td><td align=left><b>SUBJECT:</b>$lang[email_listing_default_subject]<br><b>INTRO:</b>$lang[email_listing_default_message]<br><textarea name=\"comment\" cols=60 rows=4></textarea></td></tr>";
			echo "<input type=\"hidden\" name=\"type\" value=\"listing\">";
			echo "<input type=\"hidden\" name=\"action\" value=\"mail\">";
			echo "<input type=\"hidden\" name=\"listingID\" value=\"$listingID\">";
			echo "<tr><td></td><td align=\"middle\"><input type=\"submit\" value=\"$lang[email_listing_send]\"></td></tr>";
			echo "</table></form>";

		} // end else action != mail

	} // end ($listingID != "")

}//end listing
/*	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	*/

/*	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	*/



$lang['email_auto_default_subject'] = "Vehicle from $sender";
$lang['email_auto_default_message'] = "Your friend, $sender, has sent along the following link:\r\n".$config['baseurl']."/autoview.php?autoID=".$autoID."\r\n\r\nComment:\r\n".$comment;
$lang['email_auto_sent'] = "The Vehicle has been sent to";
$lang['email_auto_send_vehicle_to_friend'] = "Send vehicle $auto to a friend...";



if ($type == "auto")
	{

	if ($autoID != "")

	{

		if ($action == "mail")

		{

			if ($to == "")
			{
				echo ("<h3>$lang[email_listing_provide_email]</h3> ");
				include("$config[template_path]/user_bottom.html");
				exit;
			}

			if ($sender == "")
			{
				echo ("<h3>$lang[email_listing_enter_name]</h3>");
				include("$config[template_path]/user_bottom.html");
				exit;
			}

			if ($sender_email == "")
			{
				echo ("<h3>$lang[email_listing_enter_email_address]</h3>");
				include("$config[template_path]/user_bottom.html");
				exit;
			}

			$message = $lang[email_auto_default_message];

			$message = stripslashes($message);

			$header = "From: ".$sender." <".$sender_email.">\r\n";
			$header .= "X-Sender: $config[admin_email]\r\n";
			$header .= "Return-Path: $config[admin_email]\r\n";

			$temp = mail($to, $lang[email_auto_default_subject], $message, $header) or print "<h3>Could not send mail.</h3>"; 

			if ($temp = true)
			{
				echo "$lang[email_auto_sent] $to.<P><a href=\"autoview.php?autoID=$autoID\">Return to auto</a>   ";
			}

		}

		else

		{
			echo "<h3>$lang[email_auto_send_vehicle_to_friend]</h3>";
			echo "<form name=\"mailman\" action=\"email_listing.php\" method=\"post\"> ";
			echo "<table border=\"0\" cellpadding=2 cellspacing=0>";
			echo "<tr><td width=\"120\" align=center>$lang[email_listing_friend_email]:</td><td align=left><input type=text name=\"to\"></td></tr>";
			echo "<tr><td width=\"120\" align=center>$lang[email_listing_your_name]:</td><td align=left><input type=text name=\"sender\"></td></tr>";
			echo "<tr><td width=\"120\" align=center>$lang[email_listing_your_email]:</td><td align=left><input type=text name=\"sender_email\"></td></tr>";

			echo "<tr><td width=\"120\" align=center><b>$lang[email_listing_your_message]:</b></td><td align=left><b>SUBJECT:</b>$lang[email_auto_default_subject]<br><b>INTRO:</b>$lang[email_auto_default_message]<br><textarea name=\"comment\" cols=60 rows=4></textarea></td></tr>";
			echo "<input type=\"hidden\" name=\"type\" value=\"auto\">";
			echo "<input type=\"hidden\" name=\"action\" value=\"mail\">";
			echo "<input type=\"hidden\" name=\"autoID\" value=\"$autoID\">";
			echo "<tr><td></td><td align=\"middle\"><input type=\"submit\" value=\"$lang[email_listing_send]\"></td></tr>";
			echo "</table></form>";

		} // end else action != mail

	} // end ($autoID != "")

}//end auto

	

	
?>
<?
CloseTable();
include("../../footer.php");
?>