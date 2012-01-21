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
class LyftenBloggieViewTags extends JView {

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
		$db  		= & JFactory::getDBO();
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		
		JHTML::_('behavior.tooltip');

		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.tags.filter_order', 		'filter_order', 	't.name', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.tags.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$filter_assigned	= $mainframe->getUserStateFromRequest( $option.'.tags.filter_assigned', 'filter_assigned', '*', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.tags.search', 			'search', 			'', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		//add css and submenu to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		JToolBarHelper::title( JText::_( 'TAGS' ), 'lbtags' );
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

		$lists = array();
		
		//build arphaned/assigned filter
		$assigned 	= array();
		$assigned[] = JHTML::_('select.option',  '', '- '. JText::_( 'ALL TAGS' ) .' -' );
		$assigned[] = JHTML::_('select.option',  'O', JText::_( 'ORPHANED' ) );
		$assigned[] = JHTML::_('select.option',  'A', JText::_( 'ASSIGNED' ) );

		$lists['assigned'] = JHTML::_('select.genericlist', $assigned, 'filter_assigned', 'class="inputbox" size="1" onchange="submitform( );"', 'value', 'text', $filter_assigned );
		
		// search filter
		$lists['search']= $search;

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('user'      	, $user);
		$this->assignRef('pageNav' 		, $pageNav);

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe;

		//initialise variables
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();

		//get vars
		$id 		= JRequest::getVar( 'id' );

		//add css to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		if ( $id ) {
			JToolBarHelper::title( JText::_( 'EDIT TAG' ), 'lbtag' );
		} else {
			JToolBarHelper::title( JText::_( 'ADD TAG' ), 'lbtag' );
		}
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::divider();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();


		//Get data from the model
		$model		= & $this->getModel();
		$row     	= & $this->get( 'Data' );

		//clean data
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES );

		//assign data to template
		$this->assignRef('row', $row);

		parent::display($tpl);
	}
}
?>