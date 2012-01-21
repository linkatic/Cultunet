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

jimport('joomla.application.component.controller');

/**
 * @package Joomla
 * @subpackage LyftenBloggie
 * @since 1.0
 */
class LyftenBloggieControllerBookmarks extends LyftenBloggieController
{
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		// Register Extra task
		$this->registerTask( 'add'  ,		 	'edit' );
		$this->registerTask( 'apply', 			'save' );
	}

	/**
	 * Logic to save a bookmark
	 **/
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$task = JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );
		$post['html'] = JRequest::getVar( 'html', '', 'post', 'string', JREQUEST_ALLOWRAW );

		$model = $this->getModel('bookmarks');

		if ( $model->store($post) ) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_lyftenbloggie&controller=bookmarks&task=edit&id='.(int) $model->get('id');
					break;

				default :
					$link = 'index.php?option=com_lyftenbloggie&view=bookmarks';
					break;
			}
			$msg = JText::_( 'BOOKMARK SAVED' );
			
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();

		} else {
			$msg 	= JText::_( 'ERROR SAVING BOOKMARK' );
			$link 	= 'index.php?option=com_lyftenbloggie&view=bookmarks';
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Logic to publish bookmarks
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

			$model = $this->getModel('bookmarks');

			if(!$model->publish($cid, 1)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'BOOKMARK PUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=bookmarks', $msg );
	}

	/**
	 * Logic to unpublish bookmarks
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

			$model = $this->getModel('bookmarks');

			if(!$model->publish($cid, 0)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'BOOKMARK UNPUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=bookmarks', $msg );
	}

	/**
	 * Logic to delete bookmarks
	 **/
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM DELETE' ) );
		} else {

			$model = $this->getModel('bookmarks');

			if($model->delete($cid))
			{
				$msg = count($cid).' '.JText::_('BOOKMARK DELETED');
			}else{
				$msg = JText::_('OPERATION FAILED');
			}

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=bookmarks', $msg );
	}

	/**
	 * logic for cancel an action
	 **/
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=bookmarks' );
	}

	/**
	 * Logic to create the view for the edit bookmarkscreen
	 **/
	function edit( )
	{	
		JRequest::setVar( 'view', 'bookmarks' );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();
	}
}