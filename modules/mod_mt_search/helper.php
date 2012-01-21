<?php
/**
 * @version		$Id: helper.php 580 2009-03-12 03:55:02Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class modMTSearchHelper {

	function getCategories( $params ) {
		$db =& JFactory::getDBO();
		
		$showCatDropdown= intval( $params->get( 'showCatDropdown', 0 ) );
		$parent_cat_id		= intval( $params->get( 'parent_cat', 0 ) );
		
		if ( $showCatDropdown == 1 && $parent_cat_id >= 0 ) {
			$db->setQuery( 'SELECT cat_id, cat_name FROM #__mt_cats '
				. ' WHERE cat_approved=1 AND cat_published=1 AND cat_parent = ' . $parent_cat_id
				. ' ORDER BY cat_name ASC' );
			$categories = $db->loadObjectList();
			return $categories;
		} else {
			return null;
		}
	}
	
	function getItemid() {
		$menu 	= &JSite::getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_mtree');
		return isset($items[0]) ? '&Itemid='.$items[0]->id : '';
	}
}