<?php
/**
 * @version		$Id: admin.mtree.html.php 908 2010-07-01 09:59:20Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class HTML_mtree {

	/***
	* Left Navigation
	*/
	function print_style() {
	?>
	<style type="text/css">
		a.mt_menu {
			font-weight: bold;
			text-decoration: none;
		}
		a.mt_menu:hover {
			font-weight: bold;
			text-decoration: underline;
		}
		a.mt_menu_selected {
			font-weight: bold;
			color: #515151;
			text-decoration: none;
			font-size: 12px;
		}
		a.mt_menu_selected:hover {
			text-decoration: underline;
			font-weight: bold;
			color: #515151;
			font-size: 12px;
		}
		ul.linkcats{
			margin:0px;
			padding:0;
		}
		ul.linkcats > li:first-child
		{
		font-weight:bold;
		}
		ul.linkcats li {
			margin:0;
			padding:0;
			list-style:none;
			padding:0 0 3px 0;
		}
		ul.linkcats img {margin-right:4px;}
		ul.linkcats a {text-decoration:underline;margin-right:3px;}
		.icon-48-mosetstree {background: url(../components/com_mtree/img/mosetstree-icon.png) no-repeat left;}
	</style>
	<?php
	}

	function print_startmenu( $task, $cat_parent ) {
		global $mtconf;
		
		$database	=& JFactory::getDBO();

		# Count the number of pending links/cats/reviews/reports/claims
		$database->setQuery( "SELECT COUNT(*) FROM #__mt_cats WHERE cat_approved='0'" );
		$pending_cats = $database->loadResult();

		$database->setQuery( "SELECT COUNT(*) FROM #__mt_links WHERE link_approved <= 0" );
		$pending_links = $database->loadResult();
	
		$database->setQuery( "SELECT COUNT(*) FROM #__mt_reviews WHERE rev_approved='0'" );
		$pending_reviews = $database->loadResult();
	
		$database->setQuery( "SELECT COUNT(*) FROM #__mt_reports WHERE rev_id = 0 && link_id > 0" );
		$pending_reports = $database->loadResult();

		$database->setQuery( "SELECT COUNT(*) FROM #__mt_reviews WHERE ownersreply_text != '' AND ownersreply_approved = '0'" );
		$pending_reviewsreply = $database->loadResult();

		$database->setQuery( "SELECT COUNT(*) FROM #__mt_reports WHERE rev_id > 0 && link_id > 0" );
		$pending_reviewsreports = $database->loadResult();

		$database->setQuery( "SELECT COUNT(*) FROM #__mt_claims" );
		$pending_claims = $database->loadResult();

		HTML_mtree::print_style();

	?>
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left" valign="top" width="160" height="0">

			<style type="text/css">

			</style>

			<table cellpadding="2" cellspacing="0" border="0" width="160" height="100%" align="left" style="border: 1px solid #cccccc;">
				<tr><td colspan="2" style="background: #DDE1E6; border-bottom: 1px solid #cccccc;font-weight:bold;"><?php echo JText::_( 'Title' ) ?></td></tr>
				
				<?php
				if (!$mtconf->get('admin_use_explorer')) {
				?>
				<tr>
					<td width="20" align="center" style="background-color:#DDE1E6"><img src="../includes/js/ThemeOffice/home.png" width="16" height="16" /></td>
					<td width="100%" style="background-color:#F1F3F5"> <a class="mt_menu<?php echo ($task=="listcats" || $task=="editcat" || $task=="") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=listcats"><?php echo JText::_( 'Navigate tree' ) ?></a></td>
				</tr>
				<?php } ?>
				<tr>
					<td align="center" style="background-color:#DDE1E6"><img src="../components/com_mtree/img/page_white_add.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5"> <a class="mt_menu<?php echo ($task=="newlink") ? "_selected": ""; ?>" href="index.php?option=com_mtree&amp;task=newlink&amp;cat_parent=<?php echo $cat_parent ?>"><?php echo JText::_( 'Add listing' ) ?></a></td>
				</tr>

				<tr>
					<td align="center" style="background-color:#DDE1E6"><img src="../components/com_mtree/img/folder_add.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5"> <a class="mt_menu<?php echo ($task=="newcat") ? "_selected": ""; ?>" href="index.php?option=com_mtree&amp;task=newcat&amp;cat_parent=<?php echo $cat_parent ?>"><?php echo JText::_( 'Add cat' ) ?></a></td>
				</tr>
				<?php 
					# Pending Approvals
					if ( 
							($pending_links > 0)
							OR
							($pending_cats > 0)
							OR
							($pending_reviews > 0)
							OR
							($pending_reports > 0)
							OR
							($pending_reviewsreply > 0)
							OR
							($pending_reviewsreports > 0)
							OR
							($pending_claims > 0)
						 ) { 
				?>
				<tr><td colspan="2" style="background: #DDE1E6; border-bottom: 1px solid #cccccc;border-top: 1px solid #cccccc;font-weight:bold;"><?php echo JText::_( 'Pending approval' ) ?></td></tr>
					
				<?php if ( $pending_cats > 0 ) { ?>
				<tr>
					<td style="background-color:#DDE1E6"><img src="../components/com_mtree/img/folder.png" width="18" height="18" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="listpending_cats") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=listpending_cats"><?php echo JText::_( 'Categories' ) ?> (<?php echo $pending_cats; ?>)</a></td>
				</tr>
					<?php 
					}

					if ( $pending_links > 0 ) { ?>
				<tr>
					<td style="background-color:#DDE1E6"><img src="../components/com_mtree/img/page_white.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="listpending_links") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=listpending_links"><?php echo JText::_( 'Listings' ) ?> (<?php echo $pending_links; ?>)</a></td>
				</tr>
				<?php 
					}	

					if ( $pending_reviews > 0 ) { ?>
				<tr>
					<td style="background-color:#DDE1E6"><img src="../components/com_mtree/img/comment.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="listpending_reviews") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=listpending_reviews"><?php echo JText::_( 'Reviews' ) ?> (<?php echo $pending_reviews; ?>)</a></td>
				</tr>
				<?php 
					}	

					if ( $pending_reports > 0 ) { ?>
				<tr>
					<td style="background-color:#DDE1E6"><img src="../components/com_mtree/img/error.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="listpending_reports") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=listpending_reports"><?php echo JText::_( 'Reports' ) ?> (<?php echo $pending_reports; ?>)</a></td>
				</tr>
				<?php 
					}	

					if ( $pending_reviewsreply > 0 ) { ?>
				<tr>
					<td style="background-color:#DDE1E6"><img src="../components/com_mtree/img/user_comment.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="listpending_reviewsreply") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=listpending_reviewsreply"><?php echo JText::_( 'Owners replies' ) ?> (<?php echo $pending_reviewsreply; ?>)</a></td>
				</tr>
				<?php 
					}	

					if ( $pending_reviewsreports > 0 ) { ?>
				<tr>
					<td style="background-color:#DDE1E6"><img src="../components/com_mtree/img/error.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="listpending_reviewsreports") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=listpending_reviewsreports"><?php echo JText::_( 'Reviews reports' ) ?> (<?php echo $pending_reviewsreports; ?>)</a></td>
				</tr>
				<?php 
					}	

					if ( $pending_claims > 0 ) { ?>
				<tr>
					<td style="background-color:#DDE1E6"><img src="../components/com_mtree/img/user_green.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="listpending_claims") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=listpending_claims"><?php echo JText::_( 'Claims' ) ?> (<?php echo $pending_claims; ?>)</a></td>
				</tr>
				<?php 
					}	

				} 
				 # End of Pending Approvals
				
				 # dTree
				if ($mtconf->get('admin_use_explorer')) {
				?>
				<tr><td colspan="2" style="background: #DDE1E6; border-bottom: 1px solid #cccccc;border-top: 1px solid #cccccc;font-weight:bold;"><?php echo JText::_( 'Explorer' ) ?></td></tr>
				<tr><td colspan="2" style="background-color:#F1F3F5;">
				<?php

				$cats = HTML_mtree::getChildren( 0, $mtconf->get('explorer_tree_level') );
				?>
				<link rel="StyleSheet" href="components/com_mtree/dtree.css" type="text/css" />
				<script type="text/javascript" src="../components/com_mtree/js/dtree.js"></script>

				<script type="text/javascript">
					<!--
					
					fpath = '../components/com_mtree/img/dtree/folder.gif';
					d = new dTree('d');

					d.config.closeSameLevel = true; 

					d.icon.root = '../includes/js/ThemeOffice/home.png',
					d.icon.folder = '../components/com_mtree/img/dtree/folder.gif',
					d.icon.folderOpen = '../components/com_mtree/img/dtree/folderopen.gif',
					d.icon.node = '../components/com_mtree/img/dtree/page.gif',
					d.icon.empty = '../components/com_mtree/img/dtree/empty.gif',
					d.icon.line = '../components/com_mtree/img/dtree/line.png',
					d.icon.join = '../components/com_mtree/img/dtree/join.png',
					d.icon.joinBottom = '../components/com_mtree/img/dtree/joinbottom.png',
					d.icon.plus = '../components/com_mtree/img/dtree/plus.png',
					d.icon.plusBottom = '../components/com_mtree/img/dtree/plusbottom.png',
					d.icon.minus = '../components/com_mtree/img/dtree/minus.gif',
					d.icon.minusBottom = '../components/com_mtree/img/dtree/minusbottom.gif',
					d.icon.nlPlus = '../components/com_mtree/img/dtree/nolines_plus.gif',
					d.icon.nlMinus = '../components/com_mtree/img/dtree/nolines_minus.gif'

					d.add(0,-1,'<?php echo JText::_( 'Root' ) ?>', 'index.php?option=com_mtree');
					<?php
					foreach( $cats AS $cat ) {
							echo "\nd.add(";
							echo $cat->cat_id.",";
							echo $cat->cat_parent.",";
							
							// Print Category Name
							echo "'".addslashes(htmlspecialchars($cat->cat_name, ENT_QUOTES ));
							echo "',";

							echo "pp(".$cat->cat_id."),";
							echo "'','',";
							echo "fpath";
							echo ");";
					}
					?>
					document.write(d);
					
					function pp(cid) {
						return 'index.php?option=com_mtree&task=listcats&cat_id='+cid;
					}
					//-->
				</script>

				</td></tr>
				<?php
					}

				# End of  dTree

				 # This Directory
				if ( $task == 'listcats' || $task == 'editcat' || $task == 'editcat_browse_cat' || $task == 'editcat_add_relcat' || $task == 'editcat_remove_relcat' ) {
					if($cat_parent > 0) {
						# Lookup all information about this directory
						$thiscat = new mtCats( $database );
						$thiscat->load( $cat_parent );

				?>
				<tr><td colspan="2" align="left" style="color: black; padding-left: 20px;font-weight:bold;background: #DDE1E6 url(../components/com_mtree/img/dtree/folderopen.gif) no-repeat center left; border-bottom: 1px solid #cccccc;border-top: 1px solid #cccccc;"><?php echo JText::_( 'This category' ) ?></td></tr>
				<tr class="row0"><td colspan="2" style="background-color:#F1F3F5">
					<?php
						$published_img = $thiscat->cat_published ? 'tick.png' : 'publish_x.png';
						$featured_img = $thiscat->cat_featured ? 'tick.png' : 'publish_x.png';
						
						$tcat = new mtDisplay();
						$tcat->add(JText::_( 'Name' ), '<a href="index.php?option=com_mtree&task=editcat&cat_id=' . $thiscat->cat_id . '&cat_parent=' . $thiscat->cat_parent . '">' . $thiscat->cat_name . '</a>');
						$tcat->add( JText::_( 'Cat id' ), $thiscat->cat_id );
						$tcat->add( JText::_( 'Listings' ), $thiscat->cat_links);
						$tcat->add( JText::_( 'Categories' ), $thiscat->cat_cats);
						$tcat->add( JText::_( 'Related categories2' ), $thiscat->getNumOfRelCats() );
						$tcat->add( JText::_( 'Published' ), '<img src="images/' . $published_img . '" width="12" height="12" border="0" alt="" />' );
						$tcat->add( JText::_( 'Featured' ), '<img src="images/' . $featured_img . '" width="12" height="12" border="0" alt="" />' );
						$tcat->display();
					?>
				</td></tr>

				<?php
					}

				# This Listing
				} elseif( $task == 'editlink' || $task == 'editlink_change_cat' || $task == 'reviews_list' || $task == 'newreview' || $task == 'editreview' || $task == 'editlink_browse_cat' || $task == 'editlink_add_cat' || $task == 'editlink_remove_cat' ) {
					global $link_id;

					if ( $link_id[0] > 0 ) {
						$thislink = new mtLinks( $database );
						$thislink->load( $link_id[0] );

						$database->setQuery( 'SELECT COUNT(*) FROM #__mt_reviews WHERE link_id = ' . $database->quote($link_id[0]) . ' AND rev_approved = 1' );
						$reviews = $database->loadResult();
						?>
				<tr><td colspan="2" align="left" style="color: black; padding-left: 20px;font-weight:bold;background: #DDE1E6 url(../includes/js/ThemeOffice/document.png) no-repeat center left; border-bottom: 1px solid #cccccc;border-top: 1px solid #cccccc;"><?php echo JText::_( 'This listing' ) ?></td></tr>
				<tr class="row0"><td colspan="2" style="background-color:#F1F3F5">
					<?php
						$tlisting = new mtDisplay();
						$tlisting->add(JText::_( 'Name' ), '<a href="index.php?option=com_mtree&task=editlink&link_id=' . $thislink->link_id . '">' . $thislink->link_name . '</a>');
						$tlisting->add( JText::_( 'Listing id' ), $thislink->link_id );
						$tlisting->add( JText::_( 'Category' ), '<a href="index.php?option=com_mtree&task=listcats&cat_id=' . $thislink->cat_id . '">' . $thislink->getCatName() . '</a>');
						$tlisting->add( JText::_( 'Reviews' ), '<a href="index.php?option=com_mtree&task=reviews_list&link_id=' . $thislink->link_id . '">' . $reviews . '</a>');
						$tlisting->add( JText::_( 'Hits' ), $thislink->link_hits );
						$tlisting->add( JText::_( 'Modified2' ), tellDateTime($thislink->link_modified) );
						$tlisting->display();
					?>
				</td></tr>
						<?php
					}
				}
				
				// Search
				$search_text 	= JRequest::getVar( 'search_text', '', 'post');
				$search_where	= JRequest::getInt( 'search_where', 0, 'post'); // 1: Listing, 2: Category
				
				?>

				<tr><td colspan="2" style="background: #DDE1E6; border-bottom: 1px solid #cccccc;border-top: 1px solid #cccccc;font-weight:bold;"><?php echo JText::_( 'Search' ) ?></td></tr>
				<tr><td colspan="2" align="left" style="background-color:#F1F3F5">
					<form action="index.php" method="post">
					<input class="text_area" type="text" name="search_text" size="10" maxlength="250" value="<?php echo $search_text; ?>" /> <input type="submit" value="<?php echo JText::_( 'Search' ) ?>" class="button" />
					<select name="search_where" class="inputbox" size="1">
						<option value="1"<?php echo ($search_where == 1)?' selected':''?>><?php echo JText::_( 'Listings' ) ?></option>
						<option value="2"<?php echo ($search_where == 2)?' selected':''?>><?php echo JText::_( 'Categories' ) ?></option>
					</select>
					<a href="index.php?option=com_mtree&task=advsearch"><?php echo JText::_( 'Advanced search short' ) ?></a>
					<input type="hidden" name="option" value="com_mtree" />
					<input type="hidden" name="task" value="search" />
					<input type="hidden" name="limitstart" value="0" />
					</form>
				</td></tr>

				<tr><td colspan="2" style="background: #DDE1E6; border-bottom: 1px solid #cccccc;border-top: 1px solid #cccccc;font-weight:bold;"><?php echo JText::_( 'More' ) ?></td></tr>
				<tr>
					<td style="background: #DDE1E6;"><img src="../components/com_mtree/img/zoom.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu" href="index.php?option=com_mtree&task=spy"><?php echo JText::_( 'Spy directory' ) ?></a></td>
				</tr>
				<tr>
					<td style="background: #DDE1E6;"><img src="../includes/js/ThemeOffice/config.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="config") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=config"><?php echo JText::_( 'Configuration' ) ?></a></td>
				</tr>
				<?php /* ?>
				<tr>
					<td style="background: #DDE1E6;"><img src="../components/com_mtree/img/table_link.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="linkchecker") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=linkchecker"><?php echo JText::_( 'Link checker' ) ?></a></td>
				</tr>
				<?php */ ?>
				<tr>
					<td style="background: #DDE1E6;"><img src="../includes/js/ThemeOffice/template.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="templates") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=templates"><?php echo JText::_( 'Templates' ) ?></a></td>
				</tr>
				<tr>
					<td style="background: #DDE1E6;"><img src="../includes/js/ThemeOffice/content.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="customfields") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=customfields"><?php echo JText::_( 'Custom fields' ) ?></a></td>
				</tr>
				
				<?php
				/* Hack para desabilitar la exportación
				<tr>
					<td style="background: #DDE1E6;"><img src="../includes/js/ThemeOffice/query.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="csv") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=csv"><?php echo JText::_( 'Export' ) ?></a></td>
				</tr>
				
				 */
				?>
				<tr>
					<td style="background: #DDE1E6;"><img src="../includes/js/ThemeOffice/globe3.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu" href="index.php?option=com_mtree&amp;task=geocode"><?php echo JText::_( 'Locate Listings in Map' ) ?></a></td>
				</tr>
				<tr>
					<td style="background: #DDE1E6;"><img src="../includes/js/ThemeOffice/globe3.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu" href="index.php?option=com_mtree&amp;task=globalupdate"><?php echo JText::_( 'Recount categories listings' ) ?></a></td>
				</tr>
				<tr>
					<td style="background: #DDE1E6;"><img src="../includes/js/ThemeOffice/credits.png" width="16" height="16" /></td>
					<td style="background-color:#F1F3F5">&nbsp;<a class="mt_menu<?php echo ($task=="about") ? "_selected": ""; ?>" href="index.php?option=com_mtree&task=about"><?php echo JText::_( 'About mosets tree' ) ?></a></td>
				</tr>

			</table>		
		</td>
		<td valign="top">
		<?php 
	}

	function print_endmenu() {	
	?></td>
		</tr>
	</table>
	<?php
	}

	function getChildren( $cat_id, $cat_level ) {
		global $mtconf;

		$database	=& JFactory::getDBO();

		$cat_ids = array();

		if ( $cat_level > 0  ) {

			$sql = "SELECT cat_id, cat_name, cat_parent, cat_cats, cat_links FROM #__mt_cats WHERE cat_published=1 && cat_approved=1 && cat_parent= " . $database->quote($cat_id) . ' ';
			
			if ( !$mtconf->get('display_empty_cat') ) { 
				$sql .= "&& ( cat_cats > 0 || cat_links > 0 ) ";	
			}

			$sql .= "\nORDER BY cat_name ASC ";

			$database->setQuery( $sql );
			$cat_ids = $database->loadObjectList();

			if ( count($cat_ids) ) {
				foreach( $cat_ids AS $cid ) {
					$children_ids = HTML_mtree::getChildren( $cid->cat_id, ($cat_level-1) );
					$cat_ids = array_merge( $cat_ids, $children_ids );
				}
			}
		}

		return $cat_ids;

	}

	/***
	* Link
	*/
	function editLink( &$row, $fields, $images, $cat_id, $other_cats, &$lists, $number_of_prev, $number_of_next, &$pathWay, $returntask, &$form, $option, $activetab=0 ) {
		global $mtconf;
		JFilterOutput::objectHTMLSafe( $row );
		
		jimport('joomla.html.pane');
		$pane	= &JPane::getInstance('sliders', array('allowAllClose' => true));
		$editor = &JFactory::getEditor();
		?>
		<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_js_library'); ?>"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site'); ?>/components/com_mtree/js/category.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site'); ?>/components/com_mtree/js/addlisting.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site'); ?>/components/com_mtree/js/jquery-ui-personalized-1.5.3.min.js"></script>
		<?php if( $mtconf->get( 'use_map' ) ) { 
		?><script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site'); ?>/components/com_mtree/js/map.js"></script><?php
		}
		?>
		<script language="javascript" type="text/javascript">
		jQuery.noConflict();
		var mosConfig_live_site=document.location.protocol+'//' + location.hostname + '<?php echo ($_SERVER["SERVER_PORT"] == 80) ? "":":".$_SERVER["SERVER_PORT"] ?><?php echo str_replace("/administrator/index.php","",$_SERVER["PHP_SELF"]); ?>';
		var active_cat=<?php echo $cat_id; ?>;
		var attCount=0;
		var msgAddAnImage = '<?php echo addslashes(JText::_( 'Add an image' )) ?>';
		var txtRemove = '<?php echo addslashes(JText::_( 'Remove' )) ?>';

		function addAtt() {
			attCount++;
			var newLi = document.createElement("LI");
			newLi.id="att"+attCount;
			newLi.style.marginRight="5px";
			newLi.style.position="relative";
			newLi.style.listStyleType="none";
			newLi.style.left="17px";
			var newFile=document.createElement("INPUT");
			newFile.className="text_area";
			newFile.name="image[]";
			newFile.type="file";
			newFile.size="28";
			newLi.appendChild(newFile);
			var newLink=document.createElement("A");
			newLink.href="javascript:remAtt("+attCount+")";
			removeText=document.createTextNode("<?php echo JText::_('Remove') ?>");
			newLink.appendChild(removeText);
			newLi.appendChild(newLink);
			gebid('upload_att').appendChild(newLi);
		}
		function remAtt(id) {gebid('upload_att').removeChild(gebid('att'+id));attCount--;}

		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancellink') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.link_name.value == ""){
				alert( "<?php echo addslashes(JText::_( 'Listing must have name' )) ?>" );
			<?php
			$requiredFields = array();
			
			
			/* Atención -> ¡Quitada la validación! */
			/* Los campos deberías poder filtrarse por categorías */
			
			
			/*$fields->resetPointer();
			while( $fields->hasNext() ) {
				$field = $fields->getField();
				if( ($field->isRequired() && !in_array($field->name,array('link_name','link_desc'))) || ($field->isRequired() && $mtconf->get('use_wysiwyg_editor_in_admin') == 0 && $field->name == 'link_desc') ) {
					if( $field->isFile() )
					{
						echo "\n";
						echo '} else if (';
						echo 'isEmpty(\'' . $field->getName() . '\')';
						echo ' && ';
						echo '(';
						echo '(typeof form.'.$field->getKeepFileName().' == \'undefined\')';
						echo '||';
						echo '(typeof form.'.$field->getKeepFileName().' == \'object\' && form.'.$field->getKeepFileName().'.checked == false)';
						echo ')';
						echo ') {'; 
						echo "\n";
						echo 'alert("' . addslashes(JText::_( 'Please complete this field' ) . $field->caption) . '");';
					}
					else
					{
						echo "\n";
						echo '} else if (isEmpty(\'' . $field->getName() . '\')) {'; 
						echo "\n";
						echo 'alert("' . addslashes(JText::_( 'Please complete this field' ) . $field->caption) . '");';
					}
				}
				if($field->hasJSValidation()) {
					echo "\n";
					echo $field->getJSValidation();
				}
				$fields->next();
			}*/
			?>
			} else {
				<?php
				if($mtconf->get('use_wysiwyg_editor_in_admin') == 1 && !is_null($fields->getFieldById(2))) {
					echo $editor->save( 'link_desc' );
				}
				?>
				var hash = jQuery("#sortableimages").sortable('serialize');
				if(hash != ''){document.adminForm.img_sort_hash.value=hash;}
				if(attCount>0 && checkImgExt(attCount,jQuery("input[@type=file][@name='image[]']"))==false) {
					alert('<?php echo addslashes(JText::_( 'Please select a jpg png or gif file for the images' )) ?>');
					return;
				} else {
					submitform(pressbutton);
				}
			}
		}
		</script>
	
	<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<center>
	<?php
		if ( $row->link_approved <= 0 ) {

			?>
			<table cellpadding="0" cellspacing="0" border="0" class="toolbar">
			<tr height="60" valign="middle" align="center">
			<?php

			if ( $number_of_prev > 0 ) {
			?>
			<td class="button">
				<a class="toolbar" href="javascript:submitbutton('prev_link');">
					<span class="icon-32-back" title="<?php echo JText::_( 'Previous' ) ?>"></span>
					<b> (<?php echo $number_of_prev ?>) <?php echo JText::_( 'Previous' ) ?></b>
				</a>
			</td>
			<?php
			} else {
			?>
			<td class="button">
				<span class="icon-32-back" title="<?php echo JText::_( 'Previous' ) ?>"></span>
				<b><font color="#C0C0C0"> (0) <?php echo JText::_( 'Previous' ) ?></font></b>
			</td>
			<?php
			}

			?>
			<td>
				<fieldset style="padding: 5px; border: 1px solid #c0c0c0">
					<input type="radio" name="act" id="act_ignore" value="ignore" checked="checked" /><label for="act_ignore"><?php echo JText::_( 'Ignore' ) ?></label>
					<input type="radio" name="act" id="act_approve" value="approve" /><label for="act_approve"><?php echo JText::_( 'Approve' ) ?></label>
					<input type="radio" name="act" id="act_discard" value="discard" /><label for="act_discard"><?php echo JText::_( 'Reject' ) ?></label>
				</fieldset>
			</td>
			<?php 

			if ( $number_of_next > 0 ) {
			?>
			<td class="button">
				<a class="toolbar" href="javascript:submitbutton('next_link');">
					<span class="icon-32-forward" title="<?php echo JText::_( 'Next' ) ?>"></span>
					<b><?php echo JText::_( 'Next' ) ?> (<?php echo $number_of_next ?>) </b>
				</a>
			</td>
			<?php
			} else {
			?>
			<td>
				<a class="toolbar" href="javascript:submitbutton('next_link');">
					<span class="icon-32-save" title="<?php echo JText::_( 'Save' ) ?>"></span>
					<b><?php echo JText::_( 'Save' ) ?> </b>
				</a>
			</td>
			<?php
			}
			?>
			</tr>
			</table>
			<?php
		}
	?>
	</center>

	<table width="100%"><tr>
		<th align="left" style="background: url(../components/com_mtree/img/dtree/folderopen.gif) no-repeat center left"><div style="margin-left: 18px"><?php echo $pathWay->printPathWayFromCat_withCurrentCat( $cat_id, 'index.php?option=com_mtree&task=listcats' ); ?></div></th>
	</tr></table>
	
	<table width="100%">
		<tr>
			<td valign="top">
				<fieldset>
					<legend><?php echo JText::_('Tem listing details'); ?></legend>
					<table class="admintable" width="100%">
						<tr valign="bottom">
							<td class="key" valign="top"><?php echo JText::_( 'Category' ) ?>:</td>
							<td>
								<ul class="linkcats" id="linkcats">
								<li id="lc<?php echo $cat_id; ?>"><?php echo $pathWay->printPathWayFromCat_withCurrentCat( $cat_id, '' ); ?></li>
								<?php
								if ( !empty($other_cats) ) {
									foreach( $other_cats AS $other_cat ) {
										if ( is_numeric( $other_cat ) ) {
											echo '<li id="lc' . $other_cat . '"><a href="javascript:remSecCat('.$other_cat.')">'.JText::_( 'Remove' ).'</a>'. $pathWay->printPathWayFromCat_withCurrentCat( $other_cat, '' ) . '</li>';
										}
									}
								}
								?>
								</ul>
								<a href="#" onclick="javascript:togglemc();return false;" id="lcmanage"><?php echo JText::_( 'Manage' ); ?></a>
								<div id="mc_con" style="display:none">
								<span id="mc_active_pathway" style="padding: 1px 0pt 1px 3px; background-color: white; width: 98%;position:relative;top:4px;height:13px;color:black"><?php echo $pathWay->printPathWayFromCat_withCurrentCat( $cat_id, '' ); ?></span>
								<?php echo $lists["cat_id"]; ?>
								<input type="button" class="button" value="<?php echo JText::_( 'Update category' ) ?>" id="mcbut1" onclick="updateMainCat()" />
								<input type="button" class="button" value="<?php echo JText::_( 'Also appear in this categories' ) ?>" id="mcbut2" onclick="addSecCat()"/>
								</div>
							</td>
						</tr>
						<?php
						$field_link_desc = $fields->getFieldById(2);
						$fields->resetPointer();
						while( $fields->hasNext() ) {
							$field = $fields->getField();
							if($field->hasInputField() && !in_array($field->name,array('metakey','metadesc'))) {
								echo '<tr>';
								echo '<td class="key" valign="top">';
								if($field->hasCaption()) {
									echo $field->getCaption();
									if($field->isRequired()) {
										echo '*';
									}
									echo ':';
								}
								echo '</td>';
								echo '<td>';
								echo $field->getModPrefixText();
								echo $field->getInputHTML();
								echo $field->getModSuffixText();
								echo '</td>';
								echo '</tr>';
							}
							$fields->next();
						}
						?>
					</table>
				</fieldset>
			</td>
			<td valign="top" width="350" style="padding: 0 0 0 5px">
				<fieldset>
					<legend><?php echo JText::_('Images'); ?></legend>
					<table class="admintable" width="100%">
						<tr>
							<td valign="top">
								<p style="color:#666"><?php echo JText::_( 'Drag to sort images, deselect checkbox to remove.' ); ?></p>

								<ul style="list-style-type: none; 
							margin: 10px 0 0 0;
							padding: 0;
							width: 350px;
							overflow: visible;" id="sortableimages"><?php
							foreach( $images AS $image ) {
								echo '<li style="
								position:relative;
								left:-13px;
								margin: 0 0 13px 0;
								padding: 0px; 
								float: left; 
								list-style-position: outside;
								line-height: 100%;" id="img_' . $image->img_id . '">';
								echo '<input style="position:relative;
								left: 20px;
								top: 10px;
								vertical-align: top;
								z-index: 1;
								margin: 0;
								padding: 0;" type="checkbox" name="keep_img[]" value="' . $image->img_id . '" checked />';
								echo '<a href="' . $mtconf->getjconf('live_site');
								switch( $mtconf->get('small_image_click_target_size','o') )
								{
									case 'm':
										echo $mtconf->get('relative_path_to_listing_medium_image');
										break;
									default:
									case 'o':
										echo $mtconf->get('relative_path_to_listing_original_image');
										break;
									case 's':
										echo $mtconf->get('relative_path_to_listing_small_image');
										break;
								}
								echo $image->filename . '" target="_blank">';
								echo '<img border="0" style="position:relative;border:1px solid black;" align="middle" src="' . $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_listing_small_image') . $image->filename . '" alt="' . $image->filename . '" />';
								echo '</a>';
								echo '</li>';
							}
							?>
							</ul>
							<ol id="upload_att" style="overflow:hidden;
							clear: both;
							list-style-type: none;
							margin: 0;
							padding: 0;">
							</ol>
							<div style="margin: 10px 0 10px 2px;">
							<a href="javascript:addAtt();" id="add_att"><?php echo JText::_( 'Add an image' ) ?></a>
							</div>
							</td>
						</tr>
					</table>
				</fieldset>
				
				<?php if( $mtconf->get( 'use_map' ) ) { ?>
				<fieldset>
					<legend><?php echo JText::_('Map'); ?></legend>
					<table width="100%" class="admintable">
						<tr>
							<td valign="top"><?php

								$width = '100%';
								$height = '200px';

								?>
								<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $mtconf->get('gmaps_api_key'); ?>" type="text/javascript"></script>
								<script type="text/javascript">
									var map = null;
								    var geocoder = null;
									var marker = null;
									var txtEnterAddress = '<?php echo JText::_( 'Enter an address and press Locate in map or move the red marker to the location in the map below.', true ); ?>';
									var txtLocateInMap = '<?php echo  JText::_( 'Locate in map', true ); ?>';
									var txtLocating = '<?php echo JText::_( 'Locating...', true ); ?>';
									var txtNotFound = '<?php echo JText::_( 'Not found:', true ); ?>';
									var txtNoAPIKey = '<?php echo JText::_( 'No API Key message', true ); ?>';
									var defaultCountry = '<?php echo addslashes($mtconf->get( 'map_default_country' )); ?>';
									var defaultState = '<?php echo addslashes($mtconf->get( 'map_default_state' )); ?>';
									var defaultCity = '<?php echo addslashes($mtconf->get( 'map_default_city' )); ?>';
									var defaultLat = '<?php echo addslashes($mtconf->get('map_default_lat')); ?>';
									var defaultLng = '<?php echo addslashes($mtconf->get('map_default_lng')); ?>';
									var defaultZoom = <?php echo addslashes($mtconf->get('map_default_zoom')); ?>;
									var linkValLat = '<?php echo $row->lat; ?>';
									var linkValLng = '<?php echo $row->lng; ?>';
									var linkValZoom = '<?php echo $row->zoom; ?>';
									var mapControl = [new <?php echo implode("(), new ",explode(',',$mtconf->get('map_control'))); ?>()];
								</script>
								<div id="mapContainer">
								<div style="padding:4px 0; width:95%"><input type="button" onclick="locateInMap()" value="<?php echo JText::_( 'Locate in map' ); ?>" name="locateButton" id="locateButton" /><span style="padding:0px; margin:3px" id="map-msg"></span></div>
								<div id="map" style="width:<?php echo $width; ?>;height:<?php echo $height; ?>"></div>
								</div>
								<input type="hidden" id="lat" name="lat" value="<?php echo $row->lat; ?>" />
								<input type="hidden" id="lng" name="lng" value="<?php echo $row->lng; ?>" />
								<input type="hidden" id="zoom" name="zoom" value="<?php echo $row->zoom; ?>" />
							</td>
						</tr>
					</table>
				</fieldset>
				<?php } 

				echo $pane->startPane("publishing-pane");
				echo $pane->startPanel( JText::_( 'Publishing' ), "publishing-page" );
				echo $form->render('publishing', 'publishing');

				echo $pane->endPanel();
				echo $pane->startPanel( JText::_( 'Parameters' ), "params-page" );
				echo $form->render('params', 'params');

				echo $pane->endPanel();
				echo $pane->startPanel( JText::_( 'Notes' ), "metadata-page" );
				echo $form->render('notes', 'notes');

				echo $pane->endPanel();
				echo $pane->endPane();

				?>
			</td>
		</tr>
	</table>

	<input type="hidden" name="img_sort_hash" value="" />
	<input type="hidden" name="link_id" value="<?php echo $row->link_id; ?>" />
	<input type="hidden" name="original_link_id" value="<?php echo $row->original_link_id; ?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="editlink" />
	<input type="hidden" name="returntask" value="<?php echo ($row->link_approved <= 0)?"listpending_links" : $returntask ?>" />
	<input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>" />
	<input type="hidden" name="other_cats" id="other_cats" value="<?php echo ( ( !empty($other_cats) ) ? implode(', ', $other_cats) : '' ) ?>" />
	</form>
	<?php
	}
	
	function move_links( $link_id, $cat_parent, $catList, $pathWay, $option ) {
		global $mtconf;
?>
<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site'); ?>/components/com_mtree/js/category.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_js_library'); ?>"></script>
<script language="javascript" type="text/javascript">
	jQuery.noConflict();
	var mosConfig_live_site=document.location.protocol+'//' + location.hostname + '<?php echo ($_SERVER["SERVER_PORT"] == 80) ? "":":".$_SERVER["SERVER_PORT"] ?><?php echo str_replace("/administrator/index.php","",$_SERVER["PHP_SELF"]); ?>';
	var active_cat=<?php echo $cat_parent; ?>;
	jQuery(document).ready(function(){
		jQuery('#browsecat').click(function(){
			cc(jQuery(this).val());
		});
	});
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancellinks_move') {
			submitform( pressbutton );
			return;
		}
		submitform( pressbutton );
	}
