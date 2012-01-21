<?php
/**
 * Virtuemart Product Type table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_product_type.php 869 2009-04-14 14:00:35Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_product_type extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_product_type', 'product_type_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_product_type'));
	}
	
	/**
	 * Check if a product type already exists
	 *
	 * Criteria for an existing product type are:
	 * - product id
	 * - shopper group id
	 * If both exists, price will be updated
	 */
	public function check() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$q = "SELECT ".$this->_tbl_key."
			FROM ".$this->_tbl."
			WHERE product_type_name='".$this->product_type_name."'";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('CHECK_PRODUCT_TYPE_NAME_EXISTS'), true);
		$db->query();
		if ($db->getAffectedRows() > 0) {
			$this->product_type_id = $db->loadResult();
		}
	}
}
?>
