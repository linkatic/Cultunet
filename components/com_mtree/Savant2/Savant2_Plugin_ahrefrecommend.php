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

class Savant2_Plugin_ahrefrecommend extends Savant2_Plugin {
	
	function plugin( $link, $attr=null )
	{
		global $Itemid, $mtconf;//, $mt_show_recommend;

		# Load Parameters
		$params = new JParameter( $link->attribs );
		$params->def( 'show_recommend', $mtconf->get('show_recommend') );

		if ( $params->get( 'show_recommend' ) == 1 && $mtconf->get('user_recommend') != -1 ) {

			$html = '';
			// $html = '<img src="images/M_images/indent1.png" width="9" height="9" />';

			$html .= '<a href="';

			$html .= JRoute::_( 'index.php?option=com_mtree&task=recommend&link_id='.$link->link_id);
			
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
			
			$html .= '>'.JText::_( 'Recommend' )	."</a>";

			# Return the recommend link
			return $html;
		}

	}

}
?>