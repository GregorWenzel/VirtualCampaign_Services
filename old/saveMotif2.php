<?php  
   include "local_config.php";
   
   $UPLOADDIR = $local_path."/temp/";
   $name = substr($_POST['motifID'],0,strlen($_POST['motifID'])-4);
   $name = "temp_".$_POST['ownerID'];
   
   $query = "INSERT INTO motifs (name, ownerID, aspect, width, fileID, creationDate, type, comment) VALUES ";
   $query .= "('".$_POST['motifName']."', ".$_POST['ownerID'].", ".round($_POST['aspect'],2).", ".$_POST['width'].",-1,".time().",".$_POST['motifType'].", '".$_POST['motifComment']."')";
   db_query($query);

   $id = mysql_insert_id();

   $cmd = 'copy "'.$UPLOADDIR.$name.'.jpg" '.$local_path."\\users\\".$_POST['ownerID']."\\motifs\\".$id."s.jpg";
   writelog(str_replace("/", "\\", $cmd));
   writelog(syscall(str_replace("/", "\\", $cmd)));

   
   $cmd = 'copy "'.$UPLOADDIR.$name.'_thumb.jpg" '.$local_path."\\users\\".$_POST['ownerID']."\\motifs\\".$id."_thumb.jpg";
   writelog(str_replace("/", "\\", $cmd));
   writelog(syscall(str_replace("/", "\\", $cmd)));

   
   unlink($UPLOADDIR.$name.".jpg");
   unlink($UPLOADDIR.$name."_thumb.jpg");
   
   print("<ID>".$id."</ID>");
   
mysql_close($mysql);


?>
