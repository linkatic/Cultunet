<?php defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );?>
<table class="adminlist">
	<thead>
		<tr><th colspan="5"><?php echo $this->templatename; ?></th></tr>
		<tr>
			<th><?php echo JText::_('#'); ?></th>
			<th><?php echo JText::_('Field name'); ?></th>
			<th><?php echo JText::_('Column header'); ?></th>
			<th><?php echo JText::_('Default value'); ?></th>
			<th><?php echo JText::_('Published'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->fields as $key => $field) { 
			$img = $field->published ? 'tick.png' : 'publish_x.png';
			$alt = $field->published ? JText::_('Published') : JText::_('Unpublished');
		?>
		<tr>
			<td><?php echo $field->field_order; ?></td>
			<td><?php echo $field->field_name; ?></td>
			<td><?php echo $field->field_column_header; ?></td>
			<td><?php echo $field->field_default_value; ?></td>
			<td><?php echo JHTML::_('image', JURI::root().'administrator/images/'.$img, $alt); ?></td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
	</tfoot>
</table>
