<?php
/**
 * @version		$Id: mtree.tools.php 887 2010-05-31 05:36:12Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

/***
* Load Link
*/
function loadLink( $link_id, &$savantConf, &$fields, &$params ) {
	global $_MAMBOTS, $mtconf;

	$database	=& JFactory::getDBO();
	$jdate		= JFactory::getDate();
	$now		= $jdate->toMySQL();
	$nullDate	= $database->getNullDate();

	# Get all link data
	$database->setQuery( "SELECT l.*, u.username AS username, u.name AS owner, u.email AS owner_email, cl.cat_id AS cat_id, c.cat_name AS cat_name, img.filename AS link_image, img.img_id FROM (#__mt_links AS l, #__mt_cl AS cl)"
		. "\n LEFT JOIN #__users AS u ON u.id = l.user_id"
		. "\n LEFT JOIN #__mt_cats AS c ON c.cat_id = cl.cat_id"
		. "\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1"
		. "\n WHERE link_published='1' AND link_approved > 0 AND l.link_id='".$link_id."' " 
		. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$now'  ) "
		. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$now' ) "
		. "\n AND l.link_id = cl.link_id"
		. "\n LIMIT 1"
	);
	$link = $database->loadObject();
	
	if(count($link)==0) return false;
	
	# Use owner's email address is listing e-mail is not available
	if ( $mtconf->get('use_owner_email') && empty($link->email) && $link->user_id > 0 ) {
		$link->email = $link->owner_email;
	}

	# Load link's template
	if ( empty($link->link_template) ) {
		// Get link's template
		$database->setQuery( "SELECT cat_template FROM #__mt_cats WHERE cat_id='".$link->cat_id."' LIMIT 1" );
		$cat_template = $database->loadResult();

		if ( !empty($cat_template) ) {
			loadCustomTemplate(null,$savantConf,$cat_template);
		}
	} else {
		loadCustomTemplate(null,$savantConf,$link->link_template);
	}
	
	# Load fields
	$fields = loadFields( $link );
	
	# Load custom fields' value from #__mt_cfvalues to $link
	$database->setQuery( "SELECT CONCAT('cust_',cf_id) as varname, value FROM #__mt_cfvalues WHERE link_id = '".$link_id."'" );
	$cfvalues = $database->loadObjectList('varname');
	foreach( $cfvalues as $cfkey => $cfvalue )
	{
		$link->$cfkey = $cfvalue->value;
	}

	# Parameters
	$params = new JParameter( $link->attribs );
	$params->def( 'show_review', $mtconf->get('show_review'));
	$params->def( 'show_rating', $mtconf->get('show_rating' ));

	return $link;

}

