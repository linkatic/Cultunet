<?php
/**
 * LyftenBloggie Tag Cloud Module 1.0.2
 * @package LyftenBloggie 1.0.2
 * @copyright (C) 2009 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class modTagCloudBlogHelper
{
	function getList(&$params, $module)
	{
		global $mainframe;

		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php')) return;
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');
		
		//initialize variables
		$db				=& JFactory::getDBO();
		$limit 			= $params->get('maxCloudLimit', '25');
		$tags 			= array();
		$document 		= & JFactory::getDocument();

		//add header files
		$document->addStyleSheet(JURI::base().'modules/mod_lb_tagcloud/style.css');

		/* retrieve max and min used tags */
		$query = 'SELECT count( id ) AS used
				FROM #__bloggies_relations
				GROUP BY tag
				ORDER BY used DESC
				LIMIT '.$limit;
		$db->setQuery($query);
		$useArray = $db->loadResultArray();
		
		if(empty($useArray)) {
			return JText::_('NO TAGS ASSIGNED');
		}
	
		$max = $useArray[0];
		$min = $useArray[sizeof($useArray)-1];

		$query = 'SELECT r.id, t.name, t.slug, count( r.tag ) AS used
					  FROM #__bloggies_relations as r
					  NATURAL JOIN #__bloggies_relations
					  LEFT JOIN #__bloggies_tags as t ON t.id = r.tag
					  GROUP BY r.tag
					  HAVING used >= '. $min .'
					  ORDER BY used DESC
					  LIMIT '.$limit;
				  
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$rows = modTagCloudBlogHelper::_vsort($rows, "name", true, true);

		if (!$max) {
			return JText::_('NO TAGS');
		}else{
			foreach($rows as $row)
			{
				$class 	= modTagCloudBlogHelper::_fontSize($min, $max, $row->used);
				$word 	= (!$row->slug)?str_replace(" ", "+", substr($row->name, 0, 20)):$row->slug;			
				$tags[] = '<a href="'.JRoute::_(LyftenBloggieHelperRoute::getTagRoute($word)).'"'.$class.' rel="tag" title="'.$row->name.'">' . $row->name .'</a>';
			}
		}
			
		return $tags;
	}
	
	/**
	* Calculates font size depending on how many times tag was used
	**/
	function _fontSize($min, $max, $used)
	{
		$difference = $max - $min;
		if ($used == $min) return ' class="tag-size-1"';
		else if ($used == $max) return ' class="tag-size-20"';
		else
		{
			$x = (20 - 1) / $difference;
			$used -= $min;
			return ' class="tag-size-'.round(1 + ($used * $x)).'"';
		}
	}

	function _vsort($array, $id="id", $sort_ascending=true, $is_object_array = false)
	{
		$temp_array = array();
		while(count($array)>0) {
			$lowest_id = 0;
			$index=0;
			if($is_object_array){
				foreach ($array as $item) {
					if (isset($item->$id)) {
						if ($array[$lowest_id]->$id) {
							if (strtolower($item->$id)<strtolower($array[$lowest_id]->$id)) {
								$lowest_id = $index;
							}
						}
					}
				$index++;
			}
			}else{
				foreach ($array as $item) {
					if (isset($item[$id])) {
						if ($array[$lowest_id][$id]) {
							if (strtolower($item[$id])<strtolower($array[$lowest_id][$id])) {
								$lowest_id = $index;
							}
						}
					}
					$index++;
				}
			}
			$temp_array[] = $array[$lowest_id];
			$array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
		}
		
		if ($sort_ascending)
		{
			return $temp_array;
		} else {
			return array_reverse($temp_array);
		}
	}
}
