<?php

/**
 * Return avatar tooltip title
 * @todo: this is perfect candidate for caching
 * 
 * @param	row		user object   
 */ 
function cAvatarTooltip( &$row ){
	$friendsModel 	=& CFactory::getModel('friends');
	$userModel 		=& CFactory::getModel('user');
	
	$user			= CFactory::getUser($row->id);
	$numFriends		= $user->getFriendCount();

	if($user->isOnline()) 
		$isOnline = '<img style="vertical-align:middle;padding: 0px 4px;" src="'.JURI::base().'components/com_community/assets/status_online.png" />'. JText::_('CC ONLINE');
	else
		$isOnline = '<img style="vertical-align:middle;padding: 0px 4px;" src="'.JURI::base().'components/com_community/assets/status_offline.png" />'.JText::_('CC OFFLINE');
	
	CFactory::load( 'helpers' , 'string');
	$html  = $row->getDisplayName() . '::';
	$html .= $user->getStatus().'<br/>';
	$html .= '<hr noshade="noshade" height="1"/>';
	$html .= $isOnline. ' | <img style="vertical-align:middle;padding: 0px 4px;" src="'.JURI::base().'components/com_community/assets/default-favicon.png" />'.JText::sprintf( (CStringHelper::isPlural($numFriends)) ? 'CC FRIENDS COUNT MANY' : 'CC FRIENDS COUNT', $numFriends);
	return htmlentities($html, ENT_COMPAT, 'UTF-8');
}
