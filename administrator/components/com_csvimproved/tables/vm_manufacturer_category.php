<?php
/**
 * Virtuemart Manufacturer table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_manufacturer_category.php 869 2009-04-14 14:00:35Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_manufacturer_category extends JTable {
	
	/* Private variables */
	/** @var string contains which field is the key field */ 
	private $mf_update = null;
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_manufacturer_category', 'mf_category_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_manufacturer_category'));
	}
	
	/**
	 * Stores a category relation
	 *
	 * The manufacturer name takes precedence over the ID
	 * See if the user wants to update the manufacturer
	 * Check for default values and if both are set or only one
	 */
	public function store() {
		$db = JFactory::getDBO();
		if ($this->checkKey()) {
			$k = $this->check();
			
			if($k)
			{
				$ret = $db->updateObject( $this->_tbl, $this, $this->mf_update, false );
			}
			else
			{
				$ret = $db->insertObject( $this->_tbl, $this, $this->mf_update );
			}
			if( !$ret )
			{
				$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
				return false;
			}
			else
			{
				/* Fix the values when doing a name update */
				if ($this->mf_update == 'mf_category_name'
					&& !empty($this->manufacturer_id)
					&& !$k) {
					$this->manufacturer_id = $this->mf_name;
				}
				return true;
			}
		}
		else return false;
	}
	
	/**
	 * Check which field to use as primary key
	 *
	 * Update/Insert follows these rules:
     * Name exists, ID exists -> ID is updated if different
     * Name exists, ID does not exist -> New manufacturer created with increment ID
     * Name does not exist, ID exists  -> ID is updated
     * Name does not exist, ID does not exist -> Do nothing
	 *
	 * @link http://www.csvimproved.com/wiki/doku.php/csvimproved:availablefields:manufacturer_id
	 * @link http://www.csvimproved.com/wiki/doku.php/csvimproved:availablefields:manufacturer_name
	 */
	private function checkKey() {
		 /* Check what to update */
		if (!empty($this->mf_category_name)) {
			// if ($csviregistry->GetVar('debug')) $csvilog->AddMessage('debug', 'Manufacturer update by name');
			$this->mf_update = 'mf_category_name';
			return true;
		}
		else if (!empty($this->mf_category_id)) {
			// if ($csviregistry->GetVar('debug')) $csvilog->AddMessage('debug', 'Manufacturer update by ID');
			$this->mf_update = $this->_tbl_key;
			return true;
		}
		else return false;
	}
	
	/**
	 * Check if the manufacturer category exists
	 *
	 * @todo Add check for lowest manufacturer category
	 */
	public function check() {
		$db = JFactory::getDBO();
		$update = $this->mf_update;
		/* Check if the attribute in the database */
		$q = "SELECT ".$this->_tbl_key."
			FROM ".$this->_tbl."
			WHERE `".$this->mf_update."` = '".$this->$update."'";
		// $csvilog->AddMessage('debug', 'Discount exists, return discount id: <a onclick="switchMenu(\''.$this->product_sku.'_discount_id\');" title="Show/hide query">Show/hide query</a><div id="'.$this->product_sku.'_discount_id" style="display: none; border: 1px solid #000000; padding: 5px;">'.htmlentities($q_discount).'</div>');
		$db->setQuery($q);
		$db->query($q);
		if ($db->getAffectedRows() > 0) {
			if (is_null($this->mf_category_id) ||
				empty($this->mf_category_id)) $this->mf_category_id = $db->loadResult();
			return true;
		}
		else return false;
	}
}
?>