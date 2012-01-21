<?php
	/**
	 * @category	Model
	 * @package		JomSocial
	 * @subpackage	Groups 
	 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
	 * @license		GNU/GPL, see LICENSE.php
	 */
	defined('_JEXEC') or die('Restricted access');
 	
	JPlugin::loadLanguage( 'com_community', JPATH_ROOT );	
	
	include_once(JPATH_BASE.DS.'components'.DS.'com_community'.DS.'defines.community.php');	
	require_once( JPATH_BASE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
	include_once(COMMUNITY_COM_PATH.DS.'libraries'.DS.'activities.php');
	include_once(COMMUNITY_COM_PATH.DS.'helpers'.DS.'time.php');

    $document   =& JFactory::getDocument();
    
    $document->addStyleSheet( rtrim( JURI::root() , '/' ) . '/modules/mod_activitystream/style.css' );		
	$activities = new CActivityStream();
	$maxEntry = $params->get('max_entry', 10);
	
	$str = $activities->getHTML('', '', null, $maxEntry, '', 'mod_', false);
	
	echo $str;
?>