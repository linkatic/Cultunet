<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );

/**
 * Jom Social Component Controller
 */
class CommunityControllerMailqueue extends CommunityController
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Remove mail queues
	 **/	 	
	function removequeue()
	{
		$mainframe	=& JFactory::getApplication();
		
		$ids	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$count	= count($ids);

		$row		=& JTable::getInstance( 'mailqueue', 'CommunityTable' );
		
		foreach( $ids as $id )
		{
			if(!$row->delete( $id ))
			{
				// If there are any error when deleting, we just stop and redirect user with error.
				$message	= JText::_('CC THERE ARE ERRORS REMOVING THE SELECTED QUEUES');
				$mainframe->redirect( 'index.php?option=com_community&view=mailqueue' , $message);
				exit;
			}
		}
		$message	= JText::sprintf( '%1$s Mail Queue(s) successfully removed.' , $count );
		$mainframe->redirect( 'index.php?option=com_community&view=mailqueue' , $message );
	}
	
	/**
	 * Purge sent mail queues
	 **/	 
	function purgequeue()
	{
		$mainframe	=& JFactory::getApplication();
		
		$model		= $this->getModel( 'Mailqueue' );
		$model->purge();
		
		$message	= JText::_('CC SENT MAIL QUEUES PURGED');
		$mainframe->redirect( 'index.php?option=com_community&view=mailqueue' , $message );
	}
}