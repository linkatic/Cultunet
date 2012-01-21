<?php
defined('_JEXEC') or die('Restricted access');

/**
* Base plugin class.
*/
require_once JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'Savant2'.DS.'Plugin.php';

/**
* Mosets Tree 
*
* @package Mosets Tree 2.0
* @copyright (C) 2007 Lee Cher Yeong
* @url http://www.mosets.com/
* @author Lee Cher Yeong <mtree@mosets.com>
**/


class Savant2_Plugin_review_rating extends Savant2_Plugin {

	function plugin($rating)
	{
		if( $rating > 0 && $rating <= 5 ) {
			$star = round($rating, 0);
			$html = '';

			// Print starts
			for( $i=0; $i<$star; $i++) {
				$html .= '<img src="'.JURI::base().'components/com_mtree/img/star_10.png" width="16" height="16" hspace="1" alt="★" />';
			}

			// Print blank star
			for( $i=$star; $i<5; $i++) {
				$html .= '<img src="'.JURI::base().'components/com_mtree/img/star_00.png" width="16" height="16" hspace="1" alt="" />';
			}

			# Return the listing link
			return $html;
		} else {
			return '';
		}

	}
}
?>