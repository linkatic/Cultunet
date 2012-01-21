<?php
/**
 * Virtuemart Coupons table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_product_files.php 666 2009-01-02 07:55:31Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_coupons extends JTable {
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_coupons', 'coupon_id', $db );
	}
	
	/**
	 * Cleans the class variables
	 */
	public function reset() {
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_coupons'));
	}
	
	/**
	 * Check if a coupon already exists
	 */
	public function check() {
		if (isset($this->coupon_id)) return true;
		else {
			$db = JFactory::getDBO();
			$csvilog = JRequest::getVar('csvilog');
			$q = "SELECT ".$this->_tbl_key."
				FROM ".$this->_tbl."
				WHERE coupon_code='".$this->coupon_code."'";
			$db->setQuery($q);
			$csvilog->AddMessage('debug', JText::_('CHECK_COUPON_CODE_EXISTS'), true);
			$db->query();
			if ($db->getAffectedRows() > 0) {
				$this->coupon_id = $db->loadResult();
			}
		}
	}
}
?>
