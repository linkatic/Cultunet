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
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class TableProfiles extends JTable
{
	var $id					= null;
	var $user_id			= null;
	var $url				= null;
	var $about				= null;
	var $avatar				= null;
	var $attribs			= null;
	
	/**
	* database A database connector object
	*/
	function __construct( &$db ) {
		parent::__construct( '#__bloggies_authors', 'id', $db );
	}

	/**
	 * Overloaded check function
	 **/
	function check()
	{
		return true;
	}
}
