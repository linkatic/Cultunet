<?php
/**
 * @category	Module
 * @package		JomSocial
 * @subpackage	OnlineUsers
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once ( dirname(__FILE__) . DS . 'helper.php' );
CFactory::load( 'helpers' , 'string' );
$users	= modOnlineUsersHelper::getUsersData( $params );
$total	= modOnlineUsersHelper::getTotalOnline( $params );

require(JModuleHelper::getLayoutPath('mod_onlineusers'));