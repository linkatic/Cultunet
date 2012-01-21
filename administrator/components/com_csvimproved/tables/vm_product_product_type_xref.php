<?php
/**
 * Virtuemart Product Type Cross reference table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_product_product_type_xref.php 869 2009-04-14 14:00:35Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 * @todo Jtext strings
 */
class TableVm_product_product_type_xref extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_product_product_type_xref', 'product_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_product_product_type_xref'));
	}
	
	/**
	 * Store a value
	 */
	public function store() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		if (!$this->check()) {
			$q = "INSERT INTO ".$db->nameQuote( $this->_tbl )."
				VALUES (".$db->Quote($this->product_id).", ".$db->Quote($this->product_type_id).")";
			$db->setQuery($q);
			return $db->query();
		}
		else {
			$csvilog->AddMessage('debug', JText::_('CROSS_REFERENCE_EXISTS'));
		}
	}
	
	/**
	 * Function to check if cross reference already exists
	 */
	public function check() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$q = "SELECT COUNT(product_id) AS total
			FROM ".$db->nameQuote( $this->_tbl )."
			WHERE product_id = ".$db->Quote($this->product_id)."
			AND product_type_id = ".$db->Quote($this->product_type_id);
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('PRODUCT_TYPE_XREF_CHECK'), true);
		if ($db->loadResult() > 0) return true;
		else return false;
	}
}
?>