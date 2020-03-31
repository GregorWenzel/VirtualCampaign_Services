<?php
   //connect to the database
include "local_config.php";
 
   $query = "UPDATE users SET online=0, heartbeat = 0 WHERE ID=".$_POST['ID'];
   db_query($query);

?>
