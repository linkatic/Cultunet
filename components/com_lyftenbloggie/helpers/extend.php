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

if(!defined('BLOGGIE_ADMIN_PATH')) {
	define('BLOGGIE_ADMIN_PATH', 	JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie');
	define('BLOGGIE_SITE_PATH', 	JPATH_ROOT.DS.'components'.DS.'com_lyftenbloggie');
	define('BLOGGIE_SITE_URL', 		JURI::root().'components/com_lyftenbloggie');
	define('BLOGGIE_ASSETS_URL',	BLOGGIE_SITE_URL.'/assets' );
}

// Register framework
JLoader::register('BloggieFactory', 	BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'factory.php');
JLoader::register('BloggieSettings', 	BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'settings.php');
JLoader::register('BloggieAuthor', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'author.php');
JLoader::register('BloggiePlugin', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'plugin.php');
JLoader::register('BloggieTemplate', 	BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'template.php');