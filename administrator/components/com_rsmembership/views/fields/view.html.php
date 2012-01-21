<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class RSMembershipViewFields extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		JToolBarHelper::title('RSMembership!','rsmembership');
		
		JSubMenuHelper::addEntry(JText::_('RSM_TRANSACTIONS'), 'index.php?option=com_rsmembership&view=transactions');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIPS'), 'index.php?option=com_rsmembership&view=memberships');
		JSubMenuHelper::addEntry(JText::_('RSM_CATEGORIES'), 'index.php?option=com_rsmembership&view=categories');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_EXTRAS'), 'index.php?option=com_rsmembership&view=extras');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_UPGRADES'), 'index.php?option=com_rsmembership&view=upgrades');
		JSubMenuHelper::addEntry(JText::_('RSM_PAYMENT_INTEGRATIONS'), 'index.php?option=com_rsmembership&view=payments');
		JSubMenuHelper::addEntry(JText::_('RSM_FILES'), 'index.php?option=com_rsmembership&view=files');
		JSubMenuHelper::addEntry(JText::_('RSM_FILE_TERMS'), 'index.php?option=com_rsmembership&view=terms');
		JSubMenuHelper::addEntry(JText::_('RSM_USERS'), 'index.php?option=com_rsmembership&view=users');
		JSubMenuHelper::addEntry(JText::_('RSM_FIELDS'), 'index.php?option=com_rsmembership&view=fields', true);
		JSubMenuHelper::addEntry(JText::_('RSM_CONFIGURATION'), 'index.php?option=com_rsmembership&view=configuration');
		JSubMenuHelper::addEntry(JText::_('RSM_UPDATES'), 'index.php?option=com_rsmembership&view=updates');
		
		$task = JRequest::getVar('task','');
		
		if ($task == 'edit')
		{
			JToolBarHelper::title('RSMembership! <small>['.JText::_('RSM_EDIT_FIELD').']</small>','rsmembership');
			
			JToolBarHelper::apply();
			JToolBarHelper::save();
			JToolBarHelper::cancel();
			
			$row = $this->get('field');
			$this->assignRef('row', $row);
			
			$type = array(
				JHTML::_('select.option', 'freetext', JText::_('RSM_FREETEXT')),
				JHTML::_('select.option', 'textbox', JText::_('RSM_TEXTBOX')),
				JHTML::_('select.option', 'textarea', JText::_('RSM_TEXTAREA')),
				JHTML::_('select.option', 'select', JText::_('RSM_SELECT')),
				JHTML::_('select.option', 'multipleselect', JText::_('RSM_MULTIPLESELECT')),
				JHTML::_('select.option', 'checkbox', JText::_('RSM_CHECKBOX')),
				JHTML::_('select.option', 'radio', JText::_('RSM_RADIO')),
				JHTML::_('select.option', 'calendar', JText::_('RSM_CALENDAR')),
			);
			$lists['type'] = JHTML::_('select.genericlist', $type, 'type', '', 'value', 'text', $row->type);
			
			$lists['required'] = JHTML::_('select.booleanlist', 'required', '', $row->required);
			
			$lists['published'] = JHTML::_('select.booleanlist','published','class="inputbox"',$row->published);
			$this->assignRef('lists', $lists);
		}
		else
		{
			JToolBarHelper::addNewX('edit');
			JToolBarHelper::editListX('edit');
			JToolBarHelper::spacer();
			
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::spacer();
			
			JToolBarHelper::deleteList('RSM_CONFIRM_DELETE');
			
			$filter_state = $mainframe->getUserStateFromRequest('rsmembership.filter_state', 'filter_state');
			$mainframe->setUserState('rsmembership.filter_state', $filter_state);
			$lists['state']	= JHTML::_('grid.state', $filter_state);
			$this->assignRef('lists', $lists);
			
			$this->assignRef('sortColumn', JRequest::getVar('filter_order','ordering'));
			$this->assignRef('sortOrder', JRequest::getVar('filter_order_Dir','ASC'));
			
			$this->assignRef('fields', $this->get('fields'));
			$this->assignRef('pagination', $this->get('pagination'));
			
			$filter_word = JRequest::getWord('search', '');
			$this->assignRef('filter_word', $filter_word);
		}
		
		parent::display($tpl);
	}
}