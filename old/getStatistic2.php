<form>

	<input name="beginmonth" value="01"></input>
	<input name="beginyear" value="2010" ></input>
	<input name="endmonth" value="12" ></input>
	<input name="endyear" value="2010"></input>
	<input type="submit">
</form>

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

$begin=  gmmktime(0  , 0,0,  $_GET["beginmonth"]  , 0 ,  $_GET["beginyear"]   );
$end=gmmktime(23  , 59,59,  $_GET["endmonth"]  , 0 ,  $_GET["endyear"]  ) ;
trace( $_GET["beginyear"] ."Begin".date('D, d M Y H:i:s',$begin));
trace( $_GET["beginyear"] ."End".date('D, d M Y H:i:s',$end));
 
$mysqladd=" AND creationDate>$begin and creationDate<$end";

include "local_config.php";

$query = "SELECT COUNT(films.ID) AS cnt FROM films WHERE films.ID=films.ID  ".$mysqladd." ";
$result = mysql_fetch_object(mysql_query($query));
trace("Gesamt anzahl Filme:".$result->cnt);



$query = "SELECT users.*,COUNT(films.ID) AS cnt FROM users, films WHERE films.ownerID = users.ID ".$mysqladd." GROUP BY ownerID";
$result = mysql_query($query);


while($user = mysql_fetch_object($result))
{
	echo  "<hr>";
	trace ("<b>".$user->username.":".$user->name." ".$user->lastname."</b>") ;
	trace ("Anzahl Filme:".$user->cnt) ;


	$query = "SELECT * from films WHERE films.ownerID = ".$user->ID. $mysqladd ;
	$films= mysql_query($query);

	while($film= mysql_fetch_object($films))
	{
		echo '<div style="background-color:#ff0000;width:500px" >';
		trace ("Film: ".$film->name );
		trace(" Duration:".$film->duration);
		trace("Produkte:".$film->products);
		trace("CreationDate:".date('D, d M Y H:i:s',$film->creationDate));
		trace("Bilder:".$film->motifIDs);

		echo '<div style="float:left;background-color:#aaaaaa">';
		
		$productsIDS=explode(",",$film->products)  ;
		foreach($productsIDS as $productID)
		{
			$query = "SELECT * from  products WHERE products.ID= ".$productID ;
			$product= mysql_query($query);
			if($product)
			{
				$product=mysql_fetch_object($product);
				trace($product->description);
			}








		}
		echo '</div>';

		echo '<div style="float:right;background-color:#888888">';
	 
		$motifIds=explode(',',$film->motifIDs)  ;
	 
		foreach($motifIds as $productID)
		{
			$query = "SELECT * from  motifs WHERE motifs.ID= ".$productID.$mysqladd ;
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
