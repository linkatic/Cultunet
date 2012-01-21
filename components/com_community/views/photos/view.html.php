<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CommunityViewPhotos extends CommunityView
{
	function _addSubmenu()
	{
		$handler	= $this->_getHandler();
		$handler->setSubmenus();
	}

	function _flashuploader()
	{
		$groupId	= JRequest::getVar( 'groupid' , '' , 'REQUEST' );
		$model		= CFactory::getModel( 'photos' );

		// Since upload will always be the browser's photos, use the browsers id
		$my			= CFactory::getUser();

		// Maintenance mode, clear out tokens that are older than 1 hours
		$model->cleanUpTokens();
		$token		= $model->getUserUploadToken( $my->id );

		// We need to generate our own session management since there
		// are some bridges causes the flash browser to not really work.
		if( !$token && $my->id != 0 )
		{
			// Get the current browsers session object.
			$mySession	=& JFactory::getSession();

			// Generate a session handler for this user.
			$myToken	= $mySession->getToken( true );
			
			$date		=& JFactory::getDate();
			$token				= new stdClass();
			$token->userid		= $my->id;
			$token->datetime	= $date->toMySQL();
			$token->token		= $myToken;
			
			$model->addUserUploadSession( $token );
		}
		
		$config			= CFactory::getConfig();
		$albumId		= JRequest::getVar( 'albumid' , '' , 'REQUEST' );
		$handler		= $this->_getHandler();
		$uploadURI		= $handler->getFlashUploadURI( $token , $albumId );

		$albums				= '';
		$createAlbumLink	= '';
		$photoUploaded		= '';
		$photoUploadLimit	= '';
		$viewAlbumLink		= '';

		if(!empty($groupId) )
		{
			CFactory::load( 'models' , 'groups' );
			$group				=& JTable::getInstance( 'Group' , 'CTable' );
			$group->load( $groupId );
			$albums				= $model->getGroupAlbums( $groupId , false , false , '', ( $group->isAdmin( $my->id )  || COwnerHelper::isCommunityAdmin() )  );
			$createAlbumLink	= CRoute::_('index.php?option=com_community&view=photos&task=newalbum&groupid=' . $groupId );
			$photoUploaded		= $model->getPhotosCount( $groupId , PHOTOS_GROUP_TYPE );
			$photoUploadLimit	= $config->get('groupphotouploadlimit');
			$viewAlbumLink		= CRoute::_('index.php?option=com_community&view=photos&task=album&groupid=' . $groupId . '&albumid=' . $albumId);
		}
		else
		{
			$albums				= $model->getAlbums( $my->id );
			$createAlbumLink	= CRoute::_('index.php?option=com_community&view=photos&task=newalbum&userid=' . $my->id );
			$photoUploaded		= $model->getPhotosCount( $my->id , PHOTOS_USER_TYPE );
			$photoUploadLimit	= $config->get('photouploadlimit');
			$viewAlbumLink		= CRoute::_('index.php?option=com_community&view=photos&task=album&userid=' . $my->id . '&albumid=' . $albumId);
		}

		$tmpl				= new CTemplate();

		$tmpl->set('createAlbumLink', $createAlbumLink );
		$tmpl->set('albums'			, $albums );
		$tmpl->set( 'uploadURI'		, $uploadURI );
		$tmpl->set('albumId' 		, $albumId);
		$tmpl->set('uploadLimit'	, $config->get('maxuploadsize') );
		$tmpl->set('photoUploaded'	, $photoUploaded );
		$tmpl->set('viewAlbumLink'	, $viewAlbumLink );
		$tmpl->set('photoUploadLimit' , $photoUploadLimit );
		echo $tmpl->fetch( 'photos.flashuploader' );
	}
	
	/**
	 * Display the multi upload form
	 **/
	function _htmluploader()
	{	
		$groupId	= JRequest::getVar( 'groupid' , '' , 'REQUEST' );
		$model		= CFactory::getModel( 'photos' );
		$my			= CFactory::getUser();
		$config		= CFactory::getConfig();
		$albumId	= JRequest::getVar( 'albumid' , '' , 'REQUEST' );
		
		if(!empty($groupId) )
		{
			CFactory::load( 'models' , 'groups' );
			
			$group				=& JTable::getInstance( 'Group' , 'CTable' );
			$group->load( $groupId );
			$albums				= $model->getGroupAlbums( $groupId , false , false , '', ( $group->isAdmin( $my->id )  || COwnerHelper::isCommunityAdmin() ) );
			$createAlbumLink	= CRoute::_('index.php?option=com_community&view=photos&task=newalbum&groupid=' . $groupId );
			$photoUploaded		= $model->getPhotosCount( $groupId , PHOTOS_GROUP_TYPE );
			$photoUploadLimit	= $config->get('groupphotouploadlimit');
			$viewAlbumLink		= CRoute::_('index.php?option=com_community&view=photos&task=album&groupid=' . $groupId . '&albumid=' . $albumId);
		}
		else
		{
			$albums				= $model->getAlbums( $my->id );
			$createAlbumLink	= CRoute::_('index.php?option=com_community&view=photos&task=newalbum&userid=' . $my->id );
			$photoUploaded		= $model->getPhotosCount( $my->id , PHOTOS_USER_TYPE );
			$photoUploadLimit	= $config->get('photouploadlimit');
			$viewAlbumLink		= CRoute::_('index.php?option=com_community&view=photos&task=album&userid=' . $my->id . '&albumid=' . $albumId);
		}

		// Attach the photo upload css.
		CTemplate::addStylesheet( 'photouploader' );
		
		$tmpl			= new CTemplate();

		$tmpl->set('createAlbumLink', $createAlbumLink );
		$tmpl->set('albums'			, $albums );
		$tmpl->set( 'my'			, CFactory::getUser() );
		$tmpl->set('albumId' 		, $albumId);
		$tmpl->set('photoUploaded'	, $photoUploaded );
		$tmpl->set('viewAlbumLink'	, $viewAlbumLink );
		$tmpl->set('photoUploadLimit' , $photoUploadLimit );
		$tmpl->set('uploadLimit'	, $config->get('maxuploadsize') );

		echo $tmpl->fetch( 'photos.htmluploader' );
	}
	
	function showSubmenu()
	{
		$this->_addSubmenu();
		parent::showSubmenu();
	}

	/**
	 * Default view method
	 * Display all photos in the whole system
	 **/
	function display()
	{
		$document	=& JFactory::getDocument();
		$my			= CFactory::getUser();
		$document->setTitle( JText::_('CC ALL PHOTOS TITLE') );
		
		$this->addPathway( JText::_('CC PHOTOS') );
		$mainframe	=& JFactory::getApplication();
		// Load tooltips lib
		CFactory::load( 'libraries' , 'tooltip' );
		CFactory::load( 'models', 'groups' );
		
 		$model		=& CFactory::getModel( 'photos' );
 		$groupId	= JRequest::getVar( 'groupid' , '' , 'REQUEST' );
		$type		= PHOTOS_USER_TYPE;
		
		$handler	= $this->_getHandler();

		$this->showSubmenu();

		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) { 
		    $groupLink = !empty($groupId) ? '&groupid=' . $groupId : '';
			$feedLink = CRoute::_('index.php?option=com_community&view=photos' . $groupLink . '&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE ALL PHOTOS FEED') . '" href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */

		$albumsData = $handler->getAllAlbumData();
		if($albumsData === FALSE)
		{
			return;
		}
		
		$featuredList	= array();
		if(empty($groupId))
		{
			CFactory::load( 'libraries' , 'featured' );
			$featured		= new CFeatured( FEATURED_ALBUMS );
			$featuredAlbums	= $featured->getItemIds();
		
			/* begin: COMMUNITY_FREE_VERSION */
			if( !COMMUNITY_FREE_VERSION ) {
				foreach($featuredAlbums as $album )
				{
					$table			=& JTable::getInstance( 'Album' , 'CTable' );
					$table->load($album);
		
					$table->thumbnail	= $table->getCoverThumbPath();
					$table->thumbnail	= ($table->thumbnail) ? JURI::root() . $table->thumbnail : JURI::root() . 'components/com_community/assets/album_thumb.jpg';
					$featuredList[]	= $table;
				}
			}
			/* end: COMMUNITY_FREE_VERSION */
		}
		
		$tmpl	= new CTemplate();
		CFactory::load( 'helpers' , 'owner' );

		$tmpl->set( 'isCommunityAdmin' , COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'featuredList'	, $featuredList );
		$tmpl->set( 'albumsHTML'	, $this->_getAllAlbumsHTML($albumsData , $type ) );
		$tmpl->set( 'pagination'	, $model->getPagination() );

		echo $tmpl->fetch( 'photos.index' );
	}

	function myphotos()
	{
		$my			= CFactory::getUser();
        $mainframe  = JFactory::getApplication();		
		$document	=& JFactory::getDocument();
		
		$this->addPathway( JText::_( 'CC PHOTOS' ) , CRoute::_('index.php?option=com_community&view=photos' ) );
		$this->addPathway( JText::_('CC MY PHOTOS TITLE') );
        CFactory::load('helpers' , 'owner' );
        $userid		= JRequest::getVar( 'userid' , '' );
		$user		= CFactory::getUser($userid);
		
        $blocked	= $user->isBlocked();
		CFactory::load('libraries','privacy');
		if( !CPrivacy::isAccessAllowed($my->id, $user->id, 'user', 'privacyPhotoView') )
		{
			//echo JText::_('CC ACCESS FORBIDDEN');
			$this->noAccess();
			return;
		}
		
		if( $blocked && !COwnerHelper::isCommunityAdmin() )
		{
			$tmpl	= new CTemplate();
			echo $tmpl->fetch('profile.blocked');
			return;
		}
				
		if($my->id == $user->id)
			$document->setTitle( JText::_('CC MY PHOTOS TITLE') );
		else
			$document->setTitle( JText::sprintf('CC USERS PHOTO TITLE', $user->getDisplayName()) );
		
		
		// Show the mini header when viewing other's photos
		if($my->id != $user->id)
			$this->attachMiniHeaderUser($user->id);

		$this->showSubmenu();

		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {
			$feedLink = CRoute::_('index.php?option=com_community&view=photos&task=myphotos&userid=' . $user->id . '&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE MY PHOTOS FEED') . '" href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */

 		$model	=& CFactory::getModel( 'photos' );

 		$albums		= $model->getAlbums( $user->id , true , true );
		
		// Load tooltips lib
		CFactory::load( 'libraries' , 'tooltip' );

		$tmpl	= new CTemplate();
		
		$tmpl->set( 'albumsHTML'	, $this->_getAlbumsHTML($albums) );
		$tmpl->set( 'pagination'	, $model->getPagination() );
		
		echo $tmpl->fetch( 'photos.myphotos' );
	}

	function _getAllAlbumsHTML( $albums , $type = PHOTOS_USER_TYPE )
	{
		$my			= CFactory::getUser();
		$groupId	= JRequest::getVar( 'groupid' , '' ,'REQUEST');
		$handler	= $this->_getHandler();
		
		$tmpl		= new CTemplate();

		CFactory::load( 'libraries' , 'activities' );
		CFactory::load( 'models' , 'groups' );
		CFactory::load( 'helpers' , 'owner' );
		
		for($i = 0; $i < count($albums); $i++)
		{
			$type	= $albums[$i]->groupid > 0 ? PHOTOS_GROUP_TYPE : PHOTOS_USER_TYPE;
			
			$albums[$i]->user		= CFactory::getUser( $albums[$i]->creator );
			$albums[$i]->link 		= CRoute::_("index.php?option=com_community&view=photos&task=album&albumid={$albums[$i]->id}&userid={$albums[$i]->creator}");
			$albums[$i]->editLink 	= CRoute::_("index.php?option=com_community&view=photos&task=editAlbum&albumid={$albums[$i]->id}&userid={$albums[$i]->creator}");
			$albums[$i]->uploadLink = CRoute::_("index.php?option=com_community&view=photos&task=uploader&albumid={$albums[$i]->id}&userid={$albums[$i]->creator}");
			$albums[$i]->isOwner	= ($my->id == $albums[$i]->creator);

			if( $type == PHOTOS_GROUP_TYPE)
			{
				$group	=& JTable::getInstance( 'Group' , 'CTable' );
				$group->load($groupId);
				
				$albums[$i]->link 		= CRoute::_("index.php?option=com_community&view=photos&task=album&albumid={$albums[$i]->id}&groupid={$albums[$i]->groupid}");
				$albums[$i]->editLink	= CRoute::_("index.php?option=com_community&view=photos&task=editAlbum&albumid={$albums[$i]->id}&groupid={$albums[$i]->groupid}");
				$albums[$i]->uploadLink = CRoute::_("index.php?option=com_community&view=photos&task=uploader&albumid={$albums[$i]->id}&groupid={$albums[$i]->groupid}");

				
				$params				= $group->getParams();
				$photopermission	= $params->get('photopermission', GROUP_PHOTO_PERMISSION_ADMINS);
			
				if( $photopermission == GROUP_PHOTO_PERMISSION_MEMBERS && $group->isMember($my->id) )
				{
					$albums[$i]->isOwner	= ($my->id == $albums[$i]->creator || $group->isAdmin($my->id ));
				}
				else if( ($photopermission == GROUP_PHOTO_PERMISSION_ADMINS && $group->isAdmin($my->id ) ) || COwnerHelper::isCommunityAdmin() )
				{
					$albums[$i]->isOwner	= true;
				}
				else
				{
					$albums[$i]->isOwner	= false;
				}
			}

			// If new albums that has just been created and
			// does not contain any images, the lastupdated will always be 0000-00-00 00:00:00:00
			// Try to use the albums creation date instead.
			if( $albums[$i]->lastupdated == '0000-00-00 00:00:00' || $albums[$i]->lastupdated == '')
			{
				$albums[$i]->lastupdated	= $albums[$i]->created;

				if( $albums[$i]->lastupdated == '' || $albums[$i]->lastupdated == '0000-00-00 00:00:00')
				{
					$albums[$i]->lastupdated	= JText::_( 'CC NO LAST ACTIVITY' );
				}
				else
				{
					$lastUpdated	= new JDate( $albums[$i]->lastupdated );
					$albums[$i]->lastupdated	= CActivityStream::_createdLapse( $lastUpdated );
				}
			}
			else
			{
				$lastUpdated	= new JDate( $albums[$i]->lastupdated );
				$albums[$i]->lastupdated	= CActivityStream::_createdLapse( $lastUpdated );
			}

		}

		CFactory::load( 'helpers' , 'owner' );

		CFactory::load( 'libraries' , 'featured' );
		$featured	= new CFeatured( FEATURED_ALBUMS );
		$featuredList	= $featured->getItemIds();

		$task			= JRequest::getVar( 'task' , '' , 'GET' );
		$showFeatured	= (empty($task) ) ? true : false;

		$createLink		= $handler->getAlbumCreateLink();

		if( $type == PHOTOS_GROUP_TYPE )
		{
			CFactory::load( 'helpers' , 'group' );
			
			$isOwner	= CGroupHelper::allowManagePhoto( $groupId );
		}
		else
		{
			$userId		= JRequest::getVar( 'userid' , '' , 'REQUEST' );
			$user		= CFactory::getUser( $userId );
			
			$isOwner		= ($my->id == $user->id) ? true : false;
		}
		
		$task	= JRequest::getCmd( 'task' , '');
		$tmpl->set( 'isMember'		, $my->id != 0 );
		$tmpl->set( 'isOwner'		, $isOwner );
		$tmpl->set( 'type'			, $type );
		$tmpl->set( 'createLink'	, $createLink );
		$tmpl->set( 'currentTask'	, $task );
		$tmpl->set( 'showFeatured'		, $showFeatured );
		$tmpl->set( 'featuredList'		, $featuredList );
		$tmpl->set( 'isCommunityAdmin'	, COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'my'		, $my );
		$tmpl->set( 'albums' 	, $albums );
		$tmpl->set( 'isSuperAdmin'	, COwnerHelper::isCommunityAdmin());

		return $tmpl->fetch( 'albums.list' );
	}

	function _getAlbumsHTML( $albums , $type = PHOTOS_USER_TYPE )
	{
		$my			= CFactory::getUser();
		$groupId	= JRequest::getVar( 'groupid' , '' ,'REQUEST');

		$tmpl		= new CTemplate();

		CFactory::load( 'libraries' , 'activities' );
		CFactory::load( 'models' , 'groups' );
		CFactory::load( 'helpers' , 'owner' );
		
		for($i = 0; $i < count($albums); $i++)
		{
			$albums[$i]->user		= CFactory::getUser( $albums[$i]->creator );
			$albums[$i]->link 		= CRoute::_("index.php?option=com_community&view=photos&task=album&albumid={$albums[$i]->id}&userid={$albums[$i]->creator}");
			$albums[$i]->editLink 	= CRoute::_("index.php?option=com_community&view=photos&task=editAlbum&albumid={$albums[$i]->id}&userid={$albums[$i]->creator}");
			$albums[$i]->uploadLink = CRoute::_("index.php?option=com_community&view=photos&task=uploader&albumid={$albums[$i]->id}&userid={$albums[$i]->creator}");
			$albums[$i]->isOwner	= ($my->id == $albums[$i]->creator);

			if( $type == PHOTOS_GROUP_TYPE)
			{
				$group	=& JTable::getInstance( 'Group' , 'CTable' );
				$group->load($groupId);
				
				$albums[$i]->link 		= CRoute::_("index.php?option=com_community&view=photos&task=album&albumid={$albums[$i]->id}&groupid={$albums[$i]->groupid}");
				$albums[$i]->editLink	= CRoute::_("index.php?option=com_community&view=photos&task=editAlbum&albumid={$albums[$i]->id}&groupid={$albums[$i]->groupid}");
				$albums[$i]->uploadLink = CRoute::_("index.php?option=com_community&view=photos&task=uploader&albumid={$albums[$i]->id}&groupid={$albums[$i]->groupid}");

				
				$params				= $group->getParams();
				$photopermission	= $params->get('photopermission', GROUP_PHOTO_PERMISSION_ADMINS);
			
				if( $photopermission == GROUP_PHOTO_PERMISSION_MEMBERS && $group->isMember($my->id) )
				{
					$albums[$i]->isOwner	= ($my->id == $albums[$i]->creator);
				}
				else if( ($photopermission == GROUP_PHOTO_PERMISSION_ADMINS && $group->isAdmin($my->id ) ) || COwnerHelper::isCommunityAdmin() )
				{
					$albums[$i]->isOwner	= true;
				}
				else
				{
					$albums[$i]->isOwner	= false;
				}
			}

			// If new albums that has just been created and
			// does not contain any images, the lastupdated will always be 0000-00-00 00:00:00:00
			// Try to use the albums creation date instead.
			if( $albums[$i]->lastupdated == '0000-00-00 00:00:00' || $albums[$i]->lastupdated == '')
			{
				$albums[$i]->lastupdated	= $albums[$i]->created;

				if( $albums[$i]->lastupdated == '' || $albums[$i]->lastupdated == '0000-00-00 00:00:00')
				{
					$albums[$i]->lastupdated	= JText::_( 'CC NO LAST ACTIVITY' );
				}
				else
				{
					$lastUpdated	= new JDate( $albums[$i]->lastupdated );
					$albums[$i]->lastupdated	= CActivityStream::_createdLapse( $lastUpdated );
				}
			}
			else
			{
				$lastUpdated	= new JDate( $albums[$i]->lastupdated );
				$albums[$i]->lastupdated	= CActivityStream::_createdLapse( $lastUpdated );
			}

		}
		CFactory::load( 'helpers' , 'owner' );

		CFactory::load( 'libraries' , 'featured' );
		$featured	= new CFeatured( FEATURED_ALBUMS );
		$featuredList	= $featured->getItemIds();

		$task			= JRequest::getVar( 'task' , '' , 'GET' );
		$showFeatured	= (empty($task) ) ? true : false;

		$createLink		= CRoute::_('index.php?option=com_community&view=photos&task=newalbum&userid=' . $my->id );

		if( $type == PHOTOS_GROUP_TYPE )
		{
			$createLink	= CRoute::_('index.php?option=com_community&view=photos&task=newalbum&groupid=' . $groupId );
			
			CFactory::load( 'helpers' , 'group' );
			
			$isOwner	= CGroupHelper::allowManagePhoto( $groupId );
		}
		else
		{
			$userId		= JRequest::getVar( 'userid' , '' , 'REQUEST' );
			$user		= CFactory::getUser( $userId );
			
			$isOwner		= ($my->id == $user->id) ? true : false;
		}
		$task	= JRequest::getCmd( 'task' , '');
		$tmpl->set( 'isMember'		, $my->id != 0 );
		$tmpl->set( 'isOwner'		, $isOwner );
		$tmpl->set( 'type'			, $type );
		$tmpl->set( 'createLink'	, $createLink );
		$tmpl->set( 'currentTask'	, $task );
		$tmpl->set( 'showFeatured'		, $showFeatured );
		$tmpl->set( 'featuredList'		, $featuredList );
		$tmpl->set( 'isCommunityAdmin'	, COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'my'		, $my );
		$tmpl->set( 'albums' 	, $albums );
		$tmpl->set( 'isSuperAdmin'	, COwnerHelper::isCommunityAdmin());

		return $tmpl->fetch( 'albums.list' );
	}
	
	/**
	 * Displays edit album form
	 **/
	function editAlbum()
	{
		$document	=& JFactory::getDocument();
		$config		=& CFactory::getConfig();

		
		// Load necessary libraries, models
		CFactory::load( 'models' , 'photos' );
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$albumId	= JRequest::getVar( 'albumid' , '' , 'GET' );
		$type		= JRequest::getVar( 'groupid' , '' , 'REQUEST' );
		$type		= !empty($type) ? PHOTOS_GROUP_TYPE : PHOTOS_USER_TYPE;
		$album->load( $albumId );
		$this->addPathway( JText::sprintf('CC EDIT ALBUM TITLE', $album->name ) );
		$this->showSubmenu();
		
		if( $album->id == 0 )
		{
			echo JText::_('CC INVALID ALBUM');
			return;
		}

		$document->setTitle( JText::sprintf('CC EDIT ALBUM TITLE', $album->name ) );
        
		$js = JURI::root(). 'components/com_community/assets/validate-1.5';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';        
        $document->addScript($js);


        $tmpl	= new CTemplate();
        $tmpl->set( 'album'	, $album );
		$tmpl->set('type' , $type );
        echo $tmpl->fetch( 'photos.editalbum' );
	}

	/**
	 * Display the new album form
	 **/
	function newalbum()
	{
		$config		=& CFactory::getConfig();	
		$document 	=& JFactory::getDocument();

		$document->setTitle( JText::_('CC CREATE NEW ALBUM TITLE') );
		$this->addPathway( JText::_( 'CC PHOTOS' ) , CRoute::_('index.php?option=com_community&view=photos' ) );
		$this->addPathway( JText::_('CC CREATE NEW ALBUM TITLE') );
		
		$js = JURI::root(). 'components/com_community/assets/validate-1.5';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';                
        $document->addScript($js);
        
		$handler	= $this->_getHandler();
		$type	= $handler->getType();

		$this->showSubmenu();
		
		CFactory::load( 'libraries' , 'apps' );
		$app 		=& CAppPlugins::getInstance();
		$appFields	= $app->triggerEvent('onFormDisplay' , array('jsform-photos-newalbum'));
		$beforeFormDisplay	= CFormElement::renderElements( $appFields , 'before' );
		$afterFormDisplay	= CFormElement::renderElements( $appFields , 'after' );

		$tmpl	= new CTemplate();
		$tmpl->set( 'beforeFormDisplay', $beforeFormDisplay );
		$tmpl->set( 'afterFormDisplay'	, $afterFormDisplay );
		$tmpl->set('type' , $type );
		
		echo $tmpl->fetch( 'photos.newalbum' );
	}

	function uploader()
	{
		$document =& JFactory::getDocument();		
		$handler	= $this->_getHandler();
		$albumId	= JRequest::getInt( 'albumid' , -1 );
		$my			= CFactory::getUser();

		$document->setTitle(JText::_('CC UPLOAD MULTIPLE PHOTOS TITLE'));
		$this->addPathway( JText::_( 'CC PHOTOS' ) , CRoute::_('index.php?option=com_community&view=photos' ) );
		
		if( $albumId != -1 )
		{
			$album	=& JTable::getInstance( 'Album' , 'CTable' );
			$album->load( $albumId );

			$this->addPathway( $album->name , $handler->getAlbumURI( $album->id ) );
		}
		$this->addPathway( JText::_('CC UPLOAD MULTIPLE PHOTOS TITLE') );

		$css		= rtrim( JURI::root() , '/' ) . '/components/com_community/assets/uploader/style.css';
		$document->addStyleSheet($css);
		
		// Display submenu on the page.
		$this->showSubmenu();
		
		// Add create album link
		$groupId	= JRequest::getVar( 'groupid' , '' , 'REQUEST' );
		$type		= PHOTOS_USER_TYPE;	

		// Get the configuration for uploader tool
		$config		=& CFactory::getConfig();
		$groupId	= JRequest::getVar( 'groupid' , '' , 'REQUEST' );

		CFactory::load( 'helpers' , 'limits' );

		if($handler->isExceedUploadLimit())
		{
			return;
		}

		$useFlash	= $config->get( 'flashuploader' );

		if( $useFlash )
		{
			echo $this->_flashuploader();
		}
		else
		{
			echo $this->_htmluploader();
		}
		
	}

	/**
	 * Display the photo thumbnails from an album
	 **/
	function album()
	{
		$document	=& JFactory::getDocument();
		$mainframe	=& JFactory::getApplication();
		$config		=& CFactory::getConfig();
		$handler	= $this->_getHandler();

		$albumId	= JRequest::getVar('albumid' , '' , 'GET');
 		$defaultId	= JRequest::getVar('photo' , '' , 'GET');
		
		$handler->setMiniHeader();
		
 		if( empty( $albumId ) )
 		{
 			echo JText::_('CC NO PROPER ALBUM ID');
 			return;
		}
		
		if( !$handler->isAlbumBrowsable( $albumId ) )
		{
			return;
		}
	
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );

		$js = '/assets/gallery';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';
		CAssets::attach($js, 'js');
		
		CFactory::load( 'helpers' , 'string' );
		$document->setTitle( JText::sprintf( 'CC USER PHOTOS TITLE' ,  $handler->getCreatorName() ) .' - '. $album->name );
		$this->setTitle( $album->name );
		$handler->setAlbumPathway( CStringHelper::escape($album->name) );
		$handler->setRSSHeader( $albumId );
		
		// Set album thumbnail and description for social bookmarking sites linking
		$document->addHeadLink($album->getCoverThumbURI(), 'image_src', 'rel');
		$document->setDescription( CStringHelper::escape($album->getDescription()) );
		
		$photos		= $handler->getAlbumPhotos( $album->id );

		// Need to append the absolute path for the captions
		for( $i = 0; $i < count( $photos ); $i++ )
		{
			$item =& JTable::getInstance( 'Photo' , 'CTable' );
			$item->bind($photos[$i]);
			$photos[$i] = $item;
			
			$photo			=& $photos[ $i ];
			$photo->link	= $handler->getPhotoURI( $photo->id , $photo->albumid );
		}
		CFactory::load( 'libraries' , 'bookmarks' );
		$bookmarks	= new CBookmarks( $handler->getAlbumExternalURI( $album->id ) );

		$this->showSubmenu();
		$tmpl	= new CTemplate();
 		$tmpl->set( 'bookmarksHTML'	, $bookmarks->getHTML() );
		$tmpl->set( 'isOwner' 		, $handler->isAlbumOwner( $album->id ) );
		$tmpl->set( 'photos' 		, $photos );
		$tmpl->set( 'album'			, $album);

		echo $tmpl->fetch('photos.album');
	}
	
	/**
	 * Displays single photo view
	 *
	 **/
	function photo()
	{
		$mainframe	=& JFactory::getApplication();
		$document	=& JFactory::getDocument();
		$my			= CFactory::getUser();
		$config		= CFactory::getConfig();
		
		// Load window library
		CFactory::load( 'libraries' , 'window' );		
		CWindow::load();
		
		// Get the configuration object.
		$config	=& CFactory::getConfig();

		$css	= JURI::root() . 'components/com_community/assets/album.css';
		$document->addStyleSheet($css);
		$css	= JURI::root() . 'components/com_community/assets/photos.css';
		$document->addStyleSheet($css);
		
		$js = '/assets/gallery';
		$js	.= ( $config->getBool('usepackedjavascript') ) ? '.pack.js' : '.js';
		CAssets::attach($js, 'js');

 		$albumId	= JRequest::getVar('albumid' , '' , 'GET');
		$defaultId	= JRequest::getVar('photoid' , '' , 'GET');
 		$handler	= $this->_getHandler();
 		$handler->setMiniHeader();
 		
 		if( empty( $albumId ) )
 		{
 			echo JText::_('CC NO PROPER ALBUM ID');
 			return;
		}
		
		CFactory::load( 'models' , 'photos' );
		CFactory::load('helpers', 'friends');

		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );

		// Set pathway
        $pathway 	=& $mainframe->getPathway();
		$pathway->addItem( $album->name , '' );
		
		// Set document title
		CFactory::load( 'helpers' , 'string' );
		$document->setTitle( $album->name );
		
		if( !$handler->isPhotoBrowsable( $defaultId ) )
		{
			return;
		}
		
		$model	=& CFactory::getModel('photos');
		$photos	= $model->getPhotos( $albumId, 1000);

		// @checks: Test if album doesnt have any default photo id. We need to get the first row
		// of the photos to be the default
		if($album->photoid == '0')
		{
			$album->photoid	= ( count( $photos ) >= 1 ) ? $photos[0]->id : '0';
		}

		// Try to see if there is any photo id in the query
		$defaultId		= ( !empty($defaultId) ) ? $defaultId : $album->photoid;

		// Load the default photo
		$photo			=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $defaultId );

		// If default has an id of 0, we need to tell the template to dont process anything
		$default		= ($photo->id == 0 ) ? false : $photo;

		//friend list for photo tag
		CFactory::load( 'libraries' , 'phototagging' );
		$tagging	= new CPhotoTagging();

		for($i=0; $i < count($photos); $i++)
		{
			$item =& JTable::getInstance( 'Photo' , 'CTable' );
			$item->bind($photos[$i]);
			$photos[$i] = $item;
			$row			=& $photos[$i];
			$taggedList		= $tagging->getTaggedList($row->id);				
							
			for($t=0;$t < count($taggedList);$t++)
			{
				$tagItem	=& $taggedList[$t];
				$tagUser	= CFactory::getUser($tagItem->userid);
				
				$canRemoveTag	= 0;
				// 1st we check the tagged user is the photo owner.
				//	If yes, canRemoveTag == true.
				//	If no, then check on user is the tag creator or not.
				//		If yes, canRemoveTag == true
				//		If no, then check on user whether user is being tagged
				if(COwnerHelper::isMine($my->id, $row->creator) || COwnerHelper::isMine($my->id, $tagItem->created_by) || COwnerHelper::isMine($my->id, $tagItem->userid))
				{
					$canRemoveTag = 1;
				}
				
				$tagItem->user			= $tagUser;
				$tagItem->canRemoveTag	= $canRemoveTag;									
				
			}
			$row->tagged	= $taggedList;			
		}

		$friends	= $handler->getTaggingUsers();   

		// Show wall contents
		CFactory::load( 'helpers' , 'friends' );
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'libraries' , 'bookmarks' );
			
		// Load up required objects.		
		$isMine			= COwnerHelper::isMine($my->id, $photo->creator );
		$bookmarks		= new CBookmarks( $handler->getPhotoExternalURI( $photo->id , $album->id ) );
                  
        /**
         * Get ban list
         */            
        $block          =& CFactory::getModel( 'block' );
        $friendlist     = array();
       
        foreach($friends as $friend){
            // Exclude blocked user
            if( !$block->getBlockStatus($my->id,$friend->id) ){
                $friendlist[]   = $friend;
            }   
        }  
		
		$this->showSubmenu();
		$tmpl	= new CTemplate();
		$tmpl->set( 'bookmarksHTML'	, $bookmarks->getHTML() );
		$tmpl->set( 'showWall'		, $handler->isWallAllowed() );
		$tmpl->set( 'allowTag'		, $handler->isTaggable() );
		$tmpl->set( 'isOwner' 		, $isMine );
		$tmpl->set( 'isAdmin'		, COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'photos' 		, $photos );
		$tmpl->set( 'default'		, $default );
		$tmpl->set( 'album'			, $album);
		$tmpl->set( 'friends'		, $friendlist);
		$tmpl->set( 'config'		, $config);
		$tmpl->set( 'photoCreator'	, CFactory::getUser( $photo->creator ) );
		echo $tmpl->fetch('photos.photo');
	}
	
	/**
	 * return the resized images
	 */	 	
	function showimage()
	{
	}
	
	
	/**
	 * Return photos handlers
	 */	 	
	private function _getHandler()
	{
		$handler = null;
		
		$groupId	= JRequest::getVar( 'groupid' , '' , 'REQUEST' );
		$type		= PHOTOS_USER_TYPE;

		if(!empty($groupId) )
		{
			// group photo
			$handler = new CommunityViewPhotosGroupHandler( $this );
		}
		else
		{
			// user photo
			$handler = new CommunityViewPhotosUserHandler( $this );
		}
		
		return $handler;
	} 
}

