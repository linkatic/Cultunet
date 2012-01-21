<?php
/**
 * Export File model
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: exportfile.php 946 2009-08-03 09:47:37Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Export File Model
 *
 * @package CSVImproved
 */
class CsvimprovedModelExportfile extends JModel {
	
	/**
	 * Retrieve the VirtueMart item ID for this site
	 *
	 * The Item ID is being used in the product url to link correctly to the products
	 *
	 * @todo Add vmitemdid from template settings
	 */
	function getVmItemId() {
		$db = JFactory::getDBO();
		$template = JRequest::getVar('template');
		
		$q = "SELECT id FROM #__menu WHERE link='index.php?option=com_virtuemart' AND published=1 AND access=0 LIMIT 1";
		$db->setQuery($q);
		$result = $db->loadObjectList();
		if (count($result) > 0) return $db->loadResult();
		else return '1';
		// else return $template->vmitemid;
	}
	
	/**
	 * Get the export filename
	 *
	 * @return string Returns the filename of the exported file
	 */
	public function getExportFilename() {
		$template = JRequest::getVar('template');
		$exportto = JRequest::getVar('exportto');
		
		/* Check if the export is limited, if so add it to the filename */
		/* Check if both values are greater than 0 */
		if ((JRequest::getInt('recordstart') > 0) && (JRequest::getInt('recordend') > 0)) {
			/* We have valid limiters, add the limit to the filename */
			$filelimit = "_".JRequest::getInt('recordend').'_'.((JRequest::getInt('recordend')-1)+JRequest::getInt('recordstart'));
		}
		else $filelimit = '';
		
		/**
		 * Set the filename to use for export
		 *
		 * If an export filename is set in the template, this will override
		 * any other settting.
		 */
		/* Use the name from the template */
		if (!empty($template->export_filename)) {
			/* Use the file location from the template if saving to server */
			if (!empty($template->file_location_export_files) && ($exportto == 'tofile')) {
				$localfile = $template->file_location_export_files.DS.$template->export_filename;
			}
			/* Use the file location from the export page if saving the file to server but there is no location in the template */
			else if ($exportto == 'tofile') $localfile = JRequest::getVar('localfile').DS.$template->export_filename;
			/* Only use filename when downloading the file */
			else $localfile = $template->export_filename;
		}
		else if ($exportto == 'tofile' && JRequest::getVar('localfile', false)) {
			$localfile = str_replace("\\", DS, JRequest::getVar('localfile')).DS.
							"CSVI_Export_".$template->template_type.'_'.date("j-m-Y_H.i").
							$filelimit.".".$template->export_type;
		}
		else {
			$localfile = '';
			/* Check if there is a path we can use */
			if (!empty($template->file_location_export_files) && ($exportto == 'tofile')) {
				$localfile .= $template->file_location_export_files.DS;
			}
			$localfile .= "CSVI_Export_".$template->template_name.'_'.date("j-m-Y_H.i").$filelimit.".".$template->export_type;
		}
		
		/* Return the filename */
		return $localfile;
	}
	
	/**
	 * Get the fiels to use for the export
	 *
	 * @return array Returns an array of required fields and default field values
	 */
	public function getExportFields() {
		$db = JFactory::getDBO();
		$template = JRequest::getVar('template');
		
		/* Get the field configuration */
		/* Get row positions of each element as set in csv table */
		$q = "SELECT * FROM #__csvi_template_fields
			WHERE field_template_id = ".$template->template_id."
			AND published = 1
			ORDER BY field_order";
		$db->setQuery($q);
		$rows = $db->loadObjectList();
		
		$required_fields = array();
		$default_values = array();
		foreach ($rows as $id => $row) {
			/* Collect the required fields */
			if ($template->export_type == 'xml') {
				if (!empty($row->field_column_header)) {
					$required_fields[$row->id]->column_header = str_replace(" ", "", $row->field_column_header);
					$required_fields[$row->id]->field_name = $row->field_name;
				}
				else {
					$required_fields[$row->id]->column_header = str_replace(" ", "", $row->field_name);
					$required_fields[$row->id]->field_name = $row->field_name;
				}
			}
			else {
				if (!empty($row->field_column_header)) {
					$required_fields[$row->id]->column_header = $row->field_column_header;
					$required_fields[$row->id]->field_name = $row->field_name;
				}
				else {
					$required_fields[$row->id]->column_header = $row->field_name;
					$required_fields[$row->id]->field_name = $row->field_name;
				}
			}
			
			/* Collect the default values */
			$required_fields[$row->id]->default_value = $row->field_default_value;
		}
		
		/* Return the required and default values */
		return $required_fields;
	}
	
