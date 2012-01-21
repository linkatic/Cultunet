<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class jflanguagesType{
	var $onclick = '';
	var $id = 'jflang';
	function jflanguagesType(){
		$this->values = array();
		if(@include_once( JPATH_SITE .DS. 'components' .DS. 'com_joomfish' .DS. 'helpers' .DS. 'defines.php' )){
			include_once(JOOMFISH_ADMINPATH .DS. 'classes' .DS. 'JoomfishManager.class.php');
			$jfManager = JoomFishManager::getInstance();
			$langActive = $jfManager->getActiveLanguages();
			$this->values[] = JHTML::_('select.option', '',JText::_('DEFAULT_LANGUAGE'));
			foreach($langActive as $oneLanguage){
				$this->values[] = JHTML::_('select.option', $oneLanguage->shortcode.','.$oneLanguage->id,$oneLanguage->name);
			}
		}
	}
	function display($map,$value = ''){
		if(empty($this->values)) return '';
		return JHTML::_('select.genericlist', $this->values, $map , 'size="1" '.$this->onclick, 'value', 'text', $value,$this->id);
	}
}