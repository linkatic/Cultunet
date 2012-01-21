<?php
/**
 * Category related functions
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: category.php 869 2009-04-14 14:00:35Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product details
 *
 * Main processor for importing categories.
 *
 * @package CSVImproved
 */
class CsvimprovedModelCategory extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_category table */
	private $_vm_category = null;
	/** @var object contains the vm_category_xref table */
	private $_vm_category_xref = null;
	/** @var object contains the vm_product_category_xref table */
	private $_vm_product_category_xref = null;
	
	/* Public variables */
	/** @var integer contains the category path for a product */
	public $category_path = null;
	/** @var integer contains the category ID for a product */
	public $category_id = null;
	/** @var integer contains the category setting for publishing */
	public $category_publish = 'Y';
	
	/* Private variables */
	
	/**
	 * Initiate the class
	 */
	public function getStart() {
		$this->getTables();
	}
	
	/**
	 * Getting the product table info
	 */
	private function getTables() {
		$this->_vm_category = $this->getTable('vm_category');
		$this->_vm_category_xref = $this->getTable('vm_category_xref');
		$this->_vm_product_category_xref = $this->getTable('vm_product_category_xref');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_category->reset();
		$this->_vm_category_xref->reset();
		$this->reset();
	}
	
	/**
	 * Clean the variables
	 */
	private function reset() {
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') $this->$name = $value;
		}
	}
	
	/**
	 * Gets the ID belonging to the category path
	 *
	 * @return array containing category_id and category_name
  	 **/
	public function GetCategoryId($category_path) {
		/* Check for any missing categories, otherwise create them */
		$category = $this->CsvCategory($category_path);
		
		return array('category_id' => $category[0]);
	} // end function GetCategoryId
	
	/**
	 * Inserts the category/categories for a product
	 *
	 * Any existing categories will be removed first, after that the new
	 * categories will be imported.
	 * 
	 * @see CsvCategory()
	 * @param $product_id contains the product ID the category/categories belong to
	 * @param $category_path contains the category/categories path for the product
	 * @param $category_id contains a single or array of category IDs
  	 **/
  	public function CheckCategoryPath($product_id=false, $category_path=false, $category_id=false, $product_list='NULL') {
		$csvilog = JRequest::getVar('csvilog');
		$template = JRequest::getVar('template');
		
		$csvilog->AddMessage('debug', 'Checking category');
		
		/* Check if there is a product ID */
		if (!$product_id) return false;
		else {
			/* If product_parent_id is true, we have a child product, child products do not have category paths */
			/* We have a category path, need to find the ID */
			if (!$category_id) {
				/* Use CsvCategory() method to confirm/add category tree for this product */
				/* Modification: $category_id now is an array */
				$category_id = $this->CsvCategory($category_path);
			}
			/* We have a category_id, no need to find the path */
			if ($category_id) {
				/* Delete old entries only if the user wants us to*/
				if (!$template->append_categories) {
					$db = JFactory::getDBO();
					$q = "DELETE FROM #__vm_product_category_xref WHERE product_id = ".$product_id;
					$db->setQuery($q);
					$db->query();
					$csvilog->AddMessage('debug', JText::_('Delete old category references'), true);
				}
				else $csvilog->AddMessage('debug', JText::_('Not deleting old category references'));
				
				/* Insert new product/category relationships */
				$category_xref_values = array('product_id' => $product_id, 'product_list' => $product_list);
				foreach( $category_id as $value ) {
					$category_xref_values['category_id'] = $value;
					$this->_vm_product_category_xref->bind($category_xref_values);
					$this->_vm_product_category_xref->store();
					$this->_vm_product_category_xref->reset();
					$category_xref_values['category_id'] = '';
				}
			}
		}
	}
	
	/**
	 * created by: John Syben
	 * Creates categories from slash delimited line
	 *
	 * @var $category_path contains the category/categories for a product
	 * @return array containing category IDs
	 **/
	private function CsvCategory($category_path) {
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check if category_path is an array, if not make it one */
		if (!is_array($category_path)) $category_path = array($category_path);
		
		/* Get all categories in this field delimited with | */
		foreach( $category_path as $line ) {
			$csvilog->AddMessage('debug', 'Checking category path: '.$line);
			
			/* Explode slash delimited category tree into array */
			$category_list = explode("/", $line);
			$category_count = count($category_list);
			
			$db = JFactory::getDBO();
			$category_parent_id = '0';
			
			/* For each category in array */
			for($i = 0; $i < $category_count; $i++) {
				/* See if this category exists with it's parent in xref */
				$q = "SELECT #__vm_category.category_id FROM #__vm_category, #__vm_category_xref ";
				$q .= "WHERE #__vm_category.category_name='" . $db->getEscaped($category_list[$i]) . "' ";
				$q .= "AND #__vm_category_xref.category_child_id=#__vm_category.category_id ";
				$q .= "AND #__vm_category_xref.category_parent_id='".$category_parent_id."'";
				$db->setQuery($q);
				$db->query();
				$csvilog->AddMessage('debug', 'Check if category path exists', true);
				
				/* If it does not exist, create it */
				if ($db->getAffectedRows() > 0) { 
					/* Category exists */
					$category_id = $db->loadResult();
				}
				/* Category does not exist - create it */
				else { 
					$timestamp = time();

					/* Let's find out the last category in the level of the new category */
					$q = "SELECT MAX(list_order) AS list_order FROM #__vm_category, #__vm_category_xref ";
					$q .= "WHERE category_parent_id='".$category_parent_id."' ";
					$q .= "AND category_child_id=category_id ";
					$db->setQuery($q);
					$db->query();
					
					$list_order = intval($db->loadResult())+1;
					
					/* Add category */
					$this->_vm_category->setValue('vendor_id', '1');
					$this->_vm_category->setValue('category_name', $category_list[$i]);
					$this->_vm_category->setValue('cdate', $timestamp);
					$this->_vm_category->setValue('mdate', $timestamp);
					$this->_vm_category->setValue('list_order', $list_order);
					$this->_vm_category->setValue('category_publish', $this->category_publish);
					$this->_vm_category->store();
					
					$csvilog->AddMessage('debug', 'Add new category:', true);
					
					$category_id = $this->_vm_category->getValue('category_id');
					
					/* Create xref with parent */
					$this->_vm_category_xref->setValue('category_parent_id', $category_parent_id);
					$this->_vm_category_xref->setValue('category_child_id', $category_id);
					$this->_vm_category_xref->store();
					
					$csvilog->AddMessage('debug', 'Add new category xref:', true);
					
					/* Clean for the next row */
					$this->_vm_category->reset();
					$this->_vm_category_xref->reset();
					
				}
				/* Set this category as parent of next in line */
				$category_parent_id = $category_id;
			} /* end for */
			$category[] = $category_id;
		}
		/* Return an array with the last category_ids which is where the product goes */
		return $category;
	} /* End function CsvCategory */
}
?>