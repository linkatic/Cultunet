<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieModelCategories extends JModel
{

	var $_data 			= null;
	var $_total 		= null;
	var $_pagination 	= null;
	var $_id 			= null;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		$limit		= $mainframe->getUserStateFromRequest( $option.'.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$id 	= JRequest::getInt('id',  0);
		$this->setId((int)$id);
	}

	/**
	 * Method to set the category identifier
	 **/
	function setId($id)
	{
		// Set id and wipe data
		$this->_id	 = $id;
		$this->_data = null;
	}

	/**
	 * Overridden get method to get properties from the author
	 **/
	function get($property, $default=null)
	{
		if ($this->getData()) {
			if(isset($this->_data->$property)) {
				return $this->_data->$property;
			}
		}
		return $default;
	}

	/**
	 * Method to get categories data
	 **/
	function getData()
	{
		if($this->_id > 0 || strtolower(JRequest::getVar('task')) == 'add')
		{
			$this->_data =& JTable::getInstance('categories', 'Table'); 
			$this->_data->load($this->_id); 
		} else {
			if(empty($this->_data))
			{
				$query = $this->_buildQuery();
				$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			}
		}

		return $this->_data;
	}

	/**
	 * Method to get the total nr of the categories
	 **/
	function getTotal()
	{
		// Lets load the categories if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the categories
	 **/
	function getPagination()
	{
		// Lets load the categories if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query for the categories
	 **/
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT c.*, u.name AS editor, COUNT(rel.catid) AS nrassigned'
					. ' FROM #__bloggies_categories AS c'
					. ' LEFT JOIN #__bloggies_entries AS rel ON rel.catid = c.id'
					. ' LEFT JOIN #__users AS u ON u.id = c.checked_out'				
					. $where
					. ' GROUP BY c.id'
					. $orderby
					;
				
		return $query;
	}

	/**
	 * Method to build the orderby clause of the query for the categories
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.categories.filter_order', 		'filter_order', 	'c.title', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.categories.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	/**
	 * Method to build the where clause of the query for the categories
	 **/
	function _buildContentWhere()
	{
		global $mainframe, $option;
		
		$filter_state 		= $mainframe->getUserStateFromRequest( 'com_lyftenbloggie.categories.filter_state', 'filter_state', '', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.categories.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = array();
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'c.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'c.published = 0';
			}
		}
		
		if ($search) {
			$where[] = 'LOWER(c.title) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}
	
	/**
	 * Method to (un)publish a category
	 **/
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__bloggies_categories'
				. ' SET published = ' . (int) $publish
				. ' WHERE id IN ('. $cids .')'
				. ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id'). ' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	/**
	 * Method to remove a category
	 **/
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__bloggies_categories'
					. ' WHERE id IN ('. $cids .')'
					;

			$this->_db->setQuery( $query );

			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	
	/**
	 * Method to (un)default
	 **/
	function setDefault($cid)
	{
		$query = 'UPDATE #__bloggies_categories AS c'
			. ' SET c.default = 0'
			. ' WHERE c.default = 1'
			;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$query = 'UPDATE #__bloggies_categories AS c'
			. ' SET c.default = 1'
			. ' WHERE c.id = '. $cid
			;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	/**
	 * Method to ensure there is a default cat
	 **/
	function ensureDefault()
	{
		$query = 'SELECT id' .
				' FROM #__bloggies_categories' .
				' WHERE `default` = 1';
		$this->_db->setQuery($query);
		if(!$this->_db->loadResult())
		{
			$query = 'SELECT id' .
					' FROM #__bloggies_categories' .
					' LIMIT 1';
			$this->_db->setQuery($query);
			$firstCat = $this->_db->loadResult();
			if($firstCat) {
				$query = 'UPDATE #__bloggies_categories AS c'
					. ' SET c.default = 1'
					. ' WHERE c.id = '.$firstCat
					;
				$this->_db->setQuery( $query );
				if (!$this->_db->query()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Method to checkin/unlock the category
	 **/
	function checkin()
	{
		if ($this->_id)
		{
			$category = & JTable::getInstance('categories', 'Table');
			return $category->checkin($this->_id);
		}
		return false;
	}

	/**
	 * Method to checkout/lock the category
	 **/
	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the category with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$category = & JTable::getInstance('categories', 'Table');
			if(!$category->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}

	/**
	 * Tests if the category is checked out
	 **/
	function isCheckedOut( $uid=0 )
	{
		if ($this->getData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		} elseif ($this->_id < 1) {
			return false;
		} else {
			JError::raiseWarning( 0, 'UNABLE LOAD DATA');
			return false;
		}
	}

	/**
	 * Method to store the category
	 **/
	function store($data)
	{
		$category  =& $this->getTable('categories', 'Table');

		// bind it to the table
		if (!$category->bind($data)) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}

		if (!$category->id) {
			$category->ordering = $category->getNextOrder();
		}
		
		// Make sure the data is valid
		if (!$category->check()) {
			$this->setError($category->getError());
			return false;
		}

		//Remove old default
		if($category->default) {
			$query = 'UPDATE #__bloggies_categories AS c'
				. ' SET c.default = 0'
				. ' WHERE c.default = 1'
				;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		
		// Store it in the db
		if (!$category->store()) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}

		$this->_category =& $category;
		
		return $this->_category->id;
	}
}
?>