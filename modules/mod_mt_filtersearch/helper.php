<?php
/**
 * @version		$Id: mod_mt_filtersearch.php 15-08-2010 $
 * @package		Mosets Tree
 * @copyright	(C) 2010 Linkatic. All rights reserved.
 * @license		GNU General Public License
 * @author		Vicente Gimeno Quiles <vgimeno@linkatic.com>
 * @url			http://www.linkatic.com
 */

defined('_JEXEC') or die('Restricted access');

include( JPATH_ROOT . DS.'modules'.DS.'mod_mt_search'.DS.'helper.php');

class modMTFilterSearchHelper extends modMTSearchHelper{

	function getSelectOptions( $cat_id ) {
		$db =& JFactory::getDBO();
		
		
		$db->setQuery( 'SELECT cf_id,field_elements FROM #__mt_customfields cf'
				. ' INNER JOIN #__mtcats_customfields catcf'
				. ' WHERE cf.cf_id = catcf.id_customfield AND cf.size=30 AND catcf.id_cat='.$cat_id.' AND cf.cf_id IN (45,50,51,55,62,68,69,74)');
		$result = $db->loadObjectList();
		
		if($result)
		{
			$options['cf_id'] = $result['0']->cf_id;
			$options['fields'] = explode('|',$result['0']->field_elements);
			return $options;
		}
		else return null;
	}
	
	function getAreas()
	{
		$db =& JFactory::getDBO();
		
		
		$db->setQuery( 'SELECT * FROM #__mt_customfields cfv'
				. ' WHERE cfv.cf_id = 32'); //cf_id = 32 -> Area
		$result = $db->loadObjectList();
		
		if($result)
		{
			$options['cf_id'] = $result['0']->cf_id;
			$options['fields'] = explode('|',$result['0']->field_elements);
			return $options;
		}
		else return null;
	}
	
	function getPaises()
	{
		$db =& JFactory::getDBO();
		
		
		$db->setQuery( 'SELECT * FROM #__mt_customfields cfv'
				. ' WHERE cfv.cf_id = 7'); //cf_id = 7 -> Paises
		$result = $db->loadObjectList();
		
		if($result)
		{
			$options['cf_id'] = $result['0']->cf_id;
			$options['fields'] = explode('|',$result['0']->field_elements);
			return $options;
		}
		else return null;
	}
	
	
	function getIdiomas()
	{
		$db =& JFactory::getDBO();
		
		
		$db->setQuery( 'SELECT * FROM #__mt_customfields cfv'
				. ' WHERE cfv.cf_id = 64'); //cf_id = 64 -> Idiomas
		$result = $db->loadObjectList();
		
		if($result)
		{
			$options['cf_id'] = $result['0']->cf_id;
			$options['fields'] = explode('|',$result['0']->field_elements);
			return $options;
		}
		else return null;
	}
	
	function getTipoAnuncios()
	{
		$db =& JFactory::getDBO();
		
		
		$db->setQuery( 'SELECT * FROM #__mt_customfields cfv'
				. ' WHERE cfv.cf_id = 91'); //cf_id = 91 -> Tipo de Anuncios
		$result = $db->loadObjectList();
		
		if($result)
		{
			$options['cf_id'] = $result['0']->cf_id;
			$options['fields'] = explode('|',$result['0']->field_elements);
			return $options;
		}
		else return null;
	}
	
	function getExtrafields() {
		global $savantConf, $Itemid, $mtconf;
	
		$database =& JFactory::getDBO();
		$document=& JFactory::getDocument();
	
		require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'mfields.class.php' );
		require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'mAdvancedSearch.class.php' );
	
		$document->setTitle(JText::_( 'Filtros de busqueda' ));
	
		# Load up search ID if available
		$search_id	= JRequest::getInt('search_id', 0);
		
		if ($search_id > 0) {
			$database->setQuery( 'SELECT search_text FROM #__mt_searchlog WHERE search_id = ' . $database->quote($search_id) );
			$post = unserialize($database->loadResult());
		} else { $post = JRequest::get( 'post' ); }
	
		# Load all published CORE & custom fields
		$database->setQuery( "SELECT cf.*, '0' AS link_id, '' AS value, '0' AS attachment, ft.ft_class FROM #__mt_customfields AS cf "
			.	"\nLEFT JOIN #__mt_fieldtypes AS ft ON ft.field_type=cf.field_type"
			.	"\nWHERE cf.published='1' ORDER BY ordering ASC" );
		$fields = new mFields($database->loadObjectList());
		$searchParams = $fields->loadSearchParams($post);
		
		$advsearch = new mAdvancedSearch( $database );
		if( intval( $post['searchcondition'] ) == 2 ) {
			$advsearch->useAndOperator();
		} else {
			$advsearch->useOrOperator();
		}
	
	
		$fields->resetPointer();
		while( $fields->hasNext() ) {
			$field = $fields->getField();
			$searchFields = $field->getSearchFields();
	
			if( isset($searchFields[0]) && isset($searchParams[$searchFields[0]]) && $searchParams[$searchFields[0]] != '' ) {
				foreach( $searchFields AS $searchField ) {
					$searchFieldValues[] = $searchParams[$searchField];
				}
				if( !empty($searchFieldValues) && $searchFieldValues[0] != '' ) {
					if( is_array($searchFieldValues[0]) && empty($searchFieldValues[0][0]) ) {
						// Do nothing
					} else {
						$tmp_where_cond = call_user_func_array(array($field, 'getWhereCondition'),$searchFieldValues);
						if( !is_null($tmp_where_cond) ) {
							$advsearch->addCondition( $field, $searchFieldValues );
						} 
					}
				}
				unset($searchFieldValues);
			}
			
			$fields->next();
		}
	
		$limit		= JRequest::getInt('limit', $mtconf->get('fe_num_of_searchresults'), 'get');
		$limitstart	= JRequest::getInt('limitstart', 0, 'get');
		if( $limitstart < 0 ) $limitstart = 0;
	
		return $advsearch->search(1,1);
	}

}