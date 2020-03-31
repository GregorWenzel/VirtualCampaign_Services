<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

include "local_config.php";

$query = "UPDATE products SET all_allowed = ".$_POST['allow']." WHERE ID = ".$_POST['product'];
db_query($query);

?>