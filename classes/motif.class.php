<?php

error_reporting(-1); // reports all errors
ini_set("display_errors", "1"); // shows all errors
ini_set("log_errors", 1);
ini_set("error_log", "./php-error.log");

/** 
 * ----------------------------------------------------------------------------
 * File: 		motif.class.php
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

class MotifUploadException extends Exception {

};

/**
 * class motif
 *
 */
 
class motif
{
	protected $_dbconn;
	public $_motif_id;
	public $_account_id;
	public $_error;
	public $_errorArr;
    protected $allowedImages = ['image\/jpeg', 'image\/png', 'application\/pdf'];
    protected $allowedVideos = ['video\/.*'];
	
	/**
	 * set required params for actions
	 * defined foreach action
	 */
	protected $_required =  array(
				'migrateMotif' => array(array('name' => 'name'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'accountID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'description'
										,'type' => 'string' 
										,'musthave' => 'optional' 
										,'maxstringlen' => '255'      
										)						
								,array('name' => 'oldID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'oldAccountID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'oldAccountGroupID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)										
								,array('name' => 'frameCount'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'oldPath'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)				
								,array('name' => 'creationTime'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''  
										)
								)
				,'uploadMotif' => array(array('name' => 'accountID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'image_film_flag'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)	
								)		
				,'saveMotif' => array(array('name' => 'accountID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'media_format_id'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'aspect'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)	
								,array('name' => 'fileName'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)	
								,array('name' => 'width'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)			
								,array('name' => 'frameCount'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)			
								,array('name' => 'name'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'description'
										,'type' => 'string' 
										,'musthave' => 'optional' 
										,'maxstringlen' => '255'      
										)
								)
				,'createMotif' => array(array('name' => 'accountID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'media_format_id'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'name'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'description'
										,'type' => 'string' 
										,'musthave' => 'optional' 
										,'maxstringlen' => '255'      
										)
								)
				,'updateMotif' => array(array('name' => 'motifID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'ownerID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'motifTypeID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'motifName'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'motifComment'
										,'type' => 'string' 
										,'musthave' => 'optional' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'image_film_flag'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'extension'
										,'type' => 'string' 
										,'musthave' => 'optional'  //only for film required 
										,'maxstringlen' => ''      
										)
								)
				,'deleteMotif' => array(array('name' => 'motifID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'ownerID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'permanent'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)										
								)
				,'readMotif' => array(array('name' => 'motifID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'ownerID'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								)
				,'updateMotifFrames' => array(array('name' => 'motifID'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'frameCount'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								)								
				,'getMotifListByAccount' => array(array('name' => 'accountID'
									,'type' => 'int' 
									,'musthave' => 'optional' 
									,'maxstringlen' => ''      
									)
								,array('name' => 'min_date'
									,'type' => 'string' 
									,'musthave' => 'required' 
									,'maxstringlen' => '255' 	
									)										
							)		
			);
				
	/**
	 * Constructor
	 *
	 * @param obj $dbconn
	 * @param int $motif_id = null
	 * 
	 */
	public function __construct($dbconn, $account_id = null, $motif_id = null)
	{
		$this->_dbconn 		= $dbconn;
		$this->_motif_id 	= $motif_id;
		$this->_account_id 	= $account_id;
	}

    protected function validType($file, $typesList = []) {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $detected_type = finfo_file($fileInfo, $file);
        foreach($typesList as $format) {
            if (preg_match("/{$format}/", $detected_type)) {
                return true;
            }
        }

        return false;
    }
	
	public function updateMotifFrames($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['updateMotifFrames']);
		
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {	
			$valueArr = format::$_returnArr;
			
			$sql = "update motif set frames_count = ".$valueArr['frameCount']." where motif_id = ".$valueArr['motifID'];
			
			if ($res =  $this->_dbconn->query($sql)) {
				return true;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'motif->updateMotifFrames'),$this->_dbconn->error);
				return false;
			}
		}
	}

    public function getMediaTypeId($ext) {
        $sql = "SELECT media_format_id FROM media_format WHERE extension = '.{$ext}' LIMIT 1";
        if ($res = $this->_dbconn->query($sql)) {
            $data = mysqli_fetch_array($res);
            if ($data['media_format_id']) {
                return $data['media_format_id'];
            }
        }
        return 1;
    }

    protected function getFileType($file) {
        $cmd = "identify " . $file;
        $image_identify = exec($cmd);
        $image_data_arr = explode(" ", $image_identify);
        $format = 'jpg';
        if (count($image_data_arr) >= 6) {
            $format = strtolower($image_data_arr[1]);
            if ($format == 'jpeg') {
                $format = 'jpg';
            }
        }
        return $format;
    }

	public function saveMotif($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['saveMotif']);
		
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {	
			$valueArr = format::$_returnArr;
            $originalFile = null;
            $originalExt = null;
            $files = glob(UPLOAD_DIR . '/'.(int)$valueArr['accountID'].'/temp/'."original_*");
            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileData= pathinfo($file);
                    $valueArr['media_format_id'] = $this->getMediaTypeId(strtolower($fileData['extension']));
                    $originalExt = $fileData['extension'];
                    $originalFile = $file;
                    break;
                }
            }

			$sql = "insert into motif"
						." set"
						." account_id 		= "	.(int)$valueArr['accountID']
						.",name		  		='"	.$this->_dbconn->real_escape_string($valueArr['name']) . "'"
						.",description		='"	.$this->_dbconn->real_escape_string($valueArr['description']) . "'"
						.",frames_count		= " .(int)$valueArr['frameCount']
						.",width			= " .(int)$valueArr['width']
						.",aspect			= " .(int)$valueArr['aspect']
						.",creation_time	= FROM_UNIXTIME(" .time().")"
						.",create_ts		= FROM_UNIXTIME(" .time().")"
						.",media_format_id 	= " .(int)$valueArr['media_format_id']						
						.";";
			if ($res =  $this->_dbconn->query($sql)) {
				$this->_motif_id = $this->_dbconn->insert_id;
				
				$info = pathinfo($valueArr['fileName']);
				$extension = $info['extension'];
				
				$source	= UPLOAD_DIR . '/'.(int)$valueArr['accountID'].'/temp/'.$valueArr['fileName'];
				$source = str_replace("//", "/", $source);
                $targetDir = UPLOAD_DIR . '/'.(int)$valueArr['accountID'].'/motifs/';
				$target = $targetDir.$this->_motif_id.".".$extension;
				$target = str_replace("//", "/", $target);

				/*
                if ($originalFile) {
                    copy($originalFile, "{$targetDir}{$this->_motif_id}.{$originalExt}");
                }
				*/
				//delete old png preview file
				//unlink($source);
//				$source = str_replace(".png", ".jpg", $source);
				
				rename ($source, $target);
				echo "<Source>".$source."</Source><Target>".$target."</Target>";					
	            error_log($source." -> ".$target."\r\n", 3, "./image_data.log");
		
				if (((int)$valueArr['frameCount']) >= 0)
				{
					$basefile = pathinfo($valueArr['fileName'], PATHINFO_FILENAME);
				
					$source	= UPLOAD_DIR . '/'.(int)$valueArr['accountID'].'/temp/'.$basefile."_thumb.jpg";
					$target = UPLOAD_DIR . '/'.(int)$valueArr['accountID'].'/motifs/'.$this->_motif_id."_thumb.jpg";
                    $source = str_replace('//', '/', $source);
                    $target = str_replace('//', '/', $target);
					echo "<Source>".$source."</Source><Target>".$target."</Target>";
					rename ($source, $target);
		            error_log($source." -> ".$target."\r\n", 3, "./image_data.log");
				}
				
				$files = glob(UPLOAD_DIR . '/'.(int)$valueArr['accountID'].'/temp/*');
				foreach ($files as $file)
				{
					if (is_file($file))
					{
						unlink($file);
					}
				}
				
			} else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'motif->saveMotif'),$this->_dbconn->error);
				return false; 
			}
		}
		
		return $this->_motif_id;
	}
	
	public function deleteMotif($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['deleteMotif']);
		
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {	
			$valueArr = format::$_returnArr;
			
			$sql = "update motif set delete_ts = now() where motif_id = ".$valueArr['motifID'];
			
			if ($res =  $this->_dbconn->query($sql)) {
				return true;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'motif->deleteMotif'),$this->_dbconn->error);
				return false;
			}
		}
	}
	
	
	public function uploadMotif($valueArr, $files) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['uploadMotif']);
		
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {	
			$valueArr = format::$_returnArr;
			//echo "<AccountID>".$valueArr['accountID']."</AccountID>";
            if (!$files["Filedata"]["tmp_name"] || !is_uploaded_file($files['Filedata']['tmp_name'])) {
                $this->_error .= error::buildErrorString(['File' => 'File not uploaded']);
                return null;
            }

            if ($files["Filedata"]["size"] < 100) {
                $this->_error .= error::buildErrorString(['File' => 'Invalid file size']);
                return null;
            }

			if('' != $files["Filedata"]["tmp_name"] && is_uploaded_file($files['Filedata']['tmp_name'])===true) {
				$orig_filename = $files['Filedata']['name'];
				$file_extension = pathinfo($orig_filename, PATHINFO_EXTENSION);
				
				if('image' == $valueArr['image_film_flag']) {
                    if (!$this->validType($files["Filedata"]["tmp_name"], $this->allowedImages)) {
                        $this->_error .= error::buildErrorString(['File' => 'Invalid file type']);
                        return null;
                    }

					$return = $this->moveAndModifyImage($valueArr['accountID'], $files);
					if(null === $return || !is_array($return)) {
						$this->_error .= 'ERROR: moveAndModifyImage';
						return null;
					} else {
						$return['aspect'] 		= round($return['aspect'],2);
					}
				} elseif ('film' == $valueArr['image_film_flag']) {
                    if (!$this->validType($files["Filedata"]["tmp_name"], $this->allowedVideos)) {
                        $this->_error .= error::buildErrorString(['File' => 'Invalid file type']);
                        return null;
                    }

					$return = $this->moveAndModifyFilm($valueArr['accountID'], $files);
					if(null === $return || !is_array($return)) {
						$this->_error .= 'ERROR: moveAndModifyFilm';
						return null;
					} else {
						$return['aspect'] 		= round($return['aspect'],2);
					}
				} else {
					//no uplaod => cannot create motif
					$this->_error .= 'ERROR: no file was uploaded';
					return null;
				}
			}		
		}
		return $return;
	}
	
    protected function validFile($filePathName) {
        return (boolean) (is_file($filePathName) && (filesize($filePathName) > 0));
    }


    public function createThumb($account_id, $files) {
        $return = [
            'name'         => '',
            'animated'     => 0,
            'frames_count' => 1,
            'width'        => 0,
            'aspect'       => 1,
            'time'         => 0,
            'extension'    => ''
        ];

        $uploadDir = UPLOAD_DIR . '/' . $account_id . '/temp/';
        $uploadDir = str_replace('//', '/', $uploadDir);
        $userSourceDir = str_replace('//', '/', UPLOAD_DIR . '/' . $account_id . '/');
        $userSourceUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $uploadDir);
        if (!is_dir($userSourceDir)) {
            if (!mkdir($userSourceDir)) {
                throw new MotifUploadException('Error account directory create');
            }
        }

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir)) {
                throw new MotifUploadException('Error upload directory create');
            }
        }

        $filename = $files['Filedata']['name'];
        $extension = "." . pathinfo($filename, PATHINFO_EXTENSION);
        $return['extension'] = $extension;
        $return['path'] = $userSourceUrl;
        $return['time'] = time();
        $targetName = "original_{$account_id}_{$return['time']}";
        $return['name'] = $targetName;
        $target = "{$uploadDir}{$targetName}{$extension}";

        if (!move_uploaded_file($files['Filedata']['tmp_name'], $target)) {
            throw new Exception('Error file upload');
        }

        $aspect = 1;
        $cmd = "identify " . $target;
        $image_identify = exec($cmd);
        $image_data_arr = explode(" ", $image_identify);
        $width = 120;
        if (count($image_data_arr) >= 6) {
            $sizes = explode('x', $image_data_arr[2]);
            if (count($sizes) > 1) {
                $width = $sizes[0];
                $aspect = $sizes[0] / $sizes[1];
            }
        }

        $return['width'] = $width;
        $return['aspect'] = $aspect;

        $thumbName = $uploadDir . $targetName . '_thumb.jpg';
        $cmd = 'convert ' . $target . ' -resize ' . (int)($aspect * NEWHEIGHT_S) . 'x' . NEWHEIGHT_S . ' ' . $thumbName;
        $cmd = str_replace(",", ".", $cmd);
        error_log($cmd . "\r\n", 3, "./image_data.log");
        exec($cmd);

        return $return;
    }

    protected function moveAndModifyImage($account_id, $files) {
//        try {
//            return $this->createThumb($account_id, $files);
//        } catch (Exception $e) {
//            $this->_error .= error::buildErrorString(['ERROR' => $e->getMessage()]);
//            return null;
//        }
        // temporary changes (remove on review)

        try {
            $return = array(
                'name'         => '',
                'animated'     => 0,
                'frames_count' => 1,
                'width'        => 0,
                'aspect'       => 1,
                'time'         => 0,
                'extension'    => ''
            );

            $uploadDir = UPLOAD_DIR . '/' . $account_id . '/temp/';

            if (!is_dir(UPLOAD_DIR . '/' . $account_id)) {
                if (!mkdir(UPLOAD_DIR . '/' . $account_id)) {
                    throw new MotifUploadException('Error account directory create');
                }
            }

            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir)) {
                    throw new MotifUploadException('Error upload directory create');
                }
            }

            // clear temp directory before upload
            $filesToDelete = glob(UPLOAD_DIR . '/'.$account_id.'/temp/*');
            foreach ($filesToDelete as $delFile) {
                if (is_file($delFile)) {
                    unlink($delFile);
                }
            }

            $filename = $files['Filedata']['name'];
            $extension = "." . pathinfo($filename, PATHINFO_EXTENSION);
			$extension = strtolower($extension);
            $return['time'] = time();
            $tempName = 'uploaded_' . $account_id . "_" . $return['time'];
            $converted_name = 'converted_' . $account_id . "_" . $return['time'];

            if (!move_uploaded_file($files['Filedata']['tmp_name'], $uploadDir . $tempName . $extension)) {
                throw new MotifUploadException('Error file upload');
            }

            $source = $uploadDir . $tempName . $extension;
            $target = $uploadDir . 'converted_' . $account_id . "_" . $return['time'] . $extension;
            $return['name']  = 'converted_' . $account_id . "_" . $return['time'];

            copy($source, $target);

            $cmd = "identify " . $uploadDir . $tempName . $extension;
            $image_identify = exec($cmd);
            $image_data_arr = explode(" ", $image_identify);
            if (count($image_data_arr) >= 6) {
                $image_format = $image_data_arr[5];
            } else {
                $image_format = "";
            }


            error_log($cmd . "\r\n", 3, "./image_data.log");
            exec($cmd);

            if (file_exists($uploadDir . $converted_name . '-0.jpg')) {
                rename($uploadDir . $converted_name . '-0.jpg', $uploadDir . $converted_name . '.jpg');
            }

            $width = 120;
            $aspect = 1;
            if (file_exists($uploadDir . $converted_name . ".jpg")) {
                list($width, $height, $type, $attr) = getimagesize($uploadDir . $converted_name . ".jpg");
                if ((int)$height > 0) {
                    $aspect = (int)$width / (int)$height;
                }
            }

            $return['width'] = $width;
            $return['aspect'] = $aspect;

            $cmd = 'convert ' . $uploadDir . $tempName . $extension . ' -resize ' . (int)($aspect * NEWHEIGHT_S) . 'x' . NEWHEIGHT_S . ' ' . $uploadDir . $converted_name . '_thumb.jpg';
            $cmd = str_replace(",", ".", $cmd);
            error_log($cmd . "\r\n", 3, "./image_data.log");
            exec($cmd);

            /*
            $cmd = 'convert ' . $uploadDir . $converted_name . '_thumb.jpg ' . $uploadDir . $converted_name . '.png';
            error_log($cmd . "\r\n", 3, "./image_data.log");
            exec($cmd);
			*/

            if (file_exists($uploadDir . $converted_name . '_thumb-0.jpg')) {
                rename($uploadDir . $converted_name . '_thumb-0.jpg', $uploadDir . $converted_name . '_thumb.jpg');
            }

            if ((!file_exists($uploadDir . $converted_name . '_thumb.jpg')) || (filesize($uploadDir . $converted_name . '_thumb.jpg') == 0)) {
                $cmd = "ffmpeg -i " . $uploadDir . $tempName . $extension . " -vf scale=" . (int)($aspect * NEWHEIGHT_S) . ":" . NEWHEIGHT_S . ' ' . $uploadDir . $converted_name . '_thumb.jpg';
                $cmd = str_replace(",", ".", $cmd);
                exec($cmd);
            }

            unlink($uploadDir . $tempName . $extension);

            $return['extension'] = $extension;

            return $return;
        } catch (MotifUploadException $e) {
            $this->_error .= error::buildErrorString(['ERROR' => $e->getMessage()]);
            return null;
        } catch (Exception $e) {
            $this->_error .= error::buildErrorString(['ERROR' => $e->getMessage()]);
            return null;
        }
    }

    protected function moveAndModifyFilm($account_id, $files) {
        try {
            $return = array(
                'name'         => '',
                'animated'     => 1,
                'frames_count' => 1,
                'width'        => 0,
                'aspect'       => 1,
                'time'         => 0,
                'extension'    => ''
            );

            $uploadDir 	= UPLOAD_DIR . '/'.$account_id.'/temp/';
            if (!is_dir($uploadDir)) {
                if (!mkdir(UPLOAD_DIR . '/'.$account_id) || !mkdir($uploadDir)) {
                    throw new MotifUploadException('Error directory create');
                }
            }

            $filename = $files['Filedata']['name'];
            $extension = "." . pathinfo($filename, PATHINFO_EXTENSION);
            $return['time'] = time();
            $tempName = 'uploaded_' . $account_id . "_" . $return['time'];
            $return['name'] = $tempName;
            $return['extension'] = $extension;

            if (!move_uploaded_file($files['Filedata']['tmp_name'], $uploadDir . $tempName . $extension)) {
                throw new MotifUploadException('Error file upload');
            }
            return $return;

        } catch (MotifUploadException $e) {
            $this->_error .= error::buildErrorString(['ERROR' => $e->getMessage()]);
            return null;
        } catch (Exception $e) {
            $this->_error .= error::buildErrorString(['ERROR' => $e->getMessage()]);
            return null;
        }
    }
	
	/**
	 * getMotifListByAccount
	 * 
	  * @param array $valueArray
	 *
	 * @return result-set or null
	 */
	public function getMotifListByAccount($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getMotifListByAccount']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
						
			$sql = "select"
					." motif.motif_id"
					.", motif.account_id"
					.", motif.media_format_id"
					.", motif.name AS motif_name"
					.", motif.frames_count"
					.", motif.width"
					.", motif.aspect"
					.", motif.path"
					.", motif.description"
					.", motif.orig_filename"
					.", motif.creation_time"
					.", motif.text_content"
					.", media_format.extension"
					.", media_format.format_type"
					.", media_format.name AS media_format_name"
					." from motif, media_format"
					." where media_format.media_format_id = motif.media_format_id"
					." and delete_ts IS NULL"
					." and UNIX_TIMESTAMP(creation_time) >= ".$valueArr['min_date'];
					
					if (isset($valueArr['accountID']))
						$sql .= " and motif.account_id = ".(int)$valueArr['accountID'];
															
					$sql .= " order by motif.creation_time;";
					
					echo "<SQL>".$sql."</SQL>";
					
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getMotifListByAccount'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}	
} 
?>