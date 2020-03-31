<?php 
     
   include "local_config.php";
   
   $UPLOADDIR = $local_path."/temp/";
   $name = substr($_POST['motifID'],0,strlen($_POST['motifID'])-4);    
   
   $query = "INSERT INTO motifs (name, ownerID, aspect, width, fileID, creationDate, type, comment, nFrames) VALUES ";
   $query .= "('".$_POST['motifName']."', ".$_POST['ownerID'].", ".$_POST['aspect'].", ".$_POST['width'].",-1,".time().",".$_POST['motifType'].", '".$_POST['motifComment']."', ".$_POST['nFrames'].")";
   db_query($query);

   $id = mysql_insert_id();
     
   $cmd = 'move "'.$UPLOADDIR."temp".$_POST['ownerID'].'" "'.$local_path."\\users\\".$_POST['ownerID']."\\motifs\\".$id.'"';
   writelog($cmd);
   writelog(syscall(str_replace("/", "\\", $cmd)));    
  
   $cmd = 'copy "'.$UPLOADDIR.$name.'.jpg" "'.$local_path."\\users\\".$_POST['ownerID']."\\motifs\\".$id.'\\motif.jpg"';
   writelog($cmd);
   writelog(syscall(str_replace("/", "\\", $cmd)));
   
   $cmd = 'copy "'.$UPLOADDIR.$name.'_thumb.jpg" "'.$local_path."\\users\\".$_POST['ownerID']."\\motifs\\".$id.'\\motif_thumb.jpg"';
   writelog($cmd);
   writelog(syscall(str_replace("/", "\\", $cmd)));
   

   
  // unlink($UPLOADDIR.$name.".jpg");
  // unlink($UPLOADDIR.$name."_thumb.jpg");
   
   print("<ID>".$id."</ID>");
   
mysql_close($mysql);

?>