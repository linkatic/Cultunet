<?php
/**
 * Templates model
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: templates.php 947 2009-08-05 08:00:22Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Templates Model
 *
 * @package CSVImproved
 */
class CsvimprovedModelTemplates extends JModel {
	
	private $_id = null;
	/** @var string table name */
	private $_tablename = 'csvi_templates';
	
	
	/**
	 * Set the template ID
	 */
	function setTemplateId($template_id) {
		if (is_numeric($template_id)) $this->_id = $template_id;
		/* String, so search on template name */
		else {
			$db = JFactory::getDBO();
			$q = "SELECT template_id FROM #__csvi_templates WHERE template_name = '".$db->getEscaped($template_id)."'";
			$db->setQuery($q);
			$this->_id = $db->loadResult();
		}
	}
	
	/**
	 * Get an empty template model
	 */
	function getEmptyTemplate() {
		return $this->getTable($this->_tablename);
	}
	
	/**
	 * Retrieves the total number of templates for CSV Improved
	 */
	function getTotalTemplates() {
		$filter = JRequest::getVar('templatetype', false);
		$db = JFactory::getDBO();
		$q = "SELECT COUNT(*) 
			FROM #__csvi_templates";
		if ($filter) $q .= " WHERE template_type LIKE '%".$filter."'";
		$db->setQuery($q);
		return $db->loadResult();
	}
	
	/**
	 * Retrieves a list of templates
	 */
	function GetTemplatesList($pagination) {
		$db = JFactory::getDBO();
		$filter = JRequest::getVar('templatetype', false);
		$q = "SELECT template_id, template_name, template_type
			FROM #__csvi_templates";
		if ($filter) $q .= " WHERE template_type LIKE '%".$filter."'";
		$q .= " ORDER BY template_name";
		$db->setQuery($q, $pagination->limitstart, $pagination->limit);
		
		return $db->loadObjectList();
	}
	
	/**
	 * Retrieves a list of templates
	 */
	function getTemplatesListExport() {
		$db = JFactory::getDBO();
		$q = "SELECT *
			FROM #__csvi_templates
			WHERE template_type LIKE ".$db->Quote('%export%')."
			ORDER BY template_name";
		$db->setQuery($q);
		
		$templates = $db->loadObjectList();
		if (count($templates) > 0) {
			if (isset($templates[0]->template_id)) {
				foreach ($templates as $key => $template) {
					if (isset($template->template_id)) {
						$this->_id = $template->template_id;
						$templates[$key]->numberoffields = $this->getNumberOfFields();
					}
				}
			}
			else {
				$templates = array();
				JError::raiseWarning(0, JText::_('DATABASE_CONFIGURATION_ERROR'));
			}
		}
		return $templates;
	}
	
	/**
	 * Retrieves a list of templates
	 */
	function getTemplatesListImport() {
		$db = JFactory::getDBO();
		$q = "SELECT *
			FROM #__csvi_templates
			WHERE template_type LIKE ".$db->Quote('%import%')."
			ORDER BY template_name";
		$db->setQuery($q);
		
		$templates = $db->loadObjectList();
		if (count($templates) > 0) {
			if (isset($templates[0]->template_id)) {
				foreach ($templates as $key => $template) {
					if (isset($template->template_id)) {
						$this->_id = $template->template_id;
						$templates[$key]->numberoffields = $this->getNumberOfFields();
					}
				}
			}
			else {
				$templates = array();
				JError::raiseWarning(0, JText::_('DATABASE_CONFIGURATION_ERROR'));
			}
		}
		return $templates;
	}
	
	/**
	 * Returns the number of fields associated with a template
	 *
	 * @param $template_id integer The id of the template
	 */
	public function getNumberOfFields() {
		$db = JFactory::getDBO();
		
		$q = "SELECT COUNT(id) AS numberoffields FROM #__csvi_template_fields ";
		$q .= "WHERE field_template_id = ".$this->_id." ";
		$q .= "AND published = 1";
		$db->setQuery($q);
		
		return $db->loadResult();
	}
	
