<?php
/**
 * Maintenance model
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: maintenance.php 907 2009-05-22 15:26:28Z Roland $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.application.component.model' );

/**
 * Maintenance Model
 *
 * @package CSVImproved
 */
class CsvimprovedModelMaintenance extends JModel {
	
	/**
	 * Empty VirtueMart tables
	 *
	 * @todo Write out product type tables that get deleted
	 */
	public function getEmptyDatabase() {
		
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		/* Empty all the necessary tables */
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_price`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product price table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product price table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product price table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_mf_xref`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product manufacturer link table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product manufacturer link table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product manufacturer link table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_attribute`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product attribute table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product attribute table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product attribute table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_category`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty category table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Category table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Category table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_category_xref`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty category link table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Category link table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Category link table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_attribute_sku`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty attribute for parent products table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Attribute for parent products table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Attribute for parent products table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_category_xref`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product category link table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product category link table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product category link table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_discount`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product discount table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product discount table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product discount table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_type`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product type table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product type table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product type table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_type_parameter`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product type parameter table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product type parameter table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product type parameter table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_product_type_xref`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product type link table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product type link table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product type link table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_relations`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product relations table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product relations table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product relations table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "DELETE FROM `#__vm_manufacturer` WHERE manufacturer_id > 1;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty manufacturer table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Manufacturer table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Manufacturer table has not been emptied'));
		
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		$q = "TRUNCATE TABLE `#__vm_product_files`;";
		$db->setQuery($q);
		$csvilog->AddMessage('debug', 'Empty product files table', true);
		if ($db->query()) $csvilog->AddStats('empty', JText::_('Product files table has been emptied'));
		else $csvilog->AddStats('incorrect', JText::_('Product files table has not been emptied'));
		
		/* Check if there are any product type tables created, if so, remove them */
		$q = "SHOW TABLES LIKE '%vm_product_type_%'";
		$db->setQuery($q);
		$tables = $db->loadResultArray();
		$config = new JConfig;
		foreach ($tables as $key => $tablename) {
			if (stristr('0123456789', substr($tablename, -1))) {
				JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
				$q_drop = "DROP TABLE ".$db->nameQuote($tablename);
				$db->setQuery($q_drop);
				$csvilog->AddMessage('debug', 'DEBUG_DELETE_PRODUCT_TYPE_NAME_TABLE'.' '.$tablename, true);
				if ($db->query()) $csvilog->AddStats('deleted', 'PRODUCT_TYPE_NAME_TABLE_DELETED'.' '.$tablename);
				else $csvilog->AddStats('incorrect', 'PRODUCT_TYPE_NAME_TABLE_NOT_DELETED'.' '.$tablename);
			}
		}
		return true;
	}
	
	/**
	 * Remove orphaned fields
	 */
	public function getRemoveOrphan() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
		
