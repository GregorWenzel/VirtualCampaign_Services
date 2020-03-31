<?php 
   include "local_config.php";

	$NEWHEIGHT_S = 120;
	$NEWHEIGHT_L = 300;
    
    //$name = substr($_POST['name'],0,strlen($_POST['name'])-4);
          
   $UPLOADDIR = $local_path."/temp/";
   $nname = trim($_POST['name']);
   if (strlen($nname) == 0)
   {
	writelog("No parameters received... exiting.");
	exit;
   }

   $name = "temp_".$_POST['userID'].".".$_POST['extension'];

   writelog("Attempting to write ". $UPLOADDIR.$name);   
   move_uploaded_file($_FILES['Filedata']['tmp_name'], $UPLOADDIR.$name);

   if (is_dir($UPLOADDIR."temp".$_POST['userID']))
        rmdir_recursive($UPLOADDIR."temp".$_POST['userID']);
   mkdir($UPLOADDIR."/temp".$_POST['userID']);
   
   $cmd = '"'.$ffmpeg_path.'/ffmpeg.exe" -i '.$UPLOADDIR.$name." ".$UPLOADDIR."temp".$_POST['userID'].'\\motif_F%04d.tga';
    
   writelog($cmd);
   writelog(syscall(str_replace("/", "\\",$cmd)));
  
   unlink($UPLOADDIR.$name);
   
   $nFrames = count(glob($UPLOADDIR."temp".$_POST['userID']."/*.tga"));
   $name = substr($_POST['name'],0,strlen($_POST['name'])-4);
   copy($UPLOADDIR."temp".$_POST['userID'].'\\motif_F0002.tga', $UPLOADDIR.$name.".tga");
   
   $cmd = '"'.$im_path.'/convert.exe" "'.$UPLOADDIR.$name.'.tga" -format jpg "'.$UPLOADDIR.$name.'.jpg"';
   writelog($cmd);
   writelog(syscall($cmd));
   
   $width = 0;
   while ($width == 0)
   {
     //writelog($UPLOADDIR.$name.'.jpg');
     list($width, $height, $type, $attr) = getimagesize($UPLOADDIR.$name.'.jpg');
   }
   
   $aspect = $width/$height;

   $cmd = '"'.$im_path.'/convert.exe" "'.$UPLOADDIR.$name.'.jpg" -resize '.$aspect*$NEWHEIGHT_S."x".$NEWHEIGHT_S.' "'.$UPLOADDIR.$name.'_thumb.jpg"';
     
   writelog($cmd);
   writelog(syscall($cmd));
   
   $cmd = '"'.$im_path.'/convert.exe" "'.$UPLOADDIR.$name.'.jpg" -resize '.$aspect*$NEWHEIGHT_L."x".$NEWHEIGHT_L.' "'.$UPLOADDIR.$name.'_bigthumb.jpg"';

   writelog($cmd);
   writelog(syscall($cmd));

   $cmd = 'copy "'.$UPLOADDIR.$name.'_bigthumb.jpg" '.$UPLOADDIR."temp_".$_POST['userID']."_bigthumb.jpg";
   writelog($cmd);
   writelog(syscall(str_replace("/", "\\", $cmd)));

   
  // unlink($UPLOADDIR.$name.'.jpg');
   print("<Result><Name>".$name."</Name><Aspect>".$aspect."</Aspect><Animated>1</Animated><nFrames>".$nFrames."</nFrames></Result>");


function rmdir_recursive($dir) {
    $files = scandir($dir);
    array_shift($files);    // remove '.' from array
    array_shift($files);    // remove '..' from array
    
    foreach ($files as $file) {
        $file = $dir . '/' . $file;
        if (is_dir($file)) {
            rmdir_recursive($file);
            rmdir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dir);
}
?>
