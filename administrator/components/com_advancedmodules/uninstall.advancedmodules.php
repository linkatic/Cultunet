<?php
/**
 * Uninstallation File
 * Performs some extra tasks when uninstalling the component
 *
 * @package     Advanced Module Manager
 * @version     1.9.7
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright (C) 2010 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Delete plugin files
if ( is_file( JPATH_PLUGINS.DS.'system'.DS.'advancedmodules.php' ) ) {
	JFile::delete( JPATH_PLUGINS.DS.'system'.DS.'advancedmodules.php' );
}
if ( is_file( JPATH_PLUGINS.DS.'system'.DS.'advancedmodules.xml' ) ) {
	JFile::delete( JPATH_PLUGINS.DS.'system'.DS.'advancedmodules.xml' );
}

// Delete plugin language files
$_file_orginal_lang_path = JPATH_ADMINISTRATOR.DS.'language';
$_dir_folders = JFolder::folders( $_file_orginal_lang_path );
foreach ( $_dir_folders as $_lang_name ) {
	$_file_lang_file = $_file_orginal_lang_path.DS.$_lang_name.DS.$_lang_name.'.plg_system_advancedmodules.ini';
	if ( is_file( $_file_lang_file ) ) {
		JFile::delete( $_file_lang_file );
	}
}