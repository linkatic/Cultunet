<?php
/**
 * Product type names import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: producttypenamesimport.php 947 2009-08-05 08:00:22Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product details
 *
 * Main processor for importing categories.
 *
 * @package CSVImproved
 * @todo Clarify folder structure
 */
class CsvimprovedModelProducttypenamesimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_product table */
	private $_vm_product_product_type_xref = null;
	
	/* Public variables */
	/** @var mixed contains the ID for the product type */
	public $product_type_id = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	/** @var object contains general import functions */
	private $_importmodel = null;
	/** @var object contains the fields to import */
	private $_csv_fields = null;
	
	/**
	 * Here starts the processing
	 */
	public function getStart() {
		/* Get the general import functions */
		$this->_importmodel = new CsvimprovedModelimport();
		
		/* Get the imported fields */
		if (is_null($this->_csv_fields)) $this->_csv_fields = JRequest::getVar('csv_fields');
		
		/* Get the statistics */
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check if the fields match the data */
		if (count($this->_csv_fields) != count(JRequest::getVar('csvi_data'))) {
			$find = array('{configfields}', '{filefields}');
			$replace = array(count($this->_csv_fields), count(JRequest::getVar('csvi_data')));
			$csvilog->AddStats('incorrect', str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT')), true);
			return false;
		}
		/* All good, let's continue */
		else {
			foreach ($this->_csv_fields as $name => $details) {
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
						$replace = array(count($this->_csv_fields), count($csvi_data));
						$fields = array_keys($this->_csv_fields);
						$message =  str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT'));
						$message .= '<br />'.JText::_('FIELDS');
						foreach($this->_csv_fields as $fieldname => $field_details) {
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
		$this->_vm_product_product_type_xref = $this->getTable('vm_product_product_type_xref');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_product_product_type_xref->reset();
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
		$product_data = JRequest::getVar('csvi_data');
		$csvilog = JRequest::getVar('csvilog');
		$db = JFactory::getDBO();
		$action = '';
		
		/* Load the tables */
		$this->getTables();
		
		/* Get the product type */
		if (is_null($this->product_type_id)) $this->product_type_id = $this->_importmodel->GetProductTypeId();
		
		/* Get the product ID */
		$this->product_id = $this->_importmodel->GetProductId();
		
		if ($this->product_type_id) {
			/* Get the fields for the product type # */
			$q = "SHOW COLUMNS FROM #__vm_product_type_".$this->product_type_id;
			$db->setQuery($q);
			$columns = $db->loadResultArray();
			
			if (is_array($columns)) {
				/* Check if the product type ID already exists */
				$q = "SELECT COUNT(product_id) AS products 
					FROM #__vm_product_type_".$this->product_type_id." 
					WHERE product_id = '".$this->product_id."'";
				$db->setQuery($q);
				$product_type_exists = $db->loadResult();
				
				/* Variable used for reporting query type */
				if ($product_type_exists > 0) {
					$q = "UPDATE #__vm_product_type_".$this->product_type_id." ";
					$q .= "SET ";
					
					foreach ($columns as $key => $colname) {
						if (isset($this->_csv_fields[strtolower($colname)])) {
							$q .= $db->nameQuote($colname)." = ".$db->Quote($product_data[$this->_csv_fields[strtolower($colname)]['order']]).",";
						}
					}
					$q = substr($q, 0, -1)." ";
					$q .= "WHERE product_id = ".$this->product_id;
					$action = 'added';
					$csvilog->AddMessage('debug', JText::_('DEBUG_UPDATING_NEW_PRODUCT_TYPE_DETAILS'), true);
				}	
				else {
					$q = "INSERT INTO #__vm_product_type_".$this->product_type_id." ";
					$q .= "(";
					$qfields = $db->nameQuote('product_id').',';
					$qvalues = '';
					foreach ($columns as $key => $colname) {
						if (isset($this->_csv_fields[strtolower($colname)])) {
							$qfields .= $db->nameQuote($colname).',';
							$qvalues .= $db->Quote($product_data[$this->_csv_fields[strtolower($colname)]['order']]).',';
						}
						
					}
					$q .= substr($qfields, 0, -1);
					$q .= ") VALUES (".$this->product_id.",";
					$q .= substr($qvalues, 0, -1);
					$q .= ")";
					$action = 'updated';
					$csvilog->AddMessage('debug', JText::_('DEBUG_ADDING_NEW_PRODUCT_TYPE_DETAILS'), true);
				}
				$db->setQuery($q);
				if ($db->query()) {
					$csvilog->AddMessage('debug', JText::_('UPDATE_PRODUCT_TYPE_X'), true);
					$csvilog->AddStats($action, JText::_('Product type detail SKU').' '.$this->product_sku, true);
				}
				else {
					$csvilog->AddMessage('debug', JText::_('DEBUG_PRODUCT_TYPE_NAMES_STORE_FAILED'), true);
					$csvilog->AddStats('incorrect', JText::_('Product SKU could not be stored'), true);
				}
				
				/* Bind the data for cross reference */
				$this->_vm_product_product_type_xref->bind($this);
				/* Add the cross reference */
				$this->_vm_product_product_type_xref->store();
			}
			else {
				$csvilog->AddMessage('debug', JText::_('DEBUG_PRODUCT_TYPE_ID_NOT_FOUND'), true);
				$csvilog->AddStats('incorrect', JText::_('No product type ID found'), true);
			}
		}
		else {
			$csvilog->AddMessage('debug', JText::_('DEBUG_PRODUCT_TYPE_ID_NOT_FOUND'), true);
			$csvilog->AddStats('incorrect', JText::_('No product type ID found'), true);
		}
			
		/* Clean everything */
		$this->getCleanTables();
	}
}
?>