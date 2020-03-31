<?php 
   
   	//init logfile
	$lf0 = "X:/web/virtualcampaign/logs/logfile".date("Y-m-d", time()).".txt";
    $fh = fopen($lf0, 'a');
   
   //connect to the database
   $mysql = mysql_connect("localhost", "gregor", "r0binhood");
   mysql_select_db("virtualcampaign");

   $query = "UPDATE motifs SET type=".$_POST['motifType'].", name='".$_POST['motifName']."', comment='".$_POST['motifComment']."' WHERE ID=".$_POST['motifID'];
   db_query($query);

mysql_close($mysql);

function writelog($txt) {
 global $fh;
 fwrite($fh, date('Y-m-d H:i:s', time()).'> '.$txt."\r\n");
}

function write_error($txt) {
 global $fh;
 fwrite($fh, date('Y-m-d H:i:s', time()).' ERROR > '.$txt."\r\n");
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

?>
