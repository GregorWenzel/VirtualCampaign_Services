<?php
  header('Cache-Control: no-store, no-cache, must-revalidate');
 
  $job = $_POST['JOBNR'];
  $id= $_POST['ID'];
  $companyID = $_POST['companyID'];
  
  if (is_file("x:\\web\\virtualcampaign\\users\\".$id."\\jobs\\".$job."\\preview_s.flv"))
  {
    print("<File>-1</File>");
  }
  else
  {  
  	$images = glob("x:/web/virtualcampaign/jobs/$job/*.jpg");
	if (count($images) > 2)
	{
		print("<File>".$images[count($images)-2]."</File>");
		print("<Frame>".substr(substr($images[count($images)-2], -8), 0, 4)."</Frame>");
	}
  }
  

mysql_close($mysql);
?>