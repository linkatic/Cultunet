<?php
/**
 * Multiple prices export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: multiplepricesexport.php 946 2009-08-03 09:47:37Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for product exports
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelMultiplePricesExport extends JModel {
	
	/* Private variables */
	private $_exportmodel = null;
	
	/** 
	 * Multiple prices export
	 *
	 * Exports multiple prices
	 **/
	public function getStart() {
		
		/* Get some basic data */
		$db = JFactory::getDBO();
		$csvidb = new CsviDb();
		$csvilog = JRequest::getVar('csvilog');
		$template = JRequest::getVar('template');
		$xmlclass = JRequest::getVar('xmlclass');
		$export_fields = JRequest::getVar('exportfields');
		
		/* Get the general export functions */
		$this->_exportmodel = new CsvimprovedModelexportfile();
		
		/* Check if there are any selectors */
		$selectors = array();
		if ($template->shopper_group_id > 0) {
			$selectors[] = '#__vm_product_price.shopper_group_id = '.$template->shopper_group_id;
		}
		
		/* Execute the query */
		$q = "SELECT #__vm_product_price.*, p.product_sku, s.shopper_group_name 
			FROM #__vm_product_price
			LEFT JOIN #__vm_product p
			ON #__vm_product_price.product_id = p.product_id
			LEFT JOIN #__vm_shopper_group s
			ON #__vm_product_price.shopper_group_id = s.shopper_group_id";
			
		/* Check if we need to attach any selectors to the query */
		if (count($selectors) > 0 ) $q .= ' WHERE '.implode(' AND ', $selectors)."\n";
		
		/* Check if we need to group the orders together */
		$groupby = JRequest::getVar('groupby', false);
		if ($groupby) {
			$q .= " GROUP BY ";
			foreach ($export_fields as $column_id => $field) {
				$q .= $field->field_name.", ";
			}
			$q = substr($q, 0, -2);
		}
		
		/* Order on product SKU so the same products are listed together */
		$q .= ' ORDER BY product_sku ';
		
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
						case 'product_price':
							$this->product_price = $record->product_price;
							$product_price =  sprintf("%01.2f", $this->_exportmodel->ProductPrice($record->product_price));
							if (strlen(trim($this->product_price)) == 0) $this->product_price = $field->default_value;
							$contents .= $this->_exportmodel->AddExportField($this->product_price, $fieldname, $field->column_header);
							break;
						default:
							$fieldvalue = $record->$fieldname;
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
	}
}
?>