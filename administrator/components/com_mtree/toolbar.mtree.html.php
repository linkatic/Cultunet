<?php
/**
 * @version		$Id: toolbar.mtree.html.php 803 2009-11-24 06:24:21Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */


defined('_JEXEC') or die('Restricted access');

class TOOLBAR_mtree {

	/***
	 * Link
	 */
	function EDITLINK_MENU() {
		
		$task = JRequest::getCmd( 'task', '');
		
		JToolBarHelper::title(  ($task=='newlink') ? JText::_( 'Add listing' ) : JText::_( 'Edit listing' ), 'addedit.png' );

		if($task == 'editlink_for_approval') {
			JToolBarHelper::save( 'savelink', 'Save changes' );
		} else {
			JToolBarHelper::save( 'savelink' );
			JToolBarHelper::apply( 'applylink' );
		}
		JToolBarHelper::cancel( 'cancellink' );
	}

	function MOVELINKS_MENU() {
		JToolBarHelper::title( JText::_( 'Move Link' ), 'addedit.png' );
		JToolBarHelper::save( 'links_move2' );
		JToolBarHelper::custom( 'cancellinks_move', 'cancel.png', 'cancel_f2.png', 'Cancel', false );
	}

	function COPYLINKS_MENU() {
		JToolBarHelper::title( JText::_( 'Copy Link' ), 'addedit.png' );
		JToolBarHelper::save( 'links_copy2' );
		JToolBarHelper::custom( 'cancellinks_copy', 'cancel.png', 'cancel_f2.png', 'Cancel', false );
	}

	/***
	 * Category
	 */
	function EDITCAT_MENU() {
		$task = JRequest::getCmd( 'task', '');

		JToolBarHelper::title( JText::_( ($task=='newcat') ? JText::_( 'Add category' ) : JText::_( 'Edit category' )), 'categories.png' );
		JToolBarHelper::save( 'savecat' );
		JToolBarHelper::apply( 'applycat' );
		JToolBarHelper::cancel( 'cancelcat' );
	}

	function MOVECATS_MENU() {
		JToolBarHelper::title( JText::_( 'Move category' ), 'move_f2.png' );
		JToolBarHelper::save( 'cats_move2' );
		JToolBarHelper::custom( 'cancelcats_move', 'cancel.png', 'cancel_f2.png', 'Cancel', false );
	}

	function COPYCATS_MENU() {
		JToolBarHelper::title( JText::_( 'Copy category' ), 'copy_f2.png' );
		JToolBarHelper::save( 'cats_copy2' );
		JToolBarHelper::custom( 'cancelcats_copy', 'cancel.png', 'cancel_f2.png', 'Cancel', false );
	}

	function REMOVECATS_MENU() {
		JToolBarHelper::title( JText::_( 'Delete' ), 'trash.png' );
		JToolBarHelper::custom( 'removecats2', 'delete.png', 'delete_f2.png', 'Delete', false );
		JToolBarHelper::custom( 'cancelcat', 'cancel.png', 'cancel_f2.png', 'Cancel', false );
	}

