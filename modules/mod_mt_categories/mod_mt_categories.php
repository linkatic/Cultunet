<?php
/**
 * @version		$Id: mod_mt_categories.php 576 2009-03-10 11:54:05Z CY $
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
$class_sfx			= $params->get( 'class_sfx', '' );
$moduleclass_sfx	= $params->get( 'moduleclass_sfx', '' );
$show_totalcats		= $params->get( 'show_totalcats', 0 );
$show_totallisting	= $params->get( 'show_totallisting', 0 );
$back_symbol		= htmlspecialchars($params->get( 'back_symbol', '<<' ));

# Try to retrieve current category
$link_id	= JRequest::getInt('link_id');
$cat_id		= JRequest::getInt('cat_id');

$cache =& JFactory::getCache('mod_mt_categories');

$cat_id			= $cache->call( array('modMTCategoriesHelper','getCategoryId'), $link_id, $cat_id );
$categories		= $cache->call( array('modMTCategoriesHelper','getCategories'), $params, $cat_id, $link_id );
$back_category	= $cache->call( array('modMTCategoriesHelper','getBackCategory'), $params, $cat_id, $link_id, empty($categories) );

require(JModuleHelper::getLayoutPath('mod_mt_categories'));
?>