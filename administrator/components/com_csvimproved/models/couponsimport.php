<?php
/**
 * Coupons import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: userinfoimport.php 694 2009-01-18 13:38:36Z Suami $
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
class CsvimprovedModelCouponsimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_user_info table */
	private $_vm_coupons = null;
	
	/* Public variables */
	/** @var integer contains the category ID for a product */
	public $coupon_id = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	/** @var object contains general import functions */
	private $_importmodel = null;
	
	/**
	 * Here starts the processing
	 *
	 * @todo change cdate/mdate to use JDate
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
							case 'coupon_value':
								$this->$name = $this->_importmodel->ToPeriod($this->_datafield);
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
		$this->_vm_coupons = $this->getTable('vm_coupons');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_coupons->reset();
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
	 */
	public function getProcessRecord() {
		/* Get the imported values */
		$csv_fields = JRequest::getVar('csv_fields');
		$product_data = JRequest::getVar('csvi_data');
		$csvilog = JRequest::getVar('csvilog');
		$db = JFactory::getDBO();
		
		/* Get the tables loaded */
		$this->getTables();
		
		$this->_vm_coupons->bind($this);
		$this->_vm_coupons->check();
		if (!$this->_vm_coupons->store()) {
			$csvilog->AddStats('incorrect', JText::_('COUPON_NOT_ADDED').$this->_vm_coupons->getError());
		}
		else $csvilog->AddStats('added', JText::_('COUPON_ADDED'));
		
		// $csvilog->AddMessage('debug', JText::_('Stored user info'), true);
		
		/* Clean the tables */
		$this->getCleanTables();
	}
}
?>