<?php
/**
 * @version		$Id: admin.mtimporter.php 845 2010-02-17 10:22:18Z CY $
 * @package		MT Importer
 * @copyright	(C) 2005-2010 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

require_once( JPATH_COMPONENT.DS.'admin.mtimporter.html.php' );
require_once( JPATH_ADMINISTRATOR.DS.'components' .DS.'com_mtree'.DS.'tools.mtree.php' );

$task		= JRequest::getCmd('task', '');

switch( $task ) {

case "check_csv":
	check_csv();
	break;

case "check_mosdir":
	check_mosdir();
	break;
	
case "check_gossamerlinks":
	check_gossamerlinks();
	break;

case "check_esyndicate":
	check_esyndicate();
	break;

case "check_bookmarks":
	check_bookmarks();
	break;

case "check_remository":
	check_remository();
	break;

case "check_sobi2":
	check_sobi2();
	break;

case "import_mosdir":
	import_mosdir();
	break;

case "import_gossamerlinks":
	import_gossamerlinks();
	break;

case "import_esyndicate":
	import_esyndicate();
	break;

case "import_bookmarks":
	import_bookmarks();
	break;

case "import_remository":
	import_remository();
	break;

case "import_sobi2":
	import_sobi2();
	break;

case "import_csv":
	import_csv();
	break;
	
case "import_jcontent":
	$cid = JRequest::getVar( 'cid', array(0), 'post');
	JArrayHelper::toInteger($cid, array(0));
	import_jcontent($cid);
	break;

case "check_jcontent":
default:
	check_jcontent();
	break;
}

function check_jcontent(){
	$database =& JFactory::getDBO();
	
	$database->setQuery( 'SELECT * FROM #__sections' );
	$sections = $database->loadObjectList();
	
	for ( $i=0; $i<count($sections); $i++ ) {
		$database->setQuery('SELECT COUNT(*) FROM #__categories WHERE section = '.$sections[$i]->id);
		$sections[$i]->categories = $database->loadResult();
		
		$database->setQuery('SELECT COUNT(*) FROM #__content WHERE sectionid = '.$sections[$i]->id);
		$sections[$i]->contentitems = $database->loadResult();
	}
	
	// Web links
	$sections[$i]->id = -1;
	$sections[$i]->title = JText::_( 'Web Links' );
	$sections[$i]->checked_out = 0;

	$database->setQuery('SELECT COUNT(*) FROM #__categories WHERE section = ' . $database->quote('com_weblinks') );
	$sections[$i]->categories = $database->loadResult();
	
	$database->setQuery('SELECT COUNT(*) FROM #__weblinks');
	$sections[$i]->contentitems = $database->loadResult();
	
	HTML_mtimporter::check_jcontent($sections);
	
}

function check_mosdir() {
	global $mainframe;
	
	$db = $mainframe->getCfg('db');
	$db_prefix = $mainframe->getCfg('dbprefix');

	$database =& JFactory::getDBO();

	# Select mosdir's categories
	$database->setQuery( "show tables from $db like '".$db_prefix."dir_cat'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."dir_cat" ) {
		$database->setQuery( "SELECT count(*) FROM #__dir_cat" );
		$pt_count['cats'] = $database->loadResult();
	} else {
		$pt_count['cats'] = -1;
	}

	# Select mosdir's listings
	$database->setQuery( "show tables from $db like '".$db_prefix."dir_listings'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."dir_listings" ) {
		$database->setQuery( "SELECT count(*) FROM #__dir_listings" );
		$pt_count['listings'] = $database->loadResult();
	} else {
		$pt_count['listings'] = -1;
	}
	
	# Look for custom fields
	$database->setQuery( "show tables from $db like '".$db_prefix."dir_customfields'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."dir_customfields" ) {
		$database->setQuery( "SELECT name FROM #__dir_customfields" );
		$custom_fields = $database->loadObjectList();
	}

	# Generate list of custom fields available in Mosets Tree
	$database->setQuery( "SELECT * FROM #__mt_customfields WHERE iscore = 0 ORDER BY caption ASC");
	$cfs = $database->loadObjectList();
	$cust[] = JHTML::_('select.option', "0", JText::_( 'Do not import' ) );
	foreach( $cfs AS $cf ) {
		$cust[] = JHTML::_('select.option', $cf->cf_id, $cf->caption );
	}

	# Find Mosets Tree's Status
	$database->setQuery( "SELECT count(*) FROM #__mt_links" );
	$mt_count['listings'] = $database->loadResult();
	$database->setQuery( "SELECT count(*) FROM #__mt_cats" );
	$mt_count['cats'] = $database->loadResult();

	HTML_mtimporter::check_mosdir( $pt_count, $mt_count, $custom_fields, $cust );
}

function check_gossamerlinks() {
	global $mainframe;
	
	$db = $mainframe->getCfg('db');
	$db_prefix = $mainframe->getCfg('dbprefix');

	$database =& JFactory::getDBO();

	$table_prefix = JRequest::getWord('table_prefix','linksql_');

	# Select gossamer's categories
	$database->setQuery( "show tables from $db like '" . $table_prefix . "Category'" );
	$tmp = $database->loadResult();
	if ( $tmp == $table_prefix . "Category" ) {
		$database->setQuery( "SELECT count(*) FROM " . $table_prefix . "Category" );
		$pt_count['cats'] = $database->loadResult();
	} else {
		$pt_count['cats'] = -1;
	}

	# Select gossamer's related categories
	$database->setQuery( "show tables from $db like '" . $table_prefix . "CatRelations'" );
	$tmp = $database->loadResult();
	if ( $tmp == $table_prefix . "CatRelations" ) {
		$database->setQuery( "SELECT count(*) FROM " . $table_prefix . "CatRelations" );
		$pt_count['relcats'] = $database->loadResult();
	} else {
		$pt_count['relcats'] = -1;
	}

	
	# Select gossamer's listings
	$database->setQuery( "show tables from $db like '" . $table_prefix . "Links'" );
	$tmp = $database->loadResult();
	if ( $tmp == $table_prefix . "Links" ) {
		$database->setQuery( "SELECT count(*) FROM " . $table_prefix . "Links" );
		$pt_count['listings'] = $database->loadResult();
	} else {
		$pt_count['listings'] = -1;
	}
	
	# Find Mosets Tree's Status
	$database->setQuery( "SELECT count(*) FROM #__mt_links" );
	$mt_count['listings'] = $database->loadResult();
	$database->setQuery( "SELECT count(*) FROM #__mt_cats" );
	$mt_count['cats'] = $database->loadResult();

	HTML_mtimporter::check_gossamer( $pt_count, $mt_count, $table_prefix );

}

function check_esyndicate() {
	global $mainframe;
	
	$db = $mainframe->getCfg('db');
	$db_prefix = $mainframe->getCfg('dbprefix');

	$database =& JFactory::getDBO();

	$table_prefix = JRequest::getString('table_prefix','v2206_');

	# Select eSyndicate's categories
	$database->setQuery( "show tables from $db like '" . $table_prefix . "categories'" );
	$tmp = $database->loadResult();
	if ( $tmp == $table_prefix . "categories" ) {
		$database->setQuery( "SELECT count(*) FROM " . $table_prefix . "categories" );
		$pt_count['cats'] = $database->loadResult();
	} else {
		$pt_count['cats'] = -1;
	}
	
	# Select eSyndicate's listings
	$database->setQuery( "show tables from $db like '" . $table_prefix . "listings'" );
	$tmp = $database->loadResult();
	if ( $tmp == $table_prefix . "listings" ) {
		$database->setQuery( "SELECT count(*) FROM " . $table_prefix . "listings" );
		$pt_count['listings'] = $database->loadResult();
	} else {
		$pt_count['listings'] = -1;
	}
	
	# Find Mosets Tree's Status
	$database->setQuery( "SELECT count(*) FROM #__mt_links" );
	$mt_count['listings'] = $database->loadResult();
	$database->setQuery( "SELECT count(*) FROM #__mt_cats" );
	$mt_count['cats'] = $database->loadResult();

	HTML_mtimporter::check_esyndicate( $pt_count, $mt_count, $table_prefix );

}

function check_bookmarks() {
	global $mainframe;
	
	$db = $mainframe->getCfg('db');
	$db_prefix = $mainframe->getCfg('dbprefix');

	$database =& JFactory::getDBO();

	# Select bookmarks' categories
	$database->setQuery( "show tables from $db like '".$db_prefix."bookmarks_categories'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."bookmarks_categories" ) {
		$database->setQuery( "SELECT count(*) FROM #__bookmarks_categories" );
		$pt_count['cats'] = $database->loadResult();
	} else {
		$pt_count['cats'] = -1;
	}

	# Select mosdir's listings
	$database->setQuery( "show tables from $db like '".$db_prefix."bookmarks'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."bookmarks" ) {
		$database->setQuery( "SELECT count(*) FROM #__bookmarks" );
		$pt_count['listings'] = $database->loadResult();
	} else {
		$pt_count['listings'] = -1;
	}
	
	# Look for custom fields
	$database->setQuery( "show tables from $db like '".$db_prefix."bookmarks_columns'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."bookmarks_columns" ) {
		$database->setQuery( "SELECT name, title FROM #__bookmarks_columns WHERE category != 'admin' AND custom =1" );
		$custom_fields = $database->loadObjectList();
	}

	# Generate list of custom fields available in Mosets Tree
	$database->setQuery( "SELECT * FROM #__mt_customfields WHERE iscore = 0 ORDER BY caption ASC");
	$cfs = $database->loadObjectList();
	$cust[] = JHTML::_('select.option', "0", JText::_( 'Do not import' ) );
	foreach( $cfs AS $cf ) {
		$cust[] = JHTML::_('select.option', $cf->cf_id, $cf->caption );
	}

	# Find Mosets Tree's Status
	$database->setQuery( "SELECT count(*) FROM #__mt_links" );
	$mt_count['listings'] = $database->loadResult();
	$database->setQuery( "SELECT count(*) FROM #__mt_cats" );
	$mt_count['cats'] = $database->loadResult();

	HTML_mtimporter::check_bookmarks( $pt_count, $mt_count, $custom_fields, $cust );
}

function check_remository() {
	global $mainframe;
	
	$db = $mainframe->getCfg('db');
	$db_prefix = $mainframe->getCfg('dbprefix');

	$database =& JFactory::getDBO();

	# Select Remository' categories
	$database->setQuery( "show tables from $db like '".$db_prefix."downloads_containers'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."downloads_containers" ) {
		$database->setQuery( "SELECT count(*) FROM #__downloads_containers" );
		$pt_count['cats'] = $database->loadResult();
	} else {
		$pt_count['cats'] = -1;
	}

	# Select remository's listings
	$database->setQuery( "show tables from $db like '".$db_prefix."downloads_files'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."downloads_files" ) {
		$database->setQuery( "SELECT count(*) FROM #__downloads_files" );
		$pt_count['listings'] = $database->loadResult();
	} else {
		$pt_count['listings'] = -1;
	}
	
	# Find Mosets Tree's Status
	$database->setQuery( "SELECT count(*) FROM #__mt_links" );
	$mt_count['listings'] = $database->loadResult();
	$database->setQuery( "SELECT count(*) FROM #__mt_cats" );
	$mt_count['cats'] = $database->loadResult();

	HTML_mtimporter::check_remository( $pt_count, $mt_count );
}

function check_sobi2() {
	global $mainframe;
	
	$db = $mainframe->getCfg('db');
	$db_prefix = $mainframe->getCfg('dbprefix');

	$database =& JFactory::getDBO();

	# Select SOBI2' categories
	$database->setQuery( "show tables from $db like '".$db_prefix."sobi2_categories'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."sobi2_categories" ) {
		$database->setQuery( "SELECT count(*) FROM #__sobi2_categories" );
		$pt_count['cats'] = $database->loadResult();
	} else {
		$pt_count['cats'] = -1;
	}

	# Select SOBI2's entries
	$database->setQuery( "show tables from $db like '".$db_prefix."sobi2_item'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."sobi2_item" ) {
		$database->setQuery( "SELECT count(*) FROM #__sobi2_item" );
		$pt_count['listings'] = $database->loadResult();
	} else {
		$pt_count['listings'] = -1;
	}
	
	# Find Mosets Tree's Status
	$database->setQuery( "SELECT count(*) FROM #__mt_links" );
	$mt_count['listings'] = $database->loadResult();
	$database->setQuery( "SELECT count(*) FROM #__mt_cats" );
	$mt_count['cats'] = $database->loadResult();

	HTML_mtimporter::check_sobi2( $pt_count, $mt_count );
}

function check_csv() {
	$database =& JFactory::getDBO();
	HTML_mtimporter::check_csv();
}

function import_jcontent( $cid ) {
	global $mainframe;
	
	$database 	=& JFactory::getDBO();
	$my			=& JFactory::getUser();
	$nullDate	= $database->getNullDate();
	
	$added_cats = 0;
	$added_links= 0;
	
	# Load sections
	$database->setQuery( "SELECT s.id, s.title, s.alias, s.description, s.published FROM #__sections AS s WHERE s.id IN (".implode(",",$cid).")" );
	$sections = $database->loadObjectList();
	
	if( count($sections) > 0 ) {
		foreach( $sections AS $section )
		{
			# Import Section
			$database->setQuery( 
			"INSERT INTO `#__mt_cats` ( `cat_name` , `alias` , `cat_desc` , `cat_parent` , `cat_links` , "
			.	"\n`cat_cats` , `cat_featured` , `cat_published` , `cat_created` , "
			.	"\n`cat_approved` , `cat_template` , `cat_usemainindex` , `metakey` , `metadesc` , `ordering` ) "
			.	"VALUES('".$database->getEscaped($section->title)."', '".$database->getEscaped($section->alias)."', '".$database->getEscaped($section->description)."', 0, 0, "
			.	"\n0, 0, ".$section->published.", ".$database->Quote($nullDate).","
			.	"\n'1', '', 0, '', '', 0)"
			);
			$database->query();
			$section_cat_id = $database->insertid();
			$added_cats++;
			
			# Load Categories
			$database->setQuery( "SELECT c.id, c.title, c.alias, c.description, c.published FROM #__categories AS c WHERE section = '".$section->id."'" );
			$categories = $database->loadObjectList();
			
			if( count($categories) ) {
				foreach( $categories as $category )
				{
					# Import Category
					$database->setQuery( 
					"INSERT INTO `#__mt_cats` ( `cat_name` , `alias` , `cat_desc` , `cat_parent` , `cat_links` , "
					.	"\n`cat_cats` , `cat_featured` , `cat_published` , `cat_created` , "
					.	"\n`cat_approved` , `cat_template` , `cat_usemainindex` , `metakey` , `metadesc` , `ordering` ) "
					.	"VALUES('".$database->getEscaped($category->title)."', '".$database->getEscaped($category->alias)."', '".$database->getEscaped($category->description)."', ".$section_cat_id.", 0, "
					.	"\n0, 0, ".$category->published.", ".$database->Quote($nullDate).","
					.	"\n'1', '', 0, '', '', 0)"
					);
					$database->query();
					$category_cat_id = $database->insertid();
					$added_cats++;
					
					# Load Content
					$database->setQuery( 
					"SELECT `title`, `alias`, CONCAT_WS(' ',`introtext`, `fulltext`) AS description, `created_by`, `state`, images "
					.	"FROM #__content WHERE catid = " . $category->id
					);
					$contents = $database->loadObjectList();
				
					if( count($contents) ) {
						foreach( $contents as $content ) {
							# Parse {mosimage}
							if(trim($content->images)) {
								$images = explode( "\n", $content->images );
								foreach($images AS $image) {
									$attr = explode("|",$image);

									// alt & title
									if ( !isset($attr[2]) || empty($attr[2]) ) {
										$attr[2] = 'Image';
									} else {
										$attr[2] = htmlspecialchars($attr[2]);
									}

									// border
									if ( !isset($attr[3]) || !$attr[3] ) {
										$attr[3] = 0;
									}

									// caption
									if ( !isset($attr[4]) || !$attr[4] ) {
										$attr[4] = '';
										$border = $attr[3];
									} else {
										$border = 0;
									}

									// caption position
									if ( !isset($attr[5]) || !$attr[5] ) {
										$attr[5] = '';
									}

									// caption alignment
									if ( !isset($attr[6]) || !$attr[6] ) {
										$attr[6] = '';
									}

									// width
									if ( !isset($attr[7]) || !$attr[7] ) {
										$attr[7] = '';
										$width = '';
									} else {
										$width = ' width: '. $attr[7] .'px;';
									}
									
									$html = '<img src="' . JURI::root() . 'images/stories/' . $attr[0] . '"';

									if ( function_exists( 'getimagesize' ) ) {
										$size = @getimagesize( JPATH_ROOT.DS.'images'.DS.'stories'.DS.$attr[0] );
										if (is_array($size)) {
											$html .= ' width="'. $size[0] .'" height="'. $size[1] .'"';
										}
									}
									// no aligment variable - if caption detected
									if ( !$attr[4] ) {
										if ($attr[1] == 'left' OR $attr[1] == 'right') {
											$html .= ' style="float: '. $attr[1] .';"';
										} else {
											$html .= $attr[1] ? ' align="middle"' : '';
										}
									}
									
									$html .=' hspace="6" alt="'. $attr[2] .'" title="'. $attr[2] .'" border="'. $border .'" />';
									
									// assemble caption - if caption detected
									$caption = '';
									if ( $attr[4] ) {				
										$caption = '<div class="mosimage_caption"';
										if ( $attr[6] ) {
											$caption .= ' style="text-align: '. $attr[6] .';"';
											$caption .= ' align="'. $attr[6] .'"';
										}
										$caption .= '>';
										$caption .= $attr[4];
										$caption .= '</div>';
									}
									
									if($attr[4]) {
										$float = '';
										$border_width = '';
										$style = '';
										if ( $attr[1] ) {
											$float = ' float: '. $attr[1] .';';
										}
										if ( $attr[3] ) {
											$border_width = ' border-width: '. $attr[3] .'px;';
										}

										if (  $attr[1] || $attr[3] ) {
											$style = ' style="'. $border_width . $float . $width .'"';
										}

										$img = '<div class="mosimage" '. $style .' align="center">'; 

										// display caption in top position
										if ( $attr[5] == 'top' && $caption ) {
											$img .= $caption;
										}
										$html = $img . $html;

										// display caption in bottom position
										if ( $attr[5] == 'bottom' && $caption ) {
											$html .= $caption;
										}
										$html .='</div>';
									}
									$arr_images[] = $html;
								}
							}
							
							if ( strpos( $content->description, 'mosimage' ) !== false ) {
								$regex = '/{mosimage\s*.*?}/i';	
								$GLOBALS['botMosImageCount'] 	= 0;
								$GLOBALS['botMosImageArray'] 	=& $arr_images;
								$content->description = preg_replace_callback( $regex, 'botMosImage_replacer', $content->description );
							}
							
							# Import Content
							$database->setQuery( 
							"INSERT INTO `#__mt_links` ( `link_name` , `alias` , `link_desc` , `user_id`, `link_published`, `link_approved`, `link_created` ) "
							.	"VALUES('".$database->getEscaped($content->title)."', '".$database->getEscaped($content->alias)."', '".$database->getEscaped($content->description)."', '".$content->created_by."', ".$content->state.", '1', ".$database->Quote($nullDate)." )"
							);
							$database->query();
							$link_id = $database->insertid();
							
							$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) VALUES( '".$link_id."', '".$category_cat_id."', 1)" );
							$database->query();
							// echo $database->getErrorMsg();
							// echo $database->getQuery();
							$added_links++;
							unset($arr_images);
						}
					}
					
				}
			}

		}
	}
	
	// Importing web links
	if( in_array(-1,$cid) )
	{
		# Insert Web Links section
		$database->setQuery( 
		"INSERT INTO `#__mt_cats` ( `cat_name` , `alias` , `cat_desc` , `cat_parent` , `cat_links` , "
		.	"\n`cat_cats` , `cat_featured` , `cat_published` , `cat_created` , "
		.	"\n`cat_approved` , `cat_template` , `cat_usemainindex` , `metakey` , `metadesc` , `ordering` ) "
		.	"VALUES('".$database->getEscaped(JText::_( 'Web Links' ))."', 'web-links', '', 0, 0, "
		.	"\n0, 0, 1, ".$database->Quote($nullDate).","
		.	"\n'1', '', 0, '', '', 0)"
		);
		$database->query();
		$section_cat_id = $database->insertid();
		$added_cats++;
		
		# Load Categories
		$database->setQuery( "SELECT c.id, c.title, c.alias, c.description, c.published FROM #__categories AS c WHERE section = 'com_weblinks'" );
		$categories = $database->loadObjectList();
		
		if( count($categories) ) {
			foreach( $categories as $category )
			{
				# Import Category
				$database->setQuery( 
				"INSERT INTO `#__mt_cats` ( `cat_name` , `alias` , `cat_desc` , `cat_parent` , `cat_links` , "
				.	"\n`cat_cats` , `cat_featured` , `cat_published` , `cat_created` , "
				.	"\n`cat_approved` , `cat_template` , `cat_usemainindex` , `metakey` , `metadesc` , `ordering` ) "
				.	"VALUES('".$database->getEscaped($category->title)."', '".$database->getEscaped($category->alias)."', '".$database->getEscaped($category->description)."', ".$section_cat_id.", 0, "
				.	"\n0, 0, ".$category->published.", ".$database->Quote($nullDate).","
				.	"\n'1', '', 0, '', '', 0)"
				);
				$database->query();
				$category_cat_id = $database->insertid();
				$added_cats++;
				
				# Load web links
				$database->setQuery( 
				"SELECT `title`, `alias`, `catid`, `url`, `description`, `date`, `hits`, `published`, `approved` "
				.	"FROM #__weblinks WHERE catid = " . $category->id
				);
				$weblinks = $database->loadObjectList();
			
				if( count($weblinks) ) {
					foreach( $weblinks as $weblink ) {
						# Import web links
						$database->setQuery( 
						"INSERT INTO `#__mt_links`"
						.	" ( `link_name` , `alias` , `link_desc` , `user_id`, `link_hits`, `website`, `link_published`, `link_approved`, `link_created` ) "
						.	"VALUES('"
						.	$database->getEscaped($weblink->title)."', '"
						.	$database->getEscaped($weblink->alias)."', '"
						.	$database->getEscaped($weblink->description)."', '"
						.	$my->id."', '"
						.	$weblink->hits."', '"
						.	$weblink->url."', '"
						.	$weblink->published."', '"
						.	$weblink->approved."', '"
						.	$weblink->date."' )"
						);
						$database->query();
						$link_id = $database->insertid();
						$added_links++;
						
						$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) VALUES( '".$link_id."', '".$category_cat_id."', 1)" );
						$database->query();
					}
				}
				
			}
		}
	}
	
	# Rebuild tree
	
	if( $added_cats > 0 || $added_links > 0 )
	{
		$tree = new mtTree();
		$tree->rebuild( 0, 1);
		$mainframe->redirect( 'index.php?option=com_mtree', JText::sprintf('Import process Completed. %s categories and %s listings imported.', $added_cats, $added_links) );
	} else {
		$mainframe->redirect( 'index.php?option=com_mtimporter&task=check_jcontent', JText::_('MT Importer did not find any categories or listings available for import. No categories or listings are added to the directory.') );
	}
	
}

function botMosImage_replacer( &$matches ) {
	$i = $GLOBALS['botMosImageCount']++;
	return @$GLOBALS['botMosImageArray'][$i];
}

function import_csv() {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	
	$files = JRequest::get( 'files' );
	$file_csv = $files['file_csv'];
	
	if( isset($file_csv['tmp_name']) && file_exists($file_csv['tmp_name']) )
	{
		# Find user_id for administrator
		$database->setQuery( "SELECT id FROM #__users WHERE username = 'admin' OR usertype = 'Super Administrator' LIMIT 1" );
		$admin_user_id = $database->loadResult();

		# Now, start reading the file
		$row = 0;
		$index_catid = -1;
		$index_linkname = -1;
		$handle = fopen($file_csv['tmp_name'], "r");
	
		// Test if the csv file is using /r as the line ending. If it is, use our custom csv parser.
		$data = fgets($handle, 100000);
		$type = 0;
		// if(strpos($data,"\r") > 0) {
		// 	$type = 1;
		// }
		rewind($handle);
	
		while (($data = mtgetcsv($handle,$type,$row)) !== FALSE) {
			$row++;

			# Set the field name first
			if ( $row == 1 ) {
				$fields = array();
				for ($f=0; $f < count($data); $f++) {
					if ( $data[$f] == 'cat_id' ) {
						$index_catid = $f;
					}
					$fields[] = $data[$f];
					if( $data[$f] == 'link_name' ) {
						$index_linkname = $f;
					} 
				}
				// echo "Fields list: <b>" .implode("|",$fields) . "</b><br />";
			} else {
			
				# Make sure the listing has at least a link_name. Everything else is optional.
				if ( !empty($data[$index_linkname]) ) {
					$num = count($data);
					$sql_cf_ids = array();
					$sql_cf_insertvalues = array();
					$sql_insertfields = array('alias','link_published','link_approved','link_created','user_id');
					$sql_insertvalues = array(JFilterOutput::stringURLSafe($data[$index_linkname]),1,1,date('Y-m-d H:i:s'),$admin_user_id);
					for ($c=0; $c < $num; $c++) {
						if ( !empty($data[$c]) && !empty($fields[$c]) && $c != $index_catid ) {
							switch($fields[$c]) {
								case 'alias':
									$sql_insertvalues[0] = $database->getEscaped($data[$c]);
									break;
								case 'link_published':
									$sql_insertvalues[1] = $database->getEscaped($data[$c]);
									break;
								case 'link_approved':
									$sql_insertvalues[2] = $database->getEscaped($data[$c]);
									break;
								case 'link_created':
									$sql_insertvalues[3] = $database->getEscaped($data[$c]);
									break;
								case 'user_id':
									$sql_insertvalues[4] = $database->getEscaped($data[$c]);
									break;
								default:
									if(is_numeric($fields[$c])) {
										if($fields[$c] > 22) {
											$sql_cf_ids[] = $fields[$c];
											$sql_cf_insertvalues[] = $database->getEscaped($data[$c]);
										}
									} else {
										$sql_insertfields[] = $fields[$c];
										$sql_insertvalues[] = $database->getEscaped($data[$c]);
									}
									break;
							}
							// echo "<br /><b>".$fields[$c].": </b>".$database->getEscaped($data[$c]);
						}
					}
				
					if ( count($sql_insertfields) == count($sql_insertvalues) && count($sql_insertvalues) > 0 ) {
						# Insert core data
						$sql = "INSERT INTO #__mt_links (".implode(",",$sql_insertfields).") VALUES ('".implode("','",$sql_insertvalues)."')";
						$database->setQuery($sql);
						$database->query();
						$link_id = $database->insertid();
						// echo '<br />' . $sql;
					
						# Insert Custom Field's data
						$values = array();
						if(count($sql_cf_ids)>0 && count($sql_cf_insertvalues)>0) {
							$sql = "INSERT INTO #__mt_cfvalues (cf_id,link_id,value) VALUES";
							for($i=0;$i<count($sql_cf_ids);$i++) {
								$values[] = "('" . $sql_cf_ids[$i] . "', '" . $link_id . "', '" . $sql_cf_insertvalues[$i] . "')";
							}
							$sql .= implode(',',$values);
							$database->setQuery($sql);
							$database->query();
							// echo '<br />' . $sql;
						}
					
						# Assign listing to categories
						if(stristr($data[$index_catid],',') === false) {
							$sql = "INSERT INTO #__mt_cl (link_id, cat_id, main) VALUES (".$link_id.", ".( ($index_catid == -1 || empty($data[$index_catid])) ? 0:$data[$index_catid] ).",1)";
							$database->setQuery($sql);
							$database->query();
							// echo '<br />' . $sql;
						# This record is assigning to more than one category at once.
						} else {
							$cat_ids = explode(',',$data[$index_catid]);
							$j = 0;
							foreach($cat_ids AS $cat_id) {
								if( !empty($cat_id) )
								{
									$sqlvalue = '('.$link_id.','.$cat_id.',';
									if($j==0) {
										$sqlvalue .= '1';
									} else {
										$sqlvalue .= '0';
									}
									$sqlvalue .= ')';
									$sqlvalues[] = $sqlvalue;
									++$j;
								}
							}
							if( !empty($sqlvalues) ) {
								$sql = 'INSERT INTO #__mt_cl (link_id, cat_id, main) VALUES ' . implode(', ',$sqlvalues);
								$database->setQuery($sql);
								$database->query();
								// echo '<br />' . $sql;
							}
							unset($sqlvalues);
						}
					}
				}

			}
			echo '<hr />';
		}

		fclose($handle);

		$mainframe->redirect( 'index.php?option=com_mtree', JText::_( 'Import process Complete!' ) );
	} else {
		$mainframe->redirect( 'index.php?option=com_mtimporter&task=check_csv', JText::_( 'No file specified' ) );
	}
}

function mtgetcsv($handle,$type=0,$line=0) {
	switch($type) {
		case 1:
			rewind($handle);
			$data = fgets($handle);
			$newlinedData = explode("\r",$data);
			if(($line+1)>count($newlinedData)) {
				return false;
			} else {
				$expr="/,(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/";
				$results=preg_split($expr,trim($newlinedData[$line]));
				return preg_replace("/^\"(.*)\"$/","$1",$results);
			}
			break;
		case 0:
		default:
			return fgetcsv($handle, 100000, ",");
	}
	
}

function import_mosdir() {
	global $mainframe;
	$database =& JFactory::getDBO();
	
	// Empty jos_mt_cats
	$database->setQuery( "TRUNCATE TABLE `#__mt_cats`" );
	$database->query();

	# Import categories
	$database->setQuery( 
		"INSERT INTO `#__mt_cats` ( `cat_id` , `cat_name` , `cat_desc` , `cat_parent` , `cat_links` , "
		.	"\n`cat_cats` , `cat_featured` , `cat_published` , `cat_created` , "
		.	"\n`cat_approved` , `cat_template` , `cat_usemainindex` , `metakey` , `metadesc` , `ordering` ) "
		
		.	"\nSELECT c.id, c.name, c.desc, c.parent, 0,"
		.	"\n0, 0, c.published, '0000-00-00 00:00:00',"
		.	"\n'1', '', 0, '', '', 0"
		.	"\nFROM #__dir_cat AS c" 
	);
	$database->query();

	// Empty jos_mt_links
	$database->setQuery( "TRUNCATE TABLE `#__mt_links`" );
	$database->query();

	# Import Listings
	$database->setQuery(
		"INSERT INTO `#__mt_links` ( `link_id` , `link_name` , `link_desc` , `user_id` , "
		.	"`link_hits` , `link_votes` , `link_rating` , `link_featured` , "
		.	"`link_published` , `link_approved` , `link_template` , `attribs` , `metakey` , "
		.	"`metadesc` , `internal_notes` , `ordering` , `link_created` , `publish_up` , "
		.	"`publish_down` , `link_modified` , `link_visited` , `address` , `city` , `state` , "
		.	"`country` , `postcode` , `telephone` , `fax` , `email` , `website` , `price`) "

		.	"SELECT d.lid, d.title, CONCAT_WS(' ',d.introtext, d.fulltext), d.created_by, "
		.	"d.hits, d.votes, d.rating, d.premium, "
		.	"d.published, '1', '', '', '', "
		.	"'', '', 0, d.created, d.publish_up,"
		.	"d.publish_down, d.modified, 0, CONCAT_WS(' ', d.address, d.address2), d.city, d.region, "
		.	"d.country, d.zip, d.phone, d.fax, d.email, d.url, '0' "

		.	"FROM #__dir_listings AS d"

	);

	$database->query();

	# Populate jos_mt_cl

	// Empty jos_cl
	$database->setQuery( "TRUNCATE TABLE `#__mt_cl`" );
	$database->query();

	$database->setQuery( "SELECT lid, cid FROM #__dir_listings" );
	$links = $database->loadObjectList();
	$cache = array();
	foreach( $links AS $link ) {
		$cat_ids = explode("|",$link->cid)	;
		array_pop( $cat_ids ); array_shift( $cat_ids );

		foreach( $cat_ids AS $cat_id) {
			if ( in_array($link->lid,$cache) ) {
				$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) VALUES( '".$link->lid."', '".$cat_id."', 0)" );
			} else {
				$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) VALUES( '".$link->lid."', '".$cat_id."', 1)" );
				$cache[] = $link->lid;
			}
			$database->query();
		}

	}

	# Import custom fields
	// Empty jos_mt_links
	$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues`" );
	$database->query();
	$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues_att`" );
	$database->query();

	// Retrieve information on custom fields mapping
	$database->setQuery( "SELECT name FROM #__dir_customfields" );
	$custom_fields = $database->loadObjectList();
	
	foreach( $custom_fields AS $cf ) {
		$cfs[$cf->name] = JRequest::getInt($cf->name, 0);
	}

	$database->setQuery( "SELECT * FROM #__dir_customrecords" );
	$custom_records = $database->loadObjectList();
	$sql = "INSERT INTO #__mt_cfvalues (cf_id,link_id,value) VALUES";
	foreach($custom_records AS $custom_record) {
		foreach($cfs AS $key => $value) {
			$values[] = "('" . $value . "', '" . $custom_record->lid . "', '" . $database->getEscaped($custom_record->$key) . "')";
		}
	}
	$sql .= implode(',',$values);
	$database->setQuery($sql);
	$database->query();

	// Insert Root
	$database->setQuery( "INSERT INTO #__mt_cats (cat_name, cat_published, cat_approved, cat_parent, lft) VALUES('Root', 1, 1, -1, 1) " );
	$database->query();
	$root_id = $database->insertid();

	$database->setQuery( "UPDATE #__mt_cats SET cat_id = 0 WHERE cat_id = ".$root_id );
	$database->query();

	// Rebuild tree
	$tree = new mtTree();
	$tree->rebuild( 0, 1);

	// Populate listings & categories alias
	require_once( JPATH_ADMINISTRATOR.DS.'components' .DS.'com_mtree'.DS.'upgrade'.DS.'2_1_0.php' );
	mUpgrade_2_1_0::populate_listing_alias();
	mUpgrade_2_1_0::populate_category_alias();

	$mainframe->redirect( 'index.php?option=com_mtree', 'Import process Complete!' );
}

function import_gossamerlinks() {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	$table_prefix = JRequest::getWord( 'table_prefix', 'linksql_');

	# Import gossamer's categories

	$database->setQuery( "SELECT * FROM " . $table_prefix . "Category" );
	$categories = $database->loadObjectList();

	// Empty jos_mt_cats
	$database->setQuery( "TRUNCATE TABLE `#__mt_cats`" );
	$database->query();
	$i = 0;

	$database->setQuery( 
		"INSERT INTO #__mt_cats (cat_id, cat_name, cat_parent, "
		.	"cat_desc, cat_links, metadesc, metakey, cat_published, "
		.	"cat_approved, cat_usemainindex, cat_allow_submission) "
		
		.	"SELECT ID, Name, FatherID, "
		.	"Description, Number_of_Links, Meta_Description, Meta_Keywords, '1', "
		.	"'1', '0', '1' "
		.	"FROM " . $table_prefix . "Category"
	);
	$database->query();


	// Insert Root
	$database->setQuery( "INSERT INTO #__mt_cats (cat_name, cat_published, cat_approved, cat_parent, lft) VALUES('Root', 1, 1, -1, 1) " );
	$database->query();
	$root_id = $database->insertid();

	$database->setQuery( "UPDATE #__mt_cats SET cat_id = 0 WHERE cat_id = ".$root_id );
	$database->query();

	// Rebuild tree
	$tree = new mtTree();
	$tree->rebuild( 0, 1);

	# Import Gossamer's related categories

	// Empty jos_mt_cats
	$database->setQuery( "TRUNCATE TABLE `#__mt_relcats`" );
	$database->query();

	$database->setQuery( 
		"INSERT INTO #__mt_relcats (cat_id, rel_id) "
		.	"SELECT CategoryID, RelatedID FROM " . $table_prefix . "CatRelations"
	);
	$database->query();

	# Import Gossamer's links
	/*
	Custom Fields:
	- LinkOwner
	- Contact_Name
	- Password
	*/
	
	// Empty jos_mt_links
	$database->setQuery( "TRUNCATE TABLE `#__mt_links`" );
	$database->query();

	$database->setQuery( 
		// "INSERT INTO #__mt_links (link_id, link_name, website, cust_1, "
		// .	"link_created, link_modified, link_desc, cust_2, email, "
		// .	"link_hits, link_approved, link_published, link_rating, link_votes, cust_3 ) "
		"INSERT INTO #__mt_links (link_id, link_name, website, "
		.	"link_created, link_modified, link_desc, email, "
		.	"link_hits, link_approved, link_published, link_rating, link_votes ) "
		
		.	"SELECT ID, Title, URL, "
		.	"CONCAT_WS(' ', Add_Date, '00:00:00'), CONCAT_WS(' ', Mod_Date, '00:00:00'), Description, Contact_Email, "
		.	"Hits, '1', '1', Rating, Votes "
		.	"FROM " . $table_prefix . "Links"
	);
	$database->query();

	$database->setQuery( "UPDATE #__mt_links set link_rating = link_rating/2" );
	$database->query();
	
	// Create custom fields for LinkOwner, Contact_Name and Password
	$database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('text', 'Link Owner', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 0, 0, 1, 0, '', '', 0)");
	$database->query();
	$linkowner_cf_id = $database->insertid();

	$database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('text', 'Contact Name', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 0, 0, 1, 0, '', '', 0)");
	$database->query();
	$contactname_cf_id = $database->insertid();

	// $database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('text', 'Password', '', 30, '', '', '', '', '', 0, 26, 1, 0, 1, 0, 0, 0, 0, 0, '', '', 0)");
	// $database->query();
	// $password_cf_id = $database->insertid();
	
	// Populate the custom fields
	$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
		.	"SELECT '" . $linkowner_cf_id . "', ID, LinkOwner "
		.	"FROM " . $table_prefix . "Links" );
	$database->query();

	$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
		.	"SELECT '" . $contactname_cf_id . "', ID, Contact_Name "
		.	"FROM " . $table_prefix . "Links" );
	$database->query();
	
	// $database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
	// 	.	"SELECT '" . $password_cf_id . "', ID, Password "
	// 	.	"FROM " . $table_prefix . "links" );
	// $database->query();
	
	# Import Gossamer's catlinks

	$database->setQuery( "SELECT * FROM " . $table_prefix . "CatLinks" );
	$relcats = $database->loadObjectList();

	$database->setQuery( "TRUNCATE TABLE `#__mt_cl`" );
	$database->query();

	$cache = array();

	foreach( $relcats AS $rl ) {
		
		if ( in_array($rl->LinkID,$cache) ) {
			$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) VALUES( '".$rl->LinkID."', '".$rl->CategoryID."', '0')" );
		} else {
			$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) VALUES( '".$rl->LinkID."', '".$rl->CategoryID."', '1')" );
			$cache[] = $rl->LinkID;
		}

		$database->query();
	}

	// Insert Root
	/*
	$database->setQuery( "INSERT INTO #__mt_cats (cat_name, cat_published, cat_approved, cat_parent, lft) VALUES('Root', 1, 1, -1, 1) " );
	$database->query();
	$root_id = $database->insertid();

	$database->setQuery( "UPDATE #__mt_cats SET cat_id = 0 WHERE cat_id = ".$root_id );
	$database->query();
	*/
	
	// Rebuild tree
	$tree = new mtTree();
	$tree->rebuild( 0, 1);

	// Populate listings & categories alias
	require_once( JPATH_ADMINISTRATOR.DS.'components' .DS.'com_mtree'.DS.'upgrade'.DS.'2_1_0.php' );
	mUpgrade_2_1_0::populate_listing_alias();
	mUpgrade_2_1_0::populate_category_alias();

	$mainframe->redirect( 'index.php?option=com_mtree', 'Import process Complete!' );

}

