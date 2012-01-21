<?php
/**
 * @category	Module
 * @package		JomSocial
 * @subpackage	JomSocialConnect
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'window.php' );
CWindow::load();

// Script needs to be here if they are 
CFactory::load( 'libraries' , 'facebook' );

// Once they reach here, we assume that they are already logged into facebook.
// Since CFacebook library handles the security we don't need to worry about any intercepts here.
$facebook		= new CFacebook( false );
$my				= CFactory::getUser();
$config			= CFactory::getConfig();
$fbUser			= $facebook->getUser();

require(JModuleHelper::getLayoutPath('mod_jomsocialconnect'));
