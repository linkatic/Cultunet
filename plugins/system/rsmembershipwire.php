<?php
/**
* @version 1.0.0
* @package RSMembership! PayPal 1.0.0
* @copyright (C) 2009 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.plugin.plugin' );

JPlugin::loadLanguage('plg_system_rsmembership', JPATH_ADMINISTRATOR);
JPlugin::loadLanguage('plg_system_rsmembershipwire', JPATH_ADMINISTRATOR);

if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php'))
	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php');

class plgSystemRSMembershipWire extends JPlugin
{
	function canRun()
	{
		return file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php');
	}
	
	function plgSystemRSMembershipWire(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->_plugin =& JPluginHelper::getPlugin('system', 'rsmembershipwire');
		$this->_params = new JParameter($this->_plugin->params);
		
		if (!$this->canRun()) return;
		RSMembership::addPlugin('Wire Transfer', 'rsmembershipwire');
	}
	
	function onMembershipPayment($plugin, $data, $extra, $membership, $transaction)
	{
		if (!$this->canRun()) return;
		if ($plugin != $this->_plugin->name) return false;
		
		$html  = '';
		$html .= $this->_params->get('details');
		
		$html .= '<form method="post" action="'.JRoute::_('index.php?option=com_rsmembership&task=thankyou').'">';
		$html .= '<input class="button" type="submit" value="'.JText::_('RSM_CONTINUE').'" />';
		$html .= '<input type="hidden" name="option" value="com_rsmembership" />';
		$html .= '<input type="hidden" name="task" value="thankyou" />';
		$html .= '</form>';
		
		// No hash for this
		$transaction->hash = '';
		
		$tax_value = $this->_params->get('tax_value');
		if (!empty($tax_value))
		{
			$tax_type = $this->_params->get('tax_type');
			
			// percent ?
			if ($tax_type == 0)
				$tax_value = $transaction->price * ($tax_value / 100);
				
			$transaction->price = $transaction->price + $tax_value;
		}
		
		if ($membership->activation == 2)
			$transaction->status = 'completed';
		
		return $html;
	}
}