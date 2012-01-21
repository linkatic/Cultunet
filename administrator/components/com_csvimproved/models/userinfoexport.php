<?php
/**
 * User info export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: userinfoexport.php 946 2009-08-03 09:47:37Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for user info exports
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelUserInfoExport extends JModel {
	
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
		
		/* Get the general export functions */
		$this->_exportmodel = new CsvimprovedModelexportfile();
		
		/* Build something fancy to only get the fieldnames the user wants */
		$userfields = array();
		foreach ($export_fields as $column_id => $field) {
			switch ($field->field_name) {
				case 'user_id':
					$userfields[] = '#__vm_user_info.user_id';
					break;
				case 'shopper_group_id':
					$userfields[] = '#__vm_shopper_group.shopper_group_id';
					break;
				case 'vendor_id':
					$userfields[] = '#__vm_shopper_vendor_xref.vendor_id';
					break;
				case 'full_name':
					$userfields[] = '#__vm_user_info.first_name';
					$userfields[] = '#__vm_user_info.middle_name';
					$userfields[] = '#__vm_user_info.last_name';
					break;
				default:
					$userfields[] = $field->field_name;
					break;
			}
		}
		
		/* Execute the query */
		$q = "SELECT ".implode(",", $userfields);
		$q .= " FROM #__vm_user_info
			LEFT JOIN #__vm_shopper_vendor_xref 
			ON #__vm_user_info.user_id = #__vm_shopper_vendor_xref.user_id
			LEFT JOIN #__vm_shopper_group
			ON #__vm_shopper_vendor_xref.shopper_group_id = #__vm_shopper_group.shopper_group_id
			LEFT JOIN #__vm_vendor
			ON #__vm_shopper_vendor_xref.vendor_id = #__vm_vendor.vendor_id
			LEFT JOIN #__users
			ON #__vm_user_info.user_id = #__users.id";
		
		/* Check if there are any selectors */
		$selectors = array();
		
		/* Check if we need to attach any selectors to the query */
		if (count($selectors) > 0 ) $q .= ' WHERE '.implode(' AND ', $selectors)."\n";
		
		/* Check if there are any selectors */
		$selectors = array();
		/* Filter by vendors */
		$vendors = JRequest::getVar('vendors', false);
		if ($vendors && $vendors[0] != '') {
			$selectors[] = '#__vm_shopper_vendor_xref.vendor_id IN (\''.implode("','", $vendors).'\')';
		}
		
		/* Filter by permissions */
		$permissions = JRequest::getVar('permissions', false);
		if ($permissions && $permissions[0] != '') {
			$selectors[] = '#__vm_user_info.perms IN (\''.implode("','", $permissions).'\')';
		}
		
		/* Filter by address type */
		$address = JRequest::getVar('userinfo_address', false);
		if ($address) {
			$selectors[] = '#__vm_user_info.address_type = '.$db->Quote(strtoupper($address));
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
					case 'full_name':
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
								$$fieldname = date("d/m/Y H:i:s", $$fieldname);
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
						case 'vendor_name':
							$fieldvalue = stripslashes($record->$fieldname);
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