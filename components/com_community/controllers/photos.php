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

class CommunityPhotosController extends CommunityBaseController
{
	public function editPhotoWall( $wallId )
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
	
	public function ajaxSaveOrdering( $ordering , $albumId )
	{
		$my		= CFactory::getUser();

		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		
		CFactory::load( 'models' , 'photos' );
		$album			=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );

		$objResponse	= new JAXResponse();
		
		if( $my->id != $album->creator )
		{
			$objResponse->addScriptCall('alert', JText::_('CC ACCESS DENIED' ) );
			return $objResponse->sendResponse();
		}

		$model			= CFactory::getModel( 'photos' );
		$ordering		= explode( '&', $ordering );
		$i 				= 0;
		$photos			= array();
		
		for( $i = 0; $i < count( $ordering ); $i++ )
		{
			$data		= explode( '=' , $ordering[ $i ] );
			$photos[ $data[ 1 ] ] 	= $i;
		}

		$model->setOrdering( $photos , $albumId );
		$objResponse->addScriptCall('void', 0);

		return $objResponse->sendResponse();
	}
	
	// Deprecated since 1.8.x
	public function jsonupload()
	{
		$this->upload();
	}
	
	private function _outputJSONText( $hasError , $text , $thumbUrl=null )
	{
		$nextUpload	= JRequest::getVar('nextupload' , '' , 'GET');
		
		echo "{\n";

		if( $hasError )
		{
			echo "error: 'true',\n";
		}

		echo "msg: '" . $text . "'\n,";
		echo "nextupload: '" . $nextUpload . "'\n,";
		echo "thumbUrl: '" . $thumbUrl . "'\n";
		echo "}";
		exit;
	}
	
	private function _showUploadError( $hasError , $message , $thumbUrl = null )
	{
		$config	=& CFactory::getConfig();
		
		if( $config->get('flashuploader') )
		{
			echo $thumbUrl;
			exit;
		}
		else
		{
			$this->_outputJSONText( $hasError , $message , $thumbUrl );
		}
	}
	
	private function _addActivity( $command, $actor , $target , $title , $content , $app , $cid, $param='')
	{
		$act = new stdClass();
		$act->cmd 		= $command;
		$act->actor   	= $actor;
		$act->target  	= $target;
		$act->title	  	= $title;
		$act->content	= $content;
		$act->app		= $app;
		$act->cid		= $cid;
		
		// Add activity logging
		CFactory::load ( 'libraries', 'activities' );
		CActivityStream::add( $act, $param );
	}
	
	/**
	 * Method to save new album or existing album
	 **/	 	
	private function _saveAlbum( $id = null )
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
		
		// @rule: Only registered users are allowed to create groups.
		if ($this->blockUnregister())
		{
			return;
		}
		
		$my			= CFactory::getUser();
		$type		=& JRequest::getVar( 'type' , PHOTOS_USER_TYPE ,'REQUEST' );
		$mainframe	=& JFactory::getApplication();
		$config 	= CFactory::getConfig();

		// Load models, libraries
		CFactory::load( 'models' , 'photos' );
		CFactory::load( 'helpers' , 'url' );
		
		$postData	= JRequest::get('POST');
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $id );
		$handler	= $this->_getHandler( $album );
		$handler->bindAlbum( $album , $postData );

		// @rule: New album should not have any id's.
		if( is_null( $id ) )
		{
			$album->creator			= $my->id;
		}
		
		$album->created			= gmdate('Y-m-d H:i:s');
		$album->type			= $handler->getType();

		//@todo: add permissions
		$params					= $my->getParams();
		$album->permissions		= $params->get('privacyPhotoView');

		$albumPath		= $handler->getAlbumPath( $album->id );
		$albumPath		= JString::str_ireplace( JPATH_ROOT . DS , '' , $albumPath );		
		$albumPath		= JString::str_ireplace( '\\' , '/' , $albumPath );
		$album->path	= $albumPath;
		
		$album->store();		
		
		return $album;
	}
	
	private function _storeOriginal($tmpPath, $destPath, $albumId = 0)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.utilities.utility');
		
		// First we try to get the user object.
		$my			= CFactory::getUser();
		
		// Then test if the user id is still 0 as this might be
		// caused by the flash uploader.
		if( $my->id == 0 )
		{
			$tokenId	= JRequest::getVar( 'token' , '' , 'REQUEST' );
			$userId		= JRequest::getVar( 'userid' , '' , 'REQUEST' );

			$my			= CFactory::getUserFromTokenId( $tokenId , $userId );
		}
		$config = CFactory::getConfig(); 
		 
		// @todo: We assume now that the config is using the relative path to the
		// default images folder in Joomla.
		// @todo:  this folder creation should really be in its own function
		$albumPath			= ($albumId == 0) ? '' : DS . $albumId;
		$originalPathFolder	= JPATH_ROOT . DS . $config->getString('photofolder') . DS . JPath::clean( $config->get('originalphotopath') );
		$originalPathFolder	= $originalPathFolder . DS . $my->id . $albumPath;
		
		if( !JFile::exists( $originalPathFolder ) )
		{
			JFolder::create( $originalPathFolder, (int) octdec( $config->get('folderpermissionsphoto') ) );
		}

		if(!JFile::copy( $tmpPath, $destPath ) )
		{
			JError::raiseWarning(21, JText::sprintf('CC ERROR MOVING UPLOADED FILE' , $destPath));
		}
	}

	/**
	 * Allows user to link to the current photo as their profile picture
	 **/
	public function ajaxLinkToProfile( $photoId )
	{
		$response	= new JAXResponse();

		$header		=	JText::_('CC EDIT AVATAR');
		$message	= '<form name="change-profile-picture" id="change-profile-picture" method="post" action="' . CRoute::_('index.php?option=com_community&view=profile&task=linkPhoto') . '">'
					. JText::_('CC SET AS PROFILE PICTURE DESCRIPTION')
					. '<input type="hidden" name="id" value="' . $photoId . '" />'
					. '</form>';
		

		// Change cWindow title
		$response->addAssign('cwin_logo', 'innerHTML', $header);
		$response->addAssign('cWindowContent', 'innerHTML', $message);
		$action		=	'<button  class="button" onclick="joms.jQuery(\'#change-profile-picture\').submit()">' . JText::_('CC YES') . '</button>';
		$action		.=	'&nbsp;<button class="button" onclick="cWindowHide();">' . JText::_('CC NO') . '</button>';
		$response->addScriptCall( 'cWindowActions' , $action );

		return $response->sendResponse();
	}
		 	
	public function ajaxAddPhotoTag($photoId, $userId, $posX, $posY, $w, $h)	
	{
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		
		$response	= new JAXResponse();
		
		CFactory::load ( 'libraries', 'phototagging' );
		CFactory::load ( 'models' , 'photos' );
				
		$my			= CFactory::getUser();		
		$photoModel	=& CFactory::getModel('photos');
		$tagging 	= new CPhotoTagging();				

		$tag = new stdClass();
		$tag->photoId	= $photoId;
		$tag->userId	= $userId;
		$tag->posX		= $posX;
		$tag->posY		= $posY;
		$tag->width		= $w;
		$tag->height	= $h;
		
		$tagId	= $tagging->addTag($tag);
		
		$jsonString = '{}';
		if($tagId > 0)
		{
			$user		= CFactory::getUser($userId);
			$isGroup	= $photoModel->isGroupPhoto($photoId);
			$photo		= $photoModel->getPhoto($photoId);
										
			$jsonString = '{'
			 	. 'id:' . $tagId . ','
			 	. 'photoId:' . $photoId . ','
			 	. 'userId:' . $userId . ','			 	
			 	. 'displayName:\'' . $user->getDisplayName() . '\','
			 	. 'profileUrl:\'' . CRoute::_('index.php?option=com_community&view=profile&userid='.$userId, false) . '\','
			 	. 'top:' . $posX . ','
			 	. 'left:' . $posY . ','
			 	. 'width:' . $w . ','
			 	. 'height:' . $h . ','
			 	. 'canRemove:true'
				. '}';
				
			// jQuery call to update photo tagged list.		
			$response->addScriptCall('joms.gallery.createPhotoTag', $jsonString);
			$response->addScriptCall('joms.gallery.createPhotoTextTag', $jsonString);
			$response->addScriptCall('cWindowHide');
			$response->addScriptCall('joms.gallery.cancelNewPhotoTag');
			
			
			//send notification emails
			$albumId		= $photo->albumid;
			$photoCreator	= $photo->creator;
			$url 			= '';			
			$album			=& JTable::getInstance( 'Album' , 'CTable' );
			$album->load( $albumId );
			
			$handler		= $this->_getHandler( $album );
			$url			= $handler->getPhotoURI( $album->id , $photoId , false );

			if($my->id != $userId)
			{
				// Add notification
				CFactory::load( 'libraries' , 'notification' );
				
				$params			= new JParameter( '' );
				$params->set( 'url' , $url );

				CNotificationLibrary::add( 'photos.tagging' , $my->id , $userId , JText::sprintf( 'CC SOMEONE TAG YOU' , $my->getDisplayName() ) , '' , 'photos.tagging' , $params );
			}				 
				 			
		}
		else
		{
			$html	= $tagging->getError();
			$action = '<button class="button" onclick="cWindowHide();joms.gallery.cancelNewPhotoTag();" name="close">' . JText::_('CC BUTTON CLOSE') . '</button>';
			
			//remove the existing cwindow (for friend selection)
			$response->addScriptCall('joms.jQuery(\'#cWindow\').remove();');
			
			//recreate the warning cwindow
			$response->addScriptCall('cWindowShow','','Notice',450,200,'warning');
			$response->addAssign('cWindowContent', 'innerHTML', $html);
 			$response->addScriptCall('cWindowActions', $action);						
		}				
		
		return $response->sendResponse();
	}
	
	public function ajaxRemovePhotoTag($photoId, $userId)
	{
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		
		$my			= CFactory::getUser();
		$response	= new JAXResponse();
		
		if( $my->id != $userId )
		{
			$response->addScriptCall( 'alert' , JText::_('ACCESS FORBIDDEN') );
			return $response->sendResponse();
		}
		
		
		CFactory::load ( 'libraries', 'phototagging' );
		$tagging = new CPhotoTagging();
				
		if(! $tagging->removeTag($photoId, $userId))
		{
			$html	= $tagging->getError();
			$response->addScriptCall('cWindowShow','','Notice',450,200,'warning');
			$response->addAssign('cWindowContent', 'innerHTML', $html);					
		}
		
		return $response->sendResponse();		
	}
	
	public function ajaxDisplayCreator($photoid)
	{
		$response	= new JAXResponse();
		
		// Load the default photo
		CFactory::load('models','photos');
		$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $photoid );
		
		$photoCreator = CFactory::getUser($photo->creator);
		
		$html = JText::sprintf('CC UPLOADED BY', CRoute::_('index.php?option=com_community&view=profile&userid='.$photoCreator->id), $photoCreator->getDisplayName());
		$response->addAssign('uploadedBy', 'innerHTML', $html);
		
		return $response->sendResponse();
	}
	
    public function ajaxRemoveFeatured( $albumId )
    {
    	$objResponse	= new JAXResponse();
    	CFactory::load( 'helpers' , 'owner' );
    	
    	$my			= CFactory::getUser();
		if($my->id == 0)
		{
		   return $this->ajaxBlockUnregister();
		}    	
		
		if( COwnerHelper::isCommunityAdmin() )
    	{
			$model	=& CFactory::getModel('Featured');

    		CFactory::load( 'libraries' , 'featured' );
    		$featured	= new CFeatured( FEATURED_ALBUMS );    		
    		
    		if($featured->delete($albumId))
    		{
    			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC ALBUM REMOVED FROM FEATURED'));	
			}
			else
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC ERROR REMOVING ALBUM FROM FEATURED'));
			}
		}
		else
		{
			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC NOT ALLOWED TO ACCESS SECTION'));
		}
		$buttons   = '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('CC BUTTON CLOSE') . '"/>';
		
		$objResponse->addScriptCall( 'cWindowActions' , $buttons );
		return $objResponse->sendResponse();
	}
	
    public function ajaxAddFeatured( $albumId )
    {
    	$objResponse	= new JAXResponse();
    	CFactory::load( 'helpers' , 'owner' );
    	
    	$my			= CFactory::getUser();
		if($my->id == 0)
		{
		   return $this->ajaxBlockUnregister();
		}    	
		
		if( COwnerHelper::isCommunityAdmin() )
    	{
			$model	=& CFactory::getModel('Featured');
			
			if( !$model->isExists( FEATURED_ALBUMS , $albumId ) )
			{
	    		CFactory::load( 'libraries' , 'featured' );
	    		CFactory::load( 'models' , 'photos');
	    		$featured	= new CFeatured( FEATURED_ALBUMS );
	    		$featured->add( $albumId , $my->id );
	    		
	    		$table		=& JTable::getInstance( 'Album' , 'CTable' );
	    		$table->load( $albumId );

				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::sprintf('CC ALBUM IS FEATURED', $table->name ));
			}
			else
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC ALBUM ALREADY FEATURED'));
			}
		}
		else
		{
			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC NOT ALLOWED TO ACCESS SECTION'));
		}
		$buttons   = '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('CC BUTTON CLOSE') . '"/>';
		
		$objResponse->addScriptCall( 'cWindowActions' , $buttons );
		return $objResponse->sendResponse();
	}
	
	/**
	 * Method is called from the reporting library. Function calls should be
	 * registered here.
	 *
	 * return	String	Message that will be displayed to user upon submission.
	 **/	 	 	
	public function reportPhoto( $link, $message , $id )
	{
		if ($this->blockUnregister()) return;
		
		CFactory::load( 'libraries' , 'reporting' );
		$report = new CReportingLibrary();
		
		// Pass the link and the reported message
		$report->createReport( JText::_('CC BAD PHOTO') ,$link , $message );
		
		// Add the action that needs to be called.
		$action					= new stdClass();
		$action->label			= 'Delete photo';
		$action->method			= 'photos,unpublishPhoto';
		$action->parameters		= $id;
		$action->defaultAction	= true;
		
		$report->addActions( array( $action ) );

		return JText::_('CC REPORT SUBMITTED');
	}

	public function unpublishPhoto( $photoId )
	{
		if ($this->blockUnregister())
			return;
		
		$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $photoId );
		$photo->publish( null , 0 );

		return JText::_('CC PHOTO UNPUBLISHED');
	}
	
	/**
	 * Deprecated since 1.8.x
	 **/	 	
	public function deletePhoto( $photoId )
	{
// 		if ($this->blockUnregister()) return;
// 		
// 		$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
// 		$photo->load( $photoId );
// 		$photo->delete();
// 		
// 		
// 		$album =& JTable::getInstance( 'Album' , 'CTable' );
// 		$album->load( $photo->albumId );
// 		
// 		// @todo: delete 1 particular activity
// 		// since we cannot identify any one activity (activity only store album id) 
// 		// just delete 1 activity with a matching album id
// 		$actModel = CFactory::getModel('activities');
// 		$actModel->removeOneActivity('photos' , $album->id );
// 
// 		return JText::_('CC PHOTO DELETED');
	}
	
	public function ajaxSetDefaultPhoto( $albumId , $photoId )
	{
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}

		$response	= new JAXResponse();
		
		CFactory::load( 'models' , 'photos' );
		CFactory::load( 'helpers' , 'owner' );
		$album =& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		$model		= CFactory::getModel( 'Photos' );
		$my			= CFactory::getUser();
		$photo		= $model->getPhoto( $photoId );
		$handler	= $this->_getHandler( $album );
		
		if( !$handler->hasPermission( $albumId ) )
		{
			$response->addScriptCall( 'alert' , JText::_('CC PERMISSION DENIED') );
			return $response->sendResponse();
		}

		$model->setDefaultImage( $albumId , $photoId );
		$response->addScriptCall('alert' , JText::_('CC PHOTO IS NOW ALBUM DEFAULT') );

		return $response->sendResponse();
	}
	
	/**
	 * Ajax method to display remove an album notice
	 *
	 * @param $id	Album id
	 **/
	public function ajaxRemoveAlbum( $id , $currentTask )
	{
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		
		$response	= new JAXResponse();
		
		// Load models / libraries
		CFactory::load( 'models' , 'photos' );
		$my			= CFactory::getUser();
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $id );

		$content	= '<div>';
		$content	.= JText::sprintf('CC CONFIRM REMOVE ALBUM' , $album->name );
		$content	.= '</div>';

		$buttons	= '<form name="jsform-photos-ajaxRemoveAlbum" method="post" action="' . CRoute::_('index.php?option=com_community&view=photos&task=removealbum') . '">';
		$buttons	.= '<input type="submit" value="' . JText::_('CC BUTTON YES') . '" class="button" name="Submit"/>';
		$buttons	.= '<input type="hidden" value="' . $album->id . '" name="albumid" />';
		$buttons	.= '<input type="hidden" value="' . $currentTask . '" name="currentTask" />';
		$buttons	.= '&nbsp;';
		$buttons	.= '<input onclick="cWindowHide();" type="button" value="' . JText::_('CC BUTTON NO') . '" class="button" />';
		$buttons	.= JHTML::_( 'form.token' );
		$buttons	.= '</form>';

		$response->addAssign('cWindowContent' , 'innerHTML' , $content);
		$response->addScriptCall('cWindowActions', $buttons);
		return $response->sendResponse();
	}
	 
	public function ajaxConfirmRemovePhoto( $photoId , $action = '' )
	{
		$response	= new JAXResponse();

		CFactory::load( 'helpers' , 'owner' );

		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		
		$model		=& CFactory::getModel('photos');
		$my			=& JFactory::getUser();
		$photo		= $model->getPhoto( $photoId );

		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $photo->albumid );
		$handler	= $this->_getHandler( $album );
		
		if( !$handler->hasPermission( $album->id ) )
		{
			$response->addScriptCall( 'alert' , JText::_('CC PERMISSION DENIED') );
			$response->sendResponse();
		}

		
		$response->addAssign('cwin_logo', 'innerHTML', JText::_('CC REMOVE PHOTO') );
		$response->addAssign('cWindowContent', 'innerHTML', JText::sprintf('CC REMOVE PHOTO DIALOG' , $photo->caption ) );
		$action		=	'<button  class="button" onclick="joms.gallery.removePhoto(\'' . $photoId . '\',\'' . $action . '\');">' . JText::_('CC BUTTON YES') . '</button>';
		$action		.=	'&nbsp;<button class="button" onclick="cWindowHide();return false;">' . JText::_('CC BUTTON NO') . '</button>';
		$response->addScriptCall( 'cWindowActions' , $action );
		
		return $response->sendResponse();
	}
	
	public function ajaxRemovePhoto( $photoId , $action = '' )
	{
		CFactory::load( 'helpers' , 'owner' );
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		
		$response	= new JAXResponse();
		
		$model		=& CFactory::getModel('photos');
		$my			=& JFactory::getUser();
		$photo		= $model->getPhoto( $photoId );

		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $photo->albumid );
		$handler	= $this->_getHandler( $album );

		if( !$handler->hasPermission( $album->id ) )
		{
			$response->addScriptCall( 'alert' , JText::_('CC PERMISSION DENIED') );
			$response->sendResponse();
		}

		CFactory::load( 'libraries' , 'apps' );
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();		
		
		$params		= array();
		$params[]	= $photo;

		$appsLib->triggerEvent( 'onBeforePhotoDelete' , $params);
		$photo->delete();
		$appsLib->triggerEvent( 'onAfterPhotoDelete' , $params);
		
		$album =& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $photo->albumid );
		
		// @todo: delete 1 particular activity
		// since we cannot identify any one activity (activity only store album id) 
		// just delete 1 activity with a matching album id
		$actModel = CFactory::getModel('activities');
		$actModel->removeOneActivity('photos' , $album->id );

		//add user points
		CFactory::load( 'libraries' , 'userpoints' );		
		CUserPoints::assignPoint('photo.remove');

		if( empty($action) )
		{
			$button		=	'<button class="button" onclick="window.location.reload();">' . JText::_('CC BUTTON CLOSE') . '</button>';
		}
		else
		{
			$response->addScriptCall( $action );
			$response->addScriptCall( 'jax.doneLoadingFunction();');
			$button		=	'<button class="button" onclick="cWindowHide();">' . JText::_('CC BUTTON CLOSE') . '</button>';
		}

		$response->addAssign('cwin_logo', 'innerHTML', JText::_('CC REMOVE PHOTO') );
		$response->addAssign('cWindowContent', 'innerHTML', JText::_('CC PHOTO REMOVED') );
		
		$response->addScriptCall( 'cWindowActions' , $button );
		
		return $response->sendResponse();
	}
	
	/**
	 * Populate the wall area in photos with wall/comments content
	 */	 	
	public function showWallContents($photoId)
	{
		// Include necessary libraries
		CFactory::load( 'libraries' , 'wall' );
		CFactory::load( 'models' , 'photos' );

		$my			= CFactory::getUser();
		$photo		=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $photoId );

		//@todo: Add limit
		$limit		= 20;
		
		if( $photo->id == '0' )
		{
			echo JText::_('CC INVALID PHOTO ID');
			return;
		}
		
		CFactory::load( 'helpers' , 'owner' );
		$contents	= CWallLibrary::getWallContents( 'photos' , $photoId , ($my->id == $photo->creator || COwnerHelper::isCommunityAdmin() ) , $limit , 0 , 'wall.content' , 'photos,photo' );
		CFactory::load( 'helpers' , 'string' );
		$contents	= CStringHelper::replaceThumbnails($contents);

		return $contents;
	}

	/**
	 * Ajax method to save the caption of a photo
	 *
	 * @param	int $photoId	The photo id
	 * @param	string $caption	The caption of the photo
	 **/	 	
	public function ajaxSaveCaption($photoId , $caption)
	{
		if (!COwnerHelper::isRegisteredUser()) return $this->ajaxBlockUnregister();
		
		global $my;
		$response	= new JAXResponse();
		
		CFactory::load( 'models' , 'photos' );
		
		$photo			=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $photoId );
		
		if( $photo->id == '0' )
		{
			// user shouldnt call this at all or reach here at all
			$response->addScriptCall( 'alert' , JText::_('CC INVALID PHOTO ID') );
			return $response->sendResponse();
		}
		
		CFactory::load( 'helpers' , 'owner' );
		if( $photo->creator != $my->id && !COwnerHelper::isCommunityAdmin() )
		{
			$response->addScriptCall( 'alert' , JText::_('CC NOT ALLOWED TO EDIT PHOTO CAPTION') );
			return $response->sendResponse();
		}
		
		$photo->caption	= $caption;
		$photo->store();		
		
		$response->addScriptCall('joms.gallery.updatePhotoCaption', $photo->id, $photo->caption);
		
		return $response->sendResponse();
	}

	/**
	 * Trigger any necessary items that needs to be changed when the photo
	 * is changed.
	 **/	 
	public function ajaxSwitchPhotoTrigger( $photoId )
	{
		$response	= new JAXResponse();
		
		// Get the wall form
		$wallInput	= $this->showWallForm( $photoId );
		$response->addAssign( 'community-photo-walls' , 'innerHTML' , $wallInput );
		
		// Get the wall contents
		$wallContents	= $this->showWallContents( $photoId );
		$response->addAssign('wallContent' , 'innerHTML' , $wallContents );
		
		$response->addScriptCall("joms.utils.textAreaWidth('#wall-message');");
		
		// Get the reporting data
		CFactory::load('libraries', 'reporting');
		$report		= new CReportingLibrary();

		$reportHTML	= $report->getReportingHTML( JText::_('CC REPORT BAD PHOTO') , 'photos,reportPhoto' , array( $photoId ) );
		$response->addScriptCall('joms.gallery.updatePhotoReport', $reportHTML);
		$response->addScriptCall('joms.jQuery("body").focus();');
		$response->addScriptCall('joms.gallery.bindFocus();');
		
		return $response->sendResponse();
	}
	
	/**
	 * This method is an AJAX call that displays the walls form
	 *
	 * @param	photoId	int The current photo id that is being browsed.
	 *
	 * returns
	 **/	 	
	public function showWallForm($photoId)
	{
		// Include necessary libraries
		require_once( JPATH_COMPONENT . DS .'libraries' . DS . 'wall.php' );
		
		// Include helper
		require_once( JPATH_COMPONENT . DS . 'helpers' . DS . 'friends.php' );
		
		// Load up required objects.
		$my				= CFactory::getUser();
		$friendsModel	=& CFactory::getModel( 'friends' );
		$config			=& CFactory::getConfig();
		$html			= '';
		CFactory::load( 'models' , 'photos' );
		$photo			=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $photoId );
		$album			=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $photo->albumid );
		
		CFactory::load( 'helpers' , 'owner' );
		
		
		$handler		= $this->_getHandler( $album );
		
		if( $handler->isWallsAllowed( $photoId ) )
		{
			$html		.= CWallLibrary::getWallInputForm( $photoId , 'photos,ajaxSaveWall', 'photos,ajaxRemoveWall' );
		}

		return $html;
	}
	
	public function ajaxRemoveWall( $wallId )
	{
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		
		$response	= new JAXResponse();

		$wallsModel	=& $this->getModel( 'wall' );
		$wall		= $wallsModel->get( $wallId );
		$photo		= & JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $wall->contentid );
		$my			= CFactory::getUser();
		
		if( $my->id == $photo->creator || COwnerHelper::isCommunityAdmin() )
		{
	
			if( $wallsModel->deletePost( $wallId ) )
			{
				$response->addAlert( JText::_('CC WALL REMOVED') );
				
				//add user points
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
	 * Ajax function to save a new wall entry
	 * 	 
	 * @param message	A message that is submitted by the user
	 * @param uniqueId	The unique id for this group
	 * 
	 **/	 	 	 	 	 		
	public function ajaxSaveWall( $message , $uniqueId )
	{
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}
		$response		= new JAXResponse();
		$my				= CFactory::getUser();
		$config			=& CFactory::getConfig();
		$message		= strip_tags( $message );
		
		// Load libraries
		CFactory::load( 'models' , 'photos' );
		CFactory::load( 'libraries' , 'wall' );
		CFactory::load( 'helpers' , 'url' );
		CFactory::load( 'libraries', 'activities' );	
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'helpers' , 'friends' );
		CFactory::load( 'helpers' , 'group' );
		
		$photo			=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $uniqueId );
		
		$album			=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $photo->albumid );
		
		$handler		= $this->_getHandler( $album );
		
		if( !$handler->isWallsAllowed( $photo->id ) )
		{
				echo JText::_('CC NOT ALLOWED TO POST COMMENT');
				return;
		}
		
		// If the content is false, the message might be empty.
		if( empty( $message) )
		{
			$response->addAlert( JText::_('CC EMPTY WALL MESSAGE') );
		}
		else
		{
			$wall	= CWallLibrary::saveWall( $uniqueId , $message , 'photos' , $my , ( $my->id == $photo->creator ) , 'photos,photo');
			$url	= $handler->getPhotoURI( $photo->albumid , $photo->id );
			$param = new JParameter('');
			$param->set( 'photoid', $uniqueId);
			$param->set( 'action', 'wall' );
			$param->set( 'wallid', $wall->id);
			$param->set( 'url'	, $url );

			// Add activity logging
			$this->_addActivity( 'photos.wall.create' , $my->id , 0 , JText::sprintf('CC ACTIVITIES WALL POST PHOTO' , $url , $photo->caption ) , $message , 'photos' , $uniqueId, $param->toString() );

			// @rule: Send notification to the photo owner.
			if( $my->id !== $photo->creator )
			{
				// Add notification
				CFactory::load( 'libraries' , 'notification' );
				
				$params			= new JParameter( '' );
				$params->set( 'url' , $handler->getPhotoURI( $photo->albumid , $photo->id , false ) );
				$params->set( 'message' , $message );
				
				CNotificationLibrary::add( 'photos.submit.wall' , $my->id , $photo->creator , JText::sprintf('CC PHOTO WALL EMAIL SUBJECT' , $my->getDisplayName() ) , '' , 'photos.wall' , $params );
			}
				
			//add user points
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('photos.wall.create');

			$response->addScriptCall( 'joms.walls.insert' , $wall->content );
		}
		
		return $response->sendResponse();
	}

	/**
	 * Default task in photos controller
	 **/	 
	public function display()
	{
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view		=& $this->getView( $viewName , '' , $viewType );
 		if($this->checkPhotoAccess())
 			echo $view->get( __FUNCTION__ );
	}

	/**
	 * Alias method that calls the display task in photos controller
	 **/
	public function myphotos()
	{		
	
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view		=& $this->getView( $viewName , '' , $viewType );
 
 	 	if($this->checkPhotoAccess())	
 			echo $view->get( __FUNCTION__ );
	}
	
	/**
	 * Create new album for the photos
	 **/	 	
	public function newalbum()
	{
		$my =& JFactory::getUser();
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view		=& $this->getView( $viewName , '' , $viewType );
		 		
		if( $this->blockUnregister() )
		{
			return;
		}
		
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$handler	= $this->_getHandler( $album );
		
		if( !$handler->isAllowedAlbumCreation() )
		{
			echo $view->noAccess();
			return;
		}

 		if( JRequest::getMethod() == 'POST' )
 		{
 			$mainframe =& JFactory::getApplication();
			$type		= JRequest::getVar( 'type' , '' , 'POST');
			$albumName	= JRequest::getVar( 'name' , '' , 'POST' );

			CFactory::load( 'libraries' , 'apps' );
			$appsLib		=& CAppPlugins::getInstance();
			$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array('jsform-photos-newalbum' ));
			
			if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
			{
				if( empty( $albumName ) )
				{
					$view->addWarning( JText::_('CC ALBUM NAME REQUIRED') );
				}
				else
				{
					$album	= $this->_saveAlbum();
					
					//add user points
					CFactory::load( 'libraries' , 'userpoints' );		
					CUserPoints::assignPoint('album.create');			
					
					$url		= $handler->getUploaderURL( $album->id );
					$message	= JText::_('CC NEW ALBUM CREATED');
					$mainframe->redirect( $url , $message );
				}
			}
		}

		if($this->checkPhotoAccess())
 		{
		 	echo $view->get( __FUNCTION__ );
		}
	}

	/**
	 * Display all photos from the current album
	 *	 
	 **/	 		
	public function album()
	{
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view		=& $this->getView( $viewName , '' , $viewType );

		if($this->checkPhotoAccess())
			echo $view->get( __FUNCTION__ );
	}

	/**
	 * Displays the photo
	 *	 
	 **/
	public function photo()
	{
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view		=& $this->getView( $viewName , '' , $viewType );
		
		if($this->checkPhotoAccess())				
			echo $view->get( __FUNCTION__ );
	}
	
	/**
	 * Method to edit an album
	 **/
	public function editAlbum()
	{
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view		=& $this->getView( $viewName , '' , $viewType );

		if ($this->blockUnregister())
			return;
		
		// Make sure the user has permission to edit any this photo album
		$my		= CFactory::getUser();
		
		// Load models, libraries
		CFactory::load( 'models' , 'photos' );
		CFactory::load( 'helpers' , 'url' );
		$albumid	= JRequest::getVar( 'albumid' , '' , 'GET' );
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load($albumid);
		$handler	= $this->_getHandler( $album );
		
		if( !$handler->hasPermission( $albumid ) )
		{
			$this->blockUserAccess();
			return true;
		}

 		if( JRequest::getMethod() == 'POST' )
 		{
			$mainframe	=& JFactory::getApplication();
			$type		= JRequest::getVar( 'type' , '' , 'POST');
			$album		= $this->_saveAlbum( $albumid );			
			$url		= $handler->getEditedAlbumURL( $albumid );
			$mainframe->redirect( $url , JText::_('CC ALBUM EDITED'));
		}
		
		if($this->checkPhotoAccess())
		{
 			echo $view->get( __FUNCTION__ );
 		}
	}

	/**
	 * Controller method to remove an album
	 **/
	public function removealbum()
	{
		if ($this->blockUnregister())
			return;
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
		
		// Get the album id.
		$my			= CFactory::getUser();
		$id			= JRequest::getInt( 'albumid' , '' );
		$task		= JRequest::getCmd( 'currentTask' , '' );
		$album			=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $id );
		
		$handler	= $this->_getHandler( $album );
		
		// Load libraries
		CFactory::load( 'models' , 'photos' );
		CFactory::load( 'libraries' , 'activities' );
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $id );

		CFactory::load( 'helpers' , 'owner' );
		
		if( !$handler->hasPermission( $album->id ) )
		{
			$this->blockUserAccess();
			return;
		}

		$model	=& CFactory::getModel( 'photos' );
		
		CFactory::load( 'libraries' , 'apps' );
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();	
		
		$params		= array();
		$params[]	= $album;
		
		$url		= $handler->getEditedAlbumURL( $album->id );

		if( $album->delete() )
		{
			$appsLib->triggerEvent( 'onAfterAlbumDelete' , $params);
			
			// @rule: remove from featured item if item is featured
			CFactory::load( 'libraries' , 'featured' );
			$featured	= new CFeatured( FEATURED_ALBUMS );
			$featured->delete( $album->id );
			
			//add user points
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('album.remove');
			
			// Remove from activity stream
			CActivityStream::remove('photos', $id);
		
			$mainframe =& JFactory::getApplication();
			
			$task		= ( !empty( $task ) ) ? '&task=' . $task : '';
			
			
			$message	= JText::sprintf('CC ALBUM REMOVED' , $album->name);
			$mainframe->redirect( $url , $message);
		}
	}
	
	/**
	 *	Generates a resized image of the photo
	 **/	 	
	public function showimage()
	{
		jimport('joomla.filesystem.file');
		$imgid 		= JRequest::getVar('imgid', '', 'GET');
		$maxWidth 	= JRequest::getVar('maxW', '', 'GET');
		$maxHeight	= JRequest::getVar('maxH', '', 'GET');
		
		// round up the w/h to the nearest 10
		$maxWidth	= round($maxWidth, -1);
		$maxHeight	= round($maxHeight, -1); 
		
		$photoModel		=& CFactory::getModel('photos');
		$photo			=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->loadFromImgPath( $imgid );

		CFactory::load('helpers', 'image');
		
		$photoPath		= JPATH_ROOT . DS .$photo->image;
		$config			= CFactory::getConfig();

		if(!JFile::exists( $photoPath ))
		{			
			$displayWidth	= $config->getInt('photodisplaysize');
			$info			= getimagesize( JPATH_ROOT . DS . $photo->original );
    		$imgType		= image_type_to_mime_type($info[2]);
    		$displayWidth 	= ($info[0] < $displayWidth) ? $info[0] : $displayWidth;
    		
			CImageHelper::resizeProportional( JPATH_ROOT . DS . $photo->original, $photoPath , $imgType, $displayWidth );
			
			if( $config->get( 'deleteoriginalphotos') )
			{
				$originalPath	= JPATH_ROOT . DS . $photo->original;
				if( JFile::exists( $originalPath ) )
				{
					JFile::delete( $originalPath );
				}
			}
		}
		
		$info	= getimagesize( JPATH_ROOT . DS .$photo->image );

		// @rule: Clean whitespaces as this might cause errors when header is used.
		$ob_active = ob_get_length () !== FALSE;

		if($ob_active)
		{
			while (@ ob_end_clean());
				if(function_exists('ob_clean'))
				{
					@ob_clean();
				}
		}
			
		header('Content-type: '.$info['mime']);
		echo JFile::read( $photoPath );
		exit;
	}

	public function uploader()
	{
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view		=& $this->getView( $viewName , '' , $viewType );
 		$my			= CFactory::getUser();
		
		// If user is not logged in, we shouldn't really let them in to this page at all.
		if ($this->blockUnregister())
		{
			return;
		}
		
		// Load models, libraries
		CFactory::load( 'models' , 'photos' );
		CFactory::load( 'helpers' , 'url' );
		$albumid	= JRequest::getInt( 'albumid' , '' , 'GET' );
		$groupId	= JRequest::getInt('groupid', '0');

		if(!empty($groupId))
		{
			CFactory::load( 'helpers' , 'group' );			
			$allowManagePhotos = CGroupHelper::allowManagePhoto($groupId);

			if(!$allowManagePhotos)
			{
				echo JText::_('You are not allowed to upload photos in the group albums.');
				return;
			}
		}
		
		// User has not selected album id yet
		if( !empty($albumid) )
		{
			$album		=& JTable::getInstance( 'Album' , 'CTable' );
			$album->load($albumid);

			if( !$album->hasAccess( $my->id , 'upload' ) )
			{
				$this->blockUserAccess();
				return;
			}
		}

		
		if($this->checkPhotoAccess())
 		{
		 	echo $view->get( __FUNCTION__ );
		}
	}
	
	public function checkPhotoAccess()
	{
		$mainframe =& JFactory::getApplication();
		$config =& CFactory::getConfig();
		
 		if(! $config->get('enablephotos'))
		 { 			
 			$mainframe->enqueueMessage(JText::_('CC MEDIA DISABLE'), '');
 			return false;
 		}
		return true;
	}
	
	private function _imageLimitExceeded( $size )
	{
		$config			= CFactory::getConfig();
		$uploadLimit	= (double) $config->get('maxuploadsize');
		$uploadLimit	= ( $uploadLimit * 1024 * 1024 );
		
		return $size > $uploadLimit;
	}
	
	private function _validImage( $image )
	{
		CFactory::load( 'helpers' , 'image' );
		$config		=& CFactory::getConfig();
		
		if( empty($image['tmp_name'] ) )
		{
			return false;
		}
		
		// This is only applicable for html uploader because flash uploader uploads all 'files' as application/octet-stream
		if( !$config->get('flashuploader') && !CImageHelper::isValidType( $image['type'] ) )
		{
			return false;
		}
		
		if( !CImageHelper::isValid( $image['tmp_name'] ) )
		{
			return false;
		}
		
		return true;
	}
	
	public function upload()
	{
		$my		= CFactory::getUser();
		$config	= CFactory::getConfig();

		// If user is using a flash browser, their session might get reset when mod_security is around
		if( $my->id == 0 )
		{
			$tokenId	= JRequest::getVar( 'token' , '' , 'REQUEST' );
			$userId		= JRequest::getVar( 'uploaderid' , '' , 'REQUEST' );
			$my			= CFactory::getUserFromTokenId( $tokenId , $userId );
		}

		// First we try to get the user object.
		if ($this->blockUnregister())
			return;

		// Load up required models and properties
		CFactory::load('libraries', 'photos');
		CFactory::load( 'models' , 'photos' );
		CFactory::load('helpers', 'image');

		$photos		= JRequest::get('Files');
		$albumId	= JRequest::getVar( 'albumid' , '' , 'REQUEST' );
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		$handler	= $this->_getHandler( $album );
		
		foreach( $photos as $imageFile )
		{
			if( !$this->_validImage( $imageFile ) )
			{
				$this->_showUploadError( true , JText::_('CC IMAGE FILE NOT SUPPORTED') );
				return;
			}
			
			if( $this->_imageLimitExceeded( filesize( $imageFile['tmp_name'] ) ) )
			{
				$this->_showUploadError( true , JText::_('CC IMAGE FILE SIZE EXCEEDED') );
				return;
			}

			// We need to read the filetype as uploaded always return application/octet-stream
			// regardless od the actual file type
			$info			= getimagesize( $imageFile['tmp_name'] );				
			$isDefaultPhoto	= JRequest::getVar( 'defaultphoto' , false , 'REQUEST' );

			if( $album->id == 0 || ( ($my->id != $album->creator ) && $album->type != PHOTOS_GROUP_TYPE ) )
			{
				$this->_showUploadError( true , JText::_('CC INVALID ALBUM') );
				return;
			}
			
			if( !$album->hasAccess( $my->id , 'upload') )
			{
				$this->_showUploadError( true , JText::_('CC INVALID ALBUM') );
				return;
			}

			// Hash the image file name so that it gets as unique possible
			$fileName		= JUtility::getHash( $imageFile['tmp_name'] . time() );
			$hashFilename	= JString::substr( $fileName , 0 , 24 );
			$imgType		= image_type_to_mime_type($info[2]);
			
			// Load the tables
			$photoTable		=& JTable::getInstance( 'Photo' , 'CTable' );

			// @todo: configurable paths?
			$storage		= JPATH_ROOT . DS . $config->getString('photofolder');
			$albumPath		= (empty($album->path)) ? '' : $album->id . DS;

			// Test if the photos path really exists.
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');
			CFactory::load( 'helpers' , 'limits' );
			
			$originalPath	= $handler->getOriginalPath( $storage , $albumPath , $album->id );

			// @rule: Just in case user tries to exploit the system, we should prevent this from even happening.
			if( $handler->isExceedUploadLimit() )
			{
				$config			=& CFactory::getConfig();
				$photoLimit		= $config->get( 'groupphotouploadlimit' );
				
				echo JText::sprintf('CC GROUP PHOTO UPLOAD LIMIT REACHED' , $photoLimit );
				return;
			}
					
			if( !JFolder::exists( $originalPath ) )
			{
				if( ! JFolder::create( $originalPath , (int) octdec( $config->get('folderpermissionsphoto') ) ) )
				{
					$this->_showUploadError( true , JText::_('CC ERROR CREATING USERS PHOTO FOLDER') );
					return;
				}
			}

			$locationPath	= $handler->getLocationPath( $storage , $albumPath , $album->id );

			if( !JFolder::exists( $locationPath ) )
			{
				if( ! JFolder::create( $locationPath, (int) octdec( $config->get('folderpermissionsphoto') ) ) )
				{
					$this->_showUploadError( true , JText::_('CC ERROR CREATING USERS PHOTO FOLDER') );
					return;
				}
			}
			
			$thumbPath	= $handler->getThumbPath( $storage, $album->id );
			$thumbPath	= $thumbPath . DS . $albumPath . 'thumb_' . $hashFilename . CImageHelper::getExtension( $imageFile['type'] );
			CPhotos::generateThumbnail($imageFile['tmp_name'] , $thumbPath , $imgType);
			
			// Original photo need to be kept to make sure that, the gallery works
			$useAlbumId		= (empty($album->path)) ? 0 : $album->id;
			$originalFile	= $originalPath . $hashFilename . CImageHelper::getExtension($imgType);
			
			$this->_storeOriginal($imageFile['tmp_name'] , $originalFile , $useAlbumId);
			$photoTable->original		= JString::str_ireplace( JPATH_ROOT . DS , '' , $originalFile );
	
			// Set photos properties
			$photoTable->albumid		= $albumId;
			$photoTable->caption		= $imageFile['name'];
			$photoTable->creator		= $my->id;
			$photoTable->created		= gmdate('Y-m-d H:i:s');
			
			// Remove the filename extension from the caption
			if(JString::strlen($photoTable->caption) > 4)
			{
				$photoTable->caption = JString::substr($photoTable->caption, 0 , JString::strlen($photoTable->caption) - 4);
			}
			
			// @todo: configurable options?
			// Permission should follow album permission
			$photoTable->published		= '1';
			$photoTable->permissions	= $album->permissions;
	
			// Set the relative path.
			// @todo: configurable path?
			$storedPath				= $handler->getStoredPath( $storage , $albumId );
			$storedPath				= $storedPath . DS . $albumPath . $hashFilename . CImageHelper::getExtension($imageFile['type']);
			
			$photoTable->image		= JString::str_ireplace( JPATH_ROOT . DS , '' , $storedPath ); 
			$photoTable->thumbnail	= JString::str_ireplace( JPATH_ROOT . DS , '' , $thumbPath );
			
			//photo filesize, use sprintf to prevent return of unexpected results for large file.
			$photoTable->filesize = sprintf("%u", filesize($originalPath));
			
			// Store the object
			$photoTable->store();
	
			// Set image as default if necessary
			// Load photo album table
			if( $isDefaultPhoto )
			{
				// Set the photo id
				$album->photoid	= $photoTable->id;
 				$album->store();
			}

			// @rule: Set first photo as default album cover if enabled
			if( !$isDefaultPhoto && $config->get('autoalbumcover') )
			{
				$photosModel	=& CFactory::getModel( 'Photos' );
				$totalPhotos	= $photosModel->getTotalPhotos( $album->id );

				if( $totalPhotos <= 1 )
				{
					$album->photoid	= $photoTable->id;
					$album->store();
				}
			}
			
			if( $handler->isPublic( $album->id ) )
			{
				$act = new stdClass();
				$act->cmd 		= 'photo.upload';
				$act->actor   	= $my->id;
				$act->target  	= 0;
				$act->title	  	= JText::sprintf( $handler->getUploadActivityTitle() , '{photo_url}', $album->name );
				$act->content	= '<img src="' . rtrim( JURI::root() , '/' ) . '/' . $photoTable->thumbnail . '" style=\"border: 1px solid #eee;margin-right: 3px;" />';
				$act->app		= 'photos';
				$act->cid		= $albumId;
	
		 		$params = new JParameter('');
		 		$params->set('multiUrl'	, $handler->getAlbumURI( $albumId ) );
		 		$params->set('photoid'	, $photoTable->id);
				$params->set('action'	, 'upload' );
				$params->set('photo_url', $handler->getPhotoURI( $albumId , $photoTable->id ) );
						
				// Add activity logging
				CFactory::load ( 'libraries', 'activities' );
				CActivityStream::add( $act , $params->toString() );
			}

			//add user points
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('photo.upload');

			// Photo upload was successfull, display a proper message
			$this->_showUploadError( false , JText::sprintf('CC PHOTO UPLOADED SUCCESSFULLY', $photoTable->caption ) , $photoTable->getThumbURI());
		}		
		exit;
	}

	/**
	 * Return photos handlers
	 */	 	
	private function _getHandler( CTableAlbum $album )
	{
		$handler = null;
		
		// During AJAX calls, we might not be able to determine the groupid
		$groupId	= JRequest::getVar( 'groupid' , $album->groupid , 'REQUEST' );
		$type		= PHOTOS_USER_TYPE;

		if(!empty($groupId) )
		{
			// group photo
			$handler = new CommunityControllerPhotoGroupHandler( $this );
		}
		else
		{
			// user photo
			$handler = new CommunityControllerPhotoUserHandler( $this );
		}
		
		return $handler;
	}
}

