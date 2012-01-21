<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( dirname(__FILE__).DS.'helper.php' );
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' . DS . 'string.php' );
require_once( JPATH_BASE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');		
CFactory::load( 'models' , 'groups' );
CFactory::load( 'helpers' , 'string' );

$showavatar 			= $params->get('show_avatar', '1');
$repeatAvatar			= $params->get('repeat_avatar', '1');
$showPrivateDiscussion 	= $params->get('show_private_discussion', '1');
$done_group 			= array();
$groupstr 				= array();

$dis = new modLatestDiscussionHelper($params);
$latest = $dis->getLatestDiscussion($showPrivateDiscussion);

JPlugin::loadLanguage("mod_latestdiscussion");

require(JModuleHelper::getLayoutPath('mod_latestdiscussion'));
