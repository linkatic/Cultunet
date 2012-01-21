<?php
/**
 * LyftenBloggie Tag Cloud Module 1.1.0
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
		
$calendar	= modBloggieCalendarHelper::getList($params, $module);

require(JModuleHelper::getLayoutPath('mod_lb_calendar'));