<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class operatorsType{
	function operatorsType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '<OPTGROUP>',JText::_('Numeric'));
		$this->values[] = JHTML::_('select.option', '=','=');
		$this->values[] = JHTML::_('select.option', '!=','!=');
		$this->values[] = JHTML::_('select.option', '>','>');
		$this->values[] = JHTML::_('select.option', '<','<');
		$this->values[] = JHTML::_('select.option', '>=','>=');
		$this->values[] = JHTML::_('select.option', '<=','<=');
		$this->values[] = JHTML::_('select.option', '</OPTGROUP>');
		$this->values[] = JHTML::_('select.option', '<OPTGROUP>',JText::_('String'));
		$this->values[] = JHTML::_('select.option', 'BEGINS','Begins with');
		$this->values[] = JHTML::_('select.option', 'END','Ends with');
		$this->values[] = JHTML::_('select.option', 'CONTAINS','Contains');
		$this->values[] = JHTML::_('select.option', 'LIKE','LIKE');
		$this->values[] = JHTML::_('select.option', 'NOT LIKE','NOT LIKE');
		$this->values[] = JHTML::_('select.option', '</OPTGROUP>');
		$this->values[] = JHTML::_('select.option', '<OPTGROUP>',JText::_('Others'));
		$this->values[] = JHTML::_('select.option', 'IS NULL','IS NULL');
		$this->values[] = JHTML::_('select.option', 'IS NOT NULL','IS NOT NULL');
		$this->values[] = JHTML::_('select.option', '</OPTGROUP>');
	}
	function display($map){
		return JHTML::_('select.genericlist', $this->values, $map, 'class="inputbox" size="1"', 'value', 'text');
	}
}