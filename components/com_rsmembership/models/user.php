<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelUser extends JModel
{
	var $_data = null;
	var $_total = 0;
	var $_query = '';
	var $_pagination = null;
	var $_db = null;
	
	function __construct()
	{
		parent::__construct();
		
		global $mainframe, $option;
		
		$user = JFactory::getUser();
		if ($user->get('guest'))
		{
			$link = JRequest::getURI();
			$link = base64_encode($link);
			$mainframe->redirect('index.php?option=com_user&view=login&return='.$link);
		}
		
		$this->_db = JFactory::getDBO();
	}
	
	function getUser()
	{
		$user = JFactory::getUser();
		$this->_db->setQuery("SELECT * FROM #__rsmembership_users WHERE `user_id`='".$user->get('id')."'");
		return $this->_db->loadObject();
	}
	
	function getConfig()
	{
		return RSMembershipHelper::getConfig();
	}
	
	function save()
	{
		$user = JFactory::getUser();
		$fields = JRequest::getVar('rsm_fields', array(), 'post');
		RSMembership::createUserData($user->get('id'), $fields);
	}
	
	function _bindData($verbose=true)
	{
		$return = true;
		
		$post = JRequest::get('post');
		if (empty($post))
			return false;
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__rsmembership_fields WHERE required='1' AND published='1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		foreach ($fields as $field)
			if (empty($post['rsm_fields'][$field->name]))
			{
				$validation_message = JText::_($field->validation);
				if (empty($validation_message))
					$validation_message = JText::sprintf('RSM_VALIDATION_DEFAULT_ERROR', JText::_($field->label));
					
				if ($verbose)
					JError::raiseWarning(500, $validation_message);
					
				$return = false;
			}
		
		return $return;
	}
}
?>