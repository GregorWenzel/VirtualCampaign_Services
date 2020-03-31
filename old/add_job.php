
<?php 
/**
 * @author Gregor Wenzel
 * @copyright 2009
 * purpose: inserts into table jobqueue all fields in POST parameter (in effect, copies data from virtualcampaign.de tables into local table)
 * POST parameters: keys and fields
 * output: ID of job added or error code
 */
 
include "local_config.php";
	
	$post_keys = array_keys($_POST);
	$n_keys = count($post_keys);
	
	$query_array = array();
	$value_array = array();

  	$query = "SELECT COUNT(*) FROM jobqueue WHERE sourceID = ".$_POST['ID'];
  	$result = mysql_query($query);
  	$idcount = mysql_fetch_array($result);
  	
  	if ($idcount[0] > 0)
  		return;
	
	writelog(implode(",",$post_keys));
	writelog("ID = ".$_POST['ID']);
	
    for ($i=0; $i<$n_keys; $i++)
		if (!is_numeric($post_keys[$i]) && ($post_keys[$i] != "ID") && ($post_keys[$i] != "renderID"))
		{
			array_push($query_array, $post_keys[$i]);
			if (is_numeric($_POST[$post_keys[$i]]))
				array_push($value_array, $_POST[$post_keys[$i]]);
			else	
				array_push($value_array, "'".$_POST[$post_keys[$i]]."'");
		}

	$query = "INSERT INTO jobqueue (".implode(",",$query_array).", sourceID) VALUES (".implode(",",$value_array).", ".$_POST['ID'].")";
    
    $result = db_query($query); 
	if (!$result)
	{
		print ("<JobAdded>-1</JobAdded>");
		print ("<JobError>".mysql_error()."</JobError>");
	}
	else
		print ("<JobAdded>".mysql_insert_id()."</JobAdded>");
		
	mysql_close($mysql);
?>
	