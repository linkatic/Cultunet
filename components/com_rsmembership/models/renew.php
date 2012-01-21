<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelRenew extends JModel
{
	var $_html = '';
	var $transaction_id = 0;
	
	function __construct()
	{
		parent::__construct();
		
		$user = JFactory::getUser();
		if ($user->get('guest'))
		{
			global $mainframe;
			$link = JRequest::getURI();
			$link = base64_encode($link);
			$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login&return='.$link, false));
		}
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		$this->_execute();
	}
	
	function _execute()
	{
		global $mainframe, $option;
		$task = JRequest::getVar('task', '');
		
		if ($task == 'renew')
		{
			$this->_bindId();
		}
		else
		{
			$this->_setId();
			
			if ($task == 'renewpayment')
			{
				// empty session
				$this->_emptySession();
				
				$membership = $this->getMembership();
				$extras = $this->getExtras();
				$paymentplugin = JRequest::getVar('payment', 'none');
				
				// calculate the total price
				$total = 0;
				$total += $membership->price;
				foreach ($extras as $extra)
					$total += $extra->price;

				$user =& JFactory::getUser();
				$user_id = $user->get('id');
				
				$row =& JTable::getInstance('RSMembership_Transactions','Table');
				$row->user_id = $user_id;
				$row->user_email = $user->get('email');
				
				$this->_data = new stdClass();
				$this->_data->username = $user->get('username');
				$this->_data->name = $user->get('name');
				$this->_data->email = $user->get('email');
				$this->_data->fields = RSMembershipHelper::getUserFields($user->get('id'));
				$row->user_data = serialize($this->_data);
				
				$row->type = 'renew';
				$params = array();
				$params[] = 'id='.$this->_id;
				$params[] = 'membership_id='.$membership->id;
				if (is_array($this->_extras) && !empty($this->_extras))
					$params[] = 'extras='.implode(',', $this->_extras);
				
				$row->params = implode(';', $params); // params, membership, extras etc
				$row->date = time();
				$row->ip = $_SERVER['REMOTE_ADDR'];
				$row->price = $total;
				$row->currency = RSMembershipHelper::getConfig('currency');
				$row->hash = '';
				$row->gateway = $paymentplugin == 'none' ? 'No Gateway' : RSMembership::getPlugin($paymentplugin);
				$row->status = 'pending';
				
				// parameters sent to the events
				$args = array('plugin' => $paymentplugin, 'data' => &$this->_data, 'extras' => $extras, 'membership' => $membership, 'transaction' => &$row);
				// trigger the payment plugin
				$results = $mainframe->triggerEvent('onMembershipPayment', $args);
				// process the result
				$this->_html = RSMembership::processPluginResult($results);
				
				// store the transaction
				$row->store();
				
				// store the transaction id
				$this->transaction_id = $row->id;
				
				// finalize the transaction (send emails)
				RSMembership::finalize($this->transaction_id);
				
				// approve the transaction
				if ($row->status == 'completed' || ($row->price == 0 && $membership->activation != 0))
					RSMembership::approve($this->transaction_id, true);
				
				if ($row->price == 0)
					$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&task=thankyou', false));
			}
		}
	}
	
	function _setId()
	{
		global $option;
		
		$session = JFactory::getSession();
		$this->_id = (int) $session->get($option.'.renew.cid', 0);
	}
	
	function _bindId()
	{
		global $option;
		
		$this->_id = JRequest::getInt('cid', 0);
		
		$session = JFactory::getSession();
		$session->set($option.'.renew.cid', $this->_id);
	}
	
	function _emptySession()
	{
		global $option;
		
		$session = JFactory::getSession();
		$session->set($option.'.renew.cid', null);
	}
	
	function getCid()
	{
		return JRequest::getInt('cid');
	}
	
	function getMembership()
	{
		$cid = $this->_id;
		
		$user = JFactory::getUser();
		$this->_db->setQuery("SELECT `membership_id`, `status`, `extras` FROM #__rsmembership_membership_users WHERE `user_id`='".$user->get('id')."' AND `id`='".$cid."'");
		$membership = $this->_db->loadObject();
		
		global $mainframe;
		if (empty($membership))
			$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
		
		if ($membership->status < 2)
		{
			JError::raiseWarning(500, JText::_('RSM_MEMBERSHIP_NOT_EXPIRED'));
			$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
		}
		
		$extras = explode(',', $membership->extras);
		if (!empty($extras[0]))
			$this->_extras = $extras;
		else
			$this->_extras = array();
		
		$this->_db->setQuery("SELECT * FROM #__rsmembership_memberships WHERE `id`='".$membership->membership_id."'");
		$membership = $this->_db->loadObject();
		
		if ($membership->use_renewal_price)
			$membership->price = $membership->renewal_price;
		
		if ($membership->no_renew)
		{
			JError::raiseWarning(500, JText::_('RSM_MEMBERSHIP_CANNOT_RENEW'));
			$mainframe->redirect(JRoute::_('index.php?option=com_rsmembership&view=mymemberships', false));
		}
		
		return $membership;
	}
	
	function getExtras()
	{
		$return = array();
		
		if (is_array($this->_extras))
			foreach ($this->_extras as $extra)
			{
				if (empty($extra)) continue;
				$row =& JTable::getInstance('RSMembership_Extra_Values','Table');
				$row->load($extra);
				$return[] = $row;
			}			
			
		return $return;
	}
	
	function getData()
	{
		$user =& JFactory::getUser();
		$this->_db->setQuery("SELECT * FROM #__rsmembership_users WHERE `user_id`='".$user->get('id')."'");
		return $this->_db->loadObject();
	}
	
	function getConfig()
	{
		return RSMembershipHelper::getConfig();
	}
	
	function getUser()
	{
		$user =& JFactory::getUser();
		return $user;
	}
	
	function getTransactionId()
	{
		return $this->transaction_id;
	}
	
	function getHTML()
	{		
		return $this->_html;
	}
}
?>