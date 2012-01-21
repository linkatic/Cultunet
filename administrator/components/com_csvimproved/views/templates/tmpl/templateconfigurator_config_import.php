<?php
/**
 * Template configurator file paths
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: templateconfigurator_config_import.php 945 2009-07-30 07:18:43Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

$row = 0; ?>
<table class="adminform">
	<!-- Template type -->
	<tr class="row<?php echo $row; ?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Choose the type of import the template has to make'), JText::_('Template type'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('Template type'); ?></td>
		<td><?php echo $this->lists['supportedfields']; ?></td>
	</tr>
	<!-- Use column headers -->
	<tr class="row<?php echo $row = 1 - $row; ?>">
	<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Use the first line of the imported file as configuration instead of the fields assigned to this template.'), JText::_('Use column headers as configuration'), 'tooltip.png', '', '', false); ?> 
	<?php echo JText::_('Use column headers as configuration');?></td>
	<td><input class="template_input checkbox" type="checkbox" id="use_column_headers" name="use_column_headers" value="1"
	<?php if ($this->template->use_column_headers == 1) echo ' checked=checked'; ?>
	onClick="if (document.adminForm.use_column_headers.checked == true) { 
			document.adminForm.skip_first_line.checked = false; 
			}">
	</td>
	</tr>
	<!-- Skip first line -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Skip the first line on import. Use this if the import file contains column headers but the fields assigned to this template need to be used.'), JText::_('Skip first line'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Skip first line'); ?></td>
		<td><input class="template_input checkbox" type="checkbox" id="skip_first_line" name="skip_first_line" value="1"
		<?php if ($this->template->skip_first_line == 1) echo ' checked=checked'; ?>
		onClick="if (document.adminForm.skip_first_line.checked == true) { 
				document.adminForm.use_column_headers.checked = false; 
				}">
	</td>
	</tr>
	
	<!-- Overwrite existing data -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Overwrite existing data will overwrite all data for each record. When not set, a record will be skipped if it exists.'), JText::_('Overwrite existing data'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Overwrite existing data'); ?></td>
		<td><input class="template_input checkbox" type="checkbox" id="overwrite_existing_data" name="overwrite_existing_data" value="1"
		<?php if ($this->template->overwrite_existing_data) echo ' checked=checked'; ?>
		>
		</td>
	</tr>
	
	<!-- Append categories -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Append categories to existing categories instead of overwriting them'), JText::_('Append categories'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Append categories'); ?></td>
		<td><input class="template_input checkbox" type="checkbox" id="append_categories" name="append_categories" value="1"
		<?php if ($this->template->append_categories) echo ' checked=checked'; ?>
		>
		</td>
	</tr>
	
	<!-- Ignore non-existent products -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Ignore non-existing products will not create any new products if the product SKU cannot be found.'), JText::_('Ignore non-existing products'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Ignore non-existing products'); ?></td>
		<td><input class="template_input checkbox" type="checkbox" id="ignore_non_exist" name="ignore_non_exist" value="1"
		<?php if ($this->template->ignore_non_exist) echo ' checked=checked'; ?>
		>
		</td>
	</tr>
	
	<!-- Skip default value -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Default values set in the assigned fields will not be used when set.'), JText::_('Skip default value'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Skip default value'); ?></td>
		<td><input class="template_input checkbox" type="checkbox" id="skip_default_value" name="skip_default_value" value="1"
		<?php if ($this->template->skip_default_value) echo ' checked=checked'; ?>
		>
		</td>
	</tr>
	
	<!-- Show preview -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Show a 5-line preview before importing'), JText::_('Show preview'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Show preview'); ?></td>
		<td><input class="template_input checkbox" type="checkbox" id="show_preview" name="show_preview" value="1"
		<?php if ($this->template->show_preview) echo ' checked=checked'; ?>>
		</td>
	</tr>
	
	<!-- Automatic thumbnail creation -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('If enabled, thumbnails will be created automatically of image files'), JText::_('Automatic thumbnail creation'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Automatic thumbnail creation'); ?></td>
		<td><input class="template_input checkbox" type="checkbox" id="thumb_create" name="thumb_create" value="1"
		<?php if ($this->template->thumb_create) echo ' checked=checked'; ?>></td>
	</tr>
	
	<!-- Thumbnail format -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Select the output format of the thumbnails. Leave on default to use the format of the master file. This can be used to create thumbnails that are all in the same format.'), JText::_('Thumbnail format'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Thumbnail format'); ?></td>
		<td><?php echo $this->lists['thumbnailformat']; ?></td>
	</tr>
	
	<!-- Thumbnail width -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Importing image files will create thumbnails of the size set here'), JText::_('Thumbnail width x height'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Thumbnail width x height'); ?></td>
		<td><input class="template_input thumbs" type="text" maxlength="4" id="thumb_width" name="thumb_width" value="<?php echo $this->template->thumb_width;?>">
		<span class="template_img_symbol">x</span>
		<input class="thumbs" type="text" maxlength="4" id="thumb_height" name="thumb_height" value="<?php echo $this->template->thumb_height; ?>">
		</td>
	</tr>
	
	<!-- Collect debug info -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Collect debug information to see what is happening on import.<br /><br />Use with caution on big files as the output will be a lot.'), JText::_('Collect debug information'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Collect debug information'); ?></td>
		<td><input class="template_input checkbox" type="checkbox" id="collect_debug_info" name="collect_debug_info" value="1"
		<?php if ($this->template->collect_debug_info) echo ' checked=checked'; ?>
		>
		</td>
	</tr>
</table>
