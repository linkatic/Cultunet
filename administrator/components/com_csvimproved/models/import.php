<?php
/**
 * Import model
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: import.php 938 2009-06-23 18:39:36Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Import Model
 *
 * @package CSVImproved
 */
class CsvimprovedModelImport extends JModel {
	
	/** @var array Imported CSV fields */
	private $_csv_fields = null;
	/** @var array Imported data */
	private $_product_data = null;
	/** @var bool Whether or not to apply default values */
	private $_skip_default_value = null;
	/** @var mixed contains the current datafield value */
	private $_datafield = null;
	
	/**
	 * Load some settings we need for the functions
	 */
	private function LoadSettings() {
		/* Load the settings */
		$this->_csv_fields = JRequest::getVar('csv_fields');
		$this->_product_data = JRequest::getVar('csvi_data', '', 'default', 'none', 2);
		$this->_skip_default_value = JRequest::getVar('skip_default_value');
	}
	
	/**
	 * Get the product id, this is necessary for updating existing products
	 *
	 * @todo Reduce number of calls to this function
	 * @return integer product_id is returned
	 */
	public function GetProductId() {
		$this->LoadSettings();
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		if (isset($this->_csv_fields["product_sku"])) {
			$q = "SELECT product_id
				FROM #__vm_product 
				WHERE product_sku = ".$db->Quote($this->_product_data[$this->_csv_fields["product_sku"]["order"]]);
			$db->setQuery($q);
			$csvilog->AddMessage('debug', JText::_('Check to see if the product ID exists'), true);
			return $db->loadResult();
		}
		else return false;
	}
	
	/**
	 * Get the product type ID, cannot do without it
	 * 
	 * The product_type_id is not auto incremental, therefore it needs to be
	 * set manually
	 */
	public function GetProductTypeId() {
		$this->LoadSettings();
		$db = JFactory::getDBO();
		
		if (!isset($this->_csv_fields["product_type_id"]) && isset($this->_csv_fields["product_type_name"])) {
			$q = "SELECT product_type_id
				FROM ".$db->nameQuote('#__vm_product_type')."
				WHERE product_type_name = ".$db->Quote($this->_product_data[$this->_csv_fields["product_type_name"]["order"]]);
			$db->setQuery($q);
			$db->query();
			return $db->loadResult();
		}
		else return false;
	}
	