	/**
	 * Search through the export fields if a certain field is being exported
	 */
	public function searchExportFields($fieldname, $export_fields) {
	 	foreach ($export_fields as $column_id => $field) {
	 		if ($field->field_name == $fieldname) return true;
	 	}
	 	return false;
	}
	
	/**
	 * Adds a limit to a query
	 *
	 * @return string|NULL the limit statement is returned if there is a record start and end else nothing is retrned
	 */
	public function ExportLimit() {
		$recordstart = JRequest::getVar('recordstart', false);
		$recordend = JRequest::getVar('recordend', false);
		/* Check if the user only wants to export some products */
		if ($recordstart && $recordend) {
			/* Check if both values are greater than 0 */
			if (($recordstart > 0) && ($recordend > 0)) {
				/* We have valid limiters, add the limit to the query */
				/* Recordend needs to have 1 deducted because MySQL starts from 0 */
				return ' LIMIT '.($recordend-1).','.$recordstart;
			}
		}
	}
	
	/**
	 * Remove trailing 0
	 *
	 * @return int returns a product price without trailing 0
	 */
	public function ProductPrice($product_price) {
		if ($product_price) {
			list($number, $decimals) = split('.', $product_price);
			if (strlen($decimals) > 2) {
				for ($i=1;$i<4;$i++) {
					if (substr($decimals, -1) == 0) $decimals = substr($decimals, 0, -1);
					else $i = 4;
				}
			}
			$pproduct_price = $number.'.'.$decimals;
		}
		return $product_price;
	}
	
	/**
	 * Get the flypage for a product
	 *
	 * @return string returns the name of the flypage
	 */
	public function GetFlypage($product_id) {
		$db = JFactory::getDBO();
		$q =  "SELECT `#__vm_category`.`category_flypage`
			FROM `#__vm_product`
			LEFT JOIN `#__vm_product_category_xref` 
			ON `#__vm_product_category_xref`.`product_id` = `#__vm_product`.`product_id`
			LEFT JOIN `#__vm_category` 
			ON `#__vm_product_category_xref`.`category_id` = `#__vm_category`.`category_id`
			WHERE `#__vm_product`.`product_id`='".$product_id."'";
		$db->setQuery($q);
		$flypage = $db->loadResult();
		if (is_null($flypage)) {
			/* There is no flypage found let's use the VirtueMart config settings */
			$configfile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart.cfg.php';
			$configlines = file($configfile);
			foreach ($configlines as $key => $line) {
				/* Check for flypage */
				if (strstr($line, "'FLYPAGE'")) {
					$lineparts = explode(',', $line);
					/* Strip off unnessecary characters */
					$flypage = JFilterInput::clean($lineparts[1], 'cmd');
					return $flypage;
				}
			}
			return 'shop.flypage.tpl';
		}
		else return $flypage;
	}
	
	/**
	 * Get the category ID for a product
	 *
	 * @return int returns the category ID
	 */
	public function GetCategoryId($product_id) {
		$db = JFactory::getDBO();
		$q = "SELECT category_id FROM #__vm_product_category_xref 
			WHERE product_id = '".$product_id."' LIMIT 1";
		$db->setQuery($q);
		return $db->loadResult();
	}
	
