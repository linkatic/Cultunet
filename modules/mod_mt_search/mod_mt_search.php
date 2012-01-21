<?php
/**
 * @version		$Id: mod_mt_search.php 741 2009-07-10 14:43:21Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

include( JPATH_ROOT . DS.'components'.DS.'com_mtree'.DS.'init.php');
require_once (dirname(__FILE__).DS.'helper.php');

$moduleclass_sfx= $params->get( 'moduleclass_sfx' );
$width 			= intval( $params->get( 'width', 16 ) );
$text 			= $params->get( 'text', JTEXT::_('search...') );
$advsearch 		= intval( $params->get( 'advsearch', 1 ) );
$search_button	= intval( $params->get( 'search_button', 1 ) );
$dropdownWidth	= intval( $params->get( 'dropdownWidth', 0 ) );
$parent_cat_id	= intval( $params->get( 'parent_cat', 0 ) );

$itemid		= modMTSearchHelper::getItemid();
$categories	= modMTSearchHelper::getCategories( $params );

$lists = array();
if( $categories ) {
	$all_category = new stdClass();
	$all_category->cat_id = $parent_cat_id;
	$all_category->cat_name = JText::_( 'All categories' );
	array_unshift( $categories, $all_category);
	$lists['categories'] = JHTML::_('select.genericlist', $categories, 'cat_id', 'class="inputbox"' . (($dropdownWidth>0) ? ' style="width:'.$dropdownWidth.'px;"' : ''), 'cat_id', 'cat_name', $parent_cat_id );
} else {
	$lists['categories'] = null;
}

if( $advsearch ) {
	$advsearch_link = JRoute::_( 'index.php?option=com_mtree&task=advsearch' . $itemid );
}

require(JModuleHelper::getLayoutPath('mod_mt_search'));
?>