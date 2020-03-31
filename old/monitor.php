<?php

/**
 * @author Gregor Wenzel
 * @copyright 2009
 * purpose: handles the current job queue (see code for further explanations)
 * POST parameters: 
 * output: currently active jobs
 */
  	
	//init logfile
	$lf0 = "X:/web/virtualcampaign/logs/logfile".date("Y-m-d", time()).".txt";
    $fh0 = fopen($lf0, 'a');

    //connect to the database
    $mysql = mysql_connect("localhost", "root", "rS06gQoe");
    mysql_select_db("virtualcampaign");

	$FUSION_PATH = "C:/Program Files (x86)/eyeon/Render Slave 5.3.78/";
	$SCRIPT_PATH = "X:/web/virtualcampaign/scripts/fusion/";
	
	//Get Fusion Queue status
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
