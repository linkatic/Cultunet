<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelExtraValues extends JModel
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
		$limit = $mainframe->getUserStateFromRequest($option.'.extravalues.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.extravalues.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.extravalues.limit', $limit);
		$this->setState($option.'.extravalues.limitstart', $limitstart);
	}
	
	function _buildQuery()
	{
		global $mainframe;
		
		$cid = JRequest::getInt('extra_id', 0);
		
		$query = "SELECT * FROM #__rsmembership_extra_values WHERE `extra_id`='".$cid."'";

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
	
	function getExtraValues()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.extravalues.limitstart'), $this->getState($option.'.extravalues.limit'));
		
		$cid = JRequest::getInt('extra_id', 0);
		
		$extra =& JTable::getInstance('RSMembership_Extras','Table');
		$extra->load($cid);
		
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
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.extravalues.limitstart'), $this->getState($option.'.extravalues.limit'));
		}
		
		return $this->_pagination;
	}
	
	function getExtraValue()
	{
		$cid = JRequest::getInt('cid', 0);
		
		$row =& JTable::getInstance('RSMembership_Extra_Values','Table');
		$row->load($cid);
		
		$this->_db->setQuery("SELECT * FROM #__rsmembership_extra_value_shared WHERE `extra_value_id`='".$cid."' ORDER BY `ordering`");
		$row->shared = $this->_db->loadObjectList();
		foreach ($row->shared as $s => $shared)
			switch ($shared->type)
			{
				case 'article':
					$this->_db->setQuery("SELECT `title` FROM #__content WHERE `id`='".(int) $shared->params."'");
					$row->shared[$s]->params = $this->_db->loadResult();
				break;
				
				case 'section':
					$this->_db->setQuery("SELECT `title` FROM #__sections WHERE `id`='".(int) $shared->params."'");
					$row->shared[$s]->params = $this->_db->loadResult();
				break;
				
				case 'category':
					$this->_db->setQuery("SELECT `title` FROM #__categories WHERE `id`='".(int) $shared->params."'");
					$row->shared[$s]->params = $this->_db->loadResult();
				break;
			}
		
		jimport('joomla.html.pagination');
		$row->sharedPagination = new JPagination(count($row->shared), 0, 0);
		
		return $row;
	}
	
	function publish($cid=array(), $publish=1)
	{
		if (!is_array($cid) || count($cid) > 0)
		{
			$publish = (int) $publish;
			$cids = implode(',', $cid);

			$query = "UPDATE #__rsmembership_extra_values SET `published`='".$publish."' WHERE `id` IN (".$cids.")"	;
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

		$query = "DELETE FROM #__rsmembership_extra_values WHERE `id` IN (".$cids.")";
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return true;
	}
	
	function save()
	{
		$row =& JTable::getInstance('RSMembership_Extra_Values','Table');
		$post = JRequest::get('post');
		
		// These elements are not filtered for HTML code
		$post['description'] = JRequest::getVar('description', '', 'post', 'none', JREQUEST_ALLOWRAW);
		
		$post['extra_id'] = $this->getExtraId();
		
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
	
	function getExtraId()
	{
		$cid = JRequest::getInt('extra_id', 0);
		
		return $cid;
	}
	
	function getId()
	{
		return $this->_id;
	}
	
	/**
	 * Folder Tasks
	 */
	
	// Folder - Publish
	function foldersPublish($cid=array(), $publish=1)
	{
		if (!is_array($cid) || count($cid) > 0)
		{
			$publish = (int) $publish;
			$cids = implode(',', $cid);

			$query = "UPDATE #__rsmembership_extra_value_shared SET `published`='".$publish."' WHERE `id` IN (".$cids.")"	;
			$this->_db->setQuery($query);
			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return $cid;
	}
	
	// Folder - Remove
	function foldersRemove($cids)
	{
		$cids = implode(',', $cids);

		$query = "DELETE FROM #__rsmembership_extra_value_shared WHERE `id` IN (".$cids.")";
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return true;
	}
}
?>