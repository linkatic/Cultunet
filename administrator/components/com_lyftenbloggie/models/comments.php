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
class LyftenBloggieModelComments extends JModel
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
	 * Overridden get method to get properties from the comment
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
	 * Method to get comments data
	 **/
	function getData()
	{
		if($this->_id > 0 || strtolower(JRequest::getVar('task')) == 'add')
		{
			$this->_loadComment(); 
		} else {
			if(empty($this->_data))
			{
				$query 			= $this->_buildQuery();
				$this->_data 	= $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
				$this->_total 	= $this->_getListCount($query);
			}
		}

		return $this->_data;
	}

	/**
	 * Method to load comment data
	 **/
	function _loadComment()
	{
		// Lets load the comment if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT c.*, COUNT(r.comment_id) AS reports, e.title AS entryname,'
					. ' CASE WHEN CHAR_LENGTH(c.author) THEN c.author ELSE u.name END as commenter'
					. ' FROM #__bloggies_comments AS c'
					. ' LEFT JOIN #__bloggies_entries AS e ON e.id = c.entry_id'
					. ' LEFT JOIN #__bloggies_reports AS r ON r.comment_id = c.id'					
					. ' LEFT JOIN #__users AS u ON u.id = c.user_id'					
					. ' WHERE c.id = '.$this->_id
					;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to get comments data
	 **/
	function getReports()
	{
		// Lets load the comments if it doesn't already exist
		$query 			= $this->_buildReportsQuery();
		$reports 		= $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		$this->_total 	= $this->_getListCount($query);

		return $reports;
	}
	
	/**
	 * Method to get a pagination object for the comments
	 **/
	function getPagination()
	{
		// Lets load the comments if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->_total, $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query for the comments
	 **/
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();
		$having		= $this->_buildContentHaving();
		
		$query = 'SELECT c.*, e.title AS entryname,'
					. ' CASE WHEN CHAR_LENGTH(c.author) THEN c.author ELSE u.name END as commenter'
					. ' FROM #__bloggies_comments AS c'
					. ' LEFT JOIN #__users AS u ON u.id = c.user_id'				
					. ' LEFT JOIN #__bloggies_entries AS e ON e.id = c.entry_id'
					. $where
					. $having
					. $orderby
					;

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObjectList();

		return $query;
	}

	/**
	 * Method to build the orderby clause of the query for the comments
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe, $option;
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.comments.filter_order', 		'filter_order', 	'c.id', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.comments.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		
		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	/**
	 * Method to build the where clause of the query for the comments
	 **/
	function _buildContentWhere()
	{
		global $mainframe, $option;
		
		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.comments.filter_state', 		'filter_state', '', 'cmd' );
		$filter_type 		= $mainframe->getUserStateFromRequest( $option.'.comments.filter_type', 		'filter_type', 	'', 'cmd' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.comments.search', 				'search', 		'', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = array();
		if ( $filter_state ) {
			if($filter_state == 2) {
				//$where[] = 'HAVING COUNT(r.comment_id) > 0';
			}else{
				$where[] = 'c.state = '.$filter_state;
			}
		}

		if ( $filter_type ) {
				$where[] = 'c.type = '.$filter_type;
		}
		
		if ($search) {
			$where[] = 'LOWER(c.author) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}

	/**
	 * Method to build the having clause of the query
	 **/
	function _buildContentHaving()
	{
		global $mainframe, $option;
		
		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.comments.filter_state', 		'filter_state', '', 'cmd' );
		
		$having = '';
		
		if($filter_state == 2) {
			$having = ' HAVING COUNT(r.comment_id) > 0';
		}
		
		return $having;
	}

	/**
	 * Method to build the query for the comments
	 **/
	function _buildReportsQuery()
	{
		$query = 'SELECT r.*, u.name AS reporter'
					. ' FROM #__bloggies_reports AS r'
					. ' LEFT JOIN #__users AS u ON u.id = r.user_id'				
					. ' WHERE comment_id = '.$this->_data->id
					. ' ORDER BY r.date'
					;
		return $query;
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

			$query = 'UPDATE #__bloggies_comments'
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
	 * Method to remove a comment
	 **/
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__bloggies_comments'
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
	 * Method to store the comment
	 **/
	function store($data)
	{
		$comment  =& $this->getTable('comments', 'Table');

		// bind it to the table
		if (!$comment->bind($data)) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}

		$comment->author_url 	= $this->_db->getEscaped($comment->author_url, true );
		
		if (!$comment->id) {
			$comment->ordering = $comment->getNextOrder();
		}
		
		// Make sure the data is valid
		if (!$comment->check()) {
			$this->setError($comment->getError());
			return false;
		}

		// Store it in the db
		if (!$comment->store()) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}
		
		$this->_data	=& $comment;
		
		return true;
	}

	/**
	 * Method to remove a report
	 **/
	function delreport($cid = array())
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__bloggies_reports'
					. ' WHERE id IN ('. $cids .')'
					;

			$this->_db->setQuery( $query );

			if(!$this->_db->query()) {
				return $this->_db->getErrorMsg();
			}
		}

		return JText::_('Report_Deleted');
	}
}
?>