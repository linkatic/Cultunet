<?php
/**
 * Virtuemart Product Manufacturer Cross reference table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_product_mf_xref.php 930 2009-06-07 17:19:20Z Roland $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_product_mf_xref extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_product_mf_xref', 'product_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_product_mf_xref'));
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
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$q = "SELECT COUNT(".$this->_tbl_key.") AS total
			FROM ".$this->_tbl."
			WHERE product_id = ".$this->product_id;
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('DEBUG_CHECK_MF_PROD_EXISTS'), true);
		$result = $db->loadResult();
		if ($result > 0) return true;
		else return false;
	}
}
?>