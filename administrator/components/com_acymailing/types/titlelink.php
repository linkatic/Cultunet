<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class titlelinkType{
	var $onclick="updateTag();";
	function titlelinkType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', "|link",JText::_('JOOMEXT_YES'));
		$this->values[] = JHTML::_('select.option', "",JText::_('JOOMEXT_NO'));
	}
	function display($map,$value){
		return JHTML::_('select.radiolist', $this->values, $map , 'size="1" onclick="'.$this->onclick.'"', 'value', 'text', $value);
	}
}