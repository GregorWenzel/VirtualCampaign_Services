<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

  //connect to the database
	include "local_config.php";
	
	$query = "INSERT INTO groups (group_name) VALUES ('".$_POST['name']."')";
	db_query($query);
?>