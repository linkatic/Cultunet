<?php
/**
 * LyftenBloggie Latest Blog Module 1.0.2
 * @package LyftenBloggie 1.0.2
 * @copyright (C) 2009 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class modLatestPopBlogHelper
{
	function getList(&$params, $module)
	{
		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php')) return;
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');

		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'extend.php')) return;
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'extend.php');
		
		//initialize variables
		$document 	= & JFactory::getDocument();
		$mWidth		= $params->get( 'module_width', 280 );
		$user_id	= $params->get( 'user_id' );
		$moduleType	= $params->get( 'moduleType', 'recent' );
		$list		= array();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();

		//add header files
		$document->addStyleSheet(JURI::base().'modules/mod_lb_latestpop/style.css');

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

		//Get Latest
		if($moduleType == 'recent') {
			$rows	= modLatestPopBlogHelper::getEntries($params, $ordering);
			$list = modLatestPopBlogHelper::prepareEntries($rows, $params);
		}else if($moduleType == 'popular') {
			//Get Popular
			$rows	= modLatestPopBlogHelper::getEntries($params, 'e.hits DESC');
			$list = modLatestPopBlogHelper::prepareEntries($rows, $params);
		}

		return $list;
	}

	function getEntries($params, $ordering)
	{
		global $mainframe;

		//initialize variables
		$cparams 	= & $mainframe->getParams('com_lyftenbloggie');
		$count		= (int) $params->get('count', 5);
		$catid		= trim( $params->get('catid') );
		$db			=& JFactory::getDBO();
		$where		= 'e.state = 1';

		if ($catid)
		{
			$ids = explode( ',', $catid );
			JArrayHelper::toInteger( $ids );
			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
		}
		
		// Content Items only
		$query = 'SELECT e.id, e.introtext, e.title, e.created, e.access,' .
			' cc.title as cattitle, a.name as author, e.image, e.created_by,' .
			' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(":", e.id, e.alias) ELSE e.id END as slug,' .
			' CASE WHEN CHAR_LENGTH(cc.slug) THEN cc.slug ELSE 0 END as catslug' .
			' FROM #__bloggies_entries AS e' .
			' LEFT JOIN #__bloggies_categories AS cc ON cc.id = e.catid' .			
			' LEFT JOIN #__users AS a ON a.id = e.created_by' .
			' WHERE '. $where .
			($catid ? $catCondition : '').
			' AND (cc.published = 1 OR e.catid = 0)' .
			' ORDER BY '. $ordering;
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();
		
		return $rows;
	}
	
	function prepareEntries($rows, $params)
	{
	
		//initialize variables
		$i			= 0;
		$list		= array();
		$user		=& JFactory::getUser();
		$gid		= (int)$user->get('gid');
		$gid		= (!$gid ? 1 : $gid);
		$showAuthor	= $params->get( 'showAuthor', 1 );
		$limitTitle	= $params->get( 'limitTitle', 0 );
		$image		= $params->get( 'image', 'image' );

		foreach ( $rows as $row )
		{
			$row = BloggieFactory::prepareEntry($row, $image);
	
			$list[$i]->created		= '<p>'.strtoupper(JHTML::_('date', $row->created_nf, '%b')).'<br /> <span>'.JHTML::_('date', $row->created_nf, '%d').'</span></p>';
			$list[$i]->archive 		= JHTML::_('date',  $row->created_nf, '&year=%Y&month=%m&day=%d');
			$list[$i]->link 		= JRoute::_(LyftenBloggieHelperRoute::getEntryRoute($list[$i]->archive, $row->slug));
			$list[$i]->cat_title	= $row->cattitle;
			$list[$i]->cat_url		= JRoute::_(LyftenBloggieHelperRoute::getCategoryRoute($row->catslug));
			$list[$i]->author 		= ($showAuthor) ? $row->author->username : '';
			$list[$i]->author_url 	= JRoute::_(LyftenBloggieHelperRoute::getAuthorEntriesRoute($row->created_by));

			$list[$i]->mainImage 	= $row->mainImage;
			if($row->access > $gid) $list[$i]->link 	= JRoute::_('index.php?option=com_user&view=login');

			//Shorten Title
			if($limitTitle && strlen($row->title) > $limitTitle){
				$text = $row->title." ";
				$text = substr($text,0,$limitTitle);
				$text = substr($text,0,strrpos($text,' '));
				$row->title = $text."...";
			}
			
			$list[$i]->text = htmlspecialchars( $row->title );
			$i++;
		}
		
		return $list;
	}	
}