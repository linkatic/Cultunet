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

$view	= JRequest::getCmd('view','');

JHTML::_('behavior.switcher');

// Load submenu's
$views	= array(
					'' 				=> JText::_('DASHBOARD'),
					'entries' 		=> JText::_('ENTRIES'),
					'categories' 	=> JText::_('CATEGORIES'),
					'tags' 			=> JText::_('TAGS'),
					'bookmarks'		=> JText::_('BOOKMARKS'),
					'comments' 		=> JText::_('COMMENTS'),
					'profiles' 		=> JText::_('PROFILES'),
					'settings' 		=> JText::_('SETTINGS'),
					'about'			=> JText::_('ABOUT'),
				);	

foreach( $views as $key => $val )
{
	$active	= ( $view == $key );
	$key= $key?'&view='.$key:'';
	JSubMenuHelper::addEntry( $val , 'index.php?option=com_lyftenbloggie' . $key , $active );
}
?>