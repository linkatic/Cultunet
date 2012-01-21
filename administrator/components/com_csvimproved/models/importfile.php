<?php
/**
 * Import file model
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: importfile.php 956 2009-08-16 00:41:22Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Import file Model
 *
 * @package CSVImproved
 */
class CsvimprovedModelImportfile extends JModel {
	
	/** @var integer for keeping track when the script started */
	private $_starttime = 0;
	
	/**
	 * Sets the system limits to user defined values
	 *
	 * Sets the system limits to user defined values to allow for longer and
	 * bigger uploaded files
	 */
	public function getSystemLimits() {
		/* Set the start time of the script */
		$this->_starttime = time();
		
		/* Get the logger */
		$csvilog = JRequest::getVar('csvilog');
		$template = JRequest::getVar('template');
		
		/* Set the user generated limits */
		$csvilog->AddMessage('debug', '<hr />');
		
		/* See if we need to use th new limits */
		if ($template->use_system_limits) {
			$csvilog->AddMessage('debug', 'Setting system limits:');
			/* Apply the new memory limits */
			$csvilog->AddMessage('debug', 'Setting max_execution_time to '.$template->max_execution_time.' seconds');
			@ini_set('max_execution_time', $template->max_execution_time);
			$csvilog->AddMessage('debug', 'Setting max_input_time to '.$template->max_input_time.' seconds');
			@ini_set('max_input_time', $template->max_input_time);
			$csvilog->AddMessage('debug', 'Setting memory_limit to '.$template->memory_limit.'M');
			@ini_set('memory_limit', $template->memory_limit.'M');
			$csvilog->AddMessage('debug', 'Setting post_max_size to '.$template->post_max_size.'M');
			@ini_set('post_max_size', $template->post_max_size.'M');
			$csvilog->AddMessage('debug', 'Setting upload_max_filesize to '.$template->upload_max_filesize.'M');
			@ini_set('upload_max_filesize', $template->upload_max_filesize.'M');
		}
	}
	
	/** 
	 * Function to check if execution time is going to be passed. 
	 *
	 * Memory can only be checked if the function memory_get_usage is available.
	 * If the function is not available always return true. This could lead to
	 * out of memory failure.
	 *
	 * @todo Add memory_get_usage check to system check page
	 * @see http://www.php.net/memory_get_usage
	 * @param &$csviregistry Global register
	 * @return bool true|false true when limits are not reached|false when limit is reached
	 */
	public function getCheckLimits() {
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check for maximum execution time */
		$timepassed = time() - $this->_starttime;
		
		if (($timepassed + 5) > ini_get('max_execution_time') && ini_get('max_execution_time') > 0) {
			$csvilog->AddStats('information', JText::_('MAXIMUM_EXECUTION_LIMIT_EXCEEDED').$timepassed.JText::_('seconds'));
			return false;
		}
		
		/* Check for maximum memory usage */
		if (!function_exists('memory_get_usage')) return true;
		else {
			/* Get the size of the statistics */
			$statslength = 0;
			if (isset($csvilog->stats)) {
				foreach ($csvilog->stats as $type => $value) {
					if (isset($value['message'])) $statslength += strlen($value['message']);
				}
			}
			$statslength = round($statslength/(1024*1024));
			
			/* Get the size of the debug message */
			$debuglength = round(strlen($csvilog->debug_message)/(1024*1024));
			
			/* Get the size of the active memory in use*/
			$activelength = round(memory_get_usage()/(1024*1024));
			
			/* Combine memory limits*/
			$totalmem = $activelength + $statslength + $debuglength;
			
			/* Set the memory limit */
			JRequest::setVar('maxmem', $totalmem);
			
			/* Check if we are passing the memory limit */
			if (($totalmem * 1.5) > (int)ini_get('memory_limit')) {
				$csvilog->AddStats('information', JText::_('MAXIMUM_MEMORY_LIMIT_EXCEEDED').' '.$totalmem.JText::_('MB'));
				return false;
			}
			
			 /* All is good return true */
			return true;
		}
	}
	
	/**
	*	Check user options for import
  	**/
  	public function getValidateImportChoices() {
		
		/* Get the template settings to see if we need a preview */
		$template = JRequest::getVar('template');
		
		/* Set debug on or off */
		JRequest::setVar('debug', $template->collect_debug_info);
		
		/* Overwrite existing data */
		$this->overwrite_existing_data = $template->overwrite_existing_data;
		
		/* Default value */
		if ($template->skip_default_value) JRequest::setVar('skip_default_value', true);
		else JRequest::setVar('skip_default_value', false);
		
		/* Skip first line */
		if ($template->skip_first_line) JRequest::setVar('currentline', JRequest::getVar('currentline')+1);
	}
	
