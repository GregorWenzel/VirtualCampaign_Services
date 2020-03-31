<?php
   
   include "local_config.php";
	
   $user = $_POST['user'];

   $query = "SELECT * FROM films WHERE ownerID=".$user." ORDER BY creationDate DESC";
   $result = mysql_query($query);

   $output = "<Films><Film>0</Film>";
   
   while($film = mysql_fetch_object($result))
   {
      $output .= "<Film><ID>".$film->ID."</ID><jobID>".$film->jobID."</jobID><Owner>".$user."</Owner><Name>".$film->name."</Name>";
      $output .= "<Date>".$film->creationDate."</Date><Duration>".$film->duration."</Duration>";
      $output .= "<Status>".$film->status."</Status>";
      $output .= "<Path>".$film->url."</Path><codecFormats>".$film->codecFormats."</codecFormats>";
	$output .= "<Motifs>".$film->motifIDs."</Motifs>";
     
	 
	  if ($film-> duration == -1)
      {
	
        $output .= "<Categories>".$film->products."</Categories>";
      }
      else
      {
        $products = split(",",$film->products);
        $cats = array();
        for ($i=0; $i<count($products); $i++)
        {
		 $query = "SELECT * FROM categories LEFT JOIN products ON categories.ID=products.categoryID WHERE products.ID = ".$products[$i];
	  	 $res = mysql_query($query);
		 $cat = mysql_fetch_object($res);
		 $cat = $cat->name;
		 $cats[$i] = $cat;
      	}
      	$output .= "<Categories>".implode(", ",$cats)."</Categories>";
      }
      $output .= "<size>".$film->sizeKB."</size><Audio>".(int)($film->audio)."</Audio><Preview>".(int)($film->preview)."</Preview>";
      $output .= "</Film>";
   }

   $output .= "</Films>";
   mysql_free_result($result);
   
   print($output);

mysql_close($mysql);
?>