	/**
	 * Validate CSV input data
	 *
	 * Checks if the field has a value, if not check if the user wants us to
	 * use the default value
	 *
	 * @param string $fieldname the fieldname to validate
	 * @return true|false returns validated value | return false if the column count does not match
	 */
	public function ValidateCsvInput($fieldname) {
		$this->LoadSettings();
		/* Check if the columns match */
		if (count($this->_csv_fields) != count($this->_product_data)) {
			$find = array('{configfields}', '{filefields}');
			$replace = array(count($this->_csv_fields), count($this->_product_data));
			JError::raiseWarning(0, str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT')));
			return false;
		}
		
		/* Columns match, validate */
		else {
			if (isset($this->_csv_fields[$fieldname])) {
				/* Check if the field has a value */
				if (array_key_exists($this->_csv_fields[$fieldname]["order"], $this->_product_data)
					&& strlen($this->_product_data[$this->_csv_fields[$fieldname]["order"]]) > 0) {
					return trim($this->_product_data[$this->_csv_fields[$fieldname]["order"]]);
				}
				/* Field has no value, check if we can use default value*/
				else if (!$this->_skip_default_value) {
					return $this->_csv_fields[$fieldname]["default_value"];
				}
			}
		}
	}
	
	/**
	 * Determine vendor ID
	 *
	 * Determine for which vendor we are importing product details.
	 *
	 * The default vendor is the one with the lowest vendor_id value
	 *
	 * @todo Add full vendor support
	 */
	public function GetVendorId() {
		$vendor_id = JRequest::getVar('vendor_id', false);
		
		if (!$vendor_id) {
			$db = JFactory::getDBO();
			$csvilog = JRequest::getVar('csvilog');
			
			/* User is uploading vendor_id */
			if (isset($this->_csv_fields["vendor_id"])) {
				$this->_datafield = $this->ValidateCsvInput('vendor_id');
				JRequest::setVar('vendor_id', $this->_datafield);
				return $this->_datafield;
			}
			/* User is not uploading vendor_id */
			/* First get the vendor with the lowest ID */
			$q = "SELECT MIN(vendor_id) AS vendor_id FROM #__vm_vendor";
			$db->setQuery($q);
			$min_vendor_id = $db->loadResult();
			
			if (isset($this->_csv_fields["product_sku"])) {
				$q = "SELECT IF (COUNT(vendor_id) = 0, ".$min_vendor_id.", vendor_id) AS vendor_id
					FROM #__vm_product 
					WHERE product_sku = ".$db->Quote($this->_product_data[$this->_csv_fields["product_sku"]["order"]]);
				$db->setQuery($q);
				
				$csvilog->AddMessage('debug', JText::_('Check to see if the vendor ID exists'), true);
				/* Existing vendor_id */
				$vendor_id = $db->loadResult();
				JRequest::setVar('vendor_id', $vendor_id);
				return $vendor_id;
			}
			/* No product_sku uploaded */
			else {
				JRequest::setVar('vendor_id', $min_vendor_id);
				return $min_vendor_id;
			}
		}
		return $vendor_id;
	}
	
	/**
	 *	Gets the default Shopper Group ID
	 * @todo add error checking
  	 **/
	public function GetDefaultShopperGroupID() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		$vendor_id = $this->GetVendorId();
		
		$q = "SELECT shopper_group_id FROM #__vm_shopper_group ";
		$q .= "WHERE `default`='1' and vendor_id='".$vendor_id."'";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('DEBUG_GET_DEFAULT_SHOPPER_GROUP'), true);
		return $db->loadResult();
	}
	
	/**
	 * Gets the default manufacturer ID
	 * As there is no default manufacturer, we take the first one
  	 **/
	public function GetDefaultManufacturerID() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		$q = "SELECT manufacturer_id FROM #__vm_manufacturer LIMIT 1 ";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('DEBUG_GET_DEFAULT_MANUFACTURER'), true);
		return $db->loadResult();
	}
	
	/**
	 * Get a template ID
	 */
	public function getTemplateId() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$q = "SELECT template_id
			FROM #__csvi_templates
			WHERE template_name = ".$db->Quote($this->_product_data[$this->_csv_fields['template_name']["order"]]);
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('DEBUG_GET_TEMPLATE_ID'), true);
		if ($db->query()) return $db->loadResult();
		else return false;
	}
	
	/**
	 * Create a thumbnail from the image file on import
	 *
	 * A thumbnail will also be created if the thumbnail file already exists
	 *
	 * @param string name of the image to create a thumbnail from (product_full_image)
	 * @see Img2Thumb()
	 * @todo option in template to create a different file extension
	 */
	public function ProcessImage($file_name_original, $file_name_thumb, $file_location) {
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$template = JRequest::getVar('template');
		$csvilog = JRequest::getVar('csvilog');
		$file_details = array();
		
		/* Check if the thumbsize is greater than 0 */
		if ($template->thumb_width > 0 && $template->thumb_height > 0) {
		
			/* Get the file details */
			$file_details = $this->FileDetails($file_name_original, $file_name_thumb, $file_location);
			
			if ($file_details) {
				/* Check if we are dealing with a remote file */
				if (substr($file_name_original, 0, 4) == 'http') $file_remote = true;
				else $file_remote = false;
				
				/* Check if the file is present */
				if ($template->collect_debug_info) {
					$csvilog->AddMessage('debug', JText::_('DEBUG_FILE_PATH').' '.$file_details['file_path']);
					$csvilog->AddMessage('debug', JText::_('DEBUG_FILE_NAME').' '.$file_details['file_name']);
					$csvilog->AddMessage('debug', JText::_('DEBUG_CHECK_FILE_EXISTS').' '.$file_details['file_path'].DS.$file_details['file_name']);
				}
			
				/* Set the value the file exists */
				$file_exists = true;
				
				/* Let's see if we need to make a thumbnail */
				if ($file_details['file_is_image']) {
					/* Resize the image */
					if (strtolower($template->thumb_extension) != 'none') $file_out_extension = $template->thumb_extension;
					else $file_out_extension = $file_details['file_extension'];
					$thumb_file_name = basename($file_details['clean_file_name_thumb'], ".".$file_details['file_extension']).".".$file_out_extension;
					$thumb_file_details = array();
					$thumb_file_details['file'] = $file_details['file_path'].'/'.$file_details['file_name'];
					$thumb_file_details['file_extension'] = $file_details['file_extension'];
					$find_delim = array("/", "\\");
					if ($template->template_type != 'productfilesimport') { 
						$thumb_file_details['file_out'] = str_replace($find_delim, '/', $file_location).'/'.dirname($file_details['clean_file_name_thumb']).'/'.$thumb_file_name;
					}
					else {
						$thumb_file_details['file_out'] = str_replace($find_delim, '/', $file_location).'/'.dirname($file_details['clean_file_name_thumb']).'/resized/'.$thumb_file_name;
					}
					$thumb_file_details['maxsize'] = 0;
					$thumb_file_details['bgred'] = 255;
					$thumb_file_details['bggreen'] = 255;
					$thumb_file_details['bgblue'] = 255;
					$thumb_file_details['file_out_width'] = $template->thumb_width;
					$thumb_file_details['file_out_height'] = $template->thumb_height;
					$thumb_file_details['file_out_extension'] = $file_out_extension;
					
					/* We need to resize the image and Save the new one (all done in the constructor) */
					if ($template->collect_debug_info) $csvilog->AddMessage('debug', JText::_('Creating a thumbnail').' '.$thumb_file_details['file_out']);
					new ImageConverter($thumb_file_details);
					
					/* Get the details of the thumb image */
					if (JFile::exists($thumb_file_details['file_out'])) {
						if ($template->collect_debug_info) $csvilog->AddMessage('debug', JText::_('Thumbnail has been created'));
						$file_details['file_thumb_name'] = 'resized'.'/'.$thumb_file_name;
						$file_details_thumb = getimagesize($thumb_file_details['file_out']);
						if ($file_details_thumb) {
							$image_thumb_width = $file_details_thumb[0];
							$image_thumb_height = $file_details_thumb[1];
						}
						else {
							$image_thumb_width = 0;
							$image_thumb_height = 0;
						}
						
						/* Add to the array */
						$file_details['file_thumb_width'] = $image_thumb_width;
						$file_details['file_thumb_height'] = $image_thumb_height;
					}
					else {
						if ($template->collect_debug_info) $csvilog->AddMessage('debug', JText::_('Thumbnail could not be created'));
						return false;
					}
					
					/* Remove the downloaded file */
					if ($file_remote) JFile::delete($file_details['file_path'].DS.$file_details['file_name']);
				}
				else {
					$csvilog->AddMessage('debug', JText::_('DEBUG_FILE_IS_NOT_IMAGE'));
				}
				/* Return an array with file details */
				return $file_details;
			}
			else {
				if ($template->collect_debug_info) $csvilog->AddMessage('debug', JText::_('File does not exist. Nothing to do.').' '.$file_name_original);
				$csvilog->AddStats('nofiles', JText::_('File does not exist. Nothing to do.').' '.$file_name_original);
				return false;
			}
		}
		else {
			if ($template->collect_debug_info) $csvilog->AddMessage('debug', JText::_('Thumbnail size too small.'));
			$csvilog->AddStats('incorrect', JText::_('Thumbnail size too small.'));
			return false;
		}
	}
	
	/**
	 * Collect information about a file
	 */
	public function FileDetails($file_name_original, $file_name_thumb, $file_location) {
		$template = JRequest::getVar('template');
		$csvilog = JRequest::getVar('csvilog');
		
		/* Get the image handling functions */
		$mime_type_detect = new MimeTypeDetect();
		
	 	 /* Remove the base of the filename for VirtueMart */
		$file_name_original = str_replace(JPATH_SITE, '', $file_name_original);
		$file_name_thumb = str_replace(JPATH_SITE, '', $file_name_thumb);
		
		/* Set all slashes in the same direction */
		$find_delim = array("/", "\\");
		$clean_file_name_original = str_replace($find_delim, '/', $file_name_original);
		$clean_file_name_thumb = str_replace($find_delim, '/', $file_name_thumb);
		
		/* Get the file details */
		$file_name = str_ireplace($file_location, '', $clean_file_name_original);
		
		if (substr($file_name_original, 0, 4) == 'http') {
			$file_path = JPATH_CACHE.DS.'csvi';
			/* Get the file first */
			if ($template->collect_debug_info) {
			$csvilog->AddMessage('debug', JText::_('Retrieving external file:').' '.$file_name_original.'<br />'.JText::_('Saving remote file to local file:').' '.$file_path.DS.$file_name);
			}
			JFile::write($file_path.DS.$file_name, JFile::read($file_name_original));
		}
		else {
			if (strstr($clean_file_name_original, DS)) $dirname = '/'.dirname($clean_file_name_original);
			else $dirname = '';
			$file_path = str_replace($find_delim, '/', $file_location).$dirname;
		}
		/* Get the details */
		if (JFile::exists($file_path.DS.$file_name)) {
			$file_extension = JFile::getExt($file_name);
			
			/* Get the mime type*/
			$file_mimetype = $mime_type_detect->FindMimeType($file_path.DS.$file_name);
			
			/* Check if file is image */
			$file_is_image = $mime_type_detect->CheckImage($file_mimetype);
			
			if ($file_is_image) {
				list($width, $height) = getimagesize($file_path.DS.$file_name);
				
				/* Calculate thumbnail sizes */
				$maxX = $template->thumb_width;
				$maxY = $template->thumb_height;
				$file_out_width = $template->thumb_width;
				$file_out_height = $template->thumb_height;
				if ($width < $height) {
					$file_out_width = $file_out_height * ($width/$height);
				}
				else {
					$file_out_height = $file_out_width / ($width/$height);
				}
				while ($file_out_width < 1 || $file_out_height < 1) {
					$file_out_width *= 2;
					$file_out_height *= 2;
				}
				
			}
			else {
				$width = 0;
				$height = 0;
				$file_out_width = 0;
				$file_out_height = 0;
			}
			$file_details['file_name'] = $file_name;
			$file_details['file_extension'] = $file_extension;
			$file_details['file_mimetype'] = $file_mimetype;
			$file_details['file_is_image'] = $file_is_image;
			$file_details['file_image_height'] = $height;
			$file_details['file_image_width'] = $width;
			$file_details['file_path'] = $file_path;
			$file_details['clean_file_name_original'] = $clean_file_name_original;
			$file_details['clean_file_name_thumb'] = $clean_file_name_thumb;
			$file_details['file_thumb_width'] = $file_out_width;
			$file_details['file_thumb_height'] = $file_out_height;
			
			return $file_details;
		}
		else return false;
	}
	
	
	/**
	 * Get the shopper group id
	 *
	 * Only get the shopper group id when the shopper_group_name is set
	 *
	 * @see $shopper_group_name
	 */
	public function ShopperGroupName($shopper_group_name) {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$q = "SELECT shopper_group_id 
			FROM #__vm_shopper_group 
			WHERE shopper_group_name = ".$db->Quote($shopper_group_name);
		$db->setQuery($q);
		$shopper_group_id = $db->loadResult();
		$csvilog->AddMessage('debug', JText::_('DEBUG_SHOPPER_GROUP_NAME'), true);
		return $shopper_group_id;
	}
	
	/**
	 * Get the product price
	 *
	 * Replace commas with periods for correct DB insertion of the prices
	 */
	public function ToPeriod($value) {
		return str_replace(",", ".", $value);
	}
}
?>