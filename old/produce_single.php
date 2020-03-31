<?php

/**
 * @author Gregor Wenzel
 * @copyright 2009
 * purpose: process a movie with a number of clips
 * POST parameters (all are comma-seperated values in a string, each movie is seperated by a '|'):
 *  jobName: movie name
 *  productTypes: list of product types
 *  codecFormats: list of codec formats
 *  in, ab: clip IDs for intro/extro (-1 = none)
 *  durations: film duration in frames
 *  durationTime: duration in seconds
 *  preview: frame used as the preview image
 *  audiopath: whether or not the film has audio
 *  status = 0 (not started)
 * output: list of inserted job IDs
 */


include "local_config.php";

$COMPANYID = $_POST['companyID'];
$USERID = $_POST['user'];
$formats = $_POST['codecFormats'];

$out = "<Film>";

$JOBNAME = $_POST['jobName'];
$motifIDs = $_POST['motifIDs'];
$productIDs = $_POST['products'];
$productTypes = $_POST['productTypes'];
$audio = $_POST['audioParam'];
$durations = $_POST['durations'];
$ab = $_POST['abdicatives'];
$in = $_POST['indicatives'];
$status = $_POST['status'];
$preview = $_POST['previewFrames'];
$durationTime = $_POST['durationTime'];
$motifFrames = $_POST['motifFrames'];

if ($audio == "none")
  $audiopath = "NA";
else
$audiopath = $audio;

$timestamp = time();
//$dur = explode(",",$durations);
//$duration = 0;

//for ($i=0; $i<count($dur); $i++)
	//$duration += intval($dur[$i]);
	
//INSERT PRODUCTION JOB 
$query = "INSERT INTO jobqueue (ownerID, companyID, motifIDs, compPath, dateSent, jobType, audioPath, productionInfo, status, jobCounter, duration, productIDs, indicative, abdicative, motifFrames) ";
$query .="VALUES (".$USERID.", ".$COMPANYID.", '".$motifIDs."', '".$JOBNAME."', '".$timestamp."', 0, '".$audiopath."', '".$formats."', 0, 0, ".$durationTime.", '".$productIDs."', ".$in.", ".$ab.", '".$motifFrames."')";

db_query($query);

$jobID = mysql_insert_id();

$out .= "<ID>".$jobID."</ID>";

$query = "UPDATE users SET nProductions = nProductions+1 WHERE ID = ".$USERID;
db_query($query);

$clipProducts = explode(",", $productIDs);
$clipMotifs = explode(".", $motifIDs);
$clipProductTypes = explode(",", $productTypes);
$preview = explode(",", $preview);
$clipMotifFrames = explode(".", $motifFrames);
$nClips = count($clipProducts);

writelog("MotifFrames = ".$motifFrames.", ClipMotifFrames = ".$clipMotifFrames);

//INSERT CLIPS FOR PRODUCTION JOB	  
for ($clip=0; $clip < $nClips; $clip++)
{      	
	$query = "INSERT INTO jobqueue (ownerID, productID, productIDs, companyID, compPath, dateSent, motifIDs, jobType, status, productionInfo, jobCounter, motifFrames) ";
	
	$query .="VALUES (".$USERID.", ".$jobID.", '".$preview[$clip]."', ".$COMPANYID.", '".intval($clipProducts[$clip])."', '".$timestamp."', '".$clipMotifs[$clip]."', 1, 0, '0', ".$clip.", '".$clipMotifFrames[$clip]."')";
	
	db_query($query);     	  
}
  

$out .= "</Film><Array>".print_r($clipProducts)."</Array>";
print($out);

mysql_close($mysql);

?>
