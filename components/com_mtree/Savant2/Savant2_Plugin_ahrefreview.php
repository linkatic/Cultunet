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

class Savant2_Plugin_ahrefreview extends Savant2_Plugin {
	
	function plugin($link, $attr=null)
	{
		global $Itemid, $mtconf;//, $mt_show_review;

		# Load Parameters
		$params = new JParameter( $link->attribs );
		$params->def( 'show_review', $mtconf->get('show_review') );

		if ( $params->get( 'show_review' ) == 1 ) {

			$html = '';
			// $html = '<img src="images/M_images/indent1.png" width="9" height="9" />';

			$html .= '<a href="';

			$html .= JRoute::_( 'index.php?option=com_mtree&task=writereview&link_id='.$link->link_id);

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

			$html .= '>'.JText::_( 'Write review' )	."</a>";

			# Return the review link
			return $html;
		}

	}

}
?>