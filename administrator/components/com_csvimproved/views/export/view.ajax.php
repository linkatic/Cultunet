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
		
		$lists = array();
		$tpl = JRequest::getVar('choice');
		switch ($tpl) {
			case 'orderexport':
				/* Get order statusses */
				$orderstatus = $this->get('OrderStatus');
				if (is_array($orderstatus)) {
					/* Add a dont use option */
					$dontuse = new StdClass();
					$dontuse->order_status_code = '';
					$dontuse->order_status_name = JText::_('EXPORT_DONT_USE');
					array_unshift($orderstatus, $dontuse);
					if (count($orderstatus) > 10) $orderstatussize = 10;
					else $orderstatussize = count($orderstatus);
					$lists['orderstatus'] = JHTML::_('select.genericlist', $orderstatus, 'orderstatus[]', 'multiple size="'.$orderstatussize.'"' , 'order_status_code', 'order_status_name');
				}
				
				/* Get order users */
				$orderuser = array();
				if (is_array($orderuser)) {
					/* Add a dont use option */
					$dontuse = new StdClass();
					$dontuse->user_id = '';
					$dontuse->user_name = JText::_('EXPORT_DONT_USE');
					array_unshift($orderuser, $dontuse);
					$lists['orderuser'] = JHTML::_('select.genericlist', $orderuser, 'orderuser[]', 'multiple size="10"' , 'user_id', 'user_name');
				}
				else $lists['orderuser'] = '';
				
				/* Get order products */
				$orderproduct = array();
				if (is_array($orderproduct)) {
					/* Add a dont use option */
					$dontuse = new StdClass();
					$dontuse->product_sku = '';
					$dontuse->product_name = JText::_('EXPORT_DONT_USE');
					array_unshift($orderproduct, $dontuse);
					$lists['orderproduct'] = JHTML::_('select.genericlist', $orderproduct, 'orderproduct[]', 'multiple size="10"' , 'product_sku', 'product_name');
				}
				
				/* Get order currencies */
				$ordercurrency = $this->get('OrderCurrency');
				if (is_array($ordercurrency)) {
					/* Add a dont use option */
					$dontuse = new StdClass();
					$dontuse->order_currency = '';
					$dontuse->currency_name = JText::_('EXPORT_DONT_USE');
					array_unshift($ordercurrency, $dontuse);
					if (count($ordercurrency) > 10) $ordercurrencysize = 10;
					else $ordercurrencysize = count($ordercurrency);
					$lists['ordercurrency'] = JHTML::_('select.genericlist', $ordercurrency, 'ordercurrency[]', 'multiple size="'.$ordercurrencysize.'"' , 'order_currency', 'currency_name');
				}
				else $lists['ordercurrency'] = '';
				
				$lists['address'] = $this->ShippingAddress();
				break;
			case 'productexport':
					/* Get the product categories */
					$lists['product_categories'] = $this->get('ProductCategories');
					
					/* Get the exchange rates */
					$exchangerate = $this->get('ExchangeRateCurrency');
					if (is_array($exchangerate)) {
						/* Add a dont use option */
						$dontuse = new StdClass();
						$dontuse->currency_code = '';
						$dontuse->currency_name = JText::_('EXPORT_DONT_USE');
						array_unshift($exchangerate, $dontuse);
						if (count($exchangerate) > 10) $exchangeratesize = 10;
						else $exchangeratesize = count($exchangerate);
						$lists['targetcurrency'] = JHTML::_('select.genericlist', $exchangerate, 'targetcurrency', 'size="'.$exchangeratesize.'"' , 'currency_code', 'currency_name');
					}
					
				break;
			case 'producttypenamesexport':
					/* Get the product type names */
					$producttypenames = $this->get('ProductTypeNames');
					if (is_array($producttypenames)) {
						/* Add a dont use option */
						$dontuse = new StdClass();
						$dontuse->product_type_id = '';
						$dontuse->product_type_name = JText::_('EXPORT_DONT_USE');
						array_unshift($producttypenames, $dontuse);
						if (count($producttypenames) > 10) $producttypenamessize = 10;
						else $producttypenamessize = count($producttypenames);
						$lists['producttypenames'] = JHTML::_('select.genericlist', $producttypenames, 'producttypenames[]', 'multiple size="'.$producttypenamessize.'"' , 'product_type_id', 'product_type_name');
					}
					else $lists['producttypenames'] = '';
				break;
			case 'userinfoexport':
				/* Get the vendors */
				$vendors = $this->get('Vendors');
				if (is_array($vendors)) {
					/* Add a dont use option */
					$dontuse = new StdClass();
					$dontuse->vendor_id = '';
					$dontuse->vendor_name = JText::_('EXPORT_DONT_USE');
					array_unshift($vendors, $dontuse);
					if (count($vendors) > 10) $vendorssize = 10;
					else $vendorssize = count($vendors);
					$lists['vendors'] = JHTML::_('select.genericlist', $vendors, 'vendors[]', 'multiple size="'.$vendorssize.'"' , 'vendor_id', 'vendor_name');
				}
				
				/* Get the permissions */
				$permissions = $this->get('Permissions');
				if (is_array($permissions)) {
					/* Add a dont use option */
					$dontuse = new StdClass();
					$dontuse->group_id = '';
					$dontuse->group_name = JText::_('EXPORT_DONT_USE');
					array_unshift($permissions, $dontuse);
					if (count($permissions) > 10) $permissionssize = 10;
					else $permissionssize = count($permissions);
					$lists['permissions'] = JHTML::_('select.genericlist', $permissions, 'permissions[]', 'multiple size="'.$permissionssize.'"' , 'group_id', 'group_name');
				}
				
				/* Get the shipping addresses */
				$lists['address'] = $this->ShippingAddress();
				break;
			case 'templateexport':
			case 'templatefieldsexport':
				/* Get templates */
				$listtemplates = array_merge($this->get('TemplatesListImport', 'templates'), $this->get('TemplatesListExport', 'templates'));
				if (is_array($listtemplates)) {
					/* Add a dont use option */
					$dontuse = new StdClass();
					$dontuse->template_id = '';
					$dontuse->template_name = JText::_('EXPORT_DONT_USE');
					array_unshift($listtemplates, $dontuse);
					if ($tpl == 'templateexport') $lists['exporttemplate'] = JHTML::_('select.genericlist', $listtemplates, 'exporttemplate[]', 'multiple' , 'template_id', 'template_name');
					else if ($tpl == 'templatefieldsexport') $lists['exporttemplatefields'] = JHTML::_('select.genericlist', $listtemplates, 'exporttemplatefields[]', 'multiple' , 'template_id', 'template_name');
				}
				else {
					$lists['exporttemplate'] = '';
					$lists['exporttemplatefields'] = '';
				}
				break;
			default:
				return false;
				break;
		}
		
		/* Assign the data */
		//$this->assignRef('templates', $templates);
		$this->assignRef('lists', $lists);
		
		/* Display it all */
		parent::display($tpl);
	}
	
	/**
	 * Create a dropdown with address types
	 */
	function ShippingAddress() {
		/* Get order shipping statusses */
		$address = array();
		/* Add a dont use option */
		$addressoption = new StdClass();
		$addressoption->address_code = '';
		$addressoption->address_name = JText::_('EXPORT_DONT_USE');
		$address[] = $addressoption;
		$addressoption = new StdClass();
		$addressoption->address_code = 'BT';
		$addressoption->address_name = JText::_('BILLING_ADDRESS');
		$address[] = $addressoption;
		$addressoption = new StdClass();
		$addressoption->address_code = 'ST';
		$addressoption->address_name = JText::_('SHIPPING_ADDRESS');
		$address[] = $addressoption;
		if (JRequest::getVar('choice') == 'orderexport') {
			$addressoption = new StdClass();
			$addressoption->address_code = 'BTST';
			$addressoption->address_name = JText::_('BILLING_SHIPPING_ADDRESS');
			$address[] = $addressoption;
		}
		return JHTML::_('select.genericlist', $address, 'address', '' , 'address_code', 'address_name');
	}
}
?>