	function LISTCATS_MENU() {
		JToolBarHelper::title( '&nbsp;', 'mosetstree' );
		JToolBarHelper::deleteList('','removecats');
		JToolBarHelper::customX( 'cats_copy', 'copy.png', 'copy_f2.png', 'Copy Categories' );
		JToolBarHelper::customX( 'cats_move', 'move.png', 'move_f2.png', 'Move Categories' );
		JToolBarHelper::divider();
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Please make a selection from the list to delete listing(s)', true ) . '\');}else{  submitbutton(\'removelinks\')}" class="toolbar"><span class="icon-32-delete" title="Delete Listings"></span>' . JText::_( 'Delete Listings' ) . '</a>', 'delete-links' );
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Please make a selection from the list to copy listing(s)', true ) . '\');}else{  submitbutton(\'links_copy\')}" class="toolbar"><span class="icon-32-copy" title="' . JText::_( 'Copy Listings' ) . '"></span>' . JText::_( 'Copy Listings' ) . '</a>', 'copy-links' );
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Please make a selection from the list to move listing(s)', true ) . '\');}else{  submitbutton(\'links_move\')}" class="toolbar"><span class="icon-32-move" title="' . JText::_( 'Move Listings' ) . '"></span>' . JText::_( 'Move Listings' ) . '</a>', 'move-links' );
	}

	/***
	 * Approval
	 */
	function LISTPENDING_LINKS_MENU() {
		JToolBarHelper::title( JText::_( 'Pending listing' ), 'addedit.png' );
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Approve and publish listing msg', true ) . '\');}else{  submitbutton(\'approve_publish_links\')}" class="toolbar"><span class="icon-32-publish" title="' . JText::_( 'Approve and publish listing' ) . '"></span>' . JText::_( 'Approve and publish listing' ) . '</a>', 'approve-links' );
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Delete listings msg', true ) . '\');}else{  submitbutton(\'removelinks\')}" class="toolbar"><span class="icon-32-delete" title="' . JText::_( 'Delete listings' ) . '"></span>' . JText::_( 'Delete listings' ) . '</a>', 'delete-links' );
	}

	function LISTPENDING_CATS_MENU() {
		JToolBarHelper::title( JText::_( 'Pending categories' ), 'categories.png' );
		JToolBarHelper::custom( 'approve_publish_cats', 'publish.png', 'publish_f2.png',JText::_( 'Approve and publish' ), true );
		JToolBarHelper::custom( 'approve_cats', 'publish.png', 'publish_f2.png', JText::_( 'Approve categories' ), true );
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'removecats');
	}

	function LISTPENDING_REVIEWS_MENU() {
		JToolBarHelper::title( JText::_( 'Pending reviews' ), 'addedit.png' );
		JToolBarHelper::save( 'save_pending_reviews' );
	}

	function LISTPENDING_REPORTS_MENU() {
		JToolBarHelper::title( JText::_( 'Pending reports' ), 'addedit.png' );
		JToolBarHelper::save( 'save_reports' );
	}

	function LISTPENDING_REVIEWSREPORTS_MENU() {
		JToolBarHelper::title( JText::_( 'Pending reviews reports' ), 'addedit.png' );
		JToolBarHelper::save( 'save_reviewsreports' );
	}

	function LISTPENDING_REVIEWSREPLY_MENU() {
		JToolBarHelper::title( JText::_( 'Pending reviews replies' ), 'addedit.png' );
		JToolBarHelper::save( 'save_reviewsreply' );
	}

	function LISTPENDING_CLAIMS_MENU() {
		JToolBarHelper::title( JText::_( 'Pending claims' ), 'addedit.png' );
		JToolBarHelper::save( 'save_claims' );
	}

	/***
	 * Reviews
	 */
	function LISTREVIEWS_MENU() {
		JToolBarHelper::title( JText::_( 'Reviews' ), 'addedit.png' );
		JToolBarHelper::custom( 'newreview', 'new.png', 'new_f2.png', 'New', false );
		JToolBarHelper::editList( 'editreview' );
		JToolBarHelper::deleteList( '', 'removereviews' );
		JToolBarHelper::divider();
		JToolBarHelper::custom( 'backreview', 'back.png', 'back_f2.png', 'Back', false );
	}

	function EDITREVIEW_MENU() {
		$task = JRequest::getCmd( 'task', '');
		
		JToolBarHelper::title(  (($task=='newreview') ? JText::_( 'Add' ) : JText::_( 'Edit' )) . ' ' . JText::_( 'Review' ), 'addedit.png' );
		JToolBarHelper::save( 'savereview' );
		JToolBarHelper::cancel( 'cancelreview' );
	}

	/***
	*	Search Results
	*/
	function SEARCH_LISTINGS() {
		JToolBarHelper::title( JText::_( 'Search results' ) . ' - ' . JText::_( 'Listings' ) , 'addedit.png' );
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Please make a selection from the list to delete listing(s)' ) . '\');}else{  submitbutton(\'removelinks\')}" class="toolbar"><span class="icon-32-delete" title="Delete Listings"></span>' . JText::_( 'Delete Listings' ) . '</a>', 'delete-links' );
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Please make a selection from the list to copy listing(s)' ) . '\');}else{  submitbutton(\'links_copy\')}" class="toolbar"><span class="icon-32-copy" title="' . JText::_( 'Copy Listings' ) . '"></span>' . JText::_( 'Copy Listings' ) . '</a>', 'copy-links' );
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Please make a selection from the list to move listing(s)' ) . '\');}else{  submitbutton(\'links_move\')}" class="toolbar"><span class="icon-32-move" title="' . JText::_( 'Move Listings' ) . '"></span>' . JText::_( 'Move Listings' ) . '</a>', 'move-links' );
	}

	function SEARCH_CATEGORIES() {
		JToolBarHelper::title( JText::_( 'Search results' ) . ' - ' . JText::_( 'Categories' ) , 'addedit.png' );
		JToolBarHelper::custom( 'editcat', 'edit.png', 'edit_f2.png', 'Edit category', true );
		JToolBarHelper::custom( 'removecats', 'delete.png', 'delete_f2.png', 'Delete categories', true );
		JToolBarHelper::custom( 'cats_move', 'move.png', 'move_f2.png', 'Move categories', true );
	}

	/***
	* Tree Templates
	*/
	function TREE_TEMPLATES() {
		JToolBarHelper::title( JText::_( 'Tree templates' ), 'thememanager' );
		JToolBarHelper::addNew('new_template');
		JToolBarHelper::makeDefault('default_template');
		JToolBarHelper::editList( 'template_pages' );
		JToolBarHelper::custom( 'copy_template', 'copy.png', 'copy_f2.png', 'Copy', true );
		JToolBarHelper::deleteList( '','delete_template' );
	}
	
	function TREE_TEMPLATEPAGES() {
		JToolBarHelper::title( JText::_( 'Tree templates' ), 'thememanager' );
		JToolBarHelper::save( 'save_templateparams' );
		JToolBarHelper::apply( 'apply_templateparams' );
		JToolBarHelper::editList( 'edit_templatepage' );
		JToolBarHelper::cancel( 'cancel_templatepages' );
	}

	function TREE_EDITTEMPLATEPAGE() {
		JToolBarHelper::title( JText::_( 'Template page editor' ), 'thememanager' );
		JToolBarHelper::save( 'save_templatepage' );
		JToolBarHelper::apply( 'apply_templatepage' );
		JToolBarHelper::cancel( 'cancel_edittemplatepage' );
	}
	
	function TREE_NEWTEMPLATE() {
		JToolBarHelper::title( JText::_( 'Upload new template' ), 'thememanager' );
		JToolBarHelper::cancel( 'cancel_templatepages' );
	}
	
	function TREE_COPYTEMPLATE() {
		JToolBarHelper::title( JText::_( 'Copy Template' ), 'thememanager' );
		JToolBarHelper::save( 'copy_template2' );
		JToolBarHelper::cancel( 'cancel_templatepages' );
	}
	
	
	/***
	* Advanced Search
	*/
	function ADVSEARCH() {
		JToolBarHelper::title( JText::_( 'Advanced search' ) );
	}
	
	function ADVSEARCH2() {
		JToolBarHelper::title( JText::_( 'Advanced search results' ) );
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Please make a selection from the list to delete listing(s)' ) . '\');}else{  submitbutton(\'removelinks\')}" class="toolbar"><span class="icon-32-delete" title="Delete Listings"></span>' . JText::_( 'Delete Listings' ) . '</a>', 'delete-links' );
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Please make a selection from the list to copy listing(s)' ) . '\');}else{  submitbutton(\'links_copy\')}" class="toolbar"><span class="icon-32-copy" title="' . JText::_( 'Copy Listings' ) . '"></span>' . JText::_( 'Copy Listings' ) . '</a>', 'copy-links' );
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(document.adminForm.link_boxchecked.value==0){alert(\'' . JText::_( 'Please make a selection from the list to move listing(s)' ) . '\');}else{  submitbutton(\'links_move\')}" class="toolbar"><span class="icon-32-move" title="' . JText::_( 'Move Listings' ) . '"></span>' . JText::_( 'Move Listings' ) . '</a>', 'move-links' );
	}
	
	/***
	* Configuration
	*/
	function CONFIG_MENU() {
		JToolBarHelper::title( JText::_( 'Configuration' ), 'config.png' );
		JToolBarHelper::save('saveconfig');
		JToolBarHelper::back();
	}
	
	/***
	* Custom Fields
	*/
	function CUSTOM_FIELDS() {
		JToolBarHelper::title( JText::_( 'Custom fields' ), 'module' );
		JToolBarHelper::publishList('cf_publish');
		JToolBarHelper::unpublishList('cf_unpublish');
		JToolBarHelper::divider();
		JToolBarHelper::custom( 'newcf', 'new.png', 'new_f2.png', 'New', false );
		JToolBarHelper::deleteList( '', 'removecf' );
	}
	
	function EDIT_CUSTOM_FIELDS() {
		$cf_id = JRequest::getInt( 'cfid' );
		JToolBarHelper::title( JText::_( 'Custom field' ) . ': ' . (($cf_id)?'Edit' : 'New')  , 'module' );
		JToolBarHelper::save( 'savecf' );
		JToolBarHelper::apply( 'applycf' );
		JToolBarHelper::cancel( 'cancelcf' );
	}
	
	function EDIT_FIELD_TYPE() {
		$ft_id = JRequest::getVar( 'cfid', array() );
		JToolBarHelper::title( ((isset($ft_id[0]) && $ft_id[0])?JText::_( 'Field type' ) . ': Edit' : JText::_( 'Install new field type' ))  , 'install.png' );
		JToolBarHelper::save( 'saveft' );
		JToolBarHelper::apply( 'applyft' );
		JToolBarHelper::cancel( 'cancelft' );
	}
	
	function MANAGE_FIELD_TYPES() {
		JToolBarHelper::title( JText::_( 'Installed field types' ), 'install.png' );
		JToolBarHelper::custom( 'newft', 'new.png', 'new_f2.png', 'Add', false );
		JToolBarHelper::editList( 'editft' );
		JToolBarHelper::deleteList( '', 'removeft', 'Uninstall' );
	}

	/***
	* Link Checker
	*/
	function LINKCHECKER_MENU() {
		JToolBarHelper::save('linkchecker');
	}
	
	/***
	* Spy
	*/
	function SPY_VIEWUSER_MENU() {
		JToolBarHelper::title( JText::_( 'User' ), 'user' );
		JToolBarHelper::deleteList();
	}
}
?>