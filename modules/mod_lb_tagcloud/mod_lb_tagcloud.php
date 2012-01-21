<?php
/**
 * LyftenBloggie Tag Cloud Module 1.0.2
 * @package LyftenBloggie 1.0.2
 * @copyright (C) 2009 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
		
$tags	= modTagCloudBlogHelper::getList($params, $module);

require(JModuleHelper::getLayoutPath('mod_lb_tagcloud'));