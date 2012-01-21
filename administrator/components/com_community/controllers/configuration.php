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
class CommunityControllerConfiguration extends CommunityController
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Method to display the specific view
	 *
	 **/	 	
	function display()
	{
		$viewName	= JRequest::getCmd( 'view' , 'community' );

		// Set the default layout and view name
		$layout		= JRequest::getCmd( 'layout' , 'default' );

		// Get the document object
		$document	=& JFactory::getDocument();

		// Get the view type
		$viewType	= $document->getType();
		
		// Get the view
		$view		=& $this->getView( $viewName , $viewType );

		$model		=& $this->getModel( $viewName );
		
		if( $model )
		{
			$view->setModel( $model , $viewName );
			
			$network	=& $this->getModel( 'network' );
			$view->setModel( $network  , false );
		}

		// Set the layout
		$view->setLayout( $layout );

		// Display the view
		$view->display();
		
		// Display Toolbar. View must have setToolBar method
		if( method_exists( $view , 'setToolBar') )
		{
			$view->setToolBar();
		}
	}
	
	/**
	 * Method to save the configuration
	 **/	 	
	function saveconfig()
	{
		// Test if this is really a post request
		$method	= JRequest::getMethod();
		
		if( $method == 'GET' )
		{
			JError::raiseError( 500 , JText::_('CC ACCESS METHOD NOT ALLOWED') );
			return;
		}
		
		$mainframe	=& JFactory::getApplication();		

		$model	=& $this->getModel( 'Configuration' );
		
		// Try to save configurations
		if( $model->save() )
		{
			$message	= JText::_('CC CONFIGURATION UPDATED');

			$model	=& $this->getModel( 'Network' );
			
			// Try to save network configurations
			if( $model->save() )
			{
				$mainframe->redirect( 'index.php?option=com_community&view=configuration', $message );
			}
			else
			{
				JError::raiseWarning( 100 , JText::_('CC UNABLE TO SAVE NETWORK CONFIGURATION INTO DATABASE PLEASE ENSURE THAT THE TABLE JOS_COMMUNITY_CONFIG EXISTS') );
			}
		}
		else
		{
			JError::raiseWarning( 100 , JText::_('CC UNABLE TO SAVE CONFIGURATION INTO DATABASE PLEASE ENSURE THAT THE TABLE JOS_COMMUNITY_CONFIG EXISTS') );
		}
	}
	
}