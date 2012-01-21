<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class RSMembershipViewExtraValues extends JView
{
	function display($tpl = null)
	{		
		$row = $this->get('extravalue');
		$this->assignRef('row', $row);
		
		$this->assign('currency', RSMembershipHelper::getConfig('currency'));
		
		parent::display($tpl);
	}
}
?>