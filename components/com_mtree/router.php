<?php
/**
 * @version		$Id: router.php 834 2010-01-11 04:24:44Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(  dirname(__FILE__).DS.'init.php' );

jimport( 'joomla.filter.filteroutput' );

global $mtconf, $sef_replace, $listing_tasks, $listlisting_names;
$sef_replace = array(
	'%26' => '&', // &
	'%3F' => '-3F', // ?
	'%2F' => '-2F', // /
	'%3C' => '-3C', // <
	'%3E' => '-3E', // >
	'%23' => '-23', // #
	'%24' => '-24', // $
	'%3A' => '-3A',  // :
	'%2E' => '-2E'  // .
	);

$listing_tasks = array(
	// task			=>	SEF String
	'viewgallery'	=>	$mtconf->get('sef_gallery'),
	'writereview'	=>	$mtconf->get('sef_review'),
	'recommend'		=>	$mtconf->get('sef_recommend'),
	'print'			=>	$mtconf->get('sef_print'),
	'contact'		=>	$mtconf->get('sef_contact'),
	'report'		=>	$mtconf->get('sef_report'),
	'claim'			=>	$mtconf->get('sef_claim'),
	'visit'			=>	$mtconf->get('sef_visit'),
	'deletelisting'	=>	$mtconf->get('sef_delete'),
	'editlisting'	=>	$mtconf->get('sef_editlisting')
	);
	
$listlisting_names = array(
	$mtconf->get('sef_featured')	=> 'featured',
	$mtconf->get('sef_updated')		=> 'updated',
	$mtconf->get('sef_favourite')	=> 'favourite',
	$mtconf->get('sef_popular')		=> 'popular',
	$mtconf->get('sef_mostrated')	=> 'mostrated',
	$mtconf->get('sef_toprated')	=> 'toprated',
	$mtconf->get('sef_mostreview')	=> 'mostreview',
	$mtconf->get('sef_new')			=> 'new'
	);
	
function MtreeBuildRoute(&$query) {
	global $mtconf, $listing_tasks;
	$segments = array();
	$db =& JFactory::getDBO();
	if(!class_exists('mtLinks')) {
		require_once( $mtconf->getjconf('absolute_path').'/administrator/components/com_mtree/admin.mtree.class.php');
	}

	if(!isset($query['task'])) {
		return $segments;
	}
	
	switch($query['task']) {
			
		case 'listcats':
			if(isset($query['cat_id'])) {
				$segments = appendCat($query['cat_id']);
				unset($query['cat_id']);
				if( isset($query['start']) ) {
					$page = getPage($query['start'],$mtconf->get('fe_num_of_links'));
					$segments[] = $mtconf->get('sef_category_page') . $page;
				}
			}
			break;
			
		case 'viewlink':
			$mtLink = new mtLinks( $db );
			$mtLink->load( $query['link_id'] );
			$segments = array_merge($segments,appendCat( $mtLink->cat_id ));
			if( isset($query['start']) ) {
				//	http://example.com/c/mtree/Computer/Games/Donkey_Kong/reviews23
				$page = getPage($query['start'],$mtconf->get('fe_num_of_reviews'));
				$segments = array_merge($segments,appendListing( $mtLink->link_name, $mtLink->link_id, $mtLink->alias, false ));
				$segments[] =  $mtconf->get('sef_reviews_page') . $page;
			} else {
				$segments = array_merge($segments,appendListing( $mtLink->link_name, $mtLink->link_id, $mtLink->alias, false ));
			}
			unset($query['link_id']);
			break;
			
		case 'mypage':
			$segments[] = $mtconf->get('sef_mypage');
			if( isset($query['start']) ) {
				$page = getPage($query['start'],$mtconf->get('fe_num_of_links'));
				$segments[] = $mtconf->get('sef_category_page') . $page;
			}
			break;
		
		case 'listfeatured':
		case 'listnew':
		case 'listupdated':
		case 'listfavourite':
		case 'listpopular':
		case 'listmostrated':
		case 'listtoprated':
		case 'listmostreview':
			$type = strtoupper(substr($query['task'],4));
			$cat_id = getId( 'cat', $query );
			$segments = appendCat( $cat_id );
			$segments[] = $mtconf->get('sef_'.strtolower($type));
			if( isset($query['start']) ) {
				$page = getPage($query['start'],$mtconf->get('fe_num_of_'.strtolower($type)));
				$segments[] = $mtconf->get('sef_category_page') . $page;
			}
			break;

		case 'advsearch':
			$segments[] = $mtconf->get('sef_advsearch');
			break;
		
		case 'advsearch2':
			$segments[] = $mtconf->get('sef_advsearch2');
			$search_id = getId( 'search', $query );
			$page = 1;
			if( isset($query['start']) ) {
				$page = getPage($query['start'],$mtconf->get('fe_num_of_searchresults'));
				$segments[] = $search_id;
				$segments[] = $page;
			} else {
				$segments[] = $search_id;
			}
			break;
		
		case 'listalpha':
			$cat_id = getId( 'cat', $query );
			$segments = appendCat( $cat_id );
			$segments[] = $mtconf->get('sef_listalpha');
			$segments[] = urlencode($query['alpha']);
			unset($query['alpha']);
			if( isset($query['start']) ) {
				$page = getPage($query['start'],$mtconf->get('fe_num_of_links'));
				$segments[] = $page;
			}
			break;

		case 'viewowner';
		case 'viewusersreview';
		case 'viewusersfav';
			$user_id = getId( 'user', $query );
			$db->setQuery( "SELECT username FROM #__users WHERE id= " . $db->quote($user_id) . " AND block='0'" );
			$username = $db->loadResult();
			if(!empty($username)) {
				switch($query['task']) {
					default:
						$segments[] = $mtconf->get('sef_owner');
						break;
					case 'viewusersreview':
						$segments[] = $mtconf->get('sef_reviews');
						break;
					case 'viewusersfav':
						$segments[] = $mtconf->get('sef_favourites');
						break;
				}
				$segments[] = murlencode($username);
			}
			if( isset($query['start']) ) {
				$page = getPage($query['start'],$mtconf->get('fe_num_of_links'));
				$segments[] = $page;
			}
			break;
		
		case 'viewimage':
			$segments[] = $mtconf->get('sef_image');
			$segments[] = getId( 'img', $query );
			break;

		case 'replyreview':
			$segments[] = $mtconf->get('sef_replyreview');
			$segments[] = getId( 'rev', $query );
			break;

		case 'reportreview':
			$segments[] = $mtconf->get('sef_reportreview');
			$segments[] = getId( 'rev', $query );
			break;
		
		// Listing's tasks
		case array_key_exists($query['task'],$listing_tasks) !== false:
			$mtLink = new mtLinks( $db );
			$mtLink->load( $query['link_id'] );
			$segments = appendCatListing( $mtLink, false );
			$segments[] = $listing_tasks[$query['task']];
			unset($query['link_id']);
			break;
		
		case 'addlisting':
		case 'addcategory':
			if(isset($query['link_id'])) {
				$mtLink = new mtLinks( $db );
				$mtLink->load( getId( 'link', $query ) );
				$segments = appendCat( $mtLink->cat_id );
			} elseif(isset($query['cat_id'])) {
				$segments = appendCat( getId( 'cat', $query ) );
			}
			if($query['task'] == 'addlisting') {
				$segments[] = $mtconf->get('sef_addlisting');
			} else {
				$segments[] = $mtconf->get('sef_addcategory');
			}
			break;
			
		case 'searchby':
			$cf_id = getId( 'cf', $query );
			$cat_id = getId( 'cat', $query );
			$segments = appendCat( $cat_id );
			$segments[] = $mtconf->get('sef_searchby');
			$segments[] = appendTag($cf_id);
			$segments[] = $query['value'];
			if( isset($query['start']) ) {
				$page = getPage($query['start'],$mtconf->get('fe_num_of_searchresults'));
				$segments[] = $page;
			}
			unset($query['value']);
			break;
			
		case 'search':
			$cat_id = getId( 'cat', $query );
			$segments = appendCat( $cat_id );
			$segments[] = $mtconf->get('sef_search');
			
			$badchars = array('#','>','<','\\'); 
			$searchword = urldecode(trim(str_replace($badchars, '', $query['searchword'])));

			// limit searchword to x characters as configured in limit_max_chars
			if ( JString::strlen( $searchword ) > $mtconf->get('limit_max_chars') ) {
				$searchword	= JString::substr( $searchword, 0, ($mtconf->get('limit_max_chars')-1) );
			}

			if( 
				strpos($searchword,'?') !== false 
				OR
				strpos($searchword,'%') !== false
				OR
				strpos($searchword,'/') !== false
			) {
				$searchword = urlencode($searchword);
			}
			$searchword = urlencode(($searchword));
			
			if( isset($searchword) && !empty($searchword) ) {
				$segments[] = $searchword;
			}
			
			// Retrieve configuration options - needed to know which SEF URLs are used
			$app =& JFactory::getApplication();
			// Allows for searching on strings that include ".xxx" that appear to Apache as an extension
			if ( $app->getCfg('sef') && $app->getCfg('sef_rewrite') && !$app->getCfg('sef_suffix') && strpos($searchword,'.') !== false
			) {
				$segments[] .= '/';
			}
			
			unset($query['searchword']);
			break;
		
		case 'rss':
			$cat_id = getId( 'cat', $query );
			$segments = appendCat( $cat_id );
			$segments[] = $mtconf->get('sef_rss');
			if( isset($query['type']) && $query['type'] == 'new') {
				$segments[] = $mtconf->get('sef_rss_new');
			} else {
				$segments[] = $mtconf->get('sef_rss_updated');
			}
			unset($query['type']);
			break;
	}

	if( $query['task'] != 'search' ) {
		unset($query['start']);
	}
	unset($query['limit']);
	unset($query['task']);
	return $segments;
}

function MtreeParseRoute($segments) {
	global $mtconf, $listing_tasks, $listlisting_names;
	$vars = array();
	$db =& JFactory::getDBO();

	$end_segment = preg_replace('/:/', '-', end($segments), 1);

	for($i=0;$i<count($segments);$i++) {
		$segments[$i] = preg_replace('/:/', '-', $segments[$i], 1);
	}
	
	// Fix for listalpha=0. eg: http://<site>/directory/list-alpha/0.html
	if($end_segment == '0') {
		$end_segment = $mtconf->get('sef_listalpha');
	}
	
	switch($end_segment) {
			
		case $mtconf->get('sef_details'):
		case eregi( $mtconf->get('sef_reviews_page') . "[0-9]+",$end_segment) == true:
			// http://example.com/directory/arts/leonardo-da-vinci/details
			$isReviewsPage = eregi($mtconf->get('sef_reviews_page') . '[0-9]+',$end_segment);
			$path_names = array_slice( $segments, 0, -1 );
			$link_id = findLinkID( $path_names );
			$vars['task'] = 'viewlink';
			$vars['link_id'] = $link_id;
			if ( $isReviewsPage ) {
				// Get the page numner
				$pagenumber = substr( $end_segment, strlen($mtconf->get('sef_reviews_page')) );
				$vars['limit'] = $mtconf->get('fe_num_of_reviews');
				$vars['limitstart'] = $mtconf->get('fe_num_of_reviews') * ($pagenumber -1);
			}
			break;

		case $mtconf->get('sef_mypage'):
		case count($segments) > 1 && $mtconf->get('sef_mypage') == $segments[count($segments)-2]:
			$vars['task'] = 'mypage';
			$pagenumber = getPageNumber($segments);
			if ( $pagenumber > 0 ) {
				$vars['limit'] = $mtconf->get('fe_num_of_links');
				$vars['limitstart'] = ($mtconf->get('fe_num_of_links') * ($pagenumber -1));
			}
			break;
			
		// List listing page
		case count($segments) == 1 && array_key_exists($end_segment,$listlisting_names):

		case isset($segments[count($segments)-2]) 
			&& 
			(array_key_exists($segments[count($segments)-2],$listlisting_names) || array_key_exists($segments[count($segments)-1],$listlisting_names)) 
			&& 
			$segments[count($segments)-2] != $mtconf->get('sef_rss'):

			$last_segment = $end_segment;
			if( array_key_exists($last_segment,$listlisting_names) ) {
				$type = $listlisting_names[$last_segment];
				$offset = -1;
			} else {
				$type = $listlisting_names[$segments[count($segments)-2]];
				$offset = -2;
			}
			$vars['task'] = 'list'.$type;
			$page = getPageNumber($segments);
			$cat_id = findCatId(array_slice($segments,0,$offset));
			$vars['cat_id'] = $cat_id;
			if($page > 0) {
				$vars['limit'] = $mtconf->get('fe_num_of_'.$type);
				$vars['limitstart'] = $mtconf->get('fe_num_of_'.$type) * ($page -1);
			}
			break;

		case $mtconf->get('sef_advsearch'):
			$vars['task'] = 'advsearch';
			break;
		
		case count($segments) == 3 && $mtconf->get('sef_advsearch2') == $segments[count($segments)-3]:
		case count($segments) == 2 && $mtconf->get('sef_advsearch2') == $segments[count($segments)-2]:
			if( count($segments) == 2 ) {
				$page = 1;
				$vars['limitstart'] = 0;
				$search_id = $end_segment;
			} else {
				$page = $end_segment;
				$vars['limitstart'] = ($mtconf->get('fe_num_of_searchresults') * ($page -1));
				$search_id = $segments[count($segments)-2];
			}
			$vars['task'] = 'advsearch2';
			$vars['search_id'] = $search_id;
			$vars['limit'] = $mtconf->get('fe_num_of_searchresults');
			break;
		
		case (count($segments) > 3 && $mtconf->get('sef_searchby') == $segments[count($segments)-4]):
		case (count($segments) > 2 && $mtconf->get('sef_searchby') == $segments[count($segments)-3]):
			if( $mtconf->get('sef_searchby') == $segments[count($segments)-3] ) {
				$page = 1;
				$vars['cf_id'] = findCfId($segments[count($segments)-2]);
				$vars['value'] = $end_segment;
				$vars['cat_id'] = findCatId(array_slice($segments,0,-3));
			} else {
				$page = $end_segment;
				$vars['cf_id'] = findCfId($segments[count($segments)-3]);
				$vars['value'] = $segments[count($segments)-2];
				$vars['cat_id'] = findCatId(array_slice($segments,0,-4));
			}
			$vars['task'] = 'searchby';
			$vars['limit'] = $mtconf->get('fe_num_of_searchresults');
			$vars['limitstart'] = ($mtconf->get('fe_num_of_searchresults') * ($page -1));
			break;
					
		case count($segments) > 2 && $mtconf->get('sef_search') == $segments[count($segments)-3]:
		case count($segments) > 1 && $mtconf->get('sef_search') == $segments[count($segments)-2]:
			if( $mtconf->get('sef_search') == $segments[count($segments)-2] ) {
				$page = 1;
				$cat_id = findCatId(array_slice($segments,0,-2));
				$vars['searchword'] = $end_segment;
			} else {
				$page = $end_segment;
				$cat_id = findCatId(array_slice($segments,0,-3));
				$vars['searchword'] = $segments[count($segments)-2];
			}
			
			$vars['task'] = 'search';
			$vars['limit'] = $mtconf->get('fe_num_of_searchresults');
			$vars['limitstart'] = ($mtconf->get('fe_num_of_searchresults') * ($page -1));
			$vars['cat_id'] = $cat_id;
			break;
		
		// No search string. eg: http://example.com/directory/search.html
		case count($segments) == 1 && $mtconf->get('sef_search') == $segments[count($segments)-1]:
			$vars['searchword'] = '';
			$vars['task'] = 'search';
			$vars['limit'] = $mtconf->get('fe_num_of_searchresults');
			$vars['limitstart'] = 0;
			$vars['cat_id'] = 0;
			break;
		
		case count($segments) > 1 && $mtconf->get('sef_listalpha') == $segments[count($segments)-2]:
		case count($segments) > 2 && $mtconf->get('sef_listalpha') == $segments[count($segments)-3]:
			if( $mtconf->get('sef_listalpha') == $segments[count($segments)-2] ) {
				$vars['cat_id'] = findCatId(array_slice($segments,0,-2));
				$vars['alpha'] = end($segments);
				$page = 1;
			} else {
				$vars['cat_id'] = findCatId(array_slice($segments,0,-3));
				$vars['alpha'] = $segments[count($segments)-2];
				$page = $segments[count($segments)-1];
			}
			$vars['task'] = 'listalpha';
			if($page > 0) {
				$vars['limit'] = $mtconf->get('fe_num_of_featured');
				$vars['limitstart'] = $mtconf->get('fe_num_of_featured') * ($page -1);
			}
			break;

		case count($segments) == 3 && in_array($segments[count($segments)-3],array($mtconf->get('sef_owner'),$mtconf->get('sef_reviews'),$mtconf->get('sef_favourites'))) == true:
		case count($segments) == 2 && in_array($segments[count($segments)-2],array($mtconf->get('sef_owner'),$mtconf->get('sef_reviews'),$mtconf->get('sef_favourites'))) == true:
			if( count($segments) == 2 ) {
				$task = $segments[count($segments)-2];
				$owner_username = $segments[ (count($segments)-1) ];
			} else {
				$task = $segments[count($segments)-3];
				$owner_username = $segments[ (count($segments)-2) ];
			}
			switch($task) {
				case $mtconf->get('sef_owner'):
					$vars['task'] = 'viewowner';
					break;
				case $mtconf->get('sef_reviews'):
					$vars['task'] = 'viewusersreview';
					break;
				case $mtconf->get('sef_favourites'):
					$vars['task'] = 'viewusersfav';
					break;
			}
			$owner_username = murldecode($owner_username);
			
			$db->setQuery( "SELECT id FROM #__users WHERE username = " . $db->quote($owner_username) . " LIMIT 1" );
			$vars['user_id'] = $db->loadResult();
			$page = $segments[count($segments)-1];
			if( !is_numeric($page) ) $page = 1;
			if($page > 0) {
				$vars['limit'] = $mtconf->get('fe_num_of_links');
				$vars['limitstart'] = $mtconf->get('fe_num_of_links') * ($page -1);
			}
			break;
		
		case count($segments) == 2 && $mtconf->get('sef_editlisting') == $segments[count($segments)-2] && is_numeric($segments[count($segments)-1]):
			$vars['task'] = 'editlisting';
			$vars['link_id'] = $end_segment;
			break;
		
		case count($segments) == 2 && $mtconf->get('sef_image') == $segments[count($segments)-2] && is_numeric($segments[count($segments)-1]):
			$vars['task'] = 'viewimage';
			$vars['img_id'] = $end_segment;
			break;
			
		case count($segments) == 2 && $mtconf->get('sef_replyreview') == $segments[count($segments)-2]:
			$vars['task'] = 'replyreview';
			$vars['rev_id'] = $end_segment;
			break;
	
		case count($segments) == 2 && $mtconf->get('sef_reportreview') == $segments[count($segments)-2]:
			$vars['task'] = 'reportreview';
			$vars['rev_id'] = $end_segment;
			break;
		
		// Listing's task - http://example.com/directory/Business/Mosets/listing_task
		case in_array($end_segment,$listing_tasks):
			$path_names = array_slice( $segments, 0, -1 );
			$link_id = findLinkID( $path_names );
			$vars['task'] = array_search($end_segment,$listing_tasks);
			$vars['link_id'] = $link_id;

			break;
		
		case $mtconf->get('sef_addlisting'):
		case $mtconf->get('sef_addcategory'):
			if($end_segment == $mtconf->get('sef_addlisting')) {
				$vars['task'] = 'addlisting';
			} else {
				$vars['task'] = 'addcategory';
			}
			$cat_id = findCatId(array_slice($segments,0,-1));
			$vars['cat_id'] = $cat_id;
			break;
			
		case count($segments) > 1 && $mtconf->get('sef_rss') == $segments[count($segments)-2]:
			$vars['task'] = 'rss';
			$vars['cat_id'] = findCatId(array_slice($segments,0,-2));
			if($end_segment==$mtconf->get('sef_rss_new')) {
				$vars['type'] = 'new';
			} elseif ($end_segment==$mtconf->get('sef_rss_updated')) {
				$vars['type'] = 'updated';
			}
			break;
	
		default:
		
			// Find as category
			$pagepattern = $mtconf->get('sef_category_page') . "[0-9]+";
			if( eregi($pagepattern,$end_segment) ) {
				$cat_segments = $segments;
				array_pop($cat_segments);
				$cat_id = findCatId($cat_segments);
			} else {
				$cat_id = findCatId($segments);
			}
			if( !empty($cat_id) ) {
				$vars['cat_id'] = $cat_id;
			}
			$vars['task'] = 'listcats';
			$page = getPageNumber($segments);
			if($page > 0) {
				$vars['limit'] = $mtconf->get('fe_num_of_links');
				$vars['limitstart'] = $mtconf->get('fe_num_of_links') * ($page -1);
			}
			
			// If no category is found, find as a listing
			if( empty($cat_id) )
			{
				$isReviewsPage = eregi($mtconf->get('sef_reviews_page') . '[0-9]+',$end_segment);
				$link_id = findLinkID( $segments );
				$vars['task'] = 'viewlink';
				$vars['link_id'] = $link_id;
				if ( $isReviewsPage ) {
					// Get the page numner
					$pagenumber = substr( $end_segment, strlen($mtconf->get('sef_reviews_page')) );
					$vars['limit'] = $mtconf->get('fe_num_of_reviews');
					$vars['limitstart'] = $mtconf->get('fe_num_of_reviews') * ($pagenumber -1);
				}
			}
			break;
	}

	return $vars;
}

function appendCat($cat_id) {
	$cache =& JFactory::getCache('com_mtree');
	return $cache->call('appendCat_cached', $cat_id);
}

function appendCat_cached( $cat_id )
{
	global $mtconf;
	
	$segments = array();
	$sefstring = '';

	if(!class_exists('mtPathWay')) {
		require_once( $mtconf->getjconf('absolute_path').'/administrator/components/com_mtree/admin.mtree.class.php');
	}

	$pathWay = new mtPathWay( $cat_id );
	$pathway_ids = $pathWay->getPathWay( $cat_id );
	
	if( !empty($pathway_ids) ) {
		foreach( $pathway_ids AS $id ) {
			$segments[] = $pathWay->getCatAlias( $id );
		}
	}
	
	// If current category is not root, append to sefstring
	$cat_alias = $pathWay->getCatAlias($cat_id);
	if ( $cat_id > 0 && !empty($cat_alias) ) {
		$segments[] = $cat_alias;
	}
	return $segments;
}

function appendListing( $link_name, $link_id, $alias='', $add_details=false ) {
	global $mtconf;
	$segments = array();
	
	switch( $mtconf->get('sef_link_slug_type') )
	{
		case 1:
			$segments[] = $alias;
			break;
		case 2:
			$segments[] = $link_id;
			break;
	}

	if( $add_details ) {
		$segments[] = $mtconf->get('sef_details');
	}

	return $segments;
}

/***
* Find Category ID from an array list of names
* @param array Category name retrieved from SEF Advance URL. 
*/
function findCatID( $cat_names ) {
	global $mtconf;
	
	$db =& JFactory::getDBO();

	if ( count($cat_names) == 0 ) {
		return 0;
	}
	
	for($i=0;$i<count($cat_names);$i++) {
		$cat_names[$i] = preg_replace('/:/', '-', $cat_names[$i], 1);
	}

	// (1) 
	// First Attempt will try to search by category's alias. 
	// If it returns one result, then this is most probably the correct category
	$db->setQuery( "SELECT cat_id, cat_parent, alias FROM #__mt_cats WHERE cat_published='1' AND cat_approved='1' && BINARY alias = " . $db->quote($cat_names[ (count($cat_names)-1) ]) );
	$cat_ids = $db->loadObjectList();
	
	if ( count($cat_ids) == 1 && $cat_ids[0]->cat_id > 0 ) {

		return $cat_ids[0]->cat_id;
	
	} else {

	// (2)
	// Second attempt will match cat_id from the first level alias up to the 
	// final slug to get the definite category ID

		$pathway_cat_id_matches = array();
		$i=0;

		foreach( $cat_names AS $key => $cat_name )
		{
			if( $i == 0 )
			{
				$db->setQuery( "SELECT cat_id, cat_parent, alias FROM #__mt_cats WHERE cat_published='1' AND cat_approved='1' && BINARY alias = " . $db->quote($cat_name) . " && cat_parent = 0 LIMIT 1");
			}
			else
			{
				$db->setQuery( "SELECT cat_id, cat_parent, alias FROM #__mt_cats WHERE cat_published='1' AND cat_approved='1' && BINARY alias = " . $db->quote($cat_name) );
			}
			$pathway_cat_id_matches[] = $db->loadObjectList();
			$i++;
			if( $i == (count($cat_names)-1) )
			{
				$pathway_cat_id_matches[] = $cat_ids;
				break;
			}
		}
		
		$i = 0;
		$pathway_cat_id = array();
		foreach( $pathway_cat_id_matches AS $pathway_cat_id_match )
		{
			if( $i == 0 )
			{
				if( isset($pathway_cat_id_match[$i]->cat_id) ) {
					$pathway_cat_id[$i] = $pathway_cat_id_match[$i]->cat_id;
				}
				$i++;
				continue;
			}
			else 
			{
				foreach( $pathway_cat_id_match AS $objCat )
				{
					if( $objCat->cat_parent == $pathway_cat_id[$i-1] )
					{
						$pathway_cat_id[$i] = $objCat->cat_id;
						continue;
					}
				}
			}
			$i++;
		}
		
		if( count($pathway_cat_id) == count($cat_names) )
		{
			return array_pop($pathway_cat_id);
		}
		else
		{
			return false;
		}
	}
}

