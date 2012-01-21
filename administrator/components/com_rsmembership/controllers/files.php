<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMembershipControllerFiles extends RSMembershipController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask('apply', 'save');
	}
	
	/**
	 * Display "New" / "Edit"
	 */
	function edit()
	{
		JRequest::setVar('view', 'files');
		JRequest::setVar('layout', 'edit');
		
		// Get the model
		$model = $this->getModel('files');
		
		if (!$model->pathExists())
		{
			JError::raiseWarning(500, JText::_('RSM_NOT_FILE'));
			$this->setRedirect('index.php?option=com_rsmembership&view=files');
		}
		
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
		$model = $this->getModel('files');
		
		// Get the selected items
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		
		$msg = '';
		
		// No items are selected
		if (!is_array($cid) || count($cid) < 1)
			JError::raiseWarning(500, JText::_('SELECT ITEM DELETE'));
		// Try to remove the item
		else
		{
			$model->remove($cid);
			
			$total = count($cid);
			$msg = JText::sprintf('RSM_FILES_DELETED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_rsmembership');
			$cache->clean();
		}
		
		// Redirect
		$this->setRedirect('index.php?option=com_rsmembership&view=files&folder='.$model->getCurrent(), $msg);
	}
	
	/**
	 * Logic to save
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('files');
		
		if (!$model->pathExists())
			$this->setRedirect('index.php?option=com_rsmembership&view=files', JText::_('RSM_NOT_FILE'));
		
		// Save
		$result = $model->save();
		$cid = $model->getId();
		$folder = $model->getFolder();
		
		$task = JRequest::getCmd('task');
		switch($task)
		{
			case 'apply':
				$link = 'index.php?option=com_rsmembership&controller=files&task=edit&cid='.$cid;
				if ($result)
					$this->setRedirect($link, JText::_('RSM_FILE_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_FILE_SAVED_ERROR'));
			break;
		
			case 'save':
				if (empty($folder))
					$link = 'index.php?option=com_rsmembership&view=files';
				else
					$link = 'index.php?option=com_rsmembership&view=files&folder='.$folder;
				if ($result)
					$this->setRedirect($link, JText::_('RSM_FILE_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('RSM_FILE_SAVED_ERROR'));
			break;
		}
	}
	
	function upload()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// Get the model
		$model = $this->getModel('files');
		
		$folder = $model->getCurrent();
		$result = $model->upload();
		
		if ($result)
			$this->setRedirect('index.php?option=com_rsmembership&view=files&folder='.$folder, JText::_('RSM_UPLOADED'));
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=files&folder='.$folder, JText::_('RSM_NOT_UPLOADED'));
	}
	
	function newdir()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the model
		$model = $this->getModel('files');
		
		$dir = JRequest::getVar('dirname', '');
		
		$folder = $model->getCurrent();
		
		if (strlen($dir) > 0)
			$result = $model->newdir($dir);
		else
			$result = false;
		
		if ($result)
			$this->setRedirect('index.php?option=com_rsmembership&view=files&folder='.$folder, JText::_('RSM_DIRECTORY_CREATED'));
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=files&folder='.$folder, JText::_('RSM_DIRECTORY_NOT_CREATED'));
			
	}
	
	function addmembershipfolders()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Get the model
		$model = $this->getModel('files');
		
		$model->addmembershipfolders($cids);
	}
	
	function addsubscriberfiles()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Get the model
		$model = $this->getModel('files');
		
		$model->addsubscriberfiles($cids);
	}
	
	function addextravaluefolders()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Get the model
		$model = $this->getModel('files');
		
		$model->addextravaluefolders($cids);
	}
}
?>