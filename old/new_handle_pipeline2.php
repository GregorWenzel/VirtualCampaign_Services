<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */
 	set_time_limit(300000);
 	
 	//init arry defs
 	$types = array("Production", "Single");
  	$motifchars = array("A","B","C","D","E","F","G","H","I","J","K","L","M");
  	
	//init logfile
	$lf0 = "X:/web/virtualcampaign/logs/logfile".date("Y-m-d", time()).".txt";
    $fh0 = fopen($lf0, 'a');

    //connect to the database
    $mysql = mysql_connect("localhost", "root", "rS06gQoe");
    mysql_select_db("virtualcampaign");

	$query = "UPDATE users SET online = 0";
	mysql_query($query);

	$FUSION_PATH = "C:/Program Files (x86)/eyeon/Render Slave 5.3.78/";
	$SCRIPT_PATH = "X:/web/virtualcampaign/scripts/fusion/";
	
	//Test Fusion Script
	$cmd = dsstr($FUSION_PATH."eyeonscript.exe")." ".$SCRIPT_PATH."new_getstatus.eyeonscript";
	//writelog($cmd);
	$renderstatus = syscall($cmd);
	$node_status = parse_nodestatus($renderstatus);
	$job_status = parse_jobstatus($renderstatus);
	
	//SERIALISE RENDERSTATUS
	$out = "<Nodes>";
	
	for ($i=0; $i<count($node_status);$i++)
	{
		$out .= "<Node>";
		$out .= "<Name>".$node_status[$i][0]."</Name>";
		$out .= "<IP>".$node_status[$i][1]."</IP>";
		$out .= "<Status>".$node_status[$i][2]."</Status>";
		$out .= "</Node>";		
	}
	$out .= "</Nodes>";

	//GET VC JOBS FROM DATABASE
	if ($_POST['mode'] == 0)
		$query = "SELECT users.username, jobQueue.ID, jobQueue.ownerID, jobQueue.compPath, jobQueue.motifIDs, jobQueue.dateSent, jobQueue.status, jobQueue.jobType, jobQueue.productionInfo, jobQueue.slaveIP, jobQueue.productIDs FROM jobQueue, users WHERE status >= 0 AND users.ID = jobQueue.ownerID AND dateSent >= ".strtotime("Today")." ORDER BY dateSent DESC";
	else
		$query = "SELECT users.username, jobQueue.ID, jobQueue.ownerID, jobQueue.compPath, jobQueue.motifIDs, jobQueue.dateSent, jobQueue.status, jobQueue.jobType, jobQueue.productionInfo, jobQueue.slaveIP, jobQueue.productIDs FROM jobQueue, users WHERE status >= 0 users.ID = jobQueue.ownerID ORDER BY dateSent DESC";
	
	$result = mysql_query($query);
	
	//SERIALISE JOBSTATUS
	$out .= "<Jobs>";
	
	while ($job = mysql_fetch_array($result))
	{
		$jobIndex = get_job_index($job);
		$out .= "<Job>";
		$out .= "<JobID>".$job['ID']."</JobID>";
		$out .= "<username>".$job['username']."</username>";
		$out .= "<OwnerID>".$job['ownerID']."</OwnerID>";
		$out .= "<Name>".$job['compPath']."</Name>";
		$out .= "<Motifs>".$job['motifIDs']."</Motifs>";
		$out .= "<Date>".$job['dateSent']."</Date>";
		if ($job['productIDs'] != "")
			$out .= "<Type>".$types[$job['jobType']]."</Type>";
		else
			$out .= "<Type>Preview</Type>";
		$out .= "<Formats>".$job['productionInfo']."</Formats>";
		$out .= "<SlaveIP>".$job['slaveIP']."</SlaveIP>";
		$out .= "<Products>".$job['productIDs']."</Products>";
		$out .= "<Status>".$job_status[$jobIndex][3]."</Status>";
		$out .= "<JobStatus>".$job['status']."</JobStatus>";
		if ($jobIndex >=0)
			$out .= "<Progress>".(100*($job_status[$jobIndex][1]/$job_status[$jobIndex][2]))."%</Progress>";
		else
			$out .= "<Progress>N/A</Progress>";
		$out .= "</Job>";
				
	}
	$out .= "</Jobs>";
	print($out);	

  //HANDLE RENDERING
  
  //0. RENDER JOBS THAT HAVE NOT STARTED...
  $query = "SELECT * FROM jobQueue WHERE jobType = 1 AND status = 0";
  $result = mysql_query($query);

  $RPATH = "x:/web/virtualcampaign/jobs/";
  $UPATH = "x:/web/virtualcampaign/users/";

  while ($job = mysql_fetch_array($result))
  { 
	$ownerID = $job['ownerID'];
	$jobid = $job['ID'];
	$userPath = $UPATH.$ownerID."/";
	$renderPath = $RPATH.$jobid."/";	  
	$motifs = split(',',$job['motifIDs']);
	$productID = $job['compPath'];
	
	//CREATE DIRECTORIES
	mkdir($userPath."/jobs/".$jobid);
	mkdir($renderPath);
	mkdir($renderPath."/tmp");
	mkdir($renderPath."/motifs");
	
	//COPY MOTIFS
	for ($i=0; $i<count($motifs); $i++)
	{  
		copy($userPath."/motifs/".$motifs[$i].".jpg", $renderPath."/motifs/motiv".$motifchars[$i].".jpg");
		fwrite($fh0, $USERPATH.$path."/motifs/".$motifs[$i].".jpg");
	}
	
	//GET COMPOSITION PATH
	$compSourcePath = "x:/web/virtualcampaign/products/".sprintf("%04d", $productID)."/".sprintf("%04d", $productID).".comp";
	
	//COPY COMPOSITION
	copy($compSourcePath, $renderPath."comp01.comp");
	
	//CREATE FINISH BATCH FILE
	$batchfile = $renderPath."finish.bat";
	$fh = fopen ($batchfile, 'w');
	$batchcommand = "c:\\ffmpeg\\ffmpeg.exe -y -f image2 -i ".$renderPath."f%%04d.jpg -threads 4 -vb 10000k ".$userPath."jobs/".$jobid."/clip.mpg\n"; 
	$batchcommand .= "c:\\ffmpeg\\ffmpeg.exe -y -i ".$userPath."jobs/".$jobid."/clip.mpg -vb 150k -qmin 3 -qmax 4 -s 360x202 ".$userPath."jobs/".$jobid."/preview_l.flv \n";
	$batchcommand .= "c:\\ffmpeg\\ffmpeg.exe -y -i ".$userPath."jobs/".$jobid."/clip.mpg -qmin 4 -qmax 6 -s 180x100 ".$userPath."jobs/".$jobid."/preview_s.flv\n";
	fwrite($fh, $batchcommand);
	fclose($fh);
	
	$cmd = "\"".$FUSION_PATH."eyeonscript.exe\" \"".$SCRIPT_PATH."new_renderjob.eyeonscript\" \"".$renderPath."comp01.comp\" ".$jobid." ".count(explode(",",$job['motifIDs']))." ".sprintf("%04d", $productID);
	writelog($cmd);
	writelog(syscall($cmd));
	
	$query = "UPDATE jobQueue SET status = 1 WHERE ID = ".$jobid;
	mysql_query($query);
  }    

  //1. JOBS THAT ARE CURRENTLY BEING RENDERED
  $query = "SELECT * FROM jobQueue WHERE jobType = 1 AND (status = 1 OR status = 3)"; 
  $result = mysql_query($query);
  $nFinished = 0;

  while ($job = mysql_fetch_object($result))
  {
	$jobIndex = get_job_index($job);
   	for ($i=0; $i<count($job_status[$jobIndex]); $i++)
   		writelog($job_status[$jobIndex][$i]);
    $jobID = $job->ID;
    $companyID = $job->companyID;
    $userID = $job->ownerID;
    $jobPath = "x:\\web\\virtualcampaign\\jobs\\".$jobID."\\";

	//LOG PREVIEW PICTURES
    if ($job->productID == -1)
    {
      $lastfile = $job_status[$jobIndex][1];

      $query = "UPDATE jobQueue SET productionInfo='".$lastfile."' WHERE ID=".$jobID;
      mysql_query($query);
    }

	//WHEN JOB IS FINISHED
	//1. COPY THE PREVIEW PICTURE
	//2. UPDATE DATABASE, STATUS = 2 (DONE)
	if(($job->status == "3") || (strstr($job_status[$jobIndex][3], "Done")))
    {
		if ($job->status == "1")
		{    	
			if (!is_file("x:\\web\\virtualcampaign\\users\\".$userID."\\jobs\\".$jobID."\\preview.jpg"))
			{
				$img = $job->previewImg;
				$cmd = "copy ".$jobPath."f".sprintf("%04d", $img).".jpg x:\\web\\virtualcampaign\\users\\".$userID."\\jobs\\".$jobID."\\preview.jpg";
				$dosmsg = syscall($cmd); writelog($cmd."\r\n> ".$dosmsg);
			}
			
			$cmd = "\"".$FUSION_PATH."eyeonscript.exe\" \"".$SCRIPT_PATH."new_removejob.eyeonscript\" ".sprintf("%04d",$job->ID);
			$dosmsg = syscall($cmd); writelog($cmd."\r\n> ".$dosmsg);
			
			$cmd = "x:/web/virtualcampaign/jobs/".$jobID."/finish.bat";
			$dosmsg = syscall($cmd); writelog($cmd."\r\n> ".$dosmsg);
		}
		else if ($job->status == "3")
		{
			mkdir("x:\\web\\virtualcampaign\\users\\".$userID."\\jobs\\".$jobID);
			$indicativeID = sprintf("%04d", $job->compPath);
			$cmd = "copy x:\\web\\virtualcampaign\\products\\".$indicativeID."\\".$indicativeID.".mpg x:\\web\\virtualcampaign\\users\\".$userID."\\jobs\\".$jobID."\\clip.mpg";
			writelog($cmd);
			writelog(syscall($cmd));
		}	
		
		$query = "UPDATE jobQueue SET status=2 WHERE ID=".$jobID;
		mysql_query($query);
        $nFinished++; 		
    }
  }
	
  //2. FINISH PRODUCTION JOBS
  $query = "SELECT * FROM jobQueue WHERE jobType = 0 AND status = 0";
  $result = mysql_query($query);

  while ($job = mysql_fetch_object($result))
  {
	$todo = 0;
	$jobID = $job->ID;
	$sourceID = $job->sourceID;
	$ownerID = $job->ownerID;
	$companyID = $job->companyID;
	$formats = split("\+", $job->productionInfo);
	$productlist = $job->productIDs;
	$targetPath = "x:\\web\\virtualcampaign\\users\\".$ownerID."\\jobs\\";
	$name = $job->compPath;
	$duration = $job->duration;
	$motiflist = $job->motifIDs;
	
	unlink($targetPath.$jobID."\\full_tmp.mpg");
		
	//GET MUSIC
	$music = $job->audioPath;

    //PREPARE CONCAT ACTION FOR CLIPS
	$cmd = "copy /B ";
	
    //FIND CLIPS THAT BELONG TO THE CURRENT PRODUCT
	$query2 = "SELECT * FROM jobQueue WHERE productID = ".$sourceID." ORDER BY jobCounter";
	$result2 = mysql_query($query2);

    $nclips = mysql_num_rows($result2);
    $clips = array();
    $counter = 0;
	
	$subclips = array();
	//CHECK IF THESE CLIPS ARE FINISHED, ADD THOSE TO CONCAT STRING
	while($clip = mysql_fetch_object($result2))
    {
   	   $status = $clip->status;
	   if ($status == 2)
	   {
	     array_push($clips, $clip->ID);
         $cmd .= $targetPath.$clip->ID."\\clip.mpg+";
         array_push($subclips, $targetPath.$clip->ID."\\clip.mpg");
       }
    }

	//IF ALL CLIPS ARE FINISHED, CREATE THE FINISHED FILM
	if (($nclips > 0) && (count($clips) == $nclips))
    {
		$query = "UPDATE jobQueue SET status=1 WHERE ID=".$jobID;
		mysql_query($query);
	
		//create the concatenated full mpg, consisting of a joining of all individual clips
		if (!is_dir($targetPath.$jobID))
        	mkdir($targetPath.$jobID);

		$cmd = substr($cmd,0,-1)." ".$targetPath.$jobID."\\full_tmp.mpg";
		
		array_push($subclips, $targetPath.$jobID."\\full_tmp.mpg");
		
		while (!is_file($targetPath.$jobID."\\full_tmp.mpg"))
		{
			$dosmsg = syscall($cmd); 
			writelog($cmd."\r\n> ".$dosmsg);
		}
			
		writelog("copy done.");
		
		if ($music != "NA")
		{
			//prepare music for this film
			if ((intval($job->indicative)>=0) || (intval($job->abdicative)>=0))
				$cmd = '"C:/Program Files (x86)/SoX/sox.exe" '.$music." ".$targetPath.$jobID."/main_music.wav fade q 2 ".ceil($job->duration/25);
			else
				$cmd = '"C:/Program Files (x86)/SoX/sox.exe" '.$music." ".$targetPath.$jobID."/music.wav fade q 2 ".ceil((1+$job->duration)/25);
			writelog($cmd);
			writelog(syscall($cmd));
		}

		$cmd = "copy /b ";
		//add indicative
		if (intval($job->indicative)>=0)
			$cmd .= "x:\\web\\virtualcampaign\\products\\".sprintf("%04d", $job->indicative)."\\".sprintf("%04d", $job->indicative).".mpg + ";
			
		$cmd .= $targetPath.$jobID."\\full_tmp.mpg";
			
		if ((intval($job->abdicative)>=0))
			$cmd .= " +  x:\\web\\virtualcampaign\\products\\".sprintf("%04d", $job->abdicative)."\\".sprintf("%04d", $job->abdicative).".mpg";

		$cmd .= " ".$targetPath.$jobID."\\full.mpg";
		
		$dosmsg = syscall($cmd); 
		writelog($cmd."\r\n> ".$dosmsg);
				
		$hasMusic = false;
		$cmd = '"C:\\Program Files (x86)\\SoX\\sox.exe" ';
		//prepare indicative music
		if (intval($job->indicative) >= 0)
		{
			if (is_file("x:\\web\\virtualcampaign\\products\\".sprintf("%04d", $job->indicative)."\\".sprintf("%04d", $job->indicative).".wav"))
			{
				$cmd .= "x:\\web\\virtualcampaign\\products\\".sprintf("%04d", $job->indicative)."\\".sprintf("%04d", $job->indicative).".wav ";
				$hasMusic = true;
			}
		}
		if ($music != "NA")
		{
			$cmd .= $targetPath.$jobID."\\main_music.wav ";
			array_push($subclips, $targetPath.$jobID."\\main_music.wav");
			
			$hasMusic = true;
		}
/*
		if (intval($job->abdicative) >= 0)
		{
			$cmd .= "x:\\products\\".sprintf("%04d", $job->abdicative)."\\".sprintf("%04d", $job->abdicative).".wav ";
			$hasMusic = true;
		}
*/		
		if ($hasMusic)
		{
			array_push($subclips, $targetPath.$jobID."\\music.wav");
			$cmd .= $targetPath.$jobID."\\music.wav";
			writelog($cmd);
			writelog(syscall($cmd));
		}
		
		//create the desired products
		$filesize = array();
        $dosname = str_replace(" ", '_', $name);
		for ($i=0; $i<count($formats); $i++)
		{
			$cmd = "c:\\ffmpeg\\ffmpeg -y -i ".$targetPath.$jobID."\\full.mpg ";
			$params = split(":",$formats[$i]);
			if($params[0] == "WMV")
			{
				switch($params[1])
				{
					case "1080P":
					//
					//	$cmd .= "-vcodec wmv2 -vb 7500k -s 1920x1080".$targetPath.$jobID."\\".$dosname."_1080P.wmv";
					//	$filesize[$i] = $targetPath.$jobID."\\".$dosname."_720P.wmv";
						break;				
					case "720P":
						$cmd .= "-vcodec wmv2 -vb 5000k -s 1280x720 ".$targetPath.$jobID."\\".$dosname."_720P.wmv";
						$filesize[$i] = $targetPath.$jobID."\\".$dosname."_720P.wmv";
						break;
					case "DVD":
						$cmd .= "-vcodec wmv2 -vb 2500k -s 720x404 ".$targetPath.$jobID."\\".$dosname."_DVD.wmv";
						$filesize[$i] = $targetPath.$jobID."\\".$dosname."_DVD.wmv";
						break;
					case "POWERPOINT":
						//$cmd .= "-vcodec wmv2 -vb 800k -s 360x202 ".$targetPath.$jobID."\\".$dosname."_POWERPOINT.wmv";
						$cmd .= "-vcodec wmv2 -vb 1000k -s 480x270 ".$targetPath.$jobID."\\".$dosname."_POWERPOINT.wmv";
						$filesize[$i] = $targetPath.$jobID."\\".$dosname."_POWERPOINT.wmv";
						break;
					case "WEB":
						$cmd .= "-vcodec wmv2 -vb 400k -s 320x180 ".$targetPath.$jobID."\\".$dosname."_WEB.wmv";
						$filesize[$i] = $targetPath.$jobID."\\".$dosname."_WEB.wmv";
					break;
					case "EMAIL":
						$cmd .= "-vcodec wmv2 -vb 200k -s 200x112 ".$targetPath.$jobID."\\".$dosname."_EMAIL.wmv";
						$filesize[$i] = $targetPath.$jobID."\\".$dosname."_EMAIL.wmv";
					break;
				}
	
		 		if ($music != "NA")
				  $cmd .= " -i ".$targetPath.$jobID."/music.wav -acodec wmav2 -async 1 -newaudio ";
	
				$dosmsg = syscall($cmd);  
				writelog($cmd."\r\n> ".$dosmsg);
				$filesize[$i] = filesize($filesize[$i]);
			}
		}
		$cmd = "c:\\ffmpeg\\ffmpeg -y -i ".$targetPath.$jobID."\\full.mpg -qmin 4 -qmax 6 -s 180x100 ".$targetPath.$jobID."\\preview_m.flv";
		$dosmsg = syscall($cmd);  
		writelog($cmd."\r\n> ".$dosmsg);

		//$cmd = "copy x:\\jobs\\".$jobID."\"
		
		array_push($subclips, $targetPath.$jobID."\\full.mpg");

		/*
		for ($m=0; $m<count($subclips); $m++)
		{
			writelog("DELETING ".$subclips[$m]);
			unlink($subclips[$m]);
		}
		*/
		
		$query2 = "SELECT * FROM jobQueue WHERE productID = ".$jobID." LIMIT 1";
		$result2 = mysql_query($query2);
		$clip = mysql_fetch_array($result2);
		
		$cmd = "copy ".$targetPath.$clip['ID']."\\preview.jpg ".$targetPath.$jobID."\\preview.jpg";
		syscall($cmd);		

//insert production data into film database
		$query = "INSERT INTO films (name, codecFormats, products, sizeKB, creationDate, duration, audio, watermarkID, preview, url, ownerID, jobID, motifIDs) ";
		$query .="VALUES ('".$name."', '".$job->productionInfo."', '".$productlist."', '".implode("+",$filesize)."', '".time()."', ".$duration.", 0, 0, 2, '".$companyID."/".$ownerID."', ".$ownerID.", ".$jobID.", '".$motiflist."')";
		mysql_query($query);	
		writelog($query);

		//set film as produced
		$query = "UPDATE jobQueue SET status=2 WHERE ID=".$jobID;
		mysql_query($query);
		
		unlink($targetPath.$jobID."\\full_tmp.mpg");
		unlink($targetPath.$jobID."\\full.mpg");
		unlink($targetPath.$jobID."\\music.wav");
		unlink($targetPath.$jobID."\\main_music.wav");
		

     }	
  }	
	
//FUNCTIONS	
function sstr($str)
{
	return "'".$str."'";
}

function dsstr($str)
{
	return "\"".$str."\"";
}

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
	
function parse_nodestatus($renderstatus)
{
	$node = explode("~", $renderstatus);
	$ns = array();
	
	for ($i=1; $i<count($node); $i++)
	{
		$nss = explode("#", $node[$i]);
		array_push($ns, $nss);
	}
	
	return($ns);
}

function parse_jobstatus($renderstatus)
{
	$render = explode("*", $renderstatus);
	$rs = array();
	
	for ($i=1; $i<count($render); $i++)
	{
		$rss = explode("#", $render[$i]);
		array_push($rs, $rss);
	}
	
	return($rs);
}

function get_job_index($job)
{
	global $job_status;
	for ($i=0; $i<count($job_status); $i++)
	{
		if (substr($job_status[$i][0],0, 4) == sprintf("%04d", $job->ID))
			return $i;
	}
	
	return -1;
}
?>