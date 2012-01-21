<?php
/**
* @version		$Id: view.html.php 10496 2008-07-03 07:08:39Z ircmaxell $
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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Users component
 *
 * @static
 * @package		Joomla
 * @subpackage	Users
 * @since 1.0
 */
class AclUsersViewUser extends JView
{
	function display($tpl = null)
	{
		$cid		= JRequest::getVar( 'cid', array(0), '', 'array' );
		$edit		= JRequest::getVar('edit',true);
		$me 		= JFactory::getUser();
		JArrayHelper::toInteger($cid, array(0));

		$db 		=& JFactory::getDBO();
		if($edit)
			$user 		=& JUser::getInstance( $cid[0] );
		else
			$user 		=& JUser::getInstance();

		$myuser		=& JFactory::getUser();
		$acl		=& JFactory::getACL();

		/**
                 *  Check for post data in the event that we are returning
		 *  from a unsuccessful attempt to save data
                 */
		$post = JRequest::get('post');
		if ( $post ) {
			$user->bind($post);
		}

		if ( $user->get('id'))
		{
			$query = 'SELECT *'
			. ' FROM #__contact_details'
			. ' WHERE user_id = '.(int) $cid[0]
			;
			$db->setQuery( $query );
			$contact = $db->loadObjectList();
		}
		else
		{
			$contact 	= NULL;
			/**
                         *  Get the default group id for a new user
                         */
			$config		= &JComponentHelper::getParams( 'com_users' );
			$newGrp		= $config->get( 'new_usertype' );
			$user->set( 'gid', $acl->get_group_id( $newGrp, null, 'ARO' ) );
		}

		$userObjectID 	= $acl->get_object_id( 'users', $user->get('id'), 'ARO' );
		$userGroups 	= $acl->get_object_groups( $userObjectID, 'ARO' );
		$userGroupName 	= strtolower( $acl->get_group_name( $userGroups[0], 'ARO' ) );

		$myObjectID 	= $acl->get_object_id( 'users', $myuser->get('id'), 'ARO' );
		$myGroups 		= $acl->get_object_groups( $myObjectID, 'ARO' );
		$myGroupName 	= strtolower( $acl->get_group_name( $myGroups[0], 'ARO' ) );;

		/**
                 *  ensure user can't add/edit group higher than themselves
		 * NOTE : This check doesn't work commented out for the time being
                 */
		if ( is_array( $myGroups ) && count( $myGroups ) > 0 )
		{
			$excludeGroups = (array) $acl->get_group_children( $myGroups[0], 'ARO', 'RECURSE' );
		}
		else
		{
			$excludeGroups = array();
		}

		if ( in_array( $userGroups[0], $excludeGroups ) )
		{
			$mainframe->redirect( 'index.php?option=com_users', JText::_('NOT_AUTH') );
		}

		/*
		if ( $userGroupName == 'super administrator' )
		{
			// super administrators can't change
	 		$lists['gid'] = '<input type="hidden" name="gid" value="'. $currentUser->gid .'" /><strong>'. JText::_( 'Super Administrator' ) .'</strong>';
		}
		else if ( $userGroupName == $myGroupName && $myGroupName == 'administrator' ) {
		*/

		if ( $userGroupName == $myGroupName && $myGroupName == 'administrator' )
		{
			/**
                         *  administrators can't change each other
                         */
			$lists['gid'] = '<input type="hidden" name="gid" value="'. $user->get('gid') .'" /><strong>'. JText::_( 'Administrator' ) .'</strong>';
		}
		else
		{
			$gtree = $acl->get_group_children_tree( null, 'USERS', false );

			// remove users 'above' me
			//$i = 0;
			//while ($i < count( $gtree )) {
			//	if ( in_array( $gtree[$i]->value, (array)$excludeGroups ) ) {
			//		array_splice( $gtree, $i, 1 );
			//	} else {
			//		$i++;
			//	}
			//}

			$lists['gid'] 	= JHTML::_('select.genericlist',   $gtree, 'gid', 'size="10" onclick="disableCheckMultiGroup();"', 'value', 'text', $user->get('gid') );
			$lists['multipleGroups'] 	= JHTML::_('select.genericlist',   $gtree, 'multiplegroups[]', 'multiple="multiple" size="10"', 'value', 'text', $user->get('gid'),'multiplegroups' );
		}

		/**
                 *  build the html select list
                 */
		$lists['block'] 	= JHTML::_('select.booleanlist',  'block', 'class="inputbox" size="1"', $user->get('block') );

		/**
                 *  build the html select list
                 */
		$lists['sendEmail'] = JHTML::_('select.booleanlist',  'sendEmail', 'class="inputbox" size="1"', $user->get('sendEmail') );
		
		/**
                 * get groups
                 */
		$aclGroups = $acl->sort_groups();
		/**
                 * format groups
                 */
		$groups = $acl->format_groups( $aclGroups,'html',28 );
		
		/**
                 * remove default groups
                 */
		unset($groups[30]); //Public Backend
		unset($groups[23]); //Manager
		unset($groups[24]); //Administrator
		unset($groups[25]); //Super Administrator
		unset($groups[29]); //Public Frontend
		unset($groups[18]); //Registered
		unset($groups[19]); //Author
		unset($groups[20]); //Editor
		unset($groups[21]); //Published
		
		/**
                 * getting multigroups
                 */
		$query_multigroups = "SELECT id_group FROM #__noixacl_multigroups WHERE id_user = {$user->id}";
		$db->setQuery( $query_multigroups );
		$resultMultiGroups = $db->loadObjectList();
		
		if( empty($resultMultiGroups) ){
			$arrMultiGroups = array();
		}
		else{
			foreach($resultMultiGroups as $multiGroup){
				$arrMultiGroups[] = $multiGroup->id_group;
			}
		}

		
		$this->assignRef('me', 		$me);
		$this->assignRef('lists',	$lists);
		$this->assignRef('user',	$user);
		$this->assignRef('contact',	$contact);
		$this->assignRef('groups',	$groups);
		$this->assignRef('UserMultiGroup', $arrMultiGroups);
		parent::display($tpl);
	}
    
}