<?php
/**
 * Virtuemart User Info table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_user_info.php 955 2009-08-13 22:23:26Z Roland $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_user_info extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_user_info', 'user_info_id', $db );
	}
	
	/**
	 * Cleans the class variables
	 */
	public function reset() {
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_user_info'));
	}
	
	/**
	 * Stores user information
	 */
	public function store() {
		$db = JFactory::getDBO();
		
		if($this->check()) {
			$ret = $db->updateObject( $this->_tbl, $this, $this->_tbl_key, false );
		}
		else {
			$ret = $db->insertObject( $this->_tbl, $this, $this->_tbl_key );
		}
		if(!$ret) {
			$this->setError(get_class( $this ).'::store failed - '.$db->getErrorMsg());
			return false;
		}
		else  return true;
	}
	
	/**
	 * Check if user info already exists 
	 *
	 * Criteria for existing user info:
	 * - user_id
	 * - address_type
	 * - address_type_name
	 */
	public function check() {
		/* See if we already have user_info_id */
		if (empty($this->user_info_id)) {
			$db = JFactory::getDBO();
			$csvilog = JRequest::getVar('csvilog');
			
			/* Check if a record already exists in the database */
			$q = "SELECT ".$this->_tbl_key."
				FROM ".$this->_tbl."
				WHERE user_id = '".$this->user_id."'
				AND address_type = '".$this->address_type."' 
				AND address_type_name = ".$db->Quote($this->address_type_name);
			$db->setQuery($q);
			$db->query($q);
			$csvilog->AddMessage('debug', JText::_('Checking if user already exists'), true);
			if ($db->getAffectedRows() > 0) {
				$this->user_info_id = $db->loadResult();
				return true;
			}
			else {
				/* There is no entry yet, so we must insert a new one */
				$this->user_info_id = md5(uniqid(rand(), true));
				return false;
			}
		}
		/* There is already a user_info_id, only update the record */
		else return true;
	}
}
?>