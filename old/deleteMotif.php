<?php 

	include "local_config.php";
	
   $ID = $_POST['ID'];	
   $sort = $_POST['sortType'];

   $query = "DELETE FROM motifs WHERE ID = ".$_POST['ID'];
   mysql_query($query);

   mysql_close($mysql);
?>
