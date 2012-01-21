<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

$admin_access 	= (isset($this->row->permissions['admin']['admin_access']) && $this->row->permissions['admin']['admin_access']);
$author_access 	= (isset($this->row->permissions['author']['author_access']) && $this->row->permissions['author']['author_access']);
$emails_access 	= (isset($this->row->permissions['emails']['can_recnoti']) && $this->row->permissions['emails']['can_recnoti']);
?>
<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	submitform( pressbutton );
}
<?php if(!$this->superAdmin && !$this->guest) { ?>
var admin = <?php echo ($admin_access) ? 'false' : 'true'; ?>;
var author = <?php echo ($author_access) ? 'false' : 'true'; ?>;
var emails = <?php echo ($emails_access) ? 'false' : 'true'; ?>;
function disableAll(act) {
	var frm = document.adminForm;
	for(var i=0;i<frm.elements.length;i++) {
		if(frm.elements[i].type && (frm.elements[i].name != 'permissions[system][system_access]')) {
			if(frm.elements[i].type.toLowerCase() == 'radio') {
					frm.elements[i].disabled = act;
			}
		}
	}
	if(!act && admin)
	{
		disableAdmin(true)
	}

	if(!act && author)
	{
		disableAuthor(true)
	}

	if(!act && emails)
	{
		disableEmail(true)
	}
}

function disableEmail(act)
{
	var frm = document.adminForm;
	for(var i=0;i<frm.elements.length;i++) {
		if(frm.elements[i].type && (frm.elements[i].name != 'permissions[emails][can_recnoti]') && (frm.elements[i].alt == 'emails')) {
			if(frm.elements[i].type.toLowerCase() == 'radio') {
					frm.elements[i].disabled = act;
			}
		}
	}
	emails = act;
}

function disableAuthor(act)
{
	var frm = document.adminForm;
	for(var i=0;i<frm.elements.length;i++) {
		if(frm.elements[i].type && (frm.elements[i].name != 'permissions[author][author_access]') && (frm.elements[i].alt == 'author')) {
			if(frm.elements[i].type.toLowerCase() == 'radio') {
					frm.elements[i].disabled = act;
			}
		}
	}
	author = act;
}

function disableAdmin(act)
{
	var frm = document.adminForm;
	for(var i=0;i<frm.elements.length;i++) {
		if(frm.elements[i].type && (frm.elements[i].name != 'permissions[admin][admin_access]') && (frm.elements[i].alt == 'admin')) {
			if(frm.elements[i].type.toLowerCase() == 'radio') {
					frm.elements[i].disabled = act;
			}
		}
	}
	admin = act;
}
<?php } ?>
<?php if($this->superAdmin) { ?>
function disableEmail(act)
{
	var frm = document.adminForm;
	for(var i=0;i<frm.elements.length;i++) {
		if(frm.elements[i].type && (frm.elements[i].name != 'permissions[emails][can_recnoti]') && (frm.elements[i].alt == 'emails')) {
			if(frm.elements[i].type.toLowerCase() == 'radio') {
					frm.elements[i].disabled = act;
			}
		}
	}
}
<?php } ?>
//-->
</script>

<?php echo BlogSystemFun::getSideMenu('1', 'Settings'); ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<table class="configtable cfgdesc" border="0" cellpadding="4" cellspacing="0" width="100%">
	<?php
	$i = 0;
	foreach($this->fields as $group=>$fields)
	{
		echo '<tr><th colspan="2">'.JText::_( 'GROUP_PERMISSIONS_'.strtoupper($group) ).'</th></tr>';
		$counter = 1;
		foreach($fields as $field_name)
		{
			$value 	= (isset($this->row->permissions[$group][$field_name])) ? $this->row->permissions[$group][$field_name] : false;
			$tip 	= JText::_('GROUP_'.strtoupper($field_name).'_TIP');
			$name 	= JText::_('GROUP_'.strtoupper($field_name));
			if($tip != 'GROUP_'.strtoupper($field_name).'_TIP')
			{
				$tip =  ' <span onmouseout="HideHelp(\''.$field_name.'\');" onmouseover="ShowHelp(\''.$field_name.'\', \''.$name.'\', \''.$tip.'\')" border="0" class="helptp">[?]</span><div style="display: none;" id="'.$field_name.'"></div>';
			}else{
				$tip = '';
			}

			//Decide what is disabled
			$disbaled = (!$this->superAdmin && !$this->guest && (($group == 'admin' && $field_name != 'admin_access' && !$admin_access) || ($group == 'author' && $field_name != 'author_access' && !$author_access) || ($group == 'emails' && $field_name != 'can_recnoti' && !$emails_access)));
			$adminDisbaled = (($emails_access || $field_name == 'can_recnoti') && $group == 'emails');
		?>
		<tr class="<?php echo ($counter % 2) ? 'row1' : 'row0'; ?>">
			<td class="title"><?php echo $name.$tip; ?></td>
			<td>
				<input name="permissions[<?php echo $group; ?>][<?php echo $field_name; ?>]" id="perNo<?php echo $i; ?>" alt="<?php echo $group; ?>" value="0"<?php echo ($value) ? '' : ' checked="checked"'; ?> class="inputbox" type="radio"<?php echo (($this->superAdmin && !$adminDisbaled) || ($this->guest && $field_name != 'system_access') || ($disbaled)) ? ' disabled="disabled"' : (($field_name == 'system_access') ? ' onclick="disableAll(true)"' : (($field_name == 'admin_access') ? ' onclick="disableAdmin(true)"' : (($field_name == 'author_access') ? ' onclick="disableAuthor(true)"' : (($field_name == 'can_recnoti') ? ' onclick="disableEmail(true)"' : '')))); ?>>
				<label for="perNo<?php echo $i; ?>"><?php echo JText::_('No'); ?></label>
				<input name="permissions[<?php echo $group; ?>][<?php echo $field_name; ?>]" id="perYes<?php echo $i; ?>" alt="<?php echo $group; ?>" value="1"<?php echo ($value) ? ' checked="checked"' : ''; ?> class="inputbox" type="radio"<?php echo (($this->superAdmin && !$adminDisbaled) || ($this->guest && $field_name != 'system_access') || ($disbaled)) ? ' disabled="disabled"' : (($field_name == 'system_access') ? ' onclick="disableAll(false)"' : (($field_name == 'admin_access') ? ' onclick="disableAdmin(false)"' : (($field_name == 'author_access') ? ' onclick="disableAuthor(false)"' : (($field_name == 'can_recnoti') ? ' onclick="disableEmail(false)"' : '')))); ?>>
				<label for="perYes<?php echo $i; ?>"><?php echo JText::_('Yes'); ?></label>
			</td>
		</tr>
		<?php
			$counter++;
			$i++;
		}
		echo '<tr class="row2"><td colspan="2"></td></tr>';
	}
	?>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_lyftenbloggie" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="group" value="<?php echo $this->row->group_id; ?>" />
<input type="hidden" name="controller" value="groups" />
<input type="hidden" name="view" value="groups" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="admin" value="<?php echo $this->superAdmin; ?>" />
</form>
	</td>
</tr>
</table>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>