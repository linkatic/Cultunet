<?php
/**
 * Templates table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvi_template_fields.php 665 2009-01-02 07:40:08Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableCsvi_template_fields extends JTable {
	/** @var int Primary key */
	var $id = 0;
	/** @var int */
	var $field_template_id = null;
	/** @var string */
	var $field_name = null;
	/** @var string */
	var $field_column_header = 0;
	/** @var string */
	var $field_default_value = 0;
	/** @var int */
	var $field_order = 0;
	/** @var int */
	var $published = 0;
	/** @var int */
	var $checked_out = 0;
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		parent::__construct('#__csvi_template_fields', 'id', $db );
	}
	
	/**
	 * Cleans the class variables
	 */
	public function reset() {
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') $this->$name = $value;
		}
	}
}
?>