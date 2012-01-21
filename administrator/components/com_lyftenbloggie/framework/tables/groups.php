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
 * @since 1.1.0
 */
class TableGroups extends JTable
{
	var $id 				= null;
	var $group 				= '';
	var $permissions		= '';
	var $email_all			= 0;
	var $published 			= 1;

	function __construct(& $db) {
		parent::__construct('#__bloggies_groups', 'id', $db);
	}
	
	// overloaded check function
	function check()
	{
		// Not typed in a group name?
		if (trim( $this->group ) == '')
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_( 'NO GROUP SELECTED' ) );
			return false;
		}
		return true;
	}
}
?>