</script>

<form action="index.php" method="post" name="adminForm">

<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
	<tr>
		<td width="20%" align="right"><?php echo JText::_( 'Number of items' ) ?>:</td>
		<td align="left"><?php echo count( $link_id );?></td>
	</tr>
	<tr>
		<td align="right" valign="top"><?php echo JText::_( 'Current category' ) ?>:</td>
		<td align="left"><strong><?php echo $pathWay->printPathWayFromLink( 0, 'index.php?option=com_mtree&task=listcats' );?></strong></td>
	</tr>
	<tr>
		<td align="right" valign="top"><?php echo JText::_( 'Category' ) ?>:</td>
		<td align="left">
			<div id="mc_active_pathway" style="border: 1px solid #C0C0C0; padding: 1px 0pt 1px 3px;margin-bottom:4px; background-color: white; width: 30%;color:black"><?php echo $pathWay->printPathWayFromCat_withCurrentCat( $cat_parent, '' ); ?></div>
			<?php echo $catList;?>
		</td>
	</tr>
</table>

<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="new_cat_parent" value="<?php echo $cat_parent;?>" />
<input type="hidden" name="task" value="links_move" />
<input type="hidden" name="boxchecked" value="1" />
<?php
		foreach ($link_id as $id) {
			echo "\n<input type=\"hidden\" name=\"lid[]\" value=\"$id\" />";
		}
?>
</form>

<?php
	}

	function copy_links( $link_id, $cat_parent, $lists, $pathWay, $option ) {
		global $mtconf;
?>
<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site'); ?>/components/com_mtree/js/category.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_js_library'); ?>"></script>
<script language="javascript" type="text/javascript">
	jQuery.noConflict();
	var mosConfig_live_site=document.location.protocol+'//' + location.hostname + '<?php echo ($_SERVER["SERVER_PORT"] == 80) ? "":":".$_SERVER["SERVER_PORT"] ?><?php echo str_replace("/administrator/index.php","",$_SERVER["PHP_SELF"]); ?>';
	var active_cat=<?php echo $cat_parent; ?>;
	jQuery(document).ready(function(){
		jQuery('#browsecat').click(function(){
			cc(jQuery(this).val());
		});
	});
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancellinks_copy') {
			submitform( pressbutton );
			return;
		}
		submitform( pressbutton );
	}
