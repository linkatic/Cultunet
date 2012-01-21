<?php
/**
 * Import file view
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: view.html.php 945 2009-07-30 07:18:43Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Import file View
 *
 * @package CSVImproved
 */
class CsvimprovedViewImportFile extends JView {
	
	/** @var object Holds the active template details  */
	public $template = null;
	
	/** @var object Holds the file class  */
	private $handlefile = null;
	
	/**
	 * Templates view display method
	 * @return void
	 * @todo look at could not start import engine
	 **/
	function display($tpl = null) {
		
		/* Get the logger class */
		JView::loadHelper('csvi_class_log');
		$csvilog = new CsviLog();
		JRequest::setVar('csvilog', $csvilog);
		
		/* Get the template type */
		/* Load template model */
		$template_model = $this->getModel('templates');
		
		/* Set the template ID */
		$template_model->setTemplateId(JRequest::getCmd('template_id', 0));
		$this->template = $this->get('Template', 'templates');
		$this->get('ProductTable');
		JRequest::setVar('template', $this->template);
		JRequest::setVar('debug', $this->template->collect_debug_info);
		
		/* Set the system limits */
		$this->get('systemlimits');
		
		/* Check the system limits */
		$this->get('checklimits');
		
		/* Set the line counter */
		JRequest::setVar('currentline', 1);
		
		/* Add extra helper paths */
		JView::addHelperPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'excel');
		JView::addHelperPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'ods');
		
		/* Get the helper files */
		JView::loadHelper('csvi_class_file');
		JView::loadHelper('csvi_class_product_discount');
		JView::loadHelper('csvi_class_product');
		JView::loadHelper('csvi_class_manufacturer');
		JView::loadHelper('excel_reader2');
		JView::loadHelper('ods');
		JView::loadHelper('csvi_class_mime_type_detect');
		JView::loadHelper('csvi_class_image_converter');
		
		/* Retrieve the available fields */
		$availablefields = new CsvimprovedModelAvailablefields();
		JRequest::setVar('supported_fields', $availablefields->GetAvailableFields($this->template->template_type));
		
		/* Get the file contents so we can start processing */
		/* Handle the upload here */
		$this->handlefile = new CsviFile();
		
		/* Set some log info */
		$csvilog->SetAction('import');
		$csvilog->SetActionType($this->template->template_type, $this->template->template_name);
		
		/* Check if the file is OK, if not do not continue */
		if (!$this->handlefile->fp) {
			$csvilog->AddStats('incorrect', JText::_('FAILURE'));
		}
		else {
			/* Retrieve first line */
			JRequest::setVar('csvi_data', $this->handlefile->ReadFirstLine());
			
			/* Check if there is any product data */
			if (!JRequest::getVar('csvi_data')) {
				JError::raiseWarning(0, JText::_('NO_CSVI_DATA'));
				return false;
			}
			else {
				/* Set some variables */
				$data_preview = array();
				$processdata = true;
				
				/* Validate the import choices */
				$this->get('validateimportchoices');
				
				/* Print out the import details */
				$this->get('importdetails');
				$csvilog->AddMessage('debug', 'Doing a '.$this->template->template_type.' import');
				
				/* Retrieve the fields to import */
				if ($this->get('retrieveconfigfields')) {
					/* Set a placeholder for the memory usage */
					$csvilog->AddMessage('debug', '{debugmem}');
					$csvilog->AddMessage('debug', '<hr />');
					/* Create loop to handle more lines.....do not do product details if first line is column headers */
					/* Do we need to import the first record? */
					if (!$this->template->skip_first_line && !$this->template->use_column_headers) {
						$csvilog->AddMessage('debug', str_ireplace('{currentline}', JRequest::getVar('currentline'), JText::_('DEBUG_PROCESS_LINE')));
						if ($this->get('Start', $this->template->template_type)) {
							/* Check if the user wants us to show a preview */
							if ($this->template->show_preview && !JRequest::getVar('was_preview', false)) {
								$data_preview[JRequest::getVar('currentline')] = $this->UpdatePreview();
							}
							else {
								$this->get('ProcessRecord', $this->template->template_type);
							}
						}
						JRequest::setVar('currentline', JRequest::getInt('currentline')+1);
						$csvilog->setLinenumber();
					}
					/* Use column headers as configuration */
					else if($this->template->use_column_headers && $this->handlefile->extension != 'xml') {
						JRequest::setVar('currentline', JRequest::getInt('currentline')+1);
						$csvilog->setLinenumber();
					}
					
					/* Store some data for the preview here */
					while ($processdata) {
						JRequest::setVar('csvi_data', $this->handlefile->ReadNextLine());
						if (JRequest::getVar('csvi_data') == false) {
							$processdata = false;
						}
						else {
							if ($this->get('checklimits')) {
								$csvilog->AddMessage('debug', str_ireplace('{currentline}', JRequest::getVar('currentline'), JText::_('DEBUG_PROCESS_LINE')));
								/* Start processing record */
								if ($this->get('Start', $this->template->template_type)) {
									/* Check if the user wants us to show a preview */
									if ($this->template->show_preview && !JRequest::getVar('was_preview', false)) {
										if (JRequest::getVar('currentline') == 6) {
											JRequest::setVar('data_preview', $data_preview);
											$processdata = false;
										}
										else {
											$data_preview[JRequest::getVar('currentline')] = $this->UpdatePreview();
										}
									}
									else {
										/* Start processing the data */
										/* Now we import the rest of the records*/
										$this->get('ProcessRecord', $this->template->template_type);
									}
								}
								
								$csvilog->AddMessage('debug', '<hr />');
								/* Increase linenumber and inform logger */
								JRequest::setVar('currentline', JRequest::getInt('currentline')+1);
								$csvilog->setLinenumber();
							}
							else {
								/* Write out the memory usage for debug usage */
								if (JRequest::getVar('debug')) $csvilog->debug_message = str_replace('{debugmem}', 'Memory usage: '.JRequest::getVar('maxmem').' MB', $csvilog->debug_message);
								else $csvilog->debug_message = str_replace('{debugmem}', '', $csvilog->debug_message);
								
								/* Stop from processing any further, no time left */
								$processdata = false;
							}
						}
					}
					/* Check if we are doing preview but less than 6 lines */
					if ($this->template->show_preview && JRequest::getVar('currentline') <= 7 && !JRequest::getVar('was_preview', false) && !JRequest::getVar('error_found')) {
						JRequest::setVar('data_preview', $data_preview);
						JView::setLayout('importpreview');
						$this->AssignVars();
					}
					
					/* Post Processing */
					if ($this->template->template_type == 'productimport') {
						$this->get('PostProcessing', 'productimport'); 
						/* Clean up  */
						if (JRequest::getVar('droprelated', false)) $this->get('DropRelatedProductsTempTable');
					}
					
					/* Write out the memory usage for debug usage */
					if (JRequest::getVar('debug')) $csvilog->debug_message = str_replace('{debugmem}', 'Memory usage: '.JRequest::getVar('maxmem').' MB', $csvilog->debug_message);
					else $csvilog->debug_message = str_replace('{debugmem}', '', $csvilog->debug_message);
				}
			}
		}
		/* Store the log results, only if we are not doing a preview */
		if (!$this->template->show_preview || JRequest::getVar('was_preview') || JRequest::getVar('error_found')) {
			$this->get('StoreLogResults', 'log');
		}
		
		/* Get the toolbar */
		$this->toolbar();
		
		/* Display it all */
		parent::display($tpl);
	}
	
	/**
	 * Displays a toolbar for a specific page
	 */
	function toolbar() {
		if ($this->template->show_preview && !JRequest::getVar('was_preview')) {
			JToolBarHelper::title(JText::_( 'Import preview' ), 'csvivirtuemart_import_48');
			if (!JRequest::getVar('upload_file_error') && (!JRequest::getVar('error_found'))) {
				JToolBarHelper::custom( 'importfile', 'csvivirtuemart_import_32', 'csvivirtuemart_import_32', JText::_('Import'), false);
			}
		}
		else JToolBarHelper::title(JText::_( 'Import result' ), 'csvivirtuemart_import_48');
	}
	
	/**
	 * Assign vars to be used for preview mode
	 */
	function AssignVars() {
		$this->assignRef('csvfields', JRequest::getVar('csv_fields'));
		$this->assignRef('datapreview', JRequest::getVar('data_preview', '', 'default', 'none', 2));
		$this->assignRef('template_id', $this->template->template_id);
		$this->assignRef('userfilename', JRequest::getVar('user_filename'));
		$this->assignRef('csvfile', JRequest::getVar('csv_file'));
	}
	
	/**
	 * Update preview data
	 */
	private function UpdatePreview() {
		/* Update preview data */
		$csvi_data = JRequest::getVar('csvi_data', '', 'default', 'none', 2);
		$csv_fields = JRequest::getVar('csv_fields');
		
		$previewmodel = $this->getModel($this->template->template_type);
		foreach ($csv_fields as $fieldname => $value) {
			if (isset($previewmodel->$fieldname)) $csvi_data[$csv_fields[$fieldname]["order"]] = $previewmodel->$fieldname;
		}
		return $csvi_data;
	}
}
?>