<?php
/**
 * @version		$Id: mod_mt_browse.php 576 2009-03-10 11:54:05Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

include( JPATH_ROOT . DS.'components'.DS.'com_mtree'.DS.'init.php');
require_once( JPATH_ROOT . DS.'administrator'.DS.'components'.DS.'com_mtree'.DS.'admin.mtree.class.php');
require_once (dirname(__FILE__).DS.'helper.php');

# Get params
$class_sfx			= $params->get( 'class_sfx' );
$layout				= $params->get( 'layout',				'default' );
$root_class			= $params->get( 'root_class',			'mainlevel' );
$subcat_class		= $params->get( 'subcat_class',			'sublevel' );
$currentcat_class	= $params->get( 'currentcat_class',		'sublevel' );
$closedcat_class	= $params->get( 'closedcat_class',		'sublevel' );
$show_totalcats		= $params->get( 'show_totalcats',		0 );
$show_totallisting	= $params->get( 'show_totallisting',	0 );
$show_empty_cat		= $params->get( 'show_empty_cat',		$mtconf->get('display_empty_cat') );
$moduleclass_sfx	= $params->get( 'moduleclass_sfx',		'' );

# Try to retrieve current category
$link_id	= JRequest::getInt('link_id');
$cat_id		= JRequest::getInt('cat_id');;

if ($show_empty_cat == -1) $show_empty_cat = $mtconf->get('display_empty_cat');

$spacer = '<img src="components/com_mtree/img/dtree/empty.gif" align="left" vspace="0" hspace="0" />';

$cache =& JFactory::getCache('mod_mt_browse');

$itemid		= modMTBrowseHelper::getItemid();
$cat		= $cache->call( array('modMTBrowseHelper','getCategory'), $cat_id, $link_id );
$cats		= $cache->call( array('modMTBrowseHelper', 'getList'), $cat->cat_id, $show_empty_cat );
$pathway	= $cache->call( array('modMTBrowseHelper', 'getPathWay'),  $cat->cat_id );

if( $cat->cat_id == 0 ) {
	$cat = null;
}

$root		= new stdClass();
$root->link = JRoute::_( 'index.php?option=com_mtree&task=listcats&cat_id=0' . $itemid );
$root->name = JText::_( 'Root' );

require(JModuleHelper::getLayoutPath('mod_mt_browse',$layout));
?>