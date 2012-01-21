<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?> 

<script type="text/javascript">
function validate_user()
{
	var form = document.membershipForm;
	var msg = new Array();
	
	<?php foreach ($this->fields_validation as $validation) { ?>
		<?php echo $validation; ?>
	<?php } ?>
	
	if (msg.length > 0)
	{
		alert(msg.join("\n"));
		return false;
	}
	
	return true;
}
</script>

<?php if ($this->params->get('show_page_title', 1)) { ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php } ?>

<form method="post" action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=validateuser'); ?>" name="membershipForm" onsubmit="return validate_user();">
<fieldset class="input">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<?php foreach ($this->fields as $field) { ?>
<tr>
	<td width="30%" height="40"><?php echo $field[0]; ?></td>
	<td><?php echo $field[1]; ?></td>
</tr>
<?php } ?>
</table>
<input type="submit" class="button" value="<?php echo JText::_('RSM_SAVE'); ?>" name="Submit" />
</fieldset>

<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="view" value="user" />
<input type="hidden" name="task" value="validateuser" />
</form>