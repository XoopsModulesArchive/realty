<?

global $listingID, $sender, $listing_title, $sender_view, $listing_url;
global $sender_time, $sender_phone, $sender_email, $comment;
$lang['appointment_send_header'] = "Arrange To View ";
$lang['appointment_your_name'] = "Your Name";
$lang['appointment_your_email'] = "Your Email";
$lang['appointment_your_phone'] = "Your Phone";
$lang['appointment_your_address'] = "Your Address";
$lang['appointment_listing_title'] = "Listing Title";
$lang['appointment_your_view_date'] = "Preferred Date";
$lang['appointment_listing_url_text'] = "Listing URL";
$lang['appointment_listing_url'] = "$config[baseurl]/listingview.php?id=$listingID";
$lang['appointment_your_message'] = "Your Message";
$lang['appointment_send'] = "Request Viewing";
$lang['appointment_your_view_time'] = "Suitable Time";
$lang['appointment_agents_email'] = "Agents Email";
$lang['appointment_listing_siteid'] = "Site ID";
$lang['appointment_sent'] = "The following Message was sent to";
$lang['appointment_default_subject'] = "$listingagent has an Appointment";
$lang['appointment_default_message'] = "<br>Your request has been sent to <b>$listingagent</b> who will be contacting you shortly. <br><br>You can view the listing again at this url<br><b>$listing_url</b>";
$lang['appointment_email_message'] = "hello $listingagent,\n\n$sender has been visiting $config[company_name] and would like to arrange a viewing of $listing_title (# $listingID) for $sender_view at $sender_time.\n\nPlease contact $sender to verify the apointment on:\n-------------\nPhone Number: $sender_phone\n\nOR\n\nBy Email at $sender_email.\n-------------\n\n$sender also sent this extra message:\n-------------\n$comment\n-------------\n\nYou can view the listing in question at this url\n$listing_url\n\n-------------\n\n$config[company_name] Real Estate Guide\n$config[baseurl]";
$lang['sender_email_message'] = "hello $sender, \nyour expression of interest has been sent to $listingagent  who should be contacting you shortly.\n\nBelow is a copy of your request.\n\n-------------\n$sender has been visiting $config[company_name] and would like to arrange a viewing of $listing_title (# $listingID) on $sender_view at $sender_time.\n\n\n\nPlease contact $sender to verify the apointment on:\n-------------\nPhone Number: $sender_phone\n\nOR\n\nBy Email at $sender_email.\n-------------\n\n$sender also sent this extra message:\n-------------\n$comment\n-------------\n\nYou can view the listing in question at this url\n$listing_url\n\n-------------\n\n$config[company_name] Real Estate Guide\n$config[baseurl]";
?>
