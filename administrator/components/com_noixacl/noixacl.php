<?php
/**
* @version	$Id: users.php 10381 2008-06-01 03:35:53Z pasamio$
* @package	Joomla
* @subpackage	Users
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

jimport('joomla.application.component.controller');
jimport('joomla.application.component.view');
jimport('joomla.html.pane');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS."libraries".DS."html");
require_once JPATH_COMPONENT_ADMINISTRATOR.DS."libraries".DS."adapter.php";

/**
 * Make sure the user is authorized to view this page
 */
$user =& JFactory::getUser();

/**
 * Task to execute 
 */
$task = JRequest::getCMD('task');

/**
 * Require the base controller
 */
$controller = JRequest::getVar('controller','groups');

if( !is_file(JPATH_COMPONENT.DS."controllers".DS.$controller.".php") ){
	JError::raiseError('noixACL 2.0',JTEXT::_("NOIXACL_CONTROLLER_DONT_EXISTS"));
}
include_once(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');

$className = ucfirst("{$controller}Controller");

/**
 * Create controller
 */
$controller	= new $className();

/**
 * Perform the Request task
 */
$controller->execute( $task );
$controller->redirect();