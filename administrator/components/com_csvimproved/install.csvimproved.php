<?php
/**
 * Installation file for CSV Improved
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: install.csvimproved.php 948 2009-08-05 08:19:28Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Joomla installer
 *
 * The Joomla installer calls this function on installation, here process
 * some tasks to prepare for use
 */
function com_install() {
	$database = JFactory::getDBO();
	$error = false;
	/* Check for missing fields */
	/* Get the current columns */
	$q = "SHOW COLUMNS FROM #__csvi_templates";
	$database->setQuery($q);
	$cols = $database->loadObjectList('Field');
	
	if (is_array($cols)) {
		/* Rename the file_location column to file_location_product_images */
		if (array_key_exists('file_location', $cols)) {
			$q = "ALTER IGNORE TABLE #__csvi_templates CHANGE ".$database->nameQuote('file_location')." ".$database->nameQuote('file_location_product_images')." VARCHAR(255) DEFAULT NULL";
			$database->setQuery($q);
			$database->query();
		}
		
		/* Check if we have the append_categories column */
		if (!array_key_exists('append_categories', $cols)) {
			$q = "ALTER IGNORE TABLE #__csvi_templates ADD COLUMN ".$database->nameQuote('append_categories')." TINYINT(1) NOT NULL DEFAULT 0";
			$database->setQuery($q);
			$database->query();
		}
		
		/* Check if we have the export_date_format column */
		if (!array_key_exists('export_date_format', $cols)) {
			$q = "ALTER IGNORE TABLE #__csvi_templates ADD COLUMN ".$database->nameQuote('export_date_format')." VARCHAR(25) NULL DEFAULT 'd/m/Y H:i:s'";
			$database->setQuery($q);
			$database->query();
		}
		
		/* Check if we have the add_currency_to_price column */
		if (!array_key_exists('add_currency_to_price', $cols)) {
			$q = "ALTER IGNORE TABLE #__csvi_templates ADD COLUMN ".$database->nameQuote('add_currency_to_price')." TINYINT(1) NULL DEFAULT '0'";
			$database->setQuery($q);
			$database->query();
		}
		
		/* Check if we have the use_system_limits column */
		if (!array_key_exists('use_system_limits', $cols)) {
			$q = "ALTER IGNORE TABLE #__csvi_templates ADD COLUMN ".$database->nameQuote('use_system_limits')." TINYINT(1) NULL DEFAULT '0'";
			$database->setQuery($q);
			$database->query();
		}
		
		/* Check if we have the file_location_category_images column */
		if (!array_key_exists('file_location_category_images', $cols)) {
			$q = "ALTER IGNORE TABLE #__csvi_templates ADD COLUMN ".$database->nameQuote('file_location_category_images')." VARCHAR(255) NULL";
			$database->setQuery($q);
			$database->query();
		}
		
		/* Check if we have the file_location_media column */
		if (!array_key_exists('file_location_media', $cols)) {
			$q = "ALTER IGNORE TABLE #__csvi_templates ADD COLUMN ".$database->nameQuote('file_location_media')." VARCHAR(255) NULL";
			$database->setQuery($q);
			$database->query();
		}
		
		/* Check if we have the file_location_export_files column */
		if (!array_key_exists('file_location_export_files', $cols)) {
			$q = "ALTER IGNORE TABLE #__csvi_templates ADD COLUMN ".$database->nameQuote('file_location_export_files')." VARCHAR(255) NULL";
			$database->setQuery($q);
			$database->query();
		}
		
		/* List of standard templates */
		$standardtemplates = array('CSVI Google Base export XML',
							'CSVI Product import',
							'CSVI Product files import',
							'CSVI Multiple Prices import',
							'CSVI Manufacturer details import',
							'CSVI Category details import', 
							'CSVI Category details export',
							'CSVI Multiple Prices export',
							'CSVI Product files export',
							'CSVI Product export');
		
		/* Get a list of templates */
		$database->setQuery("SELECT template_name FROM #__csvi_templates");
		$database->query();
		$installedtemplates = $database->loadResultArray();
		
		/* Check if there are templates not yet */
		$installtemplates = array_diff($standardtemplates, $installedtemplates);
		
		/* Set the headers for template insert */
		$templateinsert = "INSERT IGNORE INTO `#__csvi_templates` (
						`template_name`, 
						`template_type`, 
						`skip_first_line`, 
						`use_column_headers`, 
						`collect_debug_info`, 
						`overwrite_existing_data`, 
						`skip_default_value`, 
						`show_preview`, 
						`include_column_headers`, 
						`text_enclosure`, 
						`field_delimiter`, 
						`export_type`, 
						`export_site`, 
						`thumb_width`, 
						`thumb_height`, 
						`shopper_group_id`, 
						`producturl_suffix`, 
						`file_location_product_images`, 
						`product_publish`,
						`max_execution_time`,
						`max_input_time`,
						`memory_limit`,
						`post_max_size`,
						`upload_max_filesize`
						) VALUES ";
						
		/* Get system limit values */
		$max_execution_time = intval(ini_get('max_execution_time'));
		$max_input_time = intval(ini_get('max_input_time'));
		$memory_limit = intval(ini_get('memory_limit'));
		$post_max_size = intval(ini_get('post_max_size'));
		$upload_max_filesize = intval(ini_get('max_execution_time'));
		
		/* Standard templates data */
		$templatedata = array();
		$templatedata[] = "('CSVI Google Base Export XML', 'productexport', 0, 1, 1, 1, 0, 0, 1, '~', '^', 'xml', 'froogle', 0, 0, NULL, NULL, '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		$templatedata[] = "('CSVI Product Import', 'productimport', 1, 0, 0, 1, 0, 1, 0, '~', '^', 'csv', '', 0, 0, 0, '', '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		$templatedata[] = "('CSVI Product Export', 'productexport', 0, 0, 0, 0, 0, 0, 1, '~', '^', 'csv', '', 0, 0, 0, '', '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		$templatedata[] = "('CSVI Product files import', 'productfilesupload', 1, 0, 0, 1, 0, 1, 0, '~', '^', 'csv', '', 0, 0, 0, '', '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		$templatedata[] = "('CSVI Multiple Prices import', 'multiplepricesimport', 1, 0, 0, 1, 0, 1, 0, '~', '^', 'csv', '', 0, 0, 0, '', '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		$templatedata[] = "('CSVI Multiple Prices export', 'multiplepricesexport', 0, 0, 0, 0, 0, 0, 1, '~', '^', 'csv', '', 0, 0, 0, '', '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		$templatedata[] = "('CSVI Manufacturer details import', 'manufacturerimport', 1, 0, 0, 1, 0, 1, 0, '~', '^', 'csv', '', 0, 0, 0, '', '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		$templatedata[] = "('CSVI Category details import', 'categorydetailsimport', 1, 0, 0, 1, 0, 1, 0, '~', '^', 'csv', '', 0, 0, 0, '', '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		$templatedata[] = "('CSVI Category details export', 'categorydetailsexport', 0, 0, 0, 0, 0, 0, 1, '~', '^', 'csv', '', 0, 0, 0, '', '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		$templatedata[] = "('CSVI Product files export', 'productfilesexport', 1, 0, 0, 1, 0, 1, 1, '~', '^', 'csv', '', 0, 0, 0, '', '', '', ".$max_execution_time.",".$max_input_time.",".$memory_limit.",".$post_max_size.",".$upload_max_filesize.")";
		
		/* Set the headers for template field insert */
		$templatefieldinsert = "INSERT IGNORE INTO `#__csvi_template_fields` (`field_template_id`, `field_name`, `field_column_header`, `field_default_value`, `field_order`, `published`) VALUES ";
		
		/* Standard templatefields data */
		$templatefielddata[1][] = "({lastid}, 'product_name', 'title', '', 1, 1)";
		$templatefielddata[1][] = "({lastid}, 'product_url', 'link', 'http://www.yoursite.com/', 2, 1)";
		$templatefielddata[1][] = "({lastid}, 'product_desc', 'description', '', 3, 1)";
		$templatefielddata[1][] = "({lastid}, 'product_sku', 'g:id', '', 4, 1)";
		$templatefielddata[1][] = "({lastid}, 'picture_url', 'g:image_link', '', 5, 1)";
		$templatefielddata[1][] = "({lastid}, 'product_price', 'g:price', '', 6, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:condition', 'new', 7, 1)";
		$templatefielddata[1][] = "({lastid}, 'manufacturer_name', 'g:brand', 'Not Available', 8, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:condition', 'New', 9, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:payment_notes', '30 Days Money Back Guarantee', 10, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:expiration_date', 'None', 11, 1)";
		$templatefielddata[1][] = "({lastid}, 'product_width', 'g:width', 'Not Available', 12, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:payment_accepted', 'Check', 13, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:payment_accepted', 'Visa', 14, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:payment_accepted', 'MasterCard', 15, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:payment_accepted', 'AmericanExpress', 16, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:payment_accepted', 'Paypal', 17, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:payment_accepted', 'Money order', 18, 1)";
		$templatefielddata[1][] = "({lastid}, 'product_weight', 'g:weight', 'Not Available', 19, 1)";
		$templatefielddata[1][] = "({lastid}, 'product_length', 'g:lenght', 'Not Available', 20, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:price_type', 'Non Negotiable', 21, 1)";
		$templatefielddata[1][] = "({lastid}, 'product_in_stock', 'g:quantity', 'Not Available', 22, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:tax_region', 'Florida', 23, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:tax_percent', '6', 24, 1)";
		$templatefielddata[1][] = "({lastid}, 'attribute', 'g:tech_spec_link', 'http://www.yoursite.com/technical_specifications.html', 25, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:pickup', 'FALSE', 26, 1)";
		$templatefielddata[1][] = "({lastid}, 'custom', 'g:shipping', 'US:UPS Ground:5.00', 27, 1)";
		
		$templatefielddata[2][] = "({lastid}, 'category_path', '', '', 3, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_availability', '', '', 10, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_available_date', '', '', 11, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_currency', '', 'EUR', 12, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_desc', '', '', 6, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_discount', '', '', 13, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_discount_date_end', '', '', 14, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_discount_date_start', '', '', 15, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_full_image', '', '', 8, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_name', '', '', 4, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_packaging', '', '', 16, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_parent_sku', '', '', 2, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_price', '', '', 7, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_publish', '', 'Y', 17, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_s_desc', '', '', 5, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_sku', '', '', 1, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_special', '', '', 18, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_tax', '', '', 19, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_thumb_image', '', '', 9, 1)";
		$templatefielddata[2][] = "({lastid}, 'product_url', '', '', 20, 1)";
		
		$templatefielddata[3][] = "({lastid}, 'category_path', '', '', 3, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_availability', '', '', 10, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_available_date', '', '', 11, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_currency', '', 'EUR', 12, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_desc', '', '', 6, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_discount', '', '', 13, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_discount_date_end', '', '', 14, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_discount_date_start', '', '', 15, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_full_image', '', '', 8, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_name', '', '', 4, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_packaging', '', '', 16, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_parent_sku', '', '', 2, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_price', '', '', 7, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_publish', '', 'Y', 17, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_s_desc', '', '', 5, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_sku', '', '', 1, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_special', '', '', 18, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_tax', '', '', 19, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_thumb_image', '', '', 9, 1)";
		$templatefielddata[3][] = "({lastid}, 'product_url', '', '', 20, 1)";
		
		$templatefielddata[4][] = "({lastid}, 'product_files_file_description', '', '', 4, 1)";
		$templatefielddata[4][] = "({lastid}, 'product_files_file_name', '', '', 2, 1)";
		$templatefielddata[4][] = "({lastid}, 'product_files_file_published', '', '', 6, 1)";
		$templatefielddata[4][] = "({lastid}, 'product_files_file_title', '', '', 3, 1)";
		$templatefielddata[4][] = "({lastid}, 'product_files_file_url', '', '', 5, 1)";
		$templatefielddata[4][] = "({lastid}, 'product_sku', '', '', 1, 1)";
		
		$templatefielddata[5][] = "({lastid}, 'product_sku', '', '', 1, 1)";
		$templatefielddata[5][] = "({lastid}, 'product_currency', '', '', 3, 1)";
		$templatefielddata[5][] = "({lastid}, 'price_quantity_start', '', '', 4, 1)";
		$templatefielddata[5][] = "({lastid}, 'price_quantity_end', '', '', 5, 1)";
		$templatefielddata[5][] = "({lastid}, 'product_price', '', '', 2, 1)";
		
		$templatefielddata[6][] = "({lastid}, 'product_sku', '', '', 1, 1)";
		$templatefielddata[6][] = "({lastid}, 'product_currency', '', '', 3, 1)";
		$templatefielddata[6][] = "({lastid}, 'price_quantity_start', '', '', 4, 1)";
		$templatefielddata[6][] = "({lastid}, 'price_quantity_end', '', '', 5, 1)";
		$templatefielddata[6][] = "({lastid}, 'product_price', '', '', 2, 1)";
		
		$templatefielddata[7][] = "({lastid}, 'manufacturer_desc', '', '', 2, 1)";
		$templatefielddata[7][] = "({lastid}, 'manufacturer_email', '', '', 3, 1)";
		$templatefielddata[7][] = "({lastid}, 'manufacturer_name', '', '', 1, 1)";
		$templatefielddata[7][] = "({lastid}, 'manufacturer_category_name', '', '', 5, 1)";
		$templatefielddata[7][] = "({lastid}, 'manufacturer_url', '', '', 4, 1)";
		
		$templatefielddata[8][] = "({lastid}, 'category_path', '', '', 1, 1)";
		$templatefielddata[8][] = "({lastid}, 'category_description', '', '', 2, 1)";
		$templatefielddata[8][] = "({lastid}, 'category_thumb_image', '', '', 4, 1)";
		$templatefielddata[8][] = "({lastid}, 'category_full_image', '', '', 3, 1)";
		$templatefielddata[8][] = "({lastid}, 'category_publish', '', '', 8, 1)";
		$templatefielddata[8][] = "({lastid}, 'category_browsepage', '', '', 6, 1)";
		$templatefielddata[8][] = "({lastid}, 'category_products_per_row', '', '', 5, 1)";
		$templatefielddata[8][] = "({lastid}, 'category_flypage', '', '', 7, 1)";
		$templatefielddata[8][] = "({lastid}, 'category_list_order', '', '', 9, 1)";
		
		$templatefielddata[9][] = "({lastid}, 'category_path', '', '', 1, 1)";
		$templatefielddata[9][] = "({lastid}, 'category_description', '', '', 2, 1)";
		$templatefielddata[9][] = "({lastid}, 'category_thumb_image', '', '', 4, 1)";
		$templatefielddata[9][] = "({lastid}, 'category_full_image', '', '', 3, 1)";
		$templatefielddata[9][] = "({lastid}, 'category_publish', '', '', 8, 1)";
		$templatefielddata[9][] = "({lastid}, 'category_browsepage', '', '', 6, 1)";
		$templatefielddata[9][] = "({lastid}, 'category_products_per_row', '', '', 5, 1)";
		$templatefielddata[9][] = "({lastid}, 'category_flypage', '', '', 7, 1)";
		$templatefielddata[9][] = "({lastid}, 'category_list_order', '', '', 9, 1)";
		
		$templatefielddata[10][] = "({lastid}, 'product_files_file_description', '', '', 4, 1)";
		$templatefielddata[10][] = "({lastid}, 'product_files_file_name', '', '', 2, 1)";
		$templatefielddata[10][] = "({lastid}, 'product_files_file_published', '', '', 6, 1)";
		$templatefielddata[10][] = "({lastid}, 'product_files_file_title', '', '', 3, 1)";
		$templatefielddata[10][] = "({lastid}, 'product_files_file_url', '', '', 5, 1)";
		$templatefielddata[10][] = "({lastid}, 'product_sku', '', '', 1, 1)";
		
		
		foreach ($installtemplates as $key => $templatename) {
			/* Add the template */
			$database->setQuery($templateinsert.$templatedata[$key]);
			$database->query();
			
			$insertid = $database->insertid();
			/* Add the template data */
			foreach ($templatefielddata[$key+1] as $id => $fielddata) {
				$database->setQuery($templatefieldinsert.str_replace('{lastid}', $insertid, $fielddata));
				$database->query();
			}
		}
	}
	else {
		echo JText::_('PROBLEM_READING_TABLE_CSVI_TEMPLATES');
		$error = true;
	}
	
	/* Get the current columns */
	$q = "SHOW COLUMNS FROM #__csvi_logs";
	$database->setQuery($q);
	$cols = $database->loadObjectList('Field');
	
	if (is_array($cols)) {
		/* Check if we have the append_categories column */
		if (!array_key_exists('template_name', $cols)) {
			$q = "ALTER IGNORE TABLE #__csvi_logs ADD COLUMN ".$database->nameQuote('template_name')." VARCHAR(255) NULL DEFAULT NULL";
			$database->setQuery($q);
			$database->query();
		}
	}
	else {
		echo JText::_('PROBLEM_READING_TABLE_CSVI_LOGS');
		$error = true;
	}
	
	$db = JFactory::getDBO();
	/* Empty the available fields first */
	$q = "TRUNCATE TABLE ".$db->nameQuote('#__csvi_available_fields');
	$db->setQuery($q);
	$db->query();
	$tablenames = array('csvi_templates',
							'csvi_template_fields',
							'vm_category',
							'vm_category_xref',
							'vm_country',
							'vm_coupons',
							'vm_manufacturer',
							'vm_manufacturer_category',
							'vm_order_item',
							'vm_order_payment',
							'vm_order_status',
							'vm_order_user_info',
							'vm_orders',
							'vm_payment_method',
							'vm_product',
							'vm_product_attribute',
							'vm_product_attribute_sku',
							'vm_product_category_xref',
							'vm_product_discount',
							'vm_product_files',
							'vm_product_mf_xref',
							'vm_product_price',
							'vm_product_product_type_xref',
							'vm_product_relations',
							'vm_product_type',
							'vm_product_type_parameter',
							'vm_shopper_group',
							'vm_shopper_vendor_xref',
							'vm_tax_rate',
							'vm_user_info',
							'vm_vendor',
							'vm_vendor_category',
							'users');
	foreach ($tablenames as $key => $tablename) {
		$fields = DbFields($tablename);
		foreach ($fields as $name => $value) {
			$data = array();
			$data['id'] = 0;
			$csviname = $name;
			/* Rename certain fields */
			switch ($tablename) {
				case 'vm_country':
					switch ($name) {
						case 'country_name':
							$csviname = $name;
							break;
						default:
							$csviname = false;
							break;
					}
					break;
				case 'vm_product':
					switch($name) {
						case 'cdate':
							$csviname = 'product_cdate';
							break;
						case 'mdate':
							$csviname = 'product_mdate';
							break;
					}
					break;
				case 'vm_product_discount':
					switch($name) {
						case 'amount':
							$csviname = 'product_discount';
							break;
						case 'start_date':
							$csviname = 'product_discount_date_start';
							break;
						case 'end_date':
							$csviname = 'product_discount_date_end';
							break;
					}
					break;
				case 'vm_tax_rate':
					switch($name) {
						case 'tax_rate':
							$csviname = 'product_tax';
							break;
					}
					break;
				case 'vm_product_type_paramter':
					switch($name) {
						case 'parameter_name':
							$csviname = 'product_type_parameter_name';
							break;
						case 'parameter_label':
							$csviname = 'product_type_parameter_label';
							break;
						case 'parameter_description':
							$csviname = 'product_type_parameter_description';
							break;
						case 'parameter_list_order':
							$csviname = 'product_type_parameter_list_order';
							break;
						case 'parameter_parameter_type':
							$csviname = 'product_type_parameter_type';
							break;
						case 'parameter_values':
							$csviname = 'product_type_parameter_values';
							break;
						case 'parameter_multiselect':
							$csviname = 'product_type_parameter_multiselect';
							break;
						case 'parameter_default':
							$csviname = 'product_type_parameter_default';
							break;
						case 'parameter_unit':
							$csviname = 'product_type_parameter_unit';
							break;
					}
					break;
				case 'vm_product_files':
					switch($name) {
						case 'file_name':
							$csviname = 'product_files_file_name';
							break;
						case 'file_title':
							$csviname = 'product_files_file_title';
							break;
						case 'file_description':
							$csviname = 'product_files_file_description';
							break;
						case 'file_url':
							$csviname = 'product_files_file_url';
							break;
						case 'file_published':
							$csviname = 'product_files_file_published';
							break;
					}
					break;
				case 'vm_manufacturer':
					switch($name) {
						case 'mf_name':
							$csviname = 'manufacturer_name';
							break;
						case 'mf_email':
							$csviname = 'manufacturer_email';
							break;
						case 'mf_desc':
							$csviname = 'manufacturer_desc';
							break;
						case 'mf_url':
							$csviname = 'manufacturer_url';
							break;
						case 'mf_category_id':
							$csviname = 'manufacturer_category_id';
							break;
					}
					break;
				case 'vm_manufacturer_category':
					switch($name) {
						case 'mf_category_id':
							$csviname = 'manufacturer_category_id';
							break;
						case 'mf_category_name':
							$csviname = 'manufacturer_category_name';
							break;
						case 'mf_category_desc':
							$csviname = 'manufacturer_category_desc';
							break;
					}
					break;
				case 'vm_category':
					switch($name) {
						case 'products_per_row':
							$csviname = 'category_products_per_row';
							break;
						case 'list_order':
							$csviname = 'category_list_order';
							break;
					}
					break;
				case 'vm_orders':
					switch($name) {
						case 'cdate':
							$csviname = 'order_date';
							break;
						case 'mdate':
							$csviname = 'order_modified_date';
							break;
					}
					break;
				case 'vm_order_item':
					switch($name) {
						case 'product_item_price':
							$csviname = 'product_price';
							break;
						case 'order_item_sku':
							$csviname = 'product_sku';
							break;
						case 'order_item_name':
							$csviname = 'product_name';
							break;
					}
					break;
				case 'users':
					switch(strtolower($name)) {
						case 'email':
							$csviname = 'user_email';
							break;
						case 'sendemail':
							$csviname = 'sendemail';
							break;
						case 'registerdate':
							$csviname = 'registerdate';
							break;
					}
					break;
			}
			if ($csviname) {
				$q = "INSERT IGNORE INTO `#__csvi_available_fields` (`csvi_name`, `vm_name`, `vm_table`) VALUES";
				$q .= "(".$db->Quote($csviname).",".$db->Quote($name).",".$db->Quote($tablename).")";
				$db->setQuery($q);
				$db->query();
			}
		}
	}
	
	/* Install extra available fields */
	$q = JFile::read(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_csvimproved'.DS.'helpers'.DS.'availablefields_extra.sql');
	$db->setQuery($q);
	$db->query();
	
	echo '<div>'.JHTML::_('image', JURI::root().'/administrator/components/com_csvimproved/assets/images/logo.png', 'CSV Improved').'</div>';
	?>
	<br />
	<?php if ($error) { ?>
		CSVI VirtueMart 1.9 installation reported errors.
	<?php }
	else { ?>
		CSVI VirtueMart 1.9 has been succesfully installed.
		
	<?php } ?>
	<br /><br />
		Start using <?php echo JHTML::_('link', JURI::root().'administrator/index.php?option=com_csvimproved', 'CSVI VirtueMart'); ?>
		<br /><br />
		For support visit the website at <?php echo JHTML::_('link', 'http://www.csvimproved.com/', 'www.csvimproved.com', 'target="_new"'); ?>
	<br clear="all" />
	<?php
}

/**
 * Creates an array of custom database fields the user can use for import/export
 */
function DbFields($table) {
	$db = JFactory::getDBO();
	$q = "SHOW COLUMNS FROM ".$db->nameQuote('#__'.$table);
	$db->setQuery($q);
	$fields = $db->loadObjectList();
	$customfields = array();
	if (count($fields) > 0) {
		foreach ($fields as $key => $field) {
			$customfields[$field->Field] = null;
		}
	}
	return $customfields;
}
?>