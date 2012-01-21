<?php
/**
 * Default view
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
 * Default View
 *
 * @package CSVImproved
 */
class CsvimprovedViewCsvimproved extends JView {
	/**
	 * CSV Improved view display method
	 *
	 * @return void
	 **/
	function display($tpl = null) {
		/* Get the Cpanel images */
		$cpanel_images = new stdClass();
		$cpanel_images->templates = $this->CpanelButton('csvivirtuemart_template_48.png', 'index2.php?option=com_csvimproved&task=templates&controller=templates', JText::_('Templates'));
		$cpanel_images->import = $this->CpanelButton('csvivirtuemart_import_48.png', 'index2.php?option=com_csvimproved&task=import&controller=import', JText::_('Import'));
		$cpanel_images->export = $this->CpanelButton('csvivirtuemart_export_48.png', 'index2.php?option=com_csvimproved&task=export&controller=export', JText::_('Export'));
		$cpanel_images->maintenance = $this->CpanelButton('csvivirtuemart_maintenance_48.png', 'index2.php?option=com_csvimproved&task=maintenance&controller=maintenance', JText::_('Maintenance'));
		$cpanel_images->help = $this->CpanelButton('csvivirtuemart_help_48.png', 'http://www.csvimproved.com/csv-improved-documentation/', JText::_('Help'));
		$cpanel_images->about = $this->CpanelButton('csvivirtuemart_about_48.png', 'index2.php?option=com_csvimproved&task=about&controller=about', JText::_('About'));
		$cpanel_images->log = $this->CpanelButton('csvivirtuemart_log_48.png', 'index2.php?option=com_csvimproved&task=log&controller=log', JText::_('LOG'));
		$cpanel_images->availablefields = $this->CpanelButton('csvivirtuemart_av_fields_48.png', 'index2.php?option=com_csvimproved&task=availablefields&controller=availablefields', JText::_('AVAILABLE_FIELDS'));
		
		/* Show the toolbar */
		$this->toolbar();
		
		/* Assign data for display */
	    $this->assignRef('cpanel_images', $cpanel_images);
		
		/* Display the page */
		parent::display($tpl);
	}
	
	/**
	 * Prints a button for the control panel
	 *
	 * @param string $image contains the name of the image
	 * @param string $link contains the target link for the image when clicked
	 * @param string $title contains the title of the button
	 * @return string returns a complete button for the control panel
	 */
	function CpanelButton($image, $link, $title) {
		global $mainframe;
		if (substr($link, 0, 4) == "http") $attribs = ' target="_new"';
		else $attribs = '';
		$cpanelbutton = '<div class="cpanel_button">';
		$cpanelbutton .= '	<div class="icon">';
		$cpanelbutton .= JHTML::_('link', $link, JHTML::_('image', JURI::root().'administrator/components/com_csvimproved/assets/images/'.$image, $title).'<span>'.$title.'</span>', $attribs);
		$cpanelbutton .= '	</div>';
		$cpanelbutton .= '</div>';
		return $cpanelbutton;
	}
	
	function toolbar() {
		JToolBarHelper::title(   JText::_('CSV Improved Control Panel'), 'csvivirtuemart_logo_48.png' );
	}
}
?>
