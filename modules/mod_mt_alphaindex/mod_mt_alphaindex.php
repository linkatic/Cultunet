<?php
/**
 * @version		$Id: mod_mt_alphaindex.php 576 2009-03-10 11:54:05Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');

# Get params
$class_sfx				= $params->get( 'class_sfx' );
$moduleclass			= $params->get( 'moduleclass',			'mainlevel'	);
$direction				= $params->get( 'direction',			'vertical'	);
$show_number			= $params->get( 'show_number',			1	);
$display_total_links	= $params->get( 'display_total_links',	0	);
$show_empty				= $params->get( 'show_empty',			0	);
$seperator				= $params->get( 'seperator',			'&nbsp;'	);
$moduleclass_sfx		= $params->get( 'moduleclass_sfx',		''	);

$list	= modMTAlphaindexHelper::getList($params);

if( $direction == 'horizontal' ) {
	require(JModuleHelper::getLayoutPath('mod_mt_alphaindex', $direction));
} else {
	require(JModuleHelper::getLayoutPath('mod_mt_alphaindex'));
}
?>