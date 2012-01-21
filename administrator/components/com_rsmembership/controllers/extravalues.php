<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMembershipControllerExtraValues extends RSMembershipController
{
	function __construct()
	{
		parent::__construct();
		
		// Extra Value Tasks
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
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
	}
	
	/**
	 * Display "New" / "Edit"
	 */
	function edit()
	{
		JRequest::setVar('view', 'extravalues');
		JRequest::setVar('layout', 'edit');
		parent::display();
	}
	
	/**
	 * Save the ordering
	 */
	function saveOrder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Extra_Values','Table');
		
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
		
		$extra_id = JRequest::getInt('extra_id');
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=extravalues&extra_id='.$extra_id, JText::_('RSM_EXTRA_VALUES_ORDERED'));
	}
	
	/**
	 * Logic to move
	 */
	function move() 
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Extra_Values','Table');
		
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
	
		$extra_id = JRequest::getInt('extra_id');
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=extravalues&extra_id='.$extra_id);
	}
	
	/**
	 * Logic to publish/unpublish
	 */
	function changestatus()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the model
		$model = $this->getModel('extravalues');
		
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
				$msg = JText::sprintf('RSM_EXTRA_VALUES_PUBLISHED', $total);
			else
				$msg = JText::sprintf('RSM_EXTRA_VALUES_UNPUBLISHED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		$extra_id = JRequest::getInt('extra_id');
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=extravalues&extra_id='.$extra_id, $msg);
	}
	
	/**
	 * Logic to remove
	 */
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('extravalues');
		
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
			$msg = JText::sprintf('RSM_EXTRA_VALUES_DELETED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		$extra_id = JRequest::getInt('extra_id');
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=extravalues&extra_id='.$extra_id, $msg);
	}
	
	/**
	 * Logic to save
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('extravalues');
		
		// Get the selected items
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		// Can move only one element
		if (is_array($cid))	$cid = $cid[0];
		
		// Save
		$result = $model->save($cid);
		$cid = $model->getId();
		
		$extra_id = JRequest::getInt('extra_id');
		
		$task = JRequest::getCmd('task');
		switch($task)
		{
			case 'apply':
				$tabposition = JRequest::getInt('tabposition', 0);
				$link = 'index.php?option=com_rsmembership&controller=extravalues&task=edit&cid='.$cid.'&tabposition='.$tabposition;
				if ($result)
					$this->setRedirect($link, JText::_('RSM_EXTRA_VALUE_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_EXTRA_VALUE_SAVED_ERROR'));
			break;
		
			case 'save':
				$link = 'index.php?option=com_rsmembership&view=extravalues&extra_id='.$extra_id;
				if ($result)
					$this->setRedirect($link, JText::_('RSM_EXTRA_VALUE_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_EXTRA_VALUE_SAVED_ERROR'));
			break;
		}
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
		$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
		
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
		if (!empty($row->extra_value_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=extravalues&task=edit&cid='.$row->extra_value_id.'&tabposition='.$tabposition, JText::_('RSM_EXTA_VALUE_FILES_ORDERED'));
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
		$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
		
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
		if (!empty($row->extra_value_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=extravalues&task=edit&cid='.$row->extra_value_id.'&tabposition='.$tabposition);
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
		$model = $this->getModel('extravalues');
		
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
				$msg = JText::sprintf('RSM_EXTRA_VALUE_FILES_PUBLISHED', $total);
			else
				$msg = JText::sprintf('RSM_EXTRA_VALUE_FILES_UNPUBLISHED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
		$row->load($cid[0]);
		
		$tabposition = JRequest::getInt('tabposition', 0);
		// Redirect
		if (!empty($row->extra_value_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=extravalues&task=edit&cid='.$row->extra_value_id.'&tabposition='.$tabposition, $msg);
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
	
	// Folder - Remove
	function foldersRemove()
	{
		// Check for request forgeries
		JRequest::checkToken('get') or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('extravalues');
		
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
			$row =& JTable::getInstance('RSMembership_Extra_Value_Shared','Table');
			$row->load($cid[0]);
			
			if (!empty($row->extra_value_id))
			{
				$model->foldersRemove($cid);
			
				$total = count($cid);
				$msg = JText::sprintf('RSM_EXTRA_VALUE_FILES_DELETED', $total);
			
				// Clean the cache, if any
				$cache =& JFactory::getCache('com_rsmembership');
				$cache->clean();
			}
		}

		$tabposition = JRequest::getInt('tabposition', 0);
		// Redirect
		if (!empty($row->extra_value_id))
			$this->setRedirect('index.php?option=com_rsmembership&controller=extravalues&task=edit&cid='.$row->extra_value_id.'&tabposition='.$tabposition, $msg);
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=memberships');
	}
}
?>