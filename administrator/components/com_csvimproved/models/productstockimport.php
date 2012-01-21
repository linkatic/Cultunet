<?php
/**
 * Product import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: productstockimport.php 930 2009-06-07 17:19:20Z Roland $
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
class CsvimprovedModelProductstockimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_product table */
	private $_vm_product = null;
	
	/* Private variables */
	/** @var object contains general import functions */
	private $_importmodel = null;
	
	/**
	 * Here starts the processing
	 */
	public function getStart() {
		/* Get the general import functions */
		$this->_importmodel = new CsvimprovedModelimport();
		$template = JRequest::getVar('template');
		
		/* Get the data to import */
		$product_data = JRequest::getVar('csvi_data', '', 'default', 'none', 2);
		
		/* Fields to import */
		$this->product_sku = $product_data[1];
		$this->product_in_stock = $product_data[2];
		
		/* Get the logger */
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check for product_id */
		if (!isset($this->product_id)) $this->product_id = $this->_importmodel->GetProductId();
		
		/* Set the record identifier */
		$this->record_identity = (isset($this->product_sku)) ? $this->product_sku : $this->product_id;
		
		return true;
	}
	
	/**
	 * Getting the product table info
	 */
	private function getTables() {
		$this->_vm_product = $this->getTable('vm_product');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		unset($this->_vm_product);
		unset($this->product_id);
		unset($this->product_sku);
		unset($this->product_in_stock);
	}
	
	/**
	 * Process each record and store it in the database
	 */
	public function getProcessRecord() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$csv_fields = JRequest::getVar('csv_fields');
		$template = JRequest::getVar('template');
		
		if (!$template->overwrite_existing_data) {
		   $csvilog->AddMessage('debug', str_ireplace('{product_sku}', $this->product_sku, JText::_('DATA_EXISTS_PRODUCT_SKU')));
		   $csvilog->AddStats('skipped', str_ireplace('{product_sku}', $this->product_sku, JText::_('DATA_EXISTS_PRODUCT_SKU')), true);
		}
		else {
			if (empty($this->product_sku) && empty($this->product_id)) {
				$csvilog->AddStats('incorrect', JText::_('DEBUG_NO_SKU'), true);
				$csvilog->AddMessage('debug', JText::_('DEBUG_NO_SKU_OR_ID'));
				return false;
			}
			else {
				$csvilog->AddMessage('debug', JText::_('DEBUG_PROCESS_SKU').$this->record_identity);
			}
			
			$csvilog->AddMessage('debug', JText::_('DEBUG_NORMAL_UPLOAD_EXPERT'));
		  
			/* Load the tables that will contain the data */
			$this->getTables();
			
			if (!isset($this->product_id) && $template->ignore_non_exist) {
				/* Do nothing for new products when user chooses to ignore new products */
				$csvilog->AddStats('skipped', str_ireplace('{product_sku}', $this->record_identity, JText::_('DATA_EXISTS_IGNORE_NEW')), true);
			}
			/* User wants to add or update the product */
			else {
				/* Process product info */
				if (!$this->ProductQuery()) {
					$csvilog->AddStats('incorrect', str_ireplace('{product_sku}', $this->product_sku, JText::_('NO_UPDATE_PRODUCT_SKU')), true);
				}
			}
			/* Now that all is done, we need to clean the table objects */
			$this->getCleanTables();
		}
	}
   
   

	/**
     * Creates either an update or insert SQL query for a product.
     *
     * @return bool true|false true if the query executed successful|false if the query failed
     */
	function ProductQuery() {
	  $csvilog = JRequest::getVar('csvilog');
	  
	  /* Bind the initial data */
	  $this->_vm_product->bind($this);
	  
	  /* Set some initial values */
	  /* Set the modified date as we are modifying the product */
	  $this->_vm_product->mdate = time();
	  
	  /* We have a succesful save, get the product_id */
	  if ($this->_vm_product->store()) {
		  $csvilog->AddMessage('debug', JText::_('DEBUG_STORE_PRODUCT'), true);
		  if (substr($this->_vm_product->_db->getQuery(), 0, strpos($this->_vm_product->_db->getQuery(),' ')) == 'UPDATE') {
			  $langtype = 'UPDATE_PRODUCT_SKU';
			  $sqltype = 'updated';
		  }
		  else {
			  $langtype = 'ADD_PRODUCT_SKU';
			  $sqltype = 'added';
		  }
		  $csvilog->AddStats($sqltype, str_ireplace('{product_sku}', $this->record_identity, JText::_($langtype)), true);
		  return true;
	  }
	  else {
		  $csvilog->AddMessage('debug', JText::_('DEBUG_STORE_PRODUCT'), true);
		  return false;
	  }
   }
}
?>