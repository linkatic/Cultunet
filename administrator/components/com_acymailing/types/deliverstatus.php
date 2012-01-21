<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class deliverstatusType{
	function deliverstatusType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '0', JText::_('ALL_STATUS') );
		$this->values[] = JHTML::_('select.option', 'open', JText::_('OPEN') );
		$this->values[] = JHTML::_('select.option', 'notopen', JText::_('NOT_OPEN') );
		$this->values[] = JHTML::_('select.option', 'failed', JText::_('FAILED') );
		if(acymailing::level(3)) $this->values[] = JHTML::_('select.option', 'bounce', JText::_('BOUNCES') );
	}
	function display($map,$value){
		return JHTML::_('select.genericlist',   $this->values, $map, 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $value );
	}
}