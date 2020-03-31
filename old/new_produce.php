<?php

  $USERPATH = "x:\\web\\virtualcampaign\\users\\";
   $JOBNAME = $_POST['jobName'];
   $COMPANYID = $_POST['companyID'];
   $USERID = $_POST['user'];
   $formats = $_POST['codecFormats'];
   $motifIDs = $_POST['motifIDs'];
   $productIDs = $_POST['ProductIDs'];
   $audio = $_POST['AudioPath'];
  $duration = $_POST['duration'];
  $ab = $_POST['abdicative'];
  $in = $_POST['indicative'];
  $status = $_POST['status'];
  $preview = $_POST['previewFrame'];
  
	//init logfile
	$lf0 = "D:/virtualcampaign/www/scripts/logfile".date("Y-m-d", time()).".txt";
    $fh0 = fopen($lf0, 'a');
  
  $mysql = mysql_connect("localhost", "gregor", "r0binhood");
  mysql_select_db("virtualcampaign");

  if ($audio == "none")
      $audiopath = "NA";
  else
  	$audiopath = $audio;

  $timestamp = time();

//  $query = "INSERT INTO jobQueue (ownerID, companyID, motifIDs, compPath, dateSent, jobType, audioPath, productionInfo, status, jobCounter, duration, productIDs, indicative, abdicative, previewImg) ";
 
 $query = "INSERT INTO jobQueue (ownerID, companyID, motifIDs, compPath, dateSent, jobType, audioPath, productionInfo, status, jobCounter, duration, productIDs, indicative, abdicative) ";

//   $query .="VALUES (".$USERID.", ".$COMPANYID.", '".$motifIDs."', '".$JOBNAME."', '".$timestamp."', 0, '".$audiopath."', '".$formats."', 0, 0, ".$duration.", '".$productIDs."', $in, $ab, $preview)";

  $query .="VALUES (".$USERID.", ".$COMPANYID.", '".$motifIDs."', '".$JOBNAME."', '".$timestamp."', 0, '".$audiopath."', '".$formats."', 0, 0, ".$duration.", '".$productIDs."', $in, $ab)";


  mysql_query($query);
  writelog($query);
  
  $query = "SELECT LAST_INSERT_ID()";
  $result = mysql_query($query);
  $jobID = mysql_fetch_array($result);
 
  print("<ID>".$jobID[0]."</ID>");

  mkdir($USERPATH."\\".$COMPANYID."\\".$USERID."\\jobs\\".$ID);

  mysql_close($mysql);
  fclose($fh0);
    
  function writelog($txt)
{
	global $fh0;
	fwrite($fh0, date("Y-m-d H:i:s",time())."> ".$txt."\r\n");
}
?>
