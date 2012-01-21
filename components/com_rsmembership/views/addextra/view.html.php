<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMembershipViewAddExtra extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		// get parameters
		$params = clone($mainframe->getParams('com_rsmembership'));
		
		$pathway =& $mainframe->getPathway();
		$pathway->addItem(JText::_('RSM_RENEW'), '');
		
		// token
		$token = JHTML::_('form.token');
		
		// get the current layout
		$layout = $this->getLayout();
		if ($layout == 'default')
		{
			$this->assign('payments', RSMembership::getPlugins());
			
			// get the encoded return url
			$return = base64_encode(JRequest::getURI());
			$this->assignRef('return', $return);
			
			$data = $this->get('data');
			$this->assignRef('data', $data);
			
			// get the membership
			$membership = $this->get('membership');
			$this->assignRef('membership', $membership);
			
			$this->assignRef('fields', RSMembershipHelper::getFields(false));
		}
		elseif ($layout == 'payment')
		{
			$this->assignRef('html', $this->get('html'));
		}
		// get the extra
		$this->assignRef('extra', $this->get('extra'));
		$this->assignRef('cid', $this->get('cid'));
		$this->assignRef('config', $this->get('config'));
		$this->assignRef('params', $params);
		$this->assignRef('user', $this->get('user'));
		$this->assignRef('token', $token);
		
		$this->assign('currency', RSMembershipHelper::getConfig('currency'));
		
		parent::display();
	}
}