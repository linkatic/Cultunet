<?php
/**
 * @version		$Id: toolbar.mtree.php 803 2009-11-24 06:24:21Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */


defined('_JEXEC') or die('Restricted access');

require_once( JApplicationHelper::getPath( 'toolbar_html' ) );
require_once( JApplicationHelper::getPath( 'toolbar_default' ) );

$task2	= strval(JRequest::getCmd( 'task2', ''));

switch ($task) {

	/***
	 * Link
	 */
	case "newlink":
	case "editlink":
	case "editlink_for_approval":
	case "editlink_change_cat":
	case "editlink_browse_cat":
	case "editlink_add_cat":
	case "editlink_remove_cat":
		TOOLBAR_mtree::EDITLINK_MENU();
		break;

	case "links_move":
		TOOLBAR_mtree::MOVELINKS_MENU();
		break;

	case "links_copy":
		TOOLBAR_mtree::COPYLINKS_MENU();
		break;

	/***
	 * Pending / Approval
	 */
	case "listpending_links":
		TOOLBAR_mtree::LISTPENDING_LINKS_MENU();
		break;
	case "listpending_cats":
		TOOLBAR_mtree::LISTPENDING_CATS_MENU();
		break;
	case "listpending_reviews":
		TOOLBAR_mtree::LISTPENDING_REVIEWS_MENU();
		break;
	case "listpending_reports":
		TOOLBAR_mtree::LISTPENDING_REPORTS_MENU();
		break;
	case "listpending_reviewsreports":
		TOOLBAR_mtree::LISTPENDING_REVIEWSREPORTS_MENU();
		break;
	case "listpending_reviewsreply":
		TOOLBAR_mtree::LISTPENDING_REVIEWSREPLY_MENU();
		break;
	case "listpending_claims":
		TOOLBAR_mtree::LISTPENDING_CLAIMS_MENU();
		break;

	/***
	 * Reviews
	 */

	case "newreview":
	case "editreview":
		TOOLBAR_mtree::EDITREVIEW_MENU();
		break;

	case "reviews_list":
		TOOLBAR_mtree::LISTREVIEWS_MENU();
		break;

	/***
	 * Category
	 */
	case "newcat":
	case "editcat":
	case "editcat_browse_cat":
	case "editcat_add_relcat":
	case "editcat_remove_relcat":
		TOOLBAR_mtree::EDITCAT_MENU();
		break;

	case "cats_move":
		TOOLBAR_mtree::MOVECATS_MENU();
		break;

	case "cats_copy":
		TOOLBAR_mtree::COPYCATS_MENU();
		break;
	
	case "removecats":
		TOOLBAR_mtree::REMOVECATS_MENU();
		break;

	case "listcats":
	default:
		TOOLBAR_mtree::LISTCATS_MENU();
		break;
	
	/***
	* Search Results
	*/
	case "search":
		$search_where	= JRequest::getInt( 'search_where', '');
		if ( $search_where == 1 ) {
			TOOLBAR_mtree::SEARCH_LISTINGS();
		} else {
			TOOLBAR_mtree::SEARCH_CATEGORIES();
		}
		break;

	/***
	* Tree Templates
	*/
	case "templates":
		TOOLBAR_mtree::TREE_TEMPLATES();
		break;
	case "template_pages":
		TOOLBAR_mtree::TREE_TEMPLATEPAGES();
		break;
	case "edit_templatepage":
		TOOLBAR_mtree::TREE_EDITTEMPLATEPAGE();
		break;
	case "new_template":
		TOOLBAR_mtree::TREE_NEWTEMPLATE();
		break;
	case "copy_template":
		TOOLBAR_mtree::TREE_COPYTEMPLATE();
		break;

	
	/***
	* Advanced Search
	*/
	case "advsearch":
		TOOLBAR_mtree::ADVSEARCH();
		break;
	case "advsearch2":
		TOOLBAR_mtree::ADVSEARCH2();
		break;

	/***
	* Configuration
	*/
	case "config":
		TOOLBAR_mtree::CONFIG_MENU();
		break;
	
	/***
	* Custom Fields
	*/
	case "customfields":
		TOOLBAR_mtree::CUSTOM_FIELDS();
		break;
	case "editcf":
	case "newcf":
		TOOLBAR_mtree::EDIT_CUSTOM_FIELDS();
		break;
	case 'managefieldtypes':
		TOOLBAR_mtree::MANAGE_FIELD_TYPES();
		break;
	case "editft":
	case "newft":
		TOOLBAR_mtree::EDIT_FIELD_TYPE();
		break;

	/***
	* Link Checker
	*/
	case 'linkchecker':
		TOOLBAR_mtree::LINKCHECKER_MENU();
		break;

	/***
	* Geocode
	*/
	case 'geocode':
		JToolBarHelper::title( JText::_( 'Locate Listings in Map' ) );
		break;
		
	/***
	* Spy
	*/
	case 'spy':
		switch($task2) {
			case 'viewuser':
				TOOLBAR_mtree::SPY_VIEWUSER_MENU();
				break;
			default:
				JToolBarHelper::title( JText::_( 'Spy Directory' ) );
				break;
		}
		break;
	/***
	* Export
	*/
	case "csv":
		JToolBarHelper::title( JText::_( 'Export' ) );
		break;
	case "csv_export":
		JToolBarHelper::back();
		JToolBarHelper::title( JText::_( 'Export' ) );
		break;
	/***
	* About / License / Upgrade
	*/
	case "about":
		JToolBarHelper::title( JText::_( 'About mosets tree' ), 'systeminfo.png' );
		break;
	case "license":
		JToolBarHelper::title( JText::_( 'License agreement' ) );
		break;
	case "upgrade":
		break;
//	default:
//		MENU_Default::MENU_Default();
//		break;
}
?>