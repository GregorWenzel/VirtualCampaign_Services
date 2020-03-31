<?php 
   include "local_config.php";

	$NEWHEIGHT_S = 120;
	$NEWHEIGHT_L = 300;
    
/*	
 
   $name = substr($_POST['name'],0,strlen($_POST['name'])-4);
   $name = "temp_".$_POST['userID'];

   move_uploaded_file($_FILES['Filedata']['tmp_name'], $UPLOADDIR.$name.".jpg");
   list($width, $height, $type, $attr) = getimagesize($UPLOADDIR.$name.".jpg");
   $aspect = $width/$height;

*/ 
	$UPLOADDIR = $local_path."/temp/";
	$filename = $_FILES['Filedata']['name'];
	$extension = ".".end(explode(".", $filename));
	$name = "temp_".$_POST['userID'];
	$rnd = rand(0, 9999);
	
	move_uploaded_file($_FILES['Filedata']['tmp_name'], $UPLOADDIR.$rnd.$extension);
		
	$cmd = '"'.$im_path.'/convert.exe " -colorspace rgb -quality 100 -density 300 "'.$UPLOADDIR.$rnd.$extension.'" "'.$UPLOADDIR.$name.'.jpg"';
	writelog($cmd);
	writelog(syscall($cmd));
	
	list($width, $height, $type, $attr) = getimagesize($UPLOADDIR.$name.".jpg");
    $aspect = $width/$height;
	
   $cmd = '"'.$im_path.'/convert.exe" "'.$UPLOADDIR.$name.'.jpg" -resize '.$aspect*$NEWHEIGHT_S."x".$NEWHEIGHT_S.' "'.$UPLOADDIR.$name.'_thumb.jpg"';
     
   writelog($cmd);
   writelog(syscall($cmd));
   
   $cmd = '"'.$im_path.'/convert.exe" "'.$UPLOADDIR.$name.'.jpg" -resize '.$aspect*$NEWHEIGHT_L."x".$NEWHEIGHT_L.' "'.$UPLOADDIR.$name.'_bigthumb.jpg"';

   writelog($cmd);
   writelog(syscall($cmd));

  // unlink($UPLOADDIR.$rnd.$extension);
   writelog("<Result><Name>".$name."</Name><Animated>0</Animated><nFrames>1</nFrames><Aspect>".$aspect."</Aspect></Result>");
   print("<Result><Name>".$name."</Name><Animated>0</Animated><nFrames>1</nFrames><Aspect>".$aspect."</Aspect></Result>");
   

?>
