<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Profile
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'models.php' );

class CommunityModelUser extends JCCModel
{
	var $_data = null;
	var $_userpref = array();

	/**
	 * Return the username given its userid
	 * @param int	userid	 
	 */	 	
	function getUsername($id){
		$db	 = &$this->getDBO();
		$sql = "SELECT `username` FROM #__users WHERE `id`=" . $db->Quote($id);
		$db->setQuery($sql);
		
		$result = $db->loadResult();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result; 
	}
	
	/**
	 * Return the user fullname given its userid
	 * @param int	userid
	 */
	function getUserFullname($id){
		$db	 = &$this->getDBO();
		$sql = "SELECT `name` FROM #__users WHERE `id`=" .  $db->Quote($id);
		$db->setQuery($sql);

		$result = $db->loadResult();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
	}
	
	/**
	 * Return the userid given its name
	 */	 	
	function getUserId($username, $useRealName	= false){
		$db	 = &$this->getDBO();
		
		$param	= 'username';
		
		if($useRealName)
			$param = 'name';
			
		$sql = "SELECT `id` FROM #__users WHERE " . $db->nameQuote($param) . "=" . $db->Quote($username);
			
		$db->setQuery($sql);
		$result = $db->loadResult();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result; 
	}

	/**
	 * Return the user's email given its id
	 */	 	
	function getUserEmail($id){
		$db	 = &$this->getDBO();
		
		$query = "SELECT `email` FROM #__users WHERE `id`=" . $db->Quote($id);
		$db->setQuery($query);
		$result = $db->loadResult();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result; 
	}
	
	function getMembersCount()
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__users' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'block' ) . '=' . $db->Quote( 0 );
				
		$db->setQuery( $query );
		
