<?php
/**
 * @category	Library
 * @package		JomSocial
 * @subpackage	Photos 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
CFactory::load( 'libraries' , 'comment' );

class CEvents implements CCommentInterface
{	
	static public function sendCommentNotification( CTableWall $wall , $message )
	{
		CFactory::load( 'libraries' , 'notification' );

		$my			= CFactory::getUser();
		$targetUser	= CFactory::getUser( $wall->post_by );
		$url		= 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $wall->contentid;
		$params 	= $targetUser->getParams();

		$params		= new JParameter( '' );
		$params->set( 'url' , $url );
		$params->set( 'message' , $message );

		CNotificationLibrary::add( 'events.submit.wall.comment' , $my->id , $targetUser->id , JText::sprintf('PLG_WALLS WALL COMMENT EMAIL SUBJECT' , $my->getDisplayName() ) , '' , 'events.wallcomment' , $params );
		return true;
	}
	
	static function getActivityContentHTML($act)
	{
		// Ok, the activity could be an upload OR a wall comment. In the future, the content should
		// indicate which is which
		$html 	 = '';
		$param 	 = new JParameter( $act->params );
		$action  = $param->get('action' , false);

		CFactory::load('models', 'events');
		
		if( $action == 'events.create'  )
		{
			return CEvents::getEventSummary($act->cid, $param);
		}
		else if( $action == 'event.join' || $action ==  'event.attendence.attend' )
		{	
			return CEvents::getEventSummary($act->cid, $param);
		}
		else if( $action == 'event.wall.create')
		{
			CFactory::load('libraries', 'wall');
			
			$wallid = $param->get('wallid' , 0);
			$html = CWallLibrary::getWallContentSummary($wallid);
			return $html;
		}
	
		return $html;
	}
	
	static function getEventSummary($eventid, $param)
	{
		$model  =CFactory::getModel( 'events' );
		$event	=& JTable::getInstance( 'Event' , 'CTable' );
		$event->load( $eventid );
		
		$tmpl	= new CTemplate();
		$tmpl->set( 'event'	, $event );
		$tmpl->set( 'param'	, $param );
		
		return $tmpl->fetch( 'activity.events.update' );
	} 
	
	/**
	 * Return array of rss-feed compatible data
	 */	 	
	function getFEED($maxEntry=20, $userid=null)
	{
                              
		$events   = array();
		
        CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'events' );
		
		$model    = new CommunityModelEvents();
        $eventObjs= $model->getEvents( null, $userid );

		if( $eventObjs )
		{
			foreach( $eventObjs as $row )
			{
				$event	=& JTable::getInstance( 'Event' , 'CTable' );
				$event->load( $row->id );
				$events[]	= $event;
			}
			unset($eventObjs);
		}		
		
		return $events;
	}
	
}