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
class LyftenBloggieControllerCategories extends LyftenBloggieController
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
		$this->registerTask( 'orderup'   , 		'order' );
		$this->registerTask( 'orderdown' , 		'order' );

		$this->registerTask( 'accesspublic', 	'access' );
		$this->registerTask( 'accessregistered','access' );
		$this->registerTask( 'accessspecial', 	'access' );
	}

	/**
	 * Logic to save a category
	 **/
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$task		= JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );
		$post['text'] = JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWRAW );

		$model = $this->getModel('categories');

		if ( $model->store($post) ) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_lyftenbloggie&controller=categories&task=edit&id='.(int) $model->get('id');
					break;

				default :
					$link = 'index.php?option=com_lyftenbloggie&view=categories';
					break;
			}
			$msg = JText::_( 'CATEGORY SAVED' );
			
			//Take care of access levels and state
			$pubid = array();
			$pubid[] = $model->get('id');
			if($model->get('published') == 1) {
				$model->publish($pubid, 1);
			} else {
				$model->publish($pubid, 0);
			}
			
			//Ensure there is a default
			$model->ensureDefault();
			
			$cache = &JFactory::getCache('page');
			$cache->clean();

		} else {

			$msg 	= JText::_( 'ERROR SAVING CATEGORY' );
			$link 	= 'index.php?option=com_lyftenbloggie&view=categories';
		}

		$model->checkin();

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
			JError::raiseWarning(500, JText::_( 'SELECT ITEM PUBLISH' ) );
		} else {

			$model = $this->getModel('categories');

			if(!$model->publish($cid, 1)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'CATEGORY PUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=categories', $msg );
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
			JError::raiseWarning(500, JText::_( 'SELECT ITEM UNPUBLISH' ) );
		} else {

			$model = $this->getModel('categories');

			if(!$model->publish($cid, 0)) {
				JError::raiseError(500, $model->getError());
			}

			$msg 	= JText::_( 'CATEGORY UNPUBLISHED');
		
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=categories', $msg );
	}

	/**
	 * Logic to order a entry
	 **/
	function order(  )
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$db 		=& JFactory::getDBO();
		$cid 		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		JArrayHelper::toInteger($cid, array(0));

		$uid    = $cid[0];
		$inc    = ( $this->getTask() == 'orderup' ? -1 : 1 );
		$row =& JTable::getInstance('categories', 'Table');
		$row->load( $uid );

		$row->move( $inc, 'ordering > -10000 AND ordering < 10000' );

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=categories');
	}

	/**
	 * Logic to mass ordering categories
	 **/
	function saveorder( )
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		JArrayHelper::toInteger($cid, array(0));

		$db			=& JFactory::getDBO();
		$total		= count( $cid );
		$order 		= JRequest::getVar( 'order', array(0), 'post', 'array' );
		JArrayHelper::toInteger($order, array(0));

		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		
		$row 		=& JTable::getInstance('categories', 'Table');
		$conditions = array();

		// update ordering values
		for ( $i=0; $i < $total; $i++ )
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg() );
				}
				// remember to updateOrder this group
				$condition = 'ordering > -10000 AND ordering < 10000';
				$found = false;
				foreach ( $conditions as $cond )
				{
					if ($cond[1]==$condition) {
						$found = true;
						break;
					}
				}
				if (!$found) $conditions[] = array($row->id, $condition);
			}
		}

		// execute updateOrder for each group
		foreach ( $conditions as $cond ) {
			$row->load( $cond[0] );
			$row->reorder( $cond[1] );
		}

		$msg 	= JText::_( 'NEW ORDERING SAVED' );
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=categories', $msg );
	}	

	/**
	 * Logic to delete categories
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

			$model = $this->getModel('categories');

			
			if(!$model->delete($cid)) {
				$msg = '';
				JError::raiseError(500, $model->getError());
			} else {
				$total 	= count( $cid );
				$msg 	= $total.' '.JText::_('CATEGORIES DELETED');

				//Ensure there is a default
				$model->ensureDefault();

				$cache = &JFactory::getCache('com_lyftenbloggie');
				$cache->clean();
			}			
			
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=categories', $msg );
	}

	/**
	 * logic for cancel an action
	 **/
	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$category = & JTable::getInstance('categories', 'Table');
		$category->bind(JRequest::get('post'));
		$category->checkin();

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=categories' );
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

		$model = $this->getModel('categories');
		
		if(!$model->access( $id, $access )) {
			JError::raiseError(500, $model->getError());
		} else {
			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}
		
		$this->setRedirect('index.php?option=com_lyftenbloggie&view=categories' );
	}

	/**
	 * Logic to create the view for the edit category screen
	 **/
	function edit()
	{	
		JRequest::setVar( 'view', 'categories' );
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('categories');
		$user	=& JFactory::getUser();

		// Error if checkedout by another administrator
		if ($model->isCheckedOut( $user->get('id') )) {
			$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=categories', JText::_( 'EDITED BY ANOTHER ADMIN' ) );
		}

		$model->checkout( $user->get('id') );
		
		parent::display();
	}
	
	/**
	 * Method to set default Category
	 */
	function makeDefault()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			$msg = '';
			JError::raiseWarning(500, JText::_( 'SELECT ITEM PUBLISH' ) );
		} else {

			$model = $this->getModel('categories');

			$msg = JText::_( 'CATEGORY SET TO DEFAULT');
			if(isset($cid[1])) $msg 	= JText::_( 'FIRST CATEGORY SELECTED WAS SET TO DEFAULT');
			
			if(!$model->setDefault($cid[0], 1)) {
				$msg 	= $model->getError();
			}

			$cache = &JFactory::getCache('com_lyftenbloggie');
			$cache->clean();
		}

		$this->setRedirect( 'index.php?option=com_lyftenbloggie&view=categories', $msg );
	}
}