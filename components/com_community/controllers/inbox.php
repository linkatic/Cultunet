<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ();

class CommunityInboxController extends CommunityBaseController {
	var $_icon = 'inbox';

    function ajaxIphoneInbox()
    {
		$objResponse	= new JAXResponse();		
		$document		=& JFactory::getDocument();
		
		$viewType	= $document->getType(); 		 	
		$view		=& $this->getView( 'inbox', '', $viewType );
						
		
		$html = '';
		
		ob_start();				
		$this->display();				
		$content = ob_get_contents();
		ob_end_clean();
		
		$tmpl			= new CTemplate();
		$tmpl->set('toolbar_active', 'inbox');
		$simpleToolbar	= $tmpl->fetch('toolbar.simple');		
		
		$objResponse->addAssign('social-content', 'innerHTML', $simpleToolbar . $content);
		return $objResponse->sendResponse();		    	
    }

	function display() {
		$model	=& $this->getModel ( 'inbox' );
		$msg	=& $model->getInbox ();
		$modMsg	= array ();

		$view	=& $this->getView ( 'inbox' );
		$my		=& JFactory::getUser ();
		
		if($my->id == 0)
		{
			return $this->blockUnregister();
		}
		
		// Add small avatar to each image
		if (! empty ( $msg ))
		{
			foreach ( $msg as $key => $val )
			{
				// based on the grouped message parent. check the unread message
				// count for this user.
				$filter ['parent'] = $val->parent;
				$filter ['user_id'] = $my->id;
				$unRead = $model->countUnRead ( $filter );
				$msg [$key]->unRead = $unRead;
			}
		}
		$data = new stdClass ( );
		$data->msg = $msg;

		$newFilter ['user_id'] = $my->id;
		$data->inbox = $model->countUnRead ( $newFilter );
		$data->pagination = & $model->getPagination ();
		echo $view->get ( 'inbox', $data );
	}

	/**
	 * @todo: user should be loaded from library or other model
	 */
	function write()
	{
		CFactory::setActiveProfile ();
		$mainframe =& JFactory::getApplication();
		$my		= CFactory::getUser ();
		$view	= & $this->getView ( 'inbox' );
		$data	= new stdClass ( );
		
		if($my->id == 0)
		{
		return $this->blockUnregister();
		}

		$data->to			= JRequest::getVar ( 'to', '', 'POST' );
		$data->subject		= JRequest::getVar ( 'subject', '', 'POST' );		
		$data->body			= JRequest::getVar ( 'body', '', 'POST' );
		$data->sent			= 0;
		$model				= & $this->getModel ( 'user' );
		$actualTo			= array ();
		
		// are we saving ??
		if ($saving = JRequest::getVar ( 'action', '', 'POST' ))
		{
			CFactory::load( 'libraries' , 'apps' );
			$appsLib		=& CAppPlugins::getInstance();
			$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array('jsform-inbox-write' ));
			
			if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
			{
				// @rule: Check if user exceeded limit
				$inboxModel		=& $this->getModel ( 'inbox' );
				$config			=& CFactory::getConfig();
				$useRealName	= ($config->get('displayname') == 'name') ? true : false;
				
				$maxSent		= $config->get('pmperday');
				$totalSent		= $inboxModel->getTotalMessageSent( $my->id );
				
				if( $totalSent >=  $maxSent && $maxSent != 0 )
				{
					$mainframe->redirect(CRoute::_('index.php?option=com_community&view=inbox' , false) , JText::_('CC PM LIMIT REACHED'));
				}
	
				$validated = true;
				// perform some validation
				if (empty ( $data->subject ))
				{
					$view->addWarning ( JText::_('CC SUBJECT MISSING') );
					$validated = false;
				}
	
				if (empty ( $data->body ))
				{
					$view->addWarning ( JText::_('CC MESSAGE EMPTY') );
					$validated = false;
				}
	
				if (empty ( $data->to ))
				{
					$view->addWarning ( JText::_('CC RECEIVER MISSING') );
					$validated = false;
				}
				else
				{
					// make sure user is valid
					$validUser = $model->getUserId ( JString::trim( $data->to ), $useRealName );
					if(empty ( $validUser ))
					{
						$view->addWarning ( JText::sprintf('CC MESSAGE USER NOT FOUND', $data->to ) );
						$validated = false;
					}
					else
					{
						$actualTo[] = $validUser;
					}
				}
	
				// store message
				if ($validated)
				{
					$model = & $this->getModel ( 'inbox' );
	
					$msgData		= JRequest::get( 'POST' );
					$msgData ['to'] = $actualTo;
	
					$msgid = $model->send ( $msgData );
					$data->sent = 1;
					
					//add user points
					CFactory::load( 'libraries' , 'userpoints' );		
					CUserPoints::assignPoint('inbox.message.send');				
	
					// Add notification
					CFactory::load( 'libraries' , 'notification' );
					
					$params			= new JParameter( '' );
					$params->set('url' , 'index.php?option=com_community&view=inbox&task=read&msgid='. $msgid );
			
					CNotificationLibrary::add( 'inbox.create.message' , $my->id , $msgData[ 'to' ] , JText::sprintf('CC SENT YOU MESSAGE', $my->getDisplayName()) , '' , 'inbox.sent' , $params );
					
					$mainframe->redirect(CRoute::_('index.php?option=com_community&view=inbox&task=read&msgid=' . $msgid , false) , JText::_('CC MESSAGE SENT'));
					return;
				}
			}
		}
		$inModel	=& $this->getModel ( 'inbox' );

