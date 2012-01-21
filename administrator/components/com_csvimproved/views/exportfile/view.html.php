<?php
/**
 * Export file view
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: view.html.php 946 2009-08-03 09:47:37Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Export file View
 *
 * @package CSVImproved
 */
class CsvimprovedViewExportfile extends JView {
	
	/**
	 * Templates view display method
	 *
	 * @todo Handle not loading of XML file
	 * @return void
	 **/
	public function display($tpl = null) {
		/* Load template model */
		$template_model = $this->getModel('templates');
		
		/* Set the template ID */
		$template_model->setTemplateId(JRequest::getCmd('template_id', 0));
		$template = $this->get('Template', 'templates');
		
		/* Make the template global available */
		JRequest::setVar('template', $template);
		
		/* Get the logger class */
		JView::loadHelper('csvi_class_log');
		$csvilog = new CsviLog();
		JRequest::setVar('csvilog', $csvilog);
		
		/* Set some log info */
		$csvilog->SetAction('export');
		$csvilog->SetActionType($template->template_type, $template->template_name);
		
		/* Get some global details for the export */
		$this->ExportDetails();
		
		/* Load some basic data */
		$export_fields = JRequest::getVar('exportfields');
		
		/* Only start the export if there are any fields to export */
		if (count($export_fields) > 0) {
			/* Add extra helper paths */
			JView::addHelperPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'xml');
			
			/* Load the helper classes */
			JView::loadHelper('csvidb');
			
			/* Check if tab delimiter needs to be used */
			if (strtolower($template->field_delimiter) == 't') $template->field_delimiter = "\t";
			
			/* See if we need to get an XML class */
			if ($template->export_type == 'xml') {
				$this->getXmlClass();
			}
			
			/* Get the xml class */
			$xmlclass = JRequest::getVar('xmlclass');
			
			/* Start the output */
			$this->get('OutputStart');
			
			$contents = '';
			if ($template->export_type == 'xml') {
				$contents .= $xmlclass->HeaderText();
			}
			else {
				/* See if the user wants column headers */
				/* Product type names export needs to be excluded here otherwise the column headers are incorrect */
				if ($template->include_column_headers && strtolower($template->template_type) != 'producttypenamesexport') {
					foreach ($export_fields as $column_id => $field) {
						$contents .= $template->text_enclosure.$field->column_header.$template->text_enclosure.$template->field_delimiter;
					}
					if ($template->include_column_headers) $contents = substr($contents, 0 , -1);
					
					/* No extra line for Product Type Names Export */
					$contents .= "\r\n";
				}
			}
			
			/* Output content */
			CsvimprovedModelExportfile::writeOutput($contents);
			
			/* Start the export */
			$this->get('Start', $template->template_type);
			
			if ($template->export_type == 'xml') {
				CsvimprovedModelExportfile::writeOutput($xmlclass->FooterText());
			}
			
			/* Store the log results */
			$this->get('StoreLogResults', 'log');
			
			/* End the export */
			$this->get('OutputEnd');
			
			
		}
		else {
			$csvilog->AddStats('incorrect', JText::_('NO_EXPORT_FIELDS'));
			JRequest::setVar('logcount', array('export' => 0));
			
			/* Store the log results */
			$this->get('StoreLogResults', 'log');
		}
		
		/* Display it all */
		parent::display($tpl);
	}
	
	/**
	 * Get some basic details for the export
	 */
	private function ExportDetails() {
		JRequest::setVar('vmitemid', $this->get('VmItemId'));
		JRequest::setVar('exportfilename', $this->get('ExportFilename'));
		JRequest::setVar('exportfields', $this->get('ExportFields'));
	}
	
	/**
	 * Initiliase and export details
	 *
	 * @todo add check if class really exists
	 * @return bool true|false true when xml class is found|false when when no site is given
	 */
	public function getXmlClass() {
		$template = JRequest::getVar('template');
		$csvilog = JRequest::getVar('csvilog');
		
		$xmlclass = false;
		if (strlen($template->export_site) > 0) {
			switch ($template->export_site) {
				case 'csvi':
					JView::loadHelper('csvi_class_xml');
					$xmlclass = new CsviXmlExport();
					break;
				case 'beslist':
					JView::loadHelper('csvi_class_xml_beslist');
					$xmlclass = new CsviXmlBeslist();
					break;
				case 'froogle':
					JView::loadHelper('csvi_class_xml_froogle');
					$xmlclass = new CsviXmlFroogle();
					break;
				case 'oodle':
					JView::loadHelper('csvi_class_xml_oodle');
					$xmlclass = new CsviXmlOodle();
					break;
				default:
					$csvilog->AddStats('incorrect', JText::_("No XML class found"));
					return false;
					break;
			}
		}
		if ($xmlclass) JRequest::setVar('xmlclass', $xmlclass);
		return true;
	}
}
?>