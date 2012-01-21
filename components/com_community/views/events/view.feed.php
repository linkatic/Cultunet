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

class CommunityViewEvents extends CommunityView
{
	
	function display($data = null){
		$mainframe= JFactory::getApplication();
		$document =& JFactory::getDocument();
        $userid   = JRequest::getCmd('userid', null );
		
		$document->setLink(CRoute::_('index.php?option=com_community'));
		
		include_once(JPATH_COMPONENT . DS.'libraries'.DS.'events.php');
		$event    = new CEvents;
        		
		$rows     = $event->getFEED($mainframe->getCfg('feed_limit'), $userid);
		
		CFactory::load( 'helpers' , 'string' );
		
		foreach($rows as $row)
		{
			if($row->type != 'title')
			{
				$event	=& JTable::getInstance( 'Event' , 'CTable' );
				$event->load( $row->id );
                 
				$eventDetails        = '<div>'.JText::_('CC EVENTS NUM TICKET').': '. $row->ticket .'</div>'; 
				$eventDetails       .= '<div>'.JText::_('CC EVENTS LOCATION').': '. $row->location .'</div>'; 
				$eventDetails       .= '<div>'.JText::_('Start Date').': '. $row->startdate .'</div>';
				$eventDetails       .= '<div>'.JText::_('End Date').': '. $row->enddate .'</div>';
				$eventDetails       .= '<br />';
				$eventDetails       .= '<div>'.JText::_('CC EVENTS TOTAL CONFIRMED').': '. $row->confirmedcount .', ';
				$eventDetails       .= JText::_('CC EVENTS TOTAL MAYBE').': '. $row->maybecount .', ';
				$eventDetails       .= JText::_('CC EVENTS TOTAL REJECTED').': '. $row->declinedcount .'</div>';
				
				// load individual item creator class
				$item = new JFeedItem();
				$item->title 		= CStringHelper::escape($row->title);
				$item->link 		= CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid='.$row->id);  
				$item->description 	= '<img src="' . $event->getThumbAvatar() . '" alt="" />&nbsp;'.$row->description.$eventDetails;
				$item->date		    = $row->created;
				$item->category   	= '';//$row->category;				
				
				// Make sure url is absolute
				$item->description = JString::str_ireplace('href="/', 'href="'. JURI::base(), $item->description);  
	
				// loads item info into rss array
				$document->addItem( $item );
			}
		}
	   

    }
    
    
    function myevents()
    {
        $this->display();
    }

	
	function expiredevents()
	{
		if(!$this->accessAllowed('registered'))	return;

	    $mainframe	=&	JFactory::getApplication();
	    $document 	=&	JFactory::getDocument();
	    $config		=&	CFactory::getConfig();
		$my			=	CFactory::getUser();

        $document->setTitle(JText::_('CC PAST EVENTS TITLE'));

		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {
			$feedLink = CRoute::_('index.php?option=com_community&view=events&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */

		// loading neccessary files here.
		CFactory::load( 'libraries' , 'filterbar' );
		CFactory::load( 'helpers' , 'event' );
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'events');

		$sorted		= JRequest::getVar( 'sort' , 'startdate' , 'GET' );
		$model		=& CFactory::getModel( 'events' );

 		// It is safe to pass 0 as the category id as the model itself checks for this value.
 		$rows        = $model->getEvents( null, $my->id , $sorted, null, false, true );

		$sortItems =  array(
				'latest' 		=> JText::_('CC SORT EVENT CREATED') ,
				'startdate'	=> JText::_('CC SORT EVENT START DATE'));
	
		CFactory::load( 'helpers' , 'string' );
		
		foreach($rows as $row)
		{
			if($row->type != 'title')
			{
				$event	=& JTable::getInstance( 'Event' , 'CTable' );
				$event->load( $row->id );
                 
        		$eventDetails        = '<div>'.JText::_('CC EVENTS NUM TICKET').': '. $row->ticket .'</div>'; 
        		$eventDetails       .= '<div>'.JText::_('CC EVENTS LOCATION').': '. $row->location .'</div>'; 
        		$eventDetails       .= '<div>'.JText::_('Start Date').': '. $row->startdate .'</div>';
        		$eventDetails       .= '<div>'.JText::_('End Date').': '. $row->enddate .'</div>';
        		$eventDetails       .= '<br />';
        		$eventDetails       .= '<div>'.JText::_('CC EVENTS TOTAL CONFIRMED').': '. $row->confirmedcount .', ';
        		$eventDetails       .= JText::_('CC EVENTS TOTAL MAYBE').': '. $row->maybecount .', ';
        		$eventDetails       .= JText::_('CC EVENTS TOTAL REJECTED').': '. $row->declinedcount .'</div>';
				
				// load individual item creator class
				$item = new JFeedItem();
				$item->title 		= CStringHelper::escape($row->title);
				$item->link 		= CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid='.$row->id);  
				$item->description 	= '<img src="' . $event->getThumbAvatar() . '" alt="" />&nbsp;'.$row->description.$eventDetails;
				$item->date			= $row->created;
				$item->category   	= '';//$row->category;
				
				// Make sure url is absolute
				$item->description = JString::str_ireplace('href="/', 'href="'. JURI::base(), $item->description); 
	
				// loads item info into rss array
				$document->addItem( $item );
			}
		}

	}
}