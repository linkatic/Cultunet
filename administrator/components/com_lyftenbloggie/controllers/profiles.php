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
class LyftenBloggieControllerProfiles extends LyftenBloggieController
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
	 * Logic to save a profile
	 **/
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$task		= JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );
		$post['text'] = JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWRAW );

		$model = $this->getModel('profiles');

		if ( $model->store($post) ) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_lyftenbloggie&controller=profiles&task=edit&id='.(int) $model->get('user_id');
					break;

				default :
					$link = 'index.php?option=com_lyftenbloggie&view=profiles';
					break;
			}
			$msg 	= JText::_( 'PROFILE SAVED' );
			$cache 	= &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();

		} else {

			$msg 	= JText::_( 'ERROR SAVING PROFILE' );
			$link 	= 'index.php?option=com_lyftenbloggie&view=profiles';
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Logic to publish profiles
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

			$model = $this->getModel('profiles');

			if(!$model->publish($cid, 1)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'PROFILE PUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=profiles', $msg );
	}

	/**
	 * Logic to unpublish profiles
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

			$model = $this->getModel('profiles');

			if(!$model->publish($cid, 0)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'PROFILE UNPUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=profiles', $msg );
	}

	/**
	 * Logic to delete profiles
	 **/
	function reset()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM DELETE' ) );
		} else {

			$model = $this->getModel('profiles');

			if($model->reset($cid)) {
				$msg = 	count($cid).' '.JText::_( 'PROFILE RESET' );
			}else{
				$msg = 	JText::_( 'ERROR RESETTING PROFILE' );
			}

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
	
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=profiles', $msg );
	}

	/**
	 * logic for cancel an action
	 **/
	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$profile = & JTable::getInstance('profiles', 'Table');
		$profile->bind(JRequest::get('post'));
		$profile->checkin();

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=profiles' );
	}

	/**
	 * Logic to set the profile access level
	 **/
	function access( )
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$id			= (int)$cid[0];
		$task		= JRequest::getVar( 'task' );

		if ($task == 'accesspublic') {
			$access = 0;
		} elseif ($task == 'accessregistered') {
			$access = 1;
		} else {
			$access = 2;
		}

		$model = $this->getModel('profiles');
		
		if(!$model->access( $id, $access )) {
			JError::raiseError(500, $model->getError());
		} else {
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect('index.php?option=com_lyftenbloggie&view=profiles' );
	}

	/**
	 * Logic to create the view for the edit profilescreen
	 **/
	function edit( )
	{	
		// Check for request forgeries
		//JRequest::checkToken() or jexit( 'Invalid Token' );
		
		JRequest::setVar( 'view', 'profiles' );
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 		= $this->getModel('profiles');
		$profile	=& JFactory::getUser();
		
		parent::display();
	}
}