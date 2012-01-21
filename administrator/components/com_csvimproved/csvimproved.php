<?php
/**
 * Admin interface
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvimproved.php 919 2009-05-29 18:14:01Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/* Get the database object */
$db = JFactory::getDBO();

/* Get the parameters */
$params = JComponentHelper::getParams('com_csvimproved');

/* Check if we are running cron mode and set some necessary variables */
if (JRequest::getBool('cron', false)) {
	$_SERVER['SERVER_ADDR'] = $_SERVER['HTTP_HOST'] = $hostname = $params->getValue('hostname');
}
else $hostname = false;

/* Do some requirement checks */
$checks = true;
$check_msg = array();
/* Check to see if the user uses at least PHP 5 */
if (version_compare(phpversion(), '5', '<') == '-1') {
	$php5_message = str_replace('[version]', phpversion(), JText::_('NO_PHP5'));
	if (JRequest::getBool('cron')) echo $php5_message;
	else {
		$check_msg[] = '<img src="'.$mainframe->getSiteURL().'administrator/components/com_csvimproved/assets/images/help_32.png" alt="'.$php5_message.'" /> '.$php5_message;
	}
	$checks = false;
}

/* Check to see if VirtueMart is installed */
$q = "SELECT COUNT(id) AS total FROM #__components where link = 'option=com_virtuemart';";
$db->setQuery($q);

if ($db->loadResult() == 0) {
	if (JRequest::getBool('cron')) echo JText::_('NO_VIRTUEMART');
	else {
		$check_msg[] = '<img src="'.$mainframe->getSiteURL().'administrator/components/com_csvimproved/assets/images/help_32.png" alt="'.JText::_('NO_VIRTUEMART').'" /> '.JText::_('NO_VIRTUEMART');
	}
	$checks = false;
}

/* Check if all of the requirements are met */

if (!$checks) {
	if (!JRequest::getBool('cron')) echo '<img src="'.$mainframe->getSiteURL().'administrator/components/com_csvimproved/assets/images/logo.png" alt="CSV Improved"/>';
	foreach ($check_msg as $key => $msg) {
		echo '<div>'.$msg.'</div>';
	}
}
else {
	/* Do the subscription check */
	require_once (JPATH_COMPONENT.DS.'helpers'.DS.'subscription_check.php');
	$check = new SubscriptionCheck;
	$result = $check->CheckKey($hostname);
	
	/* Print out the results */
	if ($result['errorcode'] > 0 ) {
		if (JRequest::getBool('cron')) echo $result['result'];
		else $mainframe->enqueueMessage($result['result'], 'error');
	}
	
    /**
    * Admin interface
    *
    */
    if (JRequest::getBool('cron', false)) {
     /* Override preview in cron mode */
     JRequest::setVar('was_preview', true);
    }
    else {
    /* Not doing cron, so set it to false */
    JRequest::setVar('cron', false);

    /* Add stylesheets */
    $document = JFactory::getDocument();
    $document->addStyleSheet($mainframe->getSiteURL().'administrator/components/com_csvimproved/assets/css/images.css');
    $document->addStyleSheet($mainframe->getSiteURL().'administrator/components/com_csvimproved/assets/css/display.css');
    $document->addStyleSheet($mainframe->getSiteURL().'administrator/components/com_csvimproved/assets/css/tables.css');

    /* Add javascript */
	$document->addScript($mainframe->getSiteURL().'administrator/components/com_csvimproved/assets/js/jquery.js');
	$document->addScriptDeclaration('jQuery.noConflict();');
	$document->addScript($mainframe->getSiteURL().'administrator/components/com_csvimproved/assets/js/jquery.alphanumeric.js');
	$document->addScript($mainframe->getSiteURL().'administrator/components/com_csvimproved/assets/js/csvi.js');
    }

    /* Require the base controller */
    require_once (JPATH_COMPONENT.DS.'controller.php');

    /* Require specific controller if requested */
    $controller = JRequest::getVar('controller', false);
    if($controller) {
    	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
    }

    /* Create the controller */
    $classname   = 'CsvimprovedController'.$controller;
    $controller = new $classname();

    /* Perform the Request task */
    $controller->execute(JRequest::getVar('task'));

    /* Redirect if set by the controller */
    $controller->redirect();
}
?>