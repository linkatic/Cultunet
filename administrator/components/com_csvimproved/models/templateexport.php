<?php
/**
 * Template export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: templateexport.php 946 2009-08-03 09:47:37Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for template exports
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelTemplateExport extends JModel {
	
	/* Private variables */
	private $_exportmodel = null;
	
	
	/** 
	 * Template export
	 *
	 * Exports template data
	 *
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
		
		/* Get the template settings from the database*/
		$q = 'SELECT t.*, s.shopper_group_name 
			FROM #__csvi_templates t
			LEFT JOIN #__vm_shopper_group s
			ON t.shopper_group_id = s.shopper_group_id';
		$templatelist = JRequest::getVar('exporttemplate');
		if (count($templatelist) > 0 && !empty($templatelist[0])) {
			$last = end(array_keys($templatelist));
			$q .= ' WHERE template_id IN (';
			foreach ($templatelist as $key => $value) {
			    $q .= $value;
			    if ($last != $key) $q .= ",";
			}
			$q .= ')';
		 }
		 
		/* Check if we need to group the orders together */
		$groupby = JRequest::getVar('groupby', false);
		if ($groupby) {
			$q .= " GROUP BY ";
			foreach ($export_fields as $column_id => $field) {
				$q .= $field->field_name.", ";
			}
			$q = substr($q, 0, -2);
		}
		
		/* Order templates by name */
		$q .= ' ORDER BY t.template_name ';
		 
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
					$fieldvalue = $record->$fieldname;
					/* Check if we have any content otherwise use the default value */
					if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->default_value;
					$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header);
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