abstract class CommunityViewPhotoHandler extends CommunityView
{
	protected $type 	= '';
	protected $model 	= '';
	protected $view		= '';
	protected $my		= '';
		
	abstract public function getType();
	abstract public function getFlashUploadURI( $token , $albumId );
	abstract public function getAllAlbumData();
	abstract public function getAlbumURI( $albumId );
	abstract public function getAlbumExternalURI( $albumId );
	abstract public function getPhotoURI( $photoId , $albumId );
	abstract public function getPhotoExternalURI( $photoId , $albumId );
	abstract public function getCreatorName();
	abstract public function getAlbumPhotos( $albumId );
	abstract public function getTaggingUsers();
	abstract public function getAlbumCreateLink();
	
	abstract public function setAlbumPathway( $albumName );
	abstract public function setMiniHeader();
	abstract public function setSubmenus();
	abstract public function setRSSHeader( $albumId );
	
	abstract public function isExceedUploadLimit();
	abstract public function isPhotoBrowsable( $photoId );
	abstract public function isAlbumBrowsable( $albumId );
	abstract public function isAlbumOwner( $albumId );
	abstract public function isTaggable();
	abstract public function isWallAllowed();
	
	public function __construct( CommunityViewPhotos $viewObj )
	{
		$this->view		= $viewObj;
		$this->my		= CFactory::getUser();
		$this->model	= CFactory::getModel( 'photos' );
	}
}
class CommunityViewPhotosUserHandler extends CommunityViewPhotoHandler
{
	var $user	= null;
	
