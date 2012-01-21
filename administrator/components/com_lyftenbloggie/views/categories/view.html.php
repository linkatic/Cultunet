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
class LyftenBloggieViewCategories extends JView {

	function display($tpl = null)
	{
		global $mainframe, $option;

		//initialise variables
		$document	= & JFactory::getDocument();
		$task 		= strtolower(JRequest::getVar('task'));
		
		// get layout
		if($task == 'edit' || $task == 'add')
		{
			$this->setLayout('form');
			$this->_displayForm($tpl);

			return;
		}

		JHTML::_('behavior.tooltip');

		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.categories.filter_order', 		'filter_order', 	'c.ordering', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.categories.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.categories.filter_state', 		'filter_state', 	'*', 'word' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.categories.search', 			'search', 			'', 'string' );
		$search 			= trim(JString::strtolower( $search ) );

		//add css and submenu to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		JToolBarHelper::title( JText::_( 'CATEGORIES' ), 'lbcategories' );
		JToolBarHelper::makeDefault('makeDefault');
		JToolBarHelper::divider();
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
		$lists['state']	= JHTML::_('grid.state', $filter_state );
		
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
		$this->assignRef('user'			, $user);

		parent::display($tpl);
	}
	
	/**
	 * Creates the Category Edit page
	 *
	 * @since 1.1.0
	 **/
	function _displayForm( $tpl = null )
	{
		global $mainframe;

		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		$pane 		= & JPane::getInstance('sliders');

		//get vars
		$id 		= JRequest::getVar( 'id' );

		//add css to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		if ( $id ) {
			JToolBarHelper::title( JText::_( 'EDIT CATEGORY' ), 'lbcategory' );
			JToolBarHelper::apply();
		} else {
			JToolBarHelper::title( JText::_( 'NEW CATEGORY' ), 'lbcategory' );
		}
		JToolBarHelper::save();
		JToolBarHelper::divider();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		//Get data from the model
		$model		= & $this->getModel();
		$row     	= & $this->get( 'Data' );

		//fail if checked out not by 'me'
		if ($row->id) {
			if ($model->isCheckedOut( $user->get('id') )) {
				JError::raiseWarning( 'SOME_ERROR_CODE', $row->title.' '.JText::_( 'EDITED BY ANOTHER ADMIN' ));
				$mainframe->redirect( 'index.php?option=com_lyftenbloggie&view=categories' );
			}
		}

		//clean data
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'text' );
			
		//assign vars to view
		$this->assignRef('Lists'      	, $Lists);
		$this->assignRef('row'      	, $row);
		$this->assignRef('pane'			, $pane);

		parent::display($tpl);
	}
}
?>