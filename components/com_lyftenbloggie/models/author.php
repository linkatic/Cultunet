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

class LyftenBloggieModelAuthor extends JModel
{
	var $_id 		= null;
	var $_author 	= null;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		global $mainframe;

		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = intval( JArrayHelper::getValue( $_REQUEST, "limitstart", "0" ) );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		// set the limits
		$this->setState('limit', 		$limit);
		$this->setState('limitstart', 	$limitstart);	

		//set the identifier
		$user				=& JFactory::getUser();
		$this->_id 			= $user->get('id');
	}

	/**
	 * Overridden get method to get properties from the entry
	 **/
	function get($property, $default=null)
	{
		if ($this->_loadEntry()) {
			if(isset($this->_entries->$property)) {
				return $this->_entries->$property;
			}
		}
		return $default;
	}

	/**
	 * Method to get data
	 **/
	function getMyEntries()
	{
		
		// Lets load the files if it doesn't already exist
		if (empty($this->_entries))
		{
			$query = $this->_buildEntriesQuery();
			$this->_entries = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_entries;
	}
	
	/**
	 * Method to get the total
	 **/
	function getTotal()
	{
		// Lets load the files if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildEntriesQuery();
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
	 * Method to load required data
	 **/
	function _buildEntriesQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();

		$query = 'SELECT e.id, e.title, e.hits, e.state, e.catid, e.created, e.created_by, e.attribs, e.metadesc, e.metakey, e.metadata, e.metadata, e.access, e.image, e.modified_by, '
			. ' c.title as cattitle,'
			. ' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(":", e.id, e.alias) ELSE e.id END as slug,'
			. ' CASE WHEN CHAR_LENGTH(c.slug) THEN c.slug ELSE 0 END as catslug'
			. ' FROM #__bloggies_entries AS e'
			. ' LEFT JOIN #__bloggies_categories AS c ON c.id = e.catid'
			. $where
			. ' ORDER BY e.created DESC'
			;
		return $query;
	}

	/**
	 * Method to build the where clause of the query for the comments
	 **/
	function _buildContentWhere()
	{
		$layout 	= JRequest::getVar('layout');
		$where 	= array();

		if ( $layout == 'pending' )
		{
			$where[] = 'e.state > 1';
		}else{
			$where[] = 'e.created_by = \''.(int)$this->_id.'\'';
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}
}
?>