	public function __construct( $viewObj )
	{
		parent::__construct( $viewObj );
		$userid			= JRequest::getVar('userid' , '' , 'GET' );
		$this->user		= CFactory::getUser( $userid );
	}

	public function getAlbumCreateLink()
	{
		return CRoute::_('index.php?option=com_community&view=photos&task=newalbum&userid=' . $this->my->id );
	}
	
	public function getFlashUploadURI( $token , $albumId )
	{
		$session	= JFactory::getSession();
		$url	= rtrim( JURI::root() , '/' );
		$url	.= '/index.php?option=com_community&view=photos&task=upload&no_html=1&albumid=' . $albumId . '&tmpl=component';
		$url	.= '&' . $session->getName() . '=' . $session->getId() .'&token=' . $token->token .'&uploaderid=' . $this->my->id . '&userid=' . $this->my->id;
		
		return $url;
	}
	
	public function isWallAllowed()
	{
		CFactory::load( 'helpers' , 'friends' );
		CFactory::load( 'helpers' , 'owner' );
		
		$config		= CFactory::getConfig();
		
		// Check if user is really allowed to post walls on this photo.
		if( COwnerHelper::isMine( $this->my->id , $this->user->id ) || (!$config->get('lockprofilewalls')) || ( $config->get('lockprofilewalls') && CFriendsHelper::isConnected( $this->my->id , $this->user->id ) ) )
		{
			return true;
		}
		return false;
	}

