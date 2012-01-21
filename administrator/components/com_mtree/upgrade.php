<?php
/**
 * @version		$Id: upgrade.php 876 2010-05-21 11:52:19Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

$database =& JFactory::getDBO();

if(is15xSeries()) {
	
	upgrade15x_157();
	upgrade157_158();
	upgrade158_200();

}

$database->setQuery('SELECT value FROM #__mt_config WHERE varname =\'version\' LIMIT 1');
$version = $database->loadResult();

// If current version is 2.00
if(in_array($version,array('2.00','-1','2.0.0',''))) {
	upgrade(2,0,0);
}

$database->setQuery('SELECT value, varname FROM #__mt_config WHERE varname LIKE \'%version\' AND groupname = \'core\' LIMIT 4');
$version = $database->loadObjectList('varname');

// A fix for 2.0.1/3/4. Appears that 2.0.1/3/4's mtree.xml file does not update dev_version to 1/3/4
if(in_array($version['version']->value,array('2.0.1','2.0.3','2.0.4'))) {
	$version['major_version']->value = 2;
	$version['minor_version']->value = 0;
	$version['dev_version']->value = substr($version['version']->value,-1,1);
}

JToolBarHelper::title( JText::_('Mosets Tree Upgrader') );
printStartTable();

if( upgrade($version['major_version']->value,$version['minor_version']->value,$version['dev_version']->value) === false) {
	// printRow('You\'re currently at version ' . $version['major_version']->value . '.' . $version['minor_version']->value . '.' . $version['dev_version']->value);
	// printRow('No more upgrades needed.',2);
} else {
	printRow('Upgrades Completed!',2);
	printRow('<a href="index.php?option=com_mtree">&lt; Back to Mosets Tree</a>',2);
}

printEndTable();


function is15xSeries() {
	global $mainframe;

	$database =& JFactory::getDBO();
	$db_prefix = $mainframe->getCfg('dbprefix');

	$database->setQuery( "SHOW TABLE STATUS LIKE '" . $db_prefix . "mt_config'" );
	$table = $database->loadObject();
	if($table->Name == $db_prefix.'mt_config' && $table->Rows == 30) {
		return true;
	}
	return false;
}

function upgrade($major,$minor,$dev) {
	$updated = false;
	$nextUpgradeVersion = getNextUpgradeVersion($major,$minor,$dev);
	$currentUpgradeVersion = array($major,$minor,$dev);
	while($nextUpgradeVersion !== false) {
		printStartTable('Upgrade: Mosets Tree ' . $currentUpgradeVersion[0] . '.' . $currentUpgradeVersion[1] . '.' . $currentUpgradeVersion[2] . ' - ' . implode('.',$nextUpgradeVersion));
		require(JPATH_COMPONENT_ADMINISTRATOR.DS.'upgrade'.DS.implode('_',$nextUpgradeVersion) . '.php');
		$className = 'mUpgrade_' . implode('_',$nextUpgradeVersion);
		$upgrade = new $className;
		$upgrade->upgrade();
		
		if($upgrade->updated() === true) {
			printRow('Successfully upgraded to <b>Mosets Tree ' . implode('.',$nextUpgradeVersion) .'</b>.');
		} elseif( $upgrade->updated() === false ) {
			$document=& JFactory::getDocument();
			$document->addCustomTag('<meta http-equiv="Refresh" content="1; URL='.$upgrade->continue_url.'">');
			if( isset($upgrade->continue_message) ) {
				printRow($upgrade->continue_message);
				printRow('<a href="'.$upgrade->continue_url.'">Click here to continue if page does not reload.</a>');
			} else {
				printRow('processing upgrade...');
			}
			return false;
		} else {
			printRow('No update required for <b>Mosets Tree ' . implode('.',$nextUpgradeVersion) .'</b>.');
		}
		if(!$updated) $updated = $upgrade->updated();
		$currentUpgradeVersion = array($nextUpgradeVersion[0],$nextUpgradeVersion[1],$nextUpgradeVersion[2]);
		$nextUpgradeVersion = getNextUpgradeVersion($nextUpgradeVersion[0],$nextUpgradeVersion[1],$nextUpgradeVersion[2]);
		printEndTable();
	}

	return $updated;
	
}

function getNextUpgradeVersion($major,$minor,$dev) {
	// Look if there is a next $dev version
	if(file_exists(JPATH_COMPONENT_ADMINISTRATOR.DS.'upgrade'.DS. $major . '_' . $minor . '_' .($dev +1) . '.php')) {
		return array($major,$minor,($dev +1));
	// Look if there is a next $minor version
	} elseif(file_exists(JPATH_COMPONENT_ADMINISTRATOR.DS.'upgrade'.DS. $major . '_' . ($minor +1) . '_0.php')) {
		return array($major,($minor +1),0);
	// Look if there is a next $major version
	} elseif(file_exists(JPATH_COMPONENT_ADMINISTRATOR.DS.'upgrade'.DS. ($major +1) . '_0_0.php')) {
		return array(($major +1),0,0);
	} else {
		return false;
	}
	return true;
}

function updateVersion($major,$minor,$dev) {
	$database =& JFactory::getDBO();

	$database->setQuery('SELECT value FROM #__mt_config WHERE varname = \'major_version\' LIMIT 1');
	if($database->loadResult() == '') {
		addRows('config',array(array('major_version', 'core', $major, '', 'text', 0, 0)));
	} else {
		$database->setQuery('UPDATE #__mt_config SET value = \'' . $major . '\' WHERE varname = \'major_version\' LIMIT 1');
		$database->query();
	}
	
	$database->setQuery('SELECT value FROM #__mt_config WHERE varname = \'minor_version\' LIMIT 1');
	if($database->loadResult() == '') {
		addRows('config',array(array('minor_version', 'core', $minor, '', 'text', 0, 0)));
	} else {
		$database->setQuery('UPDATE #__mt_config SET value = \'' . $minor . '\' WHERE varname = \'minor_version\' LIMIT 1');
		$database->query();
	}
	
	$database->setQuery('SELECT value FROM #__mt_config WHERE varname = \'dev_version\' LIMIT 1');
	if($database->loadResult() == '') {
		addRows('config',array(array('dev_version', 'core', $dev, '', 'text', 0, 0)));
	} else {
		$database->setQuery('UPDATE #__mt_config SET value = \'' . $dev . '\' WHERE varname = \'dev_version\' LIMIT 1');
		$database->query();
	}

	$database->setQuery('UPDATE #__mt_config SET value = \'' . $major . '.' . $minor . '.' . $dev . '\' WHERE varname = \'version\' LIMIT 1');
	$database->query();
}

function upgrade158_200() {
	global $mt_listing_image_dir, $mt_cat_image_dir;
	
	$database =& JFactory::getDBO();
	
	printStartTable('Upgrade: Mosets Tree 1.58 - 2.00');
	$updated = false;
	
	# Create tables with empty rows
	if(createTable('archived_log', array('`log_id` int(10) unsigned NOT NULL', '`log_ip` varchar(255) NOT NULL default \'\'', '`log_type` varchar(32) NOT NULL default \'\'', '`user_id` int(11) NOT NULL default \'0\'', '`log_date` datetime NOT NULL default \'0000-00-00 00:00:00\'', '`link_id` int(11) NOT NULL default \'0\'', '`rev_id` int(11) NOT NULL default \'0\'', '`value` tinyint(4) NOT NULL default \'0\'', 'PRIMARY KEY  (`log_id`)', 'KEY `link_id2` (`link_id`,`log_ip`)', 'KEY `link_id1` (`link_id`,`user_id`)', 'KEY `user_id` (`user_id`)', 'KEY `log_type` (`log_type`)', 'KEY `log_ip` (`log_ip`,`user_id`)'))) $updated = true;
	if(createTable('archived_reviews', array('`rev_id` int(11) NOT NULL', '`link_id` int(11) NOT NULL default \'0\'', '`user_id` int(11) NOT NULL default \'0\'', '`guest_name` varchar(255) NOT NULL default \'\'', '`rev_title` varchar(255) NOT NULL default \'\'', '`rev_text` text NOT NULL', '`rev_date` datetime NOT NULL default \'0000-00-00 00:00:00\'', '`rev_approved` tinyint(4) NOT NULL default \'1\'', '`admin_note` mediumtext NOT NULL', '`vote_helpful` int(10) unsigned NOT NULL default \'0\'', '`vote_total` int(10) unsigned NOT NULL default \'0\'', '`ownersreply_text` text NOT NULL', '`ownersreply_date` datetime NOT NULL default \'0000-00-00 00:00:00\'', '`ownersreply_approved` tinyint(4) NOT NULL default \'0\'', '`ownersreply_admin_note` mediumtext NOT NULL', 'PRIMARY KEY  (`rev_id`)', 'KEY `link_id` (`link_id`,`rev_approved`)', 'KEY `user_id` (`user_id`)'))) $updated = true;
	if(createTable('archived_users', array('`id` int(11) NOT NULL', '`name` varchar(50) NOT NULL default \'\'', '`username` varchar(25) NOT NULL default \'\'', '`email` varchar(100) NOT NULL default \'\'', '`password` varchar(100) NOT NULL default \'\'', '`usertype` varchar(25) NOT NULL default \'\'', '`block` tinyint(4) NOT NULL default \'0\'', '`sendEmail` tinyint(4) default \'0\'', '`gid` tinyint(3) unsigned NOT NULL default \'1\'', '`registerDate` datetime NOT NULL default \'0000-00-00 00:00:00\'', '`lastvisitDate` datetime NOT NULL default \'0000-00-00 00:00:00\'', '`activation` varchar(100) NOT NULL default \'\'', '`params` text NOT NULL', 'PRIMARY KEY  (`id`)', 'KEY `usertype` (`usertype`)', 'KEY `idx_name` (`name`)'))) $updated = true;
	// if(createTable('cats_images', array('`cat_id` int(10) unsigned NOT NULL', '`filename` varchar(255) NOT NULL', '`small_filedata` mediumblob NOT NULL', '`small_filesize` int(11) NOT NULL', '`original_filedata` mediumblob NOT NULL', '`original_filesize` int(11) NOT NULL', '`extension` varchar(255) NOT NULL', 'PRIMARY KEY  (`cat_id`)', 'UNIQUE KEY `cat_id` (`cat_id`)'))) $updated = true;
	if(createTable('cfvalues', array('`id` int(11) NOT NULL auto_increment', '`cf_id` int(11) NOT NULL', '`link_id` int(11) NOT NULL', '`value` mediumtext NOT NULL', '`attachment` int(10) unsigned NOT NULL default \'0\'', 'PRIMARY KEY  (`id`)', 'KEY `cf_id` (`cf_id`,`link_id`)', 'KEY `link_id` (`link_id`)', 'KEY `value` (`value`(8))'))) $updated = true;
	if(createTable('cfvalues_att', array('`link_id` int(10) unsigned NOT NULL', '`cf_id` int(10) unsigned NOT NULL', '`filename` varchar(255) NOT NULL', '`filedata` mediumblob NOT NULL', '`filesize` int(11) NOT NULL', '`extension` varchar(255) NOT NULL', 'PRIMARY KEY  (`link_id`,`cf_id`)'))) $updated = true;
	if(createTable('clone_owners', array('`user_id` int(11) NOT NULL', '`owner_id` int(11) NOT NULL', 'PRIMARY KEY  (`user_id`)', 'KEY `owner_id` (`owner_id`)'))) $updated = true;
	if(createTable('favourites', array('`fav_id` int(11) NOT NULL auto_increment', '`link_id` int(11) NOT NULL', '`user_id` int(11) NOT NULL', '`fav_date` datetime NOT NULL', 'PRIMARY KEY  (`fav_id`)'))) $updated = true;
	// if(createTable('images', array('`img_id` int(11) NOT NULL auto_increment', '`link_id` int(10) unsigned NOT NULL', '`filename` varchar(255) NOT NULL', '`small_filedata` mediumblob NOT NULL', '`small_filesize` int(11) NOT NULL', '`medium_filedata` mediumblob NOT NULL', '`medium_filesize` int(11) NOT NULL', '`original_filedata` mediumblob NOT NULL', '`original_filesize` int(11) NOT NULL', '`extension` varchar(255) NOT NULL', '`ordering` int(10) unsigned NOT NULL', 'PRIMARY KEY  (`img_id`)', 'KEY `link_id_ordering` (`link_id`,`ordering`)'))) $updated = true;
	if(createTable('images', array('`img_id` int(11) NOT NULL auto_increment', '`link_id` int(10) unsigned NOT NULL', '`filename` varchar(255) NOT NULL', '`ordering` int(10) unsigned NOT NULL', 'PRIMARY KEY  (`img_id`)', 'KEY `link_id_ordering` (`link_id`,`ordering`)'))) $updated = true;
	if(createTable('linkcheck', array('`id` int(11) NOT NULL auto_increment', '`link_id` int(11) NOT NULL', '`field` varchar(255) NOT NULL', '`link_name` varchar(255) NOT NULL', '`domain` varchar(255) NOT NULL', '`path` text NOT NULL', '`status_code` smallint(5) unsigned NOT NULL', '`check_status` tinyint(4) NOT NULL default \'0\'', 'PRIMARY KEY  (`id`)'))) $updated = true;

	# Create tables with static rows
	if(createTable('configgroup', array('`groupname` varchar(50) NOT NULL', '`ordering` smallint(6) NOT NULL', '`displayed` smallint(6) NOT NULL', 'PRIMARY KEY  (`groupname`)'),true)) {
		addRows('configgroup',array(
			array('main', 100, 1),
			array('category', 250, 1),
			array('listing', 300, 1),
			array('search', 400, 1),
			array('ratingreview', 450, 1),
			array('feature', 500, 1),
			array('notify', 600, 1),
			array('image', 650, 1),
			array('rss', 675, 1),
			array('admin', 700, 1),
			array('linkchecker', 800, 0),
			array('core', 999, 0)
			));
		$updated = true;
	}
	if(createTable('customfields', array('`cf_id` int(11) NOT NULL auto_increment', '`field_type` varchar(36) NOT NULL', '`caption` varchar(255) NOT NULL', '`default_value` varchar(255) NOT NULL', '`size` smallint(9) NOT NULL', '`field_elements` text NOT NULL', '`prefix_text_mod` varchar(255) NOT NULL', '`suffix_text_mod` varchar(255) NOT NULL', '`prefix_text_display` varchar(255) NOT NULL', '`suffix_text_display` varchar(255) NOT NULL', '`cat_id` int(11) NOT NULL default \'0\'', '`ordering` int(11) NOT NULL', '`hidden` tinyint(4) NOT NULL default \'0\'', '`required_field` tinyint(4) NOT NULL default \'0\'', '`published` tinyint(4) NOT NULL default \'1\'', '`hide_caption` tinyint(4) NOT NULL default \'0\'', '`advanced_search` tinyint(4) NOT NULL default \'0\'', '`simple_search` tinyint(4) NOT NULL default \'0\'', '`details_view` tinyint(3) unsigned NOT NULL default \'1\'', '`summary_view` tinyint(3) unsigned NOT NULL default \'0\'', '`search_caption` varchar(255) NOT NULL', '`params` text NOT NULL', '`iscore` tinyint(4) NOT NULL default \'0\'', 'PRIMARY KEY  (`cf_id`)'))) {
		addRows('customfields',array(
			array(1, 'corename', 'Name', '', 50, '', '', '', '', '', 0, 1, 0, 1, 1, 0, 1, 1, 1, 1, '', '', 1),
			array(2, 'coredesc', 'Description', '', 250, '', '', '', '', '', 0, 2, 0, 0, 1, 0, 1, 1, 1, 1, '', '', 1),
			array(3, 'coreuser', 'Owner', '', 0, '', '', '', '', '', 0, 3, 0, 0, 1, 0, 0, 0, 1, 0, '', '', 1),
			array(4, 'coreaddress', 'Address', '', 0, '', '', '', '', '', 0, 4, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 1),
			array(5, 'corecity', 'City', '', 0, '', '', '', '', '', 0, 5, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 1),
			array(6, 'corestate', 'State', '', 0, '', '', '', '', '', 0, 6, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 1),
			array(7, 'corecountry', 'Country', '', 0, '', '', '', '', '', 0, 7, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 1),
			array(8, 'corepostcode', 'Postcode', '', 0, '', '', '', '', '', 0, 8, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 1),
			array(9, 'coretelephone', 'Telephone', '', 0, '', '', '', '', '', 0, 9, 0, 0, 1, 0, 0, 0, 1, 0, '', '', 1),
			array(10, 'corefax', 'Fax', '', 0, '', '', '', '', '', 0, 10, 0, 0, 1, 0, 0, 0, 1, 0, '', '', 1),
			array(11, 'coreemail', 'E-mail', '', 0, '', '', '', '', '', 0, 11, 0, 0, 1, 0, 0, 0, 1, 0, '', '', 1),
			array(12, 'corewebsite', 'Website', '', 0, '', '', '', '', '', 0, 12, 0, 0, 1, 0, 0, 0, 1, 1, '', '', 1),
			array(13, 'coreprice', 'Price', '', 0, '', '', '', '', '', 0, 13, 0, 0, 0, 0, 0, 0, 1, 0, '', '', 1),
			array(14, 'corehits', 'Hits', '', 0, '', '', '', '', '', 0, 14, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1),
			array(15, 'corevotes', 'Votes', '', 10, '', '', '', '', '', 0, 15, 0, 0, 1, 0, 0, 0, 1, 0, '', '', 1),
			array(16, 'corerating', 'Rating', '', 0, '', '', '', '', '', 0, 16, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1),
			array(17, 'corefeatured', 'Featured', '', 0, '', '', '', '', '', 0, 17, 0, 0, 1, 0, 0, 0, 1, 0, '', '', 1),
			array(18, 'corecreated', 'Created', '', 0, '', '', '', '', '', 0, 18, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1),
			array(19, 'coremodified', 'Modified', '', 0, '', '', '', '', '', 0, 19, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1),
			array(20, 'corevisited', 'Visited', '', 0, '', '', '', '', '', 0, 20, 0, 0, 1, 0, 0, 0, 1, 0, '', '', 1),
			array(21, 'corepublishup', 'Publish up', '', 0, '', '', '', '', '', 0, 21, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1),
			array(22, 'corepublishdown', 'Publish down', '', 0, '', '', '', '', '', 0, 22, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1),
			array(23, 'image', 'Image', '', 30, '', '', '', '', '', 0, 23, 0, 0, 0, 0, 0, 0, 1, 0, '', '', 0),
			array(24, 'mfile', 'File', '', 30, '', '', '', '', '', 0, 24, 0, 0, 0, 0, 0, 0, 1, 0, '', '', 0),
			array(25, 'multilineTextbox', 'Multi-line Textbox', '', 0, '', '', '', '', '', 0, 25, 0, 0, 0, 0, 0, 0, 1, 0, '', '', 0)
			));
		$updated = true;
	}
	if(createTable('fieldtypes', array('`ft_id` int(11) NOT NULL auto_increment', '`field_type` varchar(36) NOT NULL', '`ft_caption` varchar(255) NOT NULL', '`ft_class` mediumtext NOT NULL', '`use_elements` tinyint(3) unsigned NOT NULL default \'0\'', '`use_size` tinyint(3) unsigned NOT NULL default \'0\'', '`use_columns` tinyint(3) unsigned NOT NULL default \'0\'', '`iscore` tinyint(4) NOT NULL default \'0\'', 'PRIMARY KEY  (`ft_id`)', 'UNIQUE KEY `field_type` (`field_type`)'))) {
		addRows('fieldtypes',array(
			array('1', 'corerating', 'Rating', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlcmF0aW5nIGV4dGVuZHMgbUZpZWxkVHlwZSB7DQoJdmFyICRuYW1lID0gJ2xpbmtfcmF0aW5nJzsNCgl2YXIgJG51bU9mU2VhcmNoRmllbGRzID0gMDsNCgl2YXIgJG51bU9mSW5wdXRGaWVsZHMgPSAwOw0KfQ==')), '0', '0', '0', '1'),
			array('2', 'coreprice', 'Price', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlcHJpY2UgZXh0ZW5kcyBtRmllbGRUeXBlX251bWJlciB7DQoJdmFyICRuYW1lID0gJ3ByaWNlJzsNCn0NCg==')), '0', '1', '0', '1'),
			array('3', 'coreaddress', 'Address', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlYWRkcmVzcyBleHRlbmRzIG1GaWVsZFR5cGUgew0KCXZhciAkbmFtZSA9ICdhZGRyZXNzJzsNCn0NCg==')), '0', '1', '0', '1'),
			array('4', 'corecity', 'City', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlY2l0eSBleHRlbmRzIG1GaWVsZFR5cGUgew0KCXZhciAkbmFtZSA9ICdjaXR5JzsNCn0NCg==')), '0', '1', '0', '1'),
			array('5', 'corestate', 'State', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3Jlc3RhdGUgZXh0ZW5kcyBtRmllbGRUeXBlIHsNCgl2YXIgJG5hbWUgPSAnc3RhdGUnOw0KfQ0K')), '0', '1', '0', '1'),
			array('6', 'corecountry', 'Country', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlY291bnRyeSBleHRlbmRzIG1GaWVsZFR5cGUgew0KCXZhciAkbmFtZSA9ICdjb3VudHJ5JzsNCn0NCg==')), '0', '1', '0', '1'),
			array('7', 'corepostcode', 'Postcode', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlcG9zdGNvZGUgZXh0ZW5kcyBtRmllbGRUeXBlIHsNCgl2YXIgJG5hbWUgPSAncG9zdGNvZGUnOw0KfQ0K')), '0', '1', '0', '1'),
			array('8', 'coretelephone', 'Telephone', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JldGVsZXBob25lIGV4dGVuZHMgbUZpZWxkVHlwZSB7DQoJdmFyICRuYW1lID0gJ3RlbGVwaG9uZSc7DQp9DQo=')), '0', '1', '0', '1'),
			array('9', 'corefax', 'Fax', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlZmF4IGV4dGVuZHMgbUZpZWxkVHlwZSB7DQoJdmFyICRuYW1lID0gJ2ZheCc7DQp9DQo=')), '0', '1', '0', '1'),
			array('10', 'coreemail', 'E-mail', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlZW1haWwgZXh0ZW5kcyBtRmllbGRUeXBlX2VtYWlsIHsNCgl2YXIgJG5hbWUgPSAnZW1haWwnOw0KfQ0K')), '0', '1', '0', '1'),
			array('11', 'corewebsite', 'Website', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3Jld2Vic2l0ZSBleHRlbmRzIG1GaWVsZFR5cGVfd2VibGluayB7DQoJdmFyICRuYW1lID0gJ3dlYnNpdGUnOw0KfQ==')), '0', '0', '0', '1'),
			array('12', 'corehits', 'Hits', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlaGl0cyBleHRlbmRzIG1GaWVsZFR5cGVfbnVtYmVyIHsNCgl2YXIgJG5hbWUgPSAnbGlua19oaXRzJzsNCgl2YXIgJG51bU9mSW5wdXRGaWVsZHMgPSAwOw0KfQ0K')), '0', '0', '0', '1'),
			array('13', 'corevotes', 'Votes', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3Jldm90ZXMgZXh0ZW5kcyBtRmllbGRUeXBlX251bWJlciB7DQoJdmFyICRuYW1lID0gJ2xpbmtfdm90ZXMnOw0KCXZhciAkbnVtT2ZJbnB1dEZpZWxkcyA9IDA7DQp9DQo=')), '0', '0', '0', '1'),
			array('14', 'corefeatured', 'Featured', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlZmVhdHVyZWQgZXh0ZW5kcyBtRmllbGRUeXBlIHsNCgl2YXIgJG5hbWUgPSAnbGlua19mZWF0dXJlZCc7DQoJdmFyICRudW1PZlNlYXJjaEZpZWxkcyA9IDA7DQoJdmFyICRudW1PZklucHV0RmllbGRzID0gMDsNCn0NCg==')), '0', '0', '0', '1'),
			array('15', 'coremodified', 'Modified', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlbW9kaWZpZWQgZXh0ZW5kcyBtRmllbGRUeXBlIHsNCgl2YXIgJG5hbWUgPSAnbGlua19tb2RpZmllZCc7DQoJdmFyICRudW1PZlNlYXJjaEZpZWxkcyA9IDA7DQoJdmFyICRudW1PZklucHV0RmllbGRzID0gMDsNCn0NCg==')), '0', '0', '0', '1'),
			array('16', 'corevisited', 'Visited', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JldmlzaXRlZCBleHRlbmRzIG1GaWVsZFR5cGUgew0KCXZhciAkbmFtZSA9ICdsaW5rX3Zpc2l0ZWQnOw0KCXZhciAkbnVtT2ZTZWFyY2hGaWVsZHMgPSAwOw0KCXZhciAkbnVtT2ZJbnB1dEZpZWxkcyA9IDA7DQp9DQo=')), '0', '0', '0', '1'),
			array('17', 'corepublishup', 'Publish Up', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlcHVibGlzaHVwIGV4dGVuZHMgbUZpZWxkVHlwZSB7DQoJdmFyICRuYW1lID0gJ3B1Ymxpc2hfdXAnOw0KCXZhciAkbnVtT2ZTZWFyY2hGaWVsZHMgPSAwOw0KCXZhciAkbnVtT2ZJbnB1dEZpZWxkcyA9IDA7DQp9DQo=')), '0', '0', '0', '1'),
			array('18', 'corepublishdown', 'Publish Down', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlcHVibGlzaGRvd24gZXh0ZW5kcyBtRmllbGRUeXBlIHsNCgl2YXIgJG5hbWUgPSAncHVibGlzaF9kb3duJzsNCgl2YXIgJG51bU9mU2VhcmNoRmllbGRzID0gMDsNCgl2YXIgJG51bU9mSW5wdXRGaWVsZHMgPSAwOw0KfQ0K')), '0', '0', '0', '1'),
			array('19', 'coreuser', 'Owner', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JldXNlciBleHRlbmRzIG1GaWVsZFR5cGUgew0KCXZhciAkbmFtZSA9ICd1c2VyX2lkJzsNCgl2YXIgJG51bU9mU2VhcmNoRmllbGRzID0gMDsNCgl2YXIgJG51bU9mSW5wdXRGaWVsZHMgPSAwOw0KCQ0KCWZ1bmN0aW9uIGdldE91dHB1dCgpIHsNCgkJZ2xvYmFsICRJdGVtaWQ7DQoJCSRodG1sID0gJzxhIGhyZWY9IicgLiBzZWZSZWx0b0FicygnaW5kZXgucGhwP29wdGlvbj1jb21fbXRyZWUmYW1wO3Rhc2s9dmlld293bmVyJmFtcDt1c2VyX2lkPScgLiAkdGhpcy0+Z2V0VmFsdWUoMSkgLiAnJmFtcDtJdGVtaWQ9JyAuICRJdGVtaWQpIC4gJyI+JzsNCgkJJGh0bWwgLj0gJHRoaXMtPmdldFZhbHVlKDIpOw0KCQkkaHRtbCAuPSAnPC9hPic7DQoJCXJldHVybiAkaHRtbDsNCgl9DQp9DQo=')), '0', '0', '0', '1'),
			array('20', 'corename', 'Name', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlbmFtZSBleHRlbmRzIG1GaWVsZFR5cGUgew0KCXZhciAkbmFtZSA9ICdsaW5rX25hbWUnOw0KCWZ1bmN0aW9uIGdldE91dHB1dCgkdmlldz0xKSB7DQoJCSRwYXJhbXNbJ21heFN1bW1hcnlDaGFycyddID0gaW50dmFsKCR0aGlzLT5nZXRQYXJhbSgnbWF4U3VtbWFyeUNoYXJzJyw1NSkpOw0KCQkkcGFyYW1zWydtYXhEZXRhaWxzQ2hhcnMnXSA9IGludHZhbCgkdGhpcy0+Z2V0UGFyYW0oJ21heERldGFpbHNDaGFycycsMCkpOw0KCQkkdmFsdWUgPSAkdGhpcy0+Z2V0VmFsdWUoKTsNCgkJJG91dHB1dCA9ICcnOw0KCQlpZigkdmlldyA9PSAxIEFORCAkcGFyYW1zWydtYXhEZXRhaWxzQ2hhcnMnXSA+IDAgQU5EICR0aGlzLT5zdHJsZW5fdXRmOCgkdmFsdWUpID4gJHBhcmFtc1snbWF4RGV0YWlsc0NoYXJzJ10pIHsNCgkJCSRvdXRwdXQgLj0gJHRoaXMtPmh0bWxfY3V0c3RyKCR2YWx1ZSwkcGFyYW1zWydtYXhEZXRhaWxzQ2hhcnMnXSk7DQoJCQkkb3V0cHV0IC49ICcuLi4nOw0KCQl9IGVsc2VpZigkdmlldyA9PSAyIEFORCAkcGFyYW1zWydtYXhTdW1tYXJ5Q2hhcnMnXSA+IDAgQU5EICR0aGlzLT5zdHJsZW5fdXRmOCgkdmFsdWUpID4gJHBhcmFtc1snbWF4U3VtbWFyeUNoYXJzJ10pIHsNCgkJCSRvdXRwdXQgLj0gJHRoaXMtPmh0bWxfY3V0c3RyKCR2YWx1ZSwkcGFyYW1zWydtYXhTdW1tYXJ5Q2hhcnMnXSk7DQoJCQkkb3V0cHV0IC49ICcuLi4nOw0KCQl9IGVsc2Ugew0KCQkJJG91dHB1dCA9ICR2YWx1ZTsNCgkJfQ0KCQlyZXR1cm4gJG91dHB1dDsNCgl9DQoJDQoJZnVuY3Rpb24gc3RybGVuX3V0ZjgoJHN0cikJeyByZXR1cm4gc3RybGVuKHV0ZjhfZGVjb2RlKCR0aGlzLT51dGY4X2h0bWxfZW50aXR5X2RlY29kZSgkc3RyKSkpOwl9DQoJZnVuY3Rpb24gdXRmOF9yZXBsYWNlRW50aXR5KCRyZXN1bHQpew0KCQkkdmFsdWUgPSBpbnR2YWwoJHJlc3VsdFsxXSk7DQoJCSRzdHJpbmcgPSAnJzsNCgkJJGxlbiA9IHJvdW5kKHBvdygkdmFsdWUsMS84KSk7DQoJCWZvcigkaT0kbGVuOyRpPjA7JGktLSl7DQoJCSAgICAkcGFydCA9ICgkdmFsdWUgQU5EICgyNTU+PjIpKSB8IHBvdygyLDcpOw0KCQkgICAgaWYgKCAkaSA9PSAxICkgJHBhcnQgfD0gMjU1PDwoOC0kbGVuKTsNCgkJICAgICRzdHJpbmcgPSBjaHIoJHBhcnQpIC4gJHN0cmluZzsNCgkJICAgICR2YWx1ZSA+Pj0gNjsNCgkJfQ0KCQlyZXR1cm4gJHN0cmluZzsNCgl9DQoJZnVuY3Rpb24gdXRmOF9odG1sX2VudGl0eV9kZWNvZGUoJHN0cmluZyl7IHJldHVybiBwcmVnX3JlcGxhY2VfY2FsbGJhY2soJy8mIyhbMC05XSspOy91JyxhcnJheSgkdGhpcywndXRmOF9yZXBsYWNlRW50aXR5JyksJHN0cmluZyk7CX0NCglmdW5jdGlvbiBodG1sX2N1dHN0cigkc3RyLCAkbGVuKSB7DQoJCWlmICghcHJlZ19tYXRjaCgnL1wmI1swLTldKjsuKi9pJywgJHN0cikpIHsNCgkJCXJldHVybiBzdHJsZW4oJHN0cik7DQoJCX0NCg0KCQkkY2hhcnMgPSAwOw0KCQkkc3RhcnQgPSAwOw0KCQlmb3IoJGk9MDsgJGkgPCBzdHJsZW4oJHN0cik7ICRpKyspIHsNCgkJCWlmICgkY2hhcnMgPj0gJGxlbikgew0KCQkJCWJyZWFrOw0KCQkJfQ0KCQkgICAgJHN0cl90bXAgPSBzdWJzdHIoJHN0ciwgJHN0YXJ0LCAkaS0kc3RhcnQpOw0KCQkgICAgaWYgKHByZWdfbWF0Y2goJy9cJiNbMC05XSo7LiovaScsICRzdHJfdG1wKSkgew0KCQkJCSRjaGFycysrOw0KCQkgICAgICAgICRzdGFydCA9ICRpOw0KCQkgICAgfQ0KCQl9DQoJCSRyVmFsID0gc3Vic3RyKCRzdHIsIDAsICRzdGFydCk7DQoJCWlmIChzdHJsZW4oJHN0cikgPiAkc3RhcnQpDQoJCXJldHVybiAkclZhbDsNCgl9DQp9DQo=')), '0', '0', '0', '1'),
			array('21', 'coredesc', 'Description', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlZGVzYyBleHRlbmRzIG1GaWVsZFR5cGUgew0KCXZhciAkbmFtZSA9ICdsaW5rX2Rlc2MnOw0KCWZ1bmN0aW9uIHN0cmlwVGFncygkdmFsdWUpIHsNCgkJJHBhcmFtc1snYWxsb3dlZFRhZ3MnXSA9ICR0aGlzLT5nZXRQYXJhbSgnYWxsb3dlZFRhZ3MnLCd1LGIsaSxhLHVsLGxpLHByZSxicixibG9ja3F1b3RlJyk7DQoJCWlmKCFlbXB0eSgkcGFyYW1zWydhbGxvd2VkVGFncyddKSkgew0KCQkJJHRtcCA9IGV4cGxvZGUoJywnLCRwYXJhbXNbJ2FsbG93ZWRUYWdzJ10pOw0KCQkJYXJyYXlfd2FsaygkdG1wLCd0cmltJyk7DQoJCQkkYWxsb3dlZFRhZ3MgPSAnPCcgLiBpbXBsb2RlKCc+PCcsJHRtcCkgLiAnPic7DQoJCX0gZWxzZSB7DQoJCQkkYWxsb3dlZFRhZ3MgPSAnJzsNCgkJfQ0KCQlyZXR1cm4gc3RyaXBfdGFncyggJHZhbHVlLCAkYWxsb3dlZFRhZ3MgKTsNCgl9DQoJZnVuY3Rpb24gcGFyc2VWYWx1ZSgkdmFsdWUpIHsNCgkJJHBhcmFtc1snc3RyaXBBbGxUYWdzQmVmb3JlU2F2ZSddID0gJHRoaXMtPmdldFBhcmFtKCdzdHJpcEFsbFRhZ3NCZWZvcmVTYXZlJywxKTsNCgkJaWYoJHBhcmFtc1snc3RyaXBBbGxUYWdzQmVmb3JlU2F2ZSddKSB7DQoJCQkkdmFsdWUgPSAkdGhpcy0+c3RyaXBUYWdzKCR2YWx1ZSk7DQoJCX0NCgkJcmV0dXJuICR2YWx1ZTsJCQ0KCX0NCglmdW5jdGlvbiBnZXRJbnB1dEhUTUwoKSB7DQoJCWdsb2JhbCAkbXRjb25mOw0KCQkNCgkJJGluQmFja0VuZCA9IChzdWJzdHIoZGlybmFtZSgkX1NFUlZFUlsnUEhQX1NFTEYnXSksLTEzKSA9PSAnYWRtaW5pc3RyYXRvcicpID8gdHJ1ZSA6IGZhbHNlOw0KCQlpZiggKCRpbkJhY2tFbmQgQU5EICRtdGNvbmYtPmdldCgndXNlX3d5c2l3eWdfZWRpdG9yX2luX2FkbWluJykpIHx8ICghJGluQmFja0VuZCBBTkQgJG10Y29uZi0+Z2V0KCd1c2Vfd3lzaXd5Z19lZGl0b3InKSkgKSB7DQoJCQlvYl9zdGFydCgpOw0KCQkJZWRpdG9yQXJlYSggJ2VkaXRvcjEnLCAgJHRoaXMtPmdldFZhbHVlKCkgLCAkdGhpcy0+Z2V0SW5wdXRGaWVsZE5hbWUoMSksICcxMDAlJywgJHRoaXMtPmdldFNpemUoKSwgJzc1JywgJzI1JyApOw0KCQkJJGh0bWwgPSBvYl9nZXRfY29udGVudHMoKTsNCgkJCW9iX2VuZF9jbGVhbigpOw0KCQl9IGVsc2Ugew0KCQkJJGh0bWwgPSAnPHRleHRhcmVhIGNsYXNzPSJpbnB1dGJveCIgbmFtZT0iJyAuICR0aGlzLT5nZXRJbnB1dEZpZWxkTmFtZSgxKSAuICciIHN0eWxlPSJ3aWR0aDo5NSU7aGVpZ2h0OicgLiAkdGhpcy0+Z2V0U2l6ZSgpIC4gJ3B4Ij4nIC4gaHRtbHNwZWNpYWxjaGFycygkdGhpcy0+Z2V0VmFsdWUoKSkgLiAnPC90ZXh0YXJlYT4nOw0KCQl9DQoJCXJldHVybiAkaHRtbDsNCgl9DQoJZnVuY3Rpb24gZ2V0U2VhcmNoSFRNTCgpIHsNCgkJcmV0dXJuICc8aW5wdXQgY2xhc3M9ImlucHV0Ym94IiB0eXBlPSJ0ZXh0IiBuYW1lPSInIC4gJHRoaXMtPmdldE5hbWUoKSAuICciIHNpemU9IjMwIiAvPic7DQoJfQ0KCWZ1bmN0aW9uIGdldE91dHB1dCgkdmlldz0xKSB7DQoJCSRwYXJhbXNbJ3BhcnNlVXJsJ10gPSAkdGhpcy0+Z2V0UGFyYW0oJ3BhcnNlVXJsJywxKTsNCgkJJHBhcmFtc1snc3VtbWFyeUNoYXJzJ10gPSAkdGhpcy0+Z2V0UGFyYW0oJ3N1bW1hcnlDaGFycycsMjU1KTsNCgkJJHBhcmFtc1snc3RyaXBTdW1tYXJ5VGFncyddID0gJHRoaXMtPmdldFBhcmFtKCdzdHJpcFN1bW1hcnlUYWdzJywxKTsNCgkJJHBhcmFtc1snc3RyaXBEZXRhaWxzVGFncyddID0gJHRoaXMtPmdldFBhcmFtKCdzdHJpcERldGFpbHNUYWdzJywxKTsNCgkJJHBhcmFtc1sncGFyc2VNYW1ib3RzJ10gPSAkdGhpcy0+Z2V0UGFyYW0oJ3BhcnNlTWFtYm90cycsMCk7DQoJCQ0KCQkkaHRtbCA9ICR0aGlzLT5nZXRWYWx1ZSgpOw0KCQkNCgkJLy8gRGV0YWlscyB2aWV3DQoJCWlmKCR2aWV3ID09IDEpIHsNCgkJCWdsb2JhbCAkbXRjb25mOw0KCQkJaWYoJHBhcmFtc1snc3RyaXBEZXRhaWxzVGFncyddKSB7DQoJCQkJJGh0bWwgPSAkdGhpcy0+c3RyaXBUYWdzKCRodG1sKTsNCgkJCX0NCgkJCWlmKCRwYXJhbXNbJ3BhcnNlVXJsJ10gQU5EICR2aWV3ID09IDApIHsNCgkJCQkkcmVnZXggPSAnL2h0dHA6XC9cLyguKj8pKFxzfCQpL2knOw0KCQkJCSRodG1sID0gcHJlZ19yZXBsYWNlX2NhbGxiYWNrKCAkcmVnZXgsIGFycmF5KCR0aGlzLCdsaW5rY3JlYXRvcicpLCAkaHRtbCApOw0KCQkJfQ0KCQkJaWYgKCEkbXRjb25mLT5nZXQoJ3VzZV93eXNpd3lnX2VkaXRvcicpKSB7DQoJCQkJJGh0bWwgPSBubDJicih0cmltKCRodG1sKSk7DQoJCQl9DQoJCQlpZigkcGFyYW1zWydwYXJzZU1hbWJvdHMnXSkgew0KCQkJCSR0aGlzLT5wYXJzZU1hbWJvdHMoJGh0bWwpOw0KCQkJfQ0KCQkvLyBTdW1tYXJ5IHZpZXcNCgkJfSBlbHNlIHsNCgkJCSRodG1sID0gcHJlZ19yZXBsYWNlKCdAe1tcL1whXSo/W148Pl0qP31Ac2knLCAnJywgJGh0bWwpOw0KCQkJaWYoJHBhcmFtc1snc3RyaXBTdW1tYXJ5VGFncyddKSB7DQoJCQkJJGh0bWwgPSBzdHJpcF90YWdzKCAkaHRtbCApOw0KCQkJfQ0KCQkJJHRyaW1tZWRfZGVzYyA9ICR0aGlzLT5odG1sX2N1dHN0cigkaHRtbCwkcGFyYW1zWydzdW1tYXJ5Q2hhcnMnXSk7DQoJCQlpZiAgKCR0aGlzLT5zdHJsZW5fdXRmOCgkaHRtbCkgPiAkcGFyYW1zWydzdW1tYXJ5Q2hhcnMnXSkgew0KCQkJCSRodG1sID0gJHRyaW1tZWRfZGVzYyAuICcgPGI+Li4uPC9iPic7DQoJCQl9DQoJCX0NCgkJcmV0dXJuICRodG1sOw0KCX0NCglmdW5jdGlvbiBwYXJzZU1hbWJvdHMoICYkaHRtbCApIHsNCgkJZ2xvYmFsICRfTUFNQk9UUywgJG10Y29uZjsNCg0KCQkkX01BTUJPVFMtPmxvYWRCb3RHcm91cCggJ2NvbnRlbnQnICk7DQoNCgkJLy8gTG9hZCBQYXJhbWV0ZXJzDQoJCSRwYXJhbXMgPSYgbmV3IG1vc1BhcmFtZXRlcnMoICcnICk7DQoJCSRsaW5rID0gbmV3IHN0ZGNsYXNzOw0KCQkkbGluay0+dGV4dCA9ICRodG1sOw0KCQkNCgkJJGxpbmstPmlkID0gMTsNCgkJJGxpbmstPnRpdGxlID0gJyc7DQoJCSRwYWdlID0gMDsNCgkJJHJlc3VsdHMgPSAkX01BTUJPVFMtPnRyaWdnZXIoICdvblByZXBhcmVDb250ZW50JywgYXJyYXkoICYkbGluaywgJiRwYXJhbXMsICRwYWdlICksIHRydWUgKTsNCgkJJGh0bWwgPSAkbGluay0+dGV4dDsNCg0KCQlyZXR1cm4gdHJ1ZTsNCgl9DQoJZnVuY3Rpb24gbGlua2NyZWF0b3IoICRtYXRjaGVzICkNCgl7CQ0KCQkkdXJsID0gJ2h0dHA6Ly8nOw0KCQkkYXBwZW5kID0gJyc7DQoNCgkJaWYgKCBpbl9hcnJheShzdWJzdHIoJG1hdGNoZXNbMV0sLTEpLCBhcnJheSgnLicsJyknKSkgKSB7DQoJCQkkdXJsIC49IHN1YnN0cigkbWF0Y2hlc1sxXSwgMCwgLTEpOw0KCQkJJGFwcGVuZCA9IHN1YnN0cigkbWF0Y2hlc1sxXSwtMSk7DQoNCgkJIyBQcmV2ZW50IGN1dHRpbmcgb2ZmIGJyZWFrcyA8YnIgLz4NCgkJfSBlbHNlaWYoIHN1YnN0cigkbWF0Y2hlc1sxXSwtMykgPT0gJzxicicgKSB7DQoJCQkkdXJsIC49IHN1YnN0cigkbWF0Y2hlc1sxXSwgMCwgLTMpOw0KCQkJJGFwcGVuZCA9IHN1YnN0cigkbWF0Y2hlc1sxXSwtMyk7DQoNCgkJfSBlbHNlaWYoIHN1YnN0cigkbWF0Y2hlc1sxXSwtMSkgPT0gJz4nICkgew0KCQkJJHJlZ2V4ID0gJy88KC4qPyk+L2knOw0KCQkJcHJlZ19tYXRjaCggJHJlZ2V4LCAkbWF0Y2hlc1sxXSwgJHRhZ3MgKTsNCgkJCWlmKCAhZW1wdHkoJHRhZ3NbMV0pICkgew0KCQkJCSRhcHBlbmQgPSAnPCcuJHRhZ3NbMV0uJz4nOw0KCQkJCSR1cmwgLj0gJG1hdGNoZXNbMV07DQoJCQkJJHVybCA9IHN0cl9yZXBsYWNlKCAkYXBwZW5kLCAnJywgJHVybCApOw0KCQkJfQ0KCQl9IGVsc2Ugew0KCQkJJHVybCAuPSAkbWF0Y2hlc1sxXTsNCgkJfQ0KDQoJCXJldHVybiAnPGEgaHJlZj0iJy4kdXJsLiciIHRhcmdldD0iX2JsYW5rIj4nLiR1cmwuJzwvYT4nLiRhcHBlbmQuJyAnOw0KCX0NCglmdW5jdGlvbiBzdHJsZW5fdXRmOCgkc3RyKQl7IHJldHVybiBzdHJsZW4odXRmOF9kZWNvZGUoJHRoaXMtPnV0ZjhfaHRtbF9lbnRpdHlfZGVjb2RlKCRzdHIpKSk7CX0NCglmdW5jdGlvbiB1dGY4X3JlcGxhY2VFbnRpdHkoJHJlc3VsdCl7DQoJCSR2YWx1ZSA9IGludHZhbCgkcmVzdWx0WzFdKTsNCgkJJHN0cmluZyA9ICcnOw0KCQkkbGVuID0gcm91bmQocG93KCR2YWx1ZSwxLzgpKTsNCgkJZm9yKCRpPSRsZW47JGk+MDskaS0tKXsNCgkJICAgICRwYXJ0ID0gKCR2YWx1ZSBBTkQgKDI1NT4+MikpIHwgcG93KDIsNyk7DQoJCSAgICBpZiAoICRpID09IDEgKSAkcGFydCB8PSAyNTU8PCg4LSRsZW4pOw0KCQkgICAgJHN0cmluZyA9IGNocigkcGFydCkgLiAkc3RyaW5nOw0KCQkgICAgJHZhbHVlID4+PSA2Ow0KCQl9DQoJCXJldHVybiAkc3RyaW5nOw0KCX0NCglmdW5jdGlvbiB1dGY4X2h0bWxfZW50aXR5X2RlY29kZSgkc3RyaW5nKXsgcmV0dXJuIHByZWdfcmVwbGFjZV9jYWxsYmFjaygnLyYjKFswLTldKyk7L3UnLGFycmF5KCR0aGlzLCd1dGY4X3JlcGxhY2VFbnRpdHknKSwkc3RyaW5nKTsgfQ0KCWZ1bmN0aW9uIGh0bWxfY3V0c3RyKCRzdHIsICRsZW4pIHsNCgkJaWYgKCFwcmVnX21hdGNoKCcvXCYjWzAtOV0qOy4qL2knLCAkc3RyKSkgew0KCQkJcmV0dXJuIHN1YnN0cigkc3RyLDAsJGxlbik7DQoJCX0NCg0KCQkkY2hhcnMgPSAwOw0KCQkkc3RhcnQgPSAwOw0KCQlmb3IoJGk9MDsgJGkgPCBzdHJsZW4oJHN0cik7ICRpKyspIHsNCgkJCWlmICgkY2hhcnMgPj0gJGxlbikgew0KCQkJCWJyZWFrOw0KCQkJfQ0KCQkgICAgJHN0cl90bXAgPSBzdWJzdHIoJHN0ciwgJHN0YXJ0LCAkaS0kc3RhcnQpOw0KCQkgICAgaWYgKHByZWdfbWF0Y2goJy9cJiNbMC05XSo7LiovaScsICRzdHJfdG1wKSkgew0KCQkJCSRjaGFycysrOw0KCQkgICAgICAgICRzdGFydCA9ICRpOw0KCQkgICAgfQ0KCQl9DQoJCSRyVmFsID0gc3Vic3RyKCRzdHIsIDAsICRzdGFydCk7DQoJCWlmIChzdHJsZW4oJHN0cikgPiAkc3RhcnQpDQoJCXJldHVybiAkclZhbDsNCgl9DQp9')), '0', '0', '0', '1'),
			array('22', 'corecreated', 'Created', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9jb3JlY3JlYXRlZCBleHRlbmRzIG1GaWVsZFR5cGUgew0KCXZhciAkbmFtZSA9ICdsaW5rX2NyZWF0ZWQnOw0KCXZhciAkbnVtT2ZTZWFyY2hGaWVsZHMgPSAwOw0KCXZhciAkbnVtT2ZJbnB1dEZpZWxkcyA9IDA7DQp9')), '0', '0', '0', '1'),
			array('23', 'weblinknewwin', 'Web link', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV93ZWJsaW5rTmV3V2luIGV4dGVuZHMgbUZpZWxkVHlwZV93ZWJsaW5rIHsNCgl2YXIgJHBhcmFtc1hNTCA9IDExOw0KCWZ1bmN0aW9uIGdldE91dHB1dCgpIHsNCgkJJG1heFVybExlbmd0aCA9ICR0aGlzLT5nZXRQYXJhbSgnbWF4VXJsTGVuZ3RoJyk7DQoJCSR0ZXh0ID0gJHRoaXMtPmdldFBhcmFtKCd0ZXh0Jyk7DQoJCSRvcGVuTmV3V2luZG93ID0gJHRoaXMtPmdldFBhcmFtKCdvcGVuTmV3V2luZG93JywxKTsNCgkJJHNob3dJY29uID0gJHRoaXMtPmdldFBhcmFtKCdzaG93SWNvbicsMSk7DQoJCQ0KCQkkaHRtbCA9ICcnOw0KCQkkaHRtbCAuPSAnPGEgaHJlZj0iJyAuICR0aGlzLT5nZXRWYWx1ZSgpIC4gJyInOw0KCQlpZiggJG9wZW5OZXdXaW5kb3cgPT0gMSApIHsNCgkJCSRodG1sIC49ICcgdGFyZ2V0PSJfYmxhbmsiJzsNCgkJfQ0KCQkkaHRtbCAuPSAnPic7DQoJCWlmKCFlbXB0eSgkdGV4dCkpIHsNCgkJCSRodG1sIC49ICR0ZXh0Ow0KCQl9IGVsc2Ugew0KCQkJaWYoIGVtcHR5KCRtYXhVcmxMZW5ndGgpIHx8ICRtYXhVcmxMZW5ndGggPT0gMCApIHsNCgkJCQkkaHRtbCAuPSAkdGhpcy0+Z2V0VmFsdWUoKTsNCgkJCX0gZWxzZSB7DQoJCQkJJGh0bWwgLj0gc3Vic3RyKCR0aGlzLT5nZXRWYWx1ZSgpLDAsJG1heFVybExlbmd0aCk7DQoJCQkJaWYoIHN0cmxlbigkdGhpcy0+Z2V0VmFsdWUoKSkgPiAkbWF4VXJsTGVuZ3RoICkgew0KCQkJCQkkaHRtbCAuPSAkdGhpcy0+Z2V0UGFyYW0oJ2NsaXBwZWRTeW1ib2wnKTsNCgkJCQl9DQoJCQl9DQoJCX0NCgkJJGh0bWwgLj0gJzwvYT4nOw0KCQlpZiggJHNob3dJY29uICkgew0KCQkJJGh0bWwgLj0gJyA8aW1nIHNyYz0iJyAuICR0aGlzLT5nZXRGaWVsZFR5cGVBdHRhY2htZW50VVJMKCdhcHBsaWNhdGlvbl9kb3VibGUucG5nJykgLiAnIiAvPic7DQoJCX0NCgkJcmV0dXJuICRodG1sOw0KCX0NCn0=')), '1', '1', '1', '0'),
			array('24', 'audioplayer', 'Audio Player', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9hdWRpb3BsYXllciBleHRlbmRzIG1GaWVsZFR5cGVfZmlsZSB7DQoJZnVuY3Rpb24gZ2V0SlNWYWxpZGF0aW9uKCkgew0KCQlnbG9iYWwgJF9NVF9MQU5HOw0KCQkkanMgPSAnJzsNCgkJJGpzIC49ICd9IGVsc2UgaWYgKCFoYXNFeHQoZm9ybS4nIC4gJHRoaXMtPmdldE5hbWUoKSAuICcudmFsdWUsXCdtcDNcJykpIHsnOyANCgkJJGpzIC49ICdhbGVydCgiJyAuICR0aGlzLT5nZXRDYXB0aW9uKCkgLiAnOiBQbGVhc2Ugc2VsZWN0IGEgbXAzIGZpbGUuIik7JzsNCgkJcmV0dXJuICRqczsNCgl9DQoJZnVuY3Rpb24gZ2V0T3V0cHV0KCkgew0KCQkkaWQgPSAkdGhpcy0+Z2V0SWQoKTsNCgkJJHBhcmFtc1sndGV4dCddID0gJHRoaXMtPmdldFBhcmFtKCd0ZXh0Q29sb3VyJyk7DQoJCSRwYXJhbXNbJ3NsaWRlciddID0gJHRoaXMtPmdldFBhcmFtKCdzbGlkZXJDb2xvdXInKTsNCgkJJHBhcmFtc1snbG9hZGVyJ10gPSAkdGhpcy0+Z2V0UGFyYW0oJ2xvYWRlckNvbG91cicpOw0KCQkkcGFyYW1zWyd0cmFjayddID0gJHRoaXMtPmdldFBhcmFtKCd0cmFja0NvbG91cicpOw0KCQkkcGFyYW1zWydib3JkZXInXSA9ICR0aGlzLT5nZXRQYXJhbSgnYm9yZGVyQ29sb3VyJyk7DQoJCSRwYXJhbXNbJ2JnJ10gPSAkdGhpcy0+Z2V0UGFyYW0oJ2JhY2tncm91bmRDb2xvdXInKTsNCgkJJHBhcmFtc1snbGVmdGJnJ10gPSAkdGhpcy0+Z2V0UGFyYW0oJ2xlZnRCYWNrZ3JvdW5Db2xvdXInKTsNCgkJJHBhcmFtc1sncmlnaHRiZyddID0gJHRoaXMtPmdldFBhcmFtKCdyaWdodEJhY2tncm91bkNvbG91cicpOw0KCQkkcGFyYW1zWydyaWdodGJnaG92ZXInXSA9ICR0aGlzLT5nZXRQYXJhbSgncmlnaHRCYWNrZ3JvdW5kSG92ZXJDb2xvdXInKTsNCgkJJHBhcmFtc1snbGVmdGljb24nXSA9ICR0aGlzLT5nZXRQYXJhbSgnbGVmdEljb25Db2xvdXInKTsNCgkJJHBhcmFtc1sncmlnaHRpY29uJ10gPSAkdGhpcy0+Z2V0UGFyYW0oJ3JpZ2h0SWNvbkNvbG91cicpOw0KCQkkcGFyYW1zWydyaWdodGljb25ob3ZlciddID0gJHRoaXMtPmdldFBhcmFtKCdyaWdodEljb25Ib3ZlckNvbG91cicpOw0KCQkNCgkJJGh0bWwgPSAnJzsNCgkJJGh0bWwgLj0gJzxzY3JpcHQgbGFuZ3VhZ2U9IkphdmFTY3JpcHQiIHNyYz0iJyAuICR0aGlzLT5nZXRGaWVsZFR5cGVBdHRhY2htZW50VVJMKCdhdWRpby1wbGF5ZXIuanMnKS4gJyI+PC9zY3JpcHQ+JzsNCgkJJGh0bWwgLj0gIlxuIiAuICc8b2JqZWN0IHR5cGU9ImFwcGxpY2F0aW9uL3gtc2hvY2t3YXZlLWZsYXNoIiBkYXRhPSInIC4gJHRoaXMtPmdldEZpZWxkVHlwZUF0dGFjaG1lbnRVUkwoJ3BsYXllci5zd2YnKS4gJyIgaWQ9ImF1ZGlvcGxheWVyJyAuICRpZCAuICciIGhlaWdodD0iMjQiIHdpZHRoPSIyOTAiPic7DQoJCSRodG1sIC49ICJcbiIgLiAnPHBhcmFtIG5hbWU9Im1vdmllIiB2YWx1ZT0iJyAuICR0aGlzLT5nZXREYXRhQXR0YWNobWVudFVSTCgpLiAnIj4nOw0KCQkkaHRtbCAuPSAiXG4iIC4gJzxwYXJhbSBuYW1lPSJGbGFzaFZhcnMiIHZhbHVlPSInOw0KCQkkaHRtbCAuPSAncGxheWVySUQ9JyAuICRpZDsNCgkJJGh0bWwgLj0gJyZhbXA7c291bmRGaWxlPScgLiB1cmxlbmNvZGUoJHRoaXMtPmdldERhdGFBdHRhY2htZW50VVJMKCkpOw0KCQlmb3JlYWNoKCAkcGFyYW1zIEFTICRrZXkgPT4gJHZhbHVlICkgew0KCQkJaWYoIWVtcHR5KCR2YWx1ZSkpIHsNCgkJCQkkaHRtbCAuPSAnJmFtcDsnIC4gJGtleSAuICc9MHgnIC4gJHZhbHVlOw0KCQkJfQ0KCQl9DQoJCSRodG1sIC49ICciPic7DQoJCSRodG1sIC49ICJcbiIgLiAnPHBhcmFtIG5hbWU9InF1YWxpdHkiIHZhbHVlPSJoaWdoIj4nOw0KCQkkaHRtbCAuPSAiXG4iIC4gJzxwYXJhbSBuYW1lPSJtZW51IiB2YWx1ZT0iZmFsc2UiPic7DQoJCSRodG1sIC49ICJcbiIgLiAnPHBhcmFtIG5hbWU9Indtb2RlIiB2YWx1ZT0idHJhbnNwYXJlbnQiPic7DQoJCSRodG1sIC49ICJcbiIgLiAnPC9vYmplY3Q+JzsNCgkJJGh0bWwgLj0gIlxuPGJyIC8+IjsNCgkJJGh0bWwgLj0gIlxuIiAuICc8YSBocmVmPSInIC4gJHRoaXMtPmdldERhdGFBdHRhY2htZW50VVJMKCkgLiAnIiB0YXJnZXQ9Il9ibGFuayI+JzsNCgkJJGh0bWwgLj0gJHRoaXMtPmdldFZhbHVlKCk7DQoJCSRodG1sIC49ICc8L2E+JzsNCgkJcmV0dXJuICRodG1sOw0KCX0NCn0=')), '0', '0', '0', '0'),
			array('25', 'image', 'Image', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9pbWFnZSBleHRlbmRzIG1GaWVsZFR5cGVfZmlsZSB7DQoJZnVuY3Rpb24gcGFyc2VWYWx1ZSgkdmFsdWUpIHsNCgkJZ2xvYmFsICRtdGNvbmY7DQoJCSRwYXJhbXNbJ3NpemUnXSA9IGludHZhbCh0cmltKCR0aGlzLT5nZXRQYXJhbSgnc2l6ZScpKSk7DQoJCWlmKCRwYXJhbXNbJ3NpemUnXSA8PSAwKSB7DQoJCQkkc2l6ZSA9ICRtdGNvbmYtPmdldCgncmVzaXplX2xpc3Rpbmdfc2l6ZScpOw0KCQl9IGVsc2Ugew0KCQkJJHNpemUgPSBpbnR2YWwoJHBhcmFtc1snc2l6ZSddKTsNCgkJfQ0KCQkkbXRJbWFnZSA9IG5ldyBtdEltYWdlKCk7DQoJCSRtdEltYWdlLT5zZXRNZXRob2QoICRtdGNvbmYtPmdldCgncmVzaXplX21ldGhvZCcpICk7DQoJCSRtdEltYWdlLT5zZXRRdWFsaXR5KCAkbXRjb25mLT5nZXQoJ3Jlc2l6ZV9xdWFsaXR5JykgKTsNCgkJJG10SW1hZ2UtPnNldFNpemUoICRzaXplICk7DQoJCSRtdEltYWdlLT5zZXRUbXBGaWxlKCAkdmFsdWVbJ3RtcF9uYW1lJ10gKTsNCgkJJG10SW1hZ2UtPnNldFR5cGUoICR2YWx1ZVsndHlwZSddICk7DQoJCSRtdEltYWdlLT5zZXROYW1lKCAkdmFsdWVbJ25hbWUnXSApOw0KCQkkbXRJbWFnZS0+c2V0U3F1YXJlKGZhbHNlKTsNCgkJJG10SW1hZ2UtPnJlc2l6ZSgpOw0KCQkkdmFsdWVbJ2RhdGEnXSA9ICRtdEltYWdlLT5nZXRJbWFnZURhdGEoKTsNCgkJJHZhbHVlWydzaXplJ10gPSBzdHJsZW4oJHZhbHVlWydkYXRhJ10pOw0KCQkNCgkJcmV0dXJuICR2YWx1ZTsNCgl9DQoJZnVuY3Rpb24gZ2V0SlNWYWxpZGF0aW9uKCkgew0KCQkkanMgPSAnJzsNCgkJJGpzIC49ICd9IGVsc2UgaWYgKCFoYXNFeHQoZm9ybS4nIC4kdGhpcy0+Z2V0SW5wdXRGaWVsZE5hbWUoMSkgLiAnLnZhbHVlLFwnZ2lmfHBuZ3xqcGd8anBlZ1wnKSkgeyc7IA0KCQkkanMgLj0gJ2FsZXJ0KCInIC4gJHRoaXMtPmdldENhcHRpb24oKSAuICc6IFBsZWFzZSBzZWxlY3QgYW4gaW1hZ2Ugd2l0aCBvbmUgb2YgdGhlc2UgZXh0ZW5zaW9ucyAtIGdpZixwbmcsanBnLGpwZWcuIik7JzsNCgkJcmV0dXJuICRqczsNCgl9DQoJZnVuY3Rpb24gZ2V0T3V0cHV0KCkgew0KCQkkaHRtbCA9ICcnOw0KCQkkaHRtbCAuPSAnPGltZyBzcmM9IicgLiAkdGhpcy0+Z2V0RGF0YUF0dGFjaG1lbnRVUkwoKSAuICciIC8+JzsNCgkJcmV0dXJuICRodG1sOw0KCX0NCglmdW5jdGlvbiBnZXRJbnB1dEhUTUwoKSB7DQoJCSRodG1sID0gJyc7DQoJCWlmKCAkdGhpcy0+YXR0YWNobWVudCA+IDAgKSB7DQoJCQkkaHRtbCAuPSAkdGhpcy0+Z2V0S2VlcEZpbGVSYWRpb0hUTUwoJHRoaXMtPmF0dGFjaG1lbnQpOw0KCQkJJGh0bWwgLj0gJzxsYWJlbCBmb3I9IicgLiAkdGhpcy0+Z2V0S2VlcEZpbGVOYW1lKCkgLiAnIj48aW1nIHNyYz0iJyAuICR0aGlzLT5nZXREYXRhQXR0YWNobWVudFVSTCgpIC4gJyIgaHNwYWNlPSI1IiB2c3BhY2U9IjAiIC8+PC9sYWJlbD4nOw0KCQkJJGh0bWwgLj0gJzwvYnIgPic7DQoJCX0NCgkJJGh0bWwgLj0gJzxpbnB1dCBjbGFzcz0iaW5wdXRib3giIHR5cGU9ImZpbGUiIG5hbWU9IicgLiAkdGhpcy0+Z2V0SW5wdXRGaWVsZE5hbWUoMSkgLiAnIiAvPic7DQoJCXJldHVybiAkaHRtbDsNCgl9DQoJDQp9')), '0', '0', '0', '0'),
			array('26', 'multilineTextbox', 'Multi-line Textbox', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9tdWx0aWxpbmVUZXh0Ym94IGV4dGVuZHMgbUZpZWxkVHlwZSB7DQoJZnVuY3Rpb24gZ2V0SW5wdXRIVE1MKCkgew0KCQkkcGFyYW1zWydjb2xzJ10gPSAkdGhpcy0+Z2V0UGFyYW0oJ2NvbHMnLDYwKTsNCgkJJHBhcmFtc1sncm93cyddID0gJHRoaXMtPmdldFBhcmFtKCdyb3dzJyw2KTsNCgkJJHBhcmFtc1snc3R5bGUnXSA9ICR0aGlzLT5nZXRQYXJhbSgnc3R5bGUnLCcnKTsNCgkJDQoJCSRodG1sID0gJyc7DQoJCSRodG1sIC49ICc8dGV4dGFyZWEgbmFtZT0iJyAuICR0aGlzLT5nZXRJbnB1dEZpZWxkTmFtZSgxKSAuICciIGlkPSInIC4gJHRoaXMtPmdldElucHV0RmllbGROYW1lKDEpIC4gJyIgY2xhc3M9ImlucHV0Ym94Iic7DQoJCSRodG1sIC49ICcgY29scz0iJyAuICRwYXJhbXNbJ2NvbHMnXSAuICciIHJvd3M9IicgLiAkcGFyYW1zWydyb3dzJ10gLiAnIic7DQoJCWlmKCFlbXB0eSgkcGFyYW1zWydzdHlsZSddKSkgew0KCQkJJGh0bWwgLj0gICcgc3R5bGU9IicgLiAkcGFyYW1zWydzdHlsZSddIC4gJyInOw0KCQl9DQoJCSRodG1sIC49ICAnPicgLiAkdGhpcy0+Z2V0VmFsdWUoKSAuICc8L3RleHRhcmVhPic7DQoJCXJldHVybiAkaHRtbDsNCgl9DQoJZnVuY3Rpb24gZ2V0U2VhcmNoSFRNTCgpIHsNCgkJcmV0dXJuICc8aW5wdXQgY2xhc3M9ImlucHV0Ym94IiB0eXBlPSJ0ZXh0IiBuYW1lPSInIC4gJHRoaXMtPmdldE5hbWUoKSAuICciIHNpemU9IjMwIiAvPic7DQoJfQ0KfQ==')), '0', '0', '0', '0'),
			array('29', 'onlinevideo', 'Online Video', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9vbmxpbmV2aWRlbyBleHRlbmRzIG1GaWVsZFR5cGUgew0KDQoJZnVuY3Rpb24gZ2V0T3V0cHV0KCkgew0KCQkkaHRtbCA9Jyc7DQoJCSRpZCA9ICR0aGlzLT5nZXRWYWx1ZSgpOw0KCQkkdmlkZW9Qcm92aWRlciA9ICR0aGlzLT5nZXRQYXJhbSgndmlkZW9Qcm92aWRlcicpOw0KCQlzd2l0Y2goJHZpZGVvUHJvdmlkZXIpIHsNCgkJCWNhc2UgJ3lvdXR1YmUnOg0KCQkJCSRodG1sIC49ICc8b2JqZWN0IHdpZHRoPSI0MjUiIGhlaWdodD0iMzUwIj4nOw0KCQkJCSRodG1sIC49ICc8cGFyYW0gbmFtZT0ibW92aWUiIHZhbHVlPSJodHRwOi8vd3d3LnlvdXR1YmUuY29tL3YvJyAuICRpZCAuICciPjwvcGFyYW0+JzsNCgkJCQkkaHRtbCAuPSAnPHBhcmFtIG5hbWU9Indtb2RlIiB2YWx1ZT0idHJhbnNwYXJlbnQiPjwvcGFyYW0+JzsNCgkJCQkkaHRtbCAuPSAnPGVtYmVkIHNyYz0iaHR0cDovL3d3dy55b3V0dWJlLmNvbS92LycgLiAkaWQgLiAnIiB0eXBlPSJhcHBsaWNhdGlvbi94LXNob2Nrd2F2ZS1mbGFzaCIgd21vZGU9InRyYW5zcGFyZW50IiB3aWR0aD0iNDI1IiBoZWlnaHQ9IjM1MCI+PC9lbWJlZD4nOw0KCQkJCSRodG1sIC49ICc8L29iamVjdD4nOw0KCQkJCWJyZWFrOw0KCQkJY2FzZSAnZ29vZ2xldmlkZW8nOg0KCQkJCSRodG1sIC49ICc8ZW1iZWQgc3R5bGU9IndpZHRoOjQwMHB4OyBoZWlnaHQ6MzI2cHg7IiBpZD0iVmlkZW9QbGF5YmFjayIgdHlwZT0iYXBwbGljYXRpb24veC1zaG9ja3dhdmUtZmxhc2giIHNyYz0iaHR0cDovL3ZpZGVvLmdvb2dsZS5jb20vZ29vZ2xlcGxheWVyLnN3Zj9kb2NJZD0nIC4gJGlkIC4gJyI+JzsNCgkJCQkkaHRtbCAuPSAnPC9lbWJlZD4nOw0KCQkJCWJyZWFrOw0KCQkJY2FzZSAnbWV0YWNhZmUnOg0KCQkJCSRodG1sIC49ICc8ZW1iZWQgc3JjPSJodHRwOi8vd3d3Lm1ldGFjYWZlLmNvbS9mcGxheWVyLycgLiAkaWQgLiAnLnN3ZiIgd2lkdGg9IjQwMCIgaGVpZ2h0PSIzNDUiIHdtb2RlPSJ0cmFuc3BhcmVudCIgcGx1Z2luc3BhZ2U9Imh0dHA6Ly93d3cubWFjcm9tZWRpYS5jb20vZ28vZ2V0Zmxhc2hwbGF5ZXIiIHR5cGU9ImFwcGxpY2F0aW9uL3gtc2hvY2t3YXZlLWZsYXNoIj48L2VtYmVkPic7DQoJCQkJYnJlYWs7DQoJCQljYXNlICdpZmlsbSc6DQoJCQkJJGh0bWwgLj0gJzxlbWJlZCB3aWR0aD0iNDQ4IiBoZWlnaHQ9IjM2NSIgc3JjPSJodHRwOi8vd3d3LmlmaWxtLmNvbS9lZnAiIHF1YWxpdHk9ImhpZ2giIGJnY29sb3I9IjAwMDAwMCIgbmFtZT0iZWZwIiBhbGlnbj0ibWlkZGxlIiB0eXBlPSJhcHBsaWNhdGlvbi94LXNob2Nrd2F2ZS1mbGFzaCIgcGx1Z2luc3BhZ2U9Imh0dHA6Ly93d3cubWFjcm9tZWRpYS5jb20vZ28vZ2V0Zmxhc2hwbGF5ZXIiIGZsYXNodmFycz0iZmx2YmFzZWNsaXA9JyAuICRpZCAuICcmYW1wOyI+PC9lbWJlZD4nOw0KCQkJCWJyZWFrOw0KCQl9DQoJCXJldHVybiAkaHRtbDsNCgl9DQoJDQoJZnVuY3Rpb24gZ2V0U2VhcmNoSFRNTCgpIHsNCgkJJGNoZWNrYm94TGFiZWwgPSAkdGhpcy0+Z2V0UGFyYW0oJ2NoZWNrYm94TGFiZWwnLCdDb250YWlucyB2aWRlbycpOw0KCQlyZXR1cm4gJzxpbnB1dCBjbGFzcz0idGV4dF9hcmVhIiB0eXBlPSJjaGVja2JveCIgbmFtZT0iJyAuICR0aGlzLT5nZXRTZWFyY2hGaWVsZE5hbWUoMSkgLiAnIiBpZD0iJyAuICR0aGlzLT5nZXRTZWFyY2hGaWVsZE5hbWUoMSkgLiAnIiAvPiZuYnNwOzxsYWJlbCBmb3I9IicgLiAkdGhpcy0+Z2V0TmFtZSgpIC4gJyI+JyAuICRjaGVja2JveExhYmVsIC4gJzwvbGFiZWw+JzsNCgl9DQoJDQoJZnVuY3Rpb24gZ2V0V2hlcmVDb25kaXRpb24oKSB7DQoJCWlmKCBmdW5jX251bV9hcmdzKCkgPT0gMCApIHsNCgkJCXJldHVybiBudWxsOw0KCQl9IGVsc2Ugew0KCQkJcmV0dXJuICcoY2Z2Iy52YWx1ZSA8PiBcJ1wnKSc7DQoJCX0NCgl9DQp9')), '0', '1', '0', '0'),
			array('45', 'videoplayer', 'Video Player', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV92aWRlb3BsYXllciBleHRlbmRzIG1GaWVsZFR5cGVfZmlsZSB7DQoNCglmdW5jdGlvbiBnZXRPdXRwdXQoKSB7DQoJCSRodG1sID0nJzsNCgkJJGZpbGVuYW1lID0gJHRoaXMtPmdldFZhbHVlKCk7DQoJCSRmb3JtYXQgPSAkdGhpcy0+Z2V0UGFyYW0oJ2Zvcm1hdCcpOw0KCQkkaWQgPSAkZm9ybWF0LiRmaWxlbmFtZTsNCgkJJHdpZHRoID0gJHRoaXMtPmdldFBhcmFtKCd3aWR0aCcpOw0KCQkkaGVpZ2h0ID0gJHRoaXMtPmdldFBhcmFtKCdoZWlnaHQnKTsNCgkJJGF1dG9wbGF5ID0gJHRoaXMtPmdldFBhcmFtKCdhdXRvcGxheScsZmFsc2UpOw0KCQkNCgkJc3dpdGNoKCRmb3JtYXQpIHsNCgkJCWNhc2UgJ21vdic6DQoJCQkJJGh0bWwgLj0gJzxvYmplY3QgY2xhc3NpZD0iY2xzaWQ6MDJCRjI1RDUtOEMxNy00QjIzLUJDODAtRDM0ODhBQkREQzZCIiB3aWR0aD0iJyAuICR3aWR0aCAuICciIGhlaWdodD0iJyAuICRoZWlnaHQuICciIGNvZGViYXNlPSJodHRwOi8vd3d3LmFwcGxlLmNvbS9xdGFjdGl2ZXgvcXRwbHVnaW4uY2FiI3ZlcnNpb249NiwwLDIsMCIgYWxpZ249Im1pZGRsZSI+JzsNCgkJCQkkaHRtbCAuPSAnPHBhcmFtIG5hbWU9InNyYyIgdmFsdWU9IicgLiAkdGhpcy0+Z2V0RGF0YUF0dGFjaG1lbnRVUkwoKSAuICciIC8+JzsNCgkJCQkkaHRtbCAuPSAnPHBhcmFtIG5hbWU9ImF1dG9wbGF5IiB2YWx1ZT0iJyAuICRhdXRvcGxheSAuICciIC8+JzsNCgkJCQkkaHRtbCAuPSAnPGVtYmVkIHNyYz0iJyAuICR0aGlzLT5nZXREYXRhQXR0YWNobWVudFVSTCgpIC4gJyIgdHlwZT0idmlkZW8vcXVpY2t0aW1lIiB3aWR0aD0iJyAuICR3aWR0aCAuICciIGhlaWdodD0iJyAuICRoZWlnaHQgLiAnIiBwbHVnaW5zcGFnZT0iaHR0cDovL3d3dy5hcHBsZS5jb20vcXVpY2t0aW1lL2Rvd25sb2FkLyIgYWxpZ249Im1pZGRsZSIgYXV0b3BsYXk9IicgLiAkYXV0b3BsYXkgLiAnIiAvPic7DQoJCQkJJGh0bWwgLj0gJzwvb2JqZWN0Pic7DQoJCQkJYnJlYWs7DQoJCQljYXNlICdkaXZ4JzoNCgkJCQkkaHRtbCAuPSAnJzsNCgkJCQkkaHRtbCAuPSAnPG9iamVjdCBjbGFzc2lkPSJjbHNpZDo2N0RBQkZCRi1EMEFCLTQxZmEtOUM0Ni1DQzBGMjE3MjE2MTYiIHdpZHRoPSInIC4gJHdpZHRoIC4gJyIgaGVpZ2h0PSInIC4gJGhlaWdodCAuICciIGNvZGViYXNlPSJodHRwOi8vZ28uZGl2eC5jb20vcGx1Z2luL0RpdlhCcm93c2VyUGx1Z2luLmNhYiI+JzsNCgkJCQkkaHRtbCAuPSAnPHBhcmFtIG5hbWU9InNyYyIgdmFsdWU9IicgLiAkdGhpcy0+Z2V0RGF0YUF0dGFjaG1lbnRVUkwoKSAuICciIC8+JzsNCgkJCQkkaHRtbCAuPSAnPHBhcmFtIG5hbWU9ImF1dG9QbGF5IiB2YWx1ZT0iJyAuICRhdXRvcGxheSAuICciIC8+JzsNCgkJCQkkaHRtbCAuPSAnPGVtYmVkIHNyYz0iJyAuICR0aGlzLT5nZXREYXRhQXR0YWNobWVudFVSTCgpIC4gJyIgdHlwZT0idmlkZW8vZGl2eCIgd2lkdGg9IicgLiAkd2lkdGggLiAnIiBoZWlnaHQ9IicgLiAkaGVpZ2h0IC4gJyIgYXV0b1BsYXk9IicgLiAkYXV0b3BsYXkgLiAnIiBwbHVnaW5zcGFnZT0iaHR0cDovL2dvLmRpdnguY29tL3BsdWdpbi9kb3dubG9hZC8iIC8+JzsNCgkJCQkkaHRtbCAuPSAnPC9vYmplY3Q+JzsNCgkJCQlicmVhazsNCgkJCWNhc2UgJ2ZsYXNoJzoNCgkJCQkkaHRtbCAuPSAnPG9iamVjdCBjbGFzc2lkPSJjbHNpZDpEMjdDREI2RS1BRTZELTExY2YtOTZCOC00NDQ1NTM1NDAwMDAiIGNvZGViYXNlPSJodHRwOi8vZG93bmxvYWQubWFjcm9tZWRpYS5jb20vcHViL3Nob2Nrd2F2ZS9jYWJzL2ZsYXNoL3N3Zmxhc2guY2FiI3ZlcnNpb249NiwwLDQwLDAiIHdpZHRoPSInIC4gJHdpZHRoIC4gJyIgaGVpZ2h0PSInIC4gJGhlaWdodCAgLiAnIiBpZD0iJyAuICRpZCAuICciPic7DQoJCQkJJGh0bWwgLj0gJzxwYXJhbSBuYW1lPSJhbGxvd1NjcmlwdEFjY2VzcyIgdmFsdWU9InNhbWVEb21haW4iIC8+JzsNCgkJCQkkaHRtbCAuPSAnPHBhcmFtIG5hbWU9Im1vdmllIiB2YWx1ZT0iJyAuICR0aGlzLT5nZXREYXRhQXR0YWNobWVudFVSTCgpIC4gJyIgLz4nOw0KCQkJCSRodG1sIC49ICc8cGFyYW0gbmFtZT0icXVhbGl0eSIgdmFsdWU9ImhpZ2giIC8+JzsNCgkJCQkvLyAkaHRtbCAuPSAnPHBhcmFtIG5hbWU9ImJnY29sb3IiIHZhbHVlPSIjRkZGRkZGIiAvPic7DQoJCQkJLy8gJGh0bWwgLj0gJzxwYXJhbSBuYW1lPSJ3bW9kZSIgdmFsdWU9InRyYW5zcGFyZW50IiAvPic7DQoJCQkJLy8gJGh0bWwgLj0gJzxlbWJlZCBzcmM9IicgLiAkdGhpcy0+Z2V0RGF0YUF0dGFjaG1lbnRVUkwoKSAuICciIHR5cGU9ImFwcGxpY2F0aW9uL3gtc2hvY2t3YXZlLWZsYXNoIiBxdWFsaXR5PSJoaWdoIiBiZ2NvbG9yPSIjRkZGRkZGIiB3aWR0aD0iJyAuICR3aWR0aCAuICciIGhlaWdodD0iJyAuICRoZWlnaHQgLiAnIiBuYW1lPSInIC4gJGlkIC4gJyIgYWxpZ249Im1pZGRsZSIgcGx1Z2luc3BhZ2U9Imh0dHA6Ly93d3cubWFjcm9tZWRpYS5jb20vZ28vZ2V0Zmxhc2hwbGF5ZXIiIC8+JzsNCgkJCQkkaHRtbCAuPSAnPGVtYmVkIHNyYz0iJyAuICR0aGlzLT5nZXREYXRhQXR0YWNobWVudFVSTCgpIC4gJyIgdHlwZT0iYXBwbGljYXRpb24veC1zaG9ja3dhdmUtZmxhc2giIHF1YWxpdHk9ImhpZ2giIHdpZHRoPSInIC4gJHdpZHRoIC4gJyIgaGVpZ2h0PSInIC4gJGhlaWdodCAuICciIG5hbWU9IicgLiAkaWQgLiAnIiBhbGlnbj0ibWlkZGxlIiBwbHVnaW5zcGFnZT0iaHR0cDovL3d3dy5tYWNyb21lZGlhLmNvbS9nby9nZXRmbGFzaHBsYXllciIgLz4nOw0KCQkJCSRodG1sIC49ICc8L29iamVjdD4nOw0KCQkJCWJyZWFrOw0KCQkJY2FzZSAnd2luZG93c21lZGlhJzoNCgkJCQkkaHRtbCAuPSAnPG9iamVjdCBjbGFzc2lkPSJDTFNJRDo2QkY1MkE1Mi0zOTRBLTExRDMtQjE1My0wMEMwNEY3OUZBQTYiIGlkPSInIC4gJGlkIC4gJyIgd2lkdGg9IicgLiAkd2lkdGggLiAnIiBoZWlnaHQ9IicgLiAkaGVpZ2h0IC4gJyIgdHlwZT0iYXBwbGljYXRpb24veC1vbGVvYmplY3QiPic7DQoJCQkJJGh0bWwgLj0gJzxwYXJhbSBuYW1lPSJVUkwiIHZhbHVlPSInIC4gJHRoaXMtPmdldERhdGFBdHRhY2htZW50VVJMKCkgLiAnIiAvPic7DQoJCQkJJGh0bWwgLj0gJzxwYXJhbSBuYW1lPSJ3bW9kZSIgdmFsdWU9Im9wYXF1ZSIgLz4nOw0KCQkJCSRodG1sIC49ICc8cGFyYW0gbmFtZT0iU2hvd0NvbnRyb2xzIiB2YWx1ZT0iMSIgLz4nOw0KCQkJCSRodG1sIC49ICc8cGFyYW0gbmFtZT0iYXV0b1N0YXJ0IiB2YWx1ZT0iJyAuICgoJGF1dG9wbGF5KT8nMSc6JzAnKSAuICciIC8+JzsNCgkJCQkkaHRtbCAuPSAnPGVtYmVkIHNyYz0iJyAuICR0aGlzLT5nZXREYXRhQXR0YWNobWVudFVSTCgpIC4gJyIgdHlwZT0iYXBwbGljYXRpb24veC1tcGxheWVyMiIgd2lkdGg9IicgLiAkd2lkdGggLiAnIiBoZWlnaHQ9IicgLiAkaGVpZ2h0IC4gJyIgd21vZGU9Im9wYXF1ZSIgYm9yZGVyPSIwIiBhdXRvU3RhcnQ9IicgLiAoKCRhdXRvcGxheSk/JzEnOicwJykgLiAnIiAvPic7DQoJCQkJJGh0bWwgLj0gJzwvb2JqZWN0Pic7DQoJCQkJYnJlYWs7DQoJCX0NCgkJcmV0dXJuICRodG1sOw0KCX0NCn0=')), '0', '0', '0', '0'),
			array('46', 'year', 'Year', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV95ZWFyIGV4dGVuZHMgbUZpZWxkVHlwZSB7DQoJdmFyICRudW1PZlNlYXJjaEZpZWxkcyA9IDI7DQoJZnVuY3Rpb24gZ2V0U2VhcmNoSFRNTCgpIHsNCgkJZ2xvYmFsICRfTVRfTEFORzsNCgkJJHN0YXJ0WWVhciA9ICR0aGlzLT5nZXRQYXJhbSgnc3RhcnRZZWFyJywoZGF0ZSgnWScpLTcwKSk7DQoJCSRlbmRZZWFyID0gJHRoaXMtPmdldFBhcmFtKCdlbmRZZWFyJyxkYXRlKCdZJykpOw0KCQkNCgkJJGh0bWwgPSAnPHNlbGVjdCBuYW1lPSInIC4gJHRoaXMtPmdldFNlYXJjaEZpZWxkTmFtZSgyKSAuICciIGNsYXNzPSJpbnB1dGJveCIgc2l6ZT0iMSI+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IjEiIHNlbGVjdGVkPSJzZWxlY3RlZCI+JyAuICRfTVRfTEFORy0+RVhBQ1RMWSAuICc8L29wdGlvbj4nOw0KCQkkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iMiI+JyAuICdBZnRlcicgLiAnPC9vcHRpb24+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IjMiPicgLiAnQmVmb3JlJyAuICc8L29wdGlvbj4nOw0KCQkkaHRtbCAuPSAnPC9zZWxlY3Q+JzsNCgkJJGh0bWwgLj0gJyZuYnNwOyc7DQoNCgkJJGh0bWwgLj0gJzxzZWxlY3QgbmFtZT0iJyAuICR0aGlzLT5nZXRJbnB1dEZpZWxkTmFtZSgxKSAuICciIGNsYXNzPSJpbnB1dGJveCI+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IiI+Jm5ic3A7PC9vcHRpb24+JzsNCgkJZm9yKCR5ZWFyPSRlbmRZZWFyOyR5ZWFyPj0kc3RhcnRZZWFyOyR5ZWFyLS0pIHsNCgkJCSRodG1sIC49ICc8b3B0aW9uIHZhbHVlPSInIC4gJHllYXIgLiAnIj4nIC4gJHllYXIgLiAnPC9vcHRpb24+JzsNCgkJfQ0KCQkkaHRtbCAuPSAnPC9zZWxlY3Q+JzsJCQ0KDQoJCXJldHVybiAkaHRtbDsNCgl9DQoNCglmdW5jdGlvbiBnZXRJbnB1dEhUTUwoKSB7DQoJCSRzdGFydFllYXIgPSAkdGhpcy0+Z2V0UGFyYW0oJ3N0YXJ0WWVhcicsKGRhdGUoJ1knKS03MCkpOw0KCQkkZW5kWWVhciA9ICR0aGlzLT5nZXRQYXJhbSgnZW5kWWVhcicsZGF0ZSgnWScpKTsNCgkJJHZhbHVlID0gJHRoaXMtPmdldFZhbHVlKCk7DQoJCQ0KCQkkaHRtbCA9ICcnOw0KCQkkaHRtbCAuPSAnPHNlbGVjdCBuYW1lPSInIC4gJHRoaXMtPmdldElucHV0RmllbGROYW1lKCkgLiAnIiBjbGFzcz0iaW5wdXRib3giPic7DQoJCSRodG1sIC49ICc8b3B0aW9uIHZhbHVlPSIiPiZuYnNwOzwvb3B0aW9uPic7DQoJCWZvcigkeWVhcj0kZW5kWWVhcjskeWVhcj49JHN0YXJ0WWVhcjskeWVhci0tKSB7DQoJCQkkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iJyAuICR5ZWFyIC4gJyInOw0KCQkJaWYoICR5ZWFyID09ICR2YWx1ZSApIHsNCgkJCQkkaHRtbCAuPSAnIHNlbGVjdGVkJzsNCgkJCX0NCgkJCSRodG1sIC49ICc+JyAuICR5ZWFyIC4gJzwvb3B0aW9uPic7DQoJCX0NCgkJJGh0bWwgLj0gJzwvc2VsZWN0Pic7CQkNCgkJcmV0dXJuICRodG1sOw0KCX0NCgkNCn0=')), '0', '0', '0', '0'),
			array('47', 'date', 'Date', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9kYXRlIGV4dGVuZHMgbUZpZWxkVHlwZSB7DQoJdmFyICRudW1PZklucHV0RmllbGRzID0gMzsNCgl2YXIgJG51bU9mU2VhcmNoRmllbGRzID0gMTA7DQoJDQoJZnVuY3Rpb24gcGFyc2VWYWx1ZSggJHZhbHVlICkgeyANCgkJaWYgKCBpc19hcnJheSgkdmFsdWUpICYmIGlzX251bWVyaWMoJHZhbHVlWzBdKSAmJiBpc19udW1lcmljKCR2YWx1ZVsxXSkgJiYgaXNfbnVtZXJpYygkdmFsdWVbMl0pICkgew0KCQkJcmV0dXJuICR2YWx1ZVsyXSAuICctJyAuIHN0cl9wYWQoJHZhbHVlWzFdLDIsJzAnLFNUUl9QQURfTEVGVCkgLiAnLScgLiBzdHJfcGFkKCR2YWx1ZVswXSwyLCcwJyxTVFJfUEFEX0xFRlQpOw0KCQl9IGVsc2Ugew0KCQkJcmV0dXJuICcnOw0KCQl9DQoJfQ0KCQ0KCWZ1bmN0aW9uIGdldE91dHB1dCgpIHsNCgkJJGRhdGVGb3JtYXQgPSAkdGhpcy0+Z2V0UGFyYW0oJ2RhdGVGb3JtYXQnLCdZLW0tZCcpOw0KCQkkdmFsdWUgPSAkdGhpcy0+Z2V0VmFsdWUoKTsNCgkJcmV0dXJuIGRhdGUoJGRhdGVGb3JtYXQsbWt0aW1lKDAsMCwwLGludHZhbChzdWJzdHIoJHZhbHVlLDUsMikpLGludHZhbChzdWJzdHIoJHZhbHVlLC0yKSksaW50dmFsKHN1YnN0cigkdmFsdWUsMCw0KSkpKTsNCgl9DQoJDQoJZnVuY3Rpb24gZ2V0U2VhcmNoSFRNTCgpIHsNCgkJJHN0YXJ0WWVhciA9ICR0aGlzLT5nZXRQYXJhbSgnc3RhcnRZZWFyJywoZGF0ZSgnWScpLTcwKSk7DQoJCSRlbmRZZWFyID0gJHRoaXMtPmdldFBhcmFtKCdlbmRZZWFyJyxkYXRlKCdZJykpOw0KCQkNCgkJJGh0bWwgPSAnJzsNCgkJJGh0bWwgLj0gJzxpbnB1dCBpZD0iJyAuICR0aGlzLT5nZXRTZWFyY2hGaWVsZE5hbWUoMSkgLiAnYSIgbmFtZT0nIC4gJHRoaXMtPmdldFNlYXJjaEZpZWxkTmFtZSgxKSAuICcgdHlwZT0icmFkaW8iIHZhbHVlPSIxIiBjaGVja2VkIC8+JzsNCgkJJGh0bWwgLj0gJzxsYWJlbCBmb3I9IicgLiAkdGhpcy0+Z2V0U2VhcmNoRmllbGROYW1lKDEpIC4gJ2EiPkV4YWN0bHkgb248L2xhYmVsPiZuYnNwOyc7DQoJCQ0KCQkkaHRtbCAuPSAnPHNlbGVjdCBuYW1lPSInIC4gJHRoaXMtPmdldFNlYXJjaEZpZWxkTmFtZSgyKSAuICciIGNsYXNzPSJpbnB1dGJveCI+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IiI+Jm5ic3A7PC9vcHRpb24+JzsNCgkJZm9yKCRkYXk9MTskZGF5PD0zMTskZGF5KyspIHsgJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IicgLiAkZGF5IC4gJyI+JyAuICRkYXkgLiAnPC9vcHRpb24+JzsgfQ0KCQkkaHRtbCAuPSAnPC9zZWxlY3Q+JzsNCg0KCQkkaHRtbCAuPSAnPHNlbGVjdCBuYW1lPSInIC4gJHRoaXMtPmdldFNlYXJjaEZpZWxkTmFtZSgzKSAuICciIGNsYXNzPSJpbnB1dGJveCI+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IiI+Jm5ic3A7PC9vcHRpb24+JzsNCgkJZm9yKCRtb250aD0xOyRtb250aDw9MTI7JG1vbnRoKyspIHsgJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IicgLiAkbW9udGggLiAnIj4nIC4gZGF0ZSgiTSIsIG1rdGltZSgwLCAwLCAwLCAkbW9udGgpKSAuICc8L29wdGlvbj4nOyB9DQoJCSRodG1sIC49ICc8L3NlbGVjdD4nOw0KDQoJCSRodG1sIC49ICc8c2VsZWN0IG5hbWU9IicgLiAkdGhpcy0+Z2V0U2VhcmNoRmllbGROYW1lKDQpIC4gJyIgY2xhc3M9ImlucHV0Ym94Ij4nOw0KCQkkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iIj4mbmJzcDs8L29wdGlvbj4nOw0KCQlmb3IoJHllYXI9JGVuZFllYXI7JHllYXI+PSRzdGFydFllYXI7JHllYXItLSkgeyAkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iJyAuICR5ZWFyIC4gJyI+JyAuICR5ZWFyIC4gJzwvb3B0aW9uPic7IH0NCgkJJGh0bWwgLj0gJzwvc2VsZWN0Pic7DQoJCQ0KCQkkaHRtbCAuPSAnPGJyIC8+JzsNCgkJDQoJCSRodG1sIC49ICc8aW5wdXQgaWQ9IicgLiAkdGhpcy0+Z2V0U2VhcmNoRmllbGROYW1lKDEpIC4gJ2IiIG5hbWU9JyAuICR0aGlzLT5nZXRTZWFyY2hGaWVsZE5hbWUoMSkgLiAnIHR5cGU9InJhZGlvIiB2YWx1ZT0iMiIgLz4nOw0KCQkkaHRtbCAuPSAnPGxhYmVsIGZvcj0iJyAuICR0aGlzLT5nZXRTZWFyY2hGaWVsZE5hbWUoMSkgLiAnYiI+QmV0d2VlbjwvbGFiZWw+Jm5ic3A7JzsNCgkJDQoJCSRodG1sIC49ICc8c2VsZWN0IG5hbWU9IicgLiAkdGhpcy0+Z2V0U2VhcmNoRmllbGROYW1lKDUpIC4gJyIgY2xhc3M9ImlucHV0Ym94Ij4nOw0KCQkkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iIj4mbmJzcDs8L29wdGlvbj4nOw0KCQlmb3IoJGRheT0xOyRkYXk8PTMxOyRkYXkrKykgeyAkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iJyAuICRkYXkgLiAnIj4nIC4gJGRheSAuICc8L29wdGlvbj4nOyB9DQoJCSRodG1sIC49ICc8L3NlbGVjdD4nOw0KDQoJCSRodG1sIC49ICc8c2VsZWN0IG5hbWU9IicgLiAkdGhpcy0+Z2V0U2VhcmNoRmllbGROYW1lKDYpIC4gJyIgY2xhc3M9ImlucHV0Ym94Ij4nOw0KCQkkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iIj4mbmJzcDs8L29wdGlvbj4nOw0KCQlmb3IoJG1vbnRoPTE7JG1vbnRoPD0xMjskbW9udGgrKykgeyAkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iJyAuICRtb250aCAuICciPicgLiBkYXRlKCJNIiwgbWt0aW1lKDAsIDAsIDAsICRtb250aCkpIC4gJzwvb3B0aW9uPic7IH0NCgkJJGh0bWwgLj0gJzwvc2VsZWN0Pic7DQoJCQ0KCQkkaHRtbCAuPSAnPHNlbGVjdCBuYW1lPSInIC4gJHRoaXMtPmdldFNlYXJjaEZpZWxkTmFtZSg3KSAuICciIGNsYXNzPSJpbnB1dGJveCI+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IiI+Jm5ic3A7PC9vcHRpb24+JzsNCgkJZm9yKCR5ZWFyPSRlbmRZZWFyOyR5ZWFyPj0kc3RhcnRZZWFyOyR5ZWFyLS0pIHsgJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IicgLiAkeWVhciAuICciPicgLiAkeWVhciAuICc8L29wdGlvbj4nOyB9DQoJCSRodG1sIC49ICc8L3NlbGVjdD4nOw0KCQkNCgkJJGh0bWwgLj0gJyZuYnNwO2FuZCZuYnNwOyc7DQoJCQ0KCQkkaHRtbCAuPSAnPHNlbGVjdCBuYW1lPSInIC4gJHRoaXMtPmdldFNlYXJjaEZpZWxkTmFtZSg4KSAuICciIGNsYXNzPSJpbnB1dGJveCI+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IiI+Jm5ic3A7PC9vcHRpb24+JzsNCgkJZm9yKCRkYXk9MTskZGF5PD0zMTskZGF5KyspIHsgJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IicgLiAkZGF5IC4gJyI+JyAuICRkYXkgLiAnPC9vcHRpb24+JzsgfQ0KCQkkaHRtbCAuPSAnPC9zZWxlY3Q+JzsNCg0KCQkkaHRtbCAuPSAnPHNlbGVjdCBuYW1lPSInIC4gJHRoaXMtPmdldFNlYXJjaEZpZWxkTmFtZSg5KSAuICciIGNsYXNzPSJpbnB1dGJveCI+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IiI+Jm5ic3A7PC9vcHRpb24+JzsNCgkJZm9yKCRtb250aD0xOyRtb250aDw9MTI7JG1vbnRoKyspIHsgJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IicgLiAkbW9udGggLiAnIj4nIC4gZGF0ZSgiTSIsIG1rdGltZSgwLCAwLCAwLCAkbW9udGgpKSAuICc8L29wdGlvbj4nOyB9DQoJCSRodG1sIC49ICc8L3NlbGVjdD4nOw0KDQoJCSRodG1sIC49ICc8c2VsZWN0IG5hbWU9IicgLiAkdGhpcy0+Z2V0U2VhcmNoRmllbGROYW1lKDEwKSAuICciIGNsYXNzPSJpbnB1dGJveCI+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IiI+Jm5ic3A7PC9vcHRpb24+JzsNCgkJZm9yKCR5ZWFyPSRlbmRZZWFyOyR5ZWFyPj0kc3RhcnRZZWFyOyR5ZWFyLS0pIHsgJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IicgLiAkeWVhciAuICciPicgLiAkeWVhciAuICc8L29wdGlvbj4nOyB9DQoJCSRodG1sIC49ICc8L3NlbGVjdD4nOw0KCQkNCg0KCQkNCgkJcmV0dXJuICRodG1sOw0KCQkNCgl9DQoJDQoJZnVuY3Rpb24gZ2V0SW5wdXRIVE1MKCkgew0KCQkkc3RhcnRZZWFyID0gJHRoaXMtPmdldFBhcmFtKCdzdGFydFllYXInLChkYXRlKCdZJyktNzApKTsNCgkJJGVuZFllYXIgPSAkdGhpcy0+Z2V0UGFyYW0oJ2VuZFllYXInLGRhdGUoJ1knKSk7DQoJCSR2YWx1ZSA9ICR0aGlzLT5nZXRWYWx1ZSgpOw0KCQkNCgkJaWYoZW1wdHkoJHZhbHVlKSkgew0KCQkJJGRheVZhbHVlID0gMDsNCgkJCSRtb250aFZhbHVlID0gMDsNCgkJCSR5ZWFyVmFsdWUgPSAwOw0KCQl9IGVsc2Ugew0KCQkJJGRheVZhbHVlID0gaW50dmFsKHN1YnN0cigkdmFsdWUsLTIpKTsNCgkJCSRtb250aFZhbHVlID0gaW50dmFsKHN1YnN0cigkdmFsdWUsNSwyKSk7DQoJCQkkeWVhclZhbHVlID0gaW50dmFsKHN1YnN0cigkdmFsdWUsMCw0KSk7DQoJCX0NCgkJDQoJCSRodG1sID0gJyc7DQoJCSRodG1sIC49ICc8c2VsZWN0IG5hbWU9IicgLiAkdGhpcy0+Z2V0SW5wdXRGaWVsZE5hbWUoMSkgLiAnIiBjbGFzcz0iaW5wdXRib3giPic7DQoJCSRodG1sIC49ICc8b3B0aW9uIHZhbHVlPSIiPiZuYnNwOzwvb3B0aW9uPic7DQoJCWZvcigkZGF5PTE7JGRheTw9MzE7JGRheSsrKSB7DQoJCQkkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iJyAuICRkYXkgLiAnIic7DQoJCQlpZiggJGRheSA9PSAkZGF5VmFsdWUgKSB7DQoJCQkJJGh0bWwgLj0gJyBzZWxlY3RlZCc7DQoJCQl9DQoJCQkkaHRtbCAuPSAnPicgLiAkZGF5IC4gJzwvb3B0aW9uPic7DQoJCX0NCgkJJGh0bWwgLj0gJzwvc2VsZWN0Pic7DQoNCgkJJGh0bWwgLj0gJzxzZWxlY3QgbmFtZT0iJyAuICR0aGlzLT5nZXRJbnB1dEZpZWxkTmFtZSgyKSAuICciIGNsYXNzPSJpbnB1dGJveCI+JzsNCgkJJGh0bWwgLj0gJzxvcHRpb24gdmFsdWU9IiI+Jm5ic3A7PC9vcHRpb24+JzsNCgkJZm9yKCRtb250aD0xOyRtb250aDw9MTI7JG1vbnRoKyspIHsNCgkJCSRodG1sIC49ICc8b3B0aW9uIHZhbHVlPSInIC4gJG1vbnRoIC4gJyInOw0KCQkJaWYoICRtb250aCA9PSAkbW9udGhWYWx1ZSApIHsNCgkJCQkkaHRtbCAuPSAnIHNlbGVjdGVkJzsNCgkJCX0NCgkJCSRodG1sIC49ICc+JyAuIGRhdGUoIk0iLCBta3RpbWUoMCwgMCwgMCwgJG1vbnRoKSkgLiAnPC9vcHRpb24+JzsNCgkJfQ0KCQkkaHRtbCAuPSAnPC9zZWxlY3Q+JzsNCgkJDQoJCSRodG1sIC49ICc8c2VsZWN0IG5hbWU9IicgLiAkdGhpcy0+Z2V0SW5wdXRGaWVsZE5hbWUoMykgLiAnIiBjbGFzcz0iaW5wdXRib3giPic7DQoJCSRodG1sIC49ICc8b3B0aW9uIHZhbHVlPSIiPiZuYnNwOzwvb3B0aW9uPic7DQoJCWZvcigkeWVhcj0kZW5kWWVhcjskeWVhcj49JHN0YXJ0WWVhcjskeWVhci0tKSB7DQoJCQkkaHRtbCAuPSAnPG9wdGlvbiB2YWx1ZT0iJyAuICR5ZWFyIC4gJyInOw0KCQkJaWYoICR5ZWFyID09ICR5ZWFyVmFsdWUgKSB7DQoJCQkJJGh0bWwgLj0gJyBzZWxlY3RlZCc7DQoJCQl9DQoJCQkkaHRtbCAuPSAnPicgLiAkeWVhciAuICc8L29wdGlvbj4nOw0KCQl9DQoJCSRodG1sIC49ICc8L3NlbGVjdD4nOwkJDQoJCXJldHVybiAkaHRtbDsNCgl9DQoJDQoJZnVuY3Rpb24gZ2V0V2hlcmVDb25kaXRpb24oKSB7DQoJCSRhcmdzID0gZnVuY19nZXRfYXJncygpOw0KCQkkZGF0ZTAgPSAkYXJnc1szXSAuICctJyAuICRhcmdzWzJdIC4gJy0nIC4gJGFyZ3NbMV07DQoJCSRkYXRlMSA9ICRhcmdzWzZdIC4gJy0nIC4gJGFyZ3NbNV0gLiAnLScgLiAkYXJnc1s0XTsNCgkJJGRhdGUyID0gJGFyZ3NbOV0gLiAnLScgLiAkYXJnc1s4XSAuICctJyAuICRhcmdzWzddOw0KCQkNCgkJaWYoJGFyZ3NbMF0gPT0gMSkgew0KCQkJaWYoIGlzX251bWVyaWMoJGFyZ3NbMV0pICYmIGlzX251bWVyaWMoJGFyZ3NbMl0pICYmIGlzX251bWVyaWMoJGFyZ3NbM10pICkgew0KCQkJCXJldHVybiAnU1RSX1RPX0RBVEUoY2Z2Iy52YWx1ZSxcJyVZLSVtLSVkXCcpID0gU1RSX1RPX0RBVEUoXCcnIC4gJGRhdGUwIC4nXCcsXCclWS0lbS0lZFwnKSc7DQoJCQl9DQoJCX0gZWxzZSB7DQoJCQlpZiggaXNfbnVtZXJpYygkYXJnc1s0XSkgJiYgaXNfbnVtZXJpYygkYXJnc1s1XSkgJiYgaXNfbnVtZXJpYygkYXJnc1s2XSkgJiYgZW1wdHkoJGFyZ3NbN10pICYmIGVtcHR5KCRhcmdzWzhdKSAmJiBlbXB0eSgkYXJnc1s5XSkgKSB7DQoJCQkJZWNobyAnPGJyIC8+MiAnOw0KCQkJCXJldHVybiAnU1RSX1RPX0RBVEUoY2Z2Iy52YWx1ZSxcJyVZLSVtLSVkXCcpID49IFNUUl9UT19EQVRFKFwnJyAuICRkYXRlMSAuJ1wnLFwnJVktJW0tJWRcJyknOw0KCQkJfSBlbHNlaWYoIGlzX251bWVyaWMoJGFyZ3NbN10pICYmIGlzX251bWVyaWMoJGFyZ3NbOF0pICYmIGlzX251bWVyaWMoJGFyZ3NbOV0pICYmIGVtcHR5KCRhcmdzWzRdKSAmJiBlbXB0eSgkYXJnc1s1XSkgJiYgZW1wdHkoJGFyZ3NbNl0pICkgew0KCQkJCWVjaG8gJzxiciAvPjMnOw0KCQkJCXJldHVybiAnU1RSX1RPX0RBVEUoY2Z2Iy52YWx1ZSxcJyVZLSVtLSVkXCcpIDw9IFNUUl9UT19EQVRFKFwnJyAuICRkYXRlMiAuJ1wnLFwnJVktJW0tJWRcJyknOw0KCQkJfSBlbHNlaWYoIGlzX251bWVyaWMoJGFyZ3NbNF0pICYmIGlzX251bWVyaWMoJGFyZ3NbNV0pICYmIGlzX251bWVyaWMoJGFyZ3NbNl0pICYmIGlzX251bWVyaWMoJGFyZ3NbN10pICYmIGlzX251bWVyaWMoJGFyZ3NbOF0pICYmIGlzX251bWVyaWMoJGFyZ3NbOV0pICkgew0KCQkJCWVjaG8gJzxiciAvPjQnOw0KCQkJCSR0aW1lc3RhbXAxID0gbWt0aW1lKDAsIDAsIDAsICRhcmdzWzVdLCAkYXJnc1s0XSwgJGFyZ3NbNl0pOw0KCQkJCSR0aW1lc3RhbXAyID0gbWt0aW1lKDAsIDAsIDAsICRhcmdzWzhdLCAkYXJnc1s3XSwgJGFyZ3NbOV0pOw0KCQkJCWlmKCR0aW1lc3RhbXAxPiR0aW1lc3RhbXAyKSB7DQoJCQkJCSRtYXhEYXRlID0gJGRhdGUxOw0KCQkJCQkkbWluRGF0ZSA9ICRkYXRlMjsNCgkJCQl9IGVsc2Ugew0KCQkJCQkkbWF4RGF0ZSA9ICRkYXRlMjsNCgkJCQkJJG1pbkRhdGUgPSAkZGF0ZTE7DQoJCQkJfQ0KCQkJCWlmKCRtYXhEYXRlID09ICRtaW5EYXRlKSB7DQoJCQkJCWVjaG8gJzxiciAvPjUnOwkJCQkNCgkJCQkJcmV0dXJuICdTVFJfVE9fREFURShjZnYjLnZhbHVlLFwnJVktJW0tJWRcJykgPSBTVFJfVE9fREFURShcJycgLiAkZGF0ZTEgLiAnXCcsXCclWS0lbS0lZFwnKSc7DQoJCQkJfSBlbHNlIHsNCgkJCQkJZWNobyAnPGJyIC8+Nic7DQoJCQkJDQoJCQkJCXJldHVybiAnKFNUUl9UT19EQVRFKGNmdiMudmFsdWUsXCclWS0lbS0lZFwnKSA+PSBTVFJfVE9fREFURShcJycgLiAkbWluRGF0ZSAuJ1wnLFwnJVktJW0tJWRcJykgQU5EIFNUUl9UT19EQVRFKGNmdiMudmFsdWUsXCclWS0lbS0lZFwnKSA8PSBTVFJfVE9fREFURShcJycgLiAkbWF4RGF0ZSAuJ1wnLFwnJVktJW0tJWRcJykpJzsNCgkJCQl9DQoJCQl9IGVsc2Ugew0KCQkJCXJldHVybiBudWxsOw0KCQkJfQ0KCQl9DQoJCXJldHVybiBudWxsOw0KCX0NCn0=')), '0', '0', '0', '0'),
			array('48', 'mfile', 'File', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9tRmlsZSBleHRlbmRzIG1GaWVsZFR5cGVfZmlsZSB7DQoJZnVuY3Rpb24gZ2V0SlNWYWxpZGF0aW9uKCkgew0KCQkkZmlsZUV4dGVuc2lvbiA9IHRyaW0oJHRoaXMtPmdldFBhcmFtKCdmaWxlRXh0ZW5zaW9uJywnJykpOw0KCQlpZighZW1wdHkoJGZpbGVFeHRlbnNpb24pKSB7DQoJCQkkanMgPSAnJzsNCgkJCSRqcyAuPSAnfSBlbHNlIGlmICghaGFzRXh0KGZvcm0uJyAuJHRoaXMtPmdldElucHV0RmllbGROYW1lKDEpIC4gJy52YWx1ZSxcJycgLiAkZmlsZUV4dGVuc2lvbiAuICdcJykpIHsnOyANCgkJCSRqcyAuPSAnYWxlcnQoIicgLiAkdGhpcy0+Z2V0Q2FwdGlvbigpIC4gJzogUGxlYXNlIHNlbGVjdCBmaWxlcyB3aXRoIHRoZXNlIGV4dGVuc2lvbihzKSAtICcgLiBzdHJfcmVwbGFjZSgnfCcsJywgJywkZmlsZUV4dGVuc2lvbikgLiAnLiIpOyc7DQoJCQlyZXR1cm4gJGpzOw0KCQl9IGVsc2Ugew0KCQkJcmV0dXJuIG51bGw7DQoJCX0NCgl9DQp9DQoNCg==')), '0', '0', '0', '0'),
			array('50', 'memail', 'E-mail', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9tRW1haWwgZXh0ZW5kcyBtRmllbGRUeXBlX2VtYWlsIHt9')), '0', '1', '0', '0'),
			array('51', 'mnumber', 'Number', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkVHlwZV9tTnVtYmVyIGV4dGVuZHMgbUZpZWxkVHlwZV9udW1iZXIgew0KfQ==')), '0', '0', '0', '0'),
			array('54', 'digg', 'Digg', $database->getEscaped(base64_decode('Y2xhc3MgbUZpZWxkdHlwZV9kaWdnIGV4dGVuZHMgbUZpZWxkVHlwZSB7DQoJdmFyICRudW1PZlNlYXJjaEZpZWxkcyA9IDA7DQoJdmFyICRudW1PZklucHV0RmllbGRzID0gMDsNCg0KCWZ1bmN0aW9uIGdldE91dHB1dCgkdmlldz0xKSB7DQoJCWdsb2JhbCAkbXRjb25mLCAkSXRlbWlkOw0KCQkkaHRtbCA9ICcnOw0KCQkkaHRtbCAuPSAnPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPic7DQoJCSRodG1sIC49ICdkaWdnX3VybD1cJyc7DQoJCWlmKHN1YnN0cigkbXRjb25mLT5nZXRqY29uZignbGl2ZV9zaXRlJyksMCwxNikgPT0gJ2h0dHA6Ly9sb2NhbGhvc3QnKSB7DQoJCQkvLyBBbGxvdyBmb3IgZGVidWdnaW5nDQoJCQkkaHRtbCAuPSBzdHJfcmVwbGFjZSgnaHR0cDovL2xvY2FsaG9zdCcsJ2h0dHA6Ly8xMjcuMC4wLjEnLCRtdGNvbmYtPmdldGpjb25mKCdsaXZlX3NpdGUnKSk7DQoJCX0gZWxzZSB7DQoJCQkkaHRtbCAuPSAkbXRjb25mLT5nZXRqY29uZignbGl2ZV9zaXRlJyk7DQoJCX0NCgkJJGh0bWwgLj0gJy8nOw0KCQkkaHRtbCAuPSBzZWZSZWx0b0FicygnaW5kZXgucGhwP29wdGlvbj1jb21fbXRyZWUmYW1wO3Rhc2s9dmlld2xpbmsmYW1wO2xpbmtfaWQ9Jy4kdGhpcy0+Z2V0TGlua0lkKCkuJyZhbXA7SXRlbWlkPScuJEl0ZW1pZCkgLidcJzsnOw0KCQkvLyBEaXNwbGF5IHRoZSBjb21wYWN0IHZlcnNpb24gd2hlbiBkaXNwbGF5ZWQgaW4gU3VtbWFyeSB2aWV3DQoJCWlmKCR2aWV3PT0yKSB7DQoJCQkkaHRtbCAuPSAnZGlnZ19za2luID0gXCdjb21wYWN0XCc7JzsNCgkJfQ0KCQkkaHRtbCAuPSAnPC9zY3JpcHQ+JzsNCgkJJGh0bWwgLj0gJzxzY3JpcHQgc3JjPSJodHRwOi8vZGlnZy5jb20vdG9vbHMvZGlnZ3RoaXMuanMiIHR5cGU9InRleHQvamF2YXNjcmlwdCI+PC9zY3JpcHQ+JzsNCgkJcmV0dXJuICRodG1sOw0KCX0NCn0=')), '0', '0', '0', '0'),
			));
		$updated = true;
	}
	if(createTable('fieldtypes_info', array('`ft_id` smallint(6) NOT NULL', '`ft_version` varchar(64) NOT NULL', '`ft_website` varchar(255) NOT NULL', '`ft_desc` text NOT NULL', 'PRIMARY KEY  (`ft_id`)'))) {
		addRows('fieldtypes_info',array(
			array(1, '1.00', 'http://www.mosets.com/tree/', 'A simple field type that allows you to create a web link that optionally opens in a new window.'),
			array(24, '1.00', 'http://www.mosets.com/', 'Audio Player allows users to upload a mucis file and play the music from within the listing page. Provides basic playback options such as play, pause and volumne control. Made possible by http://www.1pixelout.net/code/audio-player-wordpress-plugin/.'),
			array(25, '1.00', 'http://www.mosets.com/', 'Image field type accepts gif, png & jpg file and resize it according to the value set in the parameter before it is stored to the database.'),
			array(29, '1.00', 'http://www.mosets.com/', ''),
			array(36, '1.00', '', ''),
			array(37, '1.00', '', ''),
			array(38, '1.00', '', ''),
			array(39, '1.00', '', ''),
			array(45, '1.00', 'http://www.mosets.com/', ''),
			array(46, '1.00', 'http://www.mosets.com/', ''),
			array(47, '1.00', 'http://www.mosets.com', 'Date input'),
			array(48, '1.00', 'http://www.mosets.com/', 'File field type accept any type of file uploads. You can choose to limit the acceptable file extension in the parameter settings.'),
			array(50, '1.00', 'http://www.mosets.com/', 'E-mail field type validates the e-mail entered by users before storing it to the database. The e-mail will be displayed with the mailto protocol in the front-end. To protect against e-mail harvester, e-mail is cloaked through javascript.'),
			array(51, '1.00', 'http://www.mosets.com/', 'Number field type accepts numeric value with up to 2 decimals.'),
			array(53, '1.00', 'http://www.mosets.com/', ''),
			array(54, '1.00', 'http://www.digg.com/', 'Displays the Digg button for each listings.')
			));
		$updated = true;
	}
	if(createTable('templates', array('`tem_id` int(11) NOT NULL auto_increment', '`tem_name` varchar(255) NOT NULL', '`params` text NOT NULL', 'PRIMARY KEY  (`tem_id`)', 'UNIQUE KEY `tem_name` (`tem_name`)'))) {
		addRows('templates',array(1, 'm2', ''));
		$updated = true;
	}
	if(createTable('fieldtypes_att', array('`fta_id` int(11) NOT NULL auto_increment', '`ft_id` int(11) NOT NULL', '`filename` varchar(255) NOT NULL', '`filedata` mediumblob NOT NULL', '`filesize` int(11) NOT NULL', '`extension` varchar(255) NOT NULL', '`ordering` int(11) NOT NULL', 'PRIMARY KEY  (`fta_id`)', 'KEY `filename` (`filename`)'))) {
		addRows('fieldtypes_att',array(
			array('104', '20', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0ibWF4U3VtbWFyeUNoYXJzIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSI1NSIgbGFiZWw9Ik1heC4gY2hhcmFjdGVycyBpbiBTdW1tYXJ5IHZpZXcuIiBkZXNjcmlwdGlvbj0iRW50ZXIgMCB0byBzaG93IHRoZSBmdWxsIG5hbWUgcmVnYXJkbGVzcyBvZiBpdHMgbGVuZ3RoLiIgLz4KCQk8cGFyYW0gbmFtZT0ibWF4RGV0YWlsc0NoYXJzIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIwIiBsYWJlbD0iTWF4LiBjaGFyYWN0ZXJzIGluIERldGFpbHMgdmlldy4iIGRlc2NyaXB0aW9uPSJFbnRlciAwIHRvIHNob3cgdGhlIGZ1bGwgbmFtZSByZWdhcmRsZXNzIG9mIGl0cyBsZW5ndGguIiAvPgoJPC9wYXJhbXM+CjwvbW9zcGFyYW1zPg==')), '400', 'text/xml', '1'),
			array('100', '21', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0ic3VtbWFyeUNoYXJzIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIyNTUiIGxhYmVsPSJOdW1iZXIgb2YgU3VtbWFyeSBjaGFyYWN0ZXJzIiAvPgoJCTxwYXJhbSBuYW1lPSJzdHJpcFN1bW1hcnlUYWdzIiB0eXBlPSJyYWRpbyIgZGVmYXVsdD0iMSIgbGFiZWw9IlN0cmlwIGFsbCBIVE1MIHRhZ3MgaW4gU3VtbWFyeSB2aWV3IiBkZXNjcmlwdGlvbj0iU2V0dGluZyB0aGlzIHRvIHllcyB3aWxsIHJlbW92ZSBhbGwgdGFncyB0aGF0IGNvdWxkIHBvdGVudGlhbGx5IGFmZmVjdCB3aGVuIHZpZXdpbmcgYSBsaXN0IG9mIGxpc3RpbmdzLiI+CgkJCTxvcHRpb24gdmFsdWU9IjAiPk5vPC9vcHRpb24+CgkJCTxvcHRpb24gdmFsdWU9IjEiPlllczwvb3B0aW9uPgoJCTwvcGFyYW0+CgkJPHBhcmFtIG5hbWU9InN0cmlwRGV0YWlsc1RhZ3MiIHR5cGU9InJhZGlvIiBkZWZhdWx0PSIxIiBsYWJlbD0iU3RyaXAgYWxsIEhUTUwgdGFncyBpbiBEZXRhaWxzIHZpZXciIGRlc2NyaXB0aW9uPSJTZXR0aW5nIHRoaXMgdG8geWVzIHdpbGwgcmVtb3ZlIGFsbCB0YWdzIGV4Y2VwdCB0aG9zZSB0aGF0IGFyZSBzcGVjaWZpZWQgaW4gJ0FsbG93ZWQgdGFncycuIj4KCQkJPG9wdGlvbiB2YWx1ZT0iMCI+Tm88L29wdGlvbj4KCQkJPG9wdGlvbiB2YWx1ZT0iMSI+WWVzPC9vcHRpb24+CgkJPC9wYXJhbT4KCQk8cGFyYW0gbmFtZT0icGFyc2VVcmwiIHR5cGU9InJhZGlvIiBkZWZhdWx0PSIxIiBsYWJlbD0iUGFyc2UgVVJMIGFzIGxpbmsgaW4gRGV0YWlscyB2aWV3Ij4KCQkJPG9wdGlvbiB2YWx1ZT0iMCI+Tm88L29wdGlvbj4KCQkJPG9wdGlvbiB2YWx1ZT0iMSI+WWVzPC9vcHRpb24+CgkJPC9wYXJhbT4KCgkJPHBhcmFtIG5hbWU9InN0cmlwQWxsVGFnc0JlZm9yZVNhdmUiIHR5cGU9InJhZGlvIiBkZWZhdWx0PSIxIiBsYWJlbD0iU3RyaXAgYWxsIEhUTUwgdGFncyBiZWZvcmUgc3RvcmluZyB0byBkYXRhYmFzZSIgZGVzY3JpcHRpb249IklmIFdZU1lXSUcgZWRpdG9yIGlzIGVuYWJsZWQgaW4gdGhlIGZyb250LWVuZCwgdGhpcyBmZWF0dXJlIGFsbG93IHlvdSB0byBzdHJpcCBhbnkgcG90ZW50aWFsbHkgaGFybWZ1bCBjb2Rlcy4gWW91IGNhbiBzdGlsbCBhbGxvdyBzb21lIHRhZ3Mgd2l0aGluIGRlc2NyaXB0aW9uIGZpZWxkLCB3aGljaCBjYW4gYmUgc3BlY2lmaWVkIGJlbG93LiI+CgkJCTxvcHRpb24gdmFsdWU9IjAiPk5vPC9vcHRpb24+CgkJCTxvcHRpb24gdmFsdWU9IjEiPlllczwvb3B0aW9uPgoJCTwvcGFyYW0+CgkJPHBhcmFtIG5hbWU9ImFsbG93ZWRUYWdzIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSJ1LGIsaSxhLHVsLGxpLHByZSxibG9ja3F1b3RlIiBsYWJlbD0iQWxsb3dlZCB0YWdzIiBkZXNjcmlwdGlvbj0iRW50ZXIgdGhlIHRhZyBuYW1lcyBzZXBlcmF0ZWQgYnkgY29tbWEuIFRoaXMgcGFyYW1ldGVyIGFsbG93IHlvdSB0byBhY2NlcHQgc29tZSBIVE1MIHRhZ3MgZXZlbiBpZiB5b3UgaGF2ZSBlbmFibGUgc3RyaXBpbmcgb2YgYWxsIEhUTUwgdGFncyBhYm92ZS4iIC8+CgkJPHBhcmFtIG5hbWU9InBhcnNlTWFtYm90cyIgdHlwZT0icmFkaW8iIGRlZmF1bHQ9IjAiIGxhYmVsPSJQYXJzZSBNYW1ib3RzIiBkZXNjcmlwdGlvbj0iRW5hYmxpbmcgdGhpcyB3aWxsIHBhcnNlIG1hbWJvdHMgY29kZXNzIHdpdGhpbiB0aGUgZGVzY3JpcHRpb24gZmllbGQiPgoJCQk8b3B0aW9uIHZhbHVlPSIwIj5Obzwvb3B0aW9uPgoJCQk8b3B0aW9uIHZhbHVlPSIxIj5ZZXM8L29wdGlvbj4KCQk8L3BhcmFtPgoJPC9wYXJhbXM+CjwvbW9zcGFyYW1zPg==')), '1822', 'text/xml', '1'),
			array('53', '23', 'application_double.png', $database->getEscaped(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGnSURBVDjLnZOxilNBGIW/mXt3CZsYQQtjJYqPkE4L8Q20WARbmxWEFQvBXrByQdDKF3CL1QcQH8DOKmVIkWIFbdybTe7M/x+Lm+zeFELcAz/DwJnD4eOf8OD5p4d37w2f/qrUwR25k3PG5cgMl5AZ5k5/O81Ho+mHo7e7RyxVDu8M97c63TjosIk61cz2gfOAWVKc/T5hU50mxfa9lInXj29vHPDkzYT1ADkAi2x8/jq6fpy7N37+8eJfPHqX+zx7/1397VSNRtOXJRIAMcB4tnOr19thcHWjMt1qZu9KcwMghEBVi+o/eZSW81nARXiUOaXzgBYPuTCH7I65Y1nNyKlN3BxcwtwoLTUNItDmoRhQECWRECIhGKEQhUfK3Pg8G+V0PPm2d5Du5zpxZXDtrA0BCoEkCkEMBWUAC8Ji09TNG8NqXnz8IUnK7sruSmaqzTQ30yIlndZJszrpZJ4kSY9efVnfqjaP9hmBECNFEQkxEIuVP1O2A9Z4LB8Xy3OlrbbfbD1gOp4c7h2k3VwnzAx3Jy0WzY90Q6ZmK93xBsNh0JL8RfUXD1Ut4VHY1QEAAAAASUVORK5CYII=')), '533', 'image/png', '1'),
			array('54', '23', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0ib3Blbk5ld1dpbmRvdyIgdHlwZT0icmFkaW8iIGRlZmF1bHQ9IjEiIGxhYmVsPSJPcGVuIE5ldyBXaW5kb3ciIGRlc2NyaXB0aW9uPSJPcGVuIGEgbmV3IHdpbmRvdyB3aGVuIHRoZSBsaW5rIGlzIGNsaWNrZWQuIj4KCQkJPG9wdGlvbiB2YWx1ZT0iMCI+Tm88L29wdGlvbj4KCQkJPG9wdGlvbiB2YWx1ZT0iMSI+WWVzPC9vcHRpb24+CgkJPC9wYXJhbT4KCQk8cGFyYW0gbmFtZT0ic2hvd0ljb24iIHR5cGU9InJhZGlvIiBkZWZhdWx0PSIxIiBsYWJlbD0iU2hvdyBJY29uIj4KCQkJPG9wdGlvbiB2YWx1ZT0iMCI+Tm88L29wdGlvbj4KCQkJPG9wdGlvbiB2YWx1ZT0iMSI+WWVzPC9vcHRpb24+CgkJPC9wYXJhbT4KCQk8cGFyYW0gbmFtZT0idGV4dCIgdHlwZT0idGV4dCIgZGVmYXVsdD0iIiBsYWJlbD0iTGluayBUZXh0IiBkZXNjcmlwdGlvbj0iVXNlIHRoaXMgcGFyYW1ldGVyIHRvIHNwZWNpZnkgdGhlIGxpbmsgdGV4dC4gSWYgbGVmdCBlbXB0eSwgdGhlIGZ1bGwgVVJMIHdpbGwgYmUgZGlzcGxheWVkIGFzIHRoZSBsaW5rJ3MgdGV4dC4iIC8+CgkJPHBhcmFtIG5hbWU9Im1heFVybExlbmd0aCIgdHlwZT0idGV4dCIgZGVmYXVsdD0iIiBsYWJlbD0iTWF4LiBVUkwgTGVuZ3RoIiBkZXNjcmlwdGlvbj0iRW50ZXIgdGhlIG1heGltdW0gVVJMJ3MgbGVuZ3RoIGJlZm9yZSBpdCBpcyBjbGlwcGVkIiAvPgoJCTxwYXJhbSBuYW1lPSJjbGlwcGVkU3ltYm9sIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIuLi4iIGxhYmVsPSJDbGlwcGVkIHN5bWJvbCIgLz4KCTwvcGFyYW1zPgo8L21vc3BhcmFtcz4=')), '839', 'text/xml', '2'),
			array('55', '24', 'audio-player.js', $database->getEscaped(base64_decode('dmFyIGFwX2luc3RhbmNlcyA9IG5ldyBBcnJheSgpOw0KDQpmdW5jdGlvbiBhcF9zdG9wQWxsKHBsYXllcklEKSB7DQoJZm9yKHZhciBpID0gMDtpPGFwX2luc3RhbmNlcy5sZW5ndGg7aSsrKSB7DQoJCXRyeSB7DQoJCQlpZihhcF9pbnN0YW5jZXNbaV0gIT0gcGxheWVySUQpIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCJhdWRpb3BsYXllciIgKyBhcF9pbnN0YW5jZXNbaV0udG9TdHJpbmcoKSkuU2V0VmFyaWFibGUoImNsb3NlUGxheWVyIiwgMSk7DQoJCQllbHNlIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCJhdWRpb3BsYXllciIgKyBhcF9pbnN0YW5jZXNbaV0udG9TdHJpbmcoKSkuU2V0VmFyaWFibGUoImNsb3NlUGxheWVyIiwgMCk7DQoJCX0gY2F0Y2goIGVycm9yT2JqZWN0ICkgew0KCQkJLy8gc3RvcCBhbnkgZXJyb3JzDQoJCX0NCgl9DQp9DQoNCmZ1bmN0aW9uIGFwX3JlZ2lzdGVyUGxheWVycygpIHsNCgl2YXIgb2JqZWN0SUQ7DQoJdmFyIG9iamVjdFRhZ3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5VGFnTmFtZSgib2JqZWN0Iik7DQoJZm9yKHZhciBpPTA7aTxvYmplY3RUYWdzLmxlbmd0aDtpKyspIHsNCgkJb2JqZWN0SUQgPSBvYmplY3RUYWdzW2ldLmlkOw0KCQlpZihvYmplY3RJRC5pbmRleE9mKCJhdWRpb3BsYXllciIpID09IDApIHsNCgkJCWFwX2luc3RhbmNlc1tpXSA9IG9iamVjdElELnN1YnN0cmluZygxMSwgb2JqZWN0SUQubGVuZ3RoKTsNCgkJfQ0KCX0NCn0NCg0KdmFyIGFwX2NsZWFySUQgPSBzZXRJbnRlcnZhbCggYXBfcmVnaXN0ZXJQbGF5ZXJzLCAxMDAgKTs=')), '791', 'application/x-javascript', '1'),
			array('56', '24', 'player.swf', $database->getEscaped(base64_decode('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')), '5260', 'application/x-shockwave-flash', '2'),
			array('60', '24', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0iYXV0b1N0YXJ0IiB0eXBlPSJyYWRpbyIgZGVmYXVsdD0iMCIgbGFiZWw9IkF1dG8gU3RhcnQiIGRlc2NyaXB0aW9uPSJBdXRvbWF0aWNhbGx5IG9wZW4gdGhlIHBsYXllciBhbmQgc3RhcnQgcGxheWluZyB0aGUgdHJhY2suIj4KCQkJPG9wdGlvbiB2YWx1ZT0iMCI+Tm88L29wdGlvbj4KCQkJPG9wdGlvbiB2YWx1ZT0iMSI+WWVzPC9vcHRpb24+CgkJPC9wYXJhbT4KCQk8cGFyYW0gbmFtZT0ibG9vcCIgdHlwZT0icmFkaW8iIGRlZmF1bHQ9IjAiIGxhYmVsPSJMb29wIiBkZXNjcmlwdGlvbj0iVGhlIHRyYWNrIHdpbGwgYmUgbG9vcGVkIGluZGVmaW5pdGVseSI+CgkJCTxvcHRpb24gdmFsdWU9IjAiPk5vPC9vcHRpb24+CgkJCTxvcHRpb24gdmFsdWU9IjEiPlllczwvb3B0aW9uPgoJCTwvcGFyYW0+CgkJPHBhcmFtIG5hbWU9InRleHRDb2xvdXIiIHR5cGU9InRleHQiIGRlZmF1bHQ9IiIgbGFiZWw9IlRleHQgY29sb3VyIiAvPgoJCTxwYXJhbSBuYW1lPSJzbGlkZXJDb2xvdXIiIHR5cGU9InRleHQiIGRlZmF1bHQ9IiIgbGFiZWw9IlNsaWRlciBjb2xvdXIiIC8+CgkJPHBhcmFtIG5hbWU9ImxvYWRlckNvbG91ciIgdHlwZT0idGV4dCIgZGVmYXVsdD0iIiBsYWJlbD0iTG9hZGVyIGNvbG91ciIgLz4KCQk8cGFyYW0gbmFtZT0idHJhY2tDb2xvdXIiIHR5cGU9InRleHQiIGRlZmF1bHQ9IiIgbGFiZWw9IlRyYWNrIGNvbG91ciIgLz4KCQk8cGFyYW0gbmFtZT0iYm9yZGVyQ29sb3VyIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIiIGxhYmVsPSJCb3JkZXIgY29sb3VyIiAvPgoJCTxwYXJhbSBuYW1lPSJiYWNrZ3JvdW5kQ29sb3VyIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIiIGxhYmVsPSJCYWNrZ3JvdW5kIGNvbG91ciIgLz4KCQk8cGFyYW0gbmFtZT0ibGVmdEJhY2tncm91bmRDb2xvdXIiIHR5cGU9InRleHQiIGRlZmF1bHQ9IiIgbGFiZWw9IkxlZnQgYmFja2dyb3VuZCBjb2xvdXIiIC8+CgkJPHBhcmFtIG5hbWU9InJpZ2h0QmFja2dyb3VuZENvbG91ciIgdHlwZT0idGV4dCIgZGVmYXVsdD0iIiBsYWJlbD0iUmlnaHQgYmFja2dyb3VuZCBjb2xvdXIiIC8+CgkJPHBhcmFtIG5hbWU9InJpZ2h0QmFja2dyb3VuZEhvdmVyQ29sb3VyIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIiIGxhYmVsPSJSaWdodCBiYWNrZ3JvdW5kIGNvbG91ciAoaG92ZXIpIiAvPgoJCTxwYXJhbSBuYW1lPSJsZWZ0SWNvbkNvbG91ciIgdHlwZT0idGV4dCIgZGVmYXVsdD0iIiBsYWJlbD0iTGVmdCBpY29uIGNvbG91ciIgLz4KCQk8cGFyYW0gbmFtZT0icmlnaHRJY29uQ29sb3VyIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIiIGxhYmVsPSJSaWdodCBpY29uIGNvbG91ciIgLz4KCQk8cGFyYW0gbmFtZT0icmlnaHRJY29uSG92ZXJDb2xvdXIiIHR5cGU9InRleHQiIGRlZmF1bHQ9IiIgbGFiZWw9IlJpZ2h0IGljb24gY29sb3VyIChob3ZlcikiIC8+Cgk8L3BhcmFtcz4KPC9tb3NwYXJhbXM+')), '1497', 'text/xml', '3'),
			array('99', '25', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0ic2l6ZSIgdHlwZT0idGV4dCIgZGVmYXVsdD0iMCIgbGFiZWw9Ik1heC4gd2lkdGggJmFtcDsgaGVpZ2h0IiBkZXNjcmlwdGlvbj0iRW50ZXIgdGhlIG1heGltdW0gc2l6ZSBvZiB0aGUgd2lkdGggYW5kIGhlaWdodCBvZiB0aGUgcmVzaXplZCBpbWFnZS4gRW50ZXIgMCB0byB1c2UgdGhlIHZhbHVlIGNvbmZpZ3VyZWQgZm9yIGxpc3RpbmcgdGh1bWJuYWlsJ3Mgc2l6ZS4iIC8+Cgk8L3BhcmFtcz4KPC9tb3NwYXJhbXM+')), '288', 'text/xml', '1'),
			array('102', '26', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0icm93cyIgdHlwZT0idGV4dCIgZGVmYXVsdD0iNiIgbGFiZWw9IlJvd3MiIC8+CgkJPHBhcmFtIG5hbWU9ImNvbHMiIHR5cGU9InRleHQiIGRlZmF1bHQ9IjYwIiBsYWJlbD0iQ29sdW1ucyIgLz4KCQk8cGFyYW0gbmFtZT0ic3R5bGUiIHR5cGU9InRleHQiIGRlZmF1bHQ9IiIgbGFiZWw9IlN0eWxlIiBkZXNjcmlwdGlvbj0iVGhlIHRleHRib3ggYnkgZGVmYXVsdCBpcyBzdHlsZWQgYnkgdGhlICdpbnB1dGJveCcgQ1NTIGNsYXNzLiBZb3UgY2FuIHNwZWNpZnkgYWRkaXRpb25hbCBzdHlsZSBoZXJlIiAvPgoJPC9wYXJhbXM+CjwvbW9zcGFyYW1zPg==')), '361', 'text/xml', '1'),
			array('88', '29', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0idmlkZW9Qcm92aWRlciIgdHlwZT0ibGlzdCIgZGVmYXVsdD0iIiBsYWJlbD0iVmlkZW8gUHJvdmlkZXIiPgoJCQk8b3B0aW9uIHZhbHVlPSJ5b3V0dWJlIj5Zb3V0dWJlPC9vcHRpb24+CgkJCTxvcHRpb24gdmFsdWU9Imdvb2dsZXZpZGVvIj5Hb29nbGUgVmlkZW88L29wdGlvbj4KCQkJPG9wdGlvbiB2YWx1ZT0ibWV0YWNhZmUiPk1ldGFjYWZlPC9vcHRpb24+CgkJCTxvcHRpb24gdmFsdWU9ImlmaWxtIj5pRmlsbTwvb3B0aW9uPgoJCTwvcGFyYW0+CgkJPHBhcmFtIG5hbWU9ImNoZWNrYm94TGFiZWwiIHR5cGU9InRleHQiIGRlZmF1bHQ9IkNvbnRhaW5zIHZpZGVvIiBsYWJlbD0iU2VhcmNoJ3MgY2hlY2tib3ggbGFiZWwiIC8+Cgk8L3BhcmFtcz4KPC9tb3NwYXJhbXM+')), '432', 'text/xml', '1'),
			array('72', '45', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0iZm9ybWF0IiB0eXBlPSJsaXN0IiBkZWZhdWx0PSIiIGxhYmVsPSJWaWRlbyBGb3JtYXQiPgoJCQk8b3B0aW9uIHZhbHVlPSJtb3YiPlF1aWNrdGltZSBNb3ZpZTwvb3B0aW9uPgoJCQk8b3B0aW9uIHZhbHVlPSJkaXZ4Ij5EaXZYPC9vcHRpb24+CgkJCTxvcHRpb24gdmFsdWU9ImZsYXNoIj5GbGFzaDwvb3B0aW9uPgoJCQk8b3B0aW9uIHZhbHVlPSJ3aW5kb3dzbWVkaWEiPldpbmRvd3MgTWVkaWEgVmlkZW88L29wdGlvbj4KCQk8L3BhcmFtPgoJCTxwYXJhbSBuYW1lPSJ3aWR0aCIgdHlwZT0idGV4dCIgZGVmYXVsdD0iIiBsYWJlbD0iV2lkdGgiIC8+CgkJPHBhcmFtIG5hbWU9ImhlaWdodCIgdHlwZT0idGV4dCIgZGVmYXVsdD0iIiBsYWJlbD0iaGVpZ2h0IiAvPgoJCTxwYXJhbSBuYW1lPSJhdXRvcGxheSIgdHlwZT0icmFkaW8iIGRlZmF1bHQ9ImZhbHNlIiBsYWJlbD0iQXV0byBQbGF5Ij4KCQkJPG9wdGlvbiB2YWx1ZT0idHJ1ZSI+WWVzPC9vcHRpb24+CgkJCTxvcHRpb24gdmFsdWU9ImZhbHNlIj5Obzwvb3B0aW9uPgoJCTwvcGFyYW0+CgkJCgk8L3BhcmFtcz4KPC9tb3NwYXJhbXM+')), '612', 'text/xml', '1'),
			array('73', '46', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0ic3RhcnRZZWFyIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIiIGxhYmVsPSJTdGFydCB5ZWFyIiBkZXNjcmlwdGlvbj0iRW50ZXIgdGhlIHN0YXJ0aW5nIHllYXIgb3IgZWFybGllc3QgeWVhciBhdmFpbGFibGUgZm9yIHNlbGVjdGlvbi4gSWYgbGVmdCBlbXB0eSwgaXQgd2lsbCBkZWZhdWx0IHRvIDcwIHllYXJzIGFnbyBmcm9tIHRoZSBjdXJyZW50IHllYXIuIiAvPgoJCTxwYXJhbSBuYW1lPSJlbmRZZWFyIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIiIGxhYmVsPSJFbmQgeWVhciIgZGVzY3JpcHRpb249IkVudGVyIHRoZSBsYXRlc3QgeWVhciBvciBhdmFpbGFibGUgZm9yIHNlbGVjdGlvbi4gSWYgbGVmdCBlbXB0eSwgdGhlIGN1cnJlbnQgeWVhciB3aWxsIGJlIHVzZWQuIiAvPgoJPC9wYXJhbXM+CjwvbW9zcGFyYW1zPg==')), '457', 'text/xml', '1'),
			array('74', '47', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0ic3RhcnRZZWFyIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIiIGxhYmVsPSJTdGFydCB5ZWFyIiBkZXNjcmlwdGlvbj0iRW50ZXIgdGhlIHN0YXJ0aW5nIHllYXIgb3IgZWFybGllc3QgeWVhciBhdmFpbGFibGUgZm9yIHNlbGVjdGlvbi4gSWYgbGVmdCBlbXB0eSwgaXQgd2lsbCBkZWZhdWx0IHRvIDcwIHllYXJzIGFnbyBmcm9tIHRoZSBjdXJyZW50IHllYXIuIiAvPgoJCTxwYXJhbSBuYW1lPSJlbmRZZWFyIiB0eXBlPSJ0ZXh0IiBkZWZhdWx0PSIiIGxhYmVsPSJFbmQgeWVhciIgZGVzY3JpcHRpb249IkVudGVyIHRoZSBsYXRlc3QgeWVhciBvciBhdmFpbGFibGUgZm9yIHNlbGVjdGlvbi4gSWYgbGVmdCBlbXB0eSwgdGhlIGN1cnJlbnQgeWVhciB3aWxsIGJlIHVzZWQuIiAvPgoJCTxwYXJhbSBuYW1lPSJkYXRhRm9ybWF0IiB0eXBlPSJsaXN0IiBkZWZhdWx0PSIiIGxhYmVsPSJEYXRlIEZvcm1hdCIgPgoJCQk8b3B0aW9uIHZhbHVlPSJZLW0tZCI+MjAwNy0wNi0wMTwvb3B0aW9uPgoJCQk8b3B0aW9uIHZhbHVlPSJqLm4uWSI+MS42LjIwMDc8L29wdGlvbj4KCQkJPG9wdGlvbiB2YWx1ZT0iZCBGIFkiPjAxIEp1bmUgMjAwNzwvb3B0aW9uPgoJCQk8b3B0aW9uIHZhbHVlPSJqUyBcb1xmIEYgWSI+MXN0IG9mIEp1bmUgMjAwNzwvb3B0aW9uPgoJCQk8b3B0aW9uIHZhbHVlPSJqL24vWSI+MS82LzIwMDc8L29wdGlvbj4KCQkJPG9wdGlvbiB2YWx1ZT0ibi9qL1kiPjYvMS8yMDA3PC9vcHRpb24+CgkJPC9wYXJhbT4JCQoJPC9wYXJhbXM+CjwvbW9zcGFyYW1zPg==')), '820', 'text/xml', '1'),
			array('75', '48', 'params.xml', $database->getEscaped(base64_decode('PG1vc3BhcmFtcyB0eXBlPSJtb2R1bGUiPgoJPHBhcmFtcz4KCQk8cGFyYW0gbmFtZT0iZmlsZUV4dGVuc2lvbnMiIHR5cGU9InRleHQiIGRlZmF1bHQ9IiIgbGFiZWw9IkFjY2VwdGFibGUgZmlsZSBleHRlbnNpb25zIiBkZXNjcmlwdGlvbj0iRW50ZXIgdGhlIGFjY2VwdGFibGUgZmlsZSB0eXBlIG9mIGV4dGVuc2lvbiBmb3IgdGhpcyBmaWVsZC4gSWYgeW91IGhhdmUgbW9yZSB0aGFuIG9uZSBleHRlbnNpb24sIHBsZWFzZSBzZXBlcmF0ZSB0aGUgZXh0ZW5zaW9uIHdpdGggYSBiYXIgJ3wnLiBFeGFtcGxlOiAnZ2lmfHBuZ3xqcGd8anBlZycgb3IgJ3BkZicuIFBsZWFzZSBkbyBub3Qgc3RhcnQgb3IgZW5kIHRoZSB2YWx1ZSB3aXRoIGEgYmFyLiAiIC8+Cgk8L3BhcmFtcz4KPC9tb3NwYXJhbXM+')), '396', 'text/xml', '1')
			));
		$updated = true;
	}
	
	if(addColumn('log', 'log_id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST')) $updated = true;
	if(addColumn('log', 'rev_id', 'INT NOT NULL DEFAULT \'0\'')) $updated = true;
	if(addColumn('log', 'value', 'TINYINT NOT NULL DEFAULT \'0\'')) $updated = true;
	if(addIndex('log', 'log_type', array('log_type'))) $updated = true;
	if(addIndex('log', 'log_ip', array('log_ip','user_id'))) $updated = true;
	if(addIndex('log', 'user_id', array('user_id'))) $updated = true;

	// Before dropping jos_mt_config, export the custom fields to #__mt_customfields.
	$database->setQuery( "SELECT * FROM #__mt_config WHERE value <> ''" );
	$customfields = $database->loadObjectList();
	$cfs =array();
	if(count($customfields)>0) {
		$ordering=26;
		foreach($customfields AS $customfield) {
			addRows('customfields',array('', 'text', $database->getEscaped($customfield->value), '', 30, '', '', '', '', '', 0, $ordering++, 0, 0, 1, 0, 0, $customfield->searchable, 1, 0, '', '', 0));
			$cfs[$customfield->name] = $database->insertid();
		}
	}
	
	// For those that are non-empty, copy all the data from #__mt_links to #__cfvalues
	if(count($cfs)>0) {
		foreach($cfs AS $cf_name => $cf_id ) {
			$database->setQuery('INSERT INTO #__mt_cfvalues (cf_id,link_id,value)'
			.	' SELECT \'' . $cf_id . '\', link_id, ' . $cf_name . ' FROM #__mt_links WHERE ' . $cf_name . ' <> \'\''
			);
			$database->query();
		}
	}
	
	// Copy images to ~/o, ~/m and ~/s directories
	$link_wimages = array();
	$database->setQuery( 'SELECT link_id, link_image FROM #__mt_links WHERE link_image <> \'\'' );
	$link_wimages = $database->loadObjectList();
	if(count($link_wimages)>0) {
		foreach($link_wimages AS $link_wimage) {
			$image_fullpath = JPATH_COMPONENT_SITE.DS.'img'.DS.'listings'.DS. $link_wimage->link_image;
			if(file_exists($image_fullpath)) {
				$database->setQuery( "INSERT INTO #__mt_images (link_id, filename, ordering) "
					.	"\n VALUES('" . $link_wimage->link_id . "', '" . $link_wimage->link_image . "', '1')" );
				if(!$database->query()) {
					echo '<br />' . $database->getErrorMsg();
				}
				$img_id = $database->insertid();

				$file_extension = pathinfo($link_wimage->link_image);
				$file_extension = strtolower($file_extension['extension']);

				copy($image_fullpath, JPATH_COMPONENT_SITE.DS.'img'.DS.'listings'.DS.'o'.DS. $img_id . '.' . $file_extension);
				copy($image_fullpath, JPATH_COMPONENT_SITE.DS.'img'.DS.'listings'.DS.'m'.DS. $img_id . '.' . $file_extension);
				copy($image_fullpath, JPATH_COMPONENT_SITE.DS.'img'.DS.'listings'.DS.'s'.DS. $img_id . '.' . $file_extension);
				unlink($image_fullpath);
				$database->setQuery("UPDATE #__mt_images SET filename = '" . $img_id . '.' . $file_extension . "' WHERE img_id = '" . $img_id . "'");
				$database->query();
				
			}
		}
		printRow('Total of ' . count($link_wimages) . ' listing images are processed.', 1 );
	}
	
	// Copy all categories' images to ~/o, and ~/s directories
	$cat_wimages = array();
	$database->setQuery( 'SELECT cat_id, cat_image FROM #__mt_cats WHERE cat_image <> \'\'' );
	$cat_wimages = $database->loadObjectList();
	if(count($cat_wimages)>0) {
		foreach($cat_wimages AS $cat_wimage) {
			$image_fullpath = JPATH_COMPONENT_SITE.DS.'img'.DS.'cats'.DS. $cat_wimage->cat_image;
			if(file_exists($image_fullpath)) {
				copy($image_fullpath, JPATH_COMPONENT_SITE.DS.'img'.DS.'cats'.DS.'o'.DS. $cat_wimage->cat_image);
				copy($image_fullpath, JPATH_COMPONENT_SITE.DS.'img'.DS.'cats'.DS.'s'.DS. $cat_wimage->cat_image);
				unlink($image_fullpath);
			}
		}
		printRow('Total of ' . count($cat_wimages) . ' category images are processed.', 1 );
	}

	// Drop all cust_# columns & link_image from #__mt_links
	$database->setQuery( 'ALTER TABLE `#__mt_links` DROP `link_image`, DROP `cust_1`, DROP `cust_2`, DROP `cust_3`, DROP `cust_4`, DROP `cust_5`, DROP `cust_6`, DROP `cust_7`, DROP `cust_8`, DROP `cust_9`, DROP `cust_10`, DROP `cust_11`, DROP `cust_12`, DROP `cust_13`, DROP `cust_14`, DROP `cust_15`, DROP `cust_16`, DROP `cust_17`, DROP `cust_18`, DROP `cust_19`, DROP `cust_20`, DROP `cust_21`, DROP `cust_22`, DROP `cust_23`, DROP `cust_24`, DROP `cust_25`, DROP `cust_26`, DROP `cust_27`, DROP `cust_28`, DROP `cust_29`, DROP `cust_30`;' );
	$database->query();
	
	if(createTable('config', array('`varname` varchar(100) NOT NULL', '`groupname` varchar(50) NOT NULL', '`value` mediumtext NOT NULL', '`default` mediumtext NOT NULL', '`configcode` mediumtext NOT NULL', '`ordering` smallint(6) NOT NULL', '`displayed` smallint(5) unsigned NOT NULL default \'1\'', 'PRIMARY KEY  (`varname`)'),true)) {
		addRows('config',array(
			array('admin_email', 'main', '', 'admin@127.0.0.1', 'text', 500, 1),
			array('admin_use_explorer', 'admin', '', '1', 'yesno', 11500, 1),
			array('allow_changing_cats_in_addlisting', 'listing', '', '1', 'yesno', 3550, 1),
			array('allow_imgupload', 'image', '', '1', 'yesno', 10100, 1),
			array('allow_listings_submission_in_root', 'listing', '', '0', 'yesno', 3500, 1),
			array('allow_owner_rate_own_listing', 'ratingreview', '', '0', 'yesno', 1700, 1),
			array('allow_owner_review_own_listing', 'ratingreview', '', '0', 'yesno', 10005, 1),
			array('allow_rating_during_review', 'ratingreview', '', '1', 'yesno', 2650, 1),
			array('allow_user_assign_more_than_one_category', 'listing', '', '1', 'yesno', 3575, 1),
			array('alpha_index_additional_chars', 'listing', '', '', 'text', 3410, 0),
			array('cat_image_dir', 'image', '', '/components/com_mtree/img/cats/', 'text', 700, 0),
			array('display_empty_cat', 'category', '', '1', 'yesno', 3300, 1),
			array('display_listings_in_root', 'listing', '', '1', 'yesno', 3600, 1),
			array('explorer_tree_level', 'admin', '', '9', 'text', 11600, 1),
			array('fe_num_of_featured', 'listing', '', '20', 'text', 6700, 1),
			array('fe_num_of_links', 'listing', '', '20', 'text', 5500, 1),
			array('fe_num_of_favourite', 'listing', '', '20', 'text', 6100, 1),
			array('fe_num_of_mostrated', 'listing', '', '20', 'text', 6300, 1),
			array('fe_num_of_mostreview', 'listing', '', '20', 'text', 6500, 1),
			array('fe_num_of_new', 'listing', '', '20', 'text', 5800, 1),
			array('fe_num_of_popular', 'listing', '', '20', 'text', 5700, 1),
			array('fe_num_of_updated', 'listing', '', '20', 'text', 6000, 1),
			array('fe_num_of_reviews', 'listing', '', '20', 'text', 5600, 1),
			array('fe_num_of_searchresults', 'listing', '', '20', 'text', 6600, 1),
			array('fe_num_of_toprated', 'listing', '', '20', 'text', 6400, 1),
			array('fe_total_new', 'listing', '', '60', 'text', 5900, 1),
			array('first_cat_order1', 'category', '', 'cat_name', 'cat_order', 1400, 1),
			array('first_cat_order2', 'category', '', 'asc', 'sort_direction', 1500, 1),
			array('first_listing_order1', 'listing', '', 'link_rating', 'listing_order', 1800, 1),
			array('first_listing_order2', 'listing', '', 'desc', 'sort_direction', 1900, 1),
			array('first_review_order1', 'ratingreview', '', 'vote_helpful', 'review_order', 2900, 1),
			array('first_review_order2', 'ratingreview', '', 'desc', 'sort_direction', 3000, 1),
			array('first_search_order1', 'search', '', 'link_featured', 'listing_order', 2150, 1),
			array('first_search_order2', 'search', '', 'desc', 'sort_direction', 2151, 1),
			array('fullmenu_tree_level', 'admin', '', '9', 'text', 11700, 1),
			array('fulltext_search', 'search', '', '0', 'yesno', 2200, 0),
			array('hit_lag', 'main', '', '86400', 'text', 9000, 1),
			array('images_per_listing', 'image', '', '10', 'text', 10200, 1),
			array('img_impath', 'image', '', '', 'text', 1100, 1),
			array('img_netpbmpath', 'image', '', '', 'text', 1200, 1),
			array('language', 'main', '', 'english', 'language', 300, 1),
			array('linkchecker_last_checked', 'linkchecker', '', '', 'text', 300, 0),
			array('linkchecker_num_of_links', 'linkchecker', '', '10', 'text	', 100, 0),
			array('linkchecker_seconds', 'linkchecker', '', '1', 'text', 200, 0),
			array('link_new', 'main', '', '7', 'text', 8800, 1),
			array('link_popular', 'main', '', '120', 'text', 8900, 1),
			array('listing_image_dir', 'image', '', '/components/com_mtree/img/listings/', 'text', 600, 0),
			array('map', 'feature', '', 'googlemaps', 'map', 4150, 1),
			array('min_votes_for_toprated', 'ratingreview', '', '15', 'text', 1500, 1),
			array('min_votes_to_show_rating', 'ratingreview', '', '0', 'text', 1600, 1),
			array('name', 'core', '', 'Mosets Tree', '', 0, 0),
			array('needapproval_addcategory', 'main', '', '1', 'yesno', 8500, 1),
			array('needapproval_addlisting', 'main', '', '1', 'yesno', 8300, 1),
			array('needapproval_addreview', 'ratingreview', '', '1', 'yesno', 2700, 1),
			array('needapproval_modifylisting', 'main', '', '1', 'yesno', 8400, 1),
			array('needapproval_replyreview', 'ratingreview', '', '1', 'yesno', 1200, 1),
			array('note_notify_admin', 'notify', '', '', 'note', 9099, 1),
			array('note_notify_owner', 'notify', '', '', 'note', 9450, 1),
			array('note_rating', 'ratingreview', '', '', 'note', 1000, 1),
			array('note_review', 'ratingreview', '', '', 'note', 2500, 1),
			array('note_rss_custom_fields', 'rss', '', '', 'note', 300, 1),
			array('notifyadmin_delete', 'notify', '', '1', 'yesno', 9300, 1),
			array('notifyadmin_modifylisting', 'notify', '', '1', 'yesno', 9200, 1),
			array('notifyadmin_newlisting', 'notify', '', '1', 'yesno', 9100, 1),
			array('notifyadmin_newreview', 'notify', '', '1', 'yesno', 9400, 1),
			array('notifyuser_approved', 'notify', '', '1', 'yesno', 9700, 1),
			array('notifyuser_modifylisting', 'notify', '', '1', 'yesno', 9600, 1),
			array('notifyuser_newlisting', 'notify', '', '1', 'yesno', 9500, 1),
			array('notifyuser_review_approved', 'notify', '', '1', 'yesno', 9800, 1),
			array('optional_email_sent_to_reviewer', 'ratingreview', '', '', 'note', 10010, 1),
			array('owner_reply_review', 'ratingreview', '', '1', 'yesno', 8000, 1),
			array('params_xml_filename', 'core', '', 'params.xml', 'text', 100, 0),
			array('predefined_reply_1_message', 'ratingreview', '', '', 'predefined_reply', 10120, 1),
			array('predefined_reply_1_title', 'ratingreview', '', '', 'predefined_reply_title', 10110, 1),
			array('predefined_reply_2_message', 'ratingreview', '', '', 'predefined_reply', 10140, 1),
			array('predefined_reply_2_title', 'ratingreview', '', '', 'predefined_reply_title', 10130, 1),
			array('predefined_reply_3_message', 'ratingreview', '', '', 'predefined_reply', 10160, 1),
			array('predefined_reply_3_title', 'ratingreview', '', '', 'predefined_reply_title', 10150, 1),
			array('predefined_reply_4_message', 'ratingreview', '', '', 'predefined_reply', 10180, 1),
			array('predefined_reply_4_title', 'ratingreview', '', '', 'predefined_reply_title', 10170, 1),
			array('predefined_reply_5_message', 'ratingreview', '', '', 'predefined_reply', 10200, 1),
			array('predefined_reply_5_title', 'ratingreview', '', '', 'predefined_reply_title', 10190, 1),
			array('predefined_reply_bcc', 'ratingreview', '', '', 'text', 10040, 1),
			array('predefined_reply_for_approved_or_rejected_review', 'ratingreview', '', '', 'note', 10100, 1),
			array('predefined_reply_from_email', 'ratingreview', '', '', 'text', 10030, 1),
			array('predefined_reply_from_name', 'ratingreview', '', '', 'text', 10020, 1),
			array('rate_once', 'ratingreview', '', '0', 'yesno', 1400, 1),
			array('relative_path_to_js_library', 'core', '', '/components/com_mtree/js/jquery-1.1.3.1.pack.js', 'text', 0, 0),
			array('require_rating_with_review', 'ratingreview', '', '1', 'yesno', 2675, 1),
			array('resize_cat_size', 'image', '', '80', 'text', 1300, 1),
			array('resize_listing_size', 'image', '', '140', 'text', 1000, 1),
			array('resize_medium_listing_size', 'image', '', '600', 'text', 1050, 1),
			array('resize_method', 'image', '', 'gd2', 'resize_method', 800, 1),
			array('resize_quality', 'image', '', '80', 'text', 900, 1),
			array('rss_address', 'rss', '', '0', 'yesno', 400, 1),
			array('rss_cat_name', 'rss', '', '0', 'yesno', 310, 1),
			array('rss_cat_url', 'rss', '', '0', 'yesno', 320, 1),
			array('rss_city', 'rss', '', '0', 'yesno', 500, 1),
			array('rss_country', 'rss', '', '0', 'yesno', 800, 1),
			array('rss_custom_fields', 'rss', '', '', 'text', 1500, 1),
			array('rss_email', 'rss', '', '0', 'yesno', 900, 1),
			array('rss_fax', 'rss', '', '0', 'yesno', 1200, 1),
			array('rss_link_rating', 'rss', '', '0', 'yesno', 340, 1),
			array('rss_link_votes', 'rss', '', '0', 'yesno', 330, 1),
			array('rss_metadesc', 'rss', '', '0', 'yesno', 1400, 1),
			array('rss_metakey', 'rss', '', '0', 'yesno', 1300, 1),
			array('rss_postcode', 'rss', '', '0', 'yesno', 600, 1),
			array('rss_state', 'rss', '', '0', 'yesno', 700, 1),
			array('rss_telephone', 'rss', '', '0', 'yesno', 1100, 1),
			array('rss_website', 'rss', '', '0', 'yesno', 1000, 1),
			array('second_cat_order1', 'category', '', 'cat_name', 'cat_order', 1600, 1),
			array('second_cat_order2', 'category', '', 'desc', 'sort_direction', 1700, 1),
			array('second_listing_order1', 'listing', '', 'link_votes', 'listing_order', 2000, 1),
			array('second_listing_order2', 'listing', '', 'desc', 'sort_direction', 2100, 1),
			array('second_review_order1', 'ratingreview', '', 'vote_total', 'review_order', 4000, 1),
			array('second_review_order2', 'ratingreview', '', 'desc', 'sort_direction', 5000, 1),
			array('second_search_order1', 'search', '', 'link_hits', 'listing_order', 2152, 1),
			array('second_search_order2', 'search', '', 'desc', 'sort_direction', 2153, 1),
			array('show_claim', 'feature', '', '1', 'yesno', 4500, 1),
			array('show_contact', 'feature', '', '1', 'yesno', 4700, 1),
			// array('show_email', 'feature', '', '1', 'yesno', 4400, 1),
			array('show_listnewrss', 'rss', '', '1', 'yesno', 100, 1),
			array('show_listupdatedrss', 'rss', '', '1\n', 'yesno', 200, 1),
			array('show_map', 'feature', '', '0', 'yesno', 4100, 1),
			array('show_ownerlisting', 'feature', '', '1', 'yesno', 4600, 1),
			array('show_print', 'feature', '', '0', 'yesno', 4200, 1),
			array('show_rating', 'ratingreview', '', '1', 'yesno', 1100, 1),
			array('show_recommend', 'feature', '', '1', 'yesno', 5100, 1),
			array('show_report', 'feature', '', '1', 'yesno', 4900, 1),
			array('show_review', 'ratingreview', '', '1', 'yesno', 2600, 1),
			array('show_visit', 'feature', '', '1', 'yesno', 4300, 1),
			array('template', 'main', 'm2', 'm2', 'template', 200, 0),
			array('third_review_order1', 'ratingreview', '', 'rev_date', 'review_order', 6000, 1),
			array('third_review_order2', 'ratingreview', '', 'desc', 'sort_direction', 7000, 1),
			array('trigger_modified_listing', 'listing', '', '', 'text', 3900, 1),
			array('user_addcategory', 'main', '', '1', 'user_access', 8000, 1),
			array('user_addlisting', 'main', '', '1', 'user_access', 7900, 1),
			array('user_allowdelete', 'main', '', '1', 'yesno', 8200, 1),
			array('user_allowmodify', 'main', '', '1', 'yesno', 8100, 1),
			array('user_contact', 'feature', '', '0', 'user_access', 4800, 1),
			array('user_rating', 'ratingreview', '', '1', 'user_access2', 1300, 1),
			array('user_recommend', 'feature', '', '0', 'user_access', 5200, 1),
			array('user_report', 'feature', '', '1', 'user_access', 5000, 1),
			array('user_report_review', 'ratingreview', '', '1', 'user_access', 9000, 1),
			array('user_review', 'ratingreview', '', '1', 'user_access2', 2800, 1),
			array('user_review_once', 'ratingreview', '', '1', 'yesno', 9000, 1),
			array('user_vote_review', 'ratingreview', '', '1', 'yesno', 10000, 1),
			array('use_internal_notes', 'admin', '', '1', 'yesno', 11900, 1),
			array('use_owner_email', 'feature', '', '0', 'yesno', 5300, 1),
			array('use_wysiwyg_editor', 'main', '', '0', 'yesno', 11000, 1),
			array('use_wysiwyg_editor_in_admin', 'admin', '', '0', 'yesno', 12000, 1),
			array('version', 'core', '2.00', '-1', '', 0, 0)
			));
		include( JPATH_COMPONENT_ADMINISTRATOR.DS.'config.mtree.php' );

		$mt_template = 'm2'; // Set default template to m2
		$mt_configs = array('mt_template', 'mt_language', 'mt_map', 'mt_admin_email', 'mt_listing_image_dir', 'mt_cat_image_dir', 'mt_resize_method', 'mt_resize_quality', 'mt_resize_listing_size', 'mt_img_impath', 'mt_img_netpbmpath', 'mt_resize_cat_size', 'mt_first_cat_order1', 'mt_first_cat_order2', 'mt_second_cat_order1', 'mt_second_cat_order2', 'mt_first_listing_order1', 'mt_first_listing_order2', 'mt_second_listing_order1', 'mt_second_listing_order2', 'mt_fulltext_search', 'mt_first_search_order1', 'mt_first_search_order2', 'mt_second_search_order1', 'mt_second_search_order2', 'mt_display_empty_cat', 'mt_display_alpha_index', 'mt_allow_listings_submission_in_root', 'mt_display_listings_in_root', 'mt_display_cat_count_in_root', 'mt_display_listing_count_in_root', 'mt_display_cat_count_in_subcat', 'mt_display_listing_count_in_subcat', 'mt_show_map', 'mt_show_print', 'mt_show_recommend', 'mt_show_rating', 'mt_show_review', 'mt_show_visit', 'mt_show_contact', 'mt_use_owner_email', 'mt_show_report', 'mt_show_claim', 'mt_show_ownerlisting', 'mt_fe_num_of_subcats', 'mt_fe_num_of_chars', 'mt_fe_num_of_links', 'mt_fe_num_of_reviews', 'mt_fe_num_of_popular', 'mt_fe_num_of_new', 'mt_fe_total_new', 'mt_fe_num_of_mostrated', 'mt_fe_num_of_toprated', 'mt_fe_num_of_mostreview', 'mt_fe_num_of_searchresults', 'mt_fe_num_of_featured', 'mt_rate_once', 'mt_min_votes_for_toprated', 'mt_min_votes_to_show_rating', 'mt_user_review_once', 'mt_user_rating', 'mt_user_review', 'mt_user_recommend', 'mt_user_addlisting', 'mt_user_addcategory', 'mt_user_allowmodify', 'mt_user_allowdelete', 'mt_needapproval_addlisting', 'mt_needapproval_modifylisting', 'mt_needapproval_addcategory', 'mt_needapproval_addreview', 'mt_link_new', 'mt_link_popular', 'mt_hit_lag', 'mt_notifyuser_newlisting', 'mt_notifyadmin_newlisting', 'mt_notifyuser_modifylisting', 'mt_notifyadmin_modifylisting', 'mt_notifyadmin_newreview', 'mt_notifyuser_approved', 'mt_notifyuser_review_approved', 'mt_notifyadmin_delete', 'mt_use_internal_notes', 'mt_allow_imgupload', 'mt_admin_use_explorer', 'mt_explorer_tree_level', 'mt_fullmenu_tree_level');
		foreach($mt_configs AS $mt_config) {
			if(isset($$mt_config) && !empty($$mt_config)) {
				$database->setQuery( 'UPDATE #__mt_config SET value = \'' . $$mt_config . '\' WHERE varname = \'' . substr($mt_config,3) . '\' LIMIT 1' );
				$database->query();
			}
		}
		$updated = true;
	}

	if( $updated ) {
		printRow('Mosets Tree has been successfully upgraded to 2.00.',2);
	} else {
		printRow('No update required.',2);
	}
	printEndTable();
}
function addRows($table, $rows) {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	$db_prefix = $mainframe->getCfg('dbprefix');
	
	if(!is_array($rows) || empty($rows) || !isset($rows[0])) {
		return false;
	} else {
		$sql = 'INSERT INTO `#__mt_' . $table . '` VALUES ';
		$value = array();
		if(is_array($rows[0])) {
			foreach($rows AS $row) {
				// echo '<br />Table: '. $table . ' row:' . $row; var_dump($row);
				$values[] = "('" . implode("','",$row) . "')";
			}
		} else {
			$values[] = '(\'' . implode('\',\'',$rows) . '\')';
		}
		$sql .= implode(', ',$values);
		$database->setQuery( $sql );
		if ( $database->query() ) {
			if(is_array($rows[0])) {
				$affected_rows = count($rows);
			} else {
				$affected_rows = 1;
			}
			printRow($affected_rows . ' rows added to table: ' . $db_prefix . 'mt_' . $table);
			return true;
		} else {
			printRow('Error adding rows to table: ' . $db_prefix . 'mt_' . $table . '. Error Message: ' . $database->getErrorMsg(), 0);
			// echo '<pre align="left">' . $database->getQuery() . '</pre>';
			return false;
		}
	}
}

function upgrade15x_157() {
	printStartTable('Upgrade: Mosets Tree 1.5x - 1.57');
	$updated = false;
	if(addColumn('cats', 'cat_show_listings', 'TINYINT UNSIGNED NOT NULL DEFAULT \'1\'', 'cat_allow_submission')) $updated = true;
	if(createTable('claims', array('`claim_id` int(11) NOT NULL auto_increment', '`user_id` int(11) NOT NULL', '`link_id` int(11) NOT NULL', '`comment` mediumtext NOT NULL', '`created` datetime NOT NULL', 'PRIMARY KEY  (`claim_id`)'))) $updated = true;
	if(createTable('reports', array('`report_id` int(11) NOT NULL auto_increment', '`user_id` int(11) NOT NULL', '`guest_name` varchar(255) NOT NULL', '`link_id` int(11) NOT NULL', '`subject` varchar(255) NOT NULL', '`comment` mediumtext NOT NULL', '`created` datetime NOT NULL', 'PRIMARY KEY  (`report_id`)'))) $updated = true;
	if( $updated ) {
		printRow('Mosets Tree has been successfully upgraded to 1.57.',2);
	} else {
		printRow('No update required.',2);
	}
	printEndTable();
}
function upgrade157_158() {
	$updated = false;
	
	printStartTable('Upgrade: Mosets Tree 1.57 - 1.58');
	if(addColumn('reports', 'admin_note', 'MEDIUMTEXT NOT NULL')) $updated = true;
	if(addColumn('claims', 'admin_note', 'MEDIUMTEXT NOT NULL')) $updated = true;
	if(addColumn('reviews', 'admin_note', 'MEDIUMTEXT NOT NULL')) $updated = true;
	if(changeColumnType('reviews', 'rev_date', 'datetime', 'NOT NULL DEFAULT \'0000-00-00 00:00:00\'')) $updated = true;
	if(addIndex('cats', 'func_getPathWay', array('lft', 'rgt', 'cat_id', 'cat_parent'))) $updated = true;
	if(addIndex('links', 'count_listfeatured', array('link_published', 'link_approved', 'link_featured', 'publish_up', 'publish_down', 'link_id'))) $updated = true;
	if(addIndex('links', 'count_viewowner', array('link_published', 'link_approved', 'user_id', 'publish_up', 'publish_down'))) $updated = true;
	if(addIndex('links', 'mylisting', array('user_id', 'link_id'))) $updated = true;

	if( $updated ) {
		printRow('Mosets Tree has been successfully upgraded to 1.58.',2);
	} else {
		printRow('No update required.',2);
	}

	printEndTable();
}

function createTable($table, $create_definitions, $drop_table_if_exists=false, $engine='MyISAM') {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	$db_prefix = $mainframe->getCfg('dbprefix');
	
	$safe_to_create = false;
	
	$database->setQuery( "SHOW TABLE STATUS LIKE '" . $db_prefix . "mt_" . $table . "'" );
	$database->query();
	if($database->getNumRows() == 1) {
		$table_exists = true;
	} else {
		$table_exists = false;
	}
	if($drop_table_if_exists && $table_exists) {
		$database->setQuery( "DROP TABLE `" . $db_prefix . "mt_" . $table . "`" );
		$database->query();
		$safe_to_create = true;
	} elseif(!$table_exists) {
		$safe_to_create = true;
	}
	if($safe_to_create && count($create_definitions) > 0) {
		$sql = 'CREATE TABLE `#__mt_' . $table . '` (';
		$sql .= implode(',',$create_definitions);
		$sql .= ')';
		if(!empty($engine)) {
			$sql .= ' ENGINE=' . $engine . ';';
		}
		$database->setQuery( $sql );
		if ( $database->query() ) {
			printRow('Created table: ' . $db_prefix . 'mt_' . $table);
			return true;
		} else {
			printRow( $database->getErrorMsg(), -1);
			return false;
		}
		// echo '<pre align="left">' . $database->getQuery() . '</pre>';
	} else {
		printRow('table: ' . $db_prefix . 'mt_' . $table . ' already exists.', 0);
		return false;
	}
	return false;
}
function changeColumnType($table, $column_name, $new_column_data_type, $new_column_definition) {
	$database =& JFactory::getDBO();

	$database->setQuery( 'DESCRIBE #__mt_' . $table . ' ' . $column_name );
	$tmp = $database->loadObject();
	if( strtolower($tmp->Type) <> strtolower($new_column_data_type) ) {
		$database->setQuery( "ALTER TABLE #__mt_" . $table . " CHANGE `" . $column_name . "` `" . $column_name . "` " . strtoupper($new_column_data_type) . " " . $new_column_definition );
		if ( $database->query() ) {
			printRow('Updated column:' . $column_name . ' to ' . strtoupper($new_column_data_type) . ' type.');
			return true;
		} else {
			printRow( $database->getErrorMsg(), -1);
			return false;
		}
	} else {
		printRow('Skipped column modification:' . $column_name . ' appears to be using the new column type and column definition.', 0);
		return false;
	}
}

function addIndex($table, $index_name, $fields) {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	$db_prefix = $mainframe->getCfg('dbprefix');

	$database->setQuery( 'SHOW INDEX FROM #__mt_' . $table . ' WHERE Key_name = "' . $index_name . '" ' );
	$tmp = $database->loadObjectList();
	if( count($tmp) == 0 && count($fields) > 0 ) {
		$database->setQuery( 'ALTER TABLE #__mt_' . $table . ' ADD INDEX `' . $index_name . '` ( `' . implode('` , `',$fields) . '` )' );
		if ( $database->query() ) {
			printRow('Added index:' . $index_name . ' to table: ' . $db_prefix . 'mt_' . $table );
			return true;
		} else {
			printRow($database->getErrorMsg(). -1);
			return false;
		}
	} else {
		printRow('Skipped index insertion:' . $index_name . ' already exists.', 0 );
		return false;
	}
}
function addColumn($table, $column_name, $column_info='', $after='') {
	global $mainframe;
	
	$database =& JFactory::getDBO();
	$db_prefix = $mainframe->getCfg('dbprefix');

	$database->setQuery( 'SHOW COLUMNS FROM #__mt_' . $table . ' LIKE "' . $column_name . '"' );
	$tmp = $database->loadResult();
	if ( $tmp == $column_name ) {
		printRow('Skipped column:' . $column_name . ' already exists.', 0 );
		return false;
	} else {
		$sql = 'ALTER TABLE #__mt_' . $table . ' ADD `' . $column_name . '` ' . $column_info;
		if(!empty($after)) {
			$sql .= ' AFTER `' . $after .'`';
		}
		$database->setQuery( $sql );
		if( $database->query() ) {
			printRow('Added column:' . $column_name . ' to table: ' . $db_prefix . 'mt_' . $table );
			return true;
		} else {
			printRow($database->getErrorMsg(). -1);
			return false;
		}
	}
}
function printRow( $msg, $status=1 ) {
	if( $status == 1 OR $status == 0 ) {
		echo '<tr><td><b>'.(($status)?'<span style="color:green">OK</span>':'Skipped').'</b> - '.$msg.'</td></tr>';
	} elseif( $status == 2 ) {
		echo '<tr><td><strong>'.$msg.'</strong></td></tr>';
	}
}

function printStartTable($header='') {
	echo '<table class="adminform">';
	if(!empty($header)) {
		echo '<tr><th>' . $header . '</th></tr>';
	}
}

function printEndTable() {
	echo '</table>';	
}

class mUpgrade {
	var $updated = false;
	function updated() {
		return $this->updated;
	}
	function addColumn($table, $column_name, $column_info='', $after='') {
		if(addColumn($table, $column_name, $column_info, $after)) {
			$this->updated = true;
		}
	}
	function addRows($table, $rows) {
		if(addRows($table, $rows)) {
			$this->updated = true;
		}	
	}
	function printStatus( $msg, $status=1 ) {
		if( $status == -1 ) {
			echo '<tr><td><b><span style="color:red">Error</span></b> - '.$msg.'</td></tr>';
		} elseif( $status == 1 OR $status == 0 ) {
			echo '<tr><td><b>'.(($status)?'<span style="color:green">OK</span>':'Skipped').'</b> - '.$msg.'</td></tr>';
		} elseif( $status == 2 ) {
			echo '<tr><td><strong>'.$msg.'</strong></td></tr>';
		}
	}
}
?>