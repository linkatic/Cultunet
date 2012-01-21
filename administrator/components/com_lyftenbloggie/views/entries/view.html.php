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
 * HTML View class for the entries View
 *
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieViewEntries extends JView {

	function display($tpl = null)
	{
		global $mainframe, $option;
		
		//initialise variables
		$user 		= & JFactory::getUser();
		$db  		= & JFactory::getDBO();
		$document	= & JFactory::getDocument();
		$config		=& JFactory::getConfig();
		$now		=& JFactory::getDate();
		$nullDate 	= $db->getNullDate();
		
		JHTML::_('behavior.tooltip');

		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.entries.filter_order', 	'filter_order', 	'c.created', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.entries.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.entries.filter_state', 	'filter_state', 	'', 'cmd' );
		$filter_type 		= $mainframe->getUserStateFromRequest( $option.'.entries.filter_type', 		'filter_type', 		'', 'cmd' );
		$filter_toplevel	= JArrayHelper::getValue( $_REQUEST, 'filter_toplevel', '' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.entries.search', 			'search', 			'', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		//add css and submenu to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		JToolBarHelper::title( JText::_( 'ENTRIES' ), 'lbentries' );
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

		// build the html for published		
		$states[] = JHTML::_('select.option',  '', ' - '.JText::_( 'SELECT STATE' ).' - ' );
		$states[] = JHTML::_('select.option',  '1', JText::_( 'PUBLISHED' ) );
		$states[] = JHTML::_('select.option',  '2', JText::_( 'PENDING REVIEW' ) );
		$states[] = JHTML::_('select.option',  '-1', JText::_( 'UNPUBLISHED' ) );
		$lists['state'] = JHTML::_('select.genericlist', $states, 'filter_state', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_state );		

		// build the search for select
		$type[] = JHTML::_('select.option',  '', JText::_( 'ALL PARTS' ) );
		$type[] = JHTML::_('select.option',  '1', JText::_( 'TITLE' ) );
		$type[] = JHTML::_('select.option',  '2', JText::_( 'DESCRIPTION' ) );
		$type[] = JHTML::_('select.option',  '3', JText::_( 'AUTHOR' ) );
		$lists['searchtype'] = JHTML::_('select.genericlist', $type, 'filter_type', 'class="inputbox" size="1""', 'value', 'text', $filter_type );		

		// build category select
		$lists['categories'] = & $this->get( 'Categories' );

		// search filter
		$lists['search']= $search;

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('ordering'		, $ordering);
		$this->assignRef('user'			, $user);
		$this->assignRef('config'		, $config);
		$this->assignRef('now'			, $now);
		$this->assignRef('nullDate'		, $nullDate);

		parent::display($tpl);
	}
}
?>