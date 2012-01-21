<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

class CommunityViewEvents extends CommunityView
{

	function _addSubmenu()
	{
	    $config		=& CFactory::getConfig();
		$task	    = JRequest::getVar( 'task' , '' , 'GET' );
		$backLink	= array( 'invitefriends', 'viewguest', 'uploadavatar' , 'edit' , 'sendmail', 'app');
		$my = CFactory::getUser();
		
		if( in_array( $task , $backLink) )
		{

		    $eventid	= JRequest::getVar( 'eventid' , '' , 'GET' );

			$this->addSubmenuItem('index.php?option=com_community&view=events&task=viewevent&eventid=' . $eventid, JText::_('CC BACK TO EVENT'));
		}
		else
		{
    		$this->addSubmenuItem('index.php?option=com_community&view=events', JText::_('CC ALL EVENTS') );

			if( COwnerHelper::isRegisteredUser())
			{
				$this->addSubmenuItem('index.php?option=com_community&view=events&task=myevents&userid='. $my->id, JText::_('CC MY EVENTS'));
				$this->addSubmenuItem('index.php?option=com_community&view=events&task=myinvites&userid='. $my->id, JText::_('CC EVENT PENDING INVITATIONS'));
				$this->addSubmenuItem('index.php?option=com_community&view=events&task=expiredevents&userid='. $my->id, JText::_('CC PAST EVENTS TITLE'));
			}

    		if( COwnerHelper::isRegisteredUser() && $config->get('createevents') || COwnerHelper::isCommunityAdmin() )
			{
    			$this->addSubmenuItem('index.php?option=com_community&view=events&task=create', JText::_('CC CREATE EVENT') , '' , SUBMENU_RIGHT );
			}

    		$this->addSubmenuItem('index.php?option=com_community&view=events&task=search', JText::_('CC SEARCH EVENTS'));
		}

	}
	
	function showSubmenu(){
		$this->_addSubmenu();
		parent::showSubmenu();
	}

	/**
	 * Application full view
	 **/
	function appFullView()
	{
		$document =& JFactory::getDocument();
		
		$this->showSubmenu();
		
		$applicationName = JString::strtolower( JRequest::getVar( 'app' , '' , 'GET' ) );

		if(empty($applicationName))
		{
			JError::raiseError( 500, 'CC APP ID REQUIRED');
		}
		
		if(!$this->accessAllowed('registered'))
			return;

		$output	= '';
		
		//@todo: Since group walls doesn't use application yet, we process it manually now.
		if( $applicationName == 'walls' )
		{
			CFactory::load( 'libraries' , 'wall' );
			$jConfig	= JFactory::getConfig();
			$limit		= $jConfig->get('list_limit');
			$limitstart = JRequest::getVar( 'limitstart', 0, 'REQUEST' );
			$eventId	= JRequest::getVar( 'eventid' , '' , 'GET' );
			$my			= CFactory::getUser();
			$config		=& CFactory::getConfig();

			$eventsModel	=& CFactory::getModel( 'Events' );
			$event			=& JTable::getInstance( 'Event' , 'CTable' );
			$event->load( $eventId );
			$config			=& CFactory::getConfig();
			$document->setTitle( JText::sprintf('CC EVENTS WALL TITLE' , $event->title ) );		
			CFactory::load( 'helpers' , 'owner' );

			$guest				= $event->isMember( $my->id );
			$waitingApproval	= $event->isPendingApproval( $my->id );
			$status				= $event->getUserStatus($my->id);
			$responded			= (($status == COMMUNITY_EVENT_STATUS_ATTEND)
									|| ($status == COMMUNITY_EVENT_STATUS_WONTATTEND)
									|| ($status == COMMUNITY_EVENT_STATUS_MAYBE));

			if( !$config->get('lockeventwalls') || ($config->get('lockeventwalls') && ($guest) && !($waitingApproval) && $responded) || COwnerHelper::isCommunityAdmin() )
			{
				$output	.= CWallLibrary::getWallInputForm( $event->id , 'events,ajaxSaveWall', 'events,ajaxRemoveWall' );

				// Get the walls content
				$output 		.='<div id="wallContent">';
				$output			.= CWallLibrary::getWallContents( 'events' , $event->id , $event->isAdmin( $my->id ) , 0 , $limitstart , 'wall.content' ,'events,events');
				$output 		.= '</div>';
				
				jimport('joomla.html.pagination');
				$wallModel 		=& CFactory::getModel('wall');
				$pagination		= new JPagination( $wallModel->getCount( $event->id , 'events' ) , $limitstart , $limit );
	
				$output		.= '<div class="pagination-container">' . $pagination->getPagesLinks() . '</div>';
			}
		}
		else
		{
			CFactory::load( 'libraries' , 'apps' );
			$model				=& CFactory::getModel('apps');
			$applications		=& CAppPlugins::getInstance();
			$applicationId		= $model->getUserApplicationId( $applicationName );
			
			$application		= $applications->get( $applicationName , $applicationId );

			if( !$application )
			{
				JError::raiseError( 500 , 'CC APPLICATION NOT FOUND' );
			}
			
			// Get the parameters
			$manifest			= JPATH_PLUGINS . DS . 'community' . DS . $applicationName . DS . $applicationName . '.xml';
			
			$params			= new JParameter( $model->getUserAppParams( $applicationId ) , $manifest );
	
			$application->params	=& $params;
			$application->id		= $applicationId;
			
			$output	= $application->onAppDisplay( $params );
		}
		
		echo $output;
	}

