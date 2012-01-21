<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * LyftenBloggie Captcha helper class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieCaptcha
{
	/**
	 * Method to get Captcha
	 **/
	function getCaptcha()
	{
		global $mainframe;
		static $captchasessionid = '';

		$doc = &JFactory::getDocument();

		$length = 6; // TODO throus function parameter
		$captchaalphanumeric  = '0123456789'; // no 'l', '1', 'o', '0' - trouble for distinct
		$captchanumeric  = '0123456789'; // sound captcha
		$captchalen = strlen( $captchaalphanumeric ) - 1;
		$captchaslist = array();
		$captchareloads = min( 5, 20 );
		for ( $j = 0; $j < $captchareloads; $j++ ) {
			$char = '';
			$number = '';
			for ( $i = 0; $i < $length; $i++ ) {
				$char .= substr( $captchaalphanumeric, mt_rand( 0, $captchalen ), 1 );        
				$number .= substr( $captchanumeric, mt_rand( 0, 9 ), 1 );        
			}
			$captchaslist[] = $number;
		}
	
		// Session >>>
		$lastsession = $_SESSION;
		$lastsessionid = session_id();
		session_write_close();

		// captcha session
		ini_set( 'session.save_handler', 'files' );
		if (!$captchasessionid == '') {
			@session_id( $captchasessionid );
			session_start();
		} else {
			session_start();
			session_regenerate_id();
			$captchasessionid = session_id();
			$_SESSION = array();
		}

		$_SESSION [ 'captchaslist' ] = $captchaslist;

		session_write_close();

		// restore previous session
		$conf = &JFactory::getConfig();
		$handler =  $conf->getValue('config.session_handler', 'none');
		if ($handler == 'none') {
			ini_set( 'session.save_handler', 'files' );
		} else {
			ini_set( 'session.save_handler', 'user' );
			$sessionstorage = new JSessionStorageDatabase();
		}
		session_id($lastsessionid);
		session_start();

		$_SESSION [ 'captchasessionid' ] = $captchasessionid;
		setcookie( 'jsid', $captchasessionid, time() + 3600, '/' ); // TODO

		// URLs
		$captcha_URI = JURI::root() . 'components/com_lyftenbloggie/assets/images/';
		$image_URI   = $captcha_URI . 'captcha.php?';
		$url_imagecore   = JRoute::_( $image_URI );
		$image_params   = '&amp;sid='.$captchasessionid.'&amp;crt=0';
		$url_image   = $url_imagecore.time().$image_params;

		$captcha = array('url_imagecore'	=>$url_imagecore,
						"image_params" 		=> $image_params,
						"url_image" 		=> $url_image,
						"captchasessionid" 	=> $captchasessionid
						) ;
				
		return $captcha;
	}
	
	/**
	 * Method to check Captcha code
	 **/	
	function checkCaptcha()
	{
		$usersecurecode 	= strtolower(JRequest::getVar( 'captchacode', '', 'post' ));
		$captchasessionid 	= JRequest::getVar( 'captchasessionid', '', 'post' );
		$captcha 			= '';
		$captchasesfrmcurs	= '';
	
		// close and save previous session
		if (session_id()) {

			// for backward compatibility
			// will be deprecated in 5.0.0 or wil be used for hi security
			@$captchasesfrmcurs .= $_SESSION['captchasessionid'];
			$lastsession = $_SESSION;
			$lastsessionid = session_id();
		}
		session_write_close();

		if ($captchasessionid == '') $captchasessionid = $captchasesfrmcurs;

		// captcha session
		if ( $captchasessionid ) {
			ini_set( 'session.save_handler', 'files' );
			@session_id( $captchasessionid );
			session_start();
			@$captcha = $_SESSION [ 'captcha' ] ;
			session_write_close();
		}

		// restore previous session
		$conf = &JFactory::getConfig();
		$handler =  $conf->getValue('config.session_handler', 'none');
		if ($handler == 'none') {
			ini_set( 'session.save_handler', 'files' );
		} else {
			ini_set( 'session.save_handler', 'user' );
			$sessionstorage = new JSessionStorageDatabase();
		}
		session_id($lastsessionid);
		session_start();

		// <<<<< Sessions
		if (($captcha == $usersecurecode) && ( $usersecurecode != '' )) {
			return true;
		}
		return false;
	}
	
	/**
	 * Closes any open HTML Tags
	* */ 
	function cleanHTML($var)
	{
		preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU",$var,$opened);
		preg_match_all("#</([a-z]+)>#iU",$var,$closed);
		$OpenedCount = count($opened[1]);

		if(count($closed[1]) == $OpenedCount) return $var;

		$opened = array_reverse($opened[1]);
		for($i=0;$i < $OpenedCount;$i++) {
			if (!in_array($opened[$i],$closed[1]) && $opened[$i] != 'img'){
				$var .= '</'.$opened[$i].'>';
			} else {
				unset($closed[1][array_search($opened[$i],$closed[1])]);
			}
		}
		return $var;
	}
}
?>