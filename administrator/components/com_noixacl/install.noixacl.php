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

function com_install()
{
	$db = JFactory::getDBO();
	
    // Install System plugin
    $src = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_noixacl'.DS.'plugins'.DS.'system'.DS;
    $dest = JPATH_ROOT.DS.'plugins'.DS.'system'.DS;

    $res = JFile::copy($src.'noixacl.php', $dest.'noixacl.php');
    $res = $res && JFile::copy($src.'noixacl.xml', $dest.'noixacl.xml');

    $db->setQuery("INSERT INTO #__plugins
	               (id, name, element, folder, access, ordering, published, iscore, client_id, checked_out, checked_out_time, params)
	               VALUES ('', 'noixACL - System Plugin 2.0.10', 'noixacl', 'system', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', '')");
    $res = $res && $db->query();

    if (!$res) {
        JError::raiseWarning(100, JText::_('NoixACL System plugin not installed. Please install it manually from the following folder').': '.$src);
    } else {
        JFolder::delete($src);
    }

    // Install User plugin
    $src = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_noixacl'.DS.'plugins'.DS.'user'.DS;
    $dest = JPATH_ROOT.DS.'plugins'.DS.'user'.DS;

    $res = JFile::copy($src.'noixacl.php', $dest.'noixacl.php');
    $res = $res && JFile::copy($src.'noixacl.xml', $dest.'noixacl.xml');

    $db->setQuery("INSERT INTO #__plugins
	               (id, name, element, folder, access, ordering, published, iscore, client_id, checked_out, checked_out_time, params)
	               VALUES ('', 'noixACL - User Plugin 2.0.10', 'noixacl', 'user', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', '')");
    $res = $res && $db->query();

    if (!$res) {
        JError::raiseWarning(100, JText::_('NoixACL User plugin not installed. Please install it manually from the following folder').': '.$src);
    } else {
        JFolder::delete($src);
    }
    
}