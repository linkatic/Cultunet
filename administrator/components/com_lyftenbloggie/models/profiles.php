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
class LyftenBloggieModelProfiles extends JModel
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

		$settings 		= & BloggieSettings::getInstance();
		$limit			= $mainframe->getUserStateFromRequest( $option.'.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart 	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		$this->setState('limit', 		$limit);
		$this->setState('limitstart', 	$limitstart);
			
		$id = JRequest::getInt('id',  0);
		$this->_id = (int)$id;
	}

	/**
	 * Method to set the Tag identifier
	 **/
	function setId($id)
	{
		// Set id and wipe data
		$this->_id	 = $id;
		$this->_data = array();
	}

	/**
	 * Overridden get method to get properties from the profile
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
	 * Method to get profiles data
	 **/
	function getData()
	{
		if($this->_id > 0 || strtolower(JRequest::getVar('task')) == 'add')
		{
			if(!$this->_loadProfile())
			{
				$this->_data =& JTable::getInstance('profiles', 'Table');
				$name = '';
				if($this->_id)
				{
					$user = & JFactory::getUser($this->_id); 
					$name = $user->get('name');
				}
				$this->_data->name = $name;
			}
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
	 * Method to get the total nr of the profiles
	 **/
	function getTotal()
	{
		// Lets load the profiles if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the profiles
	 **/
	function getPagination()
	{
		// Lets load the profiles if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query for the profiles
	 **/
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT u.*, COUNT(p.created_by) AS npost, a.user_id, g.name AS type'
					. ' FROM #__users AS u'
					. ' LEFT JOIN #__bloggies_entries AS p ON p.created_by = u.id'		
					. ' LEFT JOIN #__bloggies_authors AS a ON a.user_id = u.id'
					. ' LEFT JOIN #__core_acl_aro_groups AS g ON g.id = u.gid'	
					. $where
					. ' GROUP BY u.id'
					. $orderby
					;
		return $query;
	}

	/**
	 * Method to load profile data
	 **/
	function _loadProfile()
	{
		// Lets load the profile if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT a.*, u.name'
					. ' FROM #__bloggies_authors as a'
					. ' LEFT JOIN #__users AS u ON u.id = a.user_id'	
					. ' WHERE a.user_id = '.$this->_id
					;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			if(!isset($this->_data->id) || !$this->_data->id)
			{
				$this->_data  =& JTable::getInstance('profiles', 'Table');
				$this->_data->user_id = $this->_id;
			}

			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to build the orderby clause of the query for the profiles
	 **/
	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.profiles.filter_order', 		'filter_order', 	'u.name', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.profiles.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;

		return $orderby;
	}

	/**
	 * Method to build the where clause of the query for the profiles
	 **/
	function _buildContentWhere()
	{
		global $mainframe, $option;

		$filter_type 		= $mainframe->getUserStateFromRequest( $option.'.profiles.filter_type', 'filter_type', '', 'string' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.profiles.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );

		$where = array();

		if ( $filter_type ) {
			$where[] = 'u.gid = \''.$filter_type.'\'';
		}

		if ($search) {
			$where[] = 'LOWER(u.name) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}
	
	/**
	 * Method to remove
	 **/
	function reset($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__bloggies_authors'
					. ' WHERE user_id IN ('. $cids .')'
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
	 * Method to store the profile
	 **/
	function store($data)
	{
		$profile  =& JTable::getInstance('profiles', 'Table');

		// bind it to the table
		if (!$profile->bind($data)) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}

		// Get parameter variables from the request
		$attribs = JRequest::getVar( 'params', null, 'post', 'array' );		
	
		// Build parameter INI string
		if (is_array($attribs))
		{
			$txt = array ();
			foreach ($attribs as $k => $v) {
				$txt[] = "$k=$v";
			}
			$profile->attribs = implode("\n", $txt);
			unset($txt);
		}

		// Make sure the data is valid
		if (!$profile->check()) {
			$this->setError($profile->getError());
			return false;
		}

		// Store it in the db
		if (!$profile->store()) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}
		
		$this->_data	=& $profile;
		
		return true;
	}
}
?>