	public function isTaggable()
	{
		CFactory::load( 'helpers' , 'friends' );
		CFactory::load( 'helpers' , 'owner' );
		
		if( COwnerHelper::isMine( $this->my->id , $this->user->id ) || CFriendsHelper::isConnected( $this->my->id , $this->user->id ) )
		{
			return true;
		}
		return false;
	}
	
	public function getTaggingUsers()
	{
		$model		=& CFactory::getModel( 'friends' );
		$friends	= $model->getFriends( $this->my->id , '' , false );
		array_unshift($friends, $this->my);
		
		return $friends;
	}
	
	public function setRSSHeader( $albumId )
	{
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		$mainframe	=& JFActory::getApplication();

		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION )
		{
			// Set feed url
			$link	= CRoute::_('index.php?option=com_community&view=photos&task=album&albumid='.$album->id.'&userid='.$album->creator.'&format=feed');
			$feed	= '<link rel="alternate" type="application/rss+xml" href="'.$link.'"/>';
			
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */
	}
	
	public function getAlbumPhotos( $albumId )
	{
		$config	= CFactory::getConfig();
		$model	= CFactory::getModel('Photos');
		
		// @todo: make limit configurable?
		return $model->getAllPhotos( $albumId, PHOTOS_USER_TYPE , null , null , $config->get('photosordering') );
	}
	
