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
class LyftenBloggieModelBookmarks extends JModel
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

		$id = JRequest::getVar('id',  0);
		$this->setId((int)$id);

	}

	/**
	 * Method to set the Tag identifier
	 **/
	function setId($id)
	{
		// Set id and wipe data
		$this->_id	 = $id;
		$this->_data = null;
	}

	/**
	 * Overridden get method to get properties from the bookmark
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
	 * Method to get bookmarks data
	 **/
	function getData()
	{
		if($this->_id > 0 || strtolower(JRequest::getVar('task')) == 'add')
		{
			$this->_data =& JTable::getInstance('Bookmarks', 'Table'); 
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
	 * Method to get the total nr of the bookmarks
	 **/
	function getTotal()
	{
		// Lets load the bookmarks if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the bookmarks
	 **/
	function getPagination()
	{
		// Lets load the bookmarks if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query for the bookmarks
	 **/
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT *'
					. ' FROM #__bloggies_bookmarks'
					. $where
					. ' GROUP BY id'
					. $orderby
					;
		return $query;
	}

	/**
	 * Method to build the orderby clause of the query for the bookmarks
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.bookmarks.filter_order', 		'filter_order', 	'website', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.bookmarks.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	/**
	 * Method to build the where clause of the query for the bookmarks
	 **/
	function _buildContentWhere()
	{
		global $mainframe, $option;
		
		$filter_state 		= $mainframe->getUserStateFromRequest( 'com_lyftenbloggie.bookmarks.filter_state', 'filter_state', '', 'word' );
		$filter_type 		= $mainframe->getUserStateFromRequest( 'com_lyftenbloggie.bookmarks.filter_type', 'filter_type', '', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.bookmarks.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = array();
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'published = 0';
			}
		}
		
		if ( $filter_type ) {
			$where[] = 'type = '.$this->_db->Quote( $this->_db->getEscaped( $filter_type, true ), false );
		}
		
		if ($search) {
			$where[] = 'LOWER(website) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}
	
	/**
	 * Method to (un)publish a tag
	 **/
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__bloggies_bookmarks'
				. ' SET published = ' . (int) $publish
				. ' WHERE id IN ('. $cids .')'
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
	 * Method to remove a tag
	 **/
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__bloggies_bookmarks'
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
	 * Method to store the bookmark
	 **/
	function store($data)
	{
		$bookmark  =& JTable::getInstance('bookmarks', 'Table'); 

		// bind it to the table
		if (!$bookmark->bind($data)) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}
		
		// Make sure the data is valid
		if (!$bookmark->check()) {
			$this->setError($bookmark->getError());
			return false;
		}

		// Store it in the db
		if (!$bookmark->store()) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}
		
		$this->_data =& $bookmark;
		
		return true;
	}
}
?>