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

class RSMembershipViewShare extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		$membership_id = JRequest::getInt('membership_id');
		$extra_value_id = JRequest::getInt('extra_value_id');
		
		if (!empty($membership_id))
		{
			$this->assign('id', $membership_id);
			$this->assign('what', 'membership_id');
			$this->assign('function', 'addmembershipfolders');
		}
		else
		{
			$this->assign('id', $extra_value_id);
			$this->assign('what', 'extra_value_id');
			$this->assign('function', 'addextravaluefolders');
		}
		
		$layout = JRequest::getVar('layout');
		switch ($layout)
		{
			case 'article':
				$this->assignRef('articles', $this->get('data'));
				$this->assignRef('pagination', $this->get('pagination'));
			break;
			
			case 'module':
				$this->assign('has_patches', RSMembershipHelper::checkPatches($layout));
				
				$this->assignRef('modules', $this->get('data'));
				$this->assignRef('pagination', $this->get('pagination'));
				
				$this->assignRef('sortColumn', JRequest::getVar('filter_order', 'client_id, position, ordering'));
			break;
			
			case 'menu':
				$this->assign('has_patches', RSMembershipHelper::checkPatches($layout));
					
				$this->assignRef('items', $this->get('data'));
				$this->assignRef('pagination', $this->get('pagination'));
				
				$this->assignRef('sortColumn', JRequest::getVar('filter_order', 'menutype, ordering'));
			break;
			
			case 'section':
				$this->assignRef('sections', $this->get('data'));
				$this->assignRef('pagination', $this->get('pagination'));
			break;
			
			case 'category':
				$this->assignRef('categories', $this->get('data'));
				$this->assignRef('pagination', $this->get('pagination'));
			break;
			
			case 'url':
				$row = $this->get('url');
				
				$where = array(
					JHTML::_('select.option', 'backendurl', JText::_('RSM_BACKEND')),
					JHTML::_('select.option', 'frontendurl', JText::_('RSM_FRONTEND'))
				);
				$lists['where'] = JHTML::_('select.genericlist', $where, 'where', '', 'value', 'text', $row->type);
				$lists['published'] = JHTML::_('select.booleanlist','published','class="inputbox"',$row->published);
				
				$this->assignRef('lists', $lists);
				$this->assignRef('row', $row);
			break;
		}
		
		if (!isset($this->sortColumn))
			$this->assignRef('sortColumn', JRequest::getVar('filter_order', 'ordering'));
		$this->assignRef('sortOrder', JRequest::getVar('filter_order_Dir','ASC'));
		
		$filter_word = JRequest::getString('search', '');
		$this->assignRef('filter_word', $filter_word);
		
		parent::display($tpl);
	}
}