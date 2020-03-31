<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		mail.class.php
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
 * Mail class 
 *
 */
class mail
{
	public $_errorArr;
	public $_error;
	
	/**
	 * sendmail
	 *
	 * @param string $mailfrom
	 * @param string $mailto
	 * @param string $subject
	 * @param string $body
	 * @param string $charset='UTF-8'
	 * @return bool
	 * @static
	 */
	public static function sendmail($mailfrom, $mailto, $subject, $body, $charset='UTF-8')
	{
		//switch permanent to iso
		//$charset = 'iso-8859-1';
		
		if('' == $mailfrom) {
			$mailfrom = STANDARD_MAILFROM;
		}
		
		$mailfrom = str_ireplace('\n', '', $mailfrom);
		$mailfrom = str_ireplace('\r', '', $mailfrom);		
		
		$mailto = str_ireplace('\n', '', $mailto);
		$mailto = str_ireplace('\r', '', $mailto);
		
		$subject = str_ireplace('\n', '', $subject);
		$subject = str_ireplace('\r', '', $subject);
		$subject = substr($subject,0,78); // no line-break by php
		
		
		//Header
		$headers = '';
		$headers = "MIME-Version: 1.0" . "\n";
		$headers .= "Content-type: text/plain; charset=" . $charset . "\n";
		$headers .= "Content-transfer-encoding: quoted-printable\n";
		$headers .= "From: " . $mailfrom . "\n";
		$headers .= "Reply-To: " . $mailfrom . "\n";

//echo '<br />mail: <br />header: ' . urlencode($headers) . '<br />subject: ' . urlencode($subject) . '<br />body: ' . urlencode(nl2br($body));
    	if(!mail($mailto, $subject, self::quotePrintable($body), $headers)) {
			return false;
		}
		return true;
	}
	
	/**
	 * sendAttachmentMail
	 *
	 * @param string $mailfrom
	 * @param string $mailto
	 * @param string $subject
	 * @param string $body
	 * @param string $file_name
	 * @param string $path
	 * @param string $charset='UTF-8'
	 * @return bool
	 * @static
	 */
	public static function sendAttachmentMail($mailfrom, $mailto, $subject, $body, $file_name,$path,$charset='UTF-8')
	{
		//switch permanent to iso
		//$charset = 'iso-8859-1';
		
		//$file_name    = $attachment;
		//$path         = "./rechnungen/$file_name";
		
		if($mailfrom=='') {
			$mailfrom=STANDARD_MAILFROM;
		}
		
		$mailfrom = str_ireplace('\n', '', $mailfrom);
		$mailfrom = str_ireplace('\r', '', $mailfrom);
		
		$mailto = str_ireplace('\n', '', $mailto);
		$mailto = str_ireplace('\r', '', $mailto);
		
		$subject = str_ireplace('\n', '', $subject);
		$subject = str_ireplace('\r', '', $subject);
		$subject = substr($subject,0,78); // no line-break by php
		
		$subject = utf8_decode($subject);
		$body = utf8_decode($body);
		
	
		//$file = $path.$filename;
		$file = $path;
	    $file_size = filesize($file);
	    $handle = fopen($file, "r");
	    $content = fread($handle, $file_size);
	    fclose($handle);
	    $content = chunk_split(base64_encode($content));
	    $uid = sha1(uniqid(time()));
	    $name = basename($file);
	    $headers = '';
	    $headers .= "From: " . $mailfrom . "\n";
	    $headers .= "Reply-To: " . $mailfrom . "\n";
	    $headers .= "Bcc: " . $mailfrom . "\n";
	    $headers .= "MIME-Version: 1.0" . "\n";
	    $headers .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\n\n";
	    $headers .= "This is a multi-part message in MIME format.\n";
	    $headers .= "--".$uid."\n";
	    //$headers .= "Content-type:text/plain; charset=iso-8859-1\n";
	    //$headers .= "Content-Transfer-Encoding: 7bit\n\n";
	    //$headers .= $body."\n\n";
	    $headers .= "Content-type: text/plain; charset=" . $charset . "\n";
	    $headers .= "Content-Transfer-Encoding: quoted-printable\n\n";
	    $headers .= self::quotePrintable($body)."\n\n";
	    $headers .= "--".$uid."\n";
	    $headers .= "Content-Type: application/octet-stream; name=\"".$file_name."\"\n"; // use diff. tyoes here
	    $headers .= "Content-Transfer-Encoding: base64\n";
	    $headers .= "Content-Disposition: attachment; filename=\"".$file_name."\"\n\n";
	    $headers .= $content."\n\n";
	    $headers .= "--".$uid."--";
	    
	    if (!mail($mailto, $subject, "", $headers)) {
	    	return false;
		}
		return true;
	}
	
	
	/**
	 * quotePrintable
	 *
	 * @param string $str
	 * @return string
	 * @abstract quote printable as specified in RFC 2045
	 * @static
	 */
	public static function quotePrintable($str) {
		// hint: as of php 5.3.0, we could use quote_printable_encode() instead
		// as we have to quote each = as =61, we have to do this first, before
	    // any other characters are quoted as =XY
	    $str = str_replace('=', '=3D', $str);
	
	    // decimal values:
	    // literal: 33-60 + 62-126 (incl)
	    // 9, 32 (white space)
	    // 13, 10  (cr, lf)
	
	
	    // encode binary chars - all but linebreaks and a tab
	    for ($i = 0; $i < 32; $i++) {
	      if ($i != 9 && $i != 10 && $i != 13) {
	        $str = str_replace(chr($i), '=0' . strtoupper(dechex($i)), $str);
	      }
	    }
	
	    // encode every above 127 (incl.) - dechex produces always 2 digits
	    for ($i = 127; $i < 256; $i++) {
	      $str = str_replace(chr($i), '=' . strtoupper(dechex($i)), $str);
	    }

		return $str;
	} // quotePrintable()
	
}
?>