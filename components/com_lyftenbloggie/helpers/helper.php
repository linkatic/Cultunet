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
 * LyftenBloggie Helper class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.0.0
 **/
class EntriesHelper
{
	/**
	 *	Create catergory link from ID
	 */ 
	function getCatLinks($id)
	{
		$database = & JFactory::getDBO();

		$query = 'SELECT id, title, slug'
				.' FROM #__bloggies_categories'
				.' WHERE id = ' . $id
				;
		$database->setQuery($query);
		$cat = $database->loadObject();
		if ($cat) {
			$link = '<a href="'.JRoute::_(LyftenBloggieHelperRoute::getCategoryRoute($cat->slug)).'" title="'.JText::sprintf('VIEW ALL POSTS IN', $cat->title).'" rel="category">'.$cat->title.'</a>';		
		} else {
			$link = '<a href="'.JRoute::_(LyftenBloggieHelperRoute::getCategoryRoute(JText::_('UNCATEGORIZED'))).'" title="'.JText::sprintf('VIEW ALL POSTS IN', JText::_('UNCATEGORIZED')).'" rel="category">'.JText::_('UNCATEGORIZED').'</a>';
		}

		return $link;
	}

	/**
	 *	Create tag links from entry ID
	 */ 
	function getTagLinks($id, $linkTags = true)
	{
		$database = & JFactory::getDBO();
		$query = 'SELECT t.name, t.slug'
				.' FROM #__bloggies_relations AS r'
				.' LEFT JOIN #__bloggies_tags AS t ON t.id = r.tag'
				.' WHERE r.entry='.$id;
		$database->setQuery($query);
		$used = $database->loadObjectList();

		$tags = '';
		$n = count($used);
		for( $i = 0, $n; $i < $n; $i++ )
		{
			$tags .= ', <a href="'.JRoute::_(LyftenBloggieHelperRoute::getTagRoute($used[$i]->slug)).'" rel="tag">'.$used[$i]->name.'</a>';
		}

		return trim($tags, ',');
	}

	/**
	 * Method to fetch the subcategories
	 **/
	function getBookmarks(&$entry, $addThisPubId=null)
	{
		global $mainframe;
		$uri    	=& JURI::getInstance();
		$database 	= & JFactory::getDBO();
		$entryID 	= $entry->id;
		$title		= $entry->title;
		$desc		= $entry->metadesc;

		$query = 'SELECT type, html'
					. ' FROM #__bloggies_bookmarks'
					. ' WHERE published = 1'
					. ' GROUP BY id'
					;
		$database->setQuery($query);
		$Bmarks = $database->loadObjectList();

		//clean url
		$url = LyftenBloggieHelperRoute::getEntryRoute($entry->archive, $entry->slug);

		//Make URL SEF
        $url = BloggieFactory::getSEFLink($url);

		// Build html for top and bottom button groups
		$badge 	= '';
		$button = '';
		$tt		=1;
		$i		=0;
		foreach ($Bmarks as $Bmark)
		{
			if ($Bmark->type == 'badge'){
				if($badge) $badge .= '<br />';
				$badge .= $Bmark->html;
			}else{
				$button .= $Bmark->html;
				if($tt==9) {
					$button .= '</p><p>';
					$tt=0;
				}
				$tt++;
				$i++;
			}
		}

		// Replace keystrings in badge and button html
		$bookmarks['badge'] 	= EntriesHelper::_replaceParams($badge, $url, $title, $desc, $addThisPubId);
		$bookmarks['button'] 	= EntriesHelper::_replaceParams($button, $url, $title, $desc, $addThisPubId);

		return $bookmarks;
	}

	function _replaceParams($text, $url, $title, $desc, $addThisPubId=null)
	{
		$text = str_replace('***url***', str_replace("'", "", $url), $text);
		$text = str_replace('***url_encoded***', "' + encodeURIComponent('". str_replace("'", "", $url) ."') + '", $text);
		$text = str_replace('***title***', str_replace("'", "", $title), $text);
		$text = str_replace('***title_encoded***', "' + encodeURIComponent('". str_replace("'", "", $title) ."') + '", $text);
		$text = str_replace('***description***', str_replace("'", "", $desc), $text);
		$text = str_replace('***description_encoded***', "' + encodeURIComponent('". str_replace("'", "", $desc) ."') + '", $text);
		$text = str_replace('***imageDirectory***', BLOGGIE_SITE_URL.'/addons/themes/system/images/bookmarks', $text);
		$text = str_replace('***bgcolor***', '#ffffff', $text);
		$text = str_replace('***addThisPubId***', $addThisPubId, $text);

		return $text;
	}
	
	/**
	 * Closes any open HTML Tags
	* */ 
	function cleanHTML($var)
	{
		preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU",$var,$opened);
		preg_match_all("#</([a-z]+)>#iU",$var,$closed);
		$OpenedCount = count($opened[1]);

		if(count($closed[1]) == $OpenedCount) return $var;

		$opened = array_reverse($opened[1]);
		for($i=0;$i < $OpenedCount;$i++) {
			if (!in_array($opened[$i],$closed[1]) && $opened[$i] != 'img'){
				$var .= '</'.$opened[$i].'>';
			} else {
				unset($closed[1][array_search($opened[$i],$closed[1])]);
			}
		}
		return $var;
	}
}
?>