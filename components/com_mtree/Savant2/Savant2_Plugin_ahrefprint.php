<?php
/**
* Mosets Tree 
*
* @package Mosets Tree 0.8
* @copyright (C) 2004 Lee Cher Yeong
* @url http://www.mosets.com/
* @author Lee Cher Yeong <mtree@mosets.com>
**/
defined('_JEXEC') or die('Restricted access');

//Base plugin class.
require_once JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'Savant2'.DS.'Plugin.php';

class Savant2_Plugin_ahrefprint extends Savant2_Plugin {
	
	function plugin( $link, $attr=null )
	{
		global $Itemid, $mtconf;

		# Load Parameters
		$params = new JParameter( $link->attribs );
		$params->def( 'show_print', $mtconf->get('show_print') );

		if ( $params->get( 'show_print' ) == 1 ) {

			$html = '';
			// $html = '<img src="images/M_images/indent1.png" width="9" height="9" />';

			$html .= '<a ';
			$html .= 'href="index.php?option=com_mtree&amp;task=print&amp;link_id='.$link->link_id.'&amp;tmpl=component&amp;Itemid='.$Itemid.'" ';
			$html .= 'onclick="javascript:void window.open(this.href, \'win2\', \'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no\'); return false;" title="Print"';
			
			# Insert attributes
			if (is_array($attr)) {
				// from array
				foreach ($attr as $key => $val) {
					$key = htmlspecialchars($key);
					$val = htmlspecialchars($val);
					$html .= " $key=\"$val\"";
				}
			} elseif (! is_null($attr)) {
				// from scalar
				$html .= " $attr";
			}
			
			$html .= '>'.JText::_( 'Print' )	."</a>";

			# Return the print link
			return $html;
		}

	}

}
?>