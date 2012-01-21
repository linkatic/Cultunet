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
class LyftenBloggieViewProfiles extends JView {

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
		$db  			= & JFactory::getDBO();
		$document		= & JFactory::getDocument();
		$settings 		= & BloggieSettings::getInstance();

		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.profiles.filter_order', 	'filter_order', 	'u.name', 	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.profiles.filter_order_Dir','filter_order_Dir',	'', 		'word' );
		$filter_type 		= $mainframe->getUserStateFromRequest( $option.'.profiles.filter_type', 	'filter_type', 		'', 		'string' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.profiles.search', 			'search', 			'', 		'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		//add css and submenu to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		JToolBarHelper::title( JText::_( 'PROFILES' ), 'lbprofiles' );
		JToolBarHelper::addNew();
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'reset', JText::_('RESET SETTINGS'));
		JToolBarHelper::spacer();

		//Get data from the model
		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );

		//publish type filter
		$types = BloggieFactory::getAccesslevels();
		$type  = array(JHTML::_('select.option',  0, '- '. JText::_( 'SELECT TYPE' ) .' -'));
		$types = array_merge($type, $types);
		$lists['type'] = JHTML::_('select.genericlist',   $types, 'filter_type', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', "$filter_type" );

		// search filter
		$lists['search']= $search;

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$ordering = ($lists['order'] == 'a.name');

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('ordering'		, $ordering);
		$this->assignRef('user'			, $user);

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		global $mainframe;

		//initialise variables
		$document	= & JFactory::getDocument();
		$user 		= & JFactory::getUser();
		$id 		= JRequest::getVar( 'id' );

		//add css to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');
		$document->addScript('components/com_lyftenbloggie/assets/js/help.js');	

		//Get data from the model
		$model		= & $this->getModel();
		$row     	= & $this->get( 'Data' );

		//create the toolbar
		if ( isset($row->name) && $row->name ) {
			JToolBarHelper::title( $row->name.'\'s '.JText::_( 'PROFILE' ), 'lbprofile' );

		} else {
			JToolBarHelper::title( JText::_( 'NEW PROFILE' ), 'lbprofile' );
		}
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::divider();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		//clean data
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'about' );
		
		//Get Params
		$params 		= new JParameter( $row->attribs );

		JHTML::_('behavior.tooltip');

		//assign vars to view
		$this->assignRef('params'      	, $params);
		$this->assignRef('row'      	, $row);
		$this->assignRef('tabs'			, $tabs);

		parent::display($tpl);
	}
}
?>