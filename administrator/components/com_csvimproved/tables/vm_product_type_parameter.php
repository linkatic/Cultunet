<?php
/**
 * Virtuemart Product Type Parameter table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: vm_product_type_parameter.php 869 2009-04-14 14:00:35Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableVm_product_type_parameter extends JTable {
	
	/* Private variable declaration */
	private $db_change = null;
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		$this->reset();
		parent::__construct('#__vm_product_type_parameter', 'product_type_id', $db );
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
		$this->setProperties(CsvimprovedModelAvailablefields::DbFields('vm_product_type_parameter'));
	}
	
	/**
	 * Stores a product type parameter
	 *
	 * In addition to storing the product type parameter the concerned table
	 * is also updated. This table is #__vm_product_type_<id>. Further an index
	 * is created for the new parameter type.
	 *
	 * @todo Add more logging
	 * @todo Error checking
	 */
	public function store() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$dbfields = CsvimprovedModelAvailablefields::DbFields('vm_product_type_parameter');
		
		/* Check if it will be an update or an add */
		$k = $this->check();
		
		/* Execute the update */
		if($k) {
			$q = "UPDATE ".$this->_tbl." SET ";
			foreach ($dbfields as $name => $details) {
				if (!empty($this->$name)
					&& $name != 'product_type_id'
					&& $name != 'paramater_name') {
					$q .= $db->nameQuote($name)." = ".$db->Quote($this->$name).', ';
				}
			}
			$q = substr($q, 0, -2);
			$q .= " WHERE product_type_id = ".$this->product_type_id."
				AND parameter_name = ".$db->Quote($this->parameter_name);
			$db->setQuery($q);
			$ret = $db->query();
			$csvilog->AddMessage('debug', JText::_('UPDATE_PRODUCT_TYPE_PARAMETER'), true);
			if ($this->parameter_type != "B") {
				$this->ColumnUpdate();
				$this->CreateIndex();
			}
		}
		/* Execute the add */
		else {
			$ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key );
			$csvilog->AddMessage('debug', JText::_('INSERT_PRODUCT_TYPE_PARAMETER'), true);
			if ($this->parameter_type != "B") {
				$this->ColumnAdd();
				$this->CreateIndex();
			}
		}
		
		/* Check the result */
		if(!$ret) {
			$csvilog->AddStats('incorrect', JText::_('PRODUCT_TYPE_PARAMETER_STORE_FAILED'), true);
			$this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
			return false;
		}
		else {
			$csvilog->AddStats($this->db_change, JText::_('PRODUCT_TYPE_PARAMETER_STORE_SUCCESS'), true);
			return true;
		}
	}
	
	/**
	 * Check if a relation already exists
	 */
	public function check() {
		$db = JFactory::getDBO();
		$q = "SELECT COUNT(".$this->_tbl_key.") AS total
			FROM ".$this->_tbl."
			WHERE product_type_id = ".$this->product_type_id."
			AND parameter_name = ".$db->Quote($this->parameter_name);
		$db->setQuery($q);
		$result = $db->loadResult();
		
		if ($result > 0) {
			
			$this->db_change = 'updated';
			return true;
		}
		else {
			$this->db_change = 'added';
			return false;
		}
	}
	
	/**
	 * Function to drop an index
	 */
	private function DropIndex() {
		$csvilog = JRequest::getVar('csvilog');
		$db = JFactory::getDBO();
		$q  = "ALTER TABLE `#__vm_product_type_";
		$q .= $this->product_type_id."` DROP INDEX `idx_product_type_".$this->product_type_id."_";
		$q .= $this->parameter_name."`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('DROP_PRODUCT_TYPE_PARAMETER_INDEX'), true);
		$db->query();
	}
	
	/**
	 * Update column settings for table product_type_<id>
	 */
	private function ColumnUpdate() {
		$csvilog = JRequest::getVar('csvilog');
		$db = JFactory::getDBO();
		$q  = "ALTER TABLE `#__vm_product_type_";
		$q .= $this->product_type_id . "` MODIFY COLUMN `";
		$q .= $this->parameter_name."` ";
		$q .= $this->DbFieldType();
		if ($this->parameter_default != "" && $this->parameter_type != "T") {
			$q .= "DEFAULT '".$this->parameter_default."' NOT NULL;";
		}
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('MODIFY_PRODUCT_TYPE_PARAMETER_COLUMN'), true);
		$db->query();
	}
	
	/**
	 * Add a column for table product_type_<id>
	 */
	private function ColumnAdd() {
		$csvilog = JRequest::getVar('csvilog');
		$db = JFactory::getDBO();
		$q = "ALTER TABLE `#__vm_product_type_";
		$q .= $this->product_type_id . "` ADD `";
		$q .= $this->parameter_name."` ";
		$q .= $this->DbFieldType();
		if ($this->parameter_default != "" && $this->parameter_type != "T") {
			$q .= "DEFAULT '".$this->parameter_default."' NOT NULL;";
		}
		$db->setQuery($q);
		$csvilog->AddMessage('debug', JText::_('ADD_PRODUCT_TYPE_PARAMETER_COLUMN'), true);
		$db->query();
	}
	
	/**
	 * Get paramter type for field type
	 */
	private function DbFieldType() {
		switch( $this->parameter_type ) {
			/* Integer */
			case "I": return "int(11) "; break;
			/* Text */
			case "T": return "text "; break;
			/* Float */
			case "F": return "float "; break;
			/* Char */
			case "C": return "char(1) "; break;
			/* Date time */
			case "D": return "datetime "; break;
			/* Date */
			case "A": return "date "; break;
			/* Time */
			case "M": return "time "; break;
			/* Short Text */
			case "S":
			/* Multiple Values */
			case "V":
			default: 
				return "varchar(255) ";
				break;
		}
	}
	
	/**
	 * Create an index for the field
	 */
	private function CreateIndex() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		/* Load current indexes */
		$q = "SHOW INDEX FROM #__vm_product_type_".$this->product_type_id;
		$db->setQuery($q);
		$indexes = $db->loadObjectList('Key_name');
		
		if (!isset($indexes['idx_product_type_'.$this->product_type_id.'_'.$this->parameter_name])) {
			if ($this->parameter_type == "T") {
				$q  = "ALTER TABLE `#__vm_product_type_";
				$q .= $this->product_type_id."` ADD FULLTEXT `idx_product_type_".$this->product_type_id."_";
				$q .= $this->parameter_name."` (`".$this->parameter_name."`);";
				$db->setQuery($q);
				$db->query();
			}
			else {
				$q  = "ALTER TABLE `#__vm_product_type_";
				$q .= $this->product_type_id."` ADD KEY `idx_product_type_".$this->product_type_id."_";
				$q .= $this->parameter_name."` (`".$this->parameter_name."`);";
				$db->setQuery($q);
				$db->query();
			}
			$csvilog->AddMessage('debug', JText::_('CREATE_PRODUCT_TYPE_PARAMETER_INDEX'), true);
		}
	}
	
	/**
	 * Deletes a product type parameter and its associated data
	 */
	public function delete() {
		/* Delete the entry from the #__vm_product_type_parameter */
		if ($this->DeleteParam()) {
			/* Delete the column form the #__vm_product_type_<id> */
			if ($this->DeleteProductTypeParam()) return true;
			else return false;
		}
		else return false;
	}
	
	/**
	 * Delete a product type parameter from the #__vm_product_type_parameter table
	 */
	private function DeleteParam() {
		$db = JFactory::getDBO();
		$k = $this->_tbl_key;
		$q = 'DELETE FROM '.$db->nameQuote( $this->_tbl ).
			' WHERE '.$this->_tbl_key.' = '. $db->Quote($this->$k).'
			AND parameter_name = '.$db->Quote($this->parameter_name);
		$db->setQuery($q);
		return $db->query();
	}
	
	/**
	 * Delete a product type parameter from the #__vm_product_type_<id> table
	 */
	private function DeleteProductTypeParam() {
		$db = JFactory::getDBO();
		$q = 'ALTER TABLE '.$db->nameQuote( '#__vm_product_type_'.$this->product_type_id ).
			' DROP COLUMN '.$db->nameQuote($this->parameter_name);
		$db->setQuery($q);
		return $db->query();
	}
}
?>
