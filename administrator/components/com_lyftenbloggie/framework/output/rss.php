<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * DOM based XML Parser
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieRSS extends JObject
{
	public $DOMProcessingInstructions;
	public $DOMNamespaces;
	public $document;
	public $DOMDocument;
	public $DOMXPath;
	public $useXMLEncoding;		//automatic language detection and converion to UTF-8

	public function __construct( $encode = false )
	{
		$this->useXMLEncoding	= $encode;
	}

	/**
	 * load a local or remote xml document into rss_php
	 *
	 * @param string $url the location of the url, local or remote
	 * @return boolean success
	 **/
	public function load($url=false)
	{
		$returnValue = false;
		if($url) {
			$urlparts = parse_url($url);
			if($urlparts)
			{
				//Get Package
				$objFetchSite 	= & BloggieFactory::load('http');
				if($response = $objFetchSite->get($url, array('timeout' => 60)) )
				{
					if(!$returnValue = $this->loadParser($response['body']) )
					{
						return false;
					}
				} else {
					$this->setError($request->HTTPErrorMsg);
					return false;
				}
			} else {
				$this->setError('BloggieRSS ERROR : Cannot parse the given path / url ['.$url.']');
			}
		} else {
			$this->setError('BloggieRSS ERROR : Parameter 1 [path/url] cannot be null');
		}
		return $returnValue;
	}
		
	/**
	 * load raw xml into BloggieRSS
	 *
	 * @param string $rawxml raw xml in a string
	 * @return boolean success
	 **/
	public function loadXML($rawxml=false)
	{
		if($rawxml)
		{
			return $this->loadParser($rawxml);
		} else {
			$this->setError('BloggieRSS ERROR : Parameter 1 [rawxml] cannot be null');
		}
	}

	/**
	 * load an array into BloggieRSS
	 *
	 * @param array $array to be converted
	 * @param string $rootNodeName if specified a root object of rootNodeName will be created and all array data appened
	 * @return boolean success
	 **/
	public function loadArray($array, $rootNodeName=false)
	{
		$this->DOMDocument = new DOMDocument('1.0', 'UTF-8');
		$this->DOMDocument->strictErrorChecking = false;
		$this->DOMDocument->formatOutput = true;
		$this->DOMDocument->preserveWhiteSpace = false;
		if($rootNodeName) {
			$array = array($rootNodeName => $array);
		}
		$this->convertArray($array);
		return $this->gdoc();
	}
		
	/**
	 * return a referenced array to document
	 *
	 * @param boolean $includeAttributes include all info, default FALSE neat output of node values only
	 * @return array multidimensional associative array of all nodes and reference values
	 **/
	public function &getValues($includeAttributes=false)
	{
		if($includeAttributes)
		{
			return $this->document;
		}
		return $this->valueReturner();
	}

	/**
	 * return return full rss array
	 *
	 * @deprecated for backwards compatibility only, please use getValues()
	 * @param unknown_type $includeAttributes
	 * @return unknown
	 **/
	public function &getRSS($includeAttributes=false)
	{
		return $this->getValues($includeAttributes);
	}
	
	/**
	 * return return rss items
	 *
	 * @deprecated for backwards compatibility only, please use getValues()
	 * @param unknown_type $includeAttributes
	 * @return rss items array
	 **/
	public function &getItems($includeAttributes=false, $limit=false, $offset=false)
	{
		if($includeAttributes)
		{
			$items = $this->getElementsByTagName('item');
		} else {
			$items = $this->getValuesByTagName('item');
		}
		if($limit !== false || $offset !== false)
		{
			$items = array_splice($items, $offset, $limit);
		}
		return $items;
	}

	/**
	 * return the document as an xml document
	 *
	 * @return string XML
	 **/
	public function getXML()
	{
		$this->rebuildDOM($this->document);
		return $this->DOMDocument->saveXML();
	}

	/**
	 * return an assocate array of all DOMElements matching $tagName
	 * including all attributes
	 *
	 * @param string $tagName (tag/node)name to search for
	 * @return array formatted dom node list
	 **/
	public function &getElementsByTagName($tagName)
	{
		return $this->extractDOM($this->DOMDocument->getElementsByTagName($tagName));
	}

	/**
	 * retrieve all namespaces defined in the current document
	 *
	 * @return array
	 **/
	public function getNamespaces()
	{
		return $this->DOMNamespaces;
	}

	/**
	 * retrieve all processing instructions related to the current document
	 *
	 * @return array
	 **/
	public function getProcessingInstructions()
	{
		return $this->DOMProcessingInstructions;
	}

	/**
	 * return a referenced associative array of all values whos (tag/node)name matches $tagName
	 *
	 * @param string $tagName (tag/node)name to search for
	 * @return array referenced array of nodename/value pairs (and any child elements)
	 **/
	public function getValuesByTagName($tagName)
	{
		return $this->transformNodeList($this->DOMDocument->getElementsByTagName($tagName));
	}

	/**
	 * provides XPath query functionality to BloggieRSS
	 *
	 * @param string $XPathQuery must be valid XPath syntax
	 * @return array referenced array of nodename/value pairs (and any child elements)
	 **/
	public function query($XPathQuery, $includeAttributes=false)
	{
		$result = $this->DOMXPath->query($XPathQuery);
		if($includeAttributes)
		{
			return $this->extractDOM($result);
		}
		return $this->transformNodeList($result);
	}

	/**
	 * @internal parse XML and turn into an accessible dom document
	 *
	 * @param string $xml raw xml
	 * @return boolean success
	 **/
	private function loadParser($xml=false)
	{
		if($xml)
		{
			$this->document = array();
			if($this->useXMLEncoding)
			{
				require_once dirname(__FILE__).DS.'encoding'.DS.'iconv.php';
				require_once dirname(__FILE__).DS.'encoding'.DS.'xml.php';

				$xml_encoding = new encoding_xml;
				$xml = $xml_encoding->precode($xml);
			}
			$this->DOMDocument = new DOMDocument;
			$this->DOMDocument->strictErrorChecking = false;
			$this->DOMDocument->formatOutput = true;
			$this->DOMDocument->preserveWhiteSpace = false;
			@$this->DOMDocument->loadXML($xml);

			return $this->gdoc();
		}
		return false;
	}

	/**
	 * @internal set up initial DOMDocument, DOMXPath and extract namespaces
	 *
	 * @return boolean success
	 **/
	private function gdoc()
	{
		$this->DOMXPath = new DOMXPath($this->DOMDocument);
		$this->extractNamespaces();
		if(!$this->document = $this->extractDOM($this->DOMDocument->childNodes))
		{
			$this->setError('The file specified appears not to be a valid xml file');
			return false;
		}
		return true;
	}

	/**
	 * @internal negotiate internal DOMDocument and return an array
	 *
	 * @param DOMNode $valueBlock one of any element which extends a DOMNode
	 * @return array all name/values pairs as multidimensional associative array
	 **/
	private function &valueReturner($valueBlock=false)
	{
		if(!$valueBlock) {
			$valueBlock = $this->document;
		}

		foreach($valueBlock as $valueName => $values)
		{
				if(is_array($values->children)) {
					$valueBlock[$valueName] = $this->valueReturner($values->children);
				} else {
					$valueBlock[$valueName] = &$values->valueData;
				}
		}
		return $valueBlock;
	}

	/**
	 * @internal parses DOMNodeList objects into an associative array for return from public functions
	 *
	 * @param DOMNodeList/DOMNode $nodeList 
	 * @param array $valueBlock current array level
	 * @return array final name/value pairs for return from methods
	 **/
	private function transformNodeList($nodeList, $valueBlock=array())
	{
		$itemCounter = 0;
		$levelNames = array();
		foreach($nodeList as $node)
		{
			if(!isset($levelNames[$node->nodeName]))
			{
				$levelNames[$node->nodeName] = 0;
			} else {
				$levelNames[$node->nodeName]++;
			}
		}
		foreach($nodeList as $values)
		{
			if($levelNames[$values->nodeName])
			{
				$nodeName = $values->nodeName.':'.$itemCounter;
				$itemCounter++;
			} else {
				$nodeName = $values->nodeName;
			}
			if(is_array($values->children))
			{
				$valueBlock[$nodeName] = $this->valueReturner($values->children, $valueBlock);
			} else {
				$valueBlock[$nodeName] = &$values->valueData;
			}
		}
		return $valueBlock;
	}

	/**
	 * @internal update values internally for export
	 *
	 * @param DOMDocument/DOMNodeList $nodes
	 **/
	private function rebuildDOM($nodes=false)
	{
		if(!$nodes) {
			$nodes = $this->document;
		}
		foreach($nodes as $node) {
			if(isset($node->attributeNodes)) {
				foreach($node->attributeNodes as $aName => $aVal) {
					$node->setAttributeNode(new DOMAttr($aName, $aVal));
				}
			}
			if(is_array($node->children)) {
				$this->rebuildDOM($node->children);
			} else {
				if(is_object($node)) {
					if(isset($node->valueDataType)) {
						if($node->valueDataType == 3) {
							$newTextNode = $this->DOMDocument->createTextNode($node->valueData);
							$node->replaceChild($newTextNode, $node->childNodes->item(0));
						} elseif($node->valueDataType == 4) {
							$newCDATANode = $this->DOMDocument->createCDATASection($node->valueData);
							$node->replaceChild($newCDATANode, $node->childNodes->item(0));
						}
					}
				}
			}
		}
	}

	/**
	 * @internal extract registered XMLNS namespaces using XPath
	 *
	 **/
	private function extractNamespaces()
	{
		$namespaces = $this->DOMXPath->query('namespace::*');
		foreach ($namespaces AS $namespace) {
			if($namespace->localName !== 'xml') {
				$this->DOMNamespaces[$namespace->localName] = $this->DOMDocument->lookupNamespaceURI($namespace->localName);
			}
		}
	}

	/**
	 * @internal turn a standard DOMDocument into a more accessible format
	 *
	 * @param DOMDocument $nodeList
	 * @return DOMElement internal return only to create document
	 **/
	private function &extractDOM($nodeList)
	{
		$itemCounter = 0;
		$levelNames = array();
		foreach($nodeList as $node) {
			if(!isset($levelNames[$node->nodeName])) {
				$levelNames[$node->nodeName] = 0;
			} else {
				$levelNames[$node->nodeName]++;
			}
		}
		foreach($nodeList as $values) {
			if($values->nodeType == XML_ELEMENT_NODE) {
				if($levelNames[$values->nodeName]) {
					$nodeName = $values->nodeName.':'.$itemCounter;
					$itemCounter++;
				} else {
					$nodeName = $values->nodeName;
				}
				$tempNode[$nodeName] = array();
				$values->nameString = $values->nodeName;
				if($values->attributes) {
					for($i=0;$values->attributes->item($i);$i++) {
						$values->attributeNodes[$values->attributes->item($i)->nodeName] = &$values->attributes->item($i)->nodeValue;
					}	
				}
				$values->children = $this->extractDOM($values->childNodes);
				$tempNode[$nodeName] = $values;
			} elseif(in_array($values->nodeType, array(XML_TEXT_NODE, XML_CDATA_SECTION_NODE))) {
				$values->parentNode->valueDataType = &$values->nodeType;
				$values->parentNode->valueData = &$values->data;
			} elseif($values->nodeType === XML_PI_NODE) {
				$this->DOMProcessingInstructions[] = array('target' => $values->target, 'data' => $values->data);
			}
			# other wise we ignore as all that's left is DOMComment
			# note DOMComment Will only output in XML format they are not saved!
			
		}
		return $tempNode;
	}
	
	/**
	 * @internal internal array parser, turns any array into a DOMDocument
	 *
	 * @param unknown_type $node
	 * @param unknown_type $parentNode
	 **/
	private function convertArray($node=false,$parentNode=false)
	{
		$toRemove = array();
		foreach($node as $nodeName => $nodeValue) {
			if($parentNode) {
				if(is_numeric($nodeName) && (intval($nodeName) == $nodeName)) {
					$parentNode->parentNode->appendChild($newElement = $this->DOMDocument->createElement($parentNode->nodeName));
					$toRemove[] = $parentNode;
				} else {
					$parentNode->appendChild($newElement = $this->DOMDocument->createElement($nodeName));
				}
			} else {
				$this->DOMDocument->appendChild($newElement = $this->DOMDocument->createElement($nodeName));
			}
			if(is_array($nodeValue)) {
				$this->convertArray($nodeValue,$newElement);
			} else {
				if($nodeValue == strip_tags($nodeValue)) {
					$newTextNode = $this->DOMDocument->createTextNode($nodeValue);
					$newElement->appendChild($newTextNode);
				} else {
					$newCDATANode = $this->DOMDocument->createCDATASection($nodeValue);
					$newElement->appendChild($newCDATANode);
				}
			}
		}
		foreach($toRemove as $remove) {
			if(is_object($remove->parentNode)) {
				$remove->parentNode->removeChild($remove);
			}
		}
	}
}
?>