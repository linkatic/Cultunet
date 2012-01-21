<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelMemberships extends JModel
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
		$limit = $mainframe->getUserStateFromRequest($option.'.memberships.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.memberships.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState($option.'.memberships.limit', $limit);
		$this->setState($option.'.memberships.limitstart', $limitstart);
	}
	
	function _buildQuery()
	{
		global $mainframe;
		
		$query = "SELECT * FROM #__rsmembership_memberships WHERE 1";

		$filter_word = JRequest::getString('search', '');
		if (!empty($filter_word))
			$query .= " AND `name` LIKE '%".$filter_word."%'";
		
		$task = JRequest::getVar('task');
		if ($task != 'save' && $task != 'apply')
		{
			$category_id = $mainframe->getUserStateFromRequest('rsmembership.category_id', 'category_id', -1, 'int');
			if ($category_id != -1)
				$query .= " AND `category_id`='".(int) $category_id."'";
		}
		
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
	
	function getCategories()
	{
		$results = array();
		
		$task = JRequest::getVar('task');
		if ($task != 'edit')
		{
			$item = new stdClass();
			$item->id = -1;
			$item->name = JText::_('RSM_ALL_CATEGORIES');
			$results[] = $item;
		}
		
		$item = new stdClass();
		$item->id = 0;
		$item->name = JText::_('RSM_NO_CATEGORY');
		$results[] = $item;
		
		$results = array_merge($results, $this->_getList("SELECT * FROM #__rsmembership_categories ORDER BY ordering"));
		
		return $results;
	}
	
	function getMembership()
	{
		$cid = JRequest::getVar('cid', 0);
		if (is_array($cid))
			$cid = $cid[0];
		$cid = (int) $cid;
		
		$row =& JTable::getInstance('RSMembership_Memberships','Table');
		$row->load($cid);
		
		$this->_db->setQuery("SELECT `extra_id` FROM #__rsmembership_membership_extras WHERE `membership_id`='".$cid."'");
		$row->extras = $this->_db->loadResultArray();
		
		$this->_db->setQuery("SELECT * FROM #__rsmembership_membership_shared WHERE `membership_id`='".$cid."' ORDER BY `ordering`");
		$row->shared = $this->_db->loadObjectList();
		foreach ($row->shared as $s => $shared)
			switch ($shared->type)
			{
				case 'article':
					$this->_db->setQuery("SELECT `title` FROM #__content WHERE `id`='".(int) $shared->params."'");
					$row->shared[$s]->params = $this->_db->loadResult();
				break;
				
				case 'module':
					$this->_db->setQuery("SELECT `title`, `module` FROM #__modules WHERE `id`='".(int) $shared->params."'");
					$module = $this->_db->loadObject();
					$row->shared[$s]->params = '('.$module->module.') '.$module->title;
				break;
				
				case 'menu':
					$this->_db->setQuery("SELECT `name`, `menutype` FROM #__menu WHERE `id`='".(int) $shared->params."'");
					$menu = $this->_db->loadObject();
					$row->shared[$s]->params = '('.$menu->menutype.') '.$menu->name;
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
		
		$this->_db->setQuery("SELECT * FROM #__rsmembership_membership_attachments WHERE `membership_id`='".$cid."' ORDER BY `ordering`");
		$row->attachments = $this->_db->loadObjectList();
		
		jimport('joomla.html.pagination');
		$row->sharedPagination = new JPagination(count($row->shared), 0, 0);
		$row->attachmentsPagination = new JPagination(count($row->attachments), 0, 0);
		
		return $row;
	}
	
	function getExtras()
	{
		return $this->_getList("SELECT * FROM #__rsmembership_extras ORDER BY `ordering` ASC");
	}
	
	function getTerms()
	{
		return $this->_getList("SELECT * FROM #__rsmembership_terms ORDER BY `ordering` ASC");
	}
	
	function publish($cid=array(), $publish=1)
	{
		if (!is_array($cid) || count($cid) > 0)
		{
			$publish = (int) $publish;
			$cids = implode(',', $cid);

			$query = "UPDATE #__rsmembership_memberships SET `published`='".$publish."' WHERE `id` IN (".$cids.")"	;
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

		$this->_db->setQuery("DELETE FROM #__rsmembership_memberships WHERE `id` IN (".$cids.")");
		$this->_db->query();
		
		$this->_db->setQuery("DELETE FROM #__rsmembership_membership_attachments WHERE `membership_id` IN (".$cids.")");
		$this->_db->query();
		
		$this->_db->setQuery("DELETE FROM #__rsmembership_membership_extras WHERE `membership_id` IN (".$cids.")");
		$this->_db->query();
		
		$this->_db->setQuery("DELETE FROM #__rsmembership_membership_shared WHERE `membership_id` IN (".$cids.")");
		$this->_db->query();
		
		$this->_db->setQuery("DELETE FROM #__rsmembership_membership_upgrades WHERE `membership_from_id` IN (".$cids.") OR `membership_to_id` IN (".$cids.")");
		$this->_db->query();
		
		$this->_db->setQuery("SELECT `id` FROM #__rsmembership_membership_users WHERE `membership_id` IN (".$cids.")");
		$results = $this->_db->loadResultArray();
		if (!empty($results))
		{
			$results = implode(',', $results);
			$this->_db->setQuery("DELETE FROM #__rsmembership_membership_licenses WHERE `mu_id` IN (".$results.")");
			$this->_db->query();
		}
		
		$this->_db->setQuery("DELETE FROM #__rsmembership_membership_users WHERE `membership_id` IN (".$cids.")");
		$this->_db->query();
		
		return true;
	}
	
	function save()
	{
		$row =& JTable::getInstance('RSMembership_Memberships','Table');
		$post = JRequest::get('post');
		
		if (!empty($post['thumb_delete']))
			$post['thumb'] = '';
		
		// Thumbnail width must not be less than 1px
		$post['thumb_w'] = (int) $post['thumb_w'];
		if ($post['thumb_w'] <= 0)
			$post['thumb_w'] = 48;
		// Periods must be an integer
		$post['period'] = (int) $post['period'];
		// Stock must be an integer
		$post['stock'] = (int) $post['stock'];
		
		// These elements are not filtered for HTML code
		$allowed = array('description', 'custom_code', 'thankyou', 'redirect', 'share_redirect', 'admin_email_text', 'user_email_new_text', 'user_email_approved_text', 'user_email_renew_text', 'user_email_upgrade_text', 'user_email_addextra_text', 'user_email_expire_text');
		foreach ($allowed as $item)
			$post[$item] = JRequest::getVar($item, '', 'post', 'none', JREQUEST_ALLOWRAW);
		
		if (!$row->bind($post))
			return JError::raiseWarning(500, $row->getError());
		
		if (empty($row->id))
			$row->ordering = $row->getNextOrder();
		
		unset($row->thumb);
		
		if ($row->store())
		{
			$this->_id = $row->id;

			// Process the thumbnail
			$files = JRequest::get('files');
			$thumb = $files['thumb'];
			jimport('joomla.filesystem.file');
			$thumb['db_name'] = JPATH_ROOT.DS.'components'.DS.'com_rsmembership'.DS.'assets'.DS.'thumbs'.DS.$row->id;
			// Delete it if requested
			if (!empty($post['thumb_delete']))
				JFile::delete($thumb['db_name'].'.jpg');
			// Add the thumbnail if uploaded
			if (!$thumb['error'] && !empty($thumb['tmp_name']))
			{
				// Resize the thumb if requested
				if (!empty($post['thumb_resize']))
					$success = RSMembershipHelper::createThumb($thumb['tmp_name'], $thumb['db_name'], $row->thumb_w);
				else
					$success = JFile::upload($thumb['tmp_name'], $thumb['db_name'].'.jpg');
				// Add to database only if upload successful
				if ($success)
				{
					$this->_db->setQuery("UPDATE #__rsmembership_memberships SET `thumb`='".JFile::getName($thumb['db_name'].'.jpg')."' WHERE `id`='".$row->id."'");
					$this->_db->query();
				}
			}
			
			// Process the extras
			$extras = $post['extras'];
			JArrayHelper::toInteger($extras, array(0));
			
			$this->_db->setQuery("DELETE FROM #__rsmembership_membership_extras WHERE `membership_id`='".$row->id."'");
			$this->_db->query();
			foreach ($extras as $extra)
			{
				if (empty($extra)) continue;
				$this->_db->setQuery("INSERT INTO #__rsmembership_membership_extras SET `membership_id`='".$row->id."', `extra_id`='".$extra."'");
				$this->_db->query();
			}
			return true;
		}
		else
		{
			JError::raiseWarning(500, $row->getError());
			return false;
		}
	}
	
	function copy($cid)
	{
		jimport('joomla.filesystem.file');
		$row =& JTable::getInstance('RSMembership_Memberships', 'Table');
		$row->load($cid);
		$row->published = 0;
		$row->name = JText::_('RSM_COPY_OF').' '.$row->name;
		$row->ordering = $row->getNextOrder();
		$row->id = null;
		$row->store();
		
		$membership_new_id = $row->id;
		
		if (!empty($row->thumb))
		{
			$old_thumb = JPATH_ROOT.DS.'components'.DS.'com_rsmembership'.DS.'assets'.DS.'thumbs'.DS.$row->thumb;
			$new_thumb = JPATH_ROOT.DS.'components'.DS.'com_rsmembership'.DS.'assets'.DS.'thumbs'.DS.$row->id.'.jpg';
			$copied = JFile::copy($old_thumb, $new_thumb);
			if ($copied)
			{
				$row->thumb = $row->id.'.jpg';
				$row->store();
			}
			else
			{
				$row->thumb = '';
				$row->store();
			}
		}
		
		$this->_db->setQuery("SELECT id FROM #__rsmembership_membership_attachments WHERE `membership_id`='".$cid."'");
		$attachments = $this->_db->loadObjectList();
		if (!empty($attachments))
			foreach ($attachments as $attachment);
			{
				$row =& JTable::getInstance('RSMembership_Membership_Attachments', 'Table');
				$row->load($attachment->id);
				$row->membership_id = $membership_new_id;
				$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
				$row->id = null;
				$row->store();
			}
		
		$this->_db->setQuery("SELECT id FROM #__rsmembership_membership_extras WHERE `membership_id`='".$cid."'");
		$extras = $this->_db->loadObjectList();
		if (!empty($extras))
			foreach ($extras as $extra)
			{
				$row =& JTable::getInstance('RSMembership_Membership_Extras', 'Table');
				$row->load($extra->id);
				$row->membership_id = $membership_new_id;
				$row->id = null;
				$row->store();
			}
		
		$this->_db->setQuery("SELECT id FROM #__rsmembership_membership_shared WHERE `membership_id`='".$cid."'");
		$shares = $this->_db->loadObjectList();
		if (!empty($shares))
			foreach ($shares as $share)
			{
				$row =& JTable::getInstance('RSMembership_Membership_Shared', 'Table');
				$row->load($share->id);
				$row->membership_id = $membership_new_id;
				$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
				$row->id = null;
				$row->store();
			}
		
		return true;
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

			$query = "UPDATE #__rsmembership_membership_shared SET `published`='".$publish."' WHERE `id` IN (".$cids.")"	;
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

		$query = "DELETE FROM #__rsmembership_membership_shared WHERE `id` IN (".$cids.")";
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return true;
	}
	
	/**
	 * Attachment Tasks
	 */
	
	// Attachment - Publish
	function attachmentsPublish($cid=array(), $publish=1)
	{
		if (!is_array($cid) || count($cid) > 0)
		{
			$publish = (int) $publish;
			$cids = implode(',', $cid);

			$query = "UPDATE #__rsmembership_membership_attachments SET `published`='".$publish."' WHERE `id` IN (".$cids.")"	;
			$this->_db->setQuery($query);
			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return $cid;
	}
	
	// Attachment - Remove
	function attachmentsRemove($cids)
	{
		$cids = implode(',', $cids);

		$query = "DELETE FROM #__rsmembership_membership_attachments WHERE `id` IN (".$cids.")";
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return true;
	}
}
?>