<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class statusquickType{
	function statusquickType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '0', JText::_('JOOMEXT_RESET') );
		$this->values[] = JHTML::_('select.option', '1', JText::_('SUBSCRIBE_ALL') );
		$js = "function updateStatus(statusval){";
			$js .='var i=0;';
			$js .= "while(window.document.getElementById('status'+i+statusval)){ window.document.getElementById('status'+i+statusval).checked = true; i++;}";
		$js .= '}';
		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration( $js );
	}
	function display($map){
		return JHTML::_('select.radiolist', $this->values, $map , 'class="inputbox" size="1" onclick="updateStatus(this.value)"', 'value', 'text', '','status_all');
	}
}