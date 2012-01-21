<?php
/**
 * Virtuemart Product files table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_product_files.php 869 2009-04-14 14:00:35Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_product_files extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_product_files', 'file_id', $db );
	}
	
	/**
	 * Set a value for the class
	 */
	public function setValue($field, $value) {
		$this->$field = $value;
	}
	
	/**
	 * Get a value from the class
	 */
	public function getValue($field) {
		return $this->$field;
	}
	
	/**
	 * Cleans the class variables
	 */
	public function reset() {
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_product_files'));
	}
	
	/**
	 * Check if the file already exists
	 */
	public function check() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		$q = "SELECT ".$this->_tbl_key."
			FROM ".$this->_tbl."
			WHERE file_product_id = ".$this->file_product_id."
			AND file_name = '".$this->file_name."'";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('CHECK_PRODUCT_FILE_EXISTS'), true);
		$db->query();
		
		/* There should only be 1 file that is the same */
		if ($db->getAffectedRows() == 1) $this->file_id = $db->loadResult();
	}
}
?>
