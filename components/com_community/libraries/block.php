<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

class blockUser
{
	
	/**
	 * Block user(Ban)
	 */
	function block( $userId )
	{                              
		$my			= CFactory::getUser();  
		$mainframe	=& JFactory::getApplication();
		CFactory::load( 'helpers' , 'friends' );
		$isFriend   = CFriendsHelper::isConnected ( $userId, $my->id );
		
		$viewName   = JRequest::getVar('view','','GET'); 
		$urlUserId  = $viewName == 'friends' ? '' : "&userid=" . $userId;
		$url		= CRoute::_("index.php?option=com_community&view=" . $viewName . $urlUserId , false);
		$message	= empty($my->id) || empty($userId) ? JText::_('CC ERROR BLOCK USER') : '';
		
		if( !empty($my->id) && !empty($userId) )
		{
			$model		= CFactory::getModel('block');
			
			if( $model->blockUser($my->id, $userId) ){
			     // Remove user as friend if user is a friend
                 if( $isFriend )
                    $this->removeFriend($userId);
                    
                 $message    = JText::_('CC USER BLOCKED');
            }else{
			     $message    = JText::_('CC ERROR BLOCK USER');
            }
		}
		           
		$mainframe->redirect( $url , $message );
	}		 
	
	/**
	 * unblock user(removeBan)
	 */
	function unBlock( $userId , $layout = null )
	{
		$my			= CFactory::getUser();  
		$mainframe	=& JFactory::getApplication();
		
		$viewName   = JRequest::getVar('view','','GET'); 
		$task       = !empty( $layout ) && $layout != 'null' ? '&task=' . $layout : null ;
		$urlUserId  = $viewName == 'friends' ? '' : "&userid=" . $userId;
		$url		= CRoute::_("index.php?option=com_community&view=" . $viewName . $task . $urlUserId , false);
		
		$message	= empty($my->id) || empty($userId) ? JText::_('CC ERROR BLOCK USER') : '';
		
		if( !empty($my->id) && !empty($userId) )
		{
			$model		= CFactory::getModel('block');
			$message	= $model->removeBannedUser($my->id, $userId) ? JText::_('CC USER UNBLOCKED') : JText::_('CC ERROR BLOCK USER');
		}
		
		$mainframe->redirect( $url , $message );      
// 		$view->addInfo( JText::_( 'CC USER UNBLOCKED' ) ); 
// 		$view->get('friends');     
// 		echo "<script>setTimeout(\"location='$url'\", 3500);</script>\n";
		
	}  	 
	
	/**
	 * remove friend
	 */ 
	function removeFriend( $friendid )
	{
		$mainframe =& JFactory::getApplication();
		$model	   = CFactory::getModel('friends');
		$my		   = CFactory::getUser();
		
		$viewName  = JRequest::getVar('view','','GET');
		$view      = CFactory::getView($viewName);
		
		if( $model->deleteFriend($my->id,$friendid) )
		{
			// Substract the friend count
			$model->updateFriendCount( $my->id );
			$model->updateFriendCount( $friendid );
			
			//add user points
			// we deduct poinst to both parties
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('friends.remove');			
			CUserPoints::assignPoint('friends.remove', $friendid);

			$view->addInfo(JText::_('CC FRIEND REMOVED'));
			//@todo notify friend after remove them from our friend list
			
			//trigger for onFriendRemove
			$eventObject = new stdClass();
			$eventObject->profileOwnerId 	= $my->id;
			$eventObject->friendId 			= $friendid;
			$this->_triggerFriendEvents( 'onFriendRemove' , $eventObject);
			unset($eventObject);
		}
		else
		{
			$view->addinfo(JText::_('CC ERROR REMOVING FRIEND'));
		}
	}


	/*
	 * friends event name
	 * object	 	
     */	
	function _triggerFriendEvents( $eventName, &$args, $target = null)
	{
		CError::assert( $args , 'object', 'istype', __FILE__ , __LINE__ );
		
		require_once( COMMUNITY_COM_PATH.DS.'libraries' . DS . 'apps.php' );
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();		
		
		$params		= array();
		$params[]	= &$args;
		
		if(!is_null($target))
			$params[]	= $target;
				
		$appsLib->triggerEvent( $eventName , $params);
		return true;
	}
		
	/**
	 * restrict blocked user to access owner details
	 */	 	
	function ajaxBlockMessage()
	{
		$objResponse	= new JAXResponse();
		$uri			= CFactory::getLastURI();
		$uri			= base64_encode($uri);
		$config			=& CFactory::getConfig();
		$tmpl			= new CTemplate();
		$tmpl->set( 'uri' , $uri );
		$tmpl->set( 'config'	, $config );
		$html			= $tmpl->fetch( 'block.denied' );

		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);	
		$objResponse->addScriptCall('cWindowResize', 260);
		return $objResponse->sendResponse();
	}  
		
	/**
	 * restrict blocked user to access owner details
	 */	 	
	function ajaxBlockWarn()
	{
		$objResponse	= new JAXResponse();
		$config			=& CFactory::getConfig();
		$html			= JText::_('CC YOU HAD BLOCKED THIS USER');

		$buttons	= '<form method="post" action="" style="float:right;">'; 
		$buttons	.= '<input type="button" class="button" onclick="cWindowHide();return false;" name="cancel" value="'.JText::_('CC BUTTON CLOSE').'" />';
		$buttons	.= '</form>';

		$objResponse->addAssign('cWindowContent', 'innerHTML', $html);	   
		$objResponse->addScriptCall('joms.jQuery("#cwin_logo").html("' . $config->get('sitename') . '");'); 
		$objResponse->addScriptCall('cWindowActions', $buttons);
		return $objResponse->sendResponse();  
	}
	
}