	/**
	 * Print out import details
	 */
	function getImportDetails() {
		/* Get the logger */
		$csvilog = JRequest::getVar('csvilog');
		/* Get the template settings to see if we need a preview */
		$template = JRequest::getVar('template');
		
		$csvilog->AddMessage('debug', '<hr />');
		$csvilog->AddMessage('debug', JText::_('CSVI_VERSION_TEXT').JText::_('CSVI_VERSION'));
		if (function_exists('phpversion')) $csvilog->AddMessage('debug', 'PHP Version: '.phpversion());
		/* Show which template is used */
		$csvilog->AddMessage('debug', 'Using template: '.$template->template_name);
		
		/* Check delimiter char */
		$csvilog->AddMessage('debug', 'Using delimiter: '.$template->field_delimiter);
		
		/* Check enclosure char */
		$csvilog->AddMessage('debug', 'Using enclosure: '.$template->text_enclosure);
		
		/* Skip first line */
		if ($template->skip_first_line) $csvilog->AddMessage('debug', 'Skipping the first line');
		else $csvilog->AddMessage('debug', 'Not skipping the first line');
		
		/* Skip default value */
		if ($template->skip_default_value) $csvilog->AddMessage('debug', 'Skip default value');
		else $csvilog->AddMessage('debug', 'Not skipping default value');
		
		/* Overwrite existing data */
		if ($template->overwrite_existing_data) $csvilog->AddMessage('debug', 'Overwriting data');
		else $csvilog->AddMessage('debug', 'Do not overwrite data');
		
		/* Use column headers as configuration */
		if ($template->use_column_headers) $csvilog->AddMessage('debug', 'Use column headers for configuration');
		else $csvilog->AddMessage('debug', 'Do not use column headers for configuration');
		
		/* Show preview */
		if ($template->show_preview && (!JRequest::getVar('was_preview', false))) {
			$csvilog->AddMessage('debug', 'Using preview');
		}
		else {
			if (JRequest::getVar('was_preview', false)) {
				$csvilog->AddMessage('debug', 'Preview used');
			}
			else $csvilog->AddMessage('debug', 'Not using preview');
		}
		
		/* Show the file path */
		$csvilog->AddMessage('debug', JText::_('DEBUG_FILE_PATH').' '.$template->file_location_product_images);
	}
	
