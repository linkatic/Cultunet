<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CommunityViewGroups extends CommunityView
{
    
	/**
	 * Display a list of bulletins from the specific group
	 **/
    function display()
    {
		$mainframe= JFactory::getApplication();
		$document =& JFactory::getDocument();
		$view     = JRequest::getVar('task','','get');
		
		if($view == 'viewlatestdiscussions'){
		    $this->_viewlatestdiscussions();
            return;
        }
		
		if($view == 'viewmylatestdiscussions'){
		    $this->_viewmylatestdiscussions();
            return;
        }
		
		$document->setLink(CRoute::_('index.php?option=com_community'));
		
        $model  = CFactory::getModel('groups');
        $rows   = $model->getAllGroups();
		
		CFactory::load( 'helpers' , 'string' );
		
		foreach($rows as $row){
			// load individual item creator class
			$item = new JFeedItem();
			$item->title 		= CStringHelper::escape($row->name);
			$item->link 		= CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$row->id);  
			$item->description 	= '<img src="' . JURI::base() . $row->thumb . '" alt="" />&nbsp;'.$row->description;
			$item->date			= $row->created;
			$item->category   	= '';//$row->category;
			
			// Make sure url is absolute
			$item->description = JString::str_ireplace('href="/', 'href="'. JURI::base(), $item->description); 

			// loads item info into rss array
			$document->addItem( $item );
		}
    }

	/**
	 * Method to display groups that belongs to a user.
	 *
	 * @access public
	 */
	public function mygroups()
	{
		$mainframe =& JFactory::getApplication();
		$document  =& JFactory::getDocument();
		$userId    = JRequest::getVar('userid','');

        $document->setTitle(JText::_('CC MY GROUPS TITLE'));
		$document->setLink(CRoute::_('index.php?option=com_community'));

        $groupsModel	=& CFactory::getModel('groups');
        
		$sorted			= JRequest::getVar( 'sort' , 'latest' , 'GET' );
		$rows			= $groupsModel->getGroups( $userId , $sorted ); 
	
		CFactory::load( 'helpers' , 'string' );
		
		foreach($rows as $row){
			// load individual item creator class
			$item = new JFeedItem();
			$item->title 		= CStringHelper::escape($row->name);
			$item->link 		= CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$row->id);  
			$item->description 	= '<img src="' . JURI::base() . $row->thumb . '" alt="" />&nbsp;'.$row->description;
			$item->date			= $row->created;
			$item->category   	= '';//$row->category;
			
			// Make sure url is absolute
			$item->description = JString::str_ireplace('href="/', 'href="'. JURI::base(), $item->description); 

			// loads item info into rss array
			$document->addItem( $item );
		}

	}
    
	/**
	 * Display a list of bulletins from the specific group
	 **/
	public function viewbulletins()
	{
		$mainframe  = JFactory::getApplication();
		$document	=& JFactory::getDocument();

		// Load necessary files
		CFactory::load( 'models' , 'groups' );
		CFactory::load( 'helpers' , 'owner' );

		$id			= JRequest::getInt( 'groupid' , '' , 'GET' );
		$my			= CFactory::getUser();
		
		// Load the group
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $id );

		if( $group->id == 0 )
		{
			echo JText::_('CC INVALID GROUP ID');
			return;
		}
		
		//display notice if the user is not a member of the group
		if( $group->approvals == 1 && !($group->isMember($my->id) ) && !COwnerHelper::isCommunityAdmin() )
		{
			echo JText::_('CC PRIVATE GROUP NOTICE');
			return;
		}
		
		// Set page title
		$document->setTitle( JText::sprintf('CC VIEW ALL BULLETINS TITLE' , $group->name) );

		// Load submenu
		$this->showSubMenu();

		$model			=& CFactory::getModel( 'bulletins');
		$bulletins		= $model->getBulletins( $group->id, $mainframe->getCfg('feed_limit') );
		
		$jConfig		=& JFactory::getConfig();
		
		// Get the creator of the bulletins
		for( $i = 0; $i < count( $bulletins ); $i++ )
		{
			$row			=& $bulletins[ $i ];
			$row->creator	= CFactory::getUser( $row->created_by );
			$date		=& JFactory::getDate( $row->date );
			$date->setOffset( $jConfig->getValue('offset') );
			
			$item				= new JFeedItem();
			$item->title 		= $row->title;
			$item->link 		= CRoute::_('index.php?option=com_community&view=groups&task=viewbulletin&groupid='. $group->id . '&bulletinid=' . $row->id );
			$item->description 	= $row->message;
			$item->date			= $row->date;

			$document->addItem( $item );
		}
	}
	
	public function viewdiscussions()
	{
		$mainframe  = JFactory::getApplication();
		$document	=& JFactory::getDocument();
		$id			= JRequest::getInt( 'groupid' , '' , 'GET' );
		$my			= CFactory::getUser();

		// Load necessary models, libraries & helpers
		CFactory::load( 'models' , 'groups' );
		CFactory::load( 'helpers' , 'owner' );
		$model		=& CFactory::getModel( 'discussions' );

		// Load the group
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $id );
		$params		= $group->getParams();
		
		//check if group is valid
		if( $group->id == 0 )
		{
			echo JText::_('CC INVALID GROUP ID');
			return;
		}

		// Set page title
		$document->setTitle( JText::sprintf('CC VIEW ALL DISCUSSIONS TITLE' , $group->name) );

		//display notice if the user is not a member of the group
		if( $group->approvals == 1 && !($group->isMember($my->id) ) && !COwnerHelper::isCommunityAdmin() )
		{
			echo JText::_('CC PRIVATE GROUP NOTICE');
			return;
		}
		
		$discussions	= $model->getDiscussionTopics( $group->id , $mainframe->getCfg('feed_limit') ,  $params->get('discussordering' , DISCUSSION_ORDER_BYLASTACTIVITY) );

		$jConfig		=& JFactory::getConfig();
		for( $i = 0; $i < count( $discussions ); $i++ )
		{
			$row		=& $discussions[$i];
			$row->user	= CFactory::getUser( $row->creator );
			$date		=& JFactory::getDate( $row->created );
			$date->setOffset( $jConfig->getValue('offset') );
			
			$item				= new JFeedItem();
			$item->title 		= $row->title;
			$item->link 		= CRoute::_('index.php?option=com_community&view=groups&task=viewdiscussion&groupid=' . $group->id . '&topicid=' . $row->id );
			$item->description 	= $row->message;
			$item->date			= $date->toFormat();

			$document->addItem( $item );
		}
	}
    
    private function _viewlatestdiscussions()
    {
                                            
		$categoryId   = JRequest::getInt( 'categoryid' , 0 );
		$document     =& JFactory::getDocument();  
		$config       = CFactory::getConfig();
                                                         	
		// getting group's latest discussion activities.
     	$model    =& CFactory::getModel('groups');
		$rows     =	$model->getGroupLatestDiscussion($categoryId);

		CFactory::load( 'helpers' , 'string' );
		
		foreach($rows as $row){
		
		    $user               = Cfactory::getUser($row->creator);
		    $profileLink        = CRoute::_('index.php?option=com_community&view=profile&userid='.$row->creator);  
		    
			// load individual item creator class
			$item = new JFeedItem();
			$item->title 		= CStringHelper::escape($row->title);
			$item->link 		= CRoute::_('index.php?option=com_community&view=groups&task=viewdiscussion&groupid='.$row->groupid.'&topicid='.$row->id);
            $item->description  = JText::sprintf('CC DISCUSSION STARTED BY' , $profileLink , $user->getDisplayName()) . '<br />' . JString::substr(strip_tags($row->message),0 , $config->getInt('streamcontentlength')); 
			$item->date			= $row->lastreplied;

			// loads item info into rss array
			$document->addItem( $item );
		}  
    } 
    
    function _viewmylatestdiscussions()
    {
                                            
		$categoryId   = JRequest::getInt( 'categoryid' , 0 );
		$groupIds     = JRequest::getVar( 'groupids' , 0 );
		$document     =& JFactory::getDocument(); 
		$config       = CFactory::getConfig();
                                                       	
		// getting group's latest discussion activities.
     	$model    =& CFactory::getModel('groups');
		$rows     =	$model->getGroupLatestDiscussion($categoryId,$groupIds);

		CFactory::load( 'helpers' , 'string' );
		
		foreach($rows as $row){
		
		    $user               = Cfactory::getUser($row->creator);
		    $profileLink        = CRoute::_('index.php?option=com_community&view=profile&userid='.$row->creator);  
		    
			// load individual item creator class
			$item = new JFeedItem();
			$item->title 		= CStringHelper::escape($row->title);
			$item->link 		= CRoute::_('index.php?option=com_community&view=groups&task=viewdiscussion&groupid='.$row->groupid.'&topicid='.$row->id);
            $item->description  = JText::sprintf('CC DISCUSSION STARTED BY' , $profileLink , $user->getDisplayName()) . '<br />' . JString::substr(strip_tags($row->message),0 , $config->getInt('streamcontentlength')); 
			$item->date			= $row->lastreplied;

			// loads item info into rss array
			$document->addItem( $item );
		}  
    }
}