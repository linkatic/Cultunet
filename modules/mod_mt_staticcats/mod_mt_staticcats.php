<?php
/**
 * @version		$Id: mod_mt_staticcats.php 576 2009-03-10 11:54:05Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

include( JPATH_ROOT . DS.'components'.DS.'com_mtree'.DS.'init.php');
require_once (dirname(__FILE__).DS.'helper.php');

$class_sfx			= $params->get( 'class_sfx',		'' );
$moduleclass_sfx 	= $params->get( 'moduleclass_sfx' );
$show_totalcats		= $params->get( 'show_totalcats',	0 );
$show_totallisting	= $params->get( 'show_totallisting',1 );

$categories		= modMTStaticcatsHelper::getCategories( $params );
$cat_id			= modMTStaticcatsHelper::getCategoryId();

require(JModuleHelper::getLayoutPath('mod_mt_staticcats'));
?>