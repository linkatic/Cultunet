<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class articlescatType{
	function articlescatType(){
		$db = JFactory::getDBO();
		$query = 'SELECT a.id, a.title as category, b.title as section from '.acymailing::table('categories',false).' as a ';
		$query .= 'INNER JOIN '.acymailing::table('sections',false).' as b on a.section = b.id ORDER BY b.ordering,a.ordering';
		$db->setQuery($query);
		$categories = $db->loadObjectList('id');
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '',JText::_('ALL'));
		$currentSec = '';
		foreach($categories as $catid => $oneCategorie){
			if($currentSec != $oneCategorie->section){
				if(!empty($currentSec)) $this->values[] = JHTML::_('select.option', '</OPTGROUP>');
				$this->values[] = JHTML::_('select.option', '<OPTGROUP>',$oneCategorie->section);
				$currentSec = $oneCategorie->section;
			}
			$this->values[] = JHTML::_('select.option', $catid,$oneCategorie->category);
		}
	}
	function display($map,$value){
		return JHTML::_('select.genericlist',   $this->values, $map, 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', (int) $value );
	}
}