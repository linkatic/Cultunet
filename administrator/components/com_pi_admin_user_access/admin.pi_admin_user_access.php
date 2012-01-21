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

//globals
global $task, $sub_task, $my, $database;

//get vars
if( defined('_JEXEC') ){
	//joomla 1.5
	$task = JRequest::getVar('task', '');
	$sub_task = JRequest::getVar('sub_task', '');
}else{
	//joomla 1.0.x
	$task = mosGetParam( $_REQUEST, 'task', '' );
	$sub_task = mosGetParam( $_REQUEST, 'sub_task', '' );
}

//include class
require_once(dirname(__FILE__).'/class.php');
$class_ua = new class_ua();		

//redirect if trailversion is expired
if(!$class_ua->aua_check_trail_version() && $task!='expired'){			
	$class_ua->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=expired");			
}

//set default
if(!$task){	
	$url = 'index2.php?option=com_pi_admin_user_access&task='.$class_ua->ua_config['default_tab'];
	if(defined('_JEXEC')){
		//joomla 1.5
		$mainframe->redirect($url);
	}else{
		//joomla 1.0.x
		mosRedirect($url);
	}	
}

//do function
$task_functions_array = array('access_components_save','access_pages_save','users_save','usergroup_save','usergroup_delete','actions_save','access_itemtypes_save','access_items_save','access_sections_save','access_categories_save','config_save','toolbars_save','unlock_item','modules_save','plugins_save');
if (in_array($task, $task_functions_array)){
	$class_ua->$task();	
}

//get admin page
$class_ua->set_title();
require_once(dirname(__FILE__).'/admin/'.$task.'.php');


?>