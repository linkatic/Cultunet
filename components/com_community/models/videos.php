<?php
/**
 * @copyright (C) 2009 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once ( JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'models'.DS.'models.php');

// Deprecated since 1.8.x to support older modules / plugins
CFactory::load( 'tables' , 'videos' );
CFactory::load( 'tables' , 'videoscategory' );

class CommunityModelVideos extends JCCModel
{
	var $_pagination 	= '';
	var $total			= '';

	function CommunityModelVideos()
	{
		parent::JCCModel();
		
		$id = JRequest::getVar('videoid', 0, '', 'int');
		$this->setId((int)$id);

 	 	$mainframe = JFactory::getApplication();

 	 	// Get the pagination request variables		
 	 	$limit		= ($mainframe->getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');
	    $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function setId($id)
	{
		// Set new video ID and wipe data
		$this->_id		= $id;
	}

	/**
	 *	Checks whether specific user or group has pending videos
	 *	
	 *	@params	$id	int	The unique id of the creator or groupid
	 *	@params	$type	string	The video type whether user or group	 	 	 
	 **/
	function hasPendingVideos( $id , $type = VIDEO_USER_TYPE )
	{
		if($type == VIDEO_USER_TYPE && $id == 0)
		{
			return 0;
		}
		
		$db		= $this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_videos' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'creator_type') . '=' . $db->Quote( $type ) . ' ';
				
		if( $type == VIDEO_USER_TYPE )
		{
			$query	.= 'AND ' . $db->nameQuote( 'creator' ) . '=' . $db->Quote( $id );
		}
		else
		{
			$query	.= 'AND ' . $db->nameQuote( 'groupid' ) . '=' . $db->Quote( $id );
		}
		
		$query	.= ' AND ' . $db->nameQuote( 'status' ) . '=' . $db->Quote( 'pending' );
		$query	.= ' AND ' . $db->nameQuote( 'published' ) . '=' . $db->Quote( 1 );
		
		$db->setQuery($query);
		$result	= $db->loadResult() >= 1 ? true : false;
		
		return $result;
	}
	
	/**
	 * Loads the videos
	 * 
	 * @public
	 * @param	array	$filters	The video's filter
	 * @return	array	An array of videos object
	 * @since	1.2
	 */
	function getVideos($filters = array())
	{
		$db		= $this->getDBO();
		
		$where	= array();
		foreach ($filters as $field => $value)
		{
			if ($value || $value === 0)
			{
				switch (strtolower($field))
				{
					case 'id':
						if (is_array($value)) {
							JArrayHelper::toInteger($value);
							$value	= implode( ',', $value );
						}
						$where[]	= 'v.`id` IN (' . $value . ')';
						break;
					case 'title':
						$where[]	= 'v.`title`  LIKE ' . $db->quote('%' . $value . '%');
						break;
					case 'type':
						$where[]	= 'v.`type` = ' . $db->quote($value);
						break;
					case 'description':
						$where[]	= 'v.`description` LIKE ' . $db->quote('%' . $value . '%');
						break;
					case 'creator':
						$where[]	= 'v.`creator` = ' . $db->quote((int)$value);
						break;
					case 'creator_type':
						$where[]	= 'v.`creator_type` = ' . $db->quote($value);
						break;
					case 'created':
						$value		= JFactory::getDate($value)->toMySQL();
						$where[]	= 'v.`created` BETWEEN ' . $db->quote('1970-01-01 00:00:01') . ' AND ' . $db->quote($value);
						break;
					/*case 'and_group_privacy':
						// if there is no video found with given group privacy
						// value, this filter will be ignored.
						// careful if this filter is set, or_group_privacy will
						// not be working. see line 185
						$config		= CFactory::getConfig();
						if ($config->get('groupvideos'))
						{
							$query	= 'SELECT ' . $db->nameQuote( 'id' )
									. ' FROM ' . $db->nameQuote( '#__community_groups' )
									. ' WHERE ' . $db->nameQuote( 'approvals' ) . '<=' . $db->Quote( $value );
							$db->setQuery($query);
							$groupId	= $db->loadResultArray();
							if ($groupId)
							{
								$idString	= implode(', ', $groupId);
								$where[]	= $db->nameQuote('groupid') . ' IN ( ' . $idString . ')';
							}
							unset($groupId);
						}
						break;
					*/
					case 'permissions':
						$where[]	= 'v.`permissions` <= ' . $db->quote((int)$value);
						break;
					case 'category_id':
						if (is_array($value)) {
							JArrayHelper::toInteger($value);
							$value	= implode( ',', $value );
						}
						$where[]	= 'v.`category_id` IN (' . $value . ')';
						break;
					case 'hits':
						$where[]	= 'v.`hits` >= ' . $db->quote((int)$value);
						break;
					case 'published':
						$where[]	= 'v.`published` = ' . $db->quote((bool)$value);
						break;
					case 'featured':
						$where[]	= 'v.`featured` = ' . $db->quote((bool)$value);
						break;
					case 'duration':
						$where[]	= 'v.`duration` >= ' . $db->quote((int)$value);
						break;
					case 'status':
						$where[]	= 'v.`status` = ' . $db->quote($value);
						break;
					case 'groupid':
						$where[]	= 'v.`groupid` = ' . $db->quote($value);
						break;
					case 'limitstart':
						$limitstart	= (int) $value;
						break;
					case 'limit':
						$limit		= (int) $value;
						break;					
				}
			}
		}

		$where		= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
		
		// Or-Group-Privacy Filter
		// Any videos that match or less than the filter value will be included
		/*if (isset($filters['or_group_privacy']) && !isset($filters['and_group_privacy']))
		{
			$config		= CFactory::getConfig();
			if ($config->get('groupvideos'))
			{
				$query	= 'SELECT ' . $db->nameQuote( 'id' )
						. ' FROM ' . $db->nameQuote( '#__community_groups' )
						. ' WHERE ' . $db->nameQuote( 'approvals' ) . '<=' . $db->Quote( $filters['or_group_privacy'] );
				$db->setQuery($query);
				$groupId	= $db->loadResultArray();
				if ($groupId)
				{
					$idString	= implode(', ', $groupId);
					$where		.= ' OR ' . $db->nameQuote('groupid') . ' IN ( ' . $idString . ')';
				}
			}
		}*/
		// Joint with group table
		$join	= '';
		if (isset($filters['or_group_privacy']))
		{
			$approvals	= (int) $filters['or_group_privacy'];
			$join		=  ' LEFT JOIN ' . $db->nameQuote('#__community_groups') . ' AS g';
			$join 		.= ' ON g.id = v.groupid';
			$where		.= ' AND (g.approvals = 0 OR g.approvals IS NULL)';
		}

		$order		= '';
		$sorting	= isset($filters['sorting']) ? $filters['sorting'] : 'latest';

		switch ($sorting)
		{
			case 'mostwalls':
				// mostwalls is sorted below using JArrayHelper::sortObjects
				// since in db vidoes doesn't has wallcount field
			case 'mostviews':
				$order	= ' ORDER BY v.`hits` DESC';
				break;
			case 'title':
				$order	= ' ORDER BY v.`title` ASC';
				break;
			case 'latest':
			default :
				$order	= ' ORDER BY v.`created` DESC';
				break;
		}

		$limit		= (isset($limit)) ? $limit : $this->getState('limit');
		$limit		= ($limit < 0) ? 0 : $limit;
		$limitstart = (isset($limitstart)) ? $limitstart : $this->getState('limitstart');

		$limiter	= ' LIMIT '	. $limitstart . ', ' . $limit;

		$query		= ' SELECT v.*, v.created AS lastupdated'
					. ' FROM ' . $db->nameQuote('#__community_videos') . ' AS v'
					. $join
					. $where
					. $order
					. $limiter;
		$db->setQuery($query);
		$result		= $db->loadObjectList();

		if ($db->getErrorNum())
			JError::raiseError(500, $db->stderr());

		// Get total of records to be used in the pagination
		$query		= ' SELECT COUNT(*)'
					. ' FROM ' . $db->nameQuote('#__community_videos') . ' AS v'
					. $join
					. $where
					;
		$db->setQuery($query);
		$total		= $db->loadResult();
		$this->total	= $total;

		if($db->getErrorNum())
			JError::raiseError( 500, $db->stderr());

		// Apply pagination
		if (empty($this->_pagination)) {
	 	    jimport('joomla.html.pagination');
	 	    $this->_pagination	= new JPagination($total, $limitstart, $limit);
	 	}


		// Add the wallcount property for sorting purpose
		foreach ($result as $video) {
			// Wall post count
			$query	= ' SELECT COUNT(*)'
					. ' FROM ' . $db->nameQuote('#__community_wall')
					. ' WHERE ' . $db->nameQuote('type') . ' = ' . $db->quote('videos')
					. ' AND ' . $db->nameQuote('published') . ' = ' . $db->quote(1)
					. ' AND ' . $db->nameQuote('contentid') . ' = ' . $db->quote($video->id)
					;
			$db->setQuery($query);
			$video->wallcount	= $db->loadResult();
		}

		// Sort videos according to wall post count
		if ($sorting == 'mostwalls')
			JArrayHelper::sortObjects( $result, 'wallcount', -1);

		return $result;
	}

	/**
	 * Loads the categories
	 * 
	 * @access	public
	 * @return	array	An array of categories object
	 * @since	1.2
	 */
	function getCategories()
	{
		$my			= CFactory::getUser();
		$permissions= ($my->id==0) ? 0 : 20;
		$groupId	= JRequest::getVar('groupid' , '' , 'GET');
		$conditions = '';
		$db			= $this->getDBO();
		
		if( !empty($groupId) )
		{
			$conditions	= ' AND v.creator_type = ' . $db->quote(VIDEO_GROUP_TYPE);
			//$conditions	.= ' AND b.groupid = ' . $groupId;
			$conditions	.= ' AND g.id = ' . $groupId;
		}
		else
		{
			$conditions	.= ' AND (g.approvals = 0 OR g.approvals IS NULL)';
		}
		
		$query	= ' SELECT c.*, COUNT(v.id) AS count'
				. ' FROM ' . $db->nameQuote('#__community_videos_category') . ' AS c'
				. ' LEFT JOIN ' . $db->nameQuote('#__community_videos') . ' AS v ON c.id = v.category_id'
				. ' LEFT JOIN ' . $db->nameQuote('#__community_groups') . ' AS g ON g.id = v.groupid'
				. ' WHERE v.status = ' . $db->Quote('ready')
				. ' AND v.published = ' . $db->Quote(1)
				. ' AND v.permissions <= ' . $db->Quote($permissions)
				. $conditions
				. ' GROUP BY c.id';

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
			JError::raiseError( 500, $db->stderr());
		
		// to show the categories even when no video exists
		if (empty($result))
		{
			$query	= ' SELECT *, "0" AS count '
					. ' FROM ' . $db->nameQuote('#__community_videos_category');
			$db->setQuery( $query );
			$result	= $db->loadObjectList();
		}

		return $result;
	}
	
	function getAllCategories()
	{
		$db		= $this->getDBO();
		$query	= ' SELECT * '
				. ' FROM ' . $db->nameQuote('#__community_videos_category');
		$db->setQuery($query);
		$result	= $db->loadObjectList();
		return $result;
	}

	function getPagination()
	{
	    return $this->_pagination;
	}
	
	function getTotal()
	{
	    return $this->total;
	}
	
	function deleteVideoWalls($id)
	{
		if (!$id) return;
		$db		= $this->getDBO();
		$query	= 'DELETE FROM ' . $db->nameQuote('#__community_wall')
				. ' WHERE ' . $db->nameQuote('contentid') . ' = ' . $db->quote($id)
				. ' AND ' . $db->nameQuote('type') . ' = ' . $db->quote('videos');
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		return true;
	}
	
	function deleteVideoActivities($id = 0)
	{
		if (!$id) return;
		$db		= $this->getDBO();
		$query	= 'DELETE FROM ' . $db->nameQuote('#__community_activities')
				. ' WHERE ' . $db->nameQuote('app') . ' = ' . $db->quote('videos')
				. ' AND ' . $db->nameQuote('cid') . ' = ' . $db->quote($id);
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		return true;
	}
	
	/**
	 * Returns Group's videos
	 *
	 * @access public
	 * @param integer the id of the group
	 */	 
	function getGroupVideos( $groupid, $categoryid="", $limit="" )
	{
		$filter	= array(
			'groupid'		=> $groupid,
			'published'		=> 1,
			'status'		=> 'ready',
			'category_id'	=> $categoryid,
			'creator_type' 	=> VIDEO_GROUP_TYPE,
			'sorting'		=> JRequest::getVar('sort', 'latest'),
			'limit'			=> $limit
		);
		
		$videos 		= $this->getVideos( $filter );
		
		return $videos;
	}
	
	function getPendingVideos()
	{
		$filter		= array('status' => 'pending');
		return $this->getVideos($filter);
	}
	
	/**
	 * Get the count of the videos from specific user
	 **/
	function getVideosCount( $userId = 0, $videoType = VIDEO_USER_TYPE )
	{
		if ($userId==0) return 0;
		
		$db		= $this->getDBO();
		
		$query	= 'SELECT COUNT(1) FROM ' 
				. $db->nameQuote( '#__community_videos' ) . ' AS a '
				. 'WHERE creator=' . $db->Quote( $userId ) . ' '
				. 'AND creator_type=' . $db->Quote( $videoType );
		
		$db->setQuery( $query );
		$count	= $db->loadResult();
		
		return $count;
	}
}

abstract class CVideoProvider extends JObject
{
	abstract function getThumbnail();
	abstract function getTitle();
	abstract function getDuration();
	abstract function getType();
	abstract function init($url);
	abstract function isValid();
	abstract function getViewHTML($videoId, $videoWidth, $videoHeight);
	abstract function getEmbedCode($videoId, $videoWidth, $videoHeight);
}