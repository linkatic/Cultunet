<?php
/**
 * Manufacturer category import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: manufacturercategoryimport.php 907 2009-05-22 15:26:28Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product details
 *
 * Main processor for handling product details.
 *
 * @package CSVImproved
 * @todo Remove images
 * @todo check update null fields
 */
class CsvimprovedModelManufacturercategoryimport extends JModel {
	
	/* Private tables */
	/** @var object contains the csvi_templates table */
	private $_vm_manufacturer_category = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	
	/* Public variables */
	
	/**
	 * Here starts the processing
	 */
	public function getStart() {
		/* Get the general import functions */
		$this->_importmodel = new CsvimprovedModelimport();
		
		/* These functions we call ourselves */
		$csv_fields = JRequest::getVar('csv_fields');
		
		/* Get the statistics */
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check if either the template ID or template name has been specified */
		if (!isset($csv_fields['manufacturer_category_id']) && !isset($csv_fields['manufacturer_category_name'])) {
			$csvilog->AddStats('incorrect', "Line ".JRequest::getVar('currentline').": No manufacturer category ID or name specified");
			return false;
		}
		
		
		/* Validate CSV Input */
		if (count($csv_fields) != count(JRequest::getVar('csvi_data'))) {
			$find = array('{configfields}', '{filefields}');
			$replace = array(count($csv_fields), count(JRequest::getVar('csvi_data')));
			$csvilog->AddStats('incorrect', str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT')), true);
			return false;
		}                                
		else {
			foreach ($csv_fields as $name => $details) {
				if ($details['published']) {
					$this->_datafield = $this->_importmodel->ValidateCsvInput($name);
					if ($this->_datafield !== false) {
						switch($name) {
							case 'manufacturer_category_id':
								$this->mf_category_id = $this->_datafield;
								break;
							case 'manufacturer_category_name':
								$this->mf_category_name = $this->_datafield;
								break;
							case 'manufacturer_category_desc':
								$this->mf_category_desc = $this->_datafield;
								break;
							case 'manufacturer_category_delete':
								$this->mf_category_delete = strtoupper($this->_datafield);
								break;
							default:
								$this->$name = $this->_datafield;
								break;
						}
					}
					else {
						/* Columns do not match */
						JRequest::setVar('error_found', true);
						$csvi_data = JRequest::getVar('csvi_data');
						$find = array('{configfields}', '{filefields}');
						$replace = array(count($csv_fields), count($csvi_data));
						$fields = array_keys($csv_fields);
						$message =  str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT'));
						$message .= '<br />'.JText::_('FIELDS');
						foreach($csv_fields as $fieldname => $field_details) {
							$message .= '<br />'.$field_details['order'].': '.$fieldname;
						}
						$message .= '<br />'.JText::_('VALUE');
						foreach ($csvi_data AS $key => $data) {
							$message .= '<br />'.$key.': '.$data;
						}
						$csvilog->AddStats('incorrect', $message, true);
						
						return false;
					}
				}
			}
		}
		return true;
	}
	
	/**
	 * Getting the product table info
	 */
	private function getTables() {
		$this->_vm_manufacturer_category = $this->getTable('vm_manufacturer_category');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_manufacturer_category->reset();
		$this->reset();
	}
	
	/**
	 * Clean the variables
	 */
	private function reset() {
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') $this->$name = $value;
		}
	}
	
	/**
	 * Process each record and store it in the database
	 *
	 * @todo Add logging
	 */
	public function getProcessRecord() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$csv_fields = JRequest::getVar('csv_fields');
		$template = JRequest::getVar('template');
		if (!$template->overwrite_existing_data) {
		   $csvilog->AddMessage('debug', 'Skipping template as we are not overwriting data: '.$this->template_name);
		   $csvilog->AddStats('skipped', JText::_('Data exists'));
		}
		else {
			/* Load the tables that will contain the data */
			$this->getTables();
			
			/* Check if we have a manufacturer category ID, if not get it */
			if (isset($this->id) && is_null($this->id)) {
				$csvilog->AddMessage('debug', JText::_('There is no manufacturer category ID, going to find one.'));
				$this->id = $this->getManufacturerCategoryId();
			}
			
			/* Bind the data */
			$this->_vm_manufacturer_category->bind($this);
			
			/* User wants to delete the template */
			if (isset($this->mf_category_id) && $this->mf_category_delete == "Y") {
				$this->_vm_manufacturer_category->delete($this->mf_category_id);
				$csvilog->AddMessage('debug', JText::_('Deleting the manufacturer category'), true);
				$csvilog->AddStats('deleted', JText::_('Manufacturer category deleted'));
			}
			else if (!isset($this->mf_category_id) && $template->ignore_non_exist) {
				/* Do nothing for new products when user chooses to ignore new products */
				/* Add logging */
			}
			/* User wants to add or update the product */
			else {
				if ($this->_vm_manufacturer_category->store()) {
					$csvilog->AddMessage('debug', JText::_('Storing the manufacturer category'), true);
					$csvilog->AddStats('added', JText::_('Manufacturer category has been stored'));
					return true;
				}
				else {
					$csvilog->AddMessage('debug', JText::_('Storing the manufacturer category but we have a problem'), true);
					$csvilog->AddStats('incorrect', JText::_('Manufacturer category could not be stored'));
					return false;
				}
			}
			
			/**
			 * Now that all is done, we need to clean the table objects
			 */
			$this->getCleanTables();
		}
	}
	
	/**
	 * Get the template field ID
	 */
	private function getManufacturerCategoryId() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$q = "SELECT mf_category_id
			FROM #__vm_manufacturer_category
			WHERE mf_category_name  = ".$db->Quote($this->mf_category_name);
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('Finding manufacturer category ID'), true);
		if ($db->query()) return $db->loadResult();
		else return false;
	}
}
?>
