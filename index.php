<?php
session_start();

//header('Access-Control-Allow-Origin: *');

require 'includes/config.php';
ob_start();

if (defined('DEBUG')) {
    function customError($errno, $errstr, $errfile, $errline) {
        require_once('vendor/rollbar/rollbar.php');
        $params = ['access_token' => ROLLBAR_TOKEN];
        if (isset($_SERVER['APPLICATION_ENV'])) {
            $params['environment'] = $_SERVER['APPLICATION_ENV'];
        }
        Rollbar::init($params, false, false, false);
        if (!(error_reporting() & $errno)) {
            return false;
        }
        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                Rollbar::report_message($errstr, Level::ERROR, ["file" => $errfile, 'line' => $errline]);
                break;
            case E_WARNING:
                Rollbar::report_message($errstr, Level::WARNING, ["file" => $errfile, 'line' => $errline]);
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                Rollbar::report_message($errstr, Level::INFO, ["file" => $errfile, 'line' => $errline]);
                break;
        }
        return true;
    }
    set_error_handler("customError");
}

$valid_request = false;
if (isset($_GET['pass']) && isset($_GET['call'])) {
    $check_pass = sha1(sha1($_GET['call']) . SALTED);
    if ($_GET['pass'] == $check_pass) {
        $valid_request = true;
    }
}

if ($valid_request) {
    $include_file = '';
    $method = '';
    if (isset($_GET['call'])) {
        $method = $_GET['call'];
        switch ($method) {
            case 'login':
            case 'logout':
            case 'createAccount':
            case 'updateAccount':
            case 'deleteAccount':
            case 'createAccountGroup':
            case 'updateAccountGroup':
            case 'deleteAccountGroup':
            case 'getAccountGroupList':
            case 'getAccountList':
            case 'getContentFormats':
            case 'getFileExtensions':
            case 'migrateStatisticItem':
            case 'getStatistics':
            case 'exportStatistics':
            case 'getProductAssociations':
            case 'getGroupAssociations':
            case 'workOnAccountGroup':
            case 'workOnAccount':
            case 'updateContentFormat':
            case 'adjustNamedObject':
                $include_file = 'account.php';
                break;
            case 'migrateMotif':
            case 'uploadMotif':
            case 'createMotif':
            case 'updateMotif':
            case 'updateMotifFrames':
            case 'deleteMotif':
            case 'readMotif':
            case 'saveMotif':
            case 'getMotifListByAccount':
                $include_file = 'motif.php';
                break;
            case 'updateProductionPriority':
            case 'updateProduction':
            case 'updateJob':
            case 'getOpenProductionList':
            case 'getFinishedProductionList':
            case 'getOpenMotifList':
            case 'getJobsByProductionID':
            case 'getDicativesByAccount':
            case 'migrateFilm':
            case 'getFilmsByAccount':
            case 'createSingleProduction':
            case 'getProductionListByAccount':
            case 'createFilmProduction':
            case 'createProduct':
            case 'loadFilmProductionByAccount':
            case 'getProductList':
            case 'getProductContentFormatList':
            case 'getProductGroupList':
            case 'getProductListByAccount':
            case 'getContentTypeListByAccount':
            case 'getShortProductListByAccount':
            case 'setProductOwnership':
            case 'deleteFilm':
            case 'updateFilm':
            case 'checkProductionStatus':
            case 'checkProductionStatusAdv':
            case 'workOnProduct':
            case 'deleteByAdmin':
            case 'workOnContentFormat':
            case 'updateHistory':
            case 'checkProductID':
            case 'deleteProduction':
            case 'adjustHistory':
            case 'getJobStatus':
            case 'setJobStatus':
            case 'setJobErrorStatus':
            case 'getCodecByFilmId':
            case 'getCodecTypes':
            case 'healHistory':
            case 'getOpenDicativeJobs':
            case 'getOpenJobs':
            case 'updateProductStatistics':
			case 'sendHeartbeat':
            case 'addSubProduct':
                $include_file = 'production.php';
                break;
            case 'getAudioById':
            case 'uploadAudio':
            case 'getAudioList':
                $include_file = 'audio.php';
                break;

            default:
                break;
        }
    }

    if ('' != $include_file) {
        $locale = DEFAULT_LOCALE;
        if (isset($_GET['locale']) && '' != $_GET['locale']) {
            $locale = $_GET['locale'];
        }

        require 'languages/locale.' . $locale . '.class.php';
        require 'classes/error.class.php';
        require 'classes/format.class.php';

        require 'classes/dbconn.class.php';
        $dbconn = new dbconn($db_arr, 'utf8');
        $mysqli = $dbconn->get_dbconn();

        require 'includes/' . $include_file;
        $dbconn->disconnect();
    } else {
        echo 'ERROR invalid call: Zugriff nicht erlaubt.';
    }
} else {
    echo 'ERROR invalid request: Zugriff nicht erlaubt.';
}