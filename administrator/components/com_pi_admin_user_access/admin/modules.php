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

if(!$class_ua->ua_config['display_modules'] && $class_ua->user_type!='Super Administrator'){
	die('Restricted access');
}

//header and nav
$class_ua->echo_header();

//get usergroups from db
$class_ua->db->setQuery("SELECT * FROM #__pi_aua_usergroups ORDER BY name");
$usergroups = $class_ua->db->loadObjectList();

//get module access from db
$class_ua->db->setQuery("SELECT module_groupid FROM #__pi_aua_modules");
$access_modules = $class_ua->db->loadResultArray();

//get modules from database
$class_ua->db->setQuery("SELECT id, title, module FROM #__modules ORDER BY title");
$modules = $class_ua->db->loadObjectList();

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();

//make javascript array from components

$javascript_array_modules = 'var components = new Array(';
$first = true;
foreach($modules as $module){	
	if($first==true){
		$first = false;
	}else{
		$javascript_array_modules .= ',';
	}
	$javascript_array_modules .= "'".$module->id."'";
}	
$javascript_array_modules .= ');';


?>
<script language="javascript" type="text/javascript">

<?php echo $javascript_array_modules."\n"; ?>

function select_all(usergroup_id, select_all_id){
	action = document.getElementById(select_all_id).checked;	
	for (i = 0; i < components.length; i++){
		box_id = components[i]+'__'+usergroup_id;
		if(action==true){
			document.getElementById(box_id).checked = true;
		}else{
			document.getElementById(box_id).checked = false;
		}
	}	
}

</script>
<form name="adminForm" method="post" action="">
		<input type="hidden" name="option" value="com_pi_admin_user_access" />
		<input type="hidden" name="task" value="modules" />	
	<?php echo '<p>'._pi_ua_lang_module_info.'.</p>'; ?>	
	<?php $class_ua->not_in_free_version(); ?>
<table class="adminlist">
	<tr>		
		<th align="left">&nbsp;
						
		</th>
		<?php			
			$class_ua->loop_usergroups($usergroups);			
		?>		
		
	</tr>
		
	<?php
		
		//row with select_all checkboxes
		echo '<tr class="row0">';
		echo '<td align="left">'._pi_ua_lang_selectall.'</td>';
		foreach($usergroups as $usergroup){
			echo '<td style="text-align:center;"><input type="checkbox" name="checkall[]" value="" id="checkall_'.$usergroup->id.'" onclick="select_all('.$usergroup->id.',this.id);" /></td>';
		}
		echo '</tr>';
				
		$k = 1;	
		$counter = 0;		
		//for($n = 0; $n < count($class_ua->ua_config['components']); $n++){
		foreach($modules as $module){	
			//$component = each($pi_ua_config['components']);			
			echo '<tr class="row'.$k.'"><td>'.$module->title.' ('.$module->module.')</td>';			
			foreach($usergroups as $usergroup){
				$checked = '';
				if (in_array($module->id.'__'.$usergroup->id, $access_modules)) {
					$checked = 'checked="checked"';
				}
				echo '<td align="center"><input type="checkbox" name="module_access[]" id="'.$module->id.'__'.$usergroup->id.'" value="'.$module->id.'__'.$usergroup->id.'" '.$checked.' /></td>';
			}
			echo '</tr>';
			if($k==1){
				$k = 0;
			}else{
				$k = 1;
			}
			if($counter==15){
				echo '<tr><th>&nbsp;</th>';	
				$class_ua->loop_usergroups($usergroups);
				echo '</tr>';
				$counter = 0;
			}
			$counter = $counter+1;		
		}
	
	?>
			
</table>
</form>
<?php
$class_ua->display_footer();
?>