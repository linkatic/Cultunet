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
class TableEntries extends JTable
{
	var $id					= null;
	var $title				= null;
	var $alias				= null;
	var $title_alias		= null;
	var $introtext			= null;
	var $fulltext			= null;
	var $state				= null;
	var $sectionid			= null;
	var $image				= null;
	var $catid				= null;
	var $created			= null;
	var $created_by			= null;
	var $created_by_alias	= null;
	var $modified			= null;
	var $modified_by		= null;
	var $checked_out		= 0;
	var $checked_out_time	= 0;
	var $frontpage_up		= null;
	var $frontpage_down		= null;
	var $publish_up			= null;
	var $publish_down		= null;
	var $images				= null;
	var $urls				= null;
	var $attribs			= null;
	var $pinged				= null;
	var $version			= null;
	var $metakey			= null;
	var $metadesc			= null;
	var $metadata			= null;
	var $access				= null;
	var $hits				= null;
	
	/**
	* @param database A database connector object
	*/
	function __construct( &$db ) {
		parent::__construct( '#__bloggies_entries', 'id', $db );
	}

	/**
	 * Overloaded check function
	 **/
	function check()
	{
		if(empty($this->title)) {
			$this->setError(JText::_('ENTRY MUST HAVE A TITLE'));
			return false;
		}

		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);

		if(trim(str_replace('-','',$this->alias)) == '') {
			$datenow =& JFactory::getDate();
			$this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		}

		if (trim( str_replace( '&nbsp;', '', $this->fulltext ) ) == '') {
			$this->fulltext = '';
		}

		if(empty($this->introtext) && empty($this->fulltext)) {
			$this->setError(JText::_('ENTRY MUST HAVE SOME TEXT'));
			return false;
		}

		return true;
	}
}
