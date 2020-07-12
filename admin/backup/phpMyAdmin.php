<?php
include("../../../../mainfile.php");
include("../admin_header.php");
xoops_cp_header();
?>

<?php
include("../../include/common.php");
//login check becouse dont want everyone getting access
	//loginCheck('Admin');

//include("$config[template_path]/admin_top.html");

if ($phpmyadmin!='') { ?>
  <div align="center" style=" font-size: 10px;  color: #00008B;  margin-bottom: 10px; border: ridge;">[  <a href="../backup_main.php">Home</a>
	]&nbsp;[	  
<a href="../backup_main.php?option=databaseAdmin&task=dbStatus">Status</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseOptimize">Optimize</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseAnalyze">Analyze</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseCheck">Check</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseRepair">Repair</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=choosebackup">Backup</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseRestore">Restore</a>
]&nbsp;[
<a href='systeminfo.php'>System Info</a>
]&nbsp;[
<a href='phpMyAdmin.php'>phpMyAdmin</a>
]&nbsp;[
<a href='sqltabled.php'>Table View</a>
]</div>
	<TABLE BGCOLOR=#FFFFFF WIDTH=350 BORDER=0 CELLSPACING=0 CELLPADDING=0 ALIGN=CENTER>
	<TR><TD ALIGN=CENTER>
	Click <a href='#' onClick="javascript:window.open('<?php echo $phpmyadmin; ?>');">here</a> to launch phpMyAdmin
	<P>
	</TD></TR></TABLE>
<?php } else { ?>
  <div align="center" style=" font-size: 10px;  color: #00008B;  margin-bottom: 10px; border: ridge;">[  <a href="../backup_main.php">Home</a>
	]&nbsp;[	  
<a href="../backup_main.php?option=databaseAdmin&task=dbStatus">Status</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseOptimize">Optimize</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseAnalyze">Analyze</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseCheck">Check</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseRepair">Repair</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=choosebackup">Backup</a>
]&nbsp;[
<a href="../backup_main.php?option=databaseAdmin&task=chooseRestore">Restore</a>
]&nbsp;[
<a href='systeminfo.php'>System Info</a>
]&nbsp;[
<a href='phpMyAdmin.php'>phpMyAdmin</a>
]&nbsp;[
<a href='sqltabled.php'>Table View</a>
]</div>
	<TABLE BGCOLOR=#FFFFFF WIDTH=500 BORDER=0 CELLSPACING=0 CELLPADDING=0 ALIGN=CENTER>
	<TR><TD ALIGN=CENTER>
	<p>&nbsp;</p>
	<b>:: phpMyAdmin not installed or configured incorrectly ::</b>
	<br><br>
	phpMyAdmin is not installed or not configured in common.php!
	<br>Please edit your common.php file and enter a valid URL for phpMyAdmin.
	</TD></TR></TABLE>
<?php } ?>

<?
xoops_cp_footer();
include("../../../../footer.php");
?>
