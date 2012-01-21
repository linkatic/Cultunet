<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the User View
 *
 * @package Joomla
 * @subpackage Brezza
 * @since 1.1.0
 */
class LyftenBloggieViewAuthor extends JView
{
	/**
	 * Display the view
	 **/
	function display( $tpl = null )
	{	
		//initialise variables
		$layout 	= JRequest::getVar('layout');

		//Check User Access
		BloggieFactory::allowAccess('author.author_access', true);

		// get layout
		switch ($layout)
		{
			case 'profile':
				$this->setLayout('profile');
				$this->profile($tpl);
				break;

			case 'pending':
				BloggieFactory::allowAccess('admin.can_approve', true);
				$this->setLayout('pending');
				$this->pending($tpl);
				break;

			case 'form':
			case 'edit':
				$this->setLayout('form');
				$this->entryForm($tpl);
				break;

			case 'entries':
			default:
				$this->entries($tpl);
				break;
		}

		return;
	}

	/**
	 * Creates the entries page
	 */
	function entries( $tpl )
	{
		global $mainframe;

		//initialize variables
		$document 	= & JFactory::getDocument();
		$user 		= &BloggieAuthor::getInstance();

		//Load route helper
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');

		//Set theme Style
		$theme = BloggieTemplate::getInstance();
		$theme->setStyles(true);

		// Get data from the model
		$entries		= & $this->get('MyEntries');
		$totalentries	= & $this->get('Total');
		$pageNav 		= & $this->get('Pagination');

		/*
		 * Create the document title
		 **/
		$document->setTitle($user->get('username').'\'s '.JText::_('ENTRIES'));

		//pathway
		$pathway 	= & $mainframe->getPathWay();
		$pathway->addItem( $this->escape($user->get('username').'\'s '.JText::_('ENTRIES')) );

		//assign variables to template
		$this->assignRef('entries' , 		$entries);
		$this->assignRef('totalentries' , 	$totalentries);
		$this->assignRef('pageNav' ,		$pageNav);
		$this->assignRef('photo' ,			$theme->get('left_object'));

		parent::display($tpl);

	}

	/**
	 * Creates the pending entries page
	 */
	function pending( $tpl )
	{
		global $mainframe;

		//initialize variables
		$document 	= & JFactory::getDocument();
		$user 		= &BloggieAuthor::getInstance();

		//Load route helper
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');

		//Set theme Style
		$theme = BloggieTemplate::getInstance();
		$theme->setStyles(true);

		// Get data from the model
		$entries		= & $this->get('MyEntries');
		$totalentries	= & $this->get('Total');
		$pageNav 		= & $this->get('Pagination');

		/*
		 * Create the document title
		 **/
		$document->setTitle($user->get('username').'\'s '.JText::_('ENTRIES'));

		//pathway
		$pathway 	= & $mainframe->getPathWay();
		$pathway->addItem( $this->escape($user->get('username').'\'s '.JText::_('ENTRIES')) );

		//assign variables to template
		$this->assignRef('entries' , 		$entries);
		$this->assignRef('totalentries' , 	$totalentries);
		$this->assignRef('pageNav' ,		$pageNav);
		$this->assignRef('photo' ,			$theme->get('left_object'));

		parent::display($tpl);

	}

