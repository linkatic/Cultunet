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

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );

/**
 * Jom Social Component Controller
 */
class CommunityControllerMessaging extends CommunityController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function ajaxSendMessage( $title , $message , $limit = 1 )
	{
		$limitstart		= $limit - 1;

		$model			=& $this->getModel( 'users' );
		$userId			= $model->getSiteUsers( $limitstart , 1 );

		$response	= new JAXResponse();
		
		$response->addScriptCall( 'joms.jQuery("#messaging-form").hide();' );
		$response->addScriptCall( 'joms.jQuery("#messaging-result").show();' );

		$user			= CFactory::getUser( $userId );
		$my				=& JFactory::getUser();
		
		if(!empty($userId))
		{
			require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'notification.php' );

			CNotificationLibrary::add( 'system.messaging' , $my->id , $user->id , $title , $message );

			$response->addScriptCall( 'joms.jQuery("#no-progress").css("display","none");');
			$response->addScriptCall( 'joms.jQuery("#progress-status").append("<div>' . JText::sprintf('Sending message to <strong>%1$s</strong>',$user->getDisplayname()) . '<span style=\"color: green;margin-left: 5px;\">' . JText::_('CC SUCCESS').'</span></div>");' );
			$response->addScriptCall( 'sendMessage' , $title , $message , ( $limit + 1 ) );
		}
		else
		{
			$response->addScriptCall( 'joms.jQuery("#progress-status").append("<div style=\"font-weight:700;\">' . JText::_('CC UPDATE COMPLETED') . '</div>");');
		}
		return $response->sendResponse();
	}
}