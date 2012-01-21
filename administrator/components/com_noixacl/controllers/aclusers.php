<?php
/**
 * @version		$Id: controller.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @subpackage	Users
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/**
 * Check Access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_ADMINISTRATOR.DS."components".DS."com_users".DS."controller.php";

jimport('joomla.application.component.controller');

/**
 * Users Component Controller
 *
 * @package		Joomla
 * @subpackage	Users
 * @since 1.5
 */
class AclUsersController extends UsersController
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	function __construct($config = array())
	{
		parent::__construct($config);

        $pathViews = JPATH_ADMINISTRATOR.DS."components".DS."com_noixacl".DS."views";

        $this->addViewPath($pathViews);
        JRequest::setVar('view','users');
	}

}