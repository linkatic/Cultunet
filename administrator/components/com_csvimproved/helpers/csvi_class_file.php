<?php
/**
 * Main file processor class
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvi_class_file.php 945 2009-07-30 07:18:43Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
 
/** 
 * CsviFile class 
 * 
 * The CsviFile class handles all file operations regarding reading CSV and XLS
 * files. XLS reading is supported through the ExcelReader class.
 *
 * @package CSVImproved
 * @see Spreadsheet_Excel_Reader::read()
 */
class CsviFile {
	
	/** @var array Contains allowed extensions for uploaded files */
	var $suffixes = array();
	
	/** @var array Contains allowed mimetypes for uploaded files */
	var $mimetypes = array();
	
	/** @var string Contains the name of the uploaded file */
	var $filename = '';
	
	/** @var string Contains the extension of the uploaded file */
	var $extension = '';
	
	/** @var bool Contains the value whether or not the file uses
	 * an extension that is allowed.
	 *
	 * @see $suffixes
	 */
	var $valid_extension = false;
	
	/** @var bool Filepointer used when opening files */
	var $fp = false;
	
	/** @var integer Internal line pointer */
	var $linepointer = 2;
	
	/** @var array Contains the data that is read from file */
	var $data = null;
	
	/**
	 * Controls the reading of a file
	 *
	 * Determines on extension what file reading method to use.
	 *
	 * @todo Create valid checks for $fp
	 * @see $fp
	 */
	function __construct() {
		/* Load the necessary libraries */
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.archive');
		$this->FileSettings();
		if ($this->ValidateFile()) {
			switch ($this->extension) {
				case 'csv':
					/* File gets closed in the ReadNextLine function */
					$this->ProcessFile();
					break;
				case 'xls':
					$this->fp = true;
					/* XLS can only read the complete file */
					$this->ProcessFile();
					break;
				case 'xml':
					$this->fp = true;
					/* XML can only read the complete file */
					$this->ProcessFile();
					break;
				case 'ods':
					$this->fp = true;
					/* ODS can only read the complete file */
					$this->ProcessFile();
					break;
			}
		}
		else {
			return false;
		}
	}
	
	/**
	 * Set the settings to work with
	 *
	 * @see $suffixes
	 * @see $mimetypes
	 * @see $data
	 */
	function FileSettings() {
		$this->suffixes = array('txt','csv','xls','xml','ods');
		$this->mimetypes = array('text/html',
							'text/plain',
							'text/csv',
							'application/octet-stream',
							'application/x-octet-stream',
							'application/vnd.ms-excel',
							'application/excel',
							'application/ms-excel',
							'application/x-excel',
							'application/x-msexcel',
							'application/force-download',
							'text/comma-separated-values',
							'text/x-csv',
							'text/x-comma-separated-values',
							'application/vnd.oasis.opendocument.spreadsheet');
		
		$this->data->sheets[0] = array();
	}
	
	/**
	 * Open the uploaded xls file
	 *
	 * @todo Check for memory consumption xls
	 * @todo Add failure handling moving ODS file
	 */
	function ProcessFile() {
		$csvilog = JRequest::getVar('csvilog');
		
		switch($this->extension) {
			case 'csv':
				/* Open the csv file*/
				$this->fp = fopen($this->filename,"r");
				break;
			/* Only read the file if the extension is xls */
			case 'xls':
				$this->data = new Spreadsheet_Excel_Reader($this->filename, false);
				if (0) {
					$this->data = new Spreadsheet_Excel_Reader();
					/* Set output Encoding. */
					$this->data->setOutputEncoding('UTF-8');
					$this->data->read($this->filename);
				}
				
				break;
			case 'xml':
                $this->data = simplexml_load_file($this->filename);
				if (!$this->data){
					$csvilog->AddStats('incorrect', JText::_('ERROR_READING_XML_FILE'));
					return false;
				}
				$this->data->items = count($this->data->item);
				break;
			case 'ods':
				$this->data = new ODSParser();
				/* First we need to unpack the zipfile */
				$unpackpath = JPATH_CACHE.DS.'csvi';
				$unpackfile = $unpackpath.DS.JRequest::getVar('user_filename').'.zip';
				$importfile = $unpackpath.DS.'content.xml';
				
				/* Check the unpack folder */
				JFolder::create($unpackpath);
				
				/* Delete the destination file if it already exists */
				if (JFile::exists($unpackfile)) JFile::delete($unpackfile);
				if (JFile::exists($importfile)) JFile::delete($importfile);
				
				/* Now copy the file to the folder */
				JFile::copy($this->filename, $unpackfile);
				
				/* Clean the csvi folder */
				if (!JArchive::extract($unpackfile, $unpackpath)) {
					$csvilog->AddStats('incorrect', JText::_('Cannot unpack ODS file'));
					return false;
				}
				/* File is always called content.xml */
				else $this->filename = $importfile;
				$this->data->read($this->filename);
				break;
		}
	}
	
