<?php
/**
 * @version		$Id: example.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @subpackage	JFramework
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
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgUserNoixACL extends JPlugin {

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	public function plgUserNoixACL(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Example store user method
	 *
	 * Method is called before user data is stored in the database
	 *
	 * @param 	array		holds the old user data
	 * @param 	boolean		true if a new user is stored
	 */
	public function onBeforeStoreUser($user, $isnew)
	{
		global $mainframe;

        $db =& JFactory::getDBO();
        $app =& JFactory::getApplication();
        /**
         * get Multigroup
         */
		$arrMultiGroups = JRequest::getVar( 'multigroups' );
		
        /**
         * check if exists array
         */
		$queryDelMultigroup = "DELETE FROM #__noixacl_multigroups WHERE id_user = {$user['id']}";
		$db->setQuery( $queryDelMultigroup );
		$db->query();
		
		if( !empty($arrMultiGroups) ){

			foreach($arrMultiGroups as $multigroupID){
				$multigroupID = intval( $multigroupID );

				$queryInsertMultigroup = "INSERT INTO #__noixacl_multigroups(id_user,id_group) "
                                  . "VALUES({$user[id]},{$multigroupID})";
				$db->setQuery( $queryInsertMultigroup );
				if( !$db->query() ){
					$app->setRedirect("index.php?option=com_noixacl&controller=aclusers",JText::_('NOIXACL_USERS_CANNOT_SAVE_MULTIGROUP'));
				}
			}
		}
	}

	/**
	 * Example store user method
	 *
	 * Method is called before user data is deleted from the database
	 *
	 * @param 	array		holds the user data
	 */
	public function onBeforeDeleteUser($user)
	{
		global $mainframe;
		
		$db =& JFactory::getDBO();
		$app =& JFactory::getApplication();
		
        if( intval($user['id']) ){
            $queryDelMultigroup = "DELETE FROM #__noixacl_multigroups WHERE id_user = {$user[id]}";
            $db->setQuery( $queryDelMultigroup );
            if( !$db->query() ){
				$app->setRedirect("index.php?option=com_noixacl&controller=aclusers",JText::_('NOIXACL_USERS_CANNOT_DELETE_MULTIGROUP'));
            }
        }
	}
}
