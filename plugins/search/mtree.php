<?php
/**
 * @version		$Id: mtree.php 802 2009-11-20 04:17:27Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

$mainframe->registerEvent( 'onSearch', 'plgSearchMtree' );
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchMtreeAreas' );

JPlugin::loadLanguage( 'plg_search_mtree' );

/**
 * @return array An array of search areas
 */
function &plgSearchMtreeAreas() {
	static $areas = array(
		'mtree' => 'Directory'
	);
	return $areas;
}

/**
* Mosets Tree Search method
*
* The sql must return the following fields that are used in a common display
* routine: href, title, section, created, text, browsernav
* @param string Target search string
* @param string matching option, exact|any|all
* @param string ordering option, newest|oldest|popular|alpha|category
 * @param mixed An array if the search it to be restricted to areas, null if search all
 */
function plgSearchMtree( $text, $phrase='', $ordering='', $areas=null )
{
	if (is_array( $areas )) {
		if (!array_intersect( $areas, array_keys( plgSearchMtreeAreas() ) )) {
			return array();
		}
	}
	
	require( JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'init.php' );

	$db 		=& JFactory::getDBO();
	$nullDate	= $db->getNullDate();
	$now 		= JFactory::getDate();

	// load plugin params info
 	$plugin				=& JPluginHelper::getPlugin('search', 'mtree');
 	$pluginParams		= new JParameter( $plugin->params );
	$limit				= $pluginParams->def( 'search_limit', 50 );
	$search_listing		= $pluginParams->def( 'search_listing', 1 );
	$search_category	= $pluginParams->def( 'search_category', 0 );
	
	$text = trim( $text );
	if ($text == '') {
		return array();
	}

	$db->setQuery( 'SELECT field_type,published,simple_search FROM #__mt_customfields WHERE iscore = 1' );
	$searchable_core_fields = $db->loadObjectList('field_type');
	
	# Determine if there are custom fields that are simple searchable
	$db->setQuery( 'SELECT COUNT(*) FROM #__mt_customfields WHERE published = 1 AND simple_search = 1 AND iscore = 0' );
	$searchable_custom_fields_count = $db->loadResult();
	
	$link_fields = array(
		'link_name', 'link_desc', 'address', 'city', 'postcode', 'state', 'country', 'email', 'website', 'telephone'
		, 'fax' );

	$wheres = array();
	switch ($phrase) 
	{
		case 'exact':
			$wheres2 = array();
			foreach( $link_fields AS $lf ) {
				if ( 
					substr($lf, 0, 5) == 'link_' 
					&& 
					array_key_exists('core'.substr($lf,5),$searchable_core_fields) 
					&&
					$searchable_core_fields['core'.substr($lf,5)]->published == 1 
					&&
					$searchable_core_fields['core'.substr($lf,5)]->simple_search == 1 
				) {
					$wheres2[] = "LOWER(l.$lf) LIKE '%" . $db->getEscaped($text) . "%'";
				}
				elseif (
					array_key_exists('core'.$lf,$searchable_core_fields) 
					&& 
					$searchable_core_fields['core'.$lf]->published == 1 
					&& 
					$searchable_core_fields['core'.$lf]->simple_search == 1
				) {
					$wheres2[] = "LOWER(l.$lf) LIKE '%" . $db->getEscaped($text) . "%'";
				}
			}
			
			if($searchable_custom_fields_count > 0)
			{
				$wheres2[] = '(cf.hidden = 0 AND cf.simple_search = 1 AND cf.published = 1'
					. ' AND LOWER(cfv.value) LIKE \'%' . $db->getEscaped($text) . '%\')';
			}
			
			$where = '( (' . implode( ') OR (', $wheres2 ) . ') )';

			$wheres3[] = "LOWER(cat_name) LIKE '%" . $db->getEscaped($text) . "%'";
			$wheres3[] = "LOWER(cat_desc) LIKE '%" . $db->getEscaped($text) . "%'";
			$wheres3[] = "LOWER(metakey) LIKE '%" . $db->getEscaped($text) . "%'";
			$wheres3[] = "LOWER(metadesc) LIKE '%" . $db->getEscaped($text) . "%'";
			$where_cat = '(' . implode( ') OR (', $wheres3 ) . ')';
			break;
		case 'all':
		case 'any':
		default:
			$words = explode( ' ', $text );
			$wheres = array();
			foreach ($words as $word)
			{
				$wheres2 = array();
				foreach( $link_fields AS $lf )
				{
					if ( 
						substr($lf, 0, 5) == 'link_' 
						&& 
						array_key_exists('core'.substr($lf,5),$searchable_core_fields) 
						&& 
						$searchable_core_fields['core'.substr($lf,5)]->published == 1 
						&& 
						$searchable_core_fields['core'.substr($lf,5)]->simple_search == 1 
					) {
						$wheres2[] = "LOWER(l.$lf) LIKE '%" . $db->getEscaped($word) . "%'";
					} elseif(
						array_key_exists('core'.$lf,$searchable_core_fields) 
						&& 
						$searchable_core_fields['core'.$lf]->published == 1 
						&& 
						$searchable_core_fields['core'.$lf]->simple_search == 1
					) {
						$wheres2[] = "LOWER(l.$lf) LIKE '%" . $db->getEscaped($word) . "%'";
					}
				}
				if($searchable_custom_fields_count > 0) {
					$wheres2[] = '(cf.hidden = 0 AND cf.simple_search = 1 AND cf.published = 1'
						. ' AND LOWER(cfv.value) LIKE \'%' . $db->getEscaped($word) . '%\')';
				}
				
				$wheres[] = '(' . implode( ' OR ', $wheres2 ) . ')';
				
				$wheres3 = array();
				$wheres3[] = "LOWER(cat_name) LIKE '%" . $db->getEscaped($word) . "%'";
				$wheres3[] = "LOWER(cat_desc) LIKE '%" . $db->getEscaped($word) . "%'";
				$wheres3[] = "LOWER(metakey) LIKE '%" . $db->getEscaped($word) . "%'";
				$wheres3[] = "LOWER(metadesc) LIKE '%" . $db->getEscaped($word) . "%'";
				$wheres_cat[] = implode( ' OR ', $wheres3 );

			}
			if($wheres[0] == '()') {
				$where = '';
			} else {
				$where = "\n(\n" . implode( ($phrase == 'all' ? "\nAND\n" : "\nOR\n"), $wheres ) . "\n)";
			}
			$where_cat = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres_cat ) . ')';
			break;
	}

	switch ($ordering)
	{
		case 'newest':
		default:
			$order = 'l.link_created DESC';
			$order_cat = 'cat_created DESC';
			break;
		case 'oldest':
			$order = 'l.link_created ASC';
			$order_cat = 'cat_created ASC';
			break;
		case 'popular':
			$order = 'l.link_hits DESC';
			$order_cat = 'cat_name DESC'; // fall to alphabetically sorted since category does not nave hits
			break;
		case 'alpha':
			$order = 'l.link_name ASC';
			$order_cat = 'cat_name DESC';
			break;
		case 'category':
			$order = 'cat_name ASC, l.link_name ASC';
			$order_cat = 'cat_parent DESC';
			break;
	}

	# Retrieve Mosets Tree Itemid
	$db->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_mtree' AND published = 1 LIMIT 1");
	$Itemid = $db->loadResult();
	
	# The main search query
	if( $search_listing && !empty($where) && $limit > 0 ) {
		$sql = 'SELECT DISTINCT l.link_id AS id, l.link_created AS created, l.link_name AS title,'
			. '	l.link_desc AS text, \'0\' AS browsernav, '
			. "	CONCAT('index.php?option=com_mtree&task=viewlink&link_id=',l.link_id,'&Itemid=".$Itemid."') AS href,"
			. " CONCAT_WS('/', '" . $db->getEscaped( JText::_('Directory') ) . "', c.cat_name) AS section"
			. ' FROM (#__mt_links AS l, #__mt_cl AS cl';
		if($searchable_custom_fields_count > 0) {
			$sql .= ", #__mt_customfields AS cf";
		}
		$sql .= ")";
		if($searchable_custom_fields_count > 0) {
			$sql .= ' LEFT JOIN #__mt_cfvalues AS cfv ON cfv.link_id = l.link_id AND cfv.cf_id = cf.cf_id';
		}
		$sql .= ' LEFT JOIN #__mt_cats AS c ON c.cat_id = cl.cat_id ' 
			. ' WHERE '
			. " link_published='1' AND link_approved='1' AND ( publish_up = ".$db->Quote($nullDate)." OR publish_up <= ".$db->Quote( $now->toMySQL() )."  ) "
			. " AND ( publish_down = ".$db->Quote($nullDate)." OR publish_down >= ".$db->Quote( $now->toMySQL() )." )"
			. ' AND cl.link_id = l.link_id '
			. ' AND cl.main = 1 ';
		$sql .= ' AND '.$where
			.	' ORDER BY ' 
			.	$order;
		$db->setQuery( $sql, 0, $limit );
		$listings_result = $db->loadObjectList();
		$limit -= count($listings_result);
	} else {
		$listings_result = array();
	}

	$cats_result = array();
	if( $search_category && $limit > 0 )
	{
		$db->setQuery( 'SELECT cat_id AS id, cat_created AS created, cat_name AS title,'
			. ' cat_desc AS text, \'0\' AS browsernav, '
			. " CONCAT('index.php?option=com_mtree&task=listcats&cat_id=',cat_id,'&Itemid=" . $Itemid . "') AS href,"
			. " CONCAT_WS('/', '" . $db->getEscaped( JText::_('Directory') ) . "', cat_name) AS section"
			. ' FROM #__mt_cats '
			. ' WHERE (' . $where_cat . ')'
			. ' AND cat_approved = 1 AND cat_id > 0'
			. ' ORDER BY ' . $order_cat
			, 0
			, $limit
			);
		$cats_result = $db->loadObjectList();
	} 

	return array_merge($listings_result,$cats_result);

}
?>