	public function setAlbumPathway( $albumName )
	{
		$mainframe	= JFactory::getApplication();
        $pathway 	=& $mainframe->getPathway();
		$pathway->addItem( $this->user->getDisplayName(), CUrlHelper::userLink($this->user->id) );
	}

	public function setSubmenus()
	{
		$my			= CFactory::getUser();
		$config		= CFactory::getConfig();
		$task		= JRequest::getCmd( 'task' , '' , 'GET' );
		$albumId	= JRequest::getInt('albumid' , '' , 'GET');
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		if($albumId != 0 )
		{
			$albumId	= '&albumid=' . $albumId;
		}
		else
		{
			$albumId	= '';
		}

		switch( $task )
		{
			case 'photo':
				if( COwnerHelper::isCommunityAdmin() || ($this->my->id == $album->creator && ($this->my->id != 0) ) ) 
				{
					$this->view->addSubmenuItem('' , JText::_('CC DELETE'), "joms.gallery.confirmRemovePhoto();", true);
					
					if( $this->my->id == $album->creator )
					{
						$this->view->addSubmenuItem('' , JText::_('CC SET AS PROFILE PICTURE'), "joms.gallery.setProfilePicture();" , true);
					}
					$this->view->addSubmenuItem('' , JText::_('CC SET IMAGE AS ALBUM COVER'), "joms.gallery.setPhotoAsDefault();" , true);
				}
				if( !$config->get('deleteoriginalphotos') ) {
					$this->view->addSubmenuItem('' , JText::_('CC DOWNLOAD IMAGE'), "joms.gallery.downloadPhoto();", true);
				}
			case 'singleupload':
			case 'uploader':
				if ($albumId) $this->view->addSubmenuItem('index.php?option=com_community&view=photos&userid=' . $this->user->id . '&task=album' . $albumId , JText::_('CC BACK TO ALBUM'));
				$this->view->addSubmenuItem('index.php?option=com_community&view=photos', JText::_('CC ALL PHOTOS'));
			break;
			case 'myphotos':
			default:
				$this->view->addSubmenuItem('index.php?option=com_community&view=photos', JText::_('CC ALL PHOTOS'));
				
				if( $this->my->id != 0 || COwnerHelper::isCommunityAdmin() )
				{
					$this->view->addSubmenuItem('index.php?option=com_community&view=photos&task=uploader&userid=' . $my->id . $albumId, JText::_('CC UPLOAD PHOTOS'), '', true);
				}

				if( $task == 'album' && $my->id == $album->creator )
				{
					$this->view->addSubmenuItem('index.php?option=com_community&view=photos&task=editAlbum' . $albumId . '&userid=' . $my->id , JText::_('CC EDIT ALBUM') , '' , true );
				}

				if( $this->my->id != 0 || COwnerHelper::isCommunityAdmin() )
				{
					$this->view->addSubmenuItem('index.php?option=com_community&view=photos&task=newalbum&userid=' . $my->id, JText::_('CC ADD ALBUM') , '' , true );
				}
			break;
		}		

		if( $my->id != 0 )
		{
			$this->view->addSubmenuItem('index.php?option=com_community&view=photos&task=myphotos&userid=' . $my->id, JText::_('CC MY PHOTOS'));
		}
	}
	
