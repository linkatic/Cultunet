<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );

/**
 * Jom Social Component Controller
 */
class CommunityControllerNetwork extends CommunityController
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Method to save the configuration
	 **/	 	
	function save()
	{
		// Test if this is really a post request
		$method	= JRequest::getMethod();
		
		if( $method == 'GET' )
		{
			JError::raiseError( 500 , JText::_('CC ACCESS METHOD NOT ALLOWED') );
			return;
		}
		
		$mainframe	=& JFactory::getApplication();

		$model	=& $this->getModel( 'Network' );
		
		// Try to save network configurations
		if( $model->save() )
		{
			$message	= JText::_('CC NETWORK CONFIGURATION UPDATED');
			$mainframe->redirect( 'index.php?option=com_community&view=network', $message );
		}
		else
		{
			JError::raiseWarning( 100 , JText::_('CC UNABLE TO SAVE NETWORK CONFIGURATION INTO DATABASE PLEASE ENSURE THAT THE TABLE JOS_COMMUNITY_CONFIG EXISTS') );
		}
	}
}