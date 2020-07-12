<?php
include("../../../../mainfile.php");
include("../admin_header.php");
xoops_cp_header();
?>
<?php
include("../../include/common.php");
//login check becouse dont want everyone getting access
loginCheck('Admin');
global $config;
include($config[template_path]."/admin_top.html");	
?>

<div align="center" style=" font-size: 10px;  color: #00008B;  margin-bottom: 10px; border: ridge;">
[  <a href="../backup_main.php">Home</a>
	]&nbsp;[	  
<a href="../backup_main.php?option=databaseAdmin&amp;task=dbStatus">Status</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&amp;task=chooseOptimize">Optimize</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&amp;task=chooseAnalyze">Analyze</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&amp;task=chooseCheck">Check</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&amp;task=chooseRepair">Repair</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&amp;task=choosebackup">Backup</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&amp;task=chooseRestore">Restore</a>
]&nbsp;[
<a href='systeminfo.php'>System Info</a>
]&nbsp;[
<a href='phpMyAdmin.php'>phpMyAdmin</a>
]&nbsp;[
<a href='sqltabled.php'>Table View</a>
]
</div>
<?php 
//Adding Style Sheet back in that we remove below
?>
	<style type="text/css">
	<!--
	body {background-color: #ffffff;  color: #000000; }
	body,  td,  th,  h1,  h2 {font-family: sans-serif; }
	pre {margin: 0px;  font-family: monospace; }
	a:link {color: #000099;  text-decoration: none; }
	a:hover {text-decoration: underline; }
	table {border-collapse: collapse; }
	.center {text-align: center; }
	.center table { margin-left: auto;  margin-right: auto;  text-align: left; }
	.center th { text-align: center;  !important }
	td,  th {  }
	h1 {font-size: 150%; }
	h2 {font-size: 125%; }
	.p {text-align: left; }
	.e {background-color: #ccccff;  font-weight: bold; border: 1px solid #000000;  font-size: 75%;  vertical-align: baseline;}
	.h {background-color: #9999cc;  font-weight: bold; border: 1px solid #000000;  font-size: 75%;  vertical-align: baseline;}
	.v {background-color: #cccccc; border: 1px solid #000000;  font-size: 75%;  vertical-align: baseline;}
	i {color: #666666; }
	img {float: right;  border: 0px; }
	hr {width: 590px;  align: center;  background-color: #cccccc;  border: 0px;  height: 1px; }
	//-->
	</style>
<?
ob_start();
   
    phpinfo();
    $php_info .= ob_get_contents();
       
ob_end_clean();
// change the width of the table to 450
$php_info    = str_replace(" width=\"600\"", " width=\"590\"", $php_info);
$php_info    = str_replace("</body></html>", "", $php_info);

$php_info    = str_replace(";", "; ", $php_info);
$php_info    = str_replace(",", ", ", $php_info);
$php_info    = str_replace(":", ": ", $php_info);
$php_info    = str_replace("%", "% ", $php_info);
$php_info    = str_replace("=", "= ", $php_info);
$php_info    = str_replace("<table", "<table style=\"table-layout:fixed;\" ", $php_info);
$offset = strpos($php_info, "<table");
//echo "offset: $offset";
//print $php_info;
print substr($php_info, $offset);


?>
<?
xoops_cp_footer();
include("../../../../footer.php");
?>