abstract class CommunityControllerPhotoHandler
{
	protected $type 	= '';
	protected $model 	= '';
	protected $view		= '';
	protected $my		= '';
	
	abstract public function getType();
	abstract public function getAlbumPath( $albumId );
	abstract public function getEditedAlbumURL( $albumId );
	abstract public function getUploaderURL( $albumId );
	abstract public function getOriginalPath(  $storagePath , $albumPath  , $albumId );
	abstract public function getLocationPath( $storagePath , $albumPath , $albumId );
	abstract public function getThumbPath( $storagePath , $albumId );
	abstract public function getStoredPath( $storagePath , $albumId );
	abstract public function getAlbumURI( $albumId );
	abstract public function getPhotoURI( $albumId , $photoId, $route = true );
	abstract public function getUploadActivityTitle();
	
	abstract public function bindAlbum( CTableAlbum &$album , $postData );
	
	abstract public function hasPermission( $albumId );
	abstract public function canPostActivity( $albumId );
	
	abstract public function isAllowedAlbumCreation();
	abstract public function isWallsAllowed( $photoId );
	abstract public function isExceedUploadLimit();
	abstract public function isPublic( $albumId );
	
	public function __construct()
	{
		$this->my		= CFactory::getUser();
		$this->model	= CFactory::getModel( 'photos' );
	}
}

