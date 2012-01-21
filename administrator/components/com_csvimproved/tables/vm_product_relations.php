<?php
/**
 * Virtuemart Related Products table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_product_relations.php 666 2009-01-02 07:55:31Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_product_relations extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_product_relations', 'product_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_product_relations'));
	}
	
	/**
	 * Stores a product relation
	 *
	 * The product relation is always inserted
	 */
	public function store() {
		$db = JFactory::getDBO();
		$this->delete();
		$ret = $db->insertObject( $this->_tbl, $this, $this->_tbl_key );
		
		if(!$ret) {
			// $this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}
		else {
			return true;
		}
	}
}
?>