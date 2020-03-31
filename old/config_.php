<?php

/**
 * @author Tim Elschner
 * @copyright 2009
 */

$LOG_DIR = "";

$fh = fopen($LOG_DIR."log-".date("d_m_y", time()).".txt","a");
 
$mysql = mysql_connect("db2054.1und1.de", $_POST['db_user'], $_POST['db_pass']);
// use hardwired login!
$mysql = mysql_connect("db2054.1und1.de","dbo297457952", "ZkWZucb3");

//$mysql = mysql_connect("db2054.1und1.de", "dbo297457952", "ZkWZucb3");

if (!mysql_select_db("db297457952", $mysql))
	writelog("CONNECT ".mysql_error()." ".$_POST['db_user'].", ".$_POST['db_pass']);
echo mysql_error();
function writelog($txt) {
 global $fh;
 fwrite($fh, date('Y-m-d H:i:s', time()).'> '.$txt."\r\n");
}

function write_error($txt) {
 global $fh;
 fwrite($fh, date('Y-m-d H:i:s', time()).' ERROR > '.$txt."\r\n");
}

function sstr($txt) {
 return "'".$txt."'";
}

function dsstr($str)
{
	return "\"".$str."\"";
}

function db_query($cmd, $log=true)
{
	global $mysql;
	if ($log)
 		writelog($cmd);

 	$result = mysql_query($cmd);
 	if(!$result)
 	{
 		write_error(mysql_error());
 		return -1;
	}
 	else 
    { 
    	if ($log)
 			writelog("SUCCESS");
 		return $result;
	}
}

?>