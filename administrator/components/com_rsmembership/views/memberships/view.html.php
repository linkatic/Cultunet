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

class RSMembershipViewMemberships extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		if ($tpl == 'shared' || $tpl == 'files')
		{
			parent::display($tpl);
			return true;
		}
		
		$task = JRequest::getVar('task','');
		
		JToolBarHelper::title('RSMembership!','rsmembership');
		
		JSubMenuHelper::addEntry(JText::_('RSM_TRANSACTIONS'), 'index.php?option=com_rsmembership&view=transactions');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIPS'), 'index.php?option=com_rsmembership&view=memberships', true);
		JSubMenuHelper::addEntry(JText::_('RSM_CATEGORIES'), 'index.php?option=com_rsmembership&view=categories');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_EXTRAS'), 'index.php?option=com_rsmembership&view=extras');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_UPGRADES'), 'index.php?option=com_rsmembership&view=upgrades');
		JSubMenuHelper::addEntry(JText::_('RSM_PAYMENT_INTEGRATIONS'), 'index.php?option=com_rsmembership&view=payments');
		JSubMenuHelper::addEntry(JText::_('RSM_FILES'), 'index.php?option=com_rsmembership&view=files');
		JSubMenuHelper::addEntry(JText::_('RSM_FILE_TERMS'), 'index.php?option=com_rsmembership&view=terms');
		JSubMenuHelper::addEntry(JText::_('RSM_USERS'), 'index.php?option=com_rsmembership&view=users');
		JSubMenuHelper::addEntry(JText::_('RSM_FIELDS'), 'index.php?option=com_rsmembership&view=fields');
		JSubMenuHelper::addEntry(JText::_('RSM_CONFIGURATION'), 'index.php?option=com_rsmembership&view=configuration');
		JSubMenuHelper::addEntry(JText::_('RSM_UPDATES'), 'index.php?option=com_rsmembership&view=updates');
		
		if ($task == 'edit')
		{
			JToolBarHelper::title('RSMembership! <small>['.JText::_('RSM_EDIT_MEMBERSHIP').']</small>','rsmembership');
			
			JToolBarHelper::apply();
			JToolBarHelper::save();
			JToolBarHelper::cancel();
			
			$params = array();
			$params['startOffset'] = JRequest::getInt('tabposition', 0);
			$tabs =& JPane::getInstance('Tabs',$params,true);
			$this->assignRef('tabs', $tabs);
			
			$params = array();
			$params['allowAllClose'] = true;
			$pane =& JPane::getInstance('Sliders', $params);
			$this->assignRef('pane', $pane);

			$this->assignRef('editor', JFactory::getEditor());
			
			$row = $this->get('membership');
			$this->assignRef('row', $row);
			
			$lists['categories'] = JHTML::_('select.genericlist', $this->get('categories'), 'category_id', '', 'id', 'name', $row->category_id);
			
			$lists['published'] = JHTML::_('select.booleanlist','published','class="inputbox"',$row->published);
			
			$lists['use_renewal_price'] = JHTML::_('select.booleanlist','use_renewal_price','class="inputbox" onclick="rsm_enable_renewal_price(this.value)"',$row->use_renewal_price);
			$lists['use_coupon'] = JHTML::_('select.booleanlist','use_coupon','class="inputbox" onclick="rsm_enable_coupon(this.value)"',$row->use_coupon);
			
			$period_type = array(
				JHTML::_('select.option', 'h', JText::_('RSM_HOURS')),
				JHTML::_('select.option', 'd', JText::_('RSM_DAYS')),
				JHTML::_('select.option', 'm', JText::_('RSM_MONTHS')),
				JHTML::_('select.option', 'y', JText::_('RSM_YEARS'))
			);
			$lists['period_type'] = JHTML::_('select.genericlist', $period_type, 'period_type', '', 'value', 'text', $row->period_type);
			
			$lists['use_trial_period'] = JHTML::_('select.booleanlist','use_trial_period','class="inputbox" onclick="rsm_enable_trial(this.value)"',$row->use_trial_period);
			$lists['trial_period_type'] = JHTML::_('select.genericlist', $period_type, 'trial_period_type', !$row->use_trial_period ? 'disabled="disabled"' : '', 'value', 'text', $row->trial_period_type);
			
			$lists['recurring'] = JHTML::_('select.booleanlist','recurring','class="inputbox"',$row->recurring);
			$lists['unique'] = JHTML::_('select.booleanlist','unique','class="inputbox"',$row->unique);
			$lists['no_renew'] = JHTML::_('select.booleanlist','no_renew','class="inputbox"',$row->no_renew);
			
			$activation = array(
				JHTML::_('select.option', '0', JText::_('RSM_ACTIVATION_MANUAL')),
				JHTML::_('select.option', '1', JText::_('RSM_ACTIVATION_AUTO')),
				JHTML::_('select.option', '2', JText::_('RSM_ACTIVATION_INSTANT')),
			);
			$lists['activation'] = JHTML::_('select.genericlist', $activation, 'activation', '', 'value', 'text', $row->activation);
			
			$all_extras = $this->get('extras');
			$extras = array();
			foreach ($all_extras as $extra)
				$extras[] = JHTML::_('select.option', $extra->id, $extra->name);
			$lists['extras'] = JHTML::_('select.genericlist', $extras, 'extras[]', 'multiple="multiple" size="10"', 'value', 'text', $row->extras, 'extras');
			$this->assign('hasExtra', count($extras) > 0);
			
			$modes = array(
				JHTML::_('select.option', '0', JText::_('RSM_PLAIN_TEXT')),
				JHTML::_('select.option', '1', JText::_('RSM_HTML'))
			);
			$lists['user_email_mode'] = JHTML::_('select.radiolist', $modes, 'user_email_mode', '', 'value', 'text', $row->user_email_mode);
			$lists['admin_email_mode'] = JHTML::_('select.radiolist', $modes, 'admin_email_mode', '', 'value', 'text', $row->admin_email_mode);
			
			$action = array(
			JHTML::_('select.option', '0', JText::_('RSM_MEMBERSHIP_SHOW_THANKYOU')),
			JHTML::_('select.option', '1', JText::_('RSM_MEMBERSHIP_REDIRECT')),
			);
			$lists['action'] = JHTML::_('select.genericlist', $action, 'action', '', 'value', 'text', $row->action);
			
			$all_terms = $this->get('terms');
			$terms = array();
			$terms[] = JHTML::_('select.option', 0, JText::_('RSM_NO_TERMS_SELECTED'));
			foreach ($all_terms as $term)
				$terms[] = JHTML::_('select.option', $term->id, $term->name);
				
			$lists['terms'] = JHTML::_('select.genericlist', $terms, 'term_id', '', 'value', 'text', $row->term_id, 'term_id');
			
			$acl					=& JFactory::getACL();
			$gtree 					= $acl->get_group_children_tree( null, 'USERS', false );
			foreach ($gtree as $i => $item)
				if ($item->value == 29 || $item->value == 30)
					$gtree[$i]->disable = true;
					
			$lists['gid_enable'] 		  	  = JHTML::_('select.booleanlist','gid_enable','class="inputbox" onclick="rsm_enable_gid(this.value)"',$row->gid_enable);
			$lists['gid_subscribe'] 		  = JHTML::_('select.genericlist',   $gtree, 'gid_subscribe', 'size="10"'.(!$row->gid_enable ? ' disabled="disabled"' : ''), 'value', 'text', $row->gid_subscribe);
			$lists['gid_expire'] 			  = JHTML::_('select.genericlist',   $gtree, 'gid_expire', 'size="10"'.(!$row->gid_enable ? ' disabled="disabled"' : ''), 'value', 'text', $row->gid_expire);
			$lists['disable_expired_account'] = JHTML::_('select.booleanlist','disable_expired_account','class="inputbox"',$row->disable_expired_account);
			
			$this->assignRef('lists', $lists);
		}
		else
		{
			JToolBarHelper::addNewX('edit');
			JToolBarHelper::editListX('edit');
			JToolBarHelper::spacer();
			
			JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', 'Duplicate' );
			JToolBarHelper::spacer();
			
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::spacer();
			
			JToolBarHelper::deleteList('RSM_CONFIRM_DELETE');
			
			$lists['categories'] = JHTML::_('select.genericlist', $this->get('categories'), 'category_id', 'onchange="submitform();"', 'id', 'name', $mainframe->getUserStateFromRequest('rsmembership.category_id', 'category_id'));
			
			$filter_state = $mainframe->getUserStateFromRequest('rsmembership.filter_state', 'filter_state');
			$mainframe->setUserState('rsmembership.filter_state', $filter_state);
			$lists['state']	= JHTML::_('grid.state', $filter_state);
			$this->assignRef('lists', $lists);
			
			$this->assignRef('sortColumn', JRequest::getVar('filter_order','ordering'));
			$this->assignRef('sortOrder', JRequest::getVar('filter_order_Dir','ASC'));
			
			$this->assignRef('memberships', $this->get('memberships'));
			$this->assignRef('pagination', $this->get('pagination'));
			
			$filter_word = JRequest::getString('search', '');
			$this->assignRef('filter_word', $filter_word);
		}
		
		$this->assign('currency', RSMembershipHelper::getConfig('currency'));
		
		parent::display($tpl);
	}
}