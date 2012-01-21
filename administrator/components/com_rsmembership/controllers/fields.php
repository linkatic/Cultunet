<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMembershipControllerFields extends RSMembershipController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask('orderup', 'move');
		$this->registerTask('orderdown', 'move');
		$this->registerTask('apply', 'save');
		$this->registerTask('publish', 'changestatus');
		$this->registerTask('unpublish', 'changestatus');
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
	}
	
	/**
	 * Display "New" / "Edit"
	 */
	function edit()
	{
		JRequest::setVar('view', 'fields');
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
		$row =& JTable::getInstance('RSMembership_Fields','Table');
		
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
		$this->setRedirect('index.php?option=com_rsmembership&view=fields', JText::_('RSM_FIELDS_ORDERED'));
	}
	
	/**
	 * Logic to move
	 */
	function move() 
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the table instance
		$row =& JTable::getInstance('RSMembership_Fields','Table');
		
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
		$this->setRedirect('index.php?option=com_rsmembership&view=fields');
	}
	
	/**
	 * Logic to publish/unpublish
	 */
	function changestatus()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the model
		$model = $this->getModel('fields');
		
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
				$msg = JText::sprintf('RSM_FIELDS_PUBLISHED', $total);
			else
				$msg = JText::sprintf('RSM_FIELDS_UNPUBLISHED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=fields', $msg);
	}
	
	/**
	 * Logic to remove
	 */
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('fields');
		
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
			$msg = JText::sprintf('RSM_FIELDS_DELETED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=fields', $msg);
	}
	
	/**
	 * Logic to save
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('fields');
		
		// Save
		$result = $model->save();
		$cid = $model->getId();
		
		$task = JRequest::getCmd('task');
		switch($task)
		{
			case 'apply':
				$link = 'index.php?option=com_rsmembership&controller=fields&task=edit&cid='.$cid;
				if ($result)
					$this->setRedirect($link, JText::_('RSM_FIELD_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_FIELD_SAVED_ERROR'));
			break;
		
			case 'save':
				$link = 'index.php?option=com_rsmembership&view=fields';
				if ($result)
					$this->setRedirect($link, JText::_('RSM_FIELD_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_FIELD_SAVED_ERROR'));
			break;
		}
	}
}
?>