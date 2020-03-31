<?php
//TODO: für cli dann folgende Zeile einkommentieren
// #!/usr/bin/php 
/** 
 * ----------------------------------------------------------------------------
 * File: 		cronscripts/deletefilm.php
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
 * Rev: 	2012-02 	ChK	Created
 * 
 * ----------------------------------------------------------------------------
 */
require '../includes/config.inc.php';

$locale = DEFAULT_LOCALE;

//TODO: für cli dann folgenden part einkommentieren
/*
 array_shift($argv);
//get arguments, args: locale (e.g. de_DE) optional
//Aufruf e.g. time ./cronscripts/infomail_deletefilm.php de_DE
if(isset($argv[0])) {
	$locale = $argv[0];
} 
*/
if(isset($_GET['locale']) && '' != $_GET['locale']) {
	$locale = $_GET['locale'];
}
require BASE_DIR . '/languages/locale.' . $locale . '.class.php';

require BASE_DIR . '/classes/error.class.php';
require BASE_DIR . '/classes/format.class.php';

require BASE_DIR . '/classes/dbconn.class.php';
$dbconn = new dbconn($db_arr,'utf8');
$mysqli = $dbconn->get_dbconn();

require BASE_DIR . '/classes/account.class.php';
$account = new account($mysqli);
if($account->deleteOldFilmsByAccount()) {
	echo "\n Success";
} else  {
	echo "\nERROR: " . $account->_error;
};	 

//close db-connection
$dbconn->disconnect();
?>