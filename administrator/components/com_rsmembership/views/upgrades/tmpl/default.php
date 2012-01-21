<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=upgrades'); ?>" method="post" name="adminForm">
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
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->upgrades); ?>);"/></th>
				<th><?php echo JHTML::_('grid.sort', 'RSM_UPGRADE_FROM', 'fromname', $this->sortOrder, $this->sortColumn); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'RSM_UPGRADE_TO', 'toname', $this->sortOrder, $this->sortColumn); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'RSM_UPGRADE_PRICE', 'price', $this->sortOrder, $this->sortColumn); ?></th>
				<th width="80"><?php echo JText::_('Published'); ?></th>
			</tr>
			</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->upgrades);
	foreach ($this->upgrades as $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $this->pagination->getRowOffset($i); ?></td>
			<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=upgrades&task=edit&cid='.$row->id); ?>"><?php echo $row->fromname != '' ? $row->fromname : JText::_('RSM_NO_TITLE'); ?></a></td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=upgrades&task=edit&cid='.$row->id); ?>"><?php echo $row->toname != '' ? $row->toname : JText::_('RSM_NO_TITLE'); ?></a></td>
			<td>
				<?php echo RSMembershipHelper::getPriceFormat($row->price); ?>
			</td>
			<td align="center"><?php echo JHTML::_('grid.published', $row, $i); ?></td>
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
	<input type="hidden" name="view" value="upgrades" />
	<input type="hidden" name="controller" value="upgrades" />
	<input type="hidden" name="task" value="" />
	
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortOrder; ?>" />
</form>