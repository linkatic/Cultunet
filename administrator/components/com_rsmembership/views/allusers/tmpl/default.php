<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">
function clear_search()
{
	var form = document.adminForm;
	
	form.getElementById('search').value='';
	form.submit();
}

function add_user(id, email)
{
	window.parent.document.getElementById('email').innerHTML = email;
	window.parent.document.adminForm.user_id.value = id;
	window.parent.document.getElementById('sbox-window').close();
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=users&controller=users'); ?>" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="100%">
			  	<?php echo JText::_( 'SEARCH' ); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->filter_word; ?>" class="text_area" onChange="document.adminForm.submit();" />
				<button type="submit"><?php echo JText::_( 'Go' ); ?></button>
				<button type="button" onclick="clear_search();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">&nbsp;</td>
		</tr>
	</table>
	<div id="editcell1">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_( '#' ); ?></th>
				<th width="50"><?php echo JHTML::_('grid.sort', 'RSM_USER_ID', 'id', $this->sortOrder, $this->sortColumn); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'Name', 'name', $this->sortOrder, $this->sortColumn); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'Email', 'email', $this->sortOrder, $this->sortColumn); ?></th>
			</tr>
			</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->users);
	foreach ($this->users as $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $this->pagination->getRowOffset($i); ?></td>
			<td><?php echo $row->id; ?></td>
			<td><?php echo $row->name; ?></td>
			<td><a href="javascript: add_user('<?php echo $row->id; ?>', '<?php echo $row->email; ?>');"><?php echo $row->email; ?></a></td>
		</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
		<tfoot>
			<tr>
				<td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		</table>
	</div>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_rsmembership" />
	<input type="hidden" name="view" value="allusers" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="tmpl" value="component" />
	
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortOrder; ?>" />
</form>