		$q = "SELECT template_id FROM #__csvi_templates";
		$db->setQuery($q);
		$records = $db->loadObjectList();
		$foundids = '';
		$last = end(array_keys($records));
		foreach ($records as $key => $record) {
			$foundids .= $record->template_id;
			if ($last != $key) $foundids .= ',';
		}
		$q = "SELECT id FROM #__csvi_template_fields
			WHERE field_template_id NOT IN (".$foundids.")";
		$db->setQuery($q);
		$records = $db->loadObjectList();
		if (count($records) > 0) {
			foreach ($records as $key => $record) {
				JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
				$q = "DELETE FROM #__csvi_template_fields WHERE id = ".$record->id;
				$db->setQuery($q);
				if ($db->query()) $csvilog->AddStats('deleted', 'Field ID '.$record->id.' has been removed');
				else $csvilog->AddStats('incorrect', 'Field ID '.$record->id.' has not been removed');
			}
		}
		else $csvilog->AddStats('information', 'No orphaned fields found');
		return true;
	}
	
	/**
	 * Optimize CSV Improved and VirtueMart tables
	 *
	 * @todo clean up messages
	 */
	public function getOptimizeTables() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$tables = array();
		$tables[] = '#__csvi_available_fields';
		$tables[] = '#__csvi_template_fields';
		$tables[] = '#__csvi_template_types';
		$tables[] = '#__csvi_templates';
		$tables[] = '#__csvi_logs';
		$tables[] = '#__csvi_log_details';
		$tables[] = '#__vm_product';
		$tables[] = '#__vm_product_price';
		$tables[] = '#__vm_product_mf_xref';
		$tables[] = '#__vm_product_attribute';
		$tables[] = '#__vm_category';
		$tables[] = '#__vm_category_xref';
		$tables[] = '#__vm_product_attribute_sku';
		$tables[] = '#__vm_product_category_xref';
		$tables[] = '#__vm_product_discount';
		$tables[] = '#__vm_product_type';
		$tables[] = '#__vm_product_type_parameter';
		$tables[] = '#__vm_product_product_type_xref';
		$tables[] = '#__vm_product_relations';
		$tables[] = '#__vm_manufacturer';
		
		foreach ($tables as $id => $tablename) {
			JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
			$q =  "OPTIMIZE TABLE ".$tablename;
			$db->setQuery($q);
			if ($db->query()) $csvilog->AddStats('information', JText::_('Table has been optimized').' '.substr($tablename, 3));
			else $csvilog->AddStats('incorrect', JText::_('Table has not been optimized').' '.substr($tablename,3));
		}
		return true;
	}
	
	/**
	 * Sorts all VirtueMart categories in alphabetical order
	 */
	public function getSortCategories() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		/* Get all categories */
		$query  = "SELECT LOWER(category_name) AS category_name, category_child_id as cid, category_parent_id as pid
				FROM #__vm_category, #__vm_category_xref WHERE
				#__vm_category.category_id=#__vm_category_xref.category_child_id ";
		
		/* Execute the query */
		$db->setQuery($query);
		
		$records = $db->loadObjectList();
		$categories = array();
		
		/* Group all categories together according to their level */
		foreach ($records as $key => $record) {
			$categories[$record->pid][$record->cid] = $record->category_name;
		}
		
		/* Sort the categories and store the item list */
		foreach ($categories as $id => $category) {
			asort($category);
			$listorder = 1;
			foreach ($category as $category_id => $category_name) {
				/* Store the new sort order */
				$q = "UPDATE #__vm_category
					SET list_order = '".$listorder."'
					WHERE category_id = '".$category_id."'";
				$db->setQuery($q);
				$db->query();
				JRequest::setVar('currentline', JRequest::getInt('currentline', 0)+1);
				$csvilog->AddStats('information', "Saved category ".$category_name." with order ".$listorder);
				$listorder++;
			}
		}
		return true;
	}
	
	/**
	 * Get the current size of the product name field
	 */
	public function getSizeProductName() {
	 	$db = JFactory::getDBO();
	 	$q = "SELECT MAX(LENGTH(product_name)) FROM #__vm_product";
	 	$db->setQuery($q);
	 	return $db->loadResult();
	}
	
	/**
	 * Adjust the size of the product name field
	 */
	public function getResizeProductName() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$q = "ALTER TABLE #__vm_product CHANGE `product_name` `product_name` VARCHAR(".JRequest::getInt('productnamefieldlength').") DEFAULT NULL NULL;";
		$db->setQuery($q);
		if ($db->query()) {
			$csvilog->AddStats('information', str_replace('{size}', JRequest::getInt('productnamefieldlength'), JText::_('CHANGE_NAME_LENGTH_OK')));
			return true;
		}
		else {
			$csvilog->AddStats('incorrect', JText::_('CHANGE_NAME_LENGTH_NOK').' '.$db->getErrorMsg());
			return false;
		}
	}
	
	/**
	 * Add exchange rates
	 * The eurofxref-daily.xml file is updated daily between 14:15 and 15:00 CET
	 */
	public function getExchangeRates() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		/* Read eurofxref-daily.xml file in memory */
		$XMLContent= file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
		
		/* Process the file */
		if ($XMLContent) {
			/* Empty table */
			$q = "TRUNCATE TABLE `#__csvi_currency`;";
			$db->setQuery($q);
			$db->query();
			
			/* Add the Euro */
			$q = "INSERT INTO #__csvi_currency (currency_code, currency_rate) 
				VALUES ('EUR', 1)";
			$db->setQuery($q);
			$db->query();
			
			$currencyCode = array();
			$rate = array();
			foreach ($XMLContent as $line) {
				if (ereg("currency='([[:alpha:]]+)'",$line,$currencyCode)) {
					if (ereg("rate='([[:graph:]]+)'",$line,$rate)) {
						$q = "INSERT INTO #__csvi_currency (currency_code, currency_rate) 
							VALUES (".$db->Quote($currencyCode[1]).", ".$rate[1].")";
						$db->setQuery($q);
						if ($db->query()) {
							$csvilog->AddStats('added', str_ireplace('{country}', $currencyCode[1], JText::_('EXCHANGE_RATE_{country}_ADDED')));
						}
						else $csvilog->AddStats('incorrect', str_ireplace('{country}', $currencyCode[1], JText::_('EXCHANGE_RATE_{country}_NOT_ADDED')));
					}
				}
			}
		}
		else $csvilog->AddStats('incorrect', JText::_('CANNOT_LOAD_EXCHANGERATE_FILE'));
	}
	
	/**
	 * Remove all categories that have no products
	 * Parent categories are only deleted if there are no more children left
	 */
	public function getRemoveEmptyCategories() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		
		/* Get all categories */
		$query  = "SELECT category_child_id as cid, 
						category_parent_id as pid
				FROM #__vm_category
				LEFT JOIN #__vm_category_xref 
				ON #__vm_category.category_id = #__vm_category_xref.category_child_id
				LEFT JOIN #__vm_product_category_xref
				ON #__vm_category.category_id = #__vm_product_category_xref.category_id
				group by #__vm_product_category_xref.product_id ";
		
		/* Execute the query */
		$db->setQuery($query);
		
		$records = $db->loadObjectList();
		$categories = array();
		
		/* Group all categories together according to their level */
		foreach ($records as $key => $record) {
			$categories[] = $record->pid;
			$categories[] = $record->cid;
		}
		$categories = array_unique($categories);
		
		/* Remove all categories except the ones we have */
		$q = "DELETE FROM #__vm_category 
			WHERE category_id NOT IN (".implode(', ', $categories).")";
		$db->setQuery($q);
		if ($db->query()) $csvilog->AddStats('deleted', JText::_('MAINTENANCE_CATEGORIES_DELETED'));
		else $csvilog->AddStats('incorrect', JText::_('MAINTENANCE_CATEGORIES_NOT_DELETED'));
		
		/* Remove all category parent-child relations except the ones we have */
		$q = "DELETE FROM #__vm_category_xref 
			WHERE category_child_id NOT IN (".implode(', ', $categories).")";
		$db->setQuery($q);
		if ($db->query()) $csvilog->AddStats('deleted', JText::_('MAINTENANCE_CATEGORIES_XREF_DELETED'));
		else $csvilog->AddStats('incorrect', JText::_('MAINTENANCE_CATEGORIES_XREF_NOT_DELETED'));
	}
}
?>