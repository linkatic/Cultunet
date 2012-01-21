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
class LyftenBloggieViewGroups extends JView {

	function display($tpl = null)
	{
		global $mainframe;

		//initialise variables
		$document	= & JFactory::getDocument();
		$task 		= strtolower(JRequest::getVar('task'));

		// Lets get some HELP!!!!
		require_once (JPATH_COMPONENT.DS.'helper.php');

		// get layout
		if($task == 'edit' || $task == 'add')
		{
			$this->setLayout('form');
			$this->_displayForm($tpl);

			return;
		}

		//get vars
		$filter_state 	= $mainframe->getUserStateFromRequest( 'com_lyftenbloggie.groups.filter_state', 		'filter_state', 	'*', 'word' );

		//publish unpublished filter
		$lists['state']	= JHTML::_('grid.state', $filter_state );

		//add css and submenu to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		JToolBarHelper::title( JText::_( 'GROUPS' ), 'lbgroups' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::help( 'groups.html', true );
		JToolBarHelper::spacer();

		//Get data from the model
		$rows = & $this->get( 'Data');

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
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
		$lists		= array();

		//get vars
		$id 		= JRequest::getVar( 'group' );

		//add css to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');
		$document->addScript('components/com_lyftenbloggie/assets/js/help.js');	

		//Get data from the model
		$fields 	= BloggieAccess::getFields();
		$row     	= & $this->get( 'Data' );
		$superAdmin = (strtolower($row->group) == 'super administrator' );
		$guest 		= (strtolower($row->group) == 'guest' );

		//create the toolbar
		JToolBarHelper::title( JText::_( 'EDIT GROUP' ).': '.$row->group.($superAdmin ? ' ['.JText::_('WRITE PROTECTED').']' : ''), 'lbgroups' );

		//Disabled for Super Administrator
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::divider();
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
		JToolBarHelper::help( 'groups.html', true );
		JToolBarHelper::spacer();

		//assign vars to view
		$this->assignRef('fields', 		$fields);
		$this->assignRef('row', 		$row);
		$this->assignRef('superAdmin', 	$superAdmin);
		$this->assignRef('guest', 		$guest);

		parent::display($tpl);
	}
}
?>