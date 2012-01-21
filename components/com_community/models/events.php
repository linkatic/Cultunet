<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Groups
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once ( JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'models'.DS.'models.php');

// Deprecated since 1.8.x to support older modules / plugins
CFactory::load( 'tables' , 'event' );
CFactory::load( 'tables' , 'eventcategory' );
CFactory::load( 'tables' , 'eventmembers' );

class CommunityModelEvents extends JCCModel
implements CGeolocationSearchInterface
{
	/**
	 * Configuration data
	 *
	 * @var object	JPagination object
	 **/
	var $_pagination	= '';

	/**
	 * Configuration data
	 *
	 * @var object	JPagination object
	 **/
	var $total			= '';

	/**
	 * member count data
	 *
	 * @var int
	 **/
	var $membersCount	= array();

	/**
	 * Constructor
	 */
	function CommunityModelEvents()
	{
		parent::JCCModel();

		$mainframe =& JFactory::getApplication();

		// Get pagination request variables
 	 	$limit		= ($mainframe->getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');
	    $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');
//		$limit		= 1;

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

	}

	/**
	 * Method to get a pagination object for the events
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		return $this->_pagination;
	}

	/**
	 * Returns an object of events which the user has registered.
	 *
	 * @access	public
	 * @param	string 	User's id.
	 * @param	string 	sorting criteria.
	 * @returns array  An objects of event fields.
	 */
	function getEvents( $categoryId = null, $userId = null , $sorting = null, $search = null, $hideOldEvent = true, $showOnlyOldEvent = false, $pending = null )
	{
		$db		=& $this->getDBO();

		$extraSQL	= '';

		if( !is_null($userId) )
		{
			$extraSQL	= 'AND b.memberid=' . $db->Quote($userId) . ' ';
		}
		
		if( !is_null( $search ) )
		{
			$extraSQL	.= 'AND a.title LIKE ' . $db->Quote( '%' . $search . '%' ) . ' ';
		}
		
		if( !is_null($categoryId) && $categoryId != 0 )
		{
			$extraSQL	.= ' AND a.catid=' . $db->Quote($categoryId) . ' ';
		}

		if( !is_null( $pending ) )
		{
			$extraSQL	.= ' AND b.status=' . $db->Quote($pending) . ' ';
		}

		$orderBy	= '';

		$limit			= $this->getState('limit');
		$limitstart 	= $this->getState('limitstart');

		$total			= 0;

		switch($sorting)
		{			
			case 'latest':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY a.created DESC ';
				break;
			case 'alphabetical':
				if( empty($orderBy) )
					$orderBy	= 'ORDER BY a.title ASC ';
				break;
			case 'startdate':
			default:
				$orderBy	= 'ORDER BY a.startdate ASC ';
				break;
		}
		
		$now = new JDate();
		
		if($hideOldEvent)
		{
			$extraSQL .= ' AND a.enddate > ' . $db->Quote($now->toMySQL(true));
		}

		if($showOnlyOldEvent)
		{
		    $extraSQL .= ' AND a.enddate < ' . $db->Quote($now->toMySQL(true));
		}
		
		$query	= 'SELECT DISTINCT a.* FROM '
				. $db->nameQuote('#__community_events') . ' AS a '
				. 'LEFT JOIN ' . $db->nameQuote('#__community_events_members') . ' AS b ON a.id=b.eventid '
				. 'WHERE a.published=' . $db->Quote( '1' ) . ' '
				. $extraSQL
				. $orderBy
				. 'LIMIT ' . $limitstart . ', ' . $limit;

		$db->setQuery( $query );		
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		$query	= 'SELECT COUNT(DISTINCT(a.`id`)) FROM ' . $db->nameQuote('#__community_events') . ' AS a '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_events_members' ) . ' AS b ON a.id=b.eventid '
				. 'WHERE a.published=' . $db->Quote( '1' ) . ' '
				. $extraSQL;

		$db->setQuery( $query );
		$total	= $db->loadResult();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		if( empty($this->_pagination) )
		{
			jimport('joomla.html.pagination');

			$this->_pagination	= new JPagination( $total , $limitstart , $limit );
		}

		return $result;
	}

	/**
	 * Return the number of groups count for specific user
	 **/
	function getEventsCount( $userId )
	{
		// guest obviously has no group
		if($userId == 0)
			return 0;


		$db		=& $this->getDBO();

		$query	= 'SELECT COUNT(*) FROM '
				. $db->nameQuote( '#__community_events_members' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'memberid' ) . '=' . $db->Quote( $userId ) . ' '
				. 'AND ' . $db->nameQuote( 'status' ) . ' IN ('.COMMUNITY_EVENT_STATUS_INVITED.','.COMMUNITY_EVENT_STATUS_ATTEND.','.COMMUNITY_EVENT_STATUS_MAYBE.')';
		$db->setQuery( $query );
		$count	= $db->loadResult();

		return $count;
	}
	
	/**
	 * Return the number of groups cretion count for specific user
	 **/
	function getEventsCreationCount( $userId )
	{
		// guest obviously has no events
		if($userId == 0)
			return 0;

		$db		=& $this->getDBO();

		$query	= 'SELECT COUNT(*) FROM '
				. $db->nameQuote( '#__community_events' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'creator' ) . '=' . $db->Quote( $userId );
		$db->setQuery( $query );

		$count	= $db->loadResult();

		return $count;
	}

	/**
	 * Returns the count of the members of a specific group
	 *
	 * @access	public
	 * @param	string 	Group's id.
	 * @return	int	Count of members
	 */
	function getMembersCount( $id )
	{
		$db	=& $this->getDBO();

		if( !isset($this->membersCount[$id] ) )
		{
			$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote('#__community_events_members') . ' '
					. 'WHERE eventid=' . $db->Quote( $id ) . ' '
					. 'AND ' . $db->nameQuote( 'status' ) . ' IN ('.COMMUNITY_EVENT_STATUS_INVITED.','.COMMUNITY_EVENT_STATUS_ATTEND.','.COMMUNITY_EVENT_STATUS_MAYBE.')';

			$db->setQuery( $query );
			$this->membersCount[$id]	= $db->loadResult();

			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
			}
		}
		return $this->membersCount[$id];
	}

	/**
	 * Loads the categories
	 *
	 * @access	public
	 * @returns Array  An array of categories object
	 */
	function getCategories()
	{
		$db		=& $this->getDBO();
		
		$now = new JDate();
		$query	= 'SELECT a.*, COUNT(b.id) AS count '
				. 'FROM ' . $db->nameQuote('#__community_events_category') . ' AS a '
				. 'LEFT OUTER JOIN ' . $db->nameQuote( '#__community_events' ) . ' AS b '
				. 'ON a.id=b.catid '
				/*. 'WHERE '*/
				. 'AND b.enddate > ' . $db->Quote($now->toMySQL(true))
				. 'AND b.published=' . $db->Quote( '1' ) . ' '
				. 'GROUP BY a.id ORDER BY a.name ASC';

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
	}

	/**
	 * Returns the category name of the specific category
	 *
	 * @access public
	 * @param	string Category Id
	 * @returns string	Category name
	 **/
	function getCategoryName( $categoryId )
	{
		CError::assert($categoryId, '', '!empty', __FILE__ , __LINE__ );
		$db		=& $this->getDBO();

		$query	= 'SELECT ' . $db->nameQuote('name') . ' '
				. 'FROM ' . $db->nameQuote('#__community_events_category') . ' '
				. 'WHERE ' . $db->nameQuote('id') . '=' . $db->Quote( $categoryId );
		$db->setQuery( $query );

		$result	= $db->loadResult();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		CError::assert( $result , '', '!empty', __FILE__ , __LINE__ );
		return $result;
	}

	/**
	 * Check if the given group name exist.
	 * if id is specified, only search for those NOT within $id
	 */
	function isEventExist($title, $location, $id=0) {
		$db		=& $this->getDBO();

		$strSQL	= 'SELECT count(*) FROM `#__community_events`'
			. " WHERE `title` = " . $db->Quote( $title )
			. " AND `location` = " . $db->Quote( $location )
			. " AND `id` != " . $db->Quote( $id ) ;


		$db->setQuery( $strSQL );
		$result	= $db->loadResult();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
	}


	function getLargeAvatar( $id , $avatar )
	{
		if( empty( $avatar ) )
		{
			//@todo: remove this section once all the avatars are moved into the
			// groups table. We could just do JURI::base when getting the groups data

			// Copy old data.
			$model	=& CFactory::getModel( 'avatar' );

			$avatar	= $model->getLargeImg( $id , 'groups' );

			// We only want the relative path , specific fix for http://dev.jomsocial.com
			$avatar	= JString::str_ireplace( JURI::base() , '' , $avatar );
			$this->setImage( $id , $avatar , 'avatar' );
		}

		// If avatar is not empty, we assume that this will be newly created groups that stores
		// the avatar in the groups table
		return JURI::base() . $avatar;
	}

	/**
	 * Get the avatar image for specific group
	 **/
	function getThumbAvatar( $id , $avatar )
	{

		if( empty( $avatar ) )
		{
			//@todo: remove this section once all the avatars are moved into the
			// groups table. We could just do JURI::base when getting the groups data

			// Copy old data.
			$model	=& CFactory::getModel( 'avatar' );

			$avatar	= $model->getSmallImg( $id , 'groups' );

			// We only want the relative path , specific fix for http://dev.jomsocial.com
			$avatar	= JString::str_ireplace( JURI::base() , '' , $avatar );
			$this->setImage( $id , $avatar , 'thumb' );
		}

		// If avatar is not empty, we assume that this will be newly created groups that stores
		// the avatar in the groups table
		return JURI::base() . $avatar;
	}


	/**
	 * Delete group's wall
	 *
	 * param	string	id The id of the group.
	 *
	 **/
	function deleteGroupWall($gid)
	{
		$db =& JFactory::getDBO();

		$sql = "DELETE

				FROM
						".$db->nameQuote("#__community_wall")."
				WHERE
						".$db->nameQuote("contentid")." = ".$db->quote($gid)." AND
						".$db->nameQuote("type")." = ".$db->quote('groups');
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}

		return true;
	}
	
	/* Implement interfaces */
	
	/**
	 * caller should verify that the address is valid
	 */	 	
	public function searchWithin($address, $distance)
	{
		$db =& JFactory::getDBO();
		
		$longitude = null;
		$latitude = null;
		
		CFactory::load('libraries', 'mapping');
		$data = CMapping::getAddressData($address);
		
		if($data){
			if($data->status == 'OK')
			{
				$latitude  = (float) $data->results[0]->geometry->location->lat;
				$longitude = (float) $data->results[0]->geometry->location->lng; 
			}
		}
		
		if(is_null($latitude) || is_null($longitude)){
			return $null;
		}
		/*
		code from 
		http://blog.fedecarg.com/2009/02/08/geo-proximity-search-the-haversine-equation/
		*/	
		
		//$longitude = (float) 101.678;
		//$latitude = (float) 3.11966 ;
		
		// $radius = $radius_in_km * 0.621371192;
		$radius = 20; // in miles
		
		$lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
		$lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
		$lat_min = $latitude - ($radius / 69);
		$lat_max = $latitude + ($radius / 69);
		
		//echo 'lng (min/max): ' . $lng_min . '/' . $lng_max . PHP_EOL;
		//echo 'lat (min/max): ' . $lat_min . '/' . $lat_max;
		
		$now = new JDate();
		$sql = "SELECT *

				FROM
						".$db->nameQuote("#__community_events")."
				WHERE
						".$db->nameQuote("longitude")." > ".$db->quote($lng_min)." AND
						".$db->nameQuote("longitude")." < ".$db->quote($lng_max)." AND
						".$db->nameQuote("latitude")." > ".$db->quote($lat_min)." AND
						".$db->nameQuote("latitude")." < ".$db->quote($lat_max)." AND
						".$db->nameQuote("enddate")." > ".$db->quote($now->toMySQL(true));
	
		$db->setQuery($sql);
		echo $db->getQuery();
		$results = $db->loadObjectList();
		
		return $results;
	}

	/**
	 *	Get the pending invitations
	 *
	 */
	public function getPending($userId){
		if($userId == 0){
			return null;
		}

		$limit		= $this->getState('limit');
		$limitstart = $this->getState('limitstart');

		$db		=&	JFactory::getDBO();

		$query	=	"SELECT a.*, b.title, b.thumb"
					. " FROM " . $db->nameQuote("#__community_events_members") . " AS a, "
					. $db->nameQuote("#__community_events") . " AS b"
					. " WHERE a.`memberid`=" . $db->quote($userId)
					. " AND a.`eventid`=b.`id`"
					. " AND a.`status`=" . COMMUNITY_EVENT_STATUS_INVITED
					. " ORDER BY a.`id` DESC"
					. " LIMIT {$limitstart}, {$limit}";

		$db->setQuery($query);

		if( $db->getErrorNum() ){
			JError::raiseError(500, $db->stderr());
		}

		$result = $db->loadObjectList();
		
		return $result;
	}

	/**
	 * Check if I was invited and if yes return true
	 * 
	 */
	public function isInvitedMe($invitationId, $userId){
		$db		=&	$this->getDBO();

		$query	=	"SELECT COUNT(*) FROM "
					. $db->nameQuote("#__community_events_members")
					. " WHERE " . $db->nameQuote("id") . "=" . $db->Quote($invitationId)
					. " AND " . $db->nameQuote("memberid") . "=" . $db->Quote($userId)
					. " AND " . $db->nameQuote("status") . "=" . COMMUNITY_EVENT_STATUS_INVITED;

		$db->setQuery($query);

		$status = ($db->loadResult() > 0) ? true : false;

		if ($db->getErrorNum()){
			JError::raiseError(500, $db->stderr());
		}

		return $status;
	}

	/**
	 * Count total pending event invitations.
	 *
	 **/
	public function countPending($id){
		$db = & $this->getDBO();

		$query	=	"SELECT COUNT(*) FROM "
					. $db->nameQuote("#__community_events_members") . " "
					. "WHERE " . $db->nameQuote("memberid") . "=" . $db->quote($id) . " "
					. "AND " . $db->nameQuote("status") . "=" . $db->quote(COMMUNITY_EVENT_STATUS_INVITED) . " "
					. "ORDER BY " . $db->nameQuote("id") . " DESC";

		$db->setQuery($query);
		
		if ($db->getErrorNum())
		{
			JError::raiseError(500, $db->stderr());
		}

		return $db->loadResult();
	}

}

