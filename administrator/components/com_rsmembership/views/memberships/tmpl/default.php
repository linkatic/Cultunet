<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=memberships'); ?>" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->filter_word; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<?php echo $this->lists['categories']; ?>
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.getElementById('category_id').value='-1';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap"><?php echo $this->lists['state']; ?></td>
		</tr>
	</table>
	<div id="editcell1">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_( '#' ); ?></th>
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->memberships); ?>);"/></th>
				<th width="5"><?php echo JHTML::_('grid.sort', 'Id', 'id', $this->sortOrder, $this->sortColumn); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'RSM_MEMBERSHIP', 'name', $this->sortOrder, $this->sortColumn); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'RSM_MEMBERSHIP_LENGTH', 'period', $this->sortOrder, $this->sortColumn); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'RSM_MEMBERSHIP_PRICE', 'price', $this->sortOrder, $this->sortColumn); ?></th>
				<th width="80"><?php echo JText::_('Published'); ?></th>
				<th width="100"><?php echo JText::_('Ordering'); ?>
				<?php echo JHTML::_('grid.order',$this->memberships); ?>
				</th>
			</tr>
			</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->memberships);
	foreach ($this->memberships as $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $this->pagination->getRowOffset($i); ?></td>
			<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
			<td><?php echo $row->id; ?></td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=memberships&task=edit&cid='.$row->id); ?>"><?php echo $row->name != '' ? $row->name : JText::_('RSM_NO_TITLE'); ?></a></td>
			<td>
				<?php if (!empty($row->period)) { ?>
					<?php echo $row->period; ?> <?php echo $row->period_type; ?>
				<?php } else { ?>
					<?php echo JText::_('RSM_UNLIMITED'); ?>
				<?php } ?>
			</td>
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
				<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		</table>
	</div>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="memberships" />
	<input type="hidden" name="controller" value="memberships" />
	<input type="hidden" name="task" value="" />
	
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortOrder; ?>" />
</form>