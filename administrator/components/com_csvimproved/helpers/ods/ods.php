<?php
/**
 * XML parser for ODS files
 *
 * Parses the content.xml file
 *
 * @package CSVImproved
 * @subpackage Helpers
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: ods.php 754 2009-02-14 10:53:32Z Suami $
 *
 * @todo add support for zip file
 * @todo add multiple sheet processing
 */
 
/**
 * Parse ODS files
 *
 * @package CSVImproved
 * @subpackage Helpers
 */
class ODSParser {
	
	/* Set private variables */
	/** @var string filename */
	private $_file = null;
	/** @var string tag */
	private $_tag = null;
	/** @var object the xml parser */
	private $_xml_parser = null;
	/** @var bool set if inside a tag */
	private $_insideitem = false;
	/** @var array tags to process */
	private $_tagprocess = array('TABLE:TABLE-CELL', 'TABLE:TABLE-ROW');
	/** @var array holds the data per node */
	private $_data = array();
	/** @var integer counts the positions */
	private $_poscount = 0;
	/** @var integer counts the number of times a string is repeated */
	private $_columns_repeated = 0;
	
	/* Set public variables */
	/** @var array holds the parsed data */
	public $fulldata = array();
	/** @var int holds the number of rows */
	public $rows = null;
	/** @var int holds the number of columns */
	public $cols = null;
	
	/**
	 * Start to process the XML file
	 *
	 * @todo change die to a proper return result
	 * @todo add support for <table:table-cell/>
	 */
	public function read($filename) {
		$this->_file = $filename;
		list($this->_xml_parser, $fp) = $this->new_xml_parser($this->_file);
		if (!$this->_xml_parser) {
			die("could not open XML input");
		}
		$data = '';
		while (!feof($fp)) {
			$data .= fread($fp, 4096);
		}
		fclose($fp);
		
		/* Regex to find data elements */
		$regex = "#<text:p>(.*?)</text:p>#s";
		/* Find the content links */
		$matches = array();
		$result = preg_match_all($regex, $data, $matches);
		
		/* Loop through the links to link them */
		foreach ($matches[1] as $key => $value) {
			if (!stristr($value, '<![CDATA[')) {
				$find = $matches[0][$key];
				$replace = '<text:p><![CDATA['.$value.']]></text:p>';
				$data = str_replace($find, $replace, $data);
			}
		}
		if (!xml_parse($this->_xml_parser, $data, true)) {
			
			die(sprintf("XML error: %s at line %d\n",
						xml_error_string(xml_get_error_code($this->_xml_parser)),
						xml_get_current_line_number($this->_xml_parser)));
		}
		
		xml_parser_free($this->_xml_parser);
		$this->cols = count($this->fulldata[0]);
		$this->rows = count($this->fulldata);
		return true;
	}
	
	/**
	 * Handle the opening element
	 */
	private function startElement($parser, $tagname, $attribs) {
		if ($this->_insideitem) {
			$this->_tag = $tagname;
		} 
		elseif (in_array($tagname, $this->_tagprocess)) {
			$this->_insideitem = true;
			if (isset($attribs['TABLE:NUMBER-COLUMNS-REPEATED'])) {
				$this->_columns_repeated = $attribs['TABLE:NUMBER-COLUMNS-REPEATED'];
				$this->_poscount += $this->_columns_repeated;
			}
			else $this->_poscount++;
		} 
	}
	
	/**
	 * Handle the closing element
	 */
	private function endElement($parser, $name) {
		/* Add empty fields */
		if ($this->_poscount > count($this->_data)) {
			$addfields = $this->_poscount - count($this->_data);
			for ($addempty = 0; $addempty < $addfields; $addempty++) {
				$this->_data[] = '';
			}
		}
		/* Empty the data array otherwise all data gets put into 1 array */
		if ($name == 'TABLE:TABLE-ROW') {
			/* Check for empty sheets */
			if (count($this->_data) <> 1 && !empty($this->_data[1])) {
				$this->fulldata[] = $this->_data;
			}
			$this->_data = array();
			$this->_poscount = 0;
		}
		
		/* Reset everything */
		$this->_tag = null;
		$this->_insideitem = false;
	}
	
	/**
	 * Handle the data
	 *
	 * @todo parse <text:s text:c="2"/>
	 */
	private function characterData($parser, $data) {
		if ($this->_insideitem) {
			switch ($this->_tag) {
				case 'TEXT:P':
					/* Replace single spaces */
					$data = str_replace('<text:s/>', ' ', $data);
					if (stristr($data, '<text:s')) {
						/* Regex to find data elements */
						$regex = "#<text:s text:c=\"(.*?)\"/>#s";
						/* Find the spaces */
						$matches = array();
						$result = preg_match_all($regex, $data, $matches);
						
						/* Replace the spaces */
						foreach ($matches[1] as $key => $value) {
							$find = '<text:s text:c="'.$value.'"/>';
							$replace = str_repeat(" ", $value);
							$data = str_replace($find, $replace, $data);
						}
					}
					if (!isset($this->_data[1])) $this->_data[1] = $data;
					else {
						if ($this->_columns_repeated >= 2) {
							for ($print = 0; $print < $this->_columns_repeated; $print++) {
								$this->_data[] = $data;
							}
							$this->_columns_repeated = 0;
						}
						else $this->_data[] = $data;
					}
					break;
			}
		}
		
	}
	
	private function new_xml_parser($file) {
		$this->_xml_parser = xml_parser_create("UTF-8");
		xml_parser_set_option($this->_xml_parser, XML_OPTION_CASE_FOLDING, 1);
		xml_set_object($this->_xml_parser, $this);
		xml_set_element_handler($this->_xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($this->_xml_parser, "characterData");
		$fp = fopen($file, "rb");
		if (!$fp) return false;
		else return array($this->_xml_parser, $fp);
	}
}
?>
