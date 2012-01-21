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
class TableCategories extends JTable
{
	var $id 				= null;
	var $title 				= '';
	var $parent 			= 0;
	var $default 			= 0;
	var $slug	 			= '';
	var $text	 			= '';
	var $published			= 0;
	var $checked_out 		= 0;
	var $checked_out_time	= '';
	var $ordering 			= null;

	function __construct(& $db) {
		parent::__construct('#__bloggies_categories', 'id', $db);
	}
	
	// overloaded check function
	function check()
	{
		// Not typed in a category name?
		if (trim( $this->title ) == '') {
			$this->setError(JText::_( 'ADD CATEGORY NAME' ));
			return false;
		}

		$slug = JFilterOutput::stringURLSafe($this->title);

		if(empty($this->slug) || $this->slug === $slug ) {
			$this->slug = $slug;
		}

		/** check for existing name with same parent category*/
		$query = 'SELECT id'
				.' FROM #__bloggies_categories'
				.' WHERE title = '.$this->_db->Quote($this->title)
				;
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError(JText::sprintf('CATEGORY NAME ALREADY EXIST', $this->title));
			return false;
		}

		return true;
	}
}
?>