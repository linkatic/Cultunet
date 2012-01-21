<?php
/**
 * @version		$Id: helper.php 884 2010-05-27 11:51:06Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class modMTBrowseHelper {

	function getList( $cat_id, $show_empty_cat ) {
		global $mtconf;
		
		$db =& JFactory::getDBO();
		$itemid = modMTBrowseHelper::getItemid();

		# Retrieve categories
		$sql = 'SELECT cat_id, cat_name, cat_cats, cat_links FROM #__mt_cats WHERE cat_published=1 && cat_approved=1 && cat_parent=\''.$cat_id.'\' ';

		if ( !$show_empty_cat ) { $sql .= ' && ( cat_cats > 0 || cat_links > 0 ) ';	}

		if( $mtconf->get('first_cat_order1') != '' )
		{
			$sql .= ' ORDER BY ' . $mtconf->get('first_cat_order1') . ' ' . $mtconf->get('first_cat_order2');
			if( $mtconf->get('second_cat_order1') != '' )
			{
				$sql .= ', ' . $mtconf->get('second_cat_order1') . ' ' . $mtconf->get('second_cat_order2');
			}
		}

		$db->setQuery( $sql );
		$cats = $db->loadObjectList();
		
		$i = 0;
		foreach( $cats AS $cat )  {
			$cats[$i++]->link = JRoute::_( 'index.php?option=com_mtree&task=listcats&cat_id=' . $cat->cat_id . $itemid );
		}

		return $cats;
	}
	
	function getPathWay( $cat_id ) {
		$db =& JFactory::getDBO();
		
		$itemid = modMTBrowseHelper::getItemid();
		
		# Get Pathway
		$mtPathWay = new mtPathWay( $db );
		$pathway = $mtPathWay->getPathWay( $cat_id );
		$pathway_count = count($pathway);
		
		if( $pathway_count > 0 ) {
			$db->setQuery( 'SELECT cat_id, cat_name FROM #__mt_cats WHERE cat_id IN (' . implode(',',$pathway). ') LIMIT ' . $pathway_count );
			$cats = $db->loadObjectList();
			
			$i = 0;
			foreach( $cats as $cat )
			{
				$cats[$i]->link = JRoute::_( 'index.php?option=com_mtree&task=listcats&cat_id=' . $cat->cat_id . $itemid );
				$i++;
			}

			return $cats;
			
		} else {
			return null;
		}
	}
	
	function getCategory( $cat_id, $link_id ) {
		$db =& JFactory::getDBO();
		
		$itemid = modMTBrowseHelper::getItemid();
		
		if ( $link_id > 0 && $cat_id == 0 ) {
			$db->setQuery( 'SELECT cat_id FROM #__mt_cl WHERE link_id =\''.$link_id.'\' AND main = 1' );
			$cat_id = $db->loadResult();
		}

		$db->setQuery( 'SELECT * FROM #__mt_cats WHERE cat_id = \''.$cat_id.'\' LIMIT 1' );
		$cat = $db->loadObject();
		
		if( !isset($cat->cat_id) ) { $cat->cat_id = 0; }
		$cat->link = JRoute::_( 'index.php?option=com_mtree&task=listcats&cat_id=' . $cat->cat_id . $itemid );
		
		return $cat;
	}

	function getItemid() {
		$menu 	= &JSite::getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_mtree');
		return isset($items[0]) ? '&Itemid='.$items[0]->id : '';
	}
	
}