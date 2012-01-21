<?php
/**
 * Product export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: productexport.php 955 2009-08-13 22:23:26Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for product exports
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelProductExport extends JModel {
	
	/* Private variables */
	private $_exportmodel = null;
	
	
	/** 
	 * Product export
	 *
	 * Exports product data to either csv or xml format
	 **/
	public function getStart() {
		
		/* Get some basic data */
		$db = JFactory::getDBO();
		$csvidb = new CsviDb();
		$csvilog = JRequest::getVar('csvilog');
		$template = JRequest::getVar('template');
		$xmlclass = JRequest::getVar('xmlclass');
		$export_fields = JRequest::getVar('exportfields');
		$vmitemid = JRequest::getInt('vmitemid');
		
		/* Get the general export functions */
		$this->_exportmodel = new CsvimprovedModelexportfile();
		
		/* Build something fancy to only get the fieldnames the user wants */
		$userfields = array();
		foreach ($export_fields as $column_id => $field) {
			switch ($field->field_name) {
				case 'attribute_name':
				case 'attribute_value':
				case 'attribute_values':
					$userfields[] = '#__vm_product_attribute.attribute_name';
					$userfields[] = '#__vm_product_attribute.attribute_value';
					break;
				case 'category_id':
				case 'category_path':
					$userfields[] = '#__vm_product_category_xref.category_id';
					$userfields[] = '#__vm_product.product_id AS main_product_id';
					break;
				case 'cdate':
				case 'product_cdate':
					$userfields[] = '#__vm_product.cdate';
					break;
				case 'manufacturer_id':
					$userfields[] = '#__vm_manufacturer.manufacturer_id AS manufacturer_id';
					break;
				case 'manufacturer_category_id':
					$userfields[] = 'mf_category_id AS manufacturer_category_id';
					break;
				case 'manufacturer_desc':
					$userfields[] = 'mf_desc AS manufacturer_desc';
					break;
				case 'manufacturer_email':
					$userfields[] = 'mf_email AS manufacturer_email';
					break;
				case 'manufacturer_name':
					$userfields[] = 'mf_name AS manufacturer_name';
					break;
				case 'manufacturer_url':
					$userfields[] = 'mf_url AS manufacturer_url';
					break;
				case 'mdate':
				case 'product_mdate':
					$userfields[] = '#__vm_product.mdate';
					break;
				case 'picture_url':
					$userfields[] = '#__vm_product.product_full_image';
					break;
				case 'product_price':
					$userfields[] = '#__vm_product_price.product_price';
					$userfields[] = '#__vm_product_price.product_currency';
					$userfields[] = '#__csvi_currency.currency_code';
					$userfields[] = '#__csvi_currency.currency_rate';
					break;
				case 'price_with_discount':
					$userfields[] = '#__vm_product_price.product_price';
					$userfields[] = '#__vm_product_price.product_currency';
					$userfields[] = '#__vm_product_discount.amount AS product_discount';
					$userfields[] = '#__vm_product_discount.is_percent AS product_discount_is_percent';
					$userfields[] = '#__vm_tax_rate.tax_rate';
					$userfields[] = '#__csvi_currency.currency_code';
					$userfields[] = '#__csvi_currency.currency_rate';
					break;
				case 'price_with_tax':
					$userfields[] = '#__vm_product_price.product_price';
					$userfields[] = '#__vm_product_price.product_currency';
					$userfields[] = '#__vm_tax_rate.tax_rate';
					$userfields[] = '#__csvi_currency.currency_code';
					$userfields[] = '#__csvi_currency.currency_rate';
					break;
				case 'product_box':
					$userfields[] = '#__vm_product.product_packaging';
					break;
				case 'product_tax':
					$userfields[] = '#__vm_tax_rate.tax_rate';
					break;
				case 'product_discount':
					$userfields[] = '#__vm_product_discount.amount AS product_discount';
					$userfields[] = '#__vm_product_discount.is_percent AS product_discount_is_percent';
					break;
				case 'product_discount_date_end':
					$userfields[] = '#__vm_product_discount.end_date AS product_discount_date_end';
					break;
				case 'product_discount_date_start':
					$userfields[] = '#__vm_product_discount.start_date AS product_discount_date_start';
					break;
				case 'product_list':
					$userfields[] = '#__vm_product_category_xref.product_list';
					break;
				case 'product_parent_sku':
				case 'attribute_values':
				case 'attributes':
					$userfields[] = '#__vm_product.product_parent_id';
					break;
				case 'product_id':
				case 'related_products':
					$userfields[] = '#__vm_product.product_id AS main_product_id';
					break;
				case 'product_sku':
					$userfields[] = '#__vm_product.product_id AS main_product_id';
					$userfields[] = '#__vm_product.product_sku';
					break;
				case 'shopper_group_name':
					$userfields[] = '#__vm_shopper_group.shopper_group_name';
					break;
				case 'shopper_group_id':
					$userfields[] = '#__vm_shopper_group.shopper_group_id';
					break;
				case 'vendor_id':
					$userfields[] = '#__vm_product.vendor_id';
					break;
				/* Man made fields, do not export them */
				case 'custom':
				case 'product_box':
					break;
				default:
					$userfields[] = '`'.$field->field_name.'`';
					break;
			}
		}
		
		/** Export SQL Query
		* Get all products - including items
		* as well as products without a price
		**/
		$userfields = array_unique($userfields);
		$q = "SELECT ".implode(",\n", $userfields);
		$q .= ' FROM #__vm_product
			LEFT JOIN #__vm_product_price
			ON #__vm_product.product_id = #__vm_product_price.product_id
			LEFT JOIN #__vm_product_mf_xref
			ON #__vm_product.product_id = #__vm_product_mf_xref.product_id
			LEFT JOIN #__vm_shopper_group
			ON #__vm_product_price.shopper_group_id = #__vm_shopper_group.shopper_group_id
			LEFT JOIN #__vm_product_discount
			ON #__vm_product.product_discount_id = #__vm_product_discount.discount_id
			LEFT JOIN #__vm_manufacturer
			ON #__vm_product_mf_xref.manufacturer_id = #__vm_manufacturer.manufacturer_id
			LEFT JOIN #__vm_tax_rate
			ON #__vm_product.product_tax_id = #__vm_tax_rate.tax_rate_id 
			LEFT JOIN #__vm_product_attribute
			ON #__vm_product.product_id = #__vm_product_attribute.product_id
			LEFT JOIN #__vm_product_attribute_sku
			ON #__vm_product.product_id = #__vm_product_attribute_sku.product_id
			LEFT JOIN #__vm_product_category_xref
			ON #__vm_product.product_id = #__vm_product_category_xref.product_id
			LEFT JOIN #__vm_category
			ON #__vm_product_category_xref.category_id = #__vm_category.category_id
			LEFT JOIN #__csvi_currency
			ON #__vm_product_price.product_currency = #__csvi_currency.currency_code';
			
			
		/* Check if there are any selectors */
		$selectors = array();
		/* Filter by shopper group id */
		if ($template->shopper_group_id > 0) {                
			$selectors[] = '#__vm_product_price.shopper_group_id = '.$template->shopper_group_id;
		}
		
		/* Filter by published state */
		if (!empty($template->product_publish)) {
			$selectors[] = '#__vm_product.product_publish = '.$db->Quote($template->product_publish);
		}
		
		/* Filter by manufacturer */
		if (!empty($template->manufacturer) && ($template->manufacturer > 0)) {
			$selectors[] = '#__vm_manufacturer.manufacturer_id IN ('.$template->manufacturer.')';
		}
		
		/* Filter by product category */
		$productcategories = JRequest::getVar('productcategories', false);
		if ($productcategories && $productcategories[0] != '') {
			$selectors[] = '#__vm_product_category_xref.category_id IN (\''.implode("','", $productcategories).'\')';
		}
		
		/* Filter by stocklevel start */
		$stocklevelstart = JRequest::getVar('stocklevelstart', false);
		if ($stocklevelstart) {                
			$selectors[] = '#__vm_product.product_in_stock >= '.$stocklevelstart;
		}
		
		/* Filter by stocklevel end */
		$stocklevelend = JRequest::getVar('stocklevelend', false);
		if ($stocklevelend) {                
			$selectors[] = '#__vm_product.product_in_stock <= '.$stocklevelend;
		}
		
		/* Filter by stocklevel end */
		$productskufilter = JRequest::getVar('productskufilter', false);
		if ($productskufilter) {                
			$selectors[] = "#__vm_product.product_sku LIKE '%".$productskufilter."%'";
		}
		
		/* Check if we need to attach any selectors to the query */
		if (count($selectors) > 0 ) $q .= ' WHERE '.implode(' AND ', $selectors)."\n";
		
		/* Check if we need to group the orders together */
		$groupby = JRequest::getVar('groupby', false);
		if ($groupby) {
			$q .= " GROUP BY ";
			foreach ($export_fields as $column_id => $field) {
				switch ($field->field_name) {
					case 'attribute_list':
					case 'attribute_values':
					case 'attributes':
					case 'category_path':
					case 'category_id':
					case 'custom':
					case 'picture_url':
					case 'price_with_discount':
					case 'price_with_tax':
					case 'product_box':
					case 'product_parent_sku':
					case 'product_tax':
					case 'related_products':
						break;
					case 'product_cdate':
						$q .= '#__vm_product.cdate, ';
						break;
					case 'product_mdate':
						$q .= '#__vm_product.mdate, ';
						break;
					case 'product_id':
						$q .= '#__vm_product.product_id, ';
						break;
					case 'manufacturer_category_id':
						$q .= 'mf_category_id, ';
						break;
					case 'manufacturer_desc':
						$q .= 'mf_desc, ';
						break;
					case 'manufacturer_email':
						$q .= 'mf_email, ';
						break;
					case 'manufacturer_name':
						$q .= 'mf_name, ';
						break;
					case 'manufacturer_url':
						$q .= 'mf_url, ';
						break;
					case 'shopper_group_id':
						$q .= '#__vm_shopper_group.shopper_group_id, ';
						break;
					default:
						$q .= '`'.$field->field_name.'`, ';
						break;
				}
			}
			$q = substr($q, 0, -2);
		}
		
		/* Order products by SKU */
		$q .= ' ORDER BY #__vm_product.product_sku ';
		
		/* Add export limits */
		$q .= $this->_exportmodel->ExportLimit();
		
		/* Execute the query */
		$csvidb->setQuery($q);
		
		$logcount = $db->getAffectedRows();
		JRequest::setVar('logcount', array('export' => $logcount));
		if ($logcount > 0) {
			while ($record = $csvidb->getRow()) {
				$contents = '';
				if ($template->export_type == 'xml') $contents .= $xmlclass->NodeStart();
				foreach ($export_fields as $column_id => $field) {
					$fieldname = $field->field_name;
					switch ($fieldname) {
						case 'category_path':
							$category_path = trim($this->_exportmodel->GetCategoryPath($record->main_product_id));
							if (strlen(trim($category_path)) == 0) $category_path = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($category_path, $fieldname, $field->column_header);
							break;
						case 'attributes':
							if ($record->product_parent_id == 0) {
								$attributes = $export_sku = "";
								$db->setQuery("SELECT attribute_name, attribute_list FROM #__vm_product_attribute_sku WHERE product_id = '".$record->main_product_id."'");
								$attr_records = $db->loadObjectList();
								if (count($attr_records) >  0) {
									$has_attributes = true;
									/* Get the last array key */
									$lastkey = end(array_keys($attr_records));
									foreach ($attr_records as $attr_key => $attr_record) {
										$attributes .= $attr_record->attribute_name. "::". $attr_record->attribute_list;
										if ($lastkey != $attr_key) $attributes .= "|";
									}
								}
							}
							else $attributes = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField(trim($attributes), $fieldname, $field->column_header);
							break;
						case 'attribute_values':
							if( $record->product_parent_id != 0 || strtolower($record->attribute_name) == "download") {
								$attribute_values = $export_sku = "";
								$db->setQuery( "SELECT attribute_name, attribute_value FROM #__vm_product_attribute WHERE product_id = '".$record->main_product_id."'" );
								$attr_records = $db->loadObjectList();
								if (count($attr_records) >  0) {
									/* Get the last array key */
									$lastkey = end(array_keys($attr_records));
									foreach ($attr_records as $attr_key => $attr_record) {
										$attribute_values .= $record->attribute_name."::". $record->attribute_value;
										if ($lastkey != $attr_key) $attribute_values .= "|";
									}
								}
							}
							else $attribute_values = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField(trim($attribute_values), $fieldname, $field->column_header);
							break;
						case 'product_parent_sku':
							$db->setQuery( "SELECT product_sku FROM #__vm_product WHERE product_id='".$record->product_parent_id."'" );
							$contents .= $this->_exportmodel->AddExportField($db->loadResult(), $fieldname, $field->column_header);
							break;
						case 'product_discount_date_start':
						case 'product_discount_date_end':
						case 'product_available_date':
							$$fieldname = trim($record->$fieldname);
							if (strlen($$fieldname) == 0 || $$fieldname == 0) {
								/* Check if we have a default value */
								if (strlen(trim($field->default_value)) > 0) {
									$$fieldname = $field->default_value;
								}
								else $$fieldname = '';
							}
							else {
								$$fieldname = date($template->export_date_format, $$fieldname);
							}
							$contents .= $this->_exportmodel->AddExportField($$fieldname, $fieldname, $field->column_header);
							break;
						case 'related_products':
							$related_products = "";
							$db_related = JFactory::getDBO();
							$db_related->setQuery("SELECT related_products FROM #__vm_product_relations WHERE product_id='".$record->main_product_id."'" );
							$products = explode("|", $db_related->loadResult());
							$q = "SELECT product_sku FROM #__vm_product WHERE product_id in (";
							foreach ($products as $id => $productid) {
								$q .= $db_related->Quote($productid).',';
							}
							$q = substr($q, 0 , -1).")";
							$db_related->setQuery($q);
							$related_records = $db_related->loadObjectList();
							$lastkey = end(array_keys($related_records));
							foreach ($related_records as $related_key => $related_record) {
								$related_products .= $related_record->product_sku;
								if ($lastkey != $related_key) $related_products .= "|";
							}
							if (strlen(trim($related_products)) == 0) $related_products = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($related_products, $fieldname, $field->column_header);
							break;
						case 'product_discount':
							$product_discount = trim($record->product_discount);
							if ($record->product_discount_is_percent) $product_discount .= '%';
							else $product_discount = sprintf("%01.2f", $product_discount);
							if (strlen(trim($product_discount)) == 0) $product_discount = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($product_discount, $fieldname, $field->column_header);
							break;
						case 'product_price':
							$product_price = $this->ConvertPrice($record->product_price, $record->product_currency);
							
							$product_price =  sprintf("%01.2f", $this->_exportmodel->ProductPrice($product_price));
							if (strlen(trim($product_price)) == 0) $product_price = $field->default_value;
							if ($template->add_currency_to_price) $product_price = $record->product_currency.' '.$product_price;
							$contents .= $this->_exportmodel->AddExportField($product_price, $fieldname, $field->column_header);
							break;
						case 'product_url':
							/* Check if there is already a product URL */
							if (strlen(trim($record->product_url)) == 0) {
								/* There is no product URL, create it */
								/* Get the flypage */
								$flypage = $this->_exportmodel->GetFlypage($record->main_product_id);
								
								/* Get the category id */
								/* Check to see if we have a child product */
								if (isset($record->product_parent_id) && $record->product_parent_id > 0) $category_id = $this->_exportmodel->GetCategoryId($record->product_parent_id);
								else $category_id = $this->_exportmodel->GetCategoryId($record->main_product_id);
								
								if ($category_id > 0) {
									/* Let's create a SEF URL */
									$myURL = 'index.php?option=com_virtuemart&Itemid='.$vmitemid.'&page=shop.product_details&flypage='.$flypage.'&product_id='.$record->main_product_id.'&category_id='.$category_id;
									$product_url = JURI::root().$this->getSiteRoute($myURL);
								}
								else $product_url = "";
							}
							/* There is a product URL, use it */
							else $product_url = $record->product_url;
							
							/* Add the suffix */
							$product_url .= $template->producturl_suffix;
							
							/* Check for https, replace with http. https has unnecessary overhead */
							if (substr($product_url, 0, 5) == 'https') $product_url = 'http'.substr($product_url, 5);
							$contents .= $this->_exportmodel->AddExportField($product_url, $fieldname, $field->column_header, true);
							break;
						case 'product_full_image':
							$product_full_image = str_replace($template->file_location_product_images, '', $record->product_full_image);
							$contents .= $this->_exportmodel->AddExportField($product_full_image, $fieldname, $field->column_header);
							break;
						case 'product_thumb_image':
							$product_thumb_image = str_replace($template->file_location_product_images, '', $record->product_thumb_image);
							$contents .= $this->_exportmodel->AddExportField($product_thumb_image, $fieldname, $field->column_header);
							break;
						case 'picture_url':
							/* Check if there is already a product full image */
							if (strlen(trim($record->product_full_image)) > 0) {
								/* Create picture url */
								if (substr($record->product_full_image, 0, 4) == 'http') $picture_url = $record->product_full_image; 
								else $picture_url = JURI::root().'components/com_virtuemart/shop_image/product/'.$record->product_full_image;
							}
							/* There is no product full image, use default value */
							else $picture_url = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($picture_url, $fieldname, $field->column_header);
							break;
						case 'price_with_tax':
							$price_with_tax = sprintf("%01.2f", $this->_exportmodel->ProductPrice(($record->product_price/100)*(100+($record->tax_rate*100))));
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($price_with_tax)) == 0) $price_with_tax = $field->default_value;
							if ($template->add_currency_to_price) $price_with_tax = $record->product_currency.' '.$price_with_tax;
							$contents .= $this->_exportmodel->AddExportField($price_with_tax, $fieldname, $field->column_header);
							break;
						case 'price_with_discount':
							/* First include the tax */
							$price_with_discount = sprintf("%01.2f", ($record->product_price/100)*(100+($record->tax_rate*100)));
							/**
							 * Apply the discount
							 * 0 = value
							 * 1 = percentage
							 */
							switch($record->product_discount_is_percent) { 
								case 0: $price_with_discount -= trim($record->product_discount); break; 
								case 1: $price_with_discount *= (100 - trim($record->product_discount))/100; break; 
							}
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($price_with_discount)) == 0) $price_with_discount = $field->default_value;
							$price_with_discount = sprintf("%01.2f", $price_with_discount);
							if ($template->add_currency_to_price) $price_with_discount = $record->product_currency.' '.$price_with_discount;
							$contents .= $this->_exportmodel->AddExportField($price_with_discount, $fieldname, $field->column_header);
							break;
						case 'product_packaging':
							$product_packaging = $record->product_packaging & 0xFFFF;
							$contents .= $this->_exportmodel->AddExportField($product_packaging, $fieldname, $field->column_header);
							break;
						case 'product_box':
							$product_box = $record->product_packaging>>16&0xFFFF;
							$contents .= $this->_exportmodel->AddExportField($product_box, $fieldname, $field->column_header);
							break;
						case 'product_name':
							$fieldvalue = $record->$fieldname;
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header, true);
							break;
						case 'product_s_desc':
							$fieldvalue = $record->$fieldname;
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header, true);
							break;
						case 'product_desc':
							$fieldvalue = $record->$fieldname;
							/* Check if the field is empty */
							if (strlen(trim($fieldvalue)) == 0) {
								/* Check if we are doing a Google Base export */
								if (isset($record->product_s_desc) && $template->export_type == 'xml' && $template->export_site == 'froogle') {
									/* Check if the short description has data */
									if (strlen(trim($record->product_s_desc)) == 0) {
										/* Let's use the product name */
										$fieldvalue = $record->product_name;
									}
									else $fieldvalue = $record->product_s_desc;
								}
								else {
									/* Check if we have any content otherwise use the default value */
									$fieldvalue = $field->default_value;
								}
								
							}
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header, true);
							break;
						case 'product_full_image':
							$fieldvalue = $record->$fieldname;
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header, true);
							break;
						case 'product_thumb_image':
							$fieldvalue = $record->$fieldname;
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header, true);
							break;
						case 'product_id':
							$fieldvalue = $record->main_product_id;
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header, true);
							break;
						case 'product_tax':
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($record->tax_rate)) == 0) $product_tax = $field->default_value;
							else $product_tax = sprintf("%01.2f", ($record->tax_rate*100));
							$contents .= $this->_exportmodel->AddExportField($product_tax, $fieldname, $field->column_header);
							break;
						case 'mdate':
						case 'cdate':
							if (isset($record->$fieldname)) $fieldvalue = date($template->export_date_format, $record->$fieldname);
							else $fieldvalue = '';
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
							break;
						default:
							if (isset($record->$fieldname)) $fieldvalue = $record->$fieldname;
							else $fieldvalue = '';
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
							break;
					}
				}
				if ($template->export_type == 'xml') {
					$contents .= $xmlclass->NodeEnd();
				}
				else if (substr($contents, -1) == $template->field_delimiter) {
					$contents = substr($contents, 0 , -1);
				}
				$contents .= "\r\n";
				
				/* Output the contents */
				CsvimprovedModelExportfile::writeOutput($contents);
				
				/* Clean up */
				unset($contents);
			}
		}
		/* There are no records, write SQL query to log */
		else if ($db->getErrorNum() > 0) {
			$contents = JText::_('ERROR_RETRIEVING_DATA');
			CsvimprovedModelExportfile::writeOutput($contents);
			$csvilog->AddStats('incorrect', $db->getErrorMsg());
		}
		else {
			$contents = JText::_('NO_DATA_FOUND');
			/* Output the contents */
			CsvimprovedModelExportfile::writeOutput($contents);
		}
	}
	
	/**
	 * Convert prices to the new currency
	 */
	private function ConvertPrice($product_price, $product_currency) {
		/* See if we need to convert the price */
		if (JRequest::getVar('targetcurrency') != '') {
			$db_exrate = JFactory::getDBO();
			$q = "SELECT currency_code, currency_rate FROM #__csvi_currency WHERE currency_code IN (".$db_exrate->Quote($product_currency).", ".$db_exrate->Quote(JRequest::getVar('targetcurrency', 'EUR')).")";
			$db_exrate->setQuery($q);
			$rates = $db_exrate->loadObjectList('currency_code');
			
			/* Convert to Euro's */
			$europrice = $product_price / $rates[strtoupper($product_currency)]->currency_rate;
			
			/* Convert to destination currency */
			return $europrice * $rates[strtoupper(JRequest::getVar('targetcurrency', 'EUR'))]->currency_rate;
		}
		else return $product_price;
	}
	
	/*
	 * Create a SEF URL
	 */
	private function getSiteRoute($url) {
		if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sh404sef'.DS.'sh404sef.class.php')) {
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sh404sef'.DS.'sh404sef.class.php');
			
			$sefConfig = shRouter::shGetConfig();
			if (!$sefConfig->Enabled) return $url;
			$sefConfig->shSecEnableSecurity = 0;
			
			require_once(JPATH_ROOT.DS.'components'.DS.'com_sh404sef'.DS.'shCache.php');
			require_once(JPATH_ROOT.DS.'components'.DS.'com_sh404sef'.DS.'shSec.php');
			
			$shRouter = new shRouter();
			
			include_once(JPATH_ROOT.DS.'components'.DS.'com_sh404sef'.DS.'shInit.php');
			
			$uri = $shRouter->build($url);
			$parsed_url = $uri->toString();
			$adminpos = strpos($parsed_url,'/administrator/');
			if ($adminpos === false) {
			} else {
			  $parsed_url = substr($parsed_url,$adminpos+15);
			}
			return $parsed_url;
		}
		else return $url;
	}
}
?>