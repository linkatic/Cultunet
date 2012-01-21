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

if(!$class_ua->ua_config['display_users'] && $class_ua->user_type!='Super Administrator'){
	die('Restricted access');
}

//header and nav
$class_ua->echo_header();

echo '<p>'._pi_ua_lang_user_info.'<br />';
echo _pi_ua_lang_user_info2.'</p>';

global $usergroups;

$where = array();
$where[] = "(u.usertype='Manager' OR u.usertype='Administrator' OR u.usertype='Author' OR u.usertype='Editor' OR u.usertype='Publisher')";

$search = $class_ua->get_var('search','','post');
if($search){
	$where[] = '((u.name LIKE '.$class_ua->db->Quote( '%'.$class_ua->db->getEscaped( $search, true ).'%', false ).') OR (u.username LIKE '.$class_ua->db->Quote( '%'.$class_ua->db->getEscaped( $search, true ).'%', false ).') OR (u.email LIKE '.$class_ua->db->Quote( '%'.$class_ua->db->getEscaped( $search, true ).'%', false ).'))';
}

$joomla_group_filter = $class_ua->get_var('joomla_group_filter','','post');
if($joomla_group_filter){
	$where[] = '(u.gid = '.$joomla_group_filter.')';
}

$usergroup_filter = $class_ua->get_var('usergroup_filter','','post');
if($usergroup_filter){
	$where[] = '(i.group_id = '.$usergroup_filter.')';
}

$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

//get users from db
$class_ua->db->setQuery( "SELECT u.id, u.name, u.username, u.email, u.usertype, u.gid, i.group_id AS pi_usergroup"
. "\nFROM #__users AS u"		
. "\nLEFT JOIN #__pi_aua_userindex AS i"
. "\nON i.user_id=u.id"
. $where
. "\nORDER BY username"
);		
$rows = $class_ua->db-> loadObjectList();

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();


?>

<div style="text-align: right;">
<form name="searchform" method="post" action="index2.php?option=com_pi_admin_user_access&task=users">
<?php
echo _pi_ua_lang_filter; ?>:
<input type="text" name="search" id="search" value="<?php echo $search;?>" class="text_area" onchange="document.searchform.submit();" />
<button onclick="this.form.submit();"><?php echo _pi_ua_lang_go; ?></button>
<?php
$selected = 'selected="selected"';
if($class_ua->ua_config['show_joomla_group']){
	echo _pi_ua_lang_joomlagroup.': '; 
	echo '<select name="joomla_group_filter" id="joomla_group_filter" onchange="document.searchform.submit();">';
	echo '<option value="">'._pi_ua_lang_all.'</option>';	
	echo '<option value="19"';
	if($joomla_group_filter==19){
		echo $selected;
	}
	echo '>author</option>';
	echo '<option value="20"';
	if($joomla_group_filter==20){
		echo $selected;
	}
	echo '>editor</option>';
	echo '<option value="21"';
	if($joomla_group_filter==21){
		echo $selected;
	}
	echo '>publisher</option>';
	echo '<option value="23"';
	if($joomla_group_filter==23){
		echo $selected;
	}
	echo '>manager</option>';
	echo '<option value="24"';
	if($joomla_group_filter==24){
		echo $selected;
	}
	echo '>administrator</option>';
	echo '</select>';
}

$class_ua->db->setQuery("SELECT * FROM #__pi_aua_usergroups ORDER BY name");
$usergroups = $class_ua->db-> loadObjectList();

echo ' '._pi_ua_lang_usergroup.': '; 
echo '<select name="usergroup_filter" id="usergroup_filter" onchange="document.searchform.submit();">';
echo '<option value="">'._pi_ua_lang_all.'</option>';
foreach($usergroups as $usergroup){		
	echo '<option value="'.$usergroup->id.'"';
	if($usergroup->id==$usergroup_filter){
		echo $selected;
	}	
	echo '>'.$usergroup->name.'</option>';						
}
echo '</select>';
?>
&nbsp;<button onclick="document.getElementById('search').value='';document.getElementById('joomla_group_filter').value='';document.getElementById('usergroup_filter').value='';this.form.submit();"><?php echo _pi_ua_lang_reset; ?></button>
</form>
</div>

<form name="adminForm" method="post" action="">
		<input type="hidden" name="option" value="com_pi_admin_user_access" />
		<input type="hidden" name="task" value="users_save" />		



<table class="adminlist">
	<tr>
		<th align="left">			
			<?php echo _pi_ua_lang_username; ?>
		</th>
		<th align="left">
			<?php echo _pi_ua_lang_user; ?>
		</th>			
		<th align="left">
			<?php echo _pi_ua_lang_joomlagroup; ?>
		</th>		
		<th align="left">
			<?php echo _pi_ua_lang_usergroup; ?>					
		</th>
	</tr>	

<?php


function getUsergroupName($usergroupId){
	global $usergroups;

	foreach($usergroups as $usergroup){
		if($usergroup->id==$usergroupId){	
			$usergroupName = $usergroup->name;
			return $usergroupName;
		}	
	}	
}

$k = 0;
for($i=0; $i < count( $rows ); $i++) {
	$row_345676 = $rows[$i];
	
	$usergroupName = getUsergroupName($row_345676->pi_usergroup);		
	echo '<tr class="row'.$k.'"><td width="25%">'.$row_345676->username.'</td><td>'.$row_345676->name.'</td>';
	
	echo '<td>';
	if(!$class_ua->ua_config['show_joomla_group']){	
		echo $row_345676->usertype;
		echo '<input type="hidden" name="gid[]" value="'.$row_345676->gid.'" />';
	}else{
		echo '<select name="gid[]">';
			echo '<option value="19"';
			if($row_345676->gid==19){
				echo ' selected="selected"';
			}
			echo '>author</option>';				
			echo '<option value="20"';
			if($row_345676->gid==20){
				echo ' selected="selected"';
			}
			echo '>editor</option>';				
			echo '<option value="21"';
			if($row_345676->gid==21){
				echo ' selected="selected"';
			}
			echo '>publisher</option>';			
			echo '<option value="23"';
			if($row_345676->gid==23){
				echo ' selected="selected"';
			}
			echo '>Manager</option>';
			echo '<option value="24"';
			if($row_345676->gid==24){
				echo ' selected="selected"';
			}
			echo '>Administrator</option>';			
		echo '</select>';
	}		
	echo '</td>';	
	
	echo '<td>';
	echo '<input type="hidden" name="user_id[]" value="'.$row_345676->id.'" />';
	echo '<select name="usergroup[]">';
	echo '<option value="0"> --- </option>';
	foreach($usergroups as $usergroup){
		if($usergroup->id==$row_345676->pi_usergroup){
			$selected = ' selected="selected"';
		}else{
			$selected = '';
		}		
		echo '<option value="'.$usergroup->id.'" '.$selected.'>'.$usergroup->name.'</option>';						
	}				
	echo '</select>';
	echo '</td></tr>';
	
	if($k==1){
		$k = 0;
	}else{
		$k = 1;
	}
}
if(!count($rows)){	
	echo '<tr><td colspan="5">'._pi_ua_lang_nousers.' <a href="index2.php?option=com_users&task=view">user manager</a>.</td></tr>';	
}
?>
	
</table>
</form>
<?php
$class_ua->display_footer();
?>