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
 * @since 1.0.2
 */
class LyftenBloggieControllerUpdate extends LyftenBloggieController
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Logic to upload a update
	 **/
	function doinstall()
	{
		$installer 	= new BloggieInstaller();
		$msg 		= JText::_('UPDATE INSTALL WAS SUCCESSFULL');
	
		$installer->setPackage();
	
		if(!$installer->check()) {
			$msg = $installer->getError();
		}

		if(!$installer->install()) {
			$msg = $installer->getError();
		}

		$this->setRedirect('index.php?option=com_lyftenbloggie&view=update', $msg);
	}

	/**
	 * Logic to publish
	 */
	function update()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
	
		$upgrade 	= JRequest::getVar( 'upgrade', array(0), 'post', 'array' );
		$msg 		= JText::_( 'UPDATE INSTALLED');

		if (!$upgrade) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM' ) );
		} else {

			$model = $this->getModel('update');

			//get patch id
			$patch = (JRequest::getInt( 'patch' )) ? key($upgrade) : 0;

			//do update/patch
			if(!$model->update($upgrade[$patch], $patch)) {
				$msg 	= $model->getError();
			}

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=update', $msg );
	}

	function cancel()
	{
		$this->setRedirect("index.php?option=com_lyftenbloggie&view=update");
	}
}