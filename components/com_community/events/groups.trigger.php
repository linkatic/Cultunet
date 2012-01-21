<?php
/**
 * @category	Events
 * @package		JomSocial
 * @copyright (C) 2010 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CGroupsTrigger
{
	public function onGroupCreate( $group )
	{
		$config		=& CFactory::getConfig();
				
		// Send an email notification to the site admin's when there is a new group created
		if( $config->get( 'moderategroupcreation' ) )
		{
			$userModel	=& CFactory::getModel( 'User' );
			$my			= CFactory::getUser();
			$admins		= $userModel->getSuperAdmins();

			// Add notification
			CFactory::load( 'libraries' , 'notification' );

			//Send notification email to administrators
			foreach( $admins as $row )
			{
				if( $row->sendEmail )
				{
					$params	= new JParameter( '' );
					$params->set('url' , JURI::root() . 'administrator/index.php?option=com_community&view=groups' );
					$params->set('groupName' , $group->name );

					CNotificationLibrary::add( 'groups.notify.admin' , $my->id , $row->id , JText::sprintf( 'CC GROUP CREATED MAIL SUBJECT' , $group->name ) , '' , 'groups.notifyadmin' , $params );
				}
			}
		}
	}
	
	public function onGroupJoin( $group , $userId )
	{
		//@rule: Clear existing invites fromt he invitation table once the user joined the group
		$groupInvite		=& JTable::getInstance( 'GroupInvite' , 'CTable' );

		if( $groupInvite->load( $group->id , $userId ) )
		{
			$groupInvite->delete();
		}
		
		$member		=& JTable::getInstance( 'GroupMembers' , 'CTable' );
		$member->load( $userId , $group->id );
		
		$params		= $group->getParams();

		//@rule: Send notification when necessary
		if($params->get('joinrequestnotification') || $params->get('newmembernotification') )
		{
			$user		= CFactory::getUser( $userId );
			$subject	=  JText::sprintf( 'CC NEW MEMBER JOIN EMAIL SUBJECT' , $user->getDisplayName() , $group->name );
			
			if( !$member->approved )
			{
				$subject	= JText::sprintf( 'CC NEW MEMBER REQUESTED TO JOIN GROUP EMAIL SUBJECT' , $user->getDisplayName() , $group->name );
			}
			
			// Add notification
			CFactory::load( 'libraries' , 'notification' );
			
			$params			= new JParameter( '' );
			$params->set('url' , 'index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id );
			$params->set('group' , $group->name );
			$params->set('user' , $user->getDisplayName() );
			$params->set('approved' , $member->approved );
			CNotificationLibrary::add( 'groups.member.join' , $user->id , $group->ownerid , $subject , '' , 'groups.memberjoin' , $params );
		}
	}

	public function onBulletinDisplay( $row )
	{
		CFactory::load( 'helpers' , 'string' );
		CError::assert( $row->message, '', '!empty', __FILE__ , __LINE__ );

		// @rule: Only nl2br text that doesn't contain html tags
		if( !CStringHelper::isHTML( $row->message ) )
		{
			$row->message	= CStringHelper::nl2br( $row->message );
		}
	}
	
	public function onDiscussionDisplay( $row )
	{
		CFactory::load( 'helpers' , 'string' );
		CError::assert( $row->message, '', '!empty', __FILE__ , __LINE__ );

		// @rule: Only nl2br text that doesn't contain html tags
		if( !CStringHelper::isHTML( $row->message ) )
		{
			$row->message	= CStringHelper::nl2br( $row->message );
		}
	}
}