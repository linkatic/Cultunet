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
class CommunityViewMailqueue extends JView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		$queues		= $this->get( 'MailQueues' );
		$pagination	= $this->get( 'Pagination' );
		// Load tooltips
		JHTML::_('behavior.tooltip', '.hasTip');

 		$this->assignRef( 'mailqueues' 		, $queues );
 		$this->assignRef( 'pagination'	, $pagination );
		parent::display( $tpl );
	}
	
	function getStatusText( $status )
	{
		$text	= ( $status == '1' ) ? '<img src="' . rtrim( JURI::root() , '/' ) . '/administrator/images/tick.png" />' : JText::_('CC PENDING');
		return $text;
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
		JToolBarHelper::title( JText::_('CC MAIL QUEUE'), 'mailq' );

		// Add the necessary buttons
		JToolBarHelper::back('Home' , 'index.php?option=com_community');
		JToolBarHelper::divider();
		JToolBarHelper::trash('purgequeue', JText::_('CC PURGE SENT') , false );
		JToolBarHelper::trash('removequeue', JText::_('CC DELETE'));
	}
}