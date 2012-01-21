<?php
/**
 * Product files import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: productfilesimport.php 935 2009-06-19 11:44:32Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product details
 *
 * Main processor for importing categories.
 *
 * @package CSVImproved
 * @todo Clarify folder structure
 * @todo Add product ID <---- important
 */
class CsvimprovedModelProductfilesimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_product_files table */
	private $_vm_product_files = null;
	/** @var object contains the vm_product_attribute table */
	private $_vm_product_attribute = null;
	
	/* Public variables */
	/** @var integer contains the product ID of a product */
	public $product_id = null;
	/** @var integer contains the FQDN for the image */
	public $file_url = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	/** @var object contains general import functions */
	private $_importmodel = null;
	/** @var object contains the mimetype of a file */
	private $_mimetype = null;
	
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
		
		if ($this->file_product_id > 0) {
			/* Check if the fields match the data */
			if (count($csv_fields) != count(JRequest::getVar('csvi_data'))) {
				$find = array('{configfields}', '{filefields}');
				$replace = array(count($csv_fields), count(JRequest::getVar('csvi_data')));
				$csvilog->AddStats('incorrect', str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT')), true);
				return false;
			}
			/* All good, let's continue */
			else {
				/* Call the functions in this order */
				$this->ProductFilesFileName();
				$this->ProductFilesFileTitle();
				$this->ProductFilesFileDescription();
				$this->ProductFilesFileUrl();
				$this->ProductFilesFilePublished();
				$this->product_files_download = $this->_importmodel->ValidateCSVInput("product_files_download");
				
			}
			return true;
		}
		else {
			$csvilog->AddStats('incorrect', JText::_('NO_PRODUCT_ID_FOUND'));
			return false;
		}
	}
	
	/**
	 * Getting the product table info
	 */
	private function getTables() {
		$this->_vm_product_files = $this->getTable('vm_product_files');
		$this->_vm_product_attribute = $this->getTable('vm_product_attribute');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_product_files->reset();
		$this->_vm_product_attribute->reset();
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
		
		/* Let's see if the user has a downloadable product */
		if (isset($this->product_files_download)) {
			/* User wants to create a downloadable file */
			if (strtoupper($this->product_files_download) == 'Y') {
				/* Load file functions */
				jimport('joomla.filesystem.file');
				
				/* Unpublish the product */
				$this->file_published = 0;
				
				/* Set the downloadable properties */
				$this->_vm_product_attribute->setValue('product_id', $this->file_product_id);
				$this->_vm_product_attribute->setValue('attribute_name', 'download');
				$this->_vm_product_attribute->setValue('attribute_value', JFile::getName($this->file_name));
				$this->_vm_product_attribute->store();
			}
		}
		
		/* All data should be loaded, bind the data */
		$this->_vm_product_files->bind($this);
		
		/* Check if we already have a file ID */
		$this->_vm_product_files->check();
		
		/* Store the data */
		if ($this->_vm_product_files->store()) {
			$csvilog->AddMessage('debug', JText::_('STORE_PRODUCT_FILE'), true);
			/* Check what query has been done */
			$query = $db->getQuery();
			if (substr($query, 0, 6) == 'UPDATE') $csvilog->AddStats('updated', JText::_('PRODUCT_FILE_HAS_BEEN_UPDATED'));
			else if (substr($query, 0, 6) == 'INSERT') $csvilog->AddStats('added', JText::_('PRODUCT_FILE_HAS_BEEN_ADDED'));
			
		}
		else {
			if (substr($query, 0, 6) == 'UPDATE') $csvilog->AddStats('incorrect', JText::_('COULD_NOT_UPDATE_PRODUCT_FILE'));
			else if (substr($query, 0, 6) == 'INSERT') $csvilog->AddStats('incorrect', JText::_('COULD_NOT_ADD_PRODUCT_FILE'));
		}
		
		/* Clean the tables */
		$this->getCleanTables();
	}
	
	/**
	 * Get the products filename
	 *
	 * @see $file_exists
	 */
	private function ProductFilesFileName() {
		/* Load some settings */
		$csvilog = JRequest::getVar('csvilog');
		$template = JRequest::getVar('template');
		
		/* Validate the input */
		$this->file_name = $this->_importmodel->ValidateCSVInput("product_files_file_name");
		
		/* Process the image file */
		if ($template->thumb_create) {
			$file_details = $this->_importmodel->ProcessImage($this->file_name, $this->file_name, $template->file_location_product_images);
		}
		else {
			$file_details = $this->_importmodel->FileDetails($this->file_name, $this->file_name, $template->file_location_product_images);
		}
		
		if ($file_details) {
			$find = array(JPATH_SITE, '\\');
			$replace = array('', '/');
			$this->file_exists = true;
			$this->file_name = str_replace($find, $replace, $template->file_location_product_images.'/'.$file_details['clean_file_name_original']);
			$this->file_extension = $file_details['file_extension'];
			$this->file_mimetype = $file_details['file_mimetype'];
			$this->file_is_image = $file_details['file_is_image'];
			if (array_key_exists('file_thumb_name', $file_details)) {
				$this->file_image_thumb_width = $file_details['file_thumb_width'];
				$this->file_image_thumb_height = $file_details['file_thumb_height'];
			}
		}
		else {
			$this->file_exists = false;
		}
	}
	
	/**
	 * Get the file title of the product file
	 */
	private function ProductFilesFileTitle() {
		$this->file_title = $this->_importmodel->ValidateCSVInput("product_files_file_title");
	}
	
	/**
	 * Get the file description of the product file
	 */
	private function ProductFilesFileDescription() {
		$this->file_description = $this->_importmodel->ValidateCSVInput("product_files_file_description");
	}
	
	/**
	 * Get the file URL of the product file
	 */
	private function ProductFilesFileUrl() {
		if (empty($this->file_url)) {
			$csvilog = JRequest::getVar('csvilog');
			$this->file_url = $this->_importmodel->ValidateCSVInput("product_files_file_url");
			$csvilog->AddMessage('debug', JText::_('DEBUG_FILE_URL_FROM').' '.$this->file_name);
			$this->file_url = JURI::root().$this->file_name;
		}
	}
	
	/**
	 * Get the published state of the product file
	 */
	private function ProductFilesFilePublished() {
		$this->file_published = $this->_importmodel->ValidateCSVInput("product_files_file_published");
		if (strtoupper($this->file_published) == 'Y') $this->file_published = 1;
		else $this->file_published = 0;
	}
}
?>
