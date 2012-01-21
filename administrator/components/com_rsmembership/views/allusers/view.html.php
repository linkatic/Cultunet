<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pane');

class RSMembershipViewAllusers extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		$this->assignRef('sortColumn', JRequest::getVar('filter_order','name'));
		$this->assignRef('sortOrder', JRequest::getVar('filter_order_Dir','ASC'));
		
		$this->assignRef('users', $this->get('users'));
		$this->assignRef('pagination', $this->get('pagination'));
		
		$filter_word = JRequest::getString('search', '');
		$this->assignRef('filter_word', $filter_word);
		
		parent::display($tpl);
	}
}