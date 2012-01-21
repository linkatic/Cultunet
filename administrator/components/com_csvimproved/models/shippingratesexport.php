<?php
/**
 * Shipping rates export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: manufacturerexport.php 869 2009-04-14 14:00:35Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for shipping rates
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelShippingratesExport extends JModel {
	
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
		
		/* Build something fancy to only get the fieldnames the user wants */
		$userfields = array();
		foreach ($export_fields as $column_id => $field) {
			switch ($field->field_name) {
				/* Man made fields, do not export them */
				case 'custom':
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
		$q .= " FROM #__vm_shipping_rate 
				LEFT JOIN #__vm_currency
				ON #__vm_shipping_rate.shipping_rate_currency_id = #__vm_currency.currency_id";
		
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