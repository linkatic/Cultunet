<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
jimport('joomla.application.component.controller');
jimport( 'joomla.application.component.view');
include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php');
$view =  JRequest::getCmd('view');
if(!empty($view) AND !JRequest::getCmd('ctrl')){
	JRequest::setVar('ctrl',$view);
	$layout =  JRequest::getCmd('layout');
	if(!empty($layout)){
		JRequest::setVar('task',$layout);
	}
}
$taskGroup = JRequest::getCmd('ctrl',JRequest::getCmd('gtask','lists'));
$doc =& JFactory::getDocument();
$doc->addScript(ACYMAILING_JS.'acymailing.js');
$config =& acymailing::config();
$cssFrontend = $config->get('css_frontend','default');
if(!empty($cssFrontend)){
	$doc->addStyleSheet( ACYMAILING_CSS.'component_'.$cssFrontend.'.css' );
}
if(!include(ACYMAILING_CONTROLLER_FRONT.$taskGroup.'.php')){
	return JError::raiseError( 404, 'Page not found : '.$taskGroup );
}
$className = ucfirst($taskGroup).'Controller';
$classGroup = new $className();
JRequest::setVar( 'view', $classGroup->getName() );
$classGroup->execute( JRequest::getCmd('task'));
$classGroup->redirect();
if(JRequest::getString('tmpl') !== 'component'){
	echo acymailing::footer();
}