	function display($tpl = null)
	{

	    $mainframe	=& JFactory::getApplication();
	    $document 	=& JFactory::getDocument();
	    $config		=& CFactory::getConfig();
		$my			= CFactory::getUser();

        //page title
		$this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
        $document->setTitle(JText::_('CC BROWSE EVENTS TITLE'));		

		$this->showSubmenu();   
		
		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {
			$feedLink = CRoute::_('index.php?option=com_community&view=events&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE ALL EVENTS FEED') . '" href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */
		
		// loading neccessary files here.
		CFactory::load( 'libraries' , 'filterbar' );
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'helpers' , 'event' );
		CFactory::load( 'models' , 'events');
		//$event		= JTable::getInstance( 'Event' , 'CTable' ); 

 		$data		= new stdClass();
		$sorted		= JRequest::getVar( 'sort' , 'startdate' , 'GET' );
		
		// Get category id from the query string if there are any.
		$categoryId		= JRequest::getInt( 'categoryid' , 0 );
		$category		=& JTable::getInstance( 'EventCategory' , 'CTable' );
		$category->load( $categoryId );
		
		$model		=& CFactory::getModel( 'events' );
		
 		// Get the categories
 		$data->categories	= $model->getCategories();
 		
 		// It is safe to pass 0 as the category id as the model itself checks for this value.
 		$data->events			= $model->getEvents( $category->id , null, $sorted );
 		                                          
		// Get pagination object
		$data->pagination	= $model->getPagination();
		

		// Get the template for the event category lists
		$eventsHTML	= $this->_getEventsHTML( $data->events );

		$tmpl		= new CTemplate();

		$sortItems =  array(
				'latest' 		=> JText::_('CC SORT EVENT CREATED') ,
				'startdate'	=> JText::_('CC SORT EVENT STARTDATE'));

        $tmpl->set( 'index'				, true );
		$tmpl->set( 'categories' 		, $data->categories );
		$tmpl->set( 'eventsHTML'		, $eventsHTML );
		$tmpl->set( 'pagination' 		, $data->pagination );
		$tmpl->set( 'config'			, $config );
		$tmpl->set( 'category' 			, $category );
		$tmpl->set( 'isCommunityAdmin'	, COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'sortings'			, CFilterBar::getHTML( CRoute::getURI(), $sortItems, 'startdate') );
		$tmpl->set( 'my' 				, $my );
		
		echo $tmpl->fetch( 'events.index' );
		
	}
	
	function announce($event)
	{
		echo "send announcement";
	}

	/**
	 * Display invite form
	 **/
	function invitefriends()
	{
		$document	=& JFactory::getDocument();
		$document->setTitle( JText::_('CC INVITE FRIENDS TO EVENT TITLE') );

		if( !$this->accessAllowed( 'registered' ) )
		{
			return;
		}

		$this->showSubmenu();

		$my				= CFactory::getUser();
		$eventId		= JRequest::getVar( 'eventid' , '' , 'GET' );
		$this->_addEventInPathway( $eventId );
		$this->addPathway( JText::_('CC INVITE FRIENDS TO EVENT TITLE') );

		$friendsModel	=& CFactory::getModel( 'Friends' );
		$model	        =& CFactory::getModel( 'Events' );
        $event          =& JTable::getInstance('Event' , 'CTable');
        $event->load($eventId);

		$tmpFriends		= $friendsModel->getFriends( $my->id , 'name' , false);

		$friends		= array();

		for( $i = 0; $i < count( $tmpFriends ); $i++ )
		{
			$friend			=& $tmpFriends[ $i ];
			$eventMember	=& JTable::getInstance( 'EventMembers' , 'CTable' );
			$eventMember->load( $eventId , $friend->id );


			if( !$event->isMember( $friend->id ) && !$eventMember->exists())
			{
				$friends[]	= $friend;
			}
		}
		unset( $tmpFriends );

		$tmpl   = new CTemplate();
		$tmpl->set( 'friends' , $friends );
		$tmpl->set( 'event' , $event );
		echo $tmpl->fetch( 'events.invitefriends' );
	}
	
