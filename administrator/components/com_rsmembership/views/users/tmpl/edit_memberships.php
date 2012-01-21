<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<table class="adminlist" id="addmemberships">
	<thead>
	<tr>
		<th width="5"><?php echo JText::_( '#' ); ?></th>
		<th width="20"><?php echo JText::_('Delete'); ?></th>
		<th><?php echo JText::_('RSM_MEMBERSHIP'); ?></th>
		<th><?php echo JText::_('RSM_MEMBERSHIP_PRICE'); ?></th>
		<th><?php echo JText::_('RSM_MEMBERSHIP_START'); ?></th>
		<th><?php echo JText::_('RSM_MEMBERSHIP_END'); ?></th>
		<th colspan="2"><?php echo JText::_('RSM_MEMBERSHIP_STATUS'); ?></th>
		<th width="80"><?php echo JText::_('Published'); ?></th>
	</tr>
	</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->row->memberships);
	foreach ($this->row->memberships as $row)
	{
		if ($row->status == 0) // active
			$image = 'images/publish_g.png';
		elseif ($row->status == 1) // pending
			$image = 'images/publish_y.png';
		elseif ($row->status == 2) // expired
			$image = 'images/publish_r.png';
		elseif ($row->status == 3) // cancelled
			$image = 'images/publish_x.png';
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $i+1; ?></td>
			<td align="center">
			<a class="delete-item" onclick="return confirm('<?php echo JText::_('RSM_CONFIRM_DELETE'); ?>')" href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=users&task=membershipremove&cid_memberships[]='.$row->id.'&'.JUtility::getToken().'=1&cid='.$row->user_id.'&tabposition=1'); ?>"><?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/remove.png', JText::_('Delete')); ?></a>
			</td>
			<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=users&task=editmembership&tmpl=component&cid='.$row->id); ?>" onclick="SqueezeBox.fromElement(this); return false;" class="modal" rel="{handler: 'iframe', size: {x: 660, y: 475}}"><?php echo $row->name != '' ? $row->name : JText::_('RSM_NO_TITLE'); ?></a></td>
			<td>
				<?php echo RSMembershipHelper::getPriceFormat($row->price); ?>
			</td>
			<td><?php echo date(RSMembershipHelper::getConfig('date_format'), $row->membership_start); ?></td>
			<td><?php echo $row->membership_end > 0 ? date(RSMembershipHelper::getConfig('date_format'), $row->membership_end) : JText::_('RSM_UNLIMITED'); ?></td>
			<td><?php echo JText::_('RSM_STATUS_'.$row->status); ?></td>
			<td align="center"><?php echo JHTML::_('image', JURI::root().'administrator/'.$image, JText::_('RSM_STATUS')); ?></td>
			<td align="center"><?php echo JHTML::_('grid.published', $row, $i); ?></td>
		</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
</table>