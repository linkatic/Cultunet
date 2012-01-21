<?php
/**
 * @version		$Id: mod_mt_menu.php 699 2009-05-13 02:17:46Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');
include( JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'init.php');

$my			=& JFactory::getUser();
$cat_id		= JRequest::getInt('cat_id', 0, 'get');
$link_id	= JRequest::getInt('link_id', 0, 'get');
$user_id	= JRequest::getInt('user_id', 0);

// Get params
$class_sfx						= $params->get( 'class_sfx' );
$moduleclass_sfx				= $params->get( 'moduleclass_sfx' );
$limit_toplist 					= $params->get( 'limit_toplist', 0 );
$show['addlisting_force']		= $params->get( 'show_addlisting_force', 0 );
$show['home'] 					= $params->get( 'show_home', 0 );
$show['browse'] 				= $params->get( 'show_browse', 1 );
$show['addlisting'] 			= $params->get( 'show_addlisting', 1 );
$show['addcategory'] 			= $params->get( 'show_addcategory', 1 );
$show['mypage'] 				= $params->get( 'show_mypage', 1 );
$show['myfavourites']			= $params->get( 'show_myfavourites', 1 );
$show['newlisting'] 			= $params->get( 'show_newlisting', 1 );
$show['recentlyupdatedlisting']	= $params->get( 'show_recentlyupdatedlisting', 1 );
$show['mostfavoured']			= $params->get( 'show_mostfavoured', 1 );
$show['featuredlisting']		= $params->get( 'show_featuredlisting', 1 );
$show['popularlisting']			= $params->get( 'show_popularlisting', 1 );
$show['mostratedlisting']		= $params->get( 'show_mostratedlisting', 1 );
$show['topratedlisting'] 		= $params->get( 'show_topratedlisting', 1 );
$show['mostreviewedlisting']	= $params->get( 'show_mostreviewedlisting', 1 );

// Get Link's category
if ( $link_id > 0 && $cat_id == 0 ) {
	$cat_id = modMTMenuHelper::getCatId( $link_id );
}	

$toplist_cat_id			= modMTMenuHelper::getTopListCatId( $cat_id, $params );

$cache =& JFactory::getCache('mod_mt_menu');
$cat_allow_submission = $cache->call(array('modMTMenuHelper','getCatAllowSubmission'), $params, $cat_id);

$itemid					= modMTMenuHelper::getItemid();
$active					= modMTMenuHelper::getActive();

require(JModuleHelper::getLayoutPath('mod_mt_menu'));

?>
