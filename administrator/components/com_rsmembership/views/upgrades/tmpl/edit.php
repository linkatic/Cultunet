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
	if (form.membership_from_id.value == form.membership_to_id.value)
		alert('<?php echo JText::_('RSM_UPGRADE_SAME_ERROR', true); ?>');
	else
		submitform(pressbutton);
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=upgrades&task=edit'); ?>" method="post" name="adminForm" id="adminForm">
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_UPGRADE_FROM_DESC'); ?>"><label for="type"><?php echo JText::_('RSM_UPGRADE_FROM'); ?></label></span></td>
			<td><?php echo $this->lists['from']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_UPGRADE_TO_DESC'); ?>"><label for="type"><?php echo JText::_('RSM_UPGRADE_TO'); ?></label></span></td>
			<td><?php echo $this->lists['to']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_UPGRADE_PRICE_DESC'); ?>"><label for="price"><?php echo JText::_('RSM_UPGRADE_PRICE'); ?></label></span></td>
			<td><input type="text" name="price" value="<?php echo $this->row->price; ?>" id="price" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('PUBLISHED_DESC'); ?>"><label for="published"><?php echo JText::_('PUBLISHED'); ?></label></span></td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
	</table>

<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="controller" value="upgrades" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="view" value="upgrades" />

<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>