<?php
/**
 * Virtuemart Shopper Vendor cross table
 *
 * @package CSVImproved
 * @subpackage Tables
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_user_info.php 665 2009-01-02 07:40:08Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 * @subpackage Tables
 */
class TableVm_shopper_vendor_xref extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_shopper_vendor_xref', 'user_id', $db );
	}
	
	/**
	 * Cleans the class variables
	 */
	public function reset() {
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_shopper_vendor_xref'));
	}
	
	/**
	 * Stores a shopper vendor relation
	 */
	public function store() {
		$csvilog = JRequest::getVar('csvilog');
		
		if($this->check()) {
			$ret = $this->_db->updateObject( $this->_tbl, $this, $this->_tbl_key, false );
		}
		else {
			$ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key );
		}
		if(!$ret) return false;
		else return true;
	}
	
	/**
	 * Check if the shopper <--> vendor relation exists
	 */
	public function check() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		/* Check if the shopper vendor xref is in the database */
		$q = "SELECT ".$this->_tbl_key."
			FROM ".$this->_tbl."
			WHERE ".$db->nameQuote('vendor_id')." = ".$db->Quote($this->vendor_id)."
			AND ".$db->nameQuote('shopper_group_id')." = ".$db->Quote($this->shopper_group_id)."
			AND ".$db->nameQuote('user_id')." = ".$db->Quote($this->user_id);
		$db->setQuery($q);
		$db->query();
		$csvilog->AddMessage('debug', JText::_('DEBUG_SHOPPER_VENDOR_EXISTS'), true);
		if ($db->getAffectedRows() > 0) {
			return true;
		}
		else {
			$this->customer_number = uniqid( rand() );
			return false;
		}
	}
}
?>