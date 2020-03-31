<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

   include "local_config.php";
   
   $date = $_POST['newDate'];
   $datestring = date("Y-m-d", $date);
   $query = "UPDATE products SET description = '".$_POST['Desc']."', previewFrame = ".$_POST['previewFrame'].", date = '".$datestring."' WHERE ID = ".$_POST['ID'];
    
   db_query($query);
 
?>