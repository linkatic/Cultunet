<?php
/**
 * Product type names export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: orderexport.php 665 2009-01-02 07:40:08Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for product type names exports
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelProductTypenamesExport extends JModel {
	
	/* Private variables */
	private $_exportmodel = null;
	
	/** 
	 * Product type names export
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
		$tbl_prefix =  $config->getValue('config.dbprefix');
		
		/* Get the general export functions */
		$this->_exportmodel = new CsvimprovedModelexportfile();
		
		/* Get only the product type names */
		$producttypenames = JRequest::getVar('producttypenames', false);
		$tables = array();
		foreach ($producttypenames as $key => $tableid) {
			if ($tableid) {
				$tables[] = $tbl_prefix.'vm_product_type_'.$tableid;
			}
			else {
				/* Execute the query */
				$q = "SHOW TABLES LIKE '".$tbl_prefix."vm_product_type_%'";
				
				$db->setQuery($q);
				JRequest::setVar('logcount', array('export' => $db->getAffectedRows()));
				if ($db->getErrorNum() > 0) $csvilog->AddStats('incorrect', $db->getErrorMsg(), true);
				$tables = $db->loadResultArray();
				break;
			}
		}
		foreach ($tables as $key => $tablename) {
			if (stristr('0123456789', substr($tablename, -1))) {
				$contents = '';
				/* See if the user wants column headers */
				if ($template->include_column_headers && $template->export_type != 'xml') {
					foreach ($export_fields as $column_id => $field) {
						$contents .= $template->text_enclosure.$field->column_header.$template->text_enclosure.$template->field_delimiter;
					}
					$q = "DESCRIBE ".$db->nameQuote($tablename);
					$db->setQuery($q);
					$table_headers = $db->loadResultArray();
					foreach ($table_headers as $thkey => $header) {
						if ($header != 'product_id') {
							$contents .= $template->text_enclosure.$header.$template->text_enclosure.$template->field_delimiter;
						}
					}
					$contents = substr($contents, 0, -1);
					$contents .= "\r\n";
					
					/* Output the contents */
					CsvimprovedModelExportfile::writeOutput($contents);
					
					/* Clean up */
					unset($contents);
				}
				
				/* Get the product type name */
				$q = "SELECT product_type_name, product_type_id
					FROM #__vm_product_type 
					WHERE product_type_id = ".str_replace($tbl_prefix.'vm_product_type_', '', $tablename);
				$db->setQuery($q);
				$product_type_info = $db->loadObject();
				
				/* Add the contents */
				$q = "SELECT #__vm_product.product_sku, ".$tablename.".* 
					FROM ".$tablename."
					LEFT JOIN #__vm_product
					ON ".$tablename.".product_id = #__vm_product.product_id";
				$csvidb->setQuery($q);
				if ($csvidb->getErrorNum() == 0) {
					while ($record = $csvidb->getRow()) {
						$contents = '';
						if ($template->export_type == 'xml') $contents .= $xmlclass->NodeStart();
						/* Export the fields set in the template */
						foreach ($export_fields as $column_id => $field) {
							$fieldname = $field->field_name;
							switch($fieldname) {
								case 'product_type_id':
									$contents .= $this->_exportmodel->AddExportField($product_type_info->product_type_id, $fieldname, $fieldname);
									break;
								case 'product_type_name':
									$contents .= $this->_exportmodel->AddExportField($product_type_info->product_type_name, $fieldname, $fieldname);
									break;
								case 'product_sku':
									$contents .= $this->_exportmodel->AddExportField($record->product_sku, $fieldname, $fieldname);
									break;
								case 'product_id':
									$contents .= $this->_exportmodel->AddExportField($record->product_id, $fieldname, $fieldname);
									break;
							}
						}
						
						/* Export the product types */
						foreach ($record as $fieldname => $value) {
							if ($fieldname != 'product_sku'
								&& $fieldname != 'product_id') {
								$contents .= $this->_exportmodel->AddExportField($value, $fieldname, $fieldname);
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
					} // end database row
				} // end database lookup loop
			} // end tablename check
		} // end foreach
	}
}
?>