<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class RSMembershipViewElementTerm extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		$task = JRequest::getVar('task','');
		
		$filter_state = $mainframe->getUserStateFromRequest('rsmembership.filter_state', 'filter_state');
		$mainframe->setUserState('rsmembership.filter_state', $filter_state);
		$lists['state']	= JHTML::_('grid.state', $filter_state);
		$this->assignRef('lists', $lists);
		
		$this->assignRef('sortColumn', JRequest::getVar('filter_order','ordering'));
		$this->assignRef('sortOrder', JRequest::getVar('filter_order_Dir','ASC'));
		
		$this->assignRef('terms', $this->get('terms'));
		$this->assignRef('pagination', $this->get('pagination'));
		
		$filter_word = JRequest::getString('search', '');
		$this->assignRef('filter_word', $filter_word);
		
		parent::display($tpl);
	}
}