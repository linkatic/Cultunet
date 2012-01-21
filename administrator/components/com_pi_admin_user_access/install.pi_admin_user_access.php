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

function com_install(){
	global $database, $mosConfig_db, $mosConfig_dbprefix, $mainframe;	
	
	
	
	if( defined('_JEXEC') ){
		//joomla 1.5
		$database = JFactory::getDBO();
	}		
	
	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_access_components (
  `id` int(11) NOT NULL auto_increment,
  `component_usergroupid` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
)");
	$database->query();

//take this out for free version	
	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_items (
  `id` int(11) NOT NULL auto_increment,
  `itemid_usergroupid` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
)");
	$database->query();
	
	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_access_pages (
  `id` int(11) NOT NULL auto_increment,
  `pageid_usergroupid` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
)");
	$database->query();
	
//take this out for free version
	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_actions (
  `id` int(11) NOT NULL auto_increment,
  `action_usergroupid` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
)");
	$database->query();

//take this out for free version
	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_itemtypes (
   `id` int(11) NOT NULL auto_increment,
  `type_groupid` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
)");
	$database->query();

//take this out for free version
$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_sections (
   `id` int(11) NOT NULL auto_increment,
  `section_groupid` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
)");
	$database->query();
	
	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_categories (
   `id` int(11) NOT NULL auto_increment,
  `category_groupid` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
)");
	$database->query();

//take this out for free version	
	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_modules (
   `id` int(11) NOT NULL auto_increment,
  `module_groupid` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
)");
	$database->query();

//take this out for free version	
	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_plugins (
   `id` int(11) NOT NULL auto_increment,
  `plugin_groupid` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
)");
	$database->query();

	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_usergroups (
	   `id` int(11) NOT NULL auto_increment,
  `name` tinytext NOT NULL,
  `email` text NOT NULL,
  `ua_toolbar` tinyint(1) NOT NULL default '0',
  `j_toolbar` tinyint(1) NOT NULL default '0',
  `extra` tinytext NOT NULL,
  `description` mediumtext NOT NULL,
  `url` TINYTEXT NOT NULL,
  PRIMARY KEY  (`id`)
	)");
		$database->query();
		
	$database->setQuery("SHOW COLUMNS FROM #__pi_aua_usergroups");
	$columns = $database->loadResultArray();	
	if(!in_array('description', $columns)){
		$database->setQuery("ALTER TABLE #__pi_aua_usergroups ADD `description` MEDIUMTEXT NOT NULL AFTER `extra`");
		$database->query();
	}				
	if(!in_array('url', $columns)){
		$database->setQuery("ALTER TABLE #__pi_aua_usergroups ADD `url` TINYTEXT NOT NULL ");	
		$database->query();		
	}
		
		$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_userindex (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
)");
$database->query();

if( defined('_JEXEC') ){
	//joomla 1.5
	$icon_path = 'components';
}else{
	//joomla 1.0.x
	$icon_path = '../administrator/components';
}

	//do icon
	$database->setQuery("UPDATE #__components SET admin_menu_img='$icon_path/com_pi_admin_user_access/images/icon.gif' WHERE link='option=com_pi_admin_user_access'");
	$database->query();


	//table for configuration
	$database->setQuery("CREATE TABLE IF NOT EXISTS #__pi_aua_config (
  `id` varchar(255) NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY  (`id`)  
)");
	$database->query();

	//check if config is empty, if so insert default config
	$database->setQuery("SELECT id FROM #__pi_aua_config WHERE id='aua' ");
	$rows = $database -> loadObjectList();
	$aua_config = '';
	if(count($rows) > 0){
		$row = $rows[0];
		$aua_config = $row->id;
	}
	
	if($aua_config==''){		
		$configuration = 'language=english
default_tab=usergroups
redirect_to_pi=false
use_toolbar=true
display_usergroups=true
display_users=true
default_usergroup=
display_pagesaccess=true
active_pagesaccess=false
inherit_rights_parent_page=true
display_itemtypes=true			
active_itemtypes=false
display_items=true
active_items=false			
display_itemtype_in_list=false			
display_sections=true
active_sections=false
display_categories=true
active_categories=false
display_actions=true
active_actions=false
display_components=true
display_toolbars=true
show_joomla_group=true
disable_joomla_group_selectbox=false
item_inherits_access=no_default_has_access	
com_content_access=category_access
activate_modules=false
display_modules=true
activate_plugins=false
display_plugins=true
activate_toolbars=false
display_toolbar_superadmin=true
page_props=true	
item_props=true	
menutypes=mainmenu;Main Menu
dropdown_buttons=2;media,4;community
extra_buttons=			
notify_from_address=no-reply@pages-and-items.com
notify_from_name=	
use_componentaccess=false
components=com_poll;Polls;com_poll;0,com_pi_admin_user_access;Admin User Access;com_pi_admin_user_access;0,com_banners;Banners;com_banners;2,com_media;Media Manager;com_media;2,com_trash;Trash manager;com_trash;0
activate_module_list_redirect=
module_list_redirect_url=index.php
redirect_on_login_backend=false
redirect_on_login_backend_url=index.php
redirect_also_super_admins=false
extra_buttons_super_admin=true
';

		//insert fresh config
		$database->setQuery( "INSERT INTO #__pi_aua_config SET id='aua', config='$configuration'");
		$database->query();
	}
	
	//get config		
	$database->setQuery("SELECT config "
	."FROM #__pi_aua_config "
	."WHERE id='aua' "
	."LIMIT 1"
	);		
	$raw = $database->loadResult();		
	
	$updated_config = $raw;
			
	//check for missing config vars
	$config_needs_updating = 0;	
	
	//added in version 2.0.7
	if(!strpos($raw, 'activate_module_list_redirect')){
		$updated_config = $raw.'
activate_module_list_redirect=
module_list_redirect_url=index.php
';
		$config_needs_updating = 1;
	}
	
	//added in version 2.0.8
	if(!strpos($raw, 'redirect_on_login_backend')){
		//check if redirect to PI is on, if so we reformat this to the new url-method
		$redirect_temp = 'false';
		$redirect_url_temp = '';
		if(strpos($raw, 'redirect_to_pi=true')){
			$redirect_temp = 'true';
			$redirect_url_temp = 'index2.php?option[equal]com_pi_pages_and_items';
		}
		$updated_config = $raw.'
redirect_on_login_backend='.$redirect_temp.'
redirect_on_login_backend_url='.$redirect_url_temp.'
redirect_also_super_admins=false
';
		$config_needs_updating = 1;
	}
	
	//added in version 2.3.1
	if(!strpos($raw, 'extra_buttons_super_admin')){
		$updated_config = $raw.'
extra_buttons_super_admin=
';
		$config_needs_updating = 1;
	}
	
	if($config_needs_updating){
		$database->setQuery( "UPDATE #__pi_aua_config SET config='$updated_config' WHERE id='aua' ");
		$database->query();
	}	
	
	//delete the old frontend module
	//files	
	jimport( 'joomla.filesystem.folder' );
	$path = JPATH_ROOT.DS.'modules'.DS.'mod_admin_user_access_frontend'.DS;	
	if(file_exists($path.'mod_admin_user_access_frontend.php')){
		JFile::delete($path.'mod_admin_user_access_frontend.php');
	}	
	if(file_exists($path.'mod_admin_user_access_frontend.xml')){
		JFile::delete($path.'mod_admin_user_access_frontend.xml');
	}
	if(file_exists($path.'mod_admin_user_access_frontend2.php')){
		JFile::delete($path.'mod_admin_user_access_frontend2.php');
	}	
	//database
	$database->setQuery("DELETE FROM #__modules WHERE module='mod_admin_user_access_frontend' ");
    $database->query();
	
}

?>
<div style="width: 800px; text-align: left; background: url(components/com_pi_admin_user_access/images/icon.png) 10px 0 no-repeat;">
	<h2 style="padding: 10px 0 10px 70px;">Admin-User-Access</h2>	
	<p>
		Thank you for using Admin-User-Access.
	</p>
	<p>
		In order for component Pages-and-Items to work with component Admin-User-Access, enable this in the <a href="index2.php?option=com_pi_pages_and_items&task=config&tab=admin-user-access">Pages-and-Items configuration</a>.
	</p>
	<p>
		Check <a href="http://www.pages-and-items.com" target="_blank">www.pages-and-items.com</a> for:
		<ul>
			<li>updates</li>
			<li>support</li>
			<li>documentation</li>	
			<li>email notification service for updates and new extensions</li>	
			<li><a href="http://www.pages-and-items.com/extensions/admin-user-access/update-notifications-for-admin-user-access" target="_blank">RSS notification service for updates of this extension</a></li>		
		</ul>
	</p>
	<p>
		Follow us on <a href="http://twitter.com/PagesAndItems" target="_blank">twitter</a> (update notifications)		
	</p>
</div>