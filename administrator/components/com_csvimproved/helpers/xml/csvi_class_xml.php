<?php
/**
 * CSV Improved XML class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvi_class_xml.php 902 2009-05-10 01:50:10Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * CSV Improved XML Export Class
 *
 * @package CSVImproved
 * @subpackage Export
 */
class CsviXmlExport {
	
	/** @var string contains the data to export */
	var $contents = "";
	/** @var string contains the XML node to export */
	var $node = "";
	
	/**
	 * Creates the XML header
	 *
	 * @see $contents
	 * @return string XML header
	 */
	public function HeaderText() {
		$this->contents = '<?xml version="1.0" encoding="UTF-8"?>'.chr(10);
		$this->contents .= '<channel>'.chr(10);
		return $this->contents;
	}
	
	/**
	 * Creates the XML footer
	 *
	 * @see $contents
	 * @return string XML header
	 */
	public function FooterText() {
		$this->contents = '</channel>'.chr(10);
		return $this->contents;
	}
	
	/**
	 * Opens an XML item node
	 *
	 * @see $contents
	 * @return string XML node data
	 */
	public function NodeStart() {
		$this->contents = '<item>'.chr(10); 
		return $this->contents;
	}
	
	/**
	 * Closes an XML item node
	 *
	 * @see $contents
	 * @return string XML node data
	 */
	public function NodeEnd() {
		$this->contents = '</item>'.chr(10); 
		return $this->contents;
	}
	
	/**
	 * Adds an XML element
	 *
	 * @see $node
	 * @return string XML element
	 */
	private function Element($column_header, $cdata=false) {
		$this->node = '<'.$column_header.'>';
		if ($cdata) $this->node .= '<![CDATA[';
		$this->node .= $this->contents;
		if ($cdata) $this->node .= ']]>';
		$this->node .= '</'.$column_header.'>';
		$this->node .= "\n";
		return $this->node;
	}
	
	/**
	 * Handles all content and modifies special cases
	 *
	 * @see $contents
	 * @return string formatted XML element
	 */
	public function ContentText($content, $column_header, $fieldname, $cdata=false) {
		switch ($fieldname) {
			case 'field_default_value':
			case 'product_attribute':
				$cdata = true;
			default:
				$this->contents = $content;
				break;
		}
		return $this->Element($column_header, $cdata);
	}
}
?>
