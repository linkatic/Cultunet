<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelMymemberships extends JModel
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
		
		$user = JFactory::getUser();
		if ($user->get('guest'))
		{
			$link = JRequest::getURI();
			$link = base64_encode($link);
			$mainframe->redirect('index.php?option=com_user&view=login&return='.$link);
		}
		
		// Get pagination request variables
		$limit		= JRequest::getVar('limit', $mainframe->getCfg('list_limit'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.memberships.limit', $limit);
		$this->setState($option.'.memberships.limitstart', $limitstart);
		
		$this->_query = $this->_buildQuery();
	}
	
	function _buildQuery()
	{
		global $mainframe, $option;
		
		$user = JFactory::getUser();
		$cid = $user->get('id');
		
		$query = "SELECT u.*, m.name FROM #__rsmembership_membership_users u LEFT JOIN #__rsmembership_memberships m ON (u.membership_id = m.id) WHERE `user_id`='".$cid."' AND `u`.`published`='1'";
		
		return $query;
	}
	
	function getMemberships()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.memberships.limitstart'), $this->getState($option.'.memberships.limit'));
		
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
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.memberships.limitstart'), $this->getState($option.'.memberships.limit'));
		}
		
		return $this->_pagination;
	}
	
	function getTransactions()
	{
		$user = JFactory::getUser();
		$query = "SELECT * FROM #__rsmembership_transactions WHERE `user_id`='".$user->get('id')."' AND `status`='pending' ORDER BY `date` DESC";
		
		return $this->_getList($query);
	}
}
?>