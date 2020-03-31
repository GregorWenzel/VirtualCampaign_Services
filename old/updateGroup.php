<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

  //connect to the database
  include "local_config.php";

  $query = "UPDATE groups SET useMusic = ".$_POST['Music'].", indicative = ".$_POST['Indicative'].", abdicative = ".$_POST['Abdicative']." WHERE ID = ".$_POST['ID'];
 
  db_query($query);
?>