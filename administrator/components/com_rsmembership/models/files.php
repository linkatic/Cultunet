<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');

class RSMembershipModelFiles extends JModel
{
	var $_folder = null;
	var $_id = null;
	var $_db;
	
	function __construct()
	{
		parent::__construct();
		
		$this->_db = JFactory::getDBO();
		
		if (is_dir(JRequest::getVar('folder')))
		{
			$this->_folder = JRequest::getVar('folder');
			if (substr($this->_folder, -1) == DS)
				$this->_folder = substr($this->_folder, 0, -1);
		}
		else
			$this->_folder = JPATH_SITE;
	}
	
	function getFolders()
	{
		$folders = array();
		
		$all_folders = JFolder::folders($this->_folder);
		foreach ($all_folders as $folder)
		{
			$element = new stdClass();
			$element->name = $folder;
			$element->fullpath = $this->_folder.DS.$folder;
			$folders[] = $element;
		}
		
		return $folders;
	}
	
	function getFiles()
	{
		$files = array();
		
		$all_files = JFolder::files($this->_folder);
		foreach ($all_files as $file)
		{
			$element = new stdClass();
			$element->name = $file;
			$element->fullpath = $this->_folder.DS.$file;
			$element->published = 1;
			$files[] = $element;
		}
		
		return $files;
	}
	
	function getElements()
	{		
		$elements = explode(DS, $this->_folder);
		$navigation_path = '';
		
		if(!empty($elements))
			foreach($elements as $i=>$element)
			{
				$navigation_path .= $element;
				$newelement = new stdClass();
				$newelement->name = $element;
				$newelement->fullpath = $navigation_path;
				$elements[$i] = $newelement;
				$navigation_path .= DS;
			}
		
		return $elements;
	}
	
	function getCurrent()
	{
		return $this->_folder;
	}
	
	function getPrevious()
	{
		$elements = explode(DS, $this->_folder);
		if (count($elements) > 1)
			array_pop($elements);
		return implode(DS, $elements);
	}
	
	function pathExists()
	{
		$cid = JRequest::getVar('cid', '');
		if (is_array($cid))
			$cid = $cid[0];
		
		return (is_file($cid) || is_dir($cid));
	}
	
	function getFile()
	{
		$cid = JRequest::getVar('cid', '');
		if (is_array($cid))
			$cid = $cid[0];
		
		$this->_db->setQuery("SELECT * FROM #__rsmembership_files WHERE `path`='".$this->_db->getEscaped($cid)."'");
		$row = $this->_db->loadObject();
		if (empty($row))
		{
			$row =& JTable::getInstance('RSMembership_Files','Table');
			$row->load(0);
			$row->path = $cid;
		}
		
		return $row;
	}
	
	function getTerms()
	{
		return $this->_getList("SELECT * FROM #__rsmembership_terms ORDER BY `ordering` ASC");
	}
	
	function save()
	{
		$row =& JTable::getInstance('RSMembership_Files','Table');
		$post = JRequest::get('post');
		
		if (!empty($post['thumb_delete']))
			$post['thumb'] = '';
		// Thumbnail width must not be less than 1px
		$post['thumb_w'] = (int) $post['thumb_w'];
		if ($post['thumb_w'] <= 0)
			$post['thumb_w'] = 48;
		// These elements are not filtered for HTML code
		$post['description'] = JRequest::getVar('description', '', 'post', 'none', JREQUEST_ALLOWRAW);
		
		if (!$row->bind($post))
			return JError::raiseWarning(500, $row->getError());
		
		unset($row->thumb);
		
		if ($row->store())
		{
			$this->_id = $row->path;
			
			// Process the thumbnail
			$files = JRequest::get('files');
			$thumb = $files['thumb'];
			jimport('joomla.filesystem.file');
			$thumb['db_name'] = JPATH_ROOT.DS.'components'.DS.'com_rsmembership'.DS.'assets'.DS.'thumbs'.DS.'files'.DS.$row->id;
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
					$this->_db->setQuery("UPDATE #__rsmembership_files SET `thumb`='".JFile::getName($thumb['db_name'].'.jpg')."' WHERE `id`='".$row->id."'");
					$this->_db->query();
				}
			}
			
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
	
	function getFolder()
	{
		return dirname($this->_id);
	}
	
	function upload()
	{
		$files = JRequest::get('files');
		$upload = $files['upload'];
		if (!$files['error'])
			return JFile::upload($upload['tmp_name'], $this->getCurrent().DS.JFile::getName($upload['name']));
		else
			return false;
	}
	
	function getCanUpload()
	{
		return is_writable($this->_folder);
	}
	
	function remove($cids)
	{
		$query = "DELETE FROM #__rsmembership_files WHERE `path` IN ('".implode("','", $cids)."')";
		$this->_db->setQuery($query);
		$this->_db->query();
		
		foreach ($cids as $cid)
		{
			if (is_dir($cid))
				JFolder::delete($cid);
			elseif (is_file($cid))
				JFile::delete($cid);
		}
		
		return true;
	}
	
	function newdir($dirname)
	{
		return JFolder::create($this->_folder.DS.$dirname);
	}
	
	function addmembershipfolders($folders)
	{
		$membership_id = JRequest::getInt('membership_id', 0);
		foreach ($folders as $folder)
		{			
			$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
			$row->membership_id = $membership_id;
			if (substr($folder, -1) != DS)
				$folder .= DS;
			$row->params = $folder;
			$row->type = 'folder';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_membership_shared WHERE `params`='".$this->_db->getEscaped($folder)."' AND `membership_id`='".$membership_id."' AND `type`='folder'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
			
			if (is_dir($row->params))
				$row->store();
		}
		return true;
	}
	
	function addsubscriberfiles($files)
	{
		$membership_id = JRequest::getInt('membership_id', 0);
		foreach ($files as $file)
		{
			$row =& JTable::getInstance('RSMembership_Membership_Attachments','Table');
			$row->membership_id = $membership_id;
			$row->path = $file;
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_membership_attachments WHERE `path`='".$this->_db->getEscaped($file)."' AND `membership_id`='".$membership_id."'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`membership_id`='".$row->membership_id."'");
			
			if (is_file($row->path))
				$row->store();
		}
		return true;
	}
	
	function addextravaluefolders($folders)
	{
		$extra_value_id = JRequest::getInt('extra_value_id', 0);
		foreach ($folders as $folder)
		{			
			$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
			$row->extra_value_id = $extra_value_id;
			if (substr($folder, -1) != DS)
				$folder .= DS;
			$row->params = $folder;
			$row->type = 'folder';
			
			$this->_db->setQuery("SELECT * FROM #__rsmembership_extra_value_shared WHERE `params`='".$this->_db->getEscaped($folder)."' AND `extra_value_id`='".$extra_value_id."' AND `type`='folder'");
			$this->_db->query();
			if ($this->_db->getNumRows())
				continue;
				
			$row->ordering = $row->getNextOrder("`extra_value_id`='".$row->extra_value_id."'");
			
			if (is_dir($row->params))
				$row->store();
		}
		return true;
	}
	
	function getIsFile()
	{
		$cid = JRequest::getVar('cid');
		return is_file($cid);
	}
}
?>