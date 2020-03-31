<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

include "local_config.php";

$query = "SELECT groups.group_name AS OWNERNAME, product_owners.owner_id as OWNERID, product_owners.product_id FROM groups, product_owners WHERE product_owners.product_id = ".$_POST['productID']." AND product_owners.owner_id = groups.ID";

$result = db_query($query);
$out = "<Owners>";

while ($owner = mysql_fetch_array($result))
{
	$out .= "<Owner><ID>".$owner['OWNERID']."</ID><Name>".$owner['OWNERNAME']."</Name></Owner>";
}

$out .= "</Owners>";

print($out);

?>