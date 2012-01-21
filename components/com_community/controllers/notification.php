<?php

/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class CommunityNotificationController extends CommunityBaseController
{
	
	public function ajaxGetNotification()
	{
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}	
	
		$objResponse = new JAXResponse ();
		
		$my	= CFactory::getUser();
		
		$inboxModel		=& CFactory::getModel( 'inbox' );
		$friendModel	=& CFactory::getModel ( 'friends' );
		$eventModel		=& CFactory::getmodel( 'events' );
		
		$inboxHtml			= '';
		$frenHtml			= '';
		$rowHeight			= 50;
		$menuHeight			= 35;
		$extraMenuHeight	= 0;
		$notiTotal			= 0;			

		// getting inbox
		$unreadInbox	= $inboxModel->getUnReadInbox();
        if(! empty( $unreadInbox ))
        {
        	$extraMenuHeight	+= 25;
        	$notiTotal 			+= count($unreadInbox);
			for($i = 0; $i < count($unreadInbox); $i++)
			{
				$row =& $unreadInbox[$i];
				$user = CFactory::getUser( $row->from );
				$row->avatar = $user->getThumbAvatar();
				$row->isUnread = true;
				
				$row->from_name		= $user->getDisplayName();
				$row->profileLink	= CUrlHelper::userLink($user->id);
			}			        
        
			$tmpl = new CTemplate();
			$tmpl->set( 'messages' , $unreadInbox );
	        $inboxHtml	= $tmpl->fetch('notification.unread.inbox');
			$inboxHtml	.= '<br />';        
        }
        
        
        // getting pending fren request
        $pendingFren	= $friendModel->getPending($my->id);
        if(! empty( $pendingFren ))
        {
        	$extraMenuHeight	+= 25;
        	$notiTotal			+= count($pendingFren);
			for($i = 0; $i < count($pendingFren); $i++)
			{
				$row		=& $pendingFren[$i];
				$row->user	= CFactory::getUser($row->id );
				$row->user->friendsCount	= $row->user->getFriendCount();
				$row->user->profileLink		= CUrlHelper::userLink($row->id);
			}			
			
			$tmplF	= new CTemplate();
			
			$tmplF->set( 'rows' 	, $pendingFren );
			$tmplF->setRef( 'my'	, $my );
			$frenHtml = $tmplF->fetch( 'notification.friend.request' );
			$frenHtml	.= "<br />";
		}

		//getting pending event request
		$pendingEvent	= $eventModel->getPending($my->id);
		$eventHtml		= '';

		$event		=& JTable::getInstance( 'Event' , 'CTable' );
		
		if(!empty($pendingEvent)){
        	$extraMenuHeight	+= 25;
        	$notiTotal			+= count($pendingEvent);
			for($i = 0; $i < count($pendingEvent); $i++)
			{
				$row			=&	$pendingEvent[$i];
				$row->invitor	=	CFactory::getUser($row->invited_by);
				$event->load( $row->eventid );
				$row->eventAvatar	=	$event->getThumbAvatar();
				$row->url		=	CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $row->eventid. false);
			}

			$tmpl	= new CTemplate();

			$tmpl->set( 'rows' 	, $pendingEvent );
			$tmpl->setRef( 'my'	, $my );
			$eventHtml = $tmpl->fetch( 'notification.event.invitations' );
		}
        
        $notiHtml	= $inboxHtml . $frenHtml . $eventHtml;
        
        $objResponse->addAssign( 'cWindowContent' , 'innerHTML' , $notiHtml );
        
        $totalHeight	= $menuHeight + $extraMenuHeight + ($notiTotal * $rowHeight);
        
        if ($totalHeight > 450)
        	$totalHeight = 450; /* Max height 450 */
    	
    	$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC NOTIFICATIONS'));
        $objResponse->addScriptCall ( 'cWindowResize', $totalHeight );		
        
        $objResponse->sendResponse();
	
	}
	
	/**
	 * Ajax function to reject a friend request
	 **/
	public function ajaxRejectRequest( $requestId )
	{
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}	
	
		$objResponse	= new JAXResponse();
		$my				= CFactory::getUser();
		$friendsModel	=& CFactory::getModel('friends');

		if( $friendsModel->isMyRequest( $requestId , $my->id) )
		{
			$pendingInfo = $friendsModel->getPendingUserId($requestId);
			
			if( $friendsModel->rejectRequest( $requestId ) )
			{
				//add user points - friends.request.reject removed @ 20090313
								
				$objResponse->addScriptCall( 'joms.jQuery("#msg-pending-' . $requestId . '").html("'.JText::_('CC FRIEND REQUEST REJECTED').'");');
				$objResponse->addScriptCall( 'joms.notifications.updateNotifyCount();');
				$objResponse->addScriptCall( 'joms.jQuery("#noti-pending-' . $requestId . '").fadeOut(1000, function() { joms.jQuery("#noti-pending-' . $requestId . '").remove();} );');
			
				//trigger for onFriendReject
				require_once(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'controllers' . DS . 'friends.php');	
				$eventObject = new stdClass();
				$eventObject->profileOwnerId 	= $my->id;
				$eventObject->friendId 			= $pendingInfo->connect_from;
				CommunityFriendsController::triggerFriendEvents( 'onFriendReject' , $eventObject);
				unset($eventObject);
			}
			else
			{
				$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $requestId . '").html("' . JText::sprintf('CC FRIEND REQUEST REJECT FAILED', $requestId ) . '");' );
				$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $requestId . '").attr("class", "error");');
			}

		}
		else
		{
			$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $requestId . '").html("' . JText::_('CC NOT YOUR REQUEST') . '");' );
			$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $requestId . '").attr("class", "error");');
		}

		return $objResponse->sendResponse();
	}
		
	/**
	 * Ajax function to approve a friend request
	 **/
	public function ajaxApproveRequest( $requestId )
	{
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}	
	
		$objResponse	= new JAXResponse();
		$my				= CFactory::getUser();
		$friendsModel	=& CFactory::getModel( 'friends' );

		if( $friendsModel->isMyRequest( $requestId , $my->id) )		
		{
			$connected		= $friendsModel->approveRequest( $requestId );

			if( $connected )			
			{
				$act			= new stdClass();
				$act->cmd 		= 'friends.request.approve';
				$act->actor   	= $connected[0];
				$act->target  	= $connected[1];
				$act->title	  	= JText::_('CC ACTIVITIES FRIENDS NOW');
				$act->content	= '';
				$act->app		= 'friends';
				$act->cid		= 0;

				CFactory::load ( 'libraries', 'activities' );
				CActivityStream::add($act);
				
				//add user points - give points to both party
				CFactory::load( 'libraries' , 'userpoints' );		
				CUserPoints::assignPoint('friends.request.approve');				

				$friendId		= ( $connected[0] == $my->id ) ? $connected[1] : $connected[0];
 				$friend			= CFactory::getUser( $friendId );
 				CUserPoints::assignPoint('friends.request.approve', $friendId);

				// Add the friend count for the current user and the connected user
				$friendsModel->addFriendCount( $connected[0] );
				$friendsModel->addFriendCount( $connected[1] );
				
				// Add notification
				CFactory::load( 'libraries' , 'notification' );
				
				$params			= new JParameter( '' );
				$params->set( 'url' , 'index.php?option=com_community&view=profile&userid='.$my->id );

				CNotificationLibrary::add( 'friends.create.connection' , $my->id , $friend->id , JText::sprintf('CC FRIEND REQUEST APPROVED', $my->getDisplayName() ) , '' , 'friends.approve' , $params );		
				
				$objResponse->addScriptCall( 'joms.jQuery("#msg-pending-' . $requestId . '").html("'.addslashes(JText::sprintf('CC FRIENDS NOW', $friend->getDisplayName())).'");');
				$objResponse->addScriptCall( 'joms.notifications.updateNotifyCount();');
				$objResponse->addScriptCall( 'joms.jQuery("#noti-pending-' . $requestId . '").fadeOut(1000, function() { joms.jQuery("#noti-pending-' . $requestId . '").remove();} );');				
			
				//trigger for onFriendApprove
				require_once(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'controllers' . DS . 'friends.php');	
				$eventObject = new stdClass();
				$eventObject->profileOwnerId 	= $my->id;
				$eventObject->friendId 			= $friendId;
				CommunityFriendsController::triggerFriendEvents( 'onFriendApprove' , $eventObject);
				unset($eventObject);
			}
		}
		else
		{
			$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $requestId . '").html("' . JText::_('CC NOT YOUR REQUEST') . '");' );
			$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $requestId . '").attr("class", "error");');
		}

		return $objResponse->sendResponse();
	}


	/**
	 * Ajax function to join an event invitation
	 *
	 **/
	public function ajaxJoinInvitation( $invitationId, $eventId){

		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}

		$objResponse	=	new JAXResponse();
		$my				=	CFactory::getUser();

		// Get events model
		$model	=&	CFactory::getModel('events');

		if( $model->isInvitedMe( $invitationId , $my->id) ){
			$event	=& JTable::getInstance( 'Event' , 'CTable' );
			$event->load( $eventId );

			$guest	=& JTable::getInstance( 'EventMembers' , 'CTable' );
			$guest->load($my->id, $eventId);

			// Set status to "CONFIRMED"
			$guest->status = COMMUNITY_EVENT_STATUS_ATTEND;
			$guest->store();

			// Update event stats count
			$event->updateGuestStats();
			$event->store();

			// Activity stream purpose
			$act = new stdClass();
			$act->cmd 		= 'event.join';
			$act->actor   	= $my->id;
			$act->target  	= 0;
			$act->title	  	= JText::sprintf('CC ACTIVITIES EVENT ATTEND' , $event->title);
			$act->content	= '';
			$act->app		= 'events';
			$act->cid		= $event->id;

			$params 		= new JParameter('');
			$action_str  	= 'event.join';
			$params->set( 'eventid' , $event->id);
			$params->set( 'action', $action_str );
			$params->set( 'event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);

			// Add activity logging
			CFactory::load ( 'libraries', 'activities' );
			CActivityStream::add( $act, $params->toString() );
			
			$url	=	CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);
			
			$objResponse->addScriptCall( 'joms.jQuery("#msg-pending-' . $invitationId  . '").html("'.addslashes(JText::sprintf('CC JOINED EVENT', $event->title , $url )).'");');
			$objResponse->addScriptCall( 'joms.notifications.updateNotifyCount();');
			$objResponse->addScriptCall( 'joms.jQuery("#noti-pending-' . $invitationId  . '").fadeOut(1000, function() { joms.jQuery("#noti-pending-' . $invitationId . '").remove();} );');

		}else{
			$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $invitationId  . '").html("' . JText::_('CC YOU ARE NOT INVITED') . '");' );
			$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $invitationId  . '").attr("class", "error");');
		}

		return $objResponse->sendResponse();
	}


	/**
	 * Ajax function to reject an event invitation
	 *
	 **/
	public function ajaxRejectInvitation( $invitationId, $eventId){

		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}

		$objResponse	=	new JAXResponse();
		$my				=	CFactory::getUser();

		// Get events model
		$model	=&	CFactory::getModel('events');

		if( $model->isInvitedMe( $invitationId , $my->id) ){
			$event	=& JTable::getInstance( 'Event' , 'CTable' );
			$event->load( $eventId );

			$guest	=& JTable::getInstance( 'EventMembers' , 'CTable' );
			$guest->load($my->id, $eventId);

			// Set status to "REJECTED"
			$guest->status = COMMUNITY_EVENT_STATUS_WONTATTEND;
			$guest->store();

			// Update event stats count
			$event->updateGuestStats();
			$event->store();
			
			$url	=	CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);
			
			$objResponse->addScriptCall( 'joms.jQuery("#msg-pending-' . $invitationId  . '").html("'.addslashes(JText::sprintf('CC REJECT EVENT INVITATION', $event->title , $url )).'");');
			$objResponse->addScriptCall( 'joms.notifications.updateNotifyCount();');
			$objResponse->addScriptCall( 'joms.jQuery("#noti-pending-' . $invitationId  . '").fadeOut(1000, function() { joms.jQuery("#noti-pending-' . $invitationId . '").remove();} );');

		}else{
			$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $invitationId  . '").html("' . JText::_('CC YOU ARE NOT INVITED') . '");' );
			$objResponse->addScriptCall( 'joms.jQuery("#error-pending-' . $invitationId  . '").attr("class", "error");');
		}

		return $objResponse->sendResponse();
	}
}