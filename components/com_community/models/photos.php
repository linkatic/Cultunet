<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once ( JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'models'.DS.'models.php');

// Deprecated since 1.8.x to support older modules / plugins
CFactory::load( 'tables' , 'album' );
CFactory::load( 'tables' , 'photo' );

class CommunityModelPhotos extends JCCModel
{
	var $_pagination;
	var $total;
	var $test;
	
	function CommunityModelPhotos()
	{
		parent::JCCModel();
 	 	global $option;
 	 	$mainframe =& JFactory::getApplication();
 	 	
 	 	// Get pagination request variables
 	 	$limit		= ($mainframe->getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');
	    $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');
 	 	
 	 	// In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
 	 	$this->setState('limit',$limit);
 	 	$this->setState('limitstart',$limitstart);
	}
	
	function cleanUpTokens()
	{
		$date	= JFactory::getDate();
		$db		=& $this->getDBO();
		
		$query	= 'DELETE FROM ' . $db->nameQuote( '#__community_photos_tokens' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'datetime' ) . '<= DATE_SUB(' . $db->Quote( $date->toMySQL() ) . ', INTERVAL 1 HOUR)'; 
		
		$db->setQuery($query);
		$db->query();
	}

	function getUserUploadToken( $userId )
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT * FROM '
				. $db->nameQuote( '#__community_photos_tokens' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $userId );
				
		$db->setQuery( $query );
		$result	= $db->loadObject();
		
		return $result;
	}
	
	function addUserUploadSession( $token )
	{
		$db		=& JFactory::getDBO();
		
		$db->insertObject( '#__community_photos_tokens' , $token );
	}
	
	function update( $data , $type = 'photo' )
	{
		// Set creation date
		if(!isset($data->created))
		{
			$today			=& JFactory::getDate();
			$data->created	= $today->toMySQL();
		}
		
		if(isset($data->id) && $data->id != 0 )
			$func	= '_update' . JString::ucfirst($type);
		else
			$func	= '_create' . JString::ucfirst($type);

		return $this->$func($data);
	}
	
	// A user updated his view permission, change the permission level for
	// all album and photos
	function updatePermission($userid, $permission){
		$db	=& $this->getDBO();
		$query = 'UPDATE #__community_photos_albums SET `permissions`='
				  . $db->Quote( $permission ) 
				  . ' WHERE `creator`='
				  . $db->Quote( $userid );
		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		$query = 'UPDATE #__community_photos SET `permissions`='
				  . $db->Quote( $permission ) 
				  . ' WHERE `creator`='
				  . $db->Quote( $userid );
		$db->setQuery( $query );
		$db->query();
		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
	}
	
	function _createPhoto($data)
	{
		$db	=& $this->getDBO();
		
		// Fix the directory separators.
		$data->image		= JString::str_ireplace( '\\' , '/' , $data->image );
		$data->thumbnail	= JString::str_ireplace( '\\' , '/' , $data->thumbnail );

		$db->insertObject( '#__community_photos' , $data );

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		$data->id				= $db->insertid();
		
		return $data;
	}
	
	function _createAlbum($data)
	{
		$db	=& $this->getDBO();

		// New record, insert it.
		$db->insertObject( '#__community_photos_albums' , $data );

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		$data->id				= $db->insertid();
		
		return $data;
	}
	
	function _updateAlbum($data)
	{
	}
	
	function _updatePhoto($data)
	{
	}