	/**
	 * Get the configuration fields the user wants to use
	 *
	 * The configuration fields can be taken from the uploaded file or from 
	 * the database. Depending on the template settings
	 *
	 * @todo Get supported fields from producttypenamesupload from product type database
	 * @see Templates()
	 * @return bool true|false true when there are config fields|false when there are no or unsupported fields
	 */
	function getRetrieveConfigFields() {
		
		$template = JRequest::getVar('template');
		$csvilog = JRequest::getVar('csvilog');
		$supportedfields = array_flip(JRequest::getVar('supported_fields'));
		
		$csvi_data = JRequest::getVar('csvi_data');
		$db = JFactory::getDBO();
		$csv_fields = array();
		
		if ($template->use_column_headers) {
			/* The user has column headers in the file */
			JRequest::setVar('error_found', false);
			foreach ($csvi_data as $order => $name) {
				/* Trim the name in case the name contains any preceding or trailing spaces */
				$name = strtolower(trim($name));
				/* Check if the fieldname is supported */
				/* No special field checking for Product Type Names upload */
				if (array_key_exists($name, $supportedfields) || strtolower($template->template_type) == "producttypenamesimport") {
					$csvilog->AddMessage('debug', 'Field: '.$name);
					$csv_fields[$name]["name"] = $name;
					$csv_fields[$name]["order"] = $order;
					/* Get the default value for the fields */
					$q = "SELECT field_default_value 
						FROM #__csvi_template_fields
						WHERE field_name = ".$db->Quote($name)."
						AND field_template_id = '".JRequest::getVar('template_id')."'";
					$db->setQuery($q);
					$csv_fields[$name]["default_value"] = $db->loadResult();
					$csv_fields[$name]["published"] = 1;
				}
				else {
					/* Check if the user has any field that is not supported */
					if (strlen($name) == 0) $name = JText::_('FIELD_EMPTY');
					/* See if we can find the delimiters ourselves */
					$text_delimiter = '';
					$field_delimiter = '';
					
					/* 1. Is the user using text enclosures */
					$first_char = substr($name, 0, 1);
					$pattern = '/[a-zA-Z_]/';
					$matches = array();
					preg_match($pattern, $first_char, $matches);
					if (count($matches) == 0) {
						/* User is using text delimiter */
						$text_delimiter = $first_char;
						
						/* 2. What field delimiter is being used */
						$match_next_char = strpos($name, $text_delimiter, 1);
						$field_delimiter = substr($name, $match_next_char+1, 1);
					}
					else {
						$totalchars = strlen($name);
						/* 2. What field delimiter is being used */
						for ($i = 0;$i <= $totalchars; $i++) {
							$current_char = substr($name, $i, 1);
							preg_match($pattern, $current_char, $matches);
							if (count($matches) == 0) {
								$field_delimiter = $current_char;
								$i = $totalchars;
							}
						}
					}
					$csvilog->AddStats('nosupport', $name.'<br />'.JText::_('FOUND_FIELD_DELIMITER').': '.$field_delimiter.'<br />'.JText::_('FOUND_TEXT_ENCLOSURE').': '.$text_delimiter.'<br />'.JText::_('Do they match your template settings?'));
				}
			}
			if (isset($csvilog->stats['nosupport']) && $csvilog->stats['nosupport']['count'] > 0) {
				$csvilog->AddMessage('info', JText::_('UNSUPPORTED_FIELDS').' '.$csvilog->stats['nosupport']['message']);
				JRequest::setVar('error_found', true);
				return false;
			}
			
			$csvilog->AddMessage('debug', 'Using file for configuration');
		}
		else {
			/* The user has column headers in the database */
			/* Get row positions of each element as set in csv table */
			$q = "SELECT id,field_name,field_order,field_column_header,field_default_value, published 
				FROM #__csvi_template_fields 
				WHERE field_template_id = '".$template->template_id."'
				ORDER BY field_order";
			$db->setQuery($q);
			$rows = $db->loadAssocList();
			foreach ($rows as $id => $row) {
				if (!empty($row["field_column_header"])) $field_name = $row["field_column_header"];
				else $field_name = $row["field_name"];
				$csvilog->AddMessage('debug', 'Field: '.$field_name);
				$csv_fields[$field_name]["name"] = $field_name;
				$csv_fields[$field_name]["order"] = $row["field_order"];
				$csv_fields[$field_name]["default_value"] = $row["field_default_value"];
				$csv_fields[$field_name]["published"] = $row["published"];
			}
			$csvilog->AddMessage('debug', 'Use database for configuration');
		}
		/* Check if user is uploading related products */
		if (array_key_exists('related_products', $csv_fields)) {
			$this->RelatedProductsTempTable();
		}
		
		JRequest::setVar('csv_fields', $csv_fields);
		return true;
	}
	
	/**
	 * Check user options for import
	 * 
	 * @see csv_category()
  	 **/
  	function CheckCategoryPath() {
		$dbcat = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		if (($this->product_details->category_path && !$this->product_details->child_product) || $this->product_details->category_id) {
			$csvilog->AddMessage('debug', JTEXT::_('DEBUG_CHECKING_CATEGORY_PATH'));
			/* If product_parent_id is true, we have a child product, child products do not have category paths */
			/* if we have a category_id, no need to find the path */
			if (!$this->product_details->product_parent_id && !$this->product_details->category_id) {
				/* Use csv_category() method to confirm/add category tree for this product */
				/* Modification: $category_id now is an array */
				/* if (is_int($this->category_path)) $category */
				$this->product_details->category_id = $this->csv_category();
			}
			if (!$this->product_details->product_parent_id || $this->product_details->category_id) {
				/* Delete old entries */
				$q  = "DELETE FROM #__vm_product_category_xref WHERE product_id = '".$this->product_details->product_id."'";
				$csvilog->AddMessage('debug', JText::_('DEBUG_DELETE_OLD_CATEGORY_REFERENCES'), true );
				$dbcat->query($q);
				
				/* Insert new product/category relationships */
				foreach( $this->product_details->category_id as $value ) {
					$q  = "INSERT INTO #__vm_product_category_xref (category_id, product_id) VALUES (";
					$q .= "'$value', '".$this->product_details->product_id."')";
					$csvilog->AddMessage('debug', JText::_('DEBUG_ADD_NEW_CATEGORY_REFERENCES'), true);
					if (!$dbcat->query($q)) $csvilog->AddMessage('debug', JText::_('DEBUG_ERROR_SAVING_CATEGORY_REFERENCE'), true);
				}
			}
		}
		else {
			$csvilog->AddMessage('debug', 'Child product, not doing any category handling');
		}
	}
	
