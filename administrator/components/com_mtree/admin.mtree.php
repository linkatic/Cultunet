<?php
/**
 * @version		$Id: admin.mtree.php 908 2010-07-01 09:59:20Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

require_once(  JPATH_COMPONENT.DS.'config.mtree.class.php' );
global $mtconf;
$database =& JFactory::getDBO();
$mtconf = new mtConfig($database);

if($task != 'upgrade')
{
	require_once( JPATH_COMPONENT.DS.'admin.mtree.html.php' );
	require_once( JPATH_COMPONENT.DS.'admin.mtree.class.php' );
	require_once( JPATH_COMPONENT.DS.'tools.mtree.php' );
	require_once( JPATH_COMPONENT.DS.'mfields.class.php' );
	DEFINE( '_E_START_PUB', JText::_( 'Start Publishing' ) );
	DEFINE( '_E_FINISH_PUB', JText::_( 'Finish Publishing' ) );
}

// global $task;
$task = JRequest::getCmd( 'task', '');

# Cache
$cache = &JFactory::getCache('com_mtree');

# Categories name cache
$cache_cat_names = array();

$id	= JRequest::getInt('id', 0);

/* Cat ID 
 * Categories selected in category list
 */
$cat_id_fromurl	= JRequest::getInt('cat_id', 0);
if ($cat_id_fromurl == 0) {
	$cat_id = JRequest::getVar( 'cid', array(0), 'post');
	JArrayHelper::toInteger($cat_id, array(0));
} else {
	$cat_id = array( $cat_id_fromurl );
}

/* 
 * Link ID 
 * Listings selected in listing list
 */
$link_id_fromurl = JRequest::getInt('link_id', '');
if ($link_id_fromurl == '') {
	$link_id = JRequest::getVar('lid', array(), 'post');
	JArrayHelper::toInteger($link_id, array());
} else {
	$link_id = array( $link_id_fromurl );
}

/* Review ID */
$rev_id	= JRequest::getVar('rid', array(), 'post');
JArrayHelper::toInteger($rev_id, array());
if( empty($rev_id[0]) ) {
	$rev_id[0] = JRequest::getInt('rid', 0);
}

/* Custom Field ID */
$cf_id	= JRequest::getVar('cfid', array(), 'post');
JArrayHelper::toInteger($cf_id, array());
if( empty($cf_id[0]) ) {
	$cf_id[0] = JRequest::getInt('cfid', 0);
}

$cat_parent	= JRequest::getInt('cat_parent', 0);

/* Hide menu */
$hide_menu = JRequest::getInt('hide', 0);

/* Get Category ID for the Add Category/Listing links */
if ($task == 'newlink' || $task == 'newcat') {
	$parent_cat	= JRequest::getInt('cat_parent', 0);
} else {
	$parent_cat	= JRequest::getInt('cat_id', 0);
}

/* Start Left Navigation Menu */
if ( !$hide_menu && !in_array($task,array('upgrade','spy','ajax','downloadft', 'manageftattachments')) ) {
	HTML_mtree::print_startmenu( $task, $parent_cat );
}