	/**
	 * Valitdate the file
	 *
	 * Validate the file is of the supported type
	 * Types supported are csv, txt, xls
	 *
	 * @return bool returns true if all fine else false
	 */
	function ValidateFile() {
		$csvilog = JRequest::getVar('csvilog');
		$template = JRequest::getVar('template');
		
		switch (JRequest::getVar('selectfile')) {
			/* No file given */
			case 0:
				$csvilog->AddStats('incorrect', 'No file provided.');
				return false;
				break;
			/* Uploaded file */
			case 1:
				/* Check if the file upload has an error */
				if ($_FILES['file']['error'] == 0) {
					/* Check if user is going to preview first */
					/* User doing a preview, we must save the file first */
					if ($template->show_preview == true) {
						/* Start with a clean preview */
						JRequest::setVar('preview_message', "");
						
						/* Empty PHP cache statistics */
						clearstatcache();
						
						/* Start processing uploaded file */
						if (is_uploaded_file($_FILES['file']['tmp_name'])) {
							if (JFolder::exists(JPATH_CACHE) && is_writable(JPATH_CACHE)) {
								if (move_uploaded_file($_FILES['file']['tmp_name'], JPATH_CACHE.DS.basename($_FILES['file']['name']))) {
									JRequest::setVar('csv_file', JPATH_CACHE.DS.basename($_FILES['file']['name']));
									JRequest::setVar('upload_file_error', false);
								}
								else {
									JError::raiseWarning(0, JText::_(str_replace('{cache_path}', JPATH_CACHE, JText::_('CANNOT_UPLOAD_CACHE'))));
									JRequest::setVar('upload_file_error', true);
								}
							}
							else {
								JError::raiseWarning(0, JText::_(str_replace('{cache_path}', JPATH_CACHE, JText::_('CANNOT_UPLOAD_CACHE'))));
								JRequest::setVar('csv_file', $_FILES['file']['tmp_name']);
								JRequest::setVar('upload_file_error', true);
							}
						}
						/* Error warning cannot save uploaded file */
						else {
							$csvilog->AddStats('incorrect', JText::_('No uploaded file provided'));
							return false;
						}
					}
					else JRequest::setVar('csv_file', $_FILES['file']['tmp_name']);
					$fileinfo = pathinfo($_FILES["file"]["name"]);
					if (isset($fileinfo["extension"])) {
						$this->extension = strtolower($fileinfo["extension"]);
						if ($this->extension == 'txt') $this->extension = 'csv';
					}
				}
				else {
					/* There was a problem uploading the file */
					switch($_FILES['file']['error']) {
						case '1':
							$csvilog->AddStats('incorrect', JText::_('The uploaded file exceeds the maximum uploaded file size'));
							break;
						case '2':
							$csvilog->AddStats('incorrect', JText::_('The uploaded file exceeds the maximum uploaded file size'));
							break;
						case '3':
							$csvilog->AddStats('incorrect', JText::_('The uploaded file was only partially uploaded'));
							break;
						case '4':
							$csvilog->AddStats('incorrect', JText::_('No file was uploaded'));
							break;
						case '6':
							$csvilog->AddStats('incorrect', JText::_('Missing a temporary folder'));
							break;
						case '7':
							$csvilog->AddStats('incorrect', JText::_('Failed to write file to disk'));
							break;
						case '8':
							$csvilog->AddStats('incorrect', JText::_('File upload stopped by extension'));
							break;
						default:
							$csvilog->AddStats('incorrect', JText::_('There was a problem uploading the file'));
							break;
					}
					return false;
				}
				break;
			/* Local file */
			case 2:
				JRequest::setVar('csv_file', JRequest::getVar('local_csv_file'));
				if (!JFile::exists(JRequest::getVar('csv_file'))) {
					$csvilog->AddStats('incorrect', JText::_('Specified local file doesn\'t exist. File:'.JRequest::getVar('csv_file')));
					return false;
				}
				else JRequest::setVar('upload_file_error', false);
				$fileinfo = pathinfo(JRequest::getVar('csv_file'));
				if (isset($fileinfo["extension"])) {
					$this->extension = strtolower($fileinfo["extension"]);
					if ($this->extension == 'txt') $this->extension = 'csv';
				}
				break;
		}
		/* Set the filename */
		if (JFile::exists(JRequest::getVar('csv_file'))) {
			$this->filename = JRequest::getVar('csv_file');
		}
		else {
			$csvilog->AddMessage('', JText::_('Specified local file doesn\'t exist.'));
			return false;
		}
		
		if (in_array($this->extension, $this->suffixes)) $this->valid_extension = true;
		else {
			/* Test the mime type */
			if (!in_array($this->extension, $this->mimetypes) ) {
				$csvilog->AddStats('incorrect', JText::_('Mime type not accepted. Type for file uploaded').' '.$this->extension);
				return false;
			}
		}
		/* Debug message to know what filetype the user is uploading */
		$csvilog->AddMessage('debug', 'Importing filetype: '.$this->extension);
		
		/* Store the users filename for display purposes */
		if (isset($_FILES['file'])) JRequest::setVar('user_filename', $_FILES['file']['name']);
		
		/* All is fine */
		return true;
	}
	
