<?php
/**
 * Templates view
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
 * Templates View
 *
 * @package CSVImproved
 */
class CsvimprovedViewTemplates extends JView {
	/**
	 * Templates view display method
	 * @return void
	 **/
	function display($tpl = null) {
		$task = JRequest::getCmd('task');
		switch ($task) {
			/* Creating a new template */
			case 'newtemplate':
			/* Editing an existing template */
			case 'edittemplate':
				$this->TemplateConfigurator();
				break;
			/* Default show the list of templates */
			default:
				/* Saving a new template */
				switch ($task) {
					case 'save':
						$this->get('SaveTemplate');
						break;
					/* Clone selected template */
					case 'clonetemplate':
						$this->CloneTemplate();
						break;
					/* Delete selected template */
					case 'deletetemplate':
						$this->DeleteTemplate();
						break;
				}
				$templatetypes = array();
				/* Add a dont use option */
				$templatetype = new StdClass();
				$templatetype->template_code = '';
				$templatetype->template_name = JText::_('ALL');
				$templatetypes[] = $templatetype;
				$templatetype = new StdClass();
				$templatetype->template_code = 'import';
				$templatetype->template_name = JText::_('IMPORT');
				$templatetypes[] = $templatetype;
				$templatetype = new StdClass();
				$templatetype->template_code = 'export';
				$templatetype->template_name = JText::_('EXPORT');
				$templatetypes[] = $templatetype;
				$lists['templatetype'] = JHTML::_('select.genericlist', $templatetypes, 'templatetype', '' , 'template_code', 'template_name', JRequest::getVar('templatetype'));
				$this->assignRef('lists', $lists);
				$this->TemplateList();
				break;
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
		switch (JRequest::getCmd('task')) {
			case 'newtemplate':
				JToolBarHelper::title(JText::_('New template'), 'csvivirtuemart_new_48');
				JToolBarHelper::custom('save', 'csvivirtuemart_save_32', 'csvivirtuemart_save_32', JText::_('Save'), false);
				JToolBarHelper::custom('cancel', 'csvivirtuemart_cancel_32', 'csvivirtuemart_cancel_32', JText::_('Cancel'), false);
				break;
			case 'edittemplate':
				JToolBarHelper::title(JText::_('Edit template').'::'.$this->template->template_name, 'csvivirtuemart_edit_48');
				JToolBarHelper::custom('save', 'csvivirtuemart_save_32', 'csvivirtuemart_save_32', JText::_('Save'), false);
				JToolBarHelper::custom('cancel', 'csvivirtuemart_cancel_32', 'csvivirtuemart_cancel_32', JText::_('Cancel'), false);
				break;
			default:
				JToolBarHelper::title(JText::_('Templates'), 'csvivirtuemart_templates_48');
				JToolBarHelper::custom('newtemplate', 'csvivirtuemart_new_32', 'csvivirtuemart_new_32', JText::_('New'), false);
				JToolBarHelper::custom('edittemplate', 'csvivirtuemart_edit_32', 'csvivirtuemart_edit_32', JText::_('Edit'), true);
				JToolBarHelper::custom('clonetemplate', 'csvivirtuemart_clone_32', 'csvivirtuemart_clone_32', JText::_('Clone'), true);
				JToolBarHelper::custom('deletetemplate', 'csvivirtuemart_delete_32', 'csvivirtuemart_delete_32', JText::_('Delete'), true);
				JToolBarHelper::divider();
				JToolBarHelper::custom('fieldstemplate', 'csvivirtuemart_fields_32', 'csvivirtuemart_fields_32', JText::_('Fields'), true);
				break;
		}
	}
	
	/**
	 * Show a list of templates limited by the limit settings
	 */
	function TemplateList() {
		global $mainframe, $option;
		
		/* Start pagination */
		jimport('joomla.html.pagination');
		$limit      = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		$total = $this->get('TotalTemplates');
		/* Check if the the starting point is not beyond the total number of templates */
		if ($limitstart > $total) $limitstart = 0;
		$pagination = new JPagination( $total, $limitstart, $limit );
		
		/* Load template model */
		$template_model = $this->getModel('templates');
		
		/* Create an array of templates */
		$rawtemplateslist = $template_model->GetTemplatesList($pagination);
		$templateslist = new stdClass();
		
		if (count($rawtemplateslist) < 1) JError::raiseNotice(0, JText::_('No templates defined'));
		else {
			foreach ($rawtemplateslist as $id => $details) {
				$templateslist->$id->name = $details->template_name;
				$templateslist->$id->type = $details->template_type;
				$templateslist->$id->id = $details->template_id;
				/* Set the template ID */
				$template_model->setTemplateId($details->template_id);
				$templateslist->$id->nrfields = $this->get('NumberOfFields');
			}
		}
		
		/* Assign the data so it can be displayed */
		$this->assignRef('total', $total);
		$this->assignRef('templateslist', $templateslist);
		$this->assignRef('pagination', $pagination);
	}
	
	/**
	 * Template configurator
	 */
	function TemplateConfigurator() {
		
		/* Get the task */
		$task = JRequest::getCmd('task');
		
		/* Get the template ID */
		$template_id = JRequest::getInt('template_id', 0);
		
		/* Load template model */
		$template_model = $this->getModel('templates');
		
		/* Set the template ID */
		if ($template_id > 0) $template_model->setTemplateId($template_id);
		
		if ($task != 'newtemplate') {
			/* Get the template details */
			$template = $this->get('Template');
		}
		else if ($task == 'newtemplate') {
			/* For new templates load an empty template */
			$template = $this->get('EmptyTemplate');
			$template->setStandardValues();
		}
		
		/* Get the template type */
		if (stristr($template->template_type, 'export')) $type = 'export';
		else $type = 'import';
		
		/* Get the supported fields */
		$supportedfields = $template_model->getTemplateTypes($type);
		$lists['supportedfields'] = JHTML::_('select.genericlist', $supportedfields, 'template_type', '' , 'name', 'value', $template->template_type);
		
		/* Get the thumbnail formats */
		$thumb_format = array();
		$thumb_format[0]->name = 'none';
		$thumb_format[0]->value = JText::_('Default');
		$thumb_format[1]->name = 'jpg';
		$thumb_format[1]->value = JText::_('JPG');
		$thumb_format[2]->name = 'png';
		$thumb_format[2]->value = JText::_('PNG');
		$thumb_format[3]->name = 'gif';
		$thumb_format[3]->value = JText::_('GIF');
		$lists['thumbnailformat'] = JHTML::_('select.genericlist', $thumb_format, 'thumb_extension', '' , 'name', 'value', $template->thumb_extension);
		
		/* Get the shopper groups */
		$shopper_groups = $this->get('shoppergroups');
		
		/* Get the shopper groups */
		$manufacturers = $this->get('manufacturers');
		
		/* Assign the data so it can be displayed */
		$this->assignRef('task', $task);
		$this->assignRef('template', $template);
		$this->assignRef('lists', $lists);
		$this->assignRef('type', $type);
		$this->assignRef('supportedfields', $supportedfields);
		$this->assignRef('shopper_groups', $shopper_groups);
		$this->assignRef('manufacturers', $manufacturers);
	}
	
	/**
	 * Clone an existing template
	 *
	 * Creates a copy of an existing template and gives it the same name with
	 * a timestamp added to it. The fields are also duplicated.
	 */
	function CloneTemplate() {
		global $mainframe, $option;
		
		/* Get the template ID */
		$template_id = JRequest::getCmd('template_id');
		
		/* Load template model */
		$template_model = $this->getModel('templates');
		
		/* Set the template ID */
		if ($template_id > 0) {
			$template_model->setTemplateId($template_id);
			$this->get('CloneTemplate');
		}
		else $mainframe->enqueueMessage(JText::_('No template ID found to clone'));
	}
	
	/**
	 * Delete an existing template
	 */
	function DeleteTemplate() {
		global $mainframe, $option;
		
		/* Get the template ID */
		$template_id = JRequest::getCmd('template_id');
		
		/* Set the template ID */
		if ($template_id > 0) {
			/* Load template model */
			$template_model = $this->getModel('templates');
			$template_model->setTemplateId($template_id);
			$this->get('DeleteTemplate');
		}
		else $mainframe->enqueueMessage(JText::_('No template ID found to delete'));
	}
}
?>