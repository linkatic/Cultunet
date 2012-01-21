<?php
/**
 * @category	User
 * @package		JomSocial
 * @copyright (C) 2008 - 2010 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgUserJomSocialUser extends JPlugin
{
	function plgUserJomSocialUser(& $subject, $config)
	{
		include_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
		require_once(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'featured.php');
		require_once(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'videos.php');
		require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'events'.DS.'router.php');
		jimport('joomla.filesystem.folder');
		parent::__construct($subject, $config);
	}

	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @access	public
	 * @param 	array 	holds the user data
	 * @param 	array    extra options
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function onLoginUser($user, $options)
	{		
		CFactory::load( 'helpers' , 'user' );
		
		$id		= cGetUserId( $user['username'] );

		CFactory::setActiveProfile( $id );

		return true;
	}

	/**
	 * This method should handle any logout logic and report back to the subject
	 *
	 * @access public
	 * @param array holds the user data
	 * @return boolean True on success
	 * @since 1.5
	 */
	function onLogoutUser($user)
	{
		CFactory::unsetActiveProfile();
		
		return true;
	}

	function onBeforeDeleteUser($user)
	{
		$mainframe =& JFactory::getApplication();
		$this->deleteFromCommunityUser($user);
		$this->deleteFromCommunityWall($user);
		$groups = $this->deleteFromCommunityGroup($user);
		$this->deleteFromCommunityDiscussion($user, $groups);
		$albums = $this->deleteFromCommunityPhoto($user);
		$this->deleteFromCommunityMsg($user);
		$this->deleteFromCommunityProfile($user);
		$this->deleteFromCommunityConnection($user);
		$this->deleteFromCommunityApps($user);
		$this->deleteFromCommunityActivities($user);
		$videos = $this->deleteFromCommunityVideos($user);
		$this->deleteFromCommunityConnectUsers($user);
		$this->deleteFromCommunityFeatured($user, $groups, $albums, $videos);
	}
	
	/**
	 * Example store user method
	 *
	 * Method is called after user data is stored in the database
	 *
	 * @param 	array		holds the new user data
	 * @param 	boolean		true if a new user is stored
	 */
	function onAfterStoreUser($user, $isnew)
	{
		$mainframe =& JFactory::getApplication();
		
		if ($isnew)
		{
				//User Group
				$data =new stdClass();
				$data->user_id = $user['id'];
				$data->group_id = '2';
				
				//User Vendor
				$data2 =new stdClass();
				$data2->user_id = $user['id'];
				$data2->vendor_id = '1';
				
				//User Shopper Vendor Xref
				$data3 =new stdClass();
				$data3->user_id = $user['id'];
				$data3->vendor_id = '1';
				$data3->shopper_group_id = '5';
				$data3->customer_number = '';
				
				//User Info
				$hash_secret = "VirtueMartIsCool";
				$timestamp = time();
				$data4 =new stdClass();
				$data4->user_info_id = md5(uniqid($hash_secret));
				$data4->user_id = $user['id'];
				$data4->user_email = $user['email'];
				$data4->address_type = 'BT';
				$data4->address_type_name = '-default-';
				$data4->cdate = $timestamp;
				$data4->mdate = $timestamp;
				$data4->perms = 'shopper';
		
				
				$db = JFactory::getDBO();
				
				$db->insertObject( '#__vm_auth_user_group', $data);
				$db->insertObject( '#__vm_auth_user_vendor', $data2);
				$db->insertObject( '#__vm_shopper_vendor_xref', $data3);
				$db->insertObject( '#__vm_user_info', $data4);
			
			if($db->getErrorNum()){
				JError::raiseError( 500, $db->stderr());
			}
		}
			

	}
	
	/**
	 * Remove association when a user is removed
	 **/	 	
	function deleteFromCommunityConnectUsers( $user )
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'DELETE FROM ' . $db->nameQuote( '#__community_connect_users') . ' '
				. 'WHERE ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $user['id'] );
		$db->setQuery( $query );
		$db->query();
	
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}	
	}
	
	function deleteFromCommunityUser($user){		
		$db =& JFactory::getDBO();
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_users")." 
				WHERE 
						".$db->nameQuote("userid")." = ".$db->quote($user['id']);
						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}		
	}
	
	function deleteFromCommunityWall($user){		
		$db =& JFactory::getDBO();
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_wall")." 
				WHERE 
						(".$db->nameQuote("contentid")." = ".$db->quote($user['id'])." OR 
						".$db->nameQuote("post_by")." = ".$db->quote($user['id']).") AND
						".$db->nameQuote("type")." = ".$db->quote('user');						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function deleteFromCommunityDiscussion($user, $gids){		
		$db =& JFactory::getDBO();
		
		if(!empty($gids)){
			$sql = "SELECT 
							".$db->nameQuote("id")." 						
					FROM 
							".$db->nameQuote("#__community_groups_discuss")." 
					WHERE 
							".$db->nameQuote("groupid")." IN (".$gids.")";						
			$db->setQuery($sql);
			$row = $db->loadobjectList();
			if($db->getErrorNum()){
				JError::raiseError( 500, $db->stderr());
			}
			
			if(!empty($row)){		
				$count = 0;
				$scount = sizeof($row) - 1;
				$ids = "";
				foreach($row as $data){
					$ids .= $data->id;
					if($count < $scount){
						$ids .= ",";
					}
					$count++;
				}
			}			
			$condition 	= $db->nameQuote("creator")." = ".$db->quote($user['id'])." OR 
						".$db->nameQuote("groupid")." IN (".$gids.")";
		}else{
			$condition 	= $db->nameQuote("creator")." = ".$db->quote($user['id']);
		}
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_groups_discuss")." 
				WHERE 
						".$condition;						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		if(!empty($ids)){
			$condition = "(".$db->nameQuote("post_by")." = ".$db->quote($user['id'])." OR 
						   ".$db->nameQuote("contentid")." IN (".$ids."))";
		}else{		
			$condition = $db->nameQuote("post_by")." = ".$db->quote($user['id']);
		}
		
		$sql = "DELETE 
					
				FROM 
						".$db->nameQuote("#__community_wall")." 
				WHERE 
						".$condition." AND 
						".$db->nameQuote("type")." = ".$db->quote('discussions');				
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function deleteFromCommunityPhoto($user){		
		$db =& JFactory::getDBO();
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_photos")." 
				WHERE 
						".$db->nameQuote("creator")." = ".$db->quote($user['id']);						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		$sql = "SELECT 
						".$db->nameQuote("id")." 
				FROM 
						".$db->nameQuote("#__community_photos_albums")." 
				WHERE 
						".$db->nameQuote("creator")." = ".$db->quote($user['id']);
						
		$db->setQuery($sql);
		$albums = $db->loadResultArray();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_photos_albums")." 
				WHERE 
						".$db->nameQuote("creator")." = ".$db->quote($user['id']);
						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_photos_tokens")." 
				WHERE 
						".$db->nameQuote("userid")." = ".$db->quote($user['id']);
						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		// delete user's photo folder
		$photoFolder = JPATH_ROOT . DS . 'images' . DS . 'photos' . DS . $user['id'];
		if(JFolder::exists($photoFolder)) {
			JFolder::delete($photoFolder);
		}
		
		return $albums;
	}
	
	function deleteFromCommunityMsg($user){		
		$db =& JFactory::getDBO();
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_msg")." 
				WHERE 
						".$db->nameQuote("from")." = ".$db->quote($user['id']);						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_msg_recepient")." 
				WHERE 
						".$db->nameQuote("msg_from")." = ".$db->quote($user['id'])." OR 
						".$db->nameQuote("to")." = ".$db->quote($user['id']);
						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function deleteFromCommunityGroup($user){		
		$db =& JFactory::getDBO();
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_groups_bulletins")." 
				WHERE 
						".$db->nameQuote("created_by")." = ".$db->quote($user['id']);
						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}		
		
		$sql = "SELECT 
						".$db->nameQuote("id")." 						
				FROM 
						".$db->nameQuote("#__community_groups")." 
				WHERE 
						".$db->nameQuote("ownerid")." = ".$db->quote($user['id']);						
		$db->setQuery($sql);
		$row = $db->loadobjectList();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		if(!empty($row)){		
			$count = 0;
			$scount = sizeof($row) - 1;
			$ids = "";
			foreach($row as $data){
				$ids .= $data->id;
				if($count < $scount){
					$ids .= ",";
				}
				$count++;
			}
					
			$sql = "DELETE 
					
					FROM 
							".$db->nameQuote("#__community_groups_members")." 
					WHERE 
							".$db->nameQuote("groupid")." IN (".$ids.") OR 
							".$db->nameQuote("memberid")." = ".$user['id'];						
			$db->setQuery($sql);
			$db->Query();
			if($db->getErrorNum()){
				JError::raiseError( 500, $db->stderr());
			}
		}
					
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_groups")." 
				WHERE 
						".$db->nameQuote("ownerid")." = ".$db->quote($user['id']);						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_wall")." 
				WHERE 
						".$db->nameQuote("post_by")." = ".$db->quote($user['id'])." AND
						".$db->nameQuote("type")." = ".$db->quote('groups');						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		$ids = empty($ids)? "" : $ids;
		
		return $ids;
	}
	
	function deleteFromCommunityProfile($user){		
		$db =& JFactory::getDBO();
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_fields_values")." 
				WHERE 
						".$db->nameQuote("user_id")." = ".$db->quote($user['id']);
						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function deleteFromCommunityConnection($user){		
		$db =& JFactory::getDBO();
		
		$sql = "SELECT 
						a.".$db->nameQuote("connect_from")." 						
				FROM 
						".$db->nameQuote("#__community_connection")." a
			INNER JOIN 
						".$db->nameQuote("#__community_connection")." b ON a.".$db->nameQuote("connect_from")."=b.".$db->nameQuote("connect_to")."
				WHERE 
						a.".$db->nameQuote("connect_to")." = ".$db->quote($user['id']) ." AND 
						b.".$db->nameQuote("connect_from")." = ".$db->quote($user['id']);						
		$db->setQuery($sql);
		$row = $db->loadobjectList();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		if(!empty($row)){
			$count = 0;
			$scount = sizeof($row) - 1;
			$ids = "";				
			foreach($row as $data){
				$ids .= $data->connect_from;
				if($count < $scount){
					$ids .= ", ";
				}
				$count++;
			}		
			
			$sql = "UPDATE
							".$db->nameQuote("#__community_users")." 				
					SET 
							".$db->nameQuote("friendcount")." = ".$db->nameQuote("friendcount")." - 1 
					WHERE 
							".$db->nameQuote("userid")." IN (".$ids.")";						
			$db->setQuery($sql);
			$db->Query();
			if($db->getErrorNum()){
				JError::raiseError( 500, $db->stderr());
			}
		}
		
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_connection")." 
				WHERE 
						".$db->nameQuote("connect_from")." = ".$db->quote($user['id'])." OR 
						".$db->nameQuote("connect_to")." = ".$db->quote($user['id']);						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function deleteFromCommunityApps($user){		
		$db =& JFactory::getDBO();
				
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_apps")." 
				WHERE 
						".$db->nameQuote("userid")." = ".$db->quote($user['id']);						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function deleteFromCommunityActivities($user)
	{		
		$db =& JFactory::getDBO();
				
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_activities")." 
				WHERE 
						(".$db->nameQuote("actor")." = ".$db->quote($user['id'])." OR 
						".$db->nameQuote("target")." = ".$db->quote($user['id']).") AND 
						".$db->nameQuote("archived")." = ".$db->quote(0);								
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	function deleteFromCommunityVideos($user)
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT '.$db->nameQuote('id').' FROM ' . $db->nameQuote('#__community_videos')
				. ' WHERE creator = ' . $db->quote($user['id']);
		$db->setQuery($query);
		$videos = $db->loadResultArray();
		
		$query	= 'DELETE FROM ' . $db->nameQuote('#__community_videos')
				. ' WHERE creator = ' . $db->quote($user['id']);
		$db->setQuery($query);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		$videoLib 	= new CVideoLibrary();
		
		// Converted Videos Folder
		$videoFolder	= $videoLib->videoRootHome . DS . $user['id'];
		if(JFolder::exists($videoFolder)) {
			JFolder::delete($videoFolder);
		}
		// Original Videos Folder
		$videoFolder	= $videoLib->videoRootOrig . DS . $user['id'];
		if(JFolder::exists($videoFolder)) {
			JFolder::delete($videoFolder);
		}
		
		return $videos;
	}
	
	function deleteFromCommunityFeatured($user, $groups, $albums, $videos)
	{    	
    	//delete featured user
		$featured = new CFeatured( FEATURED_USERS );    	
    	if(!empty($user))
    	{
    		$featured->delete($user['id']);
		}
		
		//delete featured groups
		$featured	= new CFeatured( FEATURED_GROUPS );    	
    	if(!empty($groups))
    	{
    		$groupIds = explode(",", $groups);    		
    		foreach($groupIds as $groupId)
    		{
    			$featured->delete($groupId);
    		}
		}
		
		//delete featured albums
		$featured	= new CFeatured( FEATURED_ALBUMS );    	
    	if(!empty($albums))
    	{
    		foreach($albums as $albumId)
    		{
    			$featured->delete($albumId);
    		}
		}
		
		//delete featured albums
		$featured	= new CFeatured( FEATURED_VIDEOS );    	
    	if(!empty($videos))
    	{
    		foreach($videos as $videoId)
    		{
    			$featured->delete($videoId);
    		}
		}
	}
}
