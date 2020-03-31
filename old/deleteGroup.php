<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

  //connect to the database
	include "local_config.php";

  $query = "DELETE FROM groups WHERE ID = ".$_POST['ID'];
  db_query($query);

?>