function import_esyndicate() {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	$table_prefix = JRequest::getString( 'table_prefix', 'v2206_');

	// Empty jos_mt_cats
	$database->setQuery( "TRUNCATE TABLE `#__mt_cats`" );
	$database->query();
	$i = 0;

	// Import eSyndicate's categories
	$database->setQuery( 
		"INSERT INTO #__mt_cats (cat_id, cat_name, title, cat_parent, "
		.	"cat_desc, cat_links, metadesc, metakey, cat_published, "
		.	"cat_approved, cat_usemainindex, cat_allow_submission) "
		
		.	"SELECT id, title, page_title, parent_id, "
		.	"description, num_all_listings, meta_description, meta_keywords, '1', "
		.	"'1', '0', '1' "
		.	"FROM " . $table_prefix . "categories "
		.	"WHERE id > 0"
	);
	$database->query();

	// Set categories with status=approval to cat_approved=1 in Mosets Tree
	$database->setQuery( "UPDATE #__mt_cats SET cat_approved = 0 WHERE status = 'approval'" );
	$database->query();

	// Insert Root
	$database->setQuery( "INSERT INTO #__mt_cats (cat_name, cat_published, cat_approved, cat_parent, lft) VALUES('Root', 1, 1, -1, 1) " );
	$database->query();
	$root_id = $database->insertid();

	$database->setQuery( "UPDATE #__mt_cats SET cat_id = 0 WHERE cat_id = ".$root_id );
	$database->query();

	// Import eSyndicate's links
	$database->setQuery( "TRUNCATE TABLE `#__mt_links`" );
	$database->query();

	$database->setQuery( 
		"INSERT INTO #__mt_links (link_id, link_name, website, "
		.	"link_created, link_desc, email, "
		.	"link_visited, link_approved, link_published, link_rating, link_votes ) "
		
		.	"SELECT id, title, url, "
		.	"date, description, email, "
		.	"clicks, '1', '1', rating, num_votes "
		.	"FROM " . $table_prefix . "listings"
	);
	$database->query();

	$database->setQuery( "UPDATE #__mt_links SET link_approved = 0 WHERE status = 'approval'" );
	$database->query();
	
	// Populate the custom fields
	$database->setQuery( "SELECT * FROM " . $table_prefix . "listing_fields WHERE name NOT IN ('url','title','description','email','reciprocal')" );
	$customfields = $database->loadObjectList();
	
	if( !empty($customfields) )
	{
		$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues`" );
		$database->query();

		$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues_att`" );
		$database->query();
		
		$database->setQuery( "DELETE FROM `#__mt_customfields` WHERE cf_id > 28" );
		$database->query();
		
		$cf_map = array(
			'text' 		=> 'text',
			'textarea'	=> 'multilineTextbox',
			'checkbox'	=> 'checkbox',
			'radio'		=> 'radiobutton',
			'number'	=> 'mnumber',
			'storage'	=> 'mfile',
			'image'		=> 'image'
			);
		foreach( $customfields AS $customfield )
		{
			// Does support support importing of storage and image yet
			if( in_array($customfield->type,array('storage','image')) )
			{
				continue;
			}
			
			$database->setQuery("INSERT INTO `#__mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES (".$database->Quote($cf_map[$customfield->type]).", ".$database->Quote($customfield->name).", '', ".$database->Quote($customfield->length).", '', '', '', '', '', 0, 99, 0, ".$database->Quote($customfield->required).", 1, 0, ".$database->Quote($customfield->searchable).", ".$database->Quote($customfield->searchable).", 1, 0, ".$database->Quote($customfield->name).", '', 0)");
			$database->query();
			$cf_id = $database->insertid();
			
			// Set ordering
			$database->setQuery("UPDATE #__mt_customfields SET ordering = cf_id WHERE cf_id = " . $cf_id . " LIMIT 1");
			$database->query();
			
			// Set caption
			$database->setQuery("SELECT `value` FROM " . $table_prefix . "language WHERE `key` = 'field_" . $customfield->name . "' AND `code` = 'en' LIMIT 1");
			$cf_caption = $database->loadResult();
			
			$database->setQuery("UPDATE #__mt_customfields SET caption = ".$database->Quote($cf_caption).", search_caption = ".$database->Quote($cf_caption)." WHERE cf_id = " . $cf_id . " LIMIT 1");
			$database->query();
			
			// Populate custom field's data
			$database->setQuery( 
				"INSERT INTO #__mt_cfvalues (cf_id, link_id, value, attachment ) "
				.	"SELECT '".$cf_id."', id, ".$customfield->name.", ".((!in_array($customfield->type,array('storage','image'))) ?0:1)." "
				.	"FROM " . $table_prefix . "listings WHERE ".$customfield->name." != ''"
			);
			$database->query();
		
			// Additional queries for checkbox, radio, storage and image
			switch($customfield->type)
			{
				case 'checkbox':
				case 'radio':
					// Set elements
					$database->setQuery("SELECT `value` FROM " . $table_prefix . "language WHERE `key` LIKE 'field_" . $customfield->name . "_%' AND code = 'en'");
					$cf_elements = $database->loadResultArray();

					$database->setQuery("UPDATE #__mt_customfields SET field_elements = ".$database->Quote(implode($cf_elements,'|'))." WHERE cf_id = " . $cf_id . " LIMIT 1");
					$database->query();
					
					$database->setQuery("UPDATE #__mt_cfvalues SET value = REPLACE(value,',','|') WHERE cf_id = " . $cf_id);
					$database->query();
					
					foreach( $cf_elements AS $cf_element )
					{
						$database->setQuery("SELECT `key`, `value` FROM " . $table_prefix . "language WHERE `key` LIKE 'field_" . $customfield->name . "_%' AND code = 'en' AND value = ".$database->Quote($cf_element)." LIMIT 1");
						echo "<br />".$database->getQuery();
						$object_cf_element = $database->loadObject();
						$cf_element_index = intval(str_replace('field_'.$customfield->name.'_','',$object_cf_element->key));
						
						$database->setQuery("UPDATE #__mt_cfvalues SET value = REPLACE(value,".$database->Quote($cf_element_index).",".$database->Quote($object_cf_element->value).")");
						$database->query();
						
					}
					break;
				
				case 'storage':
				case 'image':
					// // Clean up entries with '0' as value
					// $database->setQuery("DELETE FROM #__mt_cfvalues WHERE cf_id = " . $cf_id . " AND value = 0");
					// $database->query();
					// 
					// $database->setQuery( "SELECT ".$customfield->name." FROM " . $table_prefix . "listings "
					// 	.	" WHERE ".$customfield->name." != '' AND ".$customfield->name." != '0'");
					// $files = $database->loadResultArray();
					// 
					// jimport('joomla.filesystem.file');
					// 
					// foreach( $files AS $file )
					// {
					// 	JFile::copy();
					// }
					// break;
			}
		}
	}
	
	// Import eSyndicate's listing_categories
	$database->setQuery( "TRUNCATE TABLE `#__mt_cl`" );
	$database->query();

	$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) "
		.	"SELECT id, category_id, 1 FROM " . $table_prefix . "listings" );
	$database->query();

	$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) "
		.	"SELECT listing_id, category_id, 0 FROM " . $table_prefix . "listing_categories" );
	$database->query();

	// Import eSyndicate comments as reviews
	$database->setQuery( "TRUNCATE TABLE `#__mt_reviews`" );
	$database->query();

	$database->setQuery( "INSERT INTO #__mt_reviews (link_id, guest_name, rev_text, rev_date, rev_approved) "
		.	"SELECT listing_id, author, body, date, 1 FROM " . $table_prefix . "listing_comments" );
	$database->query();
	
	$database->setQuery( "UPDATE #__mt_reviews SET rev_approved = 0 WHERE status = 'approval'" );
	$database->query();
	
	$database->setQuery( "UPDATE #__mt_reviews SET guest_name = 'Anonymous' WHERE guest_name = ''" );
	$database->query();
	
	$database->setQuery( "SELECT rev_id, rev_text FROM #__mt_reviews" );
	$reviews = $database->loadObjectList();
	
	if( !empty($reviews) )
	{
		$rev_title_length = 64;
		foreach($reviews AS $review)
		{
			if( JString::strlen($review->rev_text) <= $rev_title_length )
			{
				$database->setQuery( "UPDATE #__mt_reviews SET rev_title = rev_text WHERE rev_id = " . $review->rev_id . " LIMIT 1" );
				$database->query();
			} else {
				$rev_title = JString::trim(strip_tags($review->rev_text));
				$rev_title = JString::str_ireplace("\r\n","",$rev_title);
				$rev_title = JString::substr($rev_title,0,$rev_title_length);
				
				// Make sure the meta description is complete and is not truncated in the middle of a sentence.
				if( JString::strlen($review->rev_text) > $rev_title_length && substr($rev_title,-1,1) != '.') {
					if( strrpos($rev_title,'.') !== false )
					{
						$rev_title = JString::substr($rev_title,0,JString::strrpos($rev_title,'.')+1);
					}
				}
				$database->setQuery( "UPDATE #__mt_reviews SET rev_title = ".$database->Quote($rev_title)." WHERE rev_id = " . $review->rev_id . " LIMIT 1" );
				$database->query();
			}
		}
	}
	
	// Rebuild tree
	$tree = new mtTree();
	$tree->rebuild( 0, 1);

	// Populate listings & categories alias
	require_once( JPATH_ADMINISTRATOR.DS.'components' .DS.'com_mtree'.DS.'upgrade'.DS.'2_1_0.php' );
	mUpgrade_2_1_0::populate_listing_alias();
	mUpgrade_2_1_0::populate_category_alias();

	$mainframe->redirect( 'index.php?option=com_mtree', 'Import process Complete!' );

}

