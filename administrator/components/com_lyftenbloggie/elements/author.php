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

/**
 * Renders a author element
 *
 * @package 	Joomla
 * @subpackage	Articles
 * @since		1.5
 */
class JElementAuthor extends JElement
{
	/**
	 * Element name
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Author';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db			=& JFactory::getDBO();
		$db = &JFactory::getDBO();

		$query = 'SELECT user_id as id, name as title'
		. ' FROM #__bloggies_authors'
		. ' WHERE published = 1'
		;
		$db->setQuery( $query );
		$options[] 	= JHTML::_('select.option', '0', ' - '.JText::_('SELECT AUTHOR').' - ', 'id', 'title');
		$options 	= array_merge($options, $db->loadObjectList());

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'title', $value, $control_name.$name );
	}
}