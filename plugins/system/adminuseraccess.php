<?php
/**
* @package system plugin Admin-User-Access (plugin for component Admin-User-Access)
* @version 1.0.0
* @copyright Copyright (C) 2008 Carsten Engel. All rights reserved.
* @license GPL
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

//no direct access
if(!defined('_VALID_MOS') && !defined('_JEXEC')){
	die('Restricted access');
}

if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_pi_admin_user_access'.DS.'plugin_system'.DS.'plugin_system.php')){			
	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_pi_admin_user_access'.DS.'plugin_system'.DS.'plugin_system.php');
}

?>