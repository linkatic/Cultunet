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

class modMTTagCloudHelper {
	function getTags($cf_id) {
		$db =& JFactory::getDBO();
		
		if ( modMTTagCloudHelper::isCore($cf_id) )
		{
			$db->setQuery('SELECT field_type FROM #__mt_customfields WHERE cf_id = ' . $db->Quote($cf_id) . ' LIMIT 1');
			$field_type = $db->loadResult();
			$core_name = substr($field_type,4);
			$db->setQuery('SELECT ' . $core_name . ' FROM #__mt_links WHERE ' . $core_name . ' != \'\'');
		} else {
			$db->setQuery('SELECT REPLACE(value,\'|\',\',\') FROM #__mt_cfvalues WHERE cf_id = ' . $db->Quote($cf_id));
		}
		$tags = $db->loadResultArray();
		
		return $tags;
	}
	
	/**
	 * Read through array of strings and return an array mapping tag with number of occurances
	 *
	 * @access	public
	 * @param	array	$arrTags	Array of strings, where each strings are comma separated values
	 * @return	array	An array of results mapping keywords to the number of occurances
	 * @since	1.0
	 */
	function parse($arrTags)
	{
		$rawRank = array();
		foreach( $arrTags AS $tag )
		{
			$rawRank = array_merge($rawRank,modMTTagCloudHelper::explodeTrim($tag));
		}
		$rawRank = array_count_values($rawRank);
		arsort($rawRank);
		
		return $rawRank;
		// print_r($rawRank);
	}
	
	/**
	 * Method to explode a string by comma and then trim each exploded tags.
	 *
	 * @access	public
	 * @param	string	$str	A string consisting comma separated values
	 * @return	array	An array of results
	 * @since	2.1
	 */
	function explodeTrim($str)
	{
		if( empty($str) ) return array();
		
		$results = explode(',',$str);
		$count = count($results);
		
		for($i=0;$i<$count;$i++)
		{
			$results[$i] = trim($results[$i]);
		}
		
		return array_unique($results);
	}

	function getItemid() {
		$menu 	= &JSite::getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_mtree');
		return isset($items[0]) ? '&Itemid='.$items[0]->id : '';
	}
	
	function isCore($cf_id) {
		$db =& JFactory::getDBO();
		
		$db->setQuery('SELECT iscore FROM #__mt_customfields WHERE cf_id = ' . $db->Quote($cf_id) . ' LIMIT 1');
		return $db->loadResult();
	}
}
?>