	/**
	 * Creates the entry edit page
	 */
	function entryForm($tpl)
	{
		global $mainframe;

		//Load pane behavior
		jimport('joomla.html.pane');

		//Initialize variables
		$document	= & JFactory::getDocument();
		$db  		= & JFactory::getDBO();
		$params		= & $mainframe->getParams('com_lyftenbloggie');
		$nullDate 	= $db->getNullDate();	
		$uri     	= & JFactory::getURI();
		$return		= JRequest::getVar('return');
		$settings 	= & BloggieSettings::getInstance();
		$author 	= BloggieAuthor::getInstance();
		$access 	= BloggieAccess::getInstance();
		$lists 		= array();
		$image_list = array();
		$disabled	= '';

		//Add the js includes to the document <head> section
		JHTML::_('behavior.formvalidation');
		JHTML::_('behavior.tooltip');

		// load entry
		$entry 	= & BloggieEntry::getInstance();
		$row	= & $entry->getEntry();
	
		// fail if checked out not by 'me'
		if ($row->id) {
			if ($entry->isCheckedOut( $access->get('user.id') )) {
				JError::raiseWarning( 'SOME_ERROR_CODE', '<b>'.$row->title.'</b> '.JText::_( 'EDITED BY ANOTHER ADMIN' ));
				$mainframe->redirect( JRoute::_('index.php?option=com_lyftenbloggie&view=entry&id='.$row->id, false) );
			}
		}

		//check if we can edit an entry
		if( !$access->get('admin.admin_access') )
		{
			if( !$access->get('author.author_access')) {
				JError::raiseWarning( 'SOME_ERROR_CODE', JText::_("YOU MUST BE AN AUTHOR TO VIEW THIS RESOURCE"));
				$mainframe->redirect( JRoute::_('index.php?option=com_lyftenbloggie', false) );
			}elseIf($row->created_by != $access->get('user.id')){
				JError::raiseWarning( 'SOME_ERROR_CODE', JText::sprintf('NOT THE AUTHOR', $row->title));
				$mainframe->redirect( JRoute::_('index.php?option=com_lyftenbloggie', false) );
			}
		}

		//Set theme Style
		$theme = BloggieTemplate::getInstance();
		$theme->setStyles(true);

		//add header tags
		$document->addScript(JURI::base().'components/com_lyftenbloggie/assets/js/author.js');
		
		//Get more data from the model
		$lists['catid']   	= & $entry->getCategories();
		$lists['tags']		= & $entry->getTagsList();	
		$lists['created']	= & $entry->getCreated();	

		//Get Author Display Images
		$author_folders = $author->checkFolders();
	    $display_images = JFolder::files( $author_folders['base'].DS.'display', '.', false, false, array('index.html') );
	    $image_list[] = JHTML::_('select.option','', JText::_('No picture select'));
	    foreach ($display_images as $image) {
	        if (eregi( "gif|jpg|png", $image )) {
	            $image_list[] = JHTML::_('select.option', $image );
	        }
	    }
		$img = ($row->image) ? $row->image : 'default.png';
	    $lists['images'] = JHTML::_('select.genericlist',  $image_list, 'image', "class=\"inputbox\" size=\"8\""
	  . " onchange=\"changeImage(this.value);\"", 'value', 'text', $img );
	    $lists['image'] = '<img src="'.$author_folders['url'].'/display/'.$img.'" id="entryimg" name="entryimg" border="1" height="130px" border-color="#555555" alt="" />';
		unset($image_list);
		unset($author);

		// build the html for published
		$states[] = JHTML::_('select.option',  '1', JText::_( 'PUBLISHED' ) );
		$states[] = JHTML::_('select.option',  '2', JText::_( 'PENDING REVIEW' ) );
		$states[] = JHTML::_('select.option',  '-1', JText::_( 'UNPUBLISHED' ) );
		$states[] = JHTML::_('select.option',  '3', JText::_( 'PENDING DELETION' ) );
		if (!$access->get('author.can_publish')) {
			$disabled 	= ' disabled="true"';
			$row->state = (!$row->id) ? 2 : $row->state;
		}
		$lists['state'] = JHTML::_('select.genericlist', $states, 'state', 'class="inputbox" size="1"'.$disabled.'', 'value', 'text', $row->state );

		// build the html for access
		$access = BloggieFactory::getAccesslevels();
		$lists['access'] = JHTML::_('select.genericlist', $access, 'access', 'class="inputbox" size="1"', 'value', 'text', $row->access );

		// Create the form
		$form = new JParameter('', JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'entry.xml');

		// Advanced Group
		$form->loadINI($row->attribs);
		$form->loadINI($row->metadata);

		// Enabled/Disabled
		$enabled[] = JHTML::_('select.option',  '0', JText::_( 'DISABLED' ) );
		$enabled[] = JHTML::_('select.option',  '1', JText::_( 'ENABLED' ) );

		$lists['comments'] 		= ($settings->get('typeComments')) ? JHTML::_('select.genericlist', $enabled, 'params[allow_comments]', 'class="inputbox" size="1"', 'value', 'text', $form->get('allow_comments') ) : '';
		$lists['ratings'] 		= JHTML::_('select.genericlist', $enabled, 'params[show_vote]', 'class="inputbox" size="1"', 'value', 'text', $form->get('show_vote') );
		$lists['hits'] 			= JHTML::_('select.genericlist', $enabled, 'params[show_hits]', 'class="inputbox" size="1"', 'value', 'text', $form->get('show_hits') );
		$lists['favourites'] 	= JHTML::_('select.genericlist', $enabled, 'params[show_favourites]', 'class="inputbox" size="1"', 'value', 'text', $form->get('show_favourites') );

		// Make trackback list
		if($row->pinged) {
			$pinged 		= trim($row->pinged);
			$row->pinged 	= explode("\n", $pinged);
		}

		//Load the Editor
		$editor = BloggieFactory::getEditor($settings->get( 'frontEditor', 'joomla' ));

		//Build the page title string
		$title = $row->id ? JText::_('EDIT ENTRY') : JText::_('NEW ENTRY');

		// Set page title
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		// Get the menu item object
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();
		$params->set( 'page_title', $params->get( 'page_title' ) );
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$title);
			}
		} else {
			$params->set('page_title', $title);
		}
		$document->setTitle( $params->get( 'page_title' ) );

		//Set page title
		$document->setTitle($title);

		//get pathway
		$pathway =& $mainframe->getPathWay();
		$pathway->addItem($title, '');

		// Unify the introtext and fulltext fields and separated the fields by the readmore tag
		if (JString::strlen($row->fulltext) > 1) {
			$row->text = $row->introtext."<hr id=\"system-readmore\" />".$row->fulltext;
		} else {
			$row->text = $row->introtext;
		}

		//Ensure the row data is safe html
		JFilterOutput::objectHTMLSafe( $row );
		$this->assign('action'				, $uri->toString());
		$this->assignRef('form'				, $form);
		$this->assignRef('nullDate'			, $nullDate);
		$this->assignRef('row'				, $row);
		$this->assignRef('lists'			, $lists);
		$this->assignRef('title'			, $title);
		$this->assignRef('return'			, $return);
		$this->assignRef('author_folders'	, $author_folders);
		$this->assignRef('editor'			, $editor);
		$this->assignRef('editType'			, $settings->get( 'frontEditor', 0 ));
		$this->assignRef('photo' 			, $theme->get('left_object'));

		parent::display($tpl);
	}

	/**
	 * Creates the profile page
	 */
	function profile( $tpl )
	{
		global $mainframe;

		//initialise variables
		$document 	= & JFactory::getDocument();
		$uri     	= & JFactory::getURI();
		$settings 	= & BloggieSettings::getInstance();

		//Load route helper
		require_once (JPATH_SITE.DS.'components'.DS.'com_lyftenbloggie'.DS.'helpers'.DS.'route.php');

		//add header tags
		$document->addScript(JURI::base().'components/com_lyftenbloggie/assets/js/author.js');

		//Set theme Style
		$theme = BloggieTemplate::getInstance();
		$theme->setStyles(true);

		// Get Author data
		$author	= &BloggieAuthor::getInstance();

		//pathway
		$pathway = & $mainframe->getPathWay();
		$pathway->addItem( JText::_('MY PROFILE'));

		//assign vars to view
		JFilterOutput::objectHTMLSafe( $row );

		$this->assign('action'			, $uri->toString());
		$this->assignRef('author'      	, $author);
		$this->assignRef('avatarUsed'  	, $settings->get('avatarUsed'));

		parent::display($tpl);
	}

	/**
	 * Clean up all entries for tge display
	 **/
	function _prepareEntry(&$row)
	{
		//initialise variables
		$settings 	= & BloggieSettings::getInstance();

		$row->category	= EntriesHelper::getCatLinks($row->catid, true);
		$row->created	= JHTML::_('date',  $row->created, $settings->get('dateFormat', '%B %d, %Y'));
		$row->archive	= JHTML::_('date',  $row->created, '&year=%Y&month=%m&day=%d');

		//check if entry is editable
		$row->editable = false;
		if(BloggieFactory::canEdit($row->id, $row->created_by))
		{
			$uri = & JFactory::getURI();
			$row->editable = JRoute::_('/index.php?option=com_lyftenbloggie&view=author&layout=edit&id='. $row->slug.'&return='.base64_encode($uri->toString()));
			unset($uri);
		}

		return $row;
	}
}
?>