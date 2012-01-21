<?php
defined('_JEXEC') or die('Restricted access');

/**
* Base plugin class.
*/
require_once JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'Savant2'.DS.'Plugin.php';

/**
* Mosets Tree 
*
* @package Mosets Tree 1.50
* @copyright (C) 2005 Mosets Consulting
* @url http://www.mosets.com/
* @author Lee Cher Yeong <mtree@mosets.com>
**/


class Savant2_Plugin_listingname extends Savant2_Plugin {

	/**
	* 
	* Output an HTML <a href="">...</a> with optional 'Popular', 'Featured', 'New' text.
	* 
	* @access public
	* 
	* @param int $link_id Listing's ID. To be used in the URL
	*
	* @param object $link Reference to link object.
	* 
	* @return string The <a href="">...</a> tag.
	* 
	*/
	
function plugin($link, $attr=null, $show=null)
	{
		global $Itemid, $my, $mtconf;
		//$mt_link_new, $mt_link_popular, $mt_user_allowmodify, $mt_user_allowdelete, $my;

		# Setup default value for $show
		if (!isset($show["link"])) $show["link"] = true;
		if (!isset($show["new"])) $show["new"] = true;
		if (!isset($show["featured"])) $show["featured"] = true;
		if (!isset($show["popular"])) $show["popular"] = true;
		if (!isset($show["edit"])) $show["edit"] = true;
		if (!isset($show["delete"])) $show["delete"] = true;
		// End of default values


		$html = $link->link_name;
		# New Link?
		if ($show["new"] <> false) {
			$jdate		= JFactory::getDate();
			if ( ($jdate->toUnix()-strtotime($link->link_created)) < ($mtconf->get('link_new')*86400) ) {
				$html .= '<sup class="new">'.JText::_( 'Link new' ).'</sup> ';
			}
		}

		# Featured Link?
		if ($show["featured"] <> false) {
			if ( $link->link_featured ) {
				$html .= '<sup class="featured">'.JText::_( 'Link featured' ).'</sup> ';
			}
		}

		# Popular Link?
		if ($show["popular"] <> false) {
			if ( ($this->datediff($link->link_created) > 0) && ($link->link_hits/$this->datediff($link->link_created)) >= $mtconf->get('link_popular') ) {
				$html .= '<sup class="popular">'.JText::_( 'Link popular' ).'</sup> ';
			}
		}

		# Editable?
		if ($show["edit"] <> false) {
			if ( $my->id == $link->user_id && $mtconf->get('user_allowmodify') == 1 && $my->id > 0 ) {
				$html .= ' <a href="';
				$html .= 'index.php?option=com_mtree&task=editlisting&link_id='.$link->link_id.'&Itemid='.$Itemid;
				$html .= '" class="actionlink">';
				$html .= JText::_( 'Edit' );
				$html .= '</a>';
			}
		}

		# Delete?
		if ($show["delete"] <> false) {
			if ( $my->id == $link->user_id && $mtconf->get('user_allowdelete') == 1 && $my->id > 0 ) {
				$html .= ' <a href="';
				$html .= JRoute::_('index.php?option=com_mtree&task=deletelisting&link_id='.$link->link_id.'&Itemid='.$Itemid);
				$html .= '" class="actionlink"">';
				$html .= JText::_( 'Delete listing' );
				$html .= '</a> ';
			}
		}

		# Return the listing link
		return $html;
	}
	
	function datediff($start_date) {
		$jdate	= JFactory::getDate();
		$start	= strtotime($start_date);
		$end	= $jdate->toUnix();
		if ($start > $end) {
			$temp = $start;
			$start = $end;
			$end = $temp;
		}
		return intval(($end-$start)/(24*60*60));
	}

}
?>