<?php
/**
 * Category import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: categorydetailsimport.php 946 2009-08-03 09:47:37Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product details
 *
 * Main processor for importing categories.
 *
 * @package CSVImproved
 * @todo Check vendor ID
 */
class CsvimprovedModelCategorydetailsimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_category table */
	private $_vm_category = null;
	
	/* Public variables */
	public $category_path = null;
	/** @var integer contains the category ID for a product */
	public $category_id = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	/** @var object contains general import functions */
	private $_importmodel = null;
	/** @var object contains general category functions */
	private $_categorymodel = null;
	
	/**
	 * Here starts the processing
	 */
	public function getStart() {
		/* Get the general import functions */
		$this->_importmodel = new CsvimprovedModelimport();
		
		/* Get the general category functions */
		$this->_categorymodel = new CsvimprovedModelCategory();
		$this->_categorymodel->getStart();
		
		/* Get the imported fields */
		$csv_fields = JRequest::getVar('csv_fields');
		
		/* Get the logging */
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check for vendor ID */
		$this->vendor_id = $this->_importmodel->GetVendorId();
		
		/* Check if the fields match the data */
		if (count($csv_fields) != count(JRequest::getVar('csvi_data'))) {
			JRequest::setVar('error_found', true);
			$find = array('{configfields}', '{filefields}');
			$replace = array(count($csv_fields), count(JRequest::getVar('csvi_data')));
			$csvilog->AddStats('incorrect', str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT')), true);
			return false;
		}
		/* There is no category path, cannot continue */
		else if (!isset($csv_fields['category_path']) && !isset($csv_fields['category_id'])) {
			$csvilog->AddStats('incorrect', JText::_('No category path set'), true);
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
							case 'category_products_per_row':
								$this->products_per_row = $this->_datafield;
								break;
							case 'category_list_order':
								$this->list_order = $this->_datafield;
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
		$this->_vm_category = $this->getTable('vm_category');
	}
	
	/**
	 * Getting the product table info
	 * @todo Proper clean up for variables
	 */
	private function getCleanTables() {
		$this->_vm_category->reset();
		unset($this->category_name);
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
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$csv_fields = JRequest::getVar('csv_fields');
		$product_data = JRequest::getVar('csvi_data');
		$template = JRequest::getVar('template');
		
		/* Get the tables loaded */
		$this->getTables();
		
		/* Check if the user wants to create thumbnails */
		if ($template->thumb_create) {
			/* Check for image URLs */
			if (isset($this->category_full_image) && isset($this->category_thumb_image)) {
				$file_details = $this->_importmodel->ProcessImage($this->category_full_image, $this->category_thumb_image, $template->file_location_category_images);
			}
			else {
				$csvilog->AddStats('skipped', JText::_('NO_THUMB_FULL_IMAGE_IN_FILE'));
			}
		}
		else {
			$csvilog->AddMessage('debug', JText::_('DEBUG_USER_NO_CREATE_THUMB'));
		}
		
		/* First get the category ID */
		if (!isset($this->category_id)) {
			$categoryid = $this->_categorymodel->GetCategoryId($product_data[$csv_fields['category_path']["order"]]);
			/* If we can't get a category ID we cannot continue */
			if (!$categoryid) {
				$csvilog->AddStats('incorrect', JText::_('Could not find a category ID'));
				return false;
			}
			else $this->category_id = $categoryid['category_id'];
		}
		/* Set some basic values */
		$this->mdate = time();
		
		/* Check if the category_name matches the last entry in the category_path */
		if (isset($this->category_name)){
			$catparts = explode('/', $this->category_path);
			end($catparts);
			if (current($catparts) != $this->category_name) {
				$csvilog->AddStats('incorrect', JText::_('CATEGORY_NAME_NO_MATCH_CATEGORY_PATH'));
				return false;
			}
		}
		
		/* All fields have been processed, bind the data */
		$this->_vm_category->bind($this);
		
		/* Now store the data */
		if ($this->_vm_category->store()) {
			$query = $db->getQuery();
			if (substr($query, 0, 6) == 'UPDATE') $csvilog->AddStats('updated', JText::_('CATEGORY HAS BEEN UPDATED'));
			else if (substr($query, 0, 6) == 'INSERT') $csvilog->AddStats('added', JText::_('CATEGORY HAS BEEN ADDED'));
		}
		else $csvilog->AddStats('incorrect', JText::_('CATEGORY COULD NOT BE ADDED'));
		
		$csvilog->AddMessage('debug', JText::_('DEBUG_STORE_CATEGORY'), true);
		
		/* Clean the tables */
		$this->getCleanTables();
	}
}
?>