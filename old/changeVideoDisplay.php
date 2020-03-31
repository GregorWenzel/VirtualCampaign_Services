<?php    

   //connect to the database
include "local_config.php";

   $query = "UPDATE stromkosten SET noVideos = ".$_POST['NoVideos'];
   
   db_query($query);
?>