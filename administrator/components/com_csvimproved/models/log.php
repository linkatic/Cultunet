<?php
/**
 * Log model
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: log.php 946 2009-08-03 09:47:37Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Import Model
 *
 * @package CSVImproved
 */
class CsvimprovedModelLog extends JModel {
	
	/** @var int holds the log ID */
	private $_id = null;
	/** @var object holds the pagination */
	private $_page = null;
	/** @var int holds the total number of items in database */
	private $_limittotal = null;
	/** @var int holds the number of items to display */
	private $_limit = null;
	/** @var int holds the offset where to start */
	private $_limitstart = null;
	
	/**
	 * Set the log ID
	 */
	public function setId($id) {
		 $this->_id = $id;
	}
	/**
	 * Load the log entries
	 *
	 */
	public function getLogEntries() {
		$db = JFactory::getDBO();
		$q = "SELECT * 
			FROM #__csvi_logs
			ORDER BY logstamp DESC";
		$db->setQuery($q, $this->_page->limitstart, $this->_page->limit);
		$logs =  $db->loadObjectList();
		if ($db->getErrorNum() == 0) {
			return $logs;
		}
		else {
			JError::raiseWarning(0, JText::_('Cannot load logs'));
			return false;
		}
	}
	
	/**
	 * Load the log details
	 */
	public function getLogDetails() {
		$db = JFactory::getDBO();
		$q = "SELECT * 
			FROM #__csvi_log_details
			WHERE log_id = ".$this->_id;
		$db->setQuery($q);
		if ($db->query()) {
			return $db->loadObjectList();
		}
		else {
			JError::raiseWarning(0, JText::_('Cannot load log details'));
			return false;
		}
	}
	
	/**
	 * Get log statistics
	 */
	public function getLogStatistics() {
		$db = JFactory::getDBO();
		$q = "SELECT status, COUNT(status) AS total
			FROM `#__csvi_log_details`
			WHERE log_id = ".$this->_id." 
			GROUP BY log_id, status";
		$db->setQuery($q);
		if ($db->query()) return $db->loadObjectList();
		else {
			JError::raiseWarning(0, JText::_('Cannot load log statistics'));
			return false;
		}
	}
	
	/**
	 * Load total of records
	 */
	public function getTotal() {
		$db = JFactory::getDBO();
		$q = "SELECT COUNT(id) AS total FROM #__csvi_logs";
		$db->setQuery($q);
		$this->_limittotal = $db->loadResult();
		
		if ($db->getErrorNum() > 0) {
			JError::raiseWarning(0, JText::_('Cannot get total logs').' :: '.$db->getErrormsg());
			$this->_limittotal = 0;
		}
	}
	
	/**
	 * Load the pagination
	 */
	public function getPagination() {
      global $mainframe;
		/* Load totals */
		$this->getTotal();
		
      /* Lets load the pagination if it doesn't already exist */
      if (empty($this->_page)) {
         jimport('joomla.html.pagination');
         $this->_limit      = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
         $this->_limitstart = $mainframe->getUserStateFromRequest( 'com_csvimproved.limitstart', 'limitstart', 0, 'int' );
         $this->_page = new JPagination($this->_limittotal, $this->_limitstart, $this->_limit);
      }
      return $this->_page;
	}
	
