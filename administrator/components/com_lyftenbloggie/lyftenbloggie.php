<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

// load config
require_once(dirname(__FILE__).'/config.php');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

//Load route helper
require_once (JPATH_COMPONENT_SITE.DS.'helpers'.DS.'route.php');
		
// Require specific controller if requested
if( $controller = JRequest::getWord('controller', '') ) {

	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

//Create the controller
$classname  = 'LyftenBloggieController'.$controller;
$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getWord('task', ''));
$controller->redirect();
?>