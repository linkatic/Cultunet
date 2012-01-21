<?php
/**
 * @category	Tables
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CTableGroup extends JTable
{

	var $id 			= null;
	var $published		= null;
	var $ownerid 		= null;
	var $categoryid 	= null;
	var $name 			= null;
	var $description	= null;
	var $email			= null;
	var $website 		= null;
	var $approvals 		= null;
	var $created 		= null;
  	var $avatar			= null;
  	var $thumb			= null;
  	var $discusscount	= null;
  	var $wallcount		= null;
  	var $membercount	= null;
  	var $params			= null;
  	var $_pagination	= null;
  	
	/**
	 * Constructor
	 */	 	
	function __construct( &$db )
	{
		parent::__construct( '#__community_groups', 'id', $db );
	}

	function getPagination()
	{
		return $this->_pagination;
	}
	
	/**
	 * Update all internal count without saving them
	 */	 	
	function updateStats()
	{
		if( $this->id != 0 )
		{
			$db	=& JFactory::getDBO();
			
			// @rule: Update the members count each time stored is executed.
			$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__community_groups_members' ) . ' '
					. 'WHERE groupid=' . $db->Quote( $this->id ) . ' '
					. 'AND approved=' . $db->Quote( '1' );

			$db->setQuery( $query );
			$this->membercount	= $db->loadResult();

			// @rule: Update the discussion count each time stored is executed.
			$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__community_groups_discuss' ) . ' '
					. 'WHERE groupid=' . $db->Quote( $this->id );

			$db->setQuery( $query );
			$this->discusscount	= $db->loadResult();

			// @rule: Update the wall count each time stored is executed.
			$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__community_wall' ) . ' '
					. 'WHERE contentid=' . $db->Quote( $this->id ) . ' '
					. 'AND type=' . $db->Quote( 'groups' );

			$db->setQuery( $query );
			$this->wallcount	= $db->loadResult();
		}
	}
	
	public function check()
	{
		// Santinise data
		$safeHtmlFilter		= CFactory::getInputFilter();
		$this->name			= $safeHtmlFilter->clean($this->name);
		$this->description 	= $safeHtmlFilter->clean($this->description);
		$this->email 		= $safeHtmlFilter->clean($this->email);
		$this->website 		= $safeHtmlFilter->clean($this->website);
		
		return true;
	}
	
	/**
	 * Binds an array into this object's property
	 *
	 * @access	public
	 * @param	$data	mixed	An associative array or object
	 **/
	function store()
	{
		if (!$this->check()) {
			return false;
		}
		
		return parent::store();
	}

	/**
	 * Return the category name for the current group
	 * 
	 * @return string	The category name
	 **/
	function getCategoryName()
	{
		$category	=& JTable::getInstance( 'Category' , 'CTable' );
		$category->load( $this->categoryid );

		return $category->name;
	}

	/**
	 * Return the full URL path for the specific image
	 * 
	 * @param	string	$type	The type of avatar to look for 'thumb' or 'avatar'. Deprecated since 1.8 
	 * @return string	The avatar's URI
	 **/
	function getAvatar( $type = 'thumb' )
	{
		if( $type == 'thumb' )
		{
			return $this->getThumbAvatar();
		}
		
		// Get the avatar path. Some maintance/cleaning work: We no longer store
		// the default avatar in db. If the default avatar is found, we reset it
		// to empty. In next release, we'll rewrite this portion accordingly.
		// We allow the default avatar to be template specific.
		if ($this->avatar == 'components/com_community/assets/group.jpg')
		{
			$this->avatar = '';
			$this->store();
		}
		CFactory::load('helpers', 'url');
		$avatar	= CUrlHelper::avatarURI($this->avatar, 'groupAvatar.png');
		
		return $avatar;
	}

	function getThumbAvatar()
	{
		if ($this->thumb == 'components/com_community/assets/group_thumb.jpg')
		{
			$this->thumb = '';
			$this->store();
		}
		CFactory::load('helpers', 'url');
		$thumb	= CUrlHelper::avatarURI($this->thumb, 'groupThumbAvatar.png');
		
		return $thumb;
	}
	
	/**
	 * Return the owner's name for the current group
	 * 
	 * @return string	The owner's name
	 **/	 	
	function getOwnerName()
	{
		$user		= CFactory::getUser( $this->ownerid );
		return $user->getDisplayName();
	}

	function getParams()
	{
		$params	= new JParameter( $this->params );
		
		return $params;
	}
	
	/**
	 * Method to determine whether the specific user is a member of a group
	 * 
	 * @param	string	User's id
	 * @return boolean True if user is registered and false otherwise
	 **/
	function isMember( $userid )
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' 
				. $db->nameQuote( '#__community_groups_members') . ' '
				. 'WHERE ' . $db->nameQuote( 'groupid' ) . '=' . $db->Quote( $this->id ) . ' '
				. 'AND ' . $db->nameQuote( 'memberid' ) . '=' . $db->Quote( $userid )
				. 'AND ' . $db->nameQuote( 'approved' ) . '=' . $db->Quote( '1' );
		$db->setQuery( $query );

		$status	= ( $db->loadResult() > 0 ) ? true : false;

		return $status;
	}

	function isAdmin( $userid )
	{
		if($this->id ==0)
			return false;
		
		if($userid == 0)
			return false;
		
		// the creator is also the admin
		if($userid == $this->ownerid)
			return true;

		$db		=& $this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' 
				. $db->nameQuote( '#__community_groups_members') . ' '
				. 'WHERE ' . $db->nameQuote( 'groupid' ) . '=' . $db->Quote( $this->id ) . ' '
				. 'AND ' . $db->nameQuote( 'memberid' ) . '=' . $db->Quote( $userid )
				. 'AND ' . $db->nameQuote( 'approved' ) . '=' . $db->Quote( '1' )
				. 'AND ' . $db->nameQuote( 'permissions' ) . '=' . $db->Quote( COMMUNITY_GROUP_ADMIN );
		$db->setQuery( $query );

		$status	= ( $db->loadResult() > 0 ) ? true : false;

		return $status;
	}
	
	function getLink( $xhtml = false )
	{
		$link	= CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $this->id , $xhtml );
		return $link;
	}
}