class CommunityControllerPhotoUserHandler extends CommunityControllerPhotoHandler
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function canPostActivity( $albumId )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );

		if( $album->permissions <= PRIVACY_PUBLIC )
		{
			return true;			
		}
		return false;
	}
	
	public function isWallsAllowed( $photoId )
	{
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'photos' );
		$photo			=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $photoId );
		$config			= CFactory::getConfig();
		$isConnected	= CFriendsHelper::isConnected( $this->my->id , $photo->creator );
		$isMe			= COwnerHelper::isMine( $this->my->id , $photo->creator );

		// Check if user is really allowed to post walls on this photo.
		if( ($isMe) || (!$config->get('lockprofilewalls')) || ( $config->get('lockprofilewalls') && $isConnected ) || COwnerHelper::isCommunityAdmin() )
		{
			return true;
		}
		return false;
	}
	
	public function isAllowedAlbumCreation()
	{
		return true;
	}
	
	public function getUploadActivityTitle()
	{
		return 'CC ACTIVITIES UPLOAD PHOTO';
	}
	
	public function isPublic( $albumId )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		return $album->permissions <= PRIVACY_PUBLIC;
	}
	
	public function getPhotoURI( $albumId , $photoId, $route = true)
	{
		$photo			=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $photoId );
		
		$url = 'index.php?option=com_community&view=photos&task=photo&albumid=' . $albumId .  '&userid=' . $photo->creator . '#photoid=' . $photoId;
		$url = $route ? CRoute::_( $url ): $url;
		
		return $url;
	}
	
	public function getAlbumURI( $albumId )
	{
		return CRoute::_('index.php?option=com_community&view=photos&task=album&albumid=' . $albumId .  '&userid=' . $this->my->id);
	}
	
	public function getStoredPath( $storagePath , $albumId )
	{
		$path	= $storagePath . DS . 'photos' . DS . $this->my->id;
		return $path;
	}
	
	public function getThumbPath( $storagePath , $albumId )
	{
		$path		= $storagePath . DS . 'photos' . DS . $this->my->id;
		
		return $path;
	}
	
	public function isExceedUploadLimit()
	{
		CFactory::load( 'helpers' , 'limits' );
		
		if( CLimitsHelper::exceededPhotoUpload( $this->my->id , PHOTOS_USER_TYPE ) )
		{
			$config			= CFactory::getConfig();
			$photoLimit		= $config->get( 'photouploadlimit' );
			
			echo JText::sprintf('CC PHOTO UPLOAD LIMIT REACHED' , $photoLimit );
			return true;
		}
		return false;
	}
	
	public function getLocationPath( $storagePath , $albumPath , $albumId )
	{
		$path = (empty($albumPath)) ? $storagePath . DS . 'photos' . DS . $this->my->id : $storagePath . DS . 'photos' . DS . $this->my->id . DS . $albumId;
		return $path;
	}
	
	public function getOriginalPath( $storagePath , $albumPath , $albumId )
	{
		$config	= CFactory::getConfig();

		$path	= $storagePath . DS . JPath::clean( $config->get('originalphotopath') ) . DS . $this->my->id . DS . $albumPath;  
		
		return $path;
	}
	
	public function getUploaderURL( $albumId )
	{
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		return CRoute::_('index.php?option=com_community&view=photos&task=uploader&albumid=' . $album->id . '&userid=' . $album->creator , false );
	}
	
	public function getEditedAlbumURL( $albumId )
	{
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );

		return CRoute::_('index.php?option=com_community&view=photos&task=myphotos&userid=' . $album->creator , false);
	}
	
	public function getType()
	{
		return PHOTOS_USER_TYPE;
	}
	
	public function bindAlbum( CTableAlbum &$album , $postData )
	{
		$album->bind( $postData );
		
		return $album;
	}
	
	public function getAlbumPath( $albumId )
	{
		$config		= CFactory::getConfig();
		$storage	= JPATH_ROOT . DS . $config->getString('photofolder');
		$albumPath	= $storage . DS . 'photos' . DS . $this->my->id . DS . $albumId;

		return $albumPath;
	}
	
	public function hasPermission( $albumId )
	{
		CFactory::load('helpers' , 'owner' );
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );

		if($album->creator != $this->my->id && !COwnerHelper::isCommunityAdmin())
		{
			return false;
		}
		return true;
	}
}

