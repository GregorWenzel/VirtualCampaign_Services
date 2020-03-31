<?php 

	$NEWHEIGHT_S = 120;
	$NEWHEIGHT_L = 300;
	
	//init logfile
	$lf0 = "X:/web/virtualcampaign/logs/logfile".date("Y-m-d", time()).".txt";
    $fh0 = fopen($lf0, 'a');

   $UPLOADDIR = "x:/web/virtualcampaign/users/";
   $upload_dir = $UPLOADDIR.$_POST['USERID'];
   
   //connect to the database
   $mysql = mysql_connect("localhost", "gregor", "r0binhood");
   mysql_select_db("virtualcampaign");

   $timestamp = time();
   $date = date("Y-m-d",$timestamp);

   $query = "INSERT INTO motifs (creationDate, ownerID) VALUES ('".$timestamp."', ".$_POST['USERID'].")";
   mysql_query($query);
   writelog($query);

   //$query = "SELECT LAST_INSERT_ID()";
   //$result = mysql_query($query);
   //$maxid = mysql_fetch_array($result);
   $motifID = mysql_insert_id();

   move_uploaded_file($_FILES['Filedata']['tmp_name'], $upload_dir."/motifs/".$motifID.".jpg");
   list($width, $height, $type, $attr) = getimagesize($upload_dir."/motifs/".$motifID.".jpg");
   $aspect = $width/$height;
   $cmd = '"C:\\Program Files (x86)\\ImageMagick-6.5.5-Q16\\convert.exe" '.$upload_dir."/motifs/".$motifID.".jpg -resize ".$aspect*$NEWHEIGHT_S."x".$NEWHEIGHT_S." ".$upload_dir."/motifs/".$motifID."_thumb.jpg";
   
   writelog($cmd);
   writelog(syscall($cmd));
   
   $cmd = '"C:\\Program Files (x86)\ImageMagick-6.5.5-Q16\\convert.exe" '.$upload_dir."/motifs/".$motifID.".jpg"." -resize ".$aspect*$NEWHEIGHT_L."x".$NEWHEIGHT_L." ".$upload_dir."/motifs/".$motifID."_bigthumb.jpg";
   
   writelog($cmd);
   writelog(syscall($cmd));
   
   /*
   $magick = NewMagickWand();
   MagickReadImage($magick, $UPLOADDIR."/motifs/".$motifID.".jpg");
   $width = MagickGetImageWidth($magick);
   $height = MagickGetImageHeight($magick);
   
   MagickResizeImage($magick, $aspect*$NEWHEIGHT_S, $NEWHEIGHT_S, MW_GaussianFilter, 0.8);
   MagickWriteImage($magick, $UPLOADDIR."/motifs/".$motifID."_thumb.jpg");
   MagickReadImage($magick, $UPLOADDIR."/motifs/".$motifID.".jpg");
   MagickResizeImage($magick, $NEWHEIGHT_L*$aspect, $NEWHEIGHT_L, MW_GaussianFilter, 0.8);
   MagickWriteImage($magick, $UPLOADDIR."/motifs/".$motifID."_bigthumb.jpg");
   */
   
   $smallpath = "http://callisto-studios.dyndns.org/virtualcampaign/users/".$_POST['USERID']."/motifs/".$motifID."_thumb.jpg";
   $bigpath = "http://callisto-studios.dyndns.org/virtualcampaign/users/".$_POST['USERID']."/motifs/".$motifID."_bigthumb.jpg";

   print("<Result><ID>".$motifID."</ID><SmallPath>".$smallpath."</SmallPath><BigPath>".$bigpath."</BigPath><Width>".$width."</Width><Height>".$height."</Height><Aspect>".$aspect."</Aspect></Result>");

   $query = "UPDATE motifs SET width=".$width.", aspect=".$aspect.", fileID=".$motifID." WHERE ID=".$motifID;
   mysql_query($query);

mysql_close($mysql);

function writelog($txt)
{
	global $fh0;
	fwrite($fh0, date("Y-m-d H:i:s",time())."> ".$txt."\r\n");
}

function syscall($command){
	if ($proc = popen("($command)2>&1","r")){
        	while (!feof($proc)) $result .= fgets($proc, 1000);
	        pclose($proc);
        	return $result;
        }
}	

?>
