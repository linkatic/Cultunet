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

if(!$class_ua->ua_config['display_pagesaccess'] && $class_ua->user_type!='Super Administrator'){
	die('Restricted access');
}

//header and nav
$class_ua->echo_header();

//globals
global $usergroups, $accessPages, $menuItems, $aua_menu_items;

//$menutypes = $class_ua->ua_config['menutypes'];
//$menutypes2 = $menutypes;

//get menuitems from db
$class_ua->db->setQuery("SELECT id, menutype, name, parent, ordering FROM #__menu WHERE (published='0' OR published='1') ORDER BY ordering ASC");
//$menuItems = $class_ua->db->loadObjectList();
$aua_menu_items = $class_ua->db->loadObjectList();

//print_r($menuItems);
//get usergroups from db
$class_ua->db->setQuery("SELECT * FROM #__pi_aua_usergroups ORDER BY name");
//$usergroups = $class_ua->db->loadObjectList();
$aua_usergroups = $class_ua->db->loadObjectList();

//get access pages from db in the right kind of array
$class_ua->db->setQuery("SELECT pageid_usergroupid FROM #__pi_aua_access_pages");
$page_access = $class_ua->db->loadResultArray();

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();

?>
<script language="javascript" type="text/javascript">

<?php


//make javascript arrays from menuItems per menutype
for($n = 0; $n < count($class_ua->ua_config['menutypes']); $n++){
	$javascript_menu = 'var menuarray_'.$class_ua->ua_config['menutypes'][$n][0].' = new Array(';
	$first = true;
	foreach($aua_menu_items as $page){
		if($page->menutype==$class_ua->ua_config['menutypes'][$n][0]){
			if($first){
				$first = false;
			}else{
				$javascript_menu .= ',';
			}
			$javascript_menu .= '"'.$page->id.'"';
		}
	}
	$javascript_menu .= ');';
	echo $javascript_menu."\n";
}

//make javascript array from usergroups
$javascript_array_usergroups = 'var usergroups = new Array(';
$first = true;
foreach($aua_usergroups as $usergroup){
	if($first){
		$first = false;
	}else{
		$javascript_array_usergroups .= ',';
	}
	$javascript_array_usergroups .= '"'.$usergroup->id.'"';
}
$javascript_array_usergroups .= ');';
echo $javascript_array_usergroups."\n";

?>

function select_all(menutype_array_name, usergroup_id, select_all_id){
	action = document.getElementById(select_all_id).checked;	
	pages = eval(menutype_array_name);	
	for (i = 0; i < pages.length; i++){
		box_id = pages[i]+'_0_'+usergroup_id;
		if(action==true){
			//alert(box_id);
			document.getElementById(box_id).checked = true;
		}else{
			document.getElementById(box_id).checked = false;
		}
	}	
}

</script>
<form name="adminForm" method="post" action="">
		<input type="hidden" name="option" value="com_pi_admin_user_access" />
		<input type="hidden" name="task" value="page_access_save" />			
<p>
	<?php echo _pi_ua_lang_pages_info.'.'; ?>
</p>
<?php
//print_r($menutypes);
	
?>
<table class="adminlist">
	<tr>		
		<th align="left">&nbsp;				
		</th>
		<?php			
			$class_ua->loop_usergroups($aua_usergroups);			
		?>		
		
	</tr>
		
<?php	
/*
$k = '0';

function fetch_menuitems($id) {
	global $menuItems;
	$menu = null;
	$menu = array();
	
	if($menuItems!=''){
		foreach($menuItems as $menuItem){
			if($menuItem->parent==$id){
				$itemArray = array($menuItem->id, $menuItem->name, $menuItem->parent, $menuItem->menutype);				
				array_push($menu, $itemArray);
			}
		}	
		return $menu;
	}
}

function look_for_children($menu,$level, $current_menutype) {		
	if (!is_array($menu)) return; 		
	$level = $level + 1;		
	foreach ($menu as $m){		
		if($m[3]==$current_menutype){
			show_menu_item($m,$level,$current_menutype);
		}
	}  
	$level = $level - 1;   	
}

function show_menu_item($m,$level,$current_menutype) {
	global $k, $usergroups, $accessPages;
	$padding = $level*2;
	echo '<tr class="row'.$k.'"><td style="padding-left: '.$padding.'0px">';
	echo $m[1];
	echo "</td>";
	foreach($usergroups as $usergroup){
		$checked = '';
		if (in_array($m[0].'_'.$usergroup->id, $accessPages)) {
			$checked = 'checked="checked"';
		}
		echo '<td align="center"><input type="checkbox" name="page_access[]" value="'.$m[0].'_'.$usergroup->id.'" id="'.$m[0].'_0_'.$usergroup->id.'" '.$checked.'  /></td>';
	}
	echo '</tr>';
	if($k=='1'){
		$k = '0';
	}else{
		$k = '1';
	}
	if ($menu = fetch_menuitems($m[0])){
		look_for_children($menu,$level,$current_menutype);
	}
}

//echo '<tr><td>'.print_r($menutypes).'</td></tr>';

	
	
$first_menutype = true;	
foreach($menutypes2 as $menutype2){

		$row_menutype2 = each($menutypes2);	
		$current_menutype = $row_menutype2[key];
		//echo '<tr><td>'.$menutype.$row_menutype2[key].'</td></tr>';
		
	if ($menu = fetch_menuitems(0)){
		//$row_menutype = each($menutypes);			
		//$current_menutype = $row_menutype[key];
		//echo '<tr><td>'.$menutype.$row_menutype[key].'</td></tr>';
		if(!$first_menutype){
			echo '<tr><td colspan="'.(count($usergroups)+1).'">&nbsp;</td></tr>';
		}						
		echo '<tr><td colspan="'.(count($usergroups)+1).'" align="left" style="font-size: 1.5em; font-weight: bold;">'.$menutype2.'</td></tr>';
		//select-all boxes
		
		echo '<tr class="row1"><td>'._pi_ua_lang_selectall.'</td>';
		foreach($usergroups as $usergroup){
			echo '<td align="center">';
			echo '<input type="checkbox" name="checkall[]" value="" id="'.$current_menutype.'_'.$usergroup->id.'" onclick="select_all(\'menuarray_'.$current_menutype.'\','.$usergroup->id.',this.id);" />';
			echo '</td>';
		}
		echo '</tr>';
		
		$first_menutype = false;			
		//check if menutype is used at all in 			
		foreach ($menu as $m){				
			if($m[3]==$current_menutype){							
				look_for_children($menu, 0, $current_menutype);
				break;
			}
		}			
	}
}

*/
	