function import_bookmarks() {
	global $mainframe;
	$database =& JFactory::getDBO();
	
	// Empty jos_mt_cats
	$database->setQuery( "TRUNCATE TABLE `#__mt_cats`" );
	$database->query();

	# Import categories
	$database->setQuery( 
		"INSERT INTO `#__mt_cats` ( `cat_id` , `cat_name` , `cat_desc` , `cat_parent` , `cat_links` , "
		.	"\n`cat_cats` , `cat_featured` , `cat_published` , `cat_created` , "
		.	"\n`cat_approved` , `cat_template` , `cat_usemainindex` , `metakey` , `metadesc` , `ordering` ) "
		
		.	"\nSELECT c.id, c.title, c.description, c.parent, 0,"
		.	"\n0, 0, c.published, '0000-00-00 00:00:00',"
		.	"\n'1', '', 0, c.keywords, '', c.ordering"
		.	"\nFROM #__bookmarks_categories AS c" 
	);
	$database->query();

	// Empty jos_mt_links
	$database->setQuery( "TRUNCATE TABLE `#__mt_links`" );
	$database->query();

	# Import Listings
	$database->setQuery(
		"INSERT INTO `#__mt_links` ( `link_id` , `link_name` , `link_desc` , `user_id` , "
		.	"`link_hits` , `link_votes` , `link_rating` , `link_featured` , "
		.	"`link_published` , `link_approved` , `link_template` , `attribs` , `metakey` , "
		.	"`metadesc` , `internal_notes` , `ordering` , `link_created` , `publish_up` , "
		.	"`publish_down` , `link_modified` , `link_visited` , `address` , `city` , `state` , "
		.	"`country` , `postcode` , `telephone` , `fax` , `email` , `website` , `price`) "

		.	"SELECT d.id, d.title, d.description, d.created_by, "
		.	"d.hits, d.ratingcount, d.rating, 0, "
		.	"d.published, d.approved, '', '', d.keywords, "
		.	"'', '', 0, d.created, '',"
		.	"'', d.modified, d.hits, '', '', '', "
		.	"'', '', '', '', '', d.url, '0' "

		.	"FROM #__bookmarks AS d"

	);

	$database->query();

	# Populate jos_mt_cl

	// Empty jos_cl
	$database->setQuery( "TRUNCATE TABLE `#__mt_cl`" );
	$database->query();
	
	$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) "
		.	"SELECT itemid, catid, '1' FROM #__bookmarks_itemcat" 
		);
	$database->query();

	# Import custom fields
	// Empty jos_mt_links
	$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues`" );
	$database->query();
	$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues_att`" );
	$database->query();

	// Retrieve information on custom fields mapping
	$database->setQuery( "SELECT name, title FROM #__bookmarks_columns WHERE category != 'admin' AND custom =1" );
	$custom_fields = $database->loadObjectList();
	
	foreach( $custom_fields AS $cf ) {
		$cfs[$cf->name] = JRequest::getInt($cf->name, 0);
		if( $cfs[$cf->name] > 0 )
		{
			$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
				.	"SELECT '".$cfs[$cf->name]."', id, f_".$cf->name." FROM #__bookmarks WHERE f_".$cf->name." != ''" 
				);
			$database->query();
		}
	}
	
	// Insert Root
	$database->setQuery( "UPDATE #__mt_cats SET cat_parent = 0 WHERE cat_parent = -1" );
	$database->query();
	$database->setQuery( "DELETE FROM #__mt_cats WHERE cat_id = -1 LIMIT 1" );
	$database->query();
	$database->setQuery( "INSERT INTO #__mt_cats (cat_name, cat_published, cat_approved, cat_parent, lft) VALUES('Root', 1, 1, -1, 1) " );
	$database->query();
	$root_id = $database->insertid();

	$database->setQuery( "UPDATE #__mt_cats SET cat_id = 0 WHERE cat_id = ".$root_id );
	$database->query();

	// Rebuild tree
	$tree = new mtTree();
	$tree->rebuild( 0, 1);

	// Populate listings & categories alias
	require_once( JPATH_ADMINISTRATOR.DS.'components' .DS.'com_mtree'.DS.'upgrade'.DS.'2_1_0.php' );
	mUpgrade_2_1_0::populate_listing_alias();
	mUpgrade_2_1_0::populate_category_alias();

	$mainframe->redirect( 'index.php?option=com_mtree', 'Import process Complete!' );
}

