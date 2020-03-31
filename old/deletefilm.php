<?php

  include "local_config.php";

  $query = "DELETE FROM films WHERE ID=".$_POST['ID'];
  db_query($query, true);

  if ($_POST['Path'] != "")
  {
    deltree($_POST['Path']);
  }

  function deltree($f) {
   if (is_dir($f)) {
    foreach(glob($f.'/*') as $sf) {
      if (is_dir($sf) && !is_link($sf)) {
        deltree($sf);
      } else {
        unlink($sf);
      } 
    } 
   }
   rmdir($f);
  }

mysql_close($mysql);
?>