	function expiredevents()
	{
		if(!$this->accessAllowed('registered'))	return;

	    $mainframe	=&	JFactory::getApplication();
	    $document 	=&	JFactory::getDocument();
	    $config		=&	CFactory::getConfig();
		$my			=	CFactory::getUser();

		$this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
		$this->addPathway( JText::_('CC PAST EVENTS TITLE') , '' );

        $document->setTitle(JText::_('CC PAST EVENTS TITLE'));

		$this->showSubmenu();

		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {
			$feedLink = CRoute::_('index.php?option=com_community&view=events&task=expiredevents&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE EXPIRED EVENTS FEED') . '"  href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */

		// loading neccessary files here.
		CFactory::load( 'libraries' , 'filterbar' );
		CFactory::load( 'helpers' , 'event' );
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'events');
		//$event		= JTable::getInstance( 'Event' , 'CTable' );

 		$data		= new stdClass();
		$sorted		= JRequest::getVar( 'sort' , 'latest' , 'GET' );
		$model		=& CFactory::getModel( 'events' );

 		// It is safe to pass 0 as the category id as the model itself checks for this value.
 		$data->events			= $model->getEvents( null, $my->id , $sorted, null, false, true );

		// Get pagination object
		$data->pagination	= $model->getPagination();

		// Get the template for the group lists
		$eventsHTML	= $this->_getEventsHTML( $data->events, true );

		$tmpl		= new CTemplate();

		$sortItems =  array(
				'latest' 		=> JText::_('CC SORT EVENT CREATED') ,
				'startdate'	=> JText::_('CC SORT EVENT START DATE'));

		$tmpl->set( 'eventsHTML'		, $eventsHTML );
		$tmpl->set( 'pagination' 		, $data->pagination );
		$tmpl->set( 'config'			, $config );
		$tmpl->set( 'isCommunityAdmin'	, COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'sortings'			, CFilterBar::getHTML( CRoute::getURI(), $sortItems, 'startdate') );
		$tmpl->set( 'my' 				, $my );

		echo $tmpl->fetch( 'events.expiredevents' );
	}

	function myevents()
	{
		if(!$this->accessAllowed('registered'))	return;

	    $mainframe	=& JFactory::getApplication();
	    $document 	=& JFactory::getDocument();
        $userid     = JRequest::getInt('userid', null );
        $user		= CFactory::getUser($userid);
        $my			= CFactory::getUser();
		
		$title	= ($my->id == $user->id) ? JText::_('CC MY EVENTS TITLE') : JText::sprintf('CC USERS EVENTS TITLE', $user->getDisplayName());
		$document->setTitle($title);
		
		if($my->id != $user->id) $this->attachMiniHeaderUser($user->id);
		
		$this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
		$this->addPathway( JText::_('CC MY EVENTS TITLE') , '' );
		
		$this->showSubmenu();
		
		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {
			$feedLink = CRoute::_('index.php?option=com_community&view=events&userid=' . $user->id . '&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE MY EVENTS FEED') . '" href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */
		
		// loading neccessary files here.
		CFactory::load( 'libraries' , 'filterbar' );
		CFactory::load( 'helpers' , 'event' );
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'events');

 		$data		= new stdClass();
		$sorted		= JRequest::getVar( 'sort' , 'startdate' , 'GET' );
		$model		=& CFactory::getModel( 'events' );

 		// It is safe to pass 0 as the category id as the model itself checks for this value.
 		$data->events		= $model->getEvents( null, $user->id , $sorted );

		// Get pagination object
		$data->pagination	= $model->getPagination();

		// Get the template for the group lists
		$eventsHTML	= $this->_getEventsHTML( $data->events );

		$sortItems	= array(
				'latest' 		=> JText::_('CC SORT EVENT CREATED') ,
				'startdate'		=> JText::_('CC SORT EVENT STARTDATE')
		);

		$tmpl		= new CTemplate();
		$tmpl->set( 'eventsHTML'	, $eventsHTML );
		$tmpl->set( 'pagination'	, $data->pagination );
		$tmpl->set( 'sortings'		, CFilterBar::getHTML( CRoute::getURI(), $sortItems, 'startdate') );

		echo $tmpl->fetch( 'events.myevents' );
	}

	function myinvites()
	{
		if(!$this->accessAllowed('registered'))	return;

	    $mainframe	=&	JFactory::getApplication();
	    $document 	=&	JFactory::getDocument();
	    $config		=&	CFactory::getConfig();
		$my			=	CFactory::getUser();
        $userid     =	JRequest::getCmd('userid', null );

		$this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
		$this->addPathway( JText::_('CC EVENT PENDING INVITATIONS') , '' );

        $document->setTitle(JText::_('CC EVENT PENDING INVITATIONS'));

		$this->showSubmenu();

		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {
			$feedLink = CRoute::_('index.php?option=com_community&view=events&userid=' . $userid . '&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE TO PENDING INVITATIONS FEED') . '"  href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */


		CFactory::load( 'libraries' , 'filterbar' );
		CFactory::load( 'helpers' , 'event' );
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'events');

		$sorted		= JRequest::getVar( 'sort' , 'startdate' , 'GET' );
		$model		= CFactory::getModel( 'events' );
		$pending	= COMMUNITY_EVENT_STATUS_INVITED;

		// It is safe to pass 0 as the category id as the model itself checks for this value.
 		$rows		= $model->getEvents( null, $my->id , $sorted, null, true, false, $pending );
		$pagination	= $model->getPagination();
		$count		= count( $rows );
		$tmpl		= new CTemplate();
		$sortItems	= array( 'latest' 		=> JText::_('CC SORT EVENT CREATED') ,
							 'startdate'		=> JText::_('CC SORT EVENT STARTDATE')
							);
		$events		= array();
		
		if( $rows )
		{
			foreach( $rows as $row )
			{
				$event	=& JTable::getInstance( 'Event' , 'CTable' );
				$event->load( $row->id );
				$events[]	= $event;
			}
			unset($eventObjs);
		}
		
		$tmpl	= new CTemplate();
		$tmpl->set( 'events'			, $events );
		$tmpl->set( 'pagination' 		, $pagination );
		$tmpl->set( 'config'			, $config );
		$tmpl->set( 'isCommunityAdmin'	, COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'sortings'			, CFilterBar::getHTML( CRoute::getURI(), $sortItems, 'startdate') );
		$tmpl->set( 'my' 				, $my );
		$tmpl->set( 'count' 			, $count );

		echo $tmpl->fetch( 'events.myinvites' );
	}
	
