<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once ( dirname(__FILE__) . DS . 'helper.php' );

CFactory::load( 'helpers' , 'string' );

$groups	= modActiveGroupsHelper::getGroupsData( $params );

require(JModuleHelper::getLayoutPath('mod_activegroups'));
