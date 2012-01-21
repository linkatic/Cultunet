<?php
/**
 * Maintenance controller
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: maintenance.php 665 2009-01-02 07:40:08Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Maintenance Controller
 *
 * @package    CSVImproved
 */
class CsvimprovedControllerMaintenance extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Shows the maintenance screen
	 */
	public function Maintenance() {
		/* Create the view object */
		$view = $this->getView('maintenance', 'html');
		
		/* Standard model */
		$view->setModel( $this->getModel( 'maintenance', 'CsvimprovedModel' ), true );
		
		/* Extra models */
		$view->setModel( $this->getModel( 'log', 'CsvimprovedModel' ));
		$view->setModel( $this->getModel( 'availablefields', 'CsvimprovedModel' ));
		
		/* View */
		if (JRequest::getBool('boxchecked')) $view->setLayout('maintenancelog');
		else $view->setLayout('maintenance');
		
		/* Now display the view */
		$view->display();
	}
}
?>
