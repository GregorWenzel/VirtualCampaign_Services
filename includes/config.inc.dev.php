<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		config.inc.php
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
//TODO: für Live-Version dann umstellen
$_SESSION['sess_sysDebug'] = true;

if($_SESSION['sess_sysDebug']) {
	error_reporting(E_ALL);
} else {
	error_reporting(0);
}

define('STANDARD_MAILFROM','test@test.de');

date_default_timezone_set('Europe/Berlin');
define('DEFAULT_LOCALE','de_DE');
//setlocale(LC_MONETARY, DEFAULT_LOCALE);
setlocale(LC_ALL, DEFAULT_LOCALE);

define('BASE_MODULE','scripts');		// => http://virtualcampaign.ibt-studios.de/scripts
//define('BASE_MODULE',''); // for 'direct' domain (no subfolders)

$http='http://';
//TODO: falls Zertifikat vorhanden => $http='https://';

define('MY_DOMAIN',$http . $_SERVER['SERVER_NAME'] . '/' . BASE_MODULE);
define('MY_DOMAIN_SSL','https://' . $_SERVER['SERVER_NAME'] . '/' . BASE_MODULE);

define('BASE_DIR',$_SERVER['DOCUMENT_ROOT'] . '/' . BASE_MODULE);
//upd-download
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/data/accounts/');
define('AUDIO_DIR', $_SERVER['DOCUMENT_ROOT'] . '/data/audio/'); 
define('DOWNLOAD_DIR', BASE_DIR . '/users/');
define('NEWHEIGHT_S', 120);
define('NEWHEIGHT_L', 300);

//define('IMAGEMAGICKPATH', '/usr/bin/convert'); //not needed under linux, can call 'convert' direct 
//define('FFMPEGPATH', '/usr/bin/ffmpeg');	

define('MAX_UPLOAD_SIZE',2097152); //2MB => TODO: ev. aus DB, pro User unterschiedlich...
define('UPLOAD_FILE_RIGHT',0744);	//TODO: nochmal prüfen, was hier mindestens genommen werden muss, ev. auch 0766 benötigt?

//regExps
define('NOTALLOWED_PW_REGEXP','/ä|ü|ö|\'|`|"|<|>/i');
//4 Zeichen (Buchstaben, Zahlen, -, _, #, @, €
define('MUSTHAVE_PW_REGEXP','/^([A-Za-z0-9-_#@€]{4,255})$/');

//strip_tags: allowed_tags: needed for rte-use
define('ALLOWED_TAGS','');

define('SALTED','vcampaign_niDZA704MvAgRyfLvIzr');
define('DAYS_WAIT_DELETE_AFTER_MAIL', 5);

//dbconn
$db_arr = array
    ('host' 	=> 'db518818765.db.1and1.com'
    ,'port'		=> '3306'
    ,'username'	=> 'dbo518818765'
    ,'passwd'	=> 'evGE21otYa'
    ,'dbname'   => 'db518818765'
    ,'socket' 	=> ''
    );
?>