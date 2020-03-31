<?php 
   $ID = $_POST['ID'];	
   $sort = $_POST['sortType'];
   
	include "local_config.php";

   $query = "DELETE FROM motifs WHERE ISNULL(type)";
   mysql_query($query);

   $output = "<Motifs><Motif>0</Motif>";
   
   $query = "SELECT * FROM motifs WHERE ownerID=".$ID." ORDER BY creationDate DESC";
  
   $result = mysql_query($query);
   
   while ($motif = mysql_fetch_object($result))
   {
     $output .= "<Motif><ID>".$motif->ID."</ID>";
     if (intval($motif->nFrames)  == 1)
        $output .="<Path>".$motif->ownerID."/motifs/".$motif->ID."_thumb.jpg</Path>";
     else
        $output .="<Path>".$motif->ownerID."/motifs/".$motif->ID."/motif.jpg</Path>";
     $output .= "<Name>".$motif->name."</Name><Date>".$motif->creationDate."</Date><Type>".$motif->type."</Type><Width>".$motif->width."</Width><Height>".round(($motif->width)/($motif->aspect))."</Height>";
     $output .= "<Comment>".$motif->comment."</Comment><Aspect>".$motif->aspect."</Aspect><Frames>".$motif->nFrames."</Frames></Motif>";
   }
  
   $output .= "</Motifs>";

   print($output);

mysql_close($mysql);
?>
