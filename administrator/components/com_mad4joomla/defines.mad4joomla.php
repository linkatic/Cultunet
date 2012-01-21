<?php
	/**
	* @version $Id: defines.php 10041 2008-03-18 21:48:13Z fahrettinkutyol $
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

	//*Frontend Folder
	define("M4J_JS_CALNEDAR", M4J_ABS . '/components/com_mad4joomla/js/calendar/');
	
	//* Frontend Includes
	define("M4J_INCLUDE_CALENDAR", M4J_ABS . '/components/com_mad4joomla/includes/calendar.php');
	define("M4J_INCLUDE_VALIDATE", M4J_ABS . '/components/com_mad4joomla/includes/validate.php');
	
	//* Fontend HTTP
	define("M4J_FRONTEND", $mosConfig_live_site . '/components/com_mad4joomla/');
	define("M4J_FRONTEND_LINK", $mosConfig_live_site . '/index.php?option=com_mad4joomla');
	define("M4J_FRONTEND_CALENDAR", M4J_FRONTEND . 'js/calendar/');



	//* Folders
	define("M4J_LANG", M4J_ABS . '/administrator/components/com_mad4joomla/language/');
	
	//* Include Constants
	define("M4J_INCLUDE_CONFIGURATION", M4J_ABS . '/administrator/components/com_mad4joomla/config.mad4joomla.php');
	define("M4J_INCLUDE_RESET_CONFIGURATION", M4J_ABS . '/administrator/components/com_mad4joomla/includes/reset_config.php');
	
	define("M4J_INCLUDE_FUNCTIONS", M4J_ABS . '/administrator/components/com_mad4joomla/includes/functions.php');	
	define("M4J_INCLUDE_FORMFACTORY", M4J_ABS . '/administrator/components/com_mad4joomla/includes/formfactory.php');	
	define("M4J_INCLUDE_REMEMBER", M4J_ABS . '/administrator/components/com_mad4joomla/includes/remember_cid.php');
							
	define("M4J_INCLUDE_JOBS", M4J_ABS . '/administrator/components/com_mad4joomla/includes/jobs.php');
	define("M4J_INCLUDE_JOBS_NEW", M4J_ABS . '/administrator/components/com_mad4joomla/includes/jobs_new.php');
	define("M4J_INCLUDE_FORMS", M4J_ABS . '/administrator/components/com_mad4joomla/includes/forms.php');
	define("M4J_INCLUDE_FORM_NEW", M4J_ABS . '/administrator/components/com_mad4joomla/includes/form_new.php');
	define("M4J_INCLUDE_FORM_ELEMENTS", M4J_ABS . '/administrator/components/com_mad4joomla/includes/form_elements.php');	
	define("M4J_INCLUDE_ELEMENT", M4J_ABS . '/administrator/components/com_mad4joomla/includes/element.php');	
	define("M4J_INCLUDE_CATEGORY", M4J_ABS . '/administrator/components/com_mad4joomla/includes/category.php');	
	define("M4J_INCLUDE_CATEGORY_NEW", M4J_ABS . '/administrator/components/com_mad4joomla/includes/category_new.php');	
	define("M4J_INCLUDE_CONFIG", M4J_ABS . '/administrator/components/com_mad4joomla/includes/config.php');	
	define("M4J_INCLUDE_HELP", M4J_ABS . '/administrator/components/com_mad4joomla/includes/help.php');
	define("M4J_INCLUDE_LINK", M4J_ABS . '/administrator/components/com_mad4joomla/includes/link.php');
	
	
	//* HTTP Contstants
	define("M4J_HOME",$mosConfig_live_site .'/administrator/index2.php?option=com_mad4joomla');
	define("M4J_JOBS", M4J_HOME.'&section=jobs');	
	define("M4J_JOBS_NEW", M4J_HOME.'&section=jobs_new');	
	define("M4J_FORMS", M4J_HOME.'&section=forms');
	define("M4J_FORM_NEW", M4J_HOME.'&section=form_new');
	define("M4J_FORM_ELEMENTS", M4J_HOME.'&section=formelements');
	define("M4J_ELEMENT", M4J_HOME.'&section=element');
	define("M4J_CATEGORY", M4J_HOME.'&section=category');
	define("M4J_CATEGORY_NEW", M4J_HOME.'&section=category_new');
 	define("M4J_CONFIG", M4J_HOME.'&section=config'); 
	define("M4J_HELP",  M4J_HOME.'&section=help'); 
	define("M4J_LINK",  M4J_HOME.'&section=link'); 
	
	define("M4J_IMAGES", $mosConfig_live_site . '/administrator/components/com_mad4joomla/images/');
	define("M4J_CSS", $mosConfig_live_site . '/administrator/components/com_mad4joomla/admin.stylesheet.css'); 
	define("M4J_JS", $mosConfig_live_site . '/administrator/components/com_mad4joomla/js/');
	define("M4J_THICKBOX", M4J_JS. 'thickbox/');
	
	//* ACTIONS
	define("M4J_HIDE_BAR",'&nobar=1');
	define("M4J_NEW",'&task=new');	
	define("M4J_EDIT",'&task=edit');
   	define("M4J_DELETE",'&task=delete');	
	define("M4J_UPDATE",'&task=update');
	define("M4J_SAVE",'&task=save');
	define("M4J_UP",'&task=up');	
	define("M4J_DOWN",'&task=down');	
	define("M4J_PUBLISH",'&task=publish');		
	define("M4J_UNPUBLISH",'&task=unpublish');
	define("M4J_REQUIRED",'&task=required');		
	define("M4J_NOT_REQUIRED",'&task=not_required');
	define("M4J_COPY",'&task=copy');	
	define("M4J_RESET",'&task=reset');	
	define("M4J_MENUTYPE",'&menutype=');
	
	//* Version
	define("M4J_VERSION_NO", "1.2");
?>
