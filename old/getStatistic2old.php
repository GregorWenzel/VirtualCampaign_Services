<?php

/**
 * @author c.Kleinhuis
 * purpose: counts films per user, outputs products and image names per film
 * WARNING; THIS IS A HACK, TO PROVIDE INFORMATION!
 */

//connect to the database

function trace($xc){

	echo $xc;
	echo "<br>";

}

$mysql = mysql_connect("localhost", "gregor", "r0binhood");
mysql_select_db("virtualcampaign");
echo mysql_error();
$query = "SELECT users.*,COUNT(films.ID) AS cnt FROM users, films WHERE films.ownerID = users.ID GROUP BY ownerID";
$result = mysql_query($query);


while($user = mysql_fetch_object($result))
{
	echo  "<hr>";
	trace ("<b>".$user->username.":".$user->name." ".$user->lastname."</b>") ;
	trace ("Anzahl Filme:".$user->cnt) ;


	$query = "SELECT * from films WHERE films.ownerID = ".$user->ID;
	$films= mysql_query($query);

	while($film= mysql_fetch_object($films))
	{
		echo '<div style="background-color:#ff0000;width:500px" >';
		trace ("Film: ".$film->name ." Duration:".$film->duration);
		trace("Produkte:".$film->products);
		trace("Bilder:".$film->motifIDs);

		echo '<div style="float:left;background-color:#aaaaaa">';
		
		$productsIDS=explode(",",$film->products)  ;
		foreach($productsIDS as $productID)
		{
			$query = "SELECT * from  products WHERE products.ID= ".$productID;
			$product= mysql_query($query);
			if($product)
			{
				$product=mysql_fetch_object($product);
				trace($product->description);
			}








		}
		echo '</div>';

		echo '<div style="float:right;background-color:#888888">';
		$motifIds=explode('.',$film->motifIDs)  ;
		foreach($motifIds as $productID)
		{
			$query = "SELECT * from  motifs WHERE motifs.ID= ".$productID;
			$product= mysql_query($query);
			if($product)
			{
				$product=mysql_fetch_object($product);
				trace($product->name);
			}








		}
			
			
		echo '</div>';
		echo '<div style="clear:both;"></div>';
		echo '</div>';








	}



}



?>
