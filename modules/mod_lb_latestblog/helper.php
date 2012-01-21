<?php
/**
 * LyftenBloggie Latest Blog Module 1.1.0
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class modLatestBlogHelper
{
	function getList(&$params, $module)
	{
		global $mainframe;

		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php')) return;
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');
		
		//initialize variables
		$document 	= & JFactory::getDocument();
		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$limitTitle	= $params->get( 'limitTitle', 0 );
		$showAuthor	= $params->get( 'showAuthor', 1 );
		$user_id	= $params->get( 'user_id' );
		$cparams 	= & $mainframe->getParams('com_lyftenbloggie');
		$count		= (int) $params->get('count', 5);
		$catid		= trim( $params->get('catid') );
		$gid		= (int)$user->get('gid');
		$gid		= (!$gid ? 1 : $gid);
		$lists		= array();

		$lists['color']	= $params->get( 'color', '#2E2E2E' );

		$nullDate	= $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();

		$where		= 'e.state = 1';

		//add header files
		$document->addStyleSheet(JURI::base().'modules/mod_lb_latestblog/assets/style.css');
		
		// User Filter
		if($user_id)
		{
			$user_id = str_replace (" ", "", trim($user_id, ','));
			$where .= ' AND created_by IN ('. $user_id .')';
		}

		// Ordering
		switch ($params->get( 'ordering' ))
		{
			case 'm_dsc':
				$ordering		= 'e.modified DESC, e.created DESC';
				break;
			case 'c_dsc':
			default:
				$ordering		= 'e.created DESC';
				break;
		}

		if ($catid)
		{
			$ids = explode( ',', $catid );
			JArrayHelper::toInteger( $ids );
			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
		}

		// Get Authors
		if($cparams->get('authorLevel', 'LB') == 'LB')
		{
			$authors 		= ' LEFT JOIN #__bloggies_authors AS a ON a.user_id = e.created_by';
		}else{
			$authors 		= ' LEFT JOIN #__users AS a ON a.id = e.created_by';
		}
		
		// Content Items only
		$query = 'SELECT e.id, e.title, e.created, e.access, a.id as authorid, cc.title as cat_title, a.name as author,' .
			' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(":", e.id, e.alias) ELSE e.id END as slug,' .
			' CASE WHEN CHAR_LENGTH(cc.slug) THEN cc.slug ELSE 0 END as catslug' .
			' FROM #__bloggies_entries AS e' .
			' LEFT JOIN #__bloggies_categories AS cc ON cc.id = e.catid' .			
			$authors .
			' WHERE '. $where .
			($catid ? $catCondition : '').
			' AND (cc.published = 1 OR e.catid = 0)' .
			' ORDER BY '. $ordering;
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

		$i		= 0;

		foreach ( $rows as $row )
		{
			if($row->access <= $gid)
			{
				$row->cat_title = ($row->cat_title) ? $row->cat_title : JText::_('Uncategorized');
				$row->catslug 	= ($row->catslug) ? $row->catslug : strtolower(JText::_('Uncategorized'));
				$lists['data'][$i]->created		= '<p>'.strtoupper(JHTML::_('date', $row->created, '%b')).'<br /> <span>'.JHTML::_('date', $row->created, '%d').'</span></p>';
				$lists['data'][$i]->archive 	= JHTML::_('date',  $row->created, '&year=%Y&month=%m&day=%d');
				$lists['data'][$i]->link 		= JRoute::_(LyftenBloggieHelperRoute::getEntryRoute($lists['data'][$i]->archive, $row->slug));
				$lists['data'][$i]->cat_title	= $row->cat_title;
				$lists['data'][$i]->cat_url		= JRoute::_(LyftenBloggieHelperRoute::getCategoryRoute($row->catslug));
				$lists['data'][$i]->author 		= ($showAuthor) ? $row->author : '';
				$lists['data'][$i]->author_url 	= JRoute::_(LyftenBloggieHelperRoute::getAuthorEntriesRoute($row->authorid));
			} else {
				$lists['data'][$i]->link 	= JRoute::_('index.php?option=com_user&view=login');
			}
			
			//Shorten Title
			if($limitTitle && strlen($row->title) > $limitTitle){
				$text = $row->title." ";
				$text = substr($text,0,$limitTitle);
				$text = substr($text,0,strrpos($text,' '));
				$row->title = $text."...";
			}
			
			$lists['data'][$i]->text 		= htmlspecialchars( $row->title );
			$i++;
		}
		return $lists;
	}
}
