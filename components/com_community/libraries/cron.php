<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
 
defined('_JEXEC') or die('Restricted access');

class CCron {

	function run() {
		jimport( 'joomla.filesystem.file' );		
		set_time_limit(120);
		
		$this->_sendEmails();
		$this->_convertVideos();
		$this->_sendSiteDetails();
		$this->_archiveActivities();
		$this->_cleanRSZFiles();
		$this->_processPhotoStorage();
		$this->_updatePhotoFileSize();
		$this->_updateVideoFileSize();
		$this->_removeDeletedPhotos();
		$this->_processVideoStorage();
		
		// Include CAppPlugins library
		require_once( JPATH_COMPONENT . DS . 'libraries' . DS . 'apps.php');
		// Trigger system event onCronRun
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();

		$args = array();
		$appsLib->triggerEvent( 'onCronRun' , $args );
	}
	
	/**
	 * For photos that does not have proper filesize info, update it.
	 * Due to IO issues, run only 20 photos at a time	  
	 */	 	
	function _updatePhotoFileSize($updateNum = 20){
				
		$db=& JFactory::getDBO();
		
		$sql = "SELECT ".$db->nameQuote('id')." FROM #__community_photos "
			 . "WHERE ".$db->nameQuote('filesize')."=0 ORDER BY rand() LIMIT ".$updateNum;
		$db->setQuery($sql);
		$photos = $db->loadObjectList();
		
		if(!empty($photos))
		{
			CFactory::load('models','Photos');
			$photo = JTable::getInstance( 'Photo' , 'CTable' );
			
			foreach($photos as $data)
			{
				$photo->load($data->id);
				$originalPath = JPATH_ROOT . DS . $photo->original;
				if(JFile::exists($originalPath))
				{
					$photo->filesize = sprintf("%u", filesize($originalPath));
					$photo->store();
				}
			}
		}
	}
	
	/**
	 * For videos that does not have proper filesize info, update it.
	 * Due to IO issues, run only 20 photos at a time	  
	 */	 	
	function _updateVideoFileSize($updateNum = 20){
				
		$db		= JFactory::getDBO();
		$sql	= "SELECT ".$db->nameQuote('id').", ".$db->nameQuote('creator')." FROM #__community_videos "
				. "WHERE ".$db->nameQuote('type')."=".$db->quote('file')." AND "
				. $db->nameQuote('status')."=".$db->quote('ready')." AND "
				. $db->nameQuote('filesize')."=0 ORDER BY rand() LIMIT ".$updateNum;
		$db->setQuery($sql);
		$videos = $db->loadObjectList();
		
		if(!empty($videos))
		{
			$video = JTable::getInstance( 'Video' , 'CTable' );
			
			foreach($videos as $data)
			{
				$video->load($data->id);
				$videoPath = JPATH::clean( JPATH_ROOT . DS . $video->path);
				if(JFile::exists($videoPath))
				{
					$video->filesize = sprintf("%u", filesize($videoPath));
					$video->store();
				}
			}
		}
	}
	
	
	/**
	 * Remove all photos that are orphaned, whose parent album has been deleted
	 */	 	
	function _removeDeletedPhotos($updateNum = 5)
	{
		echo 'Remove deleted files <br/>';
		$db=& JFactory::getDBO();
			
		$sql = "SELECT * FROM #__community_photos "
			." WHERE `albumid`=0 "
			." ORDER BY rand() limit ".$updateNum;
		$db->setQuery($sql);
		$result = $db->loadObjectList();

		if(!$result){
			return;
		}
		
		CFactory::load('models', 'photos');
		
		foreach($result as $row)
		{
			$photo = JTable::getInstance( 'Photo' , 'CTable' );
			$photo->load($row->id);
			$photo->delete();
			
			// Remove all related activities
			$query = 'DELETE FROM ' . $db->nameQuote( '#__community_activities') 
				.' WHERE ' . $db->nameQuote( 'app' ) . ' LIKE '. $db->Quote('photos')
				.' AND ' . $db->nameQuote( 'cid' ) . ' ='.$db->Quote($row->id)
				.' AND ' . $db->nameQuote( 'params' ) . ' LIKE '. $db->Quote('%photoid='.$row->id.'%');
			$db->setQuery( $query );
			$db->query();
		}
		
	}
	/**
	 * Remove old dynamically resized image files
	 */	 	
	function _cleanRSZFiles($updateNum = 5){
		$db=& JFactory::getDBO();
			
		$sql = "SELECT * FROM #__community_photos "
			." ORDER BY rand() limit ".$updateNum;
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		
		if(!$result){
			return;
		}
		
		foreach($result as $row) 
		{
			// delete all rsz_ files which are no longer used
			$rszFiles = JFolder::files(dirname(JPATH_ROOT.DS.$row->image), '.', false, true);
			if($rszFiles)
			foreach($rszFiles as $rszRow)
			{
				if(substr(basename($rszRow), 0, 3) == 'rsz')
				{
					JFile::delete($rszRow);
				}
			}
		}
	}
	
