<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelUsers extends JModel
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
		$limit = $mainframe->getUserStateFromRequest($option.'.users.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.users.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.users.limit', $limit);
		$this->setState($option.'.users.limitstart', $limitstart);
	}
	
	function _buildQuery()
	{
		global $mainframe;
		
		$query = "SELECT DISTINCT(`u`.`id`), `u`.`name`, `u`.`email`, `u`.`block`, `u`.`username` FROM `#__users` `u` LEFT JOIN `#__rsmembership_users` `mu` ON (`u`.`id` = `mu`.`user_id`) LEFT JOIN `#__rsmembership_membership_users` `m` ON (`m`.`user_id`=`u`.`id`) WHERE 1";
		
		$filter_word = JRequest::getString('search', '');
		if (!empty($filter_word))
			$query .= " AND (`u`.`name` LIKE '%".$filter_word."%' OR `u`.`email` LIKE '%".$filter_word."%')";
		
		$filter_membership = JRequest::getVar('membership', null, 'post', 'array');
		JArrayHelper::toInteger($filter_membership, null);
		if (!empty($filter_membership))
			$query .= " AND `m`.`membership_id` IN (".implode(',', $filter_membership).")";
		
		$filter_status = JRequest::getVar('status', null, 'post', 'array');
		JArrayHelper::toInteger($filter_status, null);
		if (!empty($filter_status))
			$query .= " AND `m`.`status` IN (".implode(',', $filter_status).")";
		
		$sortColumn = JRequest::getVar('filter_order', 'u.name');
		$sortColumn = $this->_db->getEscaped($sortColumn);
		
		$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
		$sortOrder = $this->_db->getEscaped($sortOrder);
		
		$query .= " ORDER BY ".$sortColumn." ".$sortOrder;
		
		return $query;
	}
	
	function getMembership()
	{
		$cid = JRequest::getInt('cid', 0);
		
		$row =& JTable::getInstance('RSMembership_Membership_Users','Table');
		$row->load($cid);
		
		$user_id = JRequest::getInt('user_id');
		if ($user_id > 0)
			$row->user_id = $user_id;
		
		$row->user = JFactory::getUser($row->user_id);
		
		if ($row->id == 0)
		{
			$row->membership_start = time();
			$row->membership_end = time();
		}
		
		if (!empty($row->extras))
		{
			$row->extras = explode(',', $row->extras);
			$row->noextra = false;
		}
		else
			$row->noextra = true;
		
		return $row;
	}
	
	function getUsers()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.users.limitstart'), $this->getState($option.'.users.limit'));
		
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
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.users.limitstart'), $this->getState($option.'.users.limit'));
		}
		
		return $this->_pagination;
	}
	
	function getUser()
	{
		$cid = JRequest::getVar('cid', 0);
		if (is_array($cid))
			$cid = $cid[0];
		$cid = (int) $cid;
		
		$user = JFactory::getUser($cid);
		
		$row = new stdClass();
		$row->user_id = $user->get('id');
		$row->username = $user->get('username');
		$row->email = $user->get('email');
		$row->name = $user->get('name');
		
		$row->memberships = $this->_getList("SELECT u.*, m.name FROM #__rsmembership_membership_users u LEFT JOIN #__rsmembership_memberships m ON (u.membership_id = m.id) WHERE `user_id`='".$cid."' ORDER BY `membership_start` DESC");
		$row->transactions = $this->_getList("SELECT t.*, m.name FROM #__rsmembership_membership_transactions t LEFT JOIN #__rsmembership_memberships m ON (t.membership_id = m.id) WHERE `user_id`='".$cid."' ORDER BY `date` DESC");
		$row->logs = $this->_getList("SELECT * FROM #__rsmembership_logs WHERE `user_id`='".$cid."' ORDER BY `date` DESC");
		
		return $row;
	}
	
	function getMemberships()
	{
		return $this->_getList("SELECT c.name AS category_name, m.* FROM #__rsmembership_memberships m LEFT JOIN #__rsmembership_categories c ON (c.id=m.category_id) WHERE m.`published`='1' ORDER BY c. ordering, m.ordering");
	}
	
	function remove($cids)
	{
		$cids = implode(',', $cids);

		$this->_db->setQuery("DELETE FROM #__rsmembership_users WHERE `id` IN (".$cids.")");
		$this->_db->query();
		
		return true;
	}
	
	function save()
	{
		$post = JRequest::get('post');
		
		$fields = JRequest::getVar('rsm_fields', array(), 'post');
		RSMembership::createUserData((int) $post['u']['id'], $fields);
		
		$user = JFactory::getUser($post['u']['id']);
		$user->bind($post['u']);
		$user->save();
			
		$this->_id = $user->get('id');
		return true;
	}
	
	function getId()
	{
		return $this->_id;
	}
	
	function membershipSave()
	{
		$row =& JTable::getInstance('RSMembership_Membership_Users','Table');
		$post = JRequest::get('post');
		
		if (!empty($post['noextra']))
			$post['extras'] = '';
		else
			$post['extras'] = implode(',', $post['extras']);
		
		if (!empty($post['unlimited']))
			$post['membership_end'] = 0;
		
		if (!$row->bind($post))
			return JError::raiseWarning(500, $row->getError());
		
		// Updating ?
		if ($row->id > 0)
		{
			$this->_db->setQuery("SELECT status FROM #__rsmembership_membership_users WHERE id='".(int) $row->id."'");
			$status = $this->_db->loadResult();
			if ($status > 0 && $row->status == 0)
				$row->notified = 0;
		}
		
		$this->_db->setQuery("SELECT gid_enable, gid_subscribe, gid_expire, disable_expired_account FROM #__rsmembership_memberships WHERE `id`='".(int) $row->membership_id."'");
		$membership = $this->_db->loadObject();
		
		if ($row->status == 0)
		{
			if ($membership->gid_enable)
				RSMembership::updateGid($row->user_id, $membership->gid_subscribe, true);
		}
		else
		{
			if ($membership->gid_enable)
				RSMembership::updateGid($row->user_id, $membership->gid_expire);
				
			if (($row->status == 2 || $row->status == 3) && $membership->disable_expired_account)
				RSMembership::disableUser($row->user_id);
		}
		
		if (strpos($row->membership_start, '.') !== false)
			unset($row->membership_start);
		if (strpos($row->membership_end, '.') !== false)
			unset($row->membership_end);
		
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
	
	function membershipRemove($cids)
	{
		$cids = implode(',', $cids);

		$query = "DELETE FROM #__rsmembership_membership_users WHERE `id` IN (".$cids.")";
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return true;
	}
	
	function getExtras()
	{
		$extras = $this->_getList("SELECT `id`, `name` FROM #__rsmembership_extras ORDER BY `name` ASC");
		$return = array();
		foreach ($extras as $extra)
			$return[$extra->id] = $extra->name;
		
		return $return;
	}
	
	function getExtraValues()
	{
		$values = $this->_getList("SELECT `id`, `extra_id`, `name` FROM #__rsmembership_extra_values ORDER BY `name` ASC");
		$return = array();
		foreach ($values as $value)
			$return[$value->extra_id][$value->id] = $value->name;
		
		return $return;
	}
	
	function getTransactions()
	{
		$cid = JRequest::getInt('cid', 0);
		
		return $this->_getList("SELECT * FROM #__rsmembership_transactions WHERE `user_id`='".$cid."' ORDER BY `date` DESC");
	}
	
	function getCache()
	{
		return RSMembershipHelper::getCache();
	}
}
?>