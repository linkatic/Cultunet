<?php
/**
 * @version		$Id: MT_DOMIT_Lite_Document.php 575 2009-03-10 11:44:00Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */


defined('_JEXEC') or die('Restricted access');

require_once( $mtconf->getjconf('absolute_path') . '/includes/domit/xml_domit_lite_include.php' );

class MT_DOMIT_Lite_Document extends DOMIT_Lite_Document {
	function loadXMLFromText($xmlText, $useSAXY = true, $preserveCDATA = true, $fireLoadEvent = false) {
		return $this->parseXML($xmlText, $useSAXY, $preserveCDATA, $fireLoadEvent);
	} //loadXML
}
?>