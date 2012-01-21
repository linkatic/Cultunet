<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=transactions'); ?>" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->filter_word; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">&nbsp;</td>
		</tr>
	</table>
	<div id="editcell1">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_( '#' ); ?></th>
					<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->transactions); ?>);"/></th>
					<th><?php echo JHTML::_('grid.sort', 'RSM_EMAIL', 'email', $this->sortOrder, $this->sortColumn); ?></th>
					<th width="140"><?php echo JHTML::_('grid.sort', 'RSM_TYPE', 'type', $this->sortOrder, $this->sortColumn); ?></th>
					<th><?php echo JText::_('RSM_DETAILS'); ?></th>
					<th width="140"><?php echo JHTML::_('grid.sort', 'RSM_DATE', 'date', $this->sortOrder, $this->sortColumn); ?></th>
					<th width="110"><?php echo JHTML::_('grid.sort', 'RSM_IP', 'ip', $this->sortOrder, $this->sortColumn); ?></th>
					<th><?php echo JHTML::_('grid.sort', 'RSM_PRICE', 'price', $this->sortOrder, $this->sortColumn); ?></th>
					<th><?php echo JHTML::_('grid.sort', 'RSM_MEMBERSHIP_COUPON', 'coupon', $this->sortOrder, $this->sortColumn); ?></th>
					<th><?php echo JHTML::_('grid.sort', 'RSM_STATUS', 'status', $this->sortOrder, $this->sortColumn); ?></th>
					<th><?php echo JHTML::_('grid.sort', 'RSM_GATEWAY', 'gateway', $this->sortOrder, $this->sortColumn); ?></th>
					<th><?php echo JText::_('RSM_HASH'); ?></th>
				</tr>
			</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->transactions);
	foreach ($this->transactions as $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $this->pagination->getRowOffset($i); ?></td>
			<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
			<td><?php echo !empty($row->email) ? '<a href="index.php?option=com_rsmembership&controller=users&task=edit&cid='.$row->user_id.'">'.$row->email.'</a>' : '<em>'.JText::_('RSM_NO_EMAIL').'</em>'; ?></td>
			<td><?php echo JText::_('RSM_TRANSACTION_'.strtoupper($row->type)); ?></td>
			<td><?php
			$params = RSMembershipHelper::parseParams($row->params);
			switch ($row->type)
			{
				case 'new':
					if (!empty($params['membership_id']))
						echo isset($this->cache->memberships[$params['membership_id']]) ? $this->cache->memberships[$params['membership_id']] : JText::_('RSM_COULD_NOT_FIND_MEMBERSHIP');
					if (!empty($params['extras']))
						foreach ($params['extras'] as $extra)
							if (!empty($extra))
								echo '<br />- '.$this->cache->extra_values[$extra];
				break;
				
				case 'upgrade':
					if (!empty($params['from_id']) && !empty($params['to_id']))
						echo $this->cache->memberships[$params['from_id']].' -&gt; '.$this->cache->memberships[$params['to_id']];
				break;
				
				case 'addextra':
					if (!empty($params['extras']))
						foreach ($params['extras'] as $extra)
							echo $this->cache->extra_values[$extra].'<br />';
				break;
				
				case 'renew':
					if (!empty($params['membership_id']))
						echo $this->cache->memberships[$params['membership_id']];
				break;
			}
			?>
			</td>
			<td><?php echo date(RSMembershipHelper::getConfig('date_format'), $row->date); ?></td>
			<td><?php echo $row->ip; ?></td>
			<td><?php echo RSMembershipHelper::getPriceFormat($row->price); ?></td>
			<td><?php echo strlen($row->coupon) == 0 ? '<em>'.JText::_('RSM_NO_COUPON').'</em>' : $row->coupon; ?></td>
			<td><?php echo JText::_('RSM_TRANSACTION_STATUS_'.strtoupper($row->status)); ?></td>
			<td><?php echo $row->gateway; ?></td>
			<td><?php echo empty($row->hash) ? '<em>'.JText::_('RSM_NO_HASH').'</em>' : $row->hash; ?></td>
		</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
		<tfoot>
			<tr>
				<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		</table>
	</div>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="transactions" />
	<input type="hidden" name="controller" value="transactions" />
	<input type="hidden" name="task" value="" />
	
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortOrder; ?>" />
</form>