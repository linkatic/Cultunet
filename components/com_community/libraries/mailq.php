<?php
/**
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CMailq
{		
	/**
	 * Do a batch send
	 */	 	
	function send( $total = 100 )
	{
		$mailqModel = CFactory::getModel( 'mailq' );
		$userModel	= CFactory::getModel( 'user' );
		$mails		= $mailqModel->get( $total );
		$jconfig	= JFactory::getConfig();
		$mailer		= JFactory::getMailer();
		$config		= CFactory::getConfig();
		
		$senderEmail  = $jconfig->getValue('mailfrom');
		$senderName   = $jconfig->getValue('fromname');

		if(empty($mails))
		{
			return;
		}

		CFactory::load( 'helpers' , 'string' );
		
		foreach( $mails as $row )
		{
			// @rule: only send emails that is valid.
			// @rule: make sure recipient is not blocked!
			$userid = $userModel->getUserFromEmail($row->recipient);
			$user = CFactory::getUser($userid);
			
			if( !$user->isBlocked() && !JString::stristr( $row->recipient , 'foo.bar') )
			{
				
				$mailer->setSender( array( $senderEmail, $senderName ) );
				$mailer->addRecipient($row->recipient);
				$mailer->setSubject($row->subject);

				$tmpl		= new CTemplate();
				$raw		= isset($row->params) ? $row->params : '';
				$params		= new JParameter( $row->params );
				$base		= $config->get('htmlemail') ? 'email.html' : 'email.text';
				
				if( $config->get('htmlemail') )
				{
					$row->body	= JString::str_ireplace(array("\r\n", "\r", "\n"), '<br />', $row->body );
					$mailer->IsHTML( true );
				}
				else
				{
					//@rule: Some content might contain 'html' tags. Strip them out since this mail should never contain html tags.
					$row->body	= CStringHelper::escape( strip_tags($row->body) );
				}

				$tmpl->set( 'content' , $row->body );
				$tmpl->set( 'template', rtrim( JURI::root() , '/' ) . '/components/com_community/templates/' . $config->get('template') );
				$tmpl->set( 'sitename' , $config->get('sitename') );
				
				$row->body	= $tmpl->fetch( $base );
				
				// Replace any occurences of custom variables within the braces scoe { }
				if( !empty( $row->body ) )
				{
					preg_match_all("/{(.*?)}/", $row->body, $matches, PREG_SET_ORDER);
		
					foreach ($matches as $val) 
					{
						$replaceWith = $params->get($val[1], null);
						
						//if the replacement start with 'index.php', we can CRoute it
						if( strpos($replaceWith, 'index.php') === 0)
						{
							$replaceWith = CRoute::getExternalURL($replaceWith);
						}
						
						if( !is_null( $replaceWith ) ) 
						{
							$row->body	= JString::str_ireplace( $val[0] , $replaceWith , $row->body );
						}
					}
				}
				unset($tmpl);
			
				$mailer->setBody($row->body);					
				$mailer->send();
			}
			
			$mailqModel->markSent($row->id);
			$mailer->ClearAllRecipients();
		}
	}
}

/**
 * Maintain classname compatibility with JomSocial 1.6 below
 */ 
class CMailqLibrary extends CMailq
{}