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
global $conn, $config, $lang, $listingID, $autoID, $action;


/************************************* */
if (!$type||$type == "")
{
echo "<h3>You must have something to email!</h3><meta http-equiv=\"refresh\" content=2;URL=javascript:history.go(-1)>";
}

if ($type == "listing")

{
if ($listingID != "")

{

if ($action == "mail")
{
      if ($to == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_provide_email'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_name'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_email == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_email_address'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_phone == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_phone'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_address == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_address'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_view == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_view_date'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_time == "--")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_view_time'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }

$message = $lang['appointment_default_message_listing'];

$message = stripslashes($message);

$header = "From: ".$sender." <".$sender_email.">\r\n";
$header .= "X-Sender: $config[admin_email]\r\n";
$header .= "Return-Path: $config[admin_email]\r\n";

$temp = mail($to, $lang['appointment_default_subject'], $message, $header) or print "<h3>Could not send mail.</h3>";

if ($temp = true)
{
echo $lang['appointment_sent'].' to '.$to.'<P><a href="listingview.php?listingID='.$listingID.'">Return to listing</a>';
}

}

else

{

//function appointment_listing_name($listingID)
//{
global $conn, $config, $lang;
$listingID = make_db_unsafe($listingID);
$sql = "SELECT " . $config['table_prefix'] . "listingsDB.user_ID, " . $config['table_prefix'] . "listingsDB.Title, " . $config['table_prefix'] . "listingsDB.expiration, " . $config['table_prefix'] . "UserDB.user_name, " . $config['table_prefix'] . "UserDB.emailAddress FROM " . $config['table_prefix'] . "listingsDB, " . $config['table_prefix'] . "UserDB WHERE ((" . $config['table_prefix'] . "listingsDB.ID = $listingID) AND (" . $config['table_prefix'] . "UserDB.ID = " . $config['table_prefix'] . "listingsDB.user_ID))";
$recordSet = $conn->Execute($sql);
if ($recordSet === false)
{
log_error($sql);
}
while (!$recordSet->EOF)
{
$listing_user_ID = make_db_unsafe ($recordSet->fields['user_ID']);
$listing_Title = make_db_unsafe ($recordSet->fields['Title']);
$listing_user_name = make_db_unsafe ($recordSet->fields['user_name']);
$listing_emailAddress = make_db_unsafe ($recordSet->fields['emailAddress']);

echo '<h3>'.$lang['appointment_send_header'].'"'.$listing_Title.'" Listing ID # "'.$listingID.'"</h3>
<div align="center"><INPUT TYPE= "button" NAME= "back" VALUE=" '.$lang['return_to_listing'].' number '.$listingID.'" onClick= "history.go(-1)" ></div>
<form name="mailman" action="appointment.php" method="post">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
<td width="120" align="center">'.$lang['appointment_agents_email'].':</td>
<td align="left">
<input size="50" type="text" name="to" value="'.$listing_emailAddress.'" readonly></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_name'].':</td>
<td align="left"><input size="50" type="text" name="sender"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_email'].':</td>
<td align="left"><input size="50" type="text" name="sender_email"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_phone'].':</td>
<td align="left"><input size="50" type="text" name="sender_phone"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_address'].':</td>
<td align="left"><input size="50" type="text" name="sender_address"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_listing_title'].':</td>
<td align="left"><input size="30" type="text" readonly name="listing_title" value="'.$listing_Title.'">&nbsp;&nbsp;&nbsp;'.$lang['appointment_listing_siteid'].':&nbsp;&nbsp;<input readonly size="3" type=text name="listing_siteid" value="'.$listingID.'"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_view_date'].':</td>
<td align="left"><input size="20" type="text" name="sender_view"value="">&nbsp;'.$lang['appointment_your_view_time'].':&nbsp;&nbsp;<select name="sender_time" size="1"><option value=-->--<option value=8>8am<option value=9>9am<option value=10>10am<option value=11>11am<option value=12>12pm<option value=1>1pm<option value=2>2pm<option value=3>3pm<option value=4>4pm<option value=5>5pm<option value=6>6pm</select></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_listing_url_text'].':</td>
<td align=left><input size="50" type="text" readonly name="listing_url" value="'.$config['baseurl'].'/listingview.php?listingID='.$listingID.'"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_message'].':</td>
<td align=left><textarea name="comment" cols="52" rows="4"></textarea></td>
</tr>
<input type="hidden" name="type" value="listing">
<input type="hidden" name="action" value="mail">
<input type="hidden" name="listingID" value="'.$listingID.'">
<tr>
<td></td>
<td align="middle"><input type="submit" value="'.$lang['appointment_send'].'"></td>
</tr></table></form>';

$recordSet->MoveNext();
} // end while


} // end else action != mail

} // end ($listingID != "")

}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
if ($type == "auto")

