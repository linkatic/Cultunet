<?php
/**
 * Joomla User ARO table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_manufacturer.php 869 2009-04-14 14:00:35Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableCore_acl_aro extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__core_acl_aro', 'id', $db );
	}
	
	/**
	 * Cleans the class variables
	 */
	public function reset() {
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('core_acl_aro'));
	}
	
	public function check() {
		$db = JFactory::getDBO();
		$q = "SELECT ".$this->_tbl_key."
			FROM ".$this->_tbl."
			WHERE `section_value` = ".$db->Quote($this->section_value)."
			AND `value` = ".$db->Quote($this->value);
		$db->setQuery($q);
		$this->id = $db->loadResult();
	}
}
?>