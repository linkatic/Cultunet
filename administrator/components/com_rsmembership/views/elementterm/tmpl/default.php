<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=elementterm&tmpl=component'); ?>" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->filter_word; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap"><?php echo $this->lists['state']; ?></td>
		</tr>
	</table>
	<div id="editcell1">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_( '#' ); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'RSM_TERM', 'name', $this->sortOrder, $this->sortColumn); ?></th>
				<th width="80"><?php echo JText::_('Published'); ?></th>
			</tr>
			</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->terms);
	foreach ($this->terms as $row)
	{
		$row->name = $row->name != '' ? $row->name : JText::_('RSM_NO_TITLE');
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $this->pagination->getRowOffset($i); ?></td>
			<td><a style="cursor:pointer" onclick="window.parent.elSelectEvent('<?php echo $row->id; ?>', '<?php echo str_replace("'", "\'", $row->name); ?>');"><?php echo $row->name; ?></a></td>
			<td align="center"><?php echo JText::_($row->published ? 'Yes' : 'No'); ?></td>
		</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
		<tfoot>
			<tr>
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		</table>
	</div>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="elementterm" />
	<input type="hidden" name="controller" value="terms" />
	<input type="hidden" name="task" value="element" />
	<input type="hidden" name="tmpl" value="component">
	
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortOrder; ?>" />
</form>