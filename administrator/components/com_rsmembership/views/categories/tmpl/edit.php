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
		alert('<?php echo JText::_('RSM_CATEGORY_NAME_ERROR', true); ?>');
	else		
		submitform(pressbutton);
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=categories&task=edit'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('Name'); ?>"><label for="name"><?php echo JText::_('Name'); ?></label></span></td>
			<td><input type="text" name="name" value="<?php echo $this->row->name; ?>" id="name" size="100" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('Description'); ?>"><label for="description"><?php echo JText::_('Description'); ?></label></span></td>
			<td><?php echo $this->editor->display('description',$this->row->description,500,250,70,10); ?></td>
		</tr>
	</table>
	
	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="categories" />
	<input type="hidden" name="controller" value="categories" />
	<input type="hidden" name="task" value="edit" />

	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>