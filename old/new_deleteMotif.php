<?php    

   //connect to the database
   $mysql = mysql_connect("localhost", "gregor", "r0binhood");
   mysql_select_db("virtualcampaign");

   $query = "SELECT * FROM motifs WHERE ID=".$_POST['ID'];
   $result = mysql_query($query);
   $motif = mysql_fetch_object($result);
   $PATH = $motif->ownerID;

   $DIR = "x:/web/virtualcampaign/users/".$PATH."/motifs";
   
   unlink($DIR."/".$_POST['ID']."_thumb.jpg");
   unlink($DIR."/".$_POST['ID']."_bigthumb.jpg");
   unlink($DIR."/".$_POST['ID'].".jpg");

   $query = "DELETE FROM motifs WHERE ID=".$_POST['ID'];
   mysql_query($query);

mysql_close($mysql);
?>