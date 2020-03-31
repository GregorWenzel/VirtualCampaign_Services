<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		production.inc.php
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
if(!class_exists('audio', false)) {
	require 'classes/audio.class.php';
}
$valueArr 	= '';

$audio = new audio($mysqli);

switch($method) {//set in index.php
	case 'getAudioById':
		if ($res = $audio->getAudioById($_POST)) {
			$ds = mysqli_fetch_array($res);
			$output = "<Audio>";
			$output .= "<FileName>".$ds['name']."</FileName>";
			$output .= "<FileExtension>".$ds['extension']."</FileExtension>";
			$output .= "</Audio>";
			echo $output;
		}
		else {
			echo '<ERROR>' . $audio->_error . '</ERROR>';
		};		
		break;
	case 'getAudioList':
		if($res = $audio->getAudioList($_POST)) {
			$output = '<Audios>';	
			while ($ds = mysqli_fetch_array($res)) {			
				$output .= "<Audio>";
				$output .= "<ID>". $ds['audio_id'] . "</ID>";
				$output .= "<Name>". $ds['name'] . "</Name>";			
				$output .= "<MediaFormatID>"			. $ds['media_format_id']."</MediaFormatID>";
				$output .= "<CreationTime>"			. strtotime($ds['create_ts'])."</CreationTime>";
				$output .= "<AccountID>"		. $ds['account_id']	."</AccountID>";			
				$output .= "<AccountGroupIDs>";
				$res2 = $audio->getGroupsByAudioId($ds['audio_id']);
				while ($ds2 = mysqli_fetch_array($res2)) {
					$output .= "<AccountGroupID>". $ds2['account_group_id']. "</AccountGroupID>";
				}
				$output .= "</AccountGroupIDs>";
				$output .= "</Audio>";
			}
			$output .= "</Audios>";
			echo $output;
		}
		else {
			echo '<ERROR>' . $audio->_error . '</ERROR>';
		};		
		break;	
	case 'uploadAudio':
		if($arr = $audio->uploadAudio($_POST, $_FILES)) {
			echo $arr;
		} else  {
			echo '<ERROR>' . $audio->_error . '</ERROR>';
		};		
		break;
	
	default:
		break;
}
?>