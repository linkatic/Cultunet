<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">
function tableOrdering(order, dir, task)
{
	var form = document.adminForm;

	form.filter_order.value	= order;
	form.filter_order_Dir.value	= dir;
	document.adminForm.submit(task);
}
</script>

<?php if ($this->params->get('show_page_title', 1)) { ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php } ?>

<form action="<?php echo $this->action; ?>" method="post" name="adminForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="membershiptable<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php if ($this->params->get('show_headings', 1)) { ?>
<tr>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" align="right" width="5%"><?php echo JText::_('Num'); ?></td>
 	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JHTML::_('grid.sort',  JText::_('RSM_MEMBERSHIP'), 'm.name', $this->sortOrder, $this->sortColumn); ?></td>
	<?php if ($this->params->get('show_category', 0)) { ?>
 	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JHTML::_('grid.sort',  JText::_('Category'), 'c.name', $this->sortOrder, $this->sortColumn); ?></td>
	<?php } ?>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JHTML::_('grid.sort',  JText::_('RSM_PRICE'), 'price', $this->sortOrder, $this->sortColumn); ?></td>
</tr>
<?php } ?>

<?php $k = 1; ?>
<?php $i = 0; ?>
<?php foreach ($this->items as $item) {
$catid = $item->category_id ? '&catid='.$item->category_id.':'.JFilterOutput::stringURLSafe($item->category_name) : ''; ?>
<tr class="sectiontableentry<?php echo $k . $this->escape($this->params->get('pageclass_sfx')); ?>" >
	<td align="right"><?php echo $this->pagination->getRowOffset($i); ?></td>
	<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=membership'.$catid.'&cid='.$item->id.':'.JFilterOutput::stringURLSafe($item->name).$this->Itemid); ?>"><?php echo $this->escape($item->name); ?></a></td>
	<?php if ($this->params->get('show_category', 0)) { ?>
	<td><?php echo $item->category_id ? $item->category_name : JText::_('RSM_NO_CATEGORY'); ?></td>
	<?php } ?>
	<td><?php echo RSMembershipHelper::getPriceFormat($item->price); ?></td>
</tr>
<?php $k = $k == 1 ? 2 : 1; ?>
<?php $i++; ?>
<?php } ?>

<?php if ($this->params->get('show_pagination', 0)) { ?>
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<tr>
	<td align="center" colspan="4" class="sectiontablefooter<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->pagination->getPagesLinks(); ?></td>
</tr>
<tr>
	<td colspan="5" align="right"><?php echo $this->pagination->getPagesCounter(); ?></td>
</tr>
<?php } ?>
</table>

<input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
<input type="hidden" name="limitstart" value="<?php echo $this->limitstart; ?>" />
</form>
