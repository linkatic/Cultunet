<?php
/**
 * @version		$Id: mod_mt_dtree.php 576 2009-03-10 11:54:05Z CY $
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
$moduleclass_sfx	= $params->get( 'moduleclass_sfx' );
$root_image			= $params->get( 'root_image', 'components/com_mtree/img/dtree/base.gif' );
$cat_image			= $params->get( 'cat_image', 'components/com_mtree/img/dtree/folder.gif' );
$show_totalcats		= $params->get( 'show_totalcats', 0 );
$show_totallisting	= $params->get( 'show_totallisting', 0 );
$closesamelevel		= $params->get( 'closesamelevel', 1 );
$width				= $params->get( 'width', 159 );
$root_catid			= $params->get( 'root_catid', 0 );
$show_empty_cat		= $params->get( 'show_empty_cat', $mtconf->get('display_empty_cat') );
$show_listings		= $params->get( 'show_listings', 0 );

if ($show_empty_cat == -1) $show_empty_cat = $mtconf->get('display_empty_cat');

# Try to retrieve current category
$link_id	= JRequest::getInt('link_id');
$cat_id		= JRequest::getInt('cat_id');

$cache =& JFactory::getCache('mod_mt_browse');

$uri		=& JURI::getInstance();
$itemid		= modMTDtreeHelper::getItemid();
$cat_id		= $cache->call( array('modMTDtreeHelper','getCategoryId'), $link_id, $cat_id );
$categories	= $cache->call( array('modMTDtreeHelper','getCategories'), $params );
$listings	= $cache->call( array('modMTDtreeHelper','getListings'), $params, $categories );

$link_id	= JRequest::getInt('link_id');

require(JModuleHelper::getLayoutPath('mod_mt_dtree'));
?>