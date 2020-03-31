<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		error.class.php
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
 * Error class 
 *
 */
class error
{
	/**
	 * Format error messages
	 * key in param-array:
	 * ERROR => concat to string
	 * WARNING => concat to string
	 * SQL-ERROR => concat to string
	 * '' => concat to string
	 * else => no concat, information for e.g. formfields 
	 *
	 * @param array $params (key + value)
	 * @param $sql_error_msg string
	 * @return string $error string
	 * @static
	 */
	public static function buildErrorString($params,$sql_error_msg='')
	{
		//no htmltags because of urlencoding
		$error_string='';
		foreach($params as $key => $value) {
			if($key == 'ERROR'
				|| $key == 'WARNING'
				|| $key == 'SQL-ERROR'
				|| $key == ''
				) {
				$error_string .= $key . $value . '<br />';
			}
		}
		$error_string = substr($error_string,0,-6); //trim last <br />
		
		if(isset($_SESSION['sess_sysDebug']) && true == $_SESSION['sess_sysDebug']) {
			if($sql_error_msg != '') {
				$error_string .= '<br />mysql_error: ' . $sql_error_msg;
			}
		}
		return $error_string;
	}
}
?>