<?php
include("admin_header.php");
xoops_cp_header();
?>

<?php

//need the database info so call common
include("../include/common.php");
//require ("backup/database.php");
include("$config[template_path]/admin_top.html");
//leave the connect on seperate page for now 
loginCheck('Admin');
global  $config;
//$database = new database();
	function database(){
		
		$connection=mysql_connect($db_server, $db_user, $db_password);
		mysql_select_db($db_database,$connection) or die("Query failed with error:".mysql_error());
	}
	
	function openConnectionWithReturn($query){
		$result=mysql_query($query) or die("Query failed with error:".mysql_error()); 
		return $result;
	}
	
	function openConnectionNoReturn($query){
		mysql_query($query) or die("Query failed with error:".mysql_error()); 
	}
if (($option == "databaseAdmin") && ($task == "doBackup") && ($OutDest == "remote")) {
	exit();
} 
?>


    <div align="center" style=" font-size: 10px;  color: #00008B;  margin-bottom: 10px; border: ridge;">[  <a href="backup_main.php">Home</a>
	]&nbsp;[	  
<a href="backup_main.php?option=databaseAdmin&task=dbStatus">Status</a>
]&nbsp;[
<a href="backup_main.php?option=databaseAdmin&task=chooseOptimize">Optimize</a>
]&nbsp;[
<a href="backup_main.php?option=databaseAdmin&task=chooseAnalyze">Analyze</a>
]&nbsp;[
<a href="backup_main.php?option=databaseAdmin&task=chooseCheck">Check</a>
]&nbsp;[
<a href="backup_main.php?option=databaseAdmin&task=chooseRepair">Repair</a>
]&nbsp;[
<a href="backup_main.php?option=databaseAdmin&task=choosebackup">Backup</a>
]&nbsp;[
<a href="backup_main.php?option=databaseAdmin&task=chooseRestore">Restore</a>
]
<!-- Throw in some unconnected extras without these no effect to the main ones but they allow a few things to be available from the admin area with no fuss and same security -->
&nbsp;[
<a href='backup/systeminfo.php'>System Info</a>
]&nbsp;[
<a href='backup/phpMyAdmin.php'>phpMyAdmin</a>
]</div>
<table width="98%" border="0" align=
    "center" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="middle" align="center"> 

		  <?php
//multi page controls page calls to databaseAdmin.php
require_once("backup/databaseAdmin.php");
$databaseAdmin = new databaseAdmin();

switch ($_GET['task']) {
	case "chooseOptimize":
	$databaseAdmin->chooseOptimize($database);
	break;

	case "doOptimize":
	$databaseAdmin->doOptimize($database,$_POST['tables']);
	break;

	case "chooseAnalyze":
	$databaseAdmin->chooseAnalyze($database);
	break;

	case "doAnalyze":
	$databaseAdmin->doAnalyze($database,$_POST['tables']);
	break;

	case "chooseCheck":
	$databaseAdmin->chooseCheck($database);
	break;

	case "doCheck":
	$databaseAdmin->doCheck($database,$_POST['tables']);
	break;

	case "chooseRepair":
	$databaseAdmin->chooseRepair($database);
	break;

	case "doRepair":
	$databaseAdmin->doRepair($database,$_POST['tables']);
	break;

	case "choosebackup":
	$databaseAdmin->choosebackup($database);
	break;

	case "doBackup":
	$databaseAdmin->doBackup($database,$_POST['tables'],$_POST['OutType'],$_POST['OutDest'],$_POST['toBackUp'],$_SERVER['HTTP_USER_AGENT'], $local_backup_path);
	break;

	case "chooseRestore":
	$databaseAdmin->chooseRestore($database, $local_backup_path);
	break;

	case "doRestore":
	$databaseAdmin->doRestore($database,$_POST['file'],$_FILES['upfile'],$local_backup_path);
	break;

	default:
	$databaseAdmin->dbStatus($database);
}
?>

    </TD>
  </TR>
</TABLE>
<?
include ("../footer.php"); 
?>
<?
xoops_cp_footer();
include("../../../footer.php");
?>