<?php
/**
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'models.php' );

/**
 * Jom Social Model file for photo tagging
 */

class CommunityModelPhotoTagging extends JCCModel
{

// 	var $id 			= null;
// 	var $photoid		= null;
// 	var $userid 		= null;
// 	var $position		= null;
// 	var $created_by 	= null;  
// 	var $created 		= null;
	var	$_error	= null;
	
	function getError()
	{
		return $this->_error;
	}

	function isTagExists($photoId, $userId)
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) AS CNT FROM `#__community_photos_tag`';
		$query .= ' WHERE `photoid` = ' . $db->Quote($photoId);
		$query .= ' AND `userid` = ' . $db->Quote($userId);
		
		$db->setQuery($query);
		
		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		$result = $db->loadResult();
		return (empty($result)) ? false : true;
	}
	
	
	function addTag( $photoId, $userId, $position)
	{
		$db		=& $this->getDBO();
		$my		= CFactory::getUser();
		$date	=& JFactory::getDate(); //get the time without any offset!
		
		$data				= new stdClass();
		$data->photoid		= $photoId;
		$data->userid		= $userId;
		$data->position		= $position;
		$data->created_by	= $my->id;
		$data->created		= $date->toMySQL();
		
		
		if($db->insertObject( '#__community_photos_tag' , $data))
		{
			//reset error msg.
			$this->_error	= null;
			return true;
		}
		else
		{
			$this->_error	= $db->stderr();
			return false;	
		}
	}
	
	function removeTag( $photoId, $userId )
	{
		$db		=& $this->getDBO();			
		
		$query = 'DELETE FROM `#__community_photos_tag`';
		$query .= ' WHERE `photoid` = ' . $db->Quote($photoId);
		$query .= ' AND `userid` = ' . $db->Quote($userId);		
		
		$db->setQuery($query);
		$db->query();
		
		if($db->getErrorNum())
		{
			$this->_error	= $db->stderr();
			return false;
		}		

		return true;
	}
	
	public function removeTagByPhoto($photoId)
	{
		$db		=& $this->getDBO();			
		
		$query = 'DELETE FROM `#__community_photos_tag`';
		$query .= ' WHERE `photoid` = ' . $db->Quote($photoId);
		
		$db->setQuery($query);
		$db->query();
		
		if($db->getErrorNum())
		{
			$this->_error	= $db->stderr();
			return false;
		}		

		return true;
	}
	
	function getTagId( $photoId, $userId )
	{
		$db		=& $this->getDBO();			
		
		$query = 'SELECT `id` FROM `#__community_photos_tag`';
		$query .= ' WHERE `photoid` = ' . $db->Quote($photoId);
		$query .= ' AND `userid` = ' . $db->Quote($userId);		
		
		$db->setQuery($query);				
		
		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		$result = $db->loadResult();
				
		return $result;
	}	
	
	
	function getTaggedList( $photoId )
	{
		$db =& $this->getDBO();		
	
		$query = 'SELECT * FROM `#__community_photos_tag`';
		$query .= ' WHERE `photoid` = ' . $db->Quote($photoId);
		$query .= ' ORDER BY `id`';
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}		
		
		return $result;
	}
	
	function getFriendList( $photoId )
	{
		$db =& $this->getDBO();
		$my	= CFactory::getUser();		
				
		$query	= 'SELECT DISTINCT(a.`connect_to`) AS id ';
		$query .= ' FROM `#__community_connection` AS a';
		$query .= ' INNER JOIN `#__users` AS b';
		$query .= ' ON a.`connect_from` = ' . $db->Quote( $my->id ) . ' ';
		$query .= ' AND a.`connect_to` = b.`id` ';
		$query .= ' AND a.`status` = 1';
		$query .= ' AND NOT EXISTS (';
		$query .= ' SELECT `userid` FROM `#__community_photos_tag` AS c WHERE c.`userid` = a.`connect_to` AND c.`photoid` = ' . $db->Quote( $photoId );
		$query .= ')';
						
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}		
		
		return $result;
	}
	
	
	

}

?>