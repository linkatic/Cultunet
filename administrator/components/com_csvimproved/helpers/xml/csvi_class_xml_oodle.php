<?php
/**
 * Oodle XML class
 *
 * @package CSVImproved
 * @subpackage Export
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: csvi_class_xml_oodle.php 665 2009-01-02 07:40:08Z Suami $
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Oodle XML Export Class
 *
 * @package CSVImproved
 * @subpackage Export
 * @link http://www.oodle.com/info/feed/merchandise_feed_doc.html
 */
class CsviXmlOodle {
	
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
	function HeaderText() {
		$this->contents = '<?xml version="1.0" encoding="UTF-8"?>'.chr(10);
		$this->contents .= '<listings>'.chr(10);
		return $this->contents;
	}
	
	/**
	 * Creates the XML footer
	 *
	 * @see $contents
	 * @return string XML header
	 */
	function FooterText() {
		$this->contents = '</listings>'.chr(10);
		return $this->contents;
	}
	
	/**
	 * Opens an XML item node
	 *
	 * @see $contents
	 * @return string XML node data
	 */
	function NodeStart() {
		$this->contents = '<listing>'.chr(10); 
		return $this->contents;
	}
	
	/**
	 * Closes an XML item node
	 *
	 * @see $contents
	 * @return string XML node data
	 */
	function NodeEnd() {
		$this->contents = '</listing>'.chr(10); 
		return $this->contents;
	}
	
	/**
	 * Adds an XML element
	 *
	 * @see $node
	 * @return string XML element
	 */
	function Element($column_header, $cdata=false) {
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
	function ContentText($content, $column_header, $fieldname, $cdata=false) {
		switch ($fieldname) {
			case 'manufacturer_name':
			case 'product_url':
				$cdata = true;
			default:
				$this->contents = $content;
				break;
		}
		return $this->Element($column_header, $cdata);
	}
}
?>
