<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMembershipController extends JController
{
	var $_db;
	
	function __construct()
	{
		parent::__construct();
		$document =& JFactory::getDocument();
		// Add the css stylesheet
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_rsmembership/assets/css/rsmembership.css');
		
		// Set the database object
		$this->_db =& JFactory::getDBO();
		
		RSMembershipHelper::readConfig();
	}
	
	/**
	 * Display the view
	 */
	function display()
	{
		parent::display();
	}
	
	function saveRegistration()
	{
		$code = JRequest::getVar('global_register_code');
		$code = $this->_db->getEscaped($code);
		if (!empty($code))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$code."' WHERE `name`='global_register_code'");
			$this->_db->query();
			$this->setRedirect('index.php?option=com_rsmembership&view=updates', JText::_('RSM_LICENSE_SAVED'));
		}
		else
			$this->setRedirect('index.php?option=com_rsmembership&view=configuration');
	}
	
	function ajax_addmembershipfolders()
	{
		JRequest::setVar('view', 'memberships');
		JRequest::setVar('layout', 'edit_shared');
		
		parent::display();
	}
	
	function ajax_addsubscriberfiles()
	{
		JRequest::setVar('view', 'memberships');
		JRequest::setVar('layout', 'edit_files');
		
		parent::display();
	}
	
	function ajax_addextravaluefolders()
	{
		JRequest::setVar('view', 'extravalues');
		JRequest::setVar('layout', 'edit_shared');
		
		parent::display();
	}
	
	function ajax_addmemberships()
	{
		JRequest::setVar('view', 'users');
		JRequest::setVar('layout', 'edit_memberships');
		
		parent::display();
	}
	
	function ajax_date()
	{
		$date = JRequest::getInt('date');
		echo date(RSMembershipHelper::getConfig('date_format'), $date);
		exit();
	}
}
?>