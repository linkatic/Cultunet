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
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieViewBookmarks extends JView {

	function display($tpl = null)
	{
		global $mainframe, $option;

		// get layout
		$task = strtolower(JRequest::getVar('task'));
		if($task == 'edit' || $task == 'add')
		{
			$this->setLayout('form');
			$this->_displayForm($tpl);
			return;
		}

		//initialise variables
		$user 		= & JFactory::getUser();
		$db  		= & JFactory::getDBO();
		$document	= & JFactory::getDocument();
		
		JHTML::_('behavior.tooltip');

		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.bookmarks.filter_order', 		'filter_order', 	'website', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.bookmarks.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.bookmarks.filter_state', 		'filter_state', 	'*', 'word' );
		$filter_type 		= $mainframe->getUserStateFromRequest( $option.'.bookmarks.filter_type', 		'filter_type', 		'*', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.bookmarks.search', 			'search', 			'', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		//add css and submenu to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		JToolBarHelper::title( JText::_( 'BOOKMARKS' ), 'lbbookmarks' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::deleteList();
		JToolBarHelper::spacer();

		//Get data from the model
		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );

		//publish unpublished filter
		$lists['state']	= JHTML::_('grid.state', $filter_state, JText::_('Enabled'), JText::_('Disabled') );

		//build arphaned/assigned filter
		$assigned 	= array();
		$types[] = JHTML::_('select.option',  '', '- '. JText::_( 'Select Type' ) .' -' );
		$types[] = JHTML::_('select.option',  'button', JText::_( 'Button' ) );
		$types[] = JHTML::_('select.option',  'badge', JText::_( 'Badge' ) );

		$lists['types'] = JHTML::_('select.genericlist', $types, 'filter_type', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_type );
		
		// search filter
		$lists['search']= $search;

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$ordering = ($lists['order'] == 'c.ordering');

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('ordering'		, $ordering);

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe;

		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$editor 	= & JFactory::getEditor();
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		$pane 		= & JPane::getInstance('sliders');

		//get vars
		$id 		= JRequest::getVar( 'id' );

		//add css to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		if ( $id ) {
			JToolBarHelper::title( JText::_( 'EDIT BOOKMARK' ), 'lbbookmarks' );

		} else {
			JToolBarHelper::title( JText::_( 'NEW BOOKMARK' ), 'lbbookmarks' );
		}
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::divider();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		//Get data from the model
		$row = & $this->get( 'Data' );

		//build arphaned/assigned filter
		$assigned 	= array();
		$types[] = JHTML::_('select.option',  '', '- '. JText::_( 'SELECT TYPE' ) .' -' );
		$types[] = JHTML::_('select.option',  'button', JText::_( 'BUTTON' ) );
		$types[] = JHTML::_('select.option',  'badge', JText::_( 'BADGE' ) );

		$types = JHTML::_('select.genericlist', $types, 'type', 'class="inputbox" size="1"', 'value', 'text', $row->type );
		
		//assign vars to view
		$this->assignRef('types'      	, $types);
		$this->assignRef('row'      	, $row);

		parent::display($tpl);
	}
}
?>