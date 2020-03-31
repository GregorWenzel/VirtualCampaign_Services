<?php    

include "local_config.php";

   $query = "SELECT * FROM stromkosten";
   $result = mysql_query($query);
   $serverdata = mysql_fetch_array($result);
   
   print("<StaticImages>".$serverdata['noVideos']."</StaticImages>");
   

mysql_close($mysql);
?>