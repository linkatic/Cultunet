<?php
/**
 * Export model
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: export.php 902 2009-05-10 01:50:10Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Export Model
 *
 * @package CSVImproved
 */
class CsvimprovedModelExport extends JModel {
	
	/**
	 * Get the list of order statussen
	 */
	public function getOrderStatus() {
		$db = JFactory::getDBO();
		$q = "SELECT order_status_code, order_status_name
			FROM #__vm_order_status
			ORDER BY list_order";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	/**
	 * Get the list of order users
	 */
	public function getOrderUser() {
		$db = JFactory::getDBO();
		$filter = JRequest::getVar('filter');
		$q = "SELECT DISTINCT user_id,
			IF (LENGTH(TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name))) = 0, '".JText::_('EXPORT_ORDER_USER_EMPTY')."',
			IF (TRIM(CONCAT(first_name, ' ', middle_name, ' ', last_name)) is NULL, '".JText::_('EXPORT_ORDER_USER_EMPTY')."', CONCAT(first_name, ' ', middle_name, ' ', last_name))) AS user_name
			FROM #__vm_order_user_info
			WHERE (first_name LIKE ".$db->Quote('%'.$filter.'%')."
				OR middle_name LIKE ".$db->Quote('%'.$filter.'%')."
				OR last_name LIKE ".$db->Quote('%'.$filter.'%').")
			ORDER BY user_name
			LIMIT 10;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	/**
	 * Get the list of order products
	 */
	public function getOrderProduct() {
		$db = JFactory::getDBO();
		$filter = JRequest::getVar('filter');
		$q = "SELECT DISTINCT product_sku, product_name
			FROM #__vm_product p, #__vm_order_item o
			WHERE p.product_id = o.product_id
			AND (p.product_sku LIKE ".$db->Quote('%'.$filter.'%')."
				OR p.product_name LIKE ".$db->Quote('%'.$filter.'%')."
				OR p.product_s_desc LIKE ".$db->Quote('%'.$filter.'%').")
			ORDER BY product_name
			LIMIT 10;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	/**
	 * Get the list of order products
	 */
	public function getOrderCurrency() {
		$db = JFactory::getDBO();
		$q = "SELECT order_currency, currency_name
			FROM #__vm_orders o, #__vm_currency c
			WHERE o.order_currency = c.currency_code
			GROUP BY currency_name
			ORDER BY currency_name;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	/**
	 * Get the list of exchange rate currencies
	 */
	public function getExchangeRateCurrency() {
		$db = JFactory::getDBO();
		$q = "SELECT #__csvi_currency.currency_code AS currency_code, 
			IF (#__vm_currency.currency_name IS NULL, #__csvi_currency.currency_code, #__vm_currency.currency_name) AS currency_name
			FROM #__csvi_currency
			LEFT JOIN #__vm_currency
			on #__vm_currency.currency_code = #__csvi_currency.currency_code;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	/**
	 * Get the list of vendors
	 */
	public function getVendors() {
		$db = JFactory::getDBO();
		$q = "SELECT vendor_id, REPLACE(vendor_name, '\\\', '') AS vendor_name
			FROM #__vm_vendor
			ORDER BY vendor_name;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	/**
	 * Get the list of permissions
	 */
	public function getPermissions() {
		$db = JFactory::getDBO();
		$q = "SELECT group_name AS group_id, group_name
			FROM #__vm_auth_group
			ORDER BY group_name;";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	/**
	 * Check if there are any templates with fields
	 */
	public function getCountTemplateFields() {
		$db = JFactory::getDBO();
		$q = "SELECT field_template_id, COUNT(field_template_id) AS total 
			FROM #__csvi_template_fields
			WHERE field_template_id in (
				SELECT template_id
				FROM #__csvi_templates 
				WHERE template_type 
				LIKE '%export')
			GROUP BY field_template_id";
		$db->setQuery($q);
		$nrfields = $db->loadResultArray();
		if ($db->getErrorNum() > 0) {
			JError::raiseWarning(0, $db->getErrorMsg());
			return false;
		}
		else {
			/* Check if there are any templates with more than 0 fields */
			foreach ($nrfields as $key => $nr) {
				if ($nr > 0) return true;
			}
		}
	}
	
	/**
	 * Get a list of all categories and put them in a select list
	 */
	public function getProductCategories() {
		$db = JFactory::getDBO();
		/* 1. Get all categories */
		$q = "SELECT category_parent_id AS parent_id, category_child_id AS id, category_name AS catname
			FROM #__vm_category c
			LEFT JOIN #__vm_category_xref x
			ON c.category_id = x.category_child_id";
		$db->setQuery($q);
		$rawcats = $db->loadObjectList();
		
		/* 2. Group categories based on their parent_id */
		$categories = array();
		foreach ($rawcats as $key => $rawcat) {
			$categories[$rawcat->parent_id][$rawcat->id]['pid'] = $rawcat->parent_id;
			$categories[$rawcat->parent_id][$rawcat->id]['cid'] = $rawcat->id;
			$categories[$rawcat->parent_id][$rawcat->id]['catname'] = $rawcat->catname;
		}
		if (count($rawcats) > 10) $categorysize = 10;
		else $categorysize = count($rawcats)+1;
		$html = '<select id="productcategories" class="inputbox" size="'.$categorysize.'" name="productcategories[]" multiple="multiple">';
		/* Add a don't use option */
		$html .= '<option value="">'.JText::_('EXPORT_DONT_USE').'</option>';
		if (count($categories) > 0) {
			/* Take the toplevels first */
			foreach ($categories[0] as $key => $category) {
				$this->html = '';
				/* Write out toplevel */
				$html .= '<option value="'.$category['cid'].'"';
				$html .= '>'.$category['catname'].'</option>';
				
				/* Write the subcategories */
				$this->buildCategory($categories, $category['cid'], array());
				$html .= $this->html;
			}
		}
		$html .= '</select>';
		
		return $html;
	}
	
	/**
	 * Create the subcategory layout
	 */
	private function buildCategory($cattree, $catfilter, $subcats, $loop=1) {
		if (isset($cattree[$catfilter])) {
			foreach ($cattree[$catfilter] as $subcatid => $category) {
				$this->html .= '<option value="'.$category['cid'].'"';
				$this->html .= '>'.str_repeat('>', $loop).' '.$category['catname'].'</option>';
				$subcats = $this->buildCategory($cattree, $subcatid, $subcats, $loop+1);
			}
		}
	}
	
	/**
	 * Get product type names
	 */
	public function getProductTypeNames() {
		$db = JFactory::getDBO();
		$q = "SELECT product_type_id, product_type_name
			FROM #__vm_product_type";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
}
?>