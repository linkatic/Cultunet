<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
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
		alert('<?php echo JText::_('RSM_FILE_NAME_ERROR', true); ?>');
	else
		submitform(pressbutton);
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=files&task=edit'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_PATH_DESC'); ?>"><label for="name"><?php echo JText::_('RSM_PATH'); ?></label></span></td>
			<td colspan="2"><?php echo $this->row->path; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_FILE_DESC'); ?>"><label for="name"><?php echo JText::_('RSM_FILE'); ?></label></span></td>
			<td><input type="text" name="name" value="<?php echo $this->escape($this->row->name); ?>" id="name" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_FILE_DESCRIPTION_DESC'); ?>"><label for="description"><?php echo JText::_('RSM_FILE_DESCRIPTION'); ?></label></span></td>
			<td><?php echo $this->editor->display('description',$this->row->description,500,250,70,10); ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_FILE_THUMB_DESC'); ?>"><label for="thumb"><?php echo JText::_('RSM_FILE_THUMB'); ?></label></span></td>
			<td>
			<?php if (!empty($this->row->thumb)) { ?>
			<p><?php echo JHTML::_('image', JURI::root().'components/com_rsmembership/assets/thumbs/files/'.$this->row->thumb, ''); ?></p>
			<p><input type="checkbox" value="1" name="thumb_delete" /> <?php echo JText::_('RSM_DELETE_THUMB'); ?></p>
			<?php } ?>
			<input type="file" name="thumb" value="" id="thumb" size="100" />
			</td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_FILE_THUMB_WIDTH_DESC'); ?>"><label for="thumb_w"><?php echo JText::_('RSM_FILE_THUMB_WIDTH'); ?></label></span></td>
			<td>
			<input type="checkbox" value="1" name="thumb_resize" /> <?php echo JText::_('RSM_RESIZE_TO'); ?>
			<input type="text" name="thumb_w" value="<?php echo $this->row->thumb_w; ?>" id="thumb_w" size="10" maxlength="255" /> <?php echo JText::_('RSM_MEMBERSHIP_PX'); ?></td>
		</tr>
		<?php if ($this->is_file) { ?>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_TERM_DESC'); ?>"><label for="term_id"><?php echo JText::_('RSM_TERM'); ?></label></span></td>
			<td><?php echo $this->lists['terms']; ?></td>
		</tr>
		<?php } ?>
	</table>

<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="controller" value="files" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="view" value="files" />
<input type="hidden" name="folder" value="<?php echo dirname($this->row->path); ?>" />

<input type="hidden" name="path" value="<?php echo $this->row->path; ?>" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>