<?php
/**
 * @version		$Id: mod_mt_votedbest.php 576 2009-03-10 11:54:05Z CY $
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
$show_more			= $params->get( 'show_more', 1 );
$caption_showmore	= $params->get( 'caption_showmore', 'Show more...' );
$caption_rank		= $params->get( 'caption_rank', 'Rank' );
$show_header		= $params->get( 'show_header', 1 );
$use_alternating_bg	= $params->get( 'use_alternating_bg', 0 );
$moduleclass_sfx	= $params->get( 'moduleclass_sfx' );
$order["rank"]		= $params->get( 'order_rank', 1 );
$order["name"]		= $params->get( 'order_name', 2 );
$order["category"]	= $params->get( 'order_category', 0 );
$order["rating"]	= $params->get( 'order_rating', 0 );
$order["votes"]		= $params->get( 'order_votes', 0 );

$cat_id				= modMTVotedbestHelper::getCategoryId();
$listings			= modMTVotedbestHelper::getList( $params, $cat_id );
$itemid 			= modMTVotedbestHelper::getItemid();

$show_more_link 	= JRoute::_( 'index.php?option=com_mtree&task=listtoprated&cat_id=' . $cat_id . $itemid );

require(JModuleHelper::getLayoutPath('mod_mt_votedbest'));
?>