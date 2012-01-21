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
class LyftenBloggieControllerComments extends LyftenBloggieController
{
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		// Register Extra task
		$this->registerTask( 'add'  ,	'edit' );
		$this->registerTask( 'apply', 	'save' );
	}

	/**
	 * Logic to save a comment
	 **/
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$task		= JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );

		$model = $this->getModel('comments');

		if ( $model->store($post) ) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_lyftenbloggie&controller=comments&task=edit&id='.(int) $model->get('id');
					break;

				default :
					$link = 'index.php?option=com_lyftenbloggie&view=comments';
					break;
			}
			$msg = JText::_( 'COMMENT SAVED' );
			
			//Take care of access levels and state
			$commentsmodel = & $this->getModel('comments');
			
			$pubid = array();
			$pubid[] = $model->get('id');
			if($model->get('published') == 1) {
				$commentsmodel->publish($pubid, 1);
			} else {
				$commentsmodel->publish($pubid, 0);
			}
			
			$cache = &JFactory::getCache('page');
			$cache->clean();

		} else {

			$msg 	= JText::_( 'ERROR SAVING COMMENT' );
			$link 	= 'index.php?option=com_lyftenbloggie&view=comments';
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Logic to publish comments
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

			$model = $this->getModel('comments');

			if(!$model->publish($cid, 1)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'COMMENT PUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=comments', $msg );
	}

	/**
	 * Logic to unpublish comments
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

			$model = $this->getModel('comments');

			if(!$model->publish($cid, 0)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'COMMENT UNPUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=comments', $msg );
	}

	/**
	 * Logic to delete comments
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

			$model = $this->getModel('comments');

			$msg = $model->delete($cid);

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=comments', $msg );
	}

	/**
	 * Logic to delete comments reports
	 **/
	function delreports()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$id		= JArrayHelper::getValue( $_REQUEST, 'id', 0 );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = JText::_( 'SELECT A REPORT TO DELETE' );
		} else {

			$model = $this->getModel('comments');

			$msg = $model->delreport($cid);

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&controller=comments&task=edit&id='.$id, $msg );
	}

	/**
	 * logic for cancel an action
	 **/
	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=comments' );
	}

	/**
	 * Logic to create the view for the edit commentscreen
	 **/
	function edit( )
	{	
		// Check for request forgeries
		//JRequest::checkToken() or jexit( 'Invalid Token' );
		
		JRequest::setVar( 'view', 'comments' );
		JRequest::setVar( 'hidemainmenu', 1 );
		
		parent::display();
	}
}