	/**
	 * If remote storage is used, transfer some files to the remote storage
	 * - fetch file from current storage to a temp location
	 * - put file from temp to new storage
	 * - delete file from old storage	 	 	 
	 */	 	
	function _processPhotoStorage($updateNum = 5){
		$config = CFactory::getConfig();
		$jconfig = JFactory::getConfig();
		$photoStorage = $config->getString( 'photostorage');
		
		//if( $photoStorage != 'file' )
		{
			echo 'Moving files to remote storage...<br/>';
			
			CFactory::load('models', 'photos');
			CFactory::load('libraries', 'storage');
			CFactory::load('helpers', 'image');
			
			$fileTranferCount = 0;
			$storage = CStorage::getStorage( $photoStorage);			
			
			$db=& JFactory::getDBO();
			
			// @todo, we nee to find a way to make sure that we transfer most of
			// our photos remotely
			$sql = "SELECT * FROM #__community_photos "
				." WHERE `storage`!=" . $db->Quote($photoStorage)
				." AND `albumid`!=" . $db->Quote(0)
				." ORDER BY rand() limit ".$updateNum;
			$db->setQuery($sql);
			$result = $db->loadObjectList();
			
			if(!$result)
			{
				echo JText::_('No files to transfer<br/>');
				return;
			}
				
			foreach($result as $row) 
			{
				$currentStorage = CStorage::getStorage( $row->storage );
				
				// If current storage is file based, create the image since we might not have them yet
				if( $row->storage == 'file' && !JFile::exists(JPATH_ROOT.DS.$row->image) )
				{
					// resize the original image to a smaller viewable version
					echo 'Image file missing. Creating image file from '.$row->original . '<br/>';

					// make sure original file exist
					if(JFile::exists(JPATH_ROOT.DS.$row->original))
					{
						$displyWidth = $config->getInt('photodisplaysize');
						$info		= getimagesize( JPATH_ROOT . DS . $row->original );
						$imgType	= image_type_to_mime_type($info[2]);
						$width = ($info[0] < $displyWidth) ? $info[0] : $displyWidth;
						CImageHelper::resizeProportional(JPATH_ROOT.DS.$row->original, JPATH_ROOT.DS.$row->image, $imgType, $width);
					}
					else
					{
						echo 'Original file is missing!!';
					}
				}
				
				// If it exist on current storage, we can transfer it to preferred storage
				if( $currentStorage->exists($row->image) && $currentStorage->exists($row->thumbnail) )
				{
					// File exist on remote storage, move it locally first
					$tempFilename = $jconfig->getValue('tmp_path'). DS . md5($row->image);
					echo 'Downloading image to temporary location: '.$tempFilename . '<br/>';
					$currentStorage->get($row->image, $tempFilename);
					
					$thumbsTemp		= $jconfig->getValue('tmp_path'). DS . 'thumb_' . md5($row->thumbnail);
					echo 'Downloading thumbnail to temporary location: '.$thumbsTemp . '<br/>';
					$currentStorage->get($row->thumbnail, $thumbsTemp);
					
					if( JFile::exists( $tempFilename ) && JFile::exists($thumbsTemp) )
					{
						// we assume thumbnails is always there
						// put both image and thumbnails remotely 
						if( $storage->put($row->image, $tempFilename) && $storage->put($row->thumbnail, $thumbsTemp ) )
						{
							// if the put is successful, update storage type
							$photo = JTable::getInstance( 'Photo' , 'CTable' );
							$photo->load($row->id);
							$photo->storage = $photoStorage;
							$photo->store();
							
							$currentStorage->delete($row->image);
							$currentStorage->delete($row->thumbnail);
							
							// remove temporary file
							JFile::delete($tempFilename);
							JFile::delete( $thumbsTemp );
							$fileTranferCount++;
						}
					}
				}
			}
			
			echo $fileTranferCount. ' files transferred<br/>';
			
		}
	}
	
