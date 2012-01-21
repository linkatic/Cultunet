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

if(!$class_ua->ua_config['display_usergroups'] && $class_ua->user_type!='Super Administrator'){
	die('Restricted access');
}

//header and nav
$class_ua->echo_header();

//spunk up headers in joomla 1.5
$class_ua->spunk_up_headers_1_5();

//get id
if( defined('_JEXEC') ){
	//joomla 1.5
	$id = JRequest::getVar('id', '', 'get');
}else{
	//joomla 1.0.x
	mosGetParam( $_GET, 'id', '' );
}

if($sub_task == 'new'){
	//new usergroup
	
	$id = '';
	$name = '';
	$email = '';
	$description = '';	
	$url = '';
	
	//end new usergroup
}else{
	//edit usergroup
	
	//get name
	$class_ua->db->setQuery("SELECT * FROM #__pi_aua_usergroups WHERE id='$id' LIMIT 1");
	$rows = $class_ua->db->loadObjectList();
	$row = $rows[0];
	$name = $row->name;
	$email = $row->email;
	$description = stripslashes($row->description);	
	$url = $row->url;	
	
	//end edit usergroup
}

?>

<script language="JavaScript" type="text/javascript">

function submitbutton(pressbutton) {		
	if (pressbutton == 'usergroups') {		
			submitform( pressbutton );			
		}
	if (pressbutton == 'usergroup_save') {	
		if (document.getElementById('name').value == '') {			
			alert('<?php echo _pi_ua_lang_nonameentered; ?>');
			return;
		} else {
			submitform('usergroup_save');
		}
	}
}

</script>

<form name="adminForm" method="post" action="">
		<input type="hidden" name="option" value="com_pi_admin_user_access" />
		<input type="hidden" name="task" value="usergroup_save" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
<table class="adminlist">
	<tr>
		<th colspan="3">
			<?php 
				if($sub_task == 'new'){ 
					echo _pi_ua_lang_usergroup_new; 
				}else{
					echo _pi_ua_lang_usergroup_edit; 
				}
			?>
		</th>
	</tr>
	<tr>
		<td width="300">
			<?php echo _pi_ua_lang_name_usergroup; ?>
		</td>
		<td>
			<input name="name" id="name" type="text" value="<?php echo $name;?>" class="text_area" style="width: 300px;" />
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<td width="300">
			<?php echo _pi_ua_lang_description; ?>
		</td>
		<td>
			<textarea name="description" cols="20" rows="5" class="text_area" style="width: 300px;"><?php echo $description; ?></textarea>
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<td width="300">
			<?php echo _pi_ua_lang_mail_usergroup_administrator; ?>			
		</td>
		<td>
			<input name="email" type="text" value="<?php echo $email;?>" class="text_area" style="width: 300px;" />
		</td>
		<td>(<?php echo _pi_ua_lang_separate_adresses; ?>)
		</td>
	</tr>	
	<tr>
		<td width="300">
			<?php echo _pi_ua_lang_redirect_on_login_backend; ?>			
		</td>
		<td>
			<input name="url" type="text" value="<?php echo $url;?>" class="text_area" style="width: 300px;" />
			
		</td>
		<td><?php echo _pi_ua_lang_redirecturl_info; ?><br />
		Admin-User-Access > <?php echo _pi_ua_lang_config; ?> > <?php echo _pi_ua_lang_general; ?> > '<?php echo _pi_ua_lang_redirect_on_login_backend; ?>'
		<br />
		<?php echo _pi_ua_lang_example; ?>: index.php?option=com_frontenduseraccess&amp;task=usergroups
		</td>
	</tr>
</table>
</form>	 
<?php
$class_ua->display_footer();
?>