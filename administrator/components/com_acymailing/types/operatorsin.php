<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class operatorsinType{
	function operatorsinType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', 'IN','In');
		$this->values[] = JHTML::_('select.option', 'NOT IN','Not in');
	}
	function display($map){
		return JHTML::_('select.genericlist', $this->values, $map, 'class="inputbox" size="1"', 'value', 'text');
	}
}