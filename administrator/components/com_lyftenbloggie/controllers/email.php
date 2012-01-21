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
class LyftenBloggieControllerEmail extends LyftenBloggieController
{
	function __construct()
	{
		parent::__construct();
		
		// Register Extra tasks
		$this->registerTask( 'add',		'edit' );
		$this->registerTask( 'apply', 	'save' );
	}
	
	/**
	 * Logic to create the view for the edit
	 **/
	function edit( )
	{		
		JRequest::setVar( 'view', 'email' );
		JRequest::setVar( 'layout', 'form' );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();
	}

	
	/**
	 * Logic to save an page
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$task 			= JRequest::getVar('task');
		$filename	 	= JRequest::getVar('file');
		$filecontent 	= JRequest::getVar( 'filecontent', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$file 			= BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'emails'.DS.$filename;

		if(!file_exists($file)) {
			$this->setRedirect('index.php?option=com_lyftenbloggie&view=email', JText::_("OPERATION FAILED NOT FOUND"));
		}

		if (!$filename) {
			$this->setRedirect('index.php?option=com_lyftenbloggie&view=email', JText::_("OPERATION FAILED NO TEMPLATE SPECIFIED"));
		}

		if (!$filecontent) {
			$this->setRedirect('index.php?option=com_lyftenbloggie&view=email', JText::_("OPERATION FAILED CONTENT EMPTY"));
		}

		if (is_writable($file) == false) {
			$this->setRedirect('index.php?option=com_lyftenbloggie&view=email', JText::_("OPERATION FAILED NOT WRITABLE"));
		}

		if ($fp = fopen ($file, 'w'))
		{
			fputs($fp, stripslashes($filecontent));
			fclose($fp);
			if( $task == 'apply' ) {
				$url = 'index.php?option=com_lyftenbloggie&controller=email&task=edit&file='.$filename;
			} else {
				$url = 'index.php?option=com_lyftenbloggie&view=email';
			}

			$this->setRedirect( $url, JTEXT::_("EMAIL SAVED"));
		} else {
			$this->setRedirect('index.php?option=com_lyftenbloggie&view=email', JTEXT::_("OPERATION FAILED COULDNT WRITING"));
		}
	}

	function cancel()
	{
		$this->setRedirect("index.php?option=com_lyftenbloggie&view=email");
	}
}