</script>

<form action="index.php" method="post" name="adminForm">

<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
	<tr>
		<td width="20%" align="right"><?php echo JText::_( 'Number of items' ) ?>:</td>
		<td align="left"><?php echo count( $link_id );?></td>
	</tr>
	<tr>
		<td align="right" valign="top"><?php echo JText::_( 'Current category' ) ?>:</td>
		<td align="left"><strong><?php echo $pathWay->printPathWayFromLink( 0, 'index.php?option=com_mtree&task=listcats' );?></strong></td>
	</tr>
	<tr>
		<td align="right" valign="top"><?php echo JText::_( 'Copy to' ) ?>:</td>
		<td align="left">
		<div id="mc_active_pathway" style="border: 1px solid #C0C0C0; padding: 1px 0pt 1px 3px;margin-bottom:4px; background-color: white; width: 30%;color:black"><?php echo $pathWay->printPathWayFromCat_withCurrentCat( $cat_parent, '' ); ?></div>
		<?php echo $lists['cat_id'];?></td>
	</tr>

	<tr><td colspan="2" height="10px"></td></tr>

	<tr>
		<th colspan="2"><?php echo JText::_( 'Options' ) ?></th>
	</tr>
	<tr>
		<td align="right"><?php echo JText::_( 'Copy reviews' ) ?>:</td>
		<td align="left"><?php echo $lists['copy_reviews'] ;?></td>
	</tr>
	<tr>
		<td align="right"><?php echo JText::_( 'Copy secondary categories' ) ?>:</td>
		<td align="left"><?php echo $lists['copy_secondary_cats'] ;?></td>
	</tr>
	<tr>
		<td align="right"><?php echo JText::_( 'Reset hits' ) ?>:</td>
		<td align="left"><?php echo $lists['reset_hits'] ;?></td>
	</tr>
	<tr>
		<td align="right"><?php echo JText::_( 'Reset ratings and votes' ) ?>:</td>
		<td align="left"><?php echo $lists['reset_rating'] ;?></td>
	</tr>
</table>

<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="new_cat_parent" value="<?php echo $cat_parent;?>" />
<input type="hidden" name="task" value="links_copy" />
<input type="hidden" name="boxchecked" value="1" />
<?php
		foreach ($link_id as $id) {
			echo "\n<input type=\"hidden\" name=\"lid[]\" value=\"$id\" />";
		}
?>
</form>

<?php
	}

	/**
	* Category
	*/
	function listcats( &$rows, &$links, &$softlink_cat_ids, &$parent, &$pageNav, &$pathWay, $option ) {
		global $mtconf;

		$database	=& JFactory::getDBO();
		$nullDate	= $database->getNullDate();

		JHTML::_('behavior.tooltip');
		
		$max_char = 80;

		# Check if mt_pathway is published. If yes, do not use pathway here.
		$database->setQuery( "SELECT published FROM #__modules WHERE module = 'mod_mt_pathway' AND client_id='1'" );
		$modPathWayPublished = $database->loadResult();

		?>
		<script language="javascript" type="text/javascript">
			function link_listItemTask( id, task ) {
				var f = document.adminForm;
				lb = eval( 'f.' + id );
				if (lb) {
					lb.checked = true;
					submitbutton(task);
				}
				return false;
			}

			function link_isChecked(isitchecked){
				if (isitchecked == true){
					document.adminForm.link_boxchecked.value++;
				}
				else {
					document.adminForm.link_boxchecked.value--;
				}
			}

			function link_checkAll( n ) {
				var f = document.adminForm;
				var c = f.link_toggle.checked;
				var n2 = 0;
				for (i=0; i < n; i++) {
					lb = eval( 'f.lb' + i );
					if (lb) {
						lb.checked = c;
						n2++;
					}
				}
				if (c) {
					document.adminForm.link_boxchecked.value = n2;
				} else {
					document.adminForm.link_boxchecked.value = 0;
				}
			}

		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<script type="text/javascript" src="<?php echo $mtconf->getjconf('live_site');?>/includes/js/overlib_mini.js"></script>
		<script language="Javascript">
		<?php
		if ( $mtconf->get('admin_use_explorer') ) { ?>
		// Open Explorer
		d.openTo(<?php echo ( (isset($parent->cat_id)) ? $parent->cat_id : '0'); ?>, true);
		<?php } ?>
		function submitbutton_fastadd_cat() {
			var form = document.adminForm;
			form.cat_names.value = document.fastAddForm.cat_names.value;
			submitform('fastadd_cat');
			}
		</script>

		<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr><?php
				if ( !$modPathWayPublished ) {
				?><th align="left" style="background: url(../components/com_mtree/img/dtree/folderopen.gif) no-repeat center left"><div style="margin-left: 18px"><?php echo $pathWay->printPathWayFromLink( 0, 'index.php?option=com_mtree&task=listcats' ); ?></div></th>
				<?php } else {
					echo "<th> </th>"	;
				}?>
				<td align="right" nowrap>
					<a href="#fastadd" onclick="return overlib('<?php 
					$fastadd_html = "<div align=\'left\'>";
					$fastadd_html .= "<form name=\'fastAddForm\'>";
					$fastadd_html .= "<textarea class=\'text_area\' name=\'cat_names\' id=\'cat_names\' cols=\'21\' rows=\'5\' style=\'width:100%\'></textarea>";
					$fastadd_html .= "<br />";
					$fastadd_html .= "<input type=\'button\' value=\'".JText::_( 'Add' )."\' onClick=\'javascript:submitbutton_fastadd_cat();\' class=\'button\' />";
					$fastadd_html .= "</form>";
					$fastadd_html .= "</div>";
					echo $fastadd_html;
					?>', STICKY, CAPTION, '<?php echo JText::_( 'Enter one cat name perline' ) ?>', CAPCOLOR, '#000', CLOSECOLOR, '#ff6600', CELLPAD, 5, CENTER, BELOW, LEFT, OFFSETX, -20, FGCOLOR, '#ffffff', BGCOLOR, '#cccccc', WRAP, CLOSECLICK);"><?php echo JText::_( 'Fast add' ) ?></a></td>
			</tr>
		</table>

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
			<tr>
				<th align="right">
					<div style="width:40px; display:block; height: 100%;text-align:right;" >
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
					</div>
				</th>
				<th width="70%" align="left" style="text-align:left" nowrap="nowrap"><?php echo JText::_( 'Category' ) ?></th>
				<th width="5%"><?php echo JText::_( 'Categories' ) ?></th>
				<th width="5%"><?php echo JText::_( 'Listings' ) ?></th>
				<th width="10%"><?php echo JText::_( 'Featured' ) ?></th>
				<th width="10%"><?php echo JText::_( 'Published' ) ?></th>
			</tr>
			</thead>
<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i]; ?>
			<tr class="<?php echo "row$k"; ?>">
				<td><a href="index.php?option=com_mtree&amp;task=listcats&amp;cat_id=<?php echo $row->cat_id; ?>"><?php 
				if ($row->cat_image) {
					echo "<img border=\"0\" src=\"../components/com_mtree/img/dtree/imgfolder2.gif\" width=\"18\" height=\"18\" onmouseover=\"this.src='../components/com_mtree/img/dtree/imgfolder.gif'\" onmouseout=\"this.src='../components/com_mtree/img/dtree/imgfolder2.gif'; return nd(); \" />";
				} else {
					echo "<img border=\"0\" src=\"../components/com_mtree/img/dtree/folder.gif\" width=\"18\" height=\"18\" name=\"img".$i."\" onmouseover=\"this.src='../components/com_mtree/img/dtree/folderopen.gif'\" onmouseout=\"this.src='../components/com_mtree/img/dtree/folder.gif'\" />"; 
				}
				?></a><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->cat_id; ?>" onclick="isChecked(this.checked);" /></td>
				<td align="left"><a href="index.php?option=com_mtree&amp;task=editcat&amp;cat_id=<?php echo $row->cat_id; ?>"><?php echo htmlspecialchars($row->cat_name); ?></a></td>
				<td align="center"><?php echo $row->cat_cats; ?></td>
				<td align="center"><?php echo $row->cat_links; ?></td>
				<?php
				$task = $row->cat_featured ? 'cat_unfeatured' : 'cat_featured';
				$img = $row->cat_featured ? 'tick.png' : 'publish_x.png';
				?>
				<td align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a>
				</td>
				<?php
				$task = $row->cat_published ? 'cat_unpublish' : 'cat_publish';
				$img = $row->cat_published ? 'tick.png' : 'publish_x.png';
				?>
			  <td align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
			</tr>
			<?php		$k = 1 - $k; } ?>
			<tr style="background-color:#f0f0f0;">
				<th align="right" style="border-bottom:1px solid #999999;">
					<input type="checkbox" name="link_toggle" value="" onclick="link_checkAll(<?php echo count( $links ); ?>);" />
				</th>
				<th width="75%" colspan="2" style="text-align:left;border-bottom:1px solid #999999;" class="title" nowrap="nowrap"><?php echo JText::_( 'Listing' ) ?></th>
				<th width="5%" style="border-bottom:1px solid #999999;"><?php echo JText::_( 'Reviews' ) ?></th>
				<th width="10%" style="text-align:center;border-bottom:1px solid #999999;"><?php echo JText::_( 'Featured' ) ?></th>
				<th width="10%" style="text-align:center;border-bottom:1px solid #999999;"><?php echo JText::_( 'Published' ) ?></th>
			</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $links ); $i < $n; $i++) {
			$row = &$links[$i]; ?>
			<tr class="<?php echo "row$k"; ?>">
				<?php if ( $row->main == 1 ) { ?>
				<td><?php
				echo "<img src=\"../components/com_mtree/img/page_white.png\" width=\"16\" height=\"16\" />" ?><input type="checkbox" id="lb<?php echo $i;?>" name="lid[]" value="<?php echo $row->link_id; ?>" onclick="link_isChecked(this.checked);" /></td>
				<td colspan="2" align="left">
					<?php
					if ($row->internal_notes) {
						$intnotes = preg_replace('/\s+/', ' ', nl2br($row->internal_notes));
						echo JHTML::_('tooltip', $intnotes, '', 'messaging.png' );
					}
					?>
					<a href="index.php?option=com_mtree&amp;task=editlink&amp;link_id=<?php echo $row->link_id; ?>"><?php echo htmlspecialchars($row->link_name); ?></a>
				</td>
				<?php } else { ?>
				<td></td>
				<td colspan="2" align="left">
					<a href="index.php?option=com_mtree&task=listcats&cat_id=<?php echo $softlink_cat_ids[$row->link_id]->cat_id ?>"> <?php echo $pathWay->printPathWayFromLink( $row->link_id ); ?></a> <?php echo JText::_( 'Arrow' ) ?> <a href="index.php?option=com_mtree&task=editlink&link_id=<?php echo $row->link_id ?>"><?php echo htmlspecialchars($row->link_name); ?></a>
				</td>
				<?php } ?>
				<td align="center"><a href="index.php?option=com_mtree&task=reviews_list&link_id=<?php echo $row->link_id; ?>"><?php echo $row->reviews; ?></a></td>
				<?php
				$task = $row->link_featured ? 'link_unfeatured' : 'link_featured';
				$img = $row->link_featured ? 'tick.png' : 'publish_x.png';
				?>
			  <td align="center"><a href="javascript: void(0);" onclick="return listItemTask('lb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a>
				</td>
				<?php
				$jnow		=& JFactory::getDate();
				$now		= $jnow->toMySQL();

				if ( $now <= $row->publish_up && $row->link_published == "1" ) {
					$img = 'publish_y.png';
				} else if ( ( $now <= $row->publish_down || $row->publish_down == $nullDate ) && $row->link_published == "1" ) {
					$img = 'publish_g.png';
				} else if ( $now > $row->publish_down && $row->link_published == "1" ) {
					$img = 'publish_r.png';
				} elseif ( $row->link_published == "0" ) {
					$img = "publish_x.png";
				}
				$task = $row->link_published ? 'link_unpublish' : 'link_publish';

				?>
			  <td align="center"><a href="javascript: void(0);" onclick="return listItemTask('lb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a>
				</td>
			</tr><?php

				$k = 1 - $k;
			}
			?>

			<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="cat_parent" value="<?php echo $parent->cat_id; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="link_boxchecked" value="0" />
		<input type="hidden" name="cat_names" value="" />
		</form>
<?php
	}

	/**
	*
	* Writes the edit form for new and existing category
	*
	*/
	function editCat( &$row, $cat_parent, $related_cats, $browse_cat, &$lists, &$pathWay, $returntask, $option, $activetab=0, $template_all_subcats='' ) {
		global $mtconf;

		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'cat_desc' );
		
		jimport('joomla.html.pane');
		$pane	= &JPane::getInstance('sliders', array('allowAllClose' => true));
		$editor = &JFactory::getEditor();

		?>
		<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_js_library'); ?>"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site'); ?>/components/com_mtree/js/category.js"></script>
		<script language="javascript" type="text/javascript">
		jQuery.noConflict();
		var txtRemove = '<?php echo addslashes(JText::_( 'Remove' )) ?>';
		var mosConfig_live_site=document.location.protocol+'//' + location.hostname + '<?php echo ($_SERVER["SERVER_PORT"] == 80) ? "":":".$_SERVER["SERVER_PORT"] ?><?php echo str_replace("/administrator/index.php","",$_SERVER["PHP_SELF"]); ?>';
		var active_cat=<?php echo $row->cat_id; ?>;
		jQuery(document).ready(function(){
			toggleMcBut(active_cat);			
			jQuery('#browsecat').click(function(){
				cc(jQuery(this).val());
			});
		});
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancelcat' || pressbutton == 'editcat_add_relcat' || pressbutton == 'editcat_browse_cat') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if (form.cat_name.value == ""){
				alert( "<?php echo JText::_( 'Category must have name' ) ?>" );
			} else {
				<?php echo $editor->save( 'cat_desc' );	?>
				submitform( pressbutton );
			}
		}
		</script>
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
    	<tr><th colspan="5" align="left" style="background: url(../components/com_mtree/img/dtree/folderopen.gif) no-repeat center left"><div style="margin-left: 18px"><?php echo $pathWay->printPathWayFromLink( 0, 'index.php?option=com_mtree&task=listcats' ); ?></div></th></tr>
  		</table>

  	<table cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td width="60%" valign="top">

		<fieldset>
		<legend><?php echo JText::_('Category details'); ?></legend>

		<table cellpadding="4" cellspacing="1" border="0" width="100%" class="admintable">
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
			<tr>
				<td width="15%" align="right" class="key"><?php echo JText::_( 'Name' ) ?>:</td>
				<td width="85%" align="left">
					<input class="text_area" type="text" name="cat_name" size="50" maxlength="250" value="<?php echo $row->cat_name;?>" />
				</td>
			</tr>
			<tr>
				<td width="15%" align="right" class="key"><?php echo JText::_( 'Related Fields' ) ?>:</td>
				<td width="85%" align="left">
					<?php
					if ( !empty($lists['fields']) ) {
						foreach($lists['fields'] AS $field) {
							$checked="";
							foreach($lists['cats_x_fields'] AS $cxf)
							{ 
								if($cxf->id_customfield == $field->cf_id) 
								{
									$checked = "checked"; 
									break;
								}
							}
							echo '<input class="dtree_checkbox" '.$checked.' type="checkbox" name="related_fields[]" value="'.$field->cf_id.'" /> <span class="dtree_caption"	>'.$field->caption.'</span>';	
						}
					}
					?>
					
				</td>
			</tr>
			<tr valign="bottom">
				<td width="20%" align="right" valign="top" class="key"><?php echo JText::_( 'Related categories' ) ?>:</td>
				<td width="80%" align="left" colspan="2">
					<ul class="linkcats" id="linkcats">
					<li><input type="button" class="button" name="lcmanage" value="<?php echo JText::_( 'Add related categories' ); ?>" onclick="javascript:togglemc();return false;" /></li>
					<?php
					if ( !empty($related_cats) ) {
						foreach( $related_cats AS $related_cat ) {
							if ( is_numeric( $related_cat ) ) {
								echo '<li id="lc' . $related_cat . '"><a href="javascript:remSecCat('.$related_cat.')">'.JText::_( 'Remove' ).'</a>'. $pathWay->printPathWayFromCat_withCurrentCat( $related_cat, '' ) . '</li>';
							}
						}
					}
					?>
					</ul>
					<div id="mc_con" style="display:none">
					<div id="mc_active_pathway" style="border: 1px solid #C0C0C0; padding: 1px 0pt 1px 3px; background-color: white; width: 98%;position:relative;top:4px;height:13px;color:black"><?php echo $pathWay->printPathWayFromCat_withCurrentCat( $row->cat_id, '' ); ?></div>
					<?php echo $lists["new_related_cat"]; ?>
					<br />
					<input type="button" class="button" value="<?php echo JText::_( 'Add' ) ?>" id="mcbut1" onclick="addSecCat()"/>
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_( 'Description' ) ?>:</td>
				<td>
				<?php 
				echo $editor->display( 'cat_desc',  $row->cat_desc , '100%', '550', '75', '20' ) ;
				?></td>
			</tr>

			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_( 'Image' ) ?>:</td>
				<td valign="top" align="left">
					<input class="text_area" type="file" name="cat_image" />
					<?php if ($row->cat_image != "") { ?>
					<p />
					<img style="border: 5px solid #c0c0c0;" src="<?php echo $mtconf->getjconf('live_site').$mtconf->get('relative_path_to_cat_small_image') . $row->cat_image ?>" />
					<br />
					<input type="checkbox" name="remove_image" value="1" /> <?php echo JText::_( 'Remove this image' ) ?>
					<?php } ?>
				</td>
			</tr>
		</table>
		</fieldset>
			</td>
			<td valign="top" width="350" style="padding: 7px 0 0 5px"><?php
		
		jimport('joomla.html.pane');
		$pane	=& JPane::getInstance('sliders');
	
		echo $pane->startPane("content-pane");
		echo $pane->startPanel( JText::_( 'Publishing info' ), "publishing-page" );
		?>
		<table width="100%" class="paramlist admintable" cellspacing="1">
			<?php if ( $row->cat_approved == 0 || $row->cat_id == 0 ) { ?>
			<tr>
				<td valign="top" align="right" class="paramlist_key"><?php echo JText::_( 'Approved' ) ?>:</td>
				<td align="left" class="paramlist_value"><?php echo $lists['cat_approved'] ?></td>
			</tr>
			<?php } else { ?>
			<input type="hidden" name="cat_approved" value="1" />
			<?php } ?>
			<tr>
				<td valign="top" align="right" class="paramlist_key"><?php echo JText::_( 'Published' ) ?>:</td>
				<td align="left" class="paramlist_value"><?php echo $lists['cat_published'] ?></td>
			</tr>

			<tr>
				<td valign="top" align="right" class="paramlist_key"><?php echo JText::_( 'Featured' ) ?>:</td>
				<td align="left" class="paramlist_value"><?php echo $lists['cat_featured'] ?></td>
			</tr>

			<tr>
				<td valign="top" align="right" class="paramlist_key"><?php echo JText::_( 'Allow submission' ) ?>:</td>
				<td align="left" class="paramlist_value"><?php echo $lists['cat_allow_submission'] ?></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="paramlist_key"><?php echo JText::_( 'Show listings' ) ?>:</td>
				<td align="left" class="paramlist_value"><?php echo $lists['cat_show_listings'] ?></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="paramlist_key"><?php echo JText::_( 'Alias' ) ?>:</td>
				<td align="left" class="paramlist_value"><input type="text" name="alias" value="<?php echo $row->alias; ?>" class="inputbox" /></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="paramlist_key"><?php echo JText::_( 'Custom title' ) ?>:</td>
				<td align="left" class="paramlist_value"><input type="text" name="title" value="<?php echo $row->title; ?>" class="inputbox" /></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="paramlist_key"><?php echo JText::_( 'Template' ) ?>:</td>
				<td align="left" class="paramlist_value">
					<?php echo $lists['templates']; ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="paramlist_key">&nbsp;</td>
				<td align="left" class="paramlist_value">
					<input type="checkbox" name="template_all_subcats" value="1"<?php echo (($template_all_subcats == 1) ? ' checked="on"' : '' ) ?> /><?php echo JText::_( 'Change all subcats to this template' ) ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="paramlist_key">&nbsp;</td>
				<td align="left" class="paramlist_value">
					<?php echo JText::_( 'Use main index template page' ) ?><br />
				  <?php echo $lists['cat_usemainindex'] ?>
				</td>
			</tr>

			<tr>
				<td valign="top" class="paramlist_key"><?php echo JText::_( 'Meta keywords' ) ?>:</td>
				<td class="paramlist_value"><textarea class="text_area" cols="30" rows="3" style="width:210px; height:80px" name="metakey" width="500"><?php echo str_replace('&','&amp;',$row->metakey); ?></textarea>
				</td>
			</tr>

			<tr>
				<td valign="top" class="paramlist_key"><?php echo JText::_( 'Meta description' ) ?>:</td>
				<td class="paramlist_value"><textarea class="text_area" cols="30" rows="3" style="width:210px; height:80px" name="metadesc" width="500"><?php echo str_replace('&','&amp;',$row->metadesc); ?></textarea>
				</td>
			</tr>
		</table>
		<?php

		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'Operations' ), "operations-page" );

		?>
		<table width="100%" class="paramlist admintable" cellspacing="1">
			<tr>
				<th><?php echo JText::_( 'Full recount' ) ?></th>
			</tr>
			<tr>
				<td valign="top" align="left"><?php echo JText::_( 'Full recount explain' ) ?><p />&nbsp;<input type="button" class="button" value="<?php echo JText::_( 'Perform full recount' ) ?>" onClick="window.open('index3.php?option=com_mtree&task=fullrecount&hide=1&cat_id=<?php echo $row->cat_id ?>','recount','width=300,height=150')" /><br /></td>
			</tr>
			<tr>
				<th><?php echo JText::_( 'Fast recount' ) ?></th>
			</tr>
			<tr>
				<td valign="top" align="left"><?php echo JText::_( 'Fast recount explain' ) ?><p />&nbsp;<input type="button" class="button" value="<?php echo JText::_( 'Perform fast recount' ) ?>" onClick="window.open('index3.php?option=com_mtree&task=fastrecount&hide=1&cat_id=<?php echo $row->cat_id ?>','recount','width=300,height=150')" /><br /></td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();
		echo $pane->endPane();
		?>    
		</td>
	</tr>
