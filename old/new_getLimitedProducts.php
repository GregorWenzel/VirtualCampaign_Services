<?php
   //connect to the database
 
   include "local_config.php";

   $sort = $_POST['sortType'];

   $query = "SELECT products.*, locations.nameGER, categories.name AS CatName FROM products, locations, categories WHERE locations.ID = products.locationID AND categories.ID = products.categoryID ORDER BY ID, date";
  
   $result = db_query($query);

   $output = "<Products>";
   
   while($product = mysql_fetch_object($result))
   {
      $output .= "<Product><ID>".$product->ID."</ID><All>".$product->all_allowed."</All><Desc>".$product->description."</Desc><Type>".$product->type."</Type><ProductType>".$product->productType."</ProductType><Location>";
      $output .= $product->nameGER."</Location>";
  
      $output .= "<Date>".$product->date."</Date><nMotifs>".$product->nMotifs."</nMotifs><Motifs>";
      $output .= "<PreviewFrame>".$product->previewFrame."</PreviewFrame>";
      $query = "SELECT * FROM link_products_motifs WHERE productID = ".$product->ID;
      $motresult = db_query($query);
      while ($motif = mysql_fetch_object($motresult))
      {
         $output .= "<Motif>".$motif->motifTypeID."</Motif>";
      }
      $output .= "</Motifs>";
      mysql_free_result($motresult);
      $output .= "<Owners>";
      if ($product->all_allowed == 1)
		$output .= "<Owner>-1</Owner>";
      else
      {
      	$query = "SELECT * FROM product_owners WHERE product_id = ".$product->ID;
      	$ownerresult = db_query($query);
      	while ($owner = mysql_fetch_object($ownerresult))
	 		$output .= "<Owner>".$owner->owner_id."</Owner>";
      }
      	
      $output .= "</Owners>";
      $output .= "<nFrames>".$product->nFrames."</nFrames>";
      $output .= "<renderTime>".$product->renderTime."</renderTime><previewFrame>".$product->previewFrame."</previewFrame><previewPath>".$product->previewPath."</previewPath>";
      $output .= "<compPath>".$product->compPath."</compPath><Audio>".$product->audio."</Audio><Category>";

      $output .= $product->CatName."</Category>";
      $output .= "</Product>";
   }

   $output .= "</Products>";
   mysql_free_result($result);
   
   print($output);
?>
