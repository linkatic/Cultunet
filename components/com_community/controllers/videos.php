<?php
/**
 * @package		JomSocial
 * @subpackage  Controller 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.controller' );

class CommunityVideosController extends CommunityBaseController
{
	var $_name = 'videos';
	
	public function checkVideoAccess()
	{
		$mainframe	= JFactory::getApplication();
		$config		= CFactory::getConfig();
		
		if (!$config->get('enablevideos'))
		{
			$redirect	= CRoute::_('index.php?option=com_community&view=frontpage', false);
			$mainframe->redirect($redirect, JText::_('CC VIDEOS DISABLE'), 'warning');
		}
		return true;
	}
	
	public function ajaxRemoveFeatured( $videoId )
	{
		$objResponse	= new JAXResponse();
		CFactory::load( 'helpers' , 'owner' );
		
		if( COwnerHelper::isCommunityAdmin() )
		{
			$model	= CFactory::getModel('Featured');
			
			CFactory::load( 'libraries' , 'featured' );
			$featured	= new CFeatured(FEATURED_VIDEOS);
			$my			= CFactory::getUser();
			
			if($featured->delete($videoId))
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC VIDEO REMOVED FROM FEATURED'));	
			}
			else
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC ERROR REMOVING VIDEO FROM FEATURED'));
			}
		}
		else
		{
			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC NOT ALLOWED TO ACCESS SECTION'));
		}
		$buttons	= '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('CC BUTTON CLOSE') . '"/>';
		
		$objResponse->addScriptCall( 'cWindowActions' , $buttons );
		return $objResponse->sendResponse();
	}
	
	public function ajaxAddFeatured( $videoId )
	{
		$objResponse	= new JAXResponse();
		CFactory::load( 'helpers' , 'owner' );
		
		$my				= CFactory::getUser();
		if( $my->id == 0 )
		{
			return $this->ajaxBlockUnregister();
		}
		
		if( COwnerHelper::isCommunityAdmin() )
		{
			$model	= CFactory::getModel('Featured');
			
			if( !$model->isExists( FEATURED_VIDEOS , $videoId ) )
			{
				CFactory::load( 'libraries' , 'featured' );
				CFactory::load( 'models' , 'videos' );
				
				$featured	= new CFeatured( FEATURED_VIDEOS );
				$featured->add( $videoId , $my->id );
				
				$table		= JTable::getInstance( 'Video' , 'CTable' );
				$table->load( $videoId );
				
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::sprintf('CC VIDEO IS FEATURED', $table->title ));
			}
			else
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC VIDEO ALREADY FEATURED'));
			}
		}
		else
		{
			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC NOT ALLOWED TO ACCESS SECTION'));
		}
		$buttons	= '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('CC BUTTON CLOSE') . '"/>';
		
		$objResponse->addScriptCall( 'cWindowActions' , $buttons );
		return $objResponse->sendResponse();
	}
	
	/**
	 * Method is called from the reporting library. Function calls should be
	 * registered here.
	 *
	 * return	String	Message that will be displayed to user upon submission.
	 **/
	public function reportVideo( $link, $message , $id )
	{
		if ($this->blockUnregister()) return;
		
		CFactory::load( 'libraries' , 'reporting' );
		$report = new CReportingLibrary();
		
		// Pass the link and the reported message
		$report->createReport( JText::_('CC BAD PHOTO') ,$link , $message );
		
		// Add the action that needs to be called.
		$action					= new stdClass();
		$action->label			= 'Delete video';
		$action->method			= 'videos,deleteVideo';
		$action->parameters		= array( $id , 0 );
		$action->defaultAction	= false;
		
		$report->addActions( array( $action ) );
		return JText::_('CC REPORT SUBMITTED');
	}
	
	/**
	 * Show all video within the system
	 */
	public function display()
	{
		$this->checkVideoAccess();
		$document	= JFactory::getDocument();
		$viewType	= $document->getType();
		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		= $this->getView( $viewName , '' , $viewType );
		
		echo $view->get( __FUNCTION__ );
	}
	
	/**
	 * Full application view
	 */
	public function app()
	{
		$view	= $this->getView('videos');
		echo $view->get( 'appFullView' );
	}
	
	/**
	 * Display all video by current user
	 */
	public function myvideos()
	{
		$this->checkVideoAccess();
		$document	= JFactory::getDocument();
		$viewType	= $document->getType();
		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		= $this->getView( $viewName , '' , $viewType );
		
		$userid		= JRequest::getVar( 'userid' , '' );
		$user		= CFactory::getUser($userid);

		echo $view->get( __FUNCTION__ , $user->id);
	}
	
	/**
	 * Display all video by current user
	 */
	public function mypendingvideos()
	{
		if ($this->blockUnregister()) return;
		$this->checkVideoAccess();
		
		$document	= JFactory::getDocument();
		$viewType	= $document->getType();
		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		= $this->getView( $viewName , '' , $viewType );
		$user		= CFactory::getUser();
		echo $view->get( __FUNCTION__ , $user->id);
	}
	
	/**
	 * Show the  'add' video page. It should just link to either link or upload
	 */
	public function add()
	{
		if ($this->blockUnregister()) return;
		$this->checkVideoAccess();
		
		$document	= JFactory::getDocument();
		$viewType	= $document->getType();	
		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		= $this->getView( $viewName , '' , $viewType );
		echo $view->get( __FUNCTION__ );
	}
	
	/**
	 * Show the add video link form
	 * @return unknown_type
	 */
	public function link()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
		
		if ($this->blockUnregister()) return;
		$this->checkVideoAccess();
		
		$document	= JFactory::getDocument();
		$viewType	= $document->getType();
		$viewName	= JRequest::getCmd('view', $this->getName());
		$view		= $this->getView( $viewName , '' , $viewType );
		
		// Preset the redirect url according to group type or user type
		CFactory::load('helpers' , 'videos');
		$mainframe	= JFactory::getApplication();
		$redirect	= CVideosHelper::getVideoReturnUrlFromRequest();
		
		// Without CURL library, there's no way get the video information
		// remotely
		CFactory::load('helpers', 'remote');
		if (!CRemoteHelper::curlExists())
		{
			$mainframe->redirect( $redirect , JText::_('CC CURL NOT EXISTS') );
		}
		
		// Determine if the video belongs to group or user and
		// assign specify value for checking accordingly
		$creatorType	= JRequest::getVar('creatortype' , VIDEO_USER_TYPE);
		$groupid		= ($creatorType==VIDEO_GROUP_TYPE)? JRequest::getInt('groupid', 0) : 0;
		$config			= CFactory::getConfig();
		if(!empty($groupid))
		{
			CFactory::load('helpers', 'group');
			$allowManageVideos	= CGroupHelper::allowManageVideo($groupid);
			$creatorType		= VIDEO_GROUP_TYPE;
			$videoLimit			= $config->get( 'groupvideouploadlimit' );
			CError::assert($allowManageVideos, '', '!empty', __FILE__ , __LINE__ );
		} else {
			$creatorType		= VIDEO_USER_TYPE;
			$videoLimit			= $config->get('videouploadlimit');
		}
		
		// Do not allow video upload if user's video exceeded the limit
		CFactory::load('helpers' , 'limits' );
		$my = CFactory::getUser();
		if(CLimitsHelper::exceededVideoUpload($my->id, $creatorType))
		{
			$message = JText::sprintf('CC VIDEOS CREATION REACH LIMIT', $videoLimit);
			$mainframe->redirect( $redirect , $message );
			exit;
		}
		
		// Create the video object and save
		$videoUrl = JRequest::getVar( 'videoLinkUrl' , '' );
		if(empty($videoUrl))
		{
			$view->addWarning( JText::_('CC INVALID VIDEO LINKS') );
			echo $view->get( __FUNCTION__ );
			exit;
		}
		CFactory::load('libraries', 'videos');
		$videoLib 	= new CVideoLibrary();


		CFactory::load( 'models' , 'videos' );
		$video	= JTable::getInstance( 'Video' , 'CTable' );
		$isValid = $video->init( $videoUrl );
		
		if (!$isValid )
		{
			$mainframe->redirect( $redirect, $video->getProvider()->getError() ,'error' );
			return;
		}

		$video->set('creator',		$my->id);
		$video->set('creator_type',	$creatorType);
		$video->set('permissions',	JRequest::getVar( 'privacy', '', 'POST' ));
		$video->set('category_id',	JRequest::getVar( 'category' , '1' , 'POST' ));
		$video->set('groupid',		$groupid);
		
		if (!$video->store())
		{
			$message		= JText::_('CC ADD VIDEO LINK FAILED');
			$mainframe->redirect( $redirect , $message );
			
		}
		
		// Fetch the thumbnail and store it locally, 
		// else we'll use the thumbnail remotely
		CError::assert($video->thumb, '', '!empty');
		$this->_fetchThumbnail($video->id);
		
		// Add activity logging, only to non private videos
		if($video->isPublic())
		{
			$url	= $video->getViewUri(false);
			
			$act			= new stdClass();
			$act->cmd 		= 'videos.upload';
			$act->actor		= $my->id;
			$act->target	= 0;
			$act->title		= JText::sprintf('CC ACTIVITIES POST VIDEO' , '{video_url}' , $video->title );
			$act->app		= 'videos';
			$act->content	= '{getActivityContentHTML}';
			$act->cid		= $video->id;
			
			$params = new JParameter('');
			$params->set( 'video_url', $url );
			
			CFactory::load ( 'libraries', 'activities' );
			CActivityStream::add( $act , $params->toString() );
		}
		
		// @rule: Add point when user adds a new video link
		CFactory::load( 'libraries' , 'userpoints' );
		CUserPoints::assignPoint('video.add', $video->creator);
		
		// Redirect user to his/her video page
		$message		= JText::sprintf('CC VIDEO UPLOAD SUCCESS', $video->title);
		$mainframe->redirect( $redirect , $message );
	}
	
	public function upload()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
		
		if ($this->blockUnregister()) return;
		$this->checkVideoAccess();
		
		$document		= JFactory::getDocument();
		$viewType		= $document->getType();
		$viewName		= JRequest::getCmd( 'view', $this->getName() );
		$view			= $this->getView( $viewName , '' , $viewType );
		$mainframe		= JFactory::getApplication();
		$my				= CFactory::getUser();
		$creatorType	= JRequest::getVar( 'creatortype' , VIDEO_USER_TYPE );
		$groupid 		= ($creatorType==VIDEO_GROUP_TYPE)? JRequest::getInt( 'groupid' , 0 ) : 0;
		$config			= CFactory::getConfig();
		
		CFactory::load('helpers', 'videos');
		CFactory::load('libraries', 'videos');
		$redirect		= CVideosHelper::getVideoReturnUrlFromRequest();

		// Process according to video creator type
		if(!empty($groupid))
		{
			CFactory::load( 'helpers' , 'group' );
			$allowManageVideos	= CGroupHelper::allowManageVideo($groupid);
			$creatorType		= VIDEO_GROUP_TYPE;
			$videoLimit			= $config->get( 'groupvideouploadlimit' );
			CError::assert($allowManageVideos, '', '!empty', __FILE__ , __LINE__ );
		} else {
			$creatorType		= VIDEO_USER_TYPE;
			$videoLimit			= $config->get('videouploadlimit');
		}
		
		// Check is video upload is permitted
		CFactory::load('helpers' , 'limits' );
		if(CLimitsHelper::exceededVideoUpload($my->id, $creatorType))
		{
			$message		= JText::sprintf('CC VIDEOS CREATION REACH LIMIT', $videoLimit);
			$mainframe->redirect( $redirect , $message );
			exit;
		}
		if (!$config->get('enablevideos'))
		{
			$mainframe->enqueueMessage(JText::_('CC VIDEO DISABLED', 'notice'));
			$mainframe->redirect(CRoute::_('index.php?option=com_community&view=frontpage', false));
			exit;
		}
		if (!$config->get('enablevideosupload'))
		{
			$mainframe->enqueueMessage(JText::_('CC VIDEO UPLOAD DISABLED', 'notice'));
			$mainframe->redirect(CRoute::_('index.php?option=com_community&view=videos', false));
			exit;
		}
		
		// Check if the video file is valid
		$files		= JRequest::get('files');
		$videoFile	= !empty($files['videoFile']) ? $files['videoFile'] : array();
		if (empty($files) || (empty($videoFile['name']) && $videoFile['size'] < 1))
		{
			$mainframe->enqueueMessage(JText::_('CC VIDEO UPLOAD ERROR', 'error'));
			$mainframe->redirect($redirect, false);
			exit;
		}
		
		// Check if the video file exceeds file size limit
		$uploadLimit	= $config->get('maxvideouploadsize');
		$videoFileSize	= sprintf("%u", filesize($videoFile['tmp_name']));
		if( $videoFileSize > ( $uploadLimit * 1024 * 1024 ) )
		{
			$mainframe->redirect($redirect, JText::sprintf('CC VIDEO FILE SIZE EXCEEDED', $uploadLimit));
		}
		
		// Passed all checking, attempt to save the video file
		CFactory::load('helpers', 'file');
		$folderPath		= CVideoLibrary::getPath($my->id, 'original');
		$randomFileName	= CFileHelper::getRandomFilename( $folderPath , $videoFile['name'] , '' );
		$destination	= JPATH::clean($folderPath . DS . $randomFileName);
		
		if( !CFileHelper::upload( $videoFile , $destination ) )
		{
			$mainframe->enqueueMessage(JText::_('CC VIDEO UPLOAD FAILED', 'error'));
			$mainframe->redirect($redirect, false);
			exit;
		}
		
		$config	= CFactory::getConfig();
		$videofolder = $config->get('videofolder');
		
		CFactory::load( 'models' , 'videos' );
		$video	= JTable::getInstance( 'Video' , 'CTable' );
		$video->set('path',			$videofolder. '/originalvideos/' . $my->id . '/' . $randomFileName);
		$video->set('title',		JRequest::getVar('title'));
		$video->set('description',	JRequest::getVar('description'));
		$video->set('category_id',	JRequest::getInt('category', 0, 'post'));
		$video->set('permissions',	JRequest::getInt('privacy', 0, 'post'));
		$video->set('creator',		$my->id);
		$video->set('creator_type',	$creatorType);
		$video->set('groupid',		$groupid);
		$video->set('filesize',		$videoFileSize);
		
		if (!$video->store())
		{
			$mainframe->enqueueMessage(JText::_('CC VIDEO SAVE ERROR', 'error'));
			$mainframe->redirect($redirect, false);
			exit;
		}
		
		// Video saved, redirect
		$redirect	= CVideosHelper::getVideoReturnUrlFromRequest('pending');
		$mainframe->redirect($redirect, JText::sprintf('CC VIDEO UPLOAD SUCCESS', $video->title));
	}
	
	/**
	 * Displays the video
	 *	 
	 **/
	public function video()
	{
		$this->checkVideoAccess();
		$document 	= JFactory::getDocument();
		$viewType	= $document->getType();	
		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		= $this->getView( $viewName , '' , $viewType );
		echo $view->get( __FUNCTION__ );
	}

	/**
	 * Controller method to remove a video
	 **/
	public function removevideo()
	{
		$videoId	= JRequest::getVar( 'videoid' , '' , 'POST' );
		$message	=  $this->deleteVideo( $videoId );
		$mainframe	= JFactory::getApplication();
		$url		= CRoute::_('index.php?option=com_community&view=videos' , false );
		
		if($message )
		{
			// Remove from activity stream
			CFactory::load ( 'libraries', 'activities' );
			CActivityStream::remove('videos', $videoId);
			
			$mainframe->redirect( $url , $message );
		}
		else
		{
			$message	= JText::_('CC ERROR DELETING VIDEO');
			$mainframe->redirect( $url , $message );
		}
	}

	/**
	 * Controller method to remove a video
	 **/
	public function deleteVideo( $videoId=0 , $redirect = true )
	{
		if ($this->blockUnregister())
			return;

		// Load libraries
		CFactory::load( 'models' , 'videos' );
		$video		= JTable::getInstance( 'Video' , 'CTable' );
		$mainframe	= JFactory::getApplication();
		$video->load( (int)$videoId );
		
		if(!empty($video->groupid))
		{
			CFactory::load( 'helpers' , 'group' );
			$allowManageVideos = CGroupHelper::allowManageVideo($video->groupid);
			CError::assert($allowManageVideos, '', '!empty', __FILE__ , __LINE__ );
		}
		
		// @rule: Add point when user removes a video
		CFactory::load( 'libraries' , 'userpoints' );
		CUserPoints::assignPoint('video.remove', $video->creator);

		if($video->delete())
		{
			// Delete all videos related data
			$this->_deleteVideoWalls($video->id);
			$this->_deleteVideoActivities($video->id);
			$this->_deleteFeaturedVideos($video->id);
			$this->_deleteVideoFiles($video);
			$this->_deleteProfileVideo($video->creator, $video->id);
			
			if(!empty($video->groupid))
			{
				$message		= JText::sprintf('CC VIDEO REMOVED', $video->title);
				$redirect		= CRoute::_('index.php?option=com_community&view=videos&groupid=' . $video->groupid , false );
			}
			else
			{
				$message		= JText::sprintf('CC VIDEO REMOVED', $video->title);
				$redirect		= CRoute::_('index.php?option=com_community&view=videos' , false );
			}			
			
		}
		
		if( $redirect === true )
			$mainframe->redirect($redirect, $message);
			
		return $message;
	}
	
	public function saveVideo()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
		
		if ($this->blockUnregister()) return;
		
		$my			= CFactory::getUser();
		$postData	= JRequest::get('post');
		$mainframe	= JFactory::getApplication();
		
		CFactory::load('models', 'videos');
		$video		= JTable::getInstance('Video', 'CTable');
		$video->load($postData['id']);
		
		CFactory::load('helpers', 'videos');
		$redirect		= CVideosHelper::getVideoReturnUrlFromRequest();

		if (! ($video->bind($postData)  && $video->store()) )
		{
			$message	= JText::_('CC SAVE VIDEO FAILED', 'error');
			$mainframe->redirect($redirect , $message);
		}
		
		$message		= JText::sprintf('CC VIDEO SAVED', $video->title);
		$mainframe->redirect($redirect, $message);
	}
	
	public function ajaxFetchThumbnail($id)
	{
		if (!COwnerHelper::isRegisteredUser()) return $this->ajaxBlockUnregister();
		
		$thumbnail	= $this->_fetchThumbnail($id, true);
		if (!$thumbnail)
		{
			$content	= $this->getError() ? $this->getError(): '<div>Failed to fetch video thumbnail.</div>';
		}
		else
		{
			$content	= '<div>' . JText::_('CC VIDEO THUMB FETCHED SUCCESS') . '</div>';
			$content	.= '<div style="text-align:center"><img style="border: 1px solid rgb(204, 204, 204); padding: 2px;" src="' . $thumbnail . '"/></div>';
		}
		
		$response	= new JAXResponse();
		$buttons	= '<input type="button" class="button" onclick="cWindowHide()" value="' . JText::_('CC BUTTON DONE') . '">';
		$response->addAssign('cWindowContent' , 'innerHTML' , $content);
		$response->addScriptCall('cWindowActions', $buttons);
		$response->addScriptCall('joms.jQuery("#cwin_logo").html("' . JText::_('CC FETCH VIDEO THUMBNAIL') . '");');
		return $response->sendResponse();
	}
	
	/**
	 * Ajax function to save a new wall entry
	 *
	 * @param message	A message that is submitted by the user
	 * @param uniqueId	The unique id for this group
	 *
	 **/
	public function ajaxSaveWall( $message , $uniqueId )
	{
		if (!COwnerHelper::isRegisteredUser()) return $this->ajaxBlockUnregister();
		
		$response		= new JAXResponse();
		$my				= CFactory::getUser();
		
		$video			= JTable::getInstance( 'Video' , 'CTable' );
		$video->load( $uniqueId );

		// If the content is false, the message might be empty.
		if( empty( $message) )
		{
			$response->addAlert( JText::_('CC EMPTY WALL MESSAGE') );
		}
		else
		{
			CFactory::load( 'libraries' , 'wall' );
			$wall	= CWallLibrary::saveWall( $uniqueId , $message , 'videos' , $my , ( $my->id == $video->creator ) );
			
			// Add activity logging
			if ($video->isPublic())
			{
				$url	= $video->getViewUri(false);
				
				$params = new JParameter('');
				$params->set('videoid', $uniqueId);
				$params->set('action', 'wall');
				$params->set('wallid', $wall->id);
				$params->set( 'video_url', $url );
				
				$act = new stdClass();
				$act->cmd 		= 'videos.wall.create';
				$act->actor		= $my->id;
				$act->target	= 0;
				$act->title		= JText::sprintf('CC ACTIVITIES WALL POST VIDEO' , '{video_url}' , $video->title );
				$act->app		= 'videos';
				$act->cid		= $uniqueId;
				$act->params	= $params->toString();
				
				CFactory::load ( 'libraries', 'activities' );
				CActivityStream::add( $act );
			}
			
			//add user points
			CFactory::load( 'libraries' , 'userpoints' );
			CUserPoints::assignPoint('videos.wall.create');
			$response->addScriptCall( 'joms.walls.insert' , $wall->content );
		}
		return $response->sendResponse();
	}
	
	public function ajaxRemoveWall( $wallId )
	{
		CFactory::load( 'helpers' , 'owner' );
		
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		
		// Only allow wall removal by admin or owner of the video.
		$response	= new JAXResponse();
		$wallsModel	= $this->getModel( 'wall' );
		$wall		= $wallsModel->get( $wallId );
		$video		= & JTable::getInstance( 'Video' , 'CTable' );
		$video->load( $wall->contentid );
		$my			= CFactory::getUser();
		
		if( COwnerHelper::isCommunityAdmin() || ($my->id == $video->creator ) )
		{
			if( $wallsModel->deletePost( $wallId ) )
			{
				$response->addAlert( JText::_('CC WALL REMOVED') );

				if($wall->post_by != 0)
				{
					CFactory::load( 'libraries' , 'userpoints' );
					CUserPoints::assignPoint('wall.remove', $wall->post_by);
				}
			}
			else
			{
				$response->addAlert( JText::_('CC CANNOT REMOVE WALL') );
			}
		}
		else
		{
			$response->addAlert( JText::_('CC CANNOT REMOVE WALL') );
		}
		return $response->sendResponse();
	}
	
	/**
	 * Ajax method to display remove a video notice
	 *
	 * @param $id	Video id
	 **/
	public function ajaxRemoveVideo( $id , $currentTask )
	{
		if (!COwnerHelper::isRegisteredUser()) return $this->ajaxBlockUnregister();
		
		$response	= new JAXResponse();
		// Load models / libraries
		CFactory::load( 'models' , 'videos' );
		$my			= CFactory::getUser();
		$video		= JTable::getInstance( 'Video' , 'CTable' );
		$video->load( $id );
		$content	= '<div>' . JText::sprintf('CC CONFIRM REMOVE VIDEO', $video->title) . '</div>';
		$buttons	= '<form name="jsform-videos-ajaxremovevideo" method="post" action="' . CRoute::_('index.php?option=com_community&view=videos&task=removevideo') . '">'
					. '<input type="submit" class="button" name="submit" value="' . JText::_('CC BUTTON YES') . '">'
					. '<input type="hidden" name="videoid" value="' . $video->id . '">'
					. '<input type="hidden" name="currentTask" value="' . $currentTask . '">'
					. '<input type="button" class="button" onclick="cWindowHide()" value="' . JText::_('CC BUTTON NO') . '">'
					. '</form>';
		$response->addAssign('cWindowContent' , 'innerHTML' , $content);
		$response->addScriptCall('cWindowActions', $buttons);
		$response->addScriptCall('joms.jQuery("#cwin_logo").html("' . JText::_('CC DELETE VIDEO') . '");');
		return $response->sendResponse();
	}
	
	public function ajaxEditVideo($videoId , $redirectUrl = '' )
	{
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		$objResponse = new JAXResponse(); 
		
		CFactory::load( 'models' , 'videos' );	
		$video		= JTable::getInstance( 'Video' , 'CTable' );
		$my			= CFactory::getUser();
		
		$video->load( $videoId );

		$group = JTable::getInstance('Group', 'CTable');
		// Load the group, based on video's groupid, NOT the url
		$group->load($video->groupid);
		
		if( $video->creator == $my->id || $group->isAdmin($my->id) )
		{
			$model		= CFactory::getModel('videos');
			$category	= $model->getAllCategories();
			
			for( $i = 0; $i < count( $category ); $i++ )
			{
				$category[ $i ]->name	= JText::_( $category[$i]->name );
			}
			
			$categoryHTML	= JHTML::_('select.genericlist', $category, 'category_id', 'class="inputbox required"', 'id', 'name', $video->category_id, 'category_id');
			$showPrivacy	= $video->groupid != 0 ? false : true;
			$cWindowsHeight	= $video->groupid != 0 ? 350 : 480;
			
			$redirectUrl	= empty($redirectUrl) ? '' : base64_encode( $redirectUrl );
	
			$tmpl		= new CTemplate();
			$tmpl->set( 'video'			, $video );
			$tmpl->set( 'showPrivacy'	, $showPrivacy );
			$tmpl->set( 'categoryHTML'	, $categoryHTML );
			$tmpl->set( 'redirectUrl'	, $redirectUrl );
			$contents	= $tmpl->fetch( 'videos.edit' );
			
			// Change cWindow title
			$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC EDIT VIDEO'));
			$objResponse->addAssign('cWindowContent' , 'innerHTML' , $contents );
			$objResponse->addScriptCall('cWindowResize', $cWindowsHeight);
			$action = '<input type="button" onclick="document.editvideo.submit();" class="button" name="' . JText::_('Save') . '" value="' . JText::_('Save') . '" />';
			$action .= '&nbsp;<input type="button" class="button" onclick="cWindowHide();" name="' . JText::_('Close') . '" value="' . JText::_('Cancel') . '" />';
			$objResponse->addScriptCall( 'cWindowActions' , $action );
		}
		else
		{
			$objResponse->addScriptCall( 'cWindowHide');
			$objResponse->addScriptCall( 'alert', JText::_('CC NOT ALLOWED TO ACCESS SECTION' ) );
			
		}
		return $objResponse->sendResponse();
	}
	
	public function ajaxAddVideo($creatorType=VIDEO_USER_TYPE, $groupid=0)
	{
		if (!COwnerHelper::isRegisteredUser()) return $this->ajaxBlockUnregister();
		
		if($creatorType != VIDEO_GROUP_TYPE)
		{
			$groupid = 0;
		}
		
		$objResponse = new JAXResponse(); 
		
		CFactory::load('libraries', 'videos');
		CFactory::load('helpers', 'videos');
		$videoLib 	= new CVideoLibrary;
		
		$config		= CFactory::getConfig();
		
		$tmpl 		= new CTemplate();
		$tmpl->set( 'enableVideoUpload' , $config->get('enablevideosupload') );
		
		$uploadLimit	= $config->get('maxvideouploadsize', ini_get('upload_max_filesize'));
		$tmpl->set( 'uploadLimit', $uploadLimit );
		$tmpl->set( 'creatorType', $creatorType );
		$tmpl->set( 'groupid', $groupid );
		$html = $tmpl->fetch('videos.add');

		//$objResponse->addScriptCall('cWindowResize2', 500, 300);
		$objResponse->addScriptCall('cWindowResize', 300);
		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC ADD VIDEO'));
		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);

		return $objResponse->sendResponse();
	}
	
	public function ajaxLinkVideo($creatorType=VIDEO_USER_TYPE, $groupid=0)
	{
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}
		$objResponse = new JAXResponse();
		
		$model		= CFactory::getModel('videos');
		$category	= $model->getAllCategories();
		
		for( $i = 0; $i < count( $category ); $i++ )
		{
			$category[ $i ]->name	= JText::_( $category[$i]->name );
		}
		
		$list['category']	= JHTML::_('select.genericlist', $category, 'category', 'class="inputbox"', 'id', 'name', null, 'category');
		
		$model			= CFactory::getModel( 'videos' );
		$my				= CFactory::getUser();
		$config			= CFactory::getConfig();
		$totalVideos	= 0;
		$videoUploadLimit	= 0;
		
		if($creatorType != VIDEO_GROUP_TYPE)
		{
			$groupid		= 0;
			$totalVideos	= $model->getVideosCount( $my->id , VIDEO_USER_TYPE );
			$videoUploadLimit	= $config->get('videouploadlimit');
			$cWindowHeight	= 255;
		} else {
			$totalVideos	= $model->getVideosCount( $my->id , VIDEO_GROUP_TYPE );
			$videoUploadLimit	= $config->get('groupvideouploadlimit');
			$cWindowHeight = 175;
		}
		
		$tmpl		= new CTemplate();
		
		$tmpl->set( 'list',				$list );
		$tmpl->set( 'creatorType',		$creatorType );
		$tmpl->set( 'groupid',			$groupid );
		$tmpl->set( 'videoUploaded',	$totalVideos );
		$tmpl->set( 'videoUploadLimit',	$videoUploadLimit );	
		
		$html		= $tmpl->fetch('videos.link');
		
		$action		= '<button class="button" onclick="joms.videos.submitLinkVideo();">' . JText::_('CC LINK VIDEO') . '</button>';
		
		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC LINK VIDEO'));
		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);
		$objResponse->addScriptCall( 'cWindowActions' , $action );
		$objResponse->addScriptCall('cWindowResize', $cWindowHeight);		
		
		return $objResponse->sendResponse();
	}
	
	public function ajaxUploadVideo($creatorType=VIDEO_USER_TYPE, $groupid=0)
	{
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}
		$objResponse = new JAXResponse();
		
		$model		= CFactory::getModel('videos');
		$category	= $model->getAllCategories();
		for( $i = 0; $i < count( $category ); $i++ )
		{
			$category[ $i ]->name	= JText::_( $category[$i]->name );
		}
		$list['category']	= JHTML::_('select.genericlist', $category, 'category', 'class="inputbox"', 'id', 'name', null, 'category');
		
		$my				= CFactory::getUser();
		$config			= CFactory::getConfig();
		$uploadLimit	= $config->get('maxvideouploadsize', ini_get('upload_max_filesize'));
		$totalVideos	= 0;
		$videoUploadLimit	=0;
		
		if($creatorType != VIDEO_GROUP_TYPE)
		{
			$groupid		= 0;
			$totalVideos	= $model->getVideosCount( $my->id , VIDEO_USER_TYPE );
			$videoUploadLimit	= $config->get('videouploadlimit');
			$cWindowHeight	= 450;
		} else {
			$totalVideos	= $model->getVideosCount( $my->id , VIDEO_GROUP_TYPE );
			$videoUploadLimit	= $config->get('groupvideouploadlimit');
			$cWindowHeight = 370;
		}
		
		$tmpl	= new CTemplate();
		$tmpl->set( 'list', $list );
		$tmpl->set( 'uploadLimit',		$uploadLimit );
		$tmpl->set( 'creatorType',		$creatorType );
		$tmpl->set( 'groupid',			$groupid );
		$tmpl->set( 'videoUploaded',	$totalVideos );
		$tmpl->set( 'videoUploadLimit',	$videoUploadLimit );
		
		$html	= $tmpl->fetch('videos.upload');
		
		$action	= '<button class="button" onclick="joms.videos.submitUploadVideo();">' . JText::_('CC UPLOAD VIDEO') . '</button>';
	
		$objResponse->addAssign('cwin_logo', 'innerHTML', JText::_('CC UPLOAD VIDEO'));		
		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);
		$objResponse->addScriptCall( 'cWindowActions' , $action );
		$objResponse->addScriptCall('cWindowResize', $cWindowHeight);

		return $objResponse->sendResponse();		
	}
	
	/**
	 * Display searching form for videos
	 **/
	public function search()
	{
		$this->checkVideoAccess();
		$document	= JFactory::getDocument();
		$viewType	= $document->getType();	
		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		= $this->getView( $viewName , '' , $viewType );
		
		echo $view->get( __FUNCTION__ );
	}
	
	private function _fetchThumbnail($id=0, $returnThumb=false)
	{
		if (!COwnerHelper::isRegisteredUser()) return;
		if (!$id) return false;
		
		CFactory::load('models', 'videos'); 
		$table = JTable::getInstance( 'Video' , 'CTable' );
		$table->load($id);
		
		CFactory::load('helpers', 'videos');
		CFactory::load('libraries', 'videos');
		$config	= CFactory::getConfig();
		
		if ($table->type=='file')
		{
			// We can only recreate the thumbnail for local video file only
			// it's not possible to process remote video file with ffmpeg
			if ($table->storage != 'file')
			{
				$this->setError(JText::_('CC INVALID FILE REQUEST') . ': ' . 'FFmpeg cannot process remote video.');
				return false;
			}
			
			$videoLib	= new CVideoLibrary();
			
			$videoFullPath	= JPATH::clean(JPATH_ROOT.DS.$table->path);
			if (!JFile::exists($videoFullPath))
			{
				return false;
			}

			// Read duration
			$videoInfo	= $videoLib->getVideoInfo($videoFullPath);

			if (!$videoInfo)
			{
				return false;
			}
			else
			{
				$videoFrame = CVideosHelper::formatDuration( (int) ($videoInfo['duration']['sec'] / 2), 'HH:MM:SS' );
				
				// Create thumbnail
				$oldThumb		= $table->thumb;
				$thumbFolder	= CVideoLibrary::getPath($table->creator, 'thumb');
				$thumbSize		= CVideoLibrary::thumbSize();
				$thumbFilename	= $videoLib->createVideoThumb($videoFullPath, $thumbFolder, $videoFrame, $thumbSize);
			}
			
			if (!$thumbFilename)
			{
				return false;
			}
		}
		else
		{
			CFactory::load('helpers', 'remote' );
			if (!CRemoteHelper::curlExists())
			{
				$this->setError(JText::_('CC CURL NOT EXISTS'));
				return false;
			}
			
			$videoLib 	= new CVideoLibrary();
			$videoObj 	= $videoLib->getProvider($table->path);
			if ($videoObj==false)
			{
				$this->setError($videoLib->getError());
				return false;
			}
			if (!$videoObj->isValid())
			{
				$this->setError($videoObj->getError());
				return false;
			}
			
			$remoteThumb	= $videoObj->getThumbnail();
			$thumbData		= CRemoteHelper::getContent($remoteThumb , true );
			
			if (empty($thumbData))
			{
				$this->setError(JText::_('CC INVALID FILE REQUEST') . ': ' . $remoteThumb);
				return false;
			}
			preg_match( '/Content-Type: image\/(.*)/i' , $thumbData , $matches );
			
			if( !empty( $matches) )
			{
				CFactory::load('helpers', 'file' );
				CFactory::load('helpers', 'image');
				
				// split the header and body
				list( $headers , $body )	= explode( "\r\n\r\n" , $thumbData , 2 );
				$thumbData	= $body;
				
				$thumbPath		= CVideoLibrary::getPath($table->creator, 'thumb');
				$thumbFileName	=  CFileHelper::getRandomFilename($thumbPath);
				$tmpThumbPath	= $thumbPath . DS . $thumbFileName;
				if (!JFile::write($tmpThumbPath, $thumbData))
				{
					$this->setError(JText::_('CC INVALID FILE REQUEST') . ': ' . $thumbFileName);
					return false;
				}
				
				// We'll remove the old or none working thumbnail after this
				$oldThumb	= $table->thumb;
				
				// Get the image type first so we can determine what extensions to use
				$info		= getimagesize( $tmpThumbPath );
				$mime		= image_type_to_mime_type( $info[2]);
				$thumbExtension	= CImageHelper::getExtension( $mime );
				
				$thumbFilename	= $thumbFileName . $thumbExtension;
				$thumbPath	= $thumbPath . DS . $thumbFilename;
				if(!JFile::move($tmpThumbPath, $thumbPath))
				{
					$this->setError(JText::_('WARNFS_ERR02') . ': ' . $thumbFileName);
					return false;
				}
				
				// Resize the thumbnails
				CImageHelper::resizeProportional( $thumbPath , $thumbPath , $mime , CVideoLibrary::thumbSize('width') , CVideoLibrary::thumbSize('height') );
			}
			else
			{
				$this->setError(JText::_('CC IMAGE NOT PROVIDED'));
				return false;
			}
		}
		
		// Update the DB with new thumbnail
		$thumb	= $config->get('videofolder') . '/'
				. VIDEO_FOLDER_NAME . '/'
				. $table->creator . '/'
				. VIDEO_THUMB_FOLDER_NAME . '/'
				. $thumbFilename;
		
		$table->set('thumb', $thumb);
		$table->store();
		
		// If this video storage is not on local, we move it to remote storage
		// and remove the old thumb if existed
		if (($table->storage != 'file')) // && ($table->storage == $storageType))
		{
			$config			= CFactory::getConfig();
			$storageType	= $config->getString('videostorage');
			CFactory::load('libraries', 'storage');
			$storage		= CStorage::getStorage($storageType);
			$storage->delete($oldThumb);
			
			$localThumb		= JPATH::clean(JPATH_ROOT.DS.$table->thumb);
			$tempThumbname	= JPATH::clean(JPATH_ROOT.DS.md5($table->thumb));
			if (JFile::exists($localThumb))
			{
				JFile::copy($localThumb, $tempThumbname);
			}
			if (JFile::exists($tempThumbname))
			{
				$storage->put($table->thumb, $tempThumbname);
				JFile::delete($localThumb);
				JFile::delete($tempThumbname);
			}
		} else {
			if (JFile::exists(JPATH_ROOT.DS.$oldThumb))
			{
				JFile::delete(JPATH_ROOT.DS.$oldThumb);
			}
		}
		
		
		if ($returnThumb)
		{
			return $table->getThumbnail();
		}
		return true;
	}
	
	/**
	 * Delete video's wall
	 * 
	 * @param	int		$id		The id of the video
	 * @return	True on success
	 * @since	1.2
	 **/
	private function _deleteVideoWalls($id = 0)
	{
		CFactory::load('helpers', 'owner');
		
		if (!COwnerHelper::isRegisteredUser()) return;
		$video	= CFactory::getModel( 'Videos' );
		$video->deleteVideoWalls($id);
	}
	
	/**
	 * Delete video's activity stream
	 * 
	 * @params	int		$id		The video id
	 * @return	True on success
	 * @since	1.2
	 * 
	 **/
	private function _deleteVideoActivities($id = 0)
	{
		if (!COwnerHelper::isRegisteredUser()) return;
		$video	= CFactory::getModel( 'Videos' );
		$video->deleteVideoActivities($id);
	}
	
	/**
	 * Delete video's files and thumbnails
	 * 
	 * @params	object	$video	The video object
	 * @return	True on success
	 * @since	1.2
	 * 
	 **/
	private function _deleteVideoFiles($video)
	// We passed in the video object because of 
	// the table row of $video->id coud be deleted
	// thus, there's no way to retrive the thumbnail path
	// and also the flv file path
	{
		if (!$video) return;
		if (!COwnerHelper::isRegisteredUser()) return;
		
		CFactory::load('libraries', 'storage');
		$storage = CStorage::getStorage($video->storage);
		
		if ($storage->exists($video->thumb))
		{
			$storage->delete($video->thumb);
		}
		
		if ($storage->exists($video->path))
		{
			$storage->delete($video->path);
		}
		/*
		jimport('joomla.filesystem.file');
		$files		= array();
		
		$thumb	= JPATH::clean(JPATH_ROOT . DS . $video->thumb);
		if (JFile::exists( $thumb ))
		{
			$files[]= $thumb;
		}

		if ($video->type == 'file')
		{
			$flv	= JPATH::clean(JPATH_ROOT . DS . $video->path);
			if (JFile::exists($flv))
			{
				$files[]= $flv;
			}
		}

		if (!empty($files))
		{
			return JFile::delete($files);
		}
		*/
		
		return true;
	}
	
	/**
	 * Delete featured videos
	 * 
	 * @param	int		$id		The id of the video
	 * @return	True on success
	 * @since	1.2
	 **/
	private function _deleteFeaturedVideos($id = 0)
	{
		if (!COwnerHelper::isRegisteredUser()) return;
		
		CFactory::load('libraries','featured');
		$featuredVideo	= new CFeatured(FEATURED_VIDEOS);
		$featuredVideo->delete($id);
		
		return;
	}

	/**
	 * Delete profile video
	 *
	 * @param	int		$creator		The id of the video creator
	 * @return	True on success or unsuccess
	 **/
	private function _deleteProfileVideo($creator, $deletedvideoid)
	{
		if (!COwnerHelper::isRegisteredUser()) return;

		// Only the video creator can use the video as his/her profile video
		$user	=	CFactory::getUser($creator);

		// Set params to default(0 for no profile video)
		$params		=	$user->getParams();

		$videoid    = $params->get('profileVideo', 0);

		// Check if the current profile video id same with the deleted video id
		if($videoid == $deletedvideoid){
			$params->set('profileVideo', 0);
			$user->save('params');
		}

		return;
	}
	
	public function streamer()
	{
		CFactory::load('models', 'videos');
		$table		= JTable::getInstance( 'Video' , 'CTable' );
		if (!$table->load( JRequest::getVar('vid') ))
		{
			$this->setError($table->getError());
			return false;
		}
		
		$pos	= JRequest::getInt('start', 0);
		
		$file		= JString::str_ireplace('/' , '\\', JPATH_ROOT . DS . $table->path);
		if(!JFile::exists($file)) return 'video file not found.';
		
		$fileName	= JFile::getName($file);
		$fileSize	= filesize($file) - (($pos > 0) ? $pos + 1 : 0);
		
		$fh		= fopen($file, 'rb') or die ('cannot open file: ' . $file);
		$fileSize = filesize($file) - (($pos > 0) ? $pos  + 1 : 0);
		fseek($fh, $pos);
		
		$binary_header	= strtoupper(JFile::getExt($file)).pack('C', 1).pack('C', 1).pack('N', 9).pack('N', 9);
		
		session_cache_limiter('none');
		JResponse::clearHeaders();
		JResponse::setHeader( 'Expires', 'Mon, 26 Jul 1997 05:00:00 GMT', true );
		JResponse::setHeader( 'Last-Modified', gmdate("D, d M Y H:i:s") . ' GMT', true );
		JResponse::setHeader( 'Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true );
		JResponse::setHeader( 'Pragma', 'no-cache', true );
		JResponse::setHeader( 'Content-Disposition', 'attachment; filename="'.$fileName.'"', true);
		JResponse::setHeader( 'Content-Length', ($pos > 0) ? $fileSize + 13 : $fileSize, true );
		JResponse::setHeader( 'Content-Type', 'video/x-flv', true );
		JResponse::sendHeaders();
		
		if($pos > 0) 
		{
			print $binary_header;
		}
		
		$limit_bw		= true;
		$packet_size	= 90 * 1024;
		$packet_interval= 0.3;
		
		while(!feof($fh)) 
		{
			if(!$limit_bw)
			{
				print(fread($fh, filesize($file)));
			}
			else
			{
				$time_start = microtime(true);
				print(fread($fh, $packet_size));
				$time_stop = microtime(true);
				$time_difference = $time_stop - $time_start;
				if($time_difference < $packet_interval)
				{
					usleep($packet_interval * 1000000 - $time_difference * 1000000);
				}
			}
		}
		
		exit;
	}
}
