<?php
   include "local_config.php";

   $query = "SELECT users.*, groups.ID AS gID, groups.group_name AS gName FROM users, groups WHERE groups.ID = users.group_id";
   
   $result = db_query($query);

   $output = "<Users>";
   
   while($user = mysql_fetch_object($result))
   {
     $output .= "<User><UserName>".$user->username."</UserName><ID>".$user->ID."</ID><Name1>".$user->name."</Name1><Name2>".$user->lastname."</Name2><Type>".$user->usertype;  
     $output .= "</Type><LastLogin>".$user->lastlogin."</LastLogin><Online>".$user->online."</Online>";
     $output .= "<Email>".$user->email."</Email><GroupID>".$user->gID."</GroupID><GroupName>".$user->gName."</GroupName>";
     $output .= "<AccountSince>".$user->accountSince."</AccountSince><Phone>".$user->phone."</Phone>";
     $output .= "<Description>".$user->description."</Description>";
     $output .= "<Pass>".$user->pwd_clear."</Pass>";
        
     $query = "SELECT COUNT(ID) AS nFilms FROM films WHERE ownerID = ".$user->ID." UNION SELECT COUNT(ID) AS nFilms FROM jobQueue WHERE ownerID = ".$user->ID;
     $result2 = mysql_query($query);
     $statistic = mysql_fetch_object($result2);
     $output .= "<nFilms>".$statistic->nFilms."</nFilms>";
	 
	 $statistic = mysql_fetch_object($result2);
	 $output .= "<nJobs>".$statistic->nFilms."</nJobs>";
	  
     $output .="</User>";
   }

   $output .= "</Users>";
   
   $output .= "<Groups>";
   
   $query = "SELECT * FROM groups";
   $result = db_query($query);
   
   while ($group = mysql_fetch_object($result))
   {
	 $output .= "<Group><ID>".$group->ID."</ID><Name>".$group->group_name."</Name>";
	 $output .= "<Indicative>".$group->indicative."</Indicative><Abdicative>".$group->abdicative."</Abdicative>";
	 $output .= "<Music>".$group->useMusic."</Music>";
	 $output .= "</Group>";
   }
   $output .= "</Groups>";
   
   mysql_free_result($result);
   
   print($output);

?>
