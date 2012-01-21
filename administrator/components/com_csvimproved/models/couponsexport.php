<?php
/**
 * Coupons export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: userinfoexport.php 862 2009-04-06 12:35:22Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for user info exports
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelCouponsExport extends JModel {
	
	/* Private variables */
	private $_exportmodel = null;
	
	/** 
	 * Coupons export
	 *
	 * Exports coupons details to either csv or xml format
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
		
		/* Execute the query */
		$q = "SELECT * FROM #__vm_coupons";
		
		/* Check if we need to group the orders together */
		$groupby = JRequest::getVar('groupby', false);
		if ($groupby) {
			$q .= " GROUP BY ";
			foreach ($export_fields as $column_id => $field) {
				switch ($field->field_name) {
					case 'custom':
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
					$fieldname = $field->field_name;
					switch ($fieldname) {
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
	}
}
?>