	function _processVideoStorage($updateNum=5)
	{
		$config			= CFactory::getConfig();
		$jconfig		= JFactory::getConfig();
		$videoStorage	= $config->getString('videostorage');
		
		echo 'Moving videos to the preferred storage: ' . $videoStorage . '<br/>';
		
		CFactory::load('libraries', 'storage');
		CFactory::load('models', 'videos');
		CFactory::load('helpers', 'videos');
		
		$db		= JFactory::getDBO();
		$query	= ' SELECT * FROM ' . $db->nameQuote('#__community_videos')
			 	. ' WHERE ' . $db->nameQuote('storage') . ' != ' . $db->quote($videoStorage)
			 	//. ' AND ' . $db->nameQuote('type') . ' = ' . $db->quote('file')
			 	. ' AND ' . $db->nameQuote('status') . ' = ' . $db->quote('ready')
			 	. ' ORDER BY rand() limit ' . $updateNum;

		$db->setQuery($query);
		$result	= $db->loadObjectList();
		
		if (!$result)
		{
			echo JText::_('No Videos to transfer<br/>');
			return;
		}
		
		$storage	= CStorage::getStorage($videoStorage);
		$tempFolder	= $jconfig->getValue('tmp_path');
		$fileTransferCount = 0;
		
		foreach ($result as $videoEntry)
		{
			$currentStorage = CStorage::getStorage($videoEntry->storage);
			
			if ($videoEntry->type == 'file')
			{
				// If it exist on current storage, we can transfer it to preferred storage
				if ($currentStorage->exists($videoEntry->path))
				{
					// File exist on remote storage, move it locally first
					$tempFilename	= JPATH::clean( $tempFolder . DS . md5($videoEntry->path));
					$tempThumbname	= JPATH::clean( $tempFolder . DS . md5($videoEntry->thumb));
					echo 'Downloading video to temporary location: '.$tempFilename . '<br/>';
					$currentStorage->get($videoEntry->path, $tempFilename);
					echo 'Downloading video to temporary location: '.$tempThumbname . '<br/>';
					$currentStorage->get($videoEntry->thumb, $tempThumbname);
					
					if (JFile::exists($tempFilename) && JFile::exists($tempThumbname))
					{
						// we assume thumbnails is always there
						// put both video and thumbnails remotely 
						if ($storage->put($videoEntry->path, $tempFilename) &&
							$storage->put($videoEntry->thumb, $tempThumbname))
						{
							// if the put is successful, update storage type
							$video = JTable::getInstance( 'Video' , 'CTable' );
							$video->load($videoEntry->id);
							$video->storage = $videoStorage;
							$video->store();
							
							// remove files on storage and temporary files
							$currentStorage->delete($videoEntry->path);
							$currentStorage->delete($videoEntry->thumb);
							JFile::delete($tempFilename);
							JFile::delete($tempThumbname);
							
							$fileTransferCount++;
						}
					}
				}
			} else {
				// This is for non-upload video file type e.g. YouTube etc
				// We'll just process the video thumbnail only
				if ($currentStorage->exists($videoEntry->thumb))
				{
					$tempThumbname	= JPATH::clean($tempFolder.DS.md5($videoEntry->thumb));
					echo 'Downloading video to temporary location: '.$tempThumbname . '<br/>';
					$currentStorage->get($videoEntry->thumb, $tempThumbname);
					
					if (JFile::exists($tempThumbname))
					{
						if ($storage->put($videoEntry->thumb, $tempThumbname))
						{
							$video = JTable::getInstance( 'Video' , 'CTable' );
							$video->load($videoEntry->id);
							$video->storage = $videoStorage;
							$video->store();
							
							$currentStorage->delete($videoEntry->thumb);
							JFile::delete($tempThumbname);
							$fileTransferCount++;
						}
					}
				}
			}
		}
		echo $fileTransferCount. ' video file(s) transferred<br/>';
	}
	
	function _convertVideos()
	{
		CFactory::load('libraries', 'videos');
		$videos = new CVideos();
		$videos->runConvert();
		if ($videos->errorMsg[0])
		{
			echo $videos->errorMsg[0];
		}
	}
	
	function _sendSiteDetails()
	{
		CFactory::load('libraries', 'jsnetwork');
		$videos = new JSNetworkLibrary();
		$videos->submitToJomsocial();
		
		echo "Updating to Jomsocial Network...<br />";
	}
	
	function _sendEmails()
	{
		CFactory::load('libraries', 'mailq');
		$mailq = new CMailq();
		
		$config	=& CFactory::getConfig();
		$mailq->send( $config->get('totalemailpercron') );
		
		echo JText::_('CC SENDING EMAILS');
	}
	
	function _archiveActivities(){
		$config = CFactory::getConfig();
		
		$date	= CTimeHelper::getDate();
		$db=& JFactory::getDBO();
		
		$sql = "UPDATE #__community_activities SET "
			." `archived`='1' "
			." WHERE DATEDIFF(".$db->Quote($date->toMySQL())." , `created` ) >  ". $db->Quote($config->get('archive_activity_days'))
			." AND `archived`=0 ";
		$db->setQuery($sql);
		$db->query();
	}
	
	function sendEmailsOnPageLoad(){
		CFactory::load('libraries', 'mailq');
		$mailq = new CMailq();
		$mailq->send();
	}
}