<?php
/**
 * Log view
 *
 * The logger needs to record several messages. These are:
 * - Successful imported records
 * - Failed imported records
 * - Status messages
 * - Warning messages
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: view.html.php 946 2009-08-03 09:47:37Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Log View
 *
 * @package CSVImproved
 */
class CsvimprovedViewLog extends JView {
	
	/**
	 * About view display method
	 * @return void
	 **/
	function display($tpl = null) {
		/* Get the task */
		$task = JRequest::getCmd('task');
		/* Load a log ID */
		$cid = JRequest::getVar('cid');
		/* Set the log ID */
		$model = $this->getModel('log');
		if (isset($cid[0])) $model->setId($cid[0]);
		if ($task == 'logdetails') {
			/* Load the details */
			$logdetails = $this->get('LogDetails');
			/* Load the statistics */
			$logstatistics = $this->get('LogStatistics');
			/* Assign variables */
			$this->assignRef('logdetails', $logdetails);
			$this->assignRef('logstatistics', $logstatistics);
			/* Add toolbar */
			JToolBarHelper::title(JText::_('LOG_DETAILS'), 'csvivirtuemart_logdetails_48');
			JToolBarHelper::custom( 'cancel', 'csvivirtuemart_cancel_32', 'csvivirtuemart_cancel_32', JText::_('Cancel'), false);
		}
		else {
			switch ($task) {
				case 'remove':
					$this->get('Delete');
					break;
				case 'remove_all':
					$this->get('DeleteAll');
					break;
			}
			/* Get the pagination */
			$pagination = $this->get('Pagination');
			/* Load the logs */
			$logentries = $this->get('LogEntries');
			/* Assign variables */
			$this->assignRef('logentries', $logentries);
			$this->assignRef('pagination', $pagination);
			/* Add toolbar */
			JToolBarHelper::title(JText::_('LOG'), 'csvivirtuemart_log_48');
			JToolBarHelper::custom( 'logdetails', 'csvivirtuemart_logdetails_32', 'csvivirtuemart_logdetails_32', JText::_('DETAILS'), true);
			JToolBarHelper::custom( 'remove', 'csvivirtuemart_delete_32', 'csvivirtuemart_delete_32', JText::_('DELETE'), true);
			JToolBarHelper::custom( 'remove_all', 'csvivirtuemart_delete_32', 'csvivirtuemart_delete_32', JText::_('DELETE_ALL'), false);
		}
		
		/* Display it all */
		parent::display($tpl);
	}
}
?>
