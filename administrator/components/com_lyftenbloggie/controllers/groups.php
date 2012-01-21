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
 * @since 1.1.0
 */
class LyftenBloggieControllerGroups extends LyftenBloggieController
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
	 * Logic to save a group
	 **/
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$task		= JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );
		$post['text'] = JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWRAW );

		$model = $this->getModel('groups');

		if ( $model->store($post) ) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_lyftenbloggie&controller=groups&task=edit&group='.(int) $model->get('group_id');
					break;

				default :
					$link = 'index.php?option=com_lyftenbloggie&view=groups';
					break;
			}
			$msg = JText::_( 'GROUP SAVED' );
			$cache = &JFactory::getCache('page');
			$cache->clean();
		} else {
			$msg 	= $model->getError();
			$link 	= 'index.php?option=com_lyftenbloggie&view=groups';
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Logic to delete groups
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

			$model = $this->getModel('groups');

			if(!$model->delete($cid)) {
				$msg = '';
				JError::raiseError(500, $model->getError());
			} else {
				$total 	= count( $cid );
				$msg 	= $total.' '.JText::_('GROUPS DELETED');

				//Ensure there is a default
				$model->ensureDefault();

				$cache = &JFactory::getCache('com_lyftenbloggie');
				$cache->clean();
			}			
			
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=groups', $msg );
	}

	/**
	 * Logic to publish groups
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

			$model = $this->getModel('groups');

			if(!$model->publish($cid, 1)) {
				JError::raiseError(500, $model->getError());
			}

			$msg = JText::_( 'GROUP PUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=groups', $msg );
	}

	/**
	 * Logic to unpublish groups
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

			$model = $this->getModel('groups');

			if(!$model->publish($cid, 0)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'GROUP UNPUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=groups', $msg );
	}

	/**
	 * logic for cancel an action
	 **/
	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$group = & JTable::getInstance('groups', 'Table');
		$group->bind(JRequest::get('post'));
		$group->checkin();

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=groups' );
	}

	/**
	 * Logic to create the view for the edit group screen
	 **/
	function edit()
	{	
		JRequest::setVar( 'view', 'groups' );
		JRequest::setVar( 'hidemainmenu', 1 );
		
		parent::display();
	}
}