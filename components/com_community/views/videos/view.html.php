<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CommunityViewVideos extends CommunityView
{
	var $_videoLib	= null;
	var $model		= '';
	
	function CommunityViewVideos()
	{
		CFactory::load( 'helpers', 'videos' );
		CFactory::load( 'libraries' , 'videos' );
		$this->model	= CFactory::getModel('videos');
		$this->videoLib	= new CVideoLibrary();
	}

	function _getVideosHTML( $videos )
	{
		$videos	= $videos ? CVideosHelper::prepareVideos($videos) : array();
		$my		= CFactory::getUser();
		$user	= CFactory::getUser(JRequest::getInt('userid'));

		// for featured/unfeatured link
		CFactory::load( 'helpers', 'owner' );
		
		CFactory::load( 'libraries' , 'featured' );
		$featured	= new CFeatured( FEATURED_VIDEOS );
		$featuredVideos	= $featured->getItemIds();
		$featuredList	= array();
		
		foreach($featuredVideos as $videoId )
		{
			$featuredList[]	= $videoId;
		}
		
		$allowManageVideos 	= true;
		$groupVideo 		= false;
		$groupId			= JRequest::getVar('groupid' , '' , 'GET');		
		
		$task			= JRequest::getVar( 'task' , '' , 'GET' );
		$redirectUrl	= CRoute::getURI( false );

		if( !empty($groupId) )
		{
			CFactory::load( 'helpers' , 'group' );			
			$allowManageVideos	= CGroupHelper::allowManageVideo($groupId);
			$groupVideo			= true;
		}

		CFactory::load( 'libraries', 'videos' );
		CFactory::load( 'helpers', 'string' );
		
		$tmpl	= new CTemplate();
		$tmpl->set( 'sort'				, JRequest::getVar('sort', 'latest') );
		$tmpl->set( 'currentTask'		, JRequest::getCmd( 'task' , '') );
		$tmpl->set( 'videos'			, $videos );
		$tmpl->set( 'videoThumbWidth'	, CVideoLibrary::thumbSize('width') );
		$tmpl->set( 'videoThumbHeight'	, CVideoLibrary::thumbSize('height') );
		$tmpl->set( 'redirectUrl'		, $redirectUrl );
		$tmpl->set( 'my'				, $my );
		$tmpl->set( 'user'				, $user );
		$tmpl->set( 'featuredList'		, $featuredList );
		$tmpl->set( 'isCommunityAdmin' 	, COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'allowManageVideos' , $allowManageVideos );
		$tmpl->set( 'groupVideo' 		, $groupVideo);
		
		return $tmpl->fetch( 'videos.list' );
	}

	/**
	 *	Get Featured Videos
	 *	
	 *	@return		array	Objects of random featured videos
	 *	@since		1.5
	 */
	function _getFeatVideos()
	{
		CFactory::load( 'libraries' , 'featured' );
		CFactory::load('helpers', 'videos');
		CFactory::load('helpers', 'privacy');
		
		$featured	= new CFeatured( FEATURED_VIDEOS );
		$featuredVideos	= $featured->getItemIds();
		$featuredList	= array();
		
		foreach($featuredVideos as $videoId )
		{
			$table			=& JTable::getInstance( 'Video' , 'CTable' );
			$table->load($videoId);
			$table->loadExtra();
			
			$featuredList[]	= $table;
		}
		
		//$featuredList	= $featuredList ? CVideosHelper::prepareVideos($featuredList) : array();
		
		return $featuredList;
	}

	/**
	 *	Generate Featured Videos HTML
	 *	
	 *	@param		array	Array of video objects
	 *	@return		string	HTML
	 *	@since		1.2
	 */
	function _getFeatHTML($videos)
	{
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'libraries', 'videos' );

		$tmpl	= new CTemplate();
		$tmpl->set( 'videos'		, $videos );
		$tmpl->set( 'isCommunityAdmin' , COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'videoThumbWidth'	, CVideoLibrary::thumbSize('width') );
		$tmpl->set( 'videoThumbHeight'	, CVideoLibrary::thumbSize('height') );
		
		return $tmpl->fetch( 'videos.featured' );
	}

	/**
	 * Display all videos in the whole system
	 **/
	function display($id= null)
	{
		// Load required filterbar library that will be used to display the filtering and sorting.
		CFactory::load( 'libraries' , 'filterbar' );
		
		$mainframe  = JFactory::getApplication();
		$document 	= JFactory::getDocument();
		$my			= CFactory::getUser();		
		$model		= CFactory::getModel('videos');


		$this->_addSubmenu();
		$this->showSubmenu(); 


		// Get category id from the query string if there are any.
		$categoryId		= JRequest::getInt( 'catid' , null );
		$category		= JTable::getInstance( 'VideosCategory' , 'CTable' );
		$category->load( $categoryId );


		// If we are browing by category, add additional breadcrumb and add
		// category name in the page title
		if($categoryId != 0) 
		{
			$this->addPathway( JText::_('CC BROWSE VIDEOS CATEGORIES') , CRoute::_('index.php?option=com_community&view=videos') );
			$this->addPathway( $category->name , '' );
			$document->setTitle(JText::_( 'CC BROWSE VIDEOS CATEGORIES' ) . ' : ' . JText::_( $category->name ) );
        }
		else
		{
			$this->addPathway( JText::_( 'CC VIDEOS' ) );
			$document->setTitle(JText::_( 'CC BROWSE ALL VIDEOS' ));
		}

		$groupId	= JRequest::getVar('groupid' , '' , 'GET');  
		
		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {     
		    $groupLink = !empty($groupId) ? '&groupid=' . $groupId : '';
			$feedLink  = CRoute::_('index.php?option=com_community&view=videos' . $groupLink . '&format=feed');
			$feed      = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE ALL VIDEOS FEED') . '" href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */

		// Featured Videos
		$featVideos		= '';
			
		if( !empty($groupId) )
		{
			$group		= JTable::getInstance( 'Group' , 'CTable' );
			$group->load( $groupId );
			CFactory::load( 'helpers' , 'owner' );
			$isMember	= $group->isMember( $my->id );
			$isMine		= ($my->id == $group->ownerid);
			if( !$isMember && !$isMine && !COwnerHelper::isCommunityAdmin() && $group->approvals == COMMUNITY_PRIVATE_GROUP )
			{
				$this->noAccess( JText::_('CC PRIVATE GROUP NOTICE') );
				return;
			}
			
			$videos			= $model->getGroupVideos($groupId, $category->id);
			$allVideosUrl 	= 'index.php?option=com_community&view=videos&groupid='.$groupId;
			$catVideoUrl	= 'index.php?option=com_community&view=videos&groupid='.$groupId.'&catid=';
			$categories		= $model->getCategories();
		}
		else
		{
			$filters	= array
			(
				'status'		=> 'ready',
				'category_id'	=> $category->id,
				'permissions'	=> ($my->id==0) ? 0 : 20,
				'or_group_privacy'	=> 0,
				'sorting'		=> JRequest::getVar('sort', 'latest')
			);
			$videos			= $model->getVideos($filters);
			$allVideosUrl 	= 'index.php?option=com_community&view=videos';
			$catVideoUrl	= 'index.php?option=com_community&view=videos&catid=';
		
			// Featured Videos
			$featVideos		= $this->_getFeatVideos();
			$categories	= $model->getCategories();
		}

		$videosHTML		= $this->_getVideosHTML( $videos );
		$featuredHTML	= '';
		if ( $featVideos && !COMMUNITY_FREE_VERSION )
		{
			$featuredHTML	= $this->_getFeatHTML( $featVideos );
		}
		
		
		$pagination	= $model->getPagination();

		$sortItems	= array
		(
			'latest' 		=> JText::_('CC VIDEO SORT LATEST'),
			'mostwalls'		=> JText::_('CC VIDEO SORT MOST WALL POST'),
			'mostviews'		=> JText::_('CC VIDEO SORT POPULAR'),
			'title'			=> JText::_('CC VIDEO SORT TITLE')
		);

		$tmpl	= new CTemplate();
		$tmpl->set( 'sort'			, JRequest::getVar('sort', 'latest') );
		$tmpl->set( 'currentTask'	, JRequest::getCmd( 'task' , '') );
		$tmpl->set( 'featuredHTML'	, $featuredHTML );
		$tmpl->set( 'videosHTML'	, $videosHTML );
		$tmpl->set( 'categories' 	, $categories );
		$tmpl->set( 'pagination' 	, $pagination );
		$tmpl->set( 'sortings'		, CFilterBar::getHTML( CRoute::getURI(), $sortItems, 'latest') );
		$tmpl->set( 'allVideosUrl'	, $allVideosUrl );
		$tmpl->set( 'catVideoUrl'	, $catVideoUrl );
		
		echo $tmpl->fetch( 'videos.index' );	
	}

	/**
	 * Application full view
	 **/
	function appFullView()
	{
		$document		=& JFactory::getDocument();
		$document->setTitle( JText::_('CC VIDEO WALL TITLE') );
		
		$applicationName	= JString::strtolower( JRequest::getVar( 'app' , '' , 'GET' ) );

		if(empty($applicationName))
		{
			JError::raiseError( 500, 'CC APP ID REQUIRED');
		}

		$output	= '';
		
		if( $applicationName == 'walls' )
		{
			CFactory::load( 'libraries' , 'wall' );
			$limit		= JRequest::getVar( 'limit' , 5 , 'REQUEST' );
			$limitstart = JRequest::getVar( 'limitstart', 0, 'REQUEST' );
			$videoId	= JRequest::getInt( 'videoid' , '' , 'GET' );
			$my			= CFactory::getUser();
			$config		=& CFactory::getConfig();
			
			$videoModel	=& CFactory::getModel( 'videos' );
			$video		=& JTable::getInstance( 'Video' , 'CTable' );
			$video->load( $videoId );
		
			CFactory::load( 'helpers' , 'owner' );
			CFactory::load( 'helpers' , 'friends' );
					
			if( !$config->get('lockvideoswalls') || ($config->get('lockvideoswalls') && CFriendsHelper::isConnected($my->id, $video->creator) ) || COwnerHelper::isCommunityAdmin() )
			{
				$output	.= CWallLibrary::getWallInputForm( $video->id , 'videos,ajaxSaveWall', 'videos,ajaxRemoveWall' );
			}

			// Get the walls content
			$viewAllLink = false;
			$wallCount	= false;
			if(JRequest::getVar('task', '', 'REQUEST') != 'app')
			{
				$viewAllLink	= CRoute::_('index.php?option=com_community&view=videos&task=app&videoid=' . $video->id . '&app=walls');
				$wallCount		= CWallLibrary::getWallCount('videos', $video->id);
			}
			$output 		.='<div id="wallContent">';
			$output			.= CWallLibrary::getWallContents( 'videos' , $video->id , ( COwnerHelper::isCommunityAdmin() || COwnerHelper::isMine($my->id, $video->creator) ) , $limit , $limitstart );
			$output			.= CWallLibrary::getViewAllLinkHTML($viewAllLink, $wallCount);
			$output 		.= '</div>';
			
			jimport('joomla.html.pagination');
			$wallModel 		=& CFactory::getModel('wall');
			$pagination		= new JPagination( $wallModel->getCount( $video->id , 'videos' ) , $limitstart , $limit );

			$output		.= '<div class="pagination-container">' . $pagination->getPagesLinks() . '</div>';
		}
		else
		{
			$model				=& CFactory::getModel('apps');
			$applications		=& CAppPlugins::getInstance();
			$applicationId		= $model->getUserApplicationId( $applicationName );
			
			$application		= $applications->get( $applicationName , $applicationId );
	
			// Get the parameters
			$manifest			= JPATH_PLUGINS . DS . 'community' . DS . $applicationName . DS . $applicationName . '.xml';
			
			$params			= new JParameter( $model->getUserAppParams( $applicationId ) , $manifest );
	
			$application->params	=& $params;
			$application->id		= $applicationId;
			
			$output	= $application->onAppDisplay( $params );
		}
		
		echo $output;
	}

	/**
	 * View to display the search form
	 **/
	function search()
	{
		$document	=& JFactory::getDocument();
		
		$this->addPathway( JText::_( 'CC VIDEOS' ) , CRoute::_('index.php?option=com_community&view=videos' ) );
		$this->addPathway( JText::_('CC SEARCH VIDEOS') , '' );
		$document->setTitle(JText::_( 'CC SEARCH VIDEOS' ));

		$this->_addSubmenu();
		$this->showSubmenu();

		$search		= JRequest::getVar( 'search-text' , '' , 'REQUEST' );
		$result		= array();
		$pagination	= null;
		$total		= 0;

		if( !empty( $search ) )
		{
			$searchModel	= CFactory::getModel( 'Search' );
			$result			= $searchModel->searchVideo( $search );
			$pagination		= $searchModel->getPagination();
			$total			= $searchModel->getTotal();
		}
		
		$pagination	= is_null($pagination) ? '' : $pagination->getPagesLinks();
		
		$videosHTML	= $this->_getVideosHTML($result);
		
		$tmpl		= new CTemplate();
		$tmpl->set( 'videosHTML'	, $videosHTML );
		$tmpl->set( 'pagination'	, $pagination );
		$tmpl->set( 'videosCount'	, $total );
		$tmpl->set( 'search'		, $search);
		
		echo $tmpl->fetch( 'videos.search' );
	}
		 
	function myvideos($id = null)
	{
		$document 	=& JFactory::getDocument();
		$my			= CFactory::getUser();
		$userid		= JRequest::getVar( 'userid' , '' );
		$user		= CFactory::getUser($userid);
		
		// Set document title
                CFactory::load('helpers' , 'owner' );
                $blocked	= $user->isBlocked();

		if( $blocked && !COwnerHelper::isCommunityAdmin() )
		{
			$tmpl	= new CTemplate();
			echo $tmpl->fetch('profile.blocked');
			return;
		}
		
		if($my->id == $user->id)
			$title	= JText::_('CC MY VIDEOS');
		else
			$title	= JText::sprintf('CC USERS VIDEO TITLE', $user->getDisplayName());

		$document->setTitle($title);

		// Set pathway
		$mainframe	=& JFactory::getApplication();
		$this->addPathway( JText::_( 'CC VIDEOS' ) , CRoute::_('index.php?option=com_community&view=videos' ) );
		$this->addPathway( $title );  
		
		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {
			$feedLink = CRoute::_('index.php?option=com_community&view=videos&userid=' . $user->id . '&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE MY VIDEOS FEED') . '" href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */


		// Show the mini header when viewing other's photos
		if($my->id != $user->id)
			$this->attachMiniHeaderUser($user->id);

		// Display submenu
		$this->_addSubmenu();
		$this->showSubmenu();

		// Get data from DB
		$model			=& CFactory::getModel('videos');

		$filters		= array
		(
			'creator'	=> $user->id,
			'status'	=> 'ready',
			'groupid'	=> 0,
			'sorting'	=> JRequest::getVar('sort', 'latest')
		);
		$videos			= $model->getVideos($filters);

		// Load required filterbar library that will be used to display the filtering and sorting.
		CFactory::load( 'libraries' , 'filterbar' );

		$sortItems	= array
		(
			'latest' 	=> JText::_('CC VIDEO SORT LATEST'),
			'mostwalls'	=> JText::_('CC VIDEO SORT MOST WALL POST'),
			'mostviews'	=> JText::_('CC VIDEO SORT POPULAR'),
			'title'		=> JText::_('CC VIDEO SORT TITLE')
		);

		//pagination
		$pagination		= $model->getPagination();

		$videosHTML		= $this->_getVideosHTML( $videos );
		
		$tmpl		= new CTemplate();
		$tmpl->set( 'user'			, $user );
		$tmpl->set( 'sort'			, JRequest::getVar('sort', 'latest') );
		$tmpl->set( 'currentTask'	, JRequest::getCmd( 'task' , '') );
		$tmpl->set( 'videosHTML'	, $videosHTML );
		$tmpl->set( 'sortings'		, CFilterBar::getHTML( CRoute::getURI(), $sortItems, 'latest') );
		$tmpl->set( 'pagination'	, $pagination );

		echo $tmpl->fetch( 'videos.myvideos' );	
	}

	function mypendingvideos($id= null)
	{
		$document 	=& JFactory::getDocument();
		$my			= CFactory::getUser();
		
		$userid		= JRequest::getVar( 'userid' , '' );
		$user		= CFactory::getUser($userid);

		$this->_addSubmenu();
		$this->showSubmenu();

		// Set pathway
		$mainframe	=& JFactory::getApplication();
		$pathway 	=& $mainframe->getPathway();
		$pathway->addItem( 'My Pending Videos', '' );

		// Get data from DB
		$model			=& CFactory::getModel('videos');
		
		// Group video pending
		$groupid = JRequest::getVar('groupid', '0');		
		if(!empty($groupid))
		{
			$filters		= array
			(
				'groupid'	=> $groupid,
				'status'	=> 'pending'
			);
		}
		else
		{
			$filters		= array
			(
				'creator'	=> $user->id,
				'groupid'	=> 0,
				'status'	=> 'pending'
			);
		}
		
		$pendingVideos	= $model->getVideos($filters);

		CFactory::load( 'helpers' , 'owner' );

		// Substitute permission in text form
		foreach ($pendingVideos as $video) {
			//$video		=& $this->_getExtra($video);
			$video->isOwner = COwnerHelper::isMine($my->id, $video->creator);
		}
		
		$videosHTML		= $this->_getVideosHTML( $pendingVideos );
		
		$pagination		= $model->getPagination();		
		

		$tmpl	= new CTemplate();

		$tmpl->set( 'videosHTML'	, $videosHTML );
		$tmpl->set( 'sort'			, JRequest::getVar('sort', 'latest') );
		$tmpl->set( 'currentTask'	, JRequest::getCmd( 'task' , '') );
		$tmpl->set( 'pendingVideos'	, $pendingVideos );	
		$tmpl->set( 'pagination'	, $pagination );	
		
		$tmpl->set( 'params'		, $this->videoLib );

		echo $tmpl->fetch( 'videos.pending' );	
	}

	/**
	 * Method to display video
	 *
	 **/
	function video()
	{
		$mainframe	=& JFactory::getApplication();
		$document	=& JFactory::getDocument();
		$my			= CFactory::getUser();
		$videoId	= JRequest::getVar('videoid' , '' , 'GET');
		$groupId	= JRequest::getVar('groupid' , '' , 'GET');	
		
		// Load window library
		CFactory::load( 'libraries' , 'window' );

		// Load necessary window css / javascript headers.
		CWindow::load();
		
		// Load the video table
		$video		=& JTable::getInstance( 'Video' , 'CTable' );

		if (!$video->load($videoId))
		{
			$url	= CRoute::_('index.php?option=com_community&view=videos', false);
			$mainframe->redirect($url, JText::_('CC VIDEO NOT AVAILABLE'), 'warning');
		}
		
		// Specify meta tags for social sites sharing
		CFactory::load( 'helpers' , 'string' );
		$document->setTitle($video->title); // obviously this doesn't work
		$document->setMetaData('title', CStringHelper::escape($video->title)); // hack the above line
		$document->setDescription( CStringHelper::escape($video->getDescription()) );
		$document->setMetaData('medium', 'video');
		$document->addHeadLink($video->getThumbnail(), 'image_src', 'rel'); //cannot exceed 130x110 pixels (facebook)
		
		CFactory::load( 'helpers' , 'owner' );


		// Only add these links when there are photos in the album
		if( ( COwnerHelper::isCommunityAdmin()
			|| ( $my->id == $video->creator && ($my->id != 0) )
			)
			&& ( $video->creator_type != VIDEO_GROUP_TYPE ) )
		{
			$this->addSubmenuItem('' , JText::_('CC FETCH VIDEO THUMBNAIL'), 'joms.videos.fetchThumbnail(\'' . $video->id . '\')', true);

			// Only add the set as profile video for video owner
			// should not allow group video to be profile video since
			// group admin can delete them
			if( $my->id == $video->creator && !empty($groupId))
			{
				$this->addSubmenuItem('' , JText::_('CC SET AS PROFILE VIDEO'), 'joms.videos.linkConfirmProfileVideo(\'' . $video->id . '\')', true);
			}
			$redirectUrl	= CRoute::getURI( false );
			$this->addSubmenuItem( '' , JText::_('CC EDIT') , 'joms.videos.showEditWindow(\'' . $video->id . '\',\'' . $redirectUrl . '\');' , true );
			$this->addSubmenuItem( '' , JText::_('CC DELETE'), 'joms.videos.deleteVideo(\'' . $video->id . '\')', true);
		}

		$group = JTable::getInstance('Group', 'CTable');
		// Load the group, based on video's groupid, NOT the url
		$group->load($video->groupid);

		// Add edit/delete in submenu for group admin
		if($group->isAdmin($my->id))
		{
			$redirectUrl	= CRoute::getURI( false );
			$this->addSubmenuItem( '' , JText::_('CC EDIT') , 'joms.videos.showEditWindow(\'' . $video->id . '\',\'' . $redirectUrl . '\');' , true );
			$this->addSubmenuItem( '' , JText::_('CC DELETE'), 'joms.videos.deleteVideo(\'' . $video->id . '\')', true);
		}
		
		$this->_addSubmenu();
		$this->showSubmenu();		
		
		if($video->creator_type == VIDEO_GROUP_TYPE)
		{
			$this->_groupVideo();
		}
		else
		{
			$this->_userVideo();
		}
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
	function isPermitted($myid=0, $userid=0, $permissions=0)
	{
		if ( $permissions == 0) return true; // public

		// Load Libraries
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

	function _addSubmenu()
	{
		$my		= CFactory::getUser();

		$task	= JRequest::getVar( 'task' , '' , 'REQUEST' );
		$groupId	= JRequest::getVar('groupid' , '' , 'GET');
		
		if( !empty($groupId) )
		{
			$this->addSubmenuItem( 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupId, JText::_('CC BACK TO GROUP'));

			$videos			= $this->model->hasPendingVideos( $groupId , VIDEO_GROUP_TYPE );
			
			if ($videos)
			{
				$this->addSubmenuItem('index.php?option=com_community&view=videos&task=mypendingvideos&groupid=' . $groupId, JText::_('CC PENDING GROUP VIDEOS') , '' , SUBMENU_LEFT );
			}
			
			CFactory::load( 'helpers' , 'group' );			
			$allowManageVideos = CGroupHelper::allowManageVideo($groupId);				
						
			if($allowManageVideos)
			{
				$this->addSubmenuItem( '', JText::_('CC ADD'), 'joms.videos.addVideo(\''.VIDEO_GROUP_TYPE.'\', \''.$groupId.'\')', SUBMENU_RIGHT);
			}
		}
		else
		{
			$this->addSubmenuItem('index.php?option=com_community&view=videos', JText::_('CC ALL VIDEOS'), '', SUBMENU_LEFT);
				
			if(!empty($my->id))
			{
				$this->addSubmenuItem('index.php?option=com_community&view=videos&task=myvideos&userid=' . $my->id, JText::_('CC MY VIDEOS'), '', SUBMENU_LEFT);
				$this->addSubmenuItem( '' , JText::_('CC ADD'), 'joms.videos.addVideo()', SUBMENU_RIGHT);
			}
			
			$this->addSubmenuItem('index.php?option=com_community&view=videos&task=search', JText::_('CC SEARCH VIDEOS'), '', SUBMENU_LEFT);
			
			$videos			= $this->model->hasPendingVideos( $my->id , VIDEO_USER_TYPE );			

			if (!empty($my->id) && $videos )
			{
				$this->addSubmenuItem('index.php?option=com_community&view=videos&task=mypendingvideos&userid=' . $my->id, JText::_('CC PENDING VIDEOS') , '' , SUBMENU_LEFT );
			}
		}
	}
	
	function _userVideo()
	{
		$mainframe	= JFactory::getApplication();
		$document	= JFactory::getDocument();
		
		// Get necessary properties and load the libraries
		CFactory::load('models' , 'videos');
		$my			= CFactory::getUser();
 		$model		= CFactory::getModel('videos');
 		$videoId	= JRequest::getVar('videoid' , '' , 'GET');
		
		$video		= JTable::getInstance( 'Video' , 'CTable' );
		$video->load($videoId);
		$video->loadExtra();
		
		CFactory::load('helpers' , 'owner' );
        $user		= CFactory::getUser( $video->creator );
        $blocked	= $user->isBlocked();
        
        // Show the mini header when viewing other's photos
		if($my->id != $video->creator)
			$this->attachMiniHeaderUser($video->creator);
        
		if( $blocked && !COwnerHelper::isCommunityAdmin() )
		{
			$tmpl	= new CTemplate();
			echo $tmpl->fetch('profile.blocked');
			return;
		}
		
 		if( empty( $videoId ) )
 		{
 			$url	= CRoute::_('index.php?option=com_community&view=videos', false);
 			$mainframe->redirect($url, JText::_('CC NO PROPER VIDEO ID'), 'warning');
		}
		
		// Check permission
		if (!$this->isPermitted($my->id, $video->creator, $video->permissions))
		{
			// Set document title
			$document->setTitle( JText::_('CC RESTRICTED ACCESS') );
			$mainframe->enqueueMessage(JText::_('CC RESTRICTED ACCESS', 'notice'));			
			
			switch ($video->permissions)
			{
				case '40':
					echo JText::_('CC VIDEO OWNNER ONLY', 'notice');
					break;
				case '30':
					$owner	= CFactory::getUser($video->creator);
					echo JText::sprintf('CC VIDEO NEED FRIEND PERMISSION', $owner->getDisplayName());
					break;
				default:
					echo '<p>' . JText::_('CC VIDEO NEED LOGIN', 'notice') . '</p>';
					break;
			}
		}
		else
		{
			// Get extra properties
			$video->player = $video->getViewHTML($video->getWidth(), $video->getHeight());
			$video->hit();

			// Get reporting html
			$reportHTML		= '';
			CFactory::load('libraries', 'reporting');
			$report			= new CReportingLibrary();
			
			$reportHTML		= $report->getReportingHTML( JText::_('CC REPORT VIDEOS') , 'videos,reportVideo' , array( $video->id ) );
			
			// Set pathway
			$pathway 		= $mainframe->getPathway();
			$pathway->addItem( 'Video', CRoute::_('index.php?option=com_community&view=videos') );
			$pathway->addItem( $video->title, '' );
			
			// Set the current user's active profile
			CFactory::setActiveProfile( $video->creator );
			
			// Set document title
			$document->setTitle( $video->title );
			
			CFactory::load( 'libraries' , 'bookmarks' );
			$bookmarks		=new CBookmarks($video->permalink);
			$bookmarksHTML	= $bookmarks->getHTML();
			
			$tmpl	= new CTemplate();
		
			// Get the walls
			CFactory::load( 'libraries' , 'wall' );
			
			$wallContent	= CWallLibrary::getWallContents( 'videos' , $video->id , ( COwnerHelper::isCommunityAdmin() || ($my->id == $video->creator && ($my->id != 0)) ) , 10 ,0);
			$wallCount		= CWallLibrary::getWallCount('videos', $video->id);

			$viewAllLink = false;
			if(JRequest::getVar('task', '', 'REQUEST') != 'app')
			{
				$viewAllLink	= CRoute::_('index.php?option=com_community&view=videos&task=app&videoid=' . $video->id . '&app=walls');
			}
			$wallContent	.= CWallLibrary::getViewAllLinkHTML($viewAllLink, $wallCount);

			$wallForm		= '';
			$wallForm		= CWallLibrary::getWallInputForm( $video->id , 'videos,ajaxSaveWall', 'videos,ajaxRemoveWall' );
			$redirectUrl	= CRoute::getURI( false );
			
			$tmpl->set('redirectUrl'	, $redirectUrl );
			$tmpl->set('wallForm' 		, $wallForm);
			$tmpl->set('wallContent' 	, $wallContent);
			$tmpl->set('bookmarksHTML'	, $bookmarksHTML );
			$tmpl->set('reportHTML' 	, $reportHTML);
			$tmpl->set('video' 			, $video);
			
			echo $tmpl->fetch('videos.video');
		}
	}
	
	function _groupVideo()
	{
		$mainframe	= JFactory::getApplication();
		$document	= JFactory::getDocument();
		
		// Get necessary properties and load the libraries
		CFactory::load('models' , 'videos');
		$my			= CFactory::getUser();
 		$model		= CFactory::getModel('videos');
 		$videoId	= JRequest::getVar('videoid' , '' , 'GET');
		$groupId	= JRequest::getVar('groupid' , '' , 'GET');	
		
		$video		= JTable::getInstance( 'Video' , 'CTable' );
		$video->load($videoId);
		$video->loadExtra();
		
		CFactory::load('helpers' , 'owner' );
        $user		= CFactory::getUser( $video->creator );
        $blocked	= $user->isBlocked();
		
		

		if( $blocked && !COwnerHelper::isCommunityAdmin() )
		{
			$tmpl	= new CTemplate();
			echo $tmpl->fetch('profile.blocked');
			return;
		}
		
 		if( empty( $videoId ) )
 		{
 			$url	= CRoute::_('index.php?option=com_community&view=videos', false);
 			$mainframe->redirect($url, JText::_('CC NO PROPER VIDEO ID'), 'warning');
		}
		
		CFactory::load( 'helpers' , 'group' );
		
		// Check permission
		if(!CGroupHelper::allowViewMedia($groupId))
		{
			// Set document title
			$document->setTitle( JText::_('CC RESTRICTED ACCESS') );
			$mainframe->enqueueMessage(JText::_('CC RESTRICTED ACCESS', 'notice'));			
			echo JText::_('CC VIDEO NEED GROUP MEMBER PERMISSION');			
		}
		else
		{
			// Get extra properties
			$video->player = $video->getViewHTML($video->getWidth(), $video->getHeight());

			$video->hit();

			// Get reporting html
			$reportHTML		= '';
			CFactory::load('libraries', 'reporting');
			$report			= new CReportingLibrary();
		
			$reportHTML		= $report->getReportingHTML( JText::_('CC REPORT VIDEOS') , 'videos,reportVideo' , array( $video->id ) );
		
		
			// Set pathway
			$pathway 		=& $mainframe->getPathway();
			$pathway->addItem( 'Video', CRoute::_('index.php?option=com_community&view=videos') );
			$pathway->addItem( $video->title, '' );
		
			// Set the current user's active profile
			CFactory::setActiveProfile( $video->creator );
		
			// Set document title
			$document->setTitle( $video->title );

			CFactory::load( 'libraries' , 'bookmarks' );
			$bookmarks		=new CBookmarks($video->permalink);
			$bookmarksHTML	= $bookmarks->getHTML();
			
			$tmpl	= new CTemplate();
		
			// Get the walls
			CFactory::load( 'libraries' , 'wall' );
			
			$wallContent	= CWallLibrary::getWallContents( 'videos' , $video->id , ( COwnerHelper::isCommunityAdmin() || ($my->id == $video->creator && ($my->id != 0)) ) , 10 ,0);
			$wallCount		= CWallLibrary::getWallCount('videos', $video->id);

			$viewAllLink = false;
			if(JRequest::getVar('task', '', 'REQUEST') != 'app')
			{
				$viewAllLink	= CRoute::_('index.php?option=com_community&view=videos&task=app&videoid=' . $video->id . '&app=walls');
			}
			$wallContent	.= CWallLibrary::getViewAllLinkHTML($viewAllLink, $wallCount);
			
			$wallForm		= '';
			$wallForm		= CWallLibrary::getWallInputForm( $video->id , 'videos,ajaxSaveWall', 'videos,ajaxRemoveWall' , $viewAllLink );
			$redirectUrl	= CRoute::getURI( false );
			
			$tmpl->set('redirectUrl'	, $redirectUrl );
			$tmpl->set('wallForm' 		, $wallForm);
			$tmpl->set('wallContent' 	, $wallContent);
			$tmpl->set('bookmarksHTML'	, $bookmarksHTML);
			$tmpl->set('reportHTML' 	, $reportHTML);
			$tmpl->set('video' 			, $video);
			
			echo $tmpl->fetch('videos.video');
		}
	}
}
