<?php
/**
 * About view
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
class CsvimprovedViewAbout extends JView {
	
	/**
	 * About view display method
	 * @return void
	 **/
	function display($tpl = null) {
		
		/* Get the helper files */
		JView::loadHelper('subscription_check');
		
		$check = new SubscriptionCheck;
		$result = $check->CheckKey();
		
		/* Assign the values */
		$this->assignRef('result', $result['result']);
		$this->assignRef('expiredate', $result['uxdate']);
		$this->assignRef('hostname', $result['hostname']);
		
		/* Show the toolbar */
		$this->toolbar();
		
		/* Display it all */
		parent::display($tpl);
	}
	
	/**
	 * Displays a toolbar for a specific page
	 */
	function toolbar() {
		JToolBarHelper::title(JText::_('About'), 'csvivirtuemart_about_48');
		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Popup', 'csvivirtuemart_key_32', 'Preferences', 'index.php?option=com_config&amp;controller=component&amp;component=com_csvimproved', 670, 200 );
	}
}
?>