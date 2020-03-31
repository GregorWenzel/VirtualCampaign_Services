<?php 
  include "local_config.php";
  
  $query = "UPDATE users SET online=1, heartbeat=".time()." WHERE ID=".$_POST['userID'];
  db_query($query, false);
  
  $query = "SELECT * from server WHERE ID=1";
  $result = mysql_query($query);
  $server = mysql_fetch_array($result);
  
  echo mysql_error();
  
  print("<ServerIP>".$server["server_ip"]."</ServerIP>");
  print("<Maintenance>".$server['maintenance']."</Maintenance>");
  print("<MaintenanceDate>".$server['maintenance_date']."</MaintenanceDate>");
//  print("<PipeLiner>".$server['server_date']."</PipeLiner>");
// Quick Fix, output current date!  
  print("<PipeLiner>".time()."</PipeLiner>");
  
mysql_close($mysql);
?>
	
