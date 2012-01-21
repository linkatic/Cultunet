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
class TableTags extends JTable
{

	var $id 				= null;
	var $name				= null;
	var $slug				= null;
	var $published			= 1;
	
	function __construct(& $db) {
		parent::__construct('#__bloggies_tags', 'id', $db);
	}
	/**
	 * Overloaded check function
	 **/
	function check()
	{
		if(empty($this->name)) {
			$this->setError(JText::_('TAG MUST HAVE A NAME'));
			return false;
		}

		if(empty($this->slug)) {
			$this->slug = $this->name;
		}
		
		$this->slug = JFilterOutput::stringURLSafe($this->slug);
		
		return true;
	}
}
?>