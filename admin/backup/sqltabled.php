<?
include("../../../../mainfile.php");
include("../admin_header.php");
xoops_cp_header();?>
<?php
include("../../include/common.php");
//login check becouse dont want everyone getting access
loginCheck('Admin');
global $config;
include($config[template_path]."/admin_top.html");
?>

  <div align="center">[  <a href="../backup_main.php">Home</a>
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
 
<?php 


       //for now we redirct db stuff  
	   //is setup allready for common.php
	   //in o-r.
   $db_database_database = $db_database; 
   $db_database_user = $db_user; 
  $db_database_password = $db_password; 
   $db_database_server = $db_server; 
     
    echo('<html><body>'); 
    echo('<left>'); 
    echo("Database $db_database_database"); 
     
    $id_link = @mysql_connect($db_database_server, $db_database_user, $db_database_password); 
     
    $tables = mysql_list_tables($db_database_database, $id_link); 
     
    $num_tables = mysql_num_rows($tables); 

    // store table names in an array 
    $arr_tablenames[] = ''; 
     
    // store number of fields per table(index 0,1,2..) in an array 
    $arr_num_fields[] = ''; 
    for ($i=0; $i < $num_tables; $i++) { 
        $arr_tablenames[$i] = mysql_tablename($tables, $i); 
        $arr_num_fields[$i] = mysql_num_fields(mysql_db_query($db_database_database, "select * from $arr_tablenames[$i]", $id_link)); 
    } 
     
    // store field names in a multidimensional array: 
    // [i] == table number, [ii] == field number for that table 
    for ($i=0; $i < $num_tables; $i++) { 
        for ($ii=0; $ii < $arr_num_fields[$i]; $ii++) { 
            $result = mysql_db_query($db_database_database, "select * from $arr_tablenames[$i]", $id_link); 
            $hash_field_names[$i][$ii] = mysql_field_name($result, $ii); 
        }     
    } 
     
    for ($i=0; $i < $num_tables; $i++) { 
        echo("Table $arr_tablenames[$i] "); 
        echo('<table algin"left" border="1"><tr>'); 
        $result = mysql_db_query($db_database_database, "select * from $arr_tablenames[$i]", $id_link); 
        for ($ii=0; $ii < $arr_num_fields[$i]; $ii++) { 
            echo("<th style=\"font-size: 11px;\">"); 
            echo $hash_field_names[$i][$ii]; 
            echo("</th>"); 
        } 
        echo("</tr><tr>"); 
        $number_of_rows = @mysql_num_rows($result); 
        for ($iii = 0; $iii < $number_of_rows; $iii++) { 
            $record = @mysql_fetch_row($result); 
            for ($ii=0; $ii < $arr_num_fields[$i]; $ii++) { 
                echo("<td style=\"font-size: 9px;\">"); 
                echo $record[$ii]; 
                echo("</td>"); 
            } 
        echo("</tr>"); 
        } 
        echo("</table>"); 
    } 
     


    echo('</body></html>'); 
?> 
<?
xoops_cp_footer();
?>