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
class LyftenBloggieControllerTags extends LyftenBloggieController
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
	 * Logic to save a tag
	 **/
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$task		= JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );

		$model = $this->getModel('tags');
		if ( $model->store($post) )
		{
			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_lyftenbloggie&controller=tags&task=edit&id='.(int) $model->get('id');
					break;

				default :
					$link = 'index.php?option=com_lyftenbloggie&view=tags';
					break;
			}
			$msg = JText::_( 'TAG SAVED' );

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();

		} else {

			$msg = JText::_( 'ERROR SAVING TAG' );
			$link 	= 'index.php?option=com_lyftenbloggie&view=tags';
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Logic to publish categories
	 **/
	function publish()
	{
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM PUBLISH' ) );
		} else {
			$model = $this->getModel('tags');

			if(!$model->publish($cid, 1)) {
				JError::raiseError( 500, $model->getError() );
			}

			$total = count( $cid );
			$msg 	= $total.' '.JText::_( 'TAG PUBLISHED');
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=tags', $msg );
	}

	/**
	 * Logic to unpublish categories
	 **/
	function unpublish()
	{
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM UNPUBLISH' ) );
		} else {
			$model = $this->getModel('tags');

			if(!$model->publish($cid, 0)) {
				JError::raiseError( 500, $model->getError() );
			}

			$total = count( $cid );
			$msg 	= $total.' '.JText::_( 'TAG UNPUBLISHED');
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=tags', $msg );
	}

	/**
	 * Logic to delete categories
	 **/
	function remove()
	{
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM DELETE' ) );
		} else {
			$model = $this->getModel('tags');

			if (!$model->delete($cid)) {
				JError::raiseError(500, JText::_( 'OPERATION FAILED' ));
			}
			
			$msg = count($cid).' '.JText::_( 'TAGS DELETED' );
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=tags', $msg );
	}

	/**
	 * logic for cancel an action
	 **/
	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=tags' );
	}

	/**
	 * Logic to create the view for the edit categoryscreen
	 **/
	function edit( )
	{
		JRequest::setVar( 'view', 'tags' );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();
	}

	/**
	 *  Add new Tag from item screen
	 **/
	function addtag(){
		$name 	= JRequest::getString('name', '');
		$model 	= $this->getModel('tags');
		$model->addtag($name);
	}
}