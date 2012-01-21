<?php
/**
 * @version		$Id: mod_mt_filtersearch.php 15-08-2010 $
 * @package		Mosets Tree
 * @copyright	(C) 2010 Linkatic. All rights reserved.
 * @license		GNU General Public License
 * @author		Vicente Gimeno Quiles <vgimeno@linkatic.com>
 * @url			http://www.linkatic.com
 */
 
defined('_JEXEC') or die('Restricted access');

include( JPATH_ROOT . DS.'components'.DS.'com_mtree'.DS.'init.php');
require_once (dirname(__FILE__).DS.'helper.php');

$cat_id = JRequest::getVar( 'cat_id' );

$moduleclass_sfx= $params->get( 'moduleclass_sfx' );
$width 			= intval( $params->get( 'width', 16 ) );
$text 			= $params->get( 'text', JTEXT::_('search...') );
$advsearch 		= intval( $params->get( 'advsearch', 1 ) );
$search_button	= intval( $params->get( 'search_button', 1 ) );
$dropdownWidth	= intval( $params->get( 'dropdownWidth', 0 ) );
$parent_cat_id	= intval( $params->get( 'parent_cat', 0 ) );

$itemid		= modMTFilterSearchHelper::getItemid();
$categories	= modMTFilterSearchHelper::getCategories( $params );
//$extraFields = modMTFilterSearchHelper::getExtrafields();

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

//Tipos
$options	= modMTFilterSearchHelper::getSelectOptions( $cat_id );

//Areas
$options_area	= modMTFilterSearchHelper::getAreas();

//Paises
$options_paises	= modMTFilterSearchHelper::getPaises();

//Idiomas
$options_idiomas= modMTFilterSearchHelper::getIdiomas();

//Tipo de anuncios
$options_tipo_anuncios= modMTFilterSearchHelper::getTipoAnuncios();


if( $advsearch ) {
	$advsearch_link = JRoute::_( 'index.php?option=com_mtree&task=advsearch' . $itemid );
}

require(JModuleHelper::getLayoutPath('mod_mt_filtersearch'));
?>