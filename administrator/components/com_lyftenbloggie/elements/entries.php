<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

class JElementEntries extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Entries';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db			=& JFactory::getDBO();
		$db = &JFactory::getDBO();

		$query = 'SELECT id, title'
		. ' FROM #__bloggies_entries'
		. ' WHERE state = 1'
		. ' ORDER BY created'
		;
		$db->setQuery( $query );
		$options[] 	= JHTML::_('select.option', '0', ' - '.JText::_('SELECT ENTRY').' - ', 'id', 'title');
		$options 	= array_merge($options, $db->loadObjectList());

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'title', $value, $control_name.$name );
	}
}