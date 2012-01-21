<?php
/**
 * Product export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: orderexport.php 947 2009-08-05 08:00:22Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for product exports
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelOrderExport extends JModel {
	
	/* Private variables */
	private $_exportmodel = null;
	
	/** 
	 * Order export
	 *
	 * Exports manufacturer details to either csv or xml format
	 **/
	public function getStart() {
		
		/* Get some basic data */
		$db = JFactory::getDBO();
		$csvidb = new CsviDb();
		$csvilog = JRequest::getVar('csvilog');
		$template = JRequest::getVar('template');
		$xmlclass = JRequest::getVar('xmlclass');
		$export_fields = JRequest::getVar('exportfields');
		$config = Jfactory::getConfig();
		$secret =  md5($config->getValue('config.secret'));
		$address = strtoupper(JRequest::getVar('order_address', false));
		if ($address == 'BTST') $user_info_fields = CsvimprovedModelAvailablefields::DbFields('vm_order_user_info');
		
		/* Get the general export functions */
		$this->_exportmodel = new CsvimprovedModelexportfile();
		
		/* Build something fancy to only get the fieldnames the user wants */
		$userfields = array();
		foreach ($export_fields as $column_id => $field) {
			switch ($field->field_name) {
				case 'order_id':
					$userfields[] = '#__vm_orders.order_id';
					break;
				case 'user_id':
					$userfields[] = '#__vm_orders.user_id';
					break;
				case 'order_date':
					$userfields[] = '#__vm_orders.cdate AS order_date';
					break;
				case 'order_modified_date':
					$userfields[] = '#__vm_orders.mdate AS order_modified_date';
					break;
				case 'product_sku':
					$userfields[] = 'order_item_sku AS product_sku';
					break;
				case 'product_name':
					$userfields[] = 'order_item_name AS product_name';
					break;
				case 'product_item_price':
					$userfields[] = 'product_item_price AS product_price';
					break;
				case 'manufacturer_name':
					$userfields[] = 'mf_name AS manufacturer_name';
					break;
				case 'product_id':
					$userfields[] = '#__vm_order_item.product_id';
					break;
				case 'order_status':
					$userfields[] = '#__vm_orders.order_status';
					break;
				case 'product_price':
					$userfields[] = '#__vm_order_item.product_item_price';
					break;
				case 'order_payment_number':
					$userfields[] = 'AES_DECRYPT(order_payment_number,'.$db->Quote($secret).') AS order_payment_number';
					break;
				case 'cdate':
					$userfields[] = '#__vm_orders.cdate';
					break;
				case 'mdate':
					$userfields[] = '#__vm_orders.mdate';
					break;
				case 'full_name':
					if (!$this->_exportmodel->searchExportFields('first_name', $export_fields)) $userfields[] = 'user_info1.first_name';
					if (!$this->_exportmodel->searchExportFields('middle_name', $export_fields)) $userfields[] = 'user_info1.middle_name';
					if (!$this->_exportmodel->searchExportFields('last_name', $export_fields)) $userfields[] = 'user_info1.last_name';
					break;
				case 'product_price_total':
					$userfields[] = 'product_item_price*product_quantity AS product_price_total';
					break;
				case 'discount_percentage':
					$userfields[] = 'order_discount/order_total AS discount_percentage';
					break;
				case 'custom':
				case 'total_order_items':
					/* These are man made fields, do not try to get them from the database */
					break;
				default:
					if ($address == 'BTST' && array_key_exists($field->field_name, $user_info_fields)) {
						$userfields[] = 'COALESCE(user_info2.'.$field->field_name.', user_info1.'.$field->field_name.') AS '.$field->field_name;
					}
					else $userfields[] = $field->field_name;
					break;
			}
		}
		
		/* Construct the query */
		$q = "SELECT ".implode(",", $userfields);
		$q .= " FROM #__vm_orders
			LEFT JOIN #__vm_order_item
			ON #__vm_orders.order_id = #__vm_order_item.order_id
			LEFT JOIN #__vm_order_user_info AS user_info1
			ON #__vm_orders.order_id = user_info1.order_id";
		if ($address == 'BTST') $q .= " LEFT JOIN #__vm_order_user_info AS user_info2
			ON #__vm_orders.order_id = user_info2.order_id
			AND user_info2.address_type = 'ST'";
		$q .= " LEFT JOIN #__vm_order_payment
			ON #__vm_orders.order_id = #__vm_order_payment.order_id
			LEFT JOIN #__vm_payment_method
			ON #__vm_order_payment.payment_method_id = #__vm_payment_method.payment_method_id
			LEFT JOIN #__vm_order_status
			ON #__vm_orders.order_status = #__vm_order_status.order_status_code
			LEFT JOIN  #__vm_product_mf_xref
			ON #__vm_order_item.product_id = #__vm_product_mf_xref.product_id
			LEFT JOIN #__vm_manufacturer
			ON #__vm_product_mf_xref.manufacturer_id = #__vm_manufacturer.manufacturer_id
			LEFT JOIN #__users
			ON #__users.id = user_info1.user_id
			LEFT JOIN #__vm_country
			ON #__vm_country.country_3_code = user_info1.country
			";
			
			
		/* Check if there are any selectors */
		$selectors = array();
		/* Filter by order number start */
		if (JRequest::getInt('ordernostart') > 0) {                
			$selectors[] = '#__vm_orders.order_id >= '.JRequest::getInt('ordernostart');
		}
		
		/* Filter by order number end */
		if (JRequest::getInt('ordernoend') > 0) {                
			$selectors[] = '#__vm_orders.order_id <= '.JRequest::getInt('ordernoend');
		}
		
		/* Filter by order date start */
		$orderdate = JFactory::getDate(JRequest::getVar('orderdatestart'));
		if (JRequest::getVar('orderdatestart', false)) {
			$selectors[] = '#__vm_orders.cdate >= '.$db->Quote($orderdate->toUnix());
		}
		
		/* Filter by order date end */
		$orderdate = JFactory::getDate(JRequest::getVar('orderdateend'));
		if (JRequest::getVar('orderdateend', false)) {
			$selectors[] = '#__vm_orders.cdate <= '.$db->Quote($orderdate->toUnix());
		}
		
		/* Filter by order status */
		$orderstatus = JRequest::getVar('orderstatus', false);
		if ($orderstatus && $orderstatus[0] != '') {
			$selectors[] = '#__vm_orders.order_status IN (\''.implode("','", $orderstatus).'\')';
		}
		
		/* Filter by order price start */
		$pricestart = JRequest::getVar('orderpricestart', false, 'default', 'float');
		if ($pricestart) {                
			$selectors[] = '#__vm_orders.order_total >= '.$pricestart;
		}
		
		/* Filter by order price end */
		$priceend = JRequest::getVar('orderpriceend', '', 'default', 'float');
		if ($priceend) {                
			$selectors[] = '#__vm_orders.order_total <= '.$priceend;
		}
		
		/* Filter by order user id */
		$orderuser = JRequest::getVar('orderuser', false);
		if ($orderuser && $orderuser[0] != '') {
			$selectors[] = '#__vm_orders.user_id IN (\''.implode("','", $orderuser).'\')';
		}
		
		/* Filter by order product */
		$orderproduct = JRequest::getVar('orderproduct', false);
		if ($orderproduct && $orderproduct[0] != '') {
			$selectors[] = '#__vm_order_item.order_item_sku IN (\''.implode("','", $orderproduct).'\')';
		}
		
		/* Filter by address type */
		if ($address) {
			switch (strtoupper($address)) {
				case 'BTST':
					$selectors[] = "user_info1.address_type = 'BT'";
					break;
				default:
					$selectors[] = 'user_info1.address_type = '.$db->Quote(strtoupper($address));
					break;
			}
		}
		
		/* Filter by order currency */
		$ordercurrency = JRequest::getVar('ordercurrency', false);
		if ($ordercurrency && $ordercurrency[0] != '') {
			$selectors[] = '#__vm_orders.order_currency IN (\''.implode("','", $ordercurrency).'\')';
		}
		
		/* Check if we need to attach any selectors to the query */
		if (count($selectors) > 0 ) $q .= ' WHERE '.implode(' AND ', $selectors)."\n";
		
		/* Check if we need to group the orders together */
		$groupby = JRequest::getVar('groupby', false);
		if ($groupby) {
			$q .= " GROUP BY ";
			foreach ($export_fields as $column_id => $field) {
				switch ($field->field_name) {
					case 'custom':
					case 'total_order_items':
					case 'discount_percentage':
					case 'product_price_total':
					case 'full_name':
						break;
					case 'product_price':
						$q .= "product_item_price, ";
						break;
					case 'payment_method_id':
						$q = "#__vm_order_payment.payment_method_id";
						break;
					default:
						if ($address == 'BTST' && array_key_exists($field->field_name, $user_info_fields)) $q .= 'user_info1.'.$field->field_name.", ";
						else $q .= $field->field_name.", ";
						break;
				}
			}
			$q = substr($q, 0, -2);
		}
		
		/* Set the ordering */
		$q .= " ORDER BY #__vm_orders.order_id";
		
		/* Add a limit if user wants us to */
		$q .= $this->_exportmodel->ExportLimit();
		
		$csvidb->setQuery($q);
		
		$logcount = $db->getAffectedRows();
		JRequest::setVar('logcount', array('export' => $logcount));
		if ($db->getErrorNum() > 0) $csvilog->AddStats('incorrect', $db->getErrorMsg(), true);
		if ($logcount > 0) {
			while ($record = $csvidb->getRow()) {
				$contents = '';
				if ($template->export_type == 'xml') $contents .= $xmlclass->NodeStart();
				foreach ($export_fields as $column_id => $field) {
					$fieldname = $field->field_name;
					switch ($fieldname) {
						case 'order_date':
						case 'order_modified_date':
						case 'order_payment_expire':
						case 'cdate':
						case 'mdate':
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
						case 'address_type':
							$fieldvalue = $record->$fieldname;
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							if ($fieldvalue == 'BT') $fieldvalue = JText::_('BILLING_ADDRESS');
							else if ($fieldvalue == 'ST') $fieldvalue = JText::_('SHIPPING_ADDRESS');
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
							break;
						case 'full_name':
							$fieldvalue = str_replace('  ', ' ', $record->first_name.' '.$record->middle_name.' '.$record->last_name);
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
							break;
						case 'order_id':
							$fieldvalue = $record->$fieldname;
							/* Let's see if the user wants us to get a total number of items */
							if ($this->_exportmodel->searchExportFields('total_order_items', $export_fields)) {
								/* Let's calculate total order items */
								$db = JFactory::getDBO();
								$q = "SELECT COUNT(order_id) AS totalitems
									FROM #__vm_order_item
									WHERE order_id = ".$fieldvalue;
								$db->setQuery($q);
								JRequest::setVar('total_order_items', $db->loadResult());
							}
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
							break;
						case 'total_order_items':
							$fieldvalue = JRequest::getVar('total_order_items', '');
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
							break;
						case 'custom':
							/* Has no database value, get the default value only */
							$fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
							break;
						case 'product_price':
							$fieldvalue = $record->product_item_price;
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
							break;
						default:
							$fieldvalue = $record->$fieldname;
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
							break;
					}
				}
				/* Clean the totalitems */
				JRequest::setVar('total_order_items', 0);
				
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
}
?>