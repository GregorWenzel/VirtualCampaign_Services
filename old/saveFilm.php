<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

	$mysql = mysql_connect("localhost", "root", "rS06gQoe");
   	mysql_select_db("virtualcampaign");
   	
   	$query = "INSERT INTO films (name, duration, products, motifIDs, ownerID, creationDate) VALUES ('".$_POST['Name']."', -1, '".$_POST['productIDs']."', '".$_POST['motifIDs']."', ".$_POST['userID'].", ".time().")";
   	mysql_query($query);

	print("<Saved>1</Saved>");
?>