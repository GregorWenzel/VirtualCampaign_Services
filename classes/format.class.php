<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		format.class.php
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
 * Format class 
 * 
 *
 */
class format
{
	
	public static $_error_txt;
	public static $_errorArr = array();
	public static $_returnArr = array();

    public static function getData($array, $itemName, $default=null, $type=null)
    {
        if (!isset($array[$itemName])) {
//            trigger_error("Var {$itemName} not set", E_USER_NOTICE);
            if (is_null($default)) {
                return 'NULL';
            }
            return $default;
        }
        switch($type) {
            case 'int':
                return (int)$array[$itemName];
            case 'string':
                return (string)$array[$itemName];
            default:
                return $array[$itemName];
        }
    }

	/**
	 * Check required params for module
	 * 
	 * params: 
	 * foreach param
	 * 	key name			=> name of parameter
	 * 	key type 			=> int, decimal, datetime, date, password (add: minstringlen), email
	 * 	key musthave		=> optional, required
	 * 	key maxstringlen	=> count for string, else ''
	 *
	 * @param array $params
	 * @param array $required
	 * @return array (valid params) or false (invalid)
	 * @static
	 */
	public static function check_params($params, $required) {
		$check 		= true;
		$return 	= $params;
		//init error-vars for sure
		self::$_errorArr 	= array();
		self::$_error_txt 	= '';	

		//check required values
		foreach ($required as $arr) {
			//no blanks
			if (isset($return[$arr['name']]) && (!empty($return[$arr['name']]) || (string)$return[$arr['name']] === '0')) {
                if (is_array($return[$arr['name']])) {
                    $return[$arr['name']] = array_map(function($i) {
                        return trim(strip_tags($i, ALLOWED_TAGS));
                    }, $return[$arr['name']]);
                } else {
                    $return[$arr['name']] = trim(strip_tags($return[$arr['name']],ALLOWED_TAGS));
                }

				switch($arr['type']) {
					case 'string':
						//check maxlen
						if($arr['maxstringlen']!=''
							&& strlen($return[$arr['name']]) > (int)$arr['maxstringlen']) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_MAXSTRINGLEN . ': ' . $arr['maxstringlen'];
						} 
						break;
					case 'email':
						//check maxlen
						if($arr['maxstringlen']!=''
							&& strlen($return[$arr['name']]) > (int)$arr['maxstringlen']) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_MAXSTRINGLEN . $arr['maxstringlen'];
						}
						//check @
						if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,3})$/i", $return[$arr['name']])) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_EMAIL;
						}  
						break;
					case 'int':
						//for multiple dd: check if array
						if(is_array($return[$arr['name']])) {
							$dummy_check_int=false;
							$int_array=array();
							$empty_values=true;
								foreach($return[$arr['name']] as $value) {
									if($value!='') {
										$empty_values=false;
										$dummy_check_int = format::check_makeInt($value);
										if($dummy_check_int===false) {
											$check = false;
											self::$_errorArr[$arr['name']] = locale_lang::ERR_INT;
										} else {
											$int_array[]=$dummy_check_int;
										}
										if($check) {
											$return[$arr['name']] = $int_array;
										}
									}//!=''
								}//foreach	
								if($empty_values) {
									$check = false;				
									self::$_errorArr[$arr['name']] = locale_lang::ERR_MUSTHAVE;	
								}
						} else {
							$checked_int = format::check_makeInt($return[$arr['name']]);
							if($checked_int===false) {
								$check = false;
								self::$_errorArr[$arr['name']] = locale_lang::ERR_INT;
							} else {
								$return[$arr['name']] = $checked_int;
							}
						}
						break;
					case 'decimal':
						$checked_decimal = format::check_makeDecimal($return[$arr['name']]);
						if($checked_decimal===false) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_DECIMAL;
						} else {
							$return[$arr['name']] = $checked_decimal;
						}
						break;
					case 'decimaldigits':
						$checked_decimal = format::check_makeDecimal_exact($return[$arr['name']],(int)$arr['pre_digits'], (int)$arr['post_digits']);
						if($checked_decimal===false) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_DECIMAL_EXACT . ' (' . str_repeat('9', (int)$arr['pre_digits']) . ',' . str_repeat('9', (int)$arr['post_digits']) . ')';
						} else {
							$return[$arr['name']] = $checked_decimal;
						}
						break;
					case 'datetime':
						$checked_date = format::check_makeDBDate($return[$arr['name']]); 
						if($checked_date===false) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_DATE;
						} else {
							$return[$arr['name']] = $checked_date;
						}
						break;
					case 'date':
						$checked_date = format::check_makeDBDate($return[$arr['name']]); 
						if($checked_date===false) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_DATE;
						} else {
							$return[$arr['name']] = substr($checked_date,0,10);
						}
						break;
					case 'time':
						$checked_date = format::check_dbdatetime($return[$arr['name']],'time'); 
						if($checked_date===false) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_TIME;
						} 
						break;
					case 'password':
						//check minlen maxlen
						if(strlen($return[$arr['name']]) > (int)$arr['maxstringlen']
							|| strlen($return[$arr['name']]) < (int)$arr['minstringlen']
							) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_MINSTRINGLEN . ': ' . $arr['minstringlen'] . '/' . locale_lang::ERR_MAXSTRINGLEN . ': ' . $arr['maxstringlen'];
						} 
						//check special chars
						$found = format::check_notallowedChars($return[$arr['name']], NOTALLOWED_PW_REGEXP);
						if($found!==false) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_PW . 'notallowed';
						}
						$found = format::check_requiredChars($return[$arr['name']], MUSTHAVE_PW_REGEXP);
						if($found===false) {
							$check = false;
							self::$_errorArr[$arr['name']] = locale_lang::ERR_PW . 'musthave';
						}
						break;
                    case 'array':
                        if (!is_array($return[$arr['name']])) {
                            $check = false;
                            self::$_errorArr[$arr['name']] = locale_lang::ERR_INT;
                        }
                        break;
					default:
						$check = false;
						self::$_errorArr[$arr['name']] = locale_lang::ERR_DEFAULT;
						break;
				}
			} elseif($arr['musthave']=='required') {
				$check = false;				
				self::$_errorArr[$arr['name']] = locale_lang::ERR_MUSTHAVE;	
			} else {
				$return[$arr['name']]=null; //set array-var for e.g. insert-stmt
			} 
			
		}//foreach
	
		if ($check) {
			self::$_returnArr = $return;
			return true;
		} else {
			self::$_errorArr[''] = locale_lang::ERR_NOT_ALL_PARAMS;
			return false;
		}
	}
	
	
	/**
	 * Validate date and format input-date to db-date Y-m-d H:i:s
	 * 
	 * in: 2007-01-01 00:00:00 (Y-m-d H:i:s) => check + do nothing
	 * in: 01.01.2007 00:00:00 (d.m.Y H:i:s) => check + transform
	 * in: 2007/01/01 00:00:00 (Y/m/d H:i:s) => check + transform
	 * in: 01/01/2007 00:00:00 (Y/m/d H:i:s) => check + transform
	 *
	 * @param string $in
	 * @return string (valid db-ate-string) or false#
	 * @static 
	 */
	public static function check_makeDBDate($in)
	{
		$out = false;
		$in = trim($in);
		
		$split_char = null;
		if(strpos($in, '.') !== false) {
			$split_char = '.';
		} elseif(strpos($in, '-') !== false) {
			$split_char = '-';
		} elseif(strpos($in, '/') !== false) {
			$split_char = '/';
		} else {
			$split_char = false;
		}
		
		if($split_char) {
			$a1=explode(" ",$in);
			$date = explode($split_char,$a1[0]);
			if (count($date)==3) {
				//check numeric values 
				if(is_numeric($date[0])===false
					|| is_numeric($date[1])===false
					|| is_numeric($date[2])===false
				) {
					$out = false;
				} else {
					//check if isdate, else return false: checkdate(m,d,y)
					switch ($split_char) {
						case '.': //d.m.Y H:i:s
							$mon=$date[1];
							$day=$date[0];
							$year=$date[2];
							break;
						case '-': //Y-m-d H:i:s
							$mon=$date[1];
							$day=$date[2];
							$year=$date[0];
							break;
						case '/': //Y/m/d H:i:s or d/m/Y H:i:s  
							$mon=$date[1];
							$day=$date[2];
							$year=$date[0];
							if(checkdate($mon,$day,$year)===false) {
								$mon=$date[1];
								$day=$date[0];
								$year=$date[2];
							} 
							break;
						default:
							$out = false;
							break;	
					}//switch
					if(strlen($year)!=4 || strlen($mon)!=2 || strlen($day)!=2) {
						$out = false;
					} else {
						if(checkdate($mon,$day,$year)===false) {
							$out = false;
						} else {
							$out = date('Y-m-d H:i:s',strtotime($in));
						}
					}
				}
			}//count 3
		}//split_char
		return $out;
	}

	/**
	 * Validate input-datetime to db-date Y-m-d H:i:s, NO tranform
	 *
	 * @param string $in
	 * @param string $type - 'datetime'/'date'/'time'
	 * @param bool $null=true
	 * @return bool
	 * @static 
	 */
	public static function check_dbdatetime($in,$type='datetime',$null=true)
	{
		$return = true;
		
		$in = trim($in);
//force error
//$in = '2009-15-01 50:12:12';
			
		if($in != '') {
			switch($type) {
				case 'datetime':
					//check yyyy-mm-dd hh:ii:ss
					$regExp = '^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})$^';
					break;
				case 'date':
					//check yyyy-mm-dd hh:ii:ss
					$regExp = '^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$^';
					break;
				case 'time':	
					//check hh:ii:ss
					$regExp = '^([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})$^';
					break;
			}
			if (!preg_match($regExp, $in)) {
				self::$_error_txt = ' no valid db-' . $type;
				$return = false;
			} else {
				self::$_error_txt = '';
				$return = true;
				switch($type) {
					case 'datetime':
						$dummy = explode(' ',$in);
						//check valid date
						if(!self::validate_date($dummy[0])) { 
							self::$_error_txt .= ' no valid date';
							$return = false;
						}
						//check valid time
						if(!self::validate_time($dummy[1])) { 
							self::$_error_txt .= ' no valid time';
							$return = false;
						}
						break;
					case 'date':
						//check valid date
						if(!self::validate_date($in)) { 
							self::$_error_txt .= ' no valid date';
							$return = false;
						}
						break;
					case 'time':	
						//check valid time
						if(!self::validate_time($in)) { 
							self::$_error_txt .= ' no valid time';
							$return = false;
						}
						break;
				}
			}
		} else {
			//$in=''
			if($null) {
				$return = true;
			} else {
				self::$_error_txt .= ' no data for not null';
				$return = false;
			}
		}//in!=''
		return $return;
	}
	
	/**
	 * validate_date
	 *
	 * @param string $date (Y-m-d)
	 * @return bool
	 * @static
	 */
	protected static function validate_date($date)
	{
		//bool checkdate  ( int $month  , int $day  , int $year  )
		$dummy = explode('-',$date);
		return checkdate($dummy[1], $dummy[2], $dummy[0]);
	} 
	
	/**
	 * validate_time
	 *
	 * @param string $time (H:i:s)
	 * @return bool
	 * @static 
	 */
	protected static function validate_time($time)
	{
		$return = true; 
		$dummy = explode(':',$time);
		
		if($dummy[0] < 0 || $dummy[0] > 23) {
			$return = false;
		}
		if($dummy[1] < 0 || $dummy[1] > 59) {
			$return = false;
		}
		if($dummy[2] < 0 || $dummy[2] > 59) {
			$return = false;
		}
		
		return $return;
	}
	
	/**
	 * Validate int and format to int 
	 *
	 * @param mixed $in
	 * @param bool $unsigned=false
	 * @param bool $null=true
	 * @return int or false
	 * @static
	 */
	public static function check_makeInt($in, $unsigned=false, $null=true) 
	{
		$out = false;
		$in = trim($in);
//force error 
//$in = 'abc';
		 
		if($in != '') {
			if(is_numeric($in)===false) {
				$out = false;
				self::$_error_txt = ' not numeric';
			} else {
				$out = (int)$in;
				if($unsigned && ($out < 0)) {
					$out = false;
					self::$_error_txt = ' not unsigned';
				}
			}//is_numeric
		} else {
			//$in=''
			if($null) {
				$out = $in;
			} else {
				self::$_error_txt .= ' no data for not null';
				$out = false;
			}
		}//in!=''
		return $out;
	}
	
	/**
	 * Validate decimal and format to decimal 
	 *
	 * @param mixed $in
	 * @param bool $unsigned=false
	 * @param bool $null=true
	 * @return decimal or false
	 * @static
	 */
	public static function check_makeDecimal($in, $unsigned=false, $null=true) 
	{
		$out = false;
		$in = trim($in);
		if($in != '') {
			//set decimal point for db-value
			//not for import => load of original data later => error
	        //$in = str_replace(",", ".", $in);
			if(is_numeric($in)===false) {
				$out = false;
				self::$_error_txt = ' not numeric';
			} else {
				//decimal numbers
				if($unsigned) {
					$regExp	= '^([1-9]{1}[0-9]{0,}(\.[0-9]{0,})?|0(\.[0-9]{0,})?|\.[0-9]{1,})$^';
				} else {
					$regExp	= '^(-)?([1-9]{1}[0-9]{0,}(\.[0-9]{0,})?|0(\.[0-9]{0,})?|\.[0-9]{1,})$^';
				}
				if (!preg_match($regExp, $in)) {
					$out = false;
					self::$_error_txt = ' not decimal or wrong sign';
				} else {
					$out=$in;
				}
			}
		} else {
			//$in=''
			if($null) {
				$out = $in;
			} else {
				self::$_error_txt .= ' no data for not null';
				$out = false;
			}
		}//in!=''
		return $out;
	}
	
	/**
	 * Validate decimal for given length and format to decimal
	 * e.g. pre=3, post=2 => max: 999.99 
	 *
	 * @param mixed $in
	 * @param int $digits_pre
	 * @param int $digits_post
	 * @param bool $unsigned=false
	 * @param bool $null=true
	 * @return decimal or false
	 * @static
	 */
	public static function check_makeDecimal_exact($in, $digits_pre, $digits_post, $unsigned=false, $null=true) 
	{
		$out = false;
		$in = trim($in);
//force error
//$in = 12345678.1234;		
		if($in != '') {
			//set decimal point for db-value
	        $in = str_replace(",", ".", $in);
	        if(is_numeric($in)===false) {
				$out = false;
				self::$_error_txt = ' not numeric';
			} else {
			//decimal numbers
				if($unsigned) {
					$regExp	= '^[0-9]{0,' . $digits_pre . '}(\\.[0-9]{0,' . $digits_post . '})?$^';
				} else {
					$regExp	= '^(-)?[0-9]{0,' . $digits_pre . '}(\\.[0-9]{0,' . $digits_post . '})?$^';
				}
				if (!preg_match($regExp, $in)) {
					$out = false;
					self::$_error_txt = ' not decimal or wrong sign or wrong length/decimal-digits';
				} else {
					$out=$in;
				}
			}
		} else {
			//$in=''
			if($null) {
				$out = $in;
			} else {
				self::$_error_txt .= ' no data for not null';
				$out = false;
			}
		}//in!=''
		return $out;
	}
	
	/**
	 * check_maxLen
	 *
	 * @param string $string
	 * @param int $len
	 * @return bool
	 * @static
	 */
	public static function check_maxLen($string, $len) 
	{
		if(strlen($string) > $len) {
			self::$_error_txt = ' too long';
			return false;
		} else {
			return true;
		}
	}

	/**
     * myHtmlSpecialChars
     *
     * @param string $text
     * @return string
     * @static
     */
    public static function myHtmlSpecialChars($text) {
    	return htmlspecialchars($text);
    }
    
	/**
     * myHtmlSpecialCharsDecode
     *
     * @param string $text
     * @return string
     */
    public function myHtmlSpecialCharsDecode($text) {
    	return htmlspecialchars_decode($text);
    }
	
}
?>