<?php
include("admin_header.php");
xoops_cp_header();
?>
<?php 
/* This is a multi page! all content is called to the main page via the page controller. Information notes are from mysql the pages are in the help folder. Maybe place a link direct or maybe delete the help*/
//first we define the backup path
$local_backup_path = $config['basepath'].'/admin/backup/dump';
/*Allthough this is working fine for me there is a chance that it wont for anyone else or on a live site. Should be remembered in instructions to create dump or if made to chmod 777*/
//if there ok if not try making 
//if (!is_dir($local_backup_path)) mkdir($local_backup_path, 0766);
//ok now make it writeable
//chmod($local_backup_path, 0777);

class databaseAdmin {
	function dbStatus($database) {
		echo "<table border=\"0\" align=\"center\" width=\"90%\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th colspan=\"2\" class=\"articlehead\">Database Tables Status</th></tr>\n";
		echo "	<tr><td colspan=\"2\">&nbsp;<br>Looking for some information on the state of your tables or when you last checked them? This procedure provides key information on all tables defined to your database.<br>&nbsp;</td></tr>\n";
		$result = openConnectionWithReturn("SHOW TABLE STATUS");
		while ($result2 = mysql_fetch_array($result, MYSQL_ASSOC)) {
			while (list($key, $value) = each ($result2)) {
				if ($key=="Name") {
					echo "	<tr><td colspan=\"2\" class=\"heading\" align=\"center\">$value</td></tr>\n";
				} else {
					echo "	<tr><td align=\"right\" width=\"50%\">$key</td><td width=\"50%\"> : $value</td></tr>\n";
				}
			}
			echo "	<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
	
	function chooseOptimize($database) {
		echo "<form action=\"backup_main.php?option=databaseAdmin&task=doOptimize\" method=\"post\">\n";
		echo "<table border=\"0\" align=\"center\" width=\"90%\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th class=\"articlehead\">Database Tables Optimization</th></tr>\n";
		echo "	<tr><td><br>During the course of normal use, records can and will be deleted. Deleted records are maintained in a linked list and subsequent INSERT operations reuse old record positions. Running the Optimize Tables routine reclaims the unused space. It is recommended you run this procedure regularily to ensure the best possible performance.<br><br>While the Optimize Tables routine is executing, the original table is readable by other clients. Updates and writes to the table are stalled until the new table is ready. This is done in such a way that all updates are automatically redirected to the new table without any failed updates.<br><br>The Message Type returned should be one of status (normal), error, info, or warning. If a Message Type other than \"status\" and a Message of \"OK\" is returned, you may have to run a repair on the table. Read the Message carefully to determine if this is required.<br>&nbsp;</td></tr>\n";
		echo "	<tr>\n		<td align=\"center\">\n";
		echo "			<select name=\"tables[]\" size=\"10\" MULTIPLE>\n";
		echo "			<option value=\"all\" selected>Optimize All Tables\n";
		$result = openConnectionWithReturn("SHOW TABLES");
		while ($result2 = mysql_fetch_row($result)) {
			echo "			<option value=\"$result2[0]\">$result2[0]\n";
		}
		mysql_free_result($result);
		echo "			</select>\n		</td>\n	</tr>\n	<tr>\n";
		echo "		<td align=\"center\">&nbsp;<br><input type=submit value=\"Optimize the Selected Tables\"></td>\n";
		echo "	</tr>\n</table>\n</form>\n";
	}
	
	function doOptimize($database, $tables) {
		if (!$tables[0]) {
			chooseOptimize($database);
			return;
		}
		if ($tables[0] == "all") {
			$tables = array();
			$result = openConnectionWithReturn("SHOW TABLES");
			while ($result2 = mysql_fetch_row($result)) {
				list(,$tables[])=each($result2);
			}
			mysql_free_result($result);
		}
		$toOptimize = implode(",",$tables);
		echo "<table border=\"0\" align=\"center\" width=\"90%\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th colspan=\"2\" class=\"articlehead\">Database Tables Optimization Results</th></tr>";
		echo "	<tr><td colspan=\"2\"><br>During the course of normal use, records can and will be deleted. Deleted records are maintained in a linked list and subsequent INSERT operations reuse old record positions. Running the Optimize Tables routine reclaims the unused space. It is recommended you run this procedure regularily to ensure the best possible performance.<br><br>While the Optimize Tables routine is executing, the original table is readable by other clients. Updates and writes to the table are stalled until the new table is ready. This is done in such a way that all updates are automatically redirected to the new table without any failed updates.<br><br>The Message Type returned should be one of status (normal), error, info, or warning. If a Message Type other than \"status\" and a Message of \"OK\" is returned, you may have to run a repair on the table. Read the Message carefully to determine if this is required.<br>&nbsp;</td></tr>\n";
		$result = openConnectionWithReturn("OPTIMIZE TABLE " . $toOptimize);
		while ($result2 = mysql_fetch_array($result, MYSQL_ASSOC)) {
			while (list($key, $value) = each ($result2)) {
				if ($key=="Table") {
					echo "	<tr><td colspan=\"2\" class=\"heading\" align=\"center\">$value</td></tr>\n";
				} else {
					echo "	<tr><td align=\"right\" width=\"50%\">$key</td><td width=\"50%\" nowrap> : $value</td></tr>\n";
				}
			}
			echo "	<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		}
		mysql_free_result($result);
		echo "</table>\n";
	}
	
	function chooseAnalyze($database) {
		echo "<form action=\"backup_main.php?option=databaseAdmin&task=doAnalyze\" method=\"post\">\n";
		echo "<table border=\"0\" align=\"center\" width=\"90%\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th class=\"articlehead\">Database Tables Analysis</th></tr>\n";
		echo "	<tr><td><br>Analyze and store the key distribution for the table. MySQL uses the stored key distribution to decide in which order tables should be joined when one does a join on something else than a constant. During the analysis the table is locked with a read lock.<br><br>If a Message Type other than \"status\" and a Message of \"OK\" is returned, you may have to run a repair on the table. Read the Message carefully to determine if this is required.<br>&nbsp;</td></tr>\n";
		echo "	<tr>\n		<td align=\"center\">\n";
		echo "			<select name=\"tables[]\" size=\"10\" MULTIPLE>\n";
		echo "			<option value=\"all\" selected>Analyze All Tables\n";
		$result = openConnectionWithReturn("SHOW TABLES");
		while ($result2 = mysql_fetch_row($result)) {
			echo "			<option value=\"$result2[0]\">$result2[0]\n";
		}
		mysql_free_result($result);
		echo "			</select>\n		</td>\n	</tr>\n	<tr>\n";
		echo "		<td align=\"center\">&nbsp;<br><input type=submit value=\"Analyze the Selected Tables\"></td>\n";
		echo "	</tr>\n</table>\n</form>\n";
	}
	
	function doAnalyze($database, $tables) {
		if (!$tables[0]) {
			$this->chooseAnalyze($database);
			return;
		}
		if ($tables[0] == "all") {
			$result = openConnectionWithReturn("SHOW TABLES");
			while ($result2 = mysql_fetch_row($result)) {
				$toAnalyze .= $result2[0] . ",";
			}
			mysql_free_result($result);
		} else {
			while (list(, $value) = each ($tables)) {
				$toAnalyze .= $value . ",";
			}

		}
		echo "<table border=\"0\" align=\"center\" width=\"90%\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th colspan=\"2\" class=\"articlehead\">Database Tables Analysis Results</th></tr>";
		echo "	<tr><td colspan=\"2\"><br>Analyze and store the key distribution for the table. MySQL uses the stored key distribution to decide in which order tables should be joined when one does a join on something else than a constant. During the analysis the table is locked with a read lock.<br><br>If a Message Type other than \"status\" and a Message of \"OK\" is returned, you may have to run a repair on the table. Read the Message carefully to determine if this is required.<br>&nbsp;</td></tr>\n";
		$toAnalyze = rtrim($toAnalyze,",");
		$result = openConnectionWithReturn("ANALYZE TABLE " . $toAnalyze);
		while ($result2 = mysql_fetch_array($result, MYSQL_ASSOC)) {
			while (list($key, $value) = each ($result2)) {
				if ($key=="Table") {
					echo "	<tr><td colspan=\"2\" class=\"heading\" align=\"center\">$value</td></tr>\n";
				} else {
					echo "	<tr><td align=\"right\" width=\"50%\">$key</td><td width=\"50%\" nowrap> : $value</td></tr>\n";
				}
			}
			echo "	<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		}
		mysql_free_result($result);
		echo "</table>\n";
	}
	
	function chooseCheck($database) {
		echo "<form action=\"backup_main.php?option=databaseAdmin&task=doCheck\" method=\"post\">\n";
		echo "<table border=\"0\" align=\"center\" width=\"90%\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th class=\"articlehead\">Database Tables Check</th></tr>\n";
		echo "	<tr><td><br>This routine checks the selected table(s) for errors.<br><br>The Message Type returned should be one of status (normal), error, info, or warning. If a Message Type other than \"status\" and a Message of \"OK\" is returned, you may have to run a repair on the table. Read the Message carefully to determine if this is required.<br>&nbsp;</td></tr>\n";
		echo "	<tr>\n		<td align=\"center\">\n";
		echo "			<select name=\"tables[]\" size=\"10\" MULTIPLE>\n";
		echo "			<option value=\"all\" selected>Check All Tables\n";
		$result = openConnectionWithReturn("SHOW TABLES");
		while ($result2 = mysql_fetch_row($result)) {
			echo "			<option value=\"$result2[0]\">$result2[0]\n";
		}
		mysql_free_result($result);
		echo "			</select>\n		</td>\n	</tr>\n	<tr>\n";
		echo "		<td align=\"center\">&nbsp;<br><input type=submit value=\"Check the Selected Tables\"></td>\n";
		echo "	</tr>\n</table>\n</form>\n";
	}
	
	function doCheck($database, $tables) {
		if (!$tables[0]) {
			chooseCheck($database);
			return;
		}
		if ($tables[0] == "all") {
			$result = openConnectionWithReturn("SHOW TABLES");
			while ($result2 = mysql_fetch_row($result)) {
				$toCheck .= $result2[0] . ",";
			}
			mysql_free_result($result);
		} else {
			while (list(, $value) = each ($tables)) {
				$toCheck .= $value . ",";
			}
		}
		echo "<table border=\"0\" align=\"center\" width=\"90%\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th colspan=\"2\" class=\"articlehead\">Database Tables Check Results</th></tr>";
		echo "	<tr><td colspan=\"2\"><br>This routine checks the selected table(s) for errors.<br><br>The Message Type returned should be one of status (normal), error, info, or warning. If a Message Type other than \"status\" and a Message of \"OK\" is returned, you may have to run a repair on the table. Read the Message carefully to determine if this is required.<br>&nbsp;</td></tr>\n";
		$toCheck = rtrim($toCheck,",");
		$result = openConnectionWithReturn("CHECK TABLE " . $toCheck);
		while ($result2 = mysql_fetch_array($result, MYSQL_ASSOC)) {
			while (list($key, $value) = each ($result2)) {
				if ($key=="Table") {
					echo "	<tr><td colspan=\"2\" class=\"heading\" align=\"center\">$value</td></tr>\n";
				} else {
					echo "	<tr><td align=\"right\" width=\"50%\">$key</td><td width=\"50%\" nowrap> : $value</td></tr>\n";
				}
			}
			echo "	<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		}
		mysql_free_result($result);
		echo "</table>\n";
	}
	
	function chooseRepair($database) {
		echo "<form action=\"backup_main.php?option=databaseAdmin&task=doRepair\" method=\"post\">\n";
		echo "<table border=\"0\" align=\"center\" width=\"90%\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th class=\"articlehead\">Database Tables Repair</th></tr>\n";
		echo "	<tr><td><br>If any of the regular diagnostic routines (Check, Analyze, or Optimize Tables) returns a Message other than \"OK\", this procedure can be used to attempt to correct the corrupted table.<br><br>The Message Type returned should be one of status (normal), error, info, or warning. If a Message Type other than \"status\" and a Message of \"OK\" is returned, you should try repairing the table with myisamchk -o, as Repair Tables does not yet implement all the options of myisamchk. Please refer to the documentation provided with your MySQL installation for further instructions.<br>&nbsp;</td></tr>\n";
		echo "	<tr>\n		<td align=\"center\">\n";
		echo "			<select name=\"tables[]\" size=\"10\" MULTIPLE>\n";
		echo "			<option value=\"all\" selected>Repair All Tables\n";
		$result = openConnectionWithReturn("SHOW TABLES");
		while ($result2 = mysql_fetch_row($result)) {
			echo "			<option value=\"$result2[0]\">$result2[0]\n";
		}
		mysql_free_result($result);
		echo "			</select>\n		</td>\n	</tr>\n	<tr>\n";
		echo "		<td align=\"center\">&nbsp;<br><input type=submit value=\"Repair the Selected Tables\"></td>\n";
		echo "	</tr>\n</table>\n</form>\n";
	}
	
	function doRepair($database, $tables) {
		if (!$tables[0]) {
			chooseRepair($database);
			return;
		}
		if ($tables[0] == "all") {
			$result = openConnectionWithReturn("SHOW TABLES");
			while ($result2 = mysql_fetch_row($result)) {
				$toRepair .= $result2[0] . ",";
			}
			mysql_free_result($result);
		} else {
			while (list(, $value) = each ($tables)) {
				$toRepair .= $value . ",";
			}
		}
		echo "<table border=\"0\" align=\"center\" width=\"90%\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th colspan=\"2\" class=\"articlehead\">Database Tables Repair Results</th></tr>";
		echo "	<tr><td colspan=\"2\"><br>If any of the regular diagnostic routines (Check, Analyze, or Optimize Tables) returns a Message other than \"OK\", this procedure can be used to attempt to correct the corrupted table.<br><br>The Message Type returned should be one of status (normal), error, info, or warning. If a Message Type other than \"status\" and a Message of \"OK\" is returned, you should try repairing the table with myisamchk -o, as Repair Tables does not yet implement all the options of myisamchk. Please refer to the documentation provided with your MySQL installation for further instructions.<br>&nbsp;</td></tr>\n";
		$toRepair = rtrim($toRepair,",");
		$result = openConnectionWithReturn("REPAIR TABLE " . $toRepair);
		while ($result2 = mysql_fetch_array($result, MYSQL_ASSOC)) {
			while (list($key, $value) = each ($result2)) {
				if ($key=="Table") {
					echo "	<tr><td colspan=\"2\" class=\"heading\" align=\"center\">$value</td></tr>\n";
				} else {
					echo "	<tr><td align=\"right\" width=\"50%\">$key</td><td width=\"50%\" nowrap> : $value</td></tr>\n";
				}
			}
			echo "	<tr><td colspan=\"2\">&nbsp;</td></tr>\n";
		}
		mysql_free_result($result);
		echo "</table>\n";
	}
	
	function choosebackup($database) {
		echo "<form action=\"backup_main.php?option=databaseAdmin&task=doBackup\" method=\"post\">\n";
		echo "<table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "	<tr><th class=\"articlehead\">Database Tables Backup</th></tr>\n";
		echo "	<tr>\n		<td>\n";
		echo "			<p>&nbsp;<br>Where would you like to back up your Database Tables to?<br><br>\n";
//allow for choices easy ones save online or read it.
		echo "			<input type='radio' name=\"OutDest\" value=\"screen\"> Display Results on the Screen<br>\n";
		echo "			<input type='radio' name=\"OutDest\" value=\"local\" checked> Store the file in the backup directory on the server<br>&nbsp;\n";
		echo "			</p>\n 	</td>\n	</tr>\n";
		echo "	<tr>\n		<td>\n";
		echo "			<p>What format would you like to save them as?<br><br>\n";
		//allow room later for more choices of backup type such as zip etc but for now just sql is easy to work with.
		echo "			<input type='radio' name=\"OutType\" value=\"sql\" checked> As a SQL (plain text) file<br>&nbsp;\n";
		echo "			</p>\n 	</td>\n	</tr>\n";
		echo "	<tr>\n		<td>\n";
		//standard options for saveing (check all)
		echo "			<p>What do you want to back up?<br><br>\n";
		echo "			<input type='radio' name=\"toBackUp\" value=\"data\"> Data Only&nbsp; &nbsp;\n";
		echo "			<input type='radio' name=\"toBackUp\" value=\"structure\"> Structure Only&nbsp; &nbsp;\n";
		echo "			<input type='radio' name=\"toBackUp\" value=\"both\" checked> Data and Structure<br>&nbsp;\n";
		echo "			</p>\n 	</td>\n	</tr>\n";
		echo "	<tr>\n		<td align=\"center\">\n";
		//drop in the selector for the tables
		echo "			<p align=\"left\">Which Database Tables would you like to back up?<br>Please note, it is highly recommended you select ALL your tables.</p>\n";
		echo "			<select name=\"tables[]\" size=\"5\" MULTIPLE>\n";
		echo "			<option value=\"all\" selected>Backup All Tables&nbsp; &nbsp;\n";
		$result = openConnectionWithReturn("SHOW TABLES");
		while ($result2 = mysql_fetch_row($result)) {
			echo "			<option value=\"$result2[0]\">$result2[0]\n";
		}
		mysql_free_result($result);
		echo "			</select>\n		</td>\n	</tr>\n	<tr>\n";
		echo "		<td align=\"center\">&nbsp;<br><input type=submit value=\"Backup the Selected Tables\"></td>\n";
		echo "	</tr>\n</table>\n</form>\n";
xoops_cp_footer();	
}	
	function doBackup($database, $tables, $OutType, $OutDest, $toBackUp, $db_userAgent, $local_backup_path) {
		if (!$tables[0]) {
			echo "<p class=\"componentHeading\">Error! No database table(s) specified. Please select at least one table and re-try.</p>\n";
			$this->choosebackup($database);
			return;
		}
		//better name the file i guess
		//get the database name and throw the date on .sql
		//seems to be a problem obtaining the db name?
		//so we need to call the info (maybe this is bad)
		//include("../include/common.php");
		global $config;
		$filename = $db_database . "_" . date("j-m-Y") . ".sql";
					
		if ($tables[0] == "all") {
			array_pop($tables);
			$query1 = openConnectionWithReturn("SHOW TABLES");
			while ($result1 = mysql_fetch_row($query1)) {
				list(,$tables[]) = each($result1);
			}
			mysql_free_result($query1);
		}
		
		/* Store the "Create Tables" SQL in variable $CreateTable[$tblval] */

		if ($toBackUp!="data") {
			foreach ($tables as $tblval) {
				$query2 = openConnectionWithReturn("SHOW CREATE TABLE $tblval");
				while ($result2 = mysql_fetch_array($query2)) {
					$CreateTable[$tblval] = $result2;
				}
			}
			mysql_free_result($query2);
		}
		
		/* Store all the FIELD TYPES being backed-up (text fields need to be delimited) in variable $FieldType*/

		if ($toBackUp!="structure") {
			foreach ($tables as $tblval) {
				$query3 = openConnectionWithReturn("SHOW FIELDS FROM $tblval");
				while ($result3 = mysql_fetch_row($query3)) {
					$FieldType[$tblval][$result3[0]] = preg_replace("/[(0-9)]/",'', $result3[1]);
				}
			}
			mysql_free_result($query3);
		}
//better sign the backup so they got someone to blame when it breaks
		$OutBuffer = "#\n";
		$OutBuffer .= "# Admin Backup module\n";
		$OutBuffer .= "# Open-Realty version ".$config['version']."+\n";
		$OutBuffer .= "# http://www.open-realty.org\n";
		$OutBuffer .= "#\n";
		//the host server
		$OutBuffer .= "# Host: $db_server\n";
		//backup date
		$OutBuffer .= "# Backed Up On: " . date("j M, Y \a\\t H:i") . "\n";
		//the database
		$OutBuffer .= "# Database : `" . $db_database . "`\n# --------------------------------------------------------\n";
		
		//ok funs over time to write the tables
		//so sort it all out from information so far
		foreach ($tables as $tblval) {
			if ($toBackUp != "data") {
				$OutBuffer .= "#\n# Table structure for table `$tblval`\n#\nDROP TABLE IF EXISTS $tblval;\n" . $CreateTable[$tblval][1] . ";\n";
			}
			if ($toBackUp != "structure") {
				$OutBuffer .= "#\n# Dumping data for table `$tblval`\n#\n";
				$query4 = openConnectionWithReturn("SELECT * FROM $tblval");
				while ($result4 = mysql_fetch_array($query4, MYSQL_ASSOC)) {
					$InsertDump = "INSERT INTO $tblval VALUES (";
					while (list($key, $value) = each ($result4)) {
						if (preg_match ("/\b" . $FieldType[$tblval][$key] . "\b/i", "DATE TIME DATETIME CHAR VARCHAR TEXT TINYTEXT MEDIUMTEXT LONGTEXT BLOB TINYBLOB MEDIUMBLOB LONGBLOB ENUM SET")) {
							$InsertDump .= "'" . addslashes($value) . "',";
						} else {
							$InsertDump .= "$value,";
						}
					}
					$OutBuffer .= rtrim($InsertDump,',') . ");\n";
				}
				mysql_free_result($query4);
			}
		}
		
	//methods of storage as selected doit	
		//want to read it = screen
		if ($OutDest == "screen") :
			$OutBuffer = str_replace("<","&lt;",$OutBuffer);
			$OutBuffer = str_replace(">","&gt;",$OutBuffer);
			echo "<table width=\"100%\"><tr><td align=\"left\"><pre>" . $OutBuffer . "</pre>\n</td></tr></table>";
		elseif ($OutType == "sql") :
		//server folder storage
			if ($OutDest == "local") :
				$fp = fopen("$local_backup_path/$filename", "w");
				if (!$fp) :
					echo "<p align=\"center\" >Database backup FAILURE!!<br>File $local_backup_path/$filename not writable<br>Please contact your admin/webmaster!</p>";
				else :
					fwrite($fp, $OutBuffer);
					fclose($fp);
					echo "<p align=\"center\"  class=\"heading\">Database backup successful! Your file was saved on the server in directory :<br>$local_backup_path/$filename</p>";
				endif;
				$this->choosebackup($database);
			endif;
		endif;
	}//end backup
	//ok all backed up
	
	/* ********** RESTORATION ************* */
	
	function chooseRestore($database, $local_backup_path) {
		$uploads_okay = (function_exists('ini_get')) ? ((strtolower(ini_get('file_uploads')) == 'on' || ini_get('file_uploads') == 1) && intval(ini_get('upload_max_filesize'))) : (intval(@get_cfg_var('upload_max_filesize')));
		echo "<form action=\"backup_main.php?option=databaseAdmin&task=doRestore\" method=\"post\"";
		if ($uploads_okay) echo " enctype=\"multipart/form-data\"";
		echo ">\n";
		echo "\t<table border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"2\">\n";
		echo "\t\t<tr><th class=\"articlehead\" colspan=\"3\">Database Tables Restore</th></tr>\n";
		if (isset($local_backup_path)) {
			if ($handle = @opendir($local_backup_path)) {
				echo "\t\t<tr><td colspan=3><br>The following backups exist on the web server :<br>&nbsp;</td></tr>\n";
				echo "\t\t<tr><td class=\"heading\">&nbsp;</td><td class=\"heading\">Backup File Name</td><td class=\"heading\">Created Date/Time</td></tr>\n";
				while ($file = @readdir($handle)) {
					if (is_file($local_backup_path . "/" . $file)) {
						if (eregi(".\.sql$",$file) || eregi(".\.bz2$",$file) || eregi(".\.gz$",$file)) {
							echo "\t\t<tr><td align=\"center\"><input type=\"radio\" name=\"file\" value=\"$file\"></td><td>$file</td><td>" . date("m/d/y H:i:sa", filemtime($local_backup_path . "/" . $file)) . "</td></tr>\n";
						}
					}
				}
			} else {
				echo "\t\t<tr><td colspan=\"3\">Error!<br>Invalid or non-existant backup path in your configuration file : <br>" . $local_backup_path . "/" . $file . "</td></tr>\n";
			}
			@closedir($handle);
		} else {
			echo "\t\t<tr><td colspan=\"3\">Error!<br>Backup path in your configuration file has not been configured.</td></tr>\n";
		}
		if ($uploads_okay) {
	echo "\t\t<tr><td colspan=\"3\"><br>Or you can restore from a local file :</td></tr>\n";
	echo "\t\t<tr><td>&nbsp;</td><td><br><input type=\"file\" name=\"upfile\"></td><td>&nbsp;</td></tr>\n";
		}
		echo "\t\t<tr><td>&nbsp;</td><td>&nbsp;<br><input type=\"submit\" value=\"Ready To Restore\">&nbsp;&nbsp;<input type=\"reset\" value=\"Reset\"></td><td>&nbsp;</td></tr>\n";
		echo "\t</table>\n\t</form>\n";
	}
	
	function doRestore($database, $file, $uploadedFile, $local_backup_path) {
//hey no double loading allowed
		if (($file) && ($uploadedFile['name'])) {
			echo "<p>Error! Both a local file and one from the server cannot be specified at the same time.</p>\n";
			$this->chooseRestore($database, $local_backup_path);
			return;
		}
		//need a file first
		if ((!$file) && (!$uploadedFile['name'])) {
			echo "<p>Error! No restore file specified.</p>\n";
			$this->chooseRestore($database, $local_backup_path);
			return;
		}
		//cant connect to the folder?
				if ($file) :
			if (isset($local_backup_path)) :
				$infile = $local_backup_path . "/" . $file;
				$upfileFull = $file;
			else :
				echo "<p>Sorry you dont seem to have a backup folder installed</p>\n";
				$this->chooseRestore($database, $local_backup_path);
				return;
			endif;
			//its an upload but it isnt one we can work with no matter what we do or the server is able to do so kill it
		else :
			$upfileFull = $uploadedFile['name'];
			$infile = $uploadedFile['tmp_name'];
		endif;
		if (!eregi(".\.sql$",$upfileFull)) :
			echo "<p>Sorry but the file ($upfileFull)is not going to work.<br>Only *.sql files may be uploaded.</p>\n";
			$this->chooseRestore($database, $local_backup_path);
			return;
		endif;
		//ok we got the file but its got a problem
		if (substr($upfileFull,-4)==".sql") :
			$fp=fopen("$infile","r");
			if ((!$fp) || filesize("$infile")==0) :
				echo "<p>Sorry! But ($infile) Cant be read  or maybe its empty.</p>";
				$this->chooseRestore($database, $local_backup_path);
				return;
			else :
				$content=fread($fp,filesize("$infile"));
				fclose($fp);
				endif;
			endif;
		$decodedIn=explode(chr(10),$content);
		$decodedOut="";
		$queries=0;
		foreach ($decodedIn as $rawdata) {
			$rawdata=trim($rawdata);
			if (($rawdata!="") && ($rawdata{0}!="#")) {
				$decodedOut .= $rawdata;
				if (substr($rawdata,-1)==";") {
					if  ((substr($rawdata,-2)==");") || (strtoupper(substr($decodedOut,0,6))!="INSERT")) {
						if (eregi('^(DROP|CREATE)[[:space:]]+(IF EXISTS[[:space:]]+)?(DATABASE)[[:space:]]+(.+)', $decodedOut)) {
			//oops maybe the files been played with
							echo "<p>Sorry But there is foriegn statements in this file. DiD i write it?<br>You will have to clean the Drop statements out for me to use this file.</p>\n";
							$this->chooseRestore($database, $local_backup_path);
							return;
						}
						$query = openConnectionWithReturn($decodedOut);
						$decodedOut="";

						$queries++;
					}
				}
			}
		}
		echo "<p class=\"heading\">Success! Database has been restored to the backup you requested <br>($queries SQL queries processed).</p>\n";
		$this->chooseRestore($database, $local_backup_path);xoops_cp_footer();
		return;
	}
}
?>
