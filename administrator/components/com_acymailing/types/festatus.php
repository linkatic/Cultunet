<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class festatusType{
	function festatusType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '-1', JText::_('JOOMEXT_NO') );
		$this->values[] = JHTML::_('select.option', '1', JText::_('JOOMEXT_YES') );
	}
	function display($map,$value){
		static $i = 0;
		$value = (int) $value;
		$value = ($value >= 1) ? 1 : -1;
		return JHTML::_('select.radiolist', $this->values, $map , 'class="inputbox" size="1"', 'value', 'text', (int) $value,'status'.$i++);
	}
}