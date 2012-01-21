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
class LyftenBloggieControllerEntries extends LyftenBloggieController
{
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		// Register Extra task
		$this->registerTask( 'add',			 	'edit' );
		$this->registerTask( 'apply', 			'save' );

		$this->registerTask( 'accesspublic', 	'access' );
		$this->registerTask( 'accessregistered','access' );
		$this->registerTask( 'accessspecial', 	'access' );
	}

	/**
	 * Logic to save an entry
	 **/
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$task		= JRequest::getVar('task');

		//get entry object
		$entry 	= &BloggieEntry::getInstance();

		//get data from request
		$post 	= JRequest::get('post');

		//Store Entry Data
		if ($entry->store($post))
		{
			switch ($task)
			{
				case 'apply' :
					JRequest::setVar( 'hidemainmenu', 1 );
					$link = 'index.php?option=com_lyftenbloggie&view=entry&id='.(int)$entry->get('id');
					break;

				default :
					$link = 'index.php?option=com_lyftenbloggie&view=entries';
					break;
			}
			$msg = JText::_( 'BLOG ENTRY SAVED' );

			$cache = &JFactory::getCache('page');
			$cache->clean();

		} else {
			$msg = JText::_( 'ERROR SAVING BLOG ENTRY' );
			JError::raiseError( 500, $model->getError() );
			$link 	= 'index.php?option=com_lyftenbloggie&view=entry';
		}

		$entry->checkin();

		$this->setRedirect($link, $msg);
	}
	
	/**
	 * Logic to publish categories
	 **/
	function publish()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT A BLOG ENTRY' ) );
		} else {

			$model = $this->getModel('entries');

			if(!$model->publish($cid, 1)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'BLOG ENTRY PUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=entries', $msg );
	}

	/**
	 * Logic to unpublish categories
	 **/
	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT A BLOG ENTRY' ) );
		} else {

			$model = $this->getModel('entries');

			if(!$model->publish($cid, 0)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'BLOG ENTRY UNPUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=entries', $msg );
	}
	
	/**
	 * Logic to delete entries
	 **/
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT BLOG ENTRY TO DELETE' ) );
		}

		$model = $this->getModel('entries');

		if(!$model->delete($cid)) {
			$msg = '';
			JError::raiseError(500, $model->getError());
		} else {
			$total 	= count( $cid );
			$msg 	= $total.' '.JText::_('ENTRIES DELETED');

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=entries', $msg );
	}

	/**
	 * logic for cancel an action
	 **/
	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$item = & JTable::getInstance('entries', 'Table');
		$item->bind(JRequest::get('post'));
		$item->checkin();

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=entries' );
	}

	/**
	 * Logic to create the view for the edit categoryscreen
	 **/
	function edit( )
	{		
		JRequest::setVar( 'view', 'entry' );
		JRequest::setVar( 'hidemainmenu', 1 );

		//get entry object
		$entry 	= &BloggieEntry::getInstance();

		$user	=& JFactory::getUser();

		// Error if checkedout by another administrator
		if ($entry->isCheckedOut( $user->get('id') )) {
			$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=entries', JText::_( 'EDITED BY ANOTHER ADMIN' ) );
		}

		$entry->checkout( $user->get('id') );

		parent::display();
	}

	/**
	 * Method to reset hits
	 **/
	function resethits()
	{
		$id		= JRequest::getInt( 'id', 0 );
		$model 	= $this->getModel('entry');
		$db 	= & JFactory::getDBO(); 

		$query = 'UPDATE #__bloggies_entries AS e'
			. ' SET e.hits = \'0\''
			. ' WHERE e.id = \''.(int)$id.'\'';
		$db->SetQuery($query);
		$db->query();
		
		$cache = &JFactory::getCache('com_lyftenbloggie');
		$cache->clean();
		echo 0;
	}
	
	/**
	 * Logic to set the category access level
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

		$model = $this->getModel('entries');
		
		if(!$model->access( $id, $access )) {
			JError::raiseError(500, $model->getError());
		} else {
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect('index.php?option=com_lyftenbloggie&view=entries' );
	}
}