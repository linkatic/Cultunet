<?php

/**
 * 
 * @version		3.0.0
 * @package		Joomla
 * @subpackage	Photoslide GK3
 * @copyright	Copyright (C) 2008 - 2009 GavickPro. All rights reserved.
 * @license		GNU/GPL
 * 
 * ==========================================================================
 * 
 * Check system view.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
class ViewCheckSystem
{	
    /**
     * ViewCheckSystem::mainpage()
     * 
     * @return null
     */
     
    function mainpage()
    {
    	$uri =& JURI::getInstance();
    	$systemcheck = new ControllerCheckSystem();
    	$systemcheck->DBStatus();
    	$modal_news =  (JRequest::getCmd( 'tmpl', '' ) == 'component') ? 1 : 0;
		require_once(JPATH_COMPONENT.DS.'views'.DS.'navigation.view.php');
		require_once(JPATH_COMPONENT.DS.'views'.DS.'tmpl'.DS.'check.system.html.php');
    }
}

/* End of file check.system.view.php */
/* Location: ./views/gk3_photoslide/check.system.view.php */