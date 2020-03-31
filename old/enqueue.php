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
  $previewImage = $_POST['ProductIDs'];

	$lf0 = "X:/web/virtualcampaign/logs/logfile".date("Y-m-d", time()).".txt";
    $fh = fopen($lf0, 'a');

  $mysql = mysql_connect("localhost", "gregor", "r0binhood");
  mysql_select_db("virtualcampaign");

    $buffer = array();
    for ($j=0; $j<$nmotifs; $j++)
     	$buffer[$j] = $motifs[$j];

    $query = "SELECT COUNT(ID) AS count FROM jobQueue WHERE ownerID = ".$USERID." AND productID = ".$parent." AND compPath = ".$product." AND jobCounter = ".$index;
    
	fwrite($fh, $query);
	
	$result = mysql_query($query);
    $numEntries = mysql_fetch_array($result);
    
    if (intval($numEntries['count']) == 0)
	{
	    $query = "INSERT INTO jobQueue (ownerID, productID, productIDs, companyID, compPath, dateSent, motifIDs, jobType, status, productionInfo, jobCounter) ";
	
		if ($_POST['ProductType'] == 3)
	    	$query .="VALUES (".$USERID.", ".$parent.", '".$previewImage."', ".$COMPANYID.", '".$product."', '".$timestamp."', '".implode(",",$buffer)."', 1, 3, '0', ".$index.")";
		else
			$query .="VALUES (".$USERID.", ".$parent.", '".$previewImage."', ".$COMPANYID.", '".$product."', '".$timestamp."', '".implode(",",$buffer)."', 1, 0, '0', ".$index.")";
	    

		fwrite($fh0, $query);
		
		mysql_query($query);
	    
	    $query = "SELECT MAX(ID) AS MAX FROM jobQueue WHERE ownerID=".$USERID;
	    $result = mysql_query($query);
	    $jobID = mysql_fetch_object($result);
	    $ID = $jobID->MAX;
	}
mysql_close($mysql); 
?>
	
