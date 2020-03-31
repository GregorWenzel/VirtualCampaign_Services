<?php

include "local_config.php";

$productList = explode(",",$_POST['IDs']);

$res = array();

for ($i=0; $i<count($productList); $i++)
{
	$query = "SELECT COUNT(ID) FROM jobQueue WHERE ID = ".$productList[$i]." AND status = 6";
	
	$result = db_query($query);
	$num = mysql_fetch_array($result);

	array_push($res, $num[0]);
}

print ("<Result>".implode(",",$res)."</Result>");

mysql_close($mysql);
?>