	public function getType()
	{
		return PHOTOS_USER_TYPE;
	}

	public function isPhotoBrowsable( $photoId )
	{
		CFactory::load( 'helpers' , 'owner' );
	
		if( $this->user->block && !COwnerHelper::isCommunityAdmin($this->my->id ) )
		{
			$tmpl	= new CTemplate();
			echo $tmpl->fetch('profile.blocked');
			return false;
		}
		
		CFactory::load( 'libraries' , 'privacy' );
		
		if( !CPrivacy::isAccessAllowed( $this->my->id , $this->user->id , 'photos' , 'privacyPhotoView' ) )
		{
			//echo JText::_('CC ACCESS FORBIDDEN');
			$this->noAccess();
			return false;
		}
		return true;
	}
	
	public function isAlbumBrowsable( $albumId )
	{
		CFactory::load('libraries', 'privacy' );
		$mainframe	=& JFactory::getApplication();
		if($this->user->block && !COwnerHelper::isCommunityAdmin( $this->my->id ) )
		{
			$mainframe->redirect( 'index.php?option=com_community&view=photos', JText::_('CC USER ACCOUNT IS BANNED') );
			return false;
		}
		
		if( !CPrivacy::isAccessAllowed($this->my->id, $this->user->id, 'user', 'privacyPhotoView') )
		{
			//echo JText::_('CC ACCESS FORBIDDEN');
			$this->noAccess();
			return false;
		}
		return true;
	}
	
