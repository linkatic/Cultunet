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
 * HTML View class for the About View
 *
 * @package Joomla
 * @subpackage JomProducts
 * @since 1.0
 */
class LyftenBloggieViewAbout extends JView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		// Load tooltips
		JHTML::_('behavior.tooltip', '.hasTip');

		//add css to document
		$document	= & JFactory::getDocument();
		$document->addStyleSheet('components/com_lyftenbloggie/assets/css/style.css');

		//create the toolbar
		JToolBarHelper::title( JText::_( 'ABOUT' ), 'lyftenbloggie' );
		
		parent::display( $tpl );
	}
}