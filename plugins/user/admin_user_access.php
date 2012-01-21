<?php
/**
* @package plugin Admin-User-Access (user plugin for component Admin-User-Access)
* @version 1.2.0
* @copyright Copyright (C) 2008 Carsten Engel. All rights reserved.
* @license GPL versions free/trail/pro 
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

//no direct access
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgUserAdmin_user_access extends JPlugin {

	function plgUserAdmin_user_access(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}	
	
	function onAfterStoreUser($user, $isnew, $success, $msg)
	{
		global $mainframe;	
		
		//get database
		$database = JFactory::getDBO();	
		
		//get default usergroup from config
		$database->setQuery("SELECT config "
		."FROM #__pi_aua_config "
		."WHERE id='aua' "
		."LIMIT 1"
		);
		$temp = $database->loadObjectList();
		$temp = $temp[0];
		$raw = $temp->config;
		$pos_start = strpos($raw, 'default_usergroup=');							
		$string_start = substr($raw, $pos_start, 100);
		$pos_end = strpos($string_start, 'display_pagesaccess=');
		$default_usergroup = substr($string_start, 18, $pos_end-18);
		$default_usergroup = intval($default_usergroup);
				
		if ($isnew && $default_usergroup!=0)
		{			
			$user_id = $user['id'];					
			$database->setQuery( "INSERT INTO #__pi_aua_userindex SET user_id='$user_id', group_id='$default_usergroup' ");
			$database->query();
		}		
	}	
	
	function onAfterDeleteUser($user, $succes, $msg)
	{
		global $mainframe;
		
		$user_id = $user['id'];
		$database = JFactory::getDBO();	
		$database->setQuery("DELETE FROM #__pi_aua_userindex WHERE user_id='$user_id'");
		$database->query();		 	
	}	
	
	function onLoginUser($user, $options){
	
		$app = JFactory::getApplication();	
		
		if($app->isAdmin()){
			//only do this at the backend login
			
			$user =& JFactory::getUser();
			$user_type = $user->get('usertype');
			$user_id = $user->get('id');
		
			$pi_ua_config = $this->get_config();
			
			$database = JFactory::getDBO();	
			
			//get usergroup
			$group_id = '';
			$database->setQuery("SELECT group_id FROM #__pi_aua_userindex WHERE user_id='$user_id' LIMIT 1 ");
			$rows = $database->loadObjectList();
			foreach($rows as $row){	
				$group_id = $row->group_id;	
			}
			
			//check if the user's group has a redirect url			
			$group_redirect_url = '';
			if($group_id!=''){
				$database->setQuery("SELECT url FROM #__pi_aua_usergroups WHERE id='$group_id' LIMIT 1 ");
				$rows = $database->loadObjectList();
				foreach($rows as $row){	
					$group_redirect_url = $row->url;	
				}
			}
			
			//if there is a group redirect, do that. if not do general redirect
			if($group_redirect_url){
				if($user_type!='Super Administrator'){
					global $mainframe;	
					$mainframe->redirect($group_redirect_url);
				}
			}else{		
				//no group-redirect, so check if a general redirect is needed		
				if(isset($pi_ua_config['redirect_on_login_backend'])){			
					if($pi_ua_config['redirect_on_login_backend'] && $pi_ua_config['redirect_on_login_backend_url']!=''){
						if($user_type!='Super Administrator' || ($user_type=='Super Administrator' && $pi_ua_config['redirect_also_super_admins']) ){
							$url = $pi_ua_config['redirect_on_login_backend_url'];
							global $mainframe;	
							$mainframe->redirect($url);
						}
					}
				}
			}
				
		}		
		return true;
	}
	
	function get_config(){	
			
		$database = JFactory::getDBO();			
		
		$database->setQuery("SELECT config "
		."FROM #__pi_aua_config "
		."WHERE id='aua' "
		."LIMIT 1"
		);		
		$raw = $database->loadResult();		
		
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
		
		//reformat redirect urls		
		$config['redirect_on_login_backend_url'] = str_replace('[equal]','=',$config['redirect_on_login_backend_url']);	
				
		return $config;			
	}
}
?>