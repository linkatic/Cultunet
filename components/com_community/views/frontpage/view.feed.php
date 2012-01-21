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
	function display($data = null){
		$mainframe	= JFactory::getApplication();
		$config		= CFactory::getConfig();
		$document	=& JFactory::getDocument();
		$document->setTitle( JText::sprintf('CC FRONTPAGE TITLE', $config->get('sitename')));
		$document->setLink(CRoute::_('index.php?option=com_community'));
		
		include_once(JPATH_COMPONENT . DS.'libraries'.DS.'activities.php');
		$act = new CActivityStream();
		
		$rows = $act->getFEED('', '', null, $mainframe->getCfg('feed_limit'));
		
		CFactory::load( 'helpers' , 'string' );
		
		foreach($rows as $row)
		{
			if($row->type != 'title')
			{
				// load individual item creator class
				$item = new JFeedItem();
				$item->title 		= CStringHelper::escape($row->title);
				$item->link 		= CRoute::_('index.php?option=com_community&view=profile&userid='.$row->actor);
				$item->description 	= "<img src=\"{$act->favicon}\" alt=\"\"/>&nbsp;".$row->title;
				$item->date			= $row->createdDate;
				$item->category   	= '';//$row->category;
				
				// Make sure url is absolute
				$item->description = JString::str_ireplace('href="/', 'href="'. JURI::base(), $item->description); 
	
				// loads item info into rss array
				$document->addItem( $item );
			}
		}
		
	}
}