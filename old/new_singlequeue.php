<?php 

  $timestamp = time();
  $date = date("Y-m-d - G:i:s",$timestamp);
  $time = date("", $timestamp);
  
  $product = $_POST['product'];
  $motifs = split(',',$_POST['motifs']);
  $nmotifs = count($motifs);
  $COMPANYID = $_POST['companyID'];
  $USERID = $_POST['user'];
  $index = $_POST['index'];
  $parent = $_POST['ProductID'];

  $mysql = mysql_connect("localhost", "root", "rS06gQoe");
  mysql_select_db("virtualcampaign");

  $today = strtotime("Today");
  $query = "SELECT COUNT(ID) FROM jobQueue WHERE status = 0 AND dateSent >= ".$today;
  $result = mysql_query($query);
  $numQueue = mysql_fetch_array($result);
  if ($numQueue[0] > 0)
  	print("<Job>-1</Job>");
  else
  {  
	  $buffer = array();
	  for ($j=0; $j<$nmotifs; $j++)
	      $buffer[$j] = $motifs[$j];
	  
	  $query = "SELECT COUNT(ID) AS count FROM jobQueue WHERE ownerID = ".$USERID." AND productID = ".$parent." AND compPath = ".$product." AND jobCounter = ".$index;
	  $result = mysql_query($query);
	  $numEntries = mysql_fetch_array($result);
	  
	  if ($numEntries['count'] == 0)
	  {
		  $query = "INSERT INTO jobQueue (ownerID, productID, companyID, compPath, dateSent, motifIDs, jobType, status, productionInfo, jobCounter) ";
		  $query .="VALUES (".$USERID.", ".$parent.", ".$COMPANYID.", '".$product."', '".$timestamp."', '".implode(",",$buffer)."', 1, 0, '0', ".$index.")";
		  mysql_query($query);
		    
		  $query = "SELECT LAST_INSERT_ID()";
		  $result = mysql_query($query);
		  $jobID = mysql_fetch_array($result);
		  $ID = $jobID[0];
		  print("<Job>".$ID."</Job>");
	  }
	  else
	  	print("<Job>-1</Job>");
  }

mysql_close($mysql);
?>
	