	/**
	 * Get the template details
	 *
	 * Retrieves the template details from the csvi_templates table. If the
	 * template id is 0, it will automatically retrieve the template details
	 * for the template with the lowest ID in the database
	 *
	 * @see self::GetFirstTemplateId();
	 * @param $templateid integer Template ID to retrieve
	 */
	function getTemplate() {
		$row = $this->getTable($this->_tablename);
		
		if ($this->_id == 0) {
			$this_id = $this->GetFirstTemplateId();
		}
		$row->load($this->_id);
		return $row;
	}
	
	/**
	 * Get the name of a template
	 *
	 * @param $templateid integer Template ID to retrieve
	 */
	public function getTemplateName() {
		$db = JFactory::getDBO();
		$q = "SELECT template_name FROM #__csvi_templates
			WHERE template_id = ".$this->_id;
		$db->setQuery($q);
		return $db->loadResult();
	}
	
	/**
	 * Updates a template with the new user settings
	 *
	 * @param $template object contains the new template settings
	 */
	function getSaveTemplate() {
		global $mainframe;
		$row = $this->getTable($this->_tablename);
		$post = JRequest::get('post');
		
		/* Get the posted data */
		if (!$row->bind($post)) {
			$mainframe->enqueueMessage(JText::_('There was a problem binding the data'), 'error');
			return false;
		}
		
		/* Fix the manufacturer to be a string */
		if (is_array($row->manufacturer)) {
			if (in_array("0", $row->manufacturer)) {
				$row->manufacturer = '0';
			}
			else {
				$new_manufacturer = '';
				foreach ($row->manufacturer as $key => $field) {
					$new_manufacturer .= $field.",";
				}
				$row->manufacturer = substr($new_manufacturer, 0, -1);
			}
		}
		
		/* Pre-save checks */
		if (!$row->check()) {
		 $mainframe->enqueueMessage(JText::_('There was a problem checking the data'), 'error');
		 return false;
		}
		
		/* Save the changes */
		if (!$row->store()) {
			$mainframe->enqueueMessage(JText::_('There was a problem storing the data:').' '.$row->getError(), 'error');
			return false;
		}
		return $row;
	}
	
