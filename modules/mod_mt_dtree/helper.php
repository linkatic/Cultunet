<?php
/**
 * @version		$Id: helper.php 883 2010-05-27 11:32:45Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class modMTDtreeHelper {
	
	function getListings( $params, $categories ) {
		$root_catid		= $params->get( 'root_catid', 0 );
		$show_listings	= $params->get( 'show_listings', 0 );
		$listing_order1	= $params->get( 'listing_order1', 'link_name' );
		$listing_order2	= $params->get( 'listing_order2', 'desc' );

		$db 		=& JFactory::getDBO();
		$nullDate	= $db->getNullDate();

		# Get all links
		if ($show_listings ) {
			$jdate = JFactory::getDate();
			$now = $jdate->toMySQL();

			$cat_ids = array();
			foreach( $categories AS $cc ) {
				$cat_ids[] = $cc->cat_id;
			}

			$cat_ids[] = $root_catid;

			$db->setQuery( "SELECT l.link_id, l.link_name, cl.cat_id FROM #__mt_links AS l, #__mt_cl AS cl " 
				.	"\n WHERE l.link_published='1' && l.link_approved='1' "
				.	"\n AND ( l.publish_up = ".$db->Quote($nullDate)." OR publish_up <= '$now'  ) "
				.	"\n AND ( l.publish_down = ".$db->Quote($nullDate)." OR publish_down >= '$now' ) "
				.	"\n AND l.link_id = cl.link_id AND cl.cat_id IN ('".implode("','",$cat_ids)."') AND cl.main = '1'"
				.	"\n ORDER BY ".$listing_order1." ".$listing_order2
			);
			return $db->loadObjectList('link_id');
		} else {
			return null;
		}
	}
	
	function getCategories( $params ) {
		$root_catid	= $params->get( 'root_catid', 0 );
		$cat_level	= $params->get( 'cat_level', 2 );

		return modMTDtreeHelper::getChildrenCategories( $root_catid, $cat_level, $params );
	}
	
	function getChildrenCategories( $cat_id, $cat_level, $params ) {
		global $mtconf;

		$category_order1	= $params->get( 'category_order1', 'cat_name' );
		$category_order2	= $params->get( 'category_order2', 'desc' );
		$show_empty_cat		= $params->get( 'show_empty_cat', $mtconf->get('display_empty_cat') );
		if ($show_empty_cat == -1) $show_empty_cat = $mtconf->get('display_empty_cat');

		$db =& JFactory::getDBO();

		$cat_ids = array();

		if ( $cat_level > 0  ) {

			$sql = "SELECT cat_id, cat_name, cat_parent, cat_cats, cat_links FROM #__mt_cats WHERE cat_published=1 && cat_approved=1 && cat_parent='".$cat_id."' ";
			if ( !$show_empty_cat ) { 
				$sql .= "&& ( cat_cats > 0 || cat_links > 0 ) ";	
			}
			$sql .= "\nORDER BY $category_order1 $category_order2";

			$db->setQuery( $sql );

			$cat_ids = $db->loadObjectList();

			if ( count($cat_ids) ) {
				foreach( $cat_ids AS $cid ) {
					$children_ids = modMTDtreeHelper::getChildrenCategories( $cid->cat_id, ($cat_level-1), $params );
					$cat_ids = array_merge( $cat_ids, $children_ids );
				}
			}
		}

		return $cat_ids;		
	}
	
	function getCategoryId( $link_id, $cat_id ) {
		$db =& JFactory::getDBO();
		
		if ( $link_id > 0 && $cat_id == 0 ) {
			$db->setQuery( 'SELECT cat_id FROM #__mt_cl WHERE link_id =\''.$link_id.'\' AND main = 1' );
			$cat_id = $db->loadResult();
		}

		return $cat_id;
	}
	
	function getItemid() {
		$menu 	= &JSite::getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_mtree');
		return isset($items[0]) ? '&Itemid='.$items[0]->id : '';
	}
	
}