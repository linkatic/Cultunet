<?php
/**
 * Manufacturer import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: manufacturerimport.php 932 2009-06-11 05:06:04Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product details
 *
 * Main processor for importing categories.
 *
 * @package CSVImproved
 * @todo Check vendor ID
 * @todo Add default category check
 */
class CsvimprovedModelManufacturerimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_manufacturer table */
	private $_vm_manufacturer = null;
	/** @var object contains the vm_manufacturer table */
	private $_vm_manufacturer_category = null;
	
	/* Public variables */
	/** @var integer contains the manufacturer ID */
	public $manufacturer_id = null;
	/** @var integer contains the category ID for a manufacturer */
	public $category_id = null;
	/** @var integer contains the manufacturer category ID for a product */
	public $mf_category_id = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	/** @var object contains general import functions */
	private $_importmodel = null;
	
	/**
	 * Here starts the processing
	 */
	public function getStart() {
		/* Get the general import functions */
		$this->_importmodel = new CsvimprovedModelimport();
		
		/* Get the imported fields */
		$csv_fields = JRequest::getVar('csv_fields');
		
		/* Get the statistics */
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check for vendor ID */
		$this->vendor_id = $this->_importmodel->GetVendorId();
		
		/* Check if the fields match the data */
		if (count($csv_fields) != count(JRequest::getVar('csvi_data'))) {
			$find = array('{configfields}', '{filefields}');
			$replace = array(count($csv_fields), count(JRequest::getVar('csvi_data')));
			$csvilog->AddStats('incorrect', str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT')), true);
			return false;
		}
		/* All good, let's continue */
		else {
			/* Get the uploaded values */
			foreach ($csv_fields as $name => $details) {
				if ($details['published']) {
					$this->_datafield = $this->_importmodel->ValidateCsvInput($name);
					if ($this->_datafield !== false) {
						/* Check if the field needs extra treatment */
						switch ($name) {
							case 'manufacturer_name':
								$this->mf_name = $this->_datafield;
								break;
							case 'manufacturer_desc':
								$this->mf_desc = $this->_datafield;
								break;
							case 'manufacturer_email':
								$this->mf_email = $this->_datafield;
								break;
							case 'manufacturer_url':
								$this->mf_url = $this->_datafield;
								break;
							case 'manufacturer_category_id':
								$this->mf_category_id = $this->_datafield;
								break;
							case 'manufacturer_category_name':
								$this->mf_category_name = $this->_datafield;
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
		$this->_vm_manufacturer = $this->getTable('vm_manufacturer');
		$this->_vm_manufacturer_category = $this->getTable('vm_manufacturer_category');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_manufacturer->reset();
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
		/* Get the imported values */
		$csv_fields = JRequest::getVar('csv_fields');
		$product_data = JRequest::getVar('csvi_data');
		$csvilog = JRequest::getVar('csvilog');
		
		/* Get the tables loaded */
		$this->getTables();
		
		/* Check if we need to get manufacturer category ID */
		if (!isset($this->mf_category_id) && isset($this->mf_category_name)) {
			$this->_vm_manufacturer_category->bind($this);
			$this->_vm_manufacturer_category->setValue('mf_update', 'mf_category_name');
			$this->_vm_manufacturer_category->check();
			$this->mf_category_id = $this->_vm_manufacturer_category->getValue('mf_category_id');
		}
		
		$this->_vm_manufacturer->bind($this);
		if ($this->_vm_manufacturer->store()) {
			$this->manufacturer_id = $this->_vm_manufacturer->getValue('manufacturer_id');
			$csvilog->AddStats('added', JText::_('Manufacturer has been added'));
		}
		else {
			echo $this->_vm_manufacturer->getError();
			$csvilog->AddStats('incorrect', JText::_('Could not add manufacturer'));
		}
		
		/* Clean the tables */
		$this->getCleanTables();
	}
}
?>