</table>
		<?php 
		/* ?>
		<script type="text/javascript">
			tabPane1.setSelectedIndex( "<?php echo $activetab ?>");
		</script>
		<?php */ ?>
		<input type="hidden" name="cat_id" value="<?php echo $row->cat_id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="editcat" />
		<input type="hidden" name="returntask" value="<?php echo $returntask ?>" />
		<input type="hidden" name="cat_parent" value="<?php echo $cat_parent; ?>" />
		<input type="hidden" name="other_cats" id="other_cats" value="<?php echo ( ( !empty($related_cats) ) ? implode(', ', $related_cats) : '' ) ?>" />
		</form>
<?php
	}

	/***
	* Move Category
	*/
	function move_cats( $cat_id, $cat_parent, $catList, $pathWay, $option ) {
		global $mtconf;
?>
<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_js_library'); ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site'); ?>/components/com_mtree/js/category.js"></script>
<script language="javascript" type="text/javascript">
	jQuery.noConflict();
	var mosConfig_live_site=document.location.protocol+'//' + location.hostname + '<?php echo ($_SERVER["SERVER_PORT"] == 80) ? "":":".$_SERVER["SERVER_PORT"] ?><?php echo str_replace("/administrator/index.php","",$_SERVER["PHP_SELF"]); ?>';
	var active_cat=<?php echo $cat_id; ?>;
	jQuery(document).ready(function(){
		jQuery('#browsecat').click(function(){cc(jQuery(this).val());});
	});
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancelcats_move') {
			submitform( pressbutton );
			return;
		}
		submitform( pressbutton );
	}
</script>

<form action="index.php" method="post" name="adminForm">
<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
	<tr>
		<td width="20%" align="right"><?php echo JText::_( 'Number of items' ) ?>:</td>
		<td align="left"><?php echo count( $cat_id );?></td>
	</tr>
	<tr>
		<td align="right" valign="top"><?php echo JText::_( 'Current category' ) ?>:</td>
		<td align="left"><strong><?php echo $pathWay->printPathWayFromLink( 0, 'index.php?option=com_mtree&task=listcats' );?></strong></td>
	</tr>	
	<tr>
		<td align="right" valign="top"><?php echo JText::_( 'Move to' ) ?>:</td>
		<td align="left">
		<div id="mc_active_pathway" style="border: 1px solid #C0C0C0; padding: 1px 0pt 1px 3px;margin-bottom:4px; background-color: white; width: 40%;color:black"><?php echo $pathWay->printPathWayFromCat_withCurrentCat( $cat_parent, '' ); ?></div>
		<?php echo $catList;?></td>
	</tr>
</table>

<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="new_cat_parent" value="<?php echo $cat_parent;?>" />
<input type="hidden" name="task" value="cats_move" />
<input type="hidden" name="boxchecked" value="1" />
<?php
		foreach ($cat_id as $id) {
			echo "\n<input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
?>
</form>

<?php
	}
	
	/***
	* Copy Category
	*/
	function copy_cats( $cat_id, $cat_parent, $lists, $pathWay, $option ) {
		global $mtconf;
?>
<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site'); ?>/components/com_mtree/js/category.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_js_library'); ?>"></script>
<script language="javascript" type="text/javascript">
	jQuery.noConflict();
	var mosConfig_live_site=document.location.protocol+'//' + location.hostname + '<?php echo ($_SERVER["SERVER_PORT"] == 80) ? "":":".$_SERVER["SERVER_PORT"] ?><?php echo str_replace("/administrator/index.php","",$_SERVER["PHP_SELF"]); ?>';
	var active_cat=<?php echo $cat_id; ?>;
	jQuery(document).ready(function(){
		//toggleMcBut(active_cat);			
		jQuery('#browsecat').click(function(){
			cc(jQuery(this).val());
		});
	});
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancelcats_copy') {
			submitform( pressbutton );
			return;
		}
		submitform( pressbutton );
	}
</script>

<form action="index.php" method="post" name="adminForm">
<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
	<tr>
		<td width="17%" align="right"><?php echo JText::_( 'Number of items' ) ?>:</td>
		<td align="left"><?php echo count( $cat_id );?></td>
	</tr>
	<tr>
		<td align="right" valign="top"><?php echo JText::_( 'Current category' ) ?>:</td>
		<td align="left"><strong><?php echo $pathWay->printPathWayFromLink( 0, 'index.php?option=com_mtree&task=listcats' );?></strong></td>
	</tr>
	<tr>
		<td align="right" valign="top"><?php echo JText::_( 'Copy to' ) ?>:</td>
		<td align="left">
		<div id="mc_active_pathway" style="border: 1px solid #C0C0C0; padding: 1px 0pt 1px 3px;margin-bottom:4px; background-color: white; width: 40%;color:black"><?php echo $pathWay->printPathWayFromCat_withCurrentCat( $cat_parent, '' ); ?></div>
		<?php echo $lists['cat_id'] ;?></td>
	</tr>

	<tr><td colspan="2" height="10px"></td></tr>

	<tr>
		<th colspan="2"><?php echo JText::_( 'Options' ) ?></th>
	</tr>
	<tr>
		<td align="right"><?php echo JText::_( 'Copy subcats' ) ?>:</td>
		<td align="left"><?php echo $lists['copy_subcats'] ;?></td>
	</tr>	<tr>
		<td align="right"><?php echo JText::_( 'Copy relcats' ) ?>:</td>
		<td align="left"><?php echo $lists['copy_relcats'] ;?></td>
	</tr>
	<tr>
		<td align="right"><?php echo JText::_( 'Copy listings' ) ?>:</td>
		<td align="left"><?php echo $lists['copy_listings'] ;?></td>
	</tr>
	<tr>
		<td align="right"><?php echo JText::_( 'Copy reviews' ) ?>:</td>
		<td align="left"><?php echo $lists['copy_reviews'] ;?></td>
	</tr>
	<tr>
		<td align="right"><?php echo JText::_( 'Reset hits' ) ?>:</td>
		<td align="left"><?php echo $lists['reset_hits'] ;?></td>
	</tr>
	<tr>
		<td align="right"><?php echo JText::_( 'Reset ratings and votes' ) ?>:</td>
		<td align="left"><?php echo $lists['reset_rating'] ;?></td>
	</tr>
		
</table>