$aua_page_access_superthing = new aua_page_access_superthing();
$aua_page_access_superthing->aua_page_access_init($aua_usergroups, $class_ua->ua_config, $aua_menu_items, $page_access);
$aua_page_access_superthing->aua_echo_menus();
	
class aua_page_access_superthing{
	
	//var $level;
	var $usergroups;
	var $config;
	var $menu_items;
	var $aua_current_menu_type;	
	var $current_parent;
	var $page_access;
	
	function aua_page_access_init($aua_usergroups, $aua_config, $aua_menu_items, $page_access){
		$this->usergroups = $aua_usergroups;
		$this->config = $aua_config;
		//$this->level = 0;
		$this->menu_items = $aua_menu_items;
		$this->page_access = $page_access;
	}
	
	function fetch_menuitems($id, $menu_type) {		
		$menu = null;
		$menu = array();
		
		foreach($this->menu_items as $menuItem){
			if($menuItem->parent==$id && $menuItem->menutype==$menu_type){
				$itemArray = array($menuItem->id, $menuItem->name, $menuItem->parent);				
				array_push($menu, $itemArray);
			}
		}	
		return $menu;
	}

	function look_for_children($menu,$level) {
		if (!is_array($menu)) return;  
		$level = $level + 1;
		foreach ($menu as $m){
			$this->show_menu_item($m,$level);
		}  
		$level = $level - 1;   	
	}
	
	function show_menu_item($m,$level) {		
		$padding = $level*2;
		echo '<tr><td style="text-align: left; padding-left: '.$padding.'0px">';
		echo $m[1];
		echo '</td>';
		foreach($this->usergroups as $usergroup){
			$checked = '';
			if (in_array($m[0].'_'.$usergroup->id, $this->page_access)) {
				$checked = 'checked="checked"';
			}
			echo '<td align="center"><input type="checkbox" name="page_access[]" value="'.$m[0].'_'.$usergroup->id.'" id="'.$m[0].'_0_'.$usergroup->id.'" '.$checked.'  /></td>';
		}
		echo '</tr>';
		if ($menu = $this->fetch_menuitems($m[0],$this->aua_current_menu_type)){
			$this->look_for_children($menu,$level);
		}
	}
	
	function aua_echo_menus(){
		for($n = 0; $n < count($this->config['menutypes']); $n++){
			echo '<tr><td colspan="'.(count($this->usergroups)+1).'">&nbsp;</td></tr>';
			echo '<tr><td style="font-size: 1.5em; font-weight: bold;text-align: left;" colspan="'.(count($this->usergroups)+1).'">';
			echo $this->config['menutypes'][$n][1];
			echo '</td></tr>';
			$this->aua_current_menu_type = $this->config['menutypes'][$n][0];
			echo '<tr class="row1"><td>'._pi_ua_lang_selectall.'</td>';
			foreach($this->usergroups as $usergroup){
				echo '<td align="center">';
				echo '<input type="checkbox" name="checkall[]" value="" id="'.$this->aua_current_menu_type.'_'.$usergroup->id.'" onclick="select_all(\'menuarray_'.$this->aua_current_menu_type.'\','.$usergroup->id.',this.id);" />';
				echo '</td>';
			}
			echo '</tr>';				
			$this->look_for_children($this->fetch_menuitems(0,$this->aua_current_menu_type),0);
		}
	}
	
	
}




?>
			
</table>
</form>
<?php
$class_ua->display_footer();
?>