	function _displayForm($event)
	{
		$mainframe	= JFactory::getApplication();
		$my			= CFactory::getUser();
		$config		= CFactory::getConfig();
		$model		= CFactory::getModel( 'events' );
		$categories	= $model->getCategories();		
		$now 		= JFactory::getDate();
		$now->setOffset( $mainframe->getCfg('offset'));
		
		jimport( 'joomla.html.editor' );
		$editorType = ($config->get('allowhtml') )? $config->get('htmleditor' , 'none') : 'none' ;
		$editor		= new JEditor( $editorType );
		
		$totalEventCount    = $model->getEventsCreationCount( $my->id );
		
		$event->startdatetime		= JRequest::getVar('startdatetime', '00:01', 'POST');
		$event->enddatetime			= JRequest::getVar('enddatetime', '23:59', 'POST');

        $tmpl		= new CTemplate();
		$tmpl->set('config'				, $config );
		$tmpl->set('categories' 		, $categories );
		$tmpl->set('event'				, $event );
		$tmpl->set('editor'				, $editor);
		$tmpl->set('now'				, $now->toFormat('%Y-%m-%d') );
		$tmpl->set('eventCreated'		, $totalEventCount );
		$tmpl->set('eventcreatelimit'	, $config->get('eventcreatelimit') );

		echo $tmpl->fetch( 'events.forms' );
	}
	
	function create($event)
	{
		if(!$this->accessAllowed('registered'))
		{
			return;
		}

	    $document 	= JFactory::getDocument();
	    $config		= CFactory::getConfig();
		$mainframe	= JFactory::getApplication();
		CFactory::load( 'helpers' , 'owner' );
		
		if( !$config->get('createevents') && !COwnerHelper::isCommunityAdmin() )
		{
			$document->setTitle( '' );
			$mainframe->enqueueMessage( JText::_('CC EVENT CREATION DISABLED'), 'error');
			return;
		}
		
		$this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
		$this->addPathway( JText::_('CC CREATE EVENTS TITLE') , '' );
        $document->setTitle(JText::_('CC CREATE EVENTS TITLE'));
		
        $uri	= JURI::root();
        $js		= $uri . 'components/com_community/assets/validate-1.5';        		
		$js		.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';        
        
        $document->addScript($js);
				
		$this->showSubmenu();
		$this->_displayForm($event);
		return;		
	}
	
	function edit($event)
	{
		if(!$this->accessAllowed('registered'))	return;

	    $document 	= JFactory::getDocument();
	    $config		= CFactory::getConfig();

		$this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
		$this->addPathway( JText::_('CC EDIT EVENTS TITLE') , '' );
        $document->setTitle(JText::_('CC EDIT EVENTS TITLE'));

        $uri	= JURI::root();
        $js		= $uri . 'components/com_community/assets/validate-1.5';
		$js		.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';

        $document->addScript($js);
		$this->showSubmenu();
		
		$this->_displayForm($event);
		return;
	}
	
	public function printpopup($event)
	{
		$config = CFactory::getConfig();
		$my 	= CFactory::getUser();
		// We need to attach the javascirpt manually
		
		$js = JURI::root().'components/com_community/assets/joms.jquery.js';
		$script  = '<script type="text/javascript" src="'.$js.'"></script>';
		
		$js	= JURI::root().'components/com_community/assets/script-1.2';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';
		
		$script .= '<script type="text/javascript" src="'.$js.'"></script>'; 
		
		$creator = CFactory::getUser($event->creator);
		$creatorUtcOffset = $creator->getUtcOffset();
		$creatorUtcOffsetStr = CTimeHelper::getTimezone($creatorUtcOffset);

		// Output to template
        $tmpl		= new CTemplate();
        
        $tmpl->set( 'my'		, $my);
        $tmpl->set( 'event' 	, $event );
        $tmpl->set( 'script'	, $script);
        $tmpl->set( 'creatorUtcOffset' 		, $creatorUtcOffset );
        $tmpl->set( 'creatorUtcOffsetStr'	, $creatorUtcOffsetStr);

        echo $tmpl->fetch( 'events.print' );
	}
	
