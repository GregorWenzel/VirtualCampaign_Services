<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

include "local_config.php";

$query = "INSERT INTO product_owners (owner_id, product_id) VALUES (".$_POST['group'].", ".$_POST['product'].")";
db_query($query);


?>