	/**
	 * Store the log results after import/export
	 */
	public function getStoreLogResults() {
		$csvilog = JRequest::getVar('csvilog');
		$template = JRequest::getVar('template', false);
		$logresult = $csvilog->GetStats();
		$details = array();
		$logcount = array();
		if ($template && stristr($template->template_type, 'import')) {
			foreach($logresult as $linenr => $row) {
				if (is_array($row)) {
					if (isset($row['result'])) {
						if (!isset($logcount[$logresult['action']])) $logcount[$logresult['action']] = 1;
						else $logcount[$logresult['action']]++;
					}
					else {
						foreach ($row['status'] as $status => $data) {
							if (!isset($logcount[$logresult['action']])) $logcount[$logresult['action']] = 1;
							else $logcount[$logresult['action']]++;
						}
					}
				}
			}
			/* Store the logcount for insert into log table */
			JRequest::setVar('logcount', $logcount);
		}
		else {
			$logcount = JRequest::getVar('logcount');
		}
		
		/* Get user ID */
		$my = JFactory::getUser();
		$details['userid'] = $my->id;
		/* Create GMT timestamp */
		jimport('joomla.utilities.date');
		$jnow = new JDate(time());
		$details['logstamp'] = $jnow->toMySQL();
		/* Set action if it is import or export */
		$details['action'] = $logresult['action'];
		/* Type of action */
		$details['action_type'] = $logresult['action_type'];
		/* Name of template used */
		$details['template_name'] = $logresult['action_template'];
		/* Get the number of records */
		if (isset($logcount[$logresult['action']])) $details['records'] = $logcount[$logresult['action']];
		else $details['records'] = 0;
		
		/* Get the database connector */
		$rowlog = $this->getTable('csvi_logs');
		/* Bind the data */
		if (!$rowlog->bind($details)) {
			JError::raiseWarning(0, JText::_('CANNOT_BIND_LOG_DATA'));
		}
		/* Check the data */
		if (!$rowlog->check()) {
			JError::raiseWarning(0, JText::_('CANNOT_CHECK_LOG_DATA'));
		}
		
		/* Store the data */
		if (!$rowlog->store()) {
			JError::raiseWarning(0, JText::_('CANNOT_STORE_LOG_DATA'));
		}
		
		/* Store the log details */
		$rowlogdetails = $this->getTable('csvi_log_details');
		$details = array();
		foreach ($logresult as $key => $result) {
			if (is_int($key)) {
				$details['log_id'] = $rowlog->id;
				$details['result'] = $result['result'];
				foreach ($result['status'] as $type => $stat) {
					$details['status'] = $type;
					$details['description'] = $stat['message'];
					/* Bind the data */
					if (!$rowlogdetails->bind($details)) {
						JError::raiseWarning(0, JText::_('CANNOT_BIND_LOG_DETAILS_DATA'));
					}
					/* Check the data */
					if (!$rowlogdetails->check()) {
						JError::raiseWarning(0, JText::_('CANNOT_BIND_LOG_DETAILS_DATA'));
					}
					/* Store the data */
					if (!$rowlogdetails->store()) {
						JError::raiseWarning(0, JText::_('CANNOT_BIND_LOG_DETAILS_DATA'));
					}
					$rowlogdetails->reset();
				}
			}
		}
		$rowlog->reset();
	}
	
	/**
	 * Delete a log entry
	 */
	public function getDelete() {
		global $mainframe;
		$cids = JRequest::getVar('cid');
		/* Make it an array */
		if (!is_array($cids)) (array)$cids;
		$rowlog = $this->getTable('csvi_logs');
		foreach ($cids as $key => $id) {
			if (!$rowlog->delete($id)) {
				$mainframe->enqueueMessage(JText::_('CANNOT_DELETE_LOG_DATA'), 'error');
			}
			else {
				$mainframe->enqueueMessage(JText::_('DELETE_LOG_DATA')); 
			}
			$db = JFactory::getDBO();
			$q = "DELETE FROM #__csvi_log_details
				WHERE log_id = ".$id;
			$db->setQuery($q);
			if (!$db->query()) {
				$mainframe->enqueueMessage(JText::_('CANNOT_DELETE_LOG_DETAILS_DATA'), 'error');
			}
			else {
				$mainframe->enqueueMessage(JText::_('DELETE_LOG_DETAILS_DATA')); 
			}
		}
	}
	
	/**
	 * Delete a log entry
	 */
	public function getDeleteAll() {
		$mainframe = JFactory::getApplication('site');
		$db = JFactory::getDBO();
		$q = "TRUNCATE ".$db->nameQuote('#__csvi_logs');
		$db->setQuery($q);
		if ($db->query()) $mainframe->enqueueMessage(JText::_('DELETE_LOG_DATA_ALL_OK'));
		else $mainframe->enqueueMessage(JText::_('DELETE_LOG_DATA_ALL_NOK'));
		
		$q = "TRUNCATE ".$db->nameQuote('#__csvi_log_details');
		$db->setQuery($q);
		if ($db->query()) $mainframe->enqueueMessage(JText::_('DELETE_LOG_DATA_DETAILS_ALL_OK'));
		else $mainframe->enqueueMessage(JText::_('DELETE_LOG_DATA_DETAILS_ALL_NOK'));
	}
}
?>