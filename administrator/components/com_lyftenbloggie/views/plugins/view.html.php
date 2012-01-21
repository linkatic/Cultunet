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

jimport( 'joomla.application.component.view' );

/**
 * @package Joomla
 * @subpackage Brezza
 * @since 1.0.2
 */
class LyftenBloggieViewPlugins extends JView
{
	/**
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		global $mainframe;

		//initialise variables
		$document	= & JFactory::getDocument();
		$task		= JRequest::getVar('task');
		$lists		= array();

		// Lets get some HELP!!!!
		require_once (JPATH_COMPONENT.DS.'helper.php');
		
		//add stuff to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');	
		$document->addScript('components/com_lyftenbloggie/assets/js/help.js');	

		//Get data from the model
		$rows    = & $this->get( 'Plugins');
		
		//assign data to template
		$this->assignRef('rows', $rows);
		
		if(!$task)
		{
			$filter_type 		= $mainframe->getUserStateFromRequest('com_lyftenbloggie.plugins.filter_type', 'filter_type', '', 'cmd');
			$filter_order		= $mainframe->getUserStateFromRequest('com_lyftenbloggie.plugins.filter_order', 'filter_order', 'p.type', 'cmd');
			$filter_order_Dir	= $mainframe->getUserStateFromRequest('com_lyftenbloggie.plugins.filter_order_Dir', 'filter_order_Dir', '', 'word');
			$filter_state 		= $mainframe->getUserStateFromRequest('com_lyftenbloggie.plugins.filter_state', 'filter_state', '*', 'word');

			// build the html for published		
			$types = & $this->get( 'Types');
			$lists['types'] = JHTML::_('select.genericlist', $types, 'filter_type', 'class="inputbox" size="1" onchange="submitform();"', 'value', 'text', $filter_type );	

			//publish unpublished filter
			$lists['state']	= JHTML::_('grid.state', $filter_state );

			// table ordering
			$lists['order_Dir'] = $filter_order_Dir;
			$lists['order'] 	= $filter_order;

			//create the toolbar		
			JToolBarHelper::title( JText::_( 'SETTINGS' ).': '.JText::_('PLUGINS'), 'lbconfig' );
			JToolBarHelper::editList();
			JToolBarHelper::divider();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::divider();
			JToolBarHelper::deleteList();
			JToolBarHelper::spacer();
			
			$pageNav = & $this->get( 'Pagination' );
			
			//assign data to template
			$this->assignRef('lists'			, $lists);
			$this->assignRef('pageNav'			, $pageNav);
		}else{
			//create the toolbar
			JToolBarHelper::title( JText::_( 'SETTINGS' ).': '.JText::_('EDIT PLUGIN'), 'lbconfig' );

			JToolBarHelper::save();
			JToolBarHelper::apply();
			JToolBarHelper::divider();
			JToolBarHelper::cancel();
			JToolBarHelper::spacer();		
		}
		parent::display($tpl);
	}
}
