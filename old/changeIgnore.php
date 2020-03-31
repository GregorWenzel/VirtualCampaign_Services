<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

    $mysql = mysql_connect("localhost", "root", "rS06gQoe");
    mysql_select_db("virtualcampaign");
    
    $query = "UPDATE stromkosten SET ignore_shutdown = ".$_POST['ignore'];
    mysql_query($query);
    
    mysql_close($mysql);
?>