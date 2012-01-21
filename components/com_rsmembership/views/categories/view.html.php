<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMembershipViewCategories extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		$params = clone($mainframe->getParams('com_rsmembership'));
		$this->assignRef('params', $params);
		$this->assignRef('items', $this->get('categories'));
		$this->assignRef('pagination', $this->get('pagination'));
		$this->assignRef('total', $this->get('total'));
		$this->assignRef('action', JRequest::getURI());
		
		$this->assignRef('sortColumn', $this->get('sortColumn'));
		$this->assignRef('sortOrder', $this->get('sortOrder'));
		$this->assignRef('limitstart', JRequest::getInt('limitstart', 0));
		
		$Itemid = JRequest::getInt('Itemid',0);
		if ($Itemid > 0)
			$this->assign('Itemid', '&Itemid='.$Itemid);
		else
			$this->assign('Itemid', '');
		
		$this->assign('currency', RSMembershipHelper::getConfig('currency'));
		
		parent::display();
	}
}