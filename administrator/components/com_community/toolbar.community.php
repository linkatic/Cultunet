<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

//require_once( JApplicationHelper::getPath('toolbar_html') );

$view	= JRequest::getCmd('view','community');

JHTML::_('behavior.switcher');

// Load submenu's
// $views	= array(
// 					'community'			=> JText::_('CC COMMUNITY'),
// 					'users'				=> JText::_('CC USERS'),
// 					'configuration'		=> JText::_('CC CONFIGURATION'),
// 					'profiles' 			=> JText::_('CC CUSTOM PROFILES'),
// 					'groups'			=> JText::_('CC GROUPS'),
// 					'groupcategories'	=> JText::_('CC GROUP CATEGORIES'),
// 					'events'			=> JText::_('CC EVENTS'),
// 					'eventscategories'	=> JText::_('CC EVENT CATEGORIES'),
// 					'videoscategories'	=> JText::_('CC VIDEOS CATEGORIES'),
// 					'reports'			=> JText::_('CC REPORTINGS'),
// 					'userpoints'		=> JText::_('CC USER POINTS'),
// 					'about'				=> JText::_('CC ABOUT')
// 				);
				
$views	= array(
					'community'			=> 'community',
					'users'				=> 'users',
					'configuration'		=> 'community',
					'profiles' 			=> 'users',
					'groups'			=> 'groups',
					'groupcategories'	=> 'groups',
					'events'			=> 'events',
					'eventcategories'	=> 'events',
					'videoscategories'	=> 'community',
					'reports'			=> 'community',
					'userpoints'		=> 'users',
					'about'				=> 'community'
				);
				
$subViews['community']  = array(
					'community'			=> JText::_('CC COMMUNITY'),
					'configuration'		=> JText::_('CC CONFIGURATION'),
					'users'				=> JText::_('CC USERS'),
					'groups'			=> JText::_('CC GROUPS'),
					'events'			=> JText::_('CC EVENTS'),
					'videoscategories'	=> JText::_('CC VIDEOS CATEGORIES'),
					'reports'			=> JText::_('CC REPORTINGS'),
					'about'				=> JText::_('CC ABOUT')
				);

$subViews['users']  = array(
					'community'			=> JText::_('CC COMMUNITY'),
					'users'				=> JText::_('CC USERS'),
					'profiles' 			=> JText::_('CC CUSTOM PROFILES'),
					'userpoints'		=> JText::_('CC USER POINTS')
				);
				
$subViews['groups']  = array(
					'community'			=> JText::_('CC COMMUNITY'),
					'groups'			=> JText::_('CC GROUPS'),
					'groupcategories'	=> JText::_('CC GROUP CATEGORIES')
				);
				
$subViews['events']  = array(
					'community'			=> JText::_('CC COMMUNITY'),
					'events'			=> JText::_('CC EVENTS'),
					'eventcategories'	=> JText::_('CC EVENT CATEGORIES')
				);
				
				
$currentView    = '';

if(array_key_exists($view, $views))
{
	$currentView    = $views[$view];
}
				
if(! array_key_exists($currentView, $subViews))
{
    $currentView    = 'community';
}

foreach( $subViews[$currentView] as $key => $val )
{
	$active	= ( $view == $key );
	JSubMenuHelper::addEntry( $val , 'index.php?option=com_community&view=' . $key , $active );
}


// foreach( $views as $key => $val )
// {
// 	$active	= ( $view == $key );
//
// // 	if( $key == 'applications' )
// // 	{
// // 		// For applications, we just link to Joomla's plugin manager which filters
// // 		// plugins with the elements of 'community'
// // 		JSubMenuHelper::addEntry( $val , 'index.php?option=com_plugins&filter_type=community' , false );
// // 	}
// // 	else
// // 	{
// 		JSubMenuHelper::addEntry( $val , 'index.php?option=com_community&view=' . $key , $active );
// // 	}
// }
?>
