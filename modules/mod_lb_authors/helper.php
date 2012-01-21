<?php
/**
 * LyftenBloggie Authors Module 1.0.2
 * @package LyftenBloggie 1.0.2
 * @copyright (C) 2009 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class modAuthorBlogHelper
{
	function getList(&$params, $module)
	{
		global $mainframe;

		//include the needed helpers
		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php')) return;
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'helper.php');

		$db				=& JFactory::getDBO();
		$lists			= array();
		$limit 			= $params->get('maxAuthorLimit', 0);
		$limit 			= ($limit)?' LIMIT '.$limit:'';
		$cparams 		= & $mainframe->getParams('com_lyftenbloggie');
		$onAuthor		= JRequest::getVar( 'author' );

		// Get Authors
		$query = 'SELECT DISTINCT created_by' .	
			' FROM #__bloggies_entries' .
			' ORDER BY created DESC'
			. $limit
			;
		$db->setQuery($query);
		$latest = $db->loadResultArray();
		if(empty($latest)) return $lists;

		$latest = implode( ',', $latest );

		$query = 'SELECT DISTINCT u.id as user_id, u.name' .	
			' FROM #__bloggies_entries AS e' .
			' LEFT JOIN #__users AS u ON u.id = e.created_by' .
			' WHERE u.block = 0' .
			' AND e.created_by IN ('.$latest.')' .
			' ORDER BY u.id'
			;
		$db->setQuery($query);
		$authors = $db->loadObjectList();

		if(!is_array($authors)) return;

		$i	= 0;
		foreach( $authors as $author)
		{
			$lists[$i]->link 	= LyftenBloggieHelperRoute::getAuthorEntriesRoute($author->user_id);
			$lists[$i]->author 	= (!$author->name) ? modAuthorBlogHelper::_getAuthorName( $author->user_id ) : $author->name;
			$lists[$i]->count 	= modAuthorBlogHelper::_getCountEntries( $author->user_id );
			$lists[$i]->current = ($onAuthor == $author->user_id) ? ' id="current" class="active"' : '';
			$i++;
		}
	
		return $lists;
	}
	
	function _getCountEntries($uid=0)
	{
		$db	=& JFactory::getDBO();
			
		// Get Count of entries for author
		$query = 'SELECT COUNT(id)' .	
			' FROM #__bloggies_entries' .			
			' WHERE `created_by` = ' . (int)$uid;
		$db->setQuery($query);
		return ($count = $db->loadResult())?$count:0;
	}

	function _getAuthorName($uid=0)
	{
		$db	=& JFactory::getDBO();
		$db->setQuery("SELECT name FROM #__users WHERE id='$uid'");
		return $db->loadResult();
	}
}
