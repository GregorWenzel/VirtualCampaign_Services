<?php
   //connect to the database
include "local_config.php";

   $query = "SELECT * FROM groups ORDER BY ID";
   $result = db_query($query);

   $output = "<Groups>";
   
   while($group = mysql_fetch_object($result))
   { 
     $output .= "<Group><Name>".$group->group_name."</Name><ID>".$group->ID."</ID></Group>";
   }

   $output .= "</Groups>";  
   print($output);

?>
