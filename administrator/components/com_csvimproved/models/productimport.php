<?php
/**
 * Product import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: productimport.php 947 2009-08-05 08:00:22Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product details
 *
 * Main processor for handling product details.
 *
 * @package CSVImproved
 * @todo Remove images
 * @todo check update null fields
 */
class CsvimprovedModelProductimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_product table */
	private $_vm_product = null;
	/** @var object contains the vm_price table */
	private $_vm_product_price = null;
	/** @var object contains the vm_product_discount table */
	private $_vm_product_discount = null;
	/** @var object contains the vm_product_attribute table */
	private $_vm_product_attribute = null;
	/** @var object contains the vm_product_attribute_sku table */
	private $_vm_product_attribute_sku = null;
	/** @var object contains the vm_product_relations table */
	private $_vm_product_relations = null;
	/** @var object contains the vm_manufacturer table */
	private $_vm_manufacturer = null;
	/** @var object contains the vm_mf_xref table */
	private $_vm_product_mf_xref = null;
	/** @var object contains the vm_tax_rate table */
	private $_vm_tax_rate = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	/** @var array contains a list of categories the product belongs too */
	private $categories = null;
	/** @var bool contains the setting if we are dealing with a child product */
	private $child_product = false;
	/** @var string contains the parent product atributes */
	private $attributes = null;
	/** @var string contains the creation date of a product */
	private $product_cdate = null;
	/** @var string contains the modified date of a product */
	private $product_mdate = null;
	/** @var object contains general import functions */
	private $_importmodel = null;
	/** @var bool contains setting if category has been initialized */
	private $catinit = false;
	/** @var object contains the fields to import */
	private $_csv_fields = null;
	
	/* Public variables */
	/** @var integer contains the value for product boxes */
	public $product_box = 0;
	/** @var integer contains the data for product packaging */
	public $product_packaging = null;
	/** @var string contains a list of related products separated by a | */
	public $related_products = null;
	/** @var array contains the setting if the product needs to be deleted */
	public $product_delete = null;
	/** @var integer contains the ID of the product parent */
	public $product_parent_id = null;
	/** @var bool contains the setting if the discount is a percentage or absolute value */
	public $is_percent = 0;
	/** @var string contains the category path for a product */
	public $category_path = null;
	/** @var integer contains the category ID for a product */
	public $category_id = null;
	/** @var integer contains the category IDs for a product from category_path*/
	public $category_ids = null;
	/** @var integer contains the manufacturer ID */
	public $manufacturer_id = null;
	/** @var integer contains the tax value */
	public $product_tax = null;
	/** @var integer contains the tax ID */
	public $product_tax_id = null;
	/** @var integer contains the discount amount */
	public $amount = null;
	/** @var integer contains the discount start date */
	public $product_discount_date_start = null;
	/** @var integer contains the discount end date */
	public $product_discount_date_end = null;
	/** @var integer contains the product price */
	public $product_price = null;
	/** @var string contains the attribute values for a child product */
	public $attribute_values = null;
	/** @var string contains the parent SKU for a product */
	public $product_parent_sku = null;
	/** @var string contains the order in which the product is listed in the category */
	public $product_list = null;
	/** @var string contains the price including tax */
	public $price_with_tax = null;
	/** @var string contains the product currency */
	public $product_currency = null;
	/** @var string contains the shopper group id */
	public $shopper_group_id = null;
	
	/**
	 * Here starts the processing
	 *
	 * @todo test downloadable files
	 * @todo add data read in case of incorrect columns.
	 */
	public function getStart() {
		/* Get the general import functions */
		$this->_importmodel = new CsvimprovedModelimport();
		
		/* The fields to import */
		if (is_null($this->_csv_fields)) $this->_csv_fields = JRequest::getVar('csv_fields');
		
		/* Get the logger */
		$csvilog = JRequest::getVar('csvilog');
		
		$this->product_id = $this->_importmodel->GetProductId();
		$this->vendor_id = $this->_importmodel->GetVendorId();
		
		/* Process data */
		foreach ($this->_csv_fields as $name => $details) {
			if ($details['published']) {
				$this->_datafield = $this->_importmodel->ValidateCsvInput($name);
				if ($this->_datafield !== false) {
					/* Check if the field needs extra treatment */
					switch ($name) {
						case 'product_available_date':
							$this->product_available_date = $this->ConvertDate();
							break;
						case 'product_discount':
							$this->ProductDiscount();
							break;
						case 'product_discount_date_start':
							$this->$name = $this->ConvertDate();
							break;
						case 'product_discount_date_end':
							$this->$name = $this->ConvertDate();
							break;
						case 'product_price':
						case 'product_weight':
						case 'product_length':
						case 'product_width':
						case 'product_height':
							$this->$name = $this->_importmodel->ToPeriod($this->_datafield);
							break;
						case 'shopper_group_name':
							$this->shopper_group_id = $this->_importmodel->ShopperGroupName($this->_datafield);
							break;
						case 'related_products':
							if (substr($this->_datafield, -1, 1) == "|") $this->related_products = substr($this->_datafield, 0, -1);
							else $this->related_products = $this->_datafield;
							break;
						case 'product_cdate':
							$this->product_cdate = $this->ConvertDate();
							break;
						case 'product_mdate':
							$this->product_mdate = $this->ConvertDate();
							break;
						case 'category_id':
						case 'category_path':
							if (!$this->catinit) {
								if (strlen(trim($this->_datafield)) > 0) {
									$category_ids = array();
									if (stripos($this->_datafield, '|') > 0) $category_ids = explode("|", $this->_datafield);
									else $category_ids[] = $this->_datafield;
									$this->category_ids = $category_ids;
								}
								$this->catinit = true;
							}
							$this->$name = $this->_datafield;
							break;
						case 'manufacturer_name':
							$this->mf_name = $this->_datafield;
							break;
						case 'product_tax':
							$this->$name = $this->_importmodel->ToPeriod($this->_datafield);
							break;
						case 'price_with_tax':
							$this->$name = $this->_importmodel->ToPeriod($this->_datafield);
							break;
						case 'quantity_options':
							$this->$name = strtolower($this->_datafield);
							break;
						case 'product_publish':
							$allowed = array('Y', 'y', 'N', 'n');
							$this->$name = (in_array($this->_datafield, $allowed)) ? strtoupper($this->_datafield) : 'Y';
							break;
						default:
							$this->$name = $this->_datafield;
							break;
					}
				}
				else {
					/* Columns do not match */
					JRequest::setVar('error_found', true);
					$csvi_data = JRequest::getVar('csvi_data');
					$find = array('{configfields}', '{filefields}');
					$replace = array(count($this->_csv_fields), count($csvi_data));
					$fields = array_keys($this->_csv_fields);
					$message =  str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT'));
					$message .= '<br />'.JText::_('FIELDS');
					foreach($this->_csv_fields as $fieldname => $field_details) {
						$message .= '<br />'.$field_details['order'].': '.$fieldname;
					}
					$message .= '<br />'.JText::_('VALUE');
					foreach ($csvi_data AS $key => $data) {
						$message .= '<br />'.$key.': '.$data;
					}
					$csvilog->AddStats('incorrect', $message, true);
					
					return false;
				}
			}
		}
		
		/* Set the default shopper_group_id */
		if (is_null($this->shopper_group_id)) {
			$this->shopper_group_id = $this->_importmodel->GetDefaultShopperGroupID();
		}
		
		/* Calculate product packaging */
		if ($this->product_box > 0 && $this->product_packaging > 0) {
			$this->ProductPackaging();
		}
		
		/* We need the currency */
		if (is_null($this->product_currency) 
			&& (isset($this->product_price)
				|| isset($this->price_with_tax))
			) {
			$this->ProductCurrency();
		}
		
		/* Check for child product and get parent SKU if it is */
		if (!is_null($this->product_parent_sku)) {
			$this->ProductParentSku();
		}
		
		/* Set the record identifier */
		$this->record_identity = (isset($this->product_sku)) ? $this->product_sku : $this->product_id;
		
		/**
		 * Now that all is done, we need to clean the variables only when doing preview
		 */
		$template = JRequest::getVar('template');
		if ($template->show_preview && !JRequest::getVar('was_preview', false)) $this->reset();
		return true;
	}
	
	/**
	 * Getting the product table info
	 */
	private function getTables() {
		$this->_vm_product = $this->getTable('vm_product');
		$this->_vm_product_price = $this->getTable('vm_product_price');
		$this->_vm_product_discount = $this->getTable('vm_product_discount');
		$this->_vm_product_attribute = $this->getTable('vm_product_attribute');
		$this->_vm_product_attribute_sku = $this->getTable('vm_product_attribute_sku');
		$this->_vm_product_relations = $this->getTable('vm_product_relations');
		$this->_vm_manufacturer = $this->getTable('vm_manufacturer');
		$this->_vm_product_mf_xref = $this->getTable('vm_product_mf_xref');
		$this->_vm_tax_rate = $this->getTable('vm_tax_rate');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_product->reset();
		$this->_vm_product_price->reset();
		$this->_vm_product_discount->reset();
		$this->_vm_product_attribute->reset();
		$this->_vm_product_attribute_sku->reset();
		$this->_vm_product_relations->reset();
		$this->_vm_manufacturer->reset();
		$this->_vm_product_mf_xref->reset();
		$this->_vm_tax_rate->reset();
		$this->reset();
	}
	
	/**
	 * Clean the variables
	 */
	private function reset() {
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') {
				$this->$name = $value;
			}
		}
	}
	
	/**
	 * Get the product currency from the vendor 
	 *
	 * @internal If the user does not use product currency we take the one from the current vendor
	 */
	private function ProductCurrency() {
		$csvilog = JRequest::getVar('csvilog');
		$csvilog->AddMessage('debug', JText::_('DEBUG_PRODUCT_CURRENCY'));
		if (!isset($this->product_currency)) {
			$db = JFactory::getDBO();
			$q = "SELECT vendor_currency FROM #__vm_vendor WHERE vendor_id='".$this->vendor_id."' ";
			$db->setQuery($q);
			$this->product_currency = $db->loadResult();
			$csvilog->AddMessage('debug', JText::_('DEBUG_PRODUCT_CURRENCY'), true);
		}
	}
	
	/**
	 * Get the product discount
	 *
	 * Replace commas with periods for correct DB insertion of the product price
	 */
	private function ProductDiscount() {
		$this->ProductDiscountPercentage();
		if ($this->_datafield) {
			if ($this->is_percent) {
				$this->amount = substr(str_replace(",",".",$this->_datafield), 0, -1);
			}
			else $this->amount = str_replace(",",".",$this->_datafield);
		}
	}
	
	/**
	 * Determine if the discount is a percentage or not 
	 */
	private function ProductDiscountPercentage() {
		if ($this->_datafield) {
			if (substr($this->_datafield,-1,1) == "%") {
				$this->is_percent = "1";
			}
		}
	}
	
	/**
	 * Format a date to unix timestamp
	 *
	 * Format of the date is day/month/year or day-month-year.
	 *
	 * @link http://www.csvimproved.com/wiki/doku.php/csvimproved:availablefields:product_available_date
	 * @return integer UNIX timestamp if date is valid otherwise return 0
	 * @todo use JDate
	 */
	private function ConvertDate() {
		/* Date must be in the format of day/month/year or day-month-year */
		$new_date = preg_replace('/-|\./', '/', $this->_datafield);
		$date_parts = explode('/', $new_date);
		if ((count($date_parts) == 3) && ($date_parts[0] > 0 && $date_parts[0] < 32 && $date_parts[1] > 0 && $date_parts[1] < 13 && (strlen($date_parts[2]) == 4))) {
			$old_date = mktime(0,0,0,$date_parts[1],$date_parts[0],$date_parts[2]);
		}
		else $old_date = 0;
		return $old_date;
	}
	
	/**
	 * Get the product packaging
	 *
	 * @internal The number is calculated by hexnumbers
	 */
	private function ProductPackaging() {
		$this->product_packaging = (($this->product_box<<16) | ($this->product_packaging & 0xFFFF)); 
	}
	
	/**
	 * Get the product parent sku if it is a child product 
	 */
	private function ProductParentSku() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$csvilog->AddMessage('debug', JText::_('DEBUG_PRODUCT_PARENT_SKU'));
		if (!$this->category_path && isset($this->product_sku)) {
			/* Check if we are dealing with a child product */
			if ($this->product_parent_sku !== $this->product_sku) {
				$this->child_product = true;
				/**
				 * Get the parent id first
				 * This assumes that the Parent Product already has been added
				 */
				$db->setQuery("SELECT product_id FROM #__vm_product WHERE product_sku = '".$this->product_parent_sku."'");
				$this->product_parent_id = $db->loadResult();
				$csvilog->AddMessage('debug', JText::_('DEBUG_PRODUCT_PARENT_SKU'), true);
			}
			else {
				$this->product_parent_id = 0;
				$this->child_product = false;
			}
		}
	}
	
	/**
	 * Process each record and store it in the database
	 *
	 * @todo Add logging
	 */
	public function getProcessRecord() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$this->_csv_fields = JRequest::getVar('csv_fields');
		$template = JRequest::getVar('template');
		
		if ($this->product_id && !$template->overwrite_existing_data) {
		   $csvilog->AddMessage('debug', str_ireplace('{product_sku}', $this->product_sku, JText::_('DATA_EXISTS_PRODUCT_SKU')));
		   $csvilog->AddStats('skipped', str_ireplace('{product_sku}', $this->product_sku, JText::_('DATA_EXISTS_PRODUCT_SKU')), true);
		}
		else {
			if (empty($this->product_sku) && empty($this->product_id)) {
				$csvilog->AddStats('incorrect', JText::_('DEBUG_NO_SKU'), true);
				$csvilog->AddMessage('debug', JText::_('DEBUG_NO_SKU_OR_ID'));
				return false;
			}
			else {
				$csvilog->AddMessage('debug', JText::_('DEBUG_PROCESS_SKU').$this->record_identity);
			}
			
			$csvilog->AddMessage('debug', JText::_('DEBUG_NORMAL_UPLOAD'));
		  
			/* Load the tables that will contain the data */
			$this->getTables();
			
			/* User wants to delete the product */
			if (isset($this->product_id) && $this->product_delete == "Y") {
				$this->_vm_product->bind($this);
				/* Delete the product */
				if ($this->_vm_product->delete($this->product_id)) {
					/* Delete category reference */
					$q = "DELETE FROM #__vm_product_category_xref WHERE product_id = ".$this->product_id;
					$db->setQuery($q);
					$csvilog->AddMessage('debug', JText::_('DEBUG_DELETE_CATEGORY_XREF'), true);
					$db->query();
					/* Delete category reference */
					$q = "DELETE FROM #__vm_product_product_type_xref WHERE product_id = ".$this->product_id;
					$db->setQuery($q);
					$csvilog->AddMessage('debug', JText::_('DEBUG_DELETE_PRODUCT_TYPE_XREF'), true);
					$db->query();
					/* Delete manufacturer reference */
					$q = "DELETE FROM #__vm_product_mf_xref WHERE product_id = ".$this->product_id;
					$db->setQuery($q);
					$csvilog->AddMessage('debug', JText::_('DEBUG_DELETE_MANUFACTURER_XREF'), true);
					$db->query();
					$csvilog->AddStats('deleted', str_ireplace('{product_sku}', $this->record_identity, JText::_('PRODUCT_DELETED')), true);
				}
			}
			else if (!isset($this->product_id) && $this->product_delete == "Y") {
				$csvilog->AddStats('skipped', str_ireplace('{product_sku}', $this->record_identity, JText::_('NO_PRODUCT_ID')), true);
			}
			else if (!isset($this->product_id) && $template->ignore_non_exist) {
				/* Do nothing for new products when user chooses to ignore new products */
				$csvilog->AddStats('skipped', str_ireplace('{product_sku}', $this->record_identity, JText::_('DATA_EXISTS_IGNORE_NEW')), true);
			}
			/* User wants to add or update the product */
			else {
				/* Check if the user wants to create thumbnails */
				if ($template->thumb_create) {
					/* Check for image URLs */
					if (isset($this->product_full_image) && isset($this->product_thumb_image)) {
						$file_details = $this->_importmodel->ProcessImage($this->product_full_image, $this->product_thumb_image, $template->file_location_product_images);
					}
					else {
						$csvilog->AddStats('skipped', JText::_('NO_THUMB_FULL_IMAGE_IN_FILE'));
					}
				}
				else {
					$csvilog->AddMessage('debug', JText::_('DEBUG_USER_NO_CREATE_THUMB'));
				}
				
				/* Process discount */
				
				if ($this->amount) $this->ProcessDiscount();
				
				
				/* Process tax */
				
				if (isset($this->_csv_fields['product_tax'])) $this->ProcessTax();
				
				
				/* Handle manufacturer data */
				if (isset($this->mf_name) && $this->mf_name) {
					$this->ManufacturerImport();
				}
				else if (!isset($this->_csv_fields['manufacturer_name']) && !isset($this->_csv_fields['manufacturer_id'])) {
					/* User is not importing manufacturer data but we need a default manufacturer associated with the product */
					$this->manufacturer_id = $this->_importmodel->GetDefaultManufacturerID();
				}
				
				/* Process product info */
				
				if ($this->ProductQuery()) {
					/* Check if the price is to be updated */
					
					if (isset($this->_csv_fields['product_price']) || isset($this->_csv_fields['price_with_tax'])) $this->PriceQuery();
					
					/* Handle child product attributes */
					
					if ($this->attributes) $this->ProcessAttributes();
					
					
					/* Handle child product attribute values */
					
					if ($this->attribute_values) $this->ProcessAttributeValues();
					
					
					/* Handle manufacturer data */
					
					if ((isset($this->manufacturer_id) && $this->manufacturer_id)) {
						/* Add a product <--> manufacturer cross reference */
						$this->ManufacturerCrossReference();
					}
					
					/**
					* Process related products
					*
					* Related products are first input in the database as SKU
					* At the end of the import, this is converted to product ID
					*/
					
					if ($this->related_products) $this->ProcessRelatedProducts();
					
					
					/* Process category path */
					
					if (isset($this->_csv_fields["category_path"]) || isset($this->_csv_fields["category_id"])) {
						if (($this->category_ids && !$this->child_product) || $this->category_id) {
							$categorymodel = new CsvimprovedModelCategory();
							$categorymodel->getStart();
							/* Check the categories */
							if (!is_null($this->category_ids) && isset($this->_csv_fields["category_id"])) {
								$categorymodel->CheckCategoryPath($this->product_id, false, $this->category_ids, $this->product_list);
							}
							else if (!is_null($this->category_ids) && isset($this->_csv_fields["category_path"])) {
								$categorymodel->CheckCategoryPath($this->product_id, $this->category_ids, false, $this->product_list);
							}
							
						}
						else {
							$csvilog->AddMessage('debug', JText::_('DEBUG_CHILD_PRODUCT_NO_HANDLING'));
						}
					}
					
				}
				else $csvilog->AddStats('incorrect', str_ireplace('{product_sku}', $this->product_sku, JText::_('NO_UPDATE_PRODUCT_SKU')), true);
			}
			/* Now that all is done, we need to clean the table objects */
			$this->getCleanTables();
		}
		/* Reset vendor for next record */
		JRequest::setVar('vendor_id', false);
	}
   
   /**
    * Execute any processes to finalize the import
	*/
   public function getPostProcessing() {
	  $this->PostProcessRelatedProducts();
   }

	/**
     * Creates either an update or insert SQL query for a product.
     *
     * @todo Check product packaging/product box
     * @return bool true|false true if the query executed successful|false if the query failed
     */
	function ProductQuery() {
      $this->_csv_fields = JRequest::getVar('csv_fields');
	  $csvilog = JRequest::getVar('csvilog');
	  $availablefieldsmodel = JModel::getInstance('availablefields','CsvimprovedModel');
	  $supportedfields = $availablefieldsmodel->GetAvailableFields('productimport');
	  
	  /* Bind the initial data */
	  $this->_vm_product->bind($this);
	  
	  /* Set some initial values */
	  /* Set the modified date as we are modifying the product */
	  $this->_vm_product->mdate = time();
	  
	  /* Add a creating date if there is no product_id */
	  if (is_null($this->product_id)) $this->_vm_product->cdate = time();
	  foreach ($supportedfields as $id => $column) {
		  /* Only process the fields the user is uploading */
         if (isset($this->_csv_fields[$column])) {
			/* Add a redirect for the product cdate */
			if ($column == "product_cdate" && !is_null($this->product_id)) {
				$this->_vm_product->cdate = $this->$column;
			}
			
			/* Add a redirect for the product mdate */
			if ($column == "product_mdate" && !empty($this->$column)) {
				$this->_vm_product->mdate = $this->$column;
			}
		 }
	  }
	  
	  /* We have a succesful save, get the product_id */
	  if ($this->_vm_product->store()) {
		  $csvilog->AddMessage('debug', JText::_('DEBUG_STORE_PRODUCT'), true);
		  if (substr($this->_vm_product->_db->getQuery(), 0, strpos($this->_vm_product->_db->getQuery(),' ')) == 'UPDATE') {
			  $langtype = 'UPDATE_PRODUCT_SKU';
			  $sqltype = 'updated';
		  }
		  else {
			  $langtype = 'ADD_PRODUCT_SKU';
			  $sqltype = 'added';
		  }
		  $csvilog->AddStats($sqltype, str_ireplace('{product_sku}', $this->record_identity, JText::_($langtype)), true);
		  $this->product_id = $this->_vm_product->product_id;
		  return true;
	  }
	  else {
		  $csvilog->AddMessage('debug', JText::_('DEBUG_STORE_PRODUCT'), true);
		  return false;
	  }
   }

	/**
     * Process Related Products
     */
	function ProcessRelatedProducts() {
		$db = JFactory::getDBO();
		$csvilog = JRequest::getVar('csvilog');
		$relatedproducts = explode("|", $this->related_products);
		
		foreach ($relatedproducts AS $key => $relatedproduct) {
			$q = "INSERT INTO csvi_import_related_products
				VALUES (".$db->Quote($this->product_sku).", ".$db->Quote($relatedproduct).")";
				$db->setQuery($q);
				$db->query();
		}
	   
	  if (0) {
		  $this->_vm_product_relations->bind($this);
		  if ($this->_vm_product_relations->store()) return true;
		  else {
			  $csvilog->AddMessage('debug', JText::_('DEBUG_RELATED_PRODUCTS'), true);
		  }
	  }
   }

   /**
    * Post Process Related Products
    */
   function PostProcessRelatedProducts() {
	  $db = JFactory::getDBO();
	  $csvilog = JRequest::getVar('csvilog');
	  $this->_csv_fields = JRequest::getVar('csv_fields');
	  
	  if (isset($this->_csv_fields['related_products'])) {
		  $newrelations = array();
		  /* Get the related products */
		  $q = "SELECT p1.product_id, GROUP_CONCAT(p2.product_id SEPARATOR '|') AS related_products 
		  		FROM csvi_import_related_products r
				LEFT JOIN #__vm_product p1
				ON r.product_sku = p1.product_sku
				LEFT JOIN #__vm_product p2
				ON r.related_sku = p2.product_sku
				GROUP BY r.product_sku";
		  $db->setQuery($q);
		  $relations = $db->loadObjectList();
		  foreach ($relations as $key => $related) {
			  $this->_vm_product_relations->bind($related);
			  $csvilog->AddMessage('debug', JText::_('PROCESS_RELATED_PRODUCTS'), true);
			  if ($this->_vm_product_relations->store());
			  else {
				  $csvilog->AddMessage('debug', JText::_('DEBUG_RELATED_PRODUCTS'), true);
			  }
		  }
	  }
   }
   
   /** 
    * Process tax fields
	*
	* @todo Give user the option to use all fields (tax_state, tax_country)
	* @todo Add logging
    **/
   function ProcessTax() {
		$csvilog = JRequest::getVar('csvilog');
		$this->_csv_fields = JRequest::getVar('csv_fields');
		
		/* Assign the values */
		$this->_vm_tax_rate->bind($this);
		if (isset($this->_csv_fields["product_tax_id"])) $this->_vm_tax_rate->setValue('tax_rate_id', $this->product_tax_id);
		if (isset($this->_csv_fields["product_tax"])) $this->_vm_tax_rate->setValue('tax_rate', $this->product_tax/100);
		$this->_vm_tax_rate->setValue('mdate', time());
		if ($this->_vm_tax_rate->store()) {
			/* Now we have a product tax ID */
			$this->product_tax_id = $this->_vm_tax_rate->getValue('tax_rate_id');
		}
		$csvilog->AddMessage('debug', JText::_('PROCESS_TAX'), true);
	}
	
	/** 
	 * Manufacturer Importer
	 *
	 * Adds or updates a manufacturer and adds a reference to the product
	 * @todo Check for update or insert of mf
	 */
	public function ManufacturerImport() {
		$csvilog = JRequest::getVar('csvilog');
		$csvilog->AddMessage('debug', JText::_('DEBUG_MANUFACTURER_IMPORT'));
		$this->_vm_manufacturer->bind($this);
		if ($this->_vm_manufacturer->store()) {
			$this->manufacturer_id = $this->_vm_manufacturer->getValue('manufacturer_id');
			$csvilog->AddStats('added', JText::_('PROCESS_MANUFACTURER_ADD'), true);
		}
		else {
			$csvilog->AddStats('skipped', JText::_('CANNOT_PROCESS_MANUFACTURER'), true);
			$csvilog->AddMessage('debug', JText::_('DEBUG_CANNOT_PROCESS_MANUFACTURER'), true);
		}
	}
	
	/**
	 * Adds a reference between manufacturer and product
	 */
	private function ManufacturerCrossReference() {
		$csvilog = JRequest::getVar('csvilog');
		$this->_vm_product_mf_xref->bind($this);
		$this->_vm_product_mf_xref->store();
		$csvilog->AddMessage('debug', JText::_('DEBUG_PROCESS_MANUFACTURER_PRODUCT'), true);
	}
	
	/**
	  * Creates either an update or insert SQL query for a product price.
	  *
	  * @name price_query
	  * @author rolandd
	  * @todo add price calculations
	  * @return String update or insert query for a product
	  */
	function PriceQuery() {
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check if the price is including or excluding tax */
		if ($this->product_tax && $this->price_with_tax && is_null($this->product_price)) {
			if (strlen($this->price_with_tax) == 0) $this->product_price = null;
			else $this->product_price = $this->price_with_tax / (1+($this->product_tax/100));
		}
		else if (strlen($this->product_price) == 0) $this->product_price = null;
		
		/* Bind the data */
		$this->_vm_product_price->bind($this);
		
		/* Calculate the new price */
		$this->_vm_product_price->CalculatePrice();
		
		if (is_null($this->product_price)) {
			/* Delete the price */
			$this->_vm_product_price->delete();
		}
		else {
			/* Store the price*/
			/* Add some variables if needed */
			/* Set the modified date if the user has not done so */
			if (!$this->_vm_product_price->getValue('mdate')) $this->_vm_product_price->setValue('mdate', time());
			
			/* Set the create date if the user has not done so and there is no product_price_id */
			if (!$this->_vm_product_price->getValue('mdate') && !$this->_vm_product_price->getValue('product_price_id')) {
				$this->_vm_product_price->setValue('cdate', time());
			}
			
			/* Store the price */
			$this->_vm_product_price->store();
		}
		$csvilog->AddMessage('debug', JText::_('DEBUG_PRICE_QUERY'), true);
	}
	
	/**
	 * Stores the discount for a product
	 *
	 * @todo Add logging
	 */
	private function ProcessDiscount() {
		$csvilog = JRequest::getVar('csvilog');
		if ($this->amount) {
			/* Bind the data */
			$this->_vm_product_discount->bind($this);
			
			/* Add the discount fields */
			$this->_vm_product_discount->setValue('start_date', $this->product_discount_date_start);
			$this->_vm_product_discount->setValue('end_date', $this->product_discount_date_end);
			
			/* Check if a discount already exists */
			$this->_vm_product_discount->check();
			if (!$this->_vm_product_discount->store()) {
				$csvilog->AddMessage('debug', JText::_('DEBUG_ADD_DISCOUNT'), true);
				return false;
			}
			/* Fill the product information with the discount ID */
			$this->product_discount_id = $this->_vm_product_discount->getValue('discount_id');
		}	
		else {
			$csvilog->AddMessage('debug', JText::_('DEBUG_NO_DISCOUNT'));
		}
	}
	
	/**
	 * Process attributes for parent product
	 *
	 * @link http://www.csvimproved.com/wiki/doku.php/csvimproved:availablefields:attributes
	 */
	private function ProcessAttributes() {
		$csvilog = JRequest::getVar('csvilog');
		/* Check if the attributes is to be added */
		if ($this->attributes) {
			$csvfields = JRequest::getVar('csv_fields');
			$csvilog->AddMessage('debug', JText::_('DEBUG_ADDING_ATTRIBUTES'));
			$attributes = explode( "|", $this->attributes );
			$i = 0;
			while(list(,$val) = each($attributes)) {
				$values = explode( "::", $val );
				if (count($values) == 2) {
					/* Fix the array to show the correct names to bind the data */
					$this->_vm_product_attribute_sku->bind(array('product_id' => $this->product_id, 'attribute_name' => $values[0], 'attribute_list' => $values[1]));
					/* Store the data */
					$this->_vm_product_attribute_sku->store();
					$csvilog->AddMessage('debug', JText::_('DEBUG_STORE_ATTRIBUTE'), true);
					/* Clean for new insert */
					$this->_vm_product_attribute_sku->reset();
				}
				else {
					$csvilog->AddMessage('debug', JText::_('DEBUG_ATTRIBUTE_INCORRECT'));
				}
			}
		}
	}
	
	/**
	 * Process attribute values for child product
	 *
	 * @link http://www.csvimproved.com/csv-improved-documentation/available-fields/attribute_values.html
	 */
	private function ProcessAttributeValues() {
		$csvilog = JRequest::getVar('csvilog');
		/* Check if the attributes is to be added */
		if ($this->attribute_values) {
			$csvfields = JRequest::getVar('csv_fields');
			$csvilog->AddMessage('debug', JText::_('DEBUG_ADD_ATTRIBUTES'));
			$attribute_values = explode( "|", $this->attribute_values );
			$i = 0;
			while(list(,$val) = each($attribute_values)) {
				$values = explode( "::", $val );
				if (count($values) == 2) {
					/* Fix the array to show the correct names to bind the data */
					$this->_vm_product_attribute->bind(array('product_id' => $this->product_id, 'attribute_name' => $values[0], 'attribute_value' => $values[1]));
					/* Store the data */
					$this->_vm_product_attribute->store();
					$csvilog->AddMessage('debug', JText::_('DEBUG_ADD_ATTRIBUTES'), true);
					/* Clean for new insert */
					$this->_vm_product_attribute->reset();
				}
				else {
					$csvilog->AddMessage('debug', JText::_('DEBUG_NO_VALID_ATTRIBUTE').' '.$val);
				}
			}
		}
	}
}
?>