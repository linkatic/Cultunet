<?php
/**
 * Category details export class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: categorydetailsexport.php 946 2009-08-03 09:47:37Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/**
 * Processor for product exports
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsvimprovedModelCategoryDetailsExport extends JModel {
	
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
		
		/* Get all categories */
		 $query  = "SELECT LOWER(category_name) AS category_name, category_child_id AS cid, category_parent_id AS pid
			  FROM #__vm_category, #__vm_category_xref 
			  WHERE #__vm_category.category_id = #__vm_category_xref.category_child_id ";
			  
		 /* Execute the query */
		 $db->setQuery($query);
		 $cats = $db->loadObjectList();
		 
		 $categories = array();
		 /* Group all categories together according to their level */
		 foreach ($cats as $key => $cat) {
		    $categories[$cat->pid][$cat->cid] = $cat->category_name;
		 }
	
		 /* Execute the query */
		 $q = "SELECT category_id,
				category_description,
				category_thumb_image,
				category_name,
				category_full_image,
				category_publish,
				category_browsepage,
				products_per_row AS category_products_per_row,
				category_flypage,
				list_order AS category_list_order
				FROM #__vm_category ";
	
		/* Check if we need to group the orders together */
		$groupby = JRequest::getVar('groupby', false);
		if ($groupby) {
			$q .= " GROUP BY ";
			foreach ($export_fields as $column_id => $field) {
				switch ($field->field_name) {
					case 'custom':
					case 'category_path':
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
						case 'category_path':
						   JRequest::setVar('catid', $record->category_id);
						   $fieldvalue = $this->_exportmodel->CreateCategoryPath();
						   $contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header, true);
						   break;
						case 'category_name':
							if (isset($record->$fieldname)) $fieldvalue = $record->$fieldname;
							else $fieldvalue = '';
							/* Check if we have any content otherwise use the default value */
							if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->field_name;
							$contents .= $this->_exportmodel->AddExportField($fieldvalue, $fieldname, $field->column_header, true);
							break;
						default:
						   if (isset($record->$fieldname)) $fieldvalue = $record->$fieldname;
						   else $fieldvalue = '';
						   /* Check if we have any content otherwise use the default value */
						   if (strlen(trim($fieldvalue)) == 0) $fieldvalue = $field->field_name;
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
		else {
			$contents = JText::_('ERROR_RETRIEVING_DATA');
			/* Output the contents */
			CsvimprovedModelExportfile::writeOutput($contents);
		}
	}
}
?>