function import_remository() {
	global $mainframe;
	$database =& JFactory::getDBO();
	
	// Empty jos_mt_cats
	$database->setQuery( "TRUNCATE TABLE `#__mt_cats`" );
	$database->query();

	# Import categories
	$database->setQuery( 
		"INSERT INTO `#__mt_cats` ( `cat_id` , `cat_name` , `cat_desc` , `cat_parent` , `cat_links` , "
		.	"\n`cat_cats` , `cat_featured` , `cat_published` , `cat_created` , `cat_image`, "
		.	"\n`cat_approved` , `cat_template` , `cat_usemainindex` , `metakey` , `metadesc` , `ordering` ) "
		
		.	"\nSELECT c.id, c.name, c.description, c.parentid, c.filecount,"
		.	"\nc.foldercount, 0, c.published, '0000-00-00 00:00:00', c.icon,"
		.	"\n'1', '', 0, c.keywords, '', 0"
		.	"\nFROM #__downloads_containers AS c" 
	);
	$database->query();

	// Copy categories' icons
	$folder_icons_path = JPATH_ROOT.DS.'components'.DS.'com_remository'.DS.'images'.DS.'folder_icons'.DS;
	
	if( JFolder::exists($folder_icons_path) )
	{
		$files = JFolder::files($folder_icons_path);
		foreach( $files AS $file )
		{
			JFile::copy(
				$folder_icons_path.$file,
				JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'cats'.DS.'o'.DS.$file
			);
			JFile::copy(
				$folder_icons_path.$file,
				JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'cats'.DS.'s'.DS.$file
			);
		}
	}
	
	# Import Listings

	// Empty jos_mt_links
	$database->setQuery( "TRUNCATE TABLE `#__mt_links`" );
	$database->query();

	$database->setQuery(
		"INSERT INTO `#__mt_links` ( `link_id` , `link_name` , `link_desc` , `user_id` , "
		.	"`link_hits` , `link_votes` , `link_rating` , `link_featured` , "
		.	"`link_published` , `link_approved` , `link_template` , `attribs` , `metakey` , "
		.	"`metadesc` , `internal_notes` , `ordering` , `link_created` , `publish_up` , "
		.	"`publish_down` , `link_modified` , `link_visited` , `address` , `city` , `state` , "
		.	"`country` , `postcode` , `telephone` , `fax` , `email` , `website` , `price`) "

		.	"SELECT d.id, d.filetitle, d.description, d.submittedby, "
		// .	"d.hits, d.ratingcount, d.rating, 0, "
		.	"0, 0, 0, d.featured, "
		.	"d.published, 1, '', '', d.keywords, "
		.	"'', '', 0, d.submitdate, '',"
		.	"'', '', 0, '', '', '', "
		.	"'', '', '', '', d.author_email, d.author_URL, d.price "

		.	"FROM #__downloads_files AS d"

	);
	$database->query();

	// Insert rating and votes
	$database->setQuery("SELECT fileid, AVG( `value` ) AS link_rating, COUNT( `value` ) AS link_votes FROM #__downloads_log WHERE TYPE = 3 GROUP BY fileid");
	$rating_votes = $database->loadObjectList();
	if( !empty($rating_votes) )
	{
		foreach( $rating_votes AS $rating_vote )
		{
			$database->setQuery('UPDATE #__mt_links SET link_rating = ' . $database->Quote($rating_vote->link_rating) . ', link_votes = ' . $database->Quote($rating_vote->link_votes) 
				.	' WHERE link_id = ' . $database->Quote($rating_vote->fileid)
				.	' LIMIT 1'
				);
			$database->query();
		}
	}
	
	// Populate #__mt_log from #__downloads_log
	$database->setQuery( "INSERT INTO #__mt_log (`log_ip`, `log_type`, `user_id`, `log_date`, `link_id`, `value`) " 
		.	"SELECT `ipaddress`, 'vote', `userid`, `date`, `fileid`, `value` "
		.	"FROM #__downloads_log "
		.	"WHERE type = 3"
	);
	$database->query();
	
	// Copy listing's icon and images
	$file_icons_path = JPATH_ROOT.DS.'components'.DS.'com_remository'.DS.'images'.DS.'file_icons';
	
	if( JFolder::exists($file_icons_path) )
	{
		$files = JFolder::files($file_icons_path,'.',true,true);
		foreach( $files AS $file )
		{
			JFile::copy(
				$file,
				JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'m'.DS.JFile::getName($file)
			);
			JFile::copy(
				$file,
				JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'o'.DS.JFile::getName($file)
			);
			JFile::copy(
				$file,
				JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'s'.DS.JFile::getName($file)
			);
		}
		
		$database->setQuery( "TRUNCATE TABLE `#__mt_images`" );
		$database->query();
		
		$database->setQuery('INSERT INTO #__mt_images (link_id, filename, ordering) '
			.	' SELECT id, icon, \'1\' FROM #__downloads_files WHERE icon != \'\''
		);
		$database->query();
		
		// Strips paths
		$database->setQuery('UPDATE #__mt_images SET filename = '
			.	' SUBSTRING( filename, (	length( filename ) - locate( \'/\', reverse( filename ) ) ) +2 ) '
			.	' WHERE filename LIKE \'%/%\''
		);
		$database->query();
	}
	
	// Copy listing thumbnails
	$thumbnails_path = JPATH_ROOT.DS.'components'.DS.'com_remository_files';
	if( JFolder::exists($thumbnails_path) )
	{
		$folders = JFolder::folders($thumbnails_path,'.',false,true);
		foreach( $folders AS $folder )
		{
			$link_id = substr($folder,strripos($folder,'_')+1);
			
			$files = JFolder::files($folder,'jpg|png|gif|jpeg');
			
			$ordering = 2;
			if( !empty($files) )
			{
				$sql_images = array();
				foreach( $files AS $file )
				{
					$matches = preg_match( '/^th_'.$link_id.'_[0-9]{2}/', $file);
					if( $matches !== false && $matches > 0 )
					{
						$file_counter = substr(JFile::stripExt($file),strripos($file,'_')+1);
						
						JFile::copy(
							$folder.DS.$file,
							JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'s'.DS.$file
						);
						$sql_images[$ordering++] = $file;
						
						$original_image_path = $folder.DS.'img_'.$link_id."_".$file_counter.'.'.JFile::getExt($file);
						if( JFile::exists($original_image_path) )
						{
							JFile::copy(
								$original_image_path,
								JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'m'.DS.$file
							);
							JFile::copy(
								$original_image_path,
								JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'o'.DS.$file
							);
						} else {
							JFile::copy(
								$folder.DS.$file,
								JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'m'.DS.$file
							);
							JFile::copy(
								$folder.DS.$file,
								JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'o'.DS.$file
							);
						}
					}
				}
				if( !empty($sql_images) )
				{
					$sql_values = array();
					$sql = 'INSERT INTO #__mt_images (link_id, filename, ordering) ';
					$sql .= ' VALUES';
					foreach( $sql_images AS $ordering => $filename )
					{
						$sql_values[] = '('.$link_id.', '.$database->Quote($filename).', '.$ordering.')';
					}
					
					$sql .= implode(',',$sql_values);
					$database->setQuery($sql);
					$database->query();
				}
			}
		}
	}
	
	# Populate jos_mt_cl

	// Empty jos_cl
	$database->setQuery( "TRUNCATE TABLE `#__mt_cl`" );
	$database->query();
	
	$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) "
		.	"SELECT id, containerid, '1' FROM #__downloads_files" 
		);
	$database->query();

	# Create a new custom fields the files from Remository
	
	$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues`" );
	$database->query();

	$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues_att`" );
	$database->query();
	
	$database->setQuery( "DELETE FROM `#__mt_customfields` WHERE cf_id > 28" );
	$database->query();
	
	// File field
	$database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('mfile', 'File', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 0)");
	$database->query();
	$file_cf_id = $database->insertid();

	$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value, attachment, counter) "
		.	"SELECT '" . $file_cf_id . "', id, realname, 1, downloads "
		.	"FROM #__downloads_files " 
		.	"WHERE realname != ''"
		);
	$database->query();

	$database->setQuery( "INSERT INTO #__mt_cfvalues_att (cf_id, link_id, raw_filename, filename, filesize, extension) "
		.	"SELECT '" . $file_cf_id . "', id, realname, realname, 0, CONCAT('application/',filetype) "
		.	"FROM #__downloads_files " 
		.	"WHERE realname != ''"
		);
	$database->query();

	// File date field
	$database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('mdate', 'File Date', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 0)");
	$database->query();
	$filedate_cf_id = $database->insertid();

	$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
		.	"SELECT '" . $filedate_cf_id . "', id, filedate "
		.	"FROM #__downloads_files " 
		.	"WHERE filedate != '' AND filedate != '0000-00-00 00:00:00' "
		);
	$database->query();

	// License field
	$database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('text', 'License', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 0)");
	$database->query();
	$license_cf_id = $database->insertid();

	$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
		.	"SELECT '" . $license_cf_id . "', id, license "
		.	"FROM #__downloads_files " 
		.	"WHERE license != ''"
		);
	$database->query();
	
	// URL field
	$database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('weblinknewwin', 'URL', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 0)");
	$database->query();
	$url_cf_id = $database->insertid();

	$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
		.	"SELECT '" . $url_cf_id . "', id, url "
		.	"FROM #__downloads_files " 
		.	"WHERE url != '' AND url != 'http://'"
		);
	$database->query();
	
	// fileversion field
	$database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('text', 'File version', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 0)");
	$database->query();
	$fileversion_cf_id = $database->insertid();

	$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
		.	"SELECT '" . $fileversion_cf_id . "', id, fileversion "
		.	"FROM #__downloads_files " 
		.	"WHERE fileversion != ''"
		);
	$database->query();
	
	// fileauthor field
	$database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('text', 'File author', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 0)");
	$database->query();
	$fileauthor_cf_id = $database->insertid();

	$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
		.	"SELECT '" . $fileauthor_cf_id . "', id, fileauthor "
		.	"FROM #__downloads_files " 
		.	"WHERE fileauthor != ''"
		);
	$database->query();
	
	// filehomepage field
	$database->setQuery("INSERT INTO `jos_mt_customfields` (`field_type`, `caption`, `default_value`, `size`, `field_elements`, `prefix_text_mod`, `suffix_text_mod`, `prefix_text_display`, `suffix_text_display`, `cat_id`, `ordering`, `hidden`, `required_field`, `published`, `hide_caption`, `advanced_search`, `simple_search`, `details_view`, `summary_view`, `search_caption`, `params`, `iscore`) VALUES ('weblinknewwin', 'File homepage', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 0)");
	$database->query();
	$filehomepage_cf_id = $database->insertid();

	$database->setQuery( "INSERT INTO #__mt_cfvalues (cf_id, link_id, value) "
		.	"SELECT '" . $filehomepage_cf_id . "', id, filehomepage "
		.	"FROM #__downloads_files " 
		.	"WHERE filehomepage != '' AND filehomepage != 'http://' "
		);
	$database->query();
	
	// Copy files.
	$database->setQuery( "SELECT DISTINCT filepath FROM #__downloads_files WHERE filepath != ''"	);
	$filepaths = $database->loadResultArray();

	jimport('joomla.filesystem.folder');
	$att_path = JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'attachments'.DS;
	foreach( $filepaths AS $filepath )
	{
		if( JFolder::exists($filepath) )
		{
			$files = JFolder::files($filepath);
			foreach( $files AS $file )
			{
				if(JFile::copy($filepath.$file,$att_path.$file))
				{
					// Get the link ID based on the integer before file's extension
					$file_chunks = explode('.',$file);
					$file_size = filesize($att_path.$file);
					
					$file_sync_success = false;
					
					if( is_numeric($file_chunks[count($file_chunks)-2]) )
					{
						$database->setQuery('UPDATE #__mt_cfvalues_att '
							.	' SET raw_filename = '.$database->Quote($file)
							.	', filesize = ' . $database->Quote($file_size)
							.	' WHERE link_id = ' . $file_chunks[count($file_chunks)-2] . ' AND cf_id = ' . $file_cf_id 
							.	' LIMIT 1'
						);
						$database->query();
						
						if( $database->getAffectedRows() > 0 )
						{
							$file_sync_success = true;
						}
					} 
					
					if( !$file_sync_success ) 
					{
						$database->setQuery('SELECT id FROM #__downloads_files WHERE realname = ' . $database->Quote($file));
						$download_files = $database->loadResultArray();
						if( !empty($download_files) )
						{
							$database->setQuery('UPDATE #__mt_cfvalues_att '
								.	' SET raw_filename = '.$database->Quote($file)
								.	', filesize = ' . $database->Quote($file_size)
								.	' WHERE link_id IN (' . implode(', ',$download_files) . ') AND cf_id = ' . $file_cf_id 
							);
							$database->query();
						}
					}
				}
				
			}
		}
	}
	
	// Insert Root
	$database->setQuery( "UPDATE #__mt_cats SET cat_parent = 0 WHERE cat_parent = -1" );
	$database->query();
	$database->setQuery( "DELETE FROM #__mt_cats WHERE cat_id = -1 LIMIT 1" );
	$database->query();
	$database->setQuery( "INSERT INTO #__mt_cats (cat_name, cat_published, cat_approved, cat_parent, lft) VALUES('Root', 1, 1, -1, 1) " );
	$database->query();
	$root_id = $database->insertid();

	$database->setQuery( "UPDATE #__mt_cats SET cat_id = 0 WHERE cat_id = ".$root_id );
	$database->query();

	// Rebuild tree
	$tree = new mtTree();
	$tree->rebuild( 0, 1);

	// Populate listings & categories alias
	require_once( JPATH_ADMINISTRATOR.DS.'components' .DS.'com_mtree'.DS.'upgrade'.DS.'2_1_0.php' );
	mUpgrade_2_1_0::populate_listing_alias();
	mUpgrade_2_1_0::populate_category_alias();

	$mainframe->redirect( 'index.php?option=com_mtree', 'Import process Complete!' );
}