	function viewevent()
	{
	    $mainframe	= JFactory::getApplication();
	    $document 	= JFactory::getDocument();
	    $config		= CFactory::getConfig();
		$my			= CFactory::getUser();

		require_once (JPATH_COMPONENT.DS.'libraries'.DS.'tooltip.php');
		require_once( JPATH_COMPONENT . DS .'libraries' . DS . 'wall.php' );

		// Load window library
		CFactory::load( 'libraries' , 'window' );

		// Load necessary window css / javascript headers.
		CWindow::load();
        
        $this->showSubmenu();
        
        // Load the event from database
        $eventid	=	JRequest::getInt( 'eventid' , 0 );
        $eventModel	=&	CFactory::getModel( 'events' );
		$event		=&	JTable::getInstance( 'Event' , 'CTable' );
		
		if(!$event->load($eventid)){
			// event doesn't exit;
			$document->setTitle( '' );
			$mainframe->enqueueMessage( JText::_('CC EVENTS NOT AVAILABLE'), 'error');
			return;
		}
		$event->hit();
		
		// Basic page presentation
		$this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
		$this->addPathway( JText::sprintf('CC VIEW EVENTS TITLE', $event->title) , '' );
		$document->setTitle( $event->title );
		
		// Permissions and privacies
		CFactory::load('helpers' , 'owner');
		$isEventGuest	= $event->isMember( $my->id );
		
		$isMine			= ($my->id == $event->creator);
		$isAdmin		= $event->isAdmin( $my->id );
		$isCommunityAdmin	= COwnerHelper::isCommunityAdmin();
		
		// Get Event Admins
		$eventAdmins		=	$event->getAdmins( 12 , CC_RANDOMIZE );
		$eventAdminsCount	= 	$event->getAdminsCount();
		
		// Attach avatar of the admin
		for( $i = 0; ($i < count($eventAdmins)); $i++)
		{
			$row				=&	$eventAdmins[$i];
			$eventAdmins[$i]	=	CFactory::getUser( $row->id );
		}
		
		// Get Attending Event Guests
		$eventMembers			= $event->getMembers( COMMUNITY_EVENT_STATUS_ATTEND, 12 , CC_RANDOMIZE );
		$eventMembersCount		= $event->getMembersCount( COMMUNITY_EVENT_STATUS_ATTEND );

        // Get pending event guests
 		$pendingMembers	        = $event->getMembers( COMMUNITY_EVENT_STATUS_INVITED, 12 , CC_RANDOMIZE );
 		$pendingMembersCount	= $event->getMembersCount( COMMUNITY_EVENT_STATUS_INVITED );

		// Get blocked Event Guests
 		$blockedMembers	        = $event->getMembers( COMMUNITY_EVENT_STATUS_BLOCKED, 12 , CC_RANDOMIZE );
 		$blockedMembersCount	= $event->getMembersCount( COMMUNITY_EVENT_STATUS_BLOCKED );

		// Attach avatar of the admin
		for( $i = 0; ($i < count($eventMembers)); $i++)
		{
			$row	=& $eventMembers[$i];
			$eventMembers[$i]	= CFactory::getUser( $row->id );
		}

		for( $i = 0; ($i < count($pendingMembers)); $i++)
		{
			$row	=& $pendingMembers[$i];
			$pendingMembers[$i]	= CFactory::getUser( $row->id );
		}

		for( $i = 0; ($i < count($blockedMembers)); $i++)
		{
			$row	=& $blockedMembers[$i];
			$blockedMembers[$i]	= CFactory::getUser( $row->id );
		}

		$waitingApproval	    = $event->isPendingApproval( $my->id );
        $waitingRespond	        = false;

		$myStatus = $event->getUserStatus($my->id);
		
		$hasResponded = 	(($myStatus == COMMUNITY_EVENT_STATUS_ATTEND)
			|| ($myStatus == COMMUNITY_EVENT_STATUS_WONTATTEND)
			|| ($myStatus == COMMUNITY_EVENT_STATUS_MAYBE));
			
		// Get Bookmark HTML
		CFactory::load('libraries' , 'bookmarks');
		$bookmarks		= new CBookmarks(CRoute::getExternalURL( 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id ));
		$bookmarksHTML	= $bookmarks->getHTML();
		
		// Get Reporting HTML
		CFactory::load('libraries', 'reporting');
		$report		= new CReportingLibrary();
		$reportHTML	= $report->getReportingHTML( JText::_('CC REPORT EVENT') , 'events,reportEvent' , array( $event->id ) );
		
		// Get the Wall
		CFactory::load( 'libraries' , 'wall' );
		$wallContent	= CWallLibrary::getWallContents( 'events' , $event->id , $isAdmin , 10 ,0 , 'wall.content' , 'events,events');
		$wallCount		= CWallLibrary::getWallCount('events', $event->id);
		$viewAllLink = false;
		if(JRequest::getVar('task', '', 'REQUEST') != 'app')
		{
			$viewAllLink	= CRoute::_('index.php?option=com_community&view=events&task=app&eventid=' . $event->id . '&app=walls');
		}
		$wallContent	.= CWallLibrary::getViewAllLinkHTML($viewAllLink, $wallCount);
		
		$wallForm		= '';
		if( !$config->get('lockeventwalls') 
			|| ($config->get('lockeventwalls') && ($isEventGuest) && !($waitingApproval) && $hasResponded) 
			|| $isCommunityAdmin )
		{
			$wallForm	= CWallLibrary::getWallInputForm( $event->id , 'events,ajaxSaveWall', 'events,ajaxRemoveWall' );
		}
		
		// Construct the RVSP radio list
		$arr = array(
			JHTML::_('select.option',  COMMUNITY_EVENT_STATUS_ATTEND, JText::_( 'CC EVENT ACTION ATTEND' ) ),
			JHTML::_('select.option',  COMMUNITY_EVENT_STATUS_WONTATTEND, JText::_( 'CC EVENT ACTION DECLINE' ) ),
			JHTML::_('select.option',  COMMUNITY_EVENT_STATUS_MAYBE, JText::_( 'CC EVENT ACTION UNSURE' ) )
		);
		$status		= $event->getMemberStatus($my->id);
		$radioList	= JHTML::_('select.radiolist',  $arr, 'status', '', 'value', 'text', $status, false );
		
		$unapprovedCount = $event->inviteRequestCount();
		//...
		$editEvent		= JRequest::getVar( 'edit' , false , 'GET' );
		$editEvent		= ( $editEvent == 1 ) ? true : false;
		
		$exportUrl = CRoute::_('index.php?option=com_community&view=events&task=export&format=raw&eventid=' . $event->id);
		
        // Output to template
        $tmpl		= new CTemplate();
        
        $tmpl->set( 'guestStatus'			, $event->getUserStatus($my->id));
        $tmpl->set( 'event'					, $event );
        $tmpl->set( 'radioList'				, $radioList );
        $tmpl->set( 'bookmarksHTML'			, $bookmarksHTML );
        $tmpl->set( 'reportHTML'			, $reportHTML );
        $tmpl->set( 'isEventGuest'			, $isEventGuest );
        $tmpl->set( 'isMine'				, $isMine );
        $tmpl->set( 'isAdmin'				, $isAdmin );
        $tmpl->set( 'isCommunityAdmin'		, $isCommunityAdmin );
        $tmpl->set( 'unapproved'			, $unapprovedCount );
        $tmpl->set( 'waitingApproval'		, $waitingApproval );
        $tmpl->set( 'wallForm'				, $wallForm );
		$tmpl->set( 'wallContent'			, $wallContent );
		$tmpl->set( 'eventAdmins'			, $eventAdmins );
		$tmpl->set( 'eventAdminsCount'		, $eventAdminsCount );
		$tmpl->set( 'eventMembers'			, $eventMembers );
		$tmpl->set( 'eventMembersCount'		, $eventMembersCount );
		$tmpl->set( 'blockedMembers'		, $blockedMembers );
		$tmpl->set( 'blockedMembersCount'	, $blockedMembersCount);
		$tmpl->set( 'pendingMembers'        , $pendingMembers );
		$tmpl->set( 'pendingMembersCount'   , $pendingMembersCount );
		$tmpl->set( 'editEvent'				, $editEvent );
        $tmpl->set( 'my'					, $my );
        $tmpl->set( 'memberStatus'			, $myStatus );
        $tmpl->set( 'waitingRespond'		, $waitingRespond );
        $tmpl->set( 'exportUrl'				, $exportUrl );

        echo $tmpl->fetch( 'events.viewevent' );
	}
	
