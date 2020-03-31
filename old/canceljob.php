<?php 

  $jobid = $_POST['JOBID'];

  $mysql = mysql_connect("localhost", "gregor", "r0binhood");
  mysql_select_db("virtualcampaign");

  //init logfile
  $lf0 = "X:/web/virtualcampaign/logs/logfile".date("Y-m-d", time()).".txt";
  $fh0 = fopen($lf0, 'a');
  
  $query = "SELECT * FROM jobqueue WHERE ID=".$jobid;
  $result = mysql_query($query);
  $slave = mysql_fetch_object($result);
  $slaveip = $slave->slaveIP;
  $status = $slave->status;
    
  if ($status == 1)
  {
    $cmd = "\"C:\\Program Files (x86)\\eyeon\\Render Slave 5.3.78\\eyeonscript.exe\" \"x:\\web\\virtualcampaign\\scripts\\fusion\\canceljob\" ".$jobid;
    writelog($cmd);
    writelog(syscall($cmd));
  }

  $query = "UPDATE jobqueue SET status = 4 WHERE ID = ".$jobid;
  mysql_query($query);


 function syscall($command){
    if ($proc = popen("($command)2>&1","r")){
        while (!feof($proc)) $result .= fgets($proc, 1000);
        pclose($proc);
        return $result;
        }
    }

function writelog($txt)
{
	global $fh0;
	fwrite($fh0, date("Y-m-d H:i:s",time())."> ".$txt."\r\n");
}

fclose($fh0);
mysql_close($mysql);
?>
	