<?php
/**
 * Shipping rates import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: manufacturerimport.php 681 2009-01-07 07:12:53Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for shipping rates
 *
 * @package CSVImproved
 */
class CsvimprovedModelShippingratesimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_shipping_rate table */
	private $_vm_shipping_rates = null;
	
	/* Public variables */
	/** @var mixed contains the unique shipping rate id value */
	public $shipping_rate_id = null;
	
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
		$this->_vm_shipping_rate = $this->getTable('vm_shipping_rate');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_shipping_rate->reset();
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
		$db = JFactory::getDBO();
		
		/* Get the tables loaded */
		$this->getTables();
		
		/* Get the currency ID, the code takes preference over the name because it is less error prone */
		if (isset($this->currency_code)) {
			$q = "SELECT currency_id FROM #__vm_currency WHERE currency_code = ".$db->Quote($this->currency_code);
			$db->setQuery($q);
			$this->shipping_rate_currency_id = $db->loadResult();
		}
		else if (isset($this->currency_name)) {
			$q = "SELECT currency_id FROM #__vm_currency WHERE currency_name = ".$db->Quote($this->currency_name);
			$db->setQuery($q);
			$csvilog->AddMessage('debug', JText::_('DEBUG_FIND_SHIPPING_CURRENCY_ID'), true);
			$this->shipping_rate_currency_id = $db->loadResult();
		}
		
		/* Check if we have a shipping rate ID */
		if (is_null($this->shipping_rate_id) && isset($this->shipping_rate_name)) {
			/* Get the shipping rate ID */
			$q = "SELECT shipping_rate_id FROM #__vm_shipping_rate WHERE shipping_rate_name = ".$db->Quote($this->shipping_rate_name)." LIMIT 1";
			$db->setQuery($q);
			$csvilog->AddMessage('debug', JText::_('DEBUG_FIND_SHIPPING_RATE_ID'), true);
			$this->shipping_rate_id = $db->loadResult();
		}
		$this->_vm_shipping_rate->bind($this);
		if ($this->_vm_shipping_rate->store()) {
			$csvilog->AddMessage('debug', JText::_('DEBUG_SHIPPING_RATE_ADDED'), true);
			$csvilog->AddStats('added', JText::_('SHIPPING_RATE_ADDED'));
		}
		else {
			$csvilog->AddMessage('debug', JText::_('SHIPPING_RATE_NOT_ADDED'), true);
			$csvilog->AddStats('incorrect', JText::_('SHIPPING_RATE_NOT_ADDED'));
		}
		
		/* Clean the tables */
		$this->getCleanTables();
	}
}
?>
