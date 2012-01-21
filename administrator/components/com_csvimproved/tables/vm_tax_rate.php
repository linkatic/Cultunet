<?php
/**
 * Tax rate table
 *
 * @package CSVImproved
 * @subpackage Tables
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_tax_rate.php 691 2009-01-16 03:35:04Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 * @subpackage Tables
 */
class TableVm_tax_rate extends JTable {
	
	/* Private variables */
	/** @var string contains which field is the key field */ 
	private $tax_update = null;
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_tax_rate', 'tax_rate_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_tax_rate'));
	}
	
	/**
	 * Stores/Updates a tax rate
	 *
	 * Update/Insert follows these rules:
     * Tax rate exists, ID exists -> ID is updated if different
     * Tax rate exists, ID does not exist -> New tax rate created with increment ID
     * Tax rate does not exist, ID exists -> Do nothing
     * Tax rate does not exist, ID does not exist -> Do nothing
	 *
	 * @link http://www.csvimproved.com/available-fields/producttaxid.html
	 * @link http://www.csvimproved.com/available-fields/producttax.html
	 */
	public function store() {
		$k = $this->check();
		
		if($k)
		{
			$ret = $this->_db->updateObject( $this->_tbl, $this, $this->_tbl_key, false );
		}
		else
		{
			$ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key);
		}
		if( !$ret )
		{
			$this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	/**
	 * Check if a tax rate already exists
	 *
	 * Criteria for an existing tax rate are:
	 * - tax rate
	 * If exists, tax rate will be updated
	 */
	public function check() {
		$db = JFactory::getDBO();
		/* Check for tax country */
		if (empty($this->tax_country)) $this->tax_country = '';
		/* Check for tax state */
		if (empty($this->tax_state)) $this->tax_state = '';
		
		/* Check for tax rate id */
		$q = "SELECT ".$this->_tbl_key."
			FROM ".$this->_tbl."
			WHERE tax_rate='".$this->tax_rate."'";
		$db->setQuery($q);
		$db->query($q);
		if ($db->getAffectedRows() > 0) {
			$this->tax_rate_id = $db->loadResult();
			return true;
		}
		else return false;
	}
}
?>