<?php
 include "local_config.php";
 
   $query = "SELECT users.username, COUNT(films.ID) AS cnt FROM users, films WHERE films.ownerID = users.ID GROUP BY ownerID";
   $result = mysql_query($query);

   $output = "<Users>";
   
   while($user = mysql_fetch_object($result))
   {
     $output .= "<User><UserName>".$user->username."</UserName>";
     $output .= "<nFilms>".$user->cnt."</nFilms>";
     $output .="</User>";
   }

   $output .= "</Users>";
   
   mysql_free_result($result);
   mysql_close($mysql);
      
   print($output);

?>
