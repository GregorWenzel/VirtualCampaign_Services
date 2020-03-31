<?php
   //connect to the database
   include "local_config.php";

   $date = time();
   $query = "SELECT ID, username, heartbeat FROM users WHERE heartbeat > 0";
   $result = db_query($query);
	
   $out = "<Users>";

	while ($user = mysql_fetch_array($result))
	{
		if (($date - $user['heartbeat']) > 10)
		{
			$query = "UPDATE users SET online = 0, heartbeat = 0 WHERE ID =".$user['ID'];
			mysql_query($query);
		}
		else
		{
			$out .= "<User>";
			$out .= "<Name>".$user['username']."</Name>";
			$out .= "<ID>".$user['ID']."</ID>";
			$out .= "</User>";			
		}
	}
	
	$out .= "</Users>";
	
	print($out);
mysql_close($mysql);
?>