		$newFilter ['user_id'] = $my->id;
		$data->inbox = $inModel->countUnRead ( $newFilter );
		$this->_icon = 'compose';
		echo $view->get ( 'write', $data );
	}
	
	/**
	 * Remove the selected message
	 */
	function remove() {
		$msgId = JRequest::getVar ( 'msgid', '', 'GET' );
		$my = & JFactory::getUser ();
		$view = & $this->getView ( 'inbox' );
		$model = & $this->getModel ( 'inbox' );
		
		if($my->id == 0)
		{
		return $this->blockUnregister();
		}

		if ($model->removeReceivedMsg ( $msgId, $my->id )) {
			$view->addInfo ( JText::_('CC MESSAGE REMOVED' ) );
		} else {
			$view->addInfo ( JText::_('CC MESSAGE FAILED REMOVE' ));
		}
		$this->display ();
	}

	/**
	 * View all sent emails
	 */
	function sent() {
		CFactory::setActiveProfile ();
		$model = & $this->getModel ( 'inbox' );
		$msg = & $model->getSent ();
		$modMsg = array ();
		


		$view = & $this->getView ( 'inbox' );

		// Add small avatar to each image
		$avatarModel = & $this->getModel ( 'avatar' );
		if (! empty ( $msg )) {
			foreach ( $msg as $key => $val ) {			
			
				if (is_array ( $val->to )) { // multiuser


					$tmpNameArr = array ();
					$tmpAvatar = array ();

					//avatar
					foreach ( $val->to as $toId ) {
						$user			= CFactory::getUser( $toId );
						$tmpAvatar []	= $user->getThumbAvatar();
						$tmpNameArr [] 	= $user->getDisplayName();
					}

					$msg [$key]->smallAvatar	= $tmpAvatar;
					$msg [$key]->to_name 		= $tmpNameArr;
				}
			}
		}
		
		$data = new stdClass ( );
		$data->msg = $msg;

		$my = & JFactory::getUser ();
		$newFilter ['user_id'] = $my->id;
		
		if($my->id == 0)
		{
		return $this->blockUnregister();
		}

		$data->inbox = $model->countUnRead ( $newFilter );
		$data->pagination = & $model->getPagination ();
		$this->_icon = 'sent';
		echo $view->get ( 'sent', $data );
	}

	/**
	 * Open the message thread for reading
	 */
	function read() {
		$msgId = JRequest::getVar ( 'msgid', '', 'REQUEST' );
		$my = & JFactory::getUser ();
		
		if($my->id == 0)
		{
		return $this->blockUnregister();
		}

		$filter = array ();

		$filter ['msgId'] = $msgId;
		$filter ['to'] = $my->id;

		$model = & $this->getModel ( 'inbox' );
		$view = & $this->getView ( 'inbox' );
		$data	= new stdClass();
		$data->messages = $model->getMessages ( $filter );

		// mark as "read"
		$filter ['parent'] = $msgId;
		$filter ['user_id'] = $my->id;
		$model->markMessageAsRead ( $filter );			

		// ok done. display the messages.
		echo $view->get ( 'read', $data );

	}

	/**
	 * Reply a message
	 */
	function reply() {
		$msgId = JRequest::getVar ( 'msgid', '', 'REQUEST' );

		$my = CFactory::getUser ();
		$model = & $this->getModel ( 'inbox' );
		$view = & $this->getView ( 'inbox' );
		$allowReply = 1;
		
		if($my->id == 0)
		{
		return $this->blockUnregister();
		}

		$message = $model->getMessage ( $msgId );
		$messageRecepient = $model->getUserMessage ( $msgId );

		// make sure we can only reply to message that belogn to current user
		$myMsg = true;
		if (! empty ( $message )) {
			$myMsg = ($my->id == $message->from);
		}

		if (! empty ( $messageRecepient )) {
			$myMsg = ($my->id == $messageRecepient->to);
		}

		if (! $myMsg) {
			//show warning
			$view->addWarning ( 'CC NOT ALLOWED TO REPLY MESSAGE' );
			$allowReply = 0;
		}

		$cDate = & JFactory::getDate (); //get the current date from system.
		$obj = new stdClass ( );
		$obj->id        = null;
		$obj->from      = $my->id;
		$obj->posted_on = $cDate->toMySQL ();
		$obj->from_name = $my->name;
		$obj->subject   = 'RE:' . $message->subject;
		$obj->body      = JRequest::getVar ( 'body', '', 'POST' );

		if ('doSubmit' == JRequest::getVar ( 'action', '', 'POST' )) {
			$model->sendReply ( $obj, $msgId );
			$view->addInfo ( JText::_('CC MESSAGE SENT'));
			
			//add user points
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('inbox.message.reply');			
		}

		$data = array ();
		$data ['reply_to'] = $message->from_name;
		$data ['allow_reply'] = $allowReply;

		echo $view->get ( 'reply', $data );
	}
	
	/**
	 * Remove a message via ajax
	 * A user can only remove a message that he can read/reply to.
	 */
	function ajaxRemoveFullMessages($msgId){

		$objResponse = new JAXResponse ( );
		$my 	= CFactory::getUser ();
		$view 	= & $this->getView ( 'inbox' );
		$model 	= & $this->getModel ( 'inbox' );
			
		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		
		$conv	= $model->getFullMessages($msgId);						
		$delCnt = 0;
		
		if(! empty($conv))
		{
			foreach($conv as $msg)
			{
				if($model->canReply($my->id, $msg->id)) {
					if ($model->removeReceivedMsg ( $msg->id, $my->id )) {						
						$delCnt++;
					}//end if
				}//end if
			}//end foreach
		}//end if

		if($delCnt > 0) {
			$objResponse->addScriptCall ( 'joms.jQuery(\'#message-'. $msgId .'\').remove' );
		}

		$objResponse->sendResponse ();
	}
	
	/**
	 * Remove a sent message via ajax
	 * A user can only remove a sent message that he can read/reply to.
	 */
	function ajaxRemoveSentMessages($msgId){

		$objResponse = new JAXResponse ( );
		$my 	= CFactory::getUser ();
		$view 	= & $this->getView ( 'inbox' );
		$model 	= & $this->getModel ( 'inbox' );
			
		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		
		$conv	= $model->getSentMessages($msgId);						
		$delCnt = 0;
		
		if(! empty($conv))
		{
			foreach($conv as $msg)
			{
				if($model->canReply($my->id, $msg->id)) {
					if ($model->removeReceivedMsg ( $msg->id, $my->id )) {						
						$delCnt++;
					}//end if
				}//end if
			}//end foreach
		}//end if

		if($delCnt > 0) {
			$objResponse->addScriptCall ( 'joms.jQuery(\'#message-'. $msgId .'\').remove' );
		}

		$objResponse->sendResponse ();
	}		

	/**
	 * Remove a message via ajax
	 * A user can only remove a message that he can read/reply to.
	 */
	function ajaxRemoveMessage($msgId){

		$objResponse = new JAXResponse ( );
		$my 	= CFactory::getUser ();
		$view 	= & $this->getView ( 'inbox' );
		$model 	= & $this->getModel ( 'inbox' );
		
		if($my->id == 0)
		{
		return $this->ajaxBlockUnregister();
		}

		if($model->canReply($my->id, $msgId)) {
			if ($model->removeReceivedMsg ( $msgId, $my->id )) {
				$objResponse->addScriptCall ( 'joms.jQuery(\'#message-'. $msgId .'\').remove' );
			}
		} else {
			$objResponse->addScriptCall('alert', JText::_('CC PERMISSION DENIED'));
		}

		$objResponse->sendResponse ();
	}

	/**
	 * Add reply via ajax
	 * @todo: check permission and message ownership
	 */
	function ajaxAddReply($msgId, $reply) {

		$objResponse = new JAXResponse ( );

		$my = CFactory::getUser ();
		$model = & $this->getModel ( 'inbox' );
		$message = $model->getMessage ( $msgId );
		$messageRecepient = $model->getParticipantsID ( $msgId , $my->id);
		
		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		$objResponse->addScriptCall ( 'joms.jQuery(\'textarea.replybox\').css(\'disabled\', false);' );
		$objResponse->addScriptCall ( 'joms.jQuery(\'div.ajax-wait\').hide();' );

		if ( empty ( $reply ))
		{
			$objResponse->addScriptCall( 'alert' , JText::_( 'CC MESSAGE CANNOT BE EMPTY' ) );
			$objResponse->sendResponse ();
			return;
		}

		if ( empty ( $messageRecepient ))
		{
			$objResponse->addScriptCall( 'alert' , JText::_( 'CC MESSAGE CANNOT FIND RECIPIENT' ) );
			$objResponse->sendResponse ();
			return;
		}

		// make sure we can only reply to message that belogn to current user
		if ( !$model->canReply($my->id, $msgId) )
		{
			$objResponse->addScriptCall( 'alert' , JText::_( 'CC PERMISSION DENIED' ) );
			$objResponse->sendResponse ();
			return;
		}


		//$cDate =& JFactory::getDate();//get the current date from system.
		//$cDate = & gmdate ( 'Y-m-d H:i:s' ); //get the current date from system. use gmd
		//$date = cGetDate();
		$date	=& JFactory::getDate(); //get the time without any offset!
		

		$obj = new stdClass ( );
		$obj->id = null;
		$obj->from = $my->id;
		$obj->posted_on = $date->toMySQL();
		$obj->from_name = $my->name;
		$obj->subject = 'RE:' . $message->subject;
		$obj->body = $reply;

		$model->sendReply ( $obj, $msgId );
		$deleteLink = CRoute::_('index.php?option=com_community&view=inbox&task=remove&msgid='.$obj->id);
		$authorLink	= CRoute::_('index.php?option=com_community&view=profile&userid=' . $my->id );
		
		//add user points
		CFactory::load( 'libraries' , 'userpoints' );		
		CUserPoints::assignPoint('inbox.message.reply');

		// Add notification
		CFactory::load( 'libraries' , 'notification' );
				
		foreach($messageRecepient as $row)
		{
			$params			= new JParameter( '' );
			$params->set('url' , 'index.php?option=com_community&view=inbox&task=read&msgid='. $msgId );
	
			CNotificationLibrary::add( 'inbox.create.reply' , $my->id , $row , JText::sprintf('CC SENT YOU MESSAGE', $my->getDisplayName()) , '' , 'inbox.sent' , $params );
		}
		
		// onMessageDisplay Event trigger
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();		
		$args = array();
		$args[]	=& $obj;
		$appsLib->triggerEvent( 'onMessageDisplay' , $args );		

		$tmpl = new CTemplate();
		$tmpl->set( 'user', CFactory::getUser($obj->from) );
		$tmpl->set( 'msg', $obj );
		$tmpl->set( 'removeLink', $deleteLink);
		$tmpl->set( 'authorLink', $authorLink );
		$html = $tmpl->fetch( 'inbox.message' );

		$objResponse->addScriptCall ( 'cAppendReply', $html );

		return $objResponse->sendResponse ();
	}

	/**
	 * @todo: check permission and message ownership
	 */
	function ajaxCompose($id) {
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}

		$objResponse = new JAXResponse ( );
		$config =& CFactory::getConfig();
		$user 	= CFactory::getUser($id);
		$my 	= CFactory::getUser();
		
		if($my->id == 0)
		{
		return $this->ajaxBlockUnregister();
		}
		
		// Block banned users
		if( $this->block($id,'inbox') ){
            $this->ajaxblock();
        }
		
		CFactory::load( 'helpers', 'time' );
		
		$inboxModel =& $this->getModel( 'inbox' );
		$lastSent	= $inboxModel->getLastSentTime($my->id);
		$dateNow = new JDate();
		
		
		CFactory::load( 'helpers' , 'owner' );		

		// We need to make sure that this guy are not spamming other people inbox 
		// by checking against his last message time. Make sure it doesn't exceed
		// pmFloodLimit config (in seconds)
		if( ($dateNow->toUnix() - $lastSent->toUnix()) < $config->get( 'floodLimit' ) && !COwnerHelper::isCommunityAdmin() ){
			$html = '<dl id="system-message"><dt class="notice">Notice</dt><dd class="notice message"><ul><li>';
			$html .= JText::sprintf('CC PLEASE WAIT BEFORE SENDING MESSAGE', $config->get( 'floodLimit' )); 
			$html .= '</li></ul></dd></dl>';
			
			$objResponse->addAssign ( 'cWindowContent', 'innerHTML', $html );
			$objResponse->addScriptCall ( 'cWindowResize', '120' );
			
			$action = '<button class="button" onclick="cWindowHide();" name="cancel">' . JText::_('CC BUTTON CLOSE') . '</button>';
			$objResponse->addScriptCall('cWindowActions', $action);
			return $objResponse->sendResponse ();
		}
		

		$tmpl = new CTemplate();
		$tmpl->set('user', $user);
		$html = $tmpl->fetch('inbox.ajaxcompose');

		$action = '<button class="button" onclick="javascript:return joms.messaging.send();" name="send">'. JText::_('CC BUTTON SEND') .'</button>&nbsp;';
		$action .= '<button class="button" onclick="javascript:cWindowHide();" name="cancel">'. JText::_('CC BUTTON CANCEL') .'</button>';
		
		// Change cWindow title
		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC TITLE COMPOSE'));
		$objResponse->addAssign('cWindowContent', 'innerHTML', $html );
		$objResponse->addScriptCall('cWindowActions', $action);
		$objResponse->addScriptCall('cWindowResize', 230 );

		return $objResponse->sendResponse();
	}

	/**
	 * A new message submitted via ajax
	 */
	function ajaxSend($postVars) {
		$objResponse	= new JAXResponse ( ); //
		$config			=& CFactory::getConfig();
		$my				= CFactory::getUser ();
		
		if($my->id == 0)
		{
		return $this->ajaxBlockUnregister();
		}
		
		CFactory::load( 'helpers', 'time' );
		
		$inboxModel =& $this->getModel( 'inbox' );
		$lastSent	= $inboxModel->getLastSentTime($my->id);
		$dateNow = new JDate();
		
		CFactory::load( 'helpers' , 'owner' );
		// We need to make sure that this guy are not spamming other people inbox 
		// by checking against his last message time. Make sure it doesn't exceed
		// pmFloodLimit config (in seconds)
		if( ($dateNow->toUnix() - $lastSent->toUnix()) < $config->get( 'floodLimit' ) && !COwnerHelper::isCommunityAdmin() ){
			$html = '<dl id="system-message"><dt class="notice">Notice</dt><dd class="notice message"><ul><li>';
			$html .= JText::sprintf('CC PLEASE WAIT BEFORE SENDING MESSAGE', $config->get( 'floodLimit' )); 
			$html .= '</li></ul></dd></dl>';
			
			$objResponse->addAssign ( 'cWindowContent', 'innerHTML', $html );
			$objResponse->addScriptCall ( 'cWindowResize', 80 );
			
			$action = '<button class="button" onclick="cWindowHide();" name="cancel">' . JText::_('CC BUTTON CLOSE') . '</button>';
			$objResponse->addScriptCall('cWindowActions', $action);
			return $objResponse->sendResponse ();
		}


		$postVars = CAjaxHelper::toArray ( $postVars );
		$doCont   = true;
		$errMsg   = "";
		$resizeH  = 0;

		if( empty($postVars['subject']) || JString::trim($postVars['subject']) == '' )
		{
			$errMsg = '<div class="message">'.JText::_('CC SUBJECT MISSING').'</div>';
			$doCont = false;
			$resizeH += 35;
		}


		if( empty($postVars['body']) || JString::trim($postVars['body']) == '' )
		{
			$errMsg .= '<div class="message">'.JText::_('CC MESSAGE MISSING').'</div>';
			$doCont = false;
			$resizeH += 35;
		}

		if($doCont)
		{
			$data = $postVars;
			$model = & $this->getModel ( 'inbox' );

			$pattern 	 = "/<br \/>/i";
			$replacement = "\r\n";
 			$data['body'] = preg_replace($pattern, $replacement, $data['body'] );

			$msgid = $model->send ($data);

			//add user points
			CFactory::load( 'libraries' , 'userpoints' );
			CFactory::load( 'libraries' , 'notification' );	
			CUserPoints::assignPoint('inbox.message.send');

			// Add notification
			$params			= new JParameter( '' );
			$params->set('url' , 'index.php?option=com_community&view=inbox&task=read&msgid='. $msgid );
	
			CNotificationLibrary::add( 'inbox.create.message' , $my->id , $data[ 'to' ] , JText::sprintf('CC SENT YOU MESSAGE', $my->getDisplayName()) , '' , 'inbox.sent' , $params );

			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC MESSAGE SENT'));
			$objResponse->addScriptCall('cWindowResize', 80);
			$action = '<button class="button" onclick="cWindowHide();" name="close">' . JText::_('CC BUTTON CLOSE') . '</button>';
			$objResponse->addScriptCall('cWindowActions', $action);
		} else {
		    //validation failed. display error message.
			$user = CFactory::getUser($postVars['to']);

			$tmpl = new CTemplate();
			$tmpl->set('user', $user);
			$tmpl->set('subject',JString::trim($postVars['subject']));
			$tmpl->set('body',JString::trim($postVars['body']));
			$html = $tmpl->fetch('inbox.ajaxcompose');

			$action = '<button class="button" onclick="javascript:return joms.messaging.send();" name="send">'. JText::_('CC BUTTON SEND') .'</button>&nbsp;';
			$action .= '<button class="button" onclick="javascript:cWindowHide();" name="cancel">'. JText::_('CC BUTTON CANCEL') .'</button>';
			$objResponse->addAssign ( 'cWindowContent', 'innerHTML', $errMsg.$html );
			$objResponse->addScriptCall('cWindowResize', 220 + $resizeH);

			// Change cWindow title
			$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC TITLE COMPOSE'));
			$objResponse->addScriptCall ( 'cWindowActions', $action );
		}
		return $objResponse->sendResponse ();
	}

	/**
	 * @todo: need to filter this down. loading too many user at once
	 */
	function ajaxAutoName() {
		$my 			= CFactory::getUser();
		$config			= CFactory::getConfig();
		$displayName	= $config->get('displayname');		
				
		if($my->id == 0)
		{
		return $this->ajaxBlockUnregister();
		}

		$model = & $this->getModel ( 'user' );
		$friendsModel = & $this->getModel ( 'friends' );

		$friends =& $friendsModel->getFriends($my->id);

		$names = "";

		foreach( $friends as $row ){
			$names .= ($config->get('displayname') == 'name') ? $row->name : $row->username;
			$names .= "|\n";
		}

		echo $names;
		exit ();
	}


	/**
	 * Set message as Read
	 */
	function ajaxMarkMessageAsRead($msgId){

		$objResponse = new JAXResponse ( );
		$my 	= CFactory::getUser ();
		$view 	= & $this->getView ( 'inbox' );
		$model 	= & $this->getModel ( 'inbox' );
				
		if($my->id == 0)
		{
		return $this->ajaxBlockUnregister();
		}

		$filter = array(
			'parent'    => $msgId,
			'user_id'   => $my->id
		);

		$model->markAsRead( $filter );
		$objResponse->addScriptCall ( 'markAsRead', $msgId );
		$objResponse->sendResponse ();
	}



	/**
	 * Set message as Read
	 */
	function ajaxMarkMessageAsUnread($msgId){

		$objResponse = new JAXResponse ( );
		$my 	= CFactory::getUser ();
		$view 	= & $this->getView ( 'inbox' );
		$model 	= & $this->getModel ( 'inbox' );
		
		if($my->id == 0)
		{
		return $this->ajaxBlockUnregister();
		}
		
		$filter = array(
			'parent'    => $msgId,
			'user_id'   => $my->id
		);

		$model->markAsUnread( $filter );
		$objResponse->addScriptCall ( 'markAsUnread', $msgId );
		$objResponse->sendResponse ();
	}
	
	
	function markUnread()
	{
		$mainframe	=& JFactory::getApplication();		
		$my 		=& JFactory::getUser ();		
		$model		=& $this->getModel ( 'inbox' );
		
		if($my->id == 0)
		{
		return $this->blockUnregister();
		}
				
		$msgId 	= JRequest::getVar ( 'msgid', '', 'REQUEST' );
		
		if(empty($msgId))
		{
			$mainframe->redirect(CRoute::_('index.php?option=com_community&view=inbox', false), JText::_('CC INBOX MARK UNREAD FAILED'), 'error');
		}
		else
		{			
			$filter = array(
				'parent'    => $msgId,
				'user_id'   => $my->id
			);
				
			$model->markMessageAsUnread( $filter );
			$mainframe->redirect(CRoute::_('index.php?option=com_community&view=inbox', false), JText::_('CC INBOX MARK UNREAD SUCCESS'));
		}
	}
	
}
