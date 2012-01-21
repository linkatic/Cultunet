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
 * XML encoder
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class encoding_xml
{
	public $OriginalXMLEncoding;
	public $xmlEncoding;
	public $xmlStandalone;
	public $xmlVersion;
	
	public function precode($xml=false)
	{
		if($xml)
		{
			$xml = ltrim($xml,"\xEF\xBB\xBF");
			$this->XMLEncoding = 'UTF-8';
			$DocumentParts = explode("?>", $xml, 2);
			if(trim($DocumentParts[0]) && substr(trim($DocumentParts[0]),0,2) == '<?')
			{
				$XMLDeclaration = $DocumentParts[0];	
				$EncodingDocument = $XMLDeclaration."?>\n".'<testEncoding>Test</testEncoding>';
				$DOMDocument = new DOMDocument;
				$DOMDocument->strictErrorChecking = false;
				@$DOMDocument->loadXML($EncodingDocument);
				$this->OriginalXMLEncoding = strtoupper(trim($DOMDocument->xmlEncoding));
				if(!$this->OriginalXMLEncoding)
				{
					if(preg_match('/encoding="([^"]+)"/',$XMLDeclaration, $preg_encoding)) {
						$this->OriginalXMLEncoding = $preg_encoding[1];
					} else {
						$this->OriginalXMLEncoding = 'UTF-8';
					}
				}
				$this->xmlStandalone = $DOMDocument->xmlStandalone ? 'yes' : 'no';
				$this->xmlVersion = $DOMDocument->xmlVersion;
				$xml = trim($DocumentParts[1]);
			} else {
				$this->OriginalXMLEncoding = 'UTF-8';
				$this->xmlStandalone = 'yes';
				$this->xmlVersion = '1.0';
			}
			
			$xml = $this->fixEncoding($xml);
			return '<?xml version="'.$this->xmlVersion.'" encoding="'.$this->XMLEncoding.'" standalone="'.$this->xmlStandalone.'"?>'."\n".$xml;
		}
		return false;
	}
	
	protected function fixEncoding($in)
	{
		$iconv_encoding = new encoding_iconv;
		if($iconv_encoding->supported($this->OriginalXMLEncoding))
		{
			if($in !== @iconv($this->OriginalXMLEncoding, $this->OriginalXMLEncoding, $in))
			{
				if($in === @iconv('UTF-8', 'UTF-8', $in))
				{
					return $in;
				} else  {
					$encoding = $iconv_encoding->detectEncoding($in);
					if($encoding) {
						return @iconv($encoding, 'UTF-8', $in);
					}
				}
			} else {
				return @iconv($this->OriginalXMLEncoding, 'UTF-8', $in);
			}
		}
		return $in;
	}
}

?>