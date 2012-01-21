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
 * @since 1.0.2
 */
class TableThemes extends JTable
{
	var $id					= null;
	var $title				= null;
	var $name				= null;
	var $is_default			= 0;
	var $author				= null;
	var $email				= null;
	var $website			= null;
	var $version			= null;
	var $license			= null;
	var $copyright			= null;
	var $create_date		= null;
	var $attribs			= null;

	function __construct(& $db) {
		parent::__construct('#__bloggies_themes', 'id', $db);
	}
	
	// overloaded check function
	function check()
	{
		return true;
	}
}
?>