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
 * @since 1.1.0
 */
class LyftenBloggieControllerPlugins extends LyftenBloggieController
{
	function __construct()
	{
		parent::__construct();
	
		// Register Extra tasks
		$this->registerTask( 'add',			 	'edit' );
		$this->registerTask( 'apply', 			'save' );
	}

	/**
	 * Logic to create the view for the edit
	 **/
	function edit( )
	{	
		JRequest::setVar( 'view', 'plugins' );
		JRequest::setVar( 'layout', 'form' );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();
	}

	/**
	 * Logic to upload a Plugin
	 **/
	function doinstall()
	{
		$installer 	= new BloggieInstaller();
		$msg 		= JText::_('PLUGIN INSTALL WAS SUCCESSFUL');

		$installer->setPackage();

		if(!$installer->check()) {
			$msg = $installer->getError();
		}

		if(!$installer->install()) {
			$msg = $installer->getError();
		}

		$this->setRedirect('index.php?option=com_lyftenbloggie&view=plugins', $msg);
	}

	/**
	 * Logic to save an page
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$task		= JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );
		$post['fulltext'] = JRequest::getVar( 'fulltext', '', 'post', 'string', JREQUEST_ALLOWRAW );

		$model = $this->getModel('plugins');

		if ( $model->store($post) ) {

			switch ($task)
			{
				case 'apply' :
					JRequest::setVar( 'hidemainmenu', 1 );
					$link = 'index.php?option=com_lyftenbloggie&controller=plugins&task=edit&cid[]='.(int) $model->get('id');
					break;

				default :
					$link = 'index.php?option=com_lyftenbloggie&view=plugins';
					break;
			}
			$msg = JText::_( 'PLUGIN SAVED' );

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();

		} else {
			$msg = JText::_( 'ERROR SAVING PLUGIN' );
			JError::raiseError( 500, $model->getError() );
			$link 	= 'index.php?option=com_lyftenbloggie&view=plugins';
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Logic to publish plugins
	 **/
	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
	
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM PUBLISH' ) );
		} else {

			$model = $this->getModel('plugins');

			if(!$model->publish($cid, 1)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= count($cid).' '.JText::_( 'PLUGIN PUBLISHED');
	
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=plugins', $msg );
	}

	/**
	 * Logic to unpublish plugins
	 **/
	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
	
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM UNPUBLISH' ) );
		} else {

			$model = $this->getModel('plugins');

			if(!$model->publish($cid, 0)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= count($cid).' '.JText::_( 'PLUGIN UNPUBLISHED');
	
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
	
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=plugins', $msg );
	}

	function cancel()
	{
		$this->setRedirect("index.php?option=com_lyftenbloggie&view=plugins");
	}

	/**
	 * Logic to delete plugins
	 **/
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1)
		{
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM DELETE' ) );
		} else {

			$model = $this->getModel('plugins');

			if(!$msg = $model->delete($cid))
			{
				$msg = $model->getError();
			}

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect('index.php?option=com_lyftenbloggie&view=plugins', $msg);
	}
}