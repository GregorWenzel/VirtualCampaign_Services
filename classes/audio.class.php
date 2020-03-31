<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		production.class.php
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

/**
 * class production
 *
 */
class audio
{
	protected $_dbconn;
	public $_error;
	public $_errorArr;
	
	/**
	 * set required params for actions
	 * defined foreach action
	 */
	protected $_required =  array(
					'getAudioById'  => array(array('name' => 'audioID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								)
					,'getAudioList'  => array(array('name' => 'accountID'
										,'type' => 'int' 
										,'musthave' => 'optional' 
										,'maxstringlen' => ''      
										)
								)	
					,'uploadAudio' => array(array('name' => 'accountID'
										,'type' => 'int' 
										,'musthave' => 'optional' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'mediaFormatID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)										
								,array('name' => 'name'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '1024'      
										)	
								,array('name' => 'task'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '1024'      
										)	
								,array('name' => 'audioID'
										,'type' => 'int' 
										,'musthave' => 'optional' 
										,'maxstringlen' => ''      
										)											
								)														
				);
				
	/**
	 * Constructor
	 *
	 * @param obj $dbconn
	 * @param int $production_id = null
	 * 
	 */
	public function __construct($dbconn)
	{
		$this->_dbconn 			= $dbconn;
	}
	
	public function getAudioById($valueArr){
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['getAudioById']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;	
			$sql = "select audio.name, media_format.extension"
				 . " from audio, media_format"
				 . " where audio.audio_id = ".(int)$valueArr['audioID']
				 . " and delete_ts IS NULL"
				 . " and media_format.media_format_id = audio.media_format_id";

			if ($res =  $this->_dbconn->query($sql)) {
				return $res;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getAudioById'),$this->_dbconn->error);
				return false;
			}
		}
	}
	
	public function getGroupsByAudioId($audioId) {
	
		$sql = "select * from xref_audio_group where audio_id = ".(int)$audioId;
		
		if ($res =  $this->_dbconn->query($sql)) {
				return $res;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getGroupsByAudioId'),$this->_dbconn->error);
				return false;
			}
	}

	public function getAudioList($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getAudioList']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			$valueArr = format::$_returnArr;
			
			$sql = "select * from audio where delete_ts IS NULL";
			
			if (isset($valueArr['accountID']))
			{
				$sql .= " and account_id = ".(int)$valueArr['accountID'];
			}
			
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getAudioList'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}

    // Add new audio func
    protected function _addAudio($valueArr, $files)
    {
        $sql = "INSERT INTO  audio SET"
            . " media_format_id = " . (int)$valueArr['mediaFormatID']
            . ",account_id = null"
            . ",name = '" . $this->_dbconn->real_escape_string($valueArr['name']) . "'";

        if ($res = $this->_dbconn->query($sql)) {
            $_audio_id = $this->_dbconn->insert_id;
            $this->moveAndModifyAudio($_audio_id, $files, false);

            $sql = "insert into xref_audio_group set audio_id = " . $_audio_id . ", account_group_id = -1";
            $this->_dbconn->query($sql);

            return $_audio_id;
        } else {
            $this->_error .= error::buildErrorString(array('SQL-ERROR' => 'audio->uploadAudio'), $this->_dbconn->error);
            return false;
        }

    }

    public function uploadAudio($valueArr, $files)
    {
        $success = format::check_params($valueArr, $this->_required['uploadAudio']);
        if ($success === false) {
            $this->_error .= error::buildErrorString(format::$_errorArr);
            return null;
        } else {
            $valueArr = format::$_returnArr;
            if ($valueArr['task'] == "addAudio") {
                // On add
                return $this->_addAudio($valueArr, $files);
            } else if ($valueArr['task'] == "updateAudio") {
                // On update
                if (!isset($valueArr['audioID']) || !(int)$valueArr['audioID']) {
                    return $this->_error = 'Invalid audio id';

                }
                $_audio_id = (int)$valueArr['audioID'];
                if ('' != $files["Filedata"]["tmp_name"] && is_uploaded_file($files['Filedata']['tmp_name']) === true) {
                    $this->moveAndModifyAudio($_audio_id, $files, false);
                }
                $sql = "UPDATE audio SET"
                    . " media_format_id = " . (int)$valueArr['mediaFormatID']
                    . ",name = '" . $this->_dbconn->real_escape_string($valueArr['name']) . "'
                    WHERE audio_id = " . $_audio_id;
                $this->_dbconn->query($sql);

                return $_audio_id;
            } else {
                $this->_error = 'Task type error';
            }
        }
    }
	
	protected function moveAndModifyAudio($audio_id, $files, $deleteOldFile) {
		
		$uploaddir 	= AUDIO_DIR.'/';
		
		if ($deleteOldFile == true)
		{
			$mask = AUDIO_DIR."audio_".$audio_id.".*";
			array_map("unlink", glob($mask));
		}
		
		$filename = $files['Filedata']['name'];
		$extension =  ".".pathinfo($filename, PATHINFO_EXTENSION);			
		
		$targetname = 'audio_' . $audio_id;
		
		move_uploaded_file($files['Filedata']['tmp_name'], $uploaddir . $targetname . $extension);
	}
} 
?>