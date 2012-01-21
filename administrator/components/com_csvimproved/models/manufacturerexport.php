<?php
/**
 * Manufacturer details export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: manufacturerexport.php 946 2009-08-03 09:47:37Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for product exports
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelManufacturerExport extends JModel {
	
	/* Private variables */
	private $_exportmodel = null;
	
	/** 
	 * Order export
	 *
	 * Exports manufacturer details
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
		
		/* Match the fieldnames to the column headers */
		 $fieldnames = array();
		 $fieldnames['manufacturer_name'] = 'mf_name';
		 $fieldnames['manufacturer_email'] = 'mf_email';
		 $fieldnames['manufacturer_desc'] = 'mf_desc';
		 $fieldnames['manufacturer_category_name'] = 'mf_category_name';
		 $fieldnames['manufacturer_category_id'] = 'mf_category_id';
		 $fieldnames['manufacturer_url'] = 'mf_url';
		 $fieldnames['manufacturer_id'] = 'manufacturer_id';
		 
		 /* Get the template settings */
		 $q = "SELECT * 
		 	FROM #__vm_manufacturer m, #__vm_manufacturer_category c
			WHERE m.mf_category_id = c.mf_category_id ";
		
		/* Check if we need to group the orders together */
		$groupby = JRequest::getVar('groupby', false);
		if ($groupby) {
			$q .= " GROUP BY ";
			foreach ($export_fields as $column_id => $field) {
				switch ($field->field_name) {
					case 'manufacturer_category_id':
						$q .= "c.mf_category_id, ";
						break;
					case 'manufacturer_desc':
						$q .= "mf_desc, ";
						break;
					case 'manufacturer_email':
						$q .= "mf_email, ";
						break;
					case 'manufacturer_name':
						$q .= "mf_name, ";
						break;
					case 'manufacturer_url':
						$q .= "mf_url, ";
						break;
					default:
						$q .= $field->field_name.", ";
						break;
				}
			}
			$q = substr($q, 0, -2);
		}
		
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
					$fieldvalue = $record->$fieldnames[$field->field_name];
					/* Check if we have any content otherwise use the default value */
					if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
					$contents .= $this->_exportmodel->AddExportField($fieldvalue, $field->field_name, $field->column_header);
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