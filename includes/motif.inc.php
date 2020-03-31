<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		motif.inc.php
 * 
 * @author  	Christiane Kuhn <kuhn@webdevelopment-koeln.de>
 * @link 		http://webdevelopment-koeln.de
 * 
 * @category	Virtual Campaign 
 *
 * 
 * This Script was developed by Christiane Kuhn - ChK Web-Development.
 * You are authorised to use this Script on your internet sides.
 * The right to use applies only to the direct use of the software by HERZBERGMEDIA/ibt-studios for Virtual Campaign.
 * You are not authorised to resale, to lease or to publish the software outside of HERZBERGMEDIA/ibt-studios for Virtual Campaign.
 *
 * The passing of this script is not allowed.
 *
 * Please do not remove this header.
 *
 * ----------------------------------------------------------------------------
 * Rev: 	2012-01 	ChK	Created
 * 
 * ----------------------------------------------------------------------------
 *
 */
if(!class_exists('motif', false)) {
	require 'classes/motif.class.php';
}
$valueArr 	= '';
$account_id = null;
$motif_id 	= null;

if(isset($_POST['userID'])) {
	$account_id = (int)$_POST['userID'];
}
if(isset($_POST['motifID'])) {
	$motif_id = (int)$_POST['motifID'];
}
$motif = new motif($mysqli, $account_id, $motif_id);

