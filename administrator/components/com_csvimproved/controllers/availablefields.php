<?php
/**
 * Available Fields controller
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: availablefields.php 666 2009-01-02 07:55:31Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Available Fields Controller
 *
 * @package    CSVImproved
 */
class CsvimprovedControllerAvailableFields extends JController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Shows the logs
	 */
	public function AvailableFields() {
		/* Create the view */
		$view = $this->getView('availablefields', 'html');
		
		/* Add the models */
		$view->setModel( $this->getModel( 'availablefields', 'CsvimprovedModel' ), true );
		/* Available fields */
		$view->setModel( $this->getModel( 'templates', 'CsvimprovedModel' ));
		
		/* Set the layout */
		$view->setLayout('availablefields');
		
		/* Display it all */
		$view->display();
	}
}
?>
