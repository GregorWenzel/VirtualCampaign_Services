<?php
require_once('represent.php');
if (!class_exists('audio', false)) {
    require 'classes/audio.class.php';
}
$valueArr = '';

$audio = new audio($mysqli);
$requestResult = null;
$requestError = null;

switch ($method) {
    case 'getAudioById':
        /*
        if ($res = $audio->getAudioById($_POST)) {
            $ds = mysqli_fetch_array($res);
            $output = "<Audio>";
            $output .= "<FileName>" . $ds['name'] . "</FileName>";
            $output .= "<FileExtension>" . $ds['extension'] . "</FileExtension>";
            $output .= "</Audio>";
            echo $output;
        } else {
            echo '<ERROR>' . $audio->_error . '</ERROR>';
        };
        */

        if ($res = $audio->getAudioById($_POST)) {
            $ds = mysqli_fetch_array($res);
            $requestResult = [
                "Audio" => [
                    "FileName"      => $ds['name'],
                    "FileExtension" => $ds['extension']
                ],
            ];
        }

        break;
    case 'getAudioList':
        /*
        if ($res = $audio->getAudioList($_POST)) {
            $output = '<Audios>';
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Audio>";
                $output .= "<ID>" . $ds['audio_id'] . "</ID>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<MediaFormatID>" . $ds['media_format_id'] . "</MediaFormatID>";
                $output .= "<CreationTime>" . strtotime($ds['create_ts']) . "</CreationTime>";
                $output .= "<AccountID>" . $ds['account_id'] . "</AccountID>";
                $output .= "<AccountGroupIDs>";
                $res2 = $audio->getGroupsByAudioId($ds['audio_id']);
                while ($ds2 = mysqli_fetch_array($res2)) {
                    $output .= "<AccountGroupID>" . $ds2['account_group_id'] . "</AccountGroupID>";
                }
                $output .= "</AccountGroupIDs>";
                $output .= "</Audio>";
            }
            $output .= "</Audios>";
            echo $output;
        } else {
            echo '<ERROR>' . $audio->_error . '</ERROR>';
        };
        */

        if ($res = $audio->getAudioList($_POST)) {
            $requestResult = [
                "Audios" => ["Audio" => []]
            ];
            while ($ds = mysqli_fetch_array($res)) {
                $accountGroupIDs = [];
                $res2 = $audio->getGroupsByAudioId($ds['audio_id']);
                while ($ds2 = mysqli_fetch_array($res2)) {
                    $accountGroupIDs[] = $ds2['account_group_id'];
                }

                $requestResult["Audios"]["Audio"][] = [
                    "ID" => $ds['audio_id'],
                    "Name" => $ds['name'],
                    "MediaFormatID" => $ds['media_format_id'],
                    "CreationTime" => strtotime($ds['create_ts']),
                    "AccountID" => $ds['account_id'],
                    "AccountGroupIDs" => $accountGroupIDs
                ];
            }
        }

        break;
    case 'uploadAudio':
        /*
        if ($arr = $audio->uploadAudio($_POST, $_FILES)) {
            echo $arr;
        } else {
            echo '<ERROR>' . $audio->_error . '</ERROR>';
        };
        */

        if ($arr = $audio->uploadAudio($_POST, $_FILES)) {
            $requestResult["Audio"] = $arr;
        }

        break;
    default:
        break;
}

$requestError = $audio->_error;
echo (new Represent($requestResult, $requestError))->getJson();