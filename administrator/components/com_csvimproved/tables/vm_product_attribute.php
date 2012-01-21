<?php
/**
 * Virtuemart Product Attribute table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_product_attribute.php 939 2009-06-24 07:47:00Z Roland $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_product_attribute extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_product_attribute', 'attribute_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_product_attribute'));
	}
	
	/**
	 * Stores a category relation
	 *
	 * The product relation is always inserted
	 */
	public function store() {
		$db = JFactory::getDBO();
		$k = $this->check();

		if($k)
		{
			$ret = $db->updateObject( $this->_tbl, $this, $this->_tbl_key, false );
		}
		else
		{
			$ret = $db->insertObject( $this->_tbl, $this, $this->_tbl_key );
		}
		if( !$ret )
		{
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/**
	 * Check if a product attribute already exists. If so, get the attribute ID
	 *
	 * Criteria for an existing attribute:
	 * - product_id
	 * - attribute_name
	 * - attribute_value
	 */
	public function check() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check if the attribute in the database */
		$q = "SELECT ".$this->_tbl_key."
			FROM ".$this->_tbl."
			WHERE product_id = '".$this->product_id."'
			AND attribute_name = '".$this->attribute_name."' 
			AND attribute_value = '".$this->attribute_value."'";
		$db->setQuery($q);
		$db->query($q);
		$csvilog->AddMessage('debug', JText::_('DEBUG_CHECK_ATTRIBUTE_EXISTS'), true);
		if ($db->getAffectedRows() > 0) {
			$this->attribute_id = $db->loadResult();
			return true;
		}
		else return false;
	}
}
?>