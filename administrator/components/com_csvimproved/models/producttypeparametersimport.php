<?php
/**
 * Product type parameters import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: producttypeparametersimport.php 907 2009-05-22 15:26:28Z Roland $
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
class CsvimprovedModelProducttypeparametersimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_product_files table */
	private $_vm_product_type_parameter = null;
	
	/* Public variables */
	/** @var integer contains the ID for the product type */
	public $product_type_id = null;
	/** @var string contains the parameter multi-select setting */
	public $parameter_multiselect = 'N';
	/** @var string contains the parameter multi-select values */
	public $parameter_values = null;
	/** @var string contains the parameter multi-select values */
	public $product_type_parameter_delete = 'N';
	
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
			foreach ($csv_fields as $name => $details) {
				if ($details['published']) {
					$this->_datafield = $this->_importmodel->ValidateCsvInput($name);
					if ($this->_datafield !== false) {
						/* Check if the field needs extra treatment */
						switch ($name) {
							case 'product_type_parameter_name':
								$this->parameter_name = str_replace(" ", "_", $this->_datafield);
								break;
							case 'product_type_parameter_label':
								$this->parameter_label = $this->_datafield;
								break;
							case 'product_type_parameter_description':
								$find = array("\r\n", "\n");
								$replace = array("", "");
								$this->parameter_description = str_replace($find, $replace, $this->_datafield);
								break;
							case 'product_type_parameter_list_order':
								$this->parameter_list_order = $this->_datafield;
								break;
							case 'product_type_parameter_type':
								$this->parameter_type = strtoupper($this->_datafield);
								break;
							case 'product_type_parameter_values':
								/* Strip a trailing ; */
								if (';' == substr($this->_datafield, -1)) $this->_datafield = substr($this->_datafield, 0, -1);
								$this->parameter_values = $this->_datafield;
								break;
							case 'product_type_parameter_multiselect':
								$this->parameter_multiselect = strtoupper($this->_datafield);
								break;
							case 'product_type_parameter_default':
								$this->parameter_default = $this->_datafield;
								break;
							case 'product_type_parameter_unit':
								$this->parameter_unit = $this->_datafield;
								break;
							case 'product_type_parameter_delete':
								$this->$name = strtoupper($this->_datafield);
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
			
			/* Check if we have a parameter name */
			if (isset($this->parameter_name)) {
				/* Get the product type ID */
				if (is_null($this->product_type_id)) $this->product_type_id = $this->_importmodel->GetProductTypeId();
				if ($this->product_type_id) {
					if( $this->parameter_multiselect == "Y" && (!isset($this->parameter_values) || $this->parameter_values == "" )) {
						// $csvilog->AddMessage('debug', "ERROR:  If You checked Multiple select you must enter a Possible Values.");
						$csvilog->AddStats('incorrect', JText::_('NO_PARAMETER_VALUES'));
						return false;
					}
					/* Check the list order */
					$this->ProductTypeParameterListOrder();
				}
				else {
					$csvilog->AddStats('incorrect', JText::_('NO_PRODUCT_ID'));
					return false;
				}
			}
			else {
				$csvilog->AddStats('incorrect', JText::_('NO_PARAMETER_NAME'));
				return false;
			}
			
			/* Check for a breakline */
			if (!$this->parameter_label) {
				if ($this->parameter_type == "B") {
					$this->parameter_label = $this->parameter_name;
				}
			}
			
			/* Check the multi-select option */
			if ($this->parameter_multiselect == "Y" && is_null($this->parameter_values)) {
				// $csvilog->AddMessage('debug', "ERROR:  If You checked Multiple select you must enter a Possible Values.");
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Getting the product table info
	 */
	private function getTables() {
		$this->_vm_product_type_parameter = $this->getTable('vm_product_type_parameter');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_product_type_parameter->reset();
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
		$this->_vm_product_type_parameter->bind($this);
		
		/* Check if we need to store or delete the entry */
		if ($this->product_type_parameter_delete == 'Y') {
			/* Delete the parameter */
			$this->_vm_product_type_parameter->delete();
		}
		else {
			/* Store the data */
			if ($this->_vm_product_type_parameter->store()) {
				$csvilog->AddStats('updated', JText::_('PRODUCT_TYPE_PARAMETER_UPDATED'), true);
			}
			else {
				$csvilog->AddStats('incorrect', JText::_('PRODUCT_TYPE_PARAMETER_NOT_UPDATED'), true);
			}
		}
		
		/* Clean the tables */
		$this->getCleanTables();
	}
	
	/**
	 * Get the highest list order and add 1 for the new list order
	 */
	private function ProductTypeParameterListOrder() {
		$db = JFactory::getDBO();
		
		if (!isset($this->parameter_list_order) || $this->parameter_list_order == 0) {
			$q = "SELECT MAX(parameter_list_order) AS list_order
				FROM #__vm_product_type_parameter
				WHERE product_type_id = ".$this->product_type_id;
			$db->setQuery($q);
			$db->query();
			$this->parameter_list_order = $db->loadResult()+1;
		}
	}
}
?>