	/**
	 * Read the first line of the file
	 *
	 * Return a multi-dimensional array with the layout:
	 *
	 * sheets[0]
	 * sheets[0][numRows] => 0
	 * sheets[0][numCols] => 0
	 * sheets[0][cells][1][1] => product_sku
	 * sheets[0][cells][2][1] => Wb1
	 * sheets[0][cells][3][1] => Wb2
	 *
	 * @return array with the line of data read
	 */
	function ReadFirstLine() {
		switch ($this->extension) {
			case 'csv':
				$data = $this->ReadNextLine();
				break;
			case 'xls':
				/* Make sure we include the empty fields */
				for ($i=1;$i<=$this->data->sheets[0]['numCols'];$i++) {
					if (!isset($this->data->sheets[0]['cells'][1])) $this->data->sheets[0]['cells'][1][$i] = '';
				}
				$data = $this->data->sheets[0]['cells'][1];
				break;
			case 'xml':
				/* This should always return the column headers */
				$data = $this->ToArray($this->data->item[0], true);
				break;
			case 'ods':
				$data = $this->data->fulldata[0];
				break;
			default:
				$data = false;
				break;
		}
		return $data;
	}
	
	/**
	 * Read the next line
	 *
	 * Reads the next line of the uploaded file
	 * In case of csv, it reads the next line from the file
	 * In case of xls, it returns the next entry in the array because the whole 
	 * file is already read
	 *
	 * @return array with the line of data read
	 */
	function ReadNextLine() {
		$newdata = array();
		switch ($this->extension) {
			case 'csv':
				$template = JRequest::getVar('template');
				/* Set the delimiter */
				if (strtolower($template->field_delimiter) == 't') $field_delimiter = "\t";
				else $field_delimiter = $template->field_delimiter;
				
				$csvdata = array(0=>''); 
				/* Ignore empty records */
                while (is_array($csvdata) && count($csvdata)==1 && $csvdata[0]=='') {
                    if (!empty($template->text_enclosure)) $csvdata = fgetcsv($this->fp, 0, $field_delimiter, $template->text_enclosure); 
                    else $csvdata = fgetcsv($this->fp, 0, $template->field_delimiter); 
                }
				
				if ($csvdata) {
					/* Do BOM check */
					if (JRequest::getVar('currentline') == 1) {
						/* Remove text delimiters as they are not recognized by fgetcsv */
						$csvdata[0] = str_replace($template->text_enclosure, "", $this->CheckBom($csvdata[0]));
					}
					foreach ($csvdata as $key => $value) { 
                        $newdata[$key+1] = $value; 
                    } 
                    return $newdata;
				}
				else {
					$this->CloseFile();
					return false;
				}
				break;
			case 'xls':
				if ($this->data->sheets[0]['numRows'] >= $this->linepointer) {
					/* Make sure we include the empty fields */
					for ($i=1;$i<=$this->data->sheets[0]['numCols'];$i++) {
					   if (!isset($this->data->sheets[0]['cells'][$this->linepointer][$i])) $this->data->sheets[0]['cells'][$this->linepointer][$i] = '';
					}
					$newdata = $this->data->sheets[0]['cells'][$this->linepointer];
					$this->linepointer++;
					return $newdata;
				}
				else return false;
				break;
			case 'xml':
				if ($this->data->items >= JRequest::getVar('currentline')) {
					$newdata = $this->ToArray($this->data->item[JRequest::getVar('currentline')-1], false);
					return $newdata;
				}
				else return false;
				break;
			case 'ods':
				if ($this->data->rows >= JRequest::getVar('currentline')) {
					return $this->data->fulldata[JRequest::getVar('currentline')-1];
				}
				else return false;
				break;
		}
	}
	
	/**
	 * Close the file
	 *
	 * Closes the csv file pointer
	 *
	 * @see OpenFile()
	 */
	function CloseFile() {
		switch ($this->extension) {
			case 'csv':
				/* Close csv file */
				fclose ($this->fp);
				break;
		}
	}
	
	
	/**
	 * Checks if the uploaded file has a BOM
	 *
	 * If the uploaded file has a BOM, remove it since it only causes
	 * problems on import.
	 *
	 * @see ReadNextLine()
	 * @param &$data The string to check for a BOM
	 * @return string return the cleaned string
	 */
	function CheckBom(&$data) {
		/* Check the first three characters */
		if (strlen($data) > 3) {
			if (ord($data{0}) == 239 && ord($data{1}) == 187 && ord($data{2}) == 191) {
				return substr($data, 3, strlen($data));
			}
			else return $data;
		}
		else return $data;
	}
	
	/**
	 * Convert an XML node into an array
	 *
	 * @param &$data object XML node to convert
	 * @return array XML node as an array
	 */
	function ToArray($data, $firstline) {
		$newdata = array();
		$counter = 1;
		foreach($data as $name => $value) {
			if ($firstline) $newdata[$counter] = $name;
			else $newdata[$counter] = $value;
			$counter++;
		}
		return $newdata;
	}
}
?>