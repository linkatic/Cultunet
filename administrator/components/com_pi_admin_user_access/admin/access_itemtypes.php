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

if(!$class_ua->ua_config['display_itemtypes'] && $class_ua->user_type!='Super Administrator'){
	die('Restricted access');
}

//header and nav
$class_ua->echo_header();

//get usergroups from db
$class_ua->db->setQuery("SELECT * FROM #__pi_aua_usergroups ORDER BY name");
$usergroups = $class_ua->db-> loadObjectList();

//get itemtype access from db
$class_ua->db->setQuery("SELECT type_groupid FROM #__pi_aua_itemtypes");
$access_itemtypes = $class_ua->db->loadResultArray();

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();

//make a new array from installed itemtypes						
if( defined('_JEXEC') ){
	//joomla 1.5
	jimport( 'joomla.filesystem.folder' );
	$dir_itemtypes = JFolder::folders(dirname(__FILE__).'/../../');
}else{
	//joomla 1.0.x							
	$dir_itemtypes = mosReadDirectory(dirname(__FILE__).'/../../');
}												
$installed_itemtypes = array();											
foreach($dir_itemtypes as $itemtype){							
	if(strpos($itemtype, 'om_pi_itemtype_')){			
		$itemtype = substr($itemtype, 26, 300);
		array_push($installed_itemtypes, $itemtype);			
	}
}

//add 'text' and 'html' to array as those are itemtypes which are embedded in Pages-and-Items
array_push($installed_itemtypes, 'text');
array_push($installed_itemtypes, 'html');	
array_push($installed_itemtypes, 'other_item');		

					

//make array with extra column for alias and custom-or-not
$all_itemtypes = array();
$itemtype_array = 0;
foreach ($installed_itemtypes as $itemtype) {							
	//$itemtype_array = array($itemtype, $class_ua->translate_item_type($itemtype), 0);
	$itemtype_array = array($itemtype, $itemtype, 0);
	//$itemtype_array = array($itemtype, 'soep', 0);
	array_push($all_itemtypes, $itemtype_array);	  
}	

//get customitemtypes only if pages and items version is at least 1.2.5
if($class_ua->pi_installed && $class_ua->pi_config['cit']){
	//get customitemtypes
	$class_ua->db->setQuery("SELECT id, name FROM #__pi_customitemtypes"  );
	$custom_itemypes = $class_ua->db->loadObjectList();
	foreach($custom_itemypes as $custom_itemype){	
		$itemtype_array = array($custom_itemype->name, $custom_itemype->name, $custom_itemype->id);	
		array_push($all_itemtypes, $itemtype_array);
	}	
}	

//order itemtype-array on language-specific alias
foreach ($all_itemtypes as $key => $row){
	$order[$key]  = strtolower($row[1]);  	   
}
$sort_order = SORT_ASC;
array_multisort($order, $sort_order, $all_itemtypes);	

//make javascript array from itemtypes
$javascript_array_itemtypes = 'var itemtypes = new Array(';
$first = true;						
foreach($all_itemtypes as $itemtype){		
	if($first){
		$first = false;
	}else{
		$javascript_array_itemtypes .= ',';
	}
	if($itemtype[2]){
		//$custom_itemtype_name = 'custom_'.$type[2];
		$javascript_array_itemtypes .= "'".'custom_'.$itemtype[2]."'";
	}else{		
		$javascript_array_itemtypes .= "'".$itemtype[0]."'";
	}		
}
$javascript_array_itemtypes .= ');';
	
?>
<script language="javascript" type="text/javascript">

<?php echo $javascript_array_itemtypes."\n"; ?>

function select_all(usergroup_id, select_all_id){
	action = document.getElementById(select_all_id).checked;	
	for (i = 0; i < itemtypes.length; i++){
		box_id = itemtypes[i]+'__'+usergroup_id;
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
	<input type="hidden" name="task" value="access_itemtypes" />
	<?php $class_ua->not_in_free_version(); ?>		
<p>
	<?php echo _pi_ua_lang_itemtype_info; ?>
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
		if($class_ua->pi_installed){					
			$k = 1;		
			//row with select_all checkboxes
			echo '<tr class="row1">';
			echo '<td>'._pi_ua_lang_selectall.'</td>';
			foreach($usergroups as $usergroup){
				echo '<td style="text-align:center;"><input type="checkbox" name="checkall[]" value="" id="checkall_'.$usergroup->id.'" onclick="select_all('.$usergroup->id.',this.id);" /></td>';
			}
			echo '</tr>';
				
			$counter = 0;				
			foreach($all_itemtypes as $type){								
				echo '<tr class="row'.$k.'"><td>';
				echo $type[1];
				$custom_itemtype_name = 0;	
				if($type[2]){
					$custom_itemtype_name = 'custom_'.$type[2];
				}			
				if(!in_array($type[0], $class_ua->pi_config['itemtypes']) && !in_array($custom_itemtype_name, $class_ua->pi_config['itemtypes'])){
					echo ' ('._pi_ua_lang_not_published.')';
				}
				echo '</td>';			
				foreach($usergroups as $usergroup){
					$checked = '';
					if (in_array($type[0].'__'.$usergroup->id, $access_itemtypes) || in_array($custom_itemtype_name.'__'.$usergroup->id, $access_itemtypes)) {
						$checked = 'checked="checked"';
					}
					if($type[2]){					
						$field_id = $custom_itemtype_name.'__'.$usergroup->id;
					}else{
						$field_id = $type[0].'__'.$usergroup->id;
					}
					echo '<td style="text-align:center;"><input type="checkbox" name="itemtypeAccess[]" id="'.$field_id.'" value="'.$field_id.'" '.$checked.' /></td>';
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
		}
	?>			
</table>
</form>
<?php

$class_ua->display_footer();

?>