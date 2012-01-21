<?php
/**
 * Template import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: templateimport.php 907 2009-05-22 15:26:28Z Roland $
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
class CsvimprovedModelTemplateimport extends JModel {
	
	/* Private tables */
	/** @var object contains the csvi_templates table */
	private $_csvi_templates = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	
	/* Public variables */
	/** @var integer contains the unique ID of the template */
	public $template_id = null;
	/** @var string contains the name of the template */
	public $template_name = null;
	/** @var string contains the value if the template needs to be deleted */
	public $template_delete = 'N';
	
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
		if (!isset($csv_fields['template_id']) && !isset($csv_fields['template_name'])) {
			$csvilog->AddStats('incorrect', "Line ".JRequest::getVar('currentline').": No template ID or template name specified");
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
							case 'template_delete':
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
		}
		return true;
	}
	
	/**
	 * Getting the product table info
	 */
	private function getTables() {
		$this->_csvi_templates = $this->getTable('csvi_templates');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_csvi_templates->reset();
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
			$csvilog->AddMessage('debug', JText::_('DEBUG_SKIPPING_TEMPLATE_DATA_EXISTS'));
		   $csvilog->AddStats('skipped', JText::_('DATA_EXISTS'), true);
		}
		else {
			/* Load the tables that will contain the data */
			$this->getTables();
			
			/* Check if we have a template ID, if not get it */
			if (is_null($this->template_id)) $this->template_id = $this->_importmodel->getTemplateId();
			
			/* Bind the data */
			$this->_csvi_templates->bind($this);
			
			/* User wants to delete the template */
			if (isset($this->template_id) && $this->template_delete == "Y") {
				$this->_csvi_templates->delete($this->template_id);
				
			}
			else if (!isset($this->template_id) && $template->ignore_non_exist) {
				/* Do nothing for new products when user chooses to ignore new products */
				/* Add logging */
			}
			/* User wants to add or update the product */
			else {
				if ($this->_csvi_templates->store()) {
					$csvilog->AddStats('added', JText::_('TEMPLATE_STORED'), true);
					$csvilog->AddMessage('debug', JText::_('DEBUG_TEMPLATE_STORED'), true);
				}
				else {
					$csvilog->AddStats('incorrect', JText::_('TEMPLATE_NOT_STORED'), true);
					$csvilog->AddMessage('debug', JText::_('DEBUG_TEMPLATE_NOT_STORED'), true);
				}
				
				/**
				 * Now that all is done, we need to clean the table objects
				 */
				$this->getCleanTables();
			}
		}
	}
}
?>
