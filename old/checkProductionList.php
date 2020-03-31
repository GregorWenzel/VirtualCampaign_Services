<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

    $mysql = mysql_connect("localhost", "root", "rS06gQoe");
    mysql_select_db("virtualcampaign");

$productList = explode(",",$_POST['IDs']);

$res = array();

for ($i=0; $i<count($productList); $i++)
{
	$query = "SELECT COUNT(ID) FROM jobQueue WHERE sourceID = ".$productList[$i]." OR ID = ".$productList[$i]." AND status = 2";
	
	$result = mysql_query($query);
	$num = mysql_fetch_array($result);

	array_push($res, $num[0]);
}

print ("<Result>".implode(",",$res)."</Result>");

mysql_close($mysql);

function writelog($txt)
{
	global $fh0;
	fwrite($fh0, date("Y-m-d H:i:s",time())."> ".$txt."\r\n");
}
?>