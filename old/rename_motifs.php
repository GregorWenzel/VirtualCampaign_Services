<?php

/**
 * @author useruser
 * @copyright 2010
 */
    include "local_config.php";

    $local_path = str_replace("/","\\",$local_path);
    
	$query = "SELECT * FROM motifs";
    $result = db_query($query);
    
    $user_path = $local_path."virtualcampaign\\users\\";
    
   	while ($motif = mysql_fetch_array($result))
    {
        $motif_path = $user_path.$motif['ownerID']."\\motifs\\".$motif['ID'];
        echo($motif_path.".jpg<br/>");
        
        if (file_exists($motif_path.".jpg"))
        {
            rename($motif_path.".jpg", $motif_path."s.jpg");
            //echo($motif_path.".jpg<br/>");
        }
    }
    
    
	mysql_query($query);

?>