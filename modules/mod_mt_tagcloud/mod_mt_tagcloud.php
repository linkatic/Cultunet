<?php
/**
 * @version		$Id$
 * @package		Mosets Tree
 * @copyright	(C) 2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');

$class_sfx		= $params->get( 'class_sfx', '' );
$maxTags 		= $params->get( 'maxTags', 30 );
$cf_id 			= $params->get( 'cf_id', 28 );
$hide_list		= $params->get( 'hide_list', '' );

$cache =& JFactory::getCache('mod_mt_tagcloud');

$arrTags = $cache->call(array('modMTTagCloudHelper','getTags'), $cf_id);
$rawTags =	$cache->call(array('modMTTagCloudHelper','parse'), $arrTags);
$itemid  = modMTTagCloudHelper::getItemid();

if( !empty($hide_list) )
{
	$hideTags = modMTTagCloudHelper::explodeTrim($hide_list);

	if( !empty($hideTags) )
	{
		$rawTags = array_diff_key($rawTags, array_flip($hideTags));
	}
}

$totaltags = count($rawTags);
if( $totaltags > $maxTags ) {
	$rawTags = array_slice($rawTags,0,$maxTags);
	$totaltags = count($rawTags);
}

$i=0;
foreach( $rawTags AS $tag => $items )
{
	$tags[$i]->value = $tag;
	$tags[$i]->items = $items;
	$tags[$i]->link  = JRoute::_('index.php?option=com_mtree&task=searchby&cf_id='.$cf_id.'&value='.$tag.$itemid);
	$i++;
}

require(JModuleHelper::getLayoutPath('mod_mt_tagcloud'));
?>