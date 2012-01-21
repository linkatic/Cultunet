<?php
/**
 * Multiple prices import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: multiplepricesimport.php 907 2009-05-22 15:26:28Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for multiple prices
 *
 * Main processor for importing multiple prices
 *
 * @package CSVImproved
 */
class CsvimprovedModelMultiplepricesimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_manufacturer table */
	private $_vm_product_price = null;
	
	/* Public variables */
	/** @var integer contains the category ID for a product */
	public $category_id = null;
	/** @var integer contains the manufacturer category ID for a product */
	public $mf_category_id = null;
	/** @var integer contains the product ID for a price */
	public $product_id = null;
	/** @var integer contains the ID for a price */
	public $product_price_id = null;
	/** @var integer contains the value if the price needs to be deleted */
	public $price_delete = null;
	/** @var integer contains the id value of the shopper group */
	public $shopper_group_id = null;
	/** @var string contains the name value of the shopper group */
	public $shopper_group_name = null;
	
	
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
							case 'product_price':
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
		$this->_vm_product_price = $this->getTable('vm_product_price');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_product_price->reset();
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
		$template = JRequest::getVar('template');
		
		/* Get the tables loaded */
		$this->getTables();
		
		/* Get the product ID if we don't already have it */
		if (!isset($this->product_id)) $this->product_id = $this->_importmodel->GetProductId();
		
		/** 
		 * Get the shopper group ID
		 *
		 * The shopper group ID takes preference over the shopper group name
		 */
		if (strlen(trim($this->shopper_group_id)) == 0) {
			if (strlen(trim($this->shopper_group_name)) > 0) {
				$this->shopper_group_id = $this->_importmodel->ShopperGroupName($this->shopper_group_name);
			}
			else $this->shopper_group_id = $this->_importmodel->GetDefaultShopperGroupID();
		}
		
		/* Currency check as we need a currency, take VM default currency if not set */
		if (!isset($this->product_currency)) {
			$db = JFactory::getDBO();
			$q = "SELECT vendor_currency FROM #__vm_vendor WHERE vendor_id = 1";
			$db->setQuery($q);
			$this->product_currency = $db->loadResult();
		}
		
		/* Bind the data */
		$this->_vm_product_price->bind($this);
		
		/* See if we need to find a product_price_id */
		if (!isset($this->product_price_id)) {
			$csvilog->AddMessage('debug', JText::_('Going to find a product_price_id'));
			$this->_vm_product_price->checkMultiple();
			$this->product_price_id = $this->_vm_product_price->getValue('product_price_id');
		}
		else {
			$csvilog->AddMessage('debug', JText::_('Already have a product_price_id'));
		}
		/* Check if the user wants to delete a price */
		if (strtoupper($this->price_delete) == 'Y' && isset($this->product_price_id)) {
			if (!$this->_vm_product_price->delete($this->product_price_id)) {
				$csvilog->AddStats('deleted', JText::_('Price could not be deleted'));
			}
			else {
				$csvilog->AddStats('deleted', JText::_('Price has been deleted'));
			}
			$csvilog->AddMessage('debug', JText::_('Tried to delete the price'), true);
		}
		else {
			if (!isset($this->product_id)) {
				$csvilog->AddMessage('debug', JText::_('There is no product ID found'));
				$csvilog->AddStats('skipped', JText::_('There is no product ID found'));
			}
			else if (!isset($this->product_price_id) && !isset($this->product_price)) {
				$csvilog->AddMessage('debug', JText::_('There is no product price found'));
				$csvilog->AddStats('skipped', JText::_('There is no product price'));
			}
			else {
				/* See if there is any calculation needed on the prices */
				if (isset($this->product_price_id)) $this->_vm_product_price->CalculatePrice();
				
				/* Store the price */
				if (!$this->_vm_product_price->store()) {
					$csvilog->AddMessage('debug', JText::_('Product price could not be updated'), true);
					$csvilog->AddStats('added', JText::_('Product price could not be updated'));
				}
				else {
					$csvilog->AddMessage('debug', JText::_('Product price has been updated'), true);
					$csvilog->AddStats('added', JText::_('Product price has been updated'));
				}
			}
		}
		
		/* Clean the tables */
		$this->getCleanTables();
	}
}
?>
