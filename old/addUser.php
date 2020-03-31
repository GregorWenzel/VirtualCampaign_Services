<?php 

 include "local_config.php";

   $query = "INSERT INTO users (username, password, accountSince, name, lastname, email, phone, group_id, description, pwd_clear) VALUES ";
   $query .= "('".$_POST['username']."', PASSWORD('".$_POST['password']."'), '".$date."', '".$_POST['fname']."', '".$_POST['lname']."', '".$_POST['email']."', '".$_POST['phone']."', ".$_POST['groupID'].", '".$_POST['description']."', '".$_POST['password']."')";
   
   db_query($query);
   
   mkdir("X:/web/virtualcampaign/users/".$_POST['userID']);
	mkdir("X:/web/virtualcampaign/users/".$_POST['userID']."/jobs");
	mkdir("X:/web/virtualcampaign/users/".$_POST['userID']."/motifs");
	
	print("<ID>".mysql_insert_id()."</ID>");
?>
	
