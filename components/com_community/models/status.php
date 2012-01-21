<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	News feed
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'models.php' );

/**
 *
 */ 
class CommunityModelStatus extends JCCModel
{
	/**
	 * Update the user status
	 * 
	 * @param	int		user id
	 * @param	string	the message. Should be < 140 char (controller check)	 	 	 
	 */ 	 	
	function update($id, $status){
		$db	= &$this->getDBO();
		$my	= CFactory::getUser();
		// @todo: posted_on should be constructed to make sure we take into account
		// of Joomla server offset
		
		// Current user and update id should always be the same
		CError::assert( $my->id, $id, 'eq', __FILE__ , __LINE__ );
		
		// Trigger onStatusUpdate
		require_once( COMMUNITY_COM_PATH.DS.'libraries' . DS . 'apps.php' );
	
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();
		
		$args 	= array();
		$args[]	= $my->id;			// userid
		$args[]	= $my->getStatus();	// old status
		$args[]	= $status;			// new status
		$appsLib->triggerEvent( 'onProfileStatusUpdate' , $args );
		
		$today	=& JFactory::getDate();
		$data	= new stdClass();
		$data->userid		= $id;
		$data->status		= $status; 		
		$data->posted_on    = $today->toMySQL();
		
		$db->updateObject( '#__community_users' , $data , 'userid' );
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	/**
	 * Get the user status
	 * 
	 * @param int	userid
	 * 
	 * @todo: this should return the status object. Use Jtable for this	 	 	 	 
	 */	 	
	function get($id, $limit=1){
		$db	= &$this->getDBO();
		
		$sql = "SELECT * from #__community_users "
			." WHERE `userid`=" . $db->Quote($id)
			." ORDER BY `posted_on` DESC LIMIT {$limit}";
		
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		
		// Return the first row
		if(!empty($result)){
			$result= $result[0];
		} else {
			$result = new stdClass();
			$result->status = '';
		}
		
		
		return $result;
	}
	
}
