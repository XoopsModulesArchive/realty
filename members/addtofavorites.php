<?php
include("../../../mainfile.php");
include("../../../header.php");
OpenTable();
?>

<?php


include("../include/common.php");

loginCheckVisitor('User');


if ($listingID == "")
	{
	echo "<a href=\"../index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
	}	
	
	
elseif ($listingID != "")
	{
	  make_db_safe($userID);
	  make_db_safe($listingID);

	  $sql = "SELECT * FROM " . $config[table_prefix] . "userFavoriteListings WHERE user_ID = $userID AND listing_ID = $listingID";
	  $recordSet = $conn->Execute($sql);
	  if ($recordSet === false)
	    log_error($sql);
	  $num_columns = $recordSet->RecordCount();
	  if ($num_columns == 0)
	    {
	      $sql = "INSERT INTO " . $config[table_prefix] . "userFavoriteListings (user_ID, listing_ID) VALUES ($userID, $listingID)";
	  
	      $recordSet = $conn->Execute($sql);
	      if ($recordSet === false)
		log_error($sql);
	      else
		echo "<br>$lang[listing_added_to_favorites]";
	    }
	  else 
	    {
	      echo "<br>$lang[listing_already_in_favorites]";
	    }


	}

?>
<?
CloseTable();
include("../../../footer.php");
?>