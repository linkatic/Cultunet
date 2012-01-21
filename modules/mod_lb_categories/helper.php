<?php
/**
 * LyftenBloggie Categories Module 1.0.2
 * @package LyftenBloggie 1.0.2
 * @copyright (C) 2009 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');

class modCategoriesBlogHelper
{
	function getList(&$params, $module)
	{
		global $mainframe;

		$my 			= &JFactory::getUser();
		$db				=& JFactory::getDBO();
		$lists			= array();
		$onCategory		= JRequest::getVar( 'category' );
		$gid			= (int)$my->get('gid');
				
		// Get Categories
		$query = 'SELECT COUNT(e.id) AS count, c.id, c.title, c.slug' .	
			' FROM #__bloggies_categories AS c' .
			' LEFT JOIN #__bloggies_entries AS e ON e.catid = c.id' .
			' WHERE c.published = 1' .
			' AND e.access <= ' .(!$gid ? 1 : $gid) .
			' GROUP BY c.id' .
			' ORDER BY c.ordering';
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		$i	= 0;
		foreach( $categories as $category)
		{
			$lists[$i]->link 	= LyftenBloggieHelperRoute::getCategoryRoute($category->slug);
			$lists[$i]->title 	= $category->title;
			$lists[$i]->count 	= $category->count;
			$lists[$i]->current = ($onCategory == $category->id) ? ' id="current" class="active"' : '';
			$i++;
		}

		$count	= modCategoriesBlogHelper::CountEntries(0);
		if($count){
			$lists[$i]->link 	= LyftenBloggieHelperRoute::getCategoryRoute('uncategorized');
			$lists[$i]->title 	= JText::_('UNCATEGORIZED');
			$lists[$i]->count 	= $count;
			$lists[$i]->current = false;
		}
	
		return $lists;
	}
	
	function CountEntries($id=0)
	{
		$db			=& JFactory::getDBO();
		// Get Count of entries in Categories
		$query = 'SELECT COUNT(id)' .	
			' FROM #__bloggies_entries' .			
			' WHERE `catid` = ' . (int)$id;
		$db->setQuery($query);
		return ($count = $db->loadResult())?$count:0;
	}
}
