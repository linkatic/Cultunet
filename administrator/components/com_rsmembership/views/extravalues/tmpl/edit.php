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
		alert('<?php echo JText::_('RSM_EXTRA_VALUE_NAME_ERROR', true); ?>');
	else
	{
		var dt = $('extra-pane').getElements('dt');
			
		for (var i=0; i<dt.length; i++)
		{
			if (dt[i].className == 'open')
				$('tabposition').value = i;
		}
		
		submitform(pressbutton);
	}
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=extravalues&task=edit'); ?>" method="post" name="adminForm" id="adminForm">
<?php echo $this->tabs->startPane('extra-pane'); ?>
<?php echo $this->tabs->startPanel(JText::_('RSM_EXTRA_VALUE'), 'value-info'); ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_VALUE_DESC'); ?>"><label for="name"><?php echo JText::_('RSM_EXTRA_VALUE'); ?></label></span></td>
			<td><input type="text" name="name" value="<?php echo $this->escape($this->row->name); ?>" id="name" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_VALUE_SKU_DESC'); ?>"><label for="sku"><?php echo JText::_('RSM_EXTRA_VALUE_SKU'); ?></label></span></td>
			<td><input type="text" name="sku" value="<?php echo $this->escape($this->row->sku); ?>" id="sku" size="120" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200" valign="top"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_VALUE_DESCRIPTION_DESC'); ?>"><label for="description"><?php echo JText::_('RSM_EXTRA_VALUE_DESCRIPTION'); ?></label></span></td>
			<td><?php echo $this->editor->display('description',$this->row->description,500,250,70,10); ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_VALUE_PRICE_DESC'); ?>"><label for="price"><?php echo JText::_('RSM_EXTRA_VALUE_PRICE'); ?></label></span></td>
			<td><input type="text" name="price" value="<?php echo $this->row->price; ?>" id="price" maxlength="255" /></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('PUBLISHED_DESC'); ?>"><label for="published"><?php echo JText::_('PUBLISHED'); ?></label></span></td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
		<tr>
			<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_EXTRA_VALUE_CHECKED_DESC'); ?>"><label for="checked"><?php echo JText::_('RSM_EXTRA_VALUE_CHECKED'); ?></label></span></td>
			<td><?php echo $this->lists['checked']; ?></td>
		</tr>
	</table>
<?php echo $this->tabs->endPanel(); ?>

<?php echo $this->tabs->startPanel(JText::_('RSM_SHARED'), 'value-shared'); ?>
	<?php if (!empty($this->row->id)) { ?>
	<div class="button2-left"><div class="blank"><a class="modal" title="Select the path" rel="{handler: 'iframe', size: {x: 660, y: 475}}" href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=share&extra_value_id='.$this->row->id.'&tmpl=component'); ?>"><?php echo JText::_('RSM_ADD_CONTENT'); ?></a></div></div>
	<span class="rsmembership_clear" style="margin-bottom: 10px;"></span>
	<div id="addextravaluefolders_ajax">
		<?php $this->display('shared'); ?>
	</div>
	<?php } else { ?>
	<?php echo JText::_('RSM_SHARED_SAVE_FIRST'); ?>
	<?php } ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
	<tr>
		<td width="200"><span class="hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_SHARE_REDIRECT_DESC'); ?>"><label for="share_redirect"><?php echo JText::_('RSM_MEMBERSHIP_SHARE_REDIRECT'); ?></label></span></td>
		<td><input type="text" name="share_redirect" value="<?php echo $this->escape($this->row->share_redirect); ?>" id="share_redirect" size="120" maxlength="255" /></td>
	</tr>
	</table>
<?php echo $this->tabs->endPanel(); ?>
<?php echo $this->tabs->endPane(); ?>

<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="controller" value="extravalues" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="view" value="extravalues" />
<input type="hidden" name="boxchecked" value="0" />

<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="extra_id" value="<?php echo $this->row->extra_id; ?>" />
<input type="hidden" name="tabposition" value="0" id="tabposition" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>