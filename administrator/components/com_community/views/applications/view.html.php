<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

/**
 * Configuration view for Jom Social
 */
class CommunityViewApplications extends JView
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

		parent::display( $tpl );
	}

	/**
	 * Private method to set the toolbar for this view
	 * 
	 * @access private
	 * 
	 * @return null
	 **/	 	 
	function setToolBar()
	{

		// Set the titlebar text
		JToolBarHelper::title( JText::_('CC APPLICATIONS'), 'applications' );

		JToolBarHelper::back('Home' , 'index.php?option=com_community');
		// Add the necessary buttons
// 		JToolBarHelper::publishList('publish', JText::_('CC PUBLISH'));
// 		JToolBarHelper::unpublishList('unpublish', JText::_('CC UNPUBLISH'));
// 		JToolBarHelper::divider();
// 		JToolBarHelper::trash('removefield', JText::_('CC DELETE'));
// 		JToolBarHelper::addNew('newfield', JText::_('CC NEW FIELD'));
	}
}