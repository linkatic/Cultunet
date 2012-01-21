<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class userHelper {
	function getIP(){
		$ip = '';
		if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) AND strlen($_SERVER['HTTP_X_FORWARDED_FOR'])>6 ){
	        $ip = strip_tags($_SERVER['HTTP_X_FORWARDED_FOR']);
	    }elseif( !empty($_SERVER['HTTP_CLIENT_IP']) AND strlen($_SERVER['HTTP_CLIENT_IP'])>6 ){
			 $ip = strip_tags($_SERVER['HTTP_CLIENT_IP']);
		}elseif(!empty($_SERVER['REMOTE_ADDR']) AND strlen($_SERVER['REMOTE_ADDR'])>6){
			 $ip = strip_tags($_SERVER['REMOTE_ADDR']);
	    }//endif
		return strip_tags($ip);
	}
	function validEmail($email){
		if(empty($email) OR !is_string($email)) return false;
		if(!preg_match('/^([a-z0-9_\'&\.\-\+])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,10})+$/i',$email)) return false;
		return true;
	}
}