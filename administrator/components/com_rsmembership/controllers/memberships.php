<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMembershipControllerMemberships extends RSMembershipController
{
	function __construct()
	{
		parent::__construct();
		
		// Membership Tasks
		$this->registerTask('orderup', 'move');
		$this->registerTask('orderdown', 'move');
		$this->registerTask('apply', 'save');
		$this->registerTask('publish', 'changestatus');
		$this->registerTask('unpublish', 'changestatus');
		
		// Folder Tasks (Files)
		$this->registerTask('foldersorderup', 'foldersmove');
		$this->registerTask('foldersorderdown', 'foldersmove');
		$this->registerTask('folderspublish', 'folderschangestatus');
		$this->registerTask('foldersunpublish', 'folderschangestatus');
		
		// Attachment Tasks (Subscriber Emails)
		$this->registerTask('attachmentsorderup', 'attachmentsmove');
		$this->registerTask('attachmentsorderdown', 'attachmentsmove');
		$this->registerTask('attachmentspublish', 'attachmentschangestatus');
		$this->registerTask('attachmentsunpublish', 'attachmentschangestatus');
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
	}
	
	function cancel()
	{
		$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	/**
	 * Membership Tasks
	 */
	// Membership - Edit
	function edit()
	{
		JRequest::setVar('view', 'memberships');
		JRequest::setVar('layout', 'edit');
		
		parent::display();
	}
	
	// Membership - Save Ordering
	function saveOrder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Memberships','Table');
		
		// Get the selected items
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		
		// Get the ordering
		$order = JRequest::getVar('order', array (0), 'post', 'array');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		// Load each element of the array
		for ($i=0;$i<count($cid);$i++)
		{
			// Load the item
			$row->load($cid[$i]);
			
			// Set the new ordering only if different
			if ($row->ordering != $order[$i])
			{	
				$row->ordering = $order[$i];
				if (!$row->store()) 
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=memberships', JText::_('RSM_MEMBERSHIPS_ORDERED'));
	}
	
	// Membership - Move Up/Down
	function move() 
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Memberships','Table');
		
		// Get the selected items
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		
		// Get the task
		$task = JRequest::getCmd('task');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		// Set the direction to move
		$direction = $task == 'orderup' ? -1 : 1;
		
		// Can move only one element
		if (is_array($cid))	$cid = $cid[0];
		
		// Load row
		if (!$row->load($cid)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Move
		$row->move($direction);
	
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	// Membership - Publish/Unpublish
	function changestatus()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the model
		$model = $this->getModel('memberships');
		
		// Get the selected items
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		// Get the task
		$task = JRequest::getCmd('task');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		$msg = '';
		
		// No items are selected
		if (!is_array($cid) || count($cid) < 1)
			JError::raiseWarning(500, JText::_('SELECT ITEM PUBLISH'));
		// Try to publish the item
		else
		{
			$value = $task == 'publish' ? 1 : 0;
			if (!$model->publish($cid, $value))
				JError::raiseError(500, $model->getError());

			$total = count($cid);
			if ($value)
				$msg = JText::sprintf('RSM_MEMBERSHIPS_PUBLISHED', $total);
			else
				$msg = JText::sprintf('RSM_MEMBERSHIPS_UNPUBLISHED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=memberships', $msg);
	}
	
	// Membership - Remove
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('memberships');
		
		// Get the selected items
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		$msg = '';
		
		// No items are selected
		if (!is_array($cid) || count($cid) < 1)
			JError::raiseWarning(500, JText::_('SELECT ITEM DELETE'));
		// Try to remove the item
		else
		{
			$model->remove($cid);
			
			$total = count($cid);
			$msg = JText::sprintf('RSM_MEMBERSHIPS_DELETED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=memberships', $msg);
	}
	
	// Membership - Save
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('memberships');
		
		// Save
		$result = $model->save();
		$cid = $model->getId();
		
		$task = JRequest::getCmd('task');
		switch($task)
		{
			case 'apply':
				$tabposition = JRequest::getInt('tabposition', 0);
				$link = 'index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$cid.'&tabposition='.$tabposition;
				if ($result)
					$this->setRedirect($link, JText::_('RSM_MEMBERSHIP_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_MEMBERSHIP_SAVED_ERROR'));
			break;
		
			case 'save':
				$link = 'index.php?option=com_rsmembership&view=memberships';
				if ($result)
					$this->setRedirect($link, JText::_('RSM_MEMBERSHIP_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_MEMBERSHIP_SAVED_ERROR'));
			break;
		}
	}
	
	// Membership - Copy
	function copy()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		$this->_db->setQuery("SELECT id FROM #__rsmembership_memberships WHERE id IN (".implode(",",$cid).")");
		$memberships = $this->_db->loadObjectList();
		
		// Get the model
		$model = $this->getModel('memberships');
			
		if (!empty($memberships))
		{
			foreach ($memberships as $membership)
				$model->copy($membership->id);
			
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships', JText::_('RSM_MEMBERSHIP_COPIED_OK'));
		}
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships', JText::_('RSM_MEMBERSHIP_COPIED_ERROR'));
	}
	
	/**
	 * Attachment Tasks
	 */
	 
	// Attachment - Save Ordering
	function attachmentsSaveOrder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Membership_Attachments','Table');
		
		// Get the selected items
		$cid = JRequest::getVar('cid_files', array(0), 'post', 'array');
		
		// Get the ordering
		$order = JRequest::getVar('order', array (0), 'post', 'array');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		// Load each element of the array
		for ($i=0;$i<count($cid);$i++)
		{
			// Load the item
			$row->load($cid[$i]);
			
			// Set the new ordering only if different
			if ($row->ordering != $order[$i])
			{	
				$row->ordering = $order[$i];
				if (!$row->store()) 
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		
		// Redirect
		$tabposition = JRequest::getInt('tabposition', 0);
		if (!empty($row->membership_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$row->membership_id.'&tabposition='.$tabposition, JText::_('RSM_MEMBERSHIP_ATTACHMENTS_ORDERED'));
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	// Attachment - Move Up/Down
	function attachmentsMove() 
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Membership_Attachments','Table');
		
		// Get the selected items
		$cid = JRequest::getVar('cid_files', array(0), 'post', 'array');
		
		// Get the task
		$task = JRequest::getCmd('task');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		// Set the direction to move
		$direction = $task == 'attachmentsorderup' ? -1 : 1;
		
		// Can move only one element
		if (is_array($cid))	$cid = $cid[0];
		
		// Load row
		if (!$row->load($cid)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Move
		$row->move($direction);
	
		$tabposition = JRequest::getInt('tabposition', 0);
		// Redirect
		if (!empty($row->membership_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$row->membership_id.'&tabposition='.$tabposition);
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	// Attachment - Publish/Unpublish
	function attachmentsChangestatus()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		
		// Get the model
		$model = $this->getModel('memberships');
		
		// Get the selected items
		$cid = JRequest::getVar('cid_files', array(0), 'post', 'array');

		// Get the task
		$task = JRequest::getCmd('task');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		$msg = '';
		
		// No items are selected
		if (!is_array($cid) || count($cid) < 1)
			JError::raiseWarning(500, JText::_('SELECT ITEM PUBLISH'));
		// Try to publish the item
		else
		{
			$value = $task == 'attachmentspublish' ? 1 : 0;
			if (!$model->attachmentsPublish($cid, $value))
				JError::raiseError(500, $model->getError());

			$total = count($cid);
			if ($value)
				$msg = JText::sprintf('RSM_MEMBERSHIP_ATTACHMENTS_PUBLISHED', $total);
			else
				$msg = JText::sprintf('RSM_MEMBERSHIP_ATTACHMENTS_UNPUBLISHED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Membership_Attachments','Table');
		$row->load($cid[0]);
		
		// Redirect
		$tabposition = JRequest::getInt('tabposition', 0);
		if (!empty($row->membership_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$row->membership_id.'&tabposition='.$tabposition, $msg);
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	// Attachment - Remove
	function attachmentsRemove()
	{
		// Check for request forgeries
		JRequest::checkToken('get') or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('memberships');
		
		// Get the selected items
		$cid = JRequest::getVar('cid_files', array(0), 'request', 'array');

		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		$msg = '';
		
		// No items are selected
		if (!is_array($cid) || count($cid) < 1)
			JError::raiseWarning(500, JText::_('SELECT ITEM DELETE'));
		// Try to remove the item
		else
		{
			$row =& JTable::getInstance('RSMembership_Membership_Attachments','Table');
			$row->load($cid[0]);
			
			if (!empty($row->membership_id))
			{
				$model->attachmentsRemove($cid);
			
				$total = count($cid);
				$msg = JText::sprintf('RSM_MEMBERSHIP_ATTACHMENTS_DELETED', $total);
			
				// Clean the cache, if any
				$cache =& JFactory::getCache('com_rsmembership');
				$cache->clean();
			}
		}
		
		$tabposition = JRequest::getInt('tabposition', 0);
		// Redirect
		if (!empty($row->membership_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$row->membership_id.'&tabposition='.$tabposition, $msg);
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	/**
	 * Folder Tasks
	 */
	
	// Folder - Save Ordering
	function foldersSaveOrder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
		
		// Get the selected items
		$cid = JRequest::getVar('cid_folders', array(0), 'post', 'array');
		
		// Get the ordering
		$order = JRequest::getVar('order', array (0), 'post', 'array');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		// Load each element of the array
		for ($i=0;$i<count($cid);$i++)
		{
			// Load the item
			$row->load($cid[$i]);
			
			// Set the new ordering only if different
			if ($row->ordering != $order[$i])
			{	
				$row->ordering = $order[$i];
				if (!$row->store()) 
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		
		// Redirect
		$tabposition = JRequest::getInt('tabposition', 0);
		if (!empty($row->membership_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$row->membership_id.'&tabposition='.$tabposition, JText::_('RSM_MEMBERSHIP_FILES_ORDERED'));
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	// Folder - Move Up/Down
	function foldersMove() 
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
		
		// Get the selected items
		$cid = JRequest::getVar('cid_folders', array(0), 'post', 'array');
		
		// Get the task
		$task = JRequest::getCmd('task');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		// Set the direction to move
		$direction = $task == 'foldersorderup' ? -1 : 1;
		
		// Can move only one element
		if (is_array($cid))	$cid = $cid[0];
		
		// Load row
		if (!$row->load($cid)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Move
		$row->move($direction);
	
		$tabposition = JRequest::getInt('tabposition', 0);
		// Redirect
		if (!empty($row->membership_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$row->membership_id.'&tabposition='.$tabposition);
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	// Folder - Publish/Unpublish
	function foldersChangestatus()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		
		// Get the model
		$model = $this->getModel('memberships');
		
		// Get the selected items
		$cid = JRequest::getVar('cid_folders', array(0), 'post', 'array');

		// Get the task
		$task = JRequest::getCmd('task');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		$msg = '';
		
		// No items are selected
		if (!is_array($cid) || count($cid) < 1)
			JError::raiseWarning(500, JText::_('SELECT ITEM PUBLISH'));
		// Try to publish the item
		else
		{
			$value = $task == 'folderspublish' ? 1 : 0;
			if (!$model->foldersPublish($cid, $value))
				JError::raiseError(500, $model->getError());

			$total = count($cid);
			if ($value)
				$msg = JText::sprintf('RSM_MEMBERSHIP_FILES_PUBLISHED', $total);
			else
				$msg = JText::sprintf('RSM_MEMBERSHIP_FILES_UNPUBLISHED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
		$row->load($cid[0]);
		
		// Redirect
		$tabposition = JRequest::getInt('tabposition', 0);
		if (!empty($row->membership_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$row->membership_id.'&tabposition='.$tabposition, $msg);
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	// Folder - Remove
	function foldersRemove()
	{
		// Check for request forgeries
		JRequest::checkToken('get') or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('memberships');
		
		// Get the selected items
		$cid = JRequest::getVar('cid_folders', array(0), 'request', 'array');

		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		$msg = '';
		
		// No items are selected
		if (!is_array($cid) || count($cid) < 1)
			JError::raiseWarning(500, JText::_('SELECT ITEM DELETE'));
		// Try to remove the item
		else
		{
			$row =& JTable::getInstance('RSMembership_Membership_Shared','Table');
			$row->load($cid[0]);
			
			if (!empty($row->membership_id))
			{
				$model->foldersRemove($cid);
			
				$total = count($cid);
				$msg = JText::sprintf('RSM_MEMBERSHIP_FILES_DELETED', $total);
			
				// Clean the cache, if any
				$cache =& JFactory::getCache('com_rsmembership');
				$cache->clean();
			}
		}
		
		$tabposition = JRequest::getInt('tabposition', 0);
		// Redirect
		if (!empty($row->membership_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$row->membership_id.'&tabposition='.$tabposition, $msg);
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	function element()
	{
		JRequest::setVar('view', 'elementmembership');
		JRequest::setVar('layout', 'default');
		
		$view =& $this->getView('elementmembership', 'html');
		$view->setModel($this->getModel('memberships'), true);
		
		parent::display();
	}
}
?>