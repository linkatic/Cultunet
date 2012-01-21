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

if($class_ua->user_type!='Super Administrator'){
	echo "<script> alert('you need to be logged in as a super administrator to edit the pi_admin_user_access config.'); window.history.go(-1); </script>";
	exit();
}

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();

?>
<style>
.icon_preview{
	height: 20px;
	width: 26px;
	padding: 4px 6px 0 4px;
	float: left;
}

.icon_select option{
	background-repeat: no-repeat;
	background-position: 2px 5px;
	padding: 5px 0 5px 20px;
}
</style>
<script src="../includes/js/overlib_mini.js" language="JavaScript" type="text/javascript"></script>
<script src="components/com_pi_admin_user_access/javascript/javascript.js" language="JavaScript" type="text/javascript"></script>
<link href="components/com_pi_admin_user_access/css/pi_admin_user_access3.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript">

function submitbutton(pressbutton) {
	if (pressbutton == 'config_save') {
		submitform('config_save');
	}
	if (pressbutton == 'config_apply') {
		document.getElementById('sub_task').value = 'apply';
		submitform('config_save');
	}		
	if (pressbutton == 'cancel') {
		document.location.href = 'index2.php?option=com_pi_admin_user_access';		
	}
}

function change_icon(icon, target){
	//alert(icon+' '+target);
	document.getElementById(target).innerHTML = '<img src="'+icon+'" alt="" />';
}

<?php

$tab = $class_ua->get_var('tab', false);	

if(!$tab){
	
	echo "cookie_value = getCookie('aua_tabs');"."\n";	
	echo "if(cookie_value!=null){"."\n";		
		echo "current_tab = cookie_value;"."\n";
	echo "}else{"."\n";		
		echo "setCookie('aua_tabs', 'general_settings', '', '', '', '');"."\n";
		echo "current_tab = 'general_settings';"."\n";
	echo "}"."\n";
}else{
	echo "setCookie('aua_tabs', '".$tab."', '', '', '', '');"."\n";
	echo "current_tab = '".$tab."';"."\n";
}

?>

function get_tab(tab){
	if(tab!=current_tab){
		new_tab = 'tab_'+tab;	
		document.getElementById(new_tab).className = 'on';
		old_tab = 'tab_'+current_tab;	
		document.getElementById(old_tab).className = 'none';
		document.getElementById(tab).style.display = 'block';	
		document.getElementById(current_tab).style.display = 'none';
		current_tab = tab;
		setCookie('aua_tabs', tab, '', '', '', '');
	}
}

function pi_config_menu_init(){
	current_tab_name = 'tab_'+current_tab;	
	document.getElementById(current_tab_name).className = 'on';
	document.getElementById(current_tab).style.display = 'block';	
}

if(window.addEventListener)window.addEventListener("load",pi_config_menu_init,false);else if(window.attachEvent)window.attachEvent("onload",pi_config_menu_init);

