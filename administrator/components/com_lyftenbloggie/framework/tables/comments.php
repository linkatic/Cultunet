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
class TableComments extends JTable
{
	var $id				= null;
	var $entry_id		= null;
	var $author			= '';
	var $author_email	= '';
	var $author_url		= '';
	var $author_ip		= '';
	var $date			= null;
	var $date_gmt		= null;
	var $content		= '';
	var $karma			= null;
	var $approved		= null;
	var $agent			= null;
	var $type			= null;
	var $parent			= null;
	var $user_id		= null;
	var $state			= null;

	function __construct(& $db) {
		parent::__construct('#__bloggies_comments', 'id', $db);
	}
	
	// overloaded check function
	function check()
	{
		return true;
	}
}
?>