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

class class_ua{

	var $ua_config;	
	var $task;	
	var $pi_installed = false;
	var $module_installed = false;
	var $module_published = false;
	var $has_usergroups = false;
	var $section_access = false;
	var $db;
	var $user_type;
	var $page_access_rights;
	var $category_access_rights;
	var $workflow_rights;
	var $item_access_rights;	
	var $pi_config;
	var $aua_demo_seconds_left;
	var $aua_module_position = false;
	var $version = '2.3.1';
	var $plugin_system_installed = false;
	var $plugin_system_enabled = false;
	var $itemtypes = array();	
	var $aua_version_type = 'free';	
	
	//constructor
	function class_ua(){
		global $task, $database, $my;
		
		//get database
		if( defined('_JEXEC') ){
			//joomla 1.5
			$this->db = JFactory::getDBO();
		}else{
			//joomla 1.0.x
			$this->db = $database;
		}	
		
		//get config
		$this->ua_config = $this->get_config();					
		$this->task = $task;
		$this->get_language();		
		
		//reformat array menutypes
		$menutypes = explode(',',$this->ua_config['menutypes']);
		$temp = array();
		if($this->ua_config['menutypes']!=''){		
			for($n = 0; $n < count($menutypes); $n++){			
				$menutype = explode(';',$menutypes[$n]);			
				$menutype_array = array($menutype[0],$menutype[1]);
				array_push($temp,$menutype_array);
			}
		}else{
			$temp = array(array('',''));
		}		
		$this->ua_config['menutypes'] = $temp;			
		
		//reformat array dropdown_buttons
		$dropdown_buttons = explode(',',$this->ua_config['dropdown_buttons']);
		$temp = array();		
		for($n = 0; $n < count($dropdown_buttons); $n++){			
			$dropdown_button = explode(';',$dropdown_buttons[$n]);			
			$dropdown_button_array = array($dropdown_button[0],$dropdown_button[1]);
			array_push($temp,$dropdown_button_array);
		}	
		$this->ua_config['dropdown_buttons'] = $temp;		
		
		//reformat array extra_buttons	
		$temp_extra_buttons = $this->ua_config['extra_buttons'];		
		$temp_extra_buttons_array = explode('-||-', $temp_extra_buttons);			
		if(count($temp_extra_buttons_array)==1){
			$temp_extra_buttons_array = array($temp_extra_buttons);
		}	
		$temp_extra_buttons_array = str_replace('[newline]','
',$temp_extra_buttons_array);
		$temp_extra_buttons_array = str_replace('[equal]','=',$temp_extra_buttons_array);
		$this->ua_config['extra_buttons'] = $temp_extra_buttons_array;
		
		//reformat array components
		$components = explode(',',$this->ua_config['components']);		
		$temp = array();		
		for($n = 0; $n < count($components); $n++){			
			$component = explode(';',$components[$n]);
			$component = str_replace('[newline]','
',$component);
			$component = str_replace('[equal]','=',$component);	
			if(isset($component[4])){
				$component_array = array($component[0],$component[1],$component[2],$component[3],$component[4]);
			}else{
				$component_array = array($component[0],$component[1],$component[2],$component[3],'nothing');
			}
			array_push($temp,$component_array);
		}	
		$this->ua_config['components'] = $temp;				
		
		//check if pi installed
		if(file_exists(dirname(__FILE__).'/../com_pi_pages_and_items/admin.pi_pages_and_items.php')){
			$this->pi_installed = true;			
			$this->pi_config = $this->get_config_pages_and_items();
			//itemtypes
			$temp_itemtypes = explode(',',$this->pi_config['itemtypes']);		
			for($n = 0; $n < count($temp_itemtypes); $n++){				
				array_push($this->itemtypes,$temp_itemtypes[$n]);			
			}
			$this->pi_config['itemtypes'] = $this->itemtypes;
		}
		
		//check if modules are installed
		if( defined('_JEXEC') ){
			//joomla 1.5
			$mod_dir = 'modules/mod_admin_user_access_backend';
		}else{
			//joomla 1.0.x
			$mod_dir = 'modules';
		}
		if(file_exists(dirname(__FILE__).'/../../'.$mod_dir.'/mod_admin_user_access_backend.php')){
			$this->module_installed = true;	
			//check if mudule is published
			$this->db->setQuery("SELECT published FROM #__modules WHERE module='mod_admin_user_access_backend' LIMIT 1");
			$rows = $this->db->loadObjectList();
			$row = $rows[0];						
			if($row->published==1){
				$this->module_published = true;
			}		
		}
		
		//check if plugin is installed and published
		$this->db->setQuery("SELECT published "
		."FROM #__plugins "
		."WHERE element='adminuseraccess' AND folder='system' "
		."LIMIT 1"					
		);
		$rows = $this->db->loadObjectList();					
		foreach($rows as $row){	
			$this->plugin_system_installed = 1;
			$this->plugin_system_enabled = $row->published;
		}	
		
		//get module position
		$this->db->setQuery("SELECT position FROM #__modules WHERE module='mod_admin_user_access_backend' LIMIT 1");
		$positions = $this->db->loadResultArray();
		if($positions){	
			$position = $positions[0];
		}else{
			$position = 'none';
		}		
		
		//check if module is published to right position
		if(defined('_JEXEC')){
			//joomla 1.5
			$correct_position = 'menu';
		}else{
			//joomla 1.0.x
			$correct_position = 'header';
		}
		if($position==$correct_position){
			$this->aua_module_position = true;			
		}
		
		//check for usergroups
		$usergroups = false;
		$this->db->setQuery("SELECT id FROM #__pi_aua_usergroups LIMIT 1");
		$rows = $this->db->loadObjectList();		
		if($rows){
			$this->has_usergroups = true;
		}else{
			$this->has_usergroups = false;
		}		
		
		//if page-access is used, get them
		if($this->ua_config['active_categories']){
			//get categoryaccess
			$this->db->setQuery("SELECT category_groupid FROM #__pi_aua_categories ");
			$this->category_access_rights = $this->db->loadResultArray();			
		}
		
		//if category-access is used, get them
		if($this->ua_config['active_pagesaccess']){
			//get pagesaccess
			$this->db->setQuery("SELECT pageid_usergroupid FROM #__pi_aua_access_pages ");
			$this->page_access_rights = $this->db->loadResultArray();			
		}			
		
		//set var user_type
		if(defined('_JEXEC')){
			//joomla 1.5
			$user =& JFactory::getUser();		
			$this->user_type = $user->get('usertype');
		}else{
			//joomla 1.0.x
			$this->user_type = $my->usertype;	
		}				
	}
	
	function get_var($name, $default = null, $hash = 'default', $type = 'none', $mask = 0){	
		//make sure there is no $type
		if($type!='none' && $type!=''){			
			exit('don\'t use $type, it won\'t work in older versions');
		}
		if( defined('_JEXEC') ){
			//joomla 1.5
			$var = JRequest::getVar($name, $default, $hash, $type, $mask);
		}else{
			//joomla 1.0.x
			//do the thing with hash (cake anyone?)
			$hash = strtolower($hash);			
			switch ($hash) {
			case 'default':
				$hash = $_REQUEST;
				break;
			case 'get':
				$hash = $_GET;
				break;
			case 'post':
				$hash = $_POST;
				break;
			case 'files':
				exit('don\'t use FILES, it won\'t work in older versions');
				break;
			case 'method':
				exit('don\'t use METHOD, it won\'t work in older versions');
				break;
			}			
					
			$var = mosGetParam($hash, $name, $default, $mask);
		}
		return $var;
	}		
	
	function echo_header(){	
	
		$this->check_demo_time_left();
				   
		if($this->user_type=='Super Administrator'){
			echo '<div id="config_link"><a href="index2.php?option=com_pi_admin_user_access&amp;task=config">'._pi_ua_lang_config.'</a></div>'."\n";
		}
		
		echo '<script src="../includes/js/overlib_mini.js" language="JavaScript" type="text/javascript"></script>'."\n";
		echo '<link href="components/com_pi_admin_user_access/css/pi_admin_user_access3.css" rel="stylesheet" type="text/css" />'."\n";
				
		echo '<ul id="aua_menu">';	
		
		if($this->ua_config['display_usergroups']){		
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=usergroups"';
			if($this->task=='usergroups' || $this->task=='usergroup'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_usergroups.'</span></a></li>';
		}
		
		if($this->ua_config['display_users']){	
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=users"';
			if($this->task=='users' || $this->task=='user'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_users.'</span></a></li>';
		}
		
		if($this->ua_config['display_pagesaccess']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=access_pages"';
			if($this->task=='access_pages'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_page_access.'</span></a></li>';
		}		
		
		if($this->ua_config['display_itemtypes']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=access_itemtypes"';
			if($this->task=='access_itemtypes'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_itemtype_access.'</span></a></li>';
		}
		
		if($this->ua_config['display_items']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=items"';
			if($this->task=='items'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_item_access.'</span></a></li>';
		}		
		
		if($this->ua_config['display_categories']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=categories"';
			if($this->task=='categories'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_categories.'</span></a></li>';
		}
		
		if($this->ua_config['display_sections']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=sections"';
			if($this->task=='sections'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_sections.'</span></a></li>';
		}
				
		if($this->ua_config['display_actions']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=actions"';
			if($this->task=='actions'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_actions.'</span></a></li>';
		}
		
		if($this->ua_config['display_components']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=access_components"';
			if($this->task=='access_components'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_component_access.'</span></a></li>';
		}
		
		if($this->ua_config['display_modules']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=modules"';
			if($this->task=='modules'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_module_access.'</span></a></li>';
		}
		
		if($this->ua_config['display_plugins']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=plugins"';
			if($this->task=='plugins'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_plugin_access.'</span></a></li>';
		}
		
		if($this->ua_config['display_toolbars']){
			echo '<li><a href="index2.php?option=com_pi_admin_user_access&amp;task=toolbars"';
			if($this->task=='toolbars'){
				echo 'class="on"';
			}
			echo '><span>'._pi_ua_lang_displaytoolbar.'</span></a></li>';
		}
			
		echo '</ul>'."\n";	
		
		//message if pi_com_pages_and_items is not installed				
		if(!$this->pi_installed && ($this->task=='access_itemtypes')){
			echo '<div style="color: red; text-align: left;">'._pi_ua_lang_pinotinstalled.'<br/><br/></div>';
		}
		
		//message if there are no usergroups
		if(!$this->has_usergroups && $this->task!='usergroups' && $this->task!='usergroup'){
			echo '<div style="color: red; text-align: left;">'._pi_ua_lang_warning.' '._pi_ua_lang_nousergroups.'<br/><br/></div>';
		}
		
		//message if page access is not activated
		if($this->task=='access_pages'){	
			if($this->ua_config['active_pagesaccess']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_nopagesactive.'.<br/><br/></div>';
			}	
		}
		
		//message if itemtype access is not activated
		if($this->task=='access_itemtypes'){	
			if($this->ua_config['active_itemtypes']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_noitemtypeactive.'.<br/><br/></div>';
			}	
		}
		
		//message if item access is not activated
		if($this->task=='items'){	
			if($this->ua_config['active_items']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_noitemaccessactive.'.<br/><br/></div>';
			}	
		}
		
		//message if section access is not activated
		if($this->task=='sections'){	
			if($this->ua_config['active_sections']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_nosectionsactive.'.<br/><br/></div>';
			}	
		}
		
		//message if category access is not activated
		if($this->task=='categories'){	
			if($this->ua_config['active_categories']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_no_categories_active.'.<br/><br/></div>';
			}				
		}
		
		//message if workflow is not activated
		if($this->task=='actions'){	
			if($this->ua_config['active_actions']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_noworkflowactive.'<br/><br/></div>';
			}	
		}
		
		//message if component access is not activated
		if($this->task=='access_components'){	
			if($this->ua_config['use_componentaccess']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_nocomponentactive.'<br/><br/></div>';
			}	
		}
		
		//message if module access is not activated
		if($this->task=='modules'){	
			if($this->ua_config['activate_modules']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_modules_not_active.'<br/><br/></div>';
			}	
		}
		
		//message if plugin access is not activated
		if($this->task=='plugins'){	
			if($this->ua_config['activate_plugins']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_plugins_not_active.'<br/><br/></div>';
			}	
		}
		
		//message if toolbar access is not activated
		if($this->task=='toolbars'){	
			if($this->ua_config['activate_toolbars']==false){				
				echo '<div style="color: red; text-align: left;">'._pi_ua_lang_no_toolbars_active.'<br/><br/></div>';
			}	
		}			
		
		//message if module is not installed	
		if(!$this->module_installed){				
			echo '<div style="color: red; text-align: left;">'._pi_ua_lang_modnotinstalled.'<br/><br/></div>';
		}
		
		//message if module is not published	
		if(!$this->module_published){				
			echo '<div style="color: red; text-align: left;">'._pi_ua_lang_modnotpublished.'<br/><br/></div>';
		}
		
		//message if module is not published to right position	
		if(!$this->aua_module_position){				
			echo '<div style="color: red; text-align: left;">'._pi_ua_lang_modnotpublished_to_right_position.'<br/><br/></div>';
		}
		
		//message if system plugin is not installed
		if(!$this->plugin_system_installed){			
			echo '<div style="color: red; text-align: left;">'._pi_ua_lang_botnotinstalled.' (system)<br/><br/></div>';
		}
		
		//message if system plugin is not enabled
		if(!$this->plugin_system_enabled){
			echo '<div style="color: red; text-align: left;">'._pi_ua_lang_botnotpublished.' (system)<br/><br/></div>';
		}
				
	}		
	
	function usergroup_save(){			
		
		//get vars
		$id = JRequest::getVar('id', 0, 'post');
		$name = JRequest::getVar('name', '', 'post');
		$email = JRequest::getVar('email', '', 'post');	
		$description = JRequest::getVar('description', '', 'post');	
		$url = $this->get_var('url', '', 'post');			
		
		if(defined('_JEXEC')){
			//joomla 1.5
			$name = addslashes($name);
			$description = addslashes($description);
		}	
		
		if($id==''){
			//new usergroup
			$this->db->setQuery( "INSERT INTO #__pi_aua_usergroups SET name='$name', email='$email', description='$description', url='$url' ");
			if (!$this->db->query()) {
				echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
				exit();
			}
		}else{
			//edit usergroup
			$this->db->setQuery( "UPDATE #__pi_aua_usergroups SET name='$name', email='$email', description='$description', url='$url' WHERE id='$id' ");
			if (!$this->db->query()) {
				echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
				exit();
			}
		}	
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=usergroups", _pi_ua_lang_usergroup_saved);
	}		
	
	function usergroup_delete(){
				
		//get vars
		if( defined('_JEXEC') ){
			//joomla 1.5
			$cid = JRequest::getVar('cid', null, 'post', 'array');		
		}else{
			//joomla 1.0.x
			$cid = mosGetParam( $_POST, 'cid', array(0) );
		}
		
		if (!is_array($cid) || count($cid) < 1) {
			echo "<script> alert(_pi_ua_lang_select_item_to_delete); window.history.go(-1);</script>";
			exit();
		}
		
		if (count($cid)){
			$ids = implode(',', $cid);			
			
			//update rows from user-index table which usergroup stops existing
			$this->db->setQuery("SELECT id FROM #__pi_aua_userindex WHERE group_id IN ($ids)"  );
			$rows = $this->db ->loadObjectList();
			foreach($rows as $row){					
				$index_id = $row->id;
				$this->db->setQuery( "UPDATE #__pi_aua_userindex SET group_id='0' WHERE id='$index_id'"	);
				if (!$this->db->query()) {
					echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
					exit();
				}	
			}		
			
			//delete usergroup
			$this->db->setQuery("DELETE FROM #__pi_aua_usergroups WHERE id IN ($ids)");
		}
		if (!$this->db->query()){
			echo "<script> alert('".$this->db -> getErrorMsg()."'); window.history.go(-1); </script>";
		}
		
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=usergroups", _pi_ua_lang_usergroup_deleted);
	}
	
	function access_pages_save(){			
		
		//get vars
		if( defined('_JEXEC') ){
			//joomla 1.5
			$page_access = JRequest::getVar('page_access', null, 'post', 'array');		
		}else{
			//joomla 1.0.x
			$page_access = mosGetParam( $_POST, 'page_access', array(0) );
		}
		
		//empty table (no one has any rights)
		$this->empty_table('access_pages');	
		
		//write pages access		
		for($n = 0; $n < count($page_access); $n++){
			$access_right = $page_access[$n];						
			$this->db->setQuery( "INSERT INTO #__pi_aua_access_pages SET pageid_usergroupid='$access_right'");
			if (!$this->db->query()) {
				echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
				exit();
			}			
		}
		
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=access_pages", _pi_ua_lang_pageaccess_saved);
	}	
	
	function access_components_save(){				
			
		//get vars
		if( defined('_JEXEC') ){
			//joomla 1.5
			$components_access = JRequest::getVar('componentsAccess', null, 'post', 'array');		
		}else{
			//joomla 1.0.x
			$components_access = mosGetParam( $_POST, 'componentsAccess', array(0) );
		}
		
		//empty table (no one has any rights)
		$this->empty_table('access_components');	
		
		//write component access		
		for($n = 0; $n < count($components_access); $n++){
			$component_right = $components_access[$n];						
			$this->db->setQuery( "INSERT INTO #__pi_aua_access_components SET component_usergroupid='$component_right'");
			if (!$this->db->query()) {
				echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
				exit();
			}			
		}		
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=access_components", _pi_ua_lang_component_access_saved);
	}
	
	//change when free
	function modules_save(){			
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=modules", _pi_ua_lang_module_access_saved);
	}
	
	//change when free
	function plugins_save(){		
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=plugins", _pi_ua_lang_plugin_access_saved);
	}
	
	//change when free
	function actions_save(){		
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=actions", _pi_ua_lang_action_permissions_saved);
	}
	
	function empty_table($table_name){		
		
		if($table_name=='actions' || $table_name=='access_components' || $table_name=='access_pages' || $table_name=='itemtypes' || $table_name=='sections' || $table_name=='categories' || $table_name=='userindex' || $table_name=='items' || $table_name=='modules' || $table_name=='plugins'){
			$this->db->setQuery("TRUNCATE TABLE #__pi_aua_$table_name");
			if (!$this->db->query()){
				echo "<script> alert('".$this->db -> getErrorMsg()."'); window.history.go(-1); </script>";
				exit();
			}
		}else{
			exit();
		}
	}	
	
	function translate_item_type($item_type){	
		if(file_exists(dirname(__FILE__).'/../com_pi_pages_and_items/class.php')){				
			
			$this->get_language_pi();
			
			if($item_type=='text'){
				$pi_lang_plugin_name = _pi_ua_lang_itemtypetext;
			}elseif($item_type=='html'){
				$pi_lang_plugin_name = 'HTML';
			}else{
				if(file_exists(dirname(__FILE__).'/../com_pi_itemtype_'.$item_type.'/language/'.$this->ua_config['language'].'.php')){
					require_once(dirname(__FILE__).'/../com_pi_itemtype_'.$item_type.'/language/'.$this->ua_config['language'].'.php'); 
				}else{			
					if(file_exists(dirname(__FILE__).'/../com_pi_itemtype_'.$item_type.'/language/en.php')){
						//require_once(dirname(__FILE__).'/../com_pi_itemtype_'.$item_type.'/language/en.php'); 
					}elseif(file_exists(dirname(__FILE__).'/../com_pi_itemtype_'.$item_type.'/language/english.php')){
						require_once(dirname(__FILE__).'/../com_pi_itemtype_'.$item_type.'/language/english.php'); 
					}else{
						if(strpos($item_type, 'ustom_')){
							//custom itemtype						
							$pos = strpos($item_type, 'ustom_');
							$type_id = substr($item_type, $pos+6, strlen($item_type));	
							$this->db->setQuery("SELECT name FROM #__pi_customitemtypes WHERE id='$type_id' LIMIT 1");
							$rows = $this->db->loadObjectList();
							$row = $rows[0];								
							$pi_lang_plugin_name = $row->name;						
							
						}else{
							//itemtype not installed
							$pi_lang_plugin_name = false;
						}
					}
				}		
			}
		}else{
			$pi_lang_plugin_name = '';
		}
		return $pi_lang_plugin_name;
	}
	
	function get_language_pi(){
		static $language_pi_has_been_included;
		if(!$language_pi_has_been_included){
			if(file_exists(dirname(__FILE__).'/../com_pi_pages_and_items/language/'.$this->ua_config['language'].'.php')){ 
				require_once(dirname(__FILE__).'/../com_pi_pages_and_items/language/'.$this->ua_config['language'].'.php'); 
			}else{
				require_once(dirname(__FILE__).'/../com_pi_pages_and_items/language/english.php'); 
			}
			$language_pi_has_been_included = 1;
		}
	}
	
	//change when free
	function access_itemtypes_save(){						
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=access_itemtypes", _pi_ua_lang_itemtypes_access_saved);
	}
	
	function config_save(){	
	
		$module_list_redirect_url = JRequest::getVar('module_list_redirect_url', '', 'post');
		$module_list_redirect_url = str_replace('=','[equal]',$module_list_redirect_url);
		$redirect_on_login_backend_url = JRequest::getVar('redirect_on_login_backend_url', '', 'post');
		$redirect_on_login_backend_url = str_replace('=','[equal]',$redirect_on_login_backend_url);
		
		$config = 'language='.$this->get_var('language', '', 'post').'
default_tab='.$this->get_var('default_tab', 'false', 'post').'
redirect_to_pi=false
use_toolbar='.$this->get_var('use_toolbar', 'false', 'post').'
display_usergroups='.$this->get_var('display_usergroups', 'false', 'post').'
display_users='.$this->get_var('display_users', 'false', 'post').'
default_usergroup='.$this->get_var('default_usergroup', '', 'post').'
display_pagesaccess='.$this->get_var('display_pagesaccess', 'false', 'post').'
active_pagesaccess='.$this->get_var('active_pagesaccess', 'false', 'post').'
inherit_rights_parent_page='.$this->get_var('inherit_rights_parent_page', 'false', 'post').'
display_itemtypes='.$this->get_var('display_itemtypes', 'false', 'post').'			
active_itemtypes='.$this->get_var('active_itemtypes', 'false', 'post').'
display_items='.$this->get_var('display_items', 'false', 'post').'
active_items='.$this->get_var('active_items', 'false', 'post').'			
display_itemtype_in_list='.$this->get_var('display_itemtype_in_list', 'false', 'post').'			
display_sections='.$this->get_var('display_sections', 'false', 'post').'
active_sections='.$this->get_var('active_sections', 'false', 'post').'
display_categories='.$this->get_var('display_categories', 'false', 'post').'
active_categories='.$this->get_var('active_categories', 'false', 'post').'
display_actions='.$this->get_var('display_actions', 'false', 'post').'
active_actions='.$this->get_var('active_actions', 'false', 'post').'
display_components='.$this->get_var('display_components', 'false', 'post').'
display_toolbars='.$this->get_var('display_toolbars', 'false', 'post').'
show_joomla_group='.$this->get_var('show_joomla_group', 'false', 'post').'
disable_joomla_group_selectbox='.$this->get_var('disable_joomla_group_selectbox', 'false', 'post').'
item_inherits_access='.$this->get_var('item_inherits_access', 'no_default_has_access', 'post').'	
com_content_access='.$this->get_var('com_content_access', 'page_access', 'post').'
activate_modules='.$this->get_var('activate_modules', 'false', 'post').'
display_modules='.$this->get_var('display_modules', 'false', 'post').'
activate_plugins='.$this->get_var('activate_plugins', 'false', 'post').'
display_plugins='.$this->get_var('display_plugins', 'false', 'post').'
activate_toolbars='.$this->get_var('activate_toolbars', 'false', 'post').'
display_toolbar_superadmin='.$this->get_var('display_toolbar_superadmin', 'false', 'post').'
page_props=true	
item_props=true	
activate_module_list_redirect='.JRequest::getVar('activate_module_list_redirect', 'false', 'post').'
module_list_redirect_url='.$module_list_redirect_url.'
redirect_on_login_backend='.JRequest::getVar('redirect_on_login_backend', 'false', 'post').'
redirect_on_login_backend_url='.$redirect_on_login_backend_url.'
redirect_also_super_admins='.JRequest::getVar('redirect_also_super_admins', 'false', 'post').'
display_category_in_list='.JRequest::getVar('display_category_in_list', 'false', 'post').'
display_section_in_list='.JRequest::getVar('display_section_in_list', 'false', 'post').'
display_section_in_catlist='.JRequest::getVar('display_section_in_catlist', 'false', 'post').'
extra_buttons_super_admin='.JRequest::getVar('extra_buttons_super_admin', 'false', 'post').'
';	
//get menutypes
if( defined('_JEXEC') ){
	//joomla 1.5
	$menutypes = JRequest::getVar('menutypes', null, 'post', 'array');		
}else{
	//joomla 1.0.x
	$menutypes = mosGetParam( $_POST, 'menutypes', array(0) );
}

//if menutype is not selected, take it out of array
//added the 'm' because of the problem with numerical indexes when unsetting in loop
$loops = count($menutypes);
for($n = 0; $n <= $loops; $n++){
	if(!isset($menutypes['m'.$n]['menutype'])){		
		unset($menutypes['m'.$n]);							
	} 	
}

//redo array to reset indexes
$temp = array();
foreach($menutypes as $menutype){	
	$temp[] = $menutype;
}
$menutypes = $temp;

//sort array by order
foreach ($menutypes as $key => $row) {
	$order[$key]  = $row['order'];    
}
$sort_order = SORT_ASC;
array_multisort($order, $sort_order, $menutypes);

//write menutypes array to config string
$config .= 'menutypes=';
if (is_array($menutypes)){	
	//foreach($menutypes as $menutype){
	for($n = 0; $n < count($menutypes); $n++){
		if($n!=0){
			$config .= ',';	
		}
		$config .= $menutypes[$n]['menutype'].';'.$menutypes[$n]['title'];
	}	
}
$config .= '
';

//get dropdown_buttons
if( defined('_JEXEC') ){
	//joomla 1.5
	$temp_dropdown_buttons = JRequest::getVar('dropdown_buttons', null, 'post', 'array');		
}else{
	//joomla 1.0.x
	$temp_dropdown_buttons = mosGetParam( $_POST, 'dropdown_buttons', array(0) );
}

//if dropdown_buttons has no name, take it out of array
$dropdown_buttons = array();
for($n = 0; $n < count($temp_dropdown_buttons); $n++){
	if($temp_dropdown_buttons['b'.$n]['name']!=''){			
		$new_button = array($temp_dropdown_buttons['b'.$n]['id'], $temp_dropdown_buttons['b'.$n]['name'], $temp_dropdown_buttons['b'.$n]['order']);
		array_push($dropdown_buttons, $new_button);							
	} 	
}

//make array of buttons which have already got an id
$loops = count($dropdown_buttons);
$button_id_array = array();
for($n = 0; $n < $loops; $n++){
	if($dropdown_buttons[0]!=''){		
		array_push($button_id_array, $dropdown_buttons[$n][0]);							
	} 	
}

//give new buttons an id
for($n = 0; $n < $loops; $n++){
	if($dropdown_buttons[$n][0]==''){	
		//make a unique id
		$new_id = 1;
		while(in_array($new_id, $button_id_array)){
			$new_id = $new_id+1;
		}	
		$dropdown_buttons[$n][0] = $new_id;	
		array_push($button_id_array, $new_id);						
	} 	
}

//sort array by order
foreach($dropdown_buttons as $sortarray){
	$column[] = $sortarray[2];	
}

$sort_order = SORT_ASC;
array_multisort($column, $sort_order, $dropdown_buttons);

//write dropdown_buttons array to config string
$config .= 'dropdown_buttons=';
if (is_array($dropdown_buttons)){	
	for($n = 0; $n < count($dropdown_buttons); $n++){			
		if(defined('_JEXEC')){
			//joomla 1.5
			$dropdown_button = addslashes($dropdown_buttons[$n][1]);
		}else{
			$dropdown_button = $dropdown_buttons[$n][1];
		}
		if($n!=0){
			$config .= ',';	
		}
		$config .= $dropdown_buttons[$n][0].';'.$dropdown_button;
	}
}
$config .= '
';

//extra buttons
if( defined('_JEXEC') ){
	//joomla 1.5
	$extra_buttons = JRequest::getVar('extra_buttons', null, 'post', 'array');		
}else{
	//joomla 1.0.x
	$extra_buttons = mosGetParam( $_POST, 'extra_buttons', array(0) );
}

//clean lines
$extra_buttons = str_replace('
','[newline]',$extra_buttons);
$extra_buttons = str_replace('=','[equal]',$extra_buttons);

//add buttons to configstring
$config .= 'extra_buttons=';
if(is_array($extra_buttons)){		
	for($n = 0; $n < count($extra_buttons); $n++){	
		if($extra_buttons[$n]!=''){
			if(defined('_JEXEC')){
				//joomla 1.5
				$button = addslashes($extra_buttons[$n]);
			}else{
				$button = $extra_buttons[$n];
			}
			if($n!=0){
				$config .= '-||-';	
			}			
			$config .= $button;			
		}
	}	
}

$config .= '			
notify_from_address='.addslashes($this->get_var('notify_from_address', '', 'post')).'
notify_from_name='.addslashes($this->get_var('notify_from_name', '', 'post')).'	
use_componentaccess='.$this->get_var('use_componentaccess', 'false', 'post').'
';

//get components
if( defined('_JEXEC') ){
	//joomla 1.5
	$components = JRequest::getVar('components', null, 'post', 'array');		
}else{
	//joomla 1.0.x
	$components = mosGetParam( $_POST, 'components', array(0) );
}

//if component is not selected, take it out of array
//added the 'c' because of the problem with numerical indexes when unsetting in loop
$loops = count($components);
for($n = 0; $n < $loops; $n++){
	if(!isset($components['c'.$n]['active'])){		
		unset ($components['c'.$n]);					
	} 	
}

//redo array to reset indexes
$temp = array();
foreach($components as $component){	
	$temp[] = $component;
}
$components = $temp;

//sort array on column 'order'
$column = '';
foreach($components as $sortarray){	
	$column[] = $sortarray['order'];
}
if(count($components)!=0){
	$sort_order = SORT_ASC;
	array_multisort($column, $sort_order, $components);
}

//write component array to config string
$config .= 'components=';
if(is_array($components)){		
	for($n = 0; $n < count($components); $n++){		
		$component_name = addslashes($components[$n]['name']);
		$component_alias = addslashes($components[$n]['alias']);
		$component_link = addslashes($components[$n]['link']);
		$component_dropdown = addslashes($components[$n]['dropdown']);	
		$component_icon = addslashes($components[$n]['icon']);		
		if($n!=0){
			$config .= ',';	
		}
		$config .= $this->clean_config_for_db($component_name).';'.$this->clean_config_for_db($component_alias).';'.$this->clean_config_for_db($component_link).';'.$component_dropdown.';'.$component_icon;
	}	
}

		//update config
		$this->db->setQuery( "UPDATE #__pi_aua_config SET config='$config' WHERE id='aua' ");
		$this->db->query();		
		
		//if components have been taken off the list, delete all component-access-rights for those components
		$this->clean_component_rights();		
		
		//redirect
		//get vars
		if( defined('_JEXEC') ){
			//joomla 1.5			
			$sub_task = JRequest::getVar('sub_task', '');			
		}else{
			//joomla 1.0.x			
			$sub_task = mosGetParam($_REQUEST, 'sub_task', '');			
		}		
		if($sub_task=='apply'){
			$url = 'index2.php?option=com_pi_admin_user_access&task=config';
		}else{
			$url = 'index2.php?option=com_pi_admin_user_access&task='.$this->get_var('default_tab', 'false', 'post');
		}	
		$this->redirect_to_url($url, _pi_ua_lang_configsaved);
	}	
	
	function clean_component_rights(){
				
		//get component-access-rights
		$this->db->setQuery("SELECT * FROM #__pi_aua_access_components"  );
		$rows = $this->db -> loadObjectList();
		
		//get current component-config as array	
		$components = array();
		foreach($this->ua_config['components'] as $component){	
			array_push($components, $component[0]);
		}	
		
		//delete all component-access-rights for components which are no longer in pi_admin_user_access component-config
		foreach($rows as $row){	
			$id = $row->id;
			$right = $row->component_usergroupid;
			$pos = strpos($right, '__'); 
			$stripped_right = substr($right, 0, $pos);					
			echo $stripped_right.'<br />';
			if(!in_array($stripped_right,$components)){			
				$this->db->setQuery("DELETE FROM #__pi_aua_access_components WHERE id='$id'");
				$this->db->query();
			}
		}		
	}

	function display_footer(){		
		echo '<div class="smallgrey" id="ua_footer"><a href="http://www.pages-and-items.com" target="_blank">Admin-User-Access</a> &copy; '._pi_ua_lang_version.' '.$this->version.' (';		
		echo $this->aua_version_type.' version) <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="blank">GNU/GPL License</a></div>';
	}	

	function users_save(){
		//get vars
		if( defined('_JEXEC') ){
			//joomla 1.5
			$joomlagroups = JRequest::getVar('gid', null, 'post', 'array');	
			$user_ids = JRequest::getVar('user_id', null, 'post', 'array');	
			$usergroups = JRequest::getVar('usergroup', null, 'post', 'array');		
		}else{
			//joomla 1.0.x
			$joomlagroups = mosGetParam( $_POST, 'gid', array(0));	
			$user_ids = mosGetParam( $_POST, 'user_id', array(0));	
			$usergroups = mosGetParam( $_POST, 'usergroup', array(0));
		}	
		
		//get users in userindex			
		$this->db->setQuery("SELECT user_id FROM #__pi_aua_userindex ");
		$user_ids_index = $this->db->loadResultArray();
		
		//update users				
		for($n = 0; $n < count($user_ids); $n++){		
			$user_id = $user_ids[$n];					
			$gid_update = '';
			$joomla_group_id = $joomlagroups[$n];						
			$gid_update = 'gid=\''.$joomlagroups[$n].'\'';		
			switch ($joomlagroups[$n]) {
			//case 18:
				//$usertype = 'Registered';
				//break;
			case 19:
				$usertype = 'Author';
				break;
			case 20:
				$usertype = 'Editor';
				break;
			case 21:
				$usertype = 'Publisher';
				break;
			case 23:
				$usertype = 'Manager';
				break;
			case 24:
				$usertype = 'Administrator';
				break;	
			}			
			$this->db->setQuery( "UPDATE #__users SET $gid_update , usertype='$usertype' WHERE id='$user_id'" );
			if (!$this->db->query()) {
				echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
				exit();
			}
			
			if(defined('_JEXEC')){
				//joomla 1.5
				$aro_id_column = 'id';
			}else{
				//joomla 1.0.x
				$aro_id_column = 'aro_id';
			}
			//find aro id
			$this->db->setQuery("SELECT $aro_id_column "
			."FROM #__core_acl_aro "
			."WHERE value='$user_id' "			
			);
			$aros = $this->db->loadResultArray();
			$aro_id = $aros[0];
			
			//update aro id in aro group map
			$this->db->setQuery( "UPDATE #__core_acl_groups_aro_map SET group_id='$joomla_group_id' WHERE aro_id='$aro_id'" );
			$this->db->query();				
			
			//update or insert user index
			$usergroup = $usergroups[$n];
			if(in_array($user_id, $user_ids_index)){						
				$this->db->setQuery( "UPDATE #__pi_aua_userindex SET group_id='$usergroup' WHERE user_id='$user_id'" );
				$this->db->query();	
			}else{
				$this->db->setQuery( "INSERT INTO #__pi_aua_userindex SET user_id='$user_id', group_id='$usergroup'");
				$this->db->query();
			}					
		}					
		$this->redirect_to_url('index2.php?option=com_pi_admin_user_access&task=users', _pi_ua_lang_userssaved);		
	}
	
	function spunk_up_headers_1_5(){
		if( defined('_JEXEC') ){
		//joomla 1.5
			$css = '<style type="text/css">
			
			th{	
				text-align: left;
				background: #F0F0F0;
				border-bottom: 1px solid #999999;
			}
			
			td{
				vertical-align: top;
			}
			
			</style>';
			echo $css;
		}
	}	
	
	function toolbars_save(){		
		
		//get usergroups from db
		$this->db->setQuery("SELECT id FROM #__pi_aua_usergroups");
		$usergroups = $this->db ->loadObjectList();
		
		//get vars
		if( defined('_JEXEC') ){
			//joomla 1.5
			$display_ua_toolbar = JRequest::getVar('display_ua_toolbar', null, 'post', 'array');			
			$display_joomla_toolbar = JRequest::getVar('display_joomla_toolbar', null, 'post', 'array');	
			$extra_buttons = JRequest::getVar('extra_buttons', null, 'post', 'array');	
		}else{
			//joomla 1.0.x
			$display_ua_toolbar = mosGetParam( $_POST, 'display_ua_toolbar', array(0) );	
			$display_joomla_toolbar = mosGetParam( $_POST, 'display_joomla_toolbar', array(0) );
			$extra_buttons = mosGetParam( $_POST, 'extra_buttons', array(0) );
		}
		
		//make another array for extra buttons			
		$extra_buttons2 = array();		
		for($n = 0; $n < count($extra_buttons); $n++){				
			$button = each($extra_buttons);			
			array_push($extra_buttons2, $button['value']);			
		}	
			
		//exit;
		foreach($usergroups as $group){				
			$group_id = $group->id;
			if (in_array($group_id, $display_ua_toolbar)){
				$ua_toolbar = '1';
			}else{
				$ua_toolbar = '0';
			}
			if (in_array($group_id, $display_joomla_toolbar)){
				$j_toolbar = '1';
			}else{
				$j_toolbar = '0';
			}
			
			//get extra's into a string	
			$extra = '';		
			for($n = 0; $n < count($extra_buttons2); $n++){				
				$temp = explode('_',$extra_buttons2[$n]);
				if($temp[1]==$group_id){					
					$extra .= $temp[0].',';
				}				
			}
						
			$this->db->setQuery( "UPDATE #__pi_aua_usergroups SET ua_toolbar='$ua_toolbar', j_toolbar='$j_toolbar', extra='$extra' WHERE id='$group_id'" );
			if (!$this->db->query()) {
				echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
				exit();
			}			
		}		
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=toolbars", _pi_ua_lang_toolbarsaved);		
	}	
	
	function set_title(){
		if(defined('_JEXEC')){
			//joomla 1.5
			JToolBarHelper::title( JText::_( 'Admin User Access' ), 'user.png' );
		}else{
			//joomla 1.0.x
			echo '<table class="adminheading"><tr><th class="user">Admin User Access</th></tr></table>';
		}
	}
	
	//change when free
	function check_workflow($which, $usergroup){			
		return true;		
	}	
	
	//change when free
	function check_useraccess_itemtype($type, $usergroup){
		
			return true;
		
	}
	
	function redirect_to_url($url, $message=false){
		global $mainframe;
		
		if(defined('_JEXEC')){
			//joomla 1.5
			$mainframe->redirect($url, $message);
		}else{
			//joomla 1.0.x
			mosRedirect($url, $message);
		}
	}
	
	//change when free
	function access_sections_save(){			
						
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=sections", _pi_ua_lang_section_access_saved);
	}
	
	//change when free
	function access_items_save(){			
					
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=items", _pi_ua_lang_item_access_saved);
	}
	
	//change when free
	function check_item_access($item_id, $usergroup){			
		
			return true;
		
	}
	
	//change when free
	function check_section_access($section_id, $usergroup){			
		
			return true;
		
	}
	
	function check_page_access($page_id, $usergroup){				
		$page_access = $page_id.'_'.$usergroup;						
		$access = false;
		if(in_array($page_access, $this->page_access_rights)){
			$access = true;			
		}			
		return $access;
	}	
	
	function check_category_access($category_id, $usergroup){				
		$category_access = $category_id.'__'.$usergroup;				
		$access = false;
		if(in_array($category_access, $this->category_access_rights)){
			$access = true;
		}		
		return $access;
	}	
	
	function get_pages_sections_array($menuitems){
		//get categories
		$this->db->setQuery("SELECT id, section FROM #__categories");
		$categories = $this->db->loadObjectList();
		
		//loop through menuitems and categories to make pages-sections array
		$pages_sections_array = array();
		
		foreach($menuitems as $menuitem){	
			if(defined('_JEXEC')){
				//joomla 1.5
				$menu_cat_id = str_replace('index.php?option=com_content&view=category&layout=blog&id=','',$menuitem->link);
			}else{
				//joomla 1.0.x
				$menu_cat_id = $menuitem->componentid;	
			}
			foreach($categories as $category){
				if($category->id==$menu_cat_id){							
					$pages_sections_array[$menuitem->id] = $category->section;							
					break;
				}						
			}					
		}
		return $pages_sections_array;
	}
	
	//change when free
	function set_new_item_access_for_groups($item_id, $page_id, $section_id){		
		
		
	}
	
	function set_page_access_group($page_id, $usergroup){
		$page_access = $page_id.'_'.$usergroup;		
		$this->db->setQuery( "INSERT INTO #__pi_aua_access_pages SET pageid_usergroupid='$page_access'");
		if (!$this->db->query()) {
			echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
			exit();
		}
	}
	
	function set_page_access_all_groups($page_id, $parent_page_id){
	//exit($page_id.$parent_page_id);
	
		//get page access data
		$this->db->setQuery("SELECT pageid_usergroupid FROM #__pi_aua_access_pages");
		$pages_access_data = $this->db->loadResultArray();		
		
		//get usergroups
		$this->db->setQuery("SELECT id FROM #__pi_aua_usergroups");
		$usergroups = $this->db->loadObjectList();	
		
		foreach($usergroups as $usergroup){
			$access = false;
			$parent_page_access = $parent_page_id.'_'.$usergroup->id;
			//exit($parent_page_access);
			if(in_array($parent_page_access, $pages_access_data)){
				$page_access_right = $page_id.'_'.$usergroup->id;
				$access = true;				
			}
			
			if($access){
				//inherit right				
				$this->db->setQuery( "INSERT INTO #__pi_aua_access_pages SET pageid_usergroupid='$page_access_right'");
				if (!$this->db->query()) {
					echo "<script> alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
					exit();
				}
			}
		}		
	}
	
	function access_categories_save(){
		//get vars
		if( defined('_JEXEC') ){
			//joomla 1.5
			$category_access = JRequest::getVar('categoryAccess', null, 'post', 'array');		
		}else{
			//joomla 1.0.x
			$category_access = mosGetParam( $_POST, 'categoryAccess', array(0) );
		}	
		
		//empty table (no one has any rights)
		$this->empty_table('categories');	
		
		//write sections access		
		for($n = 0; $n < count($category_access); $n++){
			$category_right = $category_access[$n];						
			$this->db->setQuery( "INSERT INTO #__pi_aua_categories SET category_groupid='$category_right'");
			if (!$this->db->query()) {
				echo "<script>alert('".$this->db->getErrorMsg()."'); window.history.go(-1); </script>";
				exit();
			}			
		}					
		$this->redirect_to_url("index2.php?option=com_pi_admin_user_access&task=categories", _pi_ua_lang_category_access_saved);
	}	

	function unlock_item(){
		
		$item_id = $this->get_var('item_id', '');		
		
		$this->db->setQuery( "UPDATE #__content SET checked_out='0', checked_out_time='0' WHERE id='$item_id' "	);
		$this->db->query();
		
		$this->redirect_to_url('index2.php?option=com_content','');
		
	}	
	
	function check_demo_time_left(){			
		if($this->aua_version_type=='trail'){			
			echo '<p style="text-align: center;">';
			echo '<span class="editlinktip" onmouseover="return overlib(\''._pi_ua_lang_demo_days_left_tip.'\', CAPTION, \'&nbsp;\', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" >'._pi_ua_lang_demo_days_left.'</span>';
			echo ': ';
			if(round((($this->aua_demo_seconds_left/60)/60)/24)<=0){
				echo '0';
			}else{
				echo round((($this->aua_demo_seconds_left/60)/60)/24);
			}
			echo '</p>';
		}
	}	
	
	function aua_check_trail_version(){
					
			$aua_trail_still_valid = true;								
		
		return $aua_trail_still_valid;
	}	
	
	function loop_usergroups($usergroups){
		foreach($usergroups as $usergroup){			
			$name = stripslashes($usergroup->name);
			$description = stripslashes($usergroup->description);			
			echo '<th style="text-align:center;">';
			if($description){
				echo '<span class="editlinktip" onmouseover="return overlib(\''.addslashes($description).'\', CAPTION, \''.addslashes($name).'\', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" >';
			}
			echo $name;
			if($description){
				echo '</span>';
			}
			echo '</th>';
		}
	}
	
	function get_itemtype($item_id, $aua_item_index){
		if($this->pi_installed){	
			foreach($aua_item_index as $item){
				if($item->item_id==$item_id){
					$item_type = $item->itemtype;	
					break;					
				}		
			}
		}
		if(!$item_type){
			$item_type = 'text';
		}
		$item_type_translated = $this->translate_item_type($item_type);
		if(!$item_type_translated){
			$item_type_translated = 'text';
		}
		
		return $item_type_translated;
	}
	
	function get_language(){
		require_once(dirname(__FILE__).'/language/'.$this->ua_config['language'].'.php');		
	}
	
	function get_config_pages_and_items(){
			
		$this->db->setQuery("SELECT config "
		."FROM #__pi_config "
		."WHERE id='pi' "
		."LIMIT 1"
		);
		$temp = $this->db->loadObjectList();
		$temp = $temp[0];
		$raw = $temp->config;	
		
		//get page attributes		
		$pos_start_page_attribs = strpos($raw, 'START_PAGE_NEW_ATTRIBUTES');
		$start_of_vars = $pos_start_page_attribs+26;
		$page_new_attribs = substr($raw, $start_of_vars, 99999);		
		$pi_config['page_new_attribs'] = $page_new_attribs;		
		
		//get just the config vars
		$rest_of_config = substr($raw, 0, $pos_start_page_attribs);
		
		$params = explode( "\n", $rest_of_config);
		
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
			$pi_config[$var] = $value;	
		}		
		return $pi_config;	
	}
	
	function get_config(){
	
		//get plugin-specific config	
		if(defined('_JEXEC')){
			//joomla 1.5			
			$database = JFactory::getDBO();
		}else{
			//joomla 1.0.x			
			global $database;
		}		
		
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
		$config['module_list_redirect_url'] = str_replace('[equal]','=',$config['module_list_redirect_url']);	
		$config['redirect_on_login_backend_url'] = str_replace('[equal]','=',$config['redirect_on_login_backend_url']);				
		return $config;			
	}
	
	function not_in_free_version(){
		if($this->aua_version_type=='free'){
			echo '<p class="warning">'._pi_ua_lang_not_in_free_version.'</p>';
		}
	}
	
	function clean_config_for_db($string){		
		$string = str_replace('
		','[newline]',$string);
		$string = str_replace('=','[equal]',$string);
		return $string;
	}
	
	function aua_truncate_string($string, $length){
		$dots='...';
		$string = trim($string);		
		if(strlen($string)<=$length){
			return $string;	
		}
		if(!strstr($string," ")){
			return substr($string,0,$length).$dots;
		}	
		$lengthf = create_function('$string','return substr($string,0,strrpos($string," "));');	
		$string = substr($string,0,$length);	
		$string = $lengthf($string);
		while(strlen($string)>$length){
			$string=$lengthf($string);
		}	
		return $string.$dots;
	}
	
	




}//end class_ua







?>