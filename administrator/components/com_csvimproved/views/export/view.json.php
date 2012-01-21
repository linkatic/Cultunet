<?php
/**
 * Import view
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: view.html.php 862 2009-04-06 12:35:22Z Suami $
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
		switch (JRequest::getVar('task')) {
			case 'getuser':
				$users = $this->get('OrderUser');
				echo json_encode($users);
				break;
			case 'getproduct':
				$products = $this->get('OrderProduct');
				echo json_encode($products);
				break;
			default:
				break;
		}
	}
}
?>