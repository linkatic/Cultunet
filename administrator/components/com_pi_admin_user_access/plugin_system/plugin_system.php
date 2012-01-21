<?php
/**
* @package Admin-User-Access (com_pi_admin_user_access)
* @version 2.3.1
* @copyright Copyright (C) 2007-2011 Carsten Engel. All rights reserved.
* @license GPL available versions: free, trial and pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//silly workaround for developers who install the trail version while totally ignoring 
//all warnings about that you need Ioncube installed or else it will criple the site
$aua_trial_version = 0;

if(!$aua_trial_version || ($aua_trial_version && extension_loaded('ionCube Loader'))){
	include(dirname(__FILE__).'/plugin_system2.php');
}

?>