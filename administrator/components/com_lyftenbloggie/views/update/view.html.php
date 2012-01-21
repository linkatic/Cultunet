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
class LyftenBloggieViewUpdate extends JView
{
	/**
	 * @param	string template	Template file name
	 **/	 
	function display( $tpl = null )
	{
		//initialise variables
		$document	= & JFactory::getDocument();
		$settings 	= & BloggieSettings::getInstance();

		// Lets get some HELP!!!!
		require_once (JPATH_COMPONENT.DS.'helper.php');
	
		//add stuff to document
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');
		$document->addScript('components/com_lyftenbloggie/assets/js/help.js');

		//Get data from the model
		$rows    	= BloggieAdmin::getUpdateData();

		$newVersion = (isset($rows['update']['version']) && BLOGGIE_COM_VERSION < $rows['update']['version']);

		$patched = $settings->get('patches');
		$patched = explode(',', $patched);

		//assign data to template
		$this->assignRef('update'			, $rows['update']);
		$this->assignRef('patches'			, $rows['patchs']);
		$this->assignRef('newVersion'		, $newVersion);
		$this->assignRef('tabs'				, $tabs);
		$this->assignRef('patched'			, $patched);
	
		//create the toolbar	
		JToolBarHelper::title( JText::_( 'SETTINGS' ).': '.JText::_('UPDATES'), 'lbconfig' );
		JToolBarHelper::makeDefault('update', 'PATCH IT');
		JToolBarHelper::divider();
		JToolBarHelper::help( 'update.html', true );
		JToolBarHelper::spacer();	

		parent::display($tpl);
	}
}
