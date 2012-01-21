<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class statusType{
	function statusType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '-1', JText::_('UNSUBSCRIBED') );
		$this->values[] = JHTML::_('select.option', '0', JText::_('NO_SUBSCRIPTION') );
		$this->values[] = JHTML::_('select.option', '2', JText::_('PENDING_SUBSCRIPTION') );
		$this->values[] = JHTML::_('select.option', '1', JText::_('SUBSCRIBED') );
	}
	function display($map,$value){
		static $i = 0;
		return JHTML::_('select.radiolist', $this->values, $map , 'class="inputbox" size="1"', 'value', 'text', (int) $value,'status'.$i++);
	}
}