/**
* Return 
* @param object #__mt_links object list results
* @param int Fields' filter type. Setting this to 0 will return all published field types.
*			 $view = 1 for Normal/Details View. $view = 2 for Summary View.
* @return mFields The formatted value of the field
*/
function loadFields( $link, $view=1 ) {
	global $mtconf;

	$database	=& JFactory::getDBO();

	require_once( $mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/mfields.class.php' );
	
	# Load all published CORE & custom fields
	$sql = "SELECT cf.*, cfv.link_id, cfv.value, cfv.attachment, cfv.counter, ft.ft_class FROM #__mt_customfields AS cf "
		.	"\nLEFT JOIN #__mt_cfvalues AS cfv ON cf.cf_id=cfv.cf_id AND cfv.link_id = " . $link->link_id
		.	"\nLEFT JOIN #__mt_fieldtypes AS ft ON ft.field_type=cf.field_type"
		.	"\nWHERE cf.published = '1' ";
	switch( $view ) {
		case 1:
			$sql .= "&& details_view = '1' ";
			break;
		case 2:
			$sql .= "&& summary_view = '1' ";
			break;
		default:
			break;			
	}
	$sql .= "ORDER BY ordering ASC";
	$database->setQuery($sql);

	$fields = new mFields();
	
	//Si el precio está a cero no lo mostramos en la ficha del recurso
	if($link->price!=0.00) $fields->setCoresValue( $link->link_name, $link->link_desc, $link->address, $link->city, $link->state, $link->country, $link->postcode, $link->telephone, $link->fax, $link->email, $link->website, $link->price, $link->link_hits, $link->link_votes, $link->link_rating, $link->link_featured, $link->link_created, $link->link_modified, $link->link_visited, $link->publish_up, $link->publish_down, $link->metakey, $link->metadesc, $link->user_id, $link->username );
	else $fields->setCoresValue( $link->link_name, $link->link_desc, $link->address, $link->city, $link->state, $link->country, $link->postcode, $link->telephone, $link->fax, $link->email, $link->website, $link->price="", $link->link_hits, $link->link_votes, $link->link_rating, $link->link_featured, $link->link_created, $link->link_modified, $link->link_visited, $link->publish_up, $link->publish_down, $link->metakey, $link->metadesc, $link->user_id, $link->username );
	
	$fields->loadFields($database->loadObjectList());

	while( $fields->hasNext() ) {
		$field = $fields->getField();
		if($field->getLinkId() == 0) {
			$fields->fields[$fields->pointer]['linkId'] = $link->link_id;
		}
		$fields->next();
	}
	
	return $fields;	
}

/***
* assignCommonVar
* 
* Assign comman Savant2 variable to all template
*/

function assignCommonVar( &$savant ) {
	global $option, $Itemid, $mtconf, $task;

	$my			=& JFactory::getUser();

	$savant->assign('option', $option);
	$savant->assign('task', $task);
	$savant->assign('Itemid', $Itemid);
	$savant->assign('user_id',$my->id);
	$savant->assign('my',$my);
	$savant->assign('form_action', $mtconf->getjconf('live_site').'/index.php');
	$savant->assign('listing_image_dir', $mtconf->getjconf('live_site').$mtconf->get('listing_image_dir'));
	$savant->assign('mtconf',$mtconf->getVarArray());
	$savant->assign('jconf',$mtconf->getJVarArray());
	$savant->assignRef('config',$mtconf);
}

/***
* assignCommonViewlinkVar
* 
* Assign comman Savant2 variable to viewlink or any similar pages
*/

function assignCommonViewlinkVar( &$savant, &$link, &$fields, &$pathWay, &$params ) {
	global $option, $Itemid, $mtconf;
	
	$database	=& JFactory::getDBO();
	$my			=& JFactory::getUser();
	
	# Get total favourites
	$total_favourites = 0;
	$database->setQuery( "SELECT COUNT(*) FROM #__mt_favourites WHERE link_id = '".$link->link_id."'" );
	$total_favourites = $database->loadResult();
	
	# Is this the user's favourite extension?
	$is_user_favourite = 0;
	if( $my->id > 0 && $total_favourites > 0 ) {
		$database->setQuery( "SELECT COUNT(*) FROM #__mt_favourites WHERE user_id = '".$my->id."' AND link_id = '".$link->link_id."' LIMIT 1" );
		if( $database->loadResult() > 0 ) {
			$is_user_favourite = 1;
		}
	}
	
	# Get the total number of images
	$total_images = 0;
	$database->setQuery( "SELECT COUNT(*) FROM #__mt_images WHERE link_id = '".$link->link_id."'" );
	$total_images = $database->loadResult();
	
	# Show actions-ratign-fav box?
	$show_actions_rating_fav = 0;
	if( $mtconf->get('show_favourite') || $mtconf->get('show_rating') ) {
		$show_actions_rating_fav++;
	} 
	
	$savant->assign('show_actions', false);
	$actions = array('map','ownerlisting','print','recommend','report','visit','review','claim','contact');
	foreach( $actions AS $action ) {
		$params->def( 'show_'.$action, $mtconf->get('show_'.$action) );
		if ( $params->get( 'show_'.$action ) ) {
			switch($action) {
				case 'contact':
					if($link->email <> '' || ($mtconf->get( 'use_owner_email' ) == 1 && $link->user_id > 0) ) {
						$show_actions_rating_fav++;
						$savant->assign('show_actions', true);
					} else {
						continue;
					}
					break;
				default:
					$show_actions_rating_fav++;
					$savant->assign('show_actions', true);
					break;
			}
			break;
		}
	}
	$savant->assign('show_actions_rating_fav', $show_actions_rating_fav);
	
	assignCommonVar($savant);
	$savant->assign('total_images', $total_images);
	$savant->assign('total_favourites', $total_favourites);
	$savant->assign('is_user_favourite', $is_user_favourite);
	$savant->assign('pathway', $pathWay);
	$savant->assign('link', $link);
	$savant->assign('link_id', $link->link_id);
	$savant->assign('min_votes_to_show_rating', $mtconf->get('min_votes_to_show_rating'));
	$savant->assign('fields', $fields);

	$savant->assign('mt_show_review', $params->get( 'show_review' ));
	$savant->assign('mt_show_rating', $params->get( 'show_rating' ));

	if( 
		$mtconf->get('user_rating') == '-1' 
		|| 
		($mtconf->get('user_rating') == 1 && $my->id <= 0) 
		||
		($mtconf->get('user_rating') == 2 && $my->id > 0 && $my->id == $link->user_id)
	) { // -1:none, 0:public, 1:registered user only
		$savant->assign('allow_rating', 0);
	} else {
		$savant->assign('allow_rating', 1);
	}

	// Plugins support
	$link->id 	= $link->link_id;
	$link->title = $link->link_name;
	$link->created_by = $link->user_id;

	$dispatcher	=& JDispatcher::getInstance();
	JPluginHelper::importPlugin('content');
	$savant->assign('mambotAfterDisplayTitle', $dispatcher->trigger('onAfterDisplayTitle', array (& $link, & $params, 0)));
	$savant->assign('mambotBeforeDisplayContent', $dispatcher->trigger('onBeforeDisplayContent', array (& $link, & $params, 0)));
	$savant->assign('mambotAfterDisplayContent', $dispatcher->trigger('onAfterDisplayContent', array (& $link, & $params, 0)));

	return true;
}

/***
* assignCommonListlinksVar
* 
* Assign comman Savant2 variable to list links or any similar pages
*/

function assignCommonListlinksVar( &$savant, &$links, &$pathWay, &$pageNav ) {
	global $task, $Itemid, $my, $cat_id, $mtconf;
	
	$database =& JFactory::getDBO();
	
	require_once( $mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/mfields.class.php' );
	
	# Load custom fields' caption
	if( $task == 'advsearch' ) {
		$database->setQuery( "SELECT CONCAT( 'cust_', cf_id ) AS name, caption AS value, field_type FROM #__mt_customfields WHERE published = 1 AND advanced_search = 1" );
		$custom_fields = $database->loadObjectList( "name" );
	} else {
		$database->setQuery( "SELECT CONCAT( 'cust_', cf_id ) AS name, caption AS value, field_type FROM #__mt_customfields" );
		$custom_fields = $database->loadObjectList( "name" );
	}
	
	$fields = array();
	$arrayLinkId = array();
	if( count($links) > 0 ) {
		foreach( $links AS $link ) {
			$arrayLinkId[] = intval($link->link_id);
		}
	}
	
	$tmp_fields = array();
	$tmp_core_fields = array();
	if( count($arrayLinkId) > 0 ) {
		$sql = "SELECT cf.*, cfv.link_id, cfv.value, cfv.attachment, cfv.counter, ft.ft_class FROM #__mt_customfields AS cf "
			.	"\nLEFT JOIN #__mt_cfvalues AS cfv ON cf.cf_id=cfv.cf_id "
			.	"\nLEFT JOIN #__mt_fieldtypes AS ft ON ft.field_type=cf.field_type"
			.	"\nWHERE cf.published = '1' AND cf.summary_view = '1' "
			.	"\nAND cfv.link_id IN (" . implode(',',$arrayLinkId). ") "
			.	"\nORDER BY cf.ordering ASC, link_id DESC";
		$database->setQuery($sql);
		$tmp_fields = $database->loadObjectList();
		
		$sql_core = "SELECT cf. * , NULL AS link_id, '' AS value, NULL AS attachment, ft.ft_class "
			.	"\nFROM #__mt_customfields AS cf "
			.	"\nLEFT JOIN #__mt_fieldtypes AS ft ON ft.field_type=cf.field_type "
			.	"\nWHERE cf.published = '1' "
			.	"\nAND cf.summary_view = '1' "
			.	"\nAND cf.iscore = '1' "
			.	"\nORDER BY cf.ordering ASC";
		$database->setQuery($sql_core);
		$tmp_core_fields = $database->loadObjectList();
	}
	
	if(count($arrayLinkId) > 0 && (count($tmp_core_fields) > 0 || count($tmp_fields) > 0) ) {
		$tmp_fields = array_merge($tmp_fields,$tmp_core_fields);
	}

	// Custom fields that do not require value, appear only once with a NULL value to link_id.
	// This loop clone the custom fields for each link
	foreach( $tmp_fields AS $key => $value ) {
		if(is_null($value->link_id)) {
			foreach($arrayLinkId AS $linkId) {
				$tmp_value = $value;
				$tmp_value = (PHP_VERSION < 5) ? $value : clone($value);
				$tmp_value->link_id = $linkId;
				$tmp_fields[] = $tmp_value;
				unset($tmp_value);
			}
		}
	}
	usort($tmp_fields,"customFieldsSort");
	if( count($links) > 0 ) {
		foreach( $links AS $link ) {
			$tmp_link_id = $link->link_id;
			$fields[$link->link_id] = new mFields();
			$data = null;
			$i=0;
			foreach( $tmp_fields AS $key => $value ) {
				if( $value->link_id == $tmp_link_id ) {
					$data[$key*28] = $value;
					unset( $tmp_fields[$key] );
				}				
				$i++;
			}
			$fields[$link->link_id]->setCoresValue( $link->link_name, $link->link_desc, $link->address, $link->city, $link->state, $link->country, $link->postcode, $link->telephone, $link->fax, $link->email, $link->website, $link->price, $link->link_hits, $link->link_votes, $link->link_rating, $link->link_featured, $link->link_created, $link->link_modified, $link->link_visited, $link->publish_up, $link->publish_down, $link->metakey, $link->metadesc, $link->user_id, $link->username );
			$fields[$link->link_id]->loadFields($data);
		}
	}
	
	# Mambots
	if($mtconf->get('cat_parse_plugin')) {
		applyMambots( $links );
	}

	assignCommonVar($savant);
	$savant->assignRef('pathway', $pathWay);
	$savant->assign('pageNav', $pageNav);
	$savant->assignRef('links', $links);
	$savant->assignRef('fields', $fields);
	$savant->assign('reviews', getReviews($links));
	$savant->assign('min_votes_to_show_rating', $mtconf->get('min_votes_to_show_rating'));
	$savant->assign('custom_fields', $custom_fields);
	$savant->assign('listing_image_dir', $mtconf->getjconf('live_site').$mtconf->get('listing_image_dir'));
	$savant->assign('cat_id', $cat_id);

	return true;
}

/***
* customFieldsSort
*/
function customFieldsSort($a,$b) {
	if ($a->ordering == $b->ordering) {
        return 0;
    }
    return ($a->ordering < $b->ordering) ? -1 : 1;
}

/***
* getSubCats_Recursive
*
* Recursively retrieves list of categories ID which is the children of of a $cat_id.
* This list will include $cat_id as well.
*/
function getSubCats_Recursive( $cat_id, $published_only=true ) {
	$database	=& JFactory::getDBO();

	$mtCats = new mtCats( $database );

	if ( $cat_id > 0 ) {
		$subcats = $mtCats->getSubCats_Recursive( $cat_id, $published_only );
	}
	$subcats[] = $cat_id;

	return $subcats;

}

/***
* getCatsSelectlist
*
*/
function getCatsSelectlist( $cat_id=0, &$cat_tree, $max_level=0 ) {
	$database	=& JFactory::getDBO();

	static $level = 0;

	$sql = "SELECT *, '".$level."' AS level FROM #__mt_cats WHERE cat_published=1 && cat_approved=1 && cat_parent= " . $database->quote($cat_id) . " ORDER BY cat_name ASC";
	$database->setQuery( $sql );

	$cat_ids = $database->loadObjectList();
	
	if ( count($cat_ids) > 0 ) {

		$level++;
		
		if( $max_level == 0 || $level <= $max_level) {
			foreach( $cat_ids AS $cid ) {
				
				$cat_tree[] = array("level" => $cid->level, "cat_id" => $cid->cat_id, "cat_name" => $cid->cat_name, "cat_allow_submission" => $cid->cat_allow_submission ) ;

				if ( $cid->cat_cats > 0 ) {
					$children_ids = getCatsSelectlist( $cid->cat_id, $cat_tree, $max_level );
					$cat_ids = array_merge( $cat_ids, $children_ids );
				}
			}
		}
		$level--;
	}

	return $cat_ids;

}

/***
* loadCustomTemplate
*
* If $cat_id has been assigned a custom template, $savantConf will be updated. Otherwise,
* no changes is done, and it will load default template.
*/
function loadCustomTemplate( $cat_id=null, &$savantConf, $template='') {
	global $mtconf;

	$database	=& JFactory::getDBO();
	
	if(!empty($template)) {
		$templateDir = $mtconf->getjconf('absolute_path') . '/components/com_mtree/templates/' . $template;
		if ( is_dir( $templateDir ) ) {
			$savantConf["template_path"] = $templateDir . '/';
			$mtconf->setTemplate($template);
		}
	} else {
		$mtCats = new mtCats( $database );
		$mtCats->load( $cat_id );
		if ( !empty($mtCats->cat_template) ) {
			$savantConf['template_path'] = $mtconf->getjconf('absolute_path')."/components/com_mtree/templates/".$mtCats->cat_template."/";
			$mtconf->setTemplate($template);
			
		}
	}
}

/***
* Apply Mambot to list of link objects and also enforce the max char for summary text in listcats
*/
function applyMambots( &$links ) {
	global $mtconf;

	JPluginHelper::importPlugin('content');
	// $_MAMBOTS->loadBotGroup( 'content' );

	for( $i=0; $i<count($links); $i++ ) {
		// Load Parameters
		$params = new JParameter( $links[$i]->attribs );
	
		$links[$i]->text = substr($links[$i]->link_desc,0,255);
		
		if  ((strlen($links[$i]->link_desc)) > 255) {
			$links[$i]->text .= ' <b>...</b>';
		}

		$links[$i]->id = $links[$i]->link_id;
		$links[$i]->title = $links[$i]->link_name;
		$links[$i]->created_by = $links[$i]->user_id;

		$dispatcher	=& JDispatcher::getInstance();
		$results = $dispatcher->trigger('onPrepareContent', array (& $links[$i], & $params->params, 0));
		}

}

function mtAppendPathWay( $option, $task, $cat_id=0, $link_id=0, $img_id=0 ) {
	global $mainframe, $Itemid;

	$database	=& JFactory::getDBO();
	$mtPathWay	= new mtPathWay();
	$pathway	=& $mainframe->getPathway();

	switch( $task ) {

		case "listcats":
		case "addcategory": // Show "Add Category Path?"
			$cids = $mtPathWay->getPathWay( $cat_id );
			break;

		case "viewlink":
		case "writereview":
		case "rate":
		case "recommend":
		case "viewgallery":
			$mtLink = new mtLinks( $database );
			$mtLink->load( $link_id );
			$cat_id = $mtLink->getCatID();
			$cids = $mtPathWay->getPathWay( $cat_id );
			break;

		case "viewimage":
			if( $img_id > 0 ) {
				$database->setQuery('SELECT link_id FROM #__mt_images WHERE img_id = \'' . $img_id . '\' LIMIT 1');
				$link_id = $database->loadResult();
				if( !is_null($link_id) ) {
					$mtLink = new mtLinks( $database );
					$mtLink->load( $link_id );
					$cat_id = $mtLink->getCatID();
					$cids = $mtPathWay->getPathWay( $cat_id );
				}
			}
			break;

		// Adding listing from a category
		case "addlisting":
			if ( $cat_id > 0 ) $cids = $mtPathWay->getPathWay( $cat_id );
			elseif( $link_id > 0 ) {
				$mtLink = new mtLinks( $database );
				$mtLink->load( $link_id );
				$cat_id = $mtLink->getCatID();
				$cids = $mtPathWay->getPathWay( $cat_id );
			}
			// Show "Add Listing" Path?
			break;

		
		case "listnew":
			$pathway->addItem( JText::_( 'New listing' ) );
			break;
		case "listfeatured":
			$pathway->addItem( JText::_( 'Featured listing' ) );
			break;
		case "listpopular":
			$pathway->addItem( JText::_( 'Popular listing' ) );
			break;
		case "listmostrated":
			$pathway->addItem( JText::_( 'Most rated listing' ) );
			break;
		case "listtoprated":
			$pathway->addItem( JText::_( 'Top rated listing' ) );
			break;
		case "listmostreview":
			$pathway->addItem( JText::_( 'Most reviewed listing' ) );
			break;
		case "advsearch":
			$pathway->addItem( JText::_( 'Advanced search' ) );
			break;
		case "advsearch2":
			$pathway->addItem( JText::_( 'Advanced search results' ) );
			break;
		case "search":
			$pathway->addItem( JText::_( 'Search results' ) );
			break;
			
	}
	
	if ( isset($cids) && is_array($cids) && count($cids) > 0 ) {
		foreach( $cids AS $cid ) {
			$pathway->addItem( $mtPathWay->getCatName($cid), "index.php?option=$option&task=listcats&cat_id=$cid" );
		}
		// Append the curreny category name
		$pathway->addItem( $mtPathWay->getCatName($cat_id), "index.php?option=$option&task=listcats&cat_id=$cat_id" );
	} elseif( $cat_id > 0 ) {
		$pathway->addItem( $mtPathWay->getCatName($cat_id), "index.php?option=$option&task=listcats&cat_id=$cat_id" );
	}

	if( in_array($task,array("viewlink", "writereview", "rate", "recommend", "viewgallery")) ) {
		$pathway->addItem( $mtLink->link_name, "index.php?option=$option&task=viewlink&link_id=$link_id");
	}

}

function getReviews( $links ) {
	$database =& JFactory::getDBO();
	
	$link_ids = array();
	
	if ( count( $links ) > 0 ) {
		foreach( $links AS $l ) {
			$link_ids[] = intval($l->link_id);
		}

		if ( count($link_ids) > 0 ) {
			# Get total reviews for each links
			$database->setQuery( "SELECT r.link_id, COUNT( * ) AS total FROM #__mt_cl AS cl "
				.	"\n LEFT JOIN #__mt_reviews AS r ON cl.link_id = r.link_id "
				.	"\n WHERE cl.link_id IN ('".implode("','",$link_ids)."') AND r.rev_approved = '1' AND cl.main = '1'"
				.	"\n GROUP BY r.link_id"	
				);
			$reviews = $database->loadObjectList('link_id');
			foreach( $links AS $link ) {
				if(!array_key_exists($link->link_id,$reviews)) {
					$reviews[$link->link_id]->link_id = $link->link_id;
					$reviews[$link->link_id]->total = 0;
				}
			}
			return $reviews;
		} else {
			return array(0);
		}
	} else {
		return false;
	}
}

function parse_words($text, $minlength=1){
	if($text=='0') {
		return array('0');
	} else {
		$result = array();
		if(@preg_match_all('/"(?P<string>[^"\\\\]{' . $minlength . ',}(?:\\\\.[^"\\\\]*)*)"|(?P<words>[^ "]{' . $minlength . ',})/', $text, $regs)) {
			if(($result = @array_unique(@array_filter($regs['words']) + @array_filter($regs['string']))) !== NULL) {
				@ksort($result, SORT_NUMERIC);
			}
		}
		$arrText = explode(' ',$text);
		foreach($arrText AS $aText) {
			if($aText == '0') {
				array_unshift($result,'0');
				break;
			}
		}
		return($result);		
	}
}
?>