<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="new_cat_parent" value="<?php echo $cat_parent;?>" />
<input type="hidden" name="task" value="cats_copy" />
<input type="hidden" name="boxchecked" value="1" />
<?php
		foreach ($cat_id as $id) {
			echo "\n<input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
?>
</form>

<?php
	}

	function removecats( $categories, $cat_parent, $option ) {
	?>

		<strong><?php echo JText::_( 'Confirm delete cats' ) ?></strong>
		<p />

		<form action="index.php" method="post" name="adminForm">

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
			<tr>
				<th width="18px" nowrap="nowrap">&nbsp;</th>
				<th width="80%" nowrap="nowrap" style="text-align:left" align="left"><?php echo JText::_( 'Name' ) ?></th>
				<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Categories' ) ?></th>
				<th width="10%" nowrap="nowrap" align="center"><?php echo JText::_( 'Listings' ) ?></th>
			</tr>
			</thead>
		<?php
		$k = 0;
		for ($i=0, $n=count( $categories ); $i < $n; $i++) {
			$row = &$categories[$i]; ?>
			<tr class="<?php echo "row$k"; ?>" align="left">
				<td width="18px"><img src="../components/com_mtree/img/dtree/folder.gif" width="18" height="18" /><input type="hidden" name="cid[]" value="<?php echo $row->cat_id ?>" /></td>
				<td align="left" width="80%"><?php echo $row->cat_name; ?></td>
				<td><?php echo $row->cat_cats; ?></td>
				<td><?php echo $row->cat_links; ?></td>
			</tr>
			<?php		$k = 1 - $k; } ?>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="cat_parent" value="<?php echo $cat_parent;?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />

		</form>
	<?php

	}
	
	/***
	* Approval
	*/
	function listpending_links( $links, $pathWay, $pageNav, $option ) {
		JHTML::_('behavior.tooltip');
		?>
		<script language="javascript" type="text/javascript">
			function link_listItemTask( id, task ) {
				var f = document.adminForm;
				lb = eval( 'f.' + id );
				if (lb) {
					lb.checked = true;
					submitbutton(task);
				}
				return false;
			}

			function link_isChecked(isitchecked){
				if (isitchecked == true){
					document.adminForm.link_boxchecked.value++;
				}
				else {
					document.adminForm.link_boxchecked.value--;
				}
			}

			function link_checkAll( n ) {
				var f = document.adminForm;
				var c = f.link_toggle.checked;
				var n2 = 0;
				for (i=0; i < n; i++) {
					lb = eval( 'f.lb' + i );
					if (lb) {
						lb.checked = c;
						n2++;
					}
				}
				if (c) {
					document.adminForm.link_boxchecked.value = n2;
				} else {
					document.adminForm.link_boxchecked.value = 0;
				}
			}
		</script>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
			<tr>
				<th width="38" align="right">
					<input type="checkbox" name="link_toggle" value="" onclick="link_checkAll(<?php echo count( $links ); ?>);" />
				</th>
				<th class="title" width="30%" nowrap="nowrap"><?php echo JText::_( 'Listing' ) ?></th>
				<th width="50%" align="left" nowrap="nowrap"><?php echo JText::_( 'Category' ) ?></th>
				<th width="100"><?php echo JText::_( 'Created' ) ?></th>
			</tr>
			</thead>
		<?php
		$k = 0;
		for ($i=0, $n=count( $links ); $i < $n; $i++) {
			$row = &$links[$i]; ?>
			<tr class="<?php echo "row$k"; ?>" align="left">
				<td>
					<?php
					echo "<img src=\"../includes/js/ThemeOffice/document.png\" width=\"16\" height=\"16\">"; ?>
					<input type="checkbox" id="lb<?php echo $i;?>" name="lid[]" value="<?php echo $row->link_id; ?>" onclick="link_isChecked(this.checked);" />
				</td>
				<td><?php
					if ($row->internal_notes) {
						$intnotes = preg_replace('/\s+/', ' ', nl2br($row->internal_notes));
						echo JHTML::_('tooltip', $intnotes, '', 'messaging.png' );
						echo '&nbsp;';
					}
					echo (($row->link_approved < 0 ) ? '': '<b>' ); ?><a href="#edit" onclick="return link_listItemTask('lb<?php echo $i;?>','editlink_for_approval')"><?php echo $row->link_name; ?></a><?php echo (($row->link_approved < 0 ) ? '': '<b>' ); ?></td>
				<td><?php $pathWay->printPathWayFromLink( $row->link_id, '' ); ?></td>
				<td><?php echo tellDateTime($row->link_created); ?></td>
			</tr><?php

				$k = 1 - $k;
			}
			?>
			<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="listpending_links" />
		<input type="hidden" name="returntask" value="listpending_links" />
		<input type="hidden" name="link_boxchecked" value="0" />
		</form>
		<?php
	}

	function listpending_cats( $cats, $pathWay, $pageNav, $option ) {
		?>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
			<tr>
				<th width="44" align="right"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $cats ); ?>);" /></th>
				<th class="title" width="30%" nowrap="nowrap"><?php echo JText::_( 'Categories' ) ?></th>
				<th width="52%" align="left" nowrap="nowrap"><?php echo JText::_( 'Parent' ) ?></th>
				<th width="100"><?php echo JText::_( 'Created' ) ?></th>
			</tr>
			</thead>
		<?php
		$k = 0;
		for ($i=0, $n=count( $cats ); $i < $n; $i++) {
			$row = &$cats[$i]; ?>
			<tr class="<?php echo "row$k"; ?>" align="left">
				<td><a href="#go" onclick="return listItemTask('cb<?php echo $i;?>','listcats')"><?php 
					
				if ($row->cat_image) {
					echo "<img border=\"0\" src=\"../components/com_mtree/img/dtree/imgfolder2.gif\" width=\"18\" height=\"18\" onmouseover=\"showInfo('" .$row->cat_name ."', '".$row->cat_image."', 'cat'); this.src='../components/com_mtree/img/dtree/imgfolder.gif'\" onmouseout=\"this.src='../components/com_mtree/img/dtree/imgfolder2.gif'; return nd(); \" />";
				} else {
					echo "<img border=\"0\" src=\"../components/com_mtree/img/dtree/folder.gif\" width=\"18\" height=\"18\" name=\"img".$i."\" onmouseover=\"this.src='../components/com_mtree/img/dtree/folderopen.gif'\" onmouseout=\"this.src='../components/com_mtree/img/dtree/folder.gif'\" />"; 
				}
				?></a><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->cat_id; ?>" onclick="isChecked(this.checked);" />
				</td>
				<td><a href="#edit" onclick="return listItemTask('cb<?php echo $i;?>','editcat')"><?php echo $row->cat_name; ?></a></td>
				<td><?php echo $pathWay->printPathWayFromCat_withCurrentCat( $row->cat_parent, 0 ); ?></td>
				<td><?php echo tellDateTime($row->cat_created); ?></td>
			</tr><?php

				$k = 1 - $k;
			}
			?>

			<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="listpending_cats" />
		<input type="hidden" name="returntask" value="listpending_cats" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}

	function listpending_reviews( $reviews, $pathWay, $pageNav, $option ) {
		global $mtconf;
		require_once( $mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/spy.mtree.html.php' );
		?>
		<script language="javascript" type="text/javascript" src="<?php echo $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_js_library'); ?>"></script>
		<script language="javascript" type="text/javascript">
		jQuery.noConflict();
		var predefined_reply=new Array();
		<?php
		$num_of_predefined_reply=0;
		for ( $j=1; $j <= 5; $j++ )
		{ 
			if( $mtconf->get( 'predefined_reply_'.$j.'_title' ) <> '' && $mtconf->get( 'predefined_reply_'.$j.'_message' ) <> '') {
				echo 'predefined_reply['.$j.']="'.str_replace("'","\\'",str_replace('"','\\"',str_replace("\t","\\t",str_replace("\r\n","\\n",str_replace("\\","\\\\",$mtconf->get( 'predefined_reply_'.$j.'_message' ))))))."\";\n";
				$num_of_predefined_reply++;
			}
		}
		?>
		function selectreply(value,rev_id){
			jQuery('#emailmsg_'+rev_id).val( predefined_reply[value] );
		}
		function toggleemaileditor(rev_id){
			jQuery('#emaileditor_'+rev_id).slideToggle('fast');
		}
		</script>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<?php
		if ( count($reviews) <= 0 ) {
			?>
			<tr><th align="left">&nbsp;</th></tr>
			<tr class="row0"><td><?php echo JText::_( 'No review found' ) ?></td></tr>
			<?php
		} else {
			?>
			<thead>
			<tr>
				<td colspan="2">
					<div class="pagination">
					<?php echo $pageNav->getPagesLinks(); ?>
					<div class="limit"><?php echo $pageNav->getResultsCounter(); ?></div>
					</div>
				</td>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i=0, $n=count( $reviews ); $i < $n; $i++) {
				$row = &$reviews[$i]; ?>
				<thead><tr><th style="text-align:left" align="left"<?php echo ( ($mtconf->get('use_internal_notes')) ? ' colspan="2"': '' ) ?>><?php 
					echo mtfHTML::rating($row->value);
				?>&nbsp;<a href="index.php?option=com_mtree&amp;task=editlink&amp;link_id=<?php echo $row->link_id; ?>"><?php echo $row->link_name ?></a> by <?php
				if($row->user_id > 0) {
					echo '<a href="index.php?option=com_mtree&task=spy&task2=viewuser&id='.$row->user_id.'">' . $row->username . '</a>';
				} elseif(!empty($row->email)) {
					echo '<a href="mailto:' . $row->email . '">' . $row->guest_name . '</a>';
				} else {
					echo $row->guest_name;
				}
				?>, <?php echo $row->rev_date ?> - <a href="<?php echo $mtconf->getjconf('live_site'). "/index.php?option=com_mtree&task=viewlink&link_id=$row->link_id"; ?>" target="_blank"><?php echo JText::_( 'View listing' ) ?></a></th></tr></thead>
				<tr align="left">
					<td<?php echo ( ($mtconf->get('use_internal_notes')) ? ' width="65%"': '' ) ?> valign="top" style="border-bottom:0px"><?php echo JText::_( 'Review title' ) ?>: <input class="text_area" type="text" name="rev_title[<?php echo $row->rev_id; ?>]" value="<?php echo htmlspecialchars($row->rev_title); ?>" size="60" /></td>
					<?php if ( $mtconf->get('use_internal_notes') ) { ?><td valign="middle" width="35%" style="border-bottom:0px"><?php echo JText::_( 'Internal notes' ) ?>:</td><?php } ?>
				</tr>
				<tr align="left">
					<td<?php echo ( ($mtconf->get('use_internal_notes')) ? ' width="65%"': '' ) ?>>
						<textarea class="text_area" style="width:100%;height:150px" name="rev_text[<?php echo $row->rev_id ?>]"><?php echo htmlspecialchars($row->rev_text) ?></textarea>
						<p />
						<label for="app_<?php echo $row->rev_id ?>"><input type="radio" name="rev[<?php echo $row->rev_id ?>]" value="1" id="app_<?php echo $row->rev_id ?>" /><?php echo JText::_( 'Approve' ) ?></label>
						<label for="ign_<?php echo $row->rev_id ?>"><input type="radio" name="rev[<?php echo $row->rev_id ?>]" value="0" id="ign_<?php echo $row->rev_id ?>" checked="checked" /><?php echo JText::_( 'Ignore' ) ?></label>
						<label for="rej_<?php echo $row->rev_id ?>"><input type="radio" name="rev[<?php echo $row->rev_id ?>]" value="-1" id="rej_<?php echo $row->rev_id ?>" /><?php echo JText::_( 'Reject' ) ?></label>
						<?php if($row->value > 0) { ?>
						<label for="rejrv_<?php echo $row->rev_id ?>"><input type="radio" name="rev[<?php echo $row->rev_id ?>]" value="-2" id="rejrv_<?php echo $row->rev_id ?>" /><?php echo JText::_( 'Reject and remove vote' ) ?></label>
						<?php } 
						
						if( !empty($row->email) ) {
						?>						
						<span style="margin-top:2px;display:block;clear:left;"><input type="checkbox"<?php echo (($row->send_email)?' checked':''); ?> name="sendemail[<?php echo $row->rev_id ?>]" value="1" id="sendemail_<?php echo $row->rev_id ?>" onclick="toggleemaileditor(<?php echo $row->rev_id ?>)" /> <label for="sendemail_<?php echo $row->rev_id ?>"><?php echo JText::_( 'Send email to reviewer upon approval or rejection' ) ?></label></span>
						<div id="emaileditor_<?php echo $row->rev_id ?>"<?php echo ((!$row->send_email)?' style="display:none"':''); ?>>
							<select onchange="selectreply(this.value,<?php echo $row->rev_id ?>)"<?php echo (($num_of_predefined_reply==0)?' disabled':''); ?>>
								<option><?php echo JText::_( 'Select a pre defined reply' ) ?></option>
								<?php
								for ( $k=1; $k <= 5; $k++ )
								{ 
									if( $mtconf->get( 'predefined_reply_'.$k.'_title' ) <> '') {
										echo '<option value="'.$k.'">'.$mtconf->get( 'predefined_reply_'.$k.'_title' ).'</option>';
									}
								}
								?>
							</select>&nbsp;<?php echo JText::_( 'Or enter the email message' ) ?>
							<p />
							<textarea name="emailmsg[<?php echo $row->rev_id ?>]" id="emailmsg_<?php echo $row->rev_id ?>" class="text_area" style="width:100%;height:110px"><?php echo $row->email_message ?></textarea>
						</div>
						<?php } ?>
					</td>
					<td valign="top"><textarea class="text_area" style="width:100%;height:150px;" name="admin_note[<?php echo $row->rev_id ?>]"><?php echo htmlspecialchars($row->admin_note) ?></textarea></td>
				</tr>				
				<?php		$k = 1 - $k; } 
				
			} ?>
			<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="listpending_reviews" />
		<input type="hidden" name="returntask" value="listpending_reviews" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}

	function listpending_reports( $reports, $pathWay, $pageNav, $option ) {
		global $mtconf;
		?>
		<form action="index.php" method="post" name="adminForm">

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<?php
		if ( count($reports) <= 0 ) {
			?>
			<tr><th align="left">&nbsp;</th></tr>
			<tr class="row0"><td><?php echo JText::_( 'No report found' ) ?></td></tr>
			<?php
		} else {
			?>
			<thead>
			<tr>
				<td colspan="2">
					<div class="pagination">
					<?php echo $pageNav->getPagesLinks(); ?>
					<div class="limit"><?php echo $pageNav->getResultsCounter(); ?></div>
					</div>
				</td>
			</tr>
			</thead>
			<?php
		$k = 0;
		for ($i=0, $n=count( $reports ); $i < $n; $i++) {
			$row = &$reports[$i]; ?>
			<thead><tr><th style="text-align:left" align="left"<?php echo ( ($mtconf->get('use_internal_notes')) ? ' colspan="2"': '' ) ?>><a href="index.php?option=com_mtree&task=editlink&link_id=<?php echo $row->link_id; ?>"><?php echo $row->link_name ?></a> - <a href="<?php echo $mtconf->getjconf('live_site') . "/index.php?option=com_mtree&task=viewlink&link_id=$row->link_id"; ?>" target="_blank"><?php echo JText::_( 'View listing' ) ?></a></th></tr></thead>
			<tr align="left">
				<td<?php echo ( ($mtconf->get('use_internal_notes')) ? ' width="65%"': '' ) ?> valign="top">
					<u><?php echo $row->subject . "</u>, " . ( (empty($row->username))? $row->guest_name : '<a href="index.php?option=com_mtree&task=spy&task2=viewuser&id='.$row->user_id.'">' . $row->username . '</a> ' ) ." ". $row->created ?>
					<p />
					<?php echo nl2br($row->comment) ?>
					<p />
					<label for="res_<?php echo $row->report_id ?>"><input type="radio" name="report[<?php echo $row->report_id ?>]" value="1" id="res_<?php echo $row->report_id ?>" /><?php echo JText::_( 'Resolved' ) ?></label>

					<label for="ign_<?php echo $row->report_id ?>"><input type="radio" name="report[<?php echo $row->report_id ?>]" value="0" id="ign_<?php echo $row->report_id ?>" checked="checked" /><?php echo JText::_( 'Ignore' ) ?></label>
				</td>
				<?php if( $mtconf->get('use_internal_notes') ) { ?>
				<td style="height:100px;" valign="top" width="35%">
				<?php echo JText::_( 'Internal notes' ) ?>:<br />
				<textarea class="text_area" style="width:100%;height:80px;" name="admin_note[<?php echo $row->report_id ?>]"><?php echo htmlspecialchars($row->admin_note) ?></textarea>
				</td>
				<?php } ?>
			</tr>
			<?php		$k = 1 - $k; } } ?>
			<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="listpending_reports" />
		</form>
		<?php
	}

	function listpending_reviewsreports( $reports, $pathWay, $pageNav, $option ) {
		global $mtconf;
		?>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<?php
		if ( count($reports) <= 0 ) {
			?>
			<tr><th align="left">&nbsp;</th></tr>
			<tr class="row0"><td><?php echo JText::_( 'No report found' ) ?></td></tr>
			<?php
		} else {
		?>
		<thead>
		<tr>
			<td<?php echo ( ($mtconf->get('use_internal_notes')) ? ' colspan="2"': '' ) ?>>
				<div class="pagination">
				<?php echo $pageNav->getPagesLinks(); ?>
				<div class="limit"><?php echo $pageNav->getResultsCounter(); ?></div>
				</div>
			</td>
		</tr>
		</thead>
		<?php
		$k = 0;
		for ($i=0, $n=count( $reports ); $i < $n; $i++) {
			$row = &$reports[$i]; ?>
			<thead><tr><th style="text-align:left" align="left"<?php echo ( ($mtconf->get('use_internal_notes')) ? ' colspan="2"': '' ) ?>><a href="<?php echo $mtconf->getjconf('live_site') . "/index.php?option=com_mtree&task=viewlink&link_id=$row->link_id"; ?>" target="_blank"><?php echo $row->link_name ?></a></th></tr></thead>
			<tr align="left">
				<td<?php echo ( ($mtconf->get('use_internal_notes')) ? ' width="65%"': '' ) ?>>
					<blockquote style="margin:3px 0 10px 2px;background-color:#F3F3F3;padding:6px;border: 1px solid #e1e1e1;border-left:6px solid #E1E1E1;">
					<?php echo '<strong>' . $row->rev_title . '</strong>';
					echo ' - <a href="index.php?option=com_mtree&task=editreview&rid=' . $row->rev_id . '">' . JText::_( 'Edit review' ) . '</a>';
					 echo '<br />' . JText::_( 'Reviewed by' ) . ' <a href="index.php?option=com_mtree&task=spy&task2=viewuser&id='.$row->review_user_id.'">' . $row->review_username . '</a>, ' . $row->rev_date ?>
					<p />
					<?php echo nl2br($row->rev_text); ?>
					</blockquote>
					<?php echo '</pre>'; echo ( (empty($row->username))? $row->guest_name : '<a href="index.php?option=com_mtree&task=spy&task2=viewuser&id='.$row->user_id.'">'.$row->username."</a> " ) ." ". $row->created ?>
					<p />
					<?php echo nl2br($row->comment) ?>
					<p />
					<label for="res_<?php echo $row->report_id ?>"><input type="radio" name="report[<?php echo $row->report_id ?>]" value="1" id="res_<?php echo $row->report_id ?>" /><?php echo JText::_( 'Resolved' ) ?></label>

					<label for="ign_<?php echo $row->report_id ?>"><input type="radio" name="report[<?php echo $row->report_id ?>]" value="0" id="ign_<?php echo $row->report_id ?>" checked="checked" /><?php echo JText::_( 'Ignore' ) ?></label>
				</td>
				<?php if( $mtconf->get('use_internal_notes') ) { ?>
				<td style="height:100px;" valign="top" width="35%">
				<?php echo JText::_( 'Internal notes' ) ?>:<br />
				<textarea class="text_area" style="width:100%;height:200px" name="admin_note[<?php echo $row->report_id ?>]"><?php echo htmlspecialchars($row->admin_note) ?></textarea>
				</td>
				<?php } ?>
			</tr>
			<?php		$k = 1 - $k; } } ?>
			<tfoot>
			<tr>
				<td colspan="4">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="listpending_reviewsreports" />
		</form>
		<?php
	}

	function listpending_reviewsreply( $reviewsreply, $pathWay, $option ) {
		global $mtconf;
		?>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<?php
		if ( count($reviewsreply) <= 0 ) {
			?>
			<tr><th align="left">&nbsp;</th></tr>
			<tr class="row0"><td><?php echo JText::_( 'No reply found' ) ?></td></tr>
			<?php
		} else {

		$k = 0;
		for ($i=0, $n=count( $reviewsreply ); $i < $n; $i++) {
			$row = &$reviewsreply[$i]; ?>
			<thead><tr><th style="text-align:left" align="left"<?php echo ( ($mtconf->get('use_internal_notes')) ? ' colspan="2"': '' ) ?>><a href="<?php echo $mtconf->getjconf('live_site') . "/index.php?option=com_mtree&task=viewlink&link_id=$row->link_id"; ?>" target="_blank"><?php echo $row->link_name ?></a></th></tr></thead>
			<tr align="left">
				<td<?php echo ( ($mtconf->get('use_internal_notes')) ? ' width="65%"': '' ) ?>>
					<blockquote style="margin:3px 0 10px 2px;background-color:#F3F3F3;padding:6px;border: 1px solid #e1e1e1;border-left:6px solid #E1E1E1;">
					<?php echo '<strong>' . $row->rev_title . '</strong>';
					echo ' - <a href="index.php?option=com_mtree&task=editreview&rid=' . $row->rev_id . '">' . JText::_( 'Edit review' ) . '</a>';
					echo '<br />' . JText::_( 'Reviewed by' ) . ' <a href="index.php?option=com_mtree&task=spy&task2=viewuser&id='.$row->user_id.'">' . $row->username . '</a>, ' . $row->rev_date ?>
					<p />
					<?php echo nl2br($row->rev_text); ?>
					</blockquote>
					<?php 
						if( !empty($row->owner_username) ) {
							echo '<a href="index.php?option=com_mtree&task=spy&task2=viewuser&id='.$row->owner_user_id.'">'.$row->owner_username."</a>  ";
						}
						echo $row->ownersreply_date;
					?>
					<p />
					<textarea class="text_area" style="width:100%;height:150px" name="or_text[<?php echo $row->rev_id ?>]"><?php echo htmlspecialchars($row->ownersreply_text) ?></textarea>
					<p />

					<label for="app_<?php echo $row->rev_id ?>"><input type="radio" name="or[<?php echo $row->rev_id ?>]" value="1" id="app_<?php echo $row->rev_id ?>" /><?php echo JText::_( 'Approve' ) ?></label>
					<label for="ign_<?php echo $row->rev_id ?>"><input type="radio" name="or[<?php echo $row->rev_id ?>]" value="0" id="ign_<?php echo $row->rev_id ?>" checked="checked" /><?php echo JText::_( 'Ignore' ) ?></label>
					<label for="rej_<?php echo $row->rev_id ?>"><input type="radio" name="or[<?php echo $row->rev_id ?>]" value="-1" id="rej_<?php echo $row->rev_id ?>" /><?php echo JText::_( 'Reject' ) ?></label>
				</td>
				<?php if( $mtconf->get('use_internal_notes') ) { ?>
				<td style="height:100px;" valign="top" width="35%">
				<?php echo JText::_( 'Internal notes' ) ?>:<br />
				<textarea class="text_area" style="width:100%;height:200px" name="admin_note[<?php echo $row->rev_id ?>]"><?php echo htmlspecialchars($row->ownersreply_admin_note) ?></textarea>
				</td>
				<?php } ?>
			</tr>
			<?php		$k = 1 - $k; } } ?>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="save_reviewsreply" />
		</form>
		<?php
	}

	function listpending_claims( $claims, $pathWay, $option ) {
		global $mtconf;
		?>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<?php
		if ( count($claims) <= 0 ) {
			?>
			<tr><th align="left">&nbsp;</th></tr>
			<tr class="row0"><td><?php echo JText::_( 'No claim found' ) ?></td></tr>
			<?php
		} else {

		$k = 0;
		for ($i=0, $n=count( $claims ); $i < $n; $i++) {
			$row = &$claims[$i]; ?>
			<thead><tr><th style="text-align:left" align="left"<?php echo ( ($mtconf->get('use_internal_notes')) ? ' colspan="2"': '' ) ?>><a href="index.php?option=com_mtree&task=editlink&link_id=<?php echo $row->link_id; ?>"><?php echo $row->link_name ?></a> by <a href="mailto:<?php echo $row->email ?>"><?php echo $row->name ?></a> (<?php echo $row->username ?>), <?php echo $row->created ?> - <a href="<?php echo $mtconf->getjconf('live_site') . "/index.php?option=com_mtree&task=viewlink&link_id=$row->link_id"; ?>" target="_blank"><?php echo JText::_( 'View listing' ) ?></a></th></tr></thead>
			<tr align="left">
				<td <?php echo ( ($mtconf->get('use_internal_notes')) ? 'width="65%" ': '' ) ?>valign="top">
					<?php echo nl2br(htmlspecialchars($row->comment)) ?>
					<p />
					<label for="app_<?php echo $row->claim_id ?>"><input type="radio" name="claim[<?php echo $row->claim_id ?>]" value="<?php echo $row->user_id ?>" id="app_<?php echo $row->claim_id ?>" /><?php echo JText::_( 'Approve' ) ?></label>
					<label for="ign_<?php echo $row->claim_id ?>"><input type="radio" name="claim[<?php echo $row->claim_id ?>]" value="0" id="ign_<?php echo $row->claim_id ?>" checked="checked" /><?php echo JText::_( 'Ignore' ) ?></label>
					<label for="rej_<?php echo $row->claim_id ?>"><input type="radio" name="claim[<?php echo $row->claim_id ?>]" value="-1" id="rej_<?php echo $row->claim_id ?>" /><?php echo JText::_( 'Reject' ) ?></label>
				</td>
				<?php if ( $mtconf->get('use_internal_notes') ) { ?>
				<td style="height:100px;" valign="top" width="35%">
				<?php echo JText::_( 'Internal notes' ) ?>:<br />
				<textarea style="width:100%;height:100%" name="admin_note[<?php echo $row->claim_id ?>]"><?php echo htmlspecialchars($row->admin_note) ?></textarea>
				</td>
				<?php } ?>
			</tr>
			<?php		$k = 1 - $k; } } ?>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="save_claims" />
		</form>
		<?php
	}

	/***
	* Reviews
	*/
	function list_reviews( &$reviews, &$link, &$pathWay, &$pageNav, $option ) {
	?>
		<form action="index.php" method="post" name="adminForm">
		
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr>
				<th width="100%" align="left" style="background: url(../components/com_mtree/img/dtree/folderopen.gif) no-repeat center left"><div style="margin-left: 18px"><?php echo $pathWay->printPathWayFromLink( $link->link_id, 'index.php?option=com_mtree&task=listcats' ); ?></div></th>
			</tr>
			<tr>
				<th colspan="5" style="text-align:left"><?php echo $link->link_name; ?></th>
			</tr>
	  </table>

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $reviews ); ?>);" />
				</th>
				<th width="60%" style="text-align:left" align="left" nowrap="nowrap"><?php echo JText::_( 'Review title' ) ?></th>
				<th width="15%" style="text-align:left" nowrap="nowrap"><?php echo JText::_( 'User' ) ?></th>
				<th width="10%"><?php echo JText::_( 'Helpfuls' ) ?></th>
				<th width="15%"><?php echo JText::_( 'Created' ) ?></th>
			</tr>
			</thead>
