<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMembershipViewSubscribe extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		// get parameters
		$params = clone($mainframe->getParams('com_rsmembership'));
		
		// get the membership
		$membership = $this->get('membership');
		
		// check if the membership exists
		if (empty($membership->id))
		{
			JError::raiseWarning(500, JText::_('RSM_MEMBERSHIP_NOT_EXIST'));
			$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		if (!$membership->published || $membership->stock == -1)
		{
			JError::raiseWarning(500, JText::_('RSM_MEMBERSHIP_NOT_PUBLISHED'));
			$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		
		$pathway =& $mainframe->getPathway();
		$pathway->addItem($membership->name, JRoute::_('index.php?option=com_rsmembership&view=membership&cid='.$membership->id.':'.JFilterOutput::stringURLSafe($membership->name)));
		
		$pathway->addItem(JText::_('RSM_SUBSCRIBE'), '');
		
		// get the extras
		$extras = $this->get('extras');
		
		// check if the user is logged in
		$user =& JFactory::getUser();
		$logged = $user->get('guest') ? false : true;
		$show_login = RSMembershipHelper::getConfig('show_login');
		
		// token
		$token = JHTML::_('form.token');
		
		// get the current task
		$task = JRequest::getVar('task', '');
		
		$choose_username = RSMembershipHelper::getConfig('choose_username');
		
		// get the current layout
		$layout = $this->getLayout();
		if ($layout == 'default')
		{
			// get the encoded return url
			$return = base64_encode(JRequest::getURI());
			$this->assignRef('return', $return);
			
			$this->assign('choose_username', $choose_username);
			
			$muser = $this->get('user');
			$data = $this->get('data');
			
			if ($task == 'back' || $task == 'validatesubscribe')
				$this->assignRef('data', $data);
			
			$this->assignRef('fields_validation', RSMembershipHelper::getFieldsValidation());
			$this->assignRef('fields', RSMembershipHelper::getFields());
			
			$this->assign('use_captcha', $this->get('usecaptcha'));
			$this->assign('use_builtin', $this->get('usebuiltin'));
			$this->assign('use_recaptcha', $this->get('userecaptcha'));
			if ($this->get('userecaptcha'))
			{
				if (!class_exists('JReCAPTCHA'))
					require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'recaptcha'.DS.'recaptchalib.php');
					
				$this->assign('show_recaptcha', JReCAPTCHA::getHTML($this->get('recaptchaerror')));
			}
		}
		elseif ($layout == 'preview')
		{
			$this->assign('choose_username', $choose_username);
			$this->assignRef('fields', RSMembershipHelper::getFields(false));
			$this->assign('payments', RSMembership::getPlugins());
			$data = $this->get('data');
			$this->assignRef('data', $data);
		}
		elseif ($layout == 'payment')
		{
			$this->assignRef('html', $this->get('html'));
		}
		
		$this->assignRef('config', $this->get('config'));
		$this->assignRef('params', $params);
		$this->assignRef('membership', $membership);
		$this->assignRef('membershipterms', $this->get('membershipterms'));
		$this->assignRef('extras', $extras);
		$this->assignRef('logged', $logged);
		$this->assignRef('show_login', $show_login);
		$this->assignRef('user', $user);
		$this->assignRef('muser', $muser);
		$this->assignRef('token', $token);
		
		$this->assign('currency', RSMembershipHelper::getConfig('currency'));
		
		parent::display();
	}
}