class CommunityControllerPhotoGroupHandler extends CommunityControllerPhotoHandler
{
	private $groupid = null;
	
	public function __construct()
	{
		$this->groupid = JRequest::getVar( 'groupid' , '' , 'REQUEST' );
		parent::__construct();
	}

	public function canPostActivity( $albumId )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );

		CFactory::load( 'models' , 'groups' );
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $album->groupid );

		if( $group->approvals != COMMUNITY_PRIVATE_GROUP )
		{
			return true;			
		}
		return false;
	}
	
	public function isWallsAllowed( $photoId )
	{
		CFactory::load( 'helpers' , 'group' );
		CFactory::load( 'models' , 'photos' );
		$photo			=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $photoId );

		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $photo->albumid );
		
		if( CGroupHelper::allowPhotoWall($album->groupid ) )
		{
			return true;
		}
		return false;
	}
	
	public function isAllowedAlbumCreation()
	{
		CFactory::load( 'helpers' , 'group' );

		$allowManagePhotos = CGroupHelper::allowManagePhoto($this->groupid);
		
		return $allowManagePhotos;
	}
	
	public function getUploadActivityTitle()
	{
		return 'CC ACTIVITIES GROUP UPLOAD PHOTO';
	}
	
	public function isPublic( $albumId )
	{
		CFactory::load( 'models' , 'groups' );			
		
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		 
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $album->groupid );
		
		return $group->approvals == COMMUNITY_PUBLIC_GROUP;
	}
		
	public function getPhotoURI( $albumId , $photoId, $route = true )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		$url = 'index.php?option=com_community&view=photos&task=photo&albumid=' . $albumId .  '&groupid=' . $album->groupid;
		$url = $route ? CRoute::_( $url ): $url;
		$url .= '#photoid=' . $photoId;
		return $url;
	}
	
	public function getAlbumURI( $albumId )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		return CRoute::_('index.php?option=com_community&view=photos&task=album&albumid=' . $albumId .  '&groupid=' . $album->groupid );
	}
	
	public function getStoredPath( $storagePath , $albumId )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		$path	= $storagePath . DS . 'groupphotos' . DS . $album->groupid;
		
		return $path; 
	}
	
	public function getThumbPath( $storagePath , $albumId )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		$path		= $storagePath . DS . 'groupphotos' . DS . $album->groupid;
		return $path;
	}
	
	public function getLocationPath( $storagePath , $albumPath , $albumId )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		$path	= $storagePath . DS . 'groupphotos' . DS . $album->groupid . DS . $albumId;
		return $path;
	}
	
	public function isExceedUploadLimit()
	{
		CFactory::load( 'helpers' , 'limits' );
		if( CLimitsHelper::exceededPhotoUpload($this->groupid , PHOTOS_GROUP_TYPE ) )
		{
			$config			=& CFactory::getConfig();
			$photoLimit		= $config->get( 'groupphotouploadlimit' );
			
			echo JText::sprintf('CC GROUP PHOTO UPLOAD LIMIT REACHED' , $photoLimit );
			return true;
		}
		
		return false;
	}
	
	public function getOriginalPath( $storagePath , $albumPath , $albumId )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		$config	= CFactory::getConfig();
		$path	= $storagePath . DS . JPath::clean( $config->get('originalphotopath') ) . DS . 'groupphotos' . DS . $album->groupid . DS . $albumPath;
		
		return $path;
	}

	public function getUploaderURL( $albumId )
	{
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		return CRoute::_('index.php?option=com_community&view=photos&task=uploader&albumid=' . $album->id . '&groupid=' . $album->groupid , false );
	}
	
	public function getEditedAlbumURL( $albumId )
	{
		$album		=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );

		return CRoute::_('index.php?option=com_community&view=photos&groupid=' . $album->groupid , false);
	}
	
	public function getType()
	{
		return PHOTOS_GROUP_TYPE;
	}

	/**
	 * Binds posted data into existing album object
	 **/
	public function bindAlbum( CTableAlbum &$album , $postData )
	{
		$album->bind( $postData );

		$album->groupid	= $this->groupid;
		return $album;
	}
	
	public function getAlbumPath( $albumId )
	{
		$config		= CFactory::getConfig();
		$storage	= JPATH_ROOT . DS . $config->getString('photofolder');
		
		return $storage . DS . 'groupphotos' . DS . $this->groupid . DS . $albumId;
	}

	public function hasPermission( $albumId )
	{
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'groups' );			
		
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $album->groupid );
	
		if( COwnerHelper::isCommunityAdmin() || $group->isAdmin( $this->my->id ) || $album->creator == $this->my->id )
		{
			return true;
		}
		return false;
	}
}