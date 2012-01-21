<?php
/**
 * Logging class
 *
 * @package CSVImproved
 * @subpackage Log
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvi_class_log.php 933 2009-06-12 19:06:46Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * The central logging class
 *
 * @package CSVImproved
 * @subpackage Log
 */
class CsviLog {
	
	/* Private variables */
	private $_linenumber = 1;
	
	/* Public variables */
	/** @var string contains the log messages */
	public $logmessage = '';
	/** @var string contains the debug messages */
	public $debug_message = '';
	/** @var array contains the import statistics */
	public $stats = array();
	
	function __construct() {
	}
	
	/**
	 * Set the current line number
	 */
	public function setLinenumber() {
		$this->_linenumber = JRequest::getInt('currentline');
		return true;
	}
	
	/**
	 * Adds a message to the log stack
	 *
	 * @param string $type type of message
	 * @param string $message message to add to the stack
	 * @param boolean $sql if true adds a div box with the sql statement
	 */
	function AddMessage($type, $message, $sql=false) {
		switch ($type) {
			case 'debug':
				if (JRequest::getVar('debug')) {
					$this->debug_message .= $message;
					if ($sql) {
						$db = JFactory::getDBO();
						if ($db->getErrorNum() > 0) {
							$this->debug_message .= '<span class="sqlerror">'.JText::_('SQL_ERROR').'</span> ';
							$querymsg = $db->getErrorMsg();
						}
						else $querymsg = $db->getQuery();
						$rid = rand();
						$this->debug_message .= '<a onclick="jQuery(\'#'.$rid.'\').toggle();" title="'.JText::_('SHOW_HIDE_QUERY').'"> '.JText::_('SHOW_HIDE_QUERY').'</a><div id="'.$rid.'" class="debug">'.$querymsg.'</div>';
					}
					if ($message != '<hr />') $this->debug_message .= "<br />\n";
				}
				break;
			default:
				$this->logmessage .= $message."<br />\n";
				break;
		}
	}
	/**
	* Adds a message to the statistics stack
	*
	* <p>
	* Types:
	* --> Products
	* updated
	* deleted
	* added
	* skipped
	* incorrect
	* --> DB tables
	* empty
	* --> Fields
	* nosupport
	* --> No files found multiple images
	* nofiles
	* --> General information
	* information
	* </p>
	*
	* @param string $type type of message
	* @param string $message message to add to the stack
	* @param boolean $line prefix linenumber to message
	*/
	function AddStats($type, $message, $line=false) {
		/* Set the result */
		$success = array('updated', 'deleted', 'added', 'empty');
		$failure = array('skipped', 'incorrect', 'nosupport', 'nofiles');
		$notice = array('information');
		if (in_array($type, $success)) $this->stats[$this->_linenumber]['result'] = JText::_('SUCCESS');
		else if (in_array($type, $failure)) $this->stats[$this->_linenumber]['result'] = JText::_('FAILURE');
		else if (in_array($type, $notice)) $this->stats[$this->_linenumber]['result'] = JText::_('NOTICE');
		
		/* Set the message */
		if ($line) $message = str_replace('{currentline}', $this->_linenumber, JText::_('LINENUMBER')).' '.$message;
		
		if (!isset($this->stats[$this->_linenumber]['status'][$type])) {
			$this->stats[$this->_linenumber]['status'][$type]['message'] = $message."<br />\n";
		}
		else {
			$this->stats[$this->_linenumber]['status'][$type]['message'] .= $message."<br />\n";
		}
	}
	
	/**
	 * Retrieves the log message
	 * @return string returns the log message
	 */
	function GetLogMessage() {
		return $this->logmessage;
	}
	
	/**
	 * Retrieves the debug message
	 * @return string returns the debug message
	 */
	function GetDebugMessage() {
		return $this->debug_message;
	}
	
	/**
	 * Retrieves the statistics array
	 * @return array returns the statistics array
	 */
	function GetStats() {
		return $this->stats;
	}
	
	/**
	 * Set the type of action the log is for
	 */
	public function SetAction($action) {
		$this->stats['action'] = strtolower($action);
	}
	
	/**
	 * Set the type of action the log is for
	 */
	public function SetActionType($action, $template_name='') {
		$this->stats['action_type'] = strtolower($action);
		$this->stats['action_template'] = $template_name;
	}
}
?>