	public function isAlbumOwner( $albumId )
	{
		CFactory::load('models' , 'photos' );
		
		if( $this->my->id == 0 )
			return false;
			
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		return COwnerHelper::isMine($this->my->id, $album->creator );
	}
	
	/**
	 * Return the uri to the album view, given the album id
	 */
	public function getAlbumURI( $albumId )
	{
		return CRoute::_( 'index.php?option=com_community&view=photos&task=album&albumid=' . $albumId . '&userid=' . $this->user->id );
	}

	public function getAlbumExternalURI( $albumId )
	{
		return CRoute::getExternalURL( 'index.php?option=com_community&view=photos&task=album&albumid=' . $albumId . '&userid=' . $this->user->id );
	}
	
	/**
	 * Return the uri to the photo view, given the album id and photo id
	 */	 	
	public function getPhotoURI( $photoId , $albumId )
	{
		return CRoute::_('index.php?option=com_community&view=photos&task=photo&userid=' . $this->user->id . '&albumid=' . $albumId ) . '#photoid=' . $photoId;
	}

	public function getPhotoExternalURI( $photoId, $albumId )
	{
		return CRoute::getExternalURL( 'index.php?option=com_community&view=photos&task=album&albumid=' . $albumId . '&userid=' . $this->user->id ) . '#photoid=' . $photoId;
	}
	
	public function isExceedUploadLimit()
	{
		$my	= CFactory::getUser();
		
		if( CLimitsHelper::exceededPhotoUpload($my->id , PHOTOS_USER_TYPE ) )
		{
			$config			= CFactory::getConfig();
			$photoLimit		= $config->get( 'photouploadlimit' );
			
			echo JText::sprintf('CC PHOTO UPLOAD LIMIT REACHED' , $photoLimit );
			return true;
		}
		return false;
	}
	
	/**
	 * Return data for the 'all album' view
	 */
	public function getAllAlbumData()
	{
		$albumsData		= $this->model->getAllAlbums( $this->my->id );
		return $albumsData;
	}
	
	public function setMiniHeader()
	{
		if( $this->my->id != $this->user->id )
		{
			$this->view->attachMiniHeaderUser($this->user->id);
		}
	}
	
	public function getCreatorName()
	{
		return $this->user->getDisplayName();
	}
}

class CommunityViewPhotosGroupHandler extends CommunityViewPhotoHandler
{
	private $groupid = null;
	
	/**
	 * Constructor
	 */
	public function __construct( $viewObj )
	{
		parent::__construct( $viewObj );
		$this->groupid = JRequest::getVar( 'groupid' , '' , 'REQUEST' );
	}

	public function getFlashUploadURI( $token , $albumId )
	{
		$session	= JFactory::getSession();
		$url	= rtrim( JURI::root() , '/' );
		$url	.= '/index.php?option=com_community&view=photos&task=upload&no_html=1&albumid=' . $albumId . '&tmpl=component';
		$url	.= '&' . $session->getName() . '=' . $session->getId() .'&token=' . $token->token .'&uploaderid=' . $this->my->id . '&groupid=' . $this->groupid;
		
		return $url;
	}
	
	public function getAlbumCreateLink()
	{
		return CRoute::_('index.php?option=com_community&view=photos&task=newalbum&groupid=' . $this->groupid );
	}
	
	public function isWallAllowed()
	{
		return $this->isTaggable();
	}
	
