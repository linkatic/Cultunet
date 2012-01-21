<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */  

// no direct access
defined('_JEXEC') or die('Restricted access'); 
jimport( 'joomla.application.component.view');
include_once (COMMUNITY_COM_PATH.DS.'views'.DS.'videos'.DS.'view.php');

class CommunityViewVideos extends CommunityView
{
	var $_videoLib	= null;
	var $model		= '';
	
	function CommunityViewVideos()
	{
		CFactory::load( 'helpers', 'videos' );
		CFactory::load( 'libraries' , 'videos' );
		$this->model	= CFactory::getModel('videos');
		$this->videoLib	= new CVideoLibrary();
	}

	function display($data = null)
	{
		$mainframe  = JFactory::getApplication();
		$document 	=& JFactory::getDocument();
		
        $my			= CFactory::getUser();
		$userid		= JRequest::getVar( 'userid' , '' );
		$groupId	= JRequest::getVar( 'groupid', '', 'GET' ); 
		
		if( !empty($userid) ){  
            $user		= CFactory::getUser($userid);
            			
    		// Set document title
            CFactory::load( 'helpers' , 'owner' );
            $blocked	= $user->isBlocked();
    
    		if( $blocked && !COwnerHelper::isCommunityAdmin() )
    		{
    			$tmpl	= new CTemplate();
    			echo $tmpl->fetch('profile.blocked');
    			return;
    		}  
		
    		if($my->id == $user->id){
    			$title	= JText::_('CC MY VIDEOS');
    		}else{
    			$title	= JText::sprintf('CC USERS VIDEO TITLE', $user->getDisplayName());
            }
            
        }else{
                $title  = JText::_('CC ALL VIDEOS');
        }
         
		
		// list user videos or group videos
		if( !empty($groupId) ){   
            $title  = JText::_('CC SUBSCRIBE TO GROUP VIDEOS FEEDS');
			$group		= JTable::getInstance( 'Group' , 'CTable' );
			$group->load( $groupId );

			CFactory::load( 'helpers' , 'owner' );
			$isMember	= $group->isMember( $my->id );
			$isMine		= ($my->id == $group->ownerid);
			if( !$isMember && !$isMine && !COwnerHelper::isCommunityAdmin() && $group->approvals == COMMUNITY_PRIVATE_GROUP )
			{
				echo JText::_('CC PRIVATE GROUP NOTICE');
				return;
			}
			
			$videos			= $this->model->getGroupVideos( $groupId, '', $mainframe->getCfg('feed_limit') );
			$videoLink      = '&groupid=' . $groupId ;
			
		}else{
    		      
     		$filters		= array
     		(
    			'creator'	=> $userid,
     			'status'	=> 'ready',
     			'groupid'	=> 0,
    			'limit'     => $mainframe->getCfg('feed_limit'),
    			'limitstart'=> 0,
     			'sorting'	=> JRequest::getVar('sort', 'latest')
     		);

    		// list all user videos & all group videos
    		if( empty($userid) ){
    		      unset($filters['creator']); 
    		      unset($filters['groupid']);
    		}
   		
            $videos			= $this->model->getVideos($filters);  
			$videoLink      = '&userid=' . $creatorId;
            
        }
	
		// Prepare feeds
		$document->setTitle($title);
        	
		for($i = 0; $i < $mainframe->getCfg('feed_limit'); $i++)
		{   
			$video = $videos[$i];
          
			$item = new JFeedItem();
			$item->title 		= $video->title;
			$item->link 		= CRoute::_('index.php?option=com_community&view=videos&task=video' . $videoLink . '&videoid='.$video->id);  
			$item->description 	= '<img src="' . JURI::base() . $video->thumb . '" alt="" />&nbsp;'.$video->description;
			$item->date			= $video->created;    
			$item->author		= $video->created;
			
			// Make sure url is absolute
            $item->description  = JString::str_ireplace('href="/', 'href="'. JURI::base(), $item->description); 
	      	
	      	if( !empty($video->id) )
			     $document->addItem( $item );
		}

	}
	
	function myvideos(){
        return $this->display();
    }
	
	
}
