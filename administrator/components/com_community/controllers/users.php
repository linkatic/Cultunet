<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );

/**
 * Jom Social Component Controller
 */
class CommunityControllerUsers extends CommunityController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function delete()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$db 			=& JFactory::getDBO();
		$currentUser 	=& JFactory::getUser();
		$acl			=& JFactory::getACL();
		$cid 			= JRequest::getVar( 'cid', array(), '', 'array' );

		JArrayHelper::toInteger( $cid );

		if (count( $cid ) < 1)
		{
			$msg	= JText::_('CC SELECT A USER TO DELETE');
		}

		foreach ($cid as $id)
		{
			// check for a super admin ... can't delete them
			$objectID 	= $acl->get_object_id( 'users', $id, 'ARO' );
			$groups 	= $acl->get_object_groups( $objectID, 'ARO' );
			$this_group = strtolower( $acl->get_group_name( $groups[0], 'ARO' ) );

			$success = false;
			if ( $this_group == 'super administrator' )
			{
				$msg = JText::_('CC YOU CANNOT DELETE A SUPER ADMINISTRATOR');
			}
			else if ( $id == $currentUser->get( 'id' ) )
			{
				$msg = JText::_('CC YOU CANNOT DELETE YOURSELF');
			}
			else if ( ( $this_group == 'administrator' ) && ( $currentUser->get( 'gid' ) == 24 ) )
			{
				$msg = JText::_('CC WARNDELETE');
			}
			else
			{
				$user =& JUser::getInstance((int)$id);
				$count = 2;

				if ( $user->get( 'gid' ) == 25 )
				{
					// count number of active super admins
					$query = 'SELECT COUNT( id )'
						. ' FROM #__users'
						. ' WHERE gid = 25'
						. ' AND block = 0'
					;
					$db->setQuery( $query );
					$count = $db->loadResult();
				}

				if ( $count <= 1 && $user->get( 'gid' ) == 25 )
				{
					// cannot delete Super Admin where it is the only one that exists
					$msg = JText::_('CC YOU CANNOT DELETE THIS SUPER ADMINISTRATOR AS IT IS THE ONLY ACTIVE SUPER ADMINISTRATOR FOR YOUR SITE');
				}
				else
				{
					// delete user
					$user->delete();
					$msg = JText::_('CC USER DELETED');

					JRequest::setVar( 'task', 'remove' );
					JRequest::setVar( 'cid', $id );

					// delete user acounts active sessions
					$this->logout();
				}
			}
		}

		$this->setRedirect( 'index.php?option=com_community&view=users', $msg);
	}

	/**
	 * Force log out a user
	 */
	function logout( )
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		global $mainframe;

		$db		=& JFactory::getDBO();
		$task 	= $this->getTask();
		$cids 	= JRequest::getVar( 'cid', array(), '', 'array' );
		$client = JRequest::getVar( 'client', 0, '', 'int' );
		$id 	= JRequest::getVar( 'id', 0, '', 'int' );

		JArrayHelper::toInteger($cids);

		if ( count( $cids ) < 1 )
		{
			$this->setRedirect( 'index.php?option=com_users', JText::_('CC USER DELETED') );
			return false;
		}

		foreach($cids as $cid)
		{
			$options = array();

			if ($task == 'logout' || $task == 'block') {
				$options['clientid'][] = 0; //site
				$options['clientid'][] = 1; //administrator
			} else if ($task == 'flogout') {
				$options['clientid'][] = $client;
			}

			$mainframe->logout((int)$cid, $options);
		}


		$msg = JText::_('CC USER SESSION ENDED');
		switch ( $task )
		{
			case 'flogout':
				$this->setRedirect( 'index.php', $msg );
				break;

			case 'remove':
			case 'block':
				return;
				break;

			default:
				$this->setRedirect( 'index.php?option=com_users', $msg );
				break;
		}
	}
	
	/**
	 * Save controller that receives arguments via HTTP POST.
	 **/	 
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$lang	=& JFactory::getLanguage();
		$lang->load('com_users');

		$userId		= JRequest::getVar( 'userid' , '' , 'POST' );
		$mainframe	=& JFactory::getApplication();
		$message	= '';
		$url		= JRoute::_('index.php?option=com_community&view=users' , false );
		$my			=& JFactory::getUser();
		$acl		=& JFactory::getACL();
		$mailFrom	= $mainframe->getCfg('mailfrom');
		$fromName	= $mainframe->getCfg('fromname');
		$siteName	= $mainframe->getCfg('sitename');
			
		if( empty( $userId ) )
		{
			$message	= JText::_('CC UNABLE TO PROCESS EMPTY USER ID');
			$mainframe->redirect( $url , $message ); 	
		}

 		// Create a new JUser object
		$user			= new JUser( $userId );
		$original_gid	= $user->get('gid');

		$post				= JRequest::get('post');
		$post['username']	= JRequest::getVar('username', '', 'post', 'username');
		$post['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['password2']	= JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if (!$user->bind($post))
		{
			$message	= JText::_('CC CANNOT SAVE THE USER INFORMATION') . ' : ' . $user->getError();
			$url		= JRoute::_('index.php?option=com_community&view=users&layout=edit&id=' . $userId , false );
			$mainframe->redirect( $url , $message );
			exit;
		}
				
		$objectID 	= $acl->get_object_id( 'users', $user->get('id'), 'ARO' );
		$groups 	= $acl->get_object_groups( $objectID, 'ARO' );
		$this_group = JString::strtolower( $acl->get_group_name( $groups[0], 'ARO' ) );

		if( $user->get('id') == $my->get( 'id' ) && $user->get('block') == 1 )
		{
			$message	= JText::_('CC YOU CANNOT BLOCK YOURSELF');
			$url		= JRoute::_('index.php?option=com_community&view=users&layout=edit&id=' . $userId , false );
			$mainframe->redirect( $url , $message );
			exit;
		}

		if(( $this_group == 'super administrator' ) && $user->get('block') == 1 )
		{
			$message	= JText::_('CC YOU CANNOT BLOCK A SUPER ADMINISTRATOR');
			$url		= JRoute::_('index.php?option=com_community&view=users&layout=edit&id=' . $userId , false );
			$mainframe->redirect( $url , $message );
			exit;
		}
		
		if(( $this_group == 'administrator' ) && ( $my->get( 'gid' ) == 24 ) && $user->get('block') == 1 )
		{
			$message	= JText::_('CC WARNBLOCK'); 
			$url		= JRoute::_('index.php?option=com_community&view=users&layout=edit&id=' . $userId , false );
			$mainframe->redirect( $url , $message );
			exit;
		}
		
		if(( $this_group == 'super administrator' ) && ( $my->get( 'gid' ) != 25 ) )
		{
			$message	= JText::_('CC YOU CANNOT EDIT A SUPER ADMINISTRATOR ACCOUNT');
			$url		= JRoute::_('index.php?option=com_community&view=users&layout=edit&id=' . $userId , false );
			$mainframe->redirect( $url , $message );
			exit;
		}
		
		$isNew	= $user->get('id') == 0;

		if (!$isNew)
		{
			if ( $user->get('gid') != $original_gid && $original_gid == 25 )
			{
				$query = 'SELECT COUNT( id )'
					. ' FROM #__users'
					. ' WHERE gid = 25'
					. ' AND block = 0';
				$db->setQuery( $query );
				$count = $db->loadResult();

				if( $count <= 1 )
				{
					$message	= JText::_('CC WARN_ONLY_SUPER');
					$url		= JRoute::_('index.php?option=com_community&view=users&layout=edit&id=' . $userId , false );
					$mainframe->redirect( $url , $message );
					exit;
				}
			}
		}

		if (!$user->save())
		{
			$message	= JText::_('CC CANNOT SAVE THE USER INFORMATION') . ' : ' . $user->getError();
			$mainframe->redirect( $url , $message );
			exit;
		}

		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();
		
		$userRow	= array();
		$userRow[]	= $user;
			 
		$appsLib->triggerEvent( 'onUserDetailsUpdate' , $userRow );

		// @rule: Send out email if it is a new user.
		if($isNew)
		{
			$adminEmail = $my->get('email');
			$adminName	= $my->get('name');

			$subject = JText::_('CC NEW_USER_MESSAGE_SUBJECT');
			$message = sprintf ( JText::_('CC NEW_USER_MESSAGE'), $user->get('name'), $siteName, JURI::root(), $user->get('username'), $user->password_clear );

			if ( !empty( $mailfrom ) && !empty( $fromName ) )
			{
				$adminName 	= $fromName;
				$adminEmail = $mailFrom;
			}

			JUtility::sendMail( $adminEmail, $adminName, $user->get('email'), $subject, $message );
		}

		// If updating self, load the new user object into the session
		if($user->get('id') == $my->get('id'))
		{
			// Get an ACL object
			$acl = &JFactory::getACL();

			// Get the user group from the ACL
			$grp = $acl->getAroGroup($user->get('id'));

			// Mark the user as logged in
			$user->set('guest', 0);
			$user->set('aid', 1);

			// Fudge Authors, Editors, Publishers and Super Administrators into the special access group
			if ($acl->is_group_child_of($grp->name, 'Registered') || $acl->is_group_child_of($grp->name, 'Public Backend'))
			{
				$user->set('aid', 2);
			}
			// Set the usertype based on the ACL group name
			$user->set('usertype', $grp->name);

			$session = &JFactory::getSession();
			$session->set('user', $user);
		}

		// Process and save custom fields
		$model		=& $this->getModel( 'users' );
		$userModel	= CFactory::getModel( 'profile' );
		$values		= array();
		$profile	= $model->getEditableProfile( $userId );

		CFactory::load( 'libraries' , 'profile' );
		

		foreach( $profile->fields as $group => $fields )
		{
			foreach( $fields as $data )
			{
				// Get value from posted data and map it to the field.
				// Here we need to prepend the 'field' before the id because in the form, the 'field' is prepended to the id.
				$postData				= JRequest::getVar( 'field' . $data['id'] , '' , 'POST' );
				$values[ $data['id'] ]	= CProfileLibrary::formatData( $data['type']  , $postData );

				// @rule: Validate custom profile if necessary
				if( !CProfileLibrary::validateField( $data['type'] , $values[ $data['id'] ] , $data['required'] ) )
				{
					// If there are errors on the form, display to the user.
					$message	= JText::sprintf('The field "%1$s" contain improper values' ,  $data['name'] );
					$mainframe->redirect( 'index.php?option=com_community&view=users&layout=edit&id=' . $user->id , $message , 'error' );
					return;
				}
			}
		}

		// Update user's parameter DST
		$user		= CFactory::getUser( $userId );
		$params		=& $user->getParams();
		$offset		= $post['daylightsavingoffset'];
		$params->set('daylightsavingoffset', $offset );
		
		// Update user's point
		$points			= JRequest::getVar( 'userpoint' , '' , 'REQUEST' );
		
		if( !empty($points) )
		{
			$user->_points	= $points;
			$user->save();
		}

		// Update user's status
		if( $user->getStatus() != $post['status'] )
		{
			$user->setStatus( $post['status'] );
		}
			
		$user->save('params');

		$valuesCode = array();
		foreach( $values as $key => &$val )
		{
			$fieldCode = $userModel->getFieldCode($key);
			if( $fieldCode )
			{
				$valuesCode[$fieldCode] = &$val;
			}
		}
		
		// Trigger before onBeforeUserProfileUpdate
		$args 	= array();
		$args[]	= $userId;
		$args[]	= $valuesCode;
		$saveSuccess = false;
		$result = $appsLib->triggerEvent( 'onBeforeProfileUpdate' , $args );

		if(!$result || ( !in_array(false, $result) ) )
		{
			$saveSuccess = true;
			$userModel->saveProfile($userId, $values);
		}

		// Trigger before onAfterUserProfileUpdate
		$args 	= array();
		$args[]	= $userId;
		$args[]	= $saveSuccess; 
		$result = $appsLib->triggerEvent( 'onAfterProfileUpdate' , $args );
		
		if(!$saveSuccess)
		{
			$message	= JText::_('CC USER PROFILE NOT UPDATED');
			$mainframe->redirect( $url , $message , 'error');
		}

		$message	= JText::_('CC USER UPDATED SUCCESSFULLY');
		$mainframe->redirect( $url , $message );
	}
	
	// Override parent's toggle publish method
	function ajaxTogglePublish( $id, $field )
	{
		$user	=& JFactory::getUser();

		// @rule: Disallow guests.
		if ( $user->get('guest'))
		{
			JError::raiseError( 403, JText::_('CC ACCESS FORBIDDEN') );
			return;
		}

		$response	= new JAXResponse();

		// Load the JTable Object.
		$row	=& JTable::getInstance( 'User' , 'JTable' );
		$row->load( $id );
		
		if( $row->usertype == 'Super Administrator')
		{
			$response->addScriptCall( 'alert' , JText::_('CC CANNOT BLOCK SUPER ADMINISTRATORS') );
		}
		else
		{
			if( $row->$field == 1 )
			{
				$row->$field	= 0;
				$row->store();
				
				$image			= 'tick.png';
			}
			else
			{
				$row->$field	= 1;
				$row->store();
				
				$image			= 'publish_x.png';
			}
			// Get the view
			$view		=& $this->getView( 'users' , 'html' );
	
			$html	= $view->getPublish( $row , $field , 'users,ajaxTogglePublish' );
		   	
		   	$response->addAssign( $field . $id , 'innerHTML' , $html );
	   	}
	   	return $response->sendResponse();
	}
	
	function ajaxRemoveAvatar( $userId )
	{
		require_once( JPATH_ROOT .DS  . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
		require_once( JPATH_ROOT .DS  . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'apps.php' );
		$user		= CFactory::getUser( $userId );
		$model		= $this->getModel( 'Users' );

		$model->setImage( $user->id , DEFAULT_USER_AVATAR , 'avatar');
		$model->setImage( $user->id , DEFAULT_USER_THUMB , 'thumb');
								
		$message	= JText::_('CC USER PROFILE PICTURE REMOVED');
		$response	= new JAXResponse();
		$avatar		= JURI::root() . DEFAULT_USER_THUMB;
		$response->addScriptCall('joms.jQuery("#user-avatar").attr("src","' . $avatar . '");');
		$response->addScriptCall('joms.jQuery("#user-avatar-message").html("' . $message . '");' );
		$response->addScriptCall('joms.jQuery("#user-avatar-message").hide(5000);' );
		return $response->sendResponse();
	}
}