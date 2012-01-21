<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMembershipViewMymembership extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		$pathway =& $mainframe->getPathway();
		$pathway->addItem(JText::_('RSM_MEMBERSHIP'), '');
		
		$params = clone($mainframe->getParams('com_rsmembership'));
		$this->assignRef('params', $params);
		
		$terms = $this->get('terms');
		if (!empty($terms))
		{
			$this->assignRef('terms', $terms);
			$this->assignRef('action', JRequest::getURI());
			parent::display('terms');
		}
		else
		{
			$model =& $this->getModel();
			$this->assignRef('cid', $this->get('cid'));
			$this->assignRef('membership', $this->get('membership'));
			$this->assignRef('books', $model->_getBooksRelMembership());
			$this->assignRef('membershipterms', $this->get('membershipterms'));
			$this->assignRef('boughtextras', $this->get('boughtextras'));
			$this->assignRef('extras', $this->get('extras'));
			$upgrades_array = $this->get('upgrades');
			$upgrades = array();
			foreach ($upgrades_array as $upgrade)
				$upgrades[] = JHTML::_('select.option', $upgrade->membership_to_id, $upgrade->name);
			
			$has_upgrades = !empty($upgrades);
			$this->assign('has_upgrades', $has_upgrades);
			
			$lists['upgrades'] = JHTML::_('select.genericlist', $upgrades, 'to_id', 'class="inputbox"');
			
			$this->assignRef('folders', $this->get('folders'));
			$this->assignRef('files', $this->get('files'));
			$this->assignRef('previous', $this->get('previous'));
			$this->assignRef('date_format', RSMembershipHelper::getConfig('date_format'));
			$this->assignRef('from', $this->get('from'));
			$this->assignRef('lists', $lists);
			
			$Itemid = JRequest::getInt('Itemid',0);
			if ($Itemid > 0)
				$this->assign('Itemid', '&Itemid='.$Itemid);
			else
				$this->assign('Itemid', '');
			
			$this->assign('currency', RSMembershipHelper::getConfig('currency'));
			parent::display();
		}
	}
}