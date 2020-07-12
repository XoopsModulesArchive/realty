 <?php
include("../include/common.php");
global $action, $lang, $config;
function writemenu()
{
global $action, $lang, $config;
?>
<table align=center width=100% border=1  bordercolor=Gray><tr><td align=center width=100%>
<h3><?php echo "$config[site_title]" ?> Jon Roig's 3.0.X to Open-Realty 1.1.X VERSION UPDATE</h3>
<?php
echo "<a href=\"$PHP_SELF?\">Home</a>]&nbsp;[";
echo "<a href=\"$PHP_SELF?goto=backup\">Backup Database</a>]";
echo "</td></tr></table>";

}

?>
<table border="<?php echo $style[admin_listing_border] ?>" cellspacing="<?php echo $style[admin_listing_cellspacing] ?>" cellpadding="<?php echo $style[admin_listing_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
      <tr><td valign="top" width="100%">
<table width='100%' border='0' cellspacing='2' cellpadding='2'>
  <tr>
  <td>
<?  writemenu(); ?>
  </td>
    </tr>     
<tr>
<td>
<?php 
if (!isset($goto)) 
{ 
  $goto = "backup";
  ?>
  <table width='100%' border='0' cellspacing='2' cellpadding='2'>
  <tr>
  <td>
  <h3>Welcome to the Open-Realty 1.07 to 1.1.0 UPDATE Tool</h3>
<p>Thankyou for choosing to use Open-Realty as your doorway to the internet. <br>Please visit the <a href="http://www.open-realty.org">support site</a> for any assistance you may require.</p>
      <p>
      <input type="checkbox">As an updateing utility you must be running an allready functional version 1.07 database.<br>
      <input type="checkbox">You should have allready loaded all files for 1.1.0 to your server<br><font color="#FF0000"><i style="padding-left: 20px;">The current version found by the updater is</i> <b><? print "$config[version]";?></b></font><br>
            <input type="checkbox">You should of allready edited your 1.1.0 common.php to aim at your current 1.07 database.<br><i style="padding-left: 20px;"><font color="#FF0000">The updater is currently being directed to update server <b></i><? print"$db_server"; ?></b> <i>Database</i> <b><? print"$db_database"; ?></b></font><br>
      Assuming that you have complied with the above requirements clicking the start link below will modify your current database to work with your new version of Open-Realty 1.1.0
            </p>
      <p>Upgrade includes:<br>
      <input type="checkbox" checked disabled>The next step will create a database backup Automaticly!<br>
      <input type="checkbox" checked disabled>Required Alterations will be made to the database<br>
      <input type="checkbox" checked disabled>Additional Tables will be created as required<br>
      <input type="checkbox" checked disabled>All Current admins will be given Admin Status.<br>
      <input type="checkbox" checked disabled>All Current Users will be give Agent Status<br>
      <input type="checkbox" checked disabled>All Current Admins and Agents will Be made active<br> 
<?php echo "<p align=\"right\">[<a href=\"$PHP_SELF?goto=backup\">NEXT STEP DATABASE BACKUP</a>]</p>";?>
<!-- [<a href="$PHP_SELF?goto=update">DATABASE UPDATE</a>] -->
  </td>
    </tr>
</table>
   
<?php } 
else if ($goto == "backup") 
{ 
  $goto = "2";
    flush();
$conn = mysql_connect($db_server,$db_user,$db_password) or die(mysql_error());
$path = $path . "update_sql_bak/";
if (!is_dir($path)) mkdir($path, 0766);
chmod($path, 0777);


function get_def($db_database, $table) {
    global $conn;
    $def = "";
    $def .= "DROP TABLE IF EXISTS $table;\n";
    $def .= "CREATE TABLE $table (\n";
    $result = mysql_db_query($db_database, "SHOW FIELDS FROM $table",$conn);
    while($row = mysql_fetch_array($result)) {
        $def .= "    $row[Field] $row[Type]";
        if ($row["Default"] != "") $def .= " DEFAULT '$row[Default]'";
        if ($row["Null"] != "YES") $def .= " NOT NULL";
          if ($row[Extra] != "") $def .= " $row[Extra]";
           $def .= ",\n";
     }
     $def = ereg_replace(",\n$","", $def);
     $result = mysql_db_query($db_database, "SHOW KEYS FROM $table",$conn);
     while($row = mysql_fetch_array($result)) {
          $kname=$row[Key_name];
          if(($kname != "PRIMARY") && ($row[Non_unique] == 0)) $kname="UNIQUE|$kname";
          if(!isset($index[$kname])) $index[$kname] = array();
          $index[$kname][] = $row[Column_name];
     }
     while(list($x, $columns) = @each($index)) {
          $def .= ",\n";
          if($x == "PRIMARY") $def .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
          else if (substr($x,0,6) == "UNIQUE") $def .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
          else $def .= "   KEY $x (" . implode($columns, ", ") . ")";
     }

     $def .= "\n);";
     return (stripslashes($def));
}

function get_content($db_database, $table) {
     global $conn;
     $content="";
     $result = mysql_db_query($db_database, "SELECT * FROM $table",$conn);
     while($row = mysql_fetch_row($result)) {
         $insert = "INSERT INTO $table VALUES (";
         for($j=0; $j<mysql_num_fields($result);$j++) {
            if(!isset($row[$j])) $insert .= "NULL,";
            else if($row[$j] != "") $insert .= "'".addslashes($row[$j])."',";
            else $insert .= "'',";
         }
         $insert = ereg_replace(",$","",$insert);
         $insert .= ");\n";
         $content .= $insert;
     }
     return $content;
}

$filetype = "sql";

if (!eregi("/restore\.",$PHP_SELF)) {
   
   $cur_time=date("Y-m-d H:i");
   $tables = mysql_list_tables($db_database,$conn);
   $num_tables = @mysql_num_rows($tables);
   $i = 0;
   while($i < $num_tables) {
      $table = mysql_tablename($tables, $i);
   
      $newfile .= get_def($db_database,$table);
      $newfile .= "\n\n";
      $newfile .= get_content($db_database,$table);
      $newfile .= "\n\n";
      $i++;
   }   
   $f_name=date("Y-m-d"); //H:i
      $fp = fopen ($path."".$f_name.".$filetype","w");
      fwrite ($fp,$newfile);
      fclose ($fp);
}
?>
  <table width='100%' border='0' cellspacing='2' cellpadding='2'>
  <tr>
  <td>
<TABLE WIDTH="80%"><TR><TD>

<h3>IF NO ERRORS Backup Complete</h3>
<BR>
The Backup File was saved to [<a href="update_sql_bak/<?php echo $f_name; ?>.sql">update_sql_bak/<?php echo $f_name; ?>.sql</a>] . <br><br>To Save your backup to your computer please right click the above link and save as.<br><br>You may also open it to read now by left clicking the link. <br>If you got errors they would be caoused by the creation of the folder you can solve this by manually creating the folder or by altering the script as shown on the forum.<br> While useing the backup is advised so long as you have a backup this step is optional but going past it is done at your own risk.
</TD></TR></TABLE>
<?php echo "<p align=\"right\">[<a href=\"$PHP_SELF?goto=update\">START THE DATABASE UPDATE</a>]</p>"; ?>
<BR><BR>
  </td>
  </tr>
</table> 
<?php } 
else if ($goto == "update") 
{ 
  $goto = "3"; 
?>
  <table width='100%' border='0' cellspacing='2' cellpadding='2'>
  <tr>
  <td>
   <?   
// Add the prefix to exsisting tables ".$config[table_prefix]."
$sql_insert[] = "ALTER TABLE activityLog RENAME ".$config[table_prefix]."activityLog";
$sql_insert[] = "ALTER TABLE listingsDB RENAME ".$config[table_prefix]."listingsDB";
$sql_insert[] = "ALTER TABLE listingsDBElements RENAME ".$config[table_prefix]."listingsDBElements";
$sql_insert[] = "ALTER TABLE listingsFormElements RENAME ".$config[table_prefix]."listingsFormElements";
$sql_insert[] = "ALTER TABLE listingsImages RENAME ".$config[table_prefix]."listingsImages";
$sql_insert[] = "ALTER TABLE UserDB RENAME ".$config[table_prefix]."UserDB";
$sql_insert[] = "ALTER TABLE UserDBElements RENAME ".$config[table_prefix]."UserDBElements";
$sql_insert[] = "ALTER TABLE userFormElements RENAME ".$config[table_prefix]."userFormElements";
$sql_insert[] = "ALTER TABLE userImages RENAME ".$config[table_prefix]."userImages";
//All prefixed Add the new tables and content
$sql_insert[] = "CREATE TABLE ".$config[table_prefix]."userSavedSearches (  ID int(11) NOT NULL auto_increment,  user_ID int(11) NOT NULL default '0',  Title varchar(255) NOT NULL default '',  query_string longtext NOT NULL,  last_viewed timestamp(14) NOT NULL,  new_listings tinyint(4) NOT NULL default '0',  PRIMARY KEY  (ID)) TYPE=MyISAM AUTO_INCREMENT=1 ;";
$sql_insert[] = "CREATE TABLE ".$config[table_prefix]."userFavoriteListings (  ID int(11) NOT NULL auto_increment,  user_ID int(11) NOT NULL default '0',  listing_ID int(11) NOT NULL default '0',  PRIMARY KEY  (ID)) TYPE=MyISAM AUTO_INCREMENT=1 ;";
$sql_insert[] = "CREATE TABLE ".$config[table_prefix]."memberFormElements (  ID int(11) NOT NULL auto_increment,  field_type varchar(20) NOT NULL default '',  field_name varchar(20) NOT NULL default '',  field_caption varchar(80) NOT NULL default '',  default_text text NOT NULL,  field_elements text NOT NULL,  rank int(11) NOT NULL default '0',  required char(3) NOT NULL default '',  PRIMARY KEY  (ID)) TYPE=MyISAM AUTO_INCREMENT=8 ;";
$sql_insert[] = "CREATE TABLE ".$config[table_prefix]."agentFormElements ( ID int(11) NOT NULL auto_increment,  field_type varchar(20) NOT NULL default '',  field_name varchar(20) NOT NULL default '',  field_caption varchar(80) NOT NULL default '',  default_text text NOT NULL,  field_elements text NOT NULL,  rank int(11) NOT NULL default '0',  required char(3) NOT NULL default '',  PRIMARY KEY  (ID)) TYPE=MyISAM AUTO_INCREMENT=8 ;";
//All tables created add content to new tables
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."memberFormElements VALUES (3, 'textarea', 'info', 'Info', '', '', 10, 'No');";
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."memberFormElements VALUES (4, 'text', 'phone', 'Phone', '', '', 1, 'No');";
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."memberFormElements VALUES (5, 'text', 'mobile', 'Mobile', '', '', 3, 'No');";
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."memberFormElements VALUES (6, 'text', 'fax', 'Fax', '', '', 5, 'No');";
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."memberFormElements VALUES (7, 'url', 'homepage', 'Homepage', '', '', 7, 'No');";
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."agentFormElements VALUES (3, 'textarea', 'info', 'Info', '', '', 10, 'No');";
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."agentFormElements VALUES (4, 'text', 'phone', 'Phone', '', '', 1, 'No');";
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."agentFormElements VALUES (5, 'text', 'mobile', 'Mobile', '', '', 3, 'No');";
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."agentFormElements VALUES (6, 'text', 'fax', 'Fax', '', '', 5, 'No');";
$sql_insert[] = "INSERT INTO ".$config[table_prefix]."agentFormElements VALUES (7, 'url', 'homepage', 'Homepage', '', '', 7, 'No');";
//Alter origional tables to suit searching
$sql_insert[] = "alter table " . $config[table_prefix] . "listingsFormElements add column searchable int unsigned not null default 0";
$sql_insert[] = "alter table " . $config[table_prefix] . "listingsFormElements add column search_type varchar(10)";
$sql_insert[] = "alter table " . $config[table_prefix] . "listingsFormElements add column search_label varchar(50)";
$sql_insert[] = "alter table " . $config[table_prefix] . "listingsFormElements add column search_step int not null default 0";
$sql_insert[] = "create index idx_searchable on " . $config[table_prefix] . "listingsFormElements (searchable)";

//Alter original tables to suit
//UserDB

$sql_insert[] = "ALTER TABLE ".$config[table_prefix]."UserDB ADD COLUMN isAgent char(3) NOT NULL default 'no';";
$sql_insert[] = "ALTER TABLE ".$config[table_prefix]."UserDB ADD COLUMN active char(3) NOT NULL default 'no';";
//the next two are the same but vary in action these are required in some way to ensure that at minimum one admin is made to work since admin can not work without being an agent we need to activate and give agent status.
//update the admin account (Assume ID=1) to active agent
//this is a seperate update to ensure it is completed
$sql_insert[] = "UPDATE ".$config[table_prefix]."UserDB SET isAgent = 'yes', active = 'yes' WHERE ID = '1';";
//now we attempt to update the rest of the original admins allowing them the agent and active
$sql_insert[] = "UPDATE ".$config[table_prefix]."UserDB SET isAgent = 'yes', active = 'yes' WHERE isAdmin = 'yes';";
//This one may be wrong but i think is ok. We take the original user who was not admin we make him an active agent but not admin.
$sql_insert[] = "UPDATE ".$config[table_prefix]."UserDB SET isAgent = 'yes', active = 'yes' WHERE isAdmin = 'no';";

//listingsDB
$sql_insert[] = "ALTER TABLE ".$config[table_prefix]."listingsDB ADD COLUMN mlsimport char(3) NOT NULL default 'No';";
$sql_insert[] = "ALTER TABLE ".$config[table_prefix]."listingsDB ADD KEY idx_mlsimport (mlsimport(1));";

//listingsDBElements
$sql_insert[] = "ALTER TABLE ".$config[table_prefix]."listingsDBElements ADD KEY idx_listingid3 (listing_id);";

//listingsFormElements
$sql_insert[] = "ALTER TABLE ".$config[table_prefix]."listingsFormElements ADD COLUMN display_priv char(3) NOT NULL default 'No';";


      


      while (list($elementIndexValue, $elementContents) = each($sql_insert))
      {
         echo "<br>$elementIndexValue -- $elementContents<br>";
         $recordSet = $conn->Execute($elementContents);
         if ($recordSet === false) die ("<b><font color=red>ERROR - $elementContents</font></b>");
      }

      echo "<br><br><h3>Assuming no errors have been printed to screen in red then your database is now hopefully fully updated to 1.1.0</h3>PLEASE REMOVE THIS FILE NOW.";?>
<?php echo "<p align=\"right\">[<a href=\"index.php\">Version 1.1.0 SITE ENTRY</a>]</p>";?>
  </td>
  </tr>
</table>"; 
<?php } 
  ?>

   
<!-- ENTER FOOTER INFORMATION  -->
<p>Open-Realty 1.0.7 to 1.1.0 Upgrade script By RealtyOne And Mick.<br>This script is not sanctioned by Ryan and as such no warrenty of any sort is offered.<br>Everything that could be tested has been tested and everything that could be done to aid in an emergency recovery has been done.<br>This is currently classed as a beta version of the script and will remain that way until checked by Ryan.<br></p>
<!-- END OF FOOTER INFORMATION  -->
</td> </tr> </table>
   
</td>

</tr></table>


<?php

   
?>     
