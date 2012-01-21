<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelShare extends JModel
{
	var $_data = null;
	var $_total = 0;
	var $_query = '';
	var $_pagination = null;
	var $_db = null;
	
	function __construct()
	{
		parent::__construct();
		$this->_db = JFactory::getDBO();
		$this->_query = $this->_buildQuery();
		
		global $mainframe, $option;
		
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest($option.'.share.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.share.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.share.limit', $limit);
		$this->setState($option.'.share.limitstart', $limitstart);
	}
	
	function _buildQuery()
	{
		global $mainframe;

		$layout = JRequest::getVar('layout');
		$query = '';
		
		switch ($layout)
		{
			case 'article':
				$query = "SELECT a.*, c.title AS categorytitle, s.title AS sectiontitle FROM #__content a LEFT JOIN #__categories c ON (c.id = a.catid) LEFT JOIN #__sections s ON (s.id = a.sectionid) WHERE 1";
				$filter_word = JRequest::getString('search', '');
				if (!empty($filter_word))
					$query .= " AND `title` LIKE '%".$filter_word."%'";
		
				$sortColumn = JRequest::getVar('filter_order', 'ordering');
				$sortColumn = $this->_db->getEscaped($sortColumn);
		
				$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
				$sortOrder = $this->_db->getEscaped($sortOrder);
		
				$query .= " ORDER BY `".$sortColumn."` ".$sortOrder;
			break;
			
			case 'module':
				$query = "SELECT * FROM #__modules m WHERE 1";
				$filter_word = JRequest::getString('search', '');
				if (!empty($filter_word))
					$query .= " AND `title` LIKE '%".$filter_word."%' OR `module` LIKE '%".$filter_word."%'";
		
				$sortColumn = JRequest::getVar('filter_order', 'client_id, position, ordering');
				$sortColumn = $this->_db->getEscaped($sortColumn);
		
				$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
				$sortOrder = $this->_db->getEscaped($sortOrder);
		
				$query .= " ORDER BY ".$sortColumn." ".$sortOrder;
			break;
			
			case 'menu':
				$query = "SELECT * FROM #__menu m WHERE published != '-2'";
				$filter_word = JRequest::getString('search', '');
				if (!empty($filter_word))
					$query .= " AND `name` LIKE '%".$filter_word."%'";
		
				$sortColumn = JRequest::getVar('filter_order', 'menutype, ordering');
				$sortColumn = $this->_db->getEscaped($sortColumn);
		
				$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
				$sortOrder = $this->_db->getEscaped($sortOrder);
		
				$query .= " ORDER BY ".$sortColumn." ".$sortOrder;
			break;
			
			case 'section':
				$query = "SELECT * FROM #__sections WHERE 1";
				$filter_word = JRequest::getString('search', '');
				if (!empty($filter_word))
					$query .= " AND `title` LIKE '%".$filter_word."%'";
		
				$sortColumn = JRequest::getVar('filter_order', 'ordering');
				$sortColumn = $this->_db->getEscaped($sortColumn);
		
				$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
				$sortOrder = $this->_db->getEscaped($sortOrder);
		
				$query .= " ORDER BY `".$sortColumn."` ".$sortOrder;
			break;
			
			case 'category':
				$query = "SELECT * FROM #__categories WHERE 1";
				$filter_word = JRequest::getString('search', '');
				if (!empty($filter_word))
					$query .= " AND `title` LIKE '%".$filter_word."%'";
		
				$sortColumn = JRequest::getVar('filter_order', 'ordering');
				$sortColumn = $this->_db->getEscaped($sortColumn);
		
				$sortOrder = JRequest::getVar('filter_order_Dir', 'ASC');
				$sortOrder = $this->_db->getEscaped($sortOrder);
		
				$query .= " ORDER BY `".$sortColumn."` ".$sortOrder;
			break;
		}
		
		return $query;
	}
	
	function getData()
	{
		global $option;
		
		if (empty($this->_data))
			$this->_data = $this->_getList($this->_query, $this->getState($option.'.share.limitstart'), $this->getState($option.'.share.limit'));
		
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
			$this->_pagination = new JPagination($this->getTotal(), $this->getState($option.'.share.limitstart'), $this->getState($option.'.share.limit'));
		}
		
		return $this->_pagination;
	}
	
	function getURL()
	{
		$cid = JRequest::getInt('cid', 0);
		
		$membership_id = JRequest::getInt('membership_id', 0);
		$extra_value_id = JRequest::getInt('extra_value_id', 0);
		
		if (!empty($membership_id))
			$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
		else
			$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
		$row->load($cid);
		
		return $row;
	}
	
	function addmembershiparticles($articles)
	{
		$membership_id = JRequest::getInt('membership_id', 0);
		
		foreach ($articles as $article_id)
		{
			$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
			$row->membership_id = $membership_id;
			$row->params = $article_id;
			$row->type = 'article';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_membership_shared WHERE `params`='".$this->_db->getEscaped($article_id)."' AND `membership_id`='".$membership_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
			$row->store();
		}
		return true;
	}
	
	function addextravaluearticles($articles)
	{
		$extra_value_id = JRequest::getInt('extra_value_id', 0);
		
		foreach ($articles as $article_id)
		{
			$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
			$row->extra_value_id = $extra_value_id;
			$row->params = $article_id;
			$row->type = 'article';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_extra_value_shared WHERE `params`='".$this->_db->getEscaped($article_id)."' AND `extra_value_id`='".$extra_value_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`extra_value_id`='".$row->extra_value_id."'");
			$row->store();
		}
		return true;
	}
	
	function addmembershipsections($sections)
	{
		$membership_id = JRequest::getInt('membership_id', 0);
		
		foreach ($sections as $section_id)
		{
			$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
			$row->membership_id = $membership_id;
			$row->params = $section_id;
			$row->type = 'section';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_membership_shared WHERE `params`='".$this->_db->getEscaped($article_id)."' AND `membership_id`='".$membership_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
			$row->store();
		}
		return true;
	}
	
	function addextravaluesections($sections)
	{
		$extra_value_id = JRequest::getInt('extra_value_id', 0);
		
		foreach ($sections as $section_id)
		{
			$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
			$row->extra_value_id = $extra_value_id;
			$row->params = $section_id;
			$row->type = 'section';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_extra_value_shared WHERE `params`='".$this->_db->getEscaped($article_id)."' AND `extra_value_id`='".$extra_value_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`extra_value_id`='".$row->extra_value_id."'");
			$row->store();
		}
		return true;
	}
	
	function addmembershipcategories($categories)
	{
		$membership_id = JRequest::getInt('membership_id', 0);
		
		foreach ($categories as $category_id)
		{
			$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
			$row->membership_id = $membership_id;
			$row->params = $category_id;
			$row->type = 'category';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_membership_shared WHERE `params`='".$this->_db->getEscaped($article_id)."' AND `membership_id`='".$membership_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
			$row->store();
		}
		return true;
	}
	
	function addextravaluecategories($categories)
	{
		$extra_value_id = JRequest::getInt('extra_value_id', 0);
		
		foreach ($categories as $category_id)
		{
			$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
			$row->extra_value_id = $extra_value_id;
			$row->params = $category_id;
			$row->type = 'category';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_extra_value_shared WHERE `params`='".$this->_db->getEscaped($article_id)."' AND `extra_value_id`='".$extra_value_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`extra_value_id`='".$row->extra_value_id."'");
			$row->store();
		}
		return true;
	}
	
	function addmembershipurl($url)
	{
		$membership_id = JRequest::getInt('membership_id', 0);
		$cid = $url;
		$post = JRequest::get('post');
		
		$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
		$row->id = $cid;
		$row->membership_id = $membership_id;
		$post['params'] = JRequest::getVar('params', '', 'post', 'none', JREQUEST_ALLOWRAW);
		$row->params = $post['params'];
		$row->type = $post['where'];
		$row->book_id = $post['book_id'];
		
		if (empty($row->id))
			$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
		
		$row->store();
		
		return true;
	}
	
	function addextravalueurl($url)
	{
		$extra_value_id = JRequest::getInt('extra_value_id', 0);
		$cid = $url;
		$post = JRequest::get('post');
		
		$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
		$row->id = $cid;
		$row->extra_value_id = $extra_value_id;
		$post['params'] = JRequest::getVar('params', '', 'post', 'none', JREQUEST_ALLOWRAW);
		$row->params = $post['params'];
		$row->type = $post['where'];
		$row->book_id = $post['book_id'];
		
		if (empty($row->id))
			$row->ordering = $row->getNextOrder("`extra_value_id`='".$row->extra_value_id."'");
		
		$row->store();
		
		return true;
	}
	
	function addmembershipmodules($modules)
	{
		$membership_id = JRequest::getInt('membership_id', 0);
		
		foreach ($modules as $module_id)
		{
			$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
			$row->membership_id = $membership_id;
			$row->params = $module_id;
			$row->type = 'module';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_membership_shared WHERE `params`='".$this->_db->getEscaped($module_id)."' AND `membership_id`='".$membership_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
			$row->store();
		}
		return true;
	}
	
	function addextravaluemodules($modules)
	{
		$extra_value_id = JRequest::getInt('extra_value_id', 0);
		
		foreach ($modules as $module_id)
		{
			$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
			$row->extra_value_id = $extra_value_id;
			$row->params = $module_id;
			$row->type = 'module';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_extra_value_shared WHERE `params`='".$this->_db->getEscaped($module_id)."' AND `extra_value_id`='".$extra_value_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`extra_value_id`='".$row->extra_value_id."'");
			$row->store();
		}
		return true;
	}
	
	function addmembershipmenus($items)
	{
		$membership_id = JRequest::getInt('membership_id', 0);
		
		foreach ($items as $item_id)
		{
			$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
			$row->membership_id = $membership_id;
			$row->params = $item_id;
			$row->type = 'menu';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_membership_shared WHERE `params`='".$this->_db->getEscaped($item_id)."' AND `membership_id`='".$membership_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
			$row->store();
		}
		return true;
	}
	
	function addextravaluemenus($items)
	{
		$extra_value_id = JRequest::getInt('extra_value_id', 0);
		
		foreach ($items as $item_id)
		{
			$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
			$row->extra_value_id = $extra_value_id;
			$row->params = $item_id;
			$row->type = 'menu';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_extra_value_shared WHERE `params`='".$this->_db->getEscaped($item_id)."' AND `extra_value_id`='".$extra_value_id."' AND `type`='".$row->type."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`extra_value_id`='".$row->extra_value_id."'");
			$row->store();
		}
		return true;
	}
}
?>