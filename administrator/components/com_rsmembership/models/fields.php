<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelFields extends JModel
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
		$limit = $mainframe->getUserStateFromRequest($option.'.fields.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.fields.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.fields.limit', $limit);
		$this->setState($option.'.fields.limitstart', $limitstart);
	}
	
	function _buildQuery()
	{
		global $mainframe;

		$query = "SELECT * FROM #__rsmembership_fields WHERE 1 ";

		$filter_word = JRequest::getWord('search', '');
		if (!empty($filter_word))
			$query .= " AND `name` LIKE '%".$filter_word."%' OR `label` LIKE '%".$filter_word."%'";
		
		$filter_state = $mainframe->getUserStateFromRequest('rsmembership.filter_state', 'filter_state');
		if ($filter_state != '')
			$query .= " AND `published`='".($filter_state == 'U' ? '0' : '1')."'";
		
		$sortColumn = JRequest::getVar('filter_order', 'ordering');
		$sortColumn = $this->_db->getEscaped($sortColumn);
		
		$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
		$sortOrder = $this->_db->getEscaped($sortOrder);
		
		$query .= " ORDER BY `".$sortColumn."` ".$sortOrder;
		
		return $query;
	}
	
	function getFields()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.fields.limitstart'), $this->getState($option.'.fields.limit'));
		
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
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.fields.limitstart'), $this->getState($option.'.fields.limit'));
		}
		
		return $this->_pagination;
	}
	
	function getField()
	{
		$cid = JRequest::getInt('cid', 0);
		
		$row =& JTable::getInstance('RSMembership_Fields','Table');
		$row->load($cid);
		
		return $row;
	}
	
	function publish($cid=array(), $publish=1)
	{
		if (!is_array($cid) || count($cid) > 0)
		{
			$publish = (int) $publish;
			$cids = implode(',', $cid);

			$query = "UPDATE #__rsmembership_fields SET `published`='".$publish."' WHERE `id` IN (".$cids.")";
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
		$this->_db->setQuery("DELETE FROM #__rsmembership_fields WHERE `id` IN (".implode(',', $cids).")");
		$this->_db->query();
		
		foreach ($cids as $cid)
		{
			$this->_db->setQuery("ALTER TABLE #__rsmembership_users DROP `f".$cid."`");
			$this->_db->query();
		}
		
		return true;
	}
	
	function save()
	{
		$row =& JTable::getInstance('RSMembership_Fields','Table');
		$post = JRequest::get('post');
		
		// These elements are not filtered for HTML code
		$post['values'] = JRequest::getVar('values', '', 'post', 'none', JREQUEST_ALLOWRAW);
		$post['additional'] = JRequest::getVar('additional', '', 'post', 'none', JREQUEST_ALLOWRAW);
		$post['name'] = JFilterOutput::stringURLSafe($post['name']);
		
		if (!$row->bind($post))
			return JError::raiseWarning(500, $row->getError());
		
		if (empty($row->id))
			$row->ordering = $row->getNextOrder();
		
		if ($row->store())
		{
			$db = JFactory::getDBO();
			$db->setQuery("SHOW COLUMNS FROM #__rsmembership_users WHERE `Field` = 'f".$row->id."'");
			if (!$db->loadResult())
			{
				$type = 'VARCHAR(255)';
				if (in_array($row->type, array('freetext', 'textarea')))
					$type = 'TEXT';
				
				$db->setQuery("ALTER TABLE #__rsmembership_users ADD `f".$row->id."` ".$type." NOT NULL");
				$db->query();
			}
			
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