switch ($task) {
	/***
	 * Ajax event
	 */
	 case 'ajax':
		 require_once($mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/admin.mtree.ajax.php');
		break;
	/***
	 * Spy
	 */
	 case 'spy':
		require_once($mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/spy.mtree.php');
		break;
	/***
	* Link Checker
	*/
	case 'linkchecker':
		require_once($mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/linkchecker.mtree.php');
		break;

	/***
	* Custom Fields
	*/
	case 'customfields':
	case "newcf":
	case "editcf":
	case "savecf":
	case "applycf":
	case 'cf_orderup':
	case 'cf_orderdown':
	case 'cancelcf':
	case 'cf_unpublish':
	case 'cf_publish':
	case 'removecf':
	case 'managefieldtypes':
	case 'newft':
	case 'editft':
	case 'saveft':
	case 'applyft':
	case 'cancelft':
	case 'downloadft':
	case 'uploadft':
	case 'removeft':
	case 'manageftattachments':
		require_once($mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/customfields.mtree.php');
		switch( $task ) {
			case "newcf":
				editcf( 0, $option );
				break;
			case "editcf":
				editcf( $cf_id[0], $option );
				break;
			case "applycf":
			case "savecf":
				savecf( $option );
				break;
			case 'cf_orderup':
				ordercf( intval( $cf_id[0] ), -1, $option );
				break;
			case 'cf_orderdown':
				ordercf( intval( $cf_id[0] ), 1, $option );
				break;
			case 'cancelcf':
				cancelcf( $option );
				break;
			case 'cf_unpublish':
				cf_publish( $cf_id, 0, $option );
				break;
			case 'cf_publish':
				cf_publish( $cf_id, 1, $option );
				break;
			case 'removecf':
				removecf( $cf_id, $option );
				break;	
			case 'customfields':
				customfields( $option );
				break;	
			case 'managefieldtypes':
				managefieldtypes( $option );
				break;
			case 'newft':
				editft( 0, $option );
				break;
			case 'editft':
				editft( $cf_id[0], $option );
				break;
			case 'applyft':
			case 'saveft':
				saveft( $id, $option );
				break;
			case 'cancelft':
				cancelft( $option );
				break;
			case 'downloadft':
				downloadft( $cf_id[0], $option );
				break;
			case 'uploadft':
				uploadft( $option );
				break;
			case 'removeft':
				removeft( $cf_id[0], $option );
				break;	
			case 'manageftattachments':
				manageftattachments($id, $option);
				break;				
		}
		break;

	/***
	 * Categories
	 */
	case "listcats":
		listcats( $cat_id[0], $cat_parent, $option );
		break;
	case "newcat":
		editcat( 0, $cat_parent, $option );
		break;
	case "editcat":
		editcat( $cat_id[0], $cat_parent, $option );
		break;
	case "editcat_browse_cat":
		editcat_browse_cat( $option, 0 );
		break;
	case "editcat_add_relcat":
		editcat_browse_cat( $option, 1 );
		break;
	case "editcat_remove_relcat":
		editcat_browse_cat( $option, -1 );
		break;
	case "applycat":
	case "savecat":
		$cache->clean();
		savecat( $option );
		break;
	case "cat_publish":
		$cache->clean();
		publishCats( $cat_id, 1, $option );
		break;
	case "cat_unpublish":
	$cache->clean();
		publishCats( $cat_id, 0, $option );
		break;
	case "cancelcat":
		cancelcat( $cat_parent, $option );
		break;
	case "removecats":
		$cache->clean();
		removecats( $cat_id, $option );
		break;
	case "removecats2":
		$cache->clean();
		removecats2( $cat_id, $option );
		break;
	
	case "fastadd_cat":
		$cache->clean();
		fastadd_cat( $cat_parent, $option );
		break;

	/*
	case "cat_orderup":
		orderCats( $cat_id[0], -1, $option );
		break;
	case "cat_orderdown":
		orderCats( $cat_id[0], 1, $option );
		break;
	*/
	case "cat_featured":
		$cache->clean();
		featuredCats( $cat_id, 1, $option );
		break;
	case "cat_unfeatured":
		$cache->clean();
		featuredCats( $cat_id, 0, $option );
		break;
	case "cats_move":
		moveCats( $cat_id, $cat_parent, $option );
		break;
	case "cats_move2":
		$cache->clean();
		moveCats2( $cat_id, $option );
		break;
	case "cats_copy":
		copyCats( $cat_id, $cat_parent, $option );
		break;
	case "cats_copy2":
		$cache->clean();
		copyCats2( $cat_id, $option );
		break;
	case "cancelcats_move":
		cancelcats_move( $cat_id[0], $option );
		break;

	/***
	 * Links
	 */
	case "newlink":
		editlink( 0, $cat_parent, false, $option );
		break;
	case "editlink":
		editlink( $link_id[0], $cat_parent, false, $option );
		break;
	case "editlink_for_approval":
		editlink( $link_id[0], $cat_parent, true, $option );
		break;
	/*
	case "editlink_browse_cat":
		editlink_browse_cat( $option, 0 );
		break;
	case "editlink_add_cat":
		editlink_browse_cat( $option, 1 );
		break;
	case "editlink_remove_cat":
		editlink_browse_cat( $option, -1 );
		*/
	case "openurl":
		openurl( $option );
		break;
	case "editlink_change_cat":
		editlink_change_cat( $option );
		break;
	case "savelink":
	case "applylink":
		$cache->clean();
		savelink( $option );
		break;
	case "next_link":
		$cache->clean();
		prev_next_link( "next", $option );
		break;
	case "prev_link":
		$cache->clean();
		prev_next_link( "prev", $option );
		break;
	case "link_publish":
		$cache->clean();
		publishLinks( $link_id, 1, $option );
		break;
	case "link_unpublish":
		$cache->clean();
		publishLinks( $link_id, 0, $option );
		break;
	case "removelinks":
		$cache->clean();
		removelinks( $link_id, $option );
		break;
	/*
	case "link_orderup":
		orderLinks( $link_id[0], -1, $option );
		break;
	case "link_orderdown":
		orderLinks( $link_id[0], 1, $option );
		break;
	*/
	case "link_featured":
		$cache->clean();
		featuredLinks( $link_id, 1, $option );
		break;
	case "link_unfeatured":
		$cache->clean();
		featuredLinks( $link_id, 0, $option );
		break;
	case "cancellink":
		cancellink( $link_id[0], $option );
		break;
	case "links_move":
		moveLinks( $link_id, $cat_parent, $option );
		break;
	case "links_move2":
		$cache->clean();
		moveLinks2( $link_id, $option );
		break;
	case "cancellinks_copy":
	case "cancellinks_move":
		cancellinks_move( $link_id[0], $option );
		break;
	case "links_copy":
		copyLinks( $link_id, $cat_parent, $option );
		break;
	case "links_copy2":
		$cache->clean();
		copyLinks2( $link_id, $option );
		break;
		
	/***
	* Approval / List Pending
	*/
	case "listpending_cats":
		listpending_cats( $option );
		break;
	case "approve_cats":
		$cache->clean();
		approve_cats( $cat_id, 0, $option );
		break;
	case "approve_publish_cats":
		$cache->clean();
		approve_cats( $cat_id, 1, $option );
		break;

	case "listpending_links":
		listpending_links( $option );
		break;
	case "approve_links":
		$cache->clean();
		approve_links( $link_id, 0, $option );
		break;
	case "approve_publish_links":
		$cache->clean();
		approve_links( $link_id, 1, $option );
		break;

	case "listpending_reviews":
		listpending_reviews( $option );
		break;
	case "save_pending_reviews":
		save_pending_reviews( $option );
		break;

	case "listpending_reports":
		listpending_reports( $option );
		break;
	case "save_reports":
		save_reports( $option );
		break;

	case "listpending_reviewsreports":
		listpending_reviewsreports( $option );
		break;
	case "save_reviewsreports":
		save_reviewsreports( $option );
		break;

	case "listpending_reviewsreply":
		listpending_reviewsreply( $option );
		break;
	case "save_reviewsreply":
		save_reviewsreply( $option );
		break;

	case "listpending_claims":
		listpending_claims( $option );
		break;
	case "save_claims":
		save_claims( $option );
		break;

	/***
	* Reviews
	*/
	case "reviews_list":
		list_reviews( $link_id[0], $option );
		break;
	case "newreview":
		editreview( 0, $link_id[0], $option );
		break;
	case "editreview":
		editreview( $rev_id[0], $cat_parent, $option );
		break;
	case "savereview":
		$cache->clean();
		savereview( $option );
		break;
	case "cancelreview":
		cancelreview( $link_id[0], $option );
		break;
	case "removereviews":
		$cache->clean();
		removereviews( $rev_id, $option );
		break;
	case "backreview":
		backreview( $link_id[0], $option );
		break;

	/***
	* Search
	*/
	case "search":
		search( $option );
		break;
	case "advsearch":
		advsearch( $option );
		break;
	case "advsearch2":
		require_once( $mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/mAdvancedSearch.class.php' );
		advsearch2( $option );
		break;

	/***
	* About Mosets Tree
	*/
	case "about":
		HTML_mtree::about( $option );
		break;

	/***
	* Tree Templates
	*/
	case "templates":
		require_once( $mtconf->getjconf('absolute_path') .'/includes/domit/xml_domit_lite_include.php' );
		templates( $option );
		break;
	case "template_pages":
		require_once( $mtconf->getjconf('absolute_path') .'/includes/domit/xml_domit_lite_include.php' );
		template_pages( $option );
		break;
	case "edit_templatepage":
		edit_templatepage( $option );
		break;
	case "save_templatepage":
	case 'apply_templatepage':
		$cache->clean();
		save_templatepage( $option );
		break;
	case "cancel_edittemplatepage":
		cancel_edittemplatepage( $option );
		break;
	case "cancel_templatepages":
		cancel_templatepages( $option );
		break;
	case "new_template":
		new_template( $option );
		break;
	case "install_template":
		install_template( $option );
		break;
	case "default_template":
		default_template( $option );
		break;
	case "copy_template":
		require_once( $mtconf->getjconf('absolute_path') .'/includes/domit/xml_domit_lite_include.php' );
		copy_template( $option );
		break;
	case "copy_template2":
		copy_template2( $option );
		break;
	case "delete_template":
		delete_template( $option );
		break;
	case 'save_templateparams':
	case 'apply_templateparams':
		save_templateparam( $option );
		break;
			
	/***
	* Configuration
	*/
	case "config":
		$show = JRequest::getCmd( 'show', '');
		config( $option, $show );
		break;
	case "saveconfig":
		$cache->clean();
		saveconfig( $option );
		break;
	
	/***
	* Custom Fields
	*/
	case "customfields":
		customfields( $option );
		break;
	case "save_customfields":
		$cache->clean();
		save_customfields( $option );
		break;

	/***
	* License
	*/
	case "license":
		include( "license.mtree.php" );
		break;

	/***
	* CSV Import/Export
	*/
	case "csv":
		csv( $option );
		break;
	case "csv_export":
		csv_export( $option );
		break;

	/***
	* Upgrade routine
	*/
	case "upgrade":
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mtree'.DS.'upgrade.php' );
		break;

	/***
	* Diagnosis
	*/
	case "diagnosis":
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mtree'.DS.'diagnosis.php' );
		startprint( 0 );
		break;

	/***
	* Rebuild Tree
	*/
	case "rebuild_tree":
		$tree = new mtTree();
		$tree->rebuild( 0, 1);

		$database->setQuery( "SELECT MAX(rgt) FROM #__mt_cats" );
		$max_rgt = $database->loadResult();
		$database->setQuery( "UPDATE #__mt_cats SET rgt = ".($max_rgt +1).", lft=1 WHERE cat_id = 0 OR cat_parent = -1" );
		$database->query();
		break;

	/***
	* Global Update
	*/
	case "globalupdate":
		$cache->clean();
		require_once( $mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/recount.mtree.php' );
		update_cats_and_links_count( 0, true, true );
		$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=0", JText::_( 'Cat and listing count updated' ) );
		break;

	/***
	* Geocode
	*/
	case "geocode":
		require_once( $mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/geocode.mtree.php' );
		break;

	/***
	* Recount
	*/
	case "fullrecount":
		require_once( $mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/recount.mtree.php' );
		recount( 'full', $cat_id[0] );
		break;
	
	case "fastrecount":
		require_once( $mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/recount.mtree.php' );
		recount( 'fast', $cat_id[0] );
		break;
		

	/***
	* Default List Category
	*/
	default:
		listcats( $cat_id[0], $cat_parent, $option );
		break;
}

/* End Left Navigation Menu */
if ( !$hide_menu && !in_array($task,array('upgrade','spy','ajax','downloadft', 'manageftattachments')) ) {
	HTML_mtree::print_endmenu();
}


/***
* Link
*/

function editlink( $link_id, $cat_id, $for_approval=false, $option ) {
	global $mainframe, $mtconf;
	
	$database 	=& JFactory::getDBO();
	$my			=& JFactory::getUser();

	$row = new mtLinks( $database );
	$row->load( $link_id );

	if ($row->link_id == 0) {
		$createdate =& JFactory::getDate();
		$row->cat_id = $cat_id;
		$row->alias = '';
		$row->link_hits = 0;
		$row->link_visited = 0;
		$row->link_votes = 0;
		$row->link_rating = 0.00;
		$row->link_featured = 0;
		$row->link_created = $createdate->toUnix();
		$row->publish_up = $createdate->toUnix();
		$row->publish_down = JText::_('Never');
		$row->link_published = 1;
		$row->link_approved = 1;
		$row->user_id = $my->id;
		$row->owner= $my->username;
	} else {
		if ($row->user_id > 0) {
			$database->setQuery( 'SELECT username FROM #__users WHERE id =' . $database->quote($row->user_id) );
			$row->owner = $database->loadResult();
		} else {
			$row->owner= $my->username;
		}
	}

	if ( $cat_id == 0 && $row->cat_id > 0 ) $cat_id = $row->cat_id;
	
	# Load images
	$database->setQuery( 'SELECT img_id, filename FROM #__mt_images WHERE link_id = ' . $database->quote($row->link_id) . ' ORDER BY ordering ASC' );
	$images = $database->loadObjectList();
	
	$lists = array();

	# Load all published CORE & custom fields
	$sql = "SELECT cf.*, " . ($row->link_id ? $row->link_id : 0) . " AS link_id, cfv.value, cfv.attachment, cfv.counter, ft.ft_class FROM #__mt_customfields AS cf "
		.	"\nLEFT JOIN #__mt_cfvalues AS cfv ON cf.cf_id=cfv.cf_id AND cfv.link_id = " . $database->quote($link_id)
		.	"\nLEFT JOIN #__mt_fieldtypes AS ft ON ft.field_type=cf.field_type"
		.	"\nWHERE cf.published='1' ORDER BY ordering ASC";
	$database->setQuery($sql);

	$fields = new mFields();
	$fields->setCoresValue( $row->link_name, $row->link_desc, $row->address, $row->city, $row->state, $row->country, $row->postcode, $row->telephone, $row->fax, $row->email, $row->website, $row->price, $row->link_hits, $row->link_votes, $row->link_rating, $row->link_featured, $row->link_created, $row->link_modified, $row->link_visited, $row->publish_up, $row->publish_down, $row->metakey, $row->metadesc, $row->user_id, $row->owner );
	$fields->loadFields($database->loadObjectList());
	
	# Get other categories
	$database->setQuery( 'SELECT cl.cat_id FROM #__mt_cl AS cl WHERE cl.link_id = ' . $database->quote($link_id) . ' AND cl.main = 0');
	$other_cats = $database->loadResultArray();

	# Get Pathway
	$pathWay = new mtPathWay( $cat_id );

	# Is this approval for modification?
	if ( $row->link_approved < 0 ) {
		$row->original_link_id = (-1 * $row->link_approved);
	} else {
		$row->original_link_id = '';
	}

	# Compile list of categories
	if ( $cat_id > 0 ) {
		$database->setQuery( 'SELECT cat_parent FROM #__mt_cats WHERE cat_id = ' . $database->quote($cat_id) );
		$browse_cat_parent = $database->loadResult();
	}
	$categories = array();
	if ( $cat_id > 0 ) {
		$categories[] = JHTML::_('select.option', $browse_cat_parent, JText::_( 'Arrow back' ) );
	}
	$database->setQuery( 'SELECT cat_id AS value, cat_name AS text FROM #__mt_cats'
	. "\nWHERE cat_parent = " . $database->quote($cat_id) . " AND cat_approved = '1' AND cat_published = '1' ORDER BY cat_name ASC" );
	$categories = array_merge( $categories, $database->loadObjectList() );
	$lists['cat_id'] = JHTML::_('select.genericlist', $categories, 'new_cat_id', 'size="8" class="text_area" style="display:block;width:50%;margin-top:6px;"',	'value', 'text', $row->getCatID(), 'browsecat' );
	
	# Get Return task - Used by listpending_links
	$returntask	= JRequest::getCmd('returntask', '', 'post');
	
	# Get params definitions
	$form = new JParameter($row->attribs, JPATH_COMPONENT.DS.'models'.DS.'listing.xml');
	$form->set('owner', $row->owner);
	$form->set('alias', $row->alias);
	$form->set('link_approved', $row->link_approved);
	$form->set('link_published', $row->link_published);
	$form->set('link_featured', $row->link_featured);
	$form->set('link_created', JHTML::_('date', $row->link_created, '%Y-%m-%d %H:%M:%S'));
	$form->set('publish_up', JHTML::_('date', $row->publish_up, '%Y-%m-%d %H:%M:%S'));
	if (JHTML::_('date', $row->publish_down, '%Y') <= 1969 || $row->publish_down == $database->getNullDate()) {
		$form->set('publish_down', JText::_('Never'));
	} else {
		$form->set('publish_down', JHTML::_('date', $row->publish_down, '%Y-%m-%d %H:%M:%S'));
	}
	$form->set('link_template', $row->link_template);
	$form->set('metakey', $row->metakey);
	$form->set('metadesc', $row->metadesc);
	$form->set('link_rating', $row->link_rating);
	$form->set('link_votes', $row->link_votes);
	$form->set('link_hits', $row->link_hits);
	$form->set('link_visited', $row->link_visited);
	$form->set('internal_notes', $row->internal_notes);
	
	$form->loadINI($row->attribs);	
	
	if ( $row->link_approved <= 0 ) {
		$database->setQuery( 'SELECT link_id FROM #__mt_links WHERE link_approved <= 0 ORDER BY link_created ASC, link_modified DESC' );
		$links = $database->loadResultArray();
		$number_of_prev = array_search($row->link_id,$links);
		$number_of_next = count($links) - 1 - $number_of_prev;
	} else {
		$number_of_prev = 0;
		$number_of_next = 0;
	}

	HTML_mtree::editlink( $row, $fields, $images, $cat_id, $other_cats, $lists, $number_of_prev, $number_of_next, $pathWay, $returntask, $form, $option );
}

function openurl( $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();

	$url = JRequest::getVar( 'url', '');

	if ( substr($url, 0, 7) <> "http://" && substr($url, 0, 8) <> "https://") {
		$url = "http://".$url;
	}

	$mainframe->redirect( $url );
}

function prev_next_link( $prevnext, $option ) {
	global $mtconf;
	
	$database 	=& JFactory::getDBO();
	$jdate		= JFactory::getDate();

	$act		= JRequest::getCmd('act', '', 'post');
	$link_id	= JRequest::getInt('link_id', '', 'post');
	$post		= JRequest::get('post');
	
	$database->setQuery( 'SELECT link_id FROM #__mt_links WHERE link_approved <= 0 ORDER BY link_created ASC, link_modified DESC' );
	$links = $database->loadResultArray();
	if ( array_key_exists((array_search($link_id,$links) + 1),$links) ) {
		$next_link_id = $links[(array_search($link_id,$links) + 1)];
	} else {
		$next_link_id = 0;
	}
	
	if ( array_key_exists((array_search($link_id,$links) - 1),$links) ) {
		$prev_link_id = $links[(array_search($link_id,$links) - 1)];
	} else {
		$prev_link_id = 0;
	}

	if ( $prevnext == "next" ) {
		if ( $next_link_id > 0 ) {
			$post['returntask'] = "editlink&link_id=".$next_link_id;
		} else {
			$post['returntask'] = "listpending_links";
		}
	} elseif( $prevnext == "prev" ) {
		if ( $prev_link_id > 0 ) {
			$post['returntask'] = "editlink&link_id=".$prev_link_id;
		} else {
			$post['returntask'] = "listpending_links";
		}
	}

	switch( $act ) {

		case "ignore":
			savelink( $option, $post );
			break;

		case "discard":
			removeLinks( array($link_id), $option, $post );
			break;

		case "approve":
			$post['publishing']['link_approved'] = 1;
			$post['publishing']['link_published'] = 1;

			if( $mtconf->get('reset_created_date_upon_approval') ) {
				$post['publishing']['link_created'] = $jdate->toMySQL();
			}
			
			savelink( $option, $post );
			break;
	}

}

function savelink( $option, $post=null ) {
	global $mtconf, $mainframe;
	
	$database 	=& JFactory::getDBO();
	$my			=& JFactory::getUser();
	$config 	=& JFactory::getConfig();
	$nullDate	= $database->getNullDate();
	$dispatcher	=& JDispatcher::getInstance();
	
	$stored = false;

	$row = new mtLinks( $database );

	if( is_null($post) ) {
		$post = JRequest::get( 'post' );
	}

	if (!$row->bind( $post )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	$original_link_id	= (int) $post['original_link_id'];
	$cat_id				= (int) $post['cat_id'];
	$row->cat_id		= $cat_id;
	$other_cats 		= explode(',', $post['other_cats']);

	$tzoffset = $config->getValue('config.offset');

	$publishing = $post['publishing'];
	$row->bind($publishing);

	# Is this a new listing?
	$new_link = false;
	$old_image = '';
	
	// Yes, new listing
	if ($row->link_id == 0) {
		$new_link = true;
		
		if ($row->link_created && strlen(trim( $row->link_created )) <= 10) {
			$row->link_created 	.= ' 00:00:00';
		}

		$date =& JFactory::getDate($row->link_created, $tzoffset);
		$row->link_created = $date->toMySQL();

	// No, this listing has been saved to the database 
	// 1) Submission from visitor
	// 2) Modification request from listing owner
	} else {
		$row->link_modified = $row->getLinkModified( (empty($original_link_id)?$row->link_id:$original_link_id), $post );

		# Let's check if this link is on "pending approval" from an existing listing
		$database->setQuery( "SELECT link_approved FROM #__mt_links WHERE link_id = $row->link_id LIMIT 1" );
		$thislink = $database->loadObject(); // 1: approved; 0:unapproved/new listing; <-1: pending approval for update
		$link_approved = $thislink->link_approved;

		if ( $link_approved < 0 && $row->link_approved == 0 ) {
			$row->link_approved = $link_approved;
		}

	}

	// Append time if not added to publish date
	if (strlen(trim($row->publish_up)) <= 10) {
		$row->publish_up .= ' 00:00:00';
	}

	$date =& JFactory::getDate($row->publish_up, $tzoffset);
	$row->publish_up = $date->toMySQL();

	// Handle never unpublish date
	if (trim($row->publish_down) == JText::_('Never') || trim( $row->publish_down ) == '')
	{
		$row->publish_down = $nullDate;
	}
	else
	{
		if (strlen(trim( $row->publish_down )) <= 10) {
			$row->publish_down .= ' 00:00:00';
		}
		$date =& JFactory::getDate($row->publish_down, $tzoffset);
		$row->publish_down = $date->toMySQL();
	}
	
	$date =& JFactory::getDate($row->link_created, $tzoffset);
	$row->link_created = $date->toMySQL();

	$notes = $post['notes'];
	$row->bind($notes);
	
	# Lookup owner's userid. Return error if does not exists
	if ($publishing['owner'] == '') {
		// If owner field is left blank, assign the link to the current user
		$row->user_id = $my->id;
	} else {
		$database->setQuery( 'SELECT id FROM #__users WHERE username = ' . $database->quote($publishing['owner']) );
		$owner_id = $database->loadResult();
		if ($owner_id > 0) {
			$row->user_id = $owner_id;
		} else {
			echo "<script> alert('".JText::_( 'Invalid owner select again' )."'); window.history.go(-1); </script>\n";
			exit();
		}
	}
	
	# Listing alias
	if( empty($row->alias) )
	{
		$row->alias = JFilterOutput::stringURLSafe($row->link_name);
	}
	
	# Save parameters
	$params = $post['params'];

	if ( is_array( $params ) ) {
		$attribs = array();
		foreach ( $params as $k=>$v) {
			$attribs[] = "$k=$v";
		}
		$row->attribs = implode( "\n", $attribs );
	}

	# Publish the listing
	if ( $row->link_published && $row->link_id > 0 ) {
		$row->publishLink( 1 );
	} elseif ( !$row->link_published ) {
		$row->publishLink( 0 );
	}

	# Approve listing and send e-mail notification to the owner and admin
	if ( $row->link_approved == 1 && $row->link_id > 0 ) {
		# Get this actual link_approved value from DB
		$database->setQuery( 'SELECT link_approved FROM #__mt_links WHERE link_id = ' . $database->quote($row->link_id) );
		$link_approved = $database->loadResult();

		# This is a modification to the existing listing
		if ( $link_approved <= 0 ) {
			$row->updateLinkCount( 1 );
			$row->approveLink();
			// $stored = true;
		}
	}

	# Update the Link Counts for all cat_parent(s)
	if ($new_link) {
		$row->updateLinkCount( 1 );
	} else {
		// Get old state (approved, published)
		$database->setQuery( 'SELECT link_approved, link_published, cl.cat_id FROM (#__mt_links AS l, #__mt_cl AS cl) WHERE l.link_id = cl.link_id AND l.link_id = ' . $database->quote($row->link_id) . ' LIMIT 1' );
		$old_state = $database->loadObject();

		// From approved & published -to-> unapproved/unpublished
		if ( $old_state->link_approved == 1 && $old_state->link_published == 1 ) {
			if ( $row->link_published == 0 || $row->link_approved == 0) {
				$row->updateLinkCount( -1 );
			}

		// From unpublished/unapproved -to-> Published & Approved
		} elseif( $row->link_published == 1 && $row->link_approved == 1) {
			$row->updateLinkCount( 1 );
		}

		// Update link count if changing to a new category
		if ( $old_state->cat_id <> $cat_id && $old_state->link_approved <> 0 ) {
			$oldrow = new mtLinks( $database );
			$oldrow->cat_id = $old_state->cat_id;
			$oldrow->updateLinkCount( -1 );

			$newrow = new mtLinks( $database );
			$newrow->cat_id = $cat_id;
			$newrow->updateLinkCount( 1 );
		}
	}
	
	# Erase Previous Records, make way for the new data
	$sql = 'DELETE FROM #__mt_cfvalues WHERE link_id= ' . $database->quote($row->link_id) . ' AND attachment <= 0';
	$database->setQuery($sql);
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	# Load field type
	$database->setQuery( 'SELECT cf_id, field_type FROM #__mt_customfields' );
	$fieldtype = $database->loadObjectList('cf_id');
	
	if(count($fieldtype) > 0 ) {
		$load_ft = array();
		foreach( $fieldtype AS $ft ) {
			if(!in_array($ft->field_type,$load_ft)) {
				$load_ft[] = $ft->field_type;
			}
		}
		$database->setQuery('SELECT ft_class FROM #__mt_fieldtypes WHERE field_type IN (\'' . implode('\',\'',$load_ft) . '\')');
		$ft_classes = $database->loadResultArray();
		foreach( $ft_classes AS $ft_class ) {
			eval($ft_class);
		}
	}
	
	# Collect all active custom field's id
	$active_cfs = array();
	$additional_cfs = array();
	$core_params = array();
	
	foreach($post AS $k => $v) {
		$v = JRequest::getVar( $k, '', 'post', '', 2);
		if ( substr($k,0,2) == "cf" && ( (!is_array($v) && (!empty($v) || $v == '0')) || (is_array($v) && !empty($v[0])) ) ) {
			if(strpos(substr($k,2),'_') === false && is_numeric(substr($k,2))) {
				// This custom field uses only one input. ie: cf17, cf23, cf2
				$active_cfs[intval(substr($k,2))] = $v;
			} else {
				// This custom field uses more than one input. The date field is an example of cf that uses this. ie: cf13_0, cf13_1, cf13_2
				$ids = explode('_',substr($k,2));
				if(count($ids) == 2 && is_numeric($ids[0]) && is_numeric($ids[1]) ) {
					$additional_cfs[intval($ids[0])][intval($ids[1])] = $v;
				}
			}
		} elseif( substr($k,0,7) == 'keep_cf' ) {
			$cf_id = intval(substr($k,7));
			$keep_att_ids[] = $cf_id;
			
	# Perform parseValue on Core Fields
		} elseif( substr($k,0,2) != "cf" && isset($row->{$k}) ) {
			if(strpos(strtolower($k),'link_') === false) {
				$core_field_type = 'core' . $k;
			} else {
				$core_field_type = 'core' . str_replace('link_','',$k);
			}
			$class = 'mFieldType_' . $core_field_type;
			
			if(class_exists($class)) {
				if(empty($core_params)) {
					$database->setQuery( 'SELECT field_type, params FROM #__mt_customfields WHERE iscore = 1' );
					$core_params = $database->loadObjectList('field_type');
				}
				$mFieldTypeObject = new $class(array('params'=>$core_params[$core_field_type]->params));
				$v = call_user_func(array(&$mFieldTypeObject, 'parseValue'),$v);
				$row->{$k} = $v;
			}
		}
	}

	if (!$stored) {
		# Save core values to database
		if (!$row->store()) {
			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			exit();
		} else {
		
			# If this is a newlink, rename the photo to listingID_photoName.jpg
			if ( $new_link ) {

				// Get last inserted listing ID
				$mysql_last_insert_cl_id = $database->insertid();

				$database->setQuery( 'SELECT link_id FROM #__mt_cl WHERE cl_id = ' . $database->quote($mysql_last_insert_cl_id) );
				$mysql_last_insert_id = $database->loadResult();

			}
		}

	}
	
	// $files_cfs is used to store attachment custom fields. 
	// This will be used in the next foreach loop to 
	// prevent it from storing it's value to #__mt_cfvalues 
	// table
	$file_cfs = array();
	
	// $file_values is used to store parsed data through 
	// mFieldType_* which will be done in the next foreach 
	// loop
	$file_values = array();
	$files = JRequest::get('files');
	
	foreach($files AS $k => $v) {
		if ( substr($k,0,2) == "cf" && is_numeric(substr($k,2)) && $v['error'] == 0) {
			$active_cfs[intval(substr($k,2))] = $v;
			$file_cfs[] = intval(substr($k,2));
		}
	}

	if(count($active_cfs)>0) {
		$database->setQuery('SELECT cf_id, params FROM #__mt_customfields WHERE iscore = 0 AND cf_id IN (\'' . implode('\',\'',array_keys($active_cfs)). '\') LIMIT ' . count($active_cfs));
		$params = $database->loadObjectList('cf_id');
		
		foreach($active_cfs AS $cf_id => $v) {
			if(class_exists('mFieldType_'.$fieldtype[$cf_id]->field_type)) {
				$class = 'mFieldType_'.$fieldtype[$cf_id]->field_type;
			} else {
				$class = 'mFieldType';
			}
		
			# Perform parseValue on Custom Fields
			
			$mFieldTypeObject = new $class(array('id'=>$cf_id,'params'=>$params[$cf_id]->params));
			
			if(array_key_exists($cf_id,$additional_cfs) && count($additional_cfs[$cf_id]) > 0) {
				$arr_v = $additional_cfs[$cf_id];
				array_unshift($arr_v, $v);
				$v = &$mFieldTypeObject->parseValue($arr_v);
			} else {
				$v = &$mFieldTypeObject->parseValue($v);
			}
			
			if(in_array($cf_id,$file_cfs)) {
				$file_values[$cf_id] = $v;
			}
			
			if( (!empty($v) || $v == '0') && !in_array($cf_id,$file_cfs)) {
				# -- Now add the row
				$sql = 'INSERT INTO #__mt_cfvalues (`cf_id`, `link_id`, `value`)'
					. "\nVALUES (" . $database->quote($cf_id) . ', ' . $database->quote($row->link_id) . ', ' . $database->quote((is_array($v)) ? implode('|',$v) : $v). ')';
				$database->setQuery($sql);
				if (!$database->query()) {
					echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
					exit();
				}
			}
			unset($mFieldTypeObject);
		} // End of foreach
	}

	# Remove all attachment except those that are kept
	$raw_filenames = array();
	
	if(isset($keep_att_ids) && count($keep_att_ids)>0) {
		$database->setQuery( 'SELECT CONCAT(' . $database->quote(JPATH_SITE.$mtconf->get('relative_path_to_attachments')) . ',raw_filename) FROM #__mt_cfvalues_att WHERE link_id = ' . $database->quote($row->link_id) . ' AND cf_id NOT IN (\'' . implode('\',\'',$keep_att_ids) . '\')' );
		$raw_filenames = $database->loadResultArray();
		
		$database->setQuery('DELETE FROM #__mt_cfvalues_att WHERE link_id = ' . $database->quote($row->link_id) . ' AND cf_id NOT IN (\'' . implode('\',\'',$keep_att_ids) . '\')' );
		$database->query();
		$database->setQuery('DELETE FROM #__mt_cfvalues WHERE link_id = ' . $database->quote($row->link_id) . ' AND cf_id NOT IN (\'' . implode('\',\'',$keep_att_ids) . '\') AND attachment > 0' );
		$database->query();
	} else {
		$database->setQuery( 'SELECT CONCAT(' . $database->quote(JPATH_SITE.$mtconf->get('relative_path_to_attachments')) . ',raw_filename) FROM #__mt_cfvalues_att WHERE link_id = ' . $database->quote($row->link_id) );
		$raw_filenames = $database->loadResultArray();
		
		$database->setQuery('DELETE FROM #__mt_cfvalues_att WHERE link_id = ' . $database->quote($row->link_id) );
		$database->query();
		$database->setQuery('DELETE FROM #__mt_cfvalues WHERE link_id = ' . $database->quote($row->link_id) . ' AND attachment > 0' );
		$database->query();
	}
	
	jimport('joomla.filesystem.file');

	foreach($files AS $k => $v) {
		if ( substr($k,0,2) == "cf" && is_numeric(substr($k,2)) && $v['error'] == 0) {
			$cf_id = intval(substr($k,2));

			if(array_key_exists($cf_id,$file_values)) {
				$file = $file_values[$cf_id];
				if(!empty($file['data'])) {
					$data = $file['data'];
				} else {
					$fp = fopen($v['tmp_name'], "r");
					$data = fread($fp, $v['size']);
					fclose($fp);
				}
			} else {
				$file = $v;
				$fp = fopen($v['tmp_name'], "r");
				$data = fread($fp, $v['size']);
				fclose($fp);
			}
			
			$database->setQuery( 'SELECT CONCAT(' . $database->quote(JPATH_SITE.$mtconf->get('relative_path_to_attachments')) . ',raw_filename) FROM #__mt_cfvalues_att WHERE link_id = ' . $database->quote($row->link_id) . ' AND cf_id = ' . $database->quote($cf_id) );
			$raw_filenames = array_merge($raw_filenames, $database->loadResultArray());

			$database->setQuery('DELETE FROM #__mt_cfvalues_att WHERE link_id = ' . $database->quote($row->link_id) . ' AND cf_id = ' . $database->quote($cf_id));
			$database->query();
		
			$database->setQuery('DELETE FROM #__mt_cfvalues WHERE cf_id = ' . $database->quote($cf_id) . ' AND link_id = ' . $database->quote($row->link_id) . ' AND attachment > 0' );
			$database->query();

			$database->setQuery('INSERT INTO #__mt_cfvalues_att (link_id, cf_id, raw_filename, filename, filesize, extension) '
				.	'VALUES('
				.	$database->quote($row->link_id) . ', '
				.	$database->quote($cf_id) . ', '
				.	$database->quote($file['name']) . ', '
				.	$database->quote($file['name']) . ', '
				.	$database->quote($file['size']) . ', '
				.	$database->quote($file['type']) . ')'
				); 
				
			if($database->query() !== false) {
				$att_id = $database->insertid();
				
				$file_extension = strrchr($file['name'],'.');
				if( $file_extension === false ) {
					$file_extension = '';
				}
				
				if(JFile::write( JPATH_SITE.$mtconf->get('relative_path_to_attachments').$att_id.$file_extension, $data ))
				{
					$database->setQuery( 'UPDATE #__mt_cfvalues_att SET raw_filename = ' . $database->quote($att_id . $file_extension) . ' WHERE att_id = ' . $database->quote($att_id) . ' LIMIT 1' );
					$database->query();

					$sql = 'INSERT INTO #__mt_cfvalues (`cf_id`, `link_id`, `value`, `attachment`) '
						. 'VALUES (' . $database->quote($cf_id) . ', ' . $database->quote($row->link_id) . ', ' . $database->quote($file['name']) . ',1)';
					$database->setQuery($sql);
					$database->query();
				} else {
					// Move failed, remove record from previously INSERTed row in #__mt_cfvalues_att
					$database->setQuery('DELETE FROM #__mt_cfvalues_att WHERE att_id = ' . $database->quote($att_id) . ' LIMIT 1');
					$database->query();
				}
			}
		} 
	}

	if( !empty($raw_filenames) )
	{
		JFile::delete($raw_filenames);
	}
	
	# Remove all images except those that are kept
	$msg = '';
	if(is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_listing_small_image')) && is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_listing_medium_image')) && is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_listing_original_image'))) {
		
		if( isset($post['keep_img']) )
		{
			$keep_img_ids = $post['keep_img'];
			JArrayHelper::toInteger($keep_img_ids, array());
		}
		
		$image_filenames = array();
		if(isset($keep_img_ids) && count($keep_img_ids)>0) {
			$database->setQuery('SELECT filename FROM #__mt_images WHERE link_id = ' . $database->quote($row->link_id) . ' AND img_id NOT IN (\'' . implode('\',\'',$keep_img_ids) . '\')' );
			$image_filenames = $database->loadResultArray();
			$database->setQuery('DELETE FROM #__mt_images WHERE link_id = ' . $database->quote($row->link_id) . ' AND img_id NOT IN (\'' . implode('\',\'',$keep_img_ids) . '\')' );
			$database->query();
		} else {
			$database->setQuery('SELECT filename FROM #__mt_images WHERE link_id = ' . $database->quote($row->link_id) );
			$image_filenames = $database->loadResultArray();
			$database->setQuery('DELETE FROM #__mt_images WHERE link_id = ' . $database->quote($row->link_id) );
			$database->query();
		}
		if( count($image_filenames) ) {
			foreach($image_filenames AS $image_filename) {
				unlink($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_small_image') . $image_filename);
				unlink($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_medium_image') . $image_filename);
				unlink($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_original_image') . $image_filename);
			}
		}
	}

	$images = new mtImages( $database );
	if( isset($files['image']) ) {
		if( !is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_listing_small_image')) || !is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_listing_medium_image')) ||  !is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_listing_original_image')) ) {
			$msg = JText::_( 'Image directories not writable' );
		} else {
			for($i=0;$i<count($files['image']['name']);$i++) {
				if ( !empty($files['image']['name'][$i]) && $files['image']['error'][$i] == 0 &&  $files['image']['size'][$i] > 0 ) {
					$file_extension = pathinfo($files['image']['name'][$i]);
					$file_extension = strtolower($file_extension['extension']);

					$mtImage = new mtImage();
					$mtImage->setMethod( $mtconf->get('resize_method') );
					$mtImage->setQuality( $mtconf->get('resize_quality') );
					$mtImage->setSize( $mtconf->get('resize_listing_size') );
					$mtImage->setTmpFile( $files['image']['tmp_name'][$i] );
					$mtImage->setType( $files['image']['type'][$i] );
					$mtImage->setName( $files['image']['name'][$i] );
					$mtImage->setSquare( $mtconf->get('squared_thumbnail') );
					$mtImage->resize();
					$mtImage->setDirectory( $mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_small_image') );
					$mtImage->saveToDirectory();
				
					$mtImage->setSize( $mtconf->get('resize_medium_listing_size') );
					$mtImage->setSquare(false);
					$mtImage->resize();
					$mtImage->setDirectory( $mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_medium_image') );
					$mtImage->saveToDirectory();
					move_uploaded_file($files['image']['tmp_name'][$i], JPath::clean($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_original_image') . $files['image']['name'][$i]) );

					$database->setQuery( "INSERT INTO #__mt_images (link_id, filename, ordering) "
						.	'VALUES(' . $database->quote($row->link_id) . ', ' . $database->quote($files['image']['name'][$i]) . ', 9999)');
					$database->query();
					$img_id = intval($database->insertid());

					$old_small_image_path		= JPath::clean($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_small_image') . $files['image']['name'][$i]);
					$old_medium_image_path		= JPath::clean($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_medium_image') . $files['image']['name'][$i]);
					$old_original_image_path	= JPath::clean($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_original_image') . $files['image']['name'][$i]);
					
					rename( $old_small_image_path, $mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_small_image') . $img_id . '.' . $file_extension);
					rename( $old_medium_image_path, $mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_medium_image') . $img_id . '.' . $file_extension);
					rename( $old_original_image_path, $mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_listing_original_image') . $img_id . '.' . $file_extension);

					$database->setQuery('UPDATE #__mt_images SET filename = ' . $database->quote($img_id . '.' . $file_extension) . ' WHERE img_id = ' . $database->quote($img_id));
					$database->query();
				}
			}
		}
	}
	
	$img_sort_hash = $post['img_sort_hash'];

	if(!empty($img_sort_hash)) {
		$arr_img_sort_hashes = split("[&]*img\[\]=\d*", $img_sort_hash);
		$i=1;
		foreach($arr_img_sort_hashes AS $arr_img_sort_hash) {
			if(!empty($arr_img_sort_hash) && $arr_img_sort_hash > 0) {
				$database->setQuery( 'UPDATE #__mt_images SET ordering = ' . $database->quote($i) . ' WHERE img_id = ' . $database->quote(intval($arr_img_sort_hash)). ' LIMIT 1' );
				$database->query();
				$i++;
			}
		}
	}
	$images->reorder('link_id='.$row->link_id);
	
	# Update "Also appear in these categories" aka other categories
	$mtCL = new mtCL_main0( $database );
	$mtCL->load( $row->link_id );
	$mtCL->update( $other_cats );
	
	JPluginHelper::importPlugin('finder');
	$dispatcher->trigger('onSaveMTreeListing', array($row->link_id));
	
	$returntask	= $post['returntask'];
	
// /*
	if ( $returntask <> '' ) {
		$mainframe->redirect( "index.php?option=$option&task=$returntask", $msg );
	} else {

		$task = JFilterInput::clean($post['task'], 'cmd');
		
		if ( $task == "applylink" ) {
			$mainframe->redirect( "index.php?option=$option&task=editlink&link_id=$row->link_id", $msg );
		} else {
			$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$cat_id", $msg );
		}
	}
// */
}

function publishLinks( $link_id=null, $publish=1,  $option ) {
	global $mainframe;

	$database 	=& JFactory::getDBO();
	$my			=& JFactory::getUser();

	if (!is_array( $link_id ) || count( $link_id ) < 1) {
		$action = $publish ? strtolower(JText::_( 'Publish' )) : strtolower(JText::_( 'Unpublish' ));
		echo "<script> alert('".sprintf(JText::_( 'Select an item to' ), $action)."'); window.history.go(-1);</script>\n";
		exit;
	}

	$link_ids = implode( ',', $link_id );

	# Verify if these links is unpublished -> published OR published -> unpublished 
	foreach( $link_id AS $lid ) {
		$checklink = new mtLinks( $database );
		$checklink->load( $lid );

		if ( $checklink->link_published XOR $publish ) {
			$checklink->updateLinkCount( ( ($publish) ? 1 : -1 ) );
		}

	}

	# Publish/Unpublish Link
	$database->setQuery( 'UPDATE #__mt_links SET link_published = ' . $database->quote($publish)
		. "\nWHERE link_id IN ($link_ids)"
	);
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	} else {
		$dispatcher	=& JDispatcher::getInstance();
		JPluginHelper::importPlugin('finder');
		$dispatcher->trigger('onChangeMTreeListing', array($link_id,'state', (int)$publish));
	}

	$row = new mtLinks( $database );
	$row->load( $link_id[0] );

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=".$row->cat_id );
}

function removeLinks( $link_id, $option, $post=null ) {
	global $mainframe;

	$database 	=& JFactory::getDBO();

	$row = new mtLinks( $database );
	$row->load( $link_id[0] );
	
	if (!is_array( $link_id ) || count( $link_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to delete' )."'); window.history.go(-1);</script>\n";
		exit;
	}
	if (count( $link_id )) {
		$link_ids = implode( ',', $link_id );
		
		$total = count( $link_id );

		# Locate all CL mapping and decrease the categories' link count
		foreach( $link_id AS $id ) {
			$database->setQuery( 'SELECT cat_id FROM #__mt_cl WHERE main = 0 AND link_id = ' . $database->quote($id) );
			$link_cls = $database->loadResultArray();
			
			if( count($link_cls) > 0 ) {
				foreach( $link_cls AS $link_cl ) {
					$row->updateLinkCount( -1, $link_cl );
				}
			}
		}

		# Delete the main records
		foreach( $link_id AS $id ) {
			$database->setQuery( 'SELECT link_approved FROM #__mt_links WHERE link_id = ' . $database->quote($id) );
			$link_approved = $database->loadResult();
			if ( $link_approved <= 0 ) {
				$total--;
			}
			$row->delLink( $id );
		}
		# Update link count for all category
		if ( $total > 0 ) {
			$row->updateLinkCount( (-1 * $total) );
		}
	}
	
	if( is_null($post) ) {
		$returntask	= JRequest::getCmd('returntask', '', 'post');
	} else {
		$returntask	= $post['returntask'];
	}
	
	if ( $returntask <> '' ) {
		$mainframe->redirect( "index.php?option=$option&task=$returntask", sprintf(JText::_( 'Links have been deleted' ), count($link_id) ) );
	} else {
		$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=".$row->cat_id );
	}
}

function featuredLinks( $link_id, $featured=1, $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();

	$row = new mtLinks( $database );
	
	if (count( $link_id )) {
		foreach($link_id AS $lid) {
			$row->setFeaturedLink( $featured, $lid );
		}
	}
	$row->load( $lid );

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=".$row->cat_id );
}

function orderLinks( $link_id, $inc, $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();

	$row = new mtLinks( $database );
	$row->load( $link_id );
	$row->move( $inc, "cat_id = '$row->cat_id'" );
	
	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=".$row->cat_id );
}

function cancellink( $link_id, $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();
	
	# Check return task - used to return to listpending_links
	$returntask	= JRequest::getCmd('returntask', '', 'post');
	
	if ( $returntask <> '' ) {
		$mainframe->redirect( "index.php?option=$option&task=$returntask" );
	} else {
		$link = new mtLinks( $database );
		$link->load( $link_id );

		$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$link->cat_id" );
	}
}

function cancellinks_move( $link_id, $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();

	$link = new mtLinks( $database );
	$link->load( $link_id );

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$link->cat_id" );
}

function moveLinks( $link_id, $cat_parent, $option ) {

	$database 	=& JFactory::getDBO();

	if (!is_array( $link_id ) || count( $link_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to move' )."'); window.history.go(-1);</script>\n";
		exit;
	}	

	# Get Pathway
	$pathWay = new mtPathWay( $cat_parent );

	# Get all category under cat_parent
	$database->setQuery( 'SELECT cat_id AS value, cat_name AS text FROM #__mt_cats WHERE cat_parent = ' . $database->quote($cat_parent) . ' ORDER BY cat_name ASC');
	$rows = $database->loadObjectList();

	# Get Parent's parent
	if ( $cat_parent > 0 ) {
		$database->setQuery( 'SELECT cat_parent FROM #__mt_cats WHERE cat_id = ' . $database->quote($cat_parent) );
		$cat_back = JHTML::_('select.option', $database->loadResult(), '&lt;--Back' );
		array_unshift( $rows, $cat_back );
	}
	
	$cats = $rows;
	$catList = JHTML::_('select.genericlist', $cats, 'cat_parent', 'class="text_area" size="8" style="width:30%"', 'value', 'text', null, 'browsecat' );

	HTML_mtree::move_links( $link_id, $cat_parent, $catList, $pathWay, $option );

}

function moveLinks2( $link_id, $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();

	$new_cat_parent	= JRequest::getInt( 'new_cat_parent', '', 'post');
	
	$row = new mtLinks( $database );

	if ( count( $link_id ) > 0 ) {
		foreach( $link_id AS $id ) {
			if ( $row->load( $id ) == true ) {
				if ( !isset($old_cat_parent) ) {
					$old_cat_parent = $row->cat_id;
				}
			} else {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}

			# Assign new cat_parent
			if ( $new_cat_parent >= 0 ) {
				$row->cat_id = $new_cat_parent;
			}

			if (!$row->store()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
		} // End foreach
	} // End if
	
	# Update category, links count and update all ordering
	$result = $row->updateLinkCount( (count($link_id)*-1), $old_cat_parent );
	$result = $row->updateLinkCount( count($link_id), $new_cat_parent );

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$new_cat_parent" );
}

function copyLinks( $link_id, $cat_parent, $option ) {

	$database 	=& JFactory::getDBO();

	if (!is_array( $link_id ) || count( $link_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to copy' )."'); window.history.go(-1);</script>\n";
		exit;
	}	

	# Get Pathway
	$pathWay = new mtPathWay( $cat_parent );

	# Get all category under cat_parent
	$database->setQuery('SELECT cat_id AS value, cat_name AS text FROM #__mt_cats WHERE cat_parent = ' . $database->quote($cat_parent) . ' ORDER BY cat_name ASC');
	$rows = $database->loadObjectList();

	# Get Parent's parent
	if ( $cat_parent > 0 ) {
		$database->setQuery('SELECT cat_parent FROM #__mt_cats WHERE cat_id = ' . $database->quote($cat_parent));
		$cat_back = JHTML::_('select.option', $database->loadResult(), JText::_( 'Arrow back' ) );
		array_unshift( $rows, $cat_back );
	}
	
	$cats = $rows;

	# Main Category list
	$lists['cat_id'] = JHTML::_('select.genericlist', $cats, 'cat_parent', 'class="text_area" size="8" style="width:30%"', 'value', 'text', null, 'browsecat' );

	# Copy Reviews?
	$copy_reviews					= JRequest::getInt( 'copy_reviews', 0, 'post');
	$lists['copy_reviews']			= JHTML::_('select.booleanlist', "copy_reviews", 'class="inputbox"', $copy_reviews);

	# Copy Secondary Categories?
	$copy_secondary_cats 			= JRequest::getInt( 'copy_secondary_cats', 0, 'post');
	$lists['copy_secondary_cats'] 	= JHTML::_('select.booleanlist', "copy_secondary_cats", 'class="inputbox"', $copy_secondary_cats);
	
	# Reset Hits?
	$reset_hits 					= JRequest::getInt( 'reset_hits', 1, 'post');
	$lists['reset_hits'] 			= JHTML::_('select.booleanlist', "reset_hits", 'class="inputbox"', $reset_hits);

	# Reset Rating & Votes?
	$reset_rating 					= JRequest::getInt( 'reset_rating', 1, 'post');
	$lists['reset_rating'] 			= JHTML::_('select.booleanlist', "reset_rating", 'class="inputbox"', $reset_rating);

	HTML_mtree::copy_links( $link_id, $cat_parent, $lists, $pathWay, $option );

}

function copyLinks2( $link_id, $option ) {
	global $mainframe;
	
	$database 				=& JFactory::getDBO();
	
	$new_cat_parent			= JRequest::getInt( 'new_cat_parent', '', 'post');
	$copy_reviews 			= JRequest::getInt( 'copy_reviews', 0, 'post');
	$copy_secondary_cats 	= JRequest::getInt( 'copy_secondary_cats', 0, 'post');
	$reset_hits 			= JRequest::getInt( 'reset_hits', 1, 'post');
	$reset_rating 			= JRequest::getInt( 'reset_rating', 1, 'post');

	$row = new mtLinks( $database );

	if ( count( $link_id ) > 0 ) {
		foreach( $link_id AS $id ) {
			$row->copyLink( $id, $new_cat_parent, $reset_hits, $reset_rating, $copy_reviews, $copy_secondary_cats);
			$row->cat_id = $new_cat_parent;
			$row->updateLinkCount( 1 );
		}
	}

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$new_cat_parent" );
}

/****
* Category
*/
function listcats( $cat_id, $cat_parent, $option ) {
	global $mainframe, $mtconf;

	$database 	=& JFactory::getDBO();

	$limit 		= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mtconf->getjconf('list_limit') );
	$limitstart = $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 );

	if ( $cat_id == 0 && $cat_parent > 0 ) {
		$cat_id = $cat_parent;
	}

	# Creating db connection to #__mt_cats
	$mtCats = new mtCats( $database );
	$mtCats->load( $cat_id );

	# Page Navigation
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($mtCats->getNumOfLinks_NoRecursive( $cat_id ), $limitstart, $limit);
	
	# Main query - category
	$sql = 'SELECT cat.* FROM #__mt_cats AS cat '
		. 'WHERE cat_parent = ' . $database->quote($cat_id) . ' AND cat_approved = 1 ';
		
	if( $mtconf->get('first_cat_order1') != '' )
	{
		$sql .= ' ORDER BY ' . $mtconf->get('first_cat_order1') . ' ' . $mtconf->get('first_cat_order2');
		if( $mtconf->get('second_cat_order1') != '' )
		{
			$sql .= ', ' . $mtconf->get('second_cat_order1') . ' ' . $mtconf->get('second_cat_order2');
		}
	}

	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$cats = $database->loadObjectList();

	# Get Pathway
	$pathWay = new mtPathWay( $cat_id );

	# Get Links for this category
	$sql = "SELECT l.*, COUNT(r.rev_id) AS reviews, cl.main AS main FROM (#__mt_links AS l, #__mt_cl AS cl)"
		."\nLEFT JOIN #__mt_reviews AS r ON (r.link_id = l.link_id)"
		."\nWHERE cl.cat_id = " . $database->quote($cat_id) . " AND link_approved = '1' AND (l.link_id = cl.link_id)"
		."\nGROUP BY l.link_id";
		
	if( $mtconf->get('min_votes_to_show_rating') > 0 && $mtconf->get('first_listing_order1') == 'link_rating' ) {
		$sql .= "\nORDER BY link_votes >= " . $mtconf->get('min_votes_to_show_rating') . " DESC, " . $mtconf->get('first_listing_order1') . ' ' . $mtconf->get('first_listing_order2') . ', ' . $mtconf->get('second_listing_order1') . ' ' . $mtconf->get('second_listing_order2');
	} else {
		$sql .= "\nORDER BY " . $mtconf->get('first_listing_order1') . ' ' . $mtconf->get('first_listing_order2') . ', ' . $mtconf->get('second_listing_order1') . ' ' . $mtconf->get('second_listing_order2');
	}
	$sql .= "\nLIMIT $pageNav->limitstart,$pageNav->limit";
		/*
		if( $mtconf->get('min_votes_to_show_rating') > 0 && $mtconf->get('first_listing_order1') == 'link_rating' ) {
			$sql .= "\n ORDER BY link_votes >= " . $mtconf->get('min_votes_to_show_rating') . ' DESC, ' . $mtconf->get('first_listing_order1') . ' ' . $mtconf->get('first_listing_order2') . ', ' . $mtconf->get('second_listing_order1') . ' ' . $mtconf->get('second_listing_order2');
		} else {
			$sql .= "\n ORDER BY " . $mtconf->get('first_listing_order1') . ' ' . $mtconf->get('first_listing_order2') . ', ' . $mtconf->get('second_listing_order1') . ' ' . $mtconf->get('second_listing_order2');
		}
		*/
	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$links = $database->loadObjectList();
	
	# Get cat_ids for soft listing
	$softlinks = array();
	foreach( $links AS $link ) {
		if ( $link->main == 0 ) {
			$softlinks[] = $link->link_id;
		}
	}
	if ( !empty($softlinks) ) {
		$database->setQuery( "SELECT link_id, cat_id FROM #__mt_cl WHERE link_id IN (".implode(", ",$softlinks).") AND main = '1'" );
		$softlink_cat_ids = $database->loadObjectList( "link_id" );
	}

	HTML_mtree::listcats( $cats, $links, $softlink_cat_ids, $mtCats, $pageNav, $pathWay, $option );
}

function editcat( $cat_id, $cat_parent, $option ) {
	global $mtconf;

	$database 	=& JFactory::getDBO();

	$row = new mtCats( $database );
	$row->load( $cat_id );

	if ($row->cat_id == 0) {
		$row->cat_name = '';
		$row->cat_parent = $cat_parent;
		$row->cat_links = 0;
		$row->cat_cats = 0;
		$row->cat_featured = 0;
		$row->cat_published = 1;
		$row->cat_approved = 1;
		$row->cat_image = '';
		$row->cat_allow_submission = 1;
		$row->cat_image = '';
		$row->alias = '';
	} else {
		$cat_parent = $row->cat_parent;
	}

	$lists = array();

	# Template select list
	// Decide if parent has a custom template assigned to it. If there is, select this template
	// by default.
	if ( $cat_parent > 0 && $cat_id == 0 ) {
		$database->setQuery( 'SELECT cat_template FROM #__mt_cats WHERE cat_id = ' . $database->quote($cat_parent) . ' LIMIT 1' );
		$parent_template = $database->loadResult();
	}
	$templateDirs	= JFolder::folders($mtconf->getjconf('absolute_path') . '/components/com_mtree/templates');
	$templates[] = JHTML::_('select.option', '', ( (!empty($parent_template)) ? 'Default ('.$parent_template.')' : 'Default' ) );

	foreach($templateDirs as $templateDir) {
		if ( $templateDir <> "index.html") $templates[] = JHTML::_('select.option', $templateDir, $templateDir );
	}

	$lists['templates'] = JHTML::_('select.genericlist', $templates, 'cat_template', 'class="inputbox" size="1"',
	'value', 'text', $row->cat_template );
	
	# Get related categories
	$database->setQuery( 'SELECT rel_id FROM #__mt_relcats WHERE cat_id = ' . $database->quote($cat_id) );
	$related_cats = $database->loadResultArray();

	# Compile list of categories - Related Categories
	$categories = array();
	$browse_cat = $row->getParent($cat_parent);
	// if ( $browse_cat > 0 ) {
	if ( $cat_id > 0 ) {
		$categories[] = JHTML::_('select.option', $row->cat_parent, '&lt;--Back' );
	}
	$database->setQuery( 'SELECT cat_id AS value, cat_name AS text FROM #__mt_cats '
	. 'WHERE cat_parent=' . $database->quote($cat_id) . ' ORDER BY cat_name ASC' );
	$categories = array_merge( $categories, $database->loadObjectList() );

	# new_related_cat
	$lists['new_related_cat'] = JHTML::_('select.genericlist', $categories, 'new_related_cat', 'size="8" class="text_area" style="display:block;width:50%;margin-top:6px;"', 'value', 'text', ( ($row->cat_id == 0) ? $cat_parent : $row->cat_id ), 'browsecat' );

	# Yes/No select list for Approved Category
	$lists['cat_approved'] = JHTML::_('select.booleanlist', "cat_approved", 'class="inputbox"', (($row->cat_approved == 1) ? 1 : 0));

	# Yes/No select list for Featured Category
	$lists['cat_featured'] = JHTML::_('select.booleanlist', "cat_featured", 'class="inputbox"', $row->cat_featured);

	# Yes/No select list for "Published"
	$lists['cat_published'] = JHTML::_('select.booleanlist', "cat_published", 'class="inputbox"', $row->cat_published);

	# Yes/No select list for "Use Main Index"
	$lists['cat_usemainindex'] = JHTML::_('select.booleanlist', "cat_usemainindex", 'class="inputbox"', $row->cat_usemainindex);

	$lists['cat_allow_submission'] = JHTML::_('select.booleanlist', "cat_allow_submission", 'class="inputbox"', $row->cat_allow_submission);

	$lists['cat_show_listings'] = JHTML::_('select.booleanlist', "cat_show_listings", 'class="inputbox"', $row->cat_show_listings);

	# Obtenemos el listado de fields disponibles:
	$database->setQuery('SELECT * FROM gc_mt_customfields WHERE hidden!=1 AND published!=0');
	$lists['fields'] = $database->loadObjectList();
	
	# Obtenemos el listado de fields seleccionados relacionados con la categoría:
	$database->setQuery('SELECT * FROM gc_mtcats_customfields WHERE id_cat='.$cat_id.'');
	$lists['cats_x_fields'] = $database->loadObjectList();
		
	# Get Pathway
	$pathWay = new mtPathWay( $cat_parent );

	# Get Return task - Used by listpending_cats
	$returntask	= JRequest::getCmd('returntask', '', 'post');

	HTML_mtree::editcat( $row, $cat_parent, $related_cats, $browse_cat, $lists, $pathWay, $returntask, $option );
}

function savecat( $option ) {
	global $mtconf, $mainframe;

	$database 	=& JFactory::getDBO();
	$my			=& JFactory::getUser();
	$jdate		= JFactory::getDate();
	$now		= $jdate->toMySQL();

	$template_all_subcats	= JRequest::getInt( 'template_all_subcats', 0, 'post');
	$related_cats 			= explode(',', JRequest::getVar( 'other_cats', '', 'post'));
	$remove_image			= JRequest::getInt( 'remove_image', 0);
	$cat_image				= JRequest::getVar( 'cat_image', null, 'files');
	
	
	if ( $related_cats[0] == '' ) {
		$related_cats = array();
	}

	$post = JRequest::get( 'post' );
	$post['cat_desc'] = JRequest::getVar('cat_desc', '', 'POST', 'string', JREQUEST_ALLOWRAW);
	

	
	$row = new mtCats( $database );
	if (!$row->bind( $post )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}	
	
	//Borramos todas los fields relacionados con la categoría
	$sql = 'DELETE FROM #__mtcats_customfields WHERE id_cat = '.$row->cat_id.'';
	$database->setQuery($sql);
	$resutl = $database->loadResult();
	
	
	//Guardamos la nueva relación de fields con la categoría
	foreach($post['related_fields'] as $field_id)
	{
		$sql = 'INSERT INTO #__mtcats_customfields (id, id_cat, id_customfield) VALUES ("",'.$row->cat_id.','.$field_id.')';
		$database->setQuery($sql);
		$result = $database->loadResult();
	}
	

	if( empty($row->alias) )
	{
		$row->alias = JFilterOutput::stringURLSafe($row->cat_name);
	}
	
	# Get the name of the old photo
	if ( $row->cat_id > 0 ) {
		$sql = 'SELECT cat_image FROM #__mt_cats WHERE cat_id = ' . $database->quote($row->cat_id);
		$database->setQuery($sql);
		$old_image = $database->loadResult();
	} else {
		$old_image = '';
	}

	# Remove previous old image
	$msg = '';
	if ( $remove_image || ($old_image <> '' && array_key_exists('tmp_name',$cat_image) && !empty($cat_image['tmp_name'])) ) {
		$row->cat_image = '';

		if(file_exists($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_cat_original_image') . $old_image) && file_exists($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_cat_small_image') . $old_image) && is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_cat_small_image')) && is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_cat_original_image'))) {
			if(!unlink($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_cat_original_image') . $old_image) || !unlink($mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_cat_small_image') . $old_image)) {
				$msg .= JText::_( 'Error deleting old image' );
			}
		}
	}
	

	# Create Thumbnail
	if ( $cat_image['name'] <> '' ) {
		if(!is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_cat_small_image')) || !is_writable($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_cat_original_image'))) {
			$msg .= JText::_( 'Image directories not writable' );
		} else {
			$mtImage = new mtImage();
			$mtImage->setDirectory( $mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_cat_small_image') );
			$mtImage->setMethod( $mtconf->get('resize_method') );
			$mtImage->setQuality( $mtconf->get('resize_quality') );
			$mtImage->setSize( $mtconf->get('resize_cat_size') );
			$mtImage->setTmpFile( $cat_image['tmp_name'] );
			$mtImage->setType( $cat_image['type'] );
			if($row->cat_id > 0) {
				$mtImage->setName( $row->cat_id . '_' . $cat_image['name'] );
				$row->cat_image = $row->cat_id . '_' . $cat_image['name'];
			} else {
				$mtImage->setName( $cat_image['name'] );
				$row->cat_image = $cat_image['name'];
			}
			$mtImage->setSquare(false);
			$mtImage->resize();
			$mtImage->saveToDirectory();
			move_uploaded_file($cat_image['tmp_name'],$mtconf->getjconf('absolute_path') . $mtconf->get('relative_path_to_cat_original_image') . $row->cat_image);
		}
	}

	# Is this a new category?
	// Category created by conventional "Add Category" link
	if ($row->cat_id == 0) {
		$new_cat = true;
		$row->cat_created = $now;
	} else {

		$database->setQuery( 'SELECT cat_approved FROM #__mt_cats WHERE cat_id = ' . $database->quote($row->cat_id) );
		$cat_approved = $database->loadResult();
		// Approved new category submitted by users
		if ( $row->cat_approved == 1 && $cat_approved == 0 && $row->lft == 0 && $row->rgt == 0 ) {
			$new_cat = true;
			$row->cat_created = $now;
		} else {
			$new_cat = false;
		}
	}

	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	} else {
		
			# If this is a newlink, rename the photo to listingID_photoName.jpg
			if ( $new_cat && $cat_image['name'] <> '' ) {

				// Get last inserted listing ID
				$mysql_last_insert_id = $database->insertid();

				if ( $mysql_last_insert_id > 0 ) {

					if ( rename( $mtconf->getjconf('absolute_path').$mtconf->get('cat_image_dir').$cat_image['name'], $mtconf->getjconf('absolute_path').$mtconf->get('cat_image_dir').$mysql_last_insert_id."_".$cat_image['name'] ) ) {
						
						$database->setQuery( 'UPDATE #__mt_cats SET cat_image = ' . $database->quote($mysql_last_insert_id."_".$cat_image['name']) . ' WHERE cat_id = ' . $database->quote($mysql_last_insert_id) . ' LIMIT 1' );
						$database->query();

					}
				}
			}
		}
	
	# Change all subcats to use this template
	if ( $template_all_subcats == 1 ) {
		$row->updateSubCatsTemplate();
	}

	# Update the Category Counts for all cat_parent(s)
	if ($new_cat) {
		$row->updateLftRgt();
		$row->updateCatCount( 1 );
	}

	$row->reorder( "cat_parent='$row->cat_parent'" );

	# Update the related categories
	$mtRelCats = new mtRelCats( $database );
	$mtRelCats->setcatid( $row->cat_id );
	$mtRelCats->update( $related_cats );

	$returntask	= JRequest::getCmd('returntask', '', 'post');
	
	// /*
	if ( $returntask <> '' ) {
		$mainframe->redirect( "index.php?option=$option&task=$returntask", $msg );
	} else {
		$task = JRequest::getCmd( 'task', '', 'post');

		if ( $task == "applycat" ) {
			$mainframe->redirect( "index.php?option=$option&task=editcat&cat_id=$row->cat_id", $msg );
		} else {
			$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$row->cat_parent", $msg );
		}
	}
	// */
}

function fastadd_cat( $cat_parent, $option ) {
	global $mainframe;
	
	$database	=& JFactory::getDBO();
	$jdate		= JFactory::getDate();
	$now		= $jdate->toMySQL();
	
	$cat_names = preg_split('/\n/', JRequest::getVar( 'cat_names', '', 'post'));

	# Default Template
	// Decide if parent has a custom template assigned to it. If there is, use this template.
	if ( $cat_parent > 0 ) {
		$database->setQuery( 'SELECT cat_template FROM #__mt_cats WHERE cat_id = ' . $database->quote($cat_parent) . ' LIMIT 1' );
		$parent_template = $database->loadResult();
	}

	foreach( $cat_names AS $cat_name) {
		$cat_name = trim($cat_name);
		if ( !empty($cat_name) ) {
			
			$row = new mtCats( $database );
			$row->cat_name = stripslashes($cat_name);
			$row->alias = JFilterOutput::stringURLSafe($row->cat_name);
			$row->cat_created = $now;
			$row->cat_parent = $cat_parent;
			$row->cat_published = 1;
			$row->cat_approved = 1;
			if ( isset($parent_template) ) {
				$row->cat_template = $parent_template;
			}
			
			if (!$row->store()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}

			$row->updateLftRgt();
			# Update the Category Counts for all cat_parent(s)
			$row->updateCatCount( 1 );

			unset($row);
		}
	}
	
	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$cat_parent" );

}

function publishCats( $cat_id=null, $publish=1,  $option ) {
	global $mainframe;

	$database 	=& JFactory::getDBO();
	$my			=& JFactory::getUser();

	if (!is_array( $cat_id ) || count( $cat_id ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('".sprintf(JText::_( 'Select an item to' ), $action)."'); window.history.go(-1);</script>\n";
		exit;
	}

	$cat_ids = implode( ',', $cat_id );

	$database->setQuery( "UPDATE #__mt_cats SET cat_published='$publish'"
		. "\nWHERE cat_id IN ($cat_ids)"
	);
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	} else {
		$dispatcher	=& JDispatcher::getInstance();
		JPluginHelper::importPlugin('finder');
		$dispatcher->trigger('onChangeMTreeCategory', array($cat_id,'published', (int)$publish));
	}

	$row = new mtCats( $database );
	$row->load( $cat_id[0] );

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=".$row->getParent() );
}

function removecats( $cat_id, $option ) {

	$database 	=& JFactory::getDBO();

	if (!is_array( $cat_id ) || count( $cat_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to delete' )."'); window.history.go(-1);</script>\n";
		exit;
	}

	$database->setQuery( "SELECT * FROM #__mt_cats WHERE cat_id IN (".implode(", ",$cat_id).")" );
	$categories = $database->loadObjectList();

	$row = new mtCats( $database );
	$row->load( $cat_id[0] );

	HTML_mtree::removecats( $categories, $row->getParent(), $option );
	
}

function removecats2( $cat_id, $option ) {
	global $mainframe;

	$database 	=& JFactory::getDBO();

	$row = new mtCats( $database );
	$row->load( $cat_id[0] );
	
	$cat_parent = $row->getParent();

	if (!is_array( $cat_id ) || count( $cat_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to delete' )."'); window.history.go(-1);</script>\n";
		exit;
	}
	if (count( $cat_id )) {
		$totalcats = 0;
		$totallinks = 0;
		foreach($cat_id AS $cid) {
			$row->load( $cid );
			$totalcats += ($row->cat_cats +1);
			$totallinks += $row->cat_links;
			$row->deleteCats( $cid );
		}
		# Update Cat & Link count
		smartCountUpdate( $cat_parent, (($totallinks)*-1), (($totalcats)*-1) );
	}

	$returntask	= JRequest::getCmd('returntask', '', 'post');

	if ( $returntask <> '' ) {
		$mainframe->redirect( "index.php?option=$option&task=$returntask" );
	} else {
		$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=".$cat_parent );
	}

}

function featuredCats( $cat_id, $featured=1, $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();

	$row = new mtCats( $database );
	
	if (count( $cat_id )) {
		foreach($cat_id AS $cid) {
			$row->setFeaturedCat( $featured, $cid );
		}
	}

	$row->load( $cid );
	
	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=".$row->getParent() );
}

function orderCats( $cat_id, $inc, $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();

	$row = new mtCats( $database );
	$row->load( $cat_id );
	$row->move( $inc, "cat_parent = '$row->cat_parent'" );

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$row->cat_parent" );
}

function cancelcat( $cat_parent, $option ) {
	global $mainframe;
	
	# Check return task - used to return to listpending_cats
	$returntask	= JRequest::getCmd('returntask', '', 'post');
	
	if ( $returntask <> '' ) {
		$mainframe->redirect( "index.php?option=$option&task=$returntask" );
	} else {
		$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$cat_parent" );
	}

}

function cancelcats_move( $cat_id, $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();

	$cat = new mtCats( $database );
	$cat->load( $cat_id );

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$cat->cat_parent" );
}

function moveCats( $cat_id, $cat_parent, $option ) {

	$database 	=& JFactory::getDBO();

	if (!is_array( $cat_id ) || count( $cat_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to move' )."'); window.history.go(-1);</script>\n";
		exit;
	}	

	# Get Pathway
	$pathWay = new mtPathWay( $cat_parent );

	# Get all category under cat_parent except those which is moving
	$cat_ids = 	implode( ',', $cat_id );
	$database->setQuery('SELECT cat_id AS value, cat_name AS text FROM #__mt_cats WHERE cat_parent = ' . $database->quote($cat_parent) . ' AND cat_id NOT IN (' . $cat_ids . ') ORDER BY cat_name ASC');
	$rows = $database->loadObjectList();

	# Get Parent's parent
	if ( $cat_parent > 0 ) {
		$database->setQuery('SELECT cat_parent FROM #__mt_cats WHERE cat_id = ' . $database->quote($cat_parent));
		$cat_back = JHTML::_('select.option', $database->loadResult(), '&lt;--Back' );
		array_unshift( $rows, $cat_back );
	}
	
	$cats = $rows;
	$catList = JHTML::_('select.genericlist', $cats, 'cat_parent', 'class="text_area" size="8" style="width:30%"', 'value', 'text', null, 'browsecat' );

	HTML_mtree::move_cats( $cat_id, $cat_parent, $catList, $pathWay, $option );

}

function moveCats2( $cat_id, $option ) {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	
	$new_cat_parent_id = JRequest::getInt( 'new_cat_parent', '', 'post');
	
	if( $new_cat_parent_id == 0 ) {
		$database->setQuery( "SELECT cat_id, lft, rgt FROM #__mt_cats WHERE cat_parent = -1" );
		$new_cat_parent = $database->loadObject();
	} else {
		$database->setQuery( 'SELECT cat_id, lft, rgt FROM #__mt_cats WHERE cat_id = ' . $database->quote($new_cat_parent_id) );
		$new_cat_parent = $database->loadObject();
	}
	
	if( in_array($new_cat_parent_id,$cat_id) ) {
		$mainframe->redirect( "index.php?option=$option", JText::_('You can not move categories in to itself.') );
		return;
	}
	
	$row = new mtCats( $database );

	# Loop every moving categories 
	if ( count( $cat_id ) > 0 ) {

		$total_cats = 0;
		$total_links = 0;

		foreach( $cat_id AS $id ) {
			
			$row->load( $id );

			$total_cats++;
			$total_cats += $row->cat_cats;
			$total_links += $row->cat_links;
			
			# Assign new cat_parent
			$old_cat_parent = $row->cat_parent;
			if( $new_cat_parent_id == 0 ) {
				$row->cat_parent = 0;
			} else {
				$row->cat_parent = $new_cat_parent->cat_id;
			}

			if (!$row->store()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
			
			$inc = $new_cat_parent->rgt - $row->lft;
			$original_row_lft = $row->lft;
			$original_row_rgt = $row->rgt;
			$subcats = $row->getSubCats_Recursive( $id );

			# Categories are moved to the right
			if ( $row->rgt < $new_cat_parent->rgt ) {

				# (1) Update all category's lft and rgt to the right of this new node to accommodate new categories
				$database->setQuery("UPDATE #__mt_cats SET lft = lft+".(2*count($subcats))." WHERE lft >= $new_cat_parent->rgt");
				$database->query();

				$database->setQuery("UPDATE #__mt_cats SET rgt = rgt+".(2*count($subcats))." WHERE rgt >= $new_cat_parent->rgt");
				$database->query();

				# (2) Update lft & rgt values of moving categories
				$database->setQuery( "UPDATE #__mt_cats SET lft = lft + $inc, rgt = rgt + $inc WHERE lft >= $row->lft AND rgt <= $row->rgt" );
				$database->query();

				# (3) Finally, update all lft & rgt from the old node
				$database->setQuery("UPDATE #__mt_cats SET lft = lft-".(2*count($subcats))." WHERE lft >= $original_row_lft");
				$database->query();

				$database->setQuery("UPDATE #__mt_cats SET rgt = rgt-".(2*count($subcats))." WHERE rgt >= $original_row_rgt");
				$database->query();

			# Categories are moved to the left
			} else {

				# (1) Update all category's lft and rgt to the right of this new node to accommodate new categories
				$database->setQuery("UPDATE #__mt_cats SET lft = lft+".(2*count($subcats))." WHERE lft >= $new_cat_parent->rgt");
				$database->query();

				$database->setQuery("UPDATE #__mt_cats SET rgt = rgt+".(2*count($subcats))." WHERE rgt >= $new_cat_parent->rgt");
				$database->query();

				# (2) Update lft & rgt values of moving categories
				$database->setQuery( "UPDATE #__mt_cats SET lft = lft +($inc - ".(2*count($subcats))."), rgt = rgt +($inc - ".(2*count($subcats)).") WHERE lft >= ($row->lft + ".(2*count($subcats)).") AND rgt <= ($row->rgt + ".(2*count($subcats)).")" );
				$database->query();

				# (3) Finally, update all lft & rgt from the old node
				$database->setQuery("UPDATE #__mt_cats SET lft = lft-".(2*count($subcats))." WHERE lft >= $original_row_lft + ".(2*count($subcats)));
				$database->query();

				$database->setQuery("UPDATE #__mt_cats SET rgt = rgt-".(2*count($subcats))." WHERE rgt >= $original_row_rgt + ".(2*count($subcats)) );
				$database->query();

			}

		} // End foreach

		smartCountUpdate_catMove( $old_cat_parent, $new_cat_parent->cat_id, $total_links, $total_cats );

	} // End if
	
	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$row->cat_parent" );
}

function copyCats( $cat_id, $cat_parent, $option ) {
	
	$database =& JFactory::getDBO();
	
	if (!is_array( $cat_id ) || count( $cat_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to copy' )."'); window.history.go(-1);</script>\n";
		exit;
	}	

	# Get Pathway
	$pathWay = new mtPathWay( $cat_parent );

	# Get all category under cat_parent except those which is moving
	$cat_ids = 	implode( ',', $cat_id );
	$database->setQuery('SELECT cat_id AS value, cat_name AS text FROM #__mt_cats WHERE cat_parent = ' . $database->quote($cat_parent) . ' AND cat_id NOT IN (' . $cat_ids . ') ORDER BY cat_name ASC');
	$rows = $database->loadObjectList();

	# Get Parent's parent
	if ( $cat_parent > 0 ) {
		$database->setQuery('SELECT cat_parent FROM #__mt_cats WHERE cat_id = ' . $database->quote($cat_parent));
		$cat_back = JHTML::_('select.option', $database->loadResult(), JText::_( 'Arrow back' ) );
		array_unshift( $rows, $cat_back );
	}
	
	$cats = $rows;

	# Copy Related Cats?
	$copy_relcats			= JRequest::getInt( 'copy_relcats', 0, 'post');
	$lists['copy_relcats'] 	= JHTML::_('select.booleanlist', "copy_relcats", 'class="inputbox"', $copy_relcats);

	# Copy subcats?
	$copy_subcats 			= JRequest::getInt( 'copy_subcats', 1, 'post');
	$lists['copy_subcats'] 	= JHTML::_('select.booleanlist', "copy_subcats", 'class="inputbox"', $copy_subcats);

	# Copy Listings?
	$copy_listings 			= JRequest::getInt( 'copy_listings', 1, 'post');
	$lists['copy_listings'] = JHTML::_('select.booleanlist', "copy_listings", 'class="inputbox"', $copy_listings);

	# Copy Reviews?
	$copy_reviews 			= JRequest::getInt( 'copy_reviews', 0, 'post');
	$lists['copy_reviews'] 	= JHTML::_('select.booleanlist', "copy_reviews", 'class="inputbox"', $copy_reviews);

	# Reset Hits?
	$reset_hits 			= JRequest::getInt( 'reset_hits', 1, 'post');
	$lists['reset_hits'] 	= JHTML::_('select.booleanlist', "reset_hits", 'class="inputbox"', $reset_hits);

	# Reset Rating & Votes?
	$reset_rating 			= JRequest::getInt( 'reset_rating', 1, 'post');
	$lists['reset_rating'] 	= JHTML::_('select.booleanlist', "reset_rating", 'class="inputbox"', $reset_rating);

	# Main Category list
	$lists['cat_id'] = JHTML::_('select.genericlist', $cats, 'cat_parent', 'class="text_area" size="8" style="width:30%"', 'value', 'text', null, 'browsecat' );
	
	HTML_mtree::copy_cats( $cat_id, $cat_parent, $lists, $pathWay, $option );
}

function copyCats2( $cat_id, $option ) {
	global $mainframe;
	
	$database =& JFactory::getDBO();

	$new_cat_parent_id 	= JRequest::getInt( 'new_cat_parent', '', 'post');
	$copy_subcats 		= JRequest::getInt( 'copy_subcats', 	1, 'post');
	$copy_relcats		= JRequest::getInt( 'copy_relcats', 	0, 'post');
	$copy_listings 		= JRequest::getInt( 'copy_listings', 	1, 'post');
	$copy_reviews 		= JRequest::getInt( 'copy_reviews', 	0, 'post');
	$reset_hits 		= JRequest::getInt( 'reset_hits', 		1, 'post');
	$reset_rating 		= JRequest::getInt( 'reset_rating', 	1, 'post');

	$total_cats = 0;
	$total_links = 0;

	$row = new mtCats( $database );

	if ( count( $cat_id ) > 0 ) {

		foreach( $cat_id AS $id ) {

			$database->setQuery( 'SELECT cat_id, lft, rgt FROM #__mt_cats WHERE cat_id = ' . $database->quote($new_cat_parent_id) );
			$new_cat_parent = $database->loadObject();

			$copied_cat_ids = $row->copyCategory( $id, $new_cat_parent->cat_id, $copy_subcats, $copy_relcats, $copy_listings, $copy_reviews, $reset_hits, $reset_rating, null );

			// Retrieve category's count
			$database->setQuery( 'SELECT cat_cats, cat_links FROM #__mt_cats WHERE cat_id = ' . $database->quote($id) . ' LIMIT 1' );
			$total = $database->loadObject();

			$total_cats++;
			$total_cats += $total->cat_cats;
			$total_links += $total->cat_links;

			//print_r( $copied_cat_ids );

			// Update all category's lft and rgt to the right of this new node to accommodate new categories
			$database->setQuery("UPDATE #__mt_cats SET lft = lft+".(2*count($copied_cat_ids))." WHERE lft >= $new_cat_parent->rgt AND cat_id NOT IN (".implode(",",$copied_cat_ids).")");
			$database->query();

			$database->setQuery("UPDATE #__mt_cats SET rgt = rgt+".(2*count($copied_cat_ids))." WHERE rgt >= $new_cat_parent->rgt AND cat_id NOT IN (".implode(",",$copied_cat_ids).")");
			$database->query();

		} // End foreach
	} // End if
	
	smartCountUpdate( $new_cat_parent_id, $total_links, $total_cats );

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$new_cat_parent->cat_id" );
}

/****
* Approval / Pending
*/
function listpending_links( $option ) {
	global $mainframe, $mtconf;

	$database =& JFactory::getDBO();

	# Get Pathway
	$pathWay = new mtPathWay();

	# Limits
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mtconf->getjconf('list_limit') );
	$limitstart = $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 );

	$database->setQuery('SELECT COUNT(*) FROM #__mt_links WHERE link_approved < 1');
	$total = $database->loadResult();

	# Page Navigation
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	# Get all pending links
	$sql = "SELECT * FROM #__mt_links"
		.	"\nWHERE link_approved < '1'"
		.	"\nORDER BY link_created ASC, link_modified DESC"
		.	"\nLIMIT $pageNav->limitstart,$pageNav->limit";
	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$links = $database->loadObjectList();

	HTML_mtree::listpending_links( $links, $pathWay, $pageNav, $option );
}

function approve_links( $link_id, $publish=0, $option ) {
	global $mainframe;

	$database 	=& JFactory::getDBO();

	if (!is_array( $link_id ) || count( $link_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to approve' )."'); window.history.go(-1);</script>\n";
		exit;
	}
	
	if (count( $link_id )) {
		foreach( $link_id AS $lid ) {

			$mtLinks = new mtLinks( $database );
			$mtLinks->load( $lid );
			$mtLinks->publishLink( $publish );
			
			// Only increase Link count if this is an approval to a new listing
			if ( $mtLinks->link_approved == 0 ) {
				$mtLinks->updateLinkCount( 1 );
			} elseif( $mtLinks->link_approved < 0 ) {
				// Check if there is any category change during modification
				$database->setQuery( "SELECT cat_id FROM #__mt_cl WHERE link_id = ABS(".$mtLinks->link_approved.") AND main = '1'" );
				$ori_cat_id = $database->loadResult();
				if ( $ori_cat_id <> $mtLinks->cat_id ) {
					$mtLinks->updateLinkCount( 1 );
					mtUpdateLinkCount( $ori_cat_id, -1 );
				}			
			}
			$mtLinks->approveLink();
			unset($mtLinks);
		}
	}

	$mainframe->redirect( "index.php?option=$option&task=listpending_links",sprintf(JText::_( 'Links have been aprroved' ),count( $link_id )) );	
}

function listpending_cats( $option ) {
	global $mainframe, $mtconf;

	$database 	=& JFactory::getDBO();

	# Get Pathway
	$pathWay = new mtPathWay();

	# Limits
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mtconf->getjconf('list_limit') );
	$limitstart = $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 );

	$database->setQuery("SELECT COUNT(*) FROM #__mt_cats WHERE cat_approved <> '1'");
	$total = $database->loadResult();

	# Page Navigation
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	# Get all pending cats
	/*
	$sql = "SELECT cat.*, cimg.filename AS cat_image FROM #__mt_cats AS cat"
		. "\nLEFT JOIN #__mt_cats_images AS cimg ON cimg.cat_id = cat.cat_id"
	
	*/
	$sql = "SELECT cat.* FROM #__mt_cats AS cat"
		. "\nWHERE cat.cat_approved <> '1'"
		. "\nORDER BY cat.cat_created DESC"
		. "\nLIMIT $pageNav->limitstart,$pageNav->limit";
	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$cats = $database->loadObjectList();

	HTML_mtree::listpending_cats( $cats, $pathWay, $pageNav, $option );
}

function approve_cats( $cat_id, $publish=0, $option ) {
	global $mainframe;

	$database 	=& JFactory::getDBO();

	$mtCats = new mtCats( $database );

	if (!is_array( $cat_id ) || count( $cat_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to approve' )."'); window.history.go(-1);</script>\n";
		exit;
	}

	if (count( $cat_id )) {
		foreach( $cat_id AS $cid ) {
			$mtCats->load( $cid );
			$mtCats->approveCat();
			$mtCats->publishCat( $publish );
			if( $mtCats->lft == 0 && $mtCats->rgt == 0 ) {
				$mtCats->updateLftRgt();
			}
			$mtCats->updateCatCount( 1 );
		}
	}

	$mainframe->redirect( "index.php?option=$option&task=listpending_cats",sprintf(JText::_( 'Cats have been aprroved' ),count( $cat_id )) );	
}

function listpending_reviews( $option ) {
	global $mainframe, $mtconf;

	$database 	=& JFactory::getDBO();

	# Get Pathway
	$pathWay = new mtPathWay();

	# Limits
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit.pending_reviews", 'limit', $mtconf->getjconf('list_limit') );
	$limitstart = $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart.pending_reviews", 'limitstart', 0 );

	$database->setQuery("SELECT COUNT(*) FROM #__mt_reviews WHERE rev_approved <> '1'");
	$total = $database->loadResult();

	# Page Navigation
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	# Get all pending reviews
	$sql = "SELECT r.*, u.username AS username, u.email AS email, l.link_name, log.value FROM #__mt_reviews AS r"
		.	"\nLEFT JOIN #__users AS u ON u.id = r.user_id"
		.	"\nLEFT JOIN #__mt_links AS l ON l.link_id = r.link_id"
		.	"\nLEFT JOIN #__mt_log AS log ON log.link_id = r.link_id AND log.user_id = r.user_id AND log.log_type = 'vote' AND log.rev_id = r.rev_id"
		.	"\nWHERE r.rev_approved <> '1'"
		.	"\nORDER BY r.rev_date DESC"
		.	"\nLIMIT $pageNav->limitstart,$pageNav->limit";
		;

	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$reviews = $database->loadObjectList();

	HTML_mtree::listpending_reviews( $reviews, $pathWay, $pageNav, $option );
}

function save_pending_reviews( $option ) {
	global $mtconf, $mainframe;
	
	$database =& JFactory::getDBO();
	$mtReviews = new mtReviews( $database );

	$reviews 		= JRequest::getVar( 'rev', 			'', 'post');
	$review_titles 	= JRequest::getVar( 'rev_title', 	'', 'post');
	$review_texts 	= JRequest::getVar( 'rev_text', 	'', 'post');
	$admin_notes 	= JRequest::getVar( 'admin_note', 	'', 'post');
	$email_message 	= JRequest::getVar( 'emailmsg', 	'', 'post');
	$send_email 	= JRequest::getVar( 'sendemail', 	'', 'post');

	foreach( $reviews AS $review_id => $action ) {
		
		$review_id = intval( $review_id );
		
		$database->setQuery( 'SELECT link_id, user_id FROM #__mt_reviews WHERE rev_id = ' . $database->quote($review_id) . ' LIMIT 1' );
		$rev = $database->loadObject();
		
		switch(intval($action)){
			// 1: Approve; 0: Ignore
			case '0';
			case '1';
				$database->setQuery( 'UPDATE #__mt_reviews SET rev_title = ' . $database->quote($review_titles[$review_id]) . ', rev_text = ' . $database->quote($review_texts[$review_id]) . ' WHERE rev_id = ' . $database->quote($review_id) );
				$database->query();

				if($action == 1) {
					$mtReviews->load( $review_id );
					$mtReviews->approveReview( 1 );
				} else if ($action == 0 ) {
					if(@isset($admin_notes) && @array_key_exists($review_id,$admin_notes)) {
						$database->setQuery( 'UPDATE #__mt_reviews SET admin_note = ' . $database->quote($admin_notes[$review_id]) . ' WHERE rev_id = ' . $database->quote($review_id) );
						$database->query();
					}
					if(@isset($send_email) && @array_key_exists($review_id,$send_email) && $send_email[$review_id] == 1) {
						$database->setQuery( 'UPDATE #__mt_reviews SET send_email = 1, email_message = ' . $database->quote($email_message[$review_id]) . ' WHERE rev_id = ' . $database->quote($review_id) . ' LIMIT 1' );
						$database->query();
					} else {
						$database->setQuery( 'UPDATE #__mt_reviews SET send_email = 0, email_message = \'\' WHERE rev_id = ' . $database->quote($review_id) . ' LIMIT 1' );
						$database->query();
					}
				}
				break;
			// -1: Reject; -2: Reject and remove vote
			case '-1':
			case '-2':
				if($action==-2){					
					$database->setQuery( 'SELECT * FROM #__mt_links WHERE link_id = ' . $database->quote($rev->link_id) . ' LIMIT 1' );
					$link = $database->loadObject();
					
					$database->setQuery( 'SELECT value FROM #__mt_log WHERE log_type = \'vote\' AND user_id = ' . $database->quote($rev->user_id) . ' AND link_id = ' . $database->quote($rev->link_id) . ' LIMIT 1' );
					$user_rating = $database->loadResult();
					
					if($link->link_votes == 1){
						$new_rating = 0;
					} elseif($link->link_rating > 0 && $link->link_votes > 0 && $user_rating > 0) {
						$new_rating = (($link->link_rating * $link->link_votes) - $user_rating) / ($link->link_votes -1);
					}
					$database->setQuery( 'UPDATE #__mt_links SET link_rating = ' . $database->quote($new_rating) . ', link_votes = ' . $database->quote($link->link_votes -1) . ' WHERE link_id = ' . $database->quote($link->link_id) );
					$database->query();
					unset($link);
					$database->setQuery( 'DELETE FROM #__mt_log WHERE log_type = \'vote\' AND rev_id = ' . $database->quote($review_id) . ' AND user_id = ' . $database->quote($rev->user_id) . ' LIMIT 1' );
					$database->query();
				}
				$database->setQuery( 'DELETE FROM #__mt_reviews WHERE rev_id = ' . $database->quote($review_id) . ' LIMIT 1' );
				$database->query();
				$database->setQuery( 'DELETE FROM #__mt_log WHERE log_type = \'review\' AND rev_id = ' . $database->quote($review_id) . ' AND user_id = ' . $database->quote($rev->user_id) . ' LIMIT 1' );
				$database->query();
				break;		
		}
		
		if($action <> 0 && !empty($email_message[$review_id])) {
			$subject = sprintf(JText::_( 'Rejected approved review subject' ),$review_titles[$review_id]);
			
			$database->setQuery( 'SELECT email FROM #__users AS u WHERE u.id = ' . $database->quote($rev->user_id) . ' LIMIT 1' );
			$to_email = $database->loadResult();
			
			$from_name = $mtconf->get('predefined_reply_from_name');
			if(empty($from_name)) {
				$from_name = $mtconf->getjconf('fromname');
			}
			$from_email = $mtconf->get('predefined_reply_from_email');
			if(empty($from_email)) {
				$from_email = $mtconf->getjconf('mailfrom');
			}
			$bcc = $mtconf->get('predefined_reply_bcc');
			if(empty($bcc)) {
				$bcc = NULL;
			}			
			JUTility::sendMail( $from_email, $from_name, $to_email, $subject, $email_message[$review_id], 0, NULL, $bcc );
		}
		
	}
	$mainframe->redirect( "index.php?option=$option&task=listpending_reviews" );

}

function listpending_reports( $option ) {
	global $mainframe, $mtconf;

	$database 	=& JFactory::getDBO();

	# Get Pathway
	$pathWay = new mtPathWay();

	# Limits
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit.pendingreports", 'limit', $mtconf->getjconf('list_limit') );
	$limitstart = $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart.pendingreports", 'limitstart', 0 );

	$database->setQuery("SELECT COUNT(*) FROM #__mt_reports WHERE rev_id = 0 && link_id > 0");
	$total = $database->loadResult();

	# Page Navigation
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);
	
	# Get all pending reports
	$sql = "SELECT r.*, u.username AS username, u.email AS email, l.link_name FROM #__mt_reports AS r"
		.	"\nLEFT JOIN #__users AS u ON u.id = r.user_id"
		.	"\nLEFT JOIN #__mt_links AS l ON l.link_id = r.link_id"
		.	"\nWHERE r.rev_id = 0 && r.link_id > 0"
		.	"\nORDER BY r.created DESC"
		.	"\nLIMIT $pageNav->limitstart,$pageNav->limit";

	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$reports = $database->loadObjectList();

	HTML_mtree::listpending_reports( $reports, $pathWay, $pageNav, $option );
}

function save_reports( $option ) {
	global $mainframe;
	
	$database =& JFactory::getDBO();

	$reports 		= JRequest::getVar( 'report', '', 'post');
	$admin_notes 	= JRequest::getVar( 'admin_note', '', 'post');

	foreach( $reports AS $report_id => $action ) {
		$report_id = intval($report_id);
		if($action == 1) {
			$database->setQuery( 'DELETE FROM #__mt_reports WHERE report_id = ' . $database->quote($report_id) );
			$database->query();
		} else {
			if( @isset($admin_notes) && @array_key_exists($report_id,$admin_notes) ) {
				$database->setQuery( 'UPDATE #__mt_reports SET admin_note = ' . $database->quote($admin_notes[$report_id]) . ' WHERE report_id = ' . $database->quote($report_id) );
				$database->query();
			}
		}
	}

	$mainframe->redirect( "index.php?option=$option&task=listpending_reports" );

}

function listpending_reviewsreports( $option ) {
	global $mainframe, $mtconf;
	
	$database =& JFactory::getDBO();
	
	# Get Pathway
	$pathWay = new mtPathWay();

	# Limits
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit.pending_reviews", 'limit', $mtconf->getjconf('list_limit') );
	$limitstart = $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart.pending_reviews", 'limitstart', 0 );

	$database->setQuery("SELECT COUNT(*) FROM #__mt_reports WHERE rev_id > 0 && link_id > 0");
	$total = $database->loadResult();

	# Page Navigation
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	# Get all pending reports
	$sql = "SELECT r.*, rev.rev_title, rev.rev_text, u2.username AS review_username, u2.id AS review_user_id, rev.rev_date, u.username AS username, u.email AS email, l.link_name FROM #__mt_reports AS r"
		.	"\nLEFT JOIN #__mt_reviews AS rev ON rev.rev_id = r.rev_id"
		.	"\nLEFT JOIN #__users AS u ON u.id = r.user_id"		// The person that made the report
		.	"\nLEFT JOIN #__users AS u2 ON u2.id = rev.user_id"	// The reviewer
		.	"\nLEFT JOIN #__mt_links AS l ON l.link_id = r.link_id"
		.	"\nWHERE r.rev_id > 0 && r.link_id > 0"
		.	"\nORDER BY r.created DESC";

	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$reports = $database->loadObjectList();

	HTML_mtree::listpending_reviewsreports( $reports, $pathWay, $pageNav, $option );
}

function save_reviewsreports( $option ) {
	global $mainframe;
	
	$database =& JFactory::getDBO();

	$reports 		= JRequest::getVar( 'report', '', 'post');
	$admin_notes 	= JRequest::getVar( 'admin_note', '', 'post');

	foreach( $reports AS $report_id => $action ) {
		$report_id = intval($report_id);
		if($action == 1) {
			$database->setQuery( 'DELETE FROM #__mt_reports WHERE report_id = ' . $database->quote($report_id) );
			$database->query();
		} else {
			if( @isset($admin_notes) && @array_key_exists($report_id,$admin_notes) ) {
				$database->setQuery( 'UPDATE #__mt_reports SET admin_note = ' . $database->quote($admin_notes[$report_id]) . ' WHERE report_id = ' . $database->quote($report_id) );
				$database->query();
			}
		}
	}

	$mainframe->redirect( "index.php?option=$option&task=listpending_reviewsreports" );

}

function listpending_reviewsreply( $option ) {
	global $mainframe;

	$database =& JFactory::getDBO();

	# Get Pathway
	$pathWay = new mtPathWay();

	# Get all pending owner's reply
	$sql = "SELECT r.*, u.username AS username, owner.username AS owner_username, owner.id AS owner_user_id, owner.email AS owner_email, u.email AS email, l.link_name FROM #__mt_reviews AS r"
		.	"\nLEFT JOIN #__users AS u ON u.id = r.user_id"
		.	"\nLEFT JOIN #__mt_links AS l ON l.link_id = r.link_id"
		.	"\nLEFT JOIN #__users AS owner ON owner.id = l.user_id"
		.	"\nWHERE r.ownersreply_approved = '0' AND r.ownersreply_text != ''"
		.	"\nORDER BY r.ownersreply_date DESC";

	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$reviewreplies = $database->loadObjectList();
	HTML_mtree::listpending_reviewsreply( $reviewreplies, $pathWay, $option );
}

function save_reviewsreply( $option ) {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	
	$ownersreplies 	= JRequest::getVar( 'or', 			'', 'post');
	$or_text 		= JRequest::getVar( 'or_text', 		'', 'post');
	$admin_notes 	= JRequest::getVar( 'admin_note', 	'', 'post');

	foreach( $ownersreplies AS $rev_id => $action ) {
		$rev_id = intval($rev_id);
		
		// 1: Approve; 0: Ignore; -1: Reject
		if ( $action == 1 || $action == 0 ) {

			$database->setQuery( 'UPDATE #__mt_reviews SET ownersreply_text = ' . $database->quote($or_text[$rev_id]) . ' WHERE rev_id = ' . $database->quote($rev_id) );
			$database->query();

			if($action == 1) {
				$database->setQuery( 'UPDATE #__mt_reviews SET ownersreply_approved = 1 WHERE rev_id = ' . $database->quote($rev_id) );
				$database->query();
			} else if ($action == 0 && @isset($admin_notes) && @array_key_exists($rev_id,$admin_notes) ) {
				$database->setQuery( 'UPDATE #__mt_reviews SET ownersreply_admin_note = ' . $database->quote($admin_notes[$rev_id]) . ' WHERE rev_id = ' . $database->quote($rev_id) );
				$database->query();
			}

		} else {
			$database->setQuery( 'UPDATE #__mt_reviews SET ownersreply_text = \'\', ownersreply_approved = \'0\', ownersreply_date = \'\', ownersreply_admin_note = \'\' WHERE rev_id = ' . $database->quote($rev_id) );
			$database->query();
		}

	}

	$mainframe->redirect( "index.php?option=$option&task=listpending_reviewsreply" );

}

function listpending_claims( $option ) {
	global $mainframe;

	$database 	=& JFactory::getDBO();

	# Get Pathway
	$pathWay = new mtPathWay();

	$database->setQuery("SELECT COUNT(*) FROM #__mt_claims");
	$total = $database->loadResult();

	# Get all pending claims
	$sql = "SELECT r.*, u.username AS username, u.name AS name, u.email AS email, l.link_name FROM #__mt_claims AS r"
		.	"\nLEFT JOIN #__users AS u ON u.id = r.user_id"
		.	"\nLEFT JOIN #__mt_links AS l ON l.link_id = r.link_id"
		.	"\nORDER BY r.created DESC";

	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$claims = $database->loadObjectList();

	HTML_mtree::listpending_claims( $claims, $pathWay, $option );
}

function save_claims( $option ) {
	global $mtconf, $mainframe;

	$database =& JFactory::getDBO();
	
	$claims 		= JRequest::getVar( 'claim', 		'', 'post');
	$admin_notes 	= JRequest::getVar( 'admin_note', 	'', 'post');

	foreach( $claims AS $claim_id => $user_id ) {
		$claim_id = intval($claim_id);
		$user_id = intval($user_id);
		
		if($user_id > 0) {
			
			$database->setQuery( 'SELECT c.link_id, l.link_name FROM (#__mt_claims AS c, #__mt_links AS l) WHERE claim_id = ' . $database->quote($claim_id) . ' AND c.link_id = l.link_id');
			$link = $database->loadObject();
			
			$database->setQuery( 'SELECT email FROM #__users WHERE id = ' . $database->quote($user_id) );
			$email = $database->loadResult();

			$database->setQuery( 'UPDATE #__mt_links SET user_id = ' . $database->quote($user_id) . ' WHERE link_id = ' . $database->quote($link->link_id) . ' LIMIT 1' );
			$database->query();

			$database->setQuery( 'DELETE FROM #__mt_claims WHERE claim_id = ' . $database->quote($claim_id) );
			$database->query();

			$subject = JText::_( 'Claim approved subject' );
			$body = sprintf(JText::_( 'Claim approved msg' ), $link->link_name, $mtconf->getjconf('live_site')."/index.php?option=com_mtree&task=viewlink&link_id=$link->link_id" );

			JUTility::sendMail( $mtconf->getjconf('mailfrom'), $mtconf->getjconf('fromname'), $email, $subject, $body );

		} else if ( $user_id == -1) {
			$database->setQuery( 'DELETE FROM #__mt_claims WHERE claim_id = ' . $database->quote($claim_id) );
			$database->query();
		} else if ( $user_id == 0 ) {
			if( @isset($admin_notes) && @array_key_exists($claim_id,$admin_notes) ) {
				$database->setQuery( 'UPDATE #__mt_claims SET admin_note = ' . $database->quote($admin_notes[$claim_id]) . ' WHERE claim_id = ' . $database->quote($claim_id) );
				$database->query();
			}
		}

	}

	$mainframe->redirect( "index.php?option=$option&task=listpending_claims" );

}

/****
* Reviews
*/
function list_reviews( $link_id, $option ) {
	global $mainframe, $mtconf;

	$database 	=& JFactory::getDBO();

	# Get Link's info
	$link = new mtLinks( $database );
	$link->load( $link_id );

	# Get Pathway
	$pathWay = new mtPathWay( $link->cat_id );

	# Limits
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mtconf->getjconf('list_limit') );
	$limitstart = $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 );

	$database->setQuery('SELECT COUNT(*) FROM #__mt_reviews WHERE rev_approved=1 && link_id = ' . $database->quote($link_id) );
	$total = $database->loadResult();

	# Page Navigation
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	# Get All the reviews
	$sql = "SELECT *, u.name AS username FROM #__mt_reviews AS r"
		. "\nLEFT JOIN #__users AS u ON u.id = r.user_id"
		. "\nWHERE r.rev_approved=1 && r.link_id = '".$link_id."'"
		. "\nLIMIT $pageNav->limitstart,$pageNav->limit";
	$database->setQuery($sql);
	if(!$result = $database->query()) {
		echo $database->stderr();
		return false;
	}
	$reviews = $database->loadObjectList();

	HTML_mtree::list_reviews( $reviews, $link, $pathWay, $pageNav, $option );

}

function editreview( $rev_id, $link_id, $option ) {
	$database 	=& JFactory::getDBO();
	$my			=& JFactory::getUser();
	$jdate		= JFactory::getDate();

	$row = new mtReviews( $database );
	$row->load( $rev_id );

	if ($row->rev_id == 0) {
		$row->link_id = $link_id;
		$row->owner= $my->username;
		$row->rev_approved=1;
		$row->rev_date = $jdate->toMySQL();
		$row->not_registered = 0;
	} else {
		if ($row->user_id > 0) {
			$database->setQuery('SELECT username FROM #__users WHERE id =' . $database->quote($row->user_id) );
			$row->owner = $database->loadResult();
			$row->not_registered = 0;
		} else {
			$row->not_registered = 1;
		}
	}

	# Yes/No select list
	$lists['rev_approved'] = JHTML::_('select.booleanlist', "rev_approved", 'class="inputbox"', (($row->rev_approved == 1) ? 1 : 0));
	$lists['ownersreply_approved'] = JHTML::_('select.booleanlist', "ownersreply_approved", 'class="inputbox"', (($row->ownersreply_approved == 1) ? 1 : 0));

	# Lookup Cat ID
	$link = new mtLinks( $database );
	$link->load( $row->link_id );

	# Get Pathway
	$pathWay = new mtPathWay( $link->cat_id );

	# Get Return task - Used by listpending_links
	$returntask	= JRequest::getCmd('returntask', '', 'post');

	HTML_mtree::editreview( $row, $pathWay, $returntask, $lists, $option );
}

function savereview( $option ) {
	global $mainframe;

	$database 	=& JFactory::getDBO();
	$my			=& JFactory::getUser();
	$jdate		= JFactory::getDate();
	
	$post = JRequest::get( 'post' );
	$row = new mtReviews( $database );
	if (!$row->bind( $post )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$owner = JRequest::getVar( 'owner', '', 'post');
	$not_registered	= JRequest::getInt( 'not_registered', 0);
	
	# Is this a new review?
	if ($row->rev_id == 0) {
		$row->rev_date = $jdate->toMySQL();	
	}

	# Lookup owner's userid. Return error if does not exists
	if ($owner == '') {
		// If owner field is left blank, assign the link to the current user
		$row->user_id = $my->id;
	} else {

		if ( $not_registered == 0 ) {
		
			$database->setQuery('SELECT id FROM #__users WHERE username = ' . $database->quote($owner) );
			$owner_id = $database->loadResult();
			if ($owner_id > 0) {
				$row->user_id = $owner_id;
			} else {
				echo "<script> alert('".JText::_( 'Invalid owner select again' )."'); window.history.go(-1); </script>\n";
				exit();
			}

		} else {
			$row->user_id = 0;
			$row->guest_name = $owner;
		}
	}

	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	# Check return task - used to return to listpending_links
	$returntask	= JRequest::getCmd('returntask', '', 'post');
	
	if ( $returntask <> '' ) {
		$mainframe->redirect( "index.php?option=$option&task=$returntask" );
	} else {
		$mainframe->redirect( "index.php?option=$option&task=reviews_list&link_id=$row->link_id" );
	}

}

function removeReviews( $rev_id, $option ) {
	global $mainframe;

	$database 	=& JFactory::getDBO();

	$row = new mtReviews( $database );
	$row->load( $rev_id[0] );

	if (!is_array( $rev_id ) || count( $rev_id ) < 1) {
		echo "<script> alert('".JText::_( 'Select an item to delete' )."'); window.history.go(-1);</script>\n";
		exit;
	}
	if (count( $rev_id )) {
		$rev_ids = implode( ',', $rev_id );
		
		# Remove links
		$database->setQuery( "DELETE FROM #__mt_reviews WHERE rev_id IN ($rev_ids) LIMIT ".count( $rev_id ) );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}

		# Remove logs
		$database->setQuery( "DELETE FROM #__mt_log WHERE log_type = 'review' AND rev_id IN ($rev_ids) LIMIT ".count( $rev_id ) );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}

	$mainframe->redirect( "index.php?option=$option&task=reviews_list&link_id=".$row->link_id );
}

function cancelreview( $link_id, $option ) {
	global $mainframe;
	
	# Check return task - used to return to listpending_links
	$returntask	= JRequest::getCmd('returntask', '', 'post');
	
	if ( $returntask <> '' ) {
		$mainframe->redirect( "index.php?option=$option&task=$returntask" );
	} else {
		$mainframe->redirect( "index.php?option=$option&task=reviews_list&link_id=$link_id" );
	}
}

function backreview( $link_id, $option ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();

	$mtLinks = new mtLinks( $database );
	$mtLinks->load( $link_id );

	$mainframe->redirect( "index.php?option=$option&task=listcats&cat_id=$mtLinks->cat_id" );
}

/***
* Search
*/
function search( $option ) {
	global $mainframe, $mtconf;

	$database =& JFactory::getDBO();

	$search_text 	= JRequest::getVar( 'search_text', '', 'post');
	$search_where	= JRequest::getInt( 'search_where', 0, 'post'); // 1: Listing, 2: Category

	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mtconf->getjconf('list_limit') );
	$limitstart = $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 );
	# Detect search command
	
	# Quick Go
	$id_found = 0;
	if ( substr($search_text, 0, 3) == "id:" ) {
		$temp = explode(":", $search_text);
		if ( is_numeric($temp[1]) ) {
			$id_found = $temp[1];
		}
	}

	# Search query
	if ( $search_where == 1 ) {
		
		if ($id_found) {
			$link = new mtLinks( $database );
			$link->load( $id_found );
			
			if( !empty($link->link_name) ) {
				$mainframe->redirect( "index.php?option=com_mtree&task=editlink&link_id=".$id_found );
			} else {
				$mainframe->redirect( "index.php?option=com_mtree",JText::_( 'Your search does not return any result' ) );
			}

		} else {
			// Total Results
			$database->setQuery( 'SELECT COUNT(*) FROM #__mt_links '
				.	"\nWHERE link_name LIKE '%" . $database->getEscaped( $search_text, true ) . "%'"
				);
			$total = $database->loadResult();

			// Page Navigation
			jimport('joomla.html.pagination');
			$pageNav = new JPagination($total, $limitstart, $limit);

			// Links
			$database->setQuery( "SELECT l.*, COUNT(r.rev_id) AS reviews FROM #__mt_links AS l"
				.	"\nLEFT JOIN #__mt_reviews AS r ON r.link_id = l.link_id"
				.	"\nWHERE l.link_name LIKE '%" . $database->getEscaped( $search_text, true ) . "%'"
				.	"\nGROUP BY l.link_id"
				.	"\nORDER BY l.link_name ASC"
				.	"\nLIMIT " . $pageNav->limitstart . ', ' . $pageNav->limit
				);

		}
		
	} else {

		if ($id_found) {
			$cat = new mtCats( $database );
			$cat->load( $id_found );
			
			if( !empty($cat->cat_name) ) {
				$mainframe->redirect( "index.php?option=com_mtree&task=editcat&cat_id=".$id_found );
			} else {
				$mainframe->redirect( "index.php?option=com_mtree",JText::_( 'Your search does not return any result' ) );
			}

		} else {

			// Total Results
			$database->setQuery( "SELECT COUNT(*) FROM #__mt_cats WHERE cat_name LIKE '%" . $database->getEscaped( $search_text, true ) ."%'" );
			$total = $database->loadResult();

			// Page Navigation
			jimport('joomla.html.pagination');
			$pageNav = new JPagination($total, $limitstart, $limit);

			// Categories
			$database->setQuery( "SELECT * FROM #__mt_cats WHERE cat_name LIKE '%" . $database->getEscaped( $search_text, true ) . "%' ORDER BY cat_name ASC LIMIT $pageNav->limitstart, $pageNav->limit" );
		
		}

	}

	$results = $database->loadObjectList();

	# Get Pathway
	$pathWay = new mtPathWay();

	# Results Output
	if ( $search_where == 1 ) {
		// Links
		HTML_mtree::searchresults_links( $results, $pageNav, $pathWay, $search_where, $search_text, $option );
	} else {
		// Categories
		HTML_mtree::searchresults_categories( $results, $pageNav, $pathWay, $search_where, $search_text, $option );
	}

}

function advsearch( $option ) {
	global $mtconf;

	$database 	=& JFactory::getDBO();

	# Template select list
	$templateDirs	= JFolder::folders($mtconf->getjconf('absolute_path') . '/components/com_mtree/templates');
	$templates[] = JHTML::_('select.option', '', JText::_( 'Default' ) );

	foreach($templateDirs as $templateDir) {
		$templates[] = JHTML::_('select.option', $templateDir, $templateDir );
	}

	$lists['templates'] = JHTML::_('select.genericlist', $templates, 'link_template', 'class="inputbox" size="1"',
	'value', 'text', '' );
	
	# Publishing
	$publishing[] = JHTML::_('select.option', 1, JText::_( 'All' ) );
	$publishing[] = JHTML::_('select.option', 2, JText::_( 'Published' ) );
	$publishing[] = JHTML::_('select.option', 3, JText::_( 'Unpublished' ) );
	$publishing[] = JHTML::_('select.option', 4, JText::_( 'Pending' ) );
	$publishing[] = JHTML::_('select.option', 5, JText::_( 'Expired' ) );
	$publishing[] = JHTML::_('select.option', 6, JText::_( 'Pending approval' ) );
	$lists['publishing'] = JHTML::_('select.genericlist', $publishing, 'publishing', 'class="inputbox" size="1"',	'value', 'text', '' );

	// Comparison option
	$comparison[] = JHTML::_('select.option', 1, JText::_( 'Exactly' ) );
	$comparison[] = JHTML::_('select.option', 2, JText::_( 'More than' ) );
	$comparison[] = JHTML::_('select.option', 3, JText::_( 'Less than' ) );

	# Load all CORE and custom fields
	$database->setQuery( "SELECT cf.*, '0' AS link_id, '' AS value, '0' AS attachment, ft.ft_class FROM #__mt_customfields AS cf "
		.	"\nLEFT JOIN #__mt_fieldtypes AS ft ON ft.field_type=cf.field_type"
		.	"\nWHERE cf.published='1' ORDER BY ordering ASC" );
	$fields = new mFields($database->loadObjectList());

	# Search condition
	$searchConditions[] = JHTML::_('select.option', 1, strtolower(JText::_( 'Any' )) );
	$searchConditions[] = JHTML::_('select.option', 2, strtolower(JText::_( 'All' )) );
	$lists['searchcondition'] = JHTML::_('select.genericlist', $searchConditions, 'searchcondition', 'class="inputbox" size="1"',
	'value', 'text', 1 );

	# Price
	$lists['price'] = JHTML::_('select.genericlist', $comparison, 'price_2', 'class="inputbox" size="1"',
	'value', 'text', 1 );

	# Rating
	$lists['rating'] = JHTML::_('select.genericlist', $comparison, 'rating_2', 'class="inputbox" size="1"',
	'value', 'text', 1 );

	# Votes
	$lists['votes'] = JHTML::_('select.genericlist', $comparison, 'votes_2', 'class="inputbox" size="1"',
	'value', 'text', 1 );

	# Hits
	$lists['hits'] = JHTML::_('select.genericlist', $comparison, 'hits_2', 'class="inputbox" size="1"',
	'value', 'text', 1 );

	# Reviews
	$lists['reviews'] = JHTML::_('select.genericlist', $comparison, 'reviews_2', 'class="inputbox" size="1"',
	'value', 'text', 1 );

	HTML_mtree::advsearch( $fields, $lists, $option );
}

function advsearch2( $option ) {
	global $mtconf;

	$database =& JFactory::getDBO();
	
	$post 			= JRequest::get( 'post' );
	$search_where 	= JRequest::getVar( 'search_where', '', 'post'); // 1: Listing, 2: Category
	$limit			= JRequest::getInt( 'limit', 15);
	$limitstart		= JRequest::getInt( 'limitstart', 0);
	$owner 			= JRequest::getVar( 'owner', '', 'post');

	$searchParams = array();
	
	# Load all published CORE & custom fields
	$database->setQuery( "SELECT cf.*, '0' AS link_id, '' AS value, '0' AS attachment, ft.ft_class FROM #__mt_customfields AS cf "
		.	"\nLEFT JOIN #__mt_fieldtypes AS ft ON ft.field_type=cf.field_type"
		.	"\nWHERE cf.published='1' ORDER BY ordering ASC" );
	$fields = new mFields($database->loadObjectList());

	$searchParams = $fields->loadSearchParams($post);

	foreach( array('publishing','link_template','link_rating','rating_2','link_votes','votes_2','link_hits','hits_2','reviews','reviews_2','internal_notes','metakey','metadesc', 'price_2', 'price') AS $otherField ) {
		$searchParams[$otherField] = JRequest::getVar( $otherField, '', 'post');
	}

	# Search query
	if ( $search_where == 1 ) {
		
		$where = array();
		$having = '';
		$advsearch = new mAdvancedSearch( $database );

		if( JRequest::getInt( 'searchcondition', 1, 'post') == '2' ) {
			$advsearch->useAndOperator();
		} else {
			$advsearch->useOrOperator();
		}

		$fields->resetPointer();
		while( $fields->hasNext() ) {
			$field = $fields->getField();
			$searchFields = $field->getSearchFields();
			
			if( array_key_exists(0,$searchFields) && isset($searchParams[$searchFields[0]]) && !empty($searchParams[$searchFields[0]]) ) {
				foreach( $searchFields AS $searchField ) {
					if( isset($searchParams[$searchField]) ) {
						$searchFieldValues[] = $searchParams[$searchField];
					}
				}
				if( count($searchFieldValues) > 0 && !empty($searchFieldValues[0]) ) {
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

		if(!empty($searchParams['metadesc'])) {
			$advsearch->addRawCondition( 'metadesc LIKE \'%' . $database->getEscaped($searchParams['metadesc'], true) . '%\'');
		}

		if(!empty($searchParams['metakey'])) {
			$advsearch->addRawCondition( 'metakey LIKE \'%' . $database->getEscaped($searchParams['metakey'], true) . '%\'');
		}

		if(!empty($searchParams['internal_notes'])) {
			$advsearch->addRawCondition( 'internal_notes LIKE \'%' . $database->getEscaped($searchParams['internal_notes']) . '%\'');
		}

		if ( !empty($searchParams['link_template']) ) {
			$advsearch->addRawCondition( 'link_template = ' . $database->quote($searchParams['link_template']) );
		}

		if ( is_numeric($searchParams['link_rating']) && $searchParams['link_rating'] >= 0 && $searchParams['link_rating'] <= 5 ) {
			switch($searchParams['rating_2']) {
				case 1:
					$advsearch->addRawCondition( 'link_rating = ' . $database->quote($searchParams['link_rating']) );
					break;
				case 2:
					$advsearch->addRawCondition( 'link_rating > ' . $database->quote($searchParams['link_rating']) );
					break;
				case 3:
					$advsearch->addRawCondition( 'link_rating < ' . $database->quote($searchParams['link_rating']) );
					break;
			}
		}
		
		// votes
		if ( is_numeric($searchParams['link_votes']) && $searchParams['link_votes'] >= 0 ) {
			switch($searchParams['votes_2']) {
				case 1:
					$advsearch->addRawCondition( 'link_votes = ' . $database->quote($searchParams['link_votes']) );
					break;
				case 2:
					$advsearch->addRawCondition( 'link_votes > ' . $database->quote($searchParams['link_votes']) );
					break;
				case 3:
					$advsearch->addRawCondition( 'link_votes < ' . $database->quote($searchParams['link_votes']) );
					break;
			}
		}
		
		// hits
		if ( is_numeric($searchParams['link_hits']) && $searchParams['link_hits'] >= 0 ) {
			switch($searchParams['hits_2']) {
				case 1:
					$advsearch->addRawCondition( 'link_hits = ' . $database->quote($searchParams['link_hits']) );
					break;
				case 2:
					$advsearch->addRawCondition( 'link_hits > ' . $database->quote($searchParams['link_hits']) );
					break;
				case 3:
					$advsearch->addRawCondition( 'link_hits < ' . $database->quote($searchParams['link_hits']) );
					break;
			}
		}

		// price
		if ( is_numeric($searchParams['price']) && $searchParams['price'] >= 0 ) {
			switch($searchParams['price_2']) {
				case 1:
					$advsearch->addRawCondition( 'price = ' . $database->quote($searchParams['price']) );
					break;
				case 2:
					$advsearch->addRawCondition( 'price > ' . $database->quote($searchParams['price']) );
					break;
				case 3:
					$advsearch->addRawCondition( 'price < ' . $database->quote($searchParams['price']) );
					break;
			}
		}

		$jdate = JFactory::getDate();
		$now = $jdate->toMySQL();
		$nullDate	= $database->getNullDate();

		switch ($searchParams['publishing']) {
			case 2: // Published
				$advsearch->addRawCondition( "( (publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$now')  AND "
				. "(publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$now') AND "
				.	"link_published = '1' )" );
				break;
			case 3: // Unpublished
				$advsearch->addRawCondition( "link_published = '0'" );
				break;
			case 4: // Pending
				$advsearch->addRawCondition( "( (publish_up => '$now' OR publish_up = ".$database->Quote($nullDate).") AND link_published = '1' )" );
				break;
			case 5: // Expired
				$advsearch->addRawCondition( "( publish_down < '$now' AND publish_down <> '$nullDate' AND link_published = '1' )" );
				break;
			case 6: // Pending Listing, waiting for approval
				$advsearch->addRawCondition( "link_approved <= 0" );
				break;
		}

		# Check if this owner exists
		# Lookup owner's userid. Return error if does not exists
		if ( !empty($owner) ) {
			$database->setQuery('SELECT id FROM #__users WHERE username =' . $database->quote($owner));
			$owner_id = $database->loadResult();
			if ($owner_id > 0) {
				$advsearch->addRawCondition( 'l.user_id = ' . $database->quote($owner_id) );
			} else {
				echo "<script> alert('".JText::_( 'Invalid owner select again' )."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
		
		$advsearch->search();

		// Total Results
		$total = $advsearch->getTotal();

		// Links
		$where[] = "cl.main = '1'";
		$where[] = "cl.link_id = l.link_id";

	} else {

		// Total Results
		$database->setQuery( "SELECT COUNT(*) FROM #__mt_cats WHERE cat_name LIKE '%" . $database->getEscaped( $search_text ,true ) . "%'" );
		$total = $database->loadResult();

		// Categories
		$database->setQuery( "SELECT * FROM #__mt_cats WHERE cat_name LIKE '%" . $database->getEscaped( $search_text, true ) . "%' ORDER BY cat_name ASC LIMIT $limitstart, $limit" );
	}

	$results = $advsearch->loadResultList( $limitstart, $limit );

	# Page Navigation
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);

	# Get Pathway
	$pathWay = new mtPathWay();
	
	# Results Output
// /*
	if ( $search_where == 1 ) {
		// Links
		HTML_mtree::advsearchresults_links( $results, $fields, $pageNav, $pathWay, $search_where, $option );
	} else {
		// Categories
		HTML_mtree::searchresults_categories( $results, $pageNav, $pathWay, $search_where, $option );
	}
// */
}

/***
* Tree Templates
*/
function templates( $option ) {
	global $mainframe, $mtconf;

	$database 	=& JFactory::getDBO();

	$templateBaseDir = JPath::clean( $mtconf->getjconf('absolute_path').DS.'components'.DS.'com_mtree'.DS.'templates' );

	$rows = array();
	// Read the template dir to find templates
	$templateDirs = JFolder::folders($templateBaseDir, '.');

	$cur_template = $mtconf->get('template');

	$rowid = 0;

	// Check that the directory contains an xml file
	foreach($templateDirs as $templateDir) {
		if($templateDir == 'index.html') continue;
		$dirName = JPath::clean($templateBaseDir.DS.$templateDir);
		$xmlFilesInDir = JFolder::files($dirName,'.xml');

		foreach($xmlFilesInDir as $xmlfile) {
			// Read the file to see if it's a valid template XML file
			$xmlDoc = new DOMIT_Lite_Document();
			$xmlDoc->resolveErrors( true );
			if (!$xmlDoc->loadXML( $dirName.DS.$xmlfile, false, true )) {
				continue;
			}

			$element = &$xmlDoc->documentElement;

			if ($element->getTagName() != 'mosinstall' && $element->getTagName() != 'install') {
				continue;
			}
			if ($element->getAttribute( 'type' ) != 'template') {
				continue;
			}

			$row = new StdClass();
			$row->id = $rowid;
			$row->directory = $templateDir;
			$element = &$xmlDoc->getElementsByPath('name', 1 );
			$row->name = $element->getText();

			$element = &$xmlDoc->getElementsByPath('creationdate', 1);
			$row->creationdate = $element ? $element->getText() : 'Unknown';

			$element = &$xmlDoc->getElementsByPath('author', 1);
			$row->author = $element ? $element->getText() : 'Unknown';

			$element = &$xmlDoc->getElementsByPath('copyright', 1);
			$row->copyright = $element ? $element->getText() : '';

			$element = &$xmlDoc->getElementsByPath('authoremail', 1);
			$row->authorEmail = $element ? $element->getText() : '';

			$element = &$xmlDoc->getElementsByPath('authorurl', 1);
			$row->authorUrl = $element ? $element->getText() : '';

			$element = &$xmlDoc->getElementsByPath('version', 1);
			$row->version = $element ? $element->getText() : '';

			$element = &$xmlDoc->getElementsByPath('description', 1);
			$row->description = $element ? $element->getText() : '';

			// Get info from db
			if ($cur_template == $templateDir) {
				$row->default	= 1;
			} else {
				$row->default = 0;
			}

			$row->checked_out = 0;
			$row->mosname = strtolower( str_replace( ' ', '_', $row->name ) );

			$rows[] = $row;
			$rowid++;
		}
	}

	HTML_mtree::list_templates( $rows, $option );
}

function template_pages( $option ) {
	global $mtconf;

	$database =& JFactory::getDBO();
	
	$template = JRequest::getCmd( 'template', '');
	
	$xmlfile = $mtconf->getjconf('absolute_path') . '/components/com_mtree/templates/' . $template . '/templateDetails.xml';
	$xmlDoc = new DOMIT_Lite_Document();
	$xmlDoc->resolveErrors( true );
	$xmlDoc->loadXML( $xmlfile, false, true );

	$element = &$xmlDoc->documentElement;
	$element = &$xmlDoc->getElementsByPath('name', 1 );
	$template_name = $element->getText();
	
	$database->setQuery('SELECT params FROM #__mt_templates WHERE tem_name = ' . $database->quote($template) . ' LIMIT 1');
	$template_params = $database->loadResult();
	
	$params = new JParameter( $template_params, $xmlfile, 'template' );

	HTML_mtree::template_pages( $template, $template_name, $params, $option );
}

function edit_templatepage( $option ) {
	global $mtconf, $mainframe;

	$page = JRequest::getCmd( 'page', '');
	$template = JRequest::getCmd( 'template', '');

	$file = JPath::clean($mtconf->getjconf('absolute_path') .'/components/com_mtree/templates/'. $template .'/'. $page .'.tpl.php');

	if ( $fp = fopen( $file, 'r' ) ) {
		$content = fread( $fp, filesize( $file ) );
		$content = htmlspecialchars( $content );
		fclose($fp);
		HTML_mtree::edit_templatepage( $page, $template, $content, $option );
	} else {
		$mainframe->redirect( 'index.php?option='. $option .'&task=template_pages&template=' . $template, sprintf(JText::_( 'Cannot open file' ), $file) );
	}

}

function copy_template( $option ) {
	global $mtconf;
	
	$template = JRequest::getCmd( 'template', '');
	
	if( !empty($template) ) {
		$xmlfile = $mtconf->getjconf('absolute_path') . '/components/com_mtree/templates/' . $template . '/templateDetails.xml';
		$xmlDoc = new DOMIT_Lite_Document();
		$xmlDoc->resolveErrors( true );
		$xmlDoc->loadXML( $xmlfile, false, true );

		$element = &$xmlDoc->documentElement;
		$element = &$xmlDoc->getElementsByPath('name', 1 );
		$template_name = $element->getText();
		
		HTML_mtree::copy_template( $template, $template_name, $option );
	}
}

function copy_template2( $option ) {
	global $mtconf, $mainframe;

	$template = JRequest::getCmd( 'template', '');
	$new_template_name = JRequest::getString( 'new_template_name', '');
	$new_template_folder = JRequest::getCmd( 'new_template_folder', '');
	$new_template_creation_date = JRequest::getString( 'new_template_creation_date', '');
	$new_template_author = JRequest::getString( 'new_template_author', '');
	$new_template_author_email = JRequest::getString( 'new_template_author_email', '');
	$new_template_author_url = JRequest::getString( 'new_template_author_url', '');
	$new_template_version = JRequest::getString( 'new_template_version', '');
	$new_template_description = JRequest::getString( 'new_template_description', '');
	$new_template_copyright = JRequest::getString( 'new_template_copyright', '');

	jimport('joomla.filesystem.folder');
	
	$result = JFolder::copy($template, $new_template_folder, $mtconf->getjconf('absolute_path') . '/components/com_mtree/templates/');
	
	if( $result === true )
	{
		$new_template_xml = $mtconf->getjconf('absolute_path') . '/components/com_mtree/templates/' . $new_template_folder . DS . 'templateDetails.xml';

		jimport('joomla.utilities.simplexml');
		jimport('joomla.filesystem.file');
		
		$xml = new JSimpleXML;
		$xml->loadFile($new_template_xml);

		$xml->document->name[0]->setData($new_template_name);
		$xml->document->creationdate[0]->setData($new_template_creation_date);
		$xml->document->author[0]->setData($new_template_author);
		$xml->document->authoremail[0]->setData($new_template_author_email);
		$xml->document->authorurl[0]->setData($new_template_author_url);
		$xml->document->version[0]->setData($new_template_version);
		$xml->document->description[0]->setData($new_template_description);
		$xml->document->copyright[0]->setData($new_template_copyright);
		
		JFile::write($new_template_xml,$xml->document->toString());
		
		$db =& JFactory::getDBO();
		$db->setQuery("INSERT INTO #__mt_templates (`tem_name`) VALUES(".$db->Quote($new_template_folder).")");
		$db->query();
		
		$mainframe->redirect( 'index.php?option=com_mtree&task=templates', JText::_( 'Template successfully copied.' ) );
	}
	
	return true;
}

function delete_template( $option ) {
	global $mtconf, $mainframe;

	$database =& JFactory::getDBO();
	
	$template = JRequest::getCmd( 'template', '');

	$path = JPath::clean($mtconf->getjconf('absolute_path') . '/components/com_mtree/templates/' . $template);
	$database->setQuery('DELETE FROM #__mt_templates WHERE tem_name = ' . $database->quote($template) . ' LIMIT 1');
	$database->query();
	if (is_dir( $path )) {
		rmdirr( JPath::clean( $path ) );
	}

	$mainframe->redirect( 'index.php?option='. $option .'&task=templates' );
}

function save_templateparam( $option ) {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	
	$params = JRequest::getVar( 'params', array(), 'post', 'array');
	$template = JRequest::getCmd( 'template', '');
	if ( is_array( $params ) ) {
		$attribs = array();
		foreach ( $params as $k=>$v) {
			if( is_array($v) ) {
				$attribs[] = "$k=".implode('|',$v);
			} else {
				$attribs[] = "$k=$v";
			}
		}
		$str_params = implode( "\n", $attribs );
	}
	
	$database->setQuery('UPDATE #__mt_templates SET params = ' . $database->quote($str_params) . ' WHERE tem_name = ' . $database->quote($template) . ' LIMIT 1');
	$database->query();
	
	$task = JRequest::getCmd( 'task', '', 'post');
	if ( $task == "save_templateparams" ) {
		$mainframe->redirect( 'index.php?option='. $option .'&task=templates' );
	} else {
		$mainframe->redirect( 'index.php?option='. $option .'&task=template_pages&template=' . $template );
	}
}

function save_templatepage( $option ) {
	global $mtconf, $task, $mainframe;

	$template 	= JRequest::getCmd( 'template', '', 'post');
	$page		= JRequest::getCmd( 'page', '', 'post');
	
	$pagecontent = JRequest::getVar('pagecontent', '', 'post', 'string', JREQUEST_ALLOWRAW);

	if ( !$template ) {
		$mainframe->redirect( 'index.php?option='. $option .'&task=templates' );
	}

	if ( !$pagecontent ) {
		$mainframe->redirect( 'index.php?option='. $option .'&task=templates' );
	}

	$file = JPath::clean($mtconf->getjconf('absolute_path') .'/components/com_mtree/templates/'. $template .'/'.$page.'.tpl.php');
	if ( is_writable( $file ) == false ) {
		$mainframe->redirect( "index.php?option=$option&task=templates" , sprintf(JText::_( 'File not writeable' ), $file) );
	}
	
	if($task == 'apply_templatepage')
	{
		$return = "index.php?option=$option&task=edit_templatepage&template=$template&page=$page";
	}
	else
	{
		$return = "index.php?option=$option&task=template_pages&template=$template";
	}
	
	if ( $fp = fopen ($file, 'w' ) ) {
		fputs( $fp, $pagecontent, strlen( $pagecontent ) );
		fclose( $fp );
		$mainframe->redirect( $return, JText::_( 'Template page saved' ) );
	} else {
		$mainframe->redirect( $return, sprintf( JText::_( 'File not writeable' ), $file ) );
	}
}

function new_template( $option ) {
	HTML_mtree::new_template( $option );
}

function install_template( $option ) {
	global $mtconf, $mainframe;

	jimport('joomla.filesystem.folder');

	$database 	=& JFactory::getDBO();

	$files		= JRequest::get('files');
	$template 	= $files['template']['tmp_name'];
	
	require_once( $mtconf->getjconf('absolute_path') . '/includes/domit/xml_domit_lite_include.php' );
	require_once( $mtconf->getjconf('absolute_path') . '/administrator/includes/pcl/pclzip.lib.php' );
	require_once( $mtconf->getjconf('absolute_path') . '/administrator/includes/pcl/pclerror.lib.php' );
	$zipfile = new PclZip( $template );

	if( substr(PHP_OS, 0, 3) == 'WIN' ) {
		define('OS_WINDOWS',1);
	} else {
		define('OS_WINDOWS',0);
	}
	
	$tmp_install = JPath::clean( $mtconf->getjconf('absolute_path') . '/media/' . uniqid( 'minstall_' ) );
	if(!$zipfile->extract( PCLZIP_OPT_PATH, $tmp_install )) {
		$mainframe->redirect( 'index.php?option=com_mtree&task=templates', JText::_( 'Template installation failed' ) );
	}
	
	$tmp_xml = $tmp_install . '/templateDetails.xml';
	if( file_exists($tmp_xml) ) {

		$xmlDoc = & JFactory::getXMLParser('Simple');
		
		if (!$xmlDoc->loadFile( $tmp_xml )) {
			return false;
		}
		$template_name = $xmlDoc->document->name[0]->data();
		
		$database->setQuery('INSERT INTO #__mt_templates (tem_name) VALUES(' . $database->quote($template_name) . ')');
		$database->query();

	} else {
		rmdirr($tmp_install);
		$mainframe->redirect( 'index.php?option=com_mtree&task=templates', JText::_( 'Template installation failed' ) );
	}
	
	$tmp_installdir = JPath::clean($mtconf->getjconf('absolute_path') . '/components/com_mtree/templates/' . $template_name);
	if(file_exists($tmp_installdir)) {
		rmdirr($tmp_install);
		$mainframe->redirect( 'index.php?option=com_mtree&task=templates', JText::_( 'Template installation failed' ) );
	}

	JFolder::copy( $tmp_install, $tmp_installdir);
	rmdirr($tmp_install);
	$mainframe->redirect( 'index.php?option=com_mtree&task=templates', JText::_( 'Template installation success' ) );
	
}

function rmdirr($path) {
    if($files = glob($path . "/*")){
        foreach($files AS $file) {
            is_dir($file)? rmdirr($file) : unlink($file);
        }
    }
    rmdir($path);
}

function default_template( $option ) {
	global $mainframe;
	
	$database	=& JFactory::getDBO();
	$template 	= JRequest::getVar( 'template', '');
	
	if(!empty($template)) {
		$database->setQuery("UPDATE #__mt_config SET value ='" . $database->getEscaped($template) . "' WHERE varname = 'template' AND groupname = 'main' LIMIT 1");
		$database->query();
	}
	$mainframe->redirect('index.php?option=com_mtree&task=templates');	
}

function cancel_edittemplatepage( $option ) {
	global $mainframe;
	
	$template = JRequest::getVar( 'template', '');
	$mainframe->redirect( "index.php?option=$option&task=template_pages&template=" . $template );
}

function cancel_templatepages( $option ) {
	global $mainframe;
	$mainframe->redirect( "index.php?option=$option&task=templates" );
}

/***
* CSV Import Export
*/
function csv( $option ) {
	global $mtconf;

	$database 	=& JFactory::getDBO();

	# Load all custom fields
	$sql = "SELECT cf.*, ft.ft_class FROM #__mt_customfields AS cf "
		.	"\nLEFT JOIN #__mt_fieldtypes AS ft ON ft.field_type=cf.field_type"
		.	"\nWHERE cf.iscore = 0 ORDER BY ordering ASC";
	$database->setQuery($sql);

	$fields = new mFields();
	// $fields->setCoresValue( $row->link_name, $row->link_desc, $row->address, $row->city, $row->state, $row->country, $row->postcode, $row->telephone, $row->fax, $row->email, $row->website, $row->price, $row->link_hits, $row->link_votes, $row->link_rating, $row->link_featured, $row->link_created, $row->link_modified, $row->link_visited, $row->publish_up, $row->publish_down, $row->user_id, $row->username );
	$fields->loadFields($database->loadObjectList());

	# Publishing
	$publishing[] = JHTML::_('select.option', 1, JText::_( 'All' ) );
	$publishing[] = JHTML::_('select.option', 2, JText::_( 'Published' ) );
	$publishing[] = JHTML::_('select.option', 3, JText::_( 'Unpublished' ) );
	$publishing[] = JHTML::_('select.option', 4, JText::_( 'Pending' ) );
	$publishing[] = JHTML::_('select.option', 5, JText::_( 'Expired' ) );
	$publishing[] = JHTML::_('select.option', 6, JText::_( 'Pending approval' ) );
	$lists['publishing'] = JHTML::_('select.genericlist', $publishing, 'publishing', 'class="inputbox" size="1"',	'value', 'text', '' );

	HTML_mtree::csv( $fields, $lists, $option );
	
}

function csv_export( $option ) {
	global $mtconf;
	
	$database 	=& JFactory::getDBO();
	$fields 	= JRequest::getVar( 'fields', '', 'post');
	$publishing = JRequest::getVar( 'publishing', '', 'post');
	$nullDate	= $database->getNullDate();

	$jdate = JFactory::getDate();
	$now = $jdate->toMySQL();

	$custom_fields = array();
	$core_fields = array();
	foreach($fields AS $field) {
		if(substr($field,0,2) == 'cf') {
			$custom_fields[] =  substr($field,2);
		} elseif( $field == 'cat_id') {
			$core_fields[] = 'GROUP_CONCAT(DISTINCT cat_id ORDER BY cl.main DESC SEPARATOR \',\') AS cat_id';
		} else {
			$core_fields[] = $field;
		}
	}
	$where = array();
	switch ($publishing) {
		case 2: // Published
			$where[] = "( (publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$now')  AND "
			. "(publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$now') AND "
			.	"link_published = '1' )";
			break;
		case 3: // Unpublished
			$where[] = "link_published = '0'";
			break;
		case 4: // Pending
			$where[] =  "( (publish_up => '$now' OR publish_up = ".$database->Quote($nullDate).") AND link_published = '1' )";
			break;
		case 5: // Expired
			$where[] =  "( publish_down < '$now' AND link_published = '1' )";
			break;
		case 6: // Pending Listing, waiting for approval
			$where[] = "link_approved <= 0";
			break;
	}
	
	# Get link_id(s) first
	if(count($where)>0) {
		$database->setQuery('SELECT link_id FROM #__mt_links WHERE '.implode(" AND ", $where));
	} else {
		$database->setQuery('SELECT link_id FROM #__mt_links');
	}
	$link_ids = $database->loadResultArray();
	
	$header = '';
	$data = '';
	if(count($link_ids) > 0) {
		# Get the core fields value
		unset($where);
		$where = array();
		$where[] = "l.link_id = cl.link_id";
		// $where[] = "cl.main = '1'";
		$where[] = "l.link_id IN (" . implode(',',$link_ids) . ")";
		if(in_array('l.link_id',$core_fields)) {
			$sql = "SELECT ".implode(", ",$core_fields)." FROM (#__mt_links AS l, #__mt_cl AS cl)";
		} else {
			$sql = "SELECT ".implode(", ",array_merge(array('l.link_id'),$core_fields))." FROM (#__mt_links AS l, #__mt_cl AS cl)";
		}
		if (count($where)) {
			$sql .= "\n WHERE ".implode(" AND ", $where);
		}
		
		if(in_array('cat_id',$fields)) {
			$sql .= "\n GROUP BY cl.link_id";
		}
		
		$database->setQuery( $sql );
		$rows = $database->loadObjectList('link_id');
	
		# Get the custom fields' value
		if(count($custom_fields)>0) {
			$database->setQuery('SELECT cf_id, link_id, value FROM #__mt_cfvalues WHERE cf_id IN (' . implode(',',$custom_fields) . ') AND link_id IN (' . implode(',',$link_ids) . ')');
			$cfvalues = $database->loadObjectList();
			foreach($cfvalues AS $cfvalue) {
				$rows[$cfvalue->link_id]->{'cf'.$cfvalue->cf_id} = $cfvalue->value;
			}
		}
		$seperator = ',';

		# Create the CSV data
		$header = '';
		$data='';
		$i=0;
		foreach ($fields AS $field) {
			$i++;
			if($field == 'l.link_id') {
				$header .= 'link_id';
			} elseif(substr($field,0,2) == 'cf') {
				$header .=  substr($field,2);
			} else {
				$header .= $field;
			}
			if($i<count($fields)) {
				$header .= $seperator;
			}
		}
		$header .= "\n";

		foreach($rows AS $row) {
			$line = '';
			$j = 0;
			foreach($fields as $field){
				if($field == 'l.link_id') {
					if( !in_array('l.link_id',$core_fields) ) {
						continue;
					} else {
						$field = 'link_id';
					}
				}
				if( isset($row->$field) ) {
					$value = $row->$field;
				} else {
					$value = '';
				}
				
				if ($j >= 0) {
					if( !empty($value) ) {
						$line .= '"' . str_replace('"', '""', $value) . '"';
					}
					if( ($j+1) < count($fields) ) {
						$line .= $seperator;
					}
				}
				
				$j++;

			}
			
			if( !empty($line) ) {
				$data .= trim($line)."\n";
			}
		}
	}
	
	# this line is needed because returns embedded in the data have "\r"
	# and this looks like a "box character" in Excel
	$data = str_replace("\r", "", $data);

	HTML_mtree::csv_export( $header, $data, $option );
}

/***
* Configuration
*/
function config( $option, $show='' ) {
	global $mtconf;
	
	$database 	=& JFactory::getDBO();
	
	# Get all config groups
	$database->setQuery( 'SELECT * FROM #__mt_configgroup ' . (($show == 'all') ? '' : 'WHERE displayed = 1 ') . 'ORDER BY ordering ASC' );
	$configgroups = $database->loadResultArray();

	# Get all configs
	$database->setQuery( 'SELECT c.* FROM (#__mt_config AS c, #__mt_configgroup AS cg) '
		. 'WHERE cg.groupname = c.groupname '
		. (($show == 'all') ? '' : 'AND c.displayed = \'1\' ')
		. 'ORDER BY cg.ordering ASC, c.ordering' );
	$configs = $database->loadObjectList('varname');

	# Map
	$map = array();
	$map[] = JHTML::_('select.option', "mapquest", "MapQuest" );
	$map[] = JHTML::_('select.option', "yahoomaps", "Yahoo! Maps" );
	$map[] = JHTML::_('select.option', "googlemaps", "Google Maps" );
	$map[] = JHTML::_('select.option', "googlemaps_ca", "Google Maps Canada" );
	$map[] = JHTML::_('select.option', "googlemaps_cn", "Google Maps China" );
	$map[] = JHTML::_('select.option', "googlemaps_fr", "Google Maps France" );
	$map[] = JHTML::_('select.option', "googlemaps_de", "Google Maps Germany" );
	$map[] = JHTML::_('select.option', "googlemaps_it", "Google Maps Italy" );
	$map[] = JHTML::_('select.option', "googlemaps_jp", "Google Maps Japan" );
	$map[] = JHTML::_('select.option', "googlemaps_es", "Google Maps Spain" );
	$map[] = JHTML::_('select.option', "googlemaps_uk", "Google Maps UK" );
	$lists['map'] = JHTML::_('select.genericlist', $map, 'map', 'class="inputbox" size="1"', 'value', 'text', $configs['map']->value );

	# Image Library list
	$imageLibs=array();
	$imageLibs=detect_ImageLibs();
	if(!empty($imageLibs['gd1'])) { $thumbcreator[] = JHTML::_('select.option', 'gd1', 'GD Library '.$imageLibs['gd1'] ); }
	$thumbcreator[] = JHTML::_('select.option', 'gd2', 'GD2 Library '.( (array_key_exists('gd2',$imageLibs)) ? $imageLibs['gd2'] : '') );
	$thumbcreator[] = JHTML::_('select.option', 'netpbm', (isset($imageLibs['netpbm'])) ? $imageLibs['netpbm'] : "Netpbm" );
	$thumbcreator[] = JHTML::_('select.option', 'imagemagick', (isset($imageLibs['imagemagick'])) ? $imageLibs['imagemagick'] : "Imagemagick" ); 
	$lists['resize_method'] = JHTML::_('select.genericlist', $thumbcreator, 'resize_method', 'class="text_area" size="3"', 'value', 'text', $configs['resize_method']->value );

	# Sort Direction
	$sort[] = JHTML::_('select.option', "asc", JText::_( 'Ascending' ) );
	$sort[] = JHTML::_('select.option', "desc", JText::_( 'Descending' ) );
	$lists['sort_direction'] = $sort;

	# Category Order
	$cat_order = array();
	$cat_order[] = JHTML::_('select.option', '', JText::_( ' ' ) );
	$cat_order[] = JHTML::_('select.option', "cat_name", JText::_( 'Name' ) );
	$cat_order[] = JHTML::_('select.option', "cat_featured", JText::_( 'Featured' ) );
	$cat_order[] = JHTML::_('select.option', "cat_created", JText::_( 'Created' ) );
	$lists['cat_order'] = $cat_order;

	# Listing Order
	$listing_order = array();
	$listing_order[] = JHTML::_('select.option', "link_name", JText::_( 'Name' ) );
	$listing_order[] = JHTML::_('select.option', "link_hits", JText::_( 'Hits' ) );
	$listing_order[] = JHTML::_('select.option', "link_votes", JText::_( 'Votes' ) );
	$listing_order[] = JHTML::_('select.option', "link_rating", JText::_( 'Rating' ) );
	$listing_order[] = JHTML::_('select.option', "link_visited", JText::_( 'Visit' ) );
	$listing_order[] = JHTML::_('select.option', "link_featured", JText::_( 'Featured' ) );
	$listing_order[] = JHTML::_('select.option', "link_created", JText::_( 'Created' ) );
	$listing_order[] = JHTML::_('select.option', "link_modified", JText::_( 'Modified' ) );
	$listing_order[] = JHTML::_('select.option', "address", JText::_( 'Address' ) );
	$listing_order[] = JHTML::_('select.option', "city", JText::_( 'City' ) );
	$listing_order[] = JHTML::_('select.option', "state", JText::_( 'State' ) );
	$listing_order[] = JHTML::_('select.option', "country", JText::_( 'Country' ) );
	$listing_order[] = JHTML::_('select.option', "postcode", JText::_( 'Postcode' ) );
	$listing_order[] = JHTML::_('select.option', "telephone", JText::_( 'Telephone' ) );
	$listing_order[] = JHTML::_('select.option', "fax", JText::_( 'Fax' ) );
	$listing_order[] = JHTML::_('select.option', "email", JText::_( 'Email' ) );
	$listing_order[] = JHTML::_('select.option', "website", JText::_( 'Website' ) );
	$listing_order[] = JHTML::_('select.option', "price", JText::_( 'Price' ) );

	if( $show == 'all' || $configs['first_listing_order1']->value == 'ordering' || $configs['first_search_order1']->value == 'ordering' )
	{
		$listing_order[] = JHTML::_('select.option', "ordering", JText::_( 'Ordering' ) );
	}
	
	$lists['listing_order'] = $listing_order;

	# Review Order
	$review_order[] = JHTML::_('select.option', '', JText::_( ' ' ) );
	$review_order[] = JHTML::_('select.option', "rev_date", JText::_( 'Review date' ) );
	$review_order[] = JHTML::_('select.option', "vote_helpful", JText::_( 'Total helpful votes' ) );
	$review_order[] = JHTML::_('select.option', "vote_total", JText::_( 'Total votes' ) );
	$lists['review_order'] = $review_order;

	# User Access
	$access = array();
	$access[] = JHTML::_('select.option', "-1", JText::_( 'None' ) );
	$access[] = JHTML::_('select.option', "0", JText::_( 'Public' ) );
	$access[] = JHTML::_('select.option', "1", JText::_( 'Registered only' ) );
	$lists['user_access'] = $access;

	# User Access2
	$lists['user_access2'] = array_merge($access,array(JHTML::_('select.option', "2", JText::_( 'Registered only except listing owner' ) )));
	
	# SEF's link slug type
	$sef_link_slug_type = array();
	$sef_link_slug_type[] = JHTML::_('select.option', "1", JText::_( 'Alias' ) );
	$sef_link_slug_type[] = JHTML::_('select.option', "2", JText::_( 'Link ID' ) );
	$lists['sef_link_slug_type'] = $sef_link_slug_type;
	
	/*
	# Custom fields
	$database->setQuery( 'SELECT * FROM #__mt_customfield' );
	$customfields = $database->loadObjectList('cf_id');
	*/
	HTML_mtree::config( $configs, $configgroups, $lists, $option );


}

function saveconfig($option) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();
	$post 		= JRequest::get( 'post' );
	
	# This make sure the root entry has a cat_id equal to 0.
	$database->setQuery( "UPDATE #__mt_cats SET cat_id = 0 WHERE cat_parent = -1 LIMIT 1" );
	$database->query();
	
	# Save configs
	foreach( $post AS $key => $value ) {
		if( in_array($key,array('option','task')) ) continue;
		$sql = 'UPDATE #__mt_config SET value = ' . $database->quote($value) . ' WHERE varname = ' . $database->quote($key) . ' LIMIT 1';
		$database->setQuery($sql);
		$database->query();
	}
	$mainframe->redirect( "index.php?option=$option&task=config", JText::_( 'Config have been updated' ) );
}

?>