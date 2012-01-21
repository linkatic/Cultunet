<?php
/**
 * Templates table
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvi_templates.php 945 2009-07-30 07:18:43Z Roland $
 */

/* No direct access */
defined('_JEXEC') or die('Restricted access');

/**
 * @package CSVImproved
 * @todo Check out the reset function
 */
class TableCsvi_templates extends JTable {
	/** @var int Primary key */
	var $template_id = 0;
	/** @var string */
	var $template_name = null;
	/** @var string */
	var $template_type = null;
	/** @var int */
	var $skip_first_line = 0;
	/** @var int */
	var $show_preview = 0;
	/** @var int */
	var $use_column_headers = 0;
	/** @var int */
	var $collect_debug_info = 0;
	/** @var int */
	var $overwrite_existing_data = 0;
	/** @var int */
	var $skip_default_value = 0;
	/** @var int */
	var $include_column_headers = 0;
	/** @var string */
	var $text_enclosure = null;
	/** @var string */
	var $field_delimiter = null;
	/** @var string */
	var $export_type = null;
	/** @var string */
	var $export_site = null;
	/** @var string */
	var $thumb_create = 0;
	/** @var int */
	var $thumb_width = null;
	/** @var int */
	var $thumb_height = null;
	/** @var int */
	var $shopper_group_id = null;
	/** @var string */
	var $producturl_suffix = null;
	/** @var string */
	var $file_location_product_images = null;
	/** @var string */
	var $file_location_category_images = null;
	/** @var string */
	var $file_location_media = null;
	/** @var string */
	var $file_location_export_files = null;
	/** @var string */
	var $product_publish = null;
	/** @var int */
	var $max_execution_time = null;
	/** @var int */
	var $max_input_time = null;
	/** @var int */
	var $memory_limit = null;
	/** @var int */
	var $post_max_size = null;
	/** @var int */
	var $upload_max_filesize = null;
	/** @var string */
	var $export_filename = null;
	/** @var string */
	var $manufacturer = null;
	/** @var boolean */
	var $ignore_non_exist = 0;
	/** @var boolean */
	var $thumb_extension = 'none';
	/** @var boolean */
	var $append_categories = 0;
	/** @var string */
	var $export_date_format = 'd/m/Y H:i:s';
	/** @var boolean */
	var $use_system_limits = 0;
	/** @var boolean */
	var $add_currency_to_price = 0;
	
	
	/**
	* @param database A database connector object
	*/
	function __construct($db) {
		parent::__construct('#__csvi_templates', 'template_id', $db );
	}
	
	/**
	 * Give some standard values
	 */
	function setStandardValues() {
		/* Set some standard values */
		$this->skip_first_line = 0;
		$this->show_preview = 0;
		$this->use_column_headers = 1;
		$this->collect_debug_info = 0;
		$this->overwrite_existing_data = 1;
		$this->skip_default_value  = 0;
		$this->include_column_headers = 1;
		$this->ignore_non_exist = 0;
		$this->text_enclosure = '~';
		$this->field_delimiter = '^';
		$this->export_type = 'csv';
		$this->thumb_create = 0;
		$this->thumb_width = 90;
		$this->thumb_height = 90;
		$this->product_publish = 'Y';
		$this->file_location_product_images = JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'shop_image'.DS.'product';
		$this->file_location_category_images = JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'shop_image'.DS.'category';
		$this->file_location_media = JPATH_SITE.DS.'media';
		$this->file_location_export_files = JPATH_SITE;
		$this->max_execution_time = intval(ini_get('max_execution_time'));
		$this->max_input_time = intval(ini_get('max_input_time'));
		$this->memory_limit = intval(ini_get('memory_limit'));
		$this->post_max_size = intval(ini_get('post_max_size'));
		$this->upload_max_filesize = intval(ini_get('upload_max_filesize'));
		$this->append_categories = 0;
		$this->export_date_format = 'd/m/Y H:i:s';
		$this->use_system_limits = 0;
		$this->add_currency_to_price = 0;
	}
	
	/**
	 * Cleans the class variables
	 */
	public function reset() {
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') $this->$name = $value;
		}
	}
}
?>