	/**
	 * name: csv_category()
	 * created by: John Syben
	 * Creates categories from slash delimited line
	 **/
	function csv_category() {
		$csvilog = JRequest::getVar('csvilog');
		
		/* New: Get all categories in this field, delimited with | */
		$categories = explode("|", $this->product_details->category_path);
		foreach( $categories as $line ) {
			/* Explode slash delimited category tree into array */
			$category_list = explode("/", $line);
			$category_count = count($category_list);

			$db = JFactory::getDBO();
			$category_parent_id = '0';

			/* For each category in array */
			for($i = 0; $i < $category_count; $i++) {
				/* See if this category exists with it's parent in xref */
				$q = "SELECT #__vm_category.category_id FROM #__vm_category,#__vm_category_xref ";
				$q .= "WHERE #__vm_category.category_name='" . $db->getEscaped($category_list[$i]) . "' ";
				$q .= "AND #__vm_category_xref.category_child_id=#__vm_category.category_id ";
				$q .= "AND #__vm_category_xref.category_parent_id='$category_parent_id'";
				$db->setQuery($q);
				/* If it does not exist, create it */
				/* Category exists */
				if ($db->getAffectedRows() > 0) {
					$category_id = $db->loadResult();
				}
				/* Category does not exist - create it */
				else { 
					$timestamp = time();
					
					/* Let's find out the last category in */
					/* the level of the new category */
					$q = "SELECT MAX(list_order) AS list_order FROM #__vm_category_xref,#__vm_category ";
					$q .= "WHERE category_parent_id='".$category_parent_id."' ";
					$q .= "AND category_child_id=category_id ";
					$db->setQuery( $q );
					
					$list_order = intval($db->loadResult())+1;
					
					/* Add category */
					$q = "INSERT INTO #__vm_category ";
					$q .= "(vendor_id,category_name, category_publish,cdate,mdate,list_order) ";
					$q .= "VALUES ('1', '";
					$q .= $db->getEscaped($category_list[$i]) . "', '";
					$q .= "Y', '";
					$q .= $timestamp . "', '";
					$q .= $timestamp . "', '$list_order')";
					$csvilog->AddMessage('debug', JTEXT::_('DEBUG_ADD NEW CATEGORY'), true);
					$db->setQuery($q);
					if (!$db->query()) $csvilog->AddMessage('debug', JTEXT::_('DEBUG_ERROR_STORING_NEW_CATEGORY'), true);

					$category_id = $db->insertid();

					/* Create xref with parent */
					$q = "INSERT INTO #__vm_category_xref ";
					$q .= "(category_parent_id, category_child_id) ";
					$q .= "VALUES ('";
					$q .= $category_parent_id . "', '";
					$q .= $category_id . "')";
					$csvilog->AddMessage('debug', JTEXT::_('DEBUG_ADD_NEW_CATEGORY_XREF'), true);
					$db->setQuery($q);
					if (!$db->query()) $csvilog->AddMessage('debug', JTEXT::_('DEBUG_ERROR_ADD_NEW_CATEGORY_XREF'), true);
				}
				/* Set this category as parent of next in line */
				$category_parent_id = $category_id;
			} /* end for */
			$category[] = $category_id;
		}
		/* Return an array with the last category_ids which is where the product goes */
		return $category;
	} /* End function csv_category */
	
	
	/**
	 * Creates a temporary table to hold SKUs to relate
	 */
	private function RelatedProductsTempTable() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$q = "CREATE TEMPORARY TABLE csvi_import_related_products (
				product_sku VARCHAR(64) NOT NULL,
				related_sku TEXT NOT NULL
			)";
		$db->setQuery($q);
		if (!$db->query()) {
			$csvilog->AddMessage('debug', JText::_('DEBUG_TEMP_TABLE_RELATED_PRODUCTS'), true);
		}
	}
	
	/**
	 * Drop the temporary table
	 */
	public function getDropRelatedProductsTempTable() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$db->setQuery('DROP TABLE IF EXISTS '.$db->nameQuote('csvi_import_related_products'));
		if (!$db->query()) {
			$csvilog->AddMessage('debug', JText::_('DEBUG_DROP_TEMP_TABLE_RELATED_PRODUCTS_NOK'), true);
		}
		else $csvilog->AddMessage('debug', JText::_('DEBUG_DROP_TEMP_TABLE_RELATED_PRODUCTS_OK'), true);
		JRequest::setVar('droprelated', true);
	}
}
?>
