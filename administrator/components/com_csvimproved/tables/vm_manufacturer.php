<?php
/**
 * Virtuemart Manufacturer table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_manufacturer.php 946 2009-08-03 09:47:37Z Roland $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_manufacturer extends JTable {
	
	/* Private variables */
	/** @var string contains which field is the key field */ 
	private $mf_update = null;
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_manufacturer', 'manufacturer_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_manufacturer'));
	}
	
	/**
	 * Stores a category relation
	 *
	 * The manufacturer name takes precedence over the ID
	 * See if the user wants to update the manufacturer
	 * Check for default values and if both are set or only one
	 */
	public function store() {
		if ($this->checkKey()) {
			$k = $this->check();
			
			if($k)
			{
				$ret = $this->_db->updateObject( $this->_tbl, $this, $this->mf_update, false );
			}
			else
			{
				$ret = $this->_db->insertObject( $this->_tbl, $this, $this->mf_update );
			}
			if( !$ret ) {
				return false;
			}
			else {
				/* Fix the values when doing a name update */
				if ($this->mf_update == 'mf_name'
					&& is_int($this->mf_name)) {
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
     * Name exists, ID exists –> ID is updated if different
     * Name exists, ID does not exist –> New manufacturer created with increment ID
     * Name does not exist, ID exists –> ID is updated
     * Name does not exist, ID does not exist –> Do nothing
	 *
	 * @link http://www.csvimproved.com/wiki/doku.php/csvimproved:availablefields:manufacturer_id
	 * @link http://www.csvimproved.com/wiki/doku.php/csvimproved:availablefields:manufacturer_name
	 */
	private function checkKey() {
		$csvilog = JRequest::getVar('csvilog');
		 /* Check what to update */
		if (!empty($this->mf_name)) {
			$csvilog->AddMessage('debug', 'Manufacturer update by name');
			$this->mf_update = 'mf_name';
			return true;
		}
		else if (!empty($this->manufacturer_id)) {
			$csvilog->AddMessage('debug', 'Manufacturer update by ID');
			$this->mf_update = $this->_tbl_key;
			return true;
		}
		else {
			$csvilog->AddMessage('debug', JText::_('Manufacturer update not matching name or key'));
			return false;
		}
	}
	
	/**
	 * Check if the manufacturer exists
	 */
	public function check() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$update = $this->mf_update;
		/* Check if the attribute in the database */
		$q = "SELECT ".$this->_tbl_key."
			FROM ".$this->_tbl."
			WHERE `".$this->mf_update."` = ".$db->Quote($this->$update);
		$db->setQuery($q);
		$db->query();
		if ($db->getAffectedRows() > 0) {
			$csvilog->AddMessage('debug', JText::_('DEBUG_MANUFACTURER_EXISTS'), true);
			$this->manufacturer_id = $db->loadResult();
			return true;
		}
		else {
			$csvilog->AddMessage('debug', JText::_('DEBUG_MANUFACTURER_NOT_EXISTS'), true);
			return false;
		}
	}
}
?>