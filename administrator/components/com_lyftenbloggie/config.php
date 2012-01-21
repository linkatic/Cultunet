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

// Set defines
define('BLOGGIE_COM_VERSION', 	'1.1.0');
define('BLOGGIE_ADMIN_PATH', 	JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_lyftenbloggie');
define('BLOGGIE_ADMIN_URL', 	JURI::root().'administrator/components/com_lyftenbloggie');
define('BLOGGIE_SITE_PATH', 	JPATH_ROOT.DS.'components'.DS.'com_lyftenbloggie');
define('BLOGGIE_SITE_URL', 		JURI::root().'components/com_lyftenbloggie');
define('BLOGGIE_ASSETS_URL',	BLOGGIE_ADMIN_URL.'/assets' );

// Register framework
JLoader::register('BloggieFactory', 	BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'factory.php');
JLoader::register('BloggieAdmin', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'admin.php');	//For methods not right for frontend access
JLoader::register('BloggieSettings', 	BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'settings.php');
JLoader::register('BloggieAccess', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'access.php');
JLoader::register('BloggieInstaller', 	BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'installer.php');
JLoader::register('BloggieHttp', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'http.php');
JLoader::register('BloggieEntry', 		BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'entry.php');

// Set the table directory
JTable::addIncludePath(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'tables');

//Fix some problems with links if sh404sef is being used
if (class_exists('shRouter'))
{
	if (is_readable(JPATH_ADMINISTRATOR.'/components/com_sh404sef/sh404sef.class.php')) {
		define('USING_SEF',  true);
		require_once(JPATH_ADMINISTRATOR.'/components/com_sh404sef/sh404sef.class.php');
		require_once(JPATH_ADMINISTRATOR.'/components/com_sh404sef/config/config.sef.php');
	}
}