<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

 $mysql = mysql_connect("localhost", "gregor", "r0binhood");
  mysql_select_db("virtualcampaign");

$query = "SELECT jobqueue.ID AS jID, jobqueue.compPath AS jName, jobqueue.motifIDs AS jMotifs, jobqueue.ownerID, jobqueue.dateSent AS jDate, jobqueue.productIDs AS jProducts, films.ID AS fID, users.name AS UserName, users.lastname AS UserLName FROM jobqueue, users, films WHERE users.ID = jobqueue.ownerID AND films.jobID = jobqueue.ID AND jobType = 0 ORDER BY jobqueue.dateSent";

$result = mysql_query($query);

$fp = fopen("X:/web/virtualcampaign/joblog.csv", "w+");
	
fwrite($fp, "Film ID; Film Name; User ID; Vorname; Nachname; Datum; JobID; Produkte; Motive\r\n");

while ($film = mysql_fetch_array($result))
{
	$products = split(",", $film['jProducts']);
	$productNames = "";
	for ($i=0; $i<count($products); $i++)
	{
		$query = "SELECT description FROM products WHERE ID = ".$products[$i];

		$result2 = mysql_query($query);
		$productDesc = mysql_fetch_array($result2);
		$productNames .= $productDesc[0].",";
	}
	$date = date("d.m.y - H:i", $film['jDate']);
	
	fwrite($fp, $film['jID'].";".$film['jName'].";".$film['ownerID'].";".$film['UserName'].";".$film['UserLName'].";".$date.";".$film['jID'].";".$productNames.";".str_ireplace(".",",",$film['jMotifs'])."\r\n");	
}
fclose($fp);
	
echo "<Done>1</Done>";
	
exit;

?>