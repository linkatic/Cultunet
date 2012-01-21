<?php
/**
 * Template fields view
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
 * Template fields View
 *
 * @package CSVImproved
 */
class CsvimprovedViewTemplateFields extends JView {
	
	/**
	 * Templates view display method
	 * @return void
	 **/
	function display($tpl = null) {
		global $mainframe, $option;
		
		switch (JRequest::getCmd('task')) {
			case 'addfield':
				$this->AddField();
				break;
			case 'savefieldstable':
				$this->SaveFieldsTable();
				break;
			case 'deletefield':
				$this->DeleteField();
				break;
			case 'renumber':
				$this->RenumberFields();
				break;
			case 'publish':
				if ($this->SwitchPublishFields()) $mainframe->enqueueMessage(JText::_('Template fields have been published'));
				else $mainframe->enqueueMessage(JText::_( 'Template fields have not been published'), 'error');
				break;
			case 'unpublish':
				if ($this->SwitchPublishFields()) $mainframe->enqueueMessage(JText::_('Template fields have been unpublished'));
				else $mainframe->enqueueMessage(JText::_( 'Template fields have not been unpublished'), 'error');
				break;
		}
		
		/* Show the fields */
		$this->FieldsList();
		
		/* Get the toolbar */
		$this->toolbar();
		
		/* Display it all */
		parent::display($tpl);
	}
	
	/**
	 * Displays a toolbar for a specific page
	 */
	function toolbar() {
		JToolBarHelper::title(JText::_('Template fields'). '::'.$this->template->template_name, 'csvivirtuemart_fields_48');
		JToolBarHelper::custom( 'templates', 'csvivirtuemart_templates_32', 'csvivirtuemart_templates_32', JText::_('Templates'), false);
		JToolBarHelper::custom( 'publish', 'csvivirtuemart_publish_32', 'csvivirtuemart_publish_32', JText::_('Publish'), true);
		JToolBarHelper::custom( 'unpublish', 'csvivirtuemart_unpublish_32', 'csvivirtuemart_unpublish_32', JText::_('Unpublish'), true);
		JToolBarHelper::custom( 'savefieldstable', 'csvivirtuemart_save_32', 'csvivirtuemart_save_32', JText::_('Save'), false);
	}
	
	/**
	 * Show a list of templates limited by the limit settings
	 */
	function FieldsList() {
		global $mainframe, $option;
		
		/* Get the template ID */
		$template_id = JRequest::getInt('template_id', 0);
		
		if ($template_id > 0) {
			/* Load template model */
			$template_model = $this->getModel('templates');
			
			/* Set the template ID */
			$template_model->setTemplateId($template_id);
			
			/* Get the current template */
			$template = $this->get('Template');
			
			/* Get the supported fields for the template */
			/* Get the fields for the template type */
			$availablefields = new CsvimprovedModelAvailablefields();
			$csvisupportedfields = $availablefields->GetAvailableFields($template->template_type);
			
			/* Get the total number of fields */
			$total = $template_model->GetNumberOfFields();
					
			/* Start pagination */
			$limit      = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
			$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
			
			if (JRequest::getBool('fromtemplatelist')) $limitstart = 0;
			
			jimport('joomla.html.pagination');
			$pagination = new JPagination( $total, $limitstart, $limit );
			
			/* Get the fields for the active template */
			/* See if we need to filter the result */
			$filter = JRequest::getVar('filterfield', false);
			$fields = $template_model->GetFields($template_id, $filter, $limitstart, $limit);
			
			/* Assign the data so it can be displayed */
			$this->assignRef('template_id', $template_id);
			$this->assignRef('template', $template);
			$this->assignRef('csvisupportedfields', $csvisupportedfields);
			$this->assignRef('pagination', $pagination);
			$this->assignRef('filter', $filter);
			$this->assignRef('fields', $fields);
			$this->assignRef('totalfields', $template_model->getNextFieldNumber());
		}
		else $mainframe->enqueueMessage(JText::_('No template found'));
	}
	
	/**
	 * Switches fields between published and unpublished state
	 */
	function SwitchPublishFields() {
		$cids = JRequest::getVar('cid');
		$task = JRequest::getVar('task');
		
		return CsvimprovedModelTemplates::SwitchPublishFields($cids, $task);
	}
	
	/**
	 * Saves all input fields on the fields list
	 */
	function SaveFieldsTable() {
		global $mainframe;
		
		/* Get the template ID */
		$template_id = JRequest::getCmd('template_id');
		
		/* Get the fields array to save */
		$fields = JRequest::getVar('field');
		
		/* Save the table */
		$return = CsvimprovedModelTemplates::SaveFieldsTable($template_id, $fields);
		
		if ($return) $mainframe->enqueueMessage(JText::_('Template fields have been saved'));
		else $mainframe->enqueueMessage(JText::_('Not able to save template fields').'<br />'.CsviLog::GetLogMessage(), 'error');
	}
	
	/**
	 * Adds a new field to the template
	 */
	function AddField() {
		global $mainframe;
		/* Get the template ID */
		$template_id = JRequest::getCmd('template_id');
		
		$fields = JRequest::getVar('field');
		$field = $fields['{fill}'];
		
		/* Save the table */
		$return = CsvimprovedModelTemplates::AddField($template_id, $field);
		
		if ($return) $mainframe->enqueueMessage(JText::_('Template field has been added'));
		else $mainframe->enqueueMessage(JText::_('Template field has not been added').'<br />'.CsviLog::GetLogMessage(), 'error');
	}
	
	/**
	 * Renumber fields
	 *
	 * Renumbers all fields ordered by published state
	 */
	function RenumberFields() {
		global $mainframe;
		
		/* Get the template ID */
		$template_id = JRequest::getCmd('template_id');
		
		/* Execute query */
		$return = CsvimprovedModelTemplates::RenumberFields($template_id);
		
		if ($return) $mainframe->enqueueMessage(JText::_('New field order has been saved'));
		else $mainframe->enqueueMessage(JText::_('There was a problem saving the new field order').'<br />'.CsviLog::GetLogMessage(), 'error');
	}
	
	/**
	 * Delete selected field from the selected template
	 */
	function DeleteField() {
		global $mainframe;
		
		/* Get the field id */
		$fields = JRequest::getVar('cid');
		
		/* Execute query */
		$return = CsvimprovedModelTemplates::DeleteConfigField($fields[0]);
		
		if ($return) $mainframe->enqueueMessage(JText::_('Template field has been deleted'));
		else $mainframe->enqueueMessage(JText::_('Not able to delete template field').'<br />'.CsviLog::GetLogMessage(), 'error');
	}
}
?>
