<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.controller' );

class CommunityBookmarksController extends CommunityBaseController
{
	public function ajaxShowBookmarks( $uri )
	{
		CFactory::load( 'libraries' , 'bookmarks' );
		$bookmarks	= new CBookmarks( $uri );

		CFactory::load( 'libraries' , 'apps' );
		$appsLib	=& CAppPlugins::getInstance();

		$appsLib->loadApplications();
		
		// @onLoadBookmarks deprecated.
		// since 1.5
		$appsLib->triggerEvent( 'onLoadBookmarks' , array( $bookmarks ) );
		
		$response	= new JAXResponse();
		$tmpl		= new CTemplate();
		$tmpl->set( 'bookmarks' , $bookmarks->getBookmarks() );
		
		if( !COMMUNITY_FREE_VERSION ){
			$html		= $tmpl->fetch( 'bookmarks.list' );
			$total		= $bookmarks->getTotalBookmarks();
			$height		= $total * 10;
			$buttons    = '<input type="button" class="button" onclick="joms.bookmarks.email(\'' . $uri. '\');" value="' . JText::_('CC BUTTON SHARE THIS PAGE') . '"/>';
			$buttons   .= '<input type="button" class="button" onclick="cWindowHide();" value="' . JText::_('CC BUTTON CANCEL') . '"/>';
		}else{
			$html		= $tmpl->fetch( 'freeversion.ajax' );
			$height		= 250;
			$buttons    = '<input type="button" class="button" onclick="cWindowHide();" value="' . JText::_('CC BUTTON CANCEL') . '"/>';
		}
	
		$response->addAssign('cwin_logo', 'innerHTML', JText::_('CC SHARE THIS'));
		$response->addAssign('cWindowContent', 'innerHTML', $html);
		$response->addScriptCall('cWindowActions', $buttons);
		$response->addScriptCall('cWindowResize', $height + 220);

		return $response->sendResponse();	
	}
	
	public function ajaxEmailPage( $uri , $emails , $message = '' )
	{
		$message	= stripslashes( $message );
		$mainframe	=& JFactory::getApplication();
		$bookmarks	=& CFactory::getBookmarks( $uri );
		$mailqModel =& CFactory::getModel( 'mailq' );
		$config		=& CFactory::getConfig();		
		$response	= new JAXResponse();
		
		if(empty($emails ) )
		{
			$content	= '<div>' . JText::_('CC SHARE INVALID EMAIL') . '</div>';
			$buttons	= '<input type="button" class="button" onclick="joms.bookmarks.show(\'' . $uri . '\');" value="' . JText::_('CC BUTTON GO BACK') . '"/>';
		}
		else
		{
			$emails		= explode( ',' , $emails );
			$errors		= array();

			// Add notification
			CFactory::load( 'libraries' , 'notification' );
						
			foreach( $emails as $email )
			{
				$email	= JString::trim($email);
				
				if(!empty($email) && preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email) )
				{
					$params			= new JParameter( '' );
					$params->set('uri' , $uri );
					$params->set('message' , $message );

					CNotificationLibrary::add( 'system.bookmarks.email' , '' , $email , JText::sprintf('CC SHARE EMAIL SUBJECT', $config->get('sitename') ) , '' , 'bookmarks' , $params );
				}
				else
				{
					// If there is errors with email, inform the user.
					$errors[]	= $email;
				}
			}

			if( $errors )
			{
				$content	= '<div>' . JText::_('CC EMAILS ARE INVALID') . '</div>';
				foreach($errors as $error )
				{
					$content	.= '<div style="font-weight:700;color: red;">' . $error . '</span>';
				}
				$buttons   = '<input type="button" class="button" onclick="joms.bookmarks.show(\'' . $uri . '\');" value="' . JText::_('CC BUTTON GO BACK') . '"/>';
			}
			else
			{
				$content	= '<div>' . JText::_('CC EMAIL SENT TO RECIPIENTS') . '</div>';
				$buttons   = '<input type="button" class="button" onclick="cWindowHide();" value="' . JText::_('CC BUTTON DONE') . '"/>';
			}
		}

		$response->addAssign('cwin_logo', 'innerHTML', JText::_('CC SHARE THIS'));
		$response->addAssign('cWindowContent', 'innerHTML', $content);
		$response->addScriptCall('cWindowActions', $buttons);
		$response->addScriptCall('cWindowResize', 100);

		return $response->sendResponse();
	}
}