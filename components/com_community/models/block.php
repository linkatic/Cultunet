<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Ban User
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'models.php' );

class CommunityModelBlock extends JCCModel
{
	/**
	 * check if the user is ban
	 */	 	
	function getBlockStatus($myId,$userId)
	{
		$db	=& $this->getDBO();
		
		$query	= "SELECT `id` FROM `#__community_blocklist` "
				. " WHERE `blocked_userid`=" . $db->Quote($myId)
				. " AND `userid`=" . $db->Quote($userId);	

		$db->setQuery( $query );
		$result	= $db->loadObject() ? true : false;

		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		return $result;
	}
	
	/**
	 * ban a user
	 */	 	
	function blockUser($myId,$userId)
	{
		$db	=& $this->getDBO();
		
		// check if user is banned
		if( !$this->getBlockStatus($userId,$myId) ){
		
			$query	= "INSERT INTO `#__community_blocklist` "
					. " SET `blocked_userid`=" . $db->Quote($userId)
					. " , `userid`=" . $db->Quote($myId);	
	
			$db->setQuery( $query );
			$db->query();
	
			if($db->getErrorNum())
			{
				JError::raiseError(500, $db->stderr());
			}
			
			return true;
						
		}
		
	}
	
	/**
	 * remove ban a user (unban)
	 */	 	
	function removeBannedUser($myId,$userId)
	{
		$db	=& $this->getDBO();
		
		// check if user is banned
		if( $this->getBlockStatus($userId,$myId) ){
		
			$query	= "DELETE FROM `#__community_blocklist` "
					. " WHERE `blocked_userid`=" . $db->Quote($userId)
					. " AND `userid`=" . $db->Quote($myId);	
	
			$db->setQuery( $query );
				$db->query();
	
			if($db->getErrorNum())
			{
				JError::raiseError(500, $db->stderr());
			}   
			
			return true;
			
		}
		
	}
	
	/**
	 * get list of ban user
	 */
	function getBanList($myId)
	{
		$db	=& $this->getDBO();
		
		$query	= "SELECT m.*,n.`name` FROM `#__community_blocklist` m "
				. "LEFT JOIN `#__users` n ON m.`blocked_userid`=n.`id` "
				. "WHERE m.`userid`=" . $db->Quote($myId) . " "
                . "AND m.`blocked_userid`!=0";
		$db->setQuery( $query );
		
		$result	= $db->loadObjectList();
	
		return $result;
	}	 	 	
					
}
