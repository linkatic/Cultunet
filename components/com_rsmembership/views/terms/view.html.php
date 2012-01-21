<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMembershipViewTerms extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		$terms = $this->get('terms');
		
		$pathway =& $mainframe->getPathway();
		$pathway->addItem($terms->name, '');
		
		// get parameters
		$params = clone($mainframe->getParams('com_rsmembership'));
		$this->assignRef('params', $params);
		
		$this->assignRef('terms', $terms);
		
		parent::display();
	}
}