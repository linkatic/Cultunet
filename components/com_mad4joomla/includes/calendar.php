<?php
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
	
	function init_calendar()
		{
			
			if(!defined ('SECURITY_CODE') || substr(SECURITY_CODE,-19,-1)!='c2f613e3c2f6469763') exit();
			$lang_code = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
			
			echo'
			<link rel="stylesheet" type="text/css" media="all" href="'.M4J_FRONTEND_CALENDAR.'calendar-system.css" title="green" />
			<script language="JavaScript" src="'.M4J_FRONTEND_CALENDAR.'m4j.js" type="text/javascript"></script>
			<!-- import the calendar script -->
			<script type="text/javascript" src="'.M4J_FRONTEND_CALENDAR.'calendar_stripped.js"></script>
			<!-- import the language module -->';
			
		
			if(file_exists(M4J_JS_CALNEDAR.'lang/calendar-'.$lang_code.'.js')) 
			echo'<script type="text/javascript" src="'.M4J_FRONTEND_CALENDAR.'lang/calendar-'.$lang_code.'.js"></script>';
			else 
			echo'<script type="text/javascript" src="'.M4J_FRONTEND_CALENDAR.'lang/calendar-en.js"></script>';			
			
		}//EOF init_calendar

	function init_preview_calendar()
		{
		$lang_code = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
		$out = '
				<link rel="stylesheet" type="text/css" media="all" href="'.M4J_FRONTEND_CALENDAR.'calendar-system.css" title="green" />
				<script type="text/javascript" src="'.M4J_FRONTEND_CALENDAR.'calendar.js"></script>
				';
		if(file_exists(M4J_JS_CALNEDAR.'lang/calendar-'.$lang_code.'.js')) 
			$out .= '<script type="text/javascript" src="'.M4J_FRONTEND_CALENDAR.'lang/calendar-'.$lang_code.'.js"></script>';
		else 
			$out .= '<script type="text/javascript" src="'.M4J_FRONTEND_CALENDAR.'lang/calendar-en.js"></script>';
		return $out;
		}


	function finalize()
	{
	if(!defined ('IS_SECURE')) die();
	}

    function insert_calendar($name)
		{
		echo'
			<input id="'.$name.'" type="text" size="30" name="'.$name.'"/>
			<input type="reset" onclick="return showCalendar(\''.$name.'\');" value=" ... "/>
			';
		}//EOF insert_calendar
	  
?>