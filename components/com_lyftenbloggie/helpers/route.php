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

// Component Helper
jimport('joomla.application.component.helper');

class LyftenBloggieHelperRoute
{
	function getAccountRoute($layout = null)
	{
		$needles = array(
			'author'  => $layout
		);

		$layout = ($layout)? '&layout='.$layout : '';

		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&view=author'.$layout;

		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	/**
	 * @param	int	The route of the entry item
	 */
	function getEntryRoute($archive, $slug)
	{
		$needles = array(
			'archive'  	=> $archive,
			'entry'  	=> $slug
		);

		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&view=entry'.$archive.'&id='. $slug;

		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	/**
	 * @param	int	The route of the Author
	 */
	function getAuthorEntriesRoute($id)
	{
		$needles = array(
			'lyftenbloggie'  => null
		);

		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&author='. $id;

		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	function getNewEntryRoute()
	{
		$needles = array(
			'id'  => '0'
		);

		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&view=author&layout=form';

		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	function getMyDetailsRoute()
	{
		$needles = array(
			'view'  => 'mydetails'
		);

		$link = 'index.php?option=com_lyftenbloggie&view=mydetails';

		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	function getCommentFeedRoute($id, $type)
	{
		$needles = array(
			'id'  => (int) $id,
			'type'  => $type
		);

		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&task=feed&type='.$type.'&id='. $id;
	
		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	function getCategoryRoute($catname)
	{
		$catname = strtolower($catname);

		$needles = array(
			'category'	=> $catname
		);

		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&category='.$catname;

		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}
	
	function getEntryCatRoute($catname, $id)
	{
		$catname = strtolower($catname);

		$needles = array(
			'category'	=> $catname,
			'entry'		=> $id
		);

		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&view=entry&category='.$catname.'&id='. $id;

		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}
	
	function getTagRoute($tag)
	{
		$needles = array(
			'tag' => $tag
		);

		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&tag='.$tag;

		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}
	
	function getBlogFeedRoute($type)
	{
		$needles = array(
			'format' => 'feed',
			'type' => $type
		);

		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&task=feed&type='.$type;
		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};
		return $link;
	}
	
	function getArchiveRoute($year, $month, $day, $mn)
	{
		$needles = array(
			'lyftenbloggie'	=> ''
		);
		//Create the link
		$link = 'index.php?option=com_lyftenbloggie&year='.$year.'&month='.$month.'&day='.$day.'&mn='.$mn;

		if($item = LyftenBloggieHelperRoute::_findItem($needles)) {
			$link .= '&Itemid='.$item->id;
		};

		return $link;
	}

	function _findItem($needles)
	{
		$component =& JComponentHelper::getComponent('com_lyftenbloggie');

		$menus	= &JApplication::getMenu('site', array());
		$items	= $menus->getItems('componentid', $component->id);
		$user 	= & JFactory::getUser();
		$access = (int)$user->get('aid');

		$match = null;

		foreach($needles as $needle => $id)
		{
			if(empty($items)) return;
			
			foreach($items as $item)
			{

				//Check Categories
				if (isset($item->query['category']) && $needle == 'category' && @$item->query['category'] == $id) {
					$match = $item;
					break;
				}

				//Check Account
				if (isset($item->query['author']) && $needle == 'author' && @$item->query['layout'] == $id) {
					$match = $item;
					break;
				}

				if ($needle != 'category' && (@$item->query['view'] == $needle) && (@$item->query['id'] == $id)) {
					$match = $item;
					break;
				}			
			}

			//no menuitem exists -> return first possible match
			if(!$match)
			{
				foreach($items as $item)
				{
					if (@$item->published == 1 && @$item->access <= $access && @$item->query['category'] == '0' && @$item->query['view'] != 'favourites' && @$item->query['layout'] != 'form') {
						$match = $item;
						break;
					}
				}
			}
			
			if(isset($match)) {
				break;
			}
		}
		return $match;
	}
}
?>