{
if ($autoID != "")

{

if ($action == "mail")
{
      if ($to == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_provide_email'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_name'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_email == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_email_address'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_phone == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_phone'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_address == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_address'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_view == "")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_view_date'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }
      
      if ($sender_time == "--")
      {
         echo '<h3 style="color:red;">'.$lang['appointment_enter_view_time'].'</h3>
         <meta http-equiv="refresh" content=2;URL=javascript:history.go(-1)>';
         include("$config[template_path]/user_bottom.html");
         exit;
      }

$message = $lang['appointment_default_message_auto'];

$message = stripslashes($message);

$header = "From: ".$sender." <".$sender_email.">\r\n";
$header .= "X-Sender: $config[admin_email]\r\n";
$header .= "Return-Path: $config[admin_email]\r\n";

$temp = mail($to, $lang['appointment_default_subject'], $message, $header) or print "<h3>Could not send mail.</h3>";

if ($temp = true)
{
echo $lang['appointment_sent'].' to '.$to.'<P><a href="autoview.php?autoID='.$autoID.'">Return to Vehicle</a>';
}

}

else

{

//function appointment_listing_name($listingID)
//{
global $conn, $config, $lang;
$autoID = make_db_unsafe($autoID);
$sql = "SELECT " . $config['table_prefix'] . "autodb.user_ID, " . $config['table_prefix'] . "autodb.Title, " . $config['table_prefix'] . "autodb.expiration, " . $config['table_prefix'] . "UserDB.user_name, " . $config['table_prefix'] . "UserDB.emailAddress FROM " . $config['table_prefix'] . "autodb, " . $config['table_prefix'] . "UserDB WHERE ((" . $config['table_prefix'] . "autodb.ID = $autoID) AND (" . $config['table_prefix'] . "UserDB.ID = " . $config['table_prefix'] . "autodb.user_ID))";
$recordSet = $conn->Execute($sql);
if ($recordSet === false)
{
log_error($sql);
}
while (!$recordSet->EOF)
{
$auto_user_ID = make_db_unsafe ($recordSet->fields['user_ID']);
$auto_Title = make_db_unsafe ($recordSet->fields['Title']);
$auto_user_name = make_db_unsafe ($recordSet->fields['user_name']);
$auto_emailAddress = make_db_unsafe ($recordSet->fields['emailAddress']);

echo '<h3>'.$lang['appointment_send_header'].'"'.$auto_Title.'" Listing ID # "'.$autoID.'"</h3>
<div align="center"><INPUT TYPE= "button" NAME= "back" VALUE=" '.$lang['return_to_listing'].' number '.$autoID.'" onClick= "history.go(-1)" ></div>
<form name="mailman" action="appointment.php" method="post">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
<td width="120" align="center">'.$lang['appointment_agents_email'].':</td>
<td align="left">
<input size="50" type="text" name="to" value="'.$auto_emailAddress.'" readonly></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_name'].':</td>
<td align="left"><input size="50" type="text" name="sender"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_email'].':</td>
<td align="left"><input size="50" type="text" name="sender_email"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_phone'].':</td>
<td align="left"><input size="50" type="text" name="sender_phone"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_address'].':</td>
<td align="left"><input size="50" type="text" name="sender_address"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_auto_title'].':</td>
<td align="left"><input size="30" type="text" readonly name="auto_title" value="'.$auto_Title.'">&nbsp;&nbsp;&nbsp;'.$lang['appointment_listing_siteid'].':&nbsp;&nbsp;<input readonly size="3" type=text name="auto_siteid" value="'.$autoID.'"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_view_date'].':</td>
<td align="left"><input size="20" type="text" name="sender_view"value="">&nbsp;'.$lang['appointment_your_view_time'].':&nbsp;&nbsp;<select name="sender_time" size="1"><option value=-->--<option value=8>8am<option value=9>9am<option value=10>10am<option value=11>11am<option value=12>12pm<option value=1>1pm<option value=2>2pm<option value=3>3pm<option value=4>4pm<option value=5>5pm<option value=6>6pm</select></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_auto_url_text'].':</td>
<td align=left><input size="50" type="text" readonly name="auto_url" value="'.$config['baseurl'].'/autoview.php?autoID='.$autoID.'"></td>
</tr><tr>
<td width="120" align="center">'.$lang['appointment_your_message'].':</td>
<td align=left><textarea name="comment" cols="52" rows="4"></textarea></td>
</tr>
<input type="hidden" name="type" value="auto">
<input type="hidden" name="action" value="mail">
<input type="hidden" name="autoID" value="'.$autoID.'">
<tr>
<td></td>
<td align="middle"><input type="submit" value="'.$lang['appointment_send'].'"></td>
</tr></table></form>';

$recordSet->MoveNext();
} // end while


} // end else action != mail

} // end ($autoID != "")

}

?> 
<?CloseTable();include("../../footer.php");?>