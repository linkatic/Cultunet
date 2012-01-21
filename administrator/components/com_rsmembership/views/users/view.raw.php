<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class RSMembershipViewUsers extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getVar('task','');
		
		$row = $this->get('user');
		$this->assignRef('row', $row);
		
		parent::display($tpl);
	}
}
?>