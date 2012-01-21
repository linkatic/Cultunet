<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once( dirname( __FILE__ ) . DS . 'helper.php' );

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
CFactory::load( 'helpers' , 'string' );
$comments = modPhotoCommentsHelper::getList($params);

require(JModuleHelper::getLayoutPath('mod_photocomments'));