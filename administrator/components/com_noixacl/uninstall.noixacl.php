<?php
/**
* @version	$Id: users.php 10381 2008-06-01 03:35:53Z pasamio$
* @package	Joomla
* @subpackage	noixACL
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

function com_uninstall()
{
    // Uninstall System plugin
    $path = JPATH_ROOT.DS.'plugins'.DS.'system'.DS;

	$res = JFile::delete($path.'noixacl.php');
    $res = $res && JFile::delete($path.'noixacl.xml');
    
    $db =& JFactory::getDBO();
    $db->setQuery("DELETE FROM `#__plugins` WHERE `folder` = 'system' AND `element` = 'noixacl' LIMIT 1");
    $res = $res && $db->query();
    
	if (!$res) {
        JError::raiseWarning(100, JText::_('NoixACL System plugin could not be uninstalled. Please, uninstall it manually.'));
    }
    
    // Uninstall User plugin
    $path = JPATH_ROOT.DS.'plugins'.DS.'user'.DS;

	$res = JFile::delete($path.'noixacl.php');
    $res = $res && JFile::delete($path.'noixacl.xml');
    
    $db =& JFactory::getDBO();
    $db->setQuery("DELETE FROM `#__plugins` WHERE `folder` = 'user' AND `element` = 'noixacl' LIMIT 1");
    $res = $res && $db->query();
    
	if (!$res) {
        JError::raiseWarning(100, JText::_('NoixACL User plugin could not be uninstalled. Please, uninstall it manually.'));
    }
}