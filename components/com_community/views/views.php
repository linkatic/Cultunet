<?php
/**
 * @package	JomSocial
 * @subpackage Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');

class CommunityView extends JView
{
	var $_info 		= array();
	var $_warning 	= array();
	var $_error 	= array();
	var $_submenu	= array();
	var $title 	= '';
	var $_mini	= '';
	var	$params	= array();
	var $_showMiniHeaderUser = '';
	
	function __construct($config = array())
	{
		$mainframe =& JFactory::getApplication();
		$this->my =& JFactory::getUser();
		parent::__construct($config);
	}
	
	/**
	 * 
	 **/	 	
	function setTitle($title) {
		CFactory::load( 'helpers' , 'string' );
		$this->title = CStringHelper::escape($title);
	}
	
	/**
	 * Append the given message into a global info messages
	 * @param string	the final message	 
	 */	 	
	function addInfo($message)
	{
		$mainframe	=& JFactory::getApplication();
		$mainframe->enqueueMessage($message);
	}
	
	/**
	 * Adds a pathway item to the breadcrumbs
	 **/
	function addPathway( $text , $link = '' )
	{
		// Set pathways
		$mainframe		=& JFactory::getApplication();
		$pathway		=& $mainframe->getPathway();
		
		$pathwayNames	= $pathway->getPathwayNames();
		
		// Test for duplicates before adding the pathway
		if( !in_array( $text , $pathwayNames ) )
		{
			$pathway->addItem( $text , $link );
		}
	}
	
	/**
	 * Display no access notice
	 */	 	
	function noAccess($notice = '')
	{
		$tmpl	= new CTemplate();
		$notice	= empty( $notice ) ? JText::_('CC NOT ALLOWED TO VIEW PAGE') : $notice;
		
		$tmpl->set( 'notice' , $notice );										
		echo $tmpl->fetch('notice.access');
	}
	
	/**
	 * Append the given message into a global warning messages
	 * @param string	the final message	 
	 */	 
	function addWarning($message){
		//$this->_warning[] = $message;
		$mainframe =& JFactory::getApplication();
		$mainframe->enqueueMessage($message, 'notice');
	}
	
	function attachMiniHeaderUser( $userId )
	{
		$this->_showMiniHeaderUser	= $userId;
	}
	
	/**
	 *
	 */	 	
	function addSubmenuItem($link ='', $title = '', $onclick='' , $isAction = false )
	{
		$obj = new stdClass();
		$obj->link = $link;

		// If onclick is used, completely ignore the following
		if( !empty($onclick) )
		{
			$obj->onclick	= $onclick;
		}
		else
		{
			// We need to get the view & task from the link that is provided in $link
			// Remove default 'index.php?option=com_community&' from $link
			$link	= JString::str_ireplace('index.php?option=com_community&' , '' , $link);
			$links  = explode('&' , $link );
	
			// Pre-set the task so that links that does not contain task will not
			// fail.
			$obj->task  = '';
			foreach( $links as $row )
			{
				$active  = explode('=' , $row);
				
				if($active[0] == 'view')
			 	   $obj->view  = $active[1];
	
				if($active[0] == 'task')
				    $obj->task  = $active[1];
			}
		}

		$obj->action	= $isAction;
		$obj->title		= $title;
		$this->_submenu[] = $obj;
	}
	
	
	function showToolbar($data = null)
	{
		$mySQLVer	= 0;
		if(JFile::exists(JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'libraries'.DS.'advancesearch.php'))
		{
			require_once (JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'libraries'.DS.'advancesearch.php');
			$mySQLVer	= CAdvanceSearch::getMySQLVersion();
		}
			
		require_once (JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'libraries'.DS.'toolbar.php');
		require_once (JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'libraries'.DS.'miniheader.php');
		$format	= JRequest::getVar('format' , 'html' , 'get');
		
		if($format == 'json')
			return;
			
		$mainframe =& JFactory::getApplication();		
		$document =& JFactory::getDocument();
		$my 	= CFactory::getUser();
		
		$userid		= JRequest::getVar( 'userid' , '' );
		$user		= CFactory::getUser($userid);
		
		// Get the configuration object.
		$config	=& CFactory::getConfig();
		
		//JHTML::_('behavior.tooltip');
		
		$js = '/assets/window-1.0';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';
		CAssets::attach($js, 'js');
		
		$js	= '/assets/script-1.2';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';
		CAssets::attach($js, 'js');
		
		$js = '<script type=\'text/javascript\'>';
		$js .= '/*<![CDATA[*/';
		$js .= 'var js_viewerId  = '. $my->id .'; ';
		$js .= 'var js_profileId = '. $user->id .';';
		$js .= '/*]]>*/';
		$js .= '</script>';
		$mainframe->addCustomHeadTag($js);
		
		CFactory::load( 'libraries' , 'template' );
		CTemplate::addStyleSheet( 'style' );
		
		// FOr iPhone, we need to add the stylesheet AFTER the main stylesheet has been loaded
		if(JRequest::getVar('format') =='iphone') 
		{
			$document->addStylesheet( JURI::root() . 'components/com_community/templates/default/css/style.iphone.css' );
		}
		
		// This need to be loaded in main messaging library
		CFactory::load( 'libraries' , 'window' );
		CWindow::load();
		
		// add alternate CSS file
		$overrideExists	= JFolder::exists( JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html' . DS . 'com_community' );
		
		$styleIE7Path	= JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html' . DS . 'com_community' . DS . 'css' . DS . 'styleIE7.css';
		$tmplIE7Path	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'templates' . DS . $config->get( 'template' ) . DS . 'css' . DS . 'styleIE7.css';
		
		if( JFile::exists( $styleIE7Path ) )
		{
			$styleIE7File	= '/templates/' . $mainframe->getTemplate() . '/html/com_community/css/styleIE7.css';
		}
		else if( JFile::exists( $tmplIE7Path ) && !$overrideExists )
		{
			$styleIE7File	= '/components/com_community/templates/' . $config->get( 'template' ) . '/css/styleIE7.css';
		}
		else
		{
			$styleIE7File	= '/components/com_community/templates/default/css/styleIE7.css';
		}
		
		$styleIE6Path	= JPATH_ROOT . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html' . DS . 'com_community' . DS . 'css' . DS . 'styleIE6.css';
		$tmplIE6Path	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'templates' . DS . $config->get( 'template' ) . DS . 'css' . DS . 'styleIE6.css';
		
		if( JFile::exists( $styleIE6Path ) )
		{
			$styleIE6File	= '/templates/' . $mainframe->getTemplate() . '/html/com_community/css/styleIE6.css';
		}
		else if( JFile::exists( $tmplIE6Path ) && !$overrideExists )
		{
			$styleIE6File	= '/components/com_community/templates/' . $config->get( 'template' ) . '/css/styleIE6.css';
		}
		else
		{
			$styleIE6File	= '/components/com_community/templates/default/css/styleIE6.css';
		}

		$css = '
				<!-- Jom Social -->
				<!--[if IE 7.0]>
				<link rel="stylesheet" href="'. rtrim( JURI::root() , '/' ) . $styleIE7File . '" type="text/css" />
				<![endif]-->
				<!--[if lte IE 6]>
				<link rel="stylesheet" href="'. rtrim( JURI::root() , '/' ) . $styleIE6File . '" type="text/css" />
				<![endif]-->';

		$mainframe->addCustomHeadTag( $css );
		
        $css = '/assets/autocomplete.css';
		CAssets::attach($css, 'css');
		
		$task			= JRequest::getVar( 'task' , '' , 'GET' );
		$groupId		= JRequest::getVar( 'groupid' , '' , 'GET' );
		
		// Hide the toolbar from unregistered user
		// but still show the mini header
		if(empty($my->id))
		{
			if( !empty($groupId) && $task != 'viewgroup' )
			{
				CFactory::load( 'libraries' , 'miniheader' );
				echo CMiniHeader::showGroupMiniHeader( $groupId );
				return;
			}

			echo CMiniHeader::showMiniHeader($this->_showMiniHeaderUser);
			return;
		}								
		
		/**
		 * Inbox unread count
		 */
		$inboxUnread = 0; 
		if(! empty($data['inbox'])) $inboxUnread = $data['inbox'];
		
		/**
		 * Notification alert
		 */
		$notiAlert	= 0;
		$notiAlert	= COMMUNITY_FREE_VERSION ? 0 : $this->_newNotification(); 

		if ( (!empty($notiAlert)) && ($notiAlert > 0) )
		{
			CFactory::load( 'libraries' , 'window' );
			
			CWindow::load();
		}

		$config			=& CFactory::getConfig();
		$logoutLink		= CRoute::_( 'index.php?option=com_community&view=frontpage' , false );
		$logoutLink		= base64_encode( $logoutLink );
		$isFacebookUser	= false;
		
		if( $config->get('fbconnectkey') && $config->get('fbconnectsecret') )
		{
			CFactory::load( 'libraries' , 'facebook' );
			CFactory::load( 'models' , 'connect' );

			// Once they reach here, we assume that they are already logged into facebook.
			// Since CFacebook library handles the security we don't need to worry about any intercepts here.
			$facebook		= new CFacebook();
			$connectTable	=& JTable::getInstance( 'Connect' , 'CTable' );
			$fbUser			= $facebook->getUser();
			$connectTable->load( $fbUser );
	
			$isFacebookUser	= ( $connectTable->userid == $my->id );
		}


		$groupMiniHeader= '';
		// Show miniheader
		if( $task != 'viewgroup' )
		{
			CFactory::load( 'libraries' , 'miniheader' );
			$groupMiniHeader	= CMiniHeader::showGroupMiniHeader( $groupId );
		}
		$tmpl = new CTemplate();
		$tmpl->set('my', $my);
		$tmpl->set('isMine', COwnerHelper::isMine($my->id, $user->id));
		$tmpl->set('config' , $config);
		$tmpl->set('inboxUnread', $inboxUnread);
		$tmpl->set('notiAlert', $notiAlert);
		$tmpl->set('miniheader', CMiniHeader::showMiniHeader($this->_showMiniHeaderUser));
		$tmpl->set('groupMiniHeader', $groupMiniHeader );
		$tmpl->set('showAdvanceSearch', ($mySQLVer > 4.1) ? 1 : 0);
		$tmpl->set( 'logoutLink' , $logoutLink );		
		$tmpl->set( 'isFacebookUser' , $isFacebookUser );

		$toolbar =& CFactory::getToolbar();
		$tmpl->set('customToolbar', $toolbar);
		
		echo $tmpl->fetch('toolbar.index');
	}
	
	/**
	 *
	 */	 	
	function showSubmenu( )
	{
		if(!empty($this->_submenu)){
			$view   = JRequest::getVar('view' , '' , 'GET');
	    	$task   = JRequest::getVar('task' , '' , 'GET');
			
			$tmpl = new CTemplate();
			$tmpl ->set('submenu', $this->_submenu);
			$tmpl ->set('view', $view);
			$tmpl ->set('task', $task);
			echo $tmpl->fetch('toolbar.submenu');
			unset($tmpl);
		}
	}
	
	/**
	 * Return the page header
	 * @return	string	 
	 */	 	
	function showHeader($title, $icon = null, $buttons = null){

		$view = JRequest::getVar('view', 'frontpage', 'REQUEST');
		$task = JRequest::getVar('task', '', 'REQUEST');
		
	    $style = '';
	    if ( $icon != null )
	        $style .= ' toolbar-icon-'.$icon;
	        
	    $showHead = true;
	    
	    // Do not add header to know page, profile
		if(($view == 'profile' && ($task == '' || $task == 'display')) || $view == 'frontpage')
			$showHead = false;
		
		
		if($showHead){
			$tmpl = new CTemplate();
			$tmpl->set('style', $style);
			$tmpl->set('title', $title);
			
			echo $tmpl->fetch('toolbar.header');
		}
	}
	
	/**
	 * Return page submenu
	 */	 	
	function getSubMenu(){
	}
	
	/**
	 * Get the processed content
	 * 	 
	 * @param	string	$tplName	method name to call
	 * @param	array	$data		data for the template
	 * @param	string	$cached		should we result be cached?
	 * @return	string				the final output 	 
	 */	 	
	function get($tplName, $data=null, $cached=false){

		if(!empty($tplName) && is_callable(array($this, $tplName))){
			
			ob_start();
			$this->$tplName($data);
			$html = ob_get_contents();
			ob_end_clean();
			
			
			$info 		= '';
			if(!empty($this->_info)){
				foreach($this->_info as $msg){
					$info .= $this->info($msg);
				}
			}
			
			$warning 	= '';
			if(!empty($this->_warning)){
				foreach($this->_warning as $msg){
					$warning .= $this->warning($msg);
				}
			}
			
			$error 		= '';
			$messages = array($error, $warning, $info);
			
			// append all warning, error and info
			$html = JString::str_ireplace(array('{error}','{warning}', '{info}'), $messages, $html);
			return $html;
			
		} else {
			JError::raiseError( 500, JText::sprintf('CC VIEW NOT FOUND' , $tplName) );
		}
		
	}
	
	/**
	 * Check if current user has the correct permission to view the page.
	 * We will validate access based on the current profile privacy setting and
	 * the access type give. Should be called by view
	 * 
	 * @param string type The access type, one of CUser param variables or 
	 * 	 mine (active profile = my id)/registered(any registered user)
	 * @param bool $showWarning 	 	 	 	 	 
	 * @return	bool true if access is OK	 
	 */	 	
	function accessAllowed($type = '', $showWarning = true)
	{
		if(empty($type))
			return true;

		$my 	= CFactory::getUser();
		
		$userid		= JRequest::getVar( 'userid' , '' );
		$user		= CFactory::getUser($userid);
		
		
		// @rule: For site administrators / community admin, we should allow access
		// no matter what the privacy is.
		CFactory::load( 'helpers' , 'owner' );
		if( COwnerHelper::isCommunityAdmin() )
		{
			return true;
		}
		
		if($type == 'registered')
		{
			if(!$my->id){
				$mainframe =& JFactory::getApplication();
				$mainframe->enqueueMessage(JText::_('CC RESTRICTED ACCESS'), 'notice');
				$this->noAccess();
				return false;
			} else
				return true;
		}
		
		// you can always view your own profile
		if(COwnerHelper::isMine($my->id, $user->id)){
			return true;
		}
		
		$param =& $user->getParams();
		
		if($type == 'mine')
			$access = PRIVACY_PRIVATE;
		else
			$access = $param->get($type);

		switch($access){
			case PRIVACY_PUBLIC:
				return true;
				break;
				
			case PRIVACY_MEMBERS:
				if( $my->id == 0 )
				{
					$mainframe =& JFactory::getApplication();
					$mainframe->enqueueMessage(JText::_('CC RESTRICTED ACCESS'), 'notice');				
					$tmpl = new CTemplate();
					
					if($type == 'privacyProfileView')
					{
						$userInfo = $this->_prepUser($user);
						if(! empty($userInfo))
						{	
							$tmpl->set('data', $userInfo);
							$tmpl->set('sortings', '');
							$tmpl->set('featuredList' , '');
							$tmpl->set('isCommunityAdmin','');
							$tmpl->set('my' , $my );
							echo $tmpl->fetch('people.browse');
						}
						else							
							//user object not found.
							echo $tmpl->fetch('notice.access');
							
					}				
					else
					{																
						$this->noAccess();
					}
					return false;
				}
				return true;	
				break;
				
			case PRIVACY_FRIENDS:
				CFactory::load('helpers', 'friends');

				if( !CFriendsHelper::isConnected($my->id, $user->id) )
				{
					$mainframe =& JFactory::getApplication();
					$mainframe->enqueueMessage(JText::_('CC RESTRICTED ACCESS'), 'notice');
					$tmpl = new CTemplate();
					
					if($type == 'privacyProfileView')
					{
						$userInfo = $this->_prepUser($user);
						if(! empty($userInfo))
						{	
							$tmpl->set('data', $userInfo);
							$tmpl->set('sortings', '');// add this variable to avoid error thrown.																
							$tmpl->set('featuredList' , '');
							$tmpl->set('isCommunityAdmin','');
							$tmpl->set('my',$my);
							echo $tmpl->fetch('people.browse');
						}
						else
						{							
							//user object not found.
							$this->noAccess();
						}
					}	
					else
					{
						$this->noAccess();
					}
					return false;
				}
				else				
					return true;
				
				break;
				
			case PRIVACY_PRIVATE:

				if($my->id != $user->id)
				{
					$mainframe =& JFactory::getApplication();
					$mainframe->enqueueMessage(JText::_('CC RESTRICTED ACCESS'), 'error');
					$this->noAccess();
					return false;
				} else
					return true;
					
				break;
		}
			
		return true;
	}

	/**
	 * Test if the application is viewable by the current browser.
	 * 
	 * @param string $privacy The privacy settings for the app.
	 * @return	bool true if access is OK	 
	 */	 	
	function appPrivacyAllowed( $privacy = 0 )
	{
		if( $privacy == 0 )
		{
			return true;
		}

		$my 		= CFactory::getUser();
		$userid		= JRequest::getVar( 'userid' , '' );
		$user		= CFactory::getUser($userid);
		
		$mainframe 	=& JFactory::getApplication();
		
		switch( $privacy )
		{
			case PRIVACY_APPS_FRIENDS:
				if( !CFriendsHelper::isConnected($my->id, $user->id) )
				{
					return false;
				}
				break;
			case PRIVACY_APPS_SELF:
				if( $my->id != $user->id )
				{
					return false;
				}
				break;
		}
		return true;
	}

	/**
	 * Show profile miniheader
	 */
	function _getMiniHeader()
	{
		CFactory::load( 'helpers' , 'friends' );

	    $my 	= CFactory::getUser();
	    
        $config	=& CFactory::getConfig();

	    if ( !empty( $this->_showMiniHeaderUser ) )
		{
			$user 	= CFactory::getUser( $this->_showMiniHeaderUser );

			CFactory::load( 'libraries' , 'messaging' );
			$sendMsg	= CMessaging::getPopup ( $user->id );
        	$tmpl		= new CTemplate();
        	$tmpl->set( 'my' 		, $my);
        	$tmpl->set( 'user' 		, $user);
        	$tmpl->set( 'isMine'	, COwnerHelper::isMine($my->id, $user->id));
        	$tmpl->set( 'sendMsg'	, $sendMsg );
        	$tmpl->set( 'config'	, $config );
        	$tmpl->set( 'isFriend'	, CFriendsHelper::isConnected ( $user->id, $my->id ) && $user->id != $my->id );
        	return $tmpl->fetch('profile.miniheader');
        }
	}
	
	/**
	 *
	 */
	function _newNotification()
	{
		$my 	= CFactory::getUser();

		$inboxModel		=& CFactory::getModel( 'inbox' );
		$friendModel	=& CFactory::getModel ( 'friends' );
		$eventModel		=& CFactory::getmodel( 'events' );
		
		$filter = array();
		$filter ['user_id'] = $my->id;
		$inboxAlert		= $inboxModel->countUnRead( $filter );
		$frenAlert		= $friendModel->countPending( $my->id );
		$eventAlert		= $eventModel->countPending( $my->id );
		
		return ($inboxAlert + $frenAlert + $eventAlert);
	} 
	 	 	 	
	
	/**
	 * This function will prep user info so that it can display user mini header in privacy warning template.
	 * Do not call this function outside this view.php	 	 
	 */	 	 	
	
	function _prepUser( $user )
	{			
		if(! empty($user))
		{
			$obj = new stdClass();	
			$my					= CFactory::getUser();			
			$user				= CFactory::getUser( $user->id );
			$obj->friendsCount  = $user->getFriendCount();
			$obj->user			= $user;
			$obj->profileLink	= CUrl::build( 'profile' , '' , array( 'userid' => $user->id ) );
			$isFriend 			= CFriendsHelper::isConnected( $user->id, $my->id );
			
			$obj->addFriend 	= ((! $isFriend) && ($my->id != 0) && $my->id != $user->id) ? true : false;
			return array( $obj );
		}
		
		return false;

	}
}