	/**
	 * Clones a template and the associated fields
	 *
	 * @param $templateid integer Template ID to retrieve
	 */	
	function getCloneTemplate() {
		global $mainframe;
		
		/* Load the current template */
		$row = $this->getTable($this->_tablename);
		$row->load($this->_id);
		
		/* Save it as a new template */
		$row->template_id = 0;
		$row->template_name .= '_'.date('Ymd_His', time());
		
		/* Pre-save checks */
		if (!$row->check()) {
		 $mainframe->enqueueMessage(JText::_('There was a problem checking the data'), 'error');
		 return false;
		}
		
		/* Save the changes */
		if (!$row->store()) {
			$mainframe->enqueueMessage(JText::_('Not able to clone template:').' '.$row->getError(), 'error');
			return false;
		}
		else {
			$mainframe->enqueueMessage(JText::_('Template has been cloned'));
			$db = JFactory::getDBO();
			/* Now duplicate the fields */
			$q = "INSERT INTO `#__csvi_template_fields` (
			`field_template_id` ,
			`field_name` ,
			`field_column_header` ,
			`field_default_value` ,
			`field_order`,
			`published`
			)
			(SELECT ".$row->template_id." , 
				`field_name` ,
				`field_column_header` ,
				`field_default_value` ,
				`field_order`,
				`published`
				FROM #__csvi_template_fields
				WHERE `field_template_id`=".$this->_id."
			)";
			$db->setQuery($q);
			if ($db->query()) {
				$mainframe->enqueueMessage(JText::_('Template fields have been cloned'));
				return true;
			}
			else {
				$mainframe->enqueueMessage(JText::_('Not able to clone template fields').'<br  />'.$q.'<br />'.$db->getErrorMsg(), 'error');
				return false;
			}
		}
	}
	
	/**
	 * Delete selected template
	 *
	 * @param $template_id integer The id of the template
	 */
	function getDeleteTemplate() {
		global $mainframe;
		
		/* Delete the current template */
		$row = $this->getTable($this->_tablename);
		if ($row->delete($this->_id)) {
			$mainframe->enqueueMessage(JText::_('Template has been deleted'));
			$this->setTemplateId(0);
			/* Delete the fields related to the template */
			$db =& JFactory::getDBO();
			$q = "DELETE FROM #__csvi_template_fields WHERE field_template_id = ".$this->_id;
			$db->setQuery($q);
			if ($db->query()) $mainframe->enqueueMessage(JText::_('Template fields have been deleted'));
			else $mainframe->enqueueMessage(JText::_('Template fields could not be deleted').$db->getErrorMsg());
		}
		else {
			$mainframe->enqueueMessage(JText::_('Could not delete template:').' '.$row->getError());
		}
	}
	
	/**
	 * Retrieve fields for a template
	 *
	 * Retrieves all fields for a template or retrieves a limited number of
	 * fields if set
	 *
	 * @param $template_id integer The id of the template
	 * @param $filter string The string to filter the template names on
	 * @param $limitstart integer The starting point for the list
	 * @param $limit integer The number of fields to get
	 */
	public function GetFields($template_id, $filter, $limitstart, $limit) {
		$db = JFactory::getDBO();
		$q = "SELECT * FROM #__csvi_template_fields 
			WHERE field_template_id = ".$template_id." ";
			if ($filter) $q .= "AND field_name LIKE '%".$filter."%' ";
		$q .= "ORDER BY field_order, field_name";
		
		$db->setQuery($q, $limitstart, $limit);
		
		return $db->loadObjectList();
	}
	
	/**
	 * Publish fields
	 *
	 * @param $cids array Array of field IDs to either publish or unpublish
	 * @param $task string String which tells to publish or unpublis the ids
	 * @return bool true|false true if all fields are published|false if there is an error
	 */
	function SwitchPublishFields(&$cids, &$task) {
		$db = JFactory::getDBO();
		
		if (is_array($cids)) {
			$ids = '';
			$q = "UPDATE #__csvi_template_fields
				SET published = ";
			if ($task == 'publish') $q .= "1 ";
			else if ($task == 'unpublish') $q .= "0 ";
			$q .= "WHERE id IN (";
			foreach ($cids as $key => $field_id) {
				$ids .= $field_id.', ';
			}
			$q .= substr($ids,0, -2).")";
			$db->setQuery($q);
			if ($db->query()) return true;
			else return false;
		}
		else return false;
	}
	
	/**
	 * Save all input fields from the fields table
	 *
	 * @param $template_id integer The id of the template
	 * @param $fields array Holds all fields to update
	 */
	function SaveFieldsTable(&$template_id, &$fields) {
		$db = JFactory::getDBO();
		if (is_array($fields)) {
			/* The {fill} is used for adding new fields dynamically */
			unset($fields['{fill}']);
			foreach ($fields as $key => $field) {
				$field_name = $db->getEscaped($field['_field_name']);
				if (isset($field['_column_header'])) $column_header = $db->getEscaped($field['_column_header']);
				else $column_header = '';
				$default_value = $db->getEscaped($field['_default_value']);
				$field_order = $field['_order'];
				$field_id = $field['_id'];
			
				$q = "UPDATE #__csvi_template_fields 
					SET field_template_id = ".$template_id.",
					field_name = '".$field_name."',
					field_column_header = '".$column_header."',
					field_default_value = '".$default_value."',
					field_order = '".$field_order."' 
					WHERE id = ".$field_id;
				$db->setQuery($q);
				if (!$db->query()) {
					CsviLog::AddMessage('info', $db->getErrorMsg());
					return false;
				}
			}
			/* We reached here, so no problems */
			return true;
		}
		else return false;
	}
	
	/**
	 * Adds a new field to a template
	 *
	 * @param $template_id integer The id of the template
	 * @param $field array Holds the field data to add
	 */
	function AddField(&$template_id, &$field) {
		$db = JFactory::getDBO();
		
		$q = "INSERT INTO #__csvi_template_fields 
			(field_template_id, field_name, field_column_header, field_default_value, field_order, published) 
			VALUES (".$template_id.", '".$db->getEscaped($field['_field_name'])."','".$db->getEscaped($field['_column_header'])."','".$field['_default_value']."', '".$field['_order']."', 1)";
		$db->setQuery($q);
		if ($db->query()) return true;
		else {
			CsviLog::AddMessage('info', $db->getErrorMsg());
			return false;
		}
	}
	
	/**
	 * Renumber fields
	 *
	 * Renumbers all fields ordered by published state
	 * @param $template_id integer The id of the template
	 */
	function RenumberFields(&$template_id) {
		$db = JFactory::getDBO();
		$q = "SELECT id
			FROM #__csvi_template_fields
			WHERE field_template_id = ".$template_id."
			ORDER BY published DESC, field_order, field_name";
		$db->setQuery($q);
		$db->query();
		$fieldorder = $db->loadObjectList();
		$process = true;
		foreach ($fieldorder as $order => $field) {
			$q = "UPDATE #__csvi_template_fields
				SET field_order = ".($order+1)."
				WHERE id = ".$field->id;
			$db->setQuery($q);
			if (!$db->query()) {
				$process = false;
				CsviLog::AddMessage('info', $db->getErrorMsg());
			}
		}
		return $process;
	}
	
	/**
	 * Deletes a selected field
	 *
	 * Renumbers all fields ordered by published state
	 * @param $id integer The id of the template
	 */
	function DeleteConfigField(&$fieldid) {
		$db = JFactory::getDBO();
		$q = "DELETE from #__csvi_template_fields WHERE id='".$fieldid."'";
		$db->setQuery($q);
		if ($db->query()) return true;
		else {
			CsviLog::AddMessage('info', $db->getErrorMsg());
			return false;
		}
	}
	
	/**
	 * Get the first template id
	 */
	private function GetFirstTemplateId() {
		$db = JFactory::getDBO();
		$q = 'SELECT MIN(template_id) FROM #__csvi_templates';
		$db->setQuery($q);
		
		return $db->loadResult();
	}
	
	/**
	 * Get all the shopper groups
	 */
	function getShopperGroups() {
		$db = JFactory::getDBO();
		$q = "SELECT shopper_group_id, shopper_group_name FROM #__vm_shopper_group";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	/**
	 * Get all the manufacturers
	 */
	function getManufacturers() {
		$db = JFactory::getDBO();
		$q = "SELECT manufacturer_id, mf_name FROM #__vm_manufacturer ORDER BY mf_name";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	/**
	 * Get all the manufacturers
	 */
	function getTemplateTypes($type=false) {
		$db = JFactory::getDBO();
		$q = "SELECT template_type_name AS name, template_type_name AS value 
			FROM #__csvi_template_types ";
		$q .= ($type) ? "WHERE template_type = ".$db->Quote($type) : "";
		$q .= "ORDER BY template_type_name";
		$db->setQuery($q);
		$types = $db->loadObjectList();
		
		/* Translate the strings */
		foreach ($types as $key => $type) {
			$type->value = JText::_($type->value);
			$types[$key] = $type;
		}
		return $types;
	}
	
	/**
	 * Get the next template field number
	 */
	 public function getNextFieldNumber() {
	 	$db = JFactory::getDBO();
	 	$q = "SELECT IF (ISNULL(MAX(field_order)), 1, MAX(field_order)+1)
	 		FROM #__csvi_template_fields 
	 		WHERE field_template_id = ".$this->_id;
	 	$db->setQuery($q);
	 	return $db->loadResult();
	 }
}
?>