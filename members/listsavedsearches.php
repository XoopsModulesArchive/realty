
<?php
include("../../../mainfile.php");
include("../../../header.php");
OpenTable();
?>

<?php

include("../include/common.php");

loginCheckVisitor('User');


echo "<h3>$lang[saved_searches]</h3>";

make_db_safe($userID);
$sql = "SELECT ID, title, query_string FROM " . $config[table_prefix] . "userSavedSearches WHERE user_ID = $userID ORDER BY title";
$recordSet = $conn->Execute($sql);
if ($recordSet === false) log_error($sql);

$num_columns = $recordSet->RecordCount();
if ($num_columns == 0)
{
  echo "$lang[no_saved_searches]<br><br>";
}
else {
  while (!$recordSet->EOF) {
    echo "<a href=\"../listing_browse.php?" . make_db_unsafe($recordSet->fields[query_string]) . "\">" . make_db_unsafe($recordSet->fields[title]) . "</a>&nbsp;&nbsp;&nbsp;&nbsp;<div class=\"note\"><a href=\"deletesavedsearch.php?searchID=". make_db_unsafe($recordSet->fields[ID]) . "\"  onClick=\"return confirmDelete()\">$lang[delete_search]</a></div><br><br>";
    $recordSet->MoveNext();
  }
}


?>
<?
CloseTable();
include("../../../footer.php");
?>