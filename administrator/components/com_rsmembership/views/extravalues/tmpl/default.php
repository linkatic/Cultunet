<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=extravalues'); ?>" method="post" name="adminForm">

	<table class="adminform">
		<tr>
			<td width="100%">&nbsp;</td>
			<td nowrap="nowrap"><?php echo $this->lists['state']; ?></td>
		</tr>
	</table>
	<div id="editcell1">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_( '#' ); ?></th>
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->extravalues); ?>);"/></th>
				<th><?php echo JHTML::_('grid.sort', 'RSM_EXTRA_VALUE', 'name', $this->sortOrder, $this->sortColumn); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'RSM_EXTRA_VALUE_PRICE', 'price', $this->sortOrder, $this->sortColumn); ?></th>
				<th width="80"><?php echo JText::_('Published'); ?></th>
				<th width="100"><?php echo JText::_('Ordering'); ?>
				<?php echo JHTML::_('grid.order',$this->extravalues); ?>
				</th>
			</tr>
			</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->extravalues);
	foreach ($this->extravalues as $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $this->pagination->getRowOffset($i); ?></td>
			<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=extravalues&task=edit&cid='.$row->id); ?>"><?php echo $row->name != '' ? $row->name : JText::_('RSM_NO_TITLE'); ?></a></td>
			<td>
				<?php echo RSMembershipHelper::getPriceFormat($row->price); ?>
			</td>
			<td align="center"><?php echo JHTML::_('grid.published', $row, $i); ?></td>
			<td class="order">
			<span><?php echo $this->pagination->orderUpIcon( $i, true, 'orderup', 'Move Up', 'ordering'); ?></span>
			<span><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'orderdown', 'Move Down', 'ordering' ); ?></span>
			<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align:center" />
			</td>
		</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
		<tfoot>
			<tr>
				<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		</table>
	</div>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="extravalues" />
	<input type="hidden" name="controller" value="extravalues" />
	<input type="hidden" name="task" value="" />
	
	<input type="hidden" name="extra_id" value="<?php echo $this->extra_id; ?>" />
	
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortOrder; ?>" />
</form>