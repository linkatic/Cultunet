<?php
/**
 * Logs table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvi_logs.php 902 2009-05-10 01:50:10Z Suami $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 */
class TableCsvi_logs extends JTable {
	/** @var int Primary key */
	public $id = 0;
	/** @var int Joomla userid */
	public $userid = null;
	/** @var mixed Timestamp import/export took place */
	public $logstamp = null;
	/** @var string The action import or export */
	public $action = null;
	/** @var string The type of action  */
	public $action_type = null;
	/** @var string The name of the template used  */
	public $template_name = null;
	/** @var string The number of processed records  */
	public $records = 0;
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		parent::__construct('#__csvi_logs', 'id', $db );
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