switch($method) {//set in index.php
	case 'updateMotifFrames':
		$output = '<SUCCESS>' .$motif->updateMotifFrames($_POST). '</SUCCESS>';
		echo $output;
		break;
	case 'migrateMotif':
		if($arr = $motif->migrateMotif($_POST)) {
			$output= '';
			$output .= '<ID>' . $motif->_motif_id . '</ID>';
			echo $output;
		} else  {
			echo '<ERROR>' . $motif->_error . '</ERROR>';
		};		
		break;	
	case 'uploadMotif':
		/* POST-Vars:
		 * int ownerID
		 * int motifTypeID
		 * string motifName
		 * string motifComment
		 * string image_film_flag (values 'image', 'film' or ''(für update ohne file))
		 * string extension (only for 'film' required) 
		 * $_FILES['Filedata'] for uploaded file
		 */
		if($arr = $motif->uploadMotif($_POST, $_FILES)) {
			$output= '';
			if(is_array($arr)) {
				$output .= '<Result>';
				$output .= '<Name>' . $arr['name'] . '</Name>';
				$output .= '<Animated>' . $arr['animated'] . '</Animated>';
				$output .= '<nFrames>' . $arr['frames_count'] . '</nFrames>';
				$output .= '<Aspect>' . $arr['aspect'] . '</Aspect>';
				$output .= '<Time>' . $arr['time'] . '</Time>';
				$output .= '<Extension>' . $arr['extension']. "</Extension>";
				$output .= '</Result>';
			}
			echo $output;
		} else  {
			echo '<ERROR>' . $motif->_error . '</ERROR>';
		};		
		break;	
	case 'saveMotif':
		$motif_id = $motif->saveMotif($_POST);
		if (!is_null($motif_id)) {
			echo "<ID>".$motif_id."</ID>";
		} else  {
			echo '<ERROR>' . $motif->_error . '</ERROR>';
		};		
		break;	
	case 'updateMotif':
		/* POST-Vars:
		 * int motifID
		 * int ownerID
		 * int motifTypeID
		 * string motifName
		 * string motifComment
		 * string image_film_flag (values 'image', 'film' or ''(für update ohne file))
		 * string extension (only for 'film' required)
		 * $_FILES['Filedata'] for uploaded file
		 */
		if($motif->updateMotif($_POST)) {
			echo '<ID>' . $motif->_motif_id . ' updated</ID>';
			if(isset($arr) && is_array($arr)) {
				$output .= '<Result>';
				$output .= '<Name>' . $arr['name'] . '</Name>';
				$output .= '<Animated>' . $arr['animated'] . '</Animated>';
				$output .= '<nFrames>' . $arr['frames_count'] . '</nFrames>';
				$output .= '<Aspect>' . $arr['aspect'] . '</Aspect>';
				$output .= '<Time>' . $arr['time'] . '</Time>';
				$output .= '</Result>';
			}
		} else  {
			echo '<ERROR>' . $motif->_error . '</ERROR>';
		};		
		break;
	case 'deleteMotif':
		/* POST-Vars:
		 * int motifID
		 * int ownerID
		 */ 
		if($res = $motif->deleteMotif($_POST)) {
			echo '<ID>' . $res . '</ID>';
		} else  {
			echo '<ERROR>' . $motif->_error . '</ERROR>';
		};	
		break;
	case 'readMotif':
		/* POST-Vars:
		 * int motifID
		 * int ownerID
		 */ 
		if($res = $motif->readMotif($_POST)) {
			$output= '';
			if ($ds = mysqli_fetch_array($res)) {
     			$output .= "<Motif>";
	     			$output .= "<ID>" . $ds['motif_id'] . "</ID>";
	     			if (1 == (int)$ds['frames_count']) {
	        			//$output .= "<Path>" . $ds['account_id'] . "/motifs/" .$motif->ID . "_thumb.jpg</Path>";
	        			//per neu: motifs/account_id/....
	        			$output .= "<Path>" . $ds['path'] . "</Path>"; //TODO: thumb-Path noch mit in DB? 
	     			} else {
	        			//$output .="<Path>".$motif->ownerID."/motifs/".$motif->ID."/motif.jpg</Path>";
	        			//per neu: motifs/account_id/....
	        			$output .= "<Path>" . $ds['path'] . "</Path>";
	     			}
	     			$output .= "<Name>" . $ds['name'] . "</Name>";
	     			$output .= "<Date>" . $ds['creation_time'] . "</Date>";
	     			$output .= "<Type>" . $ds['media_format_id'] . "</Type>";
	     			$output .= "<Width>" . $ds['width'] . "</Width>";
	     			if(0 != (int)$ds['aspect']) {
	     				$output .= "<Height>" . round($ds['width']/$ds['aspect']) . "</Height>";
	     			} else {
	     				$output .= "<Height>" . $ds['width'] . "</Height>";
	     			}
	     			$output .= "<Comment>" . $ds['description'] . "</Comment>";
	     			$output .= "<Aspect>" . $ds['aspect'] . "</Aspect>";
	     			$output .= "<Frames>" . $ds['frames_count'] . "</Frames>";
     			$output .= "</Motif>";
   			} else {
   				$output .= "<Motif>";
   				$output .= "...TODO no motif found...";
   				$output .= "</Motif>";
   			}//ds
			echo $output;
		} else  {
			echo '<ERROR>' . $motif->_error . '</ERROR>';
		};	
		break;
	case 'getMotifListByAccount':
		if($res = $motif->getMotifListByAccount($_POST)) {
			$output = '<Motifs>';		
			while ($ds = mysqli_fetch_array($res)) {
				$output .= "<Motif>";
				$output .= "<ID>"			.$ds['motif_id']			."</ID>";
				$output .= "<Name>"			.$ds['motif_name']			."</Name>";
				$output .= "<AccountID>"	.$ds['account_id']			."</AccountID>";
				$output .= "<MediaFormatID>".$ds['media_format_id']		."</MediaFormatID>";
				$output .= "<FrameCount>"	.$ds['frames_count']		."</FrameCount>";
				$output .= "<Width>"		.$ds['width']				."</Width>";
				$output .= "<Aspect>"		.$ds['aspect']				."</Aspect>";
				$output .= "<Path>"			.$ds['path']				."</Path>";	
				$output .= "<FileName>"		.$ds['orig_filename']		."</FileName>";								
				$output .= "<CreationTime>"	.strtotime($ds['creation_time'])		."</CreationTime>";
				$output .= "<Description>"	.$ds['description']		."</Description>";			
				$output .= "<TextContent>"	.$ds['text_content']		."</TextContent>";	
				$output .= "<Extension>"	.$ds['extension']			."</Extension>";
				$output .= "<FormatType>"	.$ds['format_type']			."</FormatType>";
				$output .= "<FormatName>"	.$ds['media_format_name']	."</FormatName>";				
				$output .= "</Motif>";
			}
			$output .= "</Motifs>";
			echo $output;
		} else  {
			echo '<ERROR>' . $production->_error . '</ERROR>';
		};	
		break;
	default:
		break;
}
?>