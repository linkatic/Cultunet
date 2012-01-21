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

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'profile.php' );

/**
 * Configuration view for Jom Social
 */
class CommunityViewMessaging extends JView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		if( $this->getLayout() == 'edit' )
		{
			$this->_displayEditLayout( $tpl );
			return;
		}

		// Set the titlebar text
		JToolBarHelper::title( JText::_('CC MASS MESSAGING'), 'messaging' );

		// Add the necessary buttons
		JToolBarHelper::back('Home' , 'index.php?option=com_community');
		JToolBarHelper::divider();
		JToolBarHelper::save( 'save', JText::_('CC SEND MESSAGE') );
		
		jimport( 'joomla.html.editor' );
		$config		=& CFactory::getConfig();
		$editor		= new JEditor( $config->get('htmleditor' , 'none') );
		
		// Load tooltips
		JHTML::_('behavior.tooltip', '.hasTip');
		
		$this->assignRef( 'editor' , $editor );
		
		
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

	}
}