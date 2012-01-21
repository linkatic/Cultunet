<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class listsViewLists extends JView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();
		parent::display($tpl);
	}
	function listing(){
		global $Itemid;
		$app =& JFactory::getApplication();
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
		if(empty($menu) AND !empty($Itemid)){
			$menus->setActive($Itemid);
			$menu	= $menus->getItem($Itemid);
		}
		$pathway	=& $app->getPathway();
		$pathway->addItem(JText::_('MAILING_LISTS'));
		$listsClass = acymailing::get('class.list');
		$allLists = $listsClass->getLists();
		if(acymailing::level(1)){
			$allLists = $listsClass->onlyCurrentLanguage($allLists);
		}
		$this->assignRef('rows',$allLists);
	}
}