</script>
<?php
$class_ua->check_demo_time_left();
?>
<form name="adminForm" method="post" action="">
	<input type="hidden" name="option" value="com_pi_admin_user_access" />
	<input type="hidden" name="task" value="config_save" />	
	<input type="hidden" name="sub_task" id="sub_task" value="" />			
	<div style="text-align: left;">	
		<a href="index2.php?option=com_pi_admin_user_access">Admin User Access</a>	&gt; <?php echo _pi_ua_lang_config; ?>
		<h2>Admin User Access configuration</h2>			
		<ul id="aua_menu">				
			<li>
				<a id="tab_general_settings" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('general_settings');"><span><?php echo _pi_ua_lang_general; ?></span></a>
			</li>
			<li>
				<a id="tab_users" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('users');"><span><?php echo _pi_ua_lang_users; ?></span></a>
			</li>
			<li>
				<a id="tab_page_access" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('page_access');"><span><?php echo _pi_ua_lang_page_access; ?></span></a>
			</li>
			<li>
				<a id="tab_itemtype_access" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('itemtype_access');"><span><?php echo _pi_ua_lang_itemtypes; ?></span></a>
			</li>
			<li>
				<a id="tab_item_access" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('item_access');"><span><?php echo _pi_ua_lang_item_access; ?></span></a>
			</li>			
			<li>
				<a id="tab_category_access" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('category_access');"><span><?php echo _pi_ua_lang_categories; ?></span></a>
			</li>
			<li>
				<a id="tab_section_access" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('section_access');"><span><?php echo _pi_ua_lang_sections; ?></span></a>
			</li>
			<li>
				<a id="tab_workflow" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('workflow');"><span><?php echo _pi_ua_lang_actions; ?></span></a>
			</li>
			<li>
				<a id="tab_component_access" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('component_access');"><span><?php echo _pi_ua_lang_component_access; ?></span></a>
			</li>
			<li>
				<a id="tab_module_access" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('module_access');"><span><?php echo _pi_ua_lang_module_access; ?></span></a>
			</li>	
			<li>
				<a id="tab_plugin_access" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('plugin_access');"><span><?php echo _pi_ua_lang_plugin_access; ?></span></a>
			</li>
			<li>
				<a id="tab_toolbars" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('toolbars');"><span><?php echo _pi_ua_lang_displaytoolbar; ?></span></a>
			</li>	
			<li>
				<a id="tab_aec" onfocus="if(this.blur)this.blur();" href="javascript:get_tab('aec');"><span>subscriptions and paid content</span></a>
			</li>	
		</ul>				
		<div id="general_settings">
		<table class="adminlist">			
			<tr>
				<th colspan="2" align="left">
									
					<?php echo _pi_ua_lang_general; ?>
				</th>
			</tr>			
			<tr>		
				<td>
					<?php echo _pi_ua_lang_statusmod.' ('._pi_ua_lang_backend.')'; ?>
				</td>
				<td>
					<?php 					
					if($class_ua->module_installed){
						echo '<div style="color: #5F9E30;">'._pi_ua_lang_modinstalled.'</div>';		
					}else{
						echo '<div style="color: red;">'._pi_ua_lang_warning.' '._pi_ua_lang_modnotinstalled.'</div>';
					}	
					if($class_ua->module_published){
						echo '<div style="color: #5F9E30;">'._pi_ua_lang_modpublished.'</div>';		
					}else{
						echo '<div style="color: red;">'._pi_ua_lang_modnotpublished.'</div>';
					}	
					if($class_ua->aua_module_position){
						echo '<div style="color: #5F9E30;">'._pi_ua_lang_modpublished_to_right_position.'</div>';		
					}else{
						echo '<div style="color: red;">'._pi_ua_lang_modnotpublished_to_right_position.'. '._pi_ua_lang_modnotpublished_to_menu.'. <a href="index.php?option=com_modules&client=1">'._pi_ua_lang_fix_it_here.'</a></div>';
					}					
					?>
				</td>
			</tr>						
			<tr>		
				<td>
					<?php 
					echo _pi_ua_lang_statusbot.' (system)';					
					?>
				</td>
				<td>
					<?php 
					$plugin_installed = false;
					$plugin_enabled = false;
					
					//check if plugin is installed and published
					$class_ua->db->setQuery("SELECT published "
					."FROM #__plugins "
					."WHERE element='adminuseraccess' AND folder='system' "
					."LIMIT 1"					
					);
					$rows = $class_ua->db->loadObjectList();					
					foreach($rows as $row){	
						$plugin_installed = true;
						$plugin_enabled = $row->published;
					}
										
					if($plugin_installed){
						echo '<div style="color: #5F9E30;">'._pi_ua_lang_botinstalled.'</div>';		
					}else{
						echo '<div style="color: red;">'._pi_ua_lang_botnotinstalled.'</div>';
					}
					if($plugin_enabled){
						echo '<div style="color: #5F9E30;">'._pi_ua_lang_botpublished.'</div>';		
					}else{
						echo '<div style="color: red;">'._pi_ua_lang_botnotpublished.'</div>';
					}					
					?>
				</td>
			</tr>
			<tr>		
				<td>
					<?php 
					echo _pi_ua_lang_statusbot.' (user)';					
					?>
				</td>
				<td>
					<?php 
					$plugin_installed = false;
					$plugin_enabled = false;
					
					//check if plugin is installed and published
					$class_ua->db->setQuery("SELECT published "
					."FROM #__plugins "
					."WHERE element='admin_user_access' AND folder='user' "
					."LIMIT 1"					
					);
					$rows = $class_ua->db->loadObjectList();					
					foreach($rows as $row){	
						$plugin_installed = true;
						$plugin_enabled = $row->published;
					}
										
					if($plugin_installed){
						echo '<div style="color: #5F9E30;">'._pi_ua_lang_botinstalled.'</div>';		
					}else{
						echo '<div style="color: red;">'._pi_ua_lang_botnotinstalled.'</div>';
					}
					if($plugin_enabled){
						echo '<div style="color: #5F9E30;">'._pi_ua_lang_botpublished.'</div>';		
					}else{
						echo '<div style="color: red;">'._pi_ua_lang_botnotpublished.'</div>';
					}					
					?>
				</td>
			</tr>				
			<tr>		
				<td>
					<?php echo _pi_ua_lang_piuseua; ?>
				</td>
				<td>
					<?php 					
					if($class_ua->pi_installed && $class_ua->pi_config['use_user_access_component']){
						echo _pi_ua_lang_on;				
					}else{
						echo _pi_ua_lang_off;
					}
					?>
				</td>
			</tr>			
			<tr>		
				<td>
					<?php echo _pi_ua_lang_language; ?>
				</td>
				<td>
					<select name="language">
					<?php
						if( defined('_JEXEC') ){
							//joomla 1.5
							jimport( 'joomla.filesystem.folder' );
							$languages = JFolder::files(dirname(__FILE__).'/../language');
						}else{
							//joomla 1.0.x
							$languages = mosReadDirectory(dirname(__FILE__).'/../language');
						}						
						foreach($languages as $language){
							if($language!='index.html'){
								$language = str_replace('.php','',$language);
								$selected = '';
								if($language==$class_ua->ua_config['language']){
									$selected = ' selected="selected"';
								}
								echo '<option value="'.$language.'"'.$selected.'>'.$language.'</option>';
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr>		
				<td>
					<?php echo _pi_ua_lang_defaulttab; ?>
				</td>
				<td>
					<select name="default_tab">
						<?php							
							echo '<option value="usergroups"';
							if($class_ua->ua_config['default_tab']=='usergroups'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_usergroups.'</option>';
							echo '<option value="users"';
							if($class_ua->ua_config['default_tab']=='users'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_users.'</option>';
							
							echo '<option value="access_pages"';
							if($class_ua->ua_config['default_tab']=='access_pages'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_page_access.'</option>';
							
							echo '<option value="itemtypes"';
							if($class_ua->ua_config['default_tab']=='itemtypes'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_itemtype_access.'</option>';
							
							echo '<option value="items"';
							if($class_ua->ua_config['default_tab']=='items'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_item_access.'</option>';
							
							echo '<option value="sections"';
							if($class_ua->ua_config['default_tab']=='sections'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_sections.'</option>';
							
							echo '<option value="categories"';
							if($class_ua->ua_config['default_tab']=='categories'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_categories.'</option>';						
							
							echo '<option value="actions"';
							if($class_ua->ua_config['default_tab']=='actions'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_actions.'</option>';
							
							echo '<option value="access_components"';
							if($class_ua->ua_config['default_tab']=='access_components'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_component_access.'</option>';
							echo '<option value="modules"';
							if($class_ua->ua_config['default_tab']=='modules'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_module_access.'</option>';	
							echo '<option value="plugins"';
							if($class_ua->ua_config['default_tab']=='plugins'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_plugin_access.'</option>';	
							echo '<option value="toolbars"';
							if($class_ua->ua_config['default_tab']=='toolbars'){
								echo ' selected="selected"';
							}
							echo '>'._pi_ua_lang_displaytoolbar.'</option>';						
						?>					
					</select>
				</td>
			</tr>			
			<tr>		
				<td style="width: 300px;">
					<?php echo _pi_ua_lang_redirect_on_login_backend; ?>
				</td>
				<td>
					<p>
						<input type="checkbox" name="redirect_on_login_backend" value="true" <?php if($class_ua->ua_config['redirect_on_login_backend']){echo 'checked="checked"';} ?> />
						<?php echo _pi_ua_lang_redirect_on_login_backend_info; ?>.
					</p>
					<p>
						url: <input type="text" value="<?php echo $class_ua->ua_config['redirect_on_login_backend_url']; ?>" name="redirect_on_login_backend_url" style="width: 300px;" /> <?php echo _pi_ua_lang_example; ?>: index.php?option=com_content
					</p>
					<p>
						<label><input type="checkbox" name="redirect_also_super_admins" value="true" <?php if($class_ua->ua_config['redirect_also_super_admins']){echo 'checked="checked"';} ?> /> <?php echo _pi_ua_lang_redirect_also_super_admins; ?>.</label>
					</p>
				</td>
			</tr>	
			<tr>
				<td colspan="2">
					<?php echo _pi_ua_lang_showtab; ?>
				</td>
			</tr>
						
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_usergroups" value="true" <?php if($class_ua->ua_config['display_usergroups']){echo 'checked="checked"';} ?> /> <?php echo _pi_ua_lang_usergroups; ?>
				</td>
			</tr>			
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_users" value="true" <?php if($class_ua->ua_config['display_users']){echo 'checked="checked"';} ?> /> <?php echo _pi_ua_lang_users; ?>
				</td>
			</tr>			
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
						<input type="checkbox" name="display_pagesaccess" value="true" <?php if($class_ua->ua_config['display_pagesaccess']){echo 'checked="checked"';} ?> />	<?php echo _pi_ua_lang_page_access; ?>			
				</td>
			</tr>			
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_itemtypes" value="true" <?php if($class_ua->ua_config['display_itemtypes']){echo 'checked="checked"';} ?> />	<?php echo _pi_ua_lang_itemtype_access; ?>				
				</td>
			</tr>
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_items" value="true" <?php if($class_ua->ua_config['display_items']){echo 'checked="checked"';} ?> />		<?php echo _pi_ua_lang_item_access; ?>			
				</td>
			</tr>	
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_sections" value="true" <?php if($class_ua->ua_config['display_sections']){echo 'checked="checked"';} ?> />			<?php echo _pi_ua_lang_sections; ?>		
				</td>
			</tr>
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_categories" value="true" <?php if($class_ua->ua_config['display_categories']){echo 'checked="checked"';} ?> />			<?php echo _pi_ua_lang_categories; ?>		
				</td>
			</tr>		
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_actions" value="true" <?php if($class_ua->ua_config['display_actions']){echo 'checked="checked"';} ?> />		<?php echo _pi_ua_lang_actions; ?>			
				</td>
			</tr>
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_components" value="true" <?php if($class_ua->ua_config['display_components']){echo 'checked="checked"';} ?> />	<?php echo _pi_ua_lang_component_access; ?>				
				</td>
			</tr>
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_modules" value="true" <?php if($class_ua->ua_config['display_modules']){echo 'checked="checked"';} ?> />	<?php echo _pi_ua_lang_module_access; ?>				
				</td>
			</tr>
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_plugins" value="true" <?php if($class_ua->ua_config['display_plugins']){echo 'checked="checked"';} ?> />	<?php echo _pi_ua_lang_plugin_access; ?>				
				</td>
			</tr>
			<tr>		
				<td>&nbsp;
					
				</td>
				<td>
					<input type="checkbox" name="display_toolbars" value="true" <?php if($class_ua->ua_config['display_toolbars']){echo 'checked="checked"';} ?> />	<?php echo _pi_ua_lang_displaytoolbar; ?>				
				</td>
			</tr>
			<tr>		
				<td>
					<?php echo _pi_ua_lang_version; ?>	
				</td>
				<td>
					<?php echo $class_ua->version; ?>
				</td>
			</tr>
			<tr>		
				<td colspan="2">&nbsp;
					
				</td>
			</tr>
			</table>
			</div>
			<div id="users">
			<table class="adminlist">			
			<tr>
				<th colspan="2" align="left">
				
					<?php echo _pi_ua_lang_users; ?>
				</th>
			</tr>
			<tr>		
				<td width="300">
					<?php echo _pi_ua_lang_showjoomlagroup; ?>
				</td>
				<td>
					<input type="checkbox" name="show_joomla_group" value="true" <?php if($class_ua->ua_config['show_joomla_group']){echo 'checked="checked"';} ?> />
				</td>
			</tr>			
			<tr>		
				<td>
					<?php 
					echo _pi_ua_lang_default_usergroup; 					
					?>
				</td>
				<td>					
					<select name="default_usergroup">
						<?php
						$class_ua->db->setQuery("SELECT id, name "
						."FROM #__pi_aua_usergroups  "						
						."ORDER BY name ASC"
						);
						$rows = $class_ua->db->loadObjectList();
						if(count($rows)){	
							echo '<option value="0">'._pi_ua_lang_no_select_dropdown.'</option>';					
							foreach($rows as $row){	
								echo '<option value="'.$row->id.'"';
								if($class_ua->ua_config['default_usergroup']==$row->id){
									echo ' selected="selected"';
								}
								echo '>';							
								echo $row->name;
								echo '</option>';	
							}
						}
						?>						
					</select> 
					<?php 
					if(!count($rows)){
						echo '<span style="color: red;">'._pi_ua_lang_nousergroups.'</span> ';
					}
					echo _pi_ua_lang_default_usergroup_info; 					
					?>
				</td>
			</tr>
			<tr>		
				<td colspan="2">&nbsp;
										
				</td>
			</tr>	
			</table>
			</div>
			<div id="page_access">			
			<table class="adminlist">					
			<tr>
				<th colspan="2" align="left">
				
					<?php echo _pi_ua_lang_page_access; ?>
				</th>
			</tr>
			<tr>		
				<td>
					<?php echo _pi_ua_lang_activatepipage; ?>				
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="active_pagesaccess" value="true" <?php if($class_ua->ua_config['active_pagesaccess']){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_pages_info.'.'; ?>
				</td>
			</tr>
			<tr>		
				<td>
					<span class="editlinktip"><span onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_inherit_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_inherit); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_inherit; ?></span></span>					
				</td>
				<td>
					<input type="checkbox" name="inherit_rights_parent_page" value="true" <?php if($class_ua->ua_config['inherit_rights_parent_page']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>		
				<td width="300">
					com_content
				</td>
				<td>
					<label onclick="document.getElementById('com_content_access1_page').checked=true;">
					<input name="com_content_access2" type="radio" value="page_access" id="com_content_access2_page" <?php if($class_ua->ua_config['com_content_access']=='page_access'){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_com_content_pages; ?>.
					</label><br />
					<label onclick="document.getElementById('com_content_access1_cat').checked=true;">
					<input name="com_content_access2" type="radio" value="category_access" id="com_content_access2_cat"  <?php if($class_ua->ua_config['com_content_access']=='category_access'){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_com_content_categories; ?>.
					</label>
				</td>
			</tr>
			<tr>		
				<td colspan="2">
					<span class="editlinktip" onmouseover="return overlib('<?php echo _pi_lang_ua_menus_tip; ?>', CAPTION, '<?php echo _pi_lang_ua_menus; ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo stripslashes(_pi_lang_ua_menus); ?></span>
				</td>				
			</tr>
			<tr>		
				<td>&nbsp;
								
				</td>
				<td>
					<span class="sidestep2 b"><?php echo _pi_ua_lang_name; ?></span><span class="b"><?php echo _pi_ua_lang_order; ?></span>
				</td>
			</tr>
			<?php
			
			//loop through menutypes from config
			$counter = 1;
			$menus_from_config = $class_ua->ua_config['menutypes'];
			$menus_on_page = array();
			for($m = 0; $m < count($menus_from_config); $m++){
				$menu_type = $menus_from_config[$m][0];
				$menu_name = $menus_from_config[$m][1];
				if($menu_name){
					//$menu = each($menus_from_config);
					echo '<tr>';
					echo '<td>&nbsp;</td>';
					echo '<td>';
					echo '<span class="sidestep2">';
					echo '<label>';					
					//echo '<input type="checkbox" class="checkbox" name="menutypes[m'.$m.'][menutype]" value="'.strtolower($menu[key]).'" checked="checked" />';
					echo '<input type="checkbox" class="checkbox" name="menutypes[m'.$m.'][menutype]" value="'.$menu_type.'" checked="checked" />';				
					//echo $menu[value];
					echo $menu_name;
					echo '</label>';
					echo '</span>';
					//echo '<input type="hidden" name="menutypes[m'.$m.'][title]" value="'.$menu[value].'" />';
					echo '<input type="hidden" name="menutypes[m'.$m.'][title]" value="'.$menu_name.'" />';
					echo '<input type="text" name="menutypes[m'.$m.'][order]" size="2" value="'.$counter.'" />';				
					echo '</td>';
					echo '</tr>';
					//array_push($menus_on_page, $menu[key]);
					array_push($menus_on_page, $menu_type);				
					$counter = $counter + 1;
				}	
			}
			
			//get all menutypes
			$menutypes_db = array();
			if( defined('_JEXEC') ){
				//joomla 1.5				
				$class_ua->db->setQuery("SELECT title, menutype FROM #__menu_types ORDER BY title ASC"  );
				$rows = $class_ua->db-> loadObjectList();
				
				foreach($rows as $row){					
					$new_menutype = array($row->menutype,$row->title);
					array_push($menutypes_db, $new_menutype);					
				}					
			}else{
				//joomla 1.0.x
				$class_ua->db->setQuery("SELECT id, title, module, params FROM #__modules WHERE module='mod_mainmenu' ORDER BY title ASC"  );
				$rows = $class_ua->db-> loadObjectList();				
				
				foreach($rows as $row){					
					$params = explode( "\n", $row->params);
					for($n = 0; $n < count($params); $n++){
						list($var,$value) = split('=',$params[$n]); 
						$values[$var] = trim($value);	 
					}
					$new_menutype = array($values['menutype'],$row->title);
					array_push($menutypes_db, $new_menutype);					
				}				
			}
				
			//loop through menutypes from database						
			for($m = 0; $m < count($menutypes_db); $m++){
				if(!in_array($menutypes_db[$m][0], $menus_on_page)){	
					echo '<tr>';
					echo '<td>&nbsp;</td>';
					echo '<td>';
					echo '<span class="sidestep2">';
					echo '<label>';					
					echo '<input type="checkbox" class="checkbox" name="menutypes[m'.($counter-1).'][menutype]" value="'.$menutypes_db[$m][0].'" />';
					echo $menutypes_db[$m][1];
					echo '</label>';
					echo '</span>';
					echo '<input type="hidden" name="menutypes[m'.($counter-1).'][title]" value="'.$menutypes_db[$m][1].'" />';
					echo '<input type="text" name="menutypes[m'.($counter-1).'][order]" size="2" value="'.$counter.'" />';					
					echo '</td>';
					echo '</tr>';						
					$counter = $counter + 1;
				}
			}			
				
			?>
			
			</table>
			</div>
			<div id="itemtype_access">
			<?php $class_ua->not_in_free_version(); ?>
			<table class="adminlist">						
			<tr>
				<th colspan="2" align="left">
					
					<?php echo _pi_ua_lang_itemtypes; ?>
				</th>
			</tr>
			<tr>		
				<td width="300">
					<span class="editlinktip" onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_activatepiitemtypes_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_activatepiitemtypes); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_activatepiitemtypes; ?></span>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="active_itemtypes" value="true" <?php if($class_ua->ua_config['active_itemtypes']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>		
				<td colspan="2">&nbsp;
					
				</td>
			</tr>
			</table>
			</div>
			<div id="item_access">
			<?php $class_ua->not_in_free_version(); ?>
			<table class="adminlist">						
			<tr>
				<th colspan="2" align="left">
					
					<?php echo _pi_ua_lang_item_access; ?>
				</th>
			</tr>
			<tr>		
				<td>
					<span class="editlinktip" onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_activateitems_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_activateitems); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_activateitems; ?></span>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="active_items" value="true" <?php if($class_ua->ua_config['active_items']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>		
				<td>
					<?php echo _pi_ua_lang_item_inherits_access; ?>
				</td>
				<td>					
					<label>
					<input name="item_inherits_access" type="radio" value="no_default_has_no_access"  <?php if($class_ua->ua_config['item_inherits_access']=='no_default_has_no_access'){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_no_default_has_no_access; ?>
					</label><br />
					<label>
					<input name="item_inherits_access" type="radio" value="no_default_has_access"  <?php if($class_ua->ua_config['item_inherits_access']=='no_default_has_access'){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_no_default_has_access; ?>
					</label><br />
					<label>
					<input name="item_inherits_access" type="radio" value="yes_from_page"  <?php if($class_ua->ua_config['item_inherits_access']=='yes_from_page'){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_yes_from_page; ?>
					</label><br />
					<label>
					<input name="item_inherits_access" type="radio" value="yes_from_section"  <?php if($class_ua->ua_config['item_inherits_access']=='yes_from_section'){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_yes_from_section; ?>
					</label>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo _pi_ua_lang_display_itemtype_in_list; ?>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="display_itemtype_in_list" value="true" <?php if($class_ua->ua_config['display_itemtype_in_list']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					echo _pi_ua_lang_display_category_in_list.' \''._pi_ua_lang_item_access.'\''; 
					if(!isset($class_ua->ua_config['display_category_in_list'])){
						$class_ua->ua_config['display_category_in_list'] = 0;
					}
					?>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="display_category_in_list" value="true" <?php if($class_ua->ua_config['display_category_in_list']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					echo _pi_ua_lang_display_section_in_list.' \''._pi_ua_lang_item_access.'\''; 
					if(!isset($class_ua->ua_config['display_section_in_list'])){
						$class_ua->ua_config['display_section_in_list'] = 0;
					}
					?>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="display_section_in_list" value="true" <?php if($class_ua->ua_config['display_section_in_list']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>		
				<td colspan="2">&nbsp;
					
				</td>
			</tr>	
			</table>
			</div>
			<div id="section_access">
			<?php $class_ua->not_in_free_version(); ?>
			<table class="adminlist">					
			<tr>
				<th colspan="2" align="left">
					
					<?php echo _pi_ua_lang_sections; ?>
				</th>
			</tr>
			<tr>		
				<td width="300">
					<span class="editlinktip" onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_activatesections_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_activatesections); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_activatesections; ?></span>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="active_sections" value="true" <?php if($class_ua->ua_config['active_sections']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>		
				<td colspan="2">&nbsp;
					
				</td>
			</tr>
			</table>
			</div>
			<div id="category_access">			
			<table class="adminlist">					
			<tr>
				<th colspan="2" align="left">
					
					<?php echo _pi_ua_lang_categories; ?>
				</th>
			</tr>
			<tr>		
				<td width="300">
					<span class="editlinktip" onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_activatecategories_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_activatecategories); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_activatecategories; ?></span>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="active_categories" value="true" <?php if($class_ua->ua_config['active_categories']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>		
				<td width="300">
					com_content
				</td>
				<td>
					<label onclick="document.getElementById('com_content_access2_page').checked=true;">
					<input name="com_content_access" type="radio" id="com_content_access1_page" value="page_access"  <?php if($class_ua->ua_config['com_content_access']=='page_access'){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_com_content_pages; ?>.
					</label><br />
					<label onclick="document.getElementById('com_content_access2_cat').checked=true;">
					<input name="com_content_access" type="radio" value="category_access" id="com_content_access1_cat" <?php if($class_ua->ua_config['com_content_access']=='category_access'){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_com_content_categories; ?>.
					</label>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					echo _pi_ua_lang_display_section_in_catlist.' \''._pi_ua_lang_categories.'\''; 
					if(!isset($class_ua->ua_config['display_section_in_catlist'])){
						$class_ua->ua_config['display_section_in_catlist'] = 0;
					}
					?>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="display_section_in_catlist" value="true" <?php if($class_ua->ua_config['display_section_in_catlist']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>		
				<td colspan="2">&nbsp;
					
				</td>
			</tr>
			</table>
			</div>
			<div id="workflow">
			<?php $class_ua->not_in_free_version(); ?>
			<table class="adminlist">						
			<tr>
				<th colspan="2" align="left">
					
					<?php echo _pi_ua_lang_actions; ?>
				</th>
			</tr>
			<tr>		
				<td width="300">
					<span class="editlinktip" onmouseover="return overlib('<?php echo _pi_ua_lang_activatepiactions_tip; ?>', CAPTION, '<?php echo _pi_ua_lang_activatepiactions; ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_activatepiactions; ?></span>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="active_actions" value="true" <?php if($class_ua->ua_config['active_actions']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>		
				<td>
					<span class="editlinktip"><span onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_notifyaddress_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_notifyaddress); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_notifyaddress; ?></span></span>					
				</td>
				<td>
					<input type="text" name="notify_from_address" value="<?php echo $class_ua->ua_config['notify_from_address']; ?>"  />
				</td>
			</tr>
			<tr>		
				<td>
					<?php echo _pi_ua_lang_notify_name; ?>					
				</td>
				<td>
					<input type="text" name="notify_from_name" value="<?php echo $class_ua->ua_config['notify_from_name']; ?>"  />
				</td>
			</tr>			
			<tr>		
				<td colspan="2">&nbsp;
					
				</td>
			</tr>
			</table>
			</div>
			<div id="component_access">
			<table class="adminlist">						
			<tr>
				<th colspan="2" align="left">
					
					<?php echo _pi_ua_lang_component_access; ?>
				</th>
			</tr>
			<tr>		
				<td width="300">
					<span class="editlinktip"><span onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_use_componentaccess_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_use_componentaccess); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_use_componentaccess; ?></span></span>
				</td>
				<td>
					<input type="checkbox" name="use_componentaccess" value="true" <?php if($class_ua->ua_config['use_componentaccess']){echo 'checked="checked"';} ?> />
				</td>
			</tr>			
			<tr>		
				<td colspan="2">
					<table class="adminlist">
						<tr>
							<td>
								<span class="editlinktip"><span onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_useinpiuseraccess_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_useinpiuseraccess); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_useinpiuseraccess; ?></span></span>
							</td>							
							<td>
								<?php echo _pi_ua_lang_componentname; ?>
							</td>
							<td>								
								<span class="editlinktip"><span onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_alias_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_alias); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_alias; ?></span></span>
							</td>
							<td>
								<?php echo _pi_ua_lang_adminlink; ?>								
							</td>
							<td>
								<?php echo _pi_ua_lang_assign_to_dropdown_button; ?>
							</td>
							<td>&nbsp;
								
							</td>
							<td>
								<?php echo _pi_ua_lang_order; ?>
							</td>
						</tr>						
						<?php
						
							//get backend template
							$class_ua->db->setQuery("SELECT template FROM #__templates_menu WHERE client_id='1' "  );
							$backend_template = $class_ua->db->loadResult();								
							
							//get all icons from folders
							jimport( 'joomla.filesystem.folder' );
							$icons = JFolder::files(JPATH_ADMINISTRATOR.DS.'templates'.DS.$backend_template.DS.'images'.DS.'menu');
							
							$icons_array = array();
							foreach($icons as $icon){
								if(strpos($icon, '.png')){
									$temp = str_replace('.png', '', $icon);
									$temp = str_replace('icon-16-', '', $temp);
									$icon_name = ucfirst($temp);
									$icon_component = 'com_'.$temp;
									$icon_path_img = 'templates'.'/'.$backend_template.'/'.'images'.'/'.'menu'.'/'.$icon;
									//echo '<img src="templates'.'/'.$backend_template.'/'.'images'.'/'.'menu'.'/'.$icon.'" alt="'.$icon_name.'" />'.$icon_name.' '.$icon_component.'<br />';
									$icons_array[] = array($icon_path_img, $icon_name, $icon_component);
								}
							}
							//print_r($icons_array);
							
							/*
							//get default_icon
							$default_icon = '';
							for($i = 0; $i < count($icons_array); $i++){
								if($icons_array[$i][2]=='com_component'){
									$default_icon = $icons_array[$i][0];
									break;
								}
							}
							//echo 'default_icon'.$default_icon;
							*/
							
							$default_icon = 'templates'.'/'.$backend_template.'/'.'images'.'/'.'menu'.'/icon-16-component.png';
							
							//get components from database												
							$class_ua->db->setQuery("SELECT * FROM #__components WHERE parent='0' " );
							$rows = $class_ua->db-> loadObjectList();	
							
							//get components icons
							foreach($rows as $row){
								$icon_path_img = $row->admin_menu_img;	
								$icon_name = $row->name;
								$icon_component = $row->option;
								if($icon_path_img!='' && !strpos($icon_path_img, 'ThemeOffice') && file_exists(JPATH_ADMINISTRATOR.DS.$icon_path_img) && !strpos($icon_path_img, 'com_joomfish/assets/images/icon-10-blank.png')){
									$icons_array[] = array($icon_path_img, $icon_name, $icon_component);
								}
							}							
							
							//sort array by icon_name
							$column = '';//reset column if you used this elsewhere
							foreach($icons_array as $sortarray){
								$column[] = $sortarray[1];	
							}
							$sort_order = SORT_ASC;//define as a var or else ioncube goes mad
							array_multisort($column, $sort_order, $icons_array);							
							
							//loop through components in current config
							$counter = 1;
							$component_names = array();							
							$components = $class_ua->ua_config['components'];	
							$dropdown_buttons = $class_ua->ua_config['dropdown_buttons'];
							for($n = 0; $n < count($components); $n++){																			
								echo '<tr>';
								echo '<td style="text-align: right;"><input type="checkbox" name="components[c'.$n.'][active]" value="1" checked="checked" /></td>';
								$temp = stripslashes($components[$n][0]);
								$name = str_replace('"','&quot;',$temp);
								echo '<td><input type="text" name="components[c'.$n.'][name]" value="'.$name.'" /></td>';				
								$temp = stripslashes($components[$n][1]);
								$alias = str_replace('"','&quot;',$temp);
								echo '<td><input type="text" name="components[c'.$n.'][alias]" value="'.$alias.'" /></td>';	
								$temp = stripslashes($components[$n][2]);
								$link = str_replace('"','&quot;',$temp);									
								echo '<td><input type="text" name="components[c'.$n.'][link]" value="'.$link.'" /></td>';						
								echo '<td>';
								echo '<select name="components[c'.$n.'][dropdown]">';
								echo '<option value="0">'._pi_ua_lang_no_select_dropdown.'</option>';									
								for($b = 0; $b < count($dropdown_buttons); $b++){									
									echo '<option value="'.$dropdown_buttons[$b][0].'"';
									if(stripslashes($components[$n][3])==$dropdown_buttons[$b][0]){
										echo ' selected="selected"';
									}
									echo ' >';
									$temp_button_name = stripslashes($dropdown_buttons[$b][1]);									
									echo str_replace('"','&quot;',$temp_button_name);									 
									echo '</option>';																	
								}
								echo '</select>';								
								echo '</td>';	
								echo '<td>';
								$selected_icon = '';
								$selected_icon = stripslashes($components[$n][4]);													
								if($selected_icon=='' || $selected_icon=='nothing'){
									$selected_icon = $default_icon;
								}	
								echo '<select name="components[c'.$n.'][icon]" class="icon_select" onchange="change_icon(this.value, \'icon_target_'.$n.'\')">';				
								for($i = 0; $i < count($icons_array); $i++){
									echo '<option value="'.$icons_array[$i][0].'"';	
									echo ' style="background-image: url(\''.$icons_array[$i][0].'\');"';
									if($icons_array[$i][0]==$selected_icon){									
										echo ' selected="selected"';										
									}								
									echo ' >';
									echo $icons_array[$i][1];	
									echo '</option>';	
								}
								echo '</select>';
								echo '&nbsp;&nbsp;';
								echo '<div id="icon_target_'.$n.'" class="icon_preview">';
								echo '<img src="'.$selected_icon.'" alt="" />';
								echo '</div>';	
								echo '</td>';														
								echo '<td><input type="text" name="components[c'.$n.'][order]" size="2" value="'.$counter.'" /></td>';										
								echo '</tr>';	
								//get components from config in an array with just their admin-links
								array_push($component_names, $components[$n][0]);
								$counter = $counter + 1;														
							}						
							
							$components_db = array();						
							$components_gone = array();//to prevent double entries
							foreach($rows as $row){	
								$option = $row->option;	
								//need to get com_categories in, even thou there is no 'option' given for them, and names are different for joomla 1.0.x and 1.5, therefore this workaround							
								if($row->name=='Categories' || $row->name=='Manage Categories'){
									$option = 'com_categories';															
								}
								//filter out pi_itemtypes and com_cpanel
								if(strpos($option, '_pi_itemtype_') || $option=='com_cpanel'){
									$option = '';
								}								
								if($option!=''){
									//no empty links like for 'category'
									if(!in_array($option, $component_names) && !in_array($option, $components_gone)){																			
										$new_component = array($option, $row->name);
										array_push($components_db, $new_component);
										array_push($components_gone, $option);										
									}
								}	
							}
							
							//add com_frontpage, why this is not in Joomla 1.5 database I do not know
							if(!in_array('com_frontpage', $component_names)){								
								array_push($components_db, array('com_frontpage','Frontpage Manager'));
							}							
							
							//add com_sections
							if(!in_array('com_sections&scope=content', $component_names)){								
								//array_push($components_db, array('com_sections&scope=content','Sections manager'));
								array_push($components_db, array('com_sections','Sections manager'));
							}	
							
							//add com_admin
							if(!in_array('com_admin', $component_names)){								
								array_push($components_db, array('com_admin','Help'));
							}	
							
							//add com_trash
							if(!in_array('com_trash', $component_names)){								
								array_push($components_db, array('com_trash','Trash manager'));
							}
							
							//add com_trash
							if(!in_array('com_checking', $component_names)){								
								array_push($components_db, array('com_checkin','Global Check-in (not for managers!)'));
							}		
							
							//sort array by name
							$order = array();
							foreach ($components_db as $key => $row) {
								$order[$key]  = $row[1];    
							}
							$sort_order = SORT_ASC;
							array_multisort($order, $sort_order, $components_db);							
							
							//display components
							foreach ($components_db as $component) {
								echo '<tr>';
								echo '<td style="text-align: right;"><input type="checkbox" name="components[c'.$n.'][active]" value="1" /></td>';							
								$component_link = $component[0];
								if($component_link=='com_categories'){
									$component_link = 'com_categories&section=com_content';
								}elseif($component_link=='com_sections'){
									$component_link = 'com_sections&scope=content';
								}								
								echo '<td><input type="text" name="components[c'.$n.'][name]" value="'.$component[0].'" /></td>';
								echo '<td><input type="text" name="components[c'.$n.'][alias]" value="'.$component[1].'" /></td>';											
								echo '<td><input type="text" name="components[c'.$n.'][link]" value="'.$component_link.'" /></td>';
								echo '<td>';
								echo '<select name="components[c'.$n.'][dropdown]">';
								echo '<option value="0">'._pi_ua_lang_no_select_dropdown.'</option>';									
								for($b = 0; $b < count($dropdown_buttons); $b++){																	
									echo '<option value="'.$dropdown_buttons[$b][0].'"';									
									echo ' >';
									$temp_button_name = stripslashes($dropdown_buttons[$b][1]);									
									echo str_replace('"','&quot;',$temp_button_name);	
									echo '</option>';																	
								}
								echo '</select>';	
								echo '</td>';
								echo '<td>';								
									
								$selected_icon = '';	
								for($i = 0; $i < count($icons_array); $i++){
									if($icons_array[$i][2]==$component_link){
										$selected_icon = $icons_array[$i][0];
									}
								}	
								if($selected_icon==''){
									$selected_icon = $default_icon;
								}	
								echo '<select name="components[c'.$n.'][icon]" class="icon_select" onchange="change_icon(this.value, \'icon_target_'.$n.'\')">';				
								for($i = 0; $i < count($icons_array); $i++){
									echo '<option value="'.$icons_array[$i][0].'"';	
									echo ' style="background-image: url(\''.$icons_array[$i][0].'\');"';
									if($icons_array[$i][0]==$selected_icon){									
										echo ' selected="selected"';										
									}								
									echo ' >';
									echo $icons_array[$i][1];	
									echo '</option>';	
								}
								echo '</select>';
								echo '<div id="icon_target_'.$n.'" class="icon_preview">';
								echo '<img src="'.$selected_icon.'" alt="" />';
								echo '</div>';	
								echo '</td>';									
								echo '<td><input type="text" name="components[c'.$n.'][order]" size="2" value="'.$counter.'" /></td>';										
								echo '</tr>';	
								$counter = $counter + 1;
								$n = $n + 1;
							}										
									
						?>
					</table>
					
				</td>
			</tr>
			<tr>
				<td>&nbsp;																								
				</td>
				<td>
					<?php echo _pi_ua_lang_adminlink; ?> = 
					<?php 
					echo stripslashes(_pi_ua_lang_adminlink_tip); 
					
					echo '<br />';
					echo '<br />';
					
					for($i = 0; $i < count($icons_array); $i++){
						echo '<img src="'.$icons_array[$i][0].'" alt="" /> '.$icons_array[$i][1].'<br />';
					}
					
					?>
				</td>
			</tr>			
			</table>
			</div>
			<div id="module_access">
			<?php $class_ua->not_in_free_version(); ?>
			<table class="adminlist">						
			<tr>
				<th colspan="3" align="left">					
					<?php echo _pi_ua_lang_module_access; ?>
				</th>
			</tr>
			<tr>		
				<td width="300">
					<?php echo _pi_ua_lang_activate_modules; ?>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="activate_modules" value="true" <?php if($class_ua->ua_config['activate_modules']){echo 'checked="checked"';} ?> />
				</td>
				<td>
				</td>
			</tr>
			<tr>		
				<td>
					<?php echo _pi_ua_lang_redirect_from_module_manager; ?>
				</td>
				<td style="white-space: nowrap;">
					<label><input type="checkbox" class="checkbox" name="activate_module_list_redirect" value="true" <?php if($class_ua->ua_config['activate_module_list_redirect']){echo 'checked="checked"';} ?> />
					<?php echo _pi_ua_lang_enable; ?>
					</label><br />
					url : <input type="text" name="module_list_redirect_url" style="width: 300px;" value="<?php echo $class_ua->ua_config['module_list_redirect_url']; ?>" />					
				</td>
				<td>
					<?php echo _pi_ua_lang_redirect_from_module_manager_info; ?>.
				</td>
			</tr>
			</table>
			</div>
			<div id="plugin_access">
			<?php $class_ua->not_in_free_version(); ?>
			<table class="adminlist">						
			<tr>
				<th colspan="2" align="left">					
					<?php echo _pi_ua_lang_plugin_access; ?>
				</th>
			</tr>
			<tr>		
				<td width="300">
					<?php echo _pi_ua_lang_activate_plugins; ?>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="activate_plugins" value="true" <?php if($class_ua->ua_config['activate_plugins']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			</table>
			</div>
			<div id="toolbars">
			<table class="adminlist">						
			<tr>
				<th colspan="2" align="left">
					
					<?php echo _pi_ua_lang_displaytoolbar; ?>
				</th>
			</tr>
			<tr>		
				<td width="300">
					<?php echo _pi_ua_lang_activate_toolbars; ?>
				</td>
				<td>
					<input type="checkbox" class="checkbox" name="activate_toolbars" value="true" <?php if($class_ua->ua_config['activate_toolbars']){echo 'checked="checked"';} ?> />
				</td>
			</tr>
			<tr>		
				<td>
					<span class="editlinktip"><span onmouseover="return overlib('<?php echo addslashes(_pi_ua_lang_use_toolbar_tip); ?>', CAPTION, '<?php echo addslashes(_pi_ua_lang_use_toolbar); ?>', BELOW, RIGHT, WIDTH, 400);" onmouseout="return nd();" ><?php echo _pi_ua_lang_use_toolbar; ?></span></span>
				</td>
				<td>
					<input type="checkbox" name="use_toolbar" value="true" <?php if($class_ua->ua_config['use_toolbar']){echo 'checked="checked"';} ?> /><br /><img src='components/com_pi_admin_user_access/images/toolbar.gif' />
				</td>
			</tr>
			<tr>		
				<td>
					<?php echo _pi_ua_lang_display_toolbar_superadmin; ?>
				</td>
				<td>
					<input type="checkbox" name="display_toolbar_superadmin" value="true" <?php if($class_ua->ua_config['display_toolbar_superadmin']){echo 'checked="checked"';} ?> />
				</td>
			</tr>	
			<tr>		
				<td>
					<?php echo _pi_ua_lang_display_extra_buttons_superadmin; ?>
				</td>
				<td>
					<input type="checkbox" name="extra_buttons_super_admin" value="true" <?php if($class_ua->ua_config['extra_buttons_super_admin']){echo 'checked="checked"';} ?> />
				</td>
			</tr>		
			<tr>		
				<td>
					<?php echo _pi_ua_lang_dropdown_menu_configuration; ?>
				</td>
				<td>
					<div class="row">
						<?php echo _pi_ua_lang_dropdown_menu_configuration_info; ?>
					</div>
					<div class="row">
						<span class="sidestep2 b"><?php echo _pi_ua_lang_text_on_dropdown_button; ?></span><span class="b"><?php echo _pi_ua_lang_order; ?></span>
					</div>
					<?php			
					
					//loop through dropdown_buttons from config
					$counter = 1;
					$dropdown_buttons_from_config = $class_ua->ua_config['dropdown_buttons'];
					//$menus_on_page = array();
					for($m = 0; $m < count($dropdown_buttons_from_config); $m++){
						echo '<div class="row">';						
						echo '<span class="sidestep2">';
						$temp = stripslashes($dropdown_buttons_from_config[$m][1]);
						//echo 'temp='.$temp;
						$dropdown_button_name = str_replace('"','&quot;',$temp);
						//echo 'button_name='.$button_name;
						echo '<input type="text" name="dropdown_buttons[b'.$m.'][name]" value="'.$dropdown_button_name.'" />';
						
						echo '<input type="hidden" name="dropdown_buttons[b'.$m.'][id]" value="'.$dropdown_buttons_from_config[$m][0].'" />';
						echo '</span>';				
						echo '<input type="text" name="dropdown_buttons[b'.$m.'][order]" size="2" value="'.$counter.'" />';						
						echo '</div>';						
						$counter = $counter + 1;	
					}
					
					//2 extra rows for extra fields
					for($p = $m; $p < $m+2; $p++){			
						echo '<div class="row">';						
						echo '<span class="sidestep2">';
						echo '<input type="text" name="dropdown_buttons[b'.$p.'][name]" value="" />';
						echo '<input type="hidden" name="dropdown_buttons[b'.$p.'][id]" value="" />';
						echo '</span>';				
						echo '<input type="text" name="dropdown_buttons[b'.$p.'][order]" size="2" value="'.$counter.'" />';						
						echo '</div>';						
						$counter = $counter + 1;	
					}
					
					?>
				</td>
			</tr>
			
			
			<tr>		
				<td>&nbsp;
								
				</td>
				<td>
					
				</td>
			</tr>
			
			
			
			
			<tr>		
				<td>
					<?php echo _pi_ua_lang_extra_buttons; ?>
				</td>
				<td>
					<?php echo _pi_ua_lang_extra_buttons_info; ?>
					<br /><br />
					<?php echo _pi_ua_lang_example_code; ?><br />					
					<textarea name="example" class="extra_button_field" style="height: 130px;" cols="80" /><li><a href="index.php?option=com_menus&task=view&menutype=mainmenu" style="background-image: url('templates/khepri/images/menu/icon-16-component.png');">main menu</a></li>

or

<li><a href="javascript: var winl = (screen.width - 900) / 2;
var wint = (screen.height - 550) / 2;
winprops = 'height=550, width=900, top='+wint+', left='+winl+', scrollbars=yes, resizable';
popUpWin = open('../manual.htm', 'manual', winprops);	
popUpWin.window.focus();">manual</a></li>

or

popUpWin = open('index2.php?option=com_manual&amp;task=pages_and_items', 'manual', winprops);	


					</textarea>
				</td>
			</tr>
			<?php
						
				$extra_buttons = $class_ua->ua_config['extra_buttons'];
				for($n = 0; $n < count($extra_buttons); $n++){
					echo '<tr>';
					echo '<td class="align_right">';
					echo $n+1;
					echo '</td>';
					echo '<td>';					
					echo '<textarea name="extra_buttons[]" class="extra_button_field" cols="80" />';					
					echo $extra_buttons[$n];
					echo '</textarea>';					
					echo '</td>';
					echo '</tr>';
				}
			
				
				//2 extra rows for extra fields
				for($p = $n; $p < $n+2; $p++){			
					echo '<tr>';
					echo '<td class="align_right">';
					echo $p+1;
					echo '</td>';
					echo '<td>';					
					echo '<textarea name="extra_buttons[]" class="extra_button_field" cols="80" />';					
					echo '</textarea>';					
					echo '</td>';
					echo '</tr>';
				}
					
			?>
			<tr>		
				<td colspan="2">&nbsp;
					
				</td>
			</tr>
			</table>
			</div>
			<div id="aec">
			<table class="adminlist">						
			<tr>
				<th colspan="2" align="left">
					
					subscriptions and paid access 
				</th>
			</tr>
			<tr>		
				<td width="550">
					You can connect your AUA-usergroups to subscription-plans in component AEC. So you can offer paid access to subscribers.<br /><br />
				</td>
				<td>&nbsp;
					
				</td>
			</tr>
			</table>
			</div>
		</div>
</form>
<?php
$class_ua->display_footer();
?>