<?php defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?php $row = 0; ?>
<table class="adminform">
	<!-- Template type -->
	<tr class="row<?php echo $row; ?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Choose the type of export the template has to make'), JText::_('Template type'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('Template type'); ?></td>
		<td><?php echo $this->lists['supportedfields']; ?></td>
	</tr>
	<!-- Export type -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Select here the type of file to be exported'), JText::_('Export type'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Export type'); ?></td>
		<td>
			<select id="export_type" name="export_type">
				<option value="csv" <?php if ($this->template->export_type == "csv") echo 'selected="selected"'; ?>>CSV</option>
				<option value="xml" <?php if ($this->template->export_type == "xml") echo 'selected="selected"'; ?>>XML</option>
			</select>
		</td>
	</tr>
	
	<!-- Export site -->
	<?php
		$display = ($this->template->export_type == "xml") ? 'display' : 'none';
	?>
	<tr id="div_export_site" class="row<?php echo $row = 1 - $row;?>" style="display: <?php echo $display ?>;">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Select for which website the XML file should be generated.<br /><br />Only used with XML export.'), JText::_('Website'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('Website');?></td>
		<td><select name="export_site" id="export_site">
		<option value="" <?php if ($this->template->export_site == "") echo 'selected="selected"';?>><?php echo JText::_('Choose website...');?></option>
		<option value="csvi" <?php if ($this->template->export_site == "csvi") echo 'selected="selected"'; ?>>CSV Improved</option>
		<option value="beslist" <?php if ($this->template->export_site == "beslist") echo 'selected="selected"'; ?>>beslist.nl</option>
		<option value="froogle" <?php if ($this->template->export_site == "froogle") echo 'selected="selected"'; ?>>Google Base</option>
		<option value="oodle" <?php if ($this->template->export_site == "oodle") echo 'selected="selected"'; ?>>Oodle</option>
		</select>
		</td>
	</tr>
	
	<!-- Include column headers -->
	<tr id="div_include_column_headers" class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Select this option to include column headers in the exported file.<br /><br />Only used with CSV export.'), JText::_('Include column headers'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('Include column headers'); ?></td>
		<td><input class="template_input checkbox" type="checkbox" id="include_column_headers" name="include_column_headers" value="1" 
		<?php if ($this->template->include_column_headers) echo 'checked=checked'; ?>></td>
	</tr>
	
	<!-- Export filename -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Fill in a filename to have this template always export the same filename instead of an auto-generated one.'), JText::_('Filename for exported file'), 'tooltip.png', '', '', false); ?>
		<?php echo JText::_('Filename for exported file');?></td>
		<td><input class="template_input longtext" type="text" id="export_filename" name="export_filename" value="<?php echo $this->template->export_filename; ?>" /></td>
	</tr>
	
	<!-- Set shopper group name the user wants to export -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('Select a shopper group name to export products of that particular shopper group.'), JText::_('Shopper group name'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('Shopper group name');?></td>
		<td><select name="shopper_group_id">
		<option value="0"><?php echo JText::_('All');?></option>
		<?php foreach ($this->shopper_groups as $key => $group) { ?>
			<option value="<?php echo $group->shopper_group_id;?>"
			<?php if ($this->template->shopper_group_id == $group->shopper_group_id) echo 'selected="selected"'; ?>
			><?php echo $group->shopper_group_name;?></option>
		<?php } ?>
		</select>
	</tr>
	
	<!-- Set manufacturer name the user wants to export -->
	<?php $template_mf = explode(",", $this->template->manufacturer); ?>
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('MANUFACTURER_TIP'), JText::_('MANUFACTURER'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('MANUFACTURER');?></td>
		<td><select multiple name="manufacturer[]" size="7">
		<option value="0"
		<?php if (in_array("0", $template_mf)) echo 'selected="selected"'; ?>
		><?php echo JText::_('All');?></option>
		<?php foreach ($this->manufacturers as $key => $mf) { ?>
			<option value="<?php echo $mf->manufacturer_id;?>"
			<?php if (in_array($mf->manufacturer_id, $template_mf)) echo 'selected="selected"'; ?>
			><?php echo $mf->mf_name;?></option>
		<?php } ?>
		</select>
	</tr>
	
	<!-- Check which state of products the user want to export -->
	<tr>
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('EXPORT_STATE_TIP'), JText::_('EXPORT_STATE'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('EXPORT_STATE');?></td>
		<td><input type="radio" id="product_publish" name="product_publish" value=""
		<?php if ($this->template->product_publish == '') echo 'checked=checked'; ?>
		><?php echo JText::_('Both'); ?>
		<br />
		<input type="radio" id="product_publish" name="product_publish" value="Y"
		<?php if ($this->template->product_publish == 'Y') echo 'checked=checked'; ?>
		><?php echo JText::_('Published'); ?>
		<br />
		<input type="radio" id="product_publish" name="product_publish" value="N"
		<?php if ($this->template->product_publish == 'N') echo 'checked=checked'; ?>
		><?php echo JText::_('Unpublished'); ?></td>
	</tr>
	
	<!-- Product URL suffix -->
	<tr>
	<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('PRODUCT_URL_SUFFIX_TIP'), JText::_('PRODUCT_URL_SUFFIX'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('PRODUCT_URL_SUFFIX');?></td>
		<td><input class="template_input longtext" type="text" id="producturl_suffix" name="producturl_suffix" value="<?php echo $this->template->producturl_suffix; ?>" /></td>
	</tr>
	
	<!-- Date format -->
	<tr>
	<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('EXPORT_DATE_FORMAT'), JText::_('EXPORT_DATE_FORMAT'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('EXPORT_DATE_FORMAT');?></td>
		<td><input class="template_input" type="text" id="export_date_format" name="export_date_format" value="<?php echo $this->template->export_date_format; ?>" /></td>
	</tr>
	<!-- Add currency to price -->
	<tr>
	<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('EXPORT_ADD_CURRENCY_TO_PRICE_TIP'), JText::_('EXPORT_ADD_CURRENCY_TO_PRICE'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('EXPORT_ADD_CURRENCY_TO_PRICE');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'add_currency_to_price', '', $this->template->add_currency_to_price); ?></td>
	</tr>
</table>
<script type="text/javascript">
jQuery("#export_type").blur(function() {
	if (jQuery(this).val() == 'xml') {
		jQuery('#div_export_site').show();
		if (jQuery('#export_site').val() == '') {
			jQuery('#toolbar-csvi_save_32').hide();
		}
		else jQuery('#toolbar-csvi_save_32').show();
	}
	else {
		jQuery('#div_export_site').hide();
		jQuery('#toolbar-csvi_save_32').show();
	}
})

jQuery("#export_site").blur(function() {
	if (jQuery('#export_site').val() == '') {
		jQuery('#toolbar-csvi_save_32').hide();
	}
	else jQuery('#toolbar-csvi_save_32').show();
})

</script>