function import_sobi2() {
	global $mainframe;
	$database =& JFactory::getDBO();
	
	$db = $mainframe->getCfg('db');
	$db_prefix = $mainframe->getCfg('dbprefix');

	// Empty jos_mt_cats
	$database->setQuery( "TRUNCATE TABLE `#__mt_cats`" );
	$database->query();

	# Import categories
	$database->setQuery( 
		"INSERT INTO `#__mt_cats` ( `cat_id` , `cat_name` , `cat_desc` , `cat_parent` , `cat_links` , "
		.	"\n`cat_cats` , `cat_featured` , `cat_published` , `cat_created` , "
		.	"\n`cat_approved` , `cat_template` , `cat_usemainindex` , `metakey` , `metadesc` , `ordering` ) "
		
		.	"\nSELECT c.catid, c.name, CONCAT(c.introtext,c.description), 0, 0,"
		.	"\n0, 0, c.published, '0000-00-00 00:00:00',"
		.	"\n'1', '', 0, '', '', c.ordering"
		.	"\nFROM #__sobi2_categories AS c" 
	);
	$database->query();
	
	$database->setQuery(
		"UPDATE `#__mt_cats` AS cat, `#__sobi2_cats_relations` AS c "
		.	"\nSET cat.cat_parent = c.parentid "
		.	"\nWHERE c.catid = cat.cat_id"
		);
	$database->query();

	// Empty jos_mt_links
	$database->setQuery( "TRUNCATE TABLE `#__mt_links`" );
	$database->query();

	# Import Listings
	$database->setQuery(
		"INSERT INTO `#__mt_links` ( `link_id` , `link_name` , `link_desc` , `user_id` , "
		.	"`link_hits` , `link_votes` , `link_rating` , `link_featured` , "
		.	"`link_published` , `link_approved` , `link_template` , `attribs` , `metakey` , "
		.	"`metadesc` , `internal_notes` , `ordering` , `link_created` , `publish_up` , "
		.	"`publish_down` , `link_modified` , `link_visited` , `address` , `city` , `state` , "
		.	"`country` , `postcode` , `telephone` , `fax` , `email` , `website` , `price`) "
		.	"SELECT d.itemid, d.title, '', d.owner, "
		.	"d.hits, 0, 0, 0, "
		.	"d.published, d.approved, '', '', d.metakey, "
		.	"d.metadesc, '', d.ordering, '', d.publish_up,"
		.	"d.publish_down, '', d.visits, '', '', '', "
		.	"'', '', '', '', '', '', '0' "

		.	"FROM #__sobi2_item AS d"

	);
	$database->query();

	# Populate jos_mt_cl

	// Empty jos_cl
	$database->setQuery( "TRUNCATE TABLE `#__mt_cl`" );
	$database->query();
	
	$database->setQuery( "INSERT INTO #__mt_cl (link_id, cat_id, main) "
		.	"SELECT itemid, catid, ordering FROM #__sobi2_cat_items_relations" 
		);
	$database->query();

	$database->setQuery( "SELECT itemid, MIN( ordering ) AS minordering "
		.	"\n FROM `#__sobi2_cat_items_relations` "
		.	"\n GROUP BY itemid "
		.	"\n HAVING count(itemid) >1 "
		);
	$cat_item_relations = $database->loadObjectList();
	
	if( !empty($cat_item_relations) )
	{
		$itemids = array();
		foreach( $cat_item_relations AS $cat_item_relation )
		{
			$itemids[] = $cat_item_relation->itemid;
		}
		
		$database->setQuery( "UPDATE #__mt_cl SET main = 0 WHERE link_id IN (".implode(',',$itemids).")");
		$database->query();

		$database->setQuery( "UPDATE #__mt_cl SET main = 1 WHERE link_id NOT IN (".implode(',',$itemids).")");
		$database->query();
		
		foreach( $cat_item_relations AS $cat_item_relation )
		{
			$database->setQuery( "SELECT catid FROM #__sobi2_cat_items_relations WHERE itemid = " . $cat_item_relation->itemid
				.	"\n AND ordering = " . $cat_item_relation->minordering 
				.	"\n LIMIT 1"
				);
			$cat_id = $database->loadResult();
			$database->setQuery( "UPDATE #__mt_cl SET main = 1 WHERE cat_id = " . $cat_id 
				. " AND link_id = " . $cat_item_relation->itemid . " LIMIT 1" 
				);
			$database->query();				
		}
	}
	
	# Import custom fields
	/*
		7: 'calendar'
		6: 'checkbox group'
		5: 'list'
		4: 'custom'
		3: 'checkbox'
		2: 'textarea'
		1: 'inputbox'
	*/
	// Empty custom field data
	$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues`" );
	$database->query();
	$database->setQuery( "TRUNCATE TABLE `#__mt_cfvalues_att`" );
	$database->query();

	$database->setQuery( "INSERT INTO #__mt_customfields (cf_id, field_type, published, required_field, details_view, prefix_text_mod) "
		.	"SELECT (fieldid+100), fieldType, enabled, is_required, in_details, fieldDescription FROM #__sobi2_fields" 
		);
	$database->query();
	
	$database->setQuery( "UPDATE #__mt_customfields SET field_type = 'text' WHERE field_type = 1" );
	$database->query();

	$database->setQuery( "UPDATE #__mt_customfields SET field_type = 'multilineTextbox' WHERE field_type = 2" );
	$database->query();
	
	$database->setQuery( "UPDATE #__mt_customfields SET field_type = 'checkbox' WHERE field_type = 3" );
	$database->query();
	
	$database->setQuery( "UPDATE #__mt_customfields SET field_type = 'text' WHERE field_type = 1" );
	$database->query();
	
	$database->setQuery( "UPDATE #__mt_customfields SET field_type = 'checkbox' WHERE field_type = 5" );
	$database->query();
	
	$database->setQuery( "UPDATE #__mt_customfields SET field_type = 'mdate' WHERE field_type = 7" );
	$database->query();

	// Populate custom field caption from sobi2 language table
	$database->setQuery(
		"UPDATE `#__mt_customfields` AS cf, `#__sobi2_language` AS sobi "
		.	"\nSET cf.caption = sobi.langValue "
		.	"\nWHERE cf.cf_id = (sobi.fieldid+100)"
		);
	$database->query();
	
	// Populate custom field data
	$database->setQuery(
		"INSERT INTO #__mt_cfvalues (cf_id, link_id, value)"
		.	"\nSELECT (fieldid+100), itemid, data_txt FROM #__sobi2_fields_data"
		);
	$database->query();

	// Populate multi valued custom field elements from sobi2 language table
	$database->setQuery( "SELECT DISTINCT fieldid FROM #__sobi2_language WHERE sobi2Section = 'field_opt'" );
	$custom_fields = $database->loadResultArray();
	
	if( !empty($custom_fields) )
	{
		foreach( $custom_fields AS $cf )
		{
			$database->setQuery( 
				"SELECT GROUP_CONCAT(langValue SEPARATOR '|') "
				.	"\nFROM #__sobi2_language WHERE sobi2Section = 'field_opt' AND fieldid = " . $cf 
				);
			$field_elements = $database->loadResult();
			
			$database->setQuery( 
				"UPDATE #__mt_customfields SET field_elements = '".$field_elements."' WHERE cf_id = " . ($cf+100) . " LIMIT 1"
				);
			$database->query();
			
			$database->setQuery( 
				"SELECT langKey, langValue "
				.	"\nFROM #__sobi2_language WHERE sobi2Section = 'field_opt' AND fieldid = " . $cf 
				);
			$lang_key_values = $database->loadObjectList();
			
			if( !empty($lang_key_values) )
			{
				foreach( $lang_key_values AS $lang_key_value )
				{
					$database->setQuery(
						"UPDATE #__mt_cfvalues "
						.	"\nSET value = REPLACE(value,".$database->Quote($lang_key_value->langKey).",".$database->Quote($lang_key_value->langValue).") "
						.	"\nWHERE cf_id = " . ($cf+100)
					);
					$database->query();
				}
			}
		}
	}
	
	// There are 15 default fields that comes with SOBI2 of which 12 are supported by MT's core fields
	$maps = array(
		1=>'address',
		2=>'postcode',
		3=>'city',
		5=>'state',
		6=>'country',
		7=>'email',
		8=>'website',
		10=>'telephone',
		11=>'fax',
		13=>'link_desc',
		14=>'lng',
		15=>'lat'
		);
		
	foreach( $maps AS $id => $column )
	{
		$database->setQuery( 
			"UPDATE #__mt_links AS l, #__mt_cfvalues AS cfv " 
			.	"\nSET l.".$column." = cfv.value "
			.	"\nWHERE cfv.cf_id = ".($id+100)." AND l.link_id = cfv.link_id"
			);
		$database->query();
		
		$database->setQuery( "DELETE FROM #__mt_customfields WHERE cf_id = ".($id+100) );
		$database->query();

		$database->setQuery( "DELETE FROM #__mt_cfvalues WHERE cf_id = ".($id+100) );
		$database->query();
	}
	
	// Move the imported custom field to the bottom of the ordering
	$database->setQuery( "UPDATE #__mt_customfields SET ordering = ordering + 100 WHERE cf_id > 100" );
	$database->query();
	
	# Import reviews
	$database->setQuery( "show tables from $db like '".$db_prefix."sobi2_plugin_reviews'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."sobi2_plugin_reviews" )
	{
		$database->setQuery(
			"INSERT INTO #__mt_reviews (`review_id`, `link_id`, `user_id`, `rev_title`, `rev_text`, `rev_date`, `rev_approved`) "
			.	"\nSELECT revid, itemid, user_id, title, review, added, published FROM #__sobi2_plugin_reviews"
			);
	}
	
	# Import gallery
	$database->setQuery( "show tables from $db like '".$db_prefix."sobi2_plugin_gallery'" );
	$tmp = $database->loadResult();
	if ( $tmp == $db_prefix."sobi2_plugin_gallery" )
	{
		$database->setQuery( "SELECT imgid, itemid, filename, thumb, position FROM #__sobi2_plugin_gallery" );
		$images = $database->loadObjectList();
		if( !empty($images) )
		{
			jimport('joomla.filesystem.file');
			
			$gallery_path = JPATH_ROOT.DS.'images'.DS.'com_sobi2'.DS.'gallery';
			foreach( $images AS $image )
			{
				$filename = $gallery_path.DS.$image->itemid.DS.$image->filename;
				$thumb = $gallery_path.DS.$image->itemid.DS.$image->thumb;
				if( JFile::exists($thumb) AND JFile::exists($filename) )
				{
					JFile::copy(
						$thumb,
						JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'s'.DS.$image->imgid.'.'.JFile::getExt($thumb)
					);
					JFile::copy(
						$filename,
						JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'m'.DS.$image->imgid.'.'.JFile::getExt($filename)
					);
					JFile::copy(
						$filename,
						JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'img'.DS.'listings'.DS.'o'.DS.$image->imgid.'.'.JFile::getExt($filename)
					);
				}
				
			}
		}
	}
	
	// Insert Root
	$database->setQuery( "UPDATE #__mt_cats SET cat_parent = 0 WHERE cat_parent = -1" );
	$database->query();
	$database->setQuery( "UPDATE #__mt_cats SET cat_parent = 0 WHERE cat_parent = 1" );
	$database->query();
	$database->setQuery( "DELETE FROM #__mt_cats WHERE cat_id = -1 LIMIT 1" );
	$database->query();
	$database->setQuery( "INSERT INTO #__mt_cats (cat_name, cat_published, cat_approved, cat_parent, lft) VALUES('Root', 1, 1, -1, 1) " );
	$database->query();
	$root_id = $database->insertid();

	$database->setQuery( "UPDATE #__mt_cats SET cat_id = 0 WHERE cat_id = ".$root_id );
	$database->query();

	// Rebuild tree
	$tree = new mtTree();
	$tree->rebuild( 0, 1);

	// Populate listings & categories alias
	require_once( JPATH_ADMINISTRATOR.DS.'components' .DS.'com_mtree'.DS.'upgrade'.DS.'2_1_0.php' );
	mUpgrade_2_1_0::populate_listing_alias();
	mUpgrade_2_1_0::populate_category_alias();

	$mainframe->redirect( 'index.php?option=com_mtree', 'Import process Complete!' );
}

class mUpgrade {
	var $updated = false;
	function updated() {
		return $this->updated;
	}
	function addColumn($table, $column_name, $column_info='', $after='') {
		if(addColumn($table, $column_name, $column_info, $after)) {
			$this->updated = true;
		}
	}
	function addRows($table, $rows) {
		if(addRows($table, $rows)) {
			$this->updated = true;
		}	
	}
	function printStatus( $msg, $status=1 ) {
		if( $status == -1 ) {
			echo '<tr><td><b><span style="color:red">Error</span></b> - '.$msg.'</td></tr>';
		} elseif( $status == 1 OR $status == 0 ) {
			echo '<tr><td><b>'.(($status)?'<span style="color:green">OK</span>':'Skipped').'</b> - '.$msg.'</td></tr>';
		} elseif( $status == 2 ) {
			echo '<tr><td><strong>'.$msg.'</strong></td></tr>';
		}
	}
}
?>