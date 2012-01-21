<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CommunityFriendsController extends CommunityBaseController
{
	var $task;
	var $_icon = 'friends';
	var $_name = 'friends';

    public function ajaxIphoneFriends()
    {
		$objResponse	= new JAXResponse();		
		$document		=& JFactory::getDocument();
		
		$viewType	= $document->getType(); 		 	
		$view		=& $this->getView( 'friends', '', $viewType );
						
		
		$html = '';
		
		ob_start();				
		$this->display();				
		$content = ob_get_contents();
		ob_end_clean();
		
		$tmpl			= new CTemplate();
		$tmpl->set('toolbar_active', 'friends');
		$simpleToolbar	= $tmpl->fetch('toolbar.simple');		
		
		$objResponse->addAssign('social-content', 'innerHTML', $simpleToolbar . $content);
		return $objResponse->sendResponse();		    	
    }

	public function edit()
	{
		// Get/Create the model
		$model = & $this->getModel('profile');
		$model->setProfile('hello me');

		$this->display(false, __FUNCTION__);
	}

	public function display()
	{
		// By default, display the user profile page
		$this->friends();
	}

	/**
	 * View all friends. Could be current user, if $_GET['id'] is not defined
	 * otherise, show your own friends
	 */
	public function friends()
	{
		CFactory::load('libraries', 'privacy');
		$document =& JFactory::getDocument();
		$my =& JFactory::getUser();
		
		$viewType		= $document->getType();	
		$tagsFriends	= JRequest::getVar( 'tags','','GET');

		$view	=& $this->getView( 'friends','',  $viewType);
		$model	=& $this->getModel('friends');

		// Get the friend id to be displayed
		$id = JRequest::getCmd('userid', $my->id);

		// Check privacy setting
		$accesAllowed = CPrivacy::isAccessAllowed($my->id, $id, 'user', 'privacyFriendsView');
		if(!$accesAllowed || ($my->id == 0 && $id == 0))
		{
			$this->blockUnregister();
			return;
		}
		
		$data	= new stdClass();
		echo $view->get('friends');
	}

	/**
	 * Show the user invite window
	 */
	public function invite()
	{
		$view =& CFactory::getView('friends');
		$validated = false;
		
		$my = CFactory::getUser();
		
		if($my->id == 0)
		{
			return $this->blockUnregister();
		}
		
		if( JRequest::getVar('action', '', 'POST') == 'invite')
		{
			$mainframe =& JFactory::getApplication();


			CFactory::load( 'libraries' , 'apps' );
			$appsLib		=& CAppPlugins::getInstance();
			$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array('jsform-friends-invite' ) );
			
			if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
			{
				$validated 			= true;
				$emailExistError	= array();
				$emailInvalidError	= array();
	
				$emails = JRequest::getVar('emails', '', 'POST');
	
				if( empty($emails) )
				{
					$validated = false;
					$mainframe->enqueueMessage( JText::_('CC FRIEND EMAIL CANNOT BE EMPTY') , 'error');
				}
				else
				{
					$emails = explode(',', $emails);
					$userModel =& CFactory::getModel('user');
	
					// @todo: do simple email validation
					// make sure user is not a member yet
					// check for duplicate emails
					// make sure email is valid
					// make sure user is not already on the system
					$actualEmails = array();
					for( $i = 0; $i < count($emails); $i++ )
					{
						//trim the value
						$emails[$i] = JString::trim($emails[$i]);
	
						if(!empty($emails[$i]) &&
							preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $emails[$i]))
						{
							//now if the email already exist in system, alert the user.
							if(!$userModel->userExistsbyEmail($emails[$i])){
								$actualEmails[$emails[$i]] = true;
							} else {
								$emailExistError[] = $emails[$i];
							}
						} else {
						    // log the error and display to user.
						    if(!empty($emails[$i]))
								$emailInvalidError[] = $emails[$i];
						}
					}
	
					$emails = array_keys($actualEmails);
					unset($actualEmails);
	
					if(count($emails) <= 0)
						$validated = false;
	
					if(count($emailInvalidError) > 0)
					{
						for($i = 0; $i < count($emailInvalidError); $i++)
						{
							$mainframe->enqueueMessage( JText::sprintf('CC INVITE EMAIL INVALID', $emailInvalidError[$i]) , 'error');
						}
						$validated = false;
					}
	
	
					if(count($emailExistError) > 0)
					{
						for($i = 0; $i < count($emailExistError); $i++)
						{
							$mainframe->enqueueMessage( JText::sprintf('CC INVITE EMAIL EXIST', $emailExistError[$i]) , 'error');
						}
						$validated = false;
					}
				}
	
				$message =  JRequest::getVar('message', '', 'POST');
				
				$config		=& CFactory::getConfig();
	
				if( $validated )
				{
					CFactory::load( 'libraries' , 'notification' );
					
					for( $i = 0; $i < count($emails); $i++ ) 
					{
						$emails[$i] = JString::trim($emails[$i]);
						
						$params		= new JParameter( '' );
						$params->set( 'url' , 'index.php?option=com_community&view=profile&userid='.$my->id.'&invite='.$my->id );
						$params->set( 'message'	, $message );
						CNotificationLibrary::add( 'friends.invite.users' , $my->id , $emails[ $i ] , JText::sprintf('CC INVITE EMAIL SUBJECT', $my->getDisplayName(), $config->get('sitename') ) , '' , 'friends.invite' , $params );
					}
	
					$mainframe->enqueueMessage(JText::sprintf( (CStringHelper::isPlural(count($emails))) ? 'CC INVITE EMAIL SENT MANY' : 'CC INVITE EMAIL SENT' , count($emails)));
					
					//add user points - friends.invite removed @ 20090313
					//clear the post value.
					JRequest::setVar('emails', '');
					JRequest::setVar('message', '');
	
				} else {
					// Display error message
				}
			}
		}

		echo $view->get('invite');
	}

	public function online()
	{
		$view = $this->getView('friends');
		echo $view->get(__FUNCTION__);

	}

	public function news()
	{
		$view = $this->getView('friends');
		echo $view->get(__FUNCTION__);
	}

	/**
	 * List down all request that you've sent but not approved by the other side yet
	 */
	public function sent(){
		$my =& JFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}		
		
		$view = $this->getView('friends');
		$model =& $this->getModel('friends');

		$data	= new stdClass();
		$rsent = $model->getSentRequest($my->id);

		$data->sent = $rsent;
		$data->pagination =& $model->getPagination();

		echo $view->get('sent', $data);
	}

	/**
	 * Add new friend
	 */
	public function add(){
		$view = $this->getView('friends');
		
		$my =& JFactory::getUser();
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}		

		$model	=& $this->getModel('friends');
		$id		= JRequest::getCmd('userid', 0);				
		$data 	=& JFactory::getUser($id);

		$task= JRequest::getVar('task','','GET');
		$task = $task.'()';
		$this->task=$task;

		// If a query is sent, seach for it
		if($query = JRequest::getVar('userid', '', 'POST'))
		{
			$model->addFriend($id, $my->id);
			
			//trigger for onFriendRequest
			$eventObject = new stdClass();
			$eventObject->profileOwnerId 	= $my->id;
			$eventObject->friendId 			= $id;
			$this->triggerFriendEvents( 'onFriendRequest' , $eventObject);
			unset($eventObject);
			
			echo $view->get('addSuccess', $data);
		}
		else
		{
			//@todo disallow self add as a friend
			//@todo disallow add existing friend
			if($my->id==$id)
			{
				$view->addInfo( JText::_( 'CC FRIEND CANNOT ADD SELF' ) );
				$this->display();
			}
			elseif(count($model->getFriendConnection($my->id,$id))>0){
				$view->addInfo(JText::_('CC FRIEND IS ALREADY FRIEND'));
				$this->display();

			}
			else
			{
				echo $view->get('add', $data);
			}

		}

	}

	public function remove()
	{
		$mainframe =& JFactory::getApplication();
		$view	= $this->getView('friends');
		$model	= $this->getModel('friends');
		$my		= CFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}		

		$friendid	= JRequest::getVar('fid','','GET');

		if( $model->deleteFriend($my->id,$friendid) )
		{
			// Substract the friend count
			$model->updateFriendCount( $my->id );
			$model->updateFriendCount( $friendid );
			
			//add user points
			// we deduct poinst to both parties
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('friends.remove');			
			CUserPoints::assignPoint('friends.remove', $friendid);

			$view->addInfo(JText::_('CC FRIEND REMOVED'));
			//@todo notify friend after remove them from our friend list
			
			//trigger for onFriendRemove
			$eventObject = new stdClass();
			$eventObject->profileOwnerId 	= $my->id;
			$eventObject->friendId 			= $friendid;
			$this->triggerFriendEvents( 'onFriendRemove' , $eventObject);
			unset($eventObject);
		}
		else
		{
			$view->addinfo(JText::_('CC ERROR REMOVING FRIEND'));
		}

		$this->display();
	}

	/**
	 * Method to cancel a friend request
	 */
	public function deleteSent()
	{
		$my		= CFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}		
		
		$view	= $this->getView( 'friends' );
		$model	=& $this->getModel('friends');

		$friendId	= JRequest::getVar( 'fid' , '' , 'POST' );
		$message	= '';

		if($model->deleteSentRequest($my->id,$friendId))
		{
			$message	= JText::_('CC FRIEND REQUEST CANCELED');
			
			//add user points - friends.request.cancel removed @ 20090313
		}
		else
		{
			$message	= JText::_('CC FRIEND REQUEST CANCELLED ERROR');
		}

		$view->addInfo( $message );
		$this->sent();
	}

	 /**
	  * functino tag() removed @ 02-oct-2009
	  * functino ajaxAssignTag() removed @ 02-oct-2009	  
	  */

	/**
	 * Ajax function to reject a friend request
	 **/
	public function ajaxRejectRequest( $requestId )
	{
		$objResponse	= new JAXResponse();
		$my				= CFactory::getUser();
		$friendsModel	=& CFactory::getModel('friends');
		
		if($my->id == 0)
		{
		   return $this->ajaxBlockUnregister();
		}		

		if( $friendsModel->isMyRequest( $requestId , $my->id) )
		{
			$pendingInfo = $friendsModel->getPendingUserId($requestId);
			
			if( $friendsModel->rejectRequest( $requestId ) )
			{
				//add user points - friends.request.reject removed @ 20090313

				$friendId  = $pendingInfo->connect_from;
				$friend	   = CFactory::getUser( $friendId );
				$friendUrl = CRoute::_('index.php?option=com_community&view=profile&userid='.$friendId);
				
				$objResponse->addScriptCall( 'joms.jQuery("#pending-' . $requestId . '").html(\'<span class="community-invitation-message">' . JText::sprintf('CC FRIEND REQUEST DECLINED', $friend->getDisplayName(), $friendUrl) . '</span>\')');

				//trigger for onFriendReject
				$eventObject = new stdClass();
				$eventObject->profileOwnerId 	= $my->id;
				$eventObject->friendId 			= $friendId;
				$this->triggerFriendEvents( 'onFriendReject' , $eventObject);
				unset($eventObject);
			}
			else
			{
				$objResponse->addScriptCall( 'joms.jQuery("#request-notice").html("' . JText::sprintf('CC FRIEND REQUEST REJECT FAILED', $requestId ) . '");' );
				$objResponse->addScriptCall( 'joms.jQuery("#request-notice").attr("class", "error");');
			}

		}
		else
		{
			$objResponse->addScriptCall( 'joms.jQuery("#request-notice").html("' . JText::_('CC NOT YOUR REQUEST') . '");' );
			$objResponse->addScriptCall( 'joms.jQuery("#request-notice").attr("class", "error");');
		}

		return $objResponse->sendResponse();

	}

	/**
	 * Ajax function to approve a friend request
	 **/
	public function ajaxApproveRequest( $requestId )
	{
		$objResponse	= new JAXResponse();
		$my				= CFactory::getUser();
		$friendsModel	=& CFactory::getModel( 'friends' );
		
		if($my->id == 0)
		{
		   return $this->ajaxBlockUnregister();
		}		

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
				
				//add user points - give points to both parties
				CFactory::load( 'libraries' , 'userpoints' );		
				CUserPoints::assignPoint('friends.request.approve');				

				$friendId		= ( $connected[0] == $my->id ) ? $connected[1] : $connected[0];
				$friend			= CFactory::getUser( $friendId );
				$friendUrl      = CRoute::_('index.php?option=com_community&view=profile&userid='.$friendId);	
				CUserPoints::assignPoint('friends.request.approve', $friendId);

				// Add the friend count for the current user and the connected user
				// @moved to internal trigger
				
				// Add notification
				CFactory::load( 'libraries' , 'notification' );
				
				$params			= new JParameter( '' );
				$params->set( 'url' , 'index.php?option=com_community&view=profile&userid='.$my->id );

				CNotificationLibrary::add( 'friends.create.connection' , $my->id , $friend->id , JText::sprintf('CC FRIEND REQUEST APPROVED', $my->getDisplayName() ) , '' , 'friends.approve' , $params );

				$objResponse->addScriptCall( 'joms.jQuery("#pending-' . $requestId . '").html(\'<span class="community-invitation-message">' . JText::sprintf('CC FRIEND REQUEST ACCEPTED', $friend->getDisplayName(), $friendUrl) . '</span>\')');
							
				//trigger for onFriendApprove
				$eventObject = new stdClass();
				$eventObject->profileOwnerId 	= $my->id;
				$eventObject->friendId 			= $friendId;
				$this->triggerFriendEvents( 'onFriendApprove' , $eventObject);
				unset($eventObject);
			}
		}
		else
		{
			$objResponse->addScriptCall( 'joms.jQuery("#request-notice").html("' . JText::_('CC NOT YOUR REQUEST') . '");' );
			$objResponse->addScriptCall( 'joms.jQuery("#request-notice").attr("class", "error");');
		}

		return $objResponse->sendResponse();
	}


	public function ajaxSaveFriend($postVars)
	{
		$objResponse   = new JAXResponse();

		//@todo filter paramater
		$model =& $this->getModel('friends');
		$my = CFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->ajaxBlockUnregister();
		}		

		$postVars = CAjaxHelper::toArray($postVars);
		$id = $postVars['userid']; //get it from post
		$msg = strip_tags($postVars['msg']);
		$data  = CFactory::getUser($id);

		if(count($postVars)>0)
		{
			$model->addFriend($id, $my->id, $msg);

			$html 	= JText::sprintf( 'CC FRIEND WILL RECEIVE REQUEST' , $data->getDisplayName());

		    $objResponse->addAssign('cWindowContent', 'innerHTML', $html );
			$objResponse->addScriptCall('cWindowResize', 110);
			$action = '<button class="button" onclick="cWindowHide();" name="close">' . JText::_('CC BUTTON CLOSE') . '</button>';
			$objResponse->addScriptCall('cWindowActions', $action);
			
			// Add notification
			CFactory::load( 'libraries' , 'notification' );
			
			$params			= new JParameter( '' );
			$params->set('url' , 'index.php?option=com_community&view=friends&task=pending' );
			$params->set('msg' , $msg );

			CNotificationLibrary::add( 'friends.create.connection' , $my->id , $id , JText::sprintf('CC FRIEND ADD REQUEST', $my->getDisplayName() ) , '' , 'friends.request' , $params );
			
			//add user points - friends.request.add removed @ 20090313
			//trigger for onFriendRequest
			$eventObject = new stdClass();
			$eventObject->profileOwnerId 	= $my->id;
			$eventObject->friendId 			= $id;
			$this->triggerFriendEvents( 'onFriendRequest' , $eventObject);
			unset($eventObject);
		 }


		return $objResponse->sendResponse();
	}

	/**
	 * Show internal invite
	 * Internal invite is more like an internal messaging system
	 */
	public function ajaxInvite() {
		return $objResponse->sendResponse();
	}

	/**
	 * Show import friends from other account
	 *
	 */
	public function ajaxFrindImport() {
	}


	/**
	 * Displays a dialog to the user if he / she really wants to
	 * cancel the friend request
	 **/
	public function ajaxCancelRequest( $friendsId )
	{
		$my = CFactory::getUser();
	
		if($my->id == 0)
		{
		   return $this->ajaxBlockUnregister();
		}	
	
		$objResponse	= new JAXResponse();

		$html		= '<div>' . JText::_('CC CONFIRM CANCEL REQUEST') . '</div>';

		$formAction	= CRoute::_('index.php?option=com_community&view=friends&task=deleteSent' , false );
		$action		= '<form name="cancelRequest" action="' . $formAction . '" method="POST">';
		$action		.= '<input type="submit" class="button" name="save" value="' . JText::_('CC BUTTON YES') . '" />&nbsp;';
		$action		.= '<input type="hidden" name="fid" value="' . $friendsId . '" />';
		$action		.= '<input type="button" class="button" onclick="javascript:cWindowHide();return false;" name="cancel" value="'.JText::_('CC BUTTON NO').'" />';
		$action		.= '</form>';

		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC CANCEL FRIEND REQUEST'));
		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);
		$objResponse->addScriptCall('cWindowActions', $action);
		$objResponse->sendResponse();
	}
	
	/**
	 * Show the connection request box
	 */
	public function ajaxConnect( $friendId )
	{
		// Block unregistered users.
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		
		$objResponse = new JAXResponse();

		//@todo filter paramater
		$model	        =& $this->getModel('friends');
		$blockModel     =& $this->getModel('block'); 

        $my 			= CFactory::getUser();
		$view			= $this->getView('friends');
		$user  			= CFactory::getUser($friendId);
        
        CFactory::load('libraries','block');
        $blockUser   = new blockUser();  
		
		// Block blocked users
		if( $blockModel->getBlockStatus($my->id,$friendId) ){    
		      $blockUser->ajaxBlockMessage();
        }
        
        // Warn owner that the user has been blocked, cannot add as friend
		if( $blockModel->getBlockStatus($friendId,$my->id) ){    
		      $blockUser->ajaxBlockWarn();
        }
        
        
		$connection		= $model->getFriendConnection( $my->id , $friendId );
		
		CFactory::load( 'helpers' , 'string' );
		//@todo disallow self add as a friend
		//@todo disallow add existing friend
		if($my->id == $friendId)
		{
			$objResponse->addAssign('cWindowContent','innerHTML',JText::_('CC FRIEND CANNOT ADD SELF'));
			$objResponse->addScriptCall('cWindowResize', 100);
		}
		elseif(count( $connection ) > 0 )
		{
			if( $connection[0]->connect_from == $my->id )
			{
				$objResponse->addAssign('cWindowContent','innerHTML',JText::sprintf('CC FRIEND REQUEST ALREADY SENT', $user->getDisplayName()));
			}
			else
			{
				$objResponse->addAssign('cWindowContent','innerHTML',JText::sprintf('CC FRIEND REQUEST ALREADY RECEIVED', $user->getDisplayName()));
			}
			$objResponse->addScriptCall('cWindowResize', 100);
		}
		else
		{
			ob_start();
		?>
		<div id="addFriendContainer">
			<p><?php echo JText::sprintf('CC CONFIRM ADD FRIEND' , $user->getDisplayName() );?></p>
			<form name="addfriend" id="addfriend" method="post" action="">				
		        <img class="avatar" src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo CStringHelper::escape( $user->getDisplayName() );?>" alt=""/>
				<textarea class="inputbox" name="msg"></textarea>
				<input type="hidden" class="button" name="userid" value="<?php echo $user->id; ?>"/>
			</form>
		</div>
		<?php
			$html	= ob_get_contents();
			ob_end_clean();

		    $action  = '<button class="button" onclick="joms.friends.addNow();" name="save">' . JText::_('CC BUTTON ADD FRIEND') . '</button>';
		    $action .= '<button class="button" onclick="javascript:cWindowHide();" name="cancel">' . JText::_('CC BUTTON CANCEL') . '</button>';
			
			$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC FRIEND ADD'));
			$objResponse->addAssign('cWindowContent', 'innerHTML', $html);
			$objResponse->addScriptCall('cWindowActions', $action);
			$objResponse->addScriptCall('cWindowResize', 130);
		}

		return $objResponse->sendResponse();
	}


	/**
	 * List down all connection request waiting for user to approve
	 */
	public function pending()
	{	

		$my		= CFactory::getUser();
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}

		$view		= $this->getView('friends');
		$model		=& $this->getModel('friends');
		$usermodel	=& $this->getModel('user');

		// @todo: make sure the rejectId and approveId is valid for this user
		if($id = JRequest::getVar('rejectId', 0, 'GET'))
		{
		    $mainframe =& JFactory::getApplication();

			if(! $model->rejectRequest($id)){
				$mainframe->enqueueMessage(JText::sprintf('CC RRIEND REQUEST REJECT FAILED', $id));
			}
		}

		if($id = JRequest::getVar('approveId', 0, 'GET'))
		{
			$mainframe =& JFactory::getApplication();
			$connected = $model->approveRequest($id);

			// If approbe id is not valid or already approve, $connected will
			// be null.. yuck
			if($connected) {
				$act = new stdClass();
				$act->cmd 		= 'friends.request.approve';
				$act->actor   	= $connected[0];
				$act->target  	= $connected[1];
				$act->title	  	= JText::_('CC ACTIVITIES FRIENDS NOW');
				$act->content	= '';
				$act->app		= 'friends';
				$act->cid		= 0;

				CFactory::load ( 'libraries', 'activities' );
				CActivityStream::add($act);
				
				//add user points - give points to both parties
				CFactory::load( 'libraries' , 'userpoints' );		
				CUserPoints::assignPoint('friends.request.approve');				

				$friendId = ( $connected[0] == $my->id ) ? $connected[1] : $connected[0];
				$friend = CFactory::getUser($friendId);
				CUserPoints::assignPoint('friends.request.approve', $friendId);

				$mainframe->enqueueMessage(JText::sprintf('CC FRIENDS NOW', $friend->getDisplayName()));
			}
		}

		$data		= new stdClass();
		$rpending	= $model->getPending($my->id);

		$data->pending		= $rpending;
		$data->pagination	=& $model->getPagination();

		echo $view->get(__FUNCTION__, $data);
	}

	/**
	 * Browse the active user's friends
	 */
	public function browse(){
		$view =& $this->getView('friends');
		echo $view->get('browse');

	}

	public function search()
	{
		$view =& $this->getView('friends');
		$data = array();
		$data['query'] 	= '';
		$data['result']	= null;

		// If a query is sent, seach for it
		if($query = JRequest::getVar('q', '', 'POST')){
			$model =& $this->getModel('friends');
			$data['result'] = $model->searchPeople($query);
			$data['query'] 	= $query;
		}

		echo $view->get(__FUNCTION__, $data);
	}
	
	/*
	 * friends event name
	 * object	 	
     */	
	public function triggerFriendEvents( $eventName, &$args, $target = null)
	{
		CError::assert( $args , 'object', 'istype', __FILE__ , __LINE__ );
		
		require_once( COMMUNITY_COM_PATH.DS.'libraries' . DS . 'apps.php' );
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();		
		
		$params		= array();
		$params[]	= &$args;
		
		if(!is_null($target))
			$params[]	= $target;
				
		$appsLib->triggerEvent( $eventName , $params);
		return true;
	}
	
	/**
	 * Block user
	 */
	public function blockUser()
	{                          	

		$my		= CFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}
		
		$userId		= JRequest::getVar('fid','','GET');
        
        CFactory::load ( 'libraries', 'block' );
        $blockUser  = new blockUser;
        $blockUser->block( $userId );
	}
}
