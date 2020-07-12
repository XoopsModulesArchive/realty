<?php
$modversion['name'] = 'Real Estate & Auto Listings Engine 3.0 w/Virtual Tour';
$modversion['version'] = 3.0;
$modversion['description'] = 'Complete Real Estate Listings Engine with Virtual Tours. Read the Read me for more information.';
$modversion['credits'] = 'Xoops Conversion: DJ <br> (Original version: Jon Roig & Ryan Bonham)';
$modversion['author'] = 'DJ<br>http://www.liquidgfx.com<br> Jon Roig<br>http://www.jonroig.com<br>Ryan Bonham<br>http://www.open-reality.com';
$modversion['license'] = '';
$modversion['official'] = 'yes';
$modversion['image'] = 'images/realty.gif';
$modversion['dirname'] ='realty';

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'default_activitylog';
$modversion['tables'][1] = 'default_agentformelements';
$modversion['tables'][2] = 'default_listingsdb';
$modversion['tables'][3] = 'default_listingsdbelements';
$modversion['tables'][4] = 'default_listingsformelements';
$modversion['tables'][5] = 'default_listingsimages';
$modversion['tables'][6] = 'default_memberformelements';
$modversion['tables'][7] = 'default_userdb';
$modversion['tables'][8] = 'default_userdbelements';
$modversion['tables'][9] = 'default_userfavoritelistings';
$modversion['tables'][10] = 'default_userformelements';
$modversion['tables'][11] = 'default_userimages';
$modversion['tables'][12] = 'default_usersavedsearches';
$modversion['tables'][13] = 'default_vtourimages';
$modversion['tables'][14] = 'default_autodb';
$modversion['tables'][15] = 'default_autodbelements';
$modversion['tables'][16] = 'default_autoformelements';
$modversion['tables'][17] = 'default_autoimages';
$modversion['tables'][18] = 'default_autosavedsearches';
$modversion['tables'][19] = 'default_autovTourImages';

// Menu - does it have an entry in the Main Menu? 1=yes, 0=no
$modversion['hasMain'] = 1;

$modversion['sub'][1]['name'] = '<b>Real Estate</b>';
$modversion['sub'][1]['url']  = 'index.php';

$modversion['sub'][2]['name'] = 'Member Sign-up';
$modversion['sub'][2]['url']  = 'members/member_signup.php';

$modversion['sub'][3]['name'] = 'Listings Search';
$modversion['sub'][3]['url']  = 'listingsearch.php';

$modversion['sub'][4]['name'] = 'Rental Search';
$modversion['sub'][4]['url']  = 'rentalsearch.php';

$modversion['sub'][5]['name'] = 'Lots';
$modversion['sub'][5]['url']  = 'listing_browse.php?type%5B%5D=Land';

$modversion['sub'][6]['name'] = 'Residental';
$modversion['sub'][6]['url']  = 'listing_browse.php?type%5B%5D=Home';

$modversion['sub'][7]['name'] = 'Farms';
$modversion['sub'][7]['url']  = 'listing_browse.php?type%5B%5D=Farms';

$modversion['sub'][8]['name'] = 'Commercial Property';
$modversion['sub'][8]['url']  = 'listing_browse.php?type%5B%5D=Commercial';

$modversion['sub'][9]['name'] = '<b>Auto Center</b>';
$modversion['sub'][9]['url']  = 'autosearch.php';

$modversion['sub'][10]['name'] = 'Browse Vehicles';
$modversion['sub'][10]['url']  = 'auto_browse.php';

$modversion['sub'][11]['name'] = 'Search Vehicles';
$modversion['sub'][11]['url']  = 'autosearch.php';

$modversion['sub'][12]['name'] = 'List Favorite Autos';
$modversion['sub'][12]['url']  = 'members/userautofavorites.php?action=list';

$modversion['sub'][13]['name'] = 'Saved Vehicle Searches';
$modversion['sub'][13]['url']  = 'members/userautosearch.php?action=list';

$modversion['sub'][14]['name'] = '<b>Information</b>';
$modversion['sub'][14]['url']  = 'contactus.php';

$modversion['sub'][15]['name'] = 'Agent Information';
$modversion['sub'][15]['url']  = 'view_users.php';

$modversion['sub'][16]['name'] = 'About';
$modversion['sub'][16]['url']  = 'aboutus.php';

$modversion['sub'][17]['name'] = 'Contact';
$modversion['sub'][17]['url']  = 'contactus.php';

$modversion['sub'][18]['name'] = 'Favorites';
$modversion['sub'][18]['url']  = 'members/listfavorites.php';

$modversion['sub'][19]['name'] = 'Legal Information';
$modversion['sub'][19]['url']  = 'legal.php';


// Admin things - does the Admin menu need a link to the administration page? And where is the page?
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
?>
