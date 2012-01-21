<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
$levelNames = array();
$levelNames[0] = JText::_('NOT CHOSEN');
?>
<?php echo BlogSystemFun::getSideMenu('1', 'Settings'); ?>
<form action="index.php" method="post" name="adminForm">

	<table class="adminform">
		<tr>
			<td width="100%" style="text-align:right;">
			  <?php echo $this->lists['state']; ?>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="1%" nowrap="nowrap"><?php echo JText::_('ID'); ?></th>
			<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
			<th class="title"><?php echo JText::_('GROUP'); ?></th>
			<th width="15"><?php echo JText::_('ASSIGNED'); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'ENABLED' ); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		$k = 0;
		$i = 0;
		$n = count($this->rows);
		foreach ($this->rows as $row)
		{
			$link = 'index.php?option=com_lyftenbloggie&amp;controller=groups&amp;task=edit&amp;group='. $row->group_id;
			$published 	= JHTML::_('grid.published', $row, $i );
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $row->id; ?></td>
			<td width="7"><input id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" type="checkbox"></td>
			<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->group; ?></a></td>
			<td align="center"><?php echo $row->assigned?></td>
			<td align="center"><?php echo $published?></td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		} 
		?>
	</tbody>

	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="groups" />
	<input type="hidden" name="view" value="groups" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	</td>
</tr>
</table>