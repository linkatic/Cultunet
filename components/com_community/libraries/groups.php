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

class CGroups implements CCommentInterface
{
	static function getActivityContentHTML($act)
	{
		// Ok, the activity could be an upload OR a wall comment. In the future, the content should
		// indicate which is which
		$html = '';
		$param = new JParameter( $act->params );
		$action = $param->get('action' , false);
		CFactory::load('models', 'groups');
		CFactory::load('models', 'discussions');
		$config = CFactory::getConfig();
		
		$groupModel		= CFactory::getModel( 'groups' );
		
		if( $action == CGroupsAction::DISCUSSION_CREATE )
		{
			
			// Old discussion might not have 'action', and we can't display their
			// discussion summary
		
			$topicId = $param->get('topic_id', false);
			if( $topicId ){
				                                         
				$group			= JTable::getInstance( 'Group' , 'CTable' );
				$discussion		= JTable::getInstance( 'Discussion' , 'CTable' );
			
				$group->load( $act->cid );
				$discussion->load( $topicId );
				
				CFactory::load( 'helpers' , 'string' );
				$discussion->message = strip_tags($discussion->message);
				$topic = CStringHelper::escape($discussion->message);
				$tmpl	= new CTemplate();
				$tmpl->set( 'comment' , JString::substr($topic, 0, $config->getInt('streamcontentlength')) );
				$html	= $tmpl->fetch( 'activity.groups.discussion.create' );
			} 
			return $html;
		} 
		else if ($action == CGroupsAction::WALLPOST_CREATE )
		{
			// a new wall post for group
			// @since 1.8
			$group	= JTable::getInstance( 'Group' , 'CTable' );
			$group->load( $act->cid );
			
			$wallModel	= CFactory::getModel( 'Wall' );
			$wall		= JTable::getInstance( 'Wall' , 'CTable' );
			$my			= CFactory::getUser();
			
			// make sure the group is a public group or current use is
			// a member of the group
			if( ($group->approvals == 0) || $group->isMember($my->id))
			{
				
				CFactory::load( 'libraries' , 'comment' );
				
				$wall->load( $param->get('wallid' ));
				$comment	= strip_tags( $wall->comment , '<comment>');
				$comment	= CComment::stripCommentData( $comment );
				$tmpl	= new CTemplate();
				$tmpl->set( 'comment' , JString::substr($comment, 0, $config->getInt('streamcontentlength')) );
				$html	= $tmpl->fetch( 'activity.groups.wall.create' );
			}
			return $html;
		}
		else if($action == CGroupsAction::DISCUSSION_REPLY)
		{
			// @since 1.8
			$group	= JTable::getInstance( 'Group' , 'CTable' );
			$group->load( $act->cid );
			
			$wallModel	= CFactory::getModel( 'Wall' );
			$wall		= JTable::getInstance( 'Wall' , 'CTable' );
			$my			= CFactory::getUser();
			
			// make sure the group is a public group or current use is
			// a member of the group
			if( ($group->approvals == 0) || $group->isMember($my->id))
			{
				CFactory::load( 'libraries' , 'comment' );
				
				$wall->load( $param->get('wallid' ));
				
				$comment	= strip_tags( $wall->comment , '<comment>');
				$comment	= CComment::stripCommentData( $comment );
				$tmpl	= new CTemplate();
				$tmpl->set( 'comment' , JString::substr($comment, 0, $config->getInt('streamcontentlength')) );
				$html	= $tmpl->fetch( 'activity.wall.post' );
			}
			return $html;
		}
		else if ($action == CGroupsAction::CREATE) 
		{
			$group	= JTable::getInstance( 'Group' , 'CTable' );
			$group->load( $act->cid );

			$tmpl	= new CTemplate();
			$tmpl->set( 'group' , $group );
			$html	= $tmpl->fetch( 'activity.groups.create' );
		}
		
		
		return $html;
	}

	static public function sendCommentNotification( CTableWall $wall , $message )
	{
		CFactory::load( 'libraries' , 'notification' );

		$my			= CFactory::getUser();
		$targetUser	= CFactory::getUser( $wall->post_by );
		$url		= 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $wall->contentid;
		$params 	= $targetUser->getParams();

		$params		= new JParameter( '' );
		$params->set( 'url' , $url );
		$params->set( 'message' , $message );

		CNotificationLibrary::add( 'groups.submit.wall.comment' , $my->id , $targetUser->id , JText::sprintf('PLG_WALLS WALL COMMENT EMAIL SUBJECT' , $my->getDisplayName() ) , '' , 'groups.wallcomment' , $params );
		
		return true;
	}
}

class CGroupsAction
{
	const DISCUSSION_CREATE	= 'group.discussion.create';
	const DISCUSSION_REPLY	= 'group.discussion.reply';
	const WALLPOST_CREATE	= 'group.wall.create';
	const CREATE			= 'group.create';
}