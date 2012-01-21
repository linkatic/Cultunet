<?php
/**
 * Available fields model
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: availablefields.php 909 2009-05-22 17:58:50Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Available fields Model
 *
 * @package CSVImproved
 */
class CsvimprovedModelAvailablefields extends JModel {
	
	/**
	 * Fill the available fields table
	 */
	public function getFillAvailableFields() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		/* Empty the available fields first */
		$q = "TRUNCATE TABLE `#__csvi_available_fields`";
		$db->setQuery($q);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Available fields table emptied'));
		else $csvilog->AddStats('error', JText::_('Available fields table could not be emptied'));
		$tablenames = array('csvi_templates',
							'csvi_template_fields',
							'vm_category',
							'vm_category_xref',
							'vm_country',
							'vm_coupons',
							'vm_currency',
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
							'vm_shipping_rate',
							'vm_shopper_group',
							'vm_shopper_vendor_xref',
							'vm_tax_rate',
							'vm_user_info',
							'vm_vendor',
							'vm_vendor_category',
							'users');
		foreach ($tablenames as $key => $tablename) {
			$fields = $this->DbFields($tablename);
			$table = $this->getTable('csvi_available_fields');
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
					$data['csvi_name'] = $csviname;
					$data['vm_name'] = $name;
					$data['vm_table'] = $tablename;
					$table->bind($data);
					$table->store();
				}
				$table->reset();
					
			}
			$csvilog->AddStats('added', JText::_('Available fields have been added for:').' '.$tablename);
		}
		/* Add some custom fields */
		jimport('joomla.filesystem.file');
		if (JFile::exists(JPATH_COMPONENT.DS.'helpers'.DS.'availablefields_extra.sql')) {
			$q = JFile::read(JPATH_COMPONENT.DS.'helpers'.DS.'availablefields_extra.sql');
			$db->setQuery($q);
			if ($db->query()) $csvilog->AddStats('added', JText::_('Custom available fields have been added'));
			else $csvilog->AddStats('error', JText::_('Custom available fields have not been added'));
		}
		else $csvilog->AddStats('error', JText::_('AVAILABLEFIELDS_EXTRA_NOT_FOUND'));
		return;
	}
	
	/**
	 * Creates an array of custom database fields the user can use for import/export
	 */
	public function DbFields($table) {
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
	
	/**
	 * Retrieve a list of available fields
	 *
	 * @param string contains the list of tables to filter on
	 */
	public function getShowAvailableFields($pagination, $filter = false) {
		$db = JFactory::getDBO();
		$q = "SELECT * FROM ".$db->nameQuote('#__csvi_available_fields');
		if ($filter) {
			$table = $this->GetAvailableFields($filter);
			$tables = $db->nameQuote('vm_table')." = '" . implode( "' OR vm_table ='", $table )."'";
			$q .= " WHERE ".$tables;
		}
		$q .= " ORDER BY csvi_name";
		$db->setQuery($q, $pagination->limitstart, $pagination->limit);
		return $db->loadObjectList();
	}
	
	/**
	 * Retrieve a list of available fields from a certain table
	 *
	 * @todo Convert to object
	 */
	private function getAvailableDbFields($table) {
		$db = JFactory::getDBO();
		$tables = $db->nameQuote('vm_table')." = '" . implode( "' OR vm_table ='", $table )."'";
		$q = "SELECT csvi_name FROM ".$db->nameQuote('#__csvi_available_fields')." WHERE ".$tables." GROUP BY csvi_name";
		$db->setQuery($q);
		return $db->loadResultArray();
	}
	
	/**
	 * Get the fields belonging to a certain operation type
	 *
	 * @param string $type contains the name of the supported fields to activate
	 */
	function GetAvailableFields($type) {
		switch (strtolower($type)) {
			/* Imports */
			case 'productimport':
			case 'productexport':
				$tables = array('vm_product', 'vm_product_price', 'vm_product_category_xref',
								'vm_product_discount', 'vm_product_attribute',
								'vm_product_attribute_sku', 'vm_product_relations',
								'vm_manufacturer', 'vm_product_mf_xref',
								'vm_tax_rate', 'vm_shopper_group');
				break;
			case 'multiplepricesimport':
			case 'multiplepricesexport':
				$tables = array('vm_product_price');
				break;
			case 'couponsimport':
			case 'couponsexport':
				$tables = array('vm_coupons');
				break;
			case 'productfilesimport':
			case 'productfilesexport':
				$tables = array('vm_product_files');
				break;
			case 'producttypeimport':
			case 'producttypeexport':
				$tables = array('vm_product_type');
				break;
			case 'producttypeparametersimport':
			case 'producttypeparametersexport':
				$tables = array('vm_product_type_parameter');
				break;
			case 'producttypenamesimport':
			case 'producttypenamesexport':
				/**
				 * An array of all fields that are available for a product type name import
				 *
				 * The list only contains 2 fields but more fieldnames need to be added
				 * to import data. These fieldnames are the product type parameter names.
				 * This allows for filling the parameters with data.
				 */
				$tables = array('vm_product_product_type_xref');
				break;
			case 'templateimport':
			case 'templateexport':
				$tables = array('csvi_templates');
				break;
			case 'templatefieldsimport':
			case 'templatefieldsexport':
				$tables = array('csvi_template_fields');
				break;
			case 'manufacturerimport':
			case 'manufacturerexport':
				$tables = array('vm_manufacturer');
				break;
			case 'manufacturercategoryimport':
				$tables = array('vm_manufacturer_category');
				break;
			case 'categorydetailsimport':
			case 'categorydetailsexport':
				$tables = array('vm_category');
				break;
			case 'userinfoimport':
			case 'userinfoexport':
				$tables = array('vm_user_info', 'vm_shopper_group', 'vm_shopper_vendor_xref', 'users');
				break;
			case 'orderexport':
				$tables = array('vm_orders', 'vm_order_item', 'vm_order_user_info',
								'vm_order_payment', 'vm_order_status', 'vm_payment_method',
								'vm_product_mf_xref', 'vm_manufacturer', 'users', 'vm_country');
				break;
			case 'shippingratesimport':
			case 'shippingratesexport':
				$tables = array('vm_shipping_rate', 'vm_currency');
				break;
			
		}
		/* Add the template type fields */
		$tables[] = strtolower($type);
		/* Check if we are viewing available fields */
		if (JRequest::getWord('searchtemplatetype', false)) return $tables;
		else return $this->getAvailableDbFields($tables);
	}
	
	/**
	 * Get the total available fields
	 */
	public function getTotalAvailableFields() {
		$db = JFactory::getDBO();
		$q = "SELECT COUNT(id) FROM #__csvi_available_fields";
		$filter = JRequest::getWord('searchtemplatetype', false);
		if ($filter) {
			$table = $this->GetAvailableFields($filter);
			$tables = $db->nameQuote('vm_table')." = '" . implode( "' OR vm_table ='", $table )."'";
			$q .= " WHERE ".$tables;
		}
		$db->setQuery($q);
		return $db->loadResult();
	}
}
?>