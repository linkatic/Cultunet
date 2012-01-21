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
class LyftenBloggieControllerAddons extends LyftenBloggieController
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Logic to upload an addon
	 **/
	function doinstall()
	{
		$installer 	= new BloggieInstaller();
		$msg 		= JText::_('ADDON INSTALL WAS SUCCESSFULL');
		
		$installer->setPackage();
		
		if(!$installer->check()) {
			$msg = $installer->getError();
		}
	
		if(!$installer->install()) {
			$msg = $installer->getError();
		}

		$this->setRedirect('index.php?option=com_lyftenbloggie&view=addons', $msg);
	}
	
	/**
	 * Logic to publish
	 */
	function install()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$msg 	= JText::_( 'ADDON INSTALLED');

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM PUBLISH' ) );
		} else {

			$model = $this->getModel('addons');

			if(!$model->install($cid[0], 1)) {
				$msg 	= $model->getError();
			}

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=addons', $msg );
	}
	
	function cancel()
	{
		$this->setRedirect("index.php?option=com_lyftenbloggie&view=addons");
	}
}