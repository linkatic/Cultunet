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

if(!$class_ua->ua_config['display_toolbars'] && $class_ua->user_type!='Super Administrator'){
	die('Restricted access');
}

//header and nav
$class_ua->echo_header();

//get usergroups from db
$class_ua->db->setQuery("SELECT * FROM #__pi_aua_usergroups ORDER BY name");
$usergroups = $class_ua->db->loadObjectList();

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();
		
?>

<script language="javascript" type="text/javascript">

function select_all(usergroup_id, select_all_id){	
	action = document.getElementById(select_all_id).checked;	
	for (i = 0; i < 2; i++){
		box_id = 'b_'+i+'_'+usergroup_id;
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
	<input type="hidden" name="task" value="toolbars" />		

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
	echo '<td>'._pi_ua_lang_selectall.'</td>';
	foreach($usergroups as $usergroup){
		echo '<td style="text-align:center;"><input type="checkbox" name="checkall[]" value="" id="checkall_'.$usergroup->id.'" onclick="select_all('.$usergroup->id.',this.id);" /></td>';
	}
	echo '</tr>';
	
	//user access toolbar					
	echo '<tr class="row1"><td>'._pi_ua_lang_useraccesstoolbar.'</td>';			
	foreach($usergroups as $usergroup){					
		echo '<td style="text-align:center;"><input type="checkbox" name="display_ua_toolbar[]" id="b_0_'.$usergroup->id.'" value="'.$usergroup->id.'" ';
		if ($usergroup->ua_toolbar) {
			echo 'checked="checked"';
		}
		echo ' /></td>';				
	}				
	echo '</tr>';	
	
	//joomla toolbar
	echo '<tr class="row0"><td>'._pi_ua_lang_joomlatoolbar.'</td>';			
	foreach($usergroups as $usergroup){					
		echo '<td style="text-align:center;"><input type="checkbox" name="display_joomla_toolbar[]" id="b_1_'.$usergroup->id.'" value="'.$usergroup->id.'" ';
		if ($usergroup->j_toolbar) {
			echo 'checked="checked"';
		}
		echo ' /></td>';				
	}				
	echo '</tr>';
	
	echo '<tr>';
	echo '<td colspan="'.(count($usergroups)+1).'">';
	echo '&nbsp;';
	echo '</td>';
	echo '</tr>';
	
	//extra buttons
	$extra_buttons = $class_ua->ua_config['extra_buttons'];	
	//print_r($extra_buttons);	
	for($n = 0; $n < count($extra_buttons); $n++){
		echo '<tr class="row0"><td>';
		echo _pi_ua_lang_extra_button.' ';
		$button_number = $n+1;
		echo $button_number;
		echo '</td>';		
		foreach($usergroups as $usergroup){					
			echo '<td style="text-align:center;"><input type="checkbox" name="extra_buttons[]" value="'.$button_number.'_'.$usergroup->id.'" ';
			//get extra data
			$extra_buttons_array = explode(',',$usergroup->extra);
			if (in_array($button_number, $extra_buttons_array)) {
				echo 'checked="checked"';
			}
			echo ' />';			
			echo '</td>';				
		}					
		echo '</tr>';
	}
	

	?>			
</table>
</form>
<?php

$class_ua->display_footer();

?>