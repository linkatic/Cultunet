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
class LyftenBloggieViewAddons extends JView
{
	/**
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		//initialise variables
		$document	= & JFactory::getDocument();
		$settings 	= & BloggieSettings::getInstance();
		$task		= JRequest::getVar('task');
		$notice		= null;

		// Lets get some HELP!!!!
		require_once (JPATH_COMPONENT.DS.'helper.php');
		
		//add stuff to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');	
		$document->addScript('components/com_lyftenbloggie/assets/js/help.js');	

		//Get data from the model
		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );

		//Server Notice
		if(isset($rows['message']['notice']))
		{
			$notice = $rows['message']['notice'];
		}else if(isset($rows['brezza']['notice']))
		{
			$notice = $rows['brezza']['notice'];
		}

		//assign data to template
		$this->assignRef('rows', 	$rows);
		$this->assignRef('pageNav', $pageNav);
		$this->assignRef('notice', 	$notice);
		
		//create the toolbar		
		JToolBarHelper::title( JText::_( 'SETTINGS' ).': '.JText::_('ADDONS'), 'lbconfig' );
		JToolBarHelper::makeDefault('update', 'INSTALL');
		JToolBarHelper::divider();
		JToolBarHelper::help( 'addons.html', true );
		JToolBarHelper::spacer();		

		parent::display($tpl);
	}
}
