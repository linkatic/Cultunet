<?php
/**
 * Template fields
 *
 * @package CSVImproved
 * @subpackage Templates
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: templatefields.php 947 2009-08-05 08:00:22Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 

$hiddenfields = '';
?>
<form action="index.php" method="post" name="adminForm">
	<div id="fieldlistdiv">
		
		<table class="adminlist">
			<thead>
				<tr>
					<th class="title"><?php echo JText::_('New field ordering'); ?></th>
					<th class="title"><?php echo JText::_('New field name'); ?></th>
					<th class="title"><?php echo JText::_('New column header'); ?></th>
					<th class="title"><?php echo JText::_('New default value'); ?></th>
					<th class="title"><?php echo JText::_('Add'); ?></th>
			</thead>
			<tr class="row0">
				<td class="center"><input type="text" name="field[{fill}][_order]" value="<?php echo $this->totalfields; ?>" size="4" /></td>
				<td>
				<select name="field[{fill}][_field_name]">
				<?php
				foreach ($this->csvisupportedfields as $fieldid => $fieldname) {
					echo '<option value="'.$fieldname.'">'.$fieldname.'</option>';
				}
				?>
				</select>
				</td>
					<td><input type="text" name="field[{fill}][_column_header]" value="" /></td>
				<td id="newfield_defaultvalue"><input type="text" name="field[{fill}][_default_value]" value="" /></td>
				<td class="center"><?php echo JHTML::_('link', JRoute::_('index.php?option=com_csvimproved&controller=templates&task=fieldstemplate&template_id='.$this->template_id), '<img src="'.JURI::root().'administrator/components/com_csvimproved/assets/images/csvivirtuemart_add_32.png" width="20" height="20" alt="'.JText::_('Add').'" />', 'onClick="document.adminForm.task.value = \'addfield\';document.adminForm.submit();return false;"'); ?></td>
			</tr>
			
			</table>
			<br clear="all" />
			<!-- Search field for limiting the fields -->
			<div id="filterbox">
			<?php echo JText::_('Filter'); ?> <input type="text" id="filterfield" name="filterfield" value="<?php echo $this->filter; ?>" onChange="document.adminForm.submit();" />
			</div>
			<table class="adminlist">
			<thead>
			<tr>
				<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->fields); ?>);" /></th>
				<th width="10%" class="title"><?php echo JText::_('Field ordering'); ?> <a href="index2.php" onclick="document.adminForm.task.value='renumber'; document.adminForm.submit(); return false;"><img src="<?php echo JURI::root().'administrator/components/com_csvimproved/assets/images/csvivirtuemart_order_16.png'; ?>" alt="<?php echo JText::_('Renumber');?>" title="<?php echo JText::_('Renumber');?>"></a></th>
				<th class="title"><?php echo JText::_('Field name'); ?></th>
				<th class="title"><?php echo JText::_('Column header'); ?></th>
				<th class="title"><?php echo JText::_('Default value'); ?></th>
				<th class="title"><?php echo JText::_('Published') ?></th>
				<th class="title"><?php echo JText::_('Delete'); ?></th>
			</tr>
			</thead>
			<?php
			$rowcolor = 0;
			for ($i=0, $n=count($this->fields); $i < $n; $i++) {
				if ($rowcolor > 1) $rowcolor = 0;
				if (isset($this->fields[$i])) {
					$field = $this->fields[$i];
					
					$img 	= $field->published ? 'tick.png' : 'publish_x.png';
					$task 	= $field->published ? 'unpublish' : 'publish';
					$alt 	= $field->published ? JText::_('Published') : JText::_('Unpublished');
					
					$checked = JHTML::_('grid.checkedout', $field, $i);
				}
				?>
				<tr class="row<?php echo $rowcolor; ?>" id="tr<?php echo $i;?>">
				<?php $rowcolor++; ?>
				<td>
				<?php echo $checked; ?>
				</td>
				<td class="center"><input type="text" name="field[<?php echo $i ?>][_order]" value="<?php echo $field->field_order ?>"
				size="4" /></td>
				<td>
				<select name="field[<?php echo $i ?>][_field_name]">
					<?php
					foreach ($this->csvisupportedfields as $fieldid => $fieldname) {
						echo '<option value="'.$fieldname.'"';
						if ($field->field_name == $fieldname) echo ' selected="selected"';
						echo '>'.$fieldname.'</option>';
						echo "\n";
					}
					?>
				</select>
				</td>
				<!-- Column header -->
				<td><input class="column_header_input" type="text" name="field[<?php echo $i ?>][_column_header]"
				value="<?php echo $field->field_column_header ?>" /></td>
				<!-- Default value -->
				<td><input type="text" name="field[<?php echo $i ?>][_default_value]"
				value="<?php echo $field->field_default_value ?>" /></td>
				<td class="center">
				<a href="index.php" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" title="<?php echo $alt; ?>" />
				</a>
				</td>
				<td class="center">
				<a href="index.php" onClick="document.adminForm.cb<?php echo $i;?>.checked = true; document.adminForm.task.value='deletefield'; document.adminForm.submit(); return false;">
                    <img src="<?php echo JURI::root().'/administrator/components/com_csvimproved/assets/images/csvivirtuemart_delete_32.png'; ?>" width="20" height="20" border="0" alt="<?php echo $alt; ?>" />
                </a>
				</td>
				</tr>
				<?php
					$hiddenfields .= '<input type="hidden" name="field['.$i.'][_id]" value="'.$field->id.'" />'.chr(10);
				?>
				<?php
			}
			?>
			<tr>
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</table>
		<?php
		/* All fields have been added, lets add the hidden fields */
		echo '<div id="formhiddenfields">';
		echo $hiddenfields;
		echo '</div>';
		?>
	</div> <!-- End fieldslist  -->
	<input type="hidden" name="option" value="com_csvimproved" />
	<input type="hidden" name="task" value="fieldstemplate" />
	<input type="hidden" name="controller" value="templates" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="template_id" value="<?php echo $this->template_id; ?>" />
</form>