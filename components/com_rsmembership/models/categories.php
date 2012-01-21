<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelCategories extends JModel
{
	var $_data = null;
	var $_total = 0;
	var $_query = '';
	var $_pagination = null;
	var $_db = null;
	
	function __construct()
	{
		parent::__construct();
		
		global $mainframe, $option;
		
		// Get pagination request variables
		$limit		= JRequest::getVar('limit', $mainframe->getCfg('list_limit'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.categories.limit', $limit);
		$this->setState($option.'.categories.limitstart', $limitstart);
		
		$params = clone($mainframe->getParams('com_rsmembership'));
		switch ($params->get('orderby'))
		{
			case 'title': $sortColumn = 'c.name'; $sortOrder = 'ASC'; break;
			case 'rtitle': $sortColumn = 'c.name'; $sortOrder = 'DESC'; break;
			default: case 'order': $sortColumn = 'c.ordering'; $sortOrder = 'ASC'; break;
		}
		
		$filter_order = $mainframe->getUserStateFromRequest($option.'.categories.filter_order', 'filter_order', $sortColumn);
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.'.categories.filter_order_Dir', 'filter_order_Dir', $sortOrder);
		
		$this->setState($option.'.categories.filter_order', $filter_order);
		$this->setState($option.'.categories.filter_order_Dir', $filter_order_Dir);
		
		$this->_query = $this->_buildQuery();
	}
	
	function _buildQuery()
	{
		global $mainframe, $option;
		
		$query = "SELECT c.*, COUNT(m.category_id) AS memberships FROM #__rsmembership_categories c LEFT JOIN #__rsmembership_memberships m ON (m.category_id=c.id) WHERE c.`published`='1' GROUP BY c.id";
		
		$sortColumn = $this->_db->getEscaped($this->getSortColumn());
		$sortOrder = $this->_db->getEscaped($this->getSortOrder());
		
		$query .= " ORDER BY ".$sortColumn." ".$sortOrder;
		
		return $query;
	}
	
	function getCategories()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.categories.limitstart'), $this->getState($option.'.categories.limit'));
		
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
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.categories.limitstart'), $this->getState($option.'.categories.limit'));
		}
		
		return $this->_pagination;
	}
	
	function getSortColumn()
	{
		global $option;
		return $this->getState($option.'.categories.filter_order');
	}
	
	function getSortOrder()
	{
		global $option;
		return $this->getState($option.'.categories.filter_order_Dir');
	}
}
?>