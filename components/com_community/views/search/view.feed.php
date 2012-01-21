<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */  

// no direct access
defined('_JEXEC') or die('Restricted access'); 
jimport( 'joomla.application.component.view'); 

require_once( COMMUNITY_COM_PATH . DS . 'helpers' . DS . 'string.php' );

class CommunityViewSearch extends CommunityView
{

	function display($data = null)
	{
		$mainframe  = JFactory::getApplication();
		$document 	=& JFactory::getDocument();
		
		$model      = CFactory::getModel( 'search' );
		$members    = $model->getPeople();

		// Prepare feeds		
// 		$document->setTitle($title);
       	
		foreach($members as $member)
		{   
			$user   = CFactory::getUser($member->id);
			$friendCount = JText::sprintf( (CStringHelper::isPlural($user->getFriendCount())) ? 'CC FRIENDS COUNT MANY' : 'CC FRIENDS COUNT', $user->getFriendCount());

			$item = new JFeedItem();
			$item->title 		= $user->getDisplayName();
			$item->link 		= CRoute::_('index.php?option=com_community&view=profile&userid='.$user->id);  
			$item->description 	= '<img src="' . $user->getThumbAvatar() . '" alt="" />&nbsp;' . $friendCount; 
			$item->date         = '';
			
			// Make sure url is absolute
            $item->description  = JString::str_ireplace('href="/', 'href="'. JURI::base(), $item->description);
      
            $document->addItem( $item );
		}

	}
	
	function browse(){
        return $this->display();
    }
	
	
}
