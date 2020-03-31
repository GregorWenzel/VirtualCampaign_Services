<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

    //connect to the database
    $mysql = mysql_connect("localhost", "root", "rS06gQoe");
    mysql_select_db("virtualcampaign");
    
    $query = "SELECT * FROM films";
    $result = mysql_query($query);
    
    while ($film = mysql_fetch_array($result))
    {
    	$query = "SELECT compPath FROM jobqueue WHERE productID = ".$film['jobID']." ORDER BY jobCounter";
    	$result2 = mysql_query($query);
    	
    	$products = array();
    	while ($product = mysql_fetch_array($result2))
    	{
    		if (is_numeric($product['compPath']))
    			array_push($products, $product['compPath']);
		}	
		
		$query = "SELECT motifIDs FROM jobqueue WHERE ID = ".$film['jobID'];
		$result2 = mysql_query($query);
		$motifs = mysql_fetch_array($result2);
		
   		$query = "UPDATE films SET products = '".implode(",", $products)."', motifIDs = '".$motifs['motifIDs']."' WHERE ID = ".$film['ID'];
   		mysql_query($query);
    }

	mysql_close($mysql);
?>