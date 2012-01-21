<?php
/**
 * Import controller
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: import.php 666 2009-01-02 07:55:31Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Import Controller
 *
 * @package CSVImproved
 */
class CsvimprovedControllerImport extends JController
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
	 * Shows the import option screen
	 */
	function Import() {
		/* Create the view object */
		$view = $this->getView('import', 'html');
		
		/* Standard model */
		$view->setModel( $this->getModel( 'import', 'CsvimprovedModel' ), true );
		$view->setModel( $this->getModel( 'templates', 'CsvimprovedModel' ));
		$view->setLayout('import');
		
		
		/* Now display the view */
		$view->display();
	}
}
?>
