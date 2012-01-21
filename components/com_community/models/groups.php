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
CFactory::load( 'tables' , 'group' );
CFactory::load( 'tables' , 'bulletin' );
CFactory::load( 'tables' , 'groupinvite' );
CFactory::load( 'tables' , 'groupmembers' );
CFactory::load( 'tables' , 'discussion' );
CFactory::load( 'tables' , 'category' );

class CommunityModelGroups extends JCCModel
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
	function CommunityModelGroups()
	{
		parent::JCCModel();
		
		$mainframe =& JFactory::getApplication();
		
		// Get pagination request variables
 	 	$limit		= ($mainframe->getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');
	    $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');

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
	 * Deprecated since 1.8, use $groupd->updateStats()->store();
	 */	 	
	function addWallCount( $groupId )
	{
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		$group->store();
	}
	
	/**
	 * Deprecated since 1.8, use $groupd->updateStats()->store();
	 */
	function substractWallCount( $groupId )
	{
		$this->addWallCount($groupId);
	}
	
	/**
	 * Deprecated since 1.8, use $groupd->updateStats()->store();
	 */
	function addMembersCount( $groupId )
	{
		$this->addWallCount($groupId);
	}
	
	/**
	 * Deprecated since 1.8, use $groupd->updateStats()->store();
	 */
	function substractMembersCount( $groupId )
	{
		$this->addWallCount($groupId);
	}
	
	/**
	 * Deprecated since 1.8, use $groupd->updateStats()->store();
	 */
	function addDiscussCount( $groupId )
	{
		$this->addWallCount($groupId);
	}
	
	/**
	 * Deprecated since 1.8, use $groupd->updateStats()->store();
	 */
	function substractDiscussCount( $groupId )
	{
		$this->addWallCount($groupId);
	}

	function getGroupInvites( $userId , $sorting = null )
	{
		$db			=& $this->getDBO();
		$extraSQL	= ' AND a.userid=' . $db->Quote($userId);
		$orderBy	= '';
		$limit			= $this->getState('limit');
		$limitstart 	= $this->getState('limitstart');
		$total			= 0;
		
		
		switch($sorting)
		{
			
			case 'mostmembers':
				// Get the groups that this user is assigned to
				$query		= 'SELECT a.groupid FROM ' . $db->nameQuote('#__community_groups_invite') . ' AS a '
							. 'LEFT JOIN ' . $db->nameQuote('#__community_groups_members') . ' AS b '
							. 'ON a.groupid=b.groupid '
							. 'WHERE b.approved=' . $db->Quote( '1' )
							. $extraSQL; 

				$db->setQuery( $query );
				$groupsid		= $db->loadResultArray();
				
				if($db->getErrorNum())
				{
					JError::raiseError( 500, $db->stderr());
				}
				
				if( $groupsid )
				{
					$groupsid		= implode( ',' , $groupsid );
	
					$query			= 'SELECT a.* '
									. 'FROM ' . $db->nameQuote('#__community_groups_invite') . ' AS a '
									. 'INNER JOIN #__community_groups AS b '
									. 'ON a.groupid=b.id '
									. 'WHERE a.groupid IN (' . $groupsid . ') '
									. 'ORDER BY b.membercount DESC '
									. 'LIMIT ' . $limitstart . ',' . $limit;	
				}
				break;
			case 'mostdiscussed':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY b.discusscount DESC ';
			case 'mostwall':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY b.wallcount DESC ';
			case 'alphabetical':
				if( empty($orderBy) )
					$orderBy	= 'ORDER BY b.name ASC ';
			case 'mostactive':
				//@todo: Add sql queries for most active group
			
			default:
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY b.created DESC ';

				$query	= 'SELECT distinct a.* FROM '
						. $db->nameQuote('#__community_groups_invite') . ' AS a '
						. 'INNER JOIN ' . $db->nameQuote( '#__community_groups' ) . ' AS b ON a.groupid=b.id '
						. 'INNER JOIN ' . $db->nameQuote('#__community_groups_members') . ' AS c ON a.groupid=c.groupid '
						. 'AND c.approved=' . $db->Quote( '1' ) . ' '
						. 'AND b.published=' . $db->Quote( '1' ) . ' '
						. $extraSQL
						. $orderBy
						. 'LIMIT ' . $limitstart . ',' . $limit;
				break;
		}
		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		$query	= 'SELECT COUNT(distinct b.id) FROM ' . $db->nameQuote('#__community_groups_invite') . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__community_groups' ) . ' AS b '
				. 'ON a.groupid=b.id '
				. 'INNER JOIN ' . $db->nameQuote('#__community_groups_members') . ' AS c '
				. 'ON a.groupid=c.groupid '
				. 'WHERE b.published=' . $db->Quote( '1' ) . ' '
				. 'AND c.approved=' . $db->Quote( '1' )
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
	 * Returns an object of groups which the user has registered.
	 * 	 	
	 * @access	public
	 * @param	string 	User's id.
	 * @returns array  An objects of custom fields.	 
	 * @todo: re-order with most active group stays on top	 
	 */	 
	function getGroups( $userId = null , $sorting = null )
	{
		$db		=& $this->getDBO();

		$extraSQL	= '';
		
		
		if( !is_null($userId) )
		{
			$extraSQL	= ' AND b.memberid=' . $db->Quote($userId);
		}

		$orderBy	= '';

		$limit			= $this->getState('limit');
		$limitstart 	= $this->getState('limitstart');
		$total			= 0;
		
		switch($sorting)
		{
			case 'mostmembers':
				// Get the groups that this user is assigned to
				$query		= 'SELECT a.id FROM ' . $db->nameQuote('#__community_groups') . ' AS a '
							. 'LEFT JOIN ' . $db->nameQuote('#__community_groups_members') . ' AS b '
							. 'ON a.id=b.groupid '
							. 'WHERE b.approved=' . $db->Quote( '1' )
							. $extraSQL; 

				$db->setQuery( $query );
				$groupsid		= $db->loadResultArray();
				
				if($db->getErrorNum())
				{
					JError::raiseError( 500, $db->stderr());
				}
				
				if( $groupsid )
				{
					$groupsid		= implode( ',' , $groupsid );
	
					$query			= 'SELECT a.* '
									. 'FROM ' . $db->nameQuote('#__community_groups') . ' AS a '
									. 'WHERE a.published=' . $db->Quote( '1' ) . ' '
									. 'AND a.id IN (' . $groupsid . ') '
									. 'ORDER BY a.membercount DESC '
									. 'LIMIT ' . $limitstart . ',' . $limit;	
				}
				break;
			case 'mostdiscussed':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY a.discusscount DESC ';
			case 'mostwall':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY a.wallcount DESC ';
			case 'alphabetical':
				if( empty($orderBy) )
					$orderBy	= 'ORDER BY a.name ASC ';
			case 'mostactive': 
				//@todo: Add sql queries for most active group
			default:
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY a.created DESC ';

				$query	= 'SELECT a.* FROM '
						. $db->nameQuote('#__community_groups') . ' AS a '
						. 'INNER JOIN ' . $db->nameQuote('#__community_groups_members') . ' AS b ON a.id=b.groupid '
						. 'AND b.approved=' . $db->Quote( '1' ) . ' '
						. 'AND a.published=' . $db->Quote( '1' ) . ' '
						. $extraSQL
						. $orderBy
						. 'LIMIT ' . $limitstart . ',' . $limit;
				break;
		}  
		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote('#__community_groups') . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote('#__community_groups_members') . ' AS b '
				. 'WHERE a.id=b.groupid '
				. 'AND a.published=' . $db->Quote( '1' ) . ' '
				. 'AND b.approved=' . $db->Quote( '1' )
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
	function getGroupsCount( $userId )
	{
		// guest obviously has no group
		if($userId == 0)
			return 0;
		
		
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' 
				. $db->nameQuote( '#__community_groups_members' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'memberid' ) . '=' . $db->Quote( $userId ) . ' '
				. 'AND ' . $db->nameQuote( 'approved' ) . '=' . $db->Quote( '1' );
		$db->setQuery( $query );
		$count	= $db->loadResult();
		
		return $count;
	}
	
	/**
	 * Return the number of groups cretion count for specific user
	 **/	 	
	function getGroupsCreationCount( $userId )
	{
		// guest obviously has no group
		if($userId == 0)
			return 0;
			
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' 
				. $db->nameQuote( '#__community_groups' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'ownerid' ) . '=' . $db->Quote( $userId );				
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
			$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote('#__community_groups_members') . ' '
					. 'WHERE groupid=' . $db->Quote( $id ) . ' '
					. 'AND ' . $db->nameQuote( 'approved' ) . '=' . $db->Quote( '1' );
			
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
	 * Returns All the groups
	 *
	 * @access	public
	 * @param	string 	Category id
	 * @param	string	The sort type
	 * @param	string	Search value
	 * @return	Array	An array of group objects
	 */	 
	function getAllGroups( $categoryId = null , $sorting = null , $search = null , $limit = null , $skipDefaultAvatar = false )
	{
		$db		=& $this->getDBO();
		
		$extraSQL	= '';
		$pextra		= '';
		
		if( is_null( $limit ) )
		{
			$limit		= $this->getState('limit');
		}
		$limit	= ($limit < 0) ? 0 : $limit;
		$limitstart = $this->getState('limitstart');
		
		// Test if search is parsed
		if( !is_null( $search ) )
		{
			$extraSQL	.= " AND a.name LIKE " . $db->Quote( '%' . $search . '%' ) . " ";
		}

		if( $skipDefaultAvatar )
		{
			$extraSQL	.= ' AND ( a.thumb != ' . $db->Quote( DEFAULT_GROUP_THUMB ) . ' AND a.avatar != ' . $db->Quote( DEFAULT_GROUP_AVATAR ) . ' )';
		}
		$order	=''; 
		switch ( $sorting )
		{
			case 'alphabetical':
				$order		= ' ORDER BY a.name ASC ';
				break;
			case 'mostdiscussed':
				$order	= ' ORDER BY discusscount DESC ';
				break;
			case 'mostwall':
				$order	= ' ORDER BY wallcount DESC ';
				break;
			case 'mostmembers':
				$order	= ' ORDER BY membercount DESC ';
				break;
			default:
				$order	= 'ORDER BY a.created DESC ';
				break;
// 			case 'mostactive':
// 				$order	= ' ORDER BY count DESC';
// 				break;
		}

		if( !is_null($categoryId) && $categoryId != 0 )
		{
			$extraSQL	.= ' AND a.categoryid=' . $db->Quote($categoryId) . ' ';
		//	$pextra		.= ' WHERE a.categoryid=' . $db->Quote($categoryId);
		}
		
		/*
		// Super slow query
        $query = 'SELECT a.*,'
				. 'COUNT(DISTINCT(b.memberid)) AS membercount,'
				. 'COUNT(DISTINCT(c.id)) AS discussioncount,'
				. 'COUNT(DISTINCT(d.id)) AS wallcount '
				. 'FROM ' . $db->nameQuote( '#__community_groups' ) . ' AS a ' 
        		. 'INNER JOIN ' . $db->nameQuote( '#__community_groups_members' ) . ' AS b '
        		. 'ON a.id=b.groupid '
        		. $extraSQL
        		. 'AND b.approved=' . $db->Quote( '1' ) . ' '
        		. 'AND a.published=' . $db->Quote( '1' ) . ' '
        		. 'LEFT JOIN ' . $db->nameQuote( '#__community_groups_discuss' ) . ' AS c '
        		. 'ON a.id=c.groupid '
        		. 'AND c.parentid=' . $db->Quote( '0' ) . ' '
        		. 'LEFT JOIN ' . $db->nameQuote( '#__community_wall') . ' AS d '
        		. 'ON a.id=d.contentid '
				. 'AND d.type=' . $db->Quote( 'groups' ) . ' '
				. 'AND d.published=' . $db->Quote( '1' ) . ' '
                . 'GROUP BY a.id '
                . $order
				. ' LIMIT ' . $limitstart . ',' . $limit;

		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		*/

		if ($sorting == 'mostactive')
		{
			$query = " SELECT *, 
							".$db->nameQuote('cid').", 
							COUNT(".$db->nameQuote('cid').") AS ".$db->nameQuote('count')." 
					   FROM 
							".$db->nameQuote('#__community_activities')." AS a
				 INNER JOIN	".$db->nameQuote('#__community_groups')." AS b ON a.".$db->nameQuote('cid')." = b.".$db->nameQuote('id')."
					  WHERE 
							a.".$db->nameQuote('app')." = ".$db->quote('groups')." AND
							b.".$db->nameQuote('published')." = ".$db->quote('1')." AND
							a.".$db->nameQuote('archived')." = ".$db->quote('0')." AND
							a.".$db->nameQuote('cid')." != ".$db->quote('0')." 
				   GROUP BY a.".$db->nameQuote('cid')."
				   ORDER BY ".$db->nameQuote('count')." DESC
				   LIMIT $limitstart , $limit";
		}
		else
		{
			$query = "SELECT * FROM #__community_groups as a WHERE a.`published`='1' "
					. $extraSQL 
					. $order
					. " LIMIT $limitstart , $limit";
		}
        
		$db->setQuery( $query );
		$rows	= $db->loadObjectList();
		
// 		if(!empty($rows)){
// 			for($i = 0; $i < count($rows); $i++){
// 				
// 				// Count no of members
// 				$query = "SELECT COUNT(*) FROM #__community_groups_members WHERE `approved`='1' "
// 					. " AND groupid=".$db->Quote($rows[$i]->id);
// 				$db->setQuery( $query );
// 				$rows[$i]->membercount = $db->loadResult();
// 				
// 				// Count wall post
// 				$query = "SELECT COUNT(*) FROM #__community_wall WHERE "
// 					. " `contentid`=".$db->Quote($rows[$i]->id)
// 					. " AND type=".$db->Quote('groups')
// 					. " AND published=".$db->Quote('1');
// 					
// 				$db->setQuery( $query );
// 				$rows[$i]->wallcount = $db->loadResult();
// 				
// 				// Count discussion
// 				$query = "SELECT groupid FROM #__community_groups_discuss "
// 					. " WHERE groupid=".$db->Quote($rows[$i]->id)
// 					. " AND parentid=" . $db->Quote( '0' );
// 				$db->setQuery( $query );
// 				$rows[$i]->discussioncount = $db->loadResult();
// 			}
// 		}

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		$query	= 'SELECT COUNT(*) FROM #__community_groups AS a '
				. 'WHERE a.published=' . $db->Quote( '1' )
				. $extraSQL;

		$db->setQuery( $query );
		$total	= ($sorting == 'mostactive') ? count($rows) : $db->loadResult();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		if( empty($this->_pagination) )
		{
			jimport('joomla.html.pagination');
			
			$this->_pagination	= new JPagination( $total , $limitstart , $limit);
		}
		
		return $rows;
	}
	
	/**
	 * Returns an object of group
	 * 	 	
	 * @access	public
	 * @param	string 	Group Id
	 * @returns object  An object of the specific group	 
	 */
	function & getGroup( $id )
	{
		$db		=& $this->getDBO();

		$query	= 'SELECT a.*, b.name AS ownername , c.name AS category FROM ' 
				. $db->nameQuote('#__community_groups') . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote('#__users') . ' AS b '
				. 'INNER JOIN ' . $db->nameQuote('#__community_groups_category') . ' AS c '
				. 'WHERE a.id=' . $db->Quote( $id ) . ' '
				. 'AND a.ownerid=b.id '
				. 'AND a.categoryid=c.id ';

		$db->setQuery( $query );
		$result	= $db->loadObject();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
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
		$query	= 'SELECT a.*, COUNT(b.id) AS count '
				. 'FROM ' . $db->nameQuote('#__community_groups_category') . ' AS a '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_groups' ) . ' AS b '
				. 'ON a.id=b.categoryid '
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
				. 'FROM ' . $db->nameQuote('#__community_groups_category') . ' '
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
	 * Returns the members list for the specific groups
	 * 
	 * @access public
	 * @param	string Category Id
	 * @returns string	Category name
	 **/	 	 	 	 	 	
	function getAdmins( $groupid , $limit = 0 , $randomize = false )
	{
		CError::assert( $groupid , '', '!empty', __FILE__ , __LINE__ );
		
		$db		=& $this->getDBO();

		$limit		= ($limit == 0) ? $this->getState('limit') : $limit;
		$limitstart = $this->getState('limitstart');
		
		$query	= 'SELECT a.memberid AS id, a.approved , b.name as name FROM '
				. $db->nameQuote('#__community_groups_members') . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote('#__users') . ' AS b '
				. 'WHERE b.id=a.memberid '
				. 'AND a.groupid=' . $db->Quote( $groupid ) . ' '
				. 'AND a.permissions=' . $db->Quote( '1' );
		
		if($randomize)
		{
			$query	.= ' ORDER BY RAND() ';
		}
		
		if( !is_null($limit) )
		{
			$query	.= ' LIMIT ' . $limitstart . ',' . $limit;
		}
		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		$query	= 'SELECT COUNT(*) FROM '
				. $db->nameQuote('#__community_groups_members') . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote('#__users') . ' AS b '
				. 'WHERE b.id=a.memberid '
				. 'AND a.groupid=' . $db->Quote( $groupid ) . ' '
				. 'AND a.permissions=' . $db->Quote( '1' );
		$db->setQuery( $query );
		$total	= $db->loadResult();
		$this->total	= $total;
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}

		if( empty($this->_pagination) )
		{
			jimport('joomla.html.pagination');
			
			$this->_pagination	= new JPagination( $total , $limitstart , $limit);
		}

		return $result;
	}
	
	/**
	 * Returns the members list for the specific groups
	 * 
	 * @access public
	 * @param	string Category Id
	 * @returns string	Category name
	 **/	 	 	 	 	 	
	function getMembers( $groupid , $limit = 0 , $onlyApproved = true , $randomize = false , $loadAdmin = false )
	{
		CError::assert( $groupid , '', '!empty', __FILE__ , __LINE__ );
		
		$db		=& $this->getDBO();

		$limit		= ($limit == 0) ? $this->getState('limit') : $limit;
		$limitstart = $this->getState('limitstart');
		
		$query	= 'SELECT a.memberid AS id, a.approved , b.name as name FROM '
				. $db->nameQuote('#__community_groups_members') . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote('#__users') . ' AS b '
				. 'WHERE b.id=a.memberid '
				. 'AND a.groupid=' . $db->Quote( $groupid );
		
		if( $onlyApproved )
		{
			$query	.= ' AND a.approved=' . $db->Quote( '1' );
		}
		else
		{
			$query	.= ' AND a.approved=' . $db->Quote( '0' );
		}
		
		if( !$loadAdmin )
		{
			$query	.= ' AND a.permissions=' . $db->Quote( '0' ); 
		}
		
		if($randomize)
		{
			$query	.= ' ORDER BY RAND() ';
		}
		else
		{
			$config	= CFactory::getConfig();
			$query	.= ' ORDER BY b.`' . $config->get( 'displayname') . '`';
		}
		
		if( !is_null($limit) )
		{
			$query	.= ' LIMIT ' . $limitstart . ',' . $limit;
		}
		
		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		$query	= 'SELECT COUNT(*) FROM '
				. $db->nameQuote('#__community_groups_members') . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote('#__users') . ' AS b '
				. 'WHERE b.id=a.memberid '
				. 'AND a.groupid=' . $db->Quote( $groupid );

		if( $onlyApproved )
		{
			$query	.= ' AND a.approved=' . $db->Quote( '1' );
		}
		else
		{
			$query	.= ' AND a.approved=' . $db->Quote( '0' );
		}

		if( !$loadAdmin )
		{
			$query	.= ' AND a.permissions=' . $db->Quote( '0' ); 
		}

		$db->setQuery( $query );
		$total	= $db->loadResult();
		$this->total	= $total;
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}

		if( empty($this->_pagination) )
		{
			jimport('joomla.html.pagination');
			
			$this->_pagination	= new JPagination( $total , $limitstart , $limit);
		}

		return $result;
	}
	
	/**
	 * Check if the given group name exist.
	 * if id is specified, only search for those NOT within $id	 
	 */	 	
	function groupExist($name, $id=0) {
		$db		=& $this->getDBO();
		
		$strSQL	= 'SELECT count(*) FROM `#__community_groups`'
			. " WHERE `name`=" . $db->Quote( $name ) 
			. " AND `id`!=". $db->Quote( $id ) ;


		$db->setQuery( $strSQL );
		$result	= $db->loadResult();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result;
	}

	function getMembersId( $groupid , $onlyApproved = false )
	{
		$db		=& $this->getDBO();

		$query	= 'SELECT a.memberid AS id FROM '
				. $db->nameQuote('#__community_groups_members') . ' AS a '
				. 'WHERE a.groupid=' . $db->Quote( $groupid );

		if( $onlyApproved )
			$query	.= ' AND ' . $db->nameQuote( 'approved' ) . '=' . $db->Quote( '1' );

		$db->setQuery( $query );
		$result	= $db->loadResultArray();
		
		return $result;
	}
	
	function updateGroup($data)
	{
		$db		=& $this->getDBO();
		
		if($data->id == 0)
		{
			// New record, insert it.
			$db->insertObject( '#__community_groups' , $data );

			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
			}
			$data->id				= $db->insertid();
			
			// Insert an object for this user in the #__community_groups_members as well
			$members				= new stdClass();
			$members->groupid		= $data->id;
			$members->memberid		= $data->ownerid;

									
			// Creator should always be 1 as approved as they are the creator.
			$members->approved		= 1;
			$members->permissions	= 'admin';

			$db->insertObject( '#__community_groups_members' , $members );

			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
			}
		}
		else
		{
			// Old record, update it.
			$db->updateObject( '#__community_groups' , $data , 'id');
		}
		return $data->id;
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
		// If avatar is not empty, we assume that this will be newly created groups that stores
		// the avatar in the groups table
		$config		=& CFactory::getConfig();
		$baseUrl	= $config->get('imagebaseurl');
		
		if( !empty( $baseUrl) )
		{
			$baseUrl	= JString::strpos( $avatar , 'assets/group_thumb' ) === false ? $baseUrl : JURI::root();
		}
		else
		{
			$baseUrl	= JURI::root();
		}
		return rtrim( $baseUrl , '/' ) . '/' . $avatar;
	}

	/**
	 *	Set the avatar for for specific group
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
		$query		= 'SELECT ' . $db->nameQuote( $type ) . ' FROM ' . $db->nameQuote( '#__community_groups' )
					. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $id );
		
		$db->setQuery( $query );
		$oldFile	= $db->loadResult();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
	    }
	    
	    if( !$oldFile )
	    {
	    	$query	= 'UPDATE ' . $db->nameQuote( '#__community_groups' ) . ' '
	    			. 'SET ' . $db->nameQuote( $type ) . '=' . $db->Quote( $path ) . ' '
	    			. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $id );
	    	$db->setQuery( $query );
	    	$db->query( $query );

			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
		    }
		}
		else
		{

	    	$query	= 'UPDATE ' . $db->nameQuote( '#__community_groups' ) . ' '
	    			. 'SET ' . $db->nameQuote( $type ) . '=' . $db->Quote( $path ) . ' '
	    			. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $id );
	    	$db->setQuery( $query );
	    	$db->query( $query );

			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
		    }
		    
			// File exists, try to remove old files first.
			$oldFile	= JString::str_ireplace( '/' , DS , $oldFile );

			// If old file is default_thumb or default, we should not remove it.
			// Need proper way to test it
			if(!JString::stristr( $oldFile , 'group.jpg' ) && !JString::stristr( $oldFile , 'group_thumb.jpg' ) &&
			   !JString::stristr( $oldFile , 'default.jpg' ) && !JString::stristr( $oldFile , 'default_thumb.jpg' ) )
			{
				jimport( 'joomla.filesystem.file' );
				JFile::delete($oldFile);
			}
		}
	}
		
	function removeMember( $data )
	{
		$db	=& $this->getDBO();
		
		$strSQL	= 'DELETE FROM ' . $db->nameQuote('#__community_groups_members') . ' '
				. 'WHERE ' . $db->nameQuote('groupid') . '=' . $db->Quote( $data->groupid ) . ' '
				. 'AND ' . $db->nameQuote('memberid') . '=' . $db->Quote( $data->memberid );
		
		$db->setQuery( $strSQL );
		$db->query();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
	}

	/**
	 * Check if the user is a group creator 
	 */
	function isCreator( $userId , $groupId )
	{
		// guest is not a member of any group
		if($userId == 0)
			return false;
			
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_groups' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $groupId ) . ' '
				. 'AND ' . $db->nameQuote( 'ownerid' ) . '=' . $db->Quote( $userId );
		$db->setQuery( $query );
		
		$isCreator	= ( $db->loadResult() >= 1 ) ? true : false;
		return $isCreator;
	}
	
	/**
	 * Check if the user is a group admin 
	 */	 	
	function isAdmin($userid, $groupid)
	{
		if($userid == 0)
			return false;
			
		$db		=& $this->getDBO();

		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote('#__community_groups_members') . ' '
				. ' WHERE ' . $db->nameQuote('groupid') . '=' . $db->Quote($groupid) . ' '
				. ' AND ' . $db->nameQuote('memberid') . '=' . $db->Quote($userid) 
				. ' AND `permissions`=' . $db->Quote( '1' );
				
		$db->setQuery( $query );
		$isAdmin	= ( $db->loadResult() >= 1 ) ? true : false;

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		//@remove: in newer version we need to skip this test as we were using 'admin'
		// as the permission for the creator
		if( !$isAdmin )
		{
			$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_groups' ) . ' '
					. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $groupid ) . ' '
					. 'AND ' . $db->nameQuote( 'ownerid' ) . '=' . $db->Quote( $userid );
			$db->setQuery( $query );
			
			$isAdmin	= ( $db->loadResult() >= 1 ) ? true : false;

			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
			}
			
			// If user is admin, update necessary records
			if( $isAdmin )
			{
				$members	=& JTable::getInstance( 'GroupMembers' , 'CTable' );
				$members->load( $userid , $groupid );
				$members->permissions	= '1';
				$members->store();
			}
		}
		
		return $isAdmin;
	}
	
	/**
	 * Check if the given user is a member of the group
	 * @param	string	userid	
	 * @param	string	groupid	 	 
	 */	 	
	function isMember($userid, $groupid) {
		
		// guest is not a member of any group
		if($userid == 0)
			return false;
			
		$db	=& $this->getDBO();
		$strSQL	= 'SELECT COUNT(*) FROM ' . $db->nameQuote('#__community_groups_members') . ' '
				. 'WHERE ' . $db->nameQuote('groupid') . '=' . $db->Quote($groupid) . ' '
				. 'AND ' . $db->nameQuote('memberid') . '=' . $db->Quote($userid)
				. 'AND ' . $db->nameQuote( 'approved' ) .'=' . $db->Quote( '1' );
				
		$db->setQuery( $strSQL );
		$count	= $db->loadResult();
		return $count;
	}
	
	/**
	 * See if the given user is waiting authorization for the group
	 * @param	string	userid	
	 * @param	string	groupid	 	 
	 */	 	
	function isWaitingAuthorization($userid, $groupid) {
		// guest is not a member of any group
		if($userid == 0)
			return false;
			
		$db	=& $this->getDBO();
		$strSQL	= 'SELECT COUNT(*) FROM `#__community_groups_members` '
				. 'WHERE ' . $db->nameQuote('groupid') . '=' . $db->Quote($groupid) . ' '
				. 'AND ' . $db->nameQuote('memberid') . '=' . $db->Quote($userid)
				. 'AND ' . $db->nameQuote('approved') . '=' . $db->Quote(0);
				
		$db->setQuery( $strSQL );
		$count	= $db->loadResult();
		return $count;
	}

	/**
	 * Gets the groups property if it requires an approval or not.
	 * 
	 * param	string	id The id of the group.
	 * 
	 * return	boolean	True if it requires approval and False otherwise
	 **/	
	function needsApproval( $id )
	{
		$db		=& $this->getDBO();
		$query	= 'SELECT ' . $db->nameQuote( 'approvals' ) . ' FROM '
				. $db->nameQuote( '#__community_groups' ) . ' WHERE '
				. $db->nameQuote( 'id' ) . '=' . $db->Quote( $id );
				
		$db->setQuery( $query );
		$result	= $db->loadResult();
		
		return ( $result == '1' );
	}
	
	/**
	 * Sets the member data in the group members table
	 * 
	 * param	Object	An object that contains the fields value
	 * 	 
	 **/	 	
	function approveMember( $groupid , $memberid )
	{
		$db		=& $this->getDBO();

		$query	= 'UPDATE ' . $db->nameQuote( '#__community_groups_members' ) . ' SET '
				. $db->nameQuote( 'approved' ) . '=' . $db->Quote( '1' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'groupid' ) . '=' . $db->Quote( $groupid ) . ' '
				. 'AND ' . $db->nameQuote( 'memberid' ) . '=' . $db->Quote( $memberid );

		$db->setQuery( $query );
		$db->query();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	/**
	 * Sets the member data in the group members table
	 * 
	 * param	Object	An object that contains the fields value
	 * 	 
	 **/	 	
	function setMembers( $data )
	{
		$db	=& $this->getDBO();
		
		// Test if user if already exists
		if( !$this->isMember($data->memberid, $data->groupid) )
			$db->insertObject('#__community_groups_members' , $data);

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		return $data;
	}
	
	/**
	 * Delete group's bulletin 
	 * 
	 * param	string	id The id of the group.
	 * 	 
	 **/
	function deleteGroupBulletins($gid)
	{
		$db =& JFactory::getDBO();
				
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_groups_bulletins")." 
				WHERE 
						".$db->nameQuote("groupid")." = ".$db->quote($gid);
						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		return true;
	}
	
	/**
	 * Delete group's member
	 * 
	 * param	string	id The id of the group.
	 * 	 
	 **/
	function deleteGroupMembers($gid)
	{
		$db =& JFactory::getDBO();
				
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_groups_members")." 
				WHERE 
						".$db->nameQuote("groupid")."=".$db->quote($gid);						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		return true;
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
	
	/**
	 * Delete group's discussion
	 * 
	 * param	string	id The id of the group.
	 * 	 
	 **/
	function deleteGroupDiscussions($gid)
	{
		$db =& JFactory::getDBO();
	
		$sql = "SELECT 
						".$db->nameQuote("id")." 						
				FROM 
						".$db->nameQuote("#__community_groups_discuss")." 
				WHERE 
						".$db->nameQuote("groupid")." = ".$gid;						
		$db->setQuery($sql);
		$row = $db->loadobjectList();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		if(!empty($row))
		{
			$ids_array = array();	
			foreach($row as $tempid)
			{
				array_push($ids_array, $tempid->id);
			}
			$ids = implode(',', $ids_array);
		}			
					
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_groups_discuss")." 
				WHERE 
						".$db->nameQuote("groupid")." = ".$gid;				
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		if(!empty($ids))
		{				
			$sql = "DELETE 
					
					FROM 
							".$db->nameQuote("#__community_wall")." 
					WHERE 
							".$db->nameQuote("contentid")." IN (".$ids.") AND 
							".$db->nameQuote("type")." = ".$db->quote('discussions');				
			$db->setQuery($sql);
			$db->Query();
			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
			}
		}
		
		return true;
	}
	
	/**
	 * Delete group's media
	 * 
	 * param	string	id The id of the group.
	 * 	 
	 **/	
	function deleteGroupMedia($gid)
	{		
		$db 			=& JFactory::getDBO();
		$photosModel	=& CFactory::getModel( 'photos' );
		$videoModel		=& CFactory::getModel( 'videos' );
		
		// group's photos removal.
		$albums			=& $photosModel->getGroupAlbums($gid , false, false, 0);		
		foreach ($albums as $item)
		{
			$photos			= $photosModel->getAllPhotos($item->id, PHOTOS_GROUP_TYPE);
			
			foreach ($photos as $row)
			{
				$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
				$photo->load($row->id);
				$photo->delete();
			}
			
			//now we delete group photo album folder
			$album	=& JTable::getInstance( 'Album' , 'CTable' );
			$album->load($item->id);
			$album->delete();
		}
		
		//group's videos
		CFactory::load('libraries', 'storage');
		CFactory::load('libraries','featured');
		$featuredVideo	= new CFeatured(FEATURED_VIDEOS);
		$videos			= $videoModel->getGroupVideos($gid);
		
		foreach($videos as $vitem)
		{
			if (!$vitem) continue;
				
			$video		= JTable::getInstance( 'Video' , 'CTable' );
			$videaId	= (int) $vitem->id;
						
			$video->load($videaId);
									
			if($video->delete())
			{
				// Delete all videos related data											
				$videoModel->deleteVideoWalls($videaId);				
				$videoModel->deleteVideoActivities($videaId);
								
				//remove featured video								
				$featuredVideo->delete($videaId);				
																
				//remove the physical file				
				$storage = CStorage::getStorage($video->storage);
				if ($storage->exists($video->thumb))
				{
					$storage->delete($video->thumb);
				}
								
				if ($storage->exists($video->path))
				{
					$storage->delete($video->path);
				}
			}
			
		}
		
		return true;
	}
	
	/*
	 * group category id - int
	 * list of group ids - string, separate by comma
	 * limit - int
	 */
	
	function getGroupLatestDiscussion($category = 0, $groupids = '', $limit = '')
	{
	    $db 	=& JFactory::getDBO();
	    $config =& CFactory::getConfig();
	    
	    $limit  = (empty($limit)) ? $config->get('groupdiscussionmaxlist', '5') : $limit;
	    
		$query  =  ' select a.id, a.groupid, a.creator, a.title,a.message, a.lastreplied, b.post_by as `lastreplied_by`';
		$query	.= ' from #__community_groups_discuss as a';
		$query	.= ' left join #__community_wall as b';
		$query	.= ' on a.id = b.contentid and b.`type` = ' . $db->Quote('discussions');
		$query	.= ' inner join #__community_groups as d';
		$query	.= ' on a.groupid = d.id';
                $query  .= ' and d.approvals = ' . $db->Quote(0);
		if ($category != 0)
		    $query	.= ' and d.categoryid = ' . $db->Quote($category);
		if ($groupids != '')
		    $query	.= ' and d.id IN (' . $groupids . ')';
		$query	.= ' where (b.date = (select max(c.date) from #__community_wall as c where c.contentid = a.id) or b.post_by is null)';
		
		$query	.= ' group by a.id';
		$query  .= ' order by a.lastreplied desc';
		if(! empty($limit))
		    $query  .= ' LIMIT ' . $limit;
	    
	    
	    $db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;
	}
	
}
