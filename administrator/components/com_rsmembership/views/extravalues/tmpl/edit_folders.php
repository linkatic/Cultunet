<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

	<div class="button2-left"><div class="blank"><a class="modal" title="Select the path" rel="{handler: 'iframe', size: {x: 560, y: 375}}" href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=files&view=files&task=addfolder&tmpl=component&extra_value_id='.$this->row->id.'&function=addextravaluefolders'); ?>"><?php echo JText::_('RSM_ADD_FOLDERS'); ?></a></div></div>
	<span class="rsmembership_clear" style="margin-bottom: 10px;"></span>
	
	<table class="adminlist" id="addextravaluefolders">
	<thead>
	<tr>
		<th width="5"><?php echo JText::_( '#' ); ?></th>
		<th width="20">&nbsp;</th>
		<th width="20"><?php echo JText::_('Delete'); ?></th>
		<th><?php echo JText::_('RSM_PATH'); ?></th>
		<th width="80"><?php echo JText::_('Published'); ?></th>
		<th width="100"><?php echo JText::_('Ordering'); ?>
		<?php echo JHTML::_('grid.order', $this->row->folders, 'filesave.png', 'folderssaveorder'); ?>
		</th>
	</tr>
	</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->row->folders);
	foreach ($this->row->folders as $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $this->row->foldersPagination->getRowOffset($i); ?></td>
			<td><?php $cb = JHTML::_('grid.id', $i, $row->id, false, 'cid_folders'); echo str_replace('cb', 'cbfolders', $cb); ?></td>
			<td align="center">
			<a class="delete-item" onclick="return confirm('<?php echo JText::_('RSM_CONFIRM_DELETE'); ?>')" href="<?php echo JRoute::_('index.php?option=com_rsmembership&controller=extravalues&task=foldersremove&cid_folders[]='.$row->id.'&'.JUtility::getToken().'=1'); ?>"><?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/remove.png', JText::_('Delete')); ?></a>
			</td>
			<td><?php echo $row->path; ?></td>
			<td align="center"><?php echo JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'folders'); ?></td>
			<td class="order">
			<span><?php $cb = $this->row->foldersPagination->orderUpIcon( $i, true, 'foldersorderup', 'Move Up', 'ordering'); echo str_replace('cb', 'cbfolders', $cb); ?></span>
			<span><?php $cb = $this->row->foldersPagination->orderDownIcon( $i, $n, true, 'foldersorderdown', 'Move Down', 'ordering' ); echo str_replace('cb', 'cbfolders', $cb); ?></span>
			<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align:center" />
			</td>
		</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
	</table>