	public function isTaggable()
	{
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'groups' );
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $this->groupid );
		
		//check if we can allow the current viewing user to tag the photos
		if($group->isMember( $this->my->id ) || $group->isAdmin( $this->my->id ) || COwnerHelper::isCommunityAdmin() )
		{
			return true;
		}
		return false;
	}
	
	public function getTaggingUsers()
	{
		// for photo tagging. only allow to tag members
		$model	=& CFactory::getModel( 'groups' );
		$ids	= $model->getMembersId( $this->groupid , true);
		$users	= array();
		
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $this->groupid );
		
		foreach($ids as $id )
		{
			if( $this->my->id != $id )
			{
				$user		= CFactory::getUser( $id );			
				$users[]	= $user;
			}
		}

		CFactory::load( 'helpers' , 'owner' );
		
		if(COwnerHelper::isCommunityAdmin() || $group->isAdmin( $this->my->id ) || $group->isMember( $this->my->id ))
			array_unshift($users, $this->my);
		
		return $users;
	}
	
	public function setRSSHeader( $albumId )
	{
		return;
	}
	
	public function getAlbumPhotos( $albumId )
	{
		$config	= CFactory::getConfig();
		$model	= CFactory::getModel('Photos');
		
		// @todo: make limit configurable?
		return $model->getAllPhotos( $albumId , PHOTOS_GROUP_TYPE  , null , null , $config->get('photosordering') );
	}

	public function setSubmenus()
	{
		CFactory::load( 'helpers' , 'group' );
		CFactory::load( 'helpers' , 'owner' );
		
		$task		= JRequest::getVar( 'task' , '' , 'GET' );
		$albumId	= JRequest::getInt('albumid' , 0 , 'REQUEST');

		$album		= JTable::getInstance( 'Album' ,'CTable' );
		$album->load($albumId );
		
		CFactory::load( 'models' , 'groups' );
		$config		= CFactory::getConfig();
		$group		= JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $album->groupid );
		$my			= CFactory::getUser();
		$albumId	= $albumId != 0 ? '&albumid=' . $albumId : '';

		CFactory::load( 'helpers' , 'group' );
		
		$allowManagePhotos = CGroupHelper::allowManagePhoto($this->groupid);
		
		if( ($task == 'uploader' || $task == 'photo') && !empty($albumId) )
		{
			$this->view->addSubmenuItem('index.php?option=com_community&view=photos&groupid=' . $this->groupid . '&task=album' . $albumId , JText::_('CC BACK TO ALBUM'));
		}
				
		if( $allowManagePhotos && $task != 'photo' )
		{
			$this->view->addSubmenuItem('index.php?option=com_community&view=photos&task=uploader&groupid=' . $this->groupid . $albumId , JText::_('CC UPLOAD PHOTOS'), '', true);

			if( $task == 'album')
			{
				$this->view->addSubmenuItem('index.php?option=com_community&view=photos&task=editAlbum&albumid=' . $album->id . '&groupid=' . $group->id , JText::_('CC EDIT ALBUM') , '' , true );
			}

			$this->view->addSubmenuItem('index.php?option=com_community&view=photos&task=newalbum&groupid=' . $this->groupid , JText::_('CC ADD ALBUM') , '' , true );
		}

		if( $task == 'photo' )
		{
			if( $album->hasAccess( $my->id , 'deletephotos' ) )
			{
				$this->view->addSubmenuItem('' , JText::_('CC DELETE IMAGE'), "joms.gallery.confirmRemovePhoto();", true);
			}
			
			if( $my->id == $album->creator )
			{
				$this->view->addSubmenuItem('' , JText::_('CC SET AS PROFILE PICTURE'), "joms.gallery.setProfilePicture();" , true);
			}

			if( ($my->id == $album->creator && $allowManagePhotos ) || $group->isAdmin($my->id) || COwnerHelper::isCommunityAdmin() ) 
			{
				$this->view->addSubmenuItem('' , JText::_('CC SET IMAGE AS ALBUM COVER'), "joms.gallery.setPhotoAsDefault();" , true);	
			}

			if( !$config->get('deleteoriginalphotos') ) {
				$this->view->addSubmenuItem('' , JText::_('CC DOWNLOAD IMAGE'), "joms.gallery.downloadPhoto();", true);
			}
		}

		$this->view->addSubmenuItem('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $this->groupid , JText::_('CC BACK TO GROUP'));

	}
	
	public function isPhotoBrowsable( $photoId )
	{
		CFactory::load( 'helpers' , 'group' );
		
		//@rule: Do not allow non members to view albums for private group
		if(!CGroupHelper::allowViewMedia($this->groupid))
		{
			$mainframe	=& JFactory::getApplication();
			$document	=& JFactory::getDocument();

			$document->setTitle( JText::_('CC RESTRICTED ACCESS') );
			$mainframe->enqueueMessage(JText::_('CC RESTRICTED ACCESS', 'notice'));
			
			echo JText::_('CC PHOTO NEED GROUP MEMBER PERMISSION');			
			return false;
		}
		return true;
	}

	public function isAlbumOwner( $albumId )
	{
		CFactory::load('models','groups');

		if( $this->my->id == 0 )
			return false;
			
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $this->groupid );
		
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		if($album->creator != $this->my->id && !COwnerHelper::isCommunityAdmin() && !$group->isAdmin( $this->my->id ))
		{
			return false;
		}
		return true;
	}
	
	public function isAlbumBrowsable( $albumId )
	{
		CFactory::load('models','groups');
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $this->groupid );
		$document	=& JFactory::getDocument();
		$mainframe	=& JFactory::getApplication();
		//@rule: Do not allow non members to view albums for private group
		if( $group->approvals == COMMUNITY_PRIVATE_GROUP && !$group->isMember( $this->my->id ) && !$group->isAdmin( $this->my->id ) && !COwnerHelper::isCommunityAdmin() )
		{			
			// Set document title
			$document->setTitle( JText::_('CC RESTRICTED ACCESS') );
			$mainframe->enqueueMessage(JText::_('CC RESTRICTED ACCESS', 'notice'));
			
			echo JText::_('CC ALBUM NEED GROUP MEMBER PERMISSION');			
			return false;
		}
		return true;
	}
	
	public function getType()
	{
		return PHOTOS_GROUP_TYPE;
	}
	
	/**
	 * Return the uri to the album view, given the album id
	 */	 	
	public function getAlbumURI( $albumId )
	{
		return CRoute::_( 'index.php?option=com_community&view=photos&task=album&albumid=' . $albumId . '&groupid=' . $this->groupid );
	}

	public function getAlbumExternalURI( $albumId )
	{
		return CRoute::getExternalURL( 'index.php?option=com_community&view=photos&task=album&albumid=' . $albumId . '&groupid=' . $this->groupid );
	}
	
	public function getPhotoURI( $photoId , $albumId )
	{
		return CRoute::_('index.php?option=com_community&view=photos&task=photo&groupid=' . $this->groupid . '&albumid=' . $albumId ) . '#photoid=' . $photoId;
	}
	
	public function getPhotoExternalURI( $photoId, $albumId )
	{
		return CRoute::getExternalURL( 'index.php?option=com_community&view=photos&task=photo&albumid=' . $albumId . '&groupid=' . $this->groupid ) . '#photoid=' . $photoId;
	}
	
	public function isExceedUploadLimit()
	{
		if( CLimitsHelper::exceededPhotoUpload($this->groupid , PHOTOS_GROUP_TYPE ) )
		{
			$config			=& CFactory::getConfig();
			$photoLimit		= $config->get( 'groupphotouploadlimit' );
			
			echo JText::sprintf('CC GROUP PHOTO UPLOAD LIMIT REACHED' , $photoLimit );
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Return data for the 'all album' view
	 */	 	
	public function getAllAlbumData()
	{
		$my	= CFactory::getUser();
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $this->groupid );
		
		//@rule: Do not allow non members to view albums for private group
		if( $group->approvals == COMMUNITY_PRIVATE_GROUP && !$group->isMember( $my->id ) && !$group->isAdmin( $my->id ) )
		{
			$this->noAccess();
			return FALSE;			
		}
		$type		= PHOTOS_GROUP_TYPE;
		$albumsData	= $this->model->getGroupAlbums( $this->groupid, true );
		
		return $albumsData;
	}
	
	public function setMiniHeader()
	{
		// Do nothing because the mini header for groups are done on the view itself. Function is to satisfy the abstract.
	}

	public function setAlbumPathway( $albumName )
	{
		$mainframe	=& JFactory::getApplication();
        $pathway 	=& $mainframe->getPathway();
		$pathway->addItem( $albumName , '' );
	}

	public function getCreatorName()
	{
		CFactory::load('models','groups');
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $this->groupid );
		
		return $group->name;
	}
}