	function viewguest()
	{
		if(!$this->accessAllowed('registered'))	return;
		
		$mainframe	= JFactory::getApplication();
	    $document 	= JFactory::getDocument();
	    $config		= CFactory::getConfig();
		$my			= CFactory::getUser();

		// First we need the event id and make sure it's a valid event
		$eventid	= JRequest::getInt( 'eventid' , 0 );
        $eventModel	= CFactory::getModel( 'events' );
		$event		= JTable::getInstance( 'Event' , 'CTable' );
		$event->load($eventid);

		$statusType	= JRequest::getCmd('type');
		$approval	= JRequest::getCmd('approve');
		
		$types		= array( COMMUNITY_EVENT_ADMINISTRATOR , COMMUNITY_EVENT_STATUS_INVITED , COMMUNITY_EVENT_STATUS_ATTEND , COMMUNITY_EVENT_STATUS_BLOCKED , COMMUNITY_EVENT_STATUS_REQUESTINVITE );

		// This should not be processed at all as someone is trying to inject or mess up the system
		if( !in_array( $statusType , $types ) )
		{
			JError::raiseError( '500' , JText::_( 'Invalid status type' ) );
		}
		
		// Set the guest type for the title purpose
		switch ($statusType)
		{
			case COMMUNITY_EVENT_ADMINISTRATOR			:	$guestType = JText::_('CC ADMINS');				break;
			case COMMUNITY_EVENT_STATUS_INVITED			:	$guestType = JText::_('CC PENDING GUESTS');		break;
			case COMMUNITY_EVENT_STATUS_ATTEND			:	$guestType = JText::_('CC CONFIRMED GUESTS');				break;
			case COMMUNITY_EVENT_STATUS_BLOCKED		:	$guestType = JText::_('CC BLOCKED GUESTS');				break;
			case COMMUNITY_EVENT_STATUS_REQUESTINVITE	:	$guestType = JText::_('CC REQUESTED INVITATION');	break;
		}
		
		// Then we load basic page presentation
		$this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
		$this->addPathway( JText::sprintf('CC VIEW EVENTS TITLE', $event->title) , '' );

		// Set the specific title
        $document->setTitle(JText::sprintf("CC EVENT VIEW GUESTLIST" , $event->title, $guestType ));
        

		CFactory::load( 'helpers' , 'owner' );
		$status			= $event->getUserStatus($my->id);
		$allowed		= array( COMMUNITY_EVENT_STATUS_INVITED , COMMUNITY_EVENT_STATUS_ATTEND , COMMUNITY_EVENT_STATUS_WONTATTEND , COMMUNITY_EVENT_STATUS_MAYBE );
		$accessAllowed	= ( ( in_array( $status , $allowed ) ) && $status != COMMUNITY_EVENT_STATUS_BLOCKED ) ? true : false;

		if( ( $accessAllowed && $event->allowinvite ) || $event->isAdmin( $my->id ) || COwnerHelper::isCommunityAdmin() )
		{
			$this->addSubmenuItem('index.php?option=com_community&view=events&task=invitefriends&eventid=' . $eventid, JText::_('CC TAB INVITE') , '' , SUBMENU_RIGHT );        
		}
		$this->showSubmenu();
		
        CFactory::load('helpers' , 'owner' );
		$isSuperAdmin	= COwnerHelper::isCommunityAdmin();    
        
        // status = unsure | noreply | accepted | declined | blocked
        // permission = admin | guest |  

        if ($statusType == COMMUNITY_EVENT_ADMINISTRATOR)
        {
        	$guestsIds		= $event->getAdmins();
		}
		else
		{
			$guestsIds		= $event->getMembers($statusType, 0, false, $approval);
		}
        
        $guests         = array();
        CFactory::load('helpers', 'event');
		
        for ($i=0; $i < count($guestsIds); $i++)
        {
			$guests[$i]	= CFactory::getUser($guestsIds[$i]->id);
			$guests[$i]->friendsCount	= $guests[$i]->getFriendCount();
			$guests[$i]->isMe			= ( $my->id == $guests[$i]->id ) ? true : false;
			$guests[$i]->isAdmin		= $event->isAdmin($guests[$i]->id);
			$guests[$i]->statusType		= $guestsIds[$i]->statusCode;
		}
		
		$pagination		= $eventModel->getPagination();

        // Output to template
        $tmpl		= new CTemplate();
        $tmpl->set( 'event'		, $event);
        $tmpl->set( 'guests' 	, $guests );
        $tmpl->set( 'eventid'	, $event->id );
        $tmpl->set( 'isMine'	, $event->isCreator($my->id));
        $tmpl->set( 'isSuperAdmin', $isSuperAdmin );
        $tmpl->set( 'pagination', $pagination );
        $tmpl->set( 'my'		, $my );
        $tmpl->set( 'config'	, $config );
        echo $tmpl->fetch( 'events.viewguest' );
	}
	
