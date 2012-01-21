<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMembershipControllerUsers extends RSMembershipController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask('apply', 'save');
		$this->registerTask('membershipapply', 'membershipsave');
	}
	
	/**
	 * Display "New" / "Edit"
	 */
	function edit()
	{
		JRequest::setVar('view', 'users');
		JRequest::setVar('layout', 'edit');
		
		parent::display();
	}
	
	function editmembership()
	{
		JRequest::setVar('view', 'users');
		JRequest::setVar('layout', 'editmembership');
		
		parent::display();
	}
	
	/**
	 * Logic to remove
	 */
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('users');
		
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
			$msg = JText::sprintf('RSM_USERS_DELETED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=users', $msg);
	}
	
	/**
	 * Logic to save
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('users');
		
		// Save
		$result = $model->save();
		$cid = $model->getId();
		
		$task = JRequest::getCmd('task');
		switch($task)
		{
			case 'apply':
				$tabposition = JRequest::getInt('tabposition', 0);
				$link = 'index.php?option=com_rsmembership&controller=users&task=edit&cid='.$cid.'&tabposition='.$tabposition;
				if ($result)
					$this->setRedirect($link, JText::_('RSM_USER_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_USER_SAVED_ERROR'));
			break;
		
			case 'save':
				if ($result)
				{
					$link = 'index.php?option=com_rsmembership&view=users';
					$this->setRedirect($link, JText::_('RSM_USER_SAVED_OK'));
				}
				else
				{
					$link = 'index.php?option=com_rsmembership&controller=users&task=edit&cid='.$cid;
					$this->setRedirect($link, JText::_('RSM_USER_SAVED_ERROR'));
				}
			break;
		}
	}
	
	/**
	 * Logic to save membership
	 */
	function membershipSave()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('users');
		
		// Save
		$result = $model->membershipSave();
		$cid = $model->getId();
		
		$task = JRequest::getCmd('task');
		switch($task)
		{
			case 'applymembership':
				$link = 'index.php?option=com_rsmembership&controller=users&task=editmembership&tmpl=component&cid='.$cid;
				if ($result)
					$this->setRedirect($link, JText::_('RSM_MEMBERSHIP_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_MEMBERSHIP_SAVED_ERROR'));
			break;
		
			case 'savemembership':
				if ($result)
				{
					$link = 'index.php?option=com_rsmembership&view=users';
					$this->setRedirect($link, JText::_('RSM_MEMBERSHIP_SAVED_OK'));
				}
				else
				{
					$link = 'index.php?option=com_rsmembership&controller=users&task=editmembership&tmpl=component&cid='.$cid;
					$this->setRedirect($link, JText::_('RSM_MEMBERSHIP_SAVED_ERROR'));
				}
			break;
		}
	}
	
	/**
	 * Logic to remove
	 */
	function membershipRemove()
	{
		// Check for request forgeries
		JRequest::checkToken('get') or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('users');
		
		// Get the selected items
		$cid = JRequest::getVar('cid_memberships', array(0), 'request', 'array');

		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		$msg = '';
		
		// No items are selected
		if (!is_array($cid) || count($cid) < 1)
			JError::raiseWarning(500, JText::_('SELECT ITEM DELETE'));
		// Try to remove the item
		else
		{
			$model->membershipRemove($cid);
			
			$total = count($cid);
			$msg = JText::sprintf('RSM_MEMBERSHIPS_DELETED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Redirect
		$cid = JRequest::getInt('cid');
		$tabposition = JRequest::getInt('tabposition', 0);
		$this->setRedirect('index.php?option=com_rsmembership&controller=users&task=edit&cid='.$cid.'&tabposition='.$tabposition, $msg);
	}
}
?>