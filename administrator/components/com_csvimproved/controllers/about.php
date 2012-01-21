<?php
/**
 * About controller
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: about.php 666 2009-01-02 07:55:31Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * About Controller
 *
 * @package    CSVImproved
 */
class CsvimprovedControllerAbout extends JController
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
	 * Shows the about screen
	 */
	function About() {
		JRequest::setVar('view', 'about');
		JRequest::setVar('layout', 'about');
		
		parent::display();
	}
}
?>
