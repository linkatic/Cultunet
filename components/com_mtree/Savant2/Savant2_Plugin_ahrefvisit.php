<?php
/**
* Mosets Tree 
*
* @package Mosets Tree 1.50
* @copyright (C) 2005 Mosets Consulting
* @url http://www.mosets.com/
* @author Lee Cher Yeong <mtree@mosets.com>
**/
defined('_JEXEC') or die('Restricted access');

//Base plugin class.
require_once JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'Savant2'.DS.'Plugin.php';

class Savant2_Plugin_ahrefvisit extends Savant2_Plugin {
	
	function plugin( $link, $text='', $newwin=1, $attr=null )
	{
		global $mtconf, $Itemid;

		# Load Parameters
		$params = new JParameter( $link->attribs );
		$params->def( 'show_visit', $mtconf->get('show_visit') );

		if ( $params->get( 'show_visit' ) == 1 && !empty($link->website) ) {

			$html = '';
			// $html = '<img src="images/M_images/indent1.png" width="9" height="9" />';
			$html .= '<a href="';
			$html .= JRoute::_( 'index.php?option=com_mtree&task=visit&link_id='.$link->link_id);
			$html .= '"';

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
			
			if ($newwin) $html .= ' target="_blank"';

			$html .= '>';
			
			if ( empty($text) ) {
				$html .= JText::_( 'Visit' );
			} else {
				$html .= $text;
			}

			$html .= "</a>";

			# Return the visit link
			return $html;
		
		}

	}

}
?>