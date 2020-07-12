<?php
include("../../../mainfile.php");
include("../../../header.php");
OpenTable();
?>
<?php

include("../include/common.php");

loginCheckVisitor('User');



if (substr($QUERY_STRING, 0, strcspn($QUERY_STRING, "=")) == "cur_page") {
  $QUERY_STRING = substr($QUERY_STRING, strcspn($QUERY_STRING, "&") + 1);
  //  echo $QUERY_STRING;
}

make_db_safe($userID);
make_db_safe($title);
make_db_safe($query);

if ($title != "") {
  $sql = "SELECT * FROM " . $config[table_prefix] . "userSavedSearches WHERE user_ID = $userID AND title = '$title'";
  $recordSet = $conn->Execute($sql);
  if ($recordSet === false)
    log_error($sql);
  $num_columns = $recordSet->RecordCount();
  if ($num_columns == 0) {
    $sql = "INSERT INTO " . $config[table_prefix] . "userSavedSearches (user_ID, title, query_string) VALUES ($userID, '$title', '$query')";
    $recordSet = $conn->Execute($sql);
    if ($recordSet === false) {
      log_error($sql);
    } else
      echo "<br>$lang[search_added_to_saved_searches]";
  }
  else {
    echo "<br>$lang[search_title_already_in_saved_searches]<br>";
?>
<br>
 <form action="savesearch.php">
    Enter a title for the search: <input type="text" name="title"><br><br>
    <input type="submit" value="Save Search">
    <input type="hidden" name="query" value="<?php echo $query; ?>">
 </form>
<br>

<?php }
}

else { 

  $sql = "SELECT title, query_string FROM " . $config[table_prefix] . "userSavedSearches WHERE user_ID = $userID AND query_string = '$QUERY_STRING'";
  $recordSet = $conn->Execute($sql);
  if ($recordSet === false)
    log_error($sql);
  $num_columns = $recordSet->RecordCount();
  if ($num_columns != 0)
    {
      echo "<br>$lang[search_already_in_saved_searches]<a href=\"../listingsearch.php?". make_db_unsafe($recordSet->fields[query_string] . "\">" .make_db_unsafe($recordSet->fields[title]) . "</a><br>");
    }

?>
<br>
 <form action="savesearch.php">
    Enter a title for the search: <input type="text" name="title"><br><br>
    <input type="submit" value="Save Search">
    <input type="hidden" name="query" value="<?php echo $QUERY_STRING; ?>">
 </form>
<br>
<?php }



?>
<?
CloseTable();
include("../../../footer.php");
?>