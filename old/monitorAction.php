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
	
	if ($_POST['actionType'] == 'restart')
	{
		$query = "UPDATE jobqueue SET status = 0 WHERE ID = ".$_POST['JobID'];
		$result = db_query($query);
	}
	else if ($_POST['actionType'] == 'check')
	{
		//Test for faulty renderings, etc.
	}
	
	mysql_close($mysql);
?>
