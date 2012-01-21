<?php
/**
 * @category	Tables
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CTablePhoto extends JTable 
{
	var $id = null;
	var $albumid = null;
  	var $caption = null;
	var $permissions = null;
	var $created = null;
	var $thumbnail = null;
	var $image = null;
	var $creator = null;
	var $published = null;
	var $original	= null;
	var $filesize	= null;
	var $storage	= 'file';
  	
	/**
	 * Constructor
	 */	 	
	public function __construct( &$db ) {
		parent::__construct( '#__community_photos', 'id', $db );
	}

	/**
	 *	Allows us to test if the user has access to the album
	 **/	 	
	public function hasAccess( $userId , $permissionType )
	{
		CFactory::load( 'helpers' , 'owner' );
	
		// @rule: For super admin, regardless of what permission, they should be able to access
		if( COwnerHelper::isCommunityAdmin() )
			return true;

		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $this->albumid );
		
		switch( $album->type )
		{
			case PHOTOS_USER_TYPE:
			
				if( $permissionType == 'delete' )
				{
					return $this->creator == $userId; 
				}
				
			break;
			case PHOTOS_GROUP_TYPE:
				
				CFactory::load( 'models' , 'groups' );
				$group	=&JTable::getInstance( 'Group' , 'CTable' );
				$group->load( $album->groupid );
				
				if( $permissionType == 'delete' )
				{
					return $album->creator == $userId || $group->isAdmin( $userId );
				}
				
				return false;
			break;
		}
	}
	
	public function check()
	{
		// Santinise data
		$safeHtmlFilter		= CFactory::getInputFilter();
		$this->caption 	= $safeHtmlFilter->clean($this->caption);
		
		return true;
	}
	
	/**
	 * Overrides parent store function as we need to clean up some variables
	 **/
	public function store()
	{
		if (!$this->check()) {
			return false;
		}
		
		$this->image		= JString::str_ireplace( '\\' , '/' , $this->image );
		$this->thumbnail	= JString::str_ireplace( '\\' , '/' , $this->thumbnail );
		$this->original		= JString::str_ireplace( '\\' , '/' , $this->original );

		return parent::store();
	}
	
	/**
	 * Delete the photo, the original and the actual resized photos and thumbnails
	 */	 	
	public function delete()
	{
		
		CFactory::load('libraries', 'storage');
		$storage = CStorage::getStorage($this->storage);
		$storage->delete($this->image);
		$storage->delete($this->thumbnail);
		JFile::delete(JPATH_ROOT.DS.$this->original);
		
		// If the original path is empty, we can delete it too
		$files = JFolder::files( dirname(JPATH_ROOT.DS.$this->original) );
		if(empty($files)){
			JFolder::delete( dirname(JPATH_ROOT.DS.$this->original) );
		}
		
		// if the photo is the album cover, set the album cover as default 0
		$album	=& JTable::getInstance('Album', 'CTable');
		$album->load($this->albumid);
		$album->set('photoid', 0);
		$album->store();
		
		// delete the tags
		CFactory::load('libraries', 'phototagging');
		CPhotoTagging::removeTagByPhoto($this->id);
		
		// delete the activities
		CFactory::load('libraries', 'activities');
		CActivities::remove('photos', $this->id);
		
		// delete the comments
		$wall	=& CFactory::getModel('wall');
		$wall->deleteAllChildPosts($this->id, 'photos');
		
		return parent::delete();
	}
	/**
	 * Return the exact URL  
	 */	 	
	public function getThumbURI()
	{
		
		// if the photoid = 0, we return the default thumb path
		if( empty($this->id))
		{
			return rtrim( JURI::root() , '/' ) . '/components/com_community/assets/album_thumb.jpg';
		}
		
		CFactory::load('libraries', 'storage');
		$uri = '';
		if($this->storage != 'file')
		{
			$storage = CStorage::getStorage($this->storage);
			$uri = $storage->getURI($this->thumbnail);
		}
		else
		{
			$config		=& CFactory::getConfig();
			$baseUrl	= $config->get('photobaseurl');
			
			if( empty( $baseUrl) )
			{
				$baseUrl	= JURI::root();
			}
			
			// Strip cdn path if necessary
			$path = $this->thumbnail;		
			
			$cdnpath = $config->get('photocdnpath');
			if($cdnpath)
			{
				$path = str_replace($cdnpath, '',$path);
			}
			$path	= ltrim( $path , '/' );
			$uri 	= rtrim( $baseUrl, '/' ) . '/' . $path;
		}
		return $uri;
		
	}
	
	public function getPhotoLink()
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $this->albumid );
		
		$url	= 'index.php?option=com_community&view=photos&task=photo&albumid=' . $album->id;
		$url	.= $album->groupid ? '&groupid=' . $album->groupid : '&userid=' . $album->creator;
		
		return CRoute::_( $url ) . '#photoid=' . $this->id;
	}
	
	/**
	 * Return the exist URL to be displayed.
	 * @param size string, (normal, origianl, small)	 
	 */	 	
	public function getImageURI($size = 'normal')
	{
		CFactory::load('libraries', 'storage');
		$uri = '';
		if($this->storage != 'file')
		{
			$storage = CStorage::getStorage($this->storage);
			$uri = $storage->getURI($this->image);
		}
		else
		{
			$config		=& CFactory::getConfig();
			$baseUrl	= $config->get('photobaseurl');

			if( !empty( $baseUrl) )
			{
				if(JFile::exists (JPATH_ROOT . DS .$this->image))
				{
					//$uri	= rtrim( $baseUrl , '/' ) . '/' . $this->image;
					
					// Strip cdn path if necessary
					$path = $this->image;		
					
					$cdnpath = $config->get('photocdnpath');
					if($cdnpath)
					{
						$path = str_replace($cdnpath, '',$path);
					}
					$path	= ltrim( $path , '/' );
					$uri 	=  rtrim( $baseUrl, '/' ) . '/' . $path;
				}
				else
				{
					$uri	= JURI::root() . 'index.php?option=com_community&view=photos&task=showimage&tmpl=component&imgid='. $this->image;
				}
			}
			else
			{
				// If the resized photos exist, use it instead
				if(JFile::exists (JPATH_ROOT . DS .$this->image))
				{
					$uri = JURI::root() .'/'. $this->image;
				}
				else 
				{
					$uri = JURI::root() . 'index.php?option=com_community&view=photos&task=showimage&tmpl=component&imgid='. $this->image;
				}
			}
		}
		return $uri;
	}
	
	/**
	 * Return the URI of the original photo
	 */	 	
	public function getOriginalURI()
	{
		if( JFile::exists( JPATH_ROOT . DS . $this->original) )
		{
			return rtrim( JURI::root() , '/' ) . '/' . $this->original;
		}
		
		// If original url was deleted, we use the resized image as the original photo
		return rtrim( JURI::root() , '/' ) . '/' . $this->image;
	}
	
	// Load the Photo object given the image path
	public function loadFromImgPath($path)
	{
		$db	=& JFactory::getDBO();
		
		$strSQL	= 'SELECT (id) FROM ' . $db->nameQuote('#__community_photos') 
				. 'WHERE `image`=' . $db->Quote($path) ;

		//echo $strSQL;
		$db->setQuery($strSQL);
		$result	= $db->loadObject();
		
		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}

		// Backward compatibility because anything prior to this version uses id
		// @since 1.6
		if( !$result )
		{
			$id		= $path;
			$this->load( $id );
			return $id;
		}

		$this->load($result->id);
		return $result;
	}
}