<?php
		$k = 0;
		for ($i=0, $n=count( $reviews ); $i < $n; $i++) {
			$row = &$reviews[$i]; ?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20">
					<input type="checkbox" id="cb<?php echo $i;?>" name="rid[]" value="<?php echo $row->rev_id; ?>" onclick="isChecked(this.checked);" />
				</td>
				<td align="left"><a href="#edit" onclick="return listItemTask('cb<?php echo $i;?>','editreview')"><?php echo $row->rev_title; ?></a></td>
				<td align="left"><?php echo (($row->user_id) ? $row->username : $row->guest_name); ?></td>
				<td align="center"><?php if( $row->vote_total > 0 ) { 
					echo $row->vote_helpful.' '.JText::_( 'Of' ).' '.$row->vote_total; 
				} else {
					echo '-';
				}
				?></td>
				<td align="center"><?php echo $row->rev_date; ?></td>
			</tr><?php

				$k = 1 - $k;
			}
			?>

			<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="reviews_list" />
		<input type="hidden" name="link_id" value="<?php echo $link->link_id; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}

	function editreview( &$row, &$pathWay, $returntask, $lists, $option ) {

		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'rev_text' );
		JHTML::_( 'behavior.calendar' );
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancelreview') {
				submitform( pressbutton );
				return;
			}
			if (form.rev_text.value == ""){
				alert( "<?php echo JText::_( 'Please enter review text' ) ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		</script>
		
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr>
				<th align="left" style="background: url(../components/com_mtree/img/dtree/folderopen.gif) no-repeat center left"><div style="margin-left: 18px"><?php echo $pathWay->printPathWayFromLink( $row->link_id, 'index.php?option=com_mtree&task=listcats' ); ?></div></th>
			</tr>
	  </table>

		<fieldset>
		
		<table cellpadding="4" cellspacing="1" border="0" width="100%" class="admintable">
		<form action="index.php" method="post" name="adminForm" id="adminForm">
			<tr>
				<td width="15%" align="right" class="key">User:</td>
				<td width="85%" align="left">
					<input class="text_area" type="text" name="owner" size="20" maxlength="250" value="<?php echo (($row->not_registered) ? $row->guest_name : $row->owner );?>" /> <input type="checkbox" name="not_registered" id="not_registered" value="1" <?php echo (($row->not_registered) ? 'checked ' : '' ); ?>/> <label for="not_registered"><?php echo JText::_( 'This is not a registered user' ) ?></label>
				</td>
			</tr>
			<tr>
				<td width="15%" align="right" class="key"><?php echo JText::_( 'Review title' ) ?>:</td>
				<td width="85%" align="left">
					<input class="text_area" type="text" name="rev_title" size="60" maxlength="250" value="<?php echo $row->rev_title;?>" />
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_( 'Review' ) ?>:</td>
				<td align="left"><textarea name="rev_text" cols="70" rows="15" class="text_area"><?php echo $row->rev_text; ?></textarea></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_( 'Approved' ) ?>:</td>
				<td align="left"><?php echo $lists['rev_approved'] ?></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_( 'Helpfuls' ) ?>:</td>
				<td align="left"><input class="text_area" type="text" name="vote_helpful" size="3" maxlength="4" value="<?php echo $row->vote_helpful;?>" /> <?php echo JText::_( 'Of' ) ?> <input class="text_area" type="text" name="vote_total" size="3" maxlength="4" value="<?php echo $row->vote_total;?>" /></td>
			</tr>
			<tr>
			  <td valign="top" align="right" class="key"><?php echo JText::_( 'Override created date' ) ?> </td>
			  <td align="left"><?php echo JHTML::_('calendar', $row->rev_date, 'rev_date', 'rev_date', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_( 'Owners reply' ) ?>:</td>
				<td align="left"><textarea name="ownersreply_text" cols="70" rows="8" class="text_area"><?php echo $row->ownersreply_text; ?></textarea></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_( 'Approved' ) ?>:</td>
				<td align="left"><?php echo $lists['ownersreply_approved'] ?></td>
			</tr>
		</table>
		
		</fieldset>
		<input type="hidden" name="rev_id" value="<?php echo $row->rev_id; ?>" />
		<input type="hidden" name="link_id" value="<?php echo $row->link_id; ?>" />
		<input type="hidden" name="returntask" value="<?php echo $returntask ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
<?php
	}

	/***
	* Search
	*/
	function searchresults_links( &$links, &$pageNav, &$pathWay, $search_where, $search_text, $option ) {
		$database	=& JFactory::getDBO();
		$nullDate	= $database->getNullDate();
	?>
		<script language="javascript" type="text/javascript">
			function link_listItemTask( id, task ) {
				var f = document.adminForm;
				lb = eval( 'f.' + id );
				if (lb) {
					lb.checked = true;
					submitbutton(task);
				}
				return false;
			}

			function link_isChecked(isitchecked){
				if (isitchecked == true){
					document.adminForm.link_boxchecked.value++;
				}
				else {
					document.adminForm.link_boxchecked.value--;
				}
			}

			function link_checkAll( n ) {
				var f = document.adminForm;
				var c = f.link_toggle.checked;
				var n2 = 0;
				for (i=0; i < n; i++) {
					lb = eval( 'f.lb' + i );
					if (lb) {
						lb.checked = c;
						n2++;
					}
				}
				if (c) {
					document.adminForm.link_boxchecked.value = n2;
				} else {
					document.adminForm.link_boxchecked.value = 0;
				}
			}

		</script>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
				<th width="20">
					<input type="checkbox" name="link_toggle" value="" onclick="link_checkAll(<?php echo count( $links ); ?>);" />
				</th>
				<th class="title" width="20%" nowrap="nowrap"><?php echo JText::_( 'Listing' ) ?></th>
				<th width="65%" align="left" nowrap="nowrap"><?php echo JText::_( 'Category' ) ?></th>
				<th width="100"><?php echo JText::_( 'Reviews' ) ?></th>
				<th><?php echo JText::_( 'Featured' ) ?></th>
				<th><?php echo JText::_( 'Published' ) ?></th>
			</thead>
<?php
		$k = 0;
		for ($i=0, $n=count( $links ); $i < $n; $i++) {
			$row = &$links[$i]; ?>
			<tr class="<?php echo "row$k"; ?>" align="left">
				<td width="20">
					<input type="checkbox" id="lb<?php echo $i;?>" name="lid[]" value="<?php echo $row->link_id; ?>" onclick="link_isChecked(this.checked);" />
				</td>
				<td><a href="index.php?option=com_mtree&amp;task=editlink&amp;link_id=<?php echo $row->link_id; ?>"><?php echo htmlspecialchars($row->link_name); ?></a></td>
				<td><?php echo $pathWay->printPathWayFromLink( $row->link_id, '' ); ?></td>
				<td align="center"><a href="index.php?option=com_mtree&task=reviews_list&link_id=<?php echo $row->link_id; ?>"><?php echo $row->reviews; ?></a></td>
				<?php
				$task = $row->link_featured ? 'link_unfeatured' : 'link_featured';
				$img = $row->link_featured ? 'tick.png' : 'publish_x.png';
				?>
			  <td width="10%" align="center"><a href="javascript: void(0);" onclick="return link_listItemTask('lb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a>
				</td>
				<?php
				$jdate = JFactory::getDate();
				$now = $jdate->toMySQL();

				if ( $now <= $row->publish_up && $row->link_published == "1" ) {
					$img = 'publish_y.png';
				} else if ( ( $now <= $row->publish_down || $row->publish_down == $nullDate ) && $row->link_published == "1" ) {
					$img = 'publish_g.png';
				} else if ( $now > $row->publish_down && $row->link_published == "1" ) {
					$img = 'publish_r.png';
				} elseif ( $row->link_published == "0" ) {
					$img = "publish_x.png";
				}
				$task = $row->link_published ? 'link_unpublish' : 'link_publish';

				?>

			  <td width="10%" align="center"><a href="javascript: void(0);" onclick="return link_listItemTask('lb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a>
				</td>


			</tr><?php

				$k = 1 - $k;
			}
			?>

			<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="search_where" value="<?php echo $search_where ?>" />
		<input type="hidden" name="search_text" value="<?php echo $search_text ?>" />
		<input type="hidden" name="link_boxchecked" value="0" />
		</form>
	<?php
	}

	function searchresults_categories( &$rows, &$pageNav, &$pathWay, $search_where, $search_text, $option ) {
?>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
				</th>
				<th class="title" width="25%" nowrap="nowrap"><?php echo JText::_( 'Category' ) ?></th>
				<th align="left" width="65%"><?php echo JText::_( 'Parent' ) ?></th>
				<th><?php echo JText::_( 'Categories' ) ?></th>
				<th><?php echo JText::_( 'Listings' ) ?></th>
				<th><?php echo JText::_( 'Featured' ) ?></th>
				<th><?php echo JText::_( 'Published' ) ?></th>
			</thead>
<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i]; ?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20">
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->cat_id; ?>" onclick="isChecked(this.checked);" />
				</td>
				<td width="50%" align="left">
					<a href="#go" onclick="return listItemTask('cb<?php echo $i;?>','listcats')"><?php 
						echo $row->cat_name; ?></a>
				</td>
				<td align="left"><?php echo $pathWay->printPathWayFromCat( $row->cat_id, 0 ); ?></td>
				<td align="center"><?php echo $row->cat_cats; ?></td>
				<td align="center"><?php echo $row->cat_links; ?></td>
				<?php
				$task = $row->cat_featured ? 'cat_unfeatured' : 'cat_featured';
				$img = $row->cat_featured ? 'tick.png' : 'publish_x.png';
				?>
			  <td width="10%" align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a>
				</td>
				<?php
				$task = $row->cat_published ? 'cat_unpublish' : 'cat_publish';
				$img = $row->cat_published ? 'tick.png' : 'publish_x.png';
				?>
			  <td width="10%" align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a>
				</td>
			</tr><?php

				$k = 1 - $k;
			}
			?>

			<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="search_where" value="<?php echo $search_where ?>" />
		<input type="hidden" name="search_text" value="<?php echo $search_text ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
	<?php
	}

	/***
	* Tree Template
	*/
	function list_templates( $rows, $option ) {
	?>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
			<tr>
				<th class="title" width="30%" nowrap="nowrap"><?php echo JText::_( 'Name' ) ?></th>
				<th class="title" width="60%" nowrap="nowrap"><?php echo JText::_( 'Description' ) ?></th>
				<th class="title" width="10%" nowrap="nowrap" align="center"><?php echo JText::_( 'Default' ) ?></th>
			</tr>
			</thead>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i]; ?>
			<tr class="<?php echo "row$k"; ?>" align="left">
				<td><input type="radio" id="cb<?php echo $i ?>" name="template" value="<?php echo $row->directory; ?>" onClick="isChecked(this.checked);" /> <a href="" onClick="return listItemTask('cb<?php echo $i ?>','template_pages')"><?php echo $row->name; ?></a></td>
				<td><?php echo $row->description; ?></td>
				<td align="center"><?php echo ($row->default) ? '<img src="images/tick.png">' : '&nbsp;' ; ?></td>
			</tr>
			<?php		$k = 1 - $k; } ?>
			<tfoot>
			<tr><th colspan="3">&nbsp;</th></tr>
			</tfoot>
		</table>

		<p />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		</form>
		<?php
	}
	
	function template_pages( $template, $template_name, $params, $option ) {
	?>
	<form action="index.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th class="templates"><?php echo JText::_( 'Tree templates' ) ?>: <? echo $template_name ?></th>
		</tr>
	</table>
	<?php if(!is_null($params)) { ?>
	<div style="width:57%;float:left">
	<?php } ?>

	<fieldset>
	<legend><?php echo JText::_( 'Select template file to edit' ) ?></legend>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="admintable">
		<tr>
			<td width="33%" align="left" valign="top">
				
				<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
				
				<tr><td><h3><?php echo JText::_( 'Listing' ) ?></h3></td></tr>
				<tr><td>
					<input type="radio" id="cb1" name="page" value="page_listing" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb1','edit_templatepage')"><?php echo JText::_( 'Tem view listing' ) ?></a>
				</td></tr>
				<tr><td>
					<input type="radio" id="cb2" name="page" value="sub_listingDetails" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb2','edit_templatepage')"><?php echo JText::_( 'Tem listing details' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb3" name="page" value="sub_listingSummary" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb3','edit_templatepage')"><?php echo JText::_( 'Tem listing summary' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb4" name="page" value="sub_listings" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb4','edit_templatepage')"><?php echo JText::_( 'Listings' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb5" name="page" value="page_addListing" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb5','edit_templatepage')"><?php echo JText::_( 'Add listing' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb6" name="page" value="page_contactOwner" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb6','edit_templatepage')"><?php echo JText::_( 'Contact owner' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb7" name="page" value="page_writeReview" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb7','edit_templatepage')"><?php echo JText::_( 'Write review' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb8" name="page" value="page_print" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb8','edit_templatepage')"><?php echo JText::_( 'Print' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb9" name="page" value="sub_reviews" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb9','edit_templatepage')"><?php echo JText::_( 'Reviews' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb11" name="page" value="page_recommend" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb11','edit_templatepage')"><?php echo JText::_( 'Tem recommend form' ) ?></a></td></tr>
				
				</table>

			</td>
			<td width="33%" align="left" valign="top">

				<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">

				<tr><td><h3><?php echo JText::_( 'Index' ) ?></h3>
				<tr><td>
					<input type="radio" id="cb12" name="page" value="page_index" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb12','edit_templatepage')"><?php echo JText::_( 'Main' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb13" name="page" value="page_subCatIndex" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb13','edit_templatepage')"><?php echo JText::_( 'Category' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb14" name="page" value="page_listAlpha" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb14','edit_templatepage')"><?php echo JText::_( 'Tem listalpha' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb15" name="page" value="page_listListings" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb15','edit_templatepage')"><?php echo JText::_( 'Top listings' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb31" name="page" value="page_ownerListing" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb31','edit_templatepage')"><?php echo JText::_( 'Owners listing' ) ?></a></td></tr>

				<tr><td>
					<input type="radio" id="cb35" name="page" value="page_usersFavourites" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb35','edit_templatepage')"><?php echo JText::_( 'Tem users favourites' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb36" name="page" value="page_usersReview" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb36','edit_templatepage')"><?php echo JText::_( 'Tem users reviews' ) ?></a></td></tr>

				</table>
		
			</td>
			<td width="33%" align="left" valign="top">

				<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">

				<tr><td><h3><?php echo JText::_( 'Category' ) ?></h3></td></tr>
				<tr><td>
					<input type="radio" id="cb21" name="page" value="page_addCategory" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb21','edit_templatepage')"><?php echo JText::_( 'Add cat' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb22" name="page" value="sub_subCats" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb22','edit_templatepage')"><?php echo JText::_( 'Tem subcats' ) ?></a></td></tr>

				</table>
				<br />
				<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">

				<tr><td><h3><?php echo JText::_( 'Misc' ) ?></h3></td></tr>
				<tr><td>
					<input type="radio" id="cb23" name="page" value="page_advSearch" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb23','edit_templatepage')"><?php echo JText::_( 'Advanced search' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb24" name="page" value="page_advSearchRedirect" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb24','edit_templatepage')"><?php echo JText::_( 'Advanced search redirect' ) ?></a></td></tr>
										<tr><td>
					<input type="radio" id="cb25" name="page" value="page_advSearchResults" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb25','edit_templatepage')"><?php echo JText::_( 'Advanced search results' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb26" name="page" value="page_searchResults" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb26','edit_templatepage')"><?php echo JText::_( 'Search results' ) ?></a></td></tr>

				<tr><td>
					<input type="radio" id="cb27" name="page" value="page_confirmDelete" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb27','edit_templatepage')"><?php echo JText::_( 'Tem confirm delete' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb28" name="page" value="page_error" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb28','edit_templatepage')"><?php echo JText::_( 'Error' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb29" name="page" value="page_errorListing" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb29','edit_templatepage')"><?php echo JText::_( 'Tem listing error' ) ?></a></td></tr>
				<tr><td>
					<input type="radio" id="cb32" name="page" value="sub_alphaIndex" onclick="isChecked(this.checked);" /><a href="#go" onclick="return listItemTask('cb32','edit_templatepage')"><?php echo JText::_( 'Tem az' ) ?></a></td></tr>

				</table>
					
			</td>
		</tr>
	</table>
	</fieldset>
	
	<?php if(!is_null($params)) { JHTML::_('behavior.tooltip');	?>
	</div>
	<div style="width:42%;float:left;clear:none;padding: 0 0 0 5px">
	
	<fieldset>
	<legend><?php echo JText::_( 'Parameters' ) ?></legend>
	<?php echo $params->render();?>
	</fieldset>
	
	</div>
	<?php } ?>
	
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="template" value="<?php echo $template ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	
	</form>
	<?php
	}
	
	function edit_templatepage( $page, $template, $content, $option ) {
		global $mtconf;
		?>
		<form action="index.php" method="post" name="adminForm">

		<fieldset>
			<legend>
				/components/com_mtree/templates/<?php echo $template; ?>/<?php echo $page; ?>.tpl.php
	      		<?php
	      		$template_path = $mtconf->getjconf('absolute_path') . '/components/com_mtree/templates/' . $template . '/'.$page.'.tpl.php';
	      		echo is_writable( $template_path ) ? '<b><font color="orange"> - '.JText::_( 'Writeable' ).'</font></b>' : '<b><font color="red"> - '.JText::_( 'Unwriteable' ).'</font></b>';
	      		?>
			</legend>
		<table class="admintable" width="100%">
		<tr>
			<td>
			<textarea cols="90" rows="50" name="pagecontent" class="inputbox" style="width:100%"><?php echo $content; ?></textarea>
			</td>
		</tr>
		</table>
		</fieldset>
		
		<input type="hidden" name="template" value="<?php echo $template; ?>" />
		<input type="hidden" name="page" value="<?php echo $page; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
	
	function new_template( $option ) {
		global $mtconf;
	?>
	<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm">
	<table class="adminform">
	<tr><th><?php echo JText::_( 'Upload package file' ) ?></th></tr>
	<tr>
		<td align="left">
		<?php echo JText::_( 'Package file' ) ?>:
		<input class="text_area" name="template" type="file" size="70"/>
		<input class="button" type="submit" value="<?php echo JText::_( 'Upload file and install' ) ?>" />
		</td>
	</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option ?>" />
	<input type="hidden" name="task" value="install_template" />
	</form>
	
	<p />
	
	<table class="content">
	<?php
		echo '<td class="item">';
		echo '<strong>/components/com_mtree/templates</strong>';
		echo '</td><td align="left">';
		if( is_writable( $mtconf->getjconf('absolute_path') . '/components/com_mtree/templates' ) ) {
			echo '<b><font color="green">Writeable</font></b>';
		} else {
			echo '<b><font color="red">Unwriteable</font></b>';
		} 
	?></td></tr>
		
	</table>
	<?php
	}
	
	function copy_template( $template, $template_name, $option )
	{
		JHTML::_('behavior.tooltip');
	?>
	<form action="index.php" method="post" name="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'New Template' ); ?></legend>
		<table cellspacing="1" class="admintable">
			<tbody><tr>
				<td valign="top" class="key">
					<?php echo JText::_( 'Original Template' ); ?>
				</td>
				<td>
					<strong>
						<em><?php echo $template_name; ?> (<?php echo $template; ?>)</em>
					</strong>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="new_template_name">
						<?php echo JText::_( 'Template Name' ); ?>:
					</label>
				</td>
				<td>
					<input type="text" value="" size="35" id="new_template_name" name="new_template_name" class="text_area"/>
					<?php echo JHTML::_('tooltip',  JText::_( 'The name of the new template' ) ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="new_template_folder">
						<?php echo JText::_( 'Folder Name' ); ?>:
					</label>
				</td>
				<td>
					<input type="text" value="" size="35" id="new_template_folder" name="new_template_folder" class="text_area"/>
					<?php echo JHTML::_('tooltip',  JText::_( "The name of the new template's folder. Enter only alpha numeric values and underscore." ) ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="new_template_creation_date">
						<?php echo JText::_( 'Creation Date' ); ?>:
					</label>
				</td>
				<td>
					<input type="text" value="<?php echo strftime('%e %B %Y'); ?>" size="35" id="new_template_creation_date" name="new_template_creation_date" class="text_area"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="new_template_author">
						<?php echo JText::_( 'Author' ); ?>:
					</label>
				</td>
				<td>
					<input type="text" value="<?php $my =& JFactory::getUser(); echo $my->name; ?>" size="35" id="new_template_author" name="new_template_author" class="text_area"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="new_template_author_email">
						<?php echo JText::_( 'Author E-mail' ); ?>:
					</label>
				</td>
				<td>
					<input type="text" value="<?php $my =& JFactory::getUser(); echo $my->email; ?>" size="35" id="new_template_author_email" name="new_template_author_email" class="text_area"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="new_template_author_url">
						<?php echo JText::_( 'Author URL' ); ?>:
					</label>
				</td>
				<td>
					<input type="text" value="" size="35" id="new_template_author_url" name="new_template_author_url" class="text_area"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="new_template_copyright">
						<?php echo JText::_( 'Copyright' ); ?>:
					</label>
				</td>
				<td>
					<input type="text" value="" size="35" id="new_template_copyright" name="new_template_copyright" class="text_area"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="new_template_version">
						<?php echo JText::_( 'Version' ); ?>:
					</label>
				</td>
				<td>
					<input type="text" value="" size="35" id="new_template_version" name="new_template_version" class="text_area"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="new_template_description">
						<?php echo JText::_( 'Template Description' ); ?>:
					</label>
				</td>
				<td>
					<input type="text" value="" size="35" id="new_template_description" name="new_template_description" class="text_area"/>
				</td>
			</tr>			
		</tbody></table>
	</fieldset>
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="template" value="<?php echo $template; ?>" />
	<input type="hidden" name="task" value="" />
	</form>
	<?php
	}
	
	/***
	* Advanced Back-end Search
	*/
	function advsearch( $fields, $lists, $option ) {
		global $mtconf;
		?>

		<form action="index.php" method="post" name="adminForm">
		<fieldset>
		<legend><?php echo JText::_( 'Search listings' ) ?></legend>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="admintable">
			<tr>
				<td valign="top">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td colspan="2"><?php printf(JText::_( 'Return results if x of the following conditions are met' ),$lists['searchcondition']); ?></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" value="<?php echo JText::_( 'Search' ) ?>" class="button" /> &nbsp; <input type="reset" value="<?php echo JText::_( 'Reset' ) ?>" class="button" /></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<?php
						while( $fields->hasNext() ) {
							$field = $fields->getField();
							if($field->hasSearchField()) {
								echo '<tr>';
								echo '<td valign="top" align="left" class="key">' . $field->caption . ':' . '</td>';
								echo '<td align="left">';
								echo $field->getSearchHTML();
								echo '</td>';
								echo '</tr>';
							}
							$fields->next();
						}
						?>
						<tr>
							<td align="left" class="key"><?php echo JText::_( 'Owner' )?>:</td>
							<td align="left"><input name="owner" type="text" class="text_area" size="20" /></td>
						</tr>
						<tr>
							<td align="left" class="key"><?php echo JText::_( 'Publishing' )?>:</td>
							<td align="left"><?php echo $lists['publishing'] ?></td>
						</tr>
						<tr>
							<td align="left" class="key"><?php echo JText::_( 'Template' )?>:</td>
							<td align="left"><?php echo $lists['templates'] ?></td>
						</tr>
						<tr>
							<td align="left" class="key"><?php echo JText::_( 'Notes' )?>:</td>
							<td align="left" colspan="3"><input name="internal_notes" type="text" class="text_area" size="20" /></td>
						</tr>
					</table>	
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td colspan="2"><input type="submit" value="<?php echo JText::_( 'Search' ) ?>" class="button" /> &nbsp; <input type="reset" value="<?php echo JText::_( 'Reset' ) ?>" class="button" />
			</tr>
		</table>
		</fieldset>
		
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="advsearch2" />
		<input type="hidden" name="search_where" value="1" />
		</form>
		<?php		
	}

	function advsearchresults_links( &$links, &$fields, &$pageNav, &$pathWay, $search_where, $option ) {
		$database	=& JFactory::getDBO();
		$nullDate	= $database->getNullDate();
	?>
		<script language="javascript" type="text/javascript">
			function link_listItemTask( id, task ) {
				var f = document.adminForm;
				lb = eval( 'f.' + id );
				if (lb) {
					lb.checked = true;
					submitbutton(task);
				}
				return false;
			}

			function link_isChecked(isitchecked){
				if (isitchecked == true){
					document.adminForm.link_boxchecked.value++;
				}
				else {
					document.adminForm.link_boxchecked.value--;
				}
			}

			function link_checkAll( n ) {
				var f = document.adminForm;
				var c = f.link_toggle.checked;
				var n2 = 0;
				for (i=0; i < n; i++) {
					lb = eval( 'f.lb' + i );
					if (lb) {
						lb.checked = c;
						n2++;
					}
				}
				if (c) {
					document.adminForm.link_boxchecked.value = n2;
				} else {
					document.adminForm.link_boxchecked.value = 0;
				}
			}

		</script>
		<form action="index.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
				<th width="20"><input type="checkbox" name="link_toggle" value="" onclick="link_checkAll(<?php echo count( $links ); ?>);" /></th>
				<th class="title" width="20%" nowrap="nowrap"><?php echo JText::_( 'Listing' ) ?></th>
				<th width="65%" align="left" nowrap="nowrap"><?php echo JText::_( 'Category' ) ?></th>
				<th width="100"><?php echo JText::_( 'Reviews' ) ?></th>
				<th><?php echo JText::_( 'Featured' ) ?></th>
				<th><?php echo JText::_( 'Published' ) ?></th>
			</thead>
<?php
		$k = 0;
		for ($i=0, $n=count( $links ); $i < $n; $i++) {
			$row = &$links[$i]; ?>
			<tr class="<?php echo "row$k"; ?>" align="left">
				<td width="20"><input type="checkbox" id="lb<?php echo $i;?>" name="lid[]" value="<?php echo $row->link_id; ?>" onclick="link_isChecked(this.checked);" /></td>
				<td><a href="index.php?option=com_mtree&amp;task=editlink&amp;link_id=<?php echo $row->link_id; ?>"><?php echo htmlspecialchars($row->link_name); ?></a></td>
				<td><?php echo '<a href="index.php?option=com_mtree&task=listcats&cat_id='.$row->cat_id.'">'.$pathWay->getCatName( $row->cat_id ).'</a>'; ?></td>
				<td align="center"><a href="index.php?option=com_mtree&task=reviews_list&link_id=<?php echo $row->link_id; ?>"><?php echo $row->reviews; ?></a></td>
				<?php
				$task = $row->link_featured ? 'link_unfeatured' : 'link_featured';
				$img = $row->link_featured ? 'tick.png' : 'publish_x.png';
				?>
			  <td width="10%" align="center"><a href="javascript: void(0);" onclick="return link_listItemTask('lb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
				<?php

				$jdate = JFactory::getDate();
				$now = $jdate->toMySQL();

				if ( $now <= $row->publish_up && $row->link_published == "1" ) {
					$img = 'publish_y.png';
				} else if ( ( $now <= $row->publish_down || $row->publish_down == $nullDate ) && $row->link_published == "1" ) {
					$img = 'publish_g.png';
				} else if ( $now > $row->publish_down && $row->link_published == "1" ) {
					$img = 'publish_r.png';
				} elseif ( $row->link_published == "0" ) {
					$img = "publish_x.png";
				}
				$task = $row->link_published ? 'link_unpublish' : 'link_publish';

				?>
			  <td width="10%" align="center"><a href="javascript: void(0);" onclick="return link_listItemTask('lb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
			</tr><?php

				$k = 1 - $k;
			}
			?>

			<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="advsearch2" />
		<input type="hidden" name="link_boxchecked" value="0" />
		<input type="hidden" name="search_where" value="<?php echo $search_where ?>" />
		<input type="hidden" name="searchcondition" value="<?php echo JRequest::getInt( 'searchcondition', 1, 'post'); ?>" />
		<?php
		$post = JRequest::get('post');
		$core_fields = array('link_name', 'link_desc', 'website', 'address', 'city', 'state', 'country', 'postcode', 'telephone', 'fax', 'email', 'publishing', 'link_template', 'price', 'price_2', 'link_rating', 'rating_2', 'link_votes', 'votes_2', 'link_hits', 'hits_2', 'internal_notes', 'metakey', 'metadesc', 'link_created', 'link_created_2', 'link_created_3', 'link_created_4', 'link_created_5', 'link_created_6', 'link_created_7', 'link_created_8', 'link_created_9', 'link_created_10', 'link_modified', 'link_modified_2', 'link_modified_3', 'link_modified_4', 'link_modified_5', 'link_modified_6', 'link_modified_7', 'link_modified_8', 'link_modified_9', 'link_modified_10');
		foreach($core_fields AS $core_field) {
			echo '<input type="hidden" name="' . $core_field . '" value="';
			if(isset($post[$core_field])) {
				echo $post[$core_field];
			}
			echo '" />';
		}

		$fields->resetPointer();
		while( $fields->hasNext() ) {
			$field = $fields->getField();
			if( array_key_exists('cf'.$field->id, $post) && !empty($post['cf'.$field->id]) ) {
				
				if( is_array($post['cf'.$field->id]) )
				{
					$array = $post['cf'.$field->id];
					foreach( $array AS $value )
					{
						?>
						<input type="hidden" name="cf<?php echo $field->id ?>[]" value="<?php echo $value; ?>" /><?php
					}
				} else {
				?>
					<input type="hidden" name="cf<?php echo $field->id ?>" value="<?php echo $post['cf'.$field->id] ?>" /><?php
					if( $field->numOfSearchFields > 1 )
					{
						for($i=2; $i<=$field->numOfSearchFields; $i++)
						{
							?>
								<input type="hidden" name="cf<?php echo $field->id ?>_<?php echo $i; ?>" value="<?php echo $post['cf'.$field->id.'_'.$i] ?>" /><?php
						}
					}
				}
			}
			$fields->next();
		}

		?>
		</form>
	<?php
	}

	/***
	* CSV Import/Export
	*/
	function csv( $fields, $lists, $option ) {
	?>
  <script type="text/javascript" language="javascript">
		function submitbutton( pressbutton ) {
			var form = document.adminForm;

			// do field validation
			var temp = false;
			if(pressbutton=='csv_export') {
				var elts      = document.adminForm.elements['fields[]'];
				var elts_cnt  = (typeof(elts.length) != 'undefined')
											? elts.length
											: 0;

				for (var i = 0; i < elts_cnt; i++) {
						if (elts[i].checked == true) temp = true;
				} 
			} else {
				temp = true;
			}
			if (temp == true) {
				submitform( pressbutton );
			} else {
				alert('<?php echo JText::_( 'Please select at least one field' ) ?>');
			}
		}

		function setCheckboxes(the_form, do_check)
		{
				var elts      = document.forms[the_form].elements['fields[]'];
				var elts_cnt  = (typeof(elts.length) != 'undefined')
											? elts.length
											: 0;

				if (elts_cnt) {
						for (var i = 0; i < elts_cnt; i++) {
								elts[i].checked = do_check;
						}
				} else {
						elts.checked        = do_check;
				}

				return true;
		}
		</script>
		<form action="index.php" method="post" name="adminForm">

		<fieldset>
		<legend><?php echo JText::_('Fields'); ?></legend>

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="admintable">
			<tr>
				<td width="33%" valign="top" align="left">
					<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="l.link_id" /><?php echo JText::_( 'Listing id' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="link_name" /><?php echo JText::_( 'Name' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="link_desc" /><?php echo JText::_( 'Description' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="website" /><?php echo JText::_( 'Website' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="address" /><?php echo JText::_( 'Address' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="city" /><?php echo JText::_( 'City' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="state" /><?php echo JText::_( 'State' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="country" /><?php echo JText::_( 'Country' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="postcode" /><?php echo JText::_( 'Postcode' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="telephone" /><?php echo JText::_( 'Telephone' ) ?>
						</td></tr>
					</table>

				</td>
				<td width="33%" valign="top">
					<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="fax" /><?php echo JText::_( 'Fax' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="email" /><?php echo JText::_( 'Email' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="cat_id" /><?php echo JText::_( 'Category' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="user_id" /><?php echo JText::_( 'Owner' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="link_created" /><?php echo JText::_( 'Created' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="link_modified" /><?php echo JText::_( 'Modified' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="publish_up" /><?php echo JText::_( 'Publish Up' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="publish_down" /><?php echo JText::_( 'Publish Down' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="link_hits" /><?php echo JText::_( 'Hits' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="price" /><?php echo JText::_( 'Price' ) ?>
						</td></tr>
					</table>
				</td>
				<td width="33%" valign="top">
					<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="link_rating" /><?php echo JText::_( 'Rating' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="link_votes" /><?php echo JText::_( 'Votes' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="link_visited" /><?php echo JText::_( 'Visited' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="internal_notes" /><?php echo JText::_( 'Internal notes' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="metakey" /><?php echo JText::_( 'Meta keywords' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="metadesc" /><?php echo JText::_( 'Meta description' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="lat" /><?php echo JText::_( 'Latitude' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="lng" /><?php echo JText::_( 'Longitude' ) ?>
						</td></tr>
						<tr><td align="left">
							<input type="checkbox" name="fields[]" value="zoom" /><?php echo JText::_( 'Zoom' ) ?>
						</td></tr>
					</table>
				</td>

			</tr>
			<tr>
			<?php
			$fields->resetPointer();
			$count=0;
			for($i=0;$i<3;$i++) {
				echo '<td align="left" width="33%" valign="top">';
				echo '<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">';
				for($j=$count;$j<(ceil($fields->getTotal()/3)*($i+1)) && $fields->hasNext();$j++) {
					$field = $fields->getField();
					?>
					<tr><td align="left">
						<input type="checkbox" name="fields[]" value="<?php echo $field->getInputFieldName(1) ?>" id="<?php echo $field->getInputFieldName(1) ?>" /> <label for="<?php echo $field->getInputFieldName(1) ?>"><?php echo $field->getCaption(true) ?></label>
					</td></tr>
					<?php
					$count++;
					$fields->next();
					if($count>=(ceil($fields->getTotal()/3)*($i+1))) {
						break;
					}
				}
				echo '</table>';
				if($i==0) {
					echo '<p />';
					echo '<a href="#" onclick="setCheckboxes(\'adminForm\', true); return false;">' . JText::_( 'Select all' ) . '</a> / <a href="#" onclick="setCheckboxes(\'adminForm\', false); return false;">' . JText::_( 'Unselect all' ) . '</a>';
				}
				echo '</td>';
			}
			?>
			</tr>
			<tr><td colspan="3"></td></tr>

			<tr class="row0"><td colspan="3" align="left"><b><?php echo JText::_( 'Publishing' ) ?></b></td></tr>
			<tr><td align="left" colspan="3">
				<?php echo $lists['publishing'] ?>
				<p />
				<input type="button" class="button" value="<?php echo JText::_( 'Export' ) ?>" onClick="javascript:submitbutton('csv_export')" />
			</td></tr>
			
		</table>
		</fieldset>
		
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
	<?php
	}

	function csv_export( $header, $data, $option ) {
	?>
	<script language="Javascript">
	<!--
	/*
	Select and Copy form element script- By Dynamicdrive.com
	For full source, Terms of service, and 100s DTHML scripts
	Visit http://www.dynamicdrive.com
	*/

	//specify whether contents should be auto copied to clipboard (memory)
	//Applies only to IE 4+
	//0=no, 1=yes
	var copytoclip=1

	function HighlightAll(theField) {
	var tempval=eval("document."+theField)
	tempval.focus()
	tempval.select()
	if (document.all&&copytoclip==1){
	therange=tempval.createTextRange()
	therange.execCommand("Copy")
	window.status="Contents highlighted and copied to clipboard!"
	setTimeout("window.status=''",1800)
	}
	}
	//-->
	</script>

	<center>
	<form action="index.php" method="POST" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
		<tr class="row0">
			<td>
			<p />
			<a href="javascript:HighlightAll('adminForm.csv_excel')"><?php echo JText::_( 'Select all' ) ?></a>
			<p />
			<textarea name="csv_excel" rows="30" cols="80" style="width:100%"><?php 
				echo $header; 
				echo $data;
			?></textarea>
			<p />
			<a href="javascript:HighlightAll('adminForm.csv_excel')"><?php echo JText::_( 'Select all' ) ?></a>
			</td>
		</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="task" value="doreport" />
	</form>
	</center>
	<?php
	}

	/***
	* Configuration
	*/
	function config( $configs, $configgroups, $lists, $option ) {
		global $mtconf;

		jimport('joomla.html.pane');
		$pane	= &JPane::getInstance('tabs');
		
	?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		submitform( pressbutton );
	}
</script>

<form action="index.php" method="POST" name="adminForm">
	<?php
	echo $pane->startPane("content-pane");
	$configgroup = '';
	$j=0;
	
	foreach( $configgroups AS $configgroup ) {
		
		if( $j > 0 ) {
			echo $pane->endPanel();
		}
		echo $pane->startPanel( JText::_($configgroup), $configgroup.'-page');
		
	?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
	<?php 
		$i = 0;
		foreach( $configs AS $config ) { 
			if( $config->groupname == $configgroup ) {

				echo '<tr>';
				if( $config->configcode == 'note' ) {
					echo '<td colspan="2" align="left" style="border-bottom: 1px solid #C0C0C0;border-top: 1px solid #C0C0C0; background-color: #FFFFFF">';
				} elseif( !in_array($config->configcode, array('sort_direction','predefined_reply')) ) {
					echo '<td align="left" valign="top"';
					if($i<=1) {
						echo ' width="285"';
					}
					echo '>';
					$langcode = 'CONFIGNAME '.strtoupper($config->varname);
					if( JText::_( 'CONFIGNAME '.strtoupper($config->varname) ) == $langcode ) {
						echo $config->varname;
					} else {
						echo JText::_( 'CONFIGNAME '.strtoupper($config->varname) );
					}
					
					if( substr($config->varname,0,4) == 'rss_' ) {
						if( $config->varname == 'rss_custom_fields') {
							echo ' (cust_#)';
						} else {
							echo ' ('.substr($config->varname,4).')';
						}
					}
					echo ':</td><td align="left"';
					if($i<=1) {
						echo ' width="76%"';
					}
					echo '>';
				}
				switch( $config->configcode ) {
					case 'text':
					default:
						echo '<input name="'.$config->varname.'" value="'.$config->value.'" size="30" class="text_area" />';
						break;
					case 'template':
					case 'map':
					case 'resize_method':
						echo $lists[$config->configcode];
						break;
					case 'yesno':
						echo JHTML::_('select.booleanlist', $config->varname,'class="text_area"',$config->value);
						break;
					case 'sort_direction':
						continue;
						break;
					case 'cat_order':
					case 'listing_order':
					case 'review_order':
						$tmp_varname = substr($config->varname,0,-1);
						echo JHTML::_('select.genericlist', $lists[$configs[$tmp_varname.'1']->configcode], $tmp_varname.'1', 'class="inputbox" size="1"',	'value', 'text', $configs[$tmp_varname.'1']->value );
						echo JHTML::_('select.genericlist', $lists[$configs[$tmp_varname.'2']->configcode], $tmp_varname.'2', 'class="inputbox" size="1"',	'value', 'text', $configs[$tmp_varname.'2']->value );
						if( substr($config->varname,-1) == '1' ) {
							unset($configs[$tmp_varname.'2']);
						} else {
							unset($configs[$tmp_varname.'1']);
						}
						break;
					case 'predefined_reply':
						continue;
						break;
					case 'predefined_reply_title':
						$tmp_varname = substr($config->varname,17,1);
						echo '<input name="predefined_reply_'.$tmp_varname.'_title" value="'.$configs['predefined_reply_'.$tmp_varname.'_title']->value.'" size="60" class="text_area" />';
						echo '<br />';
						echo '<textarea style="margin-top:5px" name="predefined_reply_'.$tmp_varname.'_message" class="text_area" cols="80" rows="8" />'.$configs['predefined_reply_'.$tmp_varname.'_message']->value.'</textarea>';
						if( substr($config->varname,19) == 'title' ) {
							unset($configs['predefined_reply_'.$tmp_varname.'_message']);
						} else {
							unset($configs['predefined_reply_'.$tmp_varname.'_title']);
						}						
						break;
					case 'user_access':
					case 'user_access2':
					case 'sef_link_slug_type':
						echo JHTML::_('select.genericlist', $lists[$config->configcode], $config->varname, 'class="inputbox" size="1"',	'value', 'text', $config->value );
						break;
					case 'note':
						echo JText::_( 'CONFIGNOTE '.strtoupper($config->varname) );
						break;
				}
				if( JText::_( 'CONFIGDESC '.strtoupper($config->varname) ) != 'CONFIGDESC '.strtoupper($config->varname) ) {
					echo '<span style="background-color:white;padding:0 0 3px 10px;">' . JText::_( 'CONFIGDESC '.strtoupper($config->varname) ) . '</span>';
				}

			?></td>
		</tr>
	<?php 
				unset($configs[$config->varname]);
				$i++;
			}
		}
		echo '</table>';
		$j++;
	}
	echo $pane->endPanel();
	echo $pane->endPane();
	?>
  <input type="hidden" name="option" value="<?php echo $option; ?>">
  <input type="hidden" name="task" value="saveconfig">
</form>
	<?php
	}

	/***
	* About Mosets Tree
	*/
	function about() {
	global $mtconf;
	
	JHTML::_('behavior.switcher');
	?>

	<div id="submenu-wrap">
		<div id="submenu-box">
			<div class="t">
				<div class="t">
					<div class="t"></div>
		 		</div>
			</div>
			<div class="m" style="padding:0;">
				<div class="submenu-box">
					<div class="submenu-pad">
						<ul id="submenu" class="information" style="overflow:hidden">
							<li>
								<a id="general" class="active"><?php echo JText::_( 'General' ); ?></a>
							</li>
							<li>
								<a id="license"><?php echo JText::_( 'License' ); ?></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="b">
				<div class="b">
		 			<div class="b"></div>
				</div>
			</div>
		</div>
	</div>

	<form action="index.php?option=com_mtree&amp;task=about" method="post" name="adminForm">
		<div id="config-document">
			<div id="page-general">
				<table class="adminlist">
					<thead>
						<tr>
							<th colspan="3">
								<?php echo JText::_('General Information'); ?>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="3">&nbsp;</th>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td rowspan="5" width="300px">
								<center>
									<a href="http://www.mosets.com/tree/"><img width="260" height="62" src="../components/com_mtree/img/logo.png" alt="<?php echo $mtconf->get('name') ?>"></a>
								</center>
							</td>
							<td width="100">
								<strong><?php echo JText::_('Version'); ?></strong>
							</td>
							<td>
								<?php echo $mtconf->get('version') ; ?>
							</td>
						</tr>
						<tr>
							<td>
								<strong><?php echo JText::_('Website'); ?></strong>
							</td>
							<td>
								<a href="http://www.mosets.com">www.mosets.com</a>
							</td>
						</tr>
						<tr>
							<td>
								<strong><?php echo JText::_('Author'); ?></strong>
							</td>
							<td>
								C.Y. Lee at Mosets Consulting
							</td>
						</tr>
						<tr>
							<td>
								<strong><?php echo JText::_('Email'); ?></strong>
							</td>
							<td>
								<a href="mailto:mtree@mosets.com">mtree@mosets.com</a>
							</td>
						</tr>
						<tr>
							<td>
								<strong><?php echo JText::_('Support'); ?></strong>
							</td>
							<td>
								<a href="http://forum.mosets.com/forumdisplay.php?f=25" target="_blank">Mosets Tree Priority Support</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="page-license">
				<table class="adminlist">
					<thead>
						<tr>
							<th>
								<?php echo JText::_('License Agreement'); ?>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>&nbsp;</th>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td>
								
								<h3><a href="index.php?option=com_mtree&amp;task=about">GNU GENERAL PUBLIC LICENSE</a></h3>
								<p>
								Version 2, June 1991
								</p>
								Copyright (C) 1989, 1991 Free Software Foundation, Inc.<br />
								51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA<br />
								<p />
								Everyone is permitted to copy and distribute verbatim copies of this license document, but changing it is not allowed.

								<h3>Preamble</h3>

								<p>
								  The licenses for most software are designed to take away your
								freedom to share and change it.  By contrast, the GNU General Public
								License is intended to guarantee your freedom to share and change free
								software--to make sure the software is free for all its users.  This
								General Public License applies to most of the Free Software
								Foundation's software and to any other program whose authors commit to
								using it.  (Some other Free Software Foundation software is covered by
								the GNU Lesser General Public License instead.)  You can apply it to
								your programs, too.
								</p>

								<p>
								  When we speak of free software, we are referring to freedom, not
								price.  Our General Public Licenses are designed to make sure that you
								have the freedom to distribute copies of free software (and charge for
								this service if you wish), that you receive source code or can get it
								if you want it, that you can change the software or use pieces of it
								in new free programs; and that you know you can do these things.
								</p>

								<p>
								  To protect your rights, we need to make restrictions that forbid
								anyone to deny you these rights or to ask you to surrender the rights.
								These restrictions translate to certain responsibilities for you if you
								distribute copies of the software, or if you modify it.
								</p>

								<p>
								  For example, if you distribute copies of such a program, whether
								gratis or for a fee, you must give the recipients all the rights that
								you have.  You must make sure that they, too, receive or can get the
								source code.  And you must show them these terms so they know their
								rights.
								</p>

								<p>
								  We protect your rights with two steps: (1) copyright the software, and
								(2) offer you this license which gives you legal permission to copy,
								distribute and/or modify the software.
								</p>

								<p>
								  Also, for each author's protection and ours, we want to make certain
								that everyone understands that there is no warranty for this free
								software.  If the software is modified by someone else and passed on, we
								want its recipients to know that what they have is not the original, so
								that any problems introduced by others will not reflect on the original
								authors' reputations.
								</p>

								<p>
								  Finally, any free program is threatened constantly by software
								patents.  We wish to avoid the danger that redistributors of a free
								program will individually obtain patent licenses, in effect making the
								program proprietary.  To prevent this, we have made it clear that any
								patent must be licensed for everyone's free use or not licensed at all.
								</p>

								<p>
								  The precise terms and conditions for copying, distribution and
								modification follow.
								</p>


								<h3>TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION</h3>


								<a name="section0"></a><p>
								<strong>0.</strong>
								 This License applies to any program or other work which contains
								a notice placed by the copyright holder saying it may be distributed
								under the terms of this General Public License.  The "Program", below,
								refers to any such program or work, and a "work based on the Program"
								means either the Program or any derivative work under copyright law:
								that is to say, a work containing the Program or a portion of it,
								either verbatim or with modifications and/or translated into another
								language.  (Hereinafter, translation is included without limitation in
								the term "modification".)  Each licensee is addressed as "you".
								</p>

								<p>
								Activities other than copying, distribution and modification are not
								covered by this License; they are outside its scope.  The act of
								running the Program is not restricted, and the output from the Program
								is covered only if its contents constitute a work based on the
								Program (independent of having been made by running the Program).
								Whether that is true depends on what the Program does.
								</p>

								<a name="section1"></a><p>
								<strong>1.</strong>
								 You may copy and distribute verbatim copies of the Program's
								source code as you receive it, in any medium, provided that you
								conspicuously and appropriately publish on each copy an appropriate
								copyright notice and disclaimer of warranty; keep intact all the
								notices that refer to this License and to the absence of any warranty;
								and give any other recipients of the Program a copy of this License
								along with the Program.
								</p>

								<p>
								You may charge a fee for the physical act of transferring a copy, and
								you may at your option offer warranty protection in exchange for a fee.
								</p>

								<a name="section2"></a><p>
								<strong>2.</strong>
								 You may modify your copy or copies of the Program or any portion
								of it, thus forming a work based on the Program, and copy and
								distribute such modifications or work under the terms of Section 1
								above, provided that you also meet all of these conditions:
								</p>

								<dl>
								  <dt></dt>
								    <dd>
								      <strong>a)</strong>
								      You must cause the modified files to carry prominent notices
								      stating that you changed the files and the date of any change.
								    </dd>
								  <dt></dt>
								    <dd>
								      <strong>b)</strong>
								      You must cause any work that you distribute or publish, that in
								      whole or in part contains or is derived from the Program or any
								      part thereof, to be licensed as a whole at no charge to all third
								      parties under the terms of this License.
								    </dd>
								  <dt></dt>
								    <dd>
								      <strong>c)</strong>
								      If the modified program normally reads commands interactively
								      when run, you must cause it, when started running for such
								      interactive use in the most ordinary way, to print or display an
								      announcement including an appropriate copyright notice and a
								      notice that there is no warranty (or else, saying that you provide
								      a warranty) and that users may redistribute the program under
								      these conditions, and telling the user how to view a copy of this
								      License.  (Exception: if the Program itself is interactive but
								      does not normally print such an announcement, your work based on
								      the Program is not required to print an announcement.)
								    </dd>
								</dl>

								<p>
								These requirements apply to the modified work as a whole.  If
								identifiable sections of that work are not derived from the Program,
								and can be reasonably considered independent and separate works in
								themselves, then this License, and its terms, do not apply to those
								sections when you distribute them as separate works.  But when you
								distribute the same sections as part of a whole which is a work based
								on the Program, the distribution of the whole must be on the terms of
								this License, whose permissions for other licensees extend to the
								entire whole, and thus to each and every part regardless of who wrote it.
								</p>

								<p>
								Thus, it is not the intent of this section to claim rights or contest
								your rights to work written entirely by you; rather, the intent is to
								exercise the right to control the distribution of derivative or
								collective works based on the Program.
								</p>

								<p>
								In addition, mere aggregation of another work not based on the Program
								with the Program (or with a work based on the Program) on a volume of
								a storage or distribution medium does not bring the other work under
								the scope of this License.
								</p>

								<a name="section3"></a><p>
								<strong>3.</strong>
								 You may copy and distribute the Program (or a work based on it,
								under Section 2) in object code or executable form under the terms of
								Sections 1 and 2 above provided that you also do one of the following:
								</p>

								<!-- we use this doubled UL to get the sub-sections indented, -->
								<!-- while making the bullets as unobvious as possible. -->

								<dl>
								  <dt></dt>
								    <dd>
								      <strong>a)</strong>
								      Accompany it with the complete corresponding machine-readable
								      source code, which must be distributed under the terms of Sections
								      1 and 2 above on a medium customarily used for software interchange; or,
								    </dd>
								  <dt></dt>
								    <dd>
								      <strong>b)</strong>
								      Accompany it with a written offer, valid for at least three
								      years, to give any third party, for a charge no more than your
								      cost of physically performing source distribution, a complete
								      machine-readable copy of the corresponding source code, to be
								      distributed under the terms of Sections 1 and 2 above on a medium
								      customarily used for software interchange; or,
								    </dd>
								  <dt></dt>
								    <dd>
								      <strong>c)</strong>
								      Accompany it with the information you received as to the offer
								      to distribute corresponding source code.  (This alternative is
								      allowed only for noncommercial distribution and only if you
								      received the program in object code or executable form with such
								      an offer, in accord with Subsection b above.)
								    </dd>
								</dl>

								<p>
								The source code for a work means the preferred form of the work for
								making modifications to it.  For an executable work, complete source
								code means all the source code for all modules it contains, plus any
								associated interface definition files, plus the scripts used to
								control compilation and installation of the executable.  However, as a
								special exception, the source code distributed need not include
								anything that is normally distributed (in either source or binary
								form) with the major components (compiler, kernel, and so on) of the
								operating system on which the executable runs, unless that component
								itself accompanies the executable.
								</p>

								<p>
								If distribution of executable or object code is made by offering
								access to copy from a designated place, then offering equivalent
								access to copy the source code from the same place counts as
								distribution of the source code, even though third parties are not
								compelled to copy the source along with the object code.
								</p>

								<a name="section4"></a><p>
								<strong>4.</strong>
								 You may not copy, modify, sublicense, or distribute the Program
								except as expressly provided under this License.  Any attempt
								otherwise to copy, modify, sublicense or distribute the Program is
								void, and will automatically terminate your rights under this License.
								However, parties who have received copies, or rights, from you under
								this License will not have their licenses terminated so long as such
								parties remain in full compliance.
								</p>

								<a name="section5"></a><p>
								<strong>5.</strong>
								 You are not required to accept this License, since you have not
								signed it.  However, nothing else grants you permission to modify or
								distribute the Program or its derivative works.  These actions are
								prohibited by law if you do not accept this License.  Therefore, by
								modifying or distributing the Program (or any work based on the
								Program), you indicate your acceptance of this License to do so, and
								all its terms and conditions for copying, distributing or modifying
								the Program or works based on it.
								</p>

								<a name="section6"></a><p>
								<strong>6.</strong>
								 Each time you redistribute the Program (or any work based on the
								Program), the recipient automatically receives a license from the
								original licensor to copy, distribute or modify the Program subject to
								these terms and conditions.  You may not impose any further
								restrictions on the recipients' exercise of the rights granted herein.
								You are not responsible for enforcing compliance by third parties to
								this License.
								</p>

								<a name="section7"></a><p>
								<strong>7.</strong>
								 If, as a consequence of a court judgment or allegation of patent
								infringement or for any other reason (not limited to patent issues),
								conditions are imposed on you (whether by court order, agreement or
								otherwise) that contradict the conditions of this License, they do not
								excuse you from the conditions of this License.  If you cannot
								distribute so as to satisfy simultaneously your obligations under this
								License and any other pertinent obligations, then as a consequence you
								may not distribute the Program at all.  For example, if a patent
								license would not permit royalty-free redistribution of the Program by
								all those who receive copies directly or indirectly through you, then
								the only way you could satisfy both it and this License would be to
								refrain entirely from distribution of the Program.
								</p>

								<p>
								If any portion of this section is held invalid or unenforceable under
								any particular circumstance, the balance of the section is intended to
								apply and the section as a whole is intended to apply in other
								circumstances.
								</p>

								<p>
								It is not the purpose of this section to induce you to infringe any
								patents or other property right claims or to contest validity of any
								such claims; this section has the sole purpose of protecting the
								integrity of the free software distribution system, which is
								implemented by public license practices.  Many people have made
								generous contributions to the wide range of software distributed
								through that system in reliance on consistent application of that
								system; it is up to the author/donor to decide if he or she is willing
								to distribute software through any other system and a licensee cannot
								impose that choice.
								</p>

								<p>
								This section is intended to make thoroughly clear what is believed to
								be a consequence of the rest of this License.
								</p>

								<a name="section8"></a><p>
								<strong>8.</strong>
								 If the distribution and/or use of the Program is restricted in
								certain countries either by patents or by copyrighted interfaces, the
								original copyright holder who places the Program under this License
								may add an explicit geographical distribution limitation excluding
								those countries, so that distribution is permitted only in or among
								countries not thus excluded.  In such case, this License incorporates
								the limitation as if written in the body of this License.
								</p>

								<a name="section9"></a><p>
								<strong>9.</strong>
								 The Free Software Foundation may publish revised and/or new versions
								of the General Public License from time to time.  Such new versions will
								be similar in spirit to the present version, but may differ in detail to
								address new problems or concerns.
								</p>

								<p>
								Each version is given a distinguishing version number.  If the Program
								specifies a version number of this License which applies to it and "any
								later version", you have the option of following the terms and conditions
								either of that version or of any later version published by the Free
								Software Foundation.  If the Program does not specify a version number of
								this License, you may choose any version ever published by the Free Software
								Foundation.
								</p>

								<a name="section10"></a><p>
								<strong>10.</strong>
								 If you wish to incorporate parts of the Program into other free
								programs whose distribution conditions are different, write to the author
								to ask for permission.  For software which is copyrighted by the Free
								Software Foundation, write to the Free Software Foundation; we sometimes
								make exceptions for this.  Our decision will be guided by the two goals
								of preserving the free status of all derivatives of our free software and
								of promoting the sharing and reuse of software generally.
								</p>

								<a name="section11"></a><p><strong>NO WARRANTY</strong></p>

								<p>
								<strong>11.</strong>
								 BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY
								FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW.  EXCEPT WHEN
								OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES
								PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED
								OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
								MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.  THE ENTIRE RISK AS
								TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU.  SHOULD THE
								PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING,
								REPAIR OR CORRECTION.
								</p>

								<a name="section12"></a><p>
								<strong>12.</strong>
								 IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING
								WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY AND/OR
								REDISTRIBUTE THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES,
								INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING
								OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED
								TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY
								YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER
								PROGRAMS), EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE
								POSSIBILITY OF SUCH DAMAGES.
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<input type="hidden" name="option" value="com_hotproperty" />
		<input type="hidden" name="controller" value="about" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="ordering" value="<?php //echo $this->get('ordering'); ?>" />
		<input type="hidden" name="ordering_dir" value="<?php //echo $this->get('ordering_dir'); ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<?php
	}

}
?>