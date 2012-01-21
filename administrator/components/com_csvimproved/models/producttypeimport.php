<?php
/**
 * Product types import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: producttypeimport.php 907 2009-05-22 15:26:28Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product types
 *
 * Main processor for importing categories.
 *
 * @package CSVImproved
 * @todo Clarify folder structure
 */
class CsvimprovedModelProducttypeimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_product_files table */
	private $_vm_product_type = null;
	
	/* Public variables */
	/** @var mixed contains the ID for the product type */
	public $product_type_id = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	/** @var object contains general import functions */
	private $_importmodel = null;
	/** @var boolean contains the setting if a new product type table needs to be created */
	private $create_table = false;
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
		
		/* Get the file_product_id */
		$this->file_product_id = $this->_importmodel->GetProductId();
		
		/* Check if the fields match the data */
		if (count($csv_fields) != count(JRequest::getVar('csvi_data'))) {
			$find = array('{configfields}', '{filefields}');
			$replace = array(count($csv_fields), count(JRequest::getVar('csvi_data')));
			$csvilog->AddStats('incorrect', str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT')), true);
			return false;
		}
		/* All good, let's continue */
		else {
			foreach ($csv_fields as $name => $details) {
				if ($details['published']) {
					$this->_datafield = $this->_importmodel->ValidateCsvInput($name);
					if ($this->_datafield !== false) {
						/* Check if the field needs extra treatment */
						switch ($name) {
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
			
			/* Check if the list order is empty */
			if (isset($csv_fields['product_type_list_order']) && !isset($this->product_type_list_order)) {
				$this->ProductTypeListOrder();
			}
		}
		return true;
	}
	
	/**
	 * Getting the product table info
	 */
	private function getTables() {
		$this->_vm_product_type = $this->getTable('vm_product_type');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_product_type->reset();
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
		
		/* All data should be loaded, bind the data */
		$this->_vm_product_type->bind($this);
		
		/* Check if we already have a product type ID */
		if (is_null($this->product_type_id)) {
			$this->_vm_product_type->check();
			if (is_null($this->_vm_product_type->getValue('product_type_id'))) {
				$this->create_table = true;
			}
		}
		
		/* Store the data */
		if ($this->_vm_product_type->store()) {
			$csvilog->AddMessage('debug', JText::_('STORE_PRODUCT_TYPE'), true);
			$csvilog->AddStats('added', JText::_('PRODUCT_TYPE_ADDED'), true);
		}
		else {
			$csvilog->AddMessage('debug', JText::_('ERROR_STORE_PRODUCT_TYPE'), true);
			$csvilog->AddStats('error', JText::_('ERROR_PRODUCT_TYPE_ADDED'), true);
		}
		
		if ($this->create_table) {
			$this->product_type_id = $this->_vm_product_type->getValue('product_type_id');
			/* We have a new product type, need to create the table */
			if ($this->CreateProductTypeTable()) {
				$csvilog->AddStats('added', JText::_('CREATED_PRODUCT_TYPE_TABLE'));
			}
			else {
				$csvilog->AddStats('error', JText::_('ERROR_CREATED_PRODUCT_TYPE_TABLE'));
			}
		}
		
		/* Clean the tables */
		$this->getCleanTables();
	}
	
	/**
	 * Get the highest list order and add 1 for the new list order
	 */
	private function ProductTypeListOrder() {
		$db = JFactory::getDBO();

		$q = "SELECT MAX(product_type_list_order) AS list_order
			FROM #__vm_product_type";
		$db->setQuery($q);
		$db->query();
		$this->product_type_list_order = $db->loadResult()+1;
	}
	
	/**
	 * New product types require new tables
	 *
	 * Tables are created with the name product_type_<id>
	 */
	private function CreateProductTypeTable() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');

		$q = "CREATE TABLE IF NOT EXISTS `#__vm_product_type_";
		$q .= $this->product_type_id . "` (";
		$q .= $db->nameQuote('product_id')." int(11) NOT NULL,";
		$q .= "PRIMARY KEY (".$db->nameQuote('product_id').")";
		$q .= ")";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('CREATE_PRODUCT_TYPE_TABLE'), true);
		if ($db->query()) return true;
		else return false;
	}
}
?>