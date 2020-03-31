<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

include "local_config.php";

$query = "DELETE FROM product_owners WHERE owner_id = ".$_POST['group']." AND product_id = ".$_POST['product'];
db_query($query);

?>