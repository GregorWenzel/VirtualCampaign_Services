<?php
   //connect to the database
	include "config.php";

	$query = "UPDATE jobqueue SET active = 0 WHERE ID = ".$_POST['ID'];
	mysql_query($query);
	mysql_close($mysql);
?>
