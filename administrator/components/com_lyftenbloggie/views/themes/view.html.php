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
class LyftenBloggieViewThemes extends JView
{
	/**
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		//initialise variables
		$document	= & JFactory::getDocument();
		$task		= JRequest::getVar('task');

		// Lets get some HELP!!!!
		require_once (JPATH_COMPONENT.DS.'helper.php');
		
		//add stuff to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');	
		$document->addScript('components/com_lyftenbloggie/assets/js/help.js');	

		//Get data from the model
		$rows    = & $this->get( 'Themes');
		
		//assign data to template
		$this->assignRef('rows', $rows);
		
		if(!$task) {
			//create the toolbar		
			JToolBarHelper::title( JText::_( 'SETTINGS' ).': '.JText::_('THEMES'), 'lbconfig' );
			JToolBarHelper::makeDefault('publish');
			JToolBarHelper::divider();
			JToolBarHelper::deleteList();
			JToolBarHelper::spacer();
			
			$pageNav = & $this->get( 'Pagination' );
			$this->assignRef('pageNav'			, $pageNav);
		}else{
			//create the toolbar
			JToolBarHelper::title( JText::_( 'SETTINGS' ).': '.JText::_('EDIT THEMES'), 'lbconfig' );

			$lists['default'] = JHTML::_('select.booleanlist',  'is_default', 'class="inputbox"', $rows->is_default );
			
			JHTML::_('behavior.tooltip');

			//assign data to template
			$this->assignRef('lists', $lists);

			JToolBarHelper::save();
			JToolBarHelper::apply();
			JToolBarHelper::divider();
			JToolBarHelper::cancel();
			JToolBarHelper::spacer();		
		}
		parent::display($tpl);
	}
}
