<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class CommunityProfileController extends CommunityBaseController
{
	/**
	 * Edit a user's profile	
	 * 	 	
	 * @access	public
	 * @param	none 
	 */
	private $_icon = '';

	public function editProfileWall( $wallId )
	{
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'helpers' , 'time');

		$my		= CFactory::getUser();
		$wall	=& JTable::getInstance( 'Wall' , 'CTable' );
		$wall->load( $wallId );

		// @rule: We only allow editing of wall in 15 minutes
		$now		= JFactory::getDate();
		$interval	= CTimeHelper::timeIntervalDifference( $wall->date , $now->toMySQL() );
		$interval	= abs( $interval );
		
		if( ( COwnerHelper::isCommunityAdmin() || $my->id == $wall->post_by ) && ( COMMUNITY_WALLS_EDIT_INTERVAL > $interval ) )
		{
			return true;
		}
		return false;
	}
	
	public function ajaxPlayProfileVideo($videoid=null, $userid=0)
	{
		$objResponse	= new JAXResponse();

		// Get necessary properties and load the libraries
		$my		=   CFactory::getUser();
                
		$video		=   JTable::getInstance( 'Video' , 'CTable' );
		$video->load($videoid);

		$title		= JText::_('CC PROFILE VIDEO');

		if(!empty($video->id))
		{
			$video->loadExtra();

			// Check video permission
			if (!$this->isPermitted($my->id, $video->creator, $video->permissions))
			{
				switch ($video->permissions)
				{
					case PRIVACY_PRIVATE :
						$content = JText::_('CC VIDEO OWNNER ONLY');
						break;
					case PRIVACY_FRIENDS :
						$owner	= CFactory::getUser($video->creator);
						$content = JText::sprintf('CC VIDEO NEED FRIEND PERMISSION', $owner->getDisplayName());
						break;
					default:
						$content = JText::_('CC VIDEO NEED LOGIN');
						break;
				}
				
				$objResponse->addScriptCall('cWindowShow', '', $title, 430, 80);
			}
			else
			{
				$title		=	$video->title;
				$content	=	$video->getViewHTML('','',false);

				$objResponse->addScriptCall('cWindowShow', '', $title, $video->getWidth()+30, $video->getHeight()+30);
				//$objResponse->addScriptCall('cWindowResize', $video->getHeight()+50, $video->getWidth());
				// $objResponse->addScriptCall('cWindowResize', 600, 800);
			}
		}
		else
		{
			$content	= JText::_('CC PROFILE VIDEO NOT EXIST');

                        if( COwnerHelper::isMine( $my->id, $userid ) ){
                                    $redirectURL	= CRoute::_('index.php?option=com_community&view=profile&task=linkVideo' , false );
                                    $action         = '<input type="button" class="button" onclick="cWindowHide(); window.location=\''.$redirectURL.'\';" value="' . JText::_('CC ADD PROFILE VIDEO') . '"/>';

                                    $objResponse->addScriptCall('cWindowActions', $action);
                        }
                        
                        $objResponse->addScriptCall('cWindowShow', '', $title, 430, 80);
		}

		$objResponse->addAssign('cwin_logo', 'innerHTML', $title);
		$objResponse->addAssign('cWindowContent', 'innerHTML', $content);

		return $objResponse->sendResponse();
	}

	// Confirm before change video
	public function ajaxConfirmLinkProfileVideo($id)
	{
		$objResponse	= new JAXResponse();

		$header		=	JText::_('CC EDIT PROFILE VIDEO');
		$message    =   JText::_('CC PROFILE VIDEO CONFIRM LINK');

		// Change cWindow title
		$objResponse->addAssign('cwin_logo', 'innerHTML', $header);
		$objResponse->addAssign('cWindowContent', 'innerHTML', $message);
		$action		=	'<button  class="button" onclick="joms.videos.linkProfileVideo(' . $id . ');">' . JText::_('CC BUTTON YES') . '</button>';
		$action		.=	'&nbsp;<button class="button" onclick="cWindowHide();">' . JText::_('CC BUTTON NO') . '</button>';
		$objResponse->addScriptCall( 'cWindowActions' , $action );

		return $objResponse->sendResponse();
    }

	// Store to database and reload page
	public function ajaxLinkProfileVideo($videoid)
	{
		$objResponse	= new JAXResponse();

		$my				= CFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		$params			=	$my->getParams();
		$params->set('profileVideo', $videoid);
		$my->save('params');

		$header		=	JText::_('CC EDIT PROFILE VIDEO');
		$message	=	JText::_('CC PROFILE VIDEO LINKED');

		$objResponse->addAssign('cwin_logo', 'innerHTML', $header);
		$objResponse->addAssign('cWindowContent', 'innerHTML', $message);

		$action		=	'<button  class="button" onclick="window.location.reload()">' . JText::_('CC BUTTON CLOSE') . '</button>';
		$objResponse->addScriptCall( 'cWindowActions' , $action );

		return $objResponse->sendResponse();
    }

	// Need confirmation before remove link
	public function ajaxRemoveConfirmLinkProfileVideo($userid, $videoid)
	{
		$objResponse	= new JAXResponse();

		$my				= CFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		$header		=	JText::_('CC PROFILE VIDEO REMOVE');
		$message    =   JText::_('CC PROFILE VIDEO CONFIRM REMOVE LINK');

		// Change cWindow title
		$objResponse->addAssign('cwin_logo', 'innerHTML', $header);
		$objResponse->addAssign('cWindowContent', 'innerHTML', $message);
		$action		=	'<button  class="button" onclick="joms.videos.removeLinkProfileVideo(' . $userid . ', ' . $videoid . ');">' . JText::_('CC BUTTON YES') . '</button>';
		$action		.=	'&nbsp;<button class="button" onclick="cWindowHide();">' . JText::_('CC BUTTON NO') . '</button>';
		$objResponse->addScriptCall( 'cWindowActions' , $action );

		return $objResponse->sendResponse();
	}

	// Remove link
	public function ajaxRemoveLinkProfileVideo($userid, $videoid)
	{
		$objResponse	= new JAXResponse();

		$my				= CFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}

		$user	=	CFactory::getUser($userid);

		// Set params to default(0 for no profile video)
		$params			=	$user->getParams();
		$params->set('profileVideo', 0);
		$user->save('params');

		$header		=	JText::_('CC EDIT PROFILE VIDEO');
		$message	    =	JText::_('CC PROFILE VIDEO REMOVED');
		$message	    .=	'<br />' . JText::_('CC ASK TO DELETE VIDEO');

		$objResponse->addAssign('cwin_logo', 'innerHTML', $header);
		$objResponse->addAssign('cWindowContent', 'innerHTML', $message);

		$action		=	'<button  class="button" onclick="joms.videos.deleteVideo(' . $videoid . ')">' . JText::_('CC DELETE VIDEO') . '</button>';
		$action		.=	'&nbsp;<button  class="button" onclick="window.location.reload()">' . JText::_('CC BUTTON CLOSE') . '</button>';
		$objResponse->addScriptCall( 'cWindowActions' , $action );

		return $objResponse->sendResponse();
	}
    
	public function ajaxIphoneProfile()
    {		
		$document		=& JFactory::getDocument();
		
		$viewType	= $document->getType(); 		 	
		$view		=& $this->getView( 'profile', '', $viewType);
						
		
		$html = '';
		
		ob_start();				
		$this->profile();				
		$content = ob_get_contents();
		ob_end_clean();
		
		$tmpl			= new CTemplate();
		$tmpl->set('toolbar_active', 'profile');
		$simpleToolbar	= $tmpl->fetch('toolbar.simple');		
		
		$objResponse->addAssign('social-content', 'innerHTML', $simpleToolbar . $content);
		return $objResponse->sendResponse();		    	
    }

	/**
	 *	Ajax method to block user from the site. This method is only used by site administrators
	 *	
	 *	@params	$userId	int	The user id that needs to be blocked
	 *	@params	$isBlocked	boolean	Whether the user is already blocked or not. If it is blocked, system should unblock it.	 	 	 
	 **/	 	
	public function ajaxBanUser( $userId , $isBlocked )
	{
		$user	= CFactory::getUser( $userId );
		
		$objResponse	= new JAXResponse();
		$title			= '';
		$my				= CFactory::getUser();
		CFactory::load( 'helpers' , 'owner' );
		
		if($my->id == 0)
		{
		   	return $this->ajaxBlockUnregister();
		}
		
		// @rule: Only site admin can access this function.
		if( COwnerHelper::isCommunityAdmin( $my->id ) )
		{
			$isSuperAdmin	= COwnerHelper::isCommunityAdmin( $user->id );

			// @rule: Do not allow to block super administrators.
			if( $isSuperAdmin )
			{
				$content	= '<div>' . JText::_('CC NOT ALLOWED TO BAN SUPER ADMIN') . '</div>';
	
				$objResponse->addAssign('cWindowContent', 'innerHTML', $content);
	
				$action		= '<input type="button" class="button" onclick="cWindowHide();return false;" name="cancel" value="'.JText::_('CC BUTTON CLOSE').'" />';
				$objResponse->addScriptCall('cWindowActions', $action);
			}
			else
			{
				ob_start();
				if( !$isBlocked )
				{
				?>
				<div><?php echo JText::sprintf( 'CC BAN USER CONFIRMATION' , $user->getDisplayName() ); ?></div>
				<?php
					$title	= JText::_('CC BAN USER');
				}
				else
				{
				?>
				<div><?php echo JText::sprintf( 'CC UNBAN USER CONFIRMATION' , $user->getDisplayName() ); ?></div>
				<?php
				$title	= JText::_('CC UNBAN USER');
				}
				$content		= ob_get_contents();
				ob_end_clean();
				
				$objResponse->addAssign('cwin_logo', 'innerHTML', $title );
				$objResponse->addAssign('cWindowContent', 'innerHTML', $content);
	
				$formAction	= CRoute::_('index.php?option=com_community&view=profile&task=banuser' , false );
				$action		= '<form name="cancelRequest" action="' . $formAction . '" method="POST">';
				$action		.= '<input type="hidden" name="userid" value="' . $userId . '" />';
				$action		.= ( $isBlocked ) ? '<input type="hidden" name="blocked" value="1" />' : '';
				$action		.= '<input type="submit" value="' . JText::_('CC BUTTON YES') . '" class="button" />&nbsp;';
				$action		.= '<input type="button" class="button" onclick="cWindowHide();return false;" name="cancel" value="'.JText::_('CC BUTTON NO').'" />';
				$action		.= '</form>';

				$objResponse->addScriptCall('cWindowActions', $action);
				$objResponse->addScriptCall('cWindowResize', '100');
			}
		}
		
		return $objResponse->sendResponse();
	}

	/**
	 *	Ajax method to remove user's picture from the site. This method is only used by site administrators
	 *	
	 *	@params	$userId	int	The user id that needs to have their picture removed.	 	 	 
	 **/
	public function ajaxRemovePicture( $userId )
	{
		$objResponse	= new JAXResponse();

		$my				= CFactory::getUser();
		CFactory::load( 'helpers' , 'owner' );
		
		if($my->id == 0)
		{
		   	return $this->ajaxBlockUnregister();
		}		
		
		// @rule: Only site admin can access this function.
		if( COwnerHelper::isCommunityAdmin( $my->id ) )
		{
			ob_start();
			?>
				<div><?php echo JText::_( 'CC REMOVE AVATAR CONFIRMATION'); ?></div>
			<?php
			$content		= ob_get_contents();
			ob_end_clean();
	
			$title	= JText::_('CC REMOVE PROFILE PICTURE');
			$objResponse->addAssign('cWindowContent', 'innerHTML', $content);
			$objResponse->addAssign('cwin_logo', 'innerHTML', $title );
			
			$formAction	= CRoute::_('index.php?option=com_community&view=profile&task=removepicture' , false );
			$action		= '<form name="cancelRequest" action="' . $formAction . '" method="POST">';
			$action		.= '<input type="hidden" name="userid" value="' . $userId . '" />';
			$action		.= '<input type="submit" value="' . JText::_('CC BUTTON YES') . '" class="button" />&nbsp;';
			$action		.= '<input type="button" class="button" onclick="cWindowHide();return false;" value="'.JText::_('CC BUTTON NO').'" />';
			$action		.= '</form>';
			
			$objResponse->addScriptCall('cWindowActions', $action);
		}
		return $objResponse->sendResponse();
	}

	public function ajaxUploadNewPicture($userId)
	{
		$objResponse	= new JAXResponse();

		$my				= CFactory::getUser();
		CFactory::load( 'helpers' , 'owner' );

		if(!isCommunityAdmin())
		{
		   	return $this->ajaxBlockUnregister();
		}

		$title	= JText::_('CC EDIT AVATAR');
		$objResponse->addAssign('cwin_logo', 'innerHTML', $title );

		$formAction = CRoute::_('index.php?option=com_community&view=profile&task=uploadAvatar', false);

		$config			= CFactory::getConfig();
		$uploadLimit	= (double) $config->get('maxuploadsize');
		$uploadLimit	.= 'MB';

		$content	=	'<form name="jsform-profile-ajaxuploadnewpicture" action="' . $formAction . '" id="uploadForm" method="post" enctype="multipart/form-data">';
    	$content	.=	'<input class="inputbox button" type="file" id="file-upload" name="Filedata" />';
		$content	.=	'<input class="button" size="30" type="submit" id="file-upload-submit" value="' . JText::_('CC BUTTON UPLOAD PICTURE') . '">';
	    $content	.=	'<input type="hidden" name="action" value="doUpload" />';
		$content	.=	'<input type="hidden" name="userid" value="' . $userId . '" />';
		$content	.=	'</form>';
		$content	.=	'<p class="info">' . JText::sprintf('CC MAX FILE SIZE FOR UPLOAD' , $uploadLimit ) . '</p>';

		$objResponse->addAssign('cWindowContent', 'innerHTML', $content);

		return $objResponse->sendResponse();
	}

	
        /**
	 *	Check if permitted to play the video
	 *
	 *	@param	int		$myid		The current user's id
	 *	@param	int		$userid		The active profile user's id
	 *	@param	int		$permission	The video's permission
	 *	@return	bool	True if it's permitted
	 *	@since	1.2
	 */
	public function isPermitted($myid=0, $userid=0, $permissions=0)
	{
		if ( $permissions == 0) return true; // public

		// Load libraries
		CFactory::load('helpers', 'friends');
		CFactory::load('helpers', 'owner');

		if( COwnerHelper::isCommunityAdmin() ) {
			return true;
		}

		$relation	= 0;

		if( $myid != 0 )
			$relation = 20; // site members

		if( CFriendsHelper::isConnected($myid, $userid) )
			$relation	= 30; // friends

		if( COwnerHelper::isMine($myid, $userid) ){
			$relation	= 40; // mine
		}

		if( $relation >= $permissions ) {
			return true;
		}

		return false;
	}

	/**
	 * Ban user from the system
	 **/
	public function banuser()
	{
		CFactory::load( 'helpers' , 'owner' );
		
		$message	= '';
		$userId		= JRequest::getVar( 'userid' , '' , 'POST' );
		$blocked	= JRequest::getVar( 'blocked' , 0 , 'POST' );
		
		$my			= CFactory::getUser();
		$url		= CRoute::_('index.php?option=com_community&view=profile&userid=' . $userId , false );
		$mainframe	=& JFactory::getApplication();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}		
		
		if( COwnerHelper::isCommunityAdmin() )
		{
			$user	= CFactory::getUser( $userId );
			
			if( $user->id )
			{
				$user->block	= ( $blocked == 1 ) ? 0 : 1;
				$user->save();
				
				$message		= ( $blocked == 1 ) ? JText::_('CC USER UNBANNED') : JText::_('CC USER BANNED');
			}
			else
			{
				$message	= JText::_('CC INVALID PROFILE');
			}
		}
		else
		{
			$message	= JText::_('CC ADMIN ACCESS ONLY');
		}
		
		$mainframe->redirect( $url , $message );
	}

	/**
	 * Reverts profile picture for specific user
	 **/	 	
	public function removepicture()
	{
		CFactory::load( 'helpers' , 'owner' );
		
		$message	= '';
		$userId		= JRequest::getVar( 'userid' , '' , 'POST' );
		$my			= CFactory::getUser();
		$url		= CRoute::_('index.php?option=com_community&view=profile&userid=' . $userId , false );
		$mainframe	=& JFactory::getApplication();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}		
		
		if( COwnerHelper::isCommunityAdmin() )
		{
			$user	= CFactory::getUser( $userId );
			
			// User id should be valid and admin should not be allowed to block themselves.
			if( $user->id )
			{
				$userModel		=& CFactory::getModel( 'User' );
				$userModel->setImage( $user->id , DEFAULT_USER_AVATAR , 'avatar');
				$userModel->setImage( $user->id , DEFAULT_USER_THUMB , 'thumb');
								
				$message		= JText::_('CC PROFILE PICTURE REMOVED');
			}
			else
			{
				$message	= JText::_('CC INVALID PROFILE');
			}
		}
		else
		{
			$message	= JText::_('CC ADMIN ACCESS ONLY');
		}
		
		$mainframe->redirect( $url , $message );
	}
	
	/**
	 * Method is called from the reporting library. Function calls should be
	 * registered here.
	 *
	 * return	String	Message that will be displayed to user upon submission.
	 **/	 	 	
	public function reportProfile( $link, $message , $id )
	{
		CFactory::load( 'libraries' , 'reporting' );
		$report = new CReportingLibrary();
		
		$report->createReport( JText::_('Bad user') , $link , $message );

		$action					= new stdClass();
		$action->label			= 'Block User';
		$action->method			= 'profile,blockProfile';
		$action->parameters		= $id;
		$action->defaultAction	= false;
		
		$report->addActions( array( $action ) );
		
		return JText::_('CC REPORT SUBMITTED');
	}
	
	/**
	 * Function that is called from the back end
	 **/	 	
	public function blockProfile( $userId )
	{
		$user		= CFactory::getUser( $userId );
		
		CFactory::load( 'helpers' , 'owner' );
		
		if( COwnerHelper::isCommunityAdmin() )
		{
			$user->set( 'block' , 1 );
			$user->save();
			return JText::_('CC USER ACCOUNT BANNED');
		}
	}
	
	public function edit()
	{
		CFactory::setActiveProfile();
		
		$user	=& JFactory::getUser();
		
		if($user->id == 0)
		{
		   return $this->blockUnregister();
		}				
		
		if(JRequest::getVar('action', '', 'POST') != ''){
			$this->_saveProfile();
		}
		
		// Get/Create the model
		$model = & $this->getModel('profile');
		$model->setProfile('hello me');
		
		$document =& JFactory::getDocument();

		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );

		// Check if user is really allowed to edit.	
		$data = new stdClass();
		$data->profile		= $model->getEditableProfile($user->id);
		

		$view = & $this->getView( $viewName, '', $viewType);

		$this->_icon = 'edit';

		if(!$data->profile)
			echo $view->get('error', JText::_('CC USER NOT FOUND') );
		else
			echo $view->get(__FUNCTION__, $data);
	}


	public function editDetails()
	{		
		$user		=& JFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		$view		=& $this->getView ( 'profile' );

		if($user->id == 0){
			return $this->blockUnregister();
		}
				
		$lang	=& JFactory::getLanguage();
		$lang->load('com_user');

		// Check if user is really allowed to edit.
		$params =& $mainframe->getParams();

		// check to see if Frontend User Params have been enabled
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		$check = $usersConfig->get('frontend_userparams');

		if ($check == '1' || $check == 1 || $check == NULL)
		{
			if($user->authorize( 'com_user', 'edit' )) {
				$params		= $user->getParameters(true);
			}
		}
		
		$my			= CFactory::getUser();
		$config		=& CFactory::getConfig();
		
		$myParams	=& $my->getParams();
		$myDTS		= $myParams->get('daylightsavingoffset'); 		
		$cOffset	= ( $myDTS != '' ) ? $myDTS : $config->get('daylightsavingoffset');

		$dstOffset	= array();
		$counter = -12;
		for($i=0; $i <= 24; $i++ ){
			$dstOffset[] = 	JHTML::_('select.option', $counter, $counter);
			$counter++;
		}
		
		$offSetLists = JHTML::_('select.genericlist',  $dstOffset, 'daylightsavingoffset', 'class="inputbox" size="1"', 'value', 'text', $cOffset);		
		
		$data = new stdClass();		
		$data->params		= $params;
		$data->offsetList	= $offSetLists;
		
		
		$this->_icon = 'edit';				
		
		echo $view->get ( 'editDetails', $data);
	}
	
	
	public function save()
	{
		// Check for request forgeries
		$mainframe	=& JFactory::getApplication();
		JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
		
		$lang	=& JFactory::getLanguage();
		$lang->load('com_user');		
		
		$user 		=& JFactory::getUser();
		$userid		= JRequest::getVar( 'id', 0, 'post', 'int' );		

		// preform security checks
		if ($user->get('id') == 0 || $userid == 0 || $userid <> $user->get('id'))
		{
			echo $this->blockUnregister();
			return;
		}

		$username	= $user->get('username');
	
		//clean request
		$post = JRequest::get( 'post' );
		$post['username']	= $username;
		$post['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['password2']	= JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		
		//check email
	    $email		= $post['email'];
	    $emailPass	= $post['emailpass'];
	    $modelReg	=& $this->getModel('register');
	    
	    CFactory::load( 'helpers' , 'validate' );
	    if(!CValidateHelper::email($email))
	    {
	    	$msg = JText::sprintf('CC INVITE EMAIL INVALID', $email);
			$mainframe->redirect(CRoute::_('index.php?option=com_community&view=profile&task=editDetails', false), $msg, 'error');
			return false;
	    }
	    
	    if(! empty($email) && ($email != $emailPass) && $modelReg->isEmailExists(array('email'=>$email)) )
		{
			$msg		= JText::sprintf('CC EMAIL EXIST', $email);
			$msg		= stripslashes($msg);
			$mainframe->redirect(CRoute::_('index.php?option=com_community&view=profile&task=editDetails', false), $msg, 'error');
			return false;			
	    }
	
		// get the redirect
		$return = CRoute::_('index.php?option=com_community&view=profile&task=editDetails', false);

		// do a password safety check
		if( JString::strlen($post['password']) || JString::strlen($post['password2'])) 
		{
			// so that "0" can be used as password e.g.
			if($post['password'] != $post['password2']) 
			{
				$msg = JText::_('PASSWORDS_DO_NOT_MATCH');
				$mainframe->redirect(CRoute::_('index.php?option=com_community&view=profile&task=editDetails', false), $msg, 'error');
				return false;
			}
		}
		
		// we don't want users to edit certain fields so we will unset them
		unset($post['gid']);
		unset($post['block']);
		unset($post['usertype']);
		unset($post['registerDate']);
		unset($post['activation']);

		//update CUser param 1st so that the new value will not be replace wif the old one.
		$my			= CFactory::getUser();
		$params		=& $my->getParams();
		$postvars	= $post['daylightsavingoffset'];
		$params->set('daylightsavingoffset', $postvars);		

		$jConfig		=& JFactory::getConfig();
		$model			= CFactory::getModel( 'profile' );
		$editSuccess	= true;	
		$msg			= JText::_( 'CC SETTINGS SAVED' );	
		$jUser			=& JFactory::getUser();

		// Bind the form fields to the user table
		if(!$jUser->bind($post))
		{
			$msg = $jUser->getError();
			$editSuccess = false;
		}

		$my->save('params');		

		// Store the web link table to the database
		if(!$jUser->save())
		{
			$msg	= $jUser->getError();
			$editSuccess = false;
		}

		if($editSuccess)
		{
			$session =& JFactory::getSession();
			$session->set('user', $jUser);
			
			//execute the trigger
			$appsLib	=& CAppPlugins::getInstance();
			$appsLib->loadApplications();
			
			$userRow	= array();
			$userRow[]	= $jUser;
			 
			$appsLib->triggerEvent( 'onUserDetailsUpdate' , $userRow );
		}
		
		$mainframe->redirect(CRoute::_('index.php?option=com_community&view=profile&task=editDetails', false), $msg);
	}	
	
	/**
	 * Show rss feed for this user
	 */	 	
	public function feed(){
		$document	=& JFactory::getDocument();
		
		$item = new JFeedItem();
		$item->author = '';
		$document->addItem($item);
	}
	
	/**
	 * Saves a user's profile	
	 * 	 	
	 * @access	private
	 * @param	none 
	 */
	private function _saveProfile()
	{
		$model		=& $this->getModel('profile');
		$usermodel	=& $this->getModel('user');
		$document	=& JFactory::getDocument();
		$my			= CFactory::getUser();
		$mainframe	=& JFactory::getApplication();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}

		CFactory::load( 'libraries' , 'apps' );
		$appsLib		=& CAppPlugins::getInstance();
		$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array('jsform-profile-edit' ));
		
		if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
		{
			$values		= array();
			$profiles	= $model->getEditableProfile( $my->id );
			
			CFactory::load( 'libraries' , 'profile' );
	
			foreach( $profiles['fields'] as $group => $fields )
			{
				foreach( $fields as $data )
				{
					// Get value from posted data and map it to the field.
					// Here we need to prepend the 'field' before the id because in the form, the 'field' is prepended to the id.
					$postData				= JRequest::getVar( 'field' . $data['id'] , '' , 'POST' );
					$values[ $data['id'] ]	= CProfileLibrary::formatData( $data['type']  , $postData );
	
					// @rule: Validate custom profile if necessary
					if( !CProfileLibrary::validateField( $data['type'] , $values[ $data['id'] ] , $data['required']) )
					{
						// If there are errors on the form, display to the user.
						$message	= JText::sprintf('CC FIELD CONTAIN IMPROPER VALUES' ,  $data['name'] );
						$mainframe->enqueueMessage( $message , 'error' );
						return;
					}
				}
			}
			
			// Rebuild new $values with field code
			$valuesCode = array();
			foreach( $values as $key => &$val ) {
				$fieldCode = $model->getFieldCode($key);
				if( $fieldCode ){
					$valuesCode[$fieldCode] = &$val;
				}
			}
			
			$saveSuccess = true;
			$appsLib	=& CAppPlugins::getInstance();
			$appsLib->loadApplications();
			
			// Trigger before onBeforeUserProfileUpdate
			$args 	= array();
			$args[]	= $my->id;
			$args[]	= $valuesCode;
	
			$result = $appsLib->triggerEvent( 'onBeforeProfileUpdate' , $args );
			
			// make sure none of the $result is false
			if(!$result || ( !in_array(false, $result) ) ) {
				$model->saveProfile($my->id, $values);
			} else {
				$saveSuccess = false;
			}
		}

		// Trigger before onAfterUserProfileUpdate
		$args 	= array();
		$args[]	= $my->id;
		$args[]	= $saveSuccess; 
		$result = $appsLib->triggerEvent( 'onAfterProfileUpdate' , $args );
		
		if( $saveSuccess )
		{
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('profile.save');		
	
			$mainframe->enqueueMessage(JText::_('CC PROFILE SAVED') );
			
			
		} else {
			
			$mainframe->enqueueMessage( JText::_('CC PROFILE NOT SAVED'), 'error');
			
		}
	}
	
	/**
	 * Displays front page profile of user
	 * 	 	
	 * @access	public
	 * @param	none
	 * @returns none	 
	 */
	public function display()
	{
		// By default, display the user profile page
		$this->profile();
	}
	
	public function preferences()
	{
		$view           =& $this->getView('profile');
		$my		= CFactory::getUser();
                $mainframe      =& JFactory::getApplication();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}		
		
		$method	= JRequest::getMethod();
		
		if($method == 'POST')
		{			
			CFactory::load( 'libraries' , 'apps' );
			$appsLib		=& CAppPlugins::getInstance();
			$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array('jsform-profile-preferences' ));
			
			if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
			{
				$params		= $my->getParams();
                                $postvars	= JRequest::get('POST');
                                $activity       = JRequest::getInt('activityLimit');
				$editSuccess	= true;
				$message	= JText::_('CC PREFERENCES SETTINGS SAVED');

                                if($activity != 0)
                                {
                                    $params->set( 'activityLimit' , $activity );

                                    $jConfig    =& JFactory::getConfig();
                                    $model      =& CFactory::getModel( 'Profile' );

                                    if( $jConfig->getValue( 'sef' ) && isset( $postvars['alias'] ) && !empty( $postvars['alias'] ) )
                                    {
                                            $alias	= JFilterOutput::stringURLSafe( urlencode($postvars['alias']) );
                                            CFactory::load( 'helpers' , 'validate' );

                                            if( !$model->aliasExists( $alias ) && CValidateHelper::alias( $alias ) )
                                            {
                                                    $my->set( '_alias' , $alias );
                                            }
                                            else
                                            {
                                                    $message        = JText::_('CC ALIAS ALREADY EXISTS' );
                                                    $editSuccess    = false;
                                            }
                                    }

                                    $my->save( 'params' );

                                    if( $editSuccess )
                                    {
                                            $mainframe->enqueueMessage( $message );
                                    }
                                    else
                                    {
                                            $mainframe->enqueueMessage( $message , 'error' );
                                    }
                                }
                                else
                                {
                                    $mainframe->enqueueMessage( JText::_('CC PREFERENCES INVALID VALUE' ) , 'error' );
                                }
			}
		}
		echo $view->get(__FUNCTION__);
	}
	
	/**
	 * Allow user to set their privacy setting.
	 * User privacy setting is actually just part of their params	 
	 */	 	
	public function privacy()
	{
		CFactory::setActiveProfile();
		$my		= CFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}		
		
		if(JRequest::getVar( 'action', '', 'POST') != '' )
		{
			CFactory::load( 'libraries' , 'apps' );
			$appsLib		=& CAppPlugins::getInstance();
			$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array('jsform-profile-privacy' ));

			if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
			{
				$params		=& $my->getParams();
				$postvars	= JRequest::get('POST');
				$previousProfilePermission	= $my->get('privacyProfileView');
				
				foreach($postvars as $key => $val)
				{
					$params->set($key, $val);
				}
				$my->save('params');
	
				//add user points
				CFactory::load( 'libraries' , 'userpoints' );		
				CUserPoints::assignPoint('profile.privacy.update');
				
	 			//Update all photos and album permission
				$photoPermission	= JRequest::getVar('privacyPhotoView', 0, 'POST');
				$photoModel			= CFactory::getModel('photos');
				$photoModel->updatePermission($my->id, $photoPermission);
	
				//update all profile related activity streams.
				$profilePermission = JRequest::getVar('privacyProfileView', 0, 'POST');
				$activityModel = CFactory::getModel('activities');
				$activityModel->updatePermission($profilePermission, $previousProfilePermission , $my->id );
				
				$mainframe =& JFactory::getApplication();
				$mainframe->enqueueMessage( JText::_('CC PRIVACY SETTINGS SAVED') );
			}
		}
		
		$view =& CFactory::getView('profile');
		echo $view->get('privacy');
	}
	
	/**
	 * Viewing a user's profile
	 * 	 	
	 * @access	public
	 * @param	none
	 * @returns none	 
	 */
	public function profile()
	{
		// Set cookie
		$userid = JRequest::getVar('userid', 0, 'GET');		
		
		$data       = new stdClass();
		$model      =& $this->getModel('profile');
		$my         = CFactory::getUser();
		
		// Test if userid is 0, check if the user is viewing its own profile.
		if( $userid == 0 && $my->id != 0 )
		{
			$userid 	= $my->id;
			
			// We need to set the 'userid' var so that other code that uses
			// JRequest::getVar will work properly
			JRequest::setVar('userid', $userid);
		}
		
		$data->profile	= $model->getViewableProfile( $userid );

		//show error if user id invalid / not found.
        if(empty($data->profile['id']) )
		{
			$this->blockUnregister();		
		}
		else
		{
				
			CFactory::setActiveProfile($userid);
			
			$my			= CFactory::getUser();
			$appsModel	=& CFactory::getModel('apps');
					
			$avatar		=& $this->getModel('avatar');
			
			$document 	=& JFactory::getDocument();
	
			$viewType	= $document->getType();	
	 		//$viewName	= JRequest::getCmd( 'view', $this->getName() ); 						
			$view = & $this->getView( 'profile', '', $viewType);
			
			require_once (JPATH_COMPONENT.DS.'helpers'.DS.'friends.php');
			require_once (JPATH_COMPONENT.DS.'libraries'.DS.'template.php');
			
			// Try initialize the user id. Maybe that user is logged in.
			$user	= CFactory::getUser( $userid );
			$id		= $user->id;

			$data->largeAvatar			= $my->getAvatar();
			
			// Assign the user object for the current viewer whether a guest or a member
			$data->user		= $user;			
			$data->apps		= array();
			
		
			if(!$id)
			{
				echo $view->get('error', JText::_('CC USER NOT FOUND') );
			}
			else
			{
				echo $view->get(__FUNCTION__, $data, $id);
			}
		}//end if else
	}
	
	/**
	 * Links an existing photo in the system and use it as the profile picture
	 ***/	 	
	public function linkPhoto()
	{
		$id			= JRequest::getInt( 'id' , 'POST' );
		$photoModel	= CFactory::getModel( 'Photos' );
		$my			= CFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}
		
		if( $id == 0 )
		{
			echo JText::_('CC INVALID PHOTO ID');
			return;
		}
		
		$photo		=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $id );
		
		if( $my->id != $photo->creator )
		{
			echo JText::_('CC ACCESS DENIED');
			return;
		}
		
		
		jimport('joomla.filesystem.file');
		jimport('joomla.utilities.utility');

		$view 	= & $this->getView( 'profile');

		CFactory::load( 'helpers' , 'image' );
		
		$my			= CFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}				

		$mainframe		=& JFactory::getApplication();

		// @todo: configurable width?
		$imageMaxWidth	= 160;

		// Get a hash for the file name.
		$fileName		= JUtility::getHash( $photo->id . time() );
		$hashFileName	= JString::substr( $fileName , 0 , 24 );
		$photoPath		= JPATH_ROOT . DS . $photo->original;

		if( $photo->storage == 'file' )
		{
			// @rule: If photo original file still exists, we will use the original file.
			if( !JFile::exists( $photoPath ) )
			{
				$photoPath	= JPATH_ROOT . DS . $photo->image;
			}
			
			// @rule: If photo still doesn't exists, we should not allow the photo to be changed.
			if( !JFile::exists( $photoPath ) )
			{
				$mainframe->redirect( CRoute::_('index.php?option=com_community&view=profile&task=uploadAvatar' , false ) , JText::_('CC CANNOT CHANGE PROFILE PICTURE') , 'error');
				return;
			}
		}
		else
		{
			CFactory::load( 'helpers' , 'remote' );
			$content	= cRemoteGetContent( $photo->getImageURI() );
			
			if( !$content )
			{
				$mainframe->redirect( CRoute::_('index.php?option=com_community&view=profile&task=uploadAvatar' , false ) , JText::_('CC CANNOT CHANGE PROFILE PICTURE') , 'error');
				return;
			}
			$jConfig	=& JFactory::getConfig();
			$photoPath	= $jConfig->getValue('tmp_path'). DS . md5( $photo->image);	

			// Store image on temporary location
			JFile::write( $photoPath , $content );
		}

		$info			= getimagesize( $photoPath );
		$extension		= CImageHelper::getExtension( $info['mime'] );
		$config			= CFactory::getConfig();
		
		$storage			= JPATH_ROOT . DS . $config->getString('imagefolder') . DS . 'avatar';
		$storageImage		= $storage . DS . $hashFileName . $extension;
		$storageThumbnail	= $storage . DS . 'thumb_' . $hashFileName . $extension;
		$image				= $config->getString('imagefolder') . '/avatar/' . $hashFileName . $extension;
		$thumbnail			= $config->getString('imagefolder') . '/avatar/' . 'thumb_' . $hashFileName . $extension;
		$userModel			=& CFactory::getModel( 'user' );

		// Only resize when the width exceeds the max.
		if( !CImageHelper::resizeProportional( $photoPath , $storageImage , $info['mime'] , $imageMaxWidth ) )
		{
			$mainframe->enqueueMessage(JText::sprintf('CC ERROR MOVING UPLOADED FILE' , $storageImage), 'error');
		}

		// Generate thumbnail
		if(!CImageHelper::createThumb( $photoPath , $storageThumbnail , $info['mime'] ))
		{
			$mainframe->enqueueMessage(JText::sprintf('CC ERROR MOVING UPLOADED FILE' , $storageThumbnail), 'error');
		}

		if( $photo->storage != 'file' )
		{
			//@rule: For non local storage, we need to remove the temporary photo
			JFile::delete( $photoPath );
		}
		
		$userModel->setImage( $my->id , $image , 'avatar' );
		$userModel->setImage( $my->id , $thumbnail , 'thumb' );

		// Update the user object so that the profile picture gets updated.
		$my->set( '_avatar' , $image );
		$my->set( '_thumb'	, $thumbnail );

		$mainframe->redirect( CRoute::_('index.php?option=com_community&view=profile&task=uploadAvatar' , false ) , JText::_('CC PROFILE PICTURE MODIFIED') );
	}
	
	/**
	 * Upload a new user avatar
	 */	 	
	public function uploadAvatar()
	{
		CFactory::setActiveProfile();
		
		jimport('joomla.filesystem.file');
		jimport('joomla.utilities.utility');

		$view 	= & $this->getView( 'profile');

		CFactory::load( 'helpers' , 'image' );
		
		$my			= CFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}				
		
		// If uplaod is detected, we process the uploaded avatar
		if( JRequest::getVar('action', '', 'POST') )
		{
			$mainframe =& JFactory::getApplication();
						
			$file		= JRequest::getVar( 'Filedata' , '' , 'FILES' , 'array' );

			$userid		= $my->id;

			if(JRequest::getVar('userid' , '' , 'POST') != ''){
				$userid		= JRequest::getInt( 'userid' , '' , 'POST' );
				$url		= CRoute::_('index.php?option=com_community&view=profile&userid=' . $userid );
			}

			if( !isset( $file['tmp_name'] ) || empty( $file['tmp_name'] ) )
			{	
				$mainframe->enqueueMessage(JText::_('CC NO POST DATA'), 'error');

				if(isset($url)){
					$mainframe->redirect($url);
				}
			}
			else
			{
				$config			= CFactory::getConfig();
				$uploadLimit	= (double) $config->get('maxuploadsize');
				$uploadLimit	= ( $uploadLimit * 1024 * 1024 );

				// @rule: Limit image size based on the maximum upload allowed.
				if( filesize( $file['tmp_name'] ) > $uploadLimit )
				{
					$mainframe->enqueueMessage( JText::_('CC IMAGE FILE SIZE EXCEEDED') , 'error' );

					if(isset($url)){
						$mainframe->redirect($url);
					}

					$mainframe->redirect( CRoute::_('index.php?option=com_community&view=profile&userid=' . $userid . '&task=uploadAvatar', false) );
				}

                if( !CImageHelper::isValidType( $file['type'] ) )
				{
					$mainframe->enqueueMessage( JText::_('CC IMAGE FILE NOT SUPPORTED') , 'error' );

					if(isset($url))
					{
						$mainframe->redirect($url);
					}

					$mainframe->redirect( CRoute::_('index.php?option=com_community&view=profile&userid=' . $userid . '&task=uploadAvatar', false) );
            	}
				
				if( !CImageHelper::isValid($file['tmp_name'] ) )
				{
					$mainframe->enqueueMessage(JText::_('CC IMAGE FILE NOT SUPPORTED'), 'error');

					if(isset($url)){
						$mainframe->redirect($url);
					}
				}
				else
				{
					// @todo: configurable width?
					$imageMaxWidth	= 160;

					// Get a hash for the file name.
					$fileName		= JUtility::getHash( $file['tmp_name'] . time() );
					$hashFileName	= JString::substr( $fileName , 0 , 24 );

					//@todo: configurable path for avatar storage?

					$storage			= JPATH_ROOT . DS . $config->getString('imagefolder') . DS . 'avatar';
					$storageImage		= $storage . DS . $hashFileName . CImageHelper::getExtension( $file['type'] );
					$storageThumbnail	= $storage . DS . 'thumb_' . $hashFileName . CImageHelper::getExtension( $file['type'] );
					$image				= $config->getString('imagefolder') . '/avatar/' . $hashFileName . CImageHelper::getExtension( $file['type'] );
					$thumbnail			= $config->getString('imagefolder') . '/avatar/' . 'thumb_' . $hashFileName . CImageHelper::getExtension( $file['type'] );
						
					$userModel			=& CFactory::getModel( 'user' );


					// Only resize when the width exceeds the max.
					if( !CImageHelper::resizeProportional( $file['tmp_name'] , $storageImage , $file['type'] , $imageMaxWidth ) )
					{
						$mainframe->enqueueMessage(JText::sprintf('CC ERROR MOVING UPLOADED FILE' , $storageImage), 'error');

						if(isset($url)){
							$mainframe->redirect($url);
						}
					}

					// Generate thumbnail
					if(!CImageHelper::createThumb( $file['tmp_name'] , $storageThumbnail , $file['type'] ))
					{
						$mainframe->enqueueMessage(JText::sprintf('CC ERROR MOVING UPLOADED FILE' , $storageThumbnail), 'error');

						if(isset($url)){
							$mainframe->redirect($url);
						}
					}			
							
					$userModel->setImage( $userid , $image , 'avatar' );
					$userModel->setImage( $userid , $thumbnail , 'thumb' );
					
					// Update the user object so that the profile picture gets updated.
					$my->set( '_avatar' , $image );
					$my->set( '_thumb'	, $thumbnail );

					if(isset($url)){
						$mainframe->redirect($url);
					}
					
					//add user points
					CFactory::load( 'libraries' , 'userpoints' );
					CFactory::load( 'libraries' , 'activities');
							
					$act = new stdClass();
					$act->cmd 		= 'profile.avatar.upload';
					$act->actor   	= $userid;
					$act->target  	= 0;
					$act->title	  	= JText::_('CC ACTIVITIES NEW AVATAR');
					$act->content	= '';
					$act->app		= 'profile';
					$act->cid		= 0;
							
					// Add activity logging
					CFactory::load ( 'libraries', 'activities' );
					CActivityStream::add( $act );
				
					CUserPoints::assignPoint('profile.avatar.upload');
				}
			}
		}
				
		echo $view->get( __FUNCTION__ );
	}

	/**
	 * Upload a new user video.
	 */
	public function linkVideo()
	{
		CFactory::setActiveProfile();
		$my		= CFactory::getUser();
                $config		=& CFactory::getConfig();
	
		if($my->id == 0)
		{
			return $this->blockUnregister();
		}

                if( !$config->get('enableprofilevideo') )
                {
                    echo JText::_('CC PROFILE VIDEO DISABLE');
                    return;
                }
	
		$view 	= $this->getView( 'profile');
	
		echo $view->get( __FUNCTION__ );
	}
	
	
	/**
	 * Display drag&drop layout editing inetrface
	 */	 	
	public function editLayout()
	{
		$my		= CFactory::getUser();
	
		if($my->id == 0)
		{
			return $this->blockUnregister();
		}
	
		$view 	= $this->getView( 'profile');
	
		echo $view->get( __FUNCTION__ );
		
	}
	
	/**
	 * Full application view
	 */	 	
	public function app()
	{
		require_once (JPATH_COMPONENT.DS.'libraries'.DS.'apps.php');

		$view = & $this->getView('profile');
		echo $view->get( 'appFullView' );
	}
	
	/**
	 * Show pop up error message screen
	 * for invalid image file upload	 
	 */	 
	public function ajaxErrorFileUpload()
	{
		$objResponse	= new JAXResponse();
				
		$html			= '<div style="overflow:auto; height:200px; position: absolute-nothing;">' . JText::_('CC PHOTO UPLOAD DESC') . '</div>';
		$action			= '<button class="button" onclick="javascript:cWindowHide();" name="close">' . JText::_('CC BUTTON CLOSE') . '</button>';

		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);
		$objResponse->addScriptCall('cWindowActions', $action);

		return $objResponse->sendResponse();
	}
	
	/*
	 * Allow users to delete their own profile
	 * 
	 */
	public function deleteProfile()
	{
		$view	=& $this->getView('profile');
		$method	= JRequest::getMethod();
		
		$my			= CFactory::getUser();
		
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}				
		
		if($method == 'POST')
		{
			// Instead of delete the user straight away, 
			// we'll block the user and notify the admin. 
			// Admin then would delete the user from backend
			JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
			$my->set('block', 1);
			$my->save();
			
			// send notification email
			$model		=& CFactory::getModel( 'profile' );
			$emails		= $model->getAdminEmails();
			$url		= rtrim( JURI::root() , '/' ) . '/administrator/index.php?option=com_community&view=users&layout=edit&id=' . $my->id;

			// Add notification
			CFactory::load( 'libraries' , 'notification' );
			
			$params			= new JParameter( '' );
			$params->set( 'userid' , $my->id );
			$params->set( 'username' , $my->getDisplayName() );
			$params->set( 'url' , $url );
			
			$subject		= JText::sprintf( 'CC USER ACCOUNT DELETED SUBJECT' , $my->getDisplayName() );
			CNotificationLibrary::add( 'user.profile.delete' , $my->id , $emails , $subject , '' , 'user.deleted' , $params );
			
			// logout and redirect the user
			$mainframe	=& JFactory::getApplication();
			$mainframe->logout($my->id);
			$mainframe->redirect(CRoute::_('index.php?option=com_community', false));
		}
		echo $view->get(__FUNCTION__);
	}

    /**
     * Block a user
     */         
	public function ajaxBlockUser( $userId )
	{
                $my             = CFactory::getUser();
		$response	= new JAXResponse();
		$config		=& CFactory::getConfig();   

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		
		$content	= JText::_('CC CONFIRM BLOCK USER'); 
		$redirect   = CRoute::_("index.php?option=com_community&view=profile&userid=" . $userId . "&task=blockUser" , false);

		$buttons	= '<form name="jsform-profile-ajaxblockuser" method="post" action="' . $redirect . '" style="float:right;">';
		$buttons	.= '<input type="submit" value="' . JText::_('CC BUTTON YES') . '" class="button" name="Submit"/>';
		$buttons	.= '<input type="button" class="button" onclick="cWindowHide();return false;" name="cancel" value="'.JText::_('CC BUTTON NO').'" />'; 
		$buttons	.= '</form>';   

		// Add invite button
		//$response->addScriptCall('cWindowResize' , 190 );
		$response->addAssign('cWindowContent' , 'innerHTML' , $content);
		$response->addScriptCall('joms.jQuery("#cwin_logo").html("' . $config->get('sitename') . '");');
		$response->addScriptCall('cWindowActions', $buttons);
		$response->sendResponse();

	}

	public function blockUser()
	{  
        $my = CFactory::getUser();
	
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}
              
        $userId = JRequest::getVar('userid','','GET');
        
        CFactory::load ( 'libraries', 'block' );
        $blockUser  = new blockUser;
        $blockUser->block( $userId );
	}  
    
    /**
     * unBlock a user
     */  
	public function ajaxUnblockUser( $userId, $layout = null )
	{
        $my         = CFactory::getUser();
		$response	= new JAXResponse();
		$config		=& CFactory::getConfig();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}		
		
		$content	= JText::_('CC CONFIRM UNBLOCK USER'); 
		$layout     = !empty($layout) ? '&layout=' . $layout : '' ;
		$redirect   = CRoute::_("index.php?option=com_community&view=profile&userid=" . $userId . "&task=unBlockUser" . $layout , false);

		$buttons	= '<form name="jsform-profile-ajaxunblockuser" method="post" action="' . $redirect . '" style="float:right;">';
		$buttons	.= '<input type="submit" value="' . JText::_('CC BUTTON YES') . '" class="button" name="Submit"/>';    
		$buttons	.= '<input type="button" class="button" onclick="cWindowHide();return false;" name="cancel" value="'.JText::_('CC BUTTON NO').'" />';
		$buttons	.= '</form>';

		// Add invite button
		//$response->addScriptCall('cWindowResize' , 190 );
		$response->addAssign('cWindowContent' , 'innerHTML' , $content);
		$response->addScriptCall('joms.jQuery("#cwin_logo").html("' . $config->get('sitename') . '");');
		$response->addScriptCall('cWindowActions', $buttons);
		$response->sendResponse();

	}  	 
	
	/**
	 * Un Ban member or friend (for ajax remove only)
	 */
	public function unBlockUser()
	{ 
        $my = CFactory::getUser();
	
		if($my->id == 0)
		{
		   return $this->blockUnregister();
		}
		
        $userId = JRequest::getVar('userid','','GET');
		$layout = JRequest::getVar('layout','','GET');
        
        CFactory::load ( 'libraries', 'block' );
        $blockUser  = new blockUser;
        $blockUser->unBlock( $userId , $layout ); 		
	}

	/**
	 * Method to view profile video
	 */
	public function video()
	{
        $view	=& $this->getView('profile');
        echo $view->get(__FUNCTION__);
	}
}
