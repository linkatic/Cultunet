<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelUpgrades extends JModel
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
		$limit = $mainframe->getUserStateFromRequest($option.'.upgrades.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.upgrades.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.upgrades.limit', $limit);
		$this->setState($option.'.upgrades.limitstart', $limitstart);
	}
	
	function _buildQuery()
	{
		global $mainframe;
		
		$query = "SELECT u.*, mfrom.name as fromname, mto.name as toname FROM #__rsmembership_membership_upgrades u LEFT JOIN #__rsmembership_memberships mfrom ON (mfrom.id = u.membership_from_id) LEFT JOIN #__rsmembership_memberships mto ON (mto.id = u.membership_to_id) ";

		$filter_state = $mainframe->getUserStateFromRequest('rsmembership_filter_state', 'filter_state');
		if ($filter_state != '')
			$query .= " WHERE u.published='".($filter_state == 'U' ? '0' : '1')."'";
		
		$sortColumn = JRequest::getVar('filter_order', 'id');
		$sortColumn = $this->_db->getEscaped($sortColumn);
		
		$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
		$sortOrder = $this->_db->getEscaped($sortOrder);
		
		$query .= " ORDER BY `".$sortColumn."` ".$sortOrder;
		
		return $query;
	}
	
	function getUpgrades()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.upgrades.limitstart'), $this->getState($option.'.upgrades.limit'));
		
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
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.upgrades.limitstart'), $this->getState($option.'.upgrades.limit'));
		}
		
		return $this->_pagination;
	}
	
	function getUpgrade()
	{
		$cid = JRequest::getVar('cid', 0);
		if (is_array($cid))
			$cid = $cid[0];
		$cid = (int) $cid;
		
		$row =& JTable::getInstance('RSMembership_Membership_Upgrades','Table');
		$row->load($cid);
		
		return $row;
	}
	
	function getMemberships()
	{
		return $this->_getList("SELECT c.name AS category_name, m.* FROM #__rsmembership_memberships m LEFT JOIN #__rsmembership_categories c ON (c.id=m.category_id) WHERE m.`published`='1' ORDER BY c. ordering, m.ordering");
	}
	
	function publish($cid=array(), $publish=1)
	{
		if (!is_array($cid) || count($cid) > 0)
		{
			$publish = (int) $publish;
			$cids = implode(',', $cid);

			$query = "UPDATE #__rsmembership_membership_upgrades SET `published`='".$publish."' WHERE `id` IN (".$cids.")"	;
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

		$query = "DELETE FROM #__rsmembership_membership_upgrades WHERE `id` IN (".$cids.")";
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return true;
	}
	
	function save()
	{
		$row =& JTable::getInstance('RSMembership_Membership_Upgrades','Table');
		$post = JRequest::get('post');
		
		$post['membership_from_id'] = (int) $post['membership_from_id'];
		$post['membership_to_id'] = (int) $post['membership_to_id'];
		
		if (!$row->bind($post))
			return JError::raiseWarning(500, $row->getError());
			
		$this->_db->setQuery("SELECT `id` FROM #__rsmembership_membership_upgrades WHERE `membership_from_id`='".$post['membership_from_id']."' AND `membership_to_id`='".$post['membership_to_id']."'".($row->id ? " AND `id`!='".(int) $row->id."'" : ""));
		$this->_db->query();
		$exists = $this->_db->getNumRows();
		if ($exists)
		{
			JError::raiseWarning(500, JText::_('RSM_UPGRADE_ALREADY_EXIST'));
			return false;
		}
		
		$same = ($post['membership_from_id'] == $post['membership_to_id']);
		if ($same)
		{
			JError::raiseWarning(500, JText::_('RSM_UPGRADE_SAME_ERROR'));
			return false;
		}
		
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