<?php
/**
 * Template configurator file paths
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: templateconfigurator_file_paths.php 945 2009-07-30 07:18:43Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
$row = 0; ?>
<table class="adminform">
	<!-- File location product images -->
	<tr class="row1">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('FILE_LOCATION_PRODUCT_IMAGES'), JText::_('FILE_LOCATION_PRODUCT_IMAGES'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('FILE_LOCATION_PRODUCT_IMAGES');?></td>
		<td><input class="template_input longtext" type="text" id="file_location_product_images" name="file_location_product_images" size="255" value="<?php echo $this->template->file_location_product_images;?>">
	</tr>
	<tr>
		<td class="template_config_label"></td>
		<td>
		<span id="pathsuggest_product_images"><?php echo JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'shop_image'.DS.'product';?></span> | 
		<a href="#" onclick="document.getElementById('file_location_product_images').value=document.getElementById('pathsuggest_product_images').innerHTML; return false;"><?php echo JText::_('Paste');?></a> | 
		<a href="#" onclick="document.getElementById('file_location_product_images').value=''; return false;"><?php echo JText::_('Clear');?></a>
		</td>
	</tr>
	
	<!-- File location category images -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('FILE_LOCATION_CATEGORY_IMAGES'), JText::_('FILE_LOCATION_CATEGORY_IMAGES'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('FILE_LOCATION_CATEGORY_IMAGES');?></td>
		<td><input class="template_input longtext" type="text" id="file_location_category_images" name="file_location_category_images" size="255" value="<?php echo $this->template->file_location_category_images;?>">
	</tr>
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"></td>
		<td>
		<span id="pathsuggest_category_images"><?php echo JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'shop_image'.DS.'category';?></span> | 
		<a href="#" onclick="document.getElementById('file_location_category_images').value=document.getElementById('pathsuggest_category_images').innerHTML; return false;"><?php echo JText::_('Paste');?></a> | 
		<a href="#" onclick="document.getElementById('file_location_category_images').value=''; return false;"><?php echo JText::_('Clear');?></a>
		</td>
	</tr>
	
	<!-- File location media -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('FILE_LOCATION_MEDIA_TIP'), JText::_('FILE_LOCATION_MEDIA'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('FILE_LOCATION_MEDIA');?></td>
		<td><input class="template_input longtext" type="text" id="file_location_media" name="file_location_media" size="255" value="<?php echo $this->template->file_location_media;?>">
	</tr>
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"></td>
		<td>
		<span id="pathsuggest_media"><?php echo JPATH_SITE.DS.'media';?></span> | 
		<a href="#" onclick="document.getElementById('file_location_media').value=document.getElementById('pathsuggest_media').innerHTML; return false;"><?php echo JText::_('Paste');?></a> | 
		<a href="#" onclick="document.getElementById('file_location_media').value=''; return false;"><?php echo JText::_('Clear');?></a>
		</td>
	</tr>
	
	<!-- File location stored export files -->
	<tr class="row<?php echo $row = 1 - $row;?>">
		<td class="template_config_label"><?php echo JHTML::tooltip(JText::_('FILE_LOCATION_EXPORT_FILES_TIP'), JText::_('FILE_LOCATION_EXPORT_FILES'), 'tooltip.png', '', '', false); ?> 
		<?php echo JText::_('FILE_LOCATION_EXPORT_FILES');?></td>                                                                                                                           
		<td><input class="template_input longtext" type="text" id="file_location_export_files" name="file_location_export_files" size="255" value="<?php echo $this->template->file_location_export_files;?>">
	</tr>
	<tr>
		<td class="template_config_label"></td>
		<td>
		<span id="pathsuggest_export_files"><?php echo JPATH_SITE;?></span> | 
		<a href="#" onclick="document.getElementById('file_location_export_files').value=document.getElementById('pathsuggest_export_files').innerHTML; return false;"><?php echo JText::_('Paste');?></a> | 
		<a href="#" onclick="document.getElementById('file_location_export_files').value=''; return false;"><?php echo JText::_('Clear');?></a>
		</td>
	</tr>
</table>
