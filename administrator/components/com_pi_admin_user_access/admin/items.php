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

if(!$class_ua->ua_config['display_items'] && $class_ua->user_type!='Super Administrator'){
	die('Restricted access');
}

//header and nav
$class_ua->echo_header();

//get usergroups from db
$class_ua->db->setQuery("SELECT * FROM #__pi_aua_usergroups ORDER BY name");
$usergroups = $class_ua->db->loadObjectList();

//get item access from db
$class_ua->db->setQuery("SELECT itemid_usergroupid FROM #__pi_aua_items");
$access_items = $class_ua->db->loadResultArray();

/*
//get items from db
$class_ua->db->setQuery("SELECT id, title FROM #__content  WHERE state='-1' OR state='0' OR state='1' ORDER BY title");
$items = $class_ua->db->loadObjectList();
*/

//get items from db
$class_ua->db->setQuery("SELECT i.id as id, i.title as title, c.title as cattitle, s.title as sectitle FROM #__content AS i "
. "LEFT JOIN #__categories AS c "
. "ON i.catid=c.id "
. "LEFT JOIN #__sections AS s "
. "ON c.section=s.id "
." WHERE state='-1' OR state='0' OR state='1' ORDER BY title ");
$items = $class_ua->db->loadObjectList();

global $aua_item_index;

//get item-index from db
if($class_ua->pi_installed){
	$class_ua->db->setQuery("SELECT item_id, itemtype FROM #__pi_item_index");
	$aua_item_index = $class_ua->db->loadObjectList();
}
//print_r($aua_item_index);

/*
function aua_get_itemtype($item_id){
	global $aua_item_index, $class_ua;	
	foreach($aua_item_index as $item){
		if($aua_item_index->item_id==$item_id){
			$item_type = $aua_item_index->itemtype;	
			break;		
		}		
	}
	if(!$item_type){
		$item_type = 'text';
	}
	$item_type_translated = $class_ua->translate_item_type($item_type);
	return $item_type_translated;
}

//$class_ua->db->setQuery( "SELECT c.id, c.title, i.itemtype"
$class_ua->db->setQuery( "SELECT c.id, c.title, i.itemtype"
. "\nFROM #__content AS c"		
. "\nLEFT JOIN #__pi_item_index AS i"
. "\nON c.id=i.item_id"
. "\nWHERE (c.state='0' OR c.state='1')"
. "\nORDER BY c.title ASC"
);		
$items = $class_ua->db->loadObjectList();
*/

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();



	//make javascript array from items
	$javascript_array_items = 'var items = new Array(';
	$first = true;
	foreach($items as $item){		
		if($first){
			$first = false;
		}else{
			$javascript_array_items .= ',';
		}
		$javascript_array_items .= "'".$item->id."'";
	}	
	$javascript_array_items .= ');';
		
?>
<script language="javascript" type="text/javascript">

<?php echo $javascript_array_items."\n"; ?>

function select_all(usergroup_id, select_all_id){
	action = document.getElementById(select_all_id).checked;	
	for (i = 0; i < items.length; i++){
		box_id = items[i]+'__'+usergroup_id;
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
	<input type="hidden" name="task" value="items" />	
	<?php $class_ua->not_in_free_version(); ?>	
<p>
	<?php echo _pi_ua_lang_item_info; ?>
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
		
		//row with select_all checkboxes
		echo '<tr class="row1">';
		echo '<td>'._pi_ua_lang_selectall.'</td>';
		foreach($usergroups as $usergroup){
			echo '<td style="text-align:center;"><input type="checkbox" name="checkall[]" value="" id="checkall_'.$usergroup->id.'" onclick="select_all('.$usergroup->id.',this.id);" /></td>';
		}
		echo '</tr>';
			
		$counter = 0;		
		foreach($items as $item){						
			echo '<tr class="row'.$k.'"><td>';
			
			echo $class_ua->aua_truncate_string($item->title, 50);
			
			//display itemtype
			if($class_ua->ua_config['display_itemtype_in_list']){
				echo ' ('.$class_ua->get_itemtype($item->id, $aua_item_index).')';				
			}	
					
			//display category
			if(!isset($class_ua->ua_config['display_category_in_list'])){
				$class_ua->ua_config['display_category_in_list'] = 0;
			}
			if($class_ua->ua_config['display_category_in_list']){	
				$cattitle = $item->cattitle;				
				if($cattitle==''){
					$cattitle = 'uncategorized';
				}else{
					$cattitle = $class_ua->aua_truncate_string($cattitle, 30);
				}			
				echo ' (category='.$cattitle.')';
			}
			
			//display section
			if(!isset($class_ua->ua_config['display_section_in_list'])){
				$class_ua->ua_config['display_section_in_list'] = 0;
			}
			if($class_ua->ua_config['display_section_in_list'] && $item->cattitle!=''){				
				echo ' (section='.$class_ua->aua_truncate_string($item->sectitle, 30).')';				
			}			
			
			echo '</td>';			
			foreach($usergroups as $usergroup){
				$checked = '';
				if (in_array($item->id.'__'.$usergroup->id, $access_items)) {
					$checked = 'checked="checked"';
				}
				echo '<td style="text-align:center;"><input type="checkbox" name="item_access[]" id="'.$item->id.'__'.$usergroup->id.'" value="'.$item->id.'__'.$usergroup->id.'" '.$checked.' /></td>';
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