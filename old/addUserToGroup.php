<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

include "local_config.php";

  
$query = "UPDATE users SET group_id = ".$_POST['group']." WHERE ID = ".$_POST['user'];
  
db_query($query);

?>