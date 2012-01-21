<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMembershipControllerShare extends RSMembershipController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function addmembershiparticles()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addmembershiparticles($cids);
		jexit();
	}
	
	function addextravaluearticles()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addextravaluearticles($cids);
		jexit();
	}
	
	function addmembershipsections()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addmembershipsections($cids);
		jexit();
	}
	
	function addextravaluesections()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addextravaluesections($cids);
		jexit();
	}
	
	function addmembershipcategories()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addmembershipcategories($cids);
		jexit();
	}
	
	function addextravaluecategories()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addextravaluecategories($cids);
		jexit();
	}
	
	function addmembershipurl()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cid = JRequest::getInt('cid');
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addmembershipurl($cid);
		jexit();
	}
	
	function addextravalueurl()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cid = JRequest::getInt('cid');
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addextravalueurl($cid);
		jexit();
	}
	
	function addmembershipmodules()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cids);
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addmembershipmodules($cids);
		jexit();
	}
	
	function addextravaluemodules()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cids);
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addextravaluemodules($cids);
		jexit();
	}
	
	function addmembershipmenus()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cids);
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addmembershipmenus($cids);
		jexit();
	}
	
	function addextravaluemenus()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get the selected items
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cids);
		
		// Get the model
		$model = $this->getModel('share');
		
		$model->addextravaluemenus($cids);
		jexit();
	}
}
?>