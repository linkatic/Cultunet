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
		alert('<?php echo JText::_('RSM_EXTRA_NAME_ERROR', true); ?>');
	else
		submitform(pressbutton);
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=extras&task=edit'); ?>" method="post" name="adminForm" id="adminForm">
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_DESC'); ?>"><label for="name"><?php echo JText::_('RSM_EXTRA'); ?></label></span></td>
			<td><input type="text" name="name" value="<?php echo $this->escape($this->row->name); ?>" id="name" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_DESCRIPTION_DESC'); ?>"><label for="description"><?php echo JText::_('RSM_EXTRA_DESCRIPTION'); ?></label></span></td>
			<td><?php echo $this->editor->display('description',$this->row->description,500,250,70,10); ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_TYPE_DESC'); ?>"><label for="type"><?php echo JText::_('RSM_EXTRA_TYPE'); ?></label></span></td>
			<td><?php echo $this->lists['type']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_VALUES_DESC'); ?>"><?php echo JText::_('RSM_EXTRA_VALUES'); ?></span></td>
			<td>
			<?php if (empty($this->row->id)) { ?>
			<?php echo JText::_('RSM_EXTRA_SAVE_FIRST'); ?>
			<?php } else { ?>
			<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=extravalues&extra_id='.$this->row->id); ?>"><?php echo JText::_('RSM_EXTRA_VALUES_ASSIGN'); ?></a>
			<?php } ?>
			</td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('PUBLISHED_DESC'); ?>"><label for="published"><?php echo JText::_('PUBLISHED'); ?></label></span></td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
	</table>
	
<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="controller" value="extras" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="view" value="extras" />

<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>