	/**
	 * Removes a photo from the database and the file.
	 * 	 	
	 * @access	public
	 * @param	string 	User's id.
	 * @returns boolean true upon success.
	 */	 	 	
	function removePhoto( $id , $type = PHOTOS_USER_TYPE )
	{
		$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $id );
		$photo->delete();
	}
	
	function get($id , $type = 'photos')
	{
		$func	= '_get' . JString::ucfirst($type);
		return $this->$func($id);
	}

	function getPagination()
	{
		return $this->_pagination;
	}

	/**
	 * Return a list of photos from specific album
	 *
	 * @param	int	$id	The album id that we want to retrieve photos from
	 */	 
	function getAllPhotos( $albumId = null , $photoType = PHOTOS_USER_TYPE, $limit = null , $permission=null , $orderType = 'DESC' )
	{
		$db		=& $this->getDBO();
		
		$where	= ' WHERE b.`type` = ' . $db->Quote($photoType);

		if( !is_null($albumId) )
		{
			$where	.=	' AND b.`id`'
					.	'=' . $db->Quote( $albumId )
					.	' AND a.`albumid`'
					.	'=' . $db->Quote( $albumId );												
		}
		
		// Only apply the permission if explicitly specified	
		if( !is_null($permission) ) 
		{
			$where	.= ' AND a.`permissions`'
				. '=' . $db->Quote( $permission );
		}
		
		$where	.= ' AND a.`published`=' . $db->Quote( 1 );
		$limitWhere	= '';
		
		if( !is_null($limit) )
		{
			$limit		= ($limit < 0) ? 0 : $limit;
			$limitWhere	.= ' LIMIT ' . $limit;
		}
		
		$query	= 'SELECT a.* FROM ' . $db->nameQuote( '#__community_photos') . ' AS a';
		$query	.= ' INNER JOIN ' . $db->nameQuote( '#__community_photos_albums') . ' AS b';
		$query	.= ' ON a.`albumid` = b.`id`';
		$query	.= $where;
		$query	.= ' ORDER BY  a.`ordering` , a.`created` ' . $orderType;
		$query	.= $limitWhere;				

		$db->setQuery( $query );
		
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result;
	}
	
	/**
	 * Return a list of photos from specific album
	 *
	 * @param	int	$id	The album id that we want to retrieve photos from
	 */	 	
	function getPhotos( $id , $limit = null , $limitstart = null )
	{
		$db		=& $this->getDBO();
		
		// Get limit
		$limit		= ( is_null( $limit ) ) ? $this->getState('limit') : $limit;
		$limitstart	= ( is_null( $limitstart ) ) ? $this->getState( 'limitstart' ) : $limitstart;

		// Get total photos from specific album
		$query		= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_photos') . ' '
					. 'WHERE ' . $db->nameQuote( 'albumid' ) . '=' . $db->Quote( $id );
		
		$db->setQuery( $query );
		$total		= $db->loadResult();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		// Appy pagination
		if( empty($this->_pagination) )
		{
	 	    jimport('joomla.html.pagination');
	 	    $this->_pagination = new JPagination($total, $limitstart, $limit);
	 	}
	 	//var_dump($limitstart);
		// Get all photos from specific albumid
		$query		= 'SELECT * FROM ' . $db->nameQuote( '#__community_photos') . ' '
					. 'WHERE ' . $db->nameQuote( 'albumid' ) . '=' . $db->Quote( $id ) . ' '
					. 'AND `published`=' . $db->Quote( 1 )
 					. ' ORDER BY `ordering` ASC '
					. 'LIMIT ' . $limitstart . ',' . $limit;

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result;
	}
	
	/**
	 * @param	integer albumid Unique if of the album
	 */	 	
	function getAlbum( $albumId )
	{
		$album	=& JTable::getInstance( 'Album' , 'CTable' );
		$album->load( $albumId );
		
		return $album;
	}

	/**
	 * Return total photos in a given album id.
	 *
	 * @param	int	$id	The album id.
	 */	 
	function getTotalPhotos( $albumId )
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__community_photos') . ' '
				. 'WHERE ' . $db->nameQuote( 'albumid' ) . '=' . $db->Quote( $albumId );

		$db->setQuery( $query );
		$total	= $db->loadResult();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
				
		return $total;
	}
	
	function getAllAlbums( $userId = 0, $limit = 0 )
	{
			
		$db			=& $this->getDBO();
		
		// Get limit
		$limit		= $limit == 0 ? $this->getState('limit') : $limit;
		$limitstart	= $this->getState( 'limitstart' );
		
		$subQuery	= 'SELECT COUNT( DISTINCT(s.id) ) '
					. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS s '
					. 'RIGHT JOIN ' . $db->nameQuote( '#__community_groups_members' ) . ' AS t '
					. 'ON s.groupid=t.groupid '
					. 'WHERE t.memberid=' . $db->Quote( $userId ) . ' '
					. 'AND t.approved=1';
					
		// Get total albums
		$query	= 'SELECT a.*, COUNT(DISTINCT(a.id)), '
				. 'IF( a.groupid>0, IF( c.approvals=0,true,(' . $subQuery . ') ), true ) AS display '
				. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS b '
				. 'ON a.id=b.albumid '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_groups' ) . ' AS c '
				. 'ON a.groupid = c.id '
				. 'GROUP BY a.id '
				. 'HAVING display=true ';

		$db->setQuery( $query );
		$albums	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		$total			= 0;
		$albumCount		= array();

		// Check album permissions
		if( !empty($albums) )
		{
			foreach( $albums as &$row ){
				if( $this->checkAlbumsPermissions($row,$userId) )
				{
					$albumCount[$total]	= $row ;	
					$total++;
				}
			}
		}
		
		// Appy pagination
		if( empty($this->_pagination) )
		{
	 	    jimport('joomla.html.pagination');
	 	    $this->_pagination = new JPagination($total, $limitstart, $limit);
	 	}
	 	
// 		$query	= 'SELECT a.*, '
// 				. 'COUNT( DISTINCT(b.id) ) AS count, '
// 				. 'MAX(b.created) AS lastupdated, '
// 				. 'c.thumbnail, '
// 				. 'c.storage, '
// 				. 'c.id AS photoid, '
// 				. 'IF( a.groupid>0, IF( d.approvals=0,true,(' . $subQuery . ') ), true ) as display, '
// 				. 'CASE a.permissions '
// 				. '	WHEN 0 THEN '
// 				. '	  true '
// 				. '	WHEN 20 THEN '
// 				. '	  (SELECT COUNT(u.id) FROM ' . $db->nameQuote( '#__users' ) . ' AS u WHERE u.block=0 AND u.id=' . $db->Quote( $userId ) . ') '
// 				. '	WHEN 30 THEN '
// 				. '	  IF( a.creator=' . $db->Quote( $userId ) . ', true, (SELECT COUNT(v.connection_id) FROM ' . $db->nameQuote('#__community_connection') . ' AS v WHERE v.connect_from=a.creator AND v.connect_to=' . $db->Quote( $userId ) . ' AND v.status=1) ) '
// 				. '	WHEN 40 THEN '
// 				. '	  IF(a.creator=' . $db->Quote( $userId ) . ',true,false) '
// 				. '	END '
// 				. '	AS privacy '
// 				. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS a '
// 				. 'INNER JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS b '
// 				. 'ON a.id=b.albumid '
// 				. 'LEFT JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS c '
// 				. 'ON a.photoid=c.id '
// 				. 'LEFT JOIN ' . $db->nameQuote( '#__community_groups' ) . ' AS d '
// 				. 'ON a.groupid=d.id '
// 				. 'GROUP BY a.id '
// 				. 'HAVING display=true AND privacy=true '
// 				. 'ORDER BY a.`created` DESC '
// 				. 'LIMIT ' . $limitstart . ',' . $limit;
				
				
		$query	= 'SELECT x.*, '
				. ' COUNT( DISTINCT(b.id) ) AS count, MAX(b.created) AS lastupdated FROM '
				. '(SELECT a.`id`, a.`creator`, a.`name`, a.`description`, a.`permissions`, a.`created`, '
				. ' a.`path` , a.`type` , a.`groupid`, '
				. 'c.thumbnail, '
				. 'c.storage, '
				. 'c.id as photoid, '	
				. 'IF( a.groupid>0, IF( d.approvals=0,true,(' . $subQuery . ') ), true ) as display, '
				. 'CASE a.permissions '
				. '	WHEN 0 THEN '
				. '	  true '
				. '	WHEN 20 THEN '
				. '	  (SELECT COUNT(u.id) FROM ' . $db->nameQuote( '#__users' ) . ' AS u WHERE u.block=0 AND u.id=' . $db->Quote( $userId ) . ') '
				. '	WHEN 30 THEN '
				. '	  IF( a.creator=' . $db->Quote( $userId ) . ', true, (SELECT COUNT(v.connection_id) FROM ' . $db->nameQuote('#__community_connection') . ' AS v WHERE v.connect_from=a.creator AND v.connect_to=' . $db->Quote( $userId ) . ' AND v.status=1) ) '
				. '	WHEN 40 THEN '
				. '	  IF(a.creator=' . $db->Quote( $userId ) . ',true,false) '
				. '	END '
				. '	AS privacy '
				. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS a '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS c '
				. 'ON a.photoid=c.id '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_groups' ) . ' AS d '
				. 'ON a.groupid=d.id '
// 				. 'where ((a.permissions <= 0) ' 
// 				. 'or (a.permissions = 20 AND ' . $db->Quote( $userId ) . ' != 0) '
// 				. 'or (a.permissions = 30 AND a.creator IN (select v.connect_to FROM `#__community_connection` AS v WHERE  v.connect_from = '. $db->Quote( $userId ) .' AND v.STATUS=1)) '
// 				. 'or (a.permissions = 40 and a.creator = '. $db->Quote( $userId ) . ')) '
				. 'GROUP BY a.id '
 				. 'HAVING display=true AND privacy=true '				
				. 'ORDER BY a.`created` DESC '
				. ') AS x '
				. 'INNER JOIN `#__community_photos` AS b '
				. 'ON x.id=b.albumid '
				. 'GROUP BY x.id '
				. 'ORDER BY x.`created` DESC '
                . 'LIMIT ' . $limitstart . ',' . $limit;
				
		//echo $query;exit;		

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		// Update their correct Thumbnails and check album permissions
		if( !empty($result) )
		{
			foreach( $result as &$row ){
				//$photo = $this->getPhoto($row->photoid);
				$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
				$photo->bind($row);
				$photo->id = $row->photoid; // the id was photo_album id, need to fix it
				$row->thumbnail = $photo->getThumbURI();
			}
		}

		return $result;
		
	}


	function checkAlbumsPermissions($row,$myId)
	{
		CFactory::load( 'helpers' , 'friends' );
		
		switch ($row->permissions)
		{
			case 0:
					$result	= true;
				break;
			case 20:
					$result	= !empty($myId) ? true : false;
				break;
			case 30:
					$result	= CFriendsHelper::isConnected ( $row->creator, $myId ) ? true : false;
			  	break;
			case 40:
					$result	= $row->creator == $myId ? true : false;
			  	break;
			default:
					$result = false;
				break;
		}
		
		return $result;
	}
	
	/**
	 * Get site wide albums
	 * 
	 **/	 
	function getSiteAlbums( $type = PHOTOS_USER_TYPE )
	{
		$db			=& $this->getDBO();
		$searchType	= '';
		
		if( $type == PHOTOS_GROUP_TYPE )
		{
			$searchType	= PHOTOS_GROUP_TYPE;
		}
		else
		{
			$searchType	= PHOTOS_USER_TYPE;
		}
		
		// Get limit
		$limit		= $this->getState('limit');
		$limitstart	= $this->getState( 'limitstart' );

		// Get total albums
		$query	= 'SELECT COUNT(DISTINCT(a.id)) '
				. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS b '
				. 'ON a.id=b.albumid '
				. 'WHERE a.type=' . $db->Quote( $searchType );

		$db->setQuery( $query );
		$total	= $db->loadResult();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		// Appy pagination
		if( empty($this->_pagination) )
		{
	 	    jimport('joomla.html.pagination');
	 	    $this->_pagination = new JPagination($total, $limitstart, $limit);
	 	}
	 	
		$query	= 'SELECT a.*, '
				. 'COUNT( DISTINCT(b.id) ) AS count, '
				. 'MAX(b.created) AS lastupdated, '
				. 'c.thumbnail, '
				. 'c.storage, '
				. 'c.id AS photoid '
				. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS b '
				. 'ON a.id=b.albumid '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS c '
				. 'ON a.photoid=c.id '
				. 'WHERE a.type=' . $db->Quote( $searchType ) . ' '
				. 'GROUP BY a.id '
				. 'ORDER BY a.`created` DESC '
				. 'LIMIT ' . $limitstart . ',' . $limit;

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		// Update their correct Thumbnails
		if( !empty($result) )
		{
			foreach( $result as &$row ){
				//$photo = $this->getPhoto($row->photoid);
				$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
				$photo->bind($row);
				$photo->id = $row->photoid; // the id was photo_album id, need to fix it
				$row->thumbnail = $photo->getThumbURI();
			}
		}
		
		 		
		return $result;
	}
	
	function getGroupAlbums( $groupId = '' , $pagination = false , $doubleLimit = false, $limit="" , $isAdmin = false, $creator = '' )
	{
		$db			=& $this->getDBO();
		$extraSQL	= ' WHERE a.type = ' . $db->Quote( PHOTOS_GROUP_TYPE );
		$extraSQL	.= ' AND a.groupid=' . $db->Quote( $groupId ) . ' ';
		
		if( !$isAdmin && !empty($creator) )
		{
			$extraSQL	.= ' AND a.creator=' . $db->Quote( $creator ) . ' '; 
		}

		// Get limit
		$limit		= (!empty($limit)) ? $limit : $this->getState('limit');
		$limit		= ( $doubleLimit ) ? $this->getState('limit') : $limit;
		$limitstart	= $this->getState( 'limitstart' );
		
		// Get total albums
		$query	= 'SELECT COUNT(*) '
				. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS a'
				. $extraSQL;

		$db->setQuery( $query );
		$total			= $db->loadResult();
		$this->total	= $total;
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		// Appy pagination
		if( empty($this->_pagination) )
		{
	 	    jimport('joomla.html.pagination');
	 	    $this->_pagination = new JPagination($total, $limitstart, $limit);
	 	}

		$query	= 'SELECT a.*, '
				. 'COUNT( DISTINCT(b.id) ) AS count, '
				. 'MAX(b.created) AS lastupdated, '
				. 'c.thumbnail as thumbnail, '
				. 'c.storage AS storage, '
				. 'c.id as photoid '
				. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS a '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS b '
				. 'ON a.id=b.albumid '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS c '
				. 'ON a.photoid=c.id '
				. $extraSQL
				. 'GROUP BY a.id '
				. ' ORDER BY a.`created` DESC';
		if( $pagination )
		{
			$query	.= ' LIMIT ' . $limitstart . ',' . $limit;
		}

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		// Update their correct Thumbnails
		if( !empty($result) )
		{
			foreach( $result as &$row ){
				$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
				$photo->bind($row);
				$photo->id = $row->photoid; // the id was photo_album id, need to fix it
				$row->thumbnail = $photo->getThumbURI();
			}
		}
		
		return $result;
	}
	
	/**
	 * Get the albums for specific user or site wide
	 * 
	 * @param	userId	string	The specific user id
	 * 	 
	 **/	 
	function getAlbums( $userId = '' , $pagination = false, $doubleLimit = false )
	{
		return $this->_getAlbums( $userId , PHOTOS_USER_TYPE , $pagination , $doubleLimit );
	}

	function _getAlbums( $id , $type , $pagination = false , $doubleLimit = false, $limit="" )
	{
		$db			=& $this->getDBO();
		$extraSQL	= ' WHERE a.type = ' . $db->Quote( $type );

		if( !empty($id) && $type == PHOTOS_GROUP_TYPE )
		{
			$extraSQL	.= ' AND a.groupid=' . $db->Quote( $id ) . ' ';
		}
		else if( !empty( $id ) && $type == PHOTOS_USER_TYPE )
		{
			$extraSQL	.= ' AND a.creator=' . $db->Quote( $id ) . ' ';
		}

		// Get limit
		$limit		= (!empty($limit)) ? $limit : $this->getState('limit');
		$limit		= ( $doubleLimit ) ? $this->getState('limit') : $limit;
		$limitstart	= $this->getState( 'limitstart' );
		
		// Get total albums
		$query	= 'SELECT COUNT(*) '
				. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS a'
				. $extraSQL;

		$db->setQuery( $query );
		$total			= $db->loadResult();
		$this->total	= $total;
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		// Appy pagination
		if( empty($this->_pagination) )
		{
	 	    jimport('joomla.html.pagination');
	 	    $this->_pagination = new JPagination($total, $limitstart, $limit);
	 	}

		$query	= 'SELECT a.*, '
				. 'COUNT( DISTINCT(b.id) ) AS count, '
				. 'MAX(b.created) AS lastupdated, '
				. 'c.thumbnail as thumbnail, '
				. 'c.storage AS storage, '
				. 'c.id as photoid '
				. 'FROM ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS a '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS b '
				. 'ON a.id=b.albumid '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_photos' ) . ' AS c '
				. 'ON a.photoid=c.id '
				. $extraSQL
				. 'GROUP BY a.id '
				. ' ORDER BY a.`created` DESC';
		if( $pagination )
		{
			$query	.= ' LIMIT ' . $limitstart . ',' . $limit;
		}

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}
		
		// Update their correct Thumbnails
		if( !empty($result) )
		{
			foreach( $result as &$row ){
				$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
				$photo->bind($row);
				$photo->id = $row->photoid; // the id was photo_album id, need to fix it
				$row->thumbnail = $photo->getThumbURI();
			}
		}
		
		return $result;
	}
	
	function isCreator($photoId , $userId)
	{
		// Guest has no album
		if($userId == 0)
			return false;
			
		$db	=& $this->getDBO();
		
		$strSQL	= 'SELECT COUNT(*) FROM ' . $db->nameQuote('#__community_photos') . ' '
				. 'WHERE ' . $db->nameQuote('id') . '=' . $db->Quote($photoId) . ' '
				. 'AND creator=' . $db->Quote($userId);

		$db->setQuery($strSQL);
		$result	= $db->loadResult();
		
		return $result;
	}

	/**
	 * Return CPhoto object
	 */	 	
	function getPhoto($id)
	{
		$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
		$photo->load( $id );
		
		return $photo;
	}
	
	/**
	 * Get the count of the photos from specific user or groups.
	 * @param id user or group id	 
	 **/	 	
	function getPhotosCount( $id , $photoType = PHOTOS_USER_TYPE )
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM ' 
				. $db->nameQuote( '#__community_photos' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__community_photos_albums' ) . ' AS b '
				. 'ON a.albumid=b.id '
				. 'AND b.type=' . $db->Quote( $photoType );
		
		if( $photoType == PHOTOS_GROUP_TYPE )
		{
			$query	.= ' WHERE b.groupid=' . $db->Quote( $id );
		}
		else
		{
			$query	.= ' WHERE a.creator=' . $db->Quote( $id );
		}
		$query	.= ' AND `albumid`!=0';
				
		
		$db->setQuery( $query );
		$count	= $db->loadResult();
		
		return $count;
	}
	
	function getDefaultImage( $albumId ){
		$db	=& $this->getDBO();
		
		$strSQL	= 'SELECT b.* FROM ' . $db->nameQuote('#__community_photos_albums') . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote('#__community_photos') . 'AS b '
				. 'WHERE a.id=' . $db->Quote($albumId) . ' '
				. 'AND a.photoid=b.id';

		//echo $strSQL;
		$db->setQuery($strSQL);
		$result	= $db->loadObject();
		
		return $result;
	}
	
	function setDefaultImage( $albumId , $photoId )
	{
		$db	=& $this->getDBO();
		
		$data	= $this->getAlbum($albumId);
		
		$data->photoid	= $photoId;
		
		$db->updateObject( '#__community_photos_albums' , $data , 'id');
	}

	function setOrdering( $photos , $albumId )
	{
		$db	 = &$this->getDBO();
		
		foreach( $photos as $id => $order )
		{
			$query	= 'UPDATE ' . $db->nameQuote( '#__community_photos' ) . ' '
					. 'SET ' . $db->nameQuote( 'ordering' ) . '=' . $db->Quote( $order ) . ' '
					. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $id ) . ' '
					. 'AND ' . $db->nameQuote( 'albumid' ) . '=' . $db->Quote( $albumId );

			$db->setQuery( $query );
			$db->query();
	
			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
			}
		}
		return true;
	}
	
	function setCaption( $photoId , $caption )
	{
		$db		=& $this->getDBO();
		$data	= $this->getPhoto( $photoId );
		$data->caption	= $caption;
		
		$db->updateObject( '#__community_photos' , $data , 'id' );
		
		return $data;
	}
	
	function isGroupPhoto( $photoId )
	{
		$db	=& $this->getDBO();
		
		$query	= 'SELECT b.`type` FROM `#__community_photos` AS a';
		$query	.= ' INNER JOIN `#__community_photos_albums` AS b';
		$query	.= ' ON a.`albumid` = b.`id`';
		$query	.= ' WHERE a.`id` = ' . $db->Quote($photoId);
		
		$db->setQuery($query);
		$type	= $db->loadResult();
		
		if($type == PHOTOS_GROUP_TYPE)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function getPhotoGroupId( $photoId )
	{
		$db	=& $this->getDBO();
		
		$query	= 'SELECT b.`groupid` FROM `#__community_photos` AS a';
		$query	.= ' INNER JOIN `#__community_photos_albums` AS b';
		$query	.= ' ON a.`albumid` = b.`id`';
		$query	.= ' WHERE a.`id` = ' . $db->Quote($photoId);
		$query	.= ' AND b.`type` = ' . $db->Quote(PHOTOS_GROUP_TYPE);
		
		$db->setQuery($query);
		$type	= $db->loadResult();
		
		return $type; 
	}	
	
}