<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelThankYou extends JModel
{
	var $message;
	
	function __construct()
	{
		parent::__construct();
		
		global $mainframe, $option;
		
		$session = JFactory::getSession();
		$action = $session->get($option.'.subscribe.action', null);		
		$message = $session->get($option.'.subscribe.thankyou', null);
		$redirect = $session->get($option.'.subscribe.redirect', null);
		
		$session->set($option.'.subscribe.action', null);
		$session->set($option.'.subscribe.thankyou', null);
		$session->set($option.'.subscribe.redirect', null);
		
		if (is_null($action))
			$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		
		if ($action == 1)
			$mainframe->redirect($redirect);
		
		$this->message = $message;
	}
	
	function getMessage()
	{		
		return $this->message;
	}
}
?>