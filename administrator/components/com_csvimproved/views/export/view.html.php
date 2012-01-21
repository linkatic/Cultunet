<?php
/**
 * Import view
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
 * Import View
 *
 * @package CSVImproved
 */
class CsvimprovedViewExport extends JView {
	
	/**
	 * Templates view display method
	 * @return void
	 **/
	function display($tpl = null) {
		/* Show the export settings screen */
		$templates = $this->get('TemplatesListExport', 'templates');
		
		/* Toolbar title */
		JToolBarHelper::title(JText::_( 'Export' ), 'csvivirtuemart_export_48');
		if (count($templates) < 1) {
			$error = JError::getError();
			if (!is_object($error)) {
				JError::raiseWarning(0, JText::_('No export templates defined'));
			}
		}
		else {
			/* Toolbar buttons */
			/* Check if we can show the export button */
			if ($this->get('CountTemplateFields')) JToolBarHelper::custom( 'exportfile', 'csvivirtuemart_export_32', 'csvivirtuemart_export_32', JText::_('Export'), false);
			
			/* Assign the data */
			$this->assignRef('templates', $templates);
			
			/* Display it all */
			parent::display($tpl);
		}
	}
}
?>