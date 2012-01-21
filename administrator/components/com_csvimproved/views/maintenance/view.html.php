<?php
/**
 * Maintenance view
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: view.html.php 945 2009-07-30 07:18:43Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.view' );

/**
 * Templates View
 *
 * @package CSVImproved
 */
class CsvimprovedViewMaintenance extends JView {
	
	/**
	 * About view display method
	 * @todo Add result to log screen
	 **/
	function display($tpl = null) {
		/* Get the logger class */
		JView::loadHelper('csvi_class_log');
		$csvilog = new CsviLog();
		JRequest::setVar('csvilog', $csvilog);
		
		$operation = JRequest::getVar('operation');
		if (is_array($operation)) $csvilog->SetAction('Maintenance');
		switch($operation[0]) {
			case 'EmptyDatabase':
				$csvilog->SetActionType('EmptyDatabase');
				$this->get('EmptyDatabase');
				break;
			case 'RemoveOrphan':
				$csvilog->SetActionType('RemoveOrphan');
				$this->get('RemoveOrphan');
				break;
			case 'OptimizeTables':
				$csvilog->SetActionType('OptimizeTables');
				$this->get('OptimizeTables');
				break;
			case 'SortCategories':
				$csvilog->SetActionType('SortCategories');
				$this->get('SortCategories');
				break;
			case 'UpdateAvailableFields':
				$csvilog->SetActionType('UpdateAvailableFields');
				$this->get('FillAvailableFields', 'Availablefields');
				break;
			case 'ResizeProductName':
				$csvilog->SetActionType('ResizeProductName');
				$this->get('ResizeProductName');
				break;
			case 'ExchangeRates':
				$csvilog->SetActionType('ExchangeRates');
				$this->get('ExchangeRates');
				break;
			case 'RemoveEmptyCategories':
				$csvilog->SetActionType('RemoveEmptyCategories');
				$this->get('RemoveEmptyCategories');
				break;
		}
		if (is_array($operation)) $this->get('StoreLogResults', 'log');
		$this->assignRef('sizeproductname', $this->get('SizeProductName'));
		
		/* Show the toolbar */
		$this->toolbar();
		
		/* Display it all */
		parent::display($tpl);
	}
	
	/**
	 * Displays a toolbar for a specific page
	 */
	function toolbar() {
		JToolBarHelper::title(JText::_('Maintenance'), 'csvivirtuemart_maintenance_48');
		if (!JRequest::getBool('boxchecked')) JToolBarHelper::custom('maintenance', 'csvivirtuemart_continue_32.png', 'csvivirtuemart_continue_32.png', JText::_('CONTINUE'), true );
	}
}
?>
