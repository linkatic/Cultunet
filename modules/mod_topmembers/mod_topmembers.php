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
$config	=& CFactory::getConfig();

$enablekarma = 0;
if($config->get('enablekarma'))
{
	$enablekarma = $params->get('show_karma', 1);
}
$params->def('enablekarma', $enablekarma);

$users	= modTopMembersHelper::getMembersData( $params );

// preload users
$CFactoryMethod = get_class_methods('CFactory');					
if(in_array('loadUsers', $CFactoryMethod))
{
	$uids = array();
	foreach($users as $m)
	{
		$uids[] = $m->id;
	}
	CFactory::loadUsers($uids);
}

require(JModuleHelper::getLayoutPath('mod_topmembers'));