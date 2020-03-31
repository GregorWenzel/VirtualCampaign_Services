<?php

include "local_config.php";

$localIDs = explode(",", $_POST['IDs']);
$remoteIDs = array();

$count = count($localIDs);
$found = 0;

for ($i=0; $i<$count; $i++)
{
	$query = "SELECT renderID FROM jobqueue WHERE ID = ".$localIDs[$i];
	$result = db_query($query);
	
	$remoteID = mysql_fetch_array($result);
	
	if (intval($remoteID[0]) == 0)
		break;	
	
	array_push($remoteIDs, $remoteID[0]);
}

if ($found == 1)
	$out = "<Found>1</Found><RemoteIDs>".implode(",",$remoteIDs)."</RemoteIDs>";
else
	$out = "<Found>0</Found>";
	
print($out);

mysql_close($mysql);
?>