function findLinkID( $path_names ) {
	global $mtconf;
	
	$db =& JFactory::getDBO();

	$path_names[count($path_names)-1] = preg_replace('/:/', '-', $path_names[count($path_names)-1], 1);
	
	// (1) 
	// First Attempt will try to search by listing name. 
	// If it returns one result, then this is most probably the correct listing
	
	$link_name = $path_names[ (count($path_names)-1) ];
	$link_name = urldecode( $link_name );
	$link_ids = array();
	
	switch( $mtconf->get('sef_link_slug_type') )
	{			
		case 1:
			$db->setQuery( 'SELECT link_id FROM #__mt_links WHERE BINARY alias = ' . $db->quote($link_name) );
			$link_ids = $db->loadResultArray();
			break;
		case 2:
			return intval( $link_name );
			break;
	}
	
	if ( count($link_ids) == 1 && $link_ids[0] > 0 ) {

		return $link_ids[0];

	} else {

	// (2)
	// Second attempt will look for the category ID and then pinpoint the listing ID
		
		$cat_id = findCatID( array_slice($path_names, 0, -1) );
		
		if( $mtconf->get('sef_link_slug_type') == 1 )
		{
			$db->setQuery( "SELECT l.link_id FROM #__mt_links AS l, #__mt_cl AS cl "
				. " WHERE link_published='1' AND link_approved='1' AND cl.cat_id = '".$cat_id."'"
				. " AND BINARY l.alias = " . $db->quote($link_name) . " AND l.link_id = cl.link_id LIMIT 1" );
			return $db->loadResult();
		} else {
			return null;
		}
	}
}

