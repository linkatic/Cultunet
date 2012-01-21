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
require_once(dirname(__FILE__).DS.'config.php');

// get task
$task = JRequest::getVar('task', null, 'default', 'cmd');

// Check User Access
if($task != 'xmlrpc')
BloggieFactory::allowAccess('system.system_access', true);

// Require the controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// include the route helpers
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'route.php');

// Run Controller
$controller = new LyftenBloggieController();
$controller->execute($task);
$controller->redirect(); 
?>