	function search()
	{
		if(!$this->accessAllowed('registered'))	return;
		
		CFactory::load( 'helpers' , 'event' );
		
		// Get the document object and set the necessary properties of the document
		$document	=& JFactory::getDocument();
        $this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
		$this->addPathway( JText::_('CC SEARCH EVENTS') , '' );
        $document->setTitle(JText::_('CC SEARCH EVENTS TITLE'));
		
		// Display the submenu
		$this->showSubmenu();
		
		// input filtered to remove tags
		$search		= JRequest::getVar( 'search' , '' , 'post', 'string' );
		
		$events		= '';
		$posted		= false;
		$count		= 0;
		$eventsHTML	= '';

		// Test if there are any post requests made
		if( JRequest::getMethod() == 'POST' && !empty( $search ) )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );

			CFactory::load( 'libraries' , 'apps' );
			$appsLib		=& CAppPlugins::getInstance();
			$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array('jsform-events-search' ));
			
			if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
			{
				$posted			= true;
				$model			=& CFactory::getModel( 'events' );
	
				$events	= $model->getEvents( null, null , null , $search );
				$count	= count( $events );
			}
		}
		
		// Get the template for the events lists
		$eventsHTML	= $this->_getEventsHTML( $events );

		CFactory::load( 'libraries' , 'apps' );
		$app 		=& CAppPlugins::getInstance();
		$appFields	= $app->triggerEvent('onFormDisplay' , array( 'jsform-events-search') );
		$beforeFormDisplay	= CFormElement::renderElements( $appFields , 'before' );
		$afterFormDisplay	= CFormElement::renderElements( $appFields , 'after' );

		$tmpl	= new CTemplate();
		$tmpl->set( 'beforeFormDisplay', $beforeFormDisplay );
		$tmpl->set( 'afterFormDisplay'	, $afterFormDisplay );
		$tmpl->set( 'posted'		, $posted );
		$tmpl->set( 'eventsCount'	, $count );
		$tmpl->set( 'eventsHTML'	, $eventsHTML );
		$tmpl->set( 'search'		, $search );

		echo $tmpl->fetch( 'events.search' );
	}
	
	/**
	 * An event has just been created, should we just show the album ?
	 */
	function created()
	{

		$eventid 	=  JRequest::getInt( 'eventid', 0 );

		CFactory::load( 'models' , 'events');
		$event		= JTable::getInstance( 'Event' , 'CTable' );

		$event->load($eventid);
		$document = JFactory::getDocument();
        $document->setTitle( $event->title );

        $uri	= JURI::base();
		$this->showSubmenu();

		$tmpl	= new CTemplate();

		$tmpl->set( 'link'			, CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid='.$event->id));
		$tmpl->set( 'linkUpload'	, CRoute::_('index.php?option=com_community&view=events&task=uploadavatar&eventid='.$event->id));
		$tmpl->set( 'linkEdit'		, CRoute::_('index.php?option=com_community&view=events&task=edit&eventid=' . $event->id ) );
		$tmpl->set( 'linkInvite'	, CRoute::_('index.php?option=com_community&view=events&task=invitefriends&eventid=' . $event->id ) );

		echo $tmpl->fetch( 'events.created' );
	}
	
	function sendmail()
	{
		$document =& JFactory::getDocument();
        $document->setTitle(JText::_('CC SEND EMAIL TO PARTICIPANTS'));
        $this->addPathway( JText::_('CC EVENTS') , CRoute::_('index.php?option=com_community&view=events') );
		$this->addPathway( JText::_('CC SEND EMAIL TO PARTICIPANTS') );
		
        if(!$this->accessAllowed('registered'))	return;
		
		// Display the submenu
		$this->showSubmenu();
        $eventId	= JRequest::getInt('eventid' , '' );
		
		CFactory::load( 'helpers', 'owner' );		
		CFactory::load( 'models' , 'events' );
		$event		=& JTable::getInstance( 'Event' , 'CTable' );
		$event->load( $eventId );

		if( empty($eventId ) || empty( $event->title) )
		{
			echo JText::_('CC INVALID ID PROVIDED');
			return;
		}
		
		$my			=& CFactory::getUser();
		$config		=& CFactory::getConfig();

		jimport( 'joomla.html.editor' );
		$editor		= new JEditor( $config->get( 'htmleditor' ) );
		
		if( !$event->isAdmin($my->id) && !COwnerHelper::isCommunityAdmin() )
		{
			$this->noAccess();
			return;
		}

		$message	= JRequest::getVar( 'message' , '' , 'post' , 'string' , JREQUEST_ALLOWRAW );
		$title		= JRequest::getVar( 'title'	, '' );
		
        $tmpl		= new CTemplate();
        $tmpl->set( 'editor'	, $editor );
        $tmpl->set( 'event' , $event );
		$tmpl->set( 'message' , $message );
		$tmpl->set( 'title' , $title );
        echo $tmpl->fetch( 'events.sendmail' );
	}
	
	function uploadAvatar()
	{
		$document =& JFactory::getDocument();
        $document->setTitle(JText::_('CC UPLOAD EVENT AVATAR'));
        
		$eventid    = JRequest::getVar('eventid', '0');
		$this->_addEventInPathway( $eventid );
		$this->addPathway( JText::_('CC UPLOAD EVENT AVATAR') );

		$this->showSubmenu();

		$config			= CFactory::getConfig();
		$uploadLimit	= (double) $config->get('maxuploadsize');
		$uploadLimit	.= 'MB';

		CFactory::load( 'models' , 'events' );
		$event	=& JTable::getInstance( 'Event' , 'CTable' );
		$event->load( $eventid );

		CFactory::load( 'libraries' , 'apps' );
		$app 		=& CAppPlugins::getInstance();
		$appFields	= $app->triggerEvent('onFormDisplay' , array( 'jsform-events-uploadavatar') );
		$beforeFormDisplay	= CFormElement::renderElements( $appFields , 'before' );
		$afterFormDisplay	= CFormElement::renderElements( $appFields , 'after' );

		$tmpl	= new CTemplate();
		$tmpl->set( 'beforeFormDisplay', $beforeFormDisplay );
		$tmpl->set( 'afterFormDisplay'	, $afterFormDisplay );
		$tmpl->set( 'eventId' 	, $eventid );
		$tmpl->set( 'avatar'	, $event->getAvatar('avatar') );
		$tmpl->set( 'thumbnail' , $event->getAvatar() );
		$tmpl->set( 'uploadLimit'	, $uploadLimit );

		echo $tmpl->fetch( 'events.uploadavatar' );
	}
	
	function _addEventInPathway( $eventId )
	{
		CFactory::load( 'models' , 'events' );
		$event			=& JTable::getInstance( 'Event' , 'CTable' );
		$event->load( $eventId );

		$this->addPathway( $event->title , CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id) );
	}
	
	function _getEventsHTML( $eventObjs, $isExpired = false )
	{
		CFactory::load( 'helpers' , 'owner' );

		$events	= array();
		CFactory::load( 'models' , 'events' );

		if( $eventObjs )
		{
			foreach( $eventObjs as $row )
			{
				$event	=& JTable::getInstance( 'Event' , 'CTable' );
				$event->load( $row->id );
				$events[]	= $event;
			}
			unset($eventObjs);
		}

		$tmpl	= new CTemplate();
		$tmpl->set( 'isCommunityAdmin'	, COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'events'			, $events );
		$tmpl->set( 'isExpired'			, $isExpired );
		$eventsHTML	= $tmpl->fetch( 'events.list' );
		unset( $tmpl );

		return $eventsHTML;
	}
}
