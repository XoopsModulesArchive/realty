<?php

function notifyNewListing($listingID) {
global $conn, $lang, $config;
$sql = "SELECT " . $config[table_prefix] . "listingsDB.ID, " . $config[table_prefix] . "listingsDB.Title, " . $config[table_prefix] . "listingsDBElements.field_name, " . $config[table_prefix] . "listingsDBElements.field_value FROM " . $config[table_prefix] . "listingsDB, " . $config[table_prefix] . "listingsDBElements WHERE (" . $config[table_prefix] . "listingsDB.ID = " . $listingID . ") AND (" . $config[table_prefix] . "listingsDBElements.listing_id = " . $config[table_prefix] . "listingsDB.ID) AND (" . $config[table_prefix] . "listingsDB.active = 'yes')";

$recordSet = $conn->Execute($sql);
if ($recordSet === false) log_error($sql);

$hasImages = false;
$sql = "SELECT ID, COUNT(file_name) AS imageCount FROM " . $config[table_prefix] . "listingsImages WHERE listing_id = " . $listingID . " GROUP BY listing_id";
$tempRecordSet = $conn->Execute($sql);
if ($tempRecordSet === false) log_error($sql);
if ($tempRecordSet->RecordCount() <> 0)
while (!$tempRecordSet->EOF) {
  if ($tempRecordSet->fields[imageCount] > 0) {
    $hasImages = true;
  }
  $tempRecordSet->MoveNext();
}
//User Saved Searches
$sql = "SELECT user_ID, Title, query_string FROM " . $config[table_prefix] . "userSavedSearches";
$recordSet2 = $conn->Execute($sql);
if ($recordSet2 === false) log_error($sql);

while (!$recordSet2->EOF) {
 $check = true;
 $query_string = make_db_unsafe($recordSet2->fields[query_string]);

 $param_value_arr = "";
 $param_name = "";
 $param_value = "";
 $QUERY_GET_VARS = "";
 
 //break down query string start -----------------------------------------------------
 while ($query_string != "") {
   $multiple = false;
   $param_string = substr($query_string, 0, strcspn($query_string, "&"));
   $param_name = urldecode(substr($param_string, 0, strcspn($param_string, "=")));
   if (substr($param_name, strlen($param_name) - 2) == "[]") {
     $param_name = substr($param_name, 0, strlen($param_name) - 2);
     if (!is_array($param_value_arr[$param_name]))
    {
          $param_value_arr[$param_name] = array(urldecode(substr($param_string, strcspn($param_string, "=") + 1)));
    }
     else
    {
          array_push($param_value_arr[$param_name], urldecode(substr($param_string, strcspn($param_string, "=") + 1)));
    }
     $multiple = true;

   }
   else {
     $param_value = urldecode(substr($param_string, strcspn($param_string, "=") + 1));
   }
   
   if ($multiple == false)
   {
        $QUERY_GET_VARS[$param_name] = $param_value;
   }
   else
   {
     $QUERY_GET_VARS[$param_name] = $param_value_arr[$param_name];
   }
   $query_string = substr($query_string, strcspn($query_string, "&") + 1);
   $string = $new_query_string;
 }

 if (is_array($QUERY_GET_VARS))
 {
    reset($QUERY_GET_VARS);
 }
 next($QUERY_GET_VARS);
 while (list($ElementIndexValue, $ElementContents) = each($QUERY_GET_VARS))
 {
     $recordSet->MoveFirst();
     if ($ElementIndexValue == "sortby")
       {
     // do nothing
       }
     elseif ($ElementIndexValue == "cur_page")
       {
     // do nothing
       }
     elseif ($ElementIndexValue == "PHPSESSID")
       {
     // do nothing
       }
    elseif ($ElementIndexValue == "imagesOnly")
      {
       if ($ElementContents == "yes")
         {
          if (!$hasImages)
          {
            $check = false;
          }
         }
     } // end elseif ($ElementIndexValue == "imagesOnly")
    
     elseif (is_array($ElementContents))// what to do if you have the possibility of a field containing multiple items
     {
       $feature_check = false;
       while (list($featureValue, $feature_item) = each ($ElementContents))
         {
          $recordSet->MoveFirst();
          while(!$recordSet->EOF)
          {
            if ($recordSet->fields[field_name] != $ElementIndexValue)
            {
               $recordSet->MoveNext();
               continue;
            }
            if($ElementIndexValue == "price")//this never happens - code for price is below
            {
               $priceMinMax = explode("||", $feature_item);
               if ((($recordSet->fields[field_value] >= $priceMinMax[0]) && ($recordSet->fields[field_value] <= $priceMinMax[1])) || ($recordSet->fields[field_value] == ""))
               {
                  $feature_check = true;
                  break;
               }
            }
            if ($recordSet->fields[field_value] == $feature_item)
            {
               $feature_check = true;
               break;
            }
            
            if ($feature_check == true)
            {
               break;
            }
               
            $recordSet->MoveNext();
          }//End While(!$recordSet->EOF)
         
        if ($feature_check == true)
         break;
       }// END while (list($featureValue, $feature_item) = each ($ElementContents))
      
       if($feature_check == false) {
         $check = false;
         break;
       }
      } // end elseif (is_array($ElementContents))
        //min and max are remnants of the get, so ignore them
       elseif($ElementIndexValue == "min" || $ElementIndexValue == "max")
       {
         next;
       }
       else
      {
       $feature_check = false;
       $recordSet->MoveFirst();
       while(!$recordSet->EOF)
       {
          //This is where it checks the price min and max criteria - Added by abarker@ccslo.com
          if(($ElementIndexValue == "price-min" || $ElementIndexValue == "price-max") && $recordSet->fields[field_name] == "price")//is not working
         {
            If ($ElementIndexValue == "price-min")
            {
               if ($recordSet->fields[field_value] >= $ElementContents || ($recordSet->fields[field_value] == ""))
               {
                  $feature_check = true;
                  break;
               }
            }
            If ($ElementIndexValue == "price-max")
            {
               if ($recordSet->fields[field_value] <= $ElementContents || ($recordSet->fields[field_value] == ""))
               {
                  $feature_check = true;
                  break;
               }
            }
         }
 
            if ($recordSet->fields[field_name] != $ElementIndexValue)
         {
             $recordSet->MoveNext();
             continue;//restarts at begining of loop
          }
         if ($recordSet->fields[field_value] == $ElementContents)
         {
          $feature_check = true;
          break;
         }
         if ($feature_check == true)
            break;
         $recordSet->MoveNext();
        }//end while(!$recordSet->EOF)
     
    if ($feature_check == false)
    {
       $check = false;
       break;
     }

   } // end else
  } // end while
 //break down query string stop -----------------------------------------
 if ($check === true) {

   $sql = "SELECT user_name, emailAddress FROM " . $config[table_prefix] . "UserDB WHERE ID = " . $recordSet2->fields[user_ID];
   $recordSet3 = $conn->Execute($sql);
   if ($recordSet3 === false) log_error($sql);

   $message = $lang[automated_email] . "\r\n\r\n\r\n" . date("F j, Y, g:i:s a")."\r\n\r\n" . $lang[new_listing_notify_long] . "'" . $recordSet2->fields[Title] . "'.\r\n\r\n" . $lang[click_on_link_to_view_listing] . "\r\n\r\n$config[baseurl]/listingview.php?listingID=" . $listingID . "\r\n\r\n\r\n" . $lang[automated_email] . "\r\n";

   mail($recordSet3->fields[emailAddress], $lang[new_listing_notify] . "'" . $recordSet2->fields[Title] . "'", $message,"From: " . $config[admin_name] . " <" . $config[automated_email] .">", "-f" . $config[automated_email]);

echo($lang[new_listing_email_sent] .  $recordSet3->fields[user_name] . " &mdash; ':" . $lang[new_listing_notify] . "'". $recordSet2->fields[Title] . "'");
   //   send_new_listing_email($search_userID, $listingID, $recordSet->fields[Title]);

 }     
 $recordSet2->MoveNext();
}//end while (!$recordSet2->EOF)

}//end function

?> 