	/**
	 * Add a field to the output
	 *
	 * @param $data string Data to output
	 * @param $fieldname string Name of the field currently being processed
	 * @param $column_header string Name of the column
	 * @param $cdata boolean true to add cdata tag for XML|false not to add it
	 * @return string containing the field for the export file
	 */
	function AddExportField($data, $fieldname, $column_header, $cdata=false) {
		$template = JRequest::getVar('template');
		$xmlclass = JRequest::getVar('xmlclass');
		
		/* Clean up the data by removing linebreaks */
		$find = array("\r\n", "\r", "\n");
		$replace = array('','','');
		$data = str_ireplace($find, $replace, $data);
		
		if ($template->export_type == 'xml') {
			return $xmlclass->ContentText($data, $column_header, $fieldname, $cdata);
		}
		else {
			$data = str_replace($template->text_enclosure, $template->text_enclosure.$template->text_enclosure, $data); 
			return $template->text_enclosure.$data.$template->text_enclosure.$template->field_delimiter;
		}
	}
	
	/**
	  * Get the slash delimited category path of a product
	  *
	  * @name GetCategoryPath
	  * @author soeren
	  * @param int $product_id
	  * @returns String category_path
	  */
	public function GetCategoryPath($product_id) {
		$db = JFactory::getDBO();

		$q = "SELECT #__vm_product.product_id, #__vm_product.product_parent_id, category_name,#__vm_category_xref.category_parent_id "
		."FROM #__vm_category, #__vm_product, #__vm_product_category_xref,#__vm_category_xref "
		."WHERE #__vm_product.product_id='".$product_id."' "
		."AND #__vm_category_xref.category_child_id=#__vm_category.category_id "
		."AND #__vm_category_xref.category_child_id = #__vm_product_category_xref.category_id "
		."AND #__vm_product.product_id = #__vm_product_category_xref.product_id";
		$db->setQuery($q);
		$rows = $db->loadObjectList();
		$k = 1;
		$category_path = "";

		foreach ($rows as $row) {
			$category_name = Array();

			/** Check for product or item **/
			if ( $row->category_name ) {
				$category_parent_id = $row->category_parent_id;
				$category_name[] = $row->category_name;
			}
			else {
				/** must be an item
              * So let's search for the category path of the
              * parent product **/
				$q = "SELECT product_parent_id FROM #__vm_product WHERE product_id='".$product_id."'";
				$db->setQuery($q);
				$ppi = $db->loadResult();

				$q  = "SELECT #__vm_product.product_id, #__vm_product.product_parent_id, category_name,#__vm_category_xref.category_parent_id "
				."FROM #__vm_category, #__vm_product, #__vm_product_category_xref,#__vm_category_xref "
				."WHERE #__vm_product.product_id='".$ppi."' "
				."AND #__vm_category_xref.category_child_id=#__vm_category.category_id "
				."AND #__vm_category_xref.category_child_id = #__vm_product_category_xref.category_id "
				."AND #__vm_product.product_id = #__vm_product_category_xref.product_id";
				$db->setQuery($q);
				$cat_details = $db->loadObject();
				$category_parent_id = $cat_details->category_parent_id;
				$category_name[] = $cat_details->category_name;
			}
			if( $category_parent_id == "") $category_parent_id = "0";

			while ($category_parent_id != "0") {
				$q = "SELECT category_name, category_parent_id "
				."FROM #__vm_category, #__vm_category_xref "
				."WHERE #__vm_category_xref.category_child_id=#__vm_category.category_id "
				."AND #__vm_category.category_id='".$category_parent_id."'";
				$db->setQuery($q);
				$cat_details = $db->loadObject();
				$category_parent_id = $cat_details->category_parent_id;
				$category_name[] = $cat_details->category_name;
			}
			if ( sizeof( $category_name ) > 1 ) {
				for ($i = sizeof($category_name)-1; $i >= 0; $i--) {
					$category_path .= $category_name[$i];
					if( $i >= 1) $category_path .= "/";
				}
			}
			else
			$category_path .= $category_name[0];

			if( $k++ < sizeof($rows) )
			$category_path .= "|";
		}
		return $category_path;
	}
	
