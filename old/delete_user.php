<?php    

   //connect to the database
include "local_config.php";

   $query = "DELETE FROM users WHERE ID=".$_POST['userID'];
   db_query($query); 

?>