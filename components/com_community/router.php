<?php
/**
 * @package		JomSocial
 * @subpackage	Core
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
// Testing Merge

function CommunityBuildRoute(&$query)
{
	$segments = array();
	$escapeRouteChar	= array('.', '-', '\\', '/', '@', '#', '?', '!', '^', '&', '<', '>', '\'' , '"' );
	include_once( JPATH_BASE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
	$config =& CFactory::getConfig();

	// Profile based,
	if(array_key_exists( 'userid', $query))
	{
		$user		= CFactory::getUser( $query['userid'] );
		
		// Since 1.8.x we will generate URLs based on the vanity url.
		$alias		= $user->getAlias();
		if( !empty($alias) )
		{
			$segments[]	= $alias;
		}
		else
		{
			$alias	= JFilterOutput::stringURLSafe( $user->username );
			$segments[]	= $user->id . '-' . $alias;
		}
		unset($query['userid']);
	}

	if(isset($query['view']))
	{
		if(empty($query['Itemid']))
		{
			$segments[] = $query['view'];
		}
		else
		{
			$menu = &JSite::getMenu();
			$menuItem = &$menu->getItem( $query['Itemid'] );
			
			if(!isset($menuItem->query['view']) || $menuItem->query['view'] != $query['view'])
			{
				$segments[] = $query['view'];
			}
		}
		unset($query['view']);
	}

	if(isset($query['task']))
	{
		switch( $query['task'] )
		{
			case 'viewgroup':
				$db	=& JFactory::getDBO();
				$groupid =   $query['groupid'];
				$groupModel =& CFactory::getModel('groups');
				$group		=& JTable::getInstance( 'Group' , 'CTable' );
				$group->load($groupid);
				
				$segments[] = $query['task'];
				$groupName	= $group->name;
	
				foreach($escapeRouteChar as $escapeChar)
				{
					$groupName 	= JString::str_ireplace($escapeChar, '', $groupName);
				}
				
				$groupName	= urlencode($groupName);
				$groupName	= JString::str_ireplace('++', '+', $groupName);
				$segments[] = $groupid . '-' . $groupName;
	
				unset($query['groupid']);
			break;
			case 'viewevent':
				$id		= $query['eventid'];
				$event	=& JTable::getInstance( 'Event' , 'CTable' );
				$event->load( $id );

				$segments[] = $query['task'];
				$name		= $event->title;
	
				foreach($escapeRouteChar as $escapeChar)
				{
					$name 	= JString::str_ireplace($escapeChar, '', $name);
				}
				
				$name	= urlencode( $name );
				$name	= JString::str_ireplace('++', '+', $name);
				$segments[] = $event->id . '-' . $name;		
				unset( $query['eventid'] );
			break;
			case 'video':
				$videoModel	=& CFactory::getModel('Videos');
				$videoid	= $query['videoid'];
				
				$video		=& JTable::getInstance( 'Video' , 'CTable' );
				$video->load( $videoid );
				
				// We need the task for video otherwise we cannot differentiate between myvideos
				// and viewing a video since myvideos also doesn't pass any tasks.
				$segments[] = $query['task'];
				
				$title		= trim( $video->title );
				foreach($escapeRouteChar as $escapeChar)
				{
					$title	= JString::str_ireplace($escapeChar, '', $title);
				}
				$title		= urlencode( $title );
				$title		= JString::str_ireplace( '++' , '+' , $title );
				$segments[]	= $video->id . '-' . $title;
				unset( $query['videoid'] );
			break;
			case 'viewdiscussion':
				$db	=& JFactory::getDBO();
				$topicId =   $query['topicid'];
				$discussionsModel =& CFactory::getModel('discussions');
				$discussions =& JTable::getInstance( 'Discussion' , 'CTable' );
				$discussions->load($topicId);
				
				$segments[] = $query['task'];
				$discussionName	= $discussions->title;
				
				foreach($escapeRouteChar as $escapeChar)
				{
					$discussionName 	= JString::str_ireplace($escapeChar, '', $discussionName);
				}				
				
				$discussionName	= urlencode($discussionName);
				$discussionName	= JString::str_ireplace('++', '+', $discussionName);	
				
				$segments[] = $topicId . '-' . $discussionName;
				unset($query['topicid']);
			break;
			case 'viewbulletin':
				$db	=& JFactory::getDBO();
				$bulletinid =   $query['bulletinid'];
				$bulletinsModel =& CFactory::getModel('bulletins');
				$bulletins =& JTable::getInstance( 'Bulletin' , 'CTable' );
				$bulletins->load($bulletinid);
				
				$segments[] = $query['task'];
				$bullentinName	= $bulletins->title;
				
				foreach($escapeRouteChar as $escapeChar)
				{
					$bullentinName 	= JString::str_ireplace($escapeChar, '', $bullentinName);
				}				
				
				$bullentinName	= urlencode($bullentinName);
				$bullentinName	= JString::str_ireplace('++', '+', $bullentinName);
				
				$segments[] = $bulletinid . '-' . $bullentinName;
				unset($query['bulletinid']);
			break;
			default:
				if( $query['task'] != 'myphotos' && $query['task'] != 'mygroups' && $query['task'] != 'myevents' && $query['task'] != 'myvideos' && $query['task'] != 'invites' )
				{
					$segments[] = $query['task'];
				}
			break;
		}

		unset($query['task']);
	}

	return $segments;
}

function CommunityParseRoute($segments)
{
	$vars = array();

	$menu			=& JSite::getMenu();
	$selectedMenu	=& $menu->getActive();

	// We need to grab the user id first see if the first segment is a user
	// because once CFactory::getConfig is loaded, it will automatically trigger
	// the plugins. Once triggered, the getRequestUser will only get the current user.
	$count = count($segments);

	if(!empty($count) )
	{
		$alias		= $segments[0];
		$userid		= '';

		if( !empty( $alias ) )
		{
			// Check if this user exists in the alias
			$userid = CommunityGetUserId( $alias );

			// Joomla converts ':' to '-' when encoding and during decoding, 
			// it converts '-' to ':' back for the query string which will break things
			// if the username has '-'. So we do not have any choice apart from 
			// testing both this values until Joomla tries to fix this
			if( !$userid && JString::stristr( $alias , ':' ) )
			{
				$userid		= CommunityGetUserId( JString::str_ireplace( ':' , '-' , $alias ) );
			}

			// For users 
			if( !$userid )
			{
				
				if( JString::stristr( $alias , '-' ) )
				{
					$user		= explode( '-' , $alias );
		
					if( isset( $user[0] ) )
					{
						$userid	= $user[0];
					}
				}

				if( JString::stristr( $alias , ':' ) )
				{
					$user		= explode( '-' , JString::str_ireplace( ':' , '-' , $alias ) );
		
					if( isset( $user[0] ) )
					{
						$userid	= $user[0];
					}
				}
			}
		}

		if($userid != 0 )
		{
			array_shift($segments);
			$vars['userid'] = $userid;
			// if empty, we should display the user's profile
			if(empty($segments))
			{
				$vars['view'] = 'profile';
			}
		}
	}

	$count	= count($segments);
	if( !isset($selectedMenu) )
	{
		if( $count > 0 )
		{
			// If there are no menus we try to use the segments
			$vars['view']  = $segments[0];

			if(!empty($segments[1]))
			{
				$vars['task'] = $segments[1];
			}
			
			if(!empty($segments[2] ) && $segments[1] == 'viewgroup' )
			{
				$groupTitle 		= $segments[2];
				$vars['groupid']	= _parseGroup( $groupTitle );
			}
		}
		return $vars;
	}

	if( $selectedMenu->query['view'] == 'frontpage' )
	{
		// We know this is a frontpage view in the menu, try to get the 
		// view from the segments instead.
		if( $count > 0 )
		{
			$vars['view'] = $segments[0];
	
			if(!empty($segments[1]))
			{
				$vars['task'] = $segments[1];
			}
		}
	}
	else
	{
		$vars['view']	= $selectedMenu->query['view'];

		if( $count > 0 )
		{
			$vars['task']	= $segments[0];
		}
	}  


	// In case of video view, the 'task' (video) has been removed during
	// BuildRoute. We need to detect if the segment[0] is actually a 
	// permalink to the actual video, and add the proper task
	if($vars['view'] == 'videos' && (isset($vars['task']) && $vars['task'] != 'myvideos') ) 
	{
		$pattern = "'^[0-9]+'s";
		$videoTitle	= $segments[ count( $segments ) - 1 ];
		preg_match($pattern, $videoTitle, $matches);

		if($matches)
		{
			$vars['task'] = 'video';
		}
	}
	
	// Since we don't specify task for myphotos we need to redefine it here
	if( isset($vars['userid']) && $vars['view'] == 'photos' && !isset( $vars['task'] ) )
	{
		$vars['task']	= 'myphotos';
	}

	// Since we don't specify task for myvideos we need to redefine it here
	if( isset($vars['userid']) && $vars['view'] == 'videos' && !isset( $vars['task'] ) )
	{
		$vars['task']	= 'myvideos';
	}

	// Since we don't specify task for mygroups we need to redefine it here
	if( isset($vars['userid']) && $vars['view'] == 'groups' && !isset( $vars['task'] ) )
	{
		$vars['task']	= 'mygroups';
	}

	// Since we don't specify task for mygroups we need to redefine it here
	if( isset($vars['userid']) && $vars['view'] == 'events' && !isset( $vars['task'] ) )
	{
		$vars['task']	= 'myevents';
	}

	// If the task is video then, query the last segment to grab the video id
	if( isset($vars['task'] ) && $vars['task'] == 'video' )
	{
		$videoTitle	= $segments[ count( $segments ) - 1 ];
		$titles		= explode('-', $videoTitle);
		$vars['videoid'] = $titles[0];
	}
	
	// If the task is viewgroup then, query the last segment to grab the group id
	if( isset($vars['task'] ) && $vars['task'] == 'viewgroup' )
	{
		$groupTitle = $segments[count($segments) - 1];
		$vars['groupid'] = _parseGroup( $groupTitle );
	}

	// If the task is viewevent then, query the last segment to grab the eventid
	if( isset($vars['task'] ) && $vars['task'] == 'viewevent' )
	{
		$title		= $segments[ count($segments ) - 1 ];
		$titles		= explode( '-' , $title );
		$vars['eventid']	= $titles[ 0 ];
	}
	
	// If the task is viewdiscussion then, query the last segment to grab the topic id
	if( isset($vars['task'] ) && $vars['task'] == 'viewdiscussion' ){
		$groupTitle = $segments[count($segments) - 1];
		$titles = explode('-', $groupTitle);
		$vars['topicid'] = $titles[0];
	}
	
	// If the task is viewgroup then, query the last segment to grab the group id
	if( isset($vars['task'] ) && $vars['task'] == 'viewbulletin' ){
		$groupTitle = $segments[count($segments) - 1];
		$titles = explode('-', $groupTitle);
		$vars['bulletinid'] = $titles[0];
	}

	return $vars;
}

function & _parseGroup( $title )
{
	$titles 	= explode('-', $title);
	$groupId	= $titles[0];
	
	return $groupId;
}

function CommunityGetUserId( $alias )
{
	$db			=& JFactory::getDBO();
	$query		= "SELECT `userid` FROM #__community_users WHERE `alias`=" . $db->Quote( $alias );
	$db->setQuery($query);
	$id = $db->loadResult();

	return $id;
}