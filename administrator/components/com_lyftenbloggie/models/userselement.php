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
 * @since 1.0.4
 */
class LyftenBloggieModelUsersElement extends JModel
{
	var $_data 			= array();
	var $_total 		= 0;
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
	}

	/**
	 * Method to get authors data
	 **/
	function getData()
	{
		// Lets load the authors if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		//Ensure the user is not an author already
		for( $i=0; $i < count($data); $i++ )
		{
			$query = 'SELECT a.id'
					. ' FROM #__bloggies_authors AS a'		
					. ' WHERE a.user_id = \''.$data[$i]->id.'\''
					;
			$this->_db->setQuery($query);
			if(!$this->_db->loadObject())
			{
				$this->_data[] = $data[$i];
			}
		}

		return $this->_data;
	}

	/**
	 * Method to get the total nr of the authors
	 **/
	function getTotal()
	{
		// Lets load the authors if it doesn't already exist
		if (empty($this->_total))
		{
			$users = array();
			$query = $this->_buildQuery();
			
			//Get Users
			$this->_db->setQuery($query);
			$data = $this->_db->loadObjectList();

			//Ensure the user is not an author already
			for( $i=0; $i < count($data); $i++ )
			{
				$query = 'SELECT a.id'
						. ' FROM #__bloggies_authors AS a'		
						. ' WHERE a.user_id = \''.$data[$i]->id.'\''
						;
				$this->_db->setQuery($query);
				if(!$this->_db->loadObject())
				{
					$users[] = $data[$i];
				}
			}
			$this->_total = count($users);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the authors
	 **/
	function getPagination()
	{
		// Lets load the authors if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query for the authors
	 **/
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT u.*'
					. ' FROM #__users AS u'		
					. $where
					. ' GROUP BY u.id'
					. $orderby
					;
		return $query;
	}

	/**
	 * Method to build the orderby clause of the query for the authors
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.userselement.filter_order', 		'filter_order', 	'u.name', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.userselement.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	/**
	 * Method to build the where clause of the query for the authors
	 **/
	function _buildContentWhere()
	{
		global $mainframe, $option;

		$search 			= $mainframe->getUserStateFromRequest( $option.'.userselement.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = array();
		
		if ($search) {
			$where[] = 'LOWER(u.username) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}
}
?>