	/**
	 * Creates the category path
	 */
	function CreateCategoryPath() {
		$db = JFactory::getDBO();
		$catpaths = array();
		while (JRequest::getVar('catid') > 0) {
			$q = "SELECT category_parent_id, category_name FROM #__vm_category_xref x, #__vm_category c
				WHERE x.category_child_id = c.category_id
				AND category_child_id = ".JRequest::getVar('catid');
			$db->setQuery($q);
			$path = $db->loadObject();
			$catpaths[] = $path->category_name;
			JRequest::setVar('catid', $path->category_parent_id);
		}
		$catpaths = array_reverse($catpaths);
		$catpath = '';
		foreach ($catpaths as $id => $catname) {
			$catpath .= $catname."/";
		}
		return $catpath = substr($catpath, 0, -1);
	}
	
	public function getOutputStart() {
		$template = JRequest::getVar('template');
		$csvilog = JRequest::getVar('csvilog');
		$exportfilename = JRequest::getVar('exportfilename');
		
		switch(JRequest::getVar('exportto')) {
			case 'todownload':
				if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
					$UserBrowser = "Opera";
				}
				elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
					$UserBrowser = "IE";
				} else {
					$UserBrowser = '';
				}
				$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

				/* Clean the buffer */
				while( @ob_end_clean() );
		
				header('Content-Type: ' . $mime_type);
				header('Content-Encoding: UTF-8');
				header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		
				if ($UserBrowser == 'IE') {
					header('Content-Disposition: inline; filename="'.$exportfilename.'"');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
				} else {
					header('Content-Disposition: attachment; filename="'.$exportfilename.'"');
					header('Pragma: no-cache');
				}
				break;
			case 'tofile':
				jimport('joomla.filesystem.folder');
				
				/* Check if the folder exists */
				if (!JFolder::exists(dirname($exportfilename))) {
					if (!JFolder::create(dirname($exportfilename))) {
						$csvilog->AddStats('incorrect', JText::_('Cannot create folder').'  '.dirname($exportfilename));
						return false;
					}
				}
				
				/* open the file for writing */
				$handle = fopen($exportfilename, 'w+');
				if (!$handle) {
					$csvilog->AddStats('incorrect', JText::_('Cannot open file').'  '.$exportfilename);
					return false;
				}
				/* Let's make sure the file exists and is writable first. */
				if (is_writable($exportfilename)) {
				    JRequest::setVar('handle', $handle);
				    return true;
				} 
				else {
					$csvilog->AddStats('incorrect', JText::_('The file is not writable').' '.$exportfilename);
					return false;
				}
				break;
		}
		
	}
	
	/**
	 * Write the output to download or to file
	 */
	public function writeOutput($contents) {
		$csvilog = JRequest::getVar('csvilog');
		$exportfilename = JRequest::getVar('exportfilename');
		
		switch(JRequest::getVar('exportto')) {
			case 'todownload':
				echo $contents;
				break;
			case 'tofile':
				if (fwrite(JRequest::getVar('handle'), $contents) === FALSE) {
					$csvilog->AddStats('incorrect', JText::_('Cannot write to file').' '.$exportfilename);
				   return false;
				}
				break;
		}
	}
	
	public function getOutputEnd() {
		$csvilog = JRequest::getVar('csvilog');
		$exportfilename = JRequest::getVar('exportfilename');
		
		switch(JRequest::getVar('exportto')) {
			case 'todownload':
				exit();
				break;
			case 'tofile':
				$csvilog->AddStats('information', "The file ".$exportfilename." has been created");
				fclose(JRequest::getVar('handle'));
				break;
		}
	}
}
?>