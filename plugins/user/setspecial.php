<?php
/**
* @version		$Id: setspecial.php 11190 2008-10-20 00:49:55Z plautman $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

/**
 * Joomla User plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgUserSetspecial extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param 	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgUserSetspecial(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @access	public
	 * @param   array   holds the user data
	 * @param 	array   array holding options (remember, autoregister, group)
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function onLoginUser($user, $options = array())
	{
	  global $mainframe;
		jimport('joomla.user.helper');

		//$instance =& $this->_getUser($user, $options);
		$session =& JFactory::getSession();
		$user = $session->get('user');

		// Get an ACL object
		$acl =& JFactory::getACL();

		$grp = $acl->getAroGroup($user->get('id'));

		$bar = $this->params->get('bar');
		
		// Fudge Authors, Editors, Publishers and Super Administrators into the special access group
		if($acl->is_group_child_of($grp->name, $this->params->get('bar')) ||
       $acl->is_group_child_of($bar, 'Public Frontend') && $acl->is_group_child_of($grp->name, 'Public Backend') ||
		   $mainframe->isAdmin()) {
			$user->set('aid', 2);
		}
    else
      $user->set('aid', 1);
		
		return true;
	}

	
}
