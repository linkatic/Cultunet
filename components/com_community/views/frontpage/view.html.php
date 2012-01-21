<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.arrayhelper');

class CommunityViewFrontpage extends CommunityView
{
	function display()
	{
		$mainframe 	= JFactory::getApplication();		
		$config 	= CFactory::getConfig();
		$document 	= JFactory::getDocument();
		
		$config	=& CFactory::getConfig();
 		$document->setTitle( JText::sprintf('CC FRONTPAGE TITLE', $config->get('sitename')));

		$my 			= CFactory::getUser();
		$model 			= CFactory::getModel('user');
		$avatarModel 	= CFactory::getModel('avatar');
		$status 		= CFactory::getModel('status');
		$photoModel		= CFactory::getModel('photos');		
		
		$frontpageUsers	= intval( $config->get('frontpageusers') );
		$document->addScriptDeclaration("var frontpageUsers	= ".$frontpageUsers.";");
		
		$frontpageVideos	= intval( $config->get('frontpagevideos') );
		$document->addScriptDeclaration("var frontpageVideos	= ".$frontpageVideos.";");
		
		$frontpagePhotos	= intval( $config->get('frontpagephotos') );
		$onlineMembers	= $model->getOnlineUsers( 20 , false );

		$latestPhotos	= $photoModel->getAllPhotos( null , PHOTOS_USER_TYPE, $frontpagePhotos, 0 );
		$status			= $status->get( $my->id );
		
		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {
			$feedLink = CRoute::_('index.php?option=com_community&view=frontpage&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE RECENT ACTIVITIES FEED') . '" href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */

		if( $latestPhotos ) 
		{
			shuffle( $latestPhotos );
			// Make sure it is all photo object
			foreach( $latestPhotos as &$row )
			{
				
				$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
				$photo->bind($row);
				$row = $photo; 
			}
		}
		
		if( !empty($latestPhotos) )
		{
			for( $i = 0; $i < count( $latestPhotos ); $i++ )
			{
				$row	=& $latestPhotos[$i];
				
				$row->user	= CFactory::getUser( $row->creator );
			}
		}
		
		if( $onlineMembers )
			shuffle( $onlineMembers );
		
		if( !empty( $onlineMembers ) )
		{
			for( $i = 0; $i < count( $onlineMembers ); $i++ )
			{
				$row				=& $onlineMembers[$i];
				$row->user			= CFactory::getUser( $row->id );
			}
		}
		
		CFactory::load( 'libraries' , 'tooltip' );
		CFactory::load( 'libraries' , 'activities' );

		

		
		// Process headers HTML output
		$headerHTML	= '';
		$tmpl		= new CTemplate();
		if( $my->id != 0 )
		{
			$headerHTML	= $tmpl->fetch( 'frontpage.members');
			$alreadyLogin = 1;
		}
		else
		{
			$uri	= CRoute::_('index.php?option=com_community&view=profile' , false );
			$uri	= base64_encode($uri);
			
			$fbHtml	= '';

			if( $config->get('fbconnectkey') && $config->get('fbconnectsecret') )
			{
				CFactory::load( 'libraries' , 'facebook' );
				$facebook	= new CFacebook();
				$fbHtml		= $facebook->getLoginHTML();
			}

			$tmpl->set( 'fbHtml' , $fbHtml );
			$tmpl->set( 'return' , $uri );			
			$tmpl->set( 'config' , $config );
			$headerHTML	= $tmpl->fetch( 'frontpage.guests' );
			$alreadyLogin = 0;
		}
		
		$my		= CFactory::getUser();
		$totalMembers  = $model->getMembersCount();
		
		unset( $tmpl );

        $tmpl = new CTemplate();
		$tmpl->set( 'totalMembers'		, $totalMembers);
		$tmpl->set( 'my'				, $my );        
        $tmpl->set( 'alreadyLogin'		, $alreadyLogin );
        $tmpl->set( 'header'			, $headerHTML );
		$tmpl->set( 'onlineMembers' 	, $onlineMembers );
		$tmpl->set( 'userActivities'	, $this->showLatestActivities() );	
		$tmpl->set( 'config'			, $config);
		$tmpl->set( 'latestMembers'     , $this->showLatestMembers( $config->get('frontpageusers') ));
		$tmpl->set( 'latestGroups'		, $this->showLatestGroups( $config->get('frontpagegroups') ));
        $tmpl->set( 'latestPhotos'		, $latestPhotos );		
		$tmpl->set( 'latestVideos'		, $this->showLatestVideos( $config->get('frontpagevideos') ));

		/* --- Legacy code --- 
		   Pre-JomSocial 1.6 template uses $rows var to generate list
		   of latest members. This is now replaced with $latestMembers.
		*/
		$latestMembers	= $model->getLatestMember(($frontpageUsers + 21));

		if( $latestMembers )
			shuffle( $latestMembers );
			
		$latestMemberRows	= array();
		$maxLatestCount		= ( count( $latestMembers ) > $frontpageUsers ) ? $frontpageUsers : count( $latestMembers );

		if( !empty( $latestMembers ) )
		{
			for($i= 0; $i < $maxLatestCount; $i++)
			{
				$row	=& $latestMembers[$i];
				
				$user				= CFactory::getUser( $row->id );
				$row->status		= $user->getStatus();
				$row->smallAvatar	= $user->getThumbAvatar();
				$row->user			= $user;
				$latestMemberRows[]	= $row;
			}
		}
		unset( $latestMembers );
		
		$tmpl->set( 'rows', $latestMemberRows );
		/* --- Legacy code --- */
		
		CFactory::load( 'libraries', 'videos' );
		CFactory::load( 'helpers', 'string' );
		$tmpl->set( 'videoThumbWidth' , CVideoLibrary::thumbSize('width') );
		$tmpl->set( 'videoThumbHeight' , CVideoLibrary::thumbSize('height') );
		
		echo $tmpl->fetch('frontpage.index');
	}
	
	function showLatestActivities()
	{
		$act			= new CActivityStream();
		$config			= CFactory::getConfig();
		$my				= CFactory::getUser();
		$userActivities	= '';
		
		if( $config->get('frontpageactivitydefault') == 'friends' && $my->id != 0 )
		{
			CFactory::load( 'helpers' , 'time' );
			$friendsModel	= CFactory::getModel( 'Friends' );

			$userActivities	= $act->getHTML( $my->id, $friendsModel->getFriendIds( $my->id ) , CTimeHelper::getDate( $my->registerDate) , $config->get('maxactivities') );
		}
		else
		{
			$userActivities	= $act->getHTML('', '', null, $config->get('maxactivities'));
		}
		return $userActivities;
	}
	
	function showMostActive($data = null){
	}
	
	/**
	 * Show listing of group with the most recent activities
	 */	 	
	function showActiveGroup()
	{
		$groupModel 	=& CFactory::getModel('groups');
		$activityModel	=& CFactory::getModel('activities');
		$act	= new CActivityStream();
		
		$html = $act->getHTML( '', '', null, 10 , 'groups');
		
		return $html;
	}
	
	function showLatestGroups( $total = 5 )
	{
		$groupModel	=& CFactory::getModel('groups');
		$tmpGroups	= $groupModel->getAllGroups( null , null , null , $total );
		$groups		= array();
		
		foreach($tmpGroups as $row)
		{
			$group	=& JTable::getInstance('Group','CTable');
			$group->load( $row->id );
			$groups[]	= $group;
		}
		$tmpl = new CTemplate();
        $tmpl->setRef( 'groups', $groups );
        return $tmpl->fetch('frontpage.latestgroup');
	}
	
	function showLatestVideos( $total = 5 )
	{
		$my		= CFactory::getUser();
		
		// Oversample the total so that we get a randomized value
		$oversampledTotal	= $total * COMMUNITY_OVERSAMPLING_FACTOR;
		
		CFactory::load( 'helpers', 'owner' );
		CFactory::load( 'helpers', 'videos' );
		CFactory::load( 'helpers', 'privacy' );
		CFactory::load( 'libraries' , 'activities' );
		
		$videoModel 	= CFactory::getModel('videos');
		$videosfilter	= array(
			'published'	=> 1,
			'status'	=> 'ready',
			'permissions'	=> ($my->id==0) ? 0 : 20,
			'or_group_privacy'	=> 0,
			'limit'		=> $oversampledTotal
		);
		$videos			= $videoModel->getVideos($videosfilter);

		if( $videos )
		{
			shuffle( $videos );
			
			// Test the number of result so the loop will not fail with incorrect index.
			$total		= count( $videos ) < $total ? count($videos) : $total;
			$videos		= array_slice($videos, 0, $total);
			
			CFactory::load('helpers', 'videos');
			$videos		= CVideosHelper::prepareVideos($videos);
		}
		
		return $videos;
	}
	
	function showLatestMembers($limit)
	{
		$model =& CFactory::getModel('user');
		$latestMembers = $model->getLatestMember( $limit );
		$totalMembers  = $model->getMembersCount();
		
		$data = array();
		
		if( !empty( $latestMembers ) )
		{
			shuffle( $latestMembers );
			$data['members'] = $latestMembers;
			$data['limit'] = ( count( $latestMembers ) > $limit ) ? $limit : count( $latestMembers );	
		}

		$tmpl = new CTemplate();
        $tmpl->set('memberList', $this->get('getMembersHTML', $data));
        $tmpl->set('totalMembers', $totalMembers);
        return $tmpl->fetch('frontpage.latestmember');
	}

	function getMembersHTML($data)
	{
		if (empty($data)) return '';
		
		$members	= $data['members'];
		$limit		= $data['limit'];

		for($i=0; $i<$limit; $i++)
		{
			$member              =& $members[$i];				
			$user                = CFactory::getUser( $member->id );
			$member->status	     = $user->getStatus();
			$member->avatar      = $user->getThumbAvatar();
			$member->tooltip     = cAvatarTooltip($member); 
		  	$member->displayName = $user->getDisplayName();            
			$member->profileLink = CRoute::_('index.php?option=com_community&view=profile&userid='.$member->id );
		}

		$tmpl = new CTemplate();
		$tmpl->set('members', $members);
		$html = $tmpl->fetch('frontpage.latestmember.list');

		echo $html;
	}
}

