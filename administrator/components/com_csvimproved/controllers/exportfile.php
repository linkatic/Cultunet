<?php
/**
 * Export controller
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: exportfile.php 861 2009-04-05 01:22:34Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Export Controller
 *
 * @package CSVImproved
 */
class CsvimprovedControllerExportfile extends JController {
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Load export model files
	 *
	 * Here the models are loaded that are used for import. Special is the
	 * import model file as this is included based on the template type
	 */
	function ExportFile() {
		/* Create the view object */
		$view = $this->getView('exportfile', 'html');
				
		/* Default model */
		$view->setModel( $this->getModel( 'exportfile', 'CsvimprovedModel' ), true );
		/* Log functions */
		$view->setModel( $this->getModel( 'log', 'CsvimprovedModel' ));
		/* General import functions */
		$view->setModel( $this->getModel( 'export', 'CsvimprovedModel' ));
		/* General category functions */
		$view->setModel( $this->getModel( 'category', 'CsvimprovedModel' ));
		/* Template settings */
		$view->setModel( $this->getModel( 'templates', 'CsvimprovedModel' ));
		/* Available fields */
		$view->setModel( $this->getModel( 'availablefields', 'CsvimprovedModel' ));
		/* Export specific model */
		$view->setModel( $this->getModel( $this->getTemplateType(), 'CsvimprovedModel' ));
		
		if (!JRequest::getBool('cron', false)) $view->setLayout('exportfile');
		else $view->setLayout('exportfilecron');
		
		/* Now display the view. */
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