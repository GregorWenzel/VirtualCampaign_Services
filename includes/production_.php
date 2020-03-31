<?php
require_once('represent.php');

if (!class_exists('production', false)) {
    require 'classes/production.class.php';
}

$valueArr = '';
$account_id = null;
$production_id = null;

if (isset($_POST['userID'])) {
    $account_id = (int)$_POST['userID'];
}

if (isset($_POST['productionID'])) {
    $production_id = (int)$_POST['productionID'];
}

function getFilmFiles($account_id, $film_id) {
    $source = SOURCE_PATH . 'accounts/' . $account_id . '/productions/' . $film_id;
    $result = ["Source" => $source];
    if (is_dir($source)) {
        $all_files = array_diff(scandir($source), array("..", "."));
    } else {
        $all_files = [];
    }

    $result["Files"] = [];

    foreach ($all_files as $file) {
        $fileData = ["Filename" => $file];
        if (strpos($file, "preview") === false) {
            $fileData["File"] = [
                "Name" => $file,
                "Size" => filesize($source . "/" . $file)
            ];
        }
        $result["Files"][] = $fileData;
    }

    return $result;
}

$production = new production($mysqli, $account_id, $production_id);
$requestResult = null;
$requestError = null;

switch ($method) {
    case 'updateProductionPriority':
        $requestResult = ['SUCCESS' => $production->updateProductionPriority($_POST)];
        break;
    case 'updateProduction':
        /*
        $output = '<SUCCESS>' . $production->updateProduction($_POST) . '</SUCCESS>';
        echo $output;
        */
        $requestResult = ['SUCCESS' => $production->updateProduction($_POST)];
        break;
    case 'updateJob':
        /*
        $output = '<SUCCESS>' . $production->updateJob($_POST) . '</SUCCESS>';
        echo $output;
        */
        $requestResult = ['SUCCESS' => $production->updateJob($_POST)];
        break;
    case 'updateFilm':
        /*
        $output = '<SUCCESS>' . $production->updateFilm($_POST) . '</SUCCESS>';
        echo $output;
        */
        $requestResult = ['SUCCESS' => $production->updateFilm($_POST)];
        break;
    case 'migrateFilm':
        /*
        if ($arr = $production->migrateFilm($_POST)) {
            $output = '';
            $output .= '<ID>' . $production->_film_id . '</ID>';
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */
        if ($arr = $production->migrateFilm($_POST)) {
            $requestResult = ["ID" => $production->_film_id];
        }
        break;
    case 'deleteFilm':
        /*
        if ($arr = $production->deleteFilm($_POST)) {
            $output = '';
            $output .= '<ID>' . $arr . '</ID>';
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */
        if ($arr = $production->deleteFilm($_POST)) {
            $requestResult = ["ID" => $arr];
        }
        break;
    case 'createSingleProduction':
        /*
        if ($arr = $production->createSingleProduction($_POST)) {
            $output = '';
            $output .= '<ProductionID>' . $production->_production_id . '</ProductionID>';
            $output .= '<FilmID>' . $production->_film_id . '</FilmID>';
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */
        if ($arr = $production->createSingleProduction($_POST)) {
            $requestResult = [
                "ProductionID" => $production->_production_id,
                "FilmID"       => $production->_film_id
            ];
        }
        break;
    case 'createFilmProduction':
        /* POST-Vars:
         * int accountID (for TB production, film)
         * array films (each film one entry, each encoded as follows: urlencode(json_encode($arrFilms));)
         * - int audioID
         * - string name
         * - string description
                     //* - string flv_url		//TODO: noch prüfen... wird nach 'generierung' erst gesetzt, oder?
                     //* - string thumbnail_url	//TODO: noch prüfen... wird nach 'generierung' erst gesetzt, oder?
                     //* - int duration			//TODO: noch prüfen... wird nach 'generierung' erst gesetzt, oder?
         * - int preview
         * - string productIDs (comma-separated, e.g. '4,5,6'
         * - string motifIDs (point-separated, e.g. '1.2.3.4'
         * - string audioID (1:1)
         */
        /*
        if ($production->createFilmProduction($_POST)) {
            echo '<Saved>1</Saved>';
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($production->createFilmProduction($_POST)) {
            $requestResult = ["Saved" => 1];
        }
        break;
    case 'getProductionListByAccount':
        /*
        if($res = $production->getProductionListByAccount($_POST)) {
			$output= '';
			$output .= "<Films><Film>0</Film>";
			while ($ds = mysqli_fetch_array($res)) {
     			$output .= "<Film>";													//TB film
	     			$output .= "<ID>" . $ds['film_id'] . "</ID>";
	     			$output .= "<jobID>" . $ds['job_id'] . "</jobID>";
	     			$output .= "<Owner>" . $ds['account_id'] . "</Owner>";
	     			$output .= "<Name>" . $ds['name'] . "</Name>";
	     			$output .= "<Date>" . $ds['creation_time'] . "</Date>";
	     			$output .= "<Duration>" . $ds['duration'] . "</Duration>";
	     			$output .= "<Status>" . $ds['production_status'] . "</Status>";		//production.status	//TODO: oder hier job.status???
	     			$output .= "<Path>" . $ds['flv_url'] . "</Path>";
	     			$output .= "<codecFormats>" . $ds['codec_types_string'] . "</codecFormats>";	//xref-concat, e.g. WMV:DVD+WMV:WEB
	     			$output .= "<Motifs>" . $ds['motif_ids_string'] . "</Motifs>";					//old: films.motifIDs, e.g. 1.2.3.4
	     			$output .= "<Categories>" . $ds['product_group_ids_string'] . "</Categories>";	//old: films.products, e.g. 1,2,3,4
	     			$output .= "<size>" . $ds['sizes_string'] . "</size>";							//old:  films.sizeKB, e.g. 12345+12345+12345
	     			$output .= "<Audio>" . $ds['audio_id'] . "</Audio>";
	     			$output .= "<Preview>" . $ds['preview'] . "</Preview>";
					$output .= "<Priority>". $ds['priority'] . "</Priority>";
     			$output .= "</Film>";
   			}//while
		   	$output .= "</Films>";
			echo $output;
		} else  {
			echo '<ERROR>' . $production->_error . '</ERROR>';
		};
         */

        if ($res = $production->getProductionListByAccount($_POST)) {
            $requestResult = [];
            $requestResult['Films']['Film'] = [];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult['Films']['Film'][] = (object)[
                    'ID'           => $ds['film_id'],
                    'jobID'        => $ds['job_id'],
                    'Owner'        => $ds['account_id'],
                    'Name'         => $ds['name'],
                    'Date'         => $ds['creation_time'],
                    'Duration'     => $ds['duration'],
                    'Status'       => $ds['production_status'],
                    'Path'         => $ds['flv_url'],
                    'codecFormats' => $ds['codec_types_string'],
                    'Motifs'       => $ds['motif_ids_string'],
                    'Categories'   => $ds['product_group_ids_string'],
                    'size'         => $ds['sizes_string'],
                    'Audio'        => $ds['audio_id'],
                    'Preview'      => $ds['preview'],
                    'Priority'     => $ds['priority'],
                ];
            }
        }
        break;
    case 'getFilmsByAccount':
        /* POST-Vars:
         * int filmID
         * int accountID
         */
        /*
        if($res = $production->getFilmsByAccount($_POST)) {
			$output= '';
			while ($ds = mysqli_fetch_array($res)) {
     			$output .= "<Film>";													//TB film
	     			$output .= "<ID>" . $ds['film_id'] . "</ID>";
	     			$output .= "<jobID>" . $ds['production_id'] . "</jobID>";
	     			$output .= "<Owner>" . $ds['account_id'] . "</Owner>";
	     			$output .= "<Name>" . $ds['name'] . "</Name>";
	     			$output .= "<Date>" . strtotime($ds['creation_time']) . "</Date>";
	     			$output .= "<Duration>" . $ds['duration'] . "</Duration>";
					$output .= "<codecFormats>" . $ds['custom3'] . "</codecFormats>";
					$output .= "<size>" . $ds['size'] . "</size>";
					$output .= "<Products>" . $ds['custom1'] . "</Products>";
					$output .= "<Motifs>" . $ds['custom2'] . "</Motifs>";
                    $output .= getFilmFiles($ds['account_id'], $ds['film_id']);
                    $output .= "</Film>";
            }//while
            echo "<Films>".$output."</Films>";
            } else  {
                echo '<ERROR>' . $production->_error . '</ERROR>';
            };
        */

        if ($res = $production->getFilmsByAccount($_POST)) {
            $requestResult = [];
            $requestResult['Films']['Film'] = [];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult['Films']['Film'][] = (object)[
                    'ID'           => $ds['film_id'],
                    'jobID'        => $ds['production_id'],
                    'Owner'        => $ds['account_id'],
                    'Name'         => $ds['name'],
                    'UrlHash'      => $ds['url_hash'],
                    'Date'         => strtotime($ds['creation_time']),
                    'Duration'     => $ds['duration'],
                    'codecFormats' => $ds['custom3'],
                    'size'         => $ds['size'],
                    'Products'     => $ds['custom1'],
                    'Motifs'       => $ds['custom2'],
                    'Sources'      => getFilmFiles($ds['account_id'], $ds['film_id'])
                ];
            };
        }
        $requestError = $production->_error;
        break;
    case 'getProductList':
        /*
        if ($res = $production->getProductList()) {
            $output = '<Products>';
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Product>";
                $output .= "<ID>" . $ds['product_id'] . "</ID>";
                $output .= "<ProductGroupId>" . $ds['product_group_id'] . "</ProductGroupId>";
                $output .= "<ProductFormatId>" . $ds['product_format_id'] . "</ProductFormatId>";
                $output .= "<ProductName>" . $ds['product_name'] . "</ProductName>";
                $output .= "<ProductDescripton>" . $ds['product_descr'] . "</ProductDescripton>";
                $output .= "<ProductLocation>" . $ds['product_location'] . "</ProductLocation>";
                $output .= "<ProductCreationTime>" . $ds['product_creation_time'] . "</ProductCreationTime>";
                $output .= "<ProductRenderTime>" . $ds['product_render_time'] . "</ProductRenderTime>";
                $output .= "<ParentGroupId>" . $ds['parent_group_id'] . "</ParentGroupId>";
                $output .= "<ProductGroupName>" . $ds['product_group_name'] . "</ProductGroupName>";
                $output .= "<ProductFormatName>" . $ds['product_format_name'] . "</ProductFormatName>";
                $output .= "<ProductFormatWidth>" . $ds['product_format_width'] . "</ProductFormatWidth>";
                $output .= "<ProductFormatHeight>" . $ds['product_format_height'] . "</ProductFormatHeight>";
                $output .= "</Product>";
            }//while
            $output .= "</Products>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */
        $requestResult = ['Products' => [
            'Product' => [],
        ]];
        if ($res = $production->getProductList()) {
            $requestResult['Products']['Product'][] = [
                "ID"                  => $ds['product_id'],
                "ProductGroupId"      => $ds['product_group_id'],
                "ProductFormatId"     => $ds['product_format_id'],
                "ProductName"         => $ds['product_name'],
                "ProductDescripton"   => $ds['product_descr'],
                "ProductLocation"     => $ds['product_location'],
                "ProductCreationTime" => $ds['product_creation_time'],
                "ProductRenderTime"   => $ds['product_render_time'],
                "ParentGroupId"       => $ds['parent_group_id'],
                "ProductGroupName"    => $ds['product_group_name'],
                "ProductFormatName"   => $ds['product_format_name'],
                "ProductFormatWidth"  => $ds['product_format_width'],
                "ProductFormatHeight" => $ds['product_format_height'],
            ];
        }
        break;
    case 'getProductContentFormatList':
        /*
        if ($res = $production->getProductContentFormatList()) {
            $output = '<ProductContentFormats>';
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<ProductContentFormat>";
                $output .= "<ID>" . $ds['content_format_id'] . "</ID>";
                $output .= "<ContentFormatName>" . $ds['content_format_name'] . "</ContentFormatName>";
                $output .= "<ContentFormatAspect>" . $ds['content_format_aspect'] . "</ContentFormatAspect>";
                $output .= "<ContentFormatWidth>" . $ds['content_format_width'] . "</ContentFormatWidth>";
                $output .= "<ContentFormatHeight>" . $ds['content_format_height'] . "</ContentFormatHeight>";
                $output .= "</ProductContentFormat>";
            } //While
            $output .= "</ProductContentFormats>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getProductContentFormatList()) {
            $requestResult = ['ProductContentFormats' => [
                'ProductContentFormat' => [],
            ]];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult['ProductContentFormats']['ProductContentFormat'][] = [
                    "ID"                  => $ds['content_format_id'],
                    "ContentFormatName"   => $ds['content_format_name'],
                    "ContentFormatAspect" => $ds['content_format_aspect'],
                    "ContentFormatWidth"  => $ds['content_format_width'],
                    "ContentFormatHeight" => $ds['content_format_height'],
                ];
            }
        }

        break;
    case 'getProductGroupList':
        /*
        if ($res = $production->getProductGroupList()) {
            $output = '<ProductGroups>';
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<ProductGroup>";
                $output .= "<ID>" . $ds['product_group_id'] . "</ID>";
                $output .= "<ProductGroupParentID>" . $ds['product_parent_group_id'] . "</ProductGroupParentID>";
                $output .= "<ProductGroupName>" . $ds['product_group_name'] . "</ProductGroupName>";
                $output .= "</ProductGroup>";
            } //While
            $output .= "</ProductGroups>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getProductGroupList()) {
            $result = [];
            while ($ds = mysqli_fetch_array($res)) {
                $result[] = [
                    "ID"                   => $ds['product_group_id'],
                    "ProductGroupParentID" => $ds['product_parent_group_id'],
                    "ProductGroupName"     => $ds['product_group_name'],
                ];
            }
            $requestResult = ['ProductGroups' => [
                'ProductGroup' => $result,
            ]];
        }
        break;
    case 'getJobStatus':
        /*
        if ($res = $production->getJobStatus($_POST)) {
            $ds = $res->fetch_array();
            echo "<Result>";
            echo "<StatusID>" . $ds['status'] . "</StatusID>";
            echo "</Result>";
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */
        if ($res = $production->getJobStatus($_POST)) {
            $ds = $res->fetch_array();
            $requestResult = ["Result" => [
                "StatusID" => $ds['status'],
            ]];
        }

        break;
    case 'setJobStatus':
        /*
        if ($res = $production->setJobStatus($_POST)) {
            echo "<Result>1</Result>";
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */
        if ($res = $production->setJobStatus($_POST)) {
            $requestResult = ["Result" => 1];
        }
        break;
    case 'setJobErrorStatus':
        /*
        if ($res = $production->setJobErrorStatus($_POST)) {
            echo "<Result>1</Result>";
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */
        if ($res = $production->setJobErrorStatus($_POST)) {
            $requestResult = ["Result" => 1];
        }
        break;
    case 'createProduct':
        /*
        if ($res = $production->createProduct($_POST)) {
            echo '<ID>' . $res . '</ID>';
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */
        if ($res = $production->createProduct($_POST)) {
            $requestResult = ["ID" => $res];
        }
        break;
    case 'setProductOwnership':
        /*
        if ($res = $production->setProductOwnership($_POST))
            return true;
        else
            return false;
        */
        if ($res = $production->setProductOwnership($_POST))
            $requestResult = true;
        break;
    case 'getAudioById':
        /*
        if ($res = $production->getAudioById($_POST)) {
            $ds = mysqli_fetch_array($res);
            $output = "<Audio>";
            $output .= "<FileName>" . $ds['name'] . "</FileName>";
            $output .= "<FileExtension>" . $ds['extension'] . "</FileExtension>";
            $output .= "</Audio>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getAudioById($_POST)) {
            $ds = mysqli_fetch_array($res);
            $requestResult = ["Audio" => [
                "FileName"      => $ds['name'],
                "FileExtension" => $ds['extension']
            ]];
        }
        break;
    case 'getOpenMotifList':
        /*
        if ($res = $production->getOpenMotifList()) {
            $output = "<Motifs>";
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Motif>";
                $output .= "<ID>" . $ds['motif_id'] . "</ID>";
                $output .= "<AccountID>" . $ds['account_id'] . "</AccountID>";
                $output .= "<Extension>" . $ds['extension'] . "</Extension>";
                $output .= "</Motif>";
            }
            $output .= "</Motifs>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getOpenMotifList()) {
            $result = [];
            while ($ds = mysqli_fetch_array($res)) {
                $result[] = [
                    "ID"        => $ds['motif_id'],
                    "AccountID" => $ds['account_id'],
                    "Extension" => $ds['extension'],
                ];
            }
            $requestResult['Motifs']['Motif'] = $result;
        }

        break;
    case 'getOpenProductionList':
        /*
        if ($res = $production->getOpenProductionList()) {
            $output = "<Productions>";
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Production>";
                $output .= "<ID>" . $ds['production_id'] . "</ID>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<JobCount>" . $ds['jobcount'] . "</JobCount>";
                $output .= "<CreationTime>" . strtotime($ds['create_ts']) . "</CreationTime>";
                $output .= "<UpdateTime>" . strtotime($ds['production_update_ts']) . "</UpdateTime>";
                $output .= "<Status>" . $ds['status'] . "</Status>";
                $output .= "<ErrorCode>" . $ds['error_code'] . "</ErrorCode>";
                $output .= "<AccountID>" . $ds['account_id'] . "</AccountID>";
                $output .= "<IndicativeID>" . $ds['indicative'] . "</IndicativeID>";
                $output .= "<AbdicativeID>" . $ds['abdicative'] . "</AbdicativeID>";
                $output .= "<AudioID>" . $ds['audio_id'] . "</AudioID>";
                $output .= "<SpecialIntroMusic>" . $ds['special_intro_music'] . "</SpecialIntroMusic>";
                $output .= "<FilmID>" . $ds['film_film_id'] . "</FilmID>";
                $output .= "<FilmCodes>" . $ds['custom3'] . "</FilmCodes>";
                $output .= "<UserName>" . $ds['username'] . "</UserName>";
                $output .= "<Priority>" . $ds['priority'] . "</Priority>";
                $output .= "<Email>" . $ds['email'] . "</Email>";
                $totalFrameCount = $ds['total_framecount'];
                $frameCounts = explode(".", $totalFrameCount);
                $output .= "<IndicativeFrameCount>" . $frameCounts[0] . "</IndicativeFrameCount>";
                $output .= "<AbdicativeFrameCount>" . $frameCounts[2] . "</AbdicativeFrameCount>";
                $output .= "<ClipFrameCount>" . $frameCounts[1] . "</ClipFrameCount>";

                $output .= "</Production>";
            }
            $output .= "</Productions>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getOpenProductionList()) {
            $result = [];
            while ($ds = mysqli_fetch_array($res)) {
                $totalFrameCount = $ds['total_framecount'];
                $frameCounts = explode(".", $totalFrameCount);

                $result[] = [
                    "ID"                   => $ds['production_id'],
                    "Name"                 => $ds['name'],
                    "JobCount"             => $ds['jobcount'],
                    "CreationTime"         => strtotime($ds['create_ts']),
                    "UpdateTime"           => strtotime($ds['production_update_ts']),
                    "Status"               => $ds['status'],
                    "ErrorCode"            => $ds['error_code'],
                    "AccountID"            => $ds['account_id'],
                    "IndicativeID"         => $ds['indicative'],
                    "AbdicativeID"         => $ds['abdicative'],
                    "AudioID"              => $ds['audio_id'],
                    "SpecialIntroMusic"    => $ds['special_intro_music'],
                    "FilmID"               => $ds['film_film_id'],
                    "FilmCodes"            => $ds['custom3'],
                    "FilmUrlHash"          => $ds['url_hash'],
                    "UserName"             => $ds['username'],
                    "Priority"             => $ds['priority'],
                    "Email"                => $ds['email'],
                    "IndicativeFrameCount" => $frameCounts[0],
                    "AbdicativeFrameCount" => $frameCounts[2],
                    "ClipFrameCount"       => $frameCounts[1],
                ];
            }

            $requestResult = ["Productions" => [
                "Production" => $result,
            ]];
        }

        break;
    case 'getFinishedProductionList':
        /*
        if ($res = $production->getFinishedProductionList()) {
            $output = "<Productions>";
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Production>";
                $output .= "<ID>" . $ds['production_id'] . "</ID>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<JobCount>" . $ds['jobcount'] . "</JobCount>";
                $output .= "<CreationTime>" . strtotime($ds['creation_time']) . "</CreationTime>";
                $output .= "<UpdateTime>" . strtotime($ds['production_update_ts']) . "</UpdateTime>";
                $output .= "<Status>" . $ds['status'] . "</Status>";
                $output .= "<ErrorCode>" . $ds['error_code'] . "</ErrorCode>";
                $output .= "<AccountID>" . $ds['account_id'] . "</AccountID>";
                $output .= "<IndicativeID>" . $ds['indicative'] . "</IndicativeID>";
                $output .= "<AbdicativeID>" . $ds['abdicative'] . "</AbdicativeID>";
                $output .= "<AudioID>" . $ds['audio_id'] . "</AudioID>";
                $output .= "<SpecialIntroMusic>" . $ds['special_intro_music'] . "</SpecialIntroMusic>";
                $output .= "<FilmID>" . $ds['film_film_id'] . "</FilmID>";
                $output .= "<FilmCodes>" . $ds['custom3'] . "</FilmCodes>";
                $output .= "<UserName>" . $ds['username'] . "</UserName>";
                $output .= "<Priority>" . $ds['priority'] . "</Priority>";
                $totalFrameCount = $ds['total_framecount'];
                $frameCounts = explode(".", $totalFrameCount);
                $output .= "<IndicativeFrameCount>" . $frameCounts[0] . "</IndicativeFrameCount>";
                $output .= "<AbdicativeFrameCount>" . $frameCounts[2] . "</AbdicativeFrameCount>";
                $output .= "<ClipFrameCount>" . $frameCounts[1] . "</ClipFrameCount>";

                $output .= "</Production>";
            }
            $output .= "</Productions>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getFinishedProductionList()) {
            $result = [];
            while ($ds = mysqli_fetch_array($res)) {
                $totalFrameCount = $ds['total_framecount'];
                $frameCounts = explode(".", $totalFrameCount);

                $result[] = [
                    "ID"                   => $ds['production_id'],
                    "Name"                 => $ds['name'],
                    "JobCount"             => $ds['jobcount'],
                    "CreationTime"         => strtotime($ds['creation_time']),
                    "UpdateTime"           => strtotime($ds['production_update_ts']),
                    "Status"               => $ds['status'],
                    "ErrorCode"            => $ds['error_code'],
                    "AccountID"            => $ds['account_id'],
                    "IndicativeID"         => $ds['indicative'],
                    "AbdicativeID"         => $ds['abdicative'],
                    "AudioID"              => $ds['audio_id'],
                    "SpecialIntroMusic"    => $ds['special_intro_music'],
                    "FilmID"               => $ds['film_film_id'],
                    "FilmCodes"            => $ds['custom3'],
                    "UserName"             => $ds['username'],
                    "Priority"             => $ds['priority'],
                    "IndicativeFrameCount" => $frameCounts[0],
                    "AbdicativeFrameCount" => $frameCounts[2],
                    "ClipFrameCount"       => $frameCounts[1],
                ];
            }

            $requestResult = ["Productions" => [
                "Production" => $result,
            ]];
        }

        break;
    case 'getJobsByProductionID':
        /*
        if ($res = $production->getJobsByProductionID($_POST)) {
            $output = "<Jobs>";
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Job>";
                $output .= "<ID>" . $ds['job_id'] . "</ID>";
                $output .= "<Status>" . $ds['status'] . "</Status>";
                $output .= "<CreationTime>" . strtotime($ds['job_create_ts']) . "</CreationTime>";
                $output .= "<UpdateTime>" . strtotime($ds['job_update_ts']) . "</UpdateTime>";
                $output .= "<ErrorCode>" . $ds['error_code'] . "</ErrorCode>";
                $output .= "<Position>" . $ds['job_position'] . "</Position>";
                $output .= "<ProductID>" . $ds['product_product_id'] . "</ProductID>";
                $output .= "<IsDicative>" . $ds['is_dicative'] . "</IsDicative>";
                $output .= "<ProductFrames>" . $ds['frame_count'] . "</ProductFrames>";
                $output .= "<PreviewFrame>" . $ds['previewFrame'] . "</PreviewFrame>";
                $output .= "<ContentID>" . $ds['real_content_id'] . "</ContentID>";
                $output .= "<ContentPosition>" . $ds['content_position'] . "</ContentPosition>";
                $output .= "<ContentType>" . $ds['content_type'] . "</ContentType>";
                $output .= "<ContentText>" . $ds['content_text'] . "</ContentText>";
                $output .= "<ContentExtension>" . $ds['extension'] . "</ContentExtension>";
                $output .= "<ContentLoaderName>" . $ds['loader_name'] . "</ContentLoaderName>";
                $output .= "<OutputExtension>" . $ds['output_extension'] . "</OutputExtension>";
                $output .= "<RenderID>" . $ds['render_id'] . "</RenderID>";
                $output .= "<AccountID>" . $ds['account_account_id'] . "</AccountID>";
                $output .= "<ProductionID>" . $ds['production_production_id'] . "</ProductionID>";
                $output .= "</Job>";
            }

            if ($res = $production->getDicativeJobsByProductionID($_POST)) {
                while ($ds = mysqli_fetch_array($res)) {
                    $output .= "<Job>";
                    $output .= "<ID>" . $ds['job_job_id'] . "</ID>";
                    $output .= "<Status>10</Status>";
                    $output .= "<CreationTime>" . strtotime($ds['create_ts']) . "</CreationTime>";
                    $output .= "<UpdateTime>" . strtotime($ds['update_ts']) . "</UpdateTime>";
                    $output .= "<ErrorCode>0</ErrorCode>";
                    $output .= "<Position>" . $ds['job_position'] . "</Position>";
                    $output .= "<ProductID>" . $ds['product_product_id'] . "</ProductID>";
                    $output .= "<IsDicative>1</IsDicative>";
                    $output .= "<ProductFrames>" . $ds['frame_count'] . "</ProductFrames>";
                    $output .= "<AccountID>" . $ds['account_account_id'] . "</AccountID>";
                    $output .= "<ProductionID>" . $ds['production_production_id'] . "</ProductionID>";
                    $output .= "<ContentExtension></ContentExtension>";
                    $output .= "<OutputExtension></OutputExtension>";

                    $output .= "</Job>";
                }
            } else {
                //echo '<Job>' . $production->_error . '</Job>';
            }
            $output .= "</Jobs>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getJobsByProductionID($_POST)) {
            $requestResult = ["Jobs" => [
                "Job" => []
            ]];

            while ($ds = mysqli_fetch_array($res)) {
                $requestResult['Jobs']['Job'][] = [
                    "ID"                => $ds['job_id'],
                    "Status"            => $ds['status'],
                    "CreationTime"      => strtotime($ds['job_create_ts']),
                    "UpdateTime"        => strtotime($ds['job_update_ts']),
                    "ErrorCode"         => $ds['error_code'],
                    "Position"          => $ds['job_position'],
                    "ProductID"         => $ds['product_product_id'],
                    "IsDicative"        => $ds['is_dicative'],
                    "ProductFrames"     => $ds['frame_count'],
                    "PreviewFrame"      => $ds['previewFrame'],
                    "ContentID"         => $ds['real_content_id'],
                    "ContentPosition"   => $ds['content_position'],
                    "ContentType"       => $ds['content_type'],
                    "ContentText"       => $ds['content_text'],
                    "ContentExtension"  => $ds['extension'],
                    "ContentLoaderName" => $ds['loader_name'],
                    "OutputExtension"   => $ds['output_extension'],
                    "RenderID"          => $ds['render_id'],
                    "AccountID"         => $ds['account_account_id'],
                    "ProductionID"      => $ds['production_production_id'],
                ];
            }

            if ($res = $production->getDicativeJobsByProductionID($_POST)) {
                while ($ds = mysqli_fetch_array($res)) {
                    $requestResult['Jobs']['Job'][] = [
                        "ID"               => $ds['job_job_id'],
                        "Status"           => 10,
                        "CreationTime"     => strtotime($ds['create_ts']),
                        "UpdateTime"       => strtotime($ds['update_ts']),
                        "ErrorCode"        => 0,
                        "Position"         => $ds['job_position'],
                        "ProductID"        => $ds['product_product_id'],
                        "IsDicative"       => 1,
                        "ProductFrames"    => $ds['frame_count'],
                        "AccountID"        => $ds['account_account_id'],
                        "ProductionID"     => $ds['production_production_id'],
                        "ContentExtension" => null,
                        "OutputExtension"  => null,
                    ];
                }
            } else {
                $production->_error = null;
            }
        }


        break;
    case 'getDicativesByAccount':
        /*
        if ($res = $production->getDicativesByAccount($_POST)) {
            $output = '<Products>';
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Product>";
                $output .= "<ID>" . $ds['product_id'] . "</ID>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<CreationTime>" . strtotime($ds['update_ts']) . "</CreationTime>";
                $output .= "<GroupID>" . $ds['product_group_id'] . "</GroupID>";
                $output .= "<Description>" . $ds['description'] . "</Description>";
                $output .= "<Location>" . $ds['location'] . "</Location>";
                $output .= "<Price>" . $ds['price'] . "</Price>";
                $output .= "<Frames>" . $ds['frame_count'] . "</Frames>";
                $output .= "</Product>";
            }
            $output .= "</Products>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getDicativesByAccount($_POST)) {
            $result = [];
            while ($ds = mysqli_fetch_array($res)) {
                $result[] = [
                    "ID"           => $ds['product_id'],
                    "Name"         => $ds['name'],
                    "CreationTime" => strtotime($ds['update_ts']),
                    "GroupID"      => $ds['product_group_id'],
                    "Description"  => $ds['description'],
                    "Location"     => $ds['location'],
                    "Price"        => $ds['price'],
                    "Frames"       => $ds['frame_count'],
                ];
            }
            $requestResult = ["Products" => [
                "Product" => $result,
            ]];
        }

        break;
    case 'getAudioList':
        /*
        if ($res = $production->getAudioList($_POST)) {
            $output = '<Audios>';
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Audio>";
                $output .= "<ID>" . $ds['audio_id'] . "</ID>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<MediaFormatID>" . $ds['media_format_id'] . "</MediaFormatID>";
                $output .= "<CreationTime>" . strtotime($ds['create_ts']) . "</CreationTime>";
                $output .= "<AccountID>" . $ds['account_id'] . "</AccountID>";
                $output .= "<AccountGroupIDs>";
                $res2 = $production->getGroupsByAudioId($ds['audio_id']);
                while ($ds2 = mysqli_fetch_array($res2)) {
                    $output .= "<AccountGroupID>" . $ds2['account_group_id'] . "</AccountGroupID>";
                }
                $output .= "</AccountGroupIDs>";
                $output .= "</Audio>";
            }
            $output .= "</Audios>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getAudioList($_POST)) {
            $result = [];
            while ($ds = mysqli_fetch_array($res)) {
                $accountGroupIDs = ["AccountGroupID" => []];
                $res2 = $production->getGroupsByAudioId($ds['audio_id']);
                while ($ds2 = mysqli_fetch_array($res2)) {
                    $accountGroupIDs["AccountGroupID"][] = $ds2['account_group_id'];
                }

                $result[] = [
                    "ID"              => $ds['audio_id'],
                    "Name"            => $ds['name'],
                    "MediaFormatID"   => $ds['media_format_id'],
                    "CreationTime"    => $ds['create_ts'],
                    "AccountID"       => $ds['account_id'],
                    "AccountGroupIDs" => $accountGroupIDs,
                ];
            }

            $requestResult = ["Audios" => [
                "Audio" => $result,
            ]];
        }

        break;
    case 'getContentTypeListByAccount':
        /*
        if ($res = $production->getContentTypeListByAccount($_POST)) {
            $output = '<ContentTypes>';

            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<ContentType>";
                $output .= "<ID>" . $ds['content_format_id'] . "</ID>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<Aspect>" . $ds['aspect'] . "</Aspect>";
                $output .= "<Position>" . $ds['position'] . "</Position>";
                $output .= "<LoaderName>" . $ds['loader_name'] . "</LoaderName>";
                $output .= "<ProductID>" . $ds['product_id'] . "</ProductID>";
                $output .= "<Content>" . $ds['real_content_type'] . "</Content>";
                $output .= "<CanLoop>" . $ds['can_loop'] . "</CanLoop>";
                $output .= "<AcceptsFilm>" . $ds['accepts_film'] . "</AcceptsFilm>";
                $output .= "</ContentType>";
            } //While
            $output .= "</ContentTypes>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        if ($res = $production->getContentTypeListByAccount($_POST)) {
            $result = [];
            while ($ds = mysqli_fetch_array($res)) {
                $result[] = [
                    "ID"          => $ds['content_format_id'],
                    "Name"        => $ds['name'],
                    "Aspect"      => $ds['aspect'],
                    "Position"    => $ds['position'],
                    "LoaderName"  => $ds['loader_name'],
                    "ProductID"   => $ds['product_id'],
                    "Content"     => $ds['real_content_type'],
                    "CanLoop"     => $ds['can_loop'],
                    "AcceptsFilm" => $ds['accepts_film'],
                ];
            }

            $requestResult = ["ContentTypes" => [
                "ContentType" => $result,
            ]];
        }

        break;
    case 'getShortProductListByAccount':
        /*
        if ($res = $production->getShortProductListByAccount($_POST)) {
            $output = '<Products>';

            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Product>";
                $output .= "<ID>" . $ds['product_id'] . "</ID>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<Description>" . $ds['description'] . "</Description>";
                $output .= "<Location>" . $ds['location'] . "</Location>";
                $output .= "<GroupID>" . $ds['product_group_id'] . "</GroupID>";
                $output .= "<Price>" . $ds['price'] . "</Price>";
                $output .= "<CreationTime>" . strtotime($ds['update_ts']) . "</CreationTime>";
                $output .= "<Frames>" . $ds['frame_count'] . "</Frames>";
                $output .= "<PreviewFrame>" . $ds['previewFrame'] . "</PreviewFrame>";
                $output .= "</Product>";
            } //While
            $output .= "</Products>";
            $output .= "<ContentFormats>" . implode('', $content_format_list) . "</ContentFormats>";
            $output .= "<CodecTypes>" . implode(',', $codec_type_list) . "</CodecTypes>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        $content_format_list = $production->getProductTypes();
        $codec_type_list = $production->getCodecTypes();
        if ($res = $production->getShortProductListByAccount($_POST)) {
            $result = [];
            while ($ds = mysqli_fetch_array($res)) {
                $result[] = [
                    "ID"           => $ds['product_id'],
                    "Name"         => $ds['name'],
                    "Description"  => $ds['description'],
                    "Location"     => $ds['location'],
                    "GroupID"      => $ds['product_group_id'],
                    "Price"        => $ds['price'],
                    "CreationTime" => strtotime($ds['update_ts']),
                    "Frames"       => $ds['frame_count'],
                    "PreviewFrame" => $ds['previewFrame'],
                    "AllowedCodecTypes" => $ds['allowed_codec_types'],
                    "ProductType" => $ds['product_type_id'],
                    "can_reformat" => $ds['can_reformat'],
                    "resolution" => $ds['resolution'],
                ];
            }

            $requestResult = [
                "Products" => [
                    "Product" => $result,
                ],
                "ContentFormats" => $content_format_list,
                "CodecTypes" => $codec_type_list,
            ];
        }

        break;
    case 'getProductListByAccount':
        /*
        if ($res = $production->getProductListByAccount($_POST)) {
            $output = '<Products>';
            $current_product_id = -1;

            while ($ds = mysqli_fetch_array($res)) {
                if ((int)$ds['product_id'] != $current_product_id) {
                    if ($current_product_id >= 0) {
                        $output .= "<ContentFormats>" . implode('', $content_format_list) . "</ContentFormats>";
                        $output .= "<CodecTypes>" . implode(',', $codec_type_list) . "</CodecTypes>";
                        $output .= "</Product>";
                    }
                    $current_product_id = $ds['product_id'];

                    $output .= "<Product>";
                    $output .= "<ID>" . $ds['product_id'] . "</ID>";
                    $output .= "<Name>" . $ds['product_name'] . "</Name>";
                    $output .= "<Description>" . $ds['description'] . "</Description>";
                    $output .= "<Location>" . $ds['location'] . "</Location>";
                    $output .= "<GroupID>" . $ds['product_group_id'] . "</GroupID>";
                    $output .= "<Price>" . $ds['price'] . "</Price>";
                    $output .= "<CreationTime>" . strtotime($ds['update_ts']) . "</CreationTime>";
                    $output .= "<Frames>" . $ds['frame_count'] . "</Frames>";
                    $output .= "<PreviewFrame>" . $ds['previewFrame'] . "</PreviewFrame>";

                    $codec_type_list = array();
                    $codec_type_list[] = (int)$ds['codec_type_codec_type_id'];

                    $content_format_list = array();
                    $content_position_list = array();
                    if ((int)$ds['content_format_id'] > 0) {
                        $content_position_list[] = (int)$ds['position'];

                        $content_format = "<ContentFormat>";
                        $content_format .= "<Position>" . $ds['position'] . "</Position>";
                        $content_format .= "<ID>" . $ds['content_format_id'] . "</ID>";
                        $content_format .= "<Name>" . $ds['format_name'] . "</Name>";
                        $content_format .= "<IsDicative>" . (int)$ds['is_dicative'] . "</IsDicative>";
                        $content_format .= "<AcceptsFilm>" . $ds['accepts_film'] . "</AcceptsFilm>";
                        $content_format .= "<CanLoop>" . $ds['can_loop'] . "</CanLoop>";
                        $content_format .= "<Aspect>" . $ds['aspect'] . "</Aspect>";
                        $content_format .= "<ContentAllowed>" . $ds['real_content_type'] . "</ContentAllowed>";
                        $content_format .= "<LoaderName>" . $ds['loader_name'] . "</LoaderName>";
                        $content_format .= "</ContentFormat>";
                        $content_format_list[] = $content_format;
                    }
                } else {
                    if (!in_array((int)$ds['position'], $content_position_list) && (int)$ds['content_format_id'] > 0) {
                        $content_format = "<ContentFormat>";
                        $content_format .= "<Position>" . $ds['position'] . "</Position>";
                        $content_format .= "<ID>" . $ds['content_format_id'] . "</ID>";
                        $content_format .= "<Name>" . $ds['format_name'] . "</Name>";
                        $content_format .= "<IsDicative>" . (int)$ds['is_dicative'] . "</IsDicative>";
                        $content_format .= "<AcceptsFilm>" . $ds['accepts_film'] . "</AcceptsFilm>";
                        $content_format .= "<CanLoop>" . $ds['can_loop'] . "</CanLoop>";
                        $content_format .= "<Aspect>" . $ds['aspect'] . "</Aspect>";
                        $content_format .= "<ContentAllowed>" . $ds['real_content_type'] . "</ContentAllowed>";
                        $content_format .= "<LoaderName>" . $ds['loader_name'] . "</LoaderName>";
                        $content_format .= "</ContentFormat>";
                        $content_format_list[] = $content_format;
                        $content_position_list[] = (int)$ds['position'];
                    } else if (!in_array((int)$ds['codec_type_codec_type_id'], $codec_type_list)) {
                        $codec_type_list[] = (int)$ds['codec_type_codec_type_id'];
                    }
                }
            } //While
            $output .= "<ContentFormats>" . implode('', $content_format_list) . "</ContentFormats>";
            $output .= "<CodecTypes>" . implode(',', $codec_type_list) . "</CodecTypes>";
            $output .= "</Product>";
            $output .= "</Products>";
            echo $output;
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */

        function addContentFormat($data, &$product) {
            if ((int)$data['content_format_id'] > 0) {
                $product["ContentFormats"]["ContentFormat"][] = [
                    "Position"       => $data['position'],
                    "ID"             => $data['content_format_id'],
                    "Name"           => $data['format_name'],
                    "IsDicative"     => (int)$data['is_dicative'],
                    "AcceptsFilm"    => $data['accepts_film'],
                    "CanLoop"        => $data['can_loop'],
                    "Aspect"         => $data['aspect'],
                    "ContentAllowed" => $data['real_content_type'],
                    "LoaderName"     => $data['loader_name'],
                ];
            }
        }

        if ($res = $production->getProductListByAccount($_POST)) {
            $result = ["Product" => []];
            $currentProduct = null;
            $codec_type_list = [];
            $content_format_list = [];
            while ($ds = mysqli_fetch_array($res)) {
                if (!$ds['product_id']) {
                    continue;
                }

                if ($currentProduct && $currentProduct["ID"] != $ds['product_id']) {
                    $currentProduct["CodecTypes"] = implode(',', array_unique($codec_type_list));
                    $result["Product"][] = $currentProduct;
                    $currentProduct = null;
                    $content_format_list = [];
                    $codec_type_list = [];
                }

                if (!$currentProduct) {
                    $currentProduct = [
                        "ID"             => $ds['product_id'],
                        "Name"           => $ds['product_name'],
                        "Description"    => $ds['description'],
                        "Location"       => $ds['location'],
                        "GroupID"        => $ds['product_group_id'],
                        "Price"          => $ds['price'],
                        "CreationTime"   => strtotime($ds['update_ts']),
                        "Frames"         => $ds['frame_count'],
                        "PreviewFrame"   => isset($ds['previewFrame']) ? $ds['previewFrame'] : null,
                        "ContentFormats" => ["ContentFormat" => []],
                    ];
                }

                if ($currentProduct["ID"] == $ds['product_id']) {
                    if ((int)$ds['content_format_id'] > 0 && !in_array($ds['position'], $content_format_list)) {
                        addContentFormat($ds, $currentProduct);
                        $content_format_list[] = $ds['position'];
                    }
                }

                if ((int)$ds['codec_type_codec_type_id']) {
                    $codec_type_list[] = (int)$ds['codec_type_codec_type_id'];
                }
            }
            $requestResult['Products'] = $result;
        }

        break;
    case 'checkProductionStatus':
        /*
        if ($res = $production->checkProductionStatus($_POST)) {
            $resultIDs = array();
            while ($ds = mysqli_fetch_array($res)) {
                array_push($resultIDs, 1);
            }

            echo "<Result>" . implode(",", $resultIDs) . "</Result>";
        } else {
            echo '<ERROR>' . $production->_error . '</ERROR>';
        };
        */
        if ($res = $production->checkProductionStatus($_POST)) {
            $resultIDs = array();
            while ($ds = mysqli_fetch_array($res)) {
                array_push($resultIDs, 1);
            }
            $requestResult = ['Result' => implode(",", $resultIDs)];
        }

        break;
    case 'checkProductID':
        /*
        if ($res = $production->checkProductID($_POST)) {
            $ds = mysqli_fetch_array($res);
            echo "<Result><Count>" . $ds['total'] . "</Count><LowestID>" . $ds['lowest'] . "</LowestID></Result>";
        } else
            echo '<ERROR>' . $production->_error . '</ERROR>';
        */
        if ($res = $production->checkProductID($_POST)) {
            $ds = mysqli_fetch_array($res);
            $requestResult = ["Result" => [
                "Count"    => $ds['total'],
                "LowestID" => $ds['lowest']
            ]];
        }
        break;
    case 'workOnProduct':
        if ($res = $production->workOnProduct($_POST)) {
            $requestResult = ["Result" => 1];
        }

        break;
    case 'workOnContentFormat':
        $production->workOnContentFormat($_POST);
        break;
    case 'deleteByAdmin':
        /*
        $delete_timestamp = $production->deleteByAdmin($_POST);
        echo "<DELETE_TS>" . $delete_timestamp . "</DELETE_TS>";
        */
        $delete_timestamp = $production->deleteByAdmin($_POST);
        $requestResult = ["DELETE_TS" => $delete_timestamp];
        break;
    case 'updateHistory':
        /*
        if ($res = $production->updateHistory($_POST))
            echo "<DONE>1</DONE>";
        else
            echo '<ERROR>' . $production->_error . '</ERROR>';
        */
        if ($res = $production->updateHistory($_POST)) {
            $requestResult = ["DONE" => 1];
        }

        break;
    case 'deleteProduction':
        $production->deleteProduction($_POST);
        $requestResult = ["DONE" => 1];
        break;
    case 'adjustHistory':
        $production->adjustHistory();
        $requestResult = ["DONE" => 1];
        break;
    default:
        break;
}

$requestError = $production->_error;
echo (new Represent($requestResult, $requestError))->getJson();

