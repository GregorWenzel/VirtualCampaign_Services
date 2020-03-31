<?php

/**
 * @author 
 * @copyright 2010
 */

 	set_time_limit(300000);
 $timestamp = time();
  $date = date("Y-m-d - G:i:s",$timestamp);
  $time = date("", $timestamp);	
    $local_path = "X:/web/virtualcampaign";
    $db_backup_path = $local_path."/db_backup/";

    $music_path = $local_path."/ressources/music/";
    $ffmpeg_path = "C:/ffmpeg";
    $sox_path = "C:/sox/sox.exe";
    $im_path = "c:/imagemagick/";
   	$FUSION_PATH = "C:/Program Files (x86)/eyeon/Render Slave 5.3.78/";
	$SCRIPT_PATH = $local_path."/scripts/fusion/";
    
 	//init arry defs
 	$types = array("Production", "Single");
  	$motifchars = array("A","B","C","D","E","F","G","H","I","J","K","L","M");
  	
	//init logfile
	$lf0 = $local_path."/logs/logfile2".date("Y-m-d", time()).".txt";
	$fh0 = fopen($lf0, 'a');
    
echo ($lf0);

    //connect to the database
  
  $mysql = mysql_connect("localhost", "gregor", "r0binhood");
  mysql_select_db("virtualcampaign");

  //backup database
  if (!is_file($db_backup_path.date("Y-m-d", time()).".sql"))
  {
	$cmd = "mysqldump --opt -h localhost -ugregor -pr0binhood virtualcampaign > ".$db_backup_path.date("Y-m-d", time()).".sql";
	writelog($cmd);
	writelog(syscall($cmd));
  }
  function writelog($txt) {
 global $fh0;
 fwrite($fh0, date('Y-m-d H:i:s', time()).'> '.$txt."\r\n");
}

function write_error($txt) {
 global $fh0;
 fwrite($fh0, date('Y-m-d H:i:s', time()).' ERROR > '.$txt."\r\n");
}

function sstr($txt) {
 return "'".$txt."'";
}

function dsstr($str)
{
	return "\"".$str."\"";
}

function db_query($cmd, $log=true)
{
	global $mysql;
	if ($log)
 		writelog($cmd);

 	$result = mysql_query($cmd);
 	if(!$result)
 	{
 		write_error(mysql_error());
 		return -1;
	}
 	else 
    { 
    	if ($log)
 			writelog("SUCCESS");
 		return $result;
	}
}

function syscall($command){
	if ($proc = popen("($command)2>&1","r")){
        	while (!feof($proc)) $result .= fgets($proc, 1000);
	        pclose($proc);
        	return $result;
        }
}

function GetCorrectMTime($filePath) 
{ 

    $time = filemtime($filePath); 

    $isDST = (date('I', $time) == 1); 
    $systemDST = (date('I') == 1); 

    $adjustment = 0; 

    if($isDST == false && $systemDST == true) 
        $adjustment = 3600; 
    
    else if($isDST == true && $systemDST == false) 
        $adjustment = -3600; 

    else 
        $adjustment = 0; 

    return ($time + $adjustment); 
} 	
?>