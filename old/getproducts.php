<?php
   //connect to the database
   $sort = $_POST['sortType'];
   
   include "local_config.php";

   $query = "SELECT * FROM products ORDER BY date ASC";
   $result = mysql_query($query);

   $output = "<Products>";
   
   while($product = mysql_fetch_object($result))
   {
      $output .= "<Product><ID>".$product->ID."</ID><Desc>".$product->description."</Desc><Type>".$product->type."</Type><All>".$product->all_allowed."</All><Location>";
      $query = "SELECT nameGER FROM locations WHERE ID = ".$product->locationID;
      $locresult = mysql_query($query);
      $output .= mysql_fetch_object($locresult)->nameGER."</Location>";
      mysql_free_result($locresult);

      $output .= "<Date>".$product->date."</Date><nMotifs>".$product->nMotifs."</nMotifs><Motifs>";

      $query = "SELECT * FROM link_products_motifs WHERE productID = ".$product->ID;
      $motresult = mysql_query($query);
      while ($motif = mysql_fetch_object($motresult))
      {
         $output .= "<Motif>".$motif->motifTypeID."</Motif>";
      }
      $output .= "</Motifs>";
      mysql_free_result($motresult);
      $output .= "<Owners><Owner>0</Owner>";
      $query = "SELECT * FROM link_products_owners WHERE productID = ".$product->ID;
      $ownerresult = mysql_query($query);
      while ($owner = mysql_fetch_object($ownerresult))
      {
	 $output .= "<Owner>".$owner->ownerID."</Owner>";
      }
      $output .= "</Owners>";
      $output .= "<nFrames>".$product->nFrames."</nFrames>";
      $output .= "<renderTime>".$product->renderTime."</renderTime><previewFrame>".$product->previewFrame."</previewFrame><previewPath>".$product->previewPath."</previewPath>";
      $output .= "<compPath>".$product->compPath."</compPath><Audio>".$product->audio."</Audio><Category>";

      $query = "SELECT name FROM categories WHERE ID = ".$product->categoryID;
      $catresult = mysql_query($query);
      $output .= mysql_fetch_object($catresult)->name."</Category>";
      mysql_free_result($catresult);
      $output .= "</Product>";
   }

   $output .= "</Products>";
   mysql_free_result($result);
   
   print($output);
mysql_close($mysql);
?>
