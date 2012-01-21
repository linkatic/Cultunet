<?php
/**
 * Log details table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvi_log_details.php 665 2009-01-02 07:40:08Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableCsvi_log_details extends JTable {
	/** @var int Primary key */
	var $id = 0;
	/** @var int The ID of the log entry */
	var $log_id = null;
	/** @var string Description of the logging entry */
	var $description = null;
	/** @var int The result of the entry */
	var $result = null;
	/** @var string The status of action  */
	public $status = null;
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		parent::__construct('#__csvi_log_details', 'id', $db );
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