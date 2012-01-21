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

jimport( 'joomla.plugin.plugin' );

class plgSystemAdminuseraccess extends JPlugin{	
	
	function plgSystemAdminuseraccess(& $subject, $config){
		parent::__construct($subject, $config);				
	}
	
	function get_config(){
		$database = JFactory::getDBO();
		$database->setQuery("SELECT config "
		."FROM #__pi_aua_config "
		."WHERE id='aua' "
		."LIMIT 1"
		);
		$temp = $database->loadObjectList();
		$temp = $temp[0];
		$raw = $temp->config;	
		
		$params = explode( "\n", $raw);
		
		for($n = 0; $n < count($params); $n++){		
			$temp = explode('=',$params[$n]);
			$var = $temp[0];
			$value = '';
			if(count($temp)==2){
				$value = trim($temp[1]);
				if($value=='false'){
					$value = false;
				}
				if($value=='true'){
					$value = true;
				}
			}							
			$config[$var] = $value;	
		}	
		return $config;			
	}
	
	function onAfterRender(){
	
		//make sure all this only gets doen once
		if(!defined('_ADMIN_USER_ACCESS_SYSTEM_PLUGIN')){			
			define( '_ADMIN_USER_ACCESS_SYSTEM_PLUGIN', 1 );
		
		
			$app = JFactory::getApplication();
			if(!$app->isAdmin()){
				//do frontend checking
				require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_pi_admin_user_access'.DS.'plugin_system'.DS.'old_frontend_module.php');
				$class_mod_aua = new class_mod_aua;
				$class_mod_aua->init();
				$aua_config = $this->get_config();
				$class_mod_aua->mod_admin_user_access($aua_config);					
			}
			
		}
	}	
		
}

?>