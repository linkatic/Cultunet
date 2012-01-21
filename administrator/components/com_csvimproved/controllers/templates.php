<?php
/**
 * Default view
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: templates.php 938 2009-06-23 18:39:36Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * Templates Controller
 *
 * @package CSVImproved
 */
class CsvimprovedControllerTemplates extends JController {
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct() {
		parent::__construct();
		
		/* Redirect templates to templates as this is the standard call */
		$this->registerTask('templates','templateslist');
		$this->registerTask('save','templateslist');
		$this->registerTask('cancel','templateslist');
		$this->registerTask('edittemplate','templateconfigurator');
		$this->registerTask('newtemplate','templateconfigurator');
		$this->registerTask('clonetemplate','templateslist');
		$this->registerTask('deletetemplate','templateslist');
		$this->registerTask('unpublish','fieldstemplate');
		$this->registerTask('publish','fieldstemplate');
		$this->registerTask('savefieldstable','fieldstemplate');
		$this->registerTask('addfield','fieldstemplate');
		$this->registerTask('renumber','fieldstemplate');
		$this->registerTask('deletefield','fieldstemplate');
	}
	
	/**
	 * Creates a list of templates in the database
	 */
	function TemplatesList() {
		JRequest::setVar('view', 'templates');
		JRequest::setVar('layout', 'templateslist');
		
		parent::display();
	}
	
	/**
	 * Creates a list of templates in the database
	 */
	function TemplateConfigurator() {
		/* Hide the mainmenu so the user must save or cancel the template settings */
		JRequest::setVar('hidemainmenu', 1);
		JRequest::setVar('view', 'templates');
		JRequest::setVar('layout', 'templateconfigurator');
		parent::display();
	}
	
	/**
	 * Stores the updated template
	 */
	function UpdateTemplate() {
		JRequest::setVar('view', 'templates');
		JRequest::setVar('layout', 'templateslist');
		parent::display();
	}
	
	/**
	 * Stores the updated template
	 */
	function AddTemplate() {
		JRequest::setVar('view', 'templates');
		JRequest::setVar('layout', 'templateslist');
		parent::display();
	}
	
	/**
	 * Stores the updated template
	 */
	function FieldsTemplate() {
		/* Create the view */
		$view = $this->getView('templatefields', 'html');
		
		/* Add the models */
		$view->setModel( $this->getModel( 'templates', 'CsvimprovedModel' ), true );
		/* Available fields */
		$view->setModel( $this->getModel( 'availablefields', 'CsvimprovedModel' ));
		
		/* Set the layout */
		$view->setLayout('templatefields');
		
		/* Display it all */
		$view->display();
	}
	
	/**
	 * Stores the updated template
	 */
	function GetImExSettings() {
		// Create the view object.
		$view = $this->getView('templates', 'raw');
		
		// Standard model
		$view->setModel( $this->getModel( 'templates', 'CsvimprovedModel' ), true );
		
		$view->setLayout('templateconfigurator_config_'.JRequest::getVar('type'));
		
		// Now display the view. Make sure you dont have a parent::display() anymore
		$view->display();
	}
	
	/**
	 * Get a list of fields for the current template
	 */
	function ListFields() {
		// Create the view object.
		$view = $this->getView('templates', 'raw');
		
		// Standard model
		$view->setModel( $this->getModel( 'templates', 'CsvimprovedModel' ), true );
		
		$view->setLayout('listfields');
		
		// Now display the view. Make sure you dont have a parent::display() anymore
		$view->display();
	}
}
?>
