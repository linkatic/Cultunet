<?php
/**
 * @package     Advanced Module Manager
 * @version     1.9.7
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright (C) 2010 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * BASE ON JOOMLA CORE FILE:
 * /administrator/components/com_modules/admin.modules.php
 */

/**
 * @version		$Id: admin.modules.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @subpackage	Modules
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Make sure the user is authorized to view this page
$user =& JFactory::getUser();
if ( !$user->authorize( 'com_modules', 'manage' ) ) {
	$mainframe->redirect( 'index.php', JText::_( 'ALERTNOTAUTH' ) );
}

$lang =& JFactory::getLanguage();
$lang->load( 'com_modules', JPATH_ADMINISTRATOR );

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Create the controller
$controller	= new ModulesController( array( 'default_task' => 'view' ) );

// Perform the Request task
$controller->execute( JRequest::getCmd( 'task', 'view') );

// Redirect if set by the controller
$controller->redirect();