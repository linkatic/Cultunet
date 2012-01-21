<?PHP
	/**
	* @version $Id: mad4joomla 10041 2008-03-18 21:48:13Z fahrettinkutyol $
	* @package joomla
	* @copyright Copyright (C) 2008 Mad4Media. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
	* Joomla! is free software. This version may have been modified pursuant
	* to the GNU General Public License, and as distributed it includes or
	* is derivative of works licensed under the GNU General Public License or
	* other free or open source software licenses.
	* See COPYRIGHT.php for copyright notices and details.
	* @copyright (C) mad4media , www.mad4media.de
	*/

    defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//* Folders
	define("M4J_LANG", M4J_ABS . '/components/com_mad4joomla/language/');
	define("M4J_JS_CALNEDAR", M4J_ABS . '/components/com_mad4joomla/js/calendar/');
	define("M4J_TMP", M4J_ABS . '/components/com_mad4joomla/tmp/');
	
//* Include Constants
	define("M4J_INCLUDE_CALENDAR", M4J_ABS . '/components/com_mad4joomla/includes/calendar.php');
	define("M4J_INCLUDE_VALIDATE", M4J_ABS . '/components/com_mad4joomla/includes/validate.php');
	
//* Include Constants Backend	
	define("M4J_INCLUDE_CONFIGURATION", M4J_ABS . '/administrator/components/com_mad4joomla/config.mad4joomla.php');
	define("M4J_INCLUDE_RESET_CONFIGURATION", M4J_ABS . '/administrator/components/com_mad4joomla/includes/reset_config.php');
	define("M4J_INCLUDE_FUNCTIONS", M4J_ABS . '/administrator/components/com_mad4joomla/includes/functions.php');	
	
//* HTTP Contstants

	if(! defined("_JEXEC")){
		define("M4J_HOME", $mosConfig_live_site . '/index.php?option=com_mad4joomla');
	}else{
		define("M4J_HOME", 'index.php?option=com_mad4joomla');
	}

	define("M4J_FRONTEND", $mosConfig_live_site . '/components/com_mad4joomla/');
	define("M4J_FRONTEND_CALENDAR", M4J_FRONTEND . 'js/calendar/');
	define("M4J_FRONTEND_BALOONTIP", M4J_FRONTEND . 'js/balloontip/bubble-tooltip.js');
	define("M4J_FRONTEND_BALOONTIP_CSS", M4J_FRONTEND . 'js/balloontip/bubble-tooltip.css');
	define("M4J_FRONTEND_CAPTCHA_CSS",M4J_FRONTEND.'sec/css.php?m4j_c=');
	define("M4J_CSS", M4J_FRONTEND . 'stylesheet.css');	
	
//* ACTIONS	
	define("M4J_CID", M4J_HOME.'&amp;cid=');
	define("M4J_JID", M4J_HOME.'&amp;jid=');
	define("M4J_SEND", M4J_HOME.'&amp;send=');
?>