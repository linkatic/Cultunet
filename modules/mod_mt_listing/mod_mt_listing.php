<?php
/**
 * @version		$Id: mod_mt_listing.php 576 2009-03-10 11:54:05Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');
include( JPATH_ROOT . DS.'components'.DS.'com_mtree'.DS.'init.php');
require_once( JPATH_ROOT . DS.'administrator'.DS.'components'.DS.'com_mtree'.DS.'admin.mtree.class.php');

global $mtconf;

// Retrieve current category and link's ID
$cat_id 	= JRequest::getInt( 'cat_id', 0 );
$link_id 	= JRequest::getInt( 'link_id', 0 );

// Get params
$moduleclass_sfx	= $params->get( 'moduleclass_sfx' );
$listingclass		= $params->get( 'listingclass', '' );
$type				= $params->get( 'type', 1 ); // Default is new listing
$count				= $params->get( 'count', 5 );
$show_from_cat_id	= $params->get( 'show_from_cat_id', 0 );
$only_subcats		= $params->get( 'only_subcats', 0 );
$shuffle_listing	= $params->get( 'shuffle_listing', 1 );
$show_more			= $params->get( 'show_more', 1 );
$caption_showmore	= $params->get( 'caption_showmore', 'Show more...' );
$show_website		= $params->get( 'show_website', 1 );
$show_category		= $params->get( 'show_category', 1 );
$show_rank			= $params->get( 'show_rank', 1 );
$show_rel_data		= $params->get( 'show_rel_data', 1 );
$show_images		= $params->get( 'show_images', 0 );

// Disable show more when showing randomg listing
if( $type == 8 ) {
	$show_more = 0;
}

$cache =& JFactory::getCache('mod_mt_listing'.'_catid'.$cat_id.'_linkid'.$link_id);
$limit_cat_to = $cache->call(array('modMTListingHelper','getCatIdFilter'), $params, $cat_id, $link_id);
$listings =	$cache->call(array('modMTListingHelper','getList'), $params, $limit_cat_to);

$fields	= modMTListingHelper::getFields( $params, $listings );
$itemid	= modMTListingHelper::getItemid();
$ltask	= modMTListingHelper::getModuleTask( $type );

$show_more_link = '';
if( !empty($ltask) ) {
	$show_more_link 	= JRoute::_( 'index.php?option=com_mtree&task='.$ltask.'&' . (($only_subcats) ? 'cat_id='.$cat_id : (($show_from_cat_id) ? 'cat_id='.$show_from_cat_id : '') ). $itemid);	
}

require(JModuleHelper::getLayoutPath('mod_mt_listing'));
?>