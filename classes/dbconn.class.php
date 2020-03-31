<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		dbconn.class.php
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
 * dbconnect-class, set names to charset
 *
 */
class dbconn 
{ 
	/**
	 * class-variables
	 */
	protected $_dbconn;
	
	/**
	 * constructor
	 *
	 * @param array $db_arr
	 * @param string $charset
	 */
	public function __construct($db_arr, $charset='utf8')
	{
		$this->_dbconn = new mysqli($db_arr['host'],$db_arr['username'],$db_arr['passwd'],$db_arr['dbname'],$db_arr['port']);
		$this->_dbconn->query("set names '" . $charset . "'");
		$this->_dbconn->query("set sql_mode = ''");
	}
	
	/**
	 * get db-connection
	 *
	 * @return obj
	 */
	public function get_dbconn()
	{
		return $this->_dbconn;
	}
	
	/**
	 * close connection
	 *
	 */
	public function disconnect()
	{
		$this->_dbconn->close();
	}
}
?>