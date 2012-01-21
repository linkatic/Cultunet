<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
if(!include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php')){
	echo "Could not load helper file";
	return;
}
if(defined(JDEBUG) AND JDEBUG) acymailing::displayErrors();
$taskGroup = JRequest::getCmd('ctrl',JRequest::getCmd('gtask','dashboard'));
$config =& acymailing::config();
$doc =& JFactory::getDocument();
$cssBackend = $config->get('css_backend','default');
if(!empty($cssBackend)){
	$doc->addStyleSheet( ACYMAILING_CSS.'component_'.$cssBackend.'.css' );
}
JHTML::_('behavior.tooltip');
$bar = & JToolBar::getInstance('toolbar');
$bar->addButtonPath(ACYMAILING_BUTTON);
if($taskGroup != 'update'){
	$app =& JFactory::getApplication();
	if(!$config->get('installcomplete')){
		$app->redirect(acymailing::completeLink('update&task=install',false,true));
	}
	if(empty($_SESSION['acymailing']['li'])){
		$updateHelper = acymailing::get('helper.update');
		if(!$updateHelper->check()){
			$try = $config->get('litry','0') +1;
			$newConf = null;
			$newConf->litry = $try;
			if($newConf->litry>2) $newConf->litry=0;
			$config->save($newConf);
			if($try==3){
				$app->redirect(acymailing::completeLink('update&task=licensejs',false,true));
			}else{
				$app->redirect(acymailing::completeLink('update&task=license',false,true));
			}
		}
	}
}
$lang =& JFactory::getLanguage();
$lang->load(ACYMAILING_COMPONENT,JPATH_SITE);
include(ACYMAILING_CONTROLLER.$taskGroup.'.php');
$className = ucfirst($taskGroup).'Controller';
$classGroup = new $className();
JRequest::setVar( 'view', $classGroup->getName() );
$classGroup->execute( JRequest::getCmd('task','listing'));
$classGroup->redirect();
if(JRequest::getString('tmpl') !== 'component'){
	echo acymailing::footer();
	JSubMenuHelper::addEntry(JText::_('USERS'), 'index.php?option=com_acymailing&ctrl=subscriber',$taskGroup == 'subscriber');
	JSubMenuHelper::addEntry(JText::_('LISTS'), 'index.php?option=com_acymailing&ctrl=list',$taskGroup == 'list');
	JSubMenuHelper::addEntry(JText::_('NEWSLETTERS'), 'index.php?option=com_acymailing&ctrl=newsletter',$taskGroup == 'newsletter');
	if(acymailing::level(2)){
		JSubMenuHelper::addEntry(JText::_('AUTONEWSLETTERS'), 'index.php?option=com_acymailing&ctrl=autonews',$taskGroup == 'autonews');
	}
	if(acymailing::level(3)){
		JSubMenuHelper::addEntry(JText::_('CAMPAIGN'), 'index.php?option=com_acymailing&ctrl=campaign',$taskGroup == 'campaign' );
	}
	JSubMenuHelper::addEntry(JText::_('ACY_TEMPLATES'), 'index.php?option=com_acymailing&ctrl=template',$taskGroup == 'template');
	JSubMenuHelper::addEntry(JText::_('QUEUE'), 'index.php?option=com_acymailing&ctrl=queue',$taskGroup == 'queue');
	JSubMenuHelper::addEntry(JText::_('STATISTICS'), 'index.php?option=com_acymailing&ctrl=stats',$taskGroup == 'stats');
	JSubMenuHelper::addEntry(JText::_('CONFIGURATION'), 'index.php?option=com_acymailing&ctrl=config',$taskGroup == 'config');
}