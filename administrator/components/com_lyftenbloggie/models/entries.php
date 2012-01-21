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
class LyftenBloggieModelEntries extends JModel
{
	var $_pagination = null;
	var $_id = null;

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

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);

	}

	/**
	 * Method to set the identifier
	 **/
	function setId($id)
	{
		// Set id and wipe data
		$this->_id	 = $id;
		$this->_data = null;
	}

	/**
	 * Method to get data
	 **/
	function getData()
	{
		// Lets load the files if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
			
		return $this->_data;
	}

	/**
	 * Method to get the total
	 **/
	function getTotal()
	{
		// Lets load the files if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}
	
	/**
	 * Method to get a pagination object
	 **/
	function getPagination()
	{
		// Lets load the files if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query
	 **/
	function _buildQuery()
	{
		// Get the WHERE, and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT c.*, u.name AS editor, g.name AS groupname, u.name AS author, cat.title AS category'
					. ' FROM #__bloggies_entries AS c'
					. ' LEFT JOIN #__bloggies_categories AS cat ON cat.id = c.catid'
					. ' LEFT JOIN #__core_acl_aro_groups AS g ON g.id = c.access'
					. ' LEFT JOIN #__users AS u ON u.id = c.created_by'
					. $where
					. $orderby
					;
		return $query;
	}

	/**
	 * Method to build the orderby clause of the query
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.entries.filter_order', 	'filter_order', 	'c.created', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.entries.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		//Some stupid error this fixes it
		if($filter_order == 'c.created') $filter_order = 'c.created DESC';

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	/**
	 * Method to build the where clause of the query
	 **/
	function _buildContentWhere()
	{
		global $mainframe, $option;

		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'entries.filter_state', 		'filter_state', '', 'cmd' );
		$filter_type 		= $mainframe->getUserStateFromRequest( $option.'entries.filter_type', 		'filter_type', 	'', 'cmd' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'entries.search', 			'search', 		'', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );
		$filter_cat 		= $mainframe->getUserStateFromRequest( $option.'.entries.filter_cat', 		'filter_cat', 	'-1', 'cmd' );
	
		$where = array();
		
		if ( $filter_cat != '-1' ) {
			$where[] = 'c.catid = '.$filter_cat;
		}

		if ( $filter_state ) {
			$where[] = 'c.state = '.$filter_state;
		}
		
		if ($search) {
			if (!$filter_type) {
				$where[] = ' LOWER(u.name) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false ).
					' OR LOWER(c.title) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false ).
					' OR LOWER(c.introtext) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false ).
					' OR LOWER(c.fulltext) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
			}
			if ($filter_type == 1) {
				$where[] = ' LOWER(c.title) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
			}
			if ($filter_type == 2) {
				$where[] = ' LOWER(c.introtext) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false ).
					' OR LOWER(c.fulltext) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
			}
			if ($filter_type == 3) {
				$where[] = ' LOWER(u.name) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
			}
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}
	
	/**
	 * Method to (un)publish
	 **/
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__bloggies_entries'
				. ' SET state = ' . (int) $publish
				. ' WHERE id IN ('. $cids .')'
				. ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id'). ' ) )'
			;
			$this->_db->setQuery( $query );
		
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return $cid;
	}
	
	/**
	 * Method to order
	 **/
	function saveorder($cid = array(), $order)
	{
		$row =& JTable::getInstance('entries', 'Table');
		
		$groupings = array();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			
			// track entries
			$groupings[] = $row->catid;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('catid = '.$group);
		}

		return true;
	}

	/**
	 * Method to remove
	 **/
	function delete($cids)
	{		
		$cids = implode( ',', $cids );

		$query = 'DELETE FROM #__bloggies_entries'
				. ' WHERE id IN ('. $cids .')';

		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$query = 'DELETE FROM #__bloggies_relations'
				.' WHERE entry IN ('. $cids .')';
		$this->_db->setQuery($query);
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$total 	= count( $cid );
		$msg 	= $total.' '.JText::_('ENTRIES DELETED');
		return $msg;
	}
	
	/**
	 * Method to set the access level
	 **/
	function access($id, $access)
	{				
		$entries  =& $this->getTable('entries', 'Table');
		
		//handle childs
		$cids = array();
		$cids[] = $id;
		
		foreach ($cids as $cid) {
			
			$entries->load( (int)$cid );
			
			if ($entries->access < $access) {				
				$entries->access = $access;
			} else {
				$entries->load( $id );
				$entries->access = $access;
			}
			
			if ( !$entries->check() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			if ( !$entries->store() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
		}
		return true;
	}

	/**
	 * Method to get all Categories
	 **/
	function getCategories()
	{
		global $mainframe, $option;
		
		$category = array();

		$filter_cat = $mainframe->getUserStateFromRequest( $option.'.entries.filter_cat', 'filter_cat', '', 'cmd' );

		$query = 'SELECT id, title' .
				' FROM #__bloggies_categories' .
				' ORDER BY ordering';
		$this->_db->setQuery($query);

		$category[] 	= JHTML::_('select.option', '-1', ' - '.JText::_('SELECT CATEGORY').' - ', 'id', 'title');
		$categories 	= array_merge($category, $this->_db->loadObjectList());
		$categories[] 	= JHTML::_('select.option', '0', JText::_('UNCATEGORISED'), 'id', 'title');
		$return			= JHTML::_('select.genericlist',  $categories, 'filter_cat', 'class="inputbox" size="1" onchange="submitform( );"', 'id', 'title', $filter_cat);	
		return $return;
	}
}
?>