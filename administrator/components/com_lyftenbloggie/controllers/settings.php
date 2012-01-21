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

jimport( 'joomla.application.component.controller' );

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieControllerSettings extends LyftenBloggieController
{
	function __construct()
	{
		parent::__construct();
		
		// Register Extra tasks
		$this->registerTask( 'add',			 	'edit' );
		$this->registerTask( 'apply', 			'save' );
	}
	
	/**
	 * Logic to create the view for the edit categoryscreen
	 **/
	function edit( )
	{		
		JRequest::setVar( 'view', 'entry' );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();
	}
	
	/**
	 * Logic to save an entry
	 **/
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$task	= JRequest::getVar('task');
		$post 	= JRequest::get( 'post' );

		$model 	= $this->getModel('settings');
		if ( $model->store($post) ) {
			switch ($task)
			{
				case 'apply' :
					JRequest::setVar( 'hidemainmenu', 1 );
					$link = 'index.php?option=com_lyftenbloggie&view=settings';
					break;

				default :
					$link = 'index.php?option=com_lyftenbloggie';
					break;
			}
			$msg = JText::_( 'SETTINGS SAVED' );

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();

		} else {
			$msg = JText::_( 'ERROR SAVING SETTINGS' );
			JError::raiseError( 500, $model->getError() );
			$link 	= 'index.php?option=com_lyftenbloggie&view=settings';
		}

		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$this->setRedirect("index2.php?option=com_lyftenbloggie");
	}
	
	/**
	 * Method to perform needed maintenance
	 **/
	function databaseOptimise()
	{
		$model = $this->getModel('settings');

		if ( $msg = $model->databaseOptimise() ) {
			$this->setRedirect('index.php?option=com_lyftenbloggie&view=settings', $msg);
		}
	}
}