		$result	= $db->loadResult();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result;
	}
	
	/**
	 * Return the basic user profile
	 */	 	
	function getLatestMember($limit = 15)
	{
		if ($limit == 0) return array();
		$limit = ($limit < 0) ? 0 : $limit;
		
		$db	 = &$this->getDBO();
		
		$query	= 'SELECT * FROM ' . $db->nameQuote( '#__users' ) . ' ' 
				. 'WHERE ' . $db->nameQuote( 'block' ) . '=' . $db->Quote( 0 ) . ' '
				. 'ORDER BY ' . $db->nameQuote( 'registerDate' ) . ' '
				. 'DESC LIMIT ' . $limit;
		$db->setQuery( $query );
		
		$result = $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		
		$latestMembers = array();
		
		$uids = array();
		foreach($result as $m)
		{
			$uids[] = $m->id;
		}
		CFactory::loadUsers($uids);
		
		foreach( $result as $row )
		{
			$latestMembers[] = CFactory::getUser($row->id);
		}
		return $latestMembers;
	}
	
	function getActiveMember($limit = 15)
	{
		$uid = array();
		$uid_str = "";
		$db	 = &$this->getDBO();		
			
		$query = " 	 SELECT 
							b.*,
							a.".$db->nameQuote('actor').", 
							COUNT(a.".$db->nameQuote('id').") AS ".$db->nameQuote('count')." 
					   FROM 
							".$db->nameQuote('#__community_activities')." a
				 INNER JOIN	".$db->nameQuote('#__users')." b
					  WHERE 
							a.".$db->nameQuote('app')." != ".$db->quote('groups')." AND
							b.".$db->nameQuote('block')." = ".$db->quote('0')." AND
							a.".$db->nameQuote('archived')." = ".$db->quote('0')." AND
							a.".$db->nameQuote('actor')." = b.".$db->nameQuote('id')."  
				   GROUP BY a.".$db->nameQuote('actor')."
				   ORDER BY ".$db->nameQuote('count')." DESC
				   LIMIT ".$limit;
		$db->setQuery( $query );
		$result = $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		$latestMembers = array();
		
		foreach( $result as $row )
		{
			$latestMembers[] = CFactory::getUser($row->id);
		}
		return $latestMembers;
	}
	
	function getPopularMember($limit = 15)
	{
		$uid = array();
		$uid_str = "";
		$db	 = &$this->getDBO();		
			
		$query = " 	 SELECT b.*
					   FROM 
							".$db->nameQuote('#__community_users')." a
				 INNER JOIN	".$db->nameQuote('#__users')." b
					  WHERE 
							b.".$db->nameQuote('block')." = ".$db->quote('0')." AND
							a.".$db->nameQuote('userid')." = b.".$db->nameQuote('id')."  
				   ORDER BY a.".$db->nameQuote('view')." DESC
				   LIMIT ".$limit;
		$db->setQuery( $query );
		$result = $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		$latestMembers = array();
		
		foreach( $result as $row )
		{
			$latestMembers[] = CFactory::getUser($row->id);
		}
		return $latestMembers;
	}
	
	// Return JDate object of last login date
	function lastLogin($userid){
	}

	/**
	 * is the email exits 
	 */	 	
	function userExistsbyEmail( $email ) {
		$db	= &$this->getDBO();
		$sql = "SELECT count(*) from #__users"
				." WHERE `email`= " . $db->Quote($email);
			
			$db->setQuery($sql);
			$result = $db->loadResult();
			return $result;
	}
	
	/**
	 * Save user data. 
	 */	 	
	function updateUser( &$obj )
	{
		$db	= &$this->getDBO();
		return $db->updateObject( '#__community_users', $obj, 'userid');
	}
	
	/**
	 *	Set the avatar for specific application. Caller must have a database table
	 *	that is named after the appType. E.g, users should have jos_community_users	 
	 *	
	 * @param	appType		Application type. ( users , groups )
	 * @param	path		The relative path to the avatars.
	 * @param	type		The type of Image, thumb or avatar.
	 *
	 **/	 	 
	function setImage(  $id , $path , $type = 'thumb' )
	{
		CError::assert( $id , '' , '!empty' , __FILE__ , __LINE__ );
		CError::assert( $path , '' , '!empty' , __FILE__ , __LINE__ );
		
		$db			=& $this->getDBO();
		
		// Fix the back quotes
		$path		= JString::str_ireplace( '\\' , '/' , $path );
		$type		= JString::strtolower( $type );
		
		// Test if the record exists.
		$query		= 'SELECT ' . $db->nameQuote( $type ) . ' FROM ' . $db->nameQuote( '#__community_users' )
					. 'WHERE ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $id );
		
		$db->setQuery( $query );
		$oldFile	= $db->loadResult();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
	    }
	    
	    $appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();		
		$args 	= array();
		$args[]	= &$id;			// userid
		$args[]	= &$oldFile;	// old path
		$args[]	= &$path;		// new path
		$appsLib->triggerEvent( 'onProfileAvatarUpdate' , $args );
	    
	    if( !$oldFile )
	    {
	    	$query	= 'UPDATE ' . $db->nameQuote( '#__community_users' ) . ' '
	    			. 'SET ' . $db->nameQuote( $type ) . '=' . $db->Quote( $path ) . ' '
	    			. 'WHERE ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $id );
	    	$db->setQuery( $query );
	    	$db->query( $query );

			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
		    }
		}
		else
		{
	    	$query	= 'UPDATE ' . $db->nameQuote( '#__community_users' ) . ' '
	    			. 'SET ' . $db->nameQuote( $type ) . '=' . $db->Quote( $path ) . ' '
	    			. 'WHERE ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $id );
	    	$db->setQuery( $query );
	    	$db->query( $query );
	    	
			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
		    }
		    
			// If old file is default_thumb or default, we should not remove it.
			// Need proper way to test it
			if(!JString::stristr( $oldFile , 'components/com_community/assets/default.jpg' ) && !JString::stristr( $oldFile , 'components/com_community/assets/default_thumb.jpg' ) )
			{
				// File exists, try to remove old files first.
				$oldFile	= JString::str_ireplace( '/' , DS , $oldFile );			
				JFile::delete($oldFile);	
			}
		}
	}
	
	/**
	 * Return array of profile variables 
	 */	 	
	function getProfile(){
	}
	
	/**
	 * Store user latitude/longitude data
	 */	 	
	function storeLocation($userid, $lat = null, $long=null)
	{
		$db		=& $this->getDBO();
		$query 	= "UPDATE ". $db->nameQuote('#__community_users')
				  . " SET ". $db->nameQuote( 'latitude') ."=". $db->Quote( $lat ). " , "
				  . $db->nameQuote( 'longitude') ."=". $db->Quote( $long )
				  . " WHERE "
				  . $db->nameQuote( 'userid') ."=". $db->Quote( $userid );
		$db->setQuery($query);
		$db->query();
	}
	
	function getOnlineUsers( $limit = 15 , $backendUsers = false )
	{
		$db		=& $this->getDBO();
		

		$query	= 'SELECT DISTINCT(a.id)'
				. 'FROM ' . $db->nameQuote( '#__users' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__session') . ' AS b '
				. 'ON a.id=b.userid '
				. 'WHERE a.block=' . $db->Quote( '0' ) . ' ';
				
		if( !$backendUsers )
		{
			$query	.= 'AND client_id != ' . $db->Quote( 1 );
		}
		
		$query	.= 'ORDER BY b.time DESC '
				. 'LIMIT ' . $limit;

		$db->setQuery( $query );
		$result	= $db->loadObjectList();
				
		return $result;
	}

	function getSuperAdmins()
	{
		$db		=& $this->getDBO();
		
		$query = 'SELECT * ' .
				'FROM #__users ' .
				'WHERE LOWER( usertype ) = "super administrator"';
				
		$db->setQuery( $query );
		$users	= $db->loadObjectList();
		return $users;
		
	}
	
	/**
	 * Return userid from the given email. If none is found, return 0
	 */	 	
	function getUserFromEmail($email)
	{
		$db		=& $this->getDBO();
		$email = strtolower($email);
		
		$query = 'SELECT id ' .
				'FROM #__users ' .
				'WHERE LOWER( email ) = '.  $db->Quote( $email );
				
		$db->setQuery( $query );
		$userid	= $db->loadResult();
		
		return $userid;
	}
}
