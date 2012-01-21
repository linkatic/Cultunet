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

class RSMembershipViewExtravalues extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		if ($tpl == 'shared')
		{
			parent::display($tpl);
			return true;
		}
		
		JToolBarHelper::title('RSMembership!','rsmembership');
		
		JSubMenuHelper::addEntry(JText::_('RSM_TRANSACTIONS'), 'index.php?option=com_rsmembership&view=transactions');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIPS'), 'index.php?option=com_rsmembership&view=memberships');
		JSubMenuHelper::addEntry(JText::_('RSM_CATEGORIES'), 'index.php?option=com_rsmembership&view=categories');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_EXTRAS'), 'index.php?option=com_rsmembership&view=extras', true);
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_UPGRADES'), 'index.php?option=com_rsmembership&view=upgrades');
		JSubMenuHelper::addEntry(JText::_('RSM_PAYMENT_INTEGRATIONS'), 'index.php?option=com_rsmembership&view=payments');
		JSubMenuHelper::addEntry(JText::_('RSM_FILES'), 'index.php?option=com_rsmembership&view=files');
		JSubMenuHelper::addEntry(JText::_('RSM_FILE_TERMS'), 'index.php?option=com_rsmembership&view=terms');
		JSubMenuHelper::addEntry(JText::_('RSM_USERS'), 'index.php?option=com_rsmembership&view=users');
		JSubMenuHelper::addEntry(JText::_('RSM_FIELDS'), 'index.php?option=com_rsmembership&view=fields');
		JSubMenuHelper::addEntry(JText::_('RSM_CONFIGURATION'), 'index.php?option=com_rsmembership&view=configuration');
		JSubMenuHelper::addEntry(JText::_('RSM_UPDATES'), 'index.php?option=com_rsmembership&view=updates');
		
		$task = JRequest::getVar('task','');
		
		if ($task == 'edit')
		{
			JToolBarHelper::title('RSMembership! <small>['.JText::_('RSM_EDIT_MEMBERSHIP_EXTRA_VALUE').']</small>','rsmembership');
			
			JToolBarHelper::apply();
			JToolBarHelper::save();
			JToolBarHelper::cancel();
			
			$params = array();
			$params['startOffset'] = JRequest::getInt('tabposition', 0);
			$tabs =& JPane::getInstance('Tabs',$params,true);
			$this->assignRef('tabs', $tabs);
			
			$this->assignRef('editor', JFactory::getEditor());
			
			$row = $this->get('extravalue');
			$this->assignRef('row', $row);
			
			$lists['published'] = JHTML::_('select.booleanlist','published','class="inputbox"',$row->published);
			$lists['checked'] = JHTML::_('select.booleanlist','checked','class="inputbox"',$row->checked);
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
			JToolBarHelper::divider();
			
			JToolBarHelper::back('Back', "index.php?option=com_rsmembership&view=extras");
			
			$filter_state = $mainframe->getUserStateFromRequest('rsmembership.filter_state', 'filter_state');
			$mainframe->setUserState('rsmembership.filter_state', $filter_state);
			$lists['state']	= JHTML::_('grid.state', $filter_state);
			$this->assignRef('lists', $lists);
			
			$this->assignRef('sortColumn', JRequest::getVar('filter_order','ordering'));
			$this->assignRef('sortOrder', JRequest::getVar('filter_order_Dir','ASC'));
			
			$this->assignRef('extravalues', $this->get('extravalues'));
			$this->assignRef('pagination', $this->get('pagination'));
			
			$this->assignRef('extra_id', $this->get('extraid'));
		}
		
		$this->assign('currency', RSMembershipHelper::getConfig('currency'));
		
		parent::display($tpl);
	}
}