<?php
/**
 * @version		$Id: mod_mt_lastupdate.php 576 2009-03-10 11:54:05Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

include( JPATH_ROOT . DS.'components'.DS.'com_mtree'.DS.'init.php');
require_once (dirname(__FILE__).DS.'helper.php');

# Get params
$moduleclass_sfx	= $params->get( 'moduleclass_sfx' );
$caption			= $params->get( 'caption', '%s' );

$last_update		= modMTLastupdateHelper::getLastUpdate( $params );

require(JModuleHelper::getLayoutPath('mod_mt_lastupdate'));