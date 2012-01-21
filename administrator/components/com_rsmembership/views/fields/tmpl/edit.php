<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>

<script type="text/javascript">
function submitbutton(pressbutton)
{
	var form = document.adminForm;
	
	if (pressbutton == 'cancel')
	{
		submitform(pressbutton);
		return;
	}

	// do field validation
	if (form.name.value.length == 0)
		alert('<?php echo JText::_('RSM_FIELD_NAME_ERROR', true); ?>');
	// do field validation
	else if (form.label.value.length == 0)
		alert('<?php echo JText::_('RSM_FIELD_LABEL_ERROR', true); ?>');
	else if ((form.type.value == 'select' || form.type.value == 'multipleselect' || form.type.value == 'checkbox' || form.type.value == 'radio') && form.values.value.length == 0)
		alert('<?php echo JText::_('RSM_FIELD_VALUES_ERROR', true); ?>');
	else
		submitform(pressbutton);
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=fields&task=edit'); ?>" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="admintable">
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_FIELD_DESC'); ?>"><label for="name"><?php echo JText::_('RSM_FIELD'); ?></label></span></td>
			<td><input type="text" name="name" value="<?php echo $this->escape($this->row->name); ?>" id="name" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_LABEL_DESC'); ?>"><label for="label"><?php echo JText::_('RSM_LABEL'); ?></label></span></td>
			<td><input type="text" name="label" value="<?php echo $this->escape($this->row->label); ?>" id="label" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_TYPE_DESC'); ?>"><label for="type"><?php echo JText::_('RSM_TYPE'); ?></label></span></td>
			<td><?php echo $this->lists['type']; ?></td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_VALUES_DESC'); ?>"><label for="values"><?php echo JText::_('RSM_VALUES'); ?></label></span></td>
			<td><textarea cols="80" rows="10" class="text_area" type="text" name="values" id="values"><?php echo $this->escape($this->row->values); ?></textarea></td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_ADDITIONAL_ATTR_DESC'); ?>"><label for="additional"><?php echo JText::_('RSM_ADDITIONAL_ATTR'); ?></label></span></td>
			<td><textarea cols="80" rows="10" class="text_area" type="text" name="additional" id="additional"><?php echo $this->escape($this->row->additional); ?></textarea></td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_REQUIRED_DESC'); ?>"><label for="required"><?php echo JText::_('RSM_REQUIRED'); ?></label></span></td>
			<td><?php echo $this->lists['required']; ?></td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('RSM_VALIDATION_DESC'); ?>"><label for="validation"><?php echo JText::_('RSM_VALIDATION'); ?></label></span></td>
			<td><textarea cols="80" rows="10" class="text_area" type="text" name="validation" id="validation"><?php echo $this->escape($this->row->validation); ?></textarea></td>
		</tr>
		<tr>
			<td width="300" style="width: 300px;" align="right" class="key"><span class="hasTip" title="<?php echo JText::_('PUBLISHED_DESC'); ?>"><label for="published"><?php echo JText::_('PUBLISHED'); ?></label></span></td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
	</table>
</div>
	
<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="controller" value="fields" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="view" value="fields" />

<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>