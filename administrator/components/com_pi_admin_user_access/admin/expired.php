<?php
/**
* @package Admin-User-Access (com_pi_admin_user_access)
* @version 2.3.1
* @copyright Copyright (C) 2007-2011 Carsten Engel. All rights reserved.
* @license GPL available versions: free, trial and pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

//no direct access
if(!defined('_VALID_MOS') && !defined('_JEXEC')){
	die('Restricted access');
}

if(defined('_JEXEC')){
	//joomla 1.5
	JToolBarHelper::title( JText::_( 'Admin User Access' ), 'generic.png' );
}else{
	//joomla 1.0.x
	echo '<table class="adminheading"><tr><th>Admin User Access</th></tr></table>';
}	

?>
<link href="components/com_pi_admin_user_access/css/pi_admin_user_access3.css" rel="stylesheet" type="text/css" />
<div style="text-align: left; width: 350px; margin: 0 auto;">

<p>
	trialversion has expired.
</p>
<p>For testing and developing you can use this component on 'localhost' for an unlimited duration. For testing and developing online this trial-version is limited to a few weeks. If you need more time developing or testing, download a new version of the component and reinstall. No need to re-enter usergroup data or config, those will remain in the database.</p>
<p><a href="http://www.pages-and-items.com/shop" target="_blank">purchase Admin-User-Access</a>.</p>
<p>
To use the trial-version you need to run code encrypted with Ioncube. Ioncube needs to be enabled on your server.
</p>
<?php
	
	//check if ioncube is installed
	if(extension_loaded('ionCube Loader')){
		echo 'ionCube is loaded succesfully.';
	}else{
		//get name of loaderfile
		$__oc=strtolower(substr(php_uname(),0,3));
		$__ln='ioncube_loader_'.$__oc.'_'.substr(phpversion(),0,3).(($__oc=='win')?'.dll':'.so');
		echo '<span style="color: red;">ionCube is not loaded.</span><br />Top read the encrypted source code your system needs this loader-file:<br />'.$__ln.'<ol><li>Check the <a href="http://www.ioncube.com/loaders.php" target="_blank">IonCube loaderpage</a> (opens in new window).</li><li>Download the correct loader for your OS/platform.</li><li>Upload this file to:<br />administrator/components/com_pi_admin_user_access/ioncube/</li><li>Refresh this page.<br />If Ioncube is still not loaded, go to <a href="http://www.ioncube.com" target="_blank">www.ioncube.com</a> for help with installing.</li></ol>';
	}	
	
//end div
echo '</div>';

//footer
echo '<div class="smallgrey" id="ua_footer"><a href="http://www.pages-and-items.com/admin-user-access" target="_blank">Admin-User-Access</a> &copy;</div>';

?>