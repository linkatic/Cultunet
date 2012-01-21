<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelMymembership extends JModel
{
	var $_data = null;
	var $_total = 0;
	var $_query = '';
	var $_pagination = null;
	var $_db = null;
	var $_folder = null;
	var $_parents = array();
	var $_extra_parents = array();
	var $_parent = 0;
	
	var $db_files;
	var $terms;
	
	function __construct()
	{
		parent::__construct();
		
		global $mainframe;
		
		$user = JFactory::getUser();
		if ($user->get('guest'))
		{
			$link = JRequest::getURI();
			$link = base64_encode($link);
			$mainframe->redirect('index.php?option=com_user&view=login&return='.$link);
		}
		
		$this->_getMembership();
		$this->getParentFolders();
		$this->getExtraParentFolders();
		
		// let's see if the membership is active
		if ($this->_data->status > 0)
			return;
		
		// let's get the path
		$path = JRequest::getVar('path');
		if (!empty($path))
		{
			$path = explode("|", $path);
			// extract the parent folder's id
			$parent_id = (int) $path[0];
			if (empty($parent_id))
				$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
			
			// extract the path within the parent
			$path = !empty($path[1]) ? $path[1] : '';
			
			// check where are we looking
			$from = $this->getFrom();
			if ($from == 'membership')
				$parent = $this->_parents[$parent_id];
			elseif ($from == 'extra')
				$parent = $this->_extra_parents[$parent_id];

			// check if the parent is within the allowed parents list
			if (empty($parent))
				$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
			$this->_parent = $parent_id;
				
			// compute the full path: parent + path
			$path = realpath($parent.$path);
			$parent = realpath($parent);
			
			// check if we are trying to access a path that's not within the parent
			if (strpos($path, $parent) !== 0)
				$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
			
			// let's see if we've requested a download
			$task = JRequest::getVar('task');
			if ($task == 'download')
			{
				// check if path exists and is a file
				if (is_file($path))
				{					
					// check if we need to agree to terms first
					$this->_db->setQuery("SELECT `term_id` FROM #__rsmembership_files WHERE `path`='".$this->_db->getEscaped($path)."'");
					$term_id = $this->_db->loadResult();
					if (!empty($term_id))
					{
						$row =& JTable::getInstance('RSMembership_Terms','Table');
						$row->load($term_id);
						if (!$row->published)
							$term_id = 0;
					}
					
					$agree = JRequest::getVar('agree');
					if (!empty($term_id) && empty($agree))
					{
						$this->terms = $row->description;
					}
					else
					{
						@ob_end_clean();
						$filename = basename($path);
						header("Cache-Control: public, must-revalidate");
						header('Cache-Control: pre-check=0, post-check=0, max-age=0');
						header("Pragma: no-cache");
						header("Expires: 0"); 
						header("Content-Description: File Transfer");
						header("Expires: Sat, 01 Jan 2000 01:00:00 GMT");
						if (preg_match('#Opera#', $_SERVER['HTTP_USER_AGENT']))
							header("Content-Type: application/octetstream");
						else
							header("Content-Type: application/octet-stream");
						header("Content-Length: ".(string) filesize($path));
						header('Content-Disposition: attachment; filename="'.$filename.'"');
						header("Content-Transfer-Encoding: binary\n");
						@readfile($path);
						$row =& JTable::getInstance('RSMembership_Logs','Table');
						$row->date = time();
						$row->user_id = $user->get('id');
						$row->path = $path;
						$row->ip = $_SERVER['REMOTE_ADDR'];
						$row->store();
						exit();
					}
				}
				else
					$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
			}
			else
			{
				// check if the path exists and is a folder
				if (is_dir($path))
				{
					$this->_folder = $path;
					if (substr($this->_folder, -1) == DS)
						$this->_folder = substr($this->_folder, 0, -1);
				}
				else
					$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
			}
		}
		
		$this->_getDBFiles();
	}
	
	function _getDBFiles()
	{
		$this->_db->setQuery("SELECT path, name, description, thumb FROM #__rsmembership_files");
		$result = $this->_db->loadObjectList();
		$this->db_files = array();
		foreach ($result as $file)
		{
			$element = new stdClass();
			$element->name = $file->name;
			$element->description = $file->description;
			$element->thumb = $file->thumb;
			
			$this->db_files[$file->path] = $element;
		}
	}
	
	function _getMembership()
	{
		$user = JFactory::getUser();
		$id = $user->get('id');
		$cid = $this->getCid();
		$this->_db->setQuery("SELECT mu.*, m.`name`, m.`term_id`, m.no_renew FROM #__rsmembership_membership_users mu LEFT JOIN #__rsmembership_memberships m ON (`mu`.`membership_id`=`m`.`id`) WHERE `mu`.`id`='".$cid."' AND `mu`.`user_id`='".$id."'");
		
		$this->_data = $this->_db->loadObject();
		if (empty($this->_data->id))
		{
			global $mainframe;
			$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
		}
	}
	
	function _getBooksRelMembership()
	{
		$user = JFactory::getUser();
		$id = $user->get('id');
		$cid = $this->getCid();
		$return = array();
		
		/*$this->_db->setQuery("SELECT `book_id` FROM #__rsmembership_books mb 
		INNER JOIN #__rsmembership_membership_users mu 
		WHERE (`mb`.`membership_id`=`mu`.`membership_id`) AND (`mu`.`id`='".$cid."') AND (`mu`.`user_id`='".$id."')");*/
		$this->_db->setQuery("SELECT `book_id`, `title` FROM #__rsmembership_membership_shared ms 
		INNER JOIN #__rsmembership_membership_users mu 
		INNER JOIN #__flippingbook_books fb 
		WHERE (`ms`.`membership_id`=`mu`.`membership_id`) AND (`fb`.`id`=`ms`.`book_id`) AND (`mu`.`id`='".$cid."') AND (`mu`.`user_id`='".$id."') AND (`ms`.`type`='frontendurl')");
		$return = $this->_db->loadAssocList();
		
		error_log('#################BooksRelMembership: '. "SELECT `book_id` FROM #__rsmembership_books mb 
		INNER JOIN #__rsmembership_memberships_users mu 
		WHERE (`mb`.`membership_id`=`mu`.`membership_id`) AND (`mu`.`id`='".$cid."') AND (`mu`.`user_id`='".$id."')");
		
		return $return;
		
	}
	
	function getBoughtExtras()
	{
		$return = array();
		
		if (!empty($this->_data->extras))
		{
			$this->_db->setQuery("SELECT `name` FROM #__rsmembership_extra_values WHERE `id` IN (".$this->_data->extras.") AND `published`='1' ORDER BY `extra_id` ASC, `ordering` ASC");
			$return = $this->_db->loadResultArray();
		}
		
		return $return;
	}
	
	function getExtras()
	{
		$return = array();
		
		$this->_db->setQuery("SELECT e.* FROM #__rsmembership_membership_extras me LEFT JOIN #__rsmembership_extras e ON (me.extra_id = e.id) WHERE me.membership_id = '".$this->_data->membership_id."' AND e.published = '1' AND e.type NOT IN ('dropdown', 'radio')");
		$extras = $this->_db->loadObjectList();
			
		foreach ($extras as $extra)
		{
			$this->_db->setQuery("SELECT * FROM #__rsmembership_extra_values WHERE `extra_id`='".$extra->id."' ".(!empty($this->_data->extras) ? " AND `id` NOT IN (".$this->_data->extras.")" : "")." AND `published`='1' ORDER BY `ordering` ASC");
			$values = $this->_db->loadObjectList();
			
			$return = array_merge($return, $values);
		}
		
		return $return;
	}
	
	function getUpgrades()
	{
		$this->_db->setQuery("SELECT u.*, m.name FROM #__rsmembership_membership_upgrades u LEFT JOIN #__rsmembership_memberships m ON (`u`.`membership_to_id`=`m`.`id`) WHERE `u`.`membership_from_id`='".$this->_data->membership_id."' AND u.published=1");
		return $this->_db->loadObjectList();
	}
	
	function getTerms()
	{
		return $this->terms;
	}
	
	function getMembership()
	{
		return $this->_data;
	}
	
	function getMembershipTerms()
	{
		if (!empty($this->_data->term_id))
		{
			$row =& JTable::getInstance('RSMembership_Terms','Table');
			$row->load($this->_data->term_id);
			if (!$row->published)
				return false;
				
			return $row;
		}
		
		return false;
	}
	
	function getCid()
	{
		return JRequest::getInt('cid');
	}
	
	function getFrom()
	{
		return JRequest::getWord('from', 'membership');
	}
	
	function getParentFolders()
	{
		// let's see if the membership is active
		if ($this->_data->status > 0)
			return $this->_parents;
		
		$this->_db->setQuery("SELECT id, params AS path FROM #__rsmembership_membership_shared WHERE `membership_id`='".$this->_data->membership_id."' AND `type`='folder' AND `published`='1' ORDER BY `ordering` ASC");
		$parents = $this->_db->loadObjectList();
		foreach ($parents as $parent)
			$this->_parents[$parent->id] = $parent->path;
		
		return $this->_parents;
	}
	
	function getExtraParentFolders()
	{
		// let's see if the membership is active
		if ($this->_data->status > 0)
			return $this->_extra_parents;
		
		if (empty($this->_data->extras))
			return $this->_extra_parents;
			
		$this->_db->setQuery("SELECT id, params FROM #__rsmembership_extra_value_shared WHERE `extra_value_id` IN (".$this->_data->extras.") AND `type`='folder' AND `published`='1' ORDER BY `ordering` ASC");
		$parents = $this->_db->loadObjectList();
		foreach ($parents as $parent)
			$this->_extra_parents[$parent->id] = $parent->params;
		
		return $this->_extra_parents;
	}
	
	function getFolders()
	{
		$folders = array();
		
		// let's see if the membership is active
		if ($this->_data->status > 0)
			return $folders;
		
		// check if we are not browsing a folder
		if (is_null($this->_folder))
		{
			// show all the folders associated with this membership
			$all_folders = array();
			foreach ($this->_parents as $folder)
			{
				$element = new stdClass();
				$element->name = substr($folder, 0, -1);
				$element->from = 'membership';
				
				$all_folders[] = $element;
			}
			// show all the folders associated with the extra values of this membership
			foreach ($this->_extra_parents as $folder)
			{
				$element = new stdClass();
				$element->name = substr($folder, 0, -1);
				$element->from = 'extra';
				
				$all_folders[] = $element;
			}
				
			// we don't need a parent since we have the full path in the database
			$parent = '';
		}
		else
		{
			// show the folders in the current folder
			$all_folders = JFolder::folders($this->_folder);
			foreach ($all_folders as $i => $folder)
			{
				$element = new stdClass();
				$element->name = $folder;
				$element->from = $this->getFrom();
				
				$all_folders[$i] = $element;
			}
			// we need the parent to be set as the current folder
			$parent = $this->_folder.DS;
		}
		
		// prepare our folders
		foreach ($all_folders as $folder)
		{
			// membership or extra ?
			$from = $folder->from;
			// get the folder's name
			$folder = $folder->name;
			
			$element = new stdClass();
			$element->from = $from;
			// search for information about the folders (name, description) in the database
			if (!empty($this->db_files[$parent.$folder.DS]))
			{
				// found the information, so attach it
				$db_file = $this->db_files[$parent.$folder.DS];
				$element->name = $db_file->name;
				$element->description = $db_file->description;
				$element->thumb = $db_file->thumb;
			}
			else
			{
				// attach the default information (no description and the name of the folder)
				// a little hack to display only the name of the current folder
				$exp = explode(DS, $folder);
				if (count($exp) > 1)
				{
					$last_folder = end($exp);
					$element->name = $last_folder;
				}
				else
					$element->name = $folder;
				$element->description = '';
			}
			
			if ($from == 'membership')
				$parents = $this->_parents;
			elseif ($from == 'extra')
				$parents = $this->_extra_parents;
			
			// let's see if we are browsing the parent
			$pos = array_search($folder.DS, $parents);
			if ($pos !== false)
			{
				// we are browsing the parent so we need the id of the parent as the path
				$element->fullpath = $pos;
			}
			else
			{
				// we are browsing through the parent so we need the subpath along with the id of the parent
				$folder = str_replace($parents[$this->_parent], '', $parent.$folder);
				$folder = str_replace(DS, '/', $folder);
			
				$element->fullpath = $this->_parent.'|'.$folder;
			}
			
			$folders[] = $element;
		}
		
		return $folders;
	}
	
	function getFiles()
	{
		$files = array();
		
		// let's see if the membership is active
		if ($this->_data->status > 0)
			return $files;
		
		if (!is_null($this->_folder))
		{
			$all_files = JFolder::files($this->_folder);
			foreach ($all_files as $file)
			{
				$element = new stdClass();
				if (!empty($this->db_files[$this->_folder.DS.$file]))
				{
					$db_file = $this->db_files[$this->_folder.DS.$file];
					$element->name = $db_file->name;
					$element->description = $db_file->description;
					$element->thumb = $db_file->thumb;
				}
				else
				{
					$element->name = $file;
					$element->description = '';
				}
				$element->from = $this->getFrom();
				
				if ($element->from == 'membership')
					$parents = $this->_parents;
				elseif ($element->from == 'extra')
					$parents = $this->_extra_parents;
				
				$file = str_replace($parents[$this->_parent], '', $this->_folder.DS.$file);
				$file = str_replace(DS, '/', $file);
				
				$element->fullpath = $this->_parent.'|'.$file;
				$element->published = 1;
				$files[] = $element;
			}
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
		$from = $this->getFrom();
		if ($from == 'membership')
			$parents = $this->_parents;
		elseif ($from == 'extra')
			$parents = $this->_extra_parents;
			
		if (in_array($this->_folder.DS, $parents))
			return '';
		
		$elements = explode(DS, $this->_folder);
		if (count($elements) > 1)
			array_pop($elements);
		
		if (!empty($this->_parent))
		{
			$folder = implode(DS, $elements).DS;
			$folder = str_replace($parents[$this->_parent], $this->_parent.'|', $folder);
			$folder = str_replace(DS, '/', $folder);
		
			return $folder;
		}
		return false;
	}
}
?>