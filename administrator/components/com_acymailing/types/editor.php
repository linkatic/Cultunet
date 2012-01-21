<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class editorType{
	function editorType(){
		if(version_compare(JVERSION,'1.6.0','<')){
			$query = 'SELECT element,name FROM '.acymailing::table('plugins',false).' WHERE folder=\'editors\' AND published=1 ORDER BY ordering ASC, name ASC';
 		}else{
			$query = 'SELECT element,name FROM '.acymailing::table('extensions',false).' WHERE folder=\'editors\' AND enabled=1 AND type=\'plugin\' ORDER BY ordering ASC, name ASC';
         }
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$joomEditors = $db->loadObjectList();
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '0',JText::_('ACY_DEFAULT'));
		if(!empty($joomEditors)){
			foreach($joomEditors as $myEditor){
				$this->values[] = JHTML::_('select.option', $myEditor->element,$myEditor->name);
			}
		}
	}
	function display($map,$value){
		return JHTML::_('select.genericlist', $this->values, $map , 'size="1"', 'value', 'text', $value);
	}
}