function getPage($start,$limit) {
	return (($start / $limit) +1);
}

/***
* Try to find the page number from virtual directory - http://example.com/c/mtree/My_Listing/Page3.html
*
* @param array $url_array The SEF advance URL split in arrays (first custom virtual directory beginning at $pos+1)
* @return int Page number
*/
function getPageNumber( $segments ) {
	global $mtconf;
	
	$pagepattern = $mtconf->get('sef_category_page') . "[0-9]+";
	$pagenumber = 0;
	if ( eregi($pagepattern,end($segments)) ) {
		// Get the page number
		$pagenumber = substr( end($segments), strlen($mtconf->get('sef_category_page')));
	}
	return $pagenumber;
}

function getId( $type, &$query ) {
	$id = 0;
	if(isset($query[$type.'_id'])) {
		$id = intval($query[$type.'_id']);
		unset($query[$type.'_id']);
	}
	return $id;
}

/***
* Return value from appendCat + appendListing
*/
function appendCatListing( $mtLink, $add_extension=true ) {
	return array_merge( appendCat( $mtLink->cat_id ), appendListing( $mtLink->link_name, $mtLink->link_id, $mtLink->alias, false ) );
}

function appendTag($cf_id) {
	static $tags;
	
	if( !$tags )
	{
		$tags = getTagAliases();
	}
	
	if( isset($tags[$cf_id]) ) {
		return $tags[$cf_id]->alias;
	} else {
		return false;
	}
}

function findCfId($alias) {
	static $tags;
	
	if( !$tags )
	{
		$tags = getTagAliases();
	}
	
	foreach( $tags AS $tag ) {
		if( $tag->alias == $alias ) {
			return $tag->cf_id;
		}
	}
	return false;
}

function getTagAliases() {
	$db =& JFactory::getDBO();
	$db->setQuery('SELECT cf_id, caption FROM #__mt_customfields WHERE tag_search = 1 AND published = 1');
	$tags = $db->loadObjectList('cf_id');
	if( !empty($tags) )
	{
		foreach($tags AS $tag)
		{
			$tags[$tag->cf_id]->alias = JFilterOutput::stringURLSafe($tag->caption);
		}
	}
	return $tags;
}

function murlencode($string) {
	global $mtconf, $sef_replace;
	$string = urlencode($string);
	$string = eregi_replace($mtconf->get('sef_space'), "%252D", $string);
	$string = eregi_replace('\+', $mtconf->get('sef_space'), $string);
	$string = eregi_replace('\.', '%2E', $string);
	foreach ($sef_replace as $key => $value) {
		$string = eregi_replace($key, $value, $string);
	}
	return $string;
}

function murldecode($string) {
	global $mtconf, $sef_replace;
	foreach ($sef_replace as $key => $value) {
		$string = str_replace(strtolower($value), strtolower(urldecode($key)), strtolower($string));
	}
	$string = eregi_replace('%', "%25", $string);
	$string = eregi_replace($mtconf->get('sef_space'), "%20", $string);
	$string = eregi_replace('\+', "%2B", $string);
	$string = eregi_replace('&quot;', "%22", $string);
	$string = urldecode($string);
	$string = eregi_replace("%2D", "-", $string);
	return $string;
}
?>