<?php
include("../../../mainfile.php");
include("../../../header.php");
OpenTable();
?>
<?php
include("../include/common.php");
global $config, $conn, $lang, $action;
loginCheckVisitor('User');
include("$config[template_path]/user_top.html");
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																				*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
//it is vital that save is done with no action otherwise the action becomes part of the query
if ($action=="save"){echo"incorrect save method please retry";}
if (!$action)
		{
		global $title, $query;
		
		if (substr($QUERY_STRING, 0, strcspn($QUERY_STRING, "=")) == "cur_page") {
		  $QUERY_STRING = substr($QUERY_STRING, strcspn($QUERY_STRING, "&") + 1);
		  //  echo $QUERY_STRING;
		}
		
		make_db_safe($userID);
		make_db_safe($title);
		make_db_safe($query);
		
				
		if ($title != "") {
		  $sql = "SELECT * FROM ".$config['table_prefix']."autosavedsearches WHERE user_ID = $userID AND title = '$title'";
		  $recordSet = $conn->Execute($sql);
		  if ($recordSet === false)
		    log_error($sql);
		  $num_columns = $recordSet->RecordCount();
		  if ($num_columns == 0) {
		  $query = str_replace("action=save&", "", $query2);
		  $query = str_replace("action%3Dsave%26", "", $query2);
		    $sql = "INSERT INTO ".$config['table_prefix']."autosavedsearches (user_ID, title, query_string) VALUES ($userID, '$title', '$query2')";
		    $recordSet = $conn->Execute($sql);
		    if ($recordSet === false) {
		      log_error($sql);
		    } else
		      echo "<br>$lang[search_added_to_saved_searches]";
		  }
		  else {
		    echo '<br>'.$lang['search_title_already_in_saved_searches'].'<br>
		
		<br>
		 <form action="userautosearch.php">
		 
		    '.$lang['Enter_title_for_search'].': <input type="text" name="title"><br><br>
		    <input type="submit" value="'.$lang['save_search'].'">
		    <input type="hidden" name="query" value="'.$query.'">
		 </form>
		<br>';
		
		 }
		}
		
		else { 
		
		  $sql = "SELECT Title, query_string FROM ".$config['table_prefix']."autosavedsearches WHERE user_ID = $userID AND query_string = '$QUERY_STRING'";
		  $recordSet = $conn->Execute($sql);
		  $QUERY_STRING = str_replace("action=save&", "", $QUERY_STRING);
		  $QUERY_STRING = str_replace("action%3Dsave%26", "", $QUERY_STRING);
		  if ($recordSet === false)
		    log_error($sql);
		  $num_columns = $recordSet->RecordCount();
		  if ($num_columns != 0)
		    {
		      echo "<br>$lang[search_already_in_saved_searches]<a href=\"$config[baseurl]/autosearch.php?". make_db_unsafe($recordSet->fields['query_string'] . "\">" .make_db_unsafe($recordSet->fields['Title']) . "</a><br>");
		    }
		
		echo '
		 <form action="userautosearch.php">
		 
		    '.$lang['Enter_title_for_search'].': <input type="text" name="title"><br><br>
		    <input type="submit" value="'.$lang['save_search'].'">
		    <input type="hidden" name="query" value="'.$QUERY_STRING.'">
			
		 </form>
		<br>';
		 }
}//end action save
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																				*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
if ($action == "list")
		{
				echo '<h3>'.$lang['saved_searches'].'</h3>';
		
		make_db_safe($userID);
		$sql = "SELECT ID, Title, query_string FROM ".$config['table_prefix']."autosavedsearches WHERE user_ID = $userID ORDER BY title";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) log_error($sql);
		
		$num_columns = $recordSet->RecordCount();
		if ($num_columns == 0)
		{
		  echo $lang['no_saved_searches'].'<br><br>';
		}
		else {
		  while (!$recordSet->EOF) {
		    echo '<a href="'.$config['baseurl'].'/auto_browse.php?' . make_db_unsafe($recordSet->fields['query_string']) .'">' . make_db_unsafe($recordSet->fields['Title']) . '</a>&nbsp;&nbsp;&nbsp;&nbsp;<div class="note"><a href="'.$config['baseurl'].'/members/userautosearch.php?action=delete&amp;searchID='. make_db_unsafe($recordSet->fields['ID']).'"  onClick="return confirmDelete()">'.$lang['delete_search'].'</a></div><br><br>';
		    $recordSet->MoveNext();
		  }
		}
		}// end action list
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/*																				*/
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
if ($action == "delete")
		{
					if ($searchID == "")
			{
			  echo '<a href="'.$config['baseurl'].'/index.php">'.$lang['perhaps_you_were_looking_something_else'].'</a>';
			}	
			
			
			elseif ($searchID != "")
			{
			  make_db_safe($userID);
			  make_db_safe($searchID);
			  
			  $sql = "DELETE FROM ".$config['table_prefix']."autosavedsearches WHERE ID = $searchID";
			  $recordSet = $conn->Execute($sql);
			  if ($recordSet === false) log_error($sql);
			  echo "<br>$lang[search_deleted_from_favorites]";  
			}
		}// end action delete

?>
<?
CloseTable();
include("../../../footer.php");
?>