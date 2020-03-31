<?php

/**
 * @author Gregor Wenzel
 * @copyright 2009
 * purpose: process many clips in many movies at once and add them to the jobqueue
 * POST parameters (all are comma-seperated values in a string, each movie is seperated by a '|'):
 *  jobName: movie names
 *  products: list of products per film
 *  productTypes: list of product types per film
 *  codecFormats: list of codec formats (all film batches have the same format)
 *  indicatives, abdicatives: list of clip IDs for intro/extro (-1 = none)
 *  durations: list of film durations in frames
 *  previewFrames: list of the frames used as the preview image
 *  audioParam: whether or not the film has audio
 *  status = 0 (not started)
 * output: list of inserted job IDs
 */


  include "local_config.php";
  
  $COMPANYID = $_POST['companyID'];
  $USERID = $_POST['user'];
  $formats = $_POST['codecFormats'];
  
  $listFilmNames = explode("|", $_POST['jobName'],-1);
  $listProducts = explode("|", $_POST['products'],-1);
  $listProductTypes = explode("|", $_POST['productTypes'],-1);
  $listMotifs = explode("|", $_POST['motifIDs'],-1);
  $formats = $_POST['codecFormats'];
  $listIndicatives = explode('|', $_POST['indicatives'],-1);
  $listAbdicatives = explode('|', $_POST['abdicatives'],-1);
  $listDurations = explode('|', $_POST['durations'],-1);
  $listPFrames = explode('|', $_POST['previewFrames'],-1);
  $listAudio = explode('|', $_POST['audioParam'],-1);
  $listFrames = explode('|', $_POST['motifFrames'], -1);

  $nFilms = count($listProducts);
  
  writelog("Found $nFilms films.");
  
  $jobIDs = array();
  $out = "<Film>";
  
  for ($film=0; $film < $nFilms; $film++)
  {
	  $JOBNAME = $listFilmNames[$film];
	  $motifIDs = $listMotifs[$film];
	  $productIDs = $listProducts[$film];
      $productTypes = $listProductTypes[$film];
	  $audio = $listAudio[$film];
	  $durations = $listDurations[$film];
	  $ab = $listAbdicatives[$film];
	  $in = $listIndicatives[$film];
	  $status = $_POST['status'];
	  $preview = $listPFrames[$film];
      $motifFrames = $listFrames[$film];

	  if ($audio == "none")
	      $audiopath = "NA";
	  else
	  	$audiopath = $audio;
	
	  $timestamp = time();
  
  	  $dur = explode(",",$durations);
	  $duration = 0;

       for ($i=0; $i<count($dur); $i++)
		 $duration += intval($dur[$i]);
     
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
	  
	  $nClips = count($clipProducts);

      //INSERT CLIPS FOR PRODUCTION JOB	  
      for ($clip=0; $clip < $nClips; $clip++)
      {      	
	     $query = "INSERT INTO jobqueue (ownerID, productID, productIDs, companyID, compPath, dateSent, motifIDs, jobType, status, productionInfo, jobCounter) ";
	
  		 $query .="VALUES (".$USERID.", ".$jobID.", '".$preview[$clip]."', ".$COMPANYID.", '".$clipProducts[$clip]."', '".$timestamp."', '".$clipMotifs[$clip]."', 1, 0, '0', ".$clip.")";
	    
		db_query($query);     	  
      }
}	  
 
 $out .= "</Film>";
 print($out);
 
  mysql_close($mysql);
    
?>
