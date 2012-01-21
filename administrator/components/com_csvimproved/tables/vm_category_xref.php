<?php
/**
 * Virtuemart Category Cross reference table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_category_xref.php 666 2009-01-02 07:55:31Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_category_xref extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_category_xref', 'category_parent_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_category'));
	}
	
	/**
	 * Stores a category relation
	 *
	 * The product relation is always inserted
	 */
	public function store() {
		$k = $this->check();

		if($k)
		{
			$ret = $this->_db->updateObject( $this->_tbl, $this, $this->_tbl_key, false );
		}
		else
		{
			$ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key );
		}
		if( !$ret )
		{
			$this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/**
	 * Check if a relation already exists
	 */
	public function check() {
		$q = "SELECT COUNT(".$this->_tbl_key.") AS total
			FROM ".$this->_tbl."
			WHERE category_parent_id = ".$this->category_parent_id."
			AND category_child_id = ".$this->category_child_id;
		$this->_db->setQuery($q);
		$result = $this->_db->loadResult();
		
		if ($result > 0) return true;
		else return false;
	}
}
?>