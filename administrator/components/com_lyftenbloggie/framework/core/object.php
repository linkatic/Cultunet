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

/**
 * LyftenBloggie Framework Settings class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieObject extends JObject
{
	var $_debug_stack;

	/**
	 * Default Constructor
	 **/
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * System Debugger
	 **/
	function debug_push($type, $message, $level = 0)
	{
		$this->_debug_stack[] = array($type, $message, $level);
		echo $type, ': ', $message,"<br>";
	}
}