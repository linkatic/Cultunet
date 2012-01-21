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

if(!$class_ua->ua_config['display_sections'] && $class_ua->user_type!='Super Administrator'){
	die('Restricted access');
}

//header and nav
$class_ua->echo_header();

//get usergroups from db
$class_ua->db->setQuery("SELECT * FROM #__pi_aua_usergroups ORDER BY name");
$usergroups = $class_ua->db-> loadObjectList();

//get section access from db
$class_ua->db->setQuery("SELECT section_groupid FROM #__pi_aua_sections");
$access_sections = $class_ua->db->loadResultArray();

//get sections from db
$class_ua->db->setQuery("SELECT id, title FROM #__sections ORDER BY title");
$sections = $class_ua->db->loadObjectList();

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();



	//make javascript array from sections
	$javascript_array_sections = 'var sections = new Array(';
	$first = true;
	foreach($sections as $section){		
		if($first){
			$first = false;
		}else{
			$javascript_array_sections .= ',';
		}
		$javascript_array_sections .= "'".$section->id."'";
	}	
	$javascript_array_sections .= ');';
		
?>
<script language="javascript" type="text/javascript">

<?php echo $javascript_array_sections."\n"; ?>

function select_all(usergroup_id, select_all_id){
	action = document.getElementById(select_all_id).checked;	
	for (i = 0; i < sections.length; i++){
		box_id = sections[i]+'__'+usergroup_id;
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
	<input type="hidden" name="task" value="sections" />	
	<?php $class_ua->not_in_free_version(); ?>	
<p>
	<?php echo _pi_ua_lang_sections_info; ?>
</p>
<table class="adminlist">
	<tr>		
		<th align="left">&nbsp;
						
		</th>
		<?php			
			$class_ua->loop_usergroups($usergroups);			
		?>			
	</tr>
		
	<?php
							
		$k = 1;		
		$counter = 0;	
		//row with select_all checkboxes
		echo '<tr class="row1">';
		echo '<td>'._pi_ua_lang_selectall.'</td>';
		foreach($usergroups as $usergroup){
			echo '<td style="text-align:center;"><input type="checkbox" name="checkall[]" value="" id="checkall_'.$usergroup->id.'" onclick="select_all('.$usergroup->id.',this.id);" /></td>';
		}
		echo '</tr>';
				
		foreach($sections as $section){						
			echo '<tr class="row'.$k.'"><td>'.$section->title.'</td>';			
			foreach($usergroups as $usergroup){
				$checked = '';
				if (in_array($section->id.'__'.$usergroup->id, $access_sections)) {
					$checked = 'checked="checked"';
				}
				echo '<td style="text-align:center;"><input type="checkbox" name="sectionAccess[]" id="'.$section->id.'__'.$usergroup->id.'" value="'.$section->id.'__'.$usergroup->id.'" '.$checked.' /></td>';
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