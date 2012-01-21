<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelExtras extends JModel
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
		$limit = $mainframe->getUserStateFromRequest($option.'.extras.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.extras.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.extras.limit', $limit);
		$this->setState($option.'.extras.limitstart', $limitstart);
	}
	
	function _buildQuery()
	{
		global $mainframe;

		$query = "SELECT * FROM #__rsmembership_extras WHERE 1";

		$filter_word = JRequest::getString('search', '');
		if (!empty($filter_word))
			$query .= " AND `name` LIKE '%".$filter_word."%'";
		
		$filter_state = $mainframe->getUserStateFromRequest('rsmembership_filter_state', 'filter_state');
		if ($filter_state != '')
			$query .= " AND `published`='".($filter_state == 'U' ? '0' : '1')."'";
		
		$sortColumn = JRequest::getVar('filter_order', 'ordering');
		$sortColumn = $this->_db->getEscaped($sortColumn);
		
		$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
		$sortOrder = $this->_db->getEscaped($sortOrder);
		
		$query .= " ORDER BY `".$sortColumn."` ".$sortOrder;
		
		return $query;
	}
	
	function getExtras()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.extras.limitstart'), $this->getState($option.'.extras.limit'));
		
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
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.extras.limitstart'), $this->getState($option.'.extras.limit'));
		}
		
		return $this->_pagination;
	}
	
	function getExtra()
	{
		$cid = JRequest::getVar('cid', 0);
		if (is_array($cid))
			$cid = $cid[0];
		$cid = (int) $cid;
		
		$row =& JTable::getInstance('RSMembership_Extras','Table');
		$row->load($cid);
		
		return $row;
	}
	
	function publish($cid=array(), $publish=1)
	{
		if (!is_array($cid) || count($cid) > 0)
		{
			$publish = (int) $publish;
			$cids = implode(',', $cid);

			$query = "UPDATE #__rsmembership_extras SET `published`='".$publish."' WHERE `id` IN (".$cids.")"	;
			$this->_db->setQuery($query);
			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return $cid;
	}
	
	function remove($cids)
	{
		$cids = implode(',', $cids);

		$this->_db->setQuery("DELETE FROM #__rsmembership_extras WHERE `id` IN (".$cids.")");
		$this->_db->query();
		
		$this->_db->setQuery("SELECT id FROM #__rsmembership_extra_values WHERE `extra_id` IN (".$cids.")");
		$ids = $this->_db->loadResultArray();
		if (!empty($ids))
		{
			$this->_db->setQuery("DELETE FROM #__rsmembership_extra_value_shared WHERE `extra_value_id` IN (".implode(',', $ids).")");
			$this->_db->query();
		}
		
		$this->_db->setQuery("DELETE FROM #__rsmembership_extra_values WHERE `extra_id` IN (".$cids.")");
		$this->_db->query();
		
		$this->_db->setQuery("DELETE FROM #__rsmembership_membership_extras WHERE `extra_id` IN (".$cids.")");
		$this->_db->query();
		
		return true;
	}
	
	function save()
	{
		$row =& JTable::getInstance('RSMembership_Extras','Table');
		$post = JRequest::get('post');
		
		// These elements are not filtered for HTML code
		$post['description'] = JRequest::getVar('description', '', 'post', 'none', JREQUEST_ALLOWRAW);
		
		if (!$row->bind($post))
			return JError::raiseWarning(500, $row->getError());
		
		if (empty($row->id))
			$row->ordering = $row->getNextOrder();
		
		if ($row->store())
		{
			$this->_id = $row->id;
			return true;
		}
		else
		{
			JError::raiseWarning(500, $row->getError());
			return false;
		}
	}
	
	function getId()
	{
		return $this->_id;
	}
}
?>