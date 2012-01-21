<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelTransactions extends JModel
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
		$limit = $mainframe->getUserStateFromRequest($option.'.transactions.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.transactions.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.transactions.limit', $limit);
		$this->setState($option.'.transactions.limitstart', $limitstart);
	}
	
	function _buildQuery()
	{
		global $mainframe;

		$query = "SELECT t.*, IFNULL(u.email, t.user_email) AS email FROM #__rsmembership_transactions t LEFT JOIN #__users u ON (`t`.`user_id`=`u`.`id`)";
		
		$filter_word = JRequest::getString('search', '');
		if (!empty($filter_word))
			$query .= " WHERE u.email LIKE '%".$filter_word."%'";
		
		$sortColumn = JRequest::getVar('filter_order', 'date');
		$sortColumn = $this->_db->getEscaped($sortColumn);
		
		$sortOrder = JRequest::getVar('filter_order_Dir', 'DESC');
		$sortOrder = $this->_db->getEscaped($sortOrder);
		
		$query .= " ORDER BY ".$sortColumn." ".$sortOrder;
		
		return $query;
	}
	
	function getTransactions()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.transactions.limitstart'), $this->getState($option.'.transactions.limit'));
		
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
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.transactions.limitstart'), $this->getState($option.'.transactions.limit'));
		}
		
		return $this->_pagination;
	}
	
	function remove($cids)
	{
		$cids = implode(',', $cids);

		$query = "DELETE FROM #__rsmembership_transactions WHERE `id` IN (".$cids.")";
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return true;
	}
	
	function getCache()
	{
		return RSMembershipHelper::getCache();
	}
}
?>