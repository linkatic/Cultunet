<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

// Get a new installer
$plg_installer = new JInstaller();

$db = JFactory::getDBO();

$db->setQuery("SELECT id FROM #__plugins WHERE `element`='rsmembership' AND `folder`='system' LIMIT 1");
$plg_id = $db->loadResult();
if ($plg_id)
	$plg_installer->uninstall('plugin', $plg_id);

$db->setQuery("SELECT id FROM #__plugins WHERE `element`='rsmembershipwire' AND `folder`='system' LIMIT 1");
$plg_id = $db->loadResult();
if ($plg_id)
	$plg_installer->uninstall('plugin', $plg_id);
?>
<strong>RSMembership! 1.0.0 uninstalled</strong>