<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelAllusers extends JModel
{
	var $_data = null;
	var $_total = 0;
	var $_query = '';
	var $_pagination = null;
	var $_db = null;
	
	var $_id = 0;
	
	function __construct()
	{
		parent::__construct();
		$this->_db = JFactory::getDBO();
		$this->_query = $this->_buildQuery();
		
		global $mainframe, $option;
		
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest($option.'.allusers.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.allusers.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.allusers.limit', $limit);
		$this->setState($option.'.allusers.limitstart', $limitstart);
	}
	
	function _buildQuery()
	{
		global $mainframe;
		
		$query = "SELECT * FROM #__users";
		
		$filter_word = JRequest::getString('search', '');
		if (!empty($filter_word))
			$query .= " WHERE `name` LIKE '%".$filter_word."%' OR `username` LIKE '%".$filter_word."%' OR `email` LIKE '%".$filter_word."%'";
		
		$sortColumn = JRequest::getVar('filter_order', 'name');
		$sortColumn = $this->_db->getEscaped($sortColumn);
		
		$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
		$sortOrder = $this->_db->getEscaped($sortOrder);
		
		$query .= " ORDER BY ".$sortColumn." ".$sortOrder;
		
		return $query;
	}
	
	function getUsers()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.allusers.limitstart'), $this->getState($option.'.allusers.limit'));
		
		return $this->_data;
	}
	
	function getTotal()
	{
		if (empty($this->_total))
			$this->_total = $this->_getListCount($this->_query); 
		
		return $this->_total;
	}
	
	function getPagination()
	{
		if (empty($this->_pagination))
		{
			global $option;
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.allusers.limitstart'), $this->getState($option.'.allusers.limit'));
		}
		
		return $this->_pagination;
	}
}
?>