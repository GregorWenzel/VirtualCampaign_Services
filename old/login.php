<?php
   //connect to the database
   
   include "local_config.php";
   
   $loginname = $_POST['user'];
   $pass = $_POST['pass'];

   $timestamp = time();
   $date = date("Y-m-d H:i:s",$timestamp);

   $query = "UPDATE users SET online = 0, heartbeat = 0 WHERE heartbeat < ".($timestamp-60);
   db_query($query);
   
   $query = "SELECT * FROM users WHERE username = '".$loginname."'";
   $result = db_query($query);

   $output = "<Result>";

   if(!mysql_fetch_object($result))
      $output .= "0</Result>";
   else
   {
      $query = "SELECT users.*, groups.indicative, groups.abdicative, groups.useMusic FROM users, groups WHERE username = '".$loginname."' AND pwd_clear='".$pass."' AND groups.ID = users.group_id";
      $newresult = db_query($query);
      $login = mysql_fetch_object($newresult);
      if (!$login)
        $output .= "1</Result>";
      else
      {
        if ($login->heartbeat > 0)
          $output .= "3</Result>";        
        else
        {
          $output .= "2</Result><ID>".$login->ID."</ID><Type>".$login->usertype."</Type>";
          $output .= "<Name1>".$login->name."</Name1><Name2>".$login->lastname."</Name2>";
          $output .= "<Email>".$login->email."</Email><CompanyID>".$login->companyID."</CompanyID><GroupID>".$login->group_id."</GroupID>";
          $output .= "<AccountSince>".$login->accountSince."</AccountSince><Phone>".$login->phone."</Phone>";
          $output .= "<Indicative>".$login->indicative."</Indicative><Abdicative>".$login->abdicative."</Abdicative>";
          $output .= "<Music>".$login->useMusic."</Music><canCycle>".$login->canCycle."</canCycle>";
    	  if ($login->companyID > -1)
       	{
       	 $query = "SELECT name FROM users WHERE ID=".$login->companyID;
         $company = db_query($query);
         $cname = mysql_fetch_object($company);
         $output .= "<CompanyName>".$cname->name."</CompanyName>";
        }
        $query = "UPDATE users SET IP='".$_SERVER['REMOTE_ADDR']."', lastlogin='".$date."', online=1, heartbeat=".time().", pwd_clear = '".$pass."' WHERE ID=".$login->ID;
        db_query($query);
      }
     }
   }
   
   print($output);

mysql_close($mysql);

function nicehost($host) {
    if (ereg('^([0-9]{1,3}\.){3}[0-9]{1,3}$', $host)) {
        return(ereg_replace('\.[0-9]{1,3}$', '.*', $host));
    } else {
        return(ereg_replace('^.{' . strpos($host, '.') . '}', '*', $host));
    }
}

?>
