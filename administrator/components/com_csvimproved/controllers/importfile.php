<?php
/**
 * Import controller
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: importfile.php 666 2009-01-02 07:55:31Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Import Controller
 *
 * @package CSVImproved
 */
class CsvimprovedControllerImportfile extends JController
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
	 * Load import model files
	 *
	 * Here the models are loaded that are used for import. Special is the
	 * import model file as this is included based on the template type
	 */
	function ImportFile() {
		/* Create the view object */
		$view = $this->getView('importfile', 'html');
		
		/* Standard model */
		$view->setModel( $this->getModel( 'importfile', 'CsvimprovedModel' ), true );
		
		/* Log functions */
		$view->setModel( $this->getModel( 'log', 'CsvimprovedModel' ));
		/* General import functions */
		$view->setModel( $this->getModel( 'import', 'CsvimprovedModel' ));
		/* General category functions */
		$view->setModel( $this->getModel( 'category', 'CsvimprovedModel' ));
		/* Template settings */
		$view->setModel( $this->getModel( 'templates', 'CsvimprovedModel' ));
		/* Available fields */
		$view->setModel( $this->getModel( 'availablefields', 'CsvimprovedModel' ));
		/* Import specific model */
		$view->setModel( $this->getModel( $this->getTemplateType(), 'CsvimprovedModel' ));
		
		if (!JRequest::getBool('cron', false)) $view->setLayout('importfile');
		else $view->setLayout('importfilecron');
		
		/* Now display the view */
		$view->display();
	}
	
	/**
	 * Retrieves the template type
	 */
	private function getTemplateType() {
		$db = JFactory::getDBO();
		
		$q = "SELECT LOWER(template_type) AS template_type
			FROM #__csvi_templates
			WHERE template_id = ".JRequest::getInt('template_id');
		$db->setQuery($q);
		if ($db->query()) return $db->loadResult();
		else return false;
	}
}
?>
