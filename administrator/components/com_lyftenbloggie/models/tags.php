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
class LyftenBloggieModelTags extends JModel
{

	var $_data = null;
	var $_total = null;
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

		$id = JRequest::getInt('id',  0);
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
	 * Overridden get method to get properties from the tag
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
	 * Method to get tags data
	 **/
	function getData()
	{
		if($this->_id > 0 || strtolower(JRequest::getVar('task')) == 'add')
		{
			$this->_data =& JTable::getInstance('tags', 'Table'); 
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
	 * Method to get the total nr of the tags
	 **/
	function getTotal()
	{
		// Lets load the tags if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the tags
	 **/
	function getPagination()
	{
		// Lets load the tags if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query for the tags
	 **/
	function _buildQuery()
	{
		// Get the WHERE, HAVING and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();
		$having		= $this->_buildContentHaving();

		$query = 'SELECT t.*, COUNT(rel.id) AS nrassigned'
					. ' FROM #__bloggies_tags AS t'
					. ' LEFT JOIN #__bloggies_relations AS rel ON rel.tag = t.id'
					. $where
					. ' GROUP BY t.id'
					. $having
					. $orderby
					;
				
		return $query;
	}

	/**
	 * Method to build the orderby clause of the query for the tags
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.tags.filter_order', 		'filter_order', 	't.name', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.tags.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	/**
	 * Method to build the where clause of the query for the tags
	 **/
	function _buildContentWhere()
	{
		global $mainframe, $option;

		$search 			= $mainframe->getUserStateFromRequest( $option.'.tags.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = '';

		if ($search) {
			$where = ' WHERE LOWER(t.name) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
		}

		return $where;
	}
	
	/**
	 * Method to build the having clause of the query for the files
	 **/
	function _buildContentHaving()
	{
		global $mainframe, $option;
		
		$filter_assigned	= $mainframe->getUserStateFromRequest( $option.'.tags.filter_assigned', 'filter_assigned', '', 'word' );
		
		$having = '';
		
		if ( $filter_assigned ) {
			if ( $filter_assigned == 'O' ) {
				$having = ' HAVING COUNT(rel.id) = 0';
			} else if ($filter_assigned == 'A' ) {
				$having = ' HAVING COUNT(rel.id) > 0';
			}
		}
		
		return $having;
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

			$query = 'UPDATE #__bloggies_tags'
				. ' SET published = ' . (int) $publish
				. ' WHERE id IN ('. $cids .')';
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
			$query = 'DELETE FROM #__bloggies_tags'
					. ' WHERE id IN ('. $cids .')'
					;

			$this->_db->setQuery( $query );

			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			$query = 'DELETE FROM #__bloggies_relations'
					. ' WHERE tag IN ('. $cids .')'
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
	 * Method to store the tag
	 **/
	function store($data)
	{
		$tag  =& JTable::getInstance('tags', 'Table'); 

		// bind it to the table
		if (!$tag->bind($data)) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		// Make sure the data is valid
		if (!$tag->check()) {
			$this->setError($tag->getError() );
			return false;
		}

		// Store it in the db
		if (!$tag->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		$this->_data =& $tag;

		return true;
	}
}
?>