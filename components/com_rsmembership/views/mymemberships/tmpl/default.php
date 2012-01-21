<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->params->get('show_page_title', 1)) { ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php } ?>
<div class="greyblock">
<?php if(count($this->memberships)) { ?>
<form action="<?php echo $this->action; ?>" method="post" name="adminForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="membershiptable<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php if ($this->params->get('show_headings', 1)) { ?>
<tr>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" align="right" width="5%"><?php echo JText::_('Num'); ?></td>
 	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_MEMBERSHIP'); ?></td>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_MEMBERSHIP_START'); ?></td>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_MEMBERSHIP_END'); ?></td>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_STATUS'); ?></td>
</tr>
<?php } ?>


<?php $k = 1; ?>
<?php $i = 0; ?>
<?php foreach ($this->memberships as $item) { ?>
<tr class="sectiontableentry<?php echo $k . $this->escape($this->params->get('pageclass_sfx')); ?>" >
	<td align="right"><?php echo $this->pagination->getRowOffset($i); ?></td>
	<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=mymembership&cid='.$item->id.$this->Itemid); ?>"><?php echo $this->escape($item->name); ?></a></td>
	<td><?php echo date($this->date_format, $item->membership_start); ?></td>
	<td><?php echo $item->membership_end > 0 ? date($this->date_format, $item->membership_end) : JText::_('RSM_UNLIMITED'); ?></td>
	<td><?php echo JText::_('RSM_STATUS_'.$item->status); ?></td>
</tr>
<?php $k = $k == 1 ? 2 : 1; ?>
<?php $i++; ?>
<?php } ?>

<?php if ($this->params->get('show_pagination', 1)) { ?>
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

<input type="hidden" name="limitstart" value="<?php echo $this->limitstart; ?>" />
</form>

<?php if (!empty($this->transactions)) { ?>
<p><?php echo JText::sprintf('RSM_HAVE_PENDING_TRANSACTIONS', count($this->transactions)); ?></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php if ($this->params->get('show_headings', 1)) { ?>
<tr>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" align="right" width="5%"><?php echo JText::_('Num'); ?></td>
 	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_TRANSACTION'); ?></td>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_DATE'); ?></td>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_PRICE'); ?></td>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_GATEWAY'); ?></td>
	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_STATUS'); ?></td>
</tr>
<?php } ?>
<?php $k = 1; ?>
<?php $i = 0; ?>
<?php foreach ($this->transactions as $item) { ?>
<tr class="sectiontableentry<?php echo $k . $this->escape($this->params->get('pageclass_sfx')); ?>" >
	<td align="right"><?php echo $this->pagination->getRowOffset($i); ?></td>
	<td><?php echo JText::_('RSM_TRANSACTION_'.strtoupper($item->type)); ?></td>
	<td><?php echo date($this->date_format, $item->date); ?></td>
	<td><?php echo RSMembershipHelper::getPriceFormat($item->price); ?></td>
	<td><?php echo $item->gateway; ?></td>
	<td><?php echo JText::_('RSM_TRANSACTION_STATUS_'.strtoupper($item->status)); ?></td>
</tr>
<?php $k = $k == 1 ? 2 : 1; ?>
<?php $i++; ?>
<?php } ?>
</table>
<?php } ?>
<?php } else { ?>
<?php echo JText::_('NO_SUSCRIPCION_DIGITAL'); } ?>
</div>
