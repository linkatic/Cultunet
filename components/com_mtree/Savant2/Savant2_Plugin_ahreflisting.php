<?php
defined('_JEXEC') or die('Restricted access');

/**
* Base plugin class.
*/
require_once JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'Savant2'.DS.'Plugin.php';

/**
* Mosets Tree 
*
* @package Mosets Tree 0.8
* @copyright (C) 2004 Lee Cher Yeong
* @url http://www.mosets.com/
* @author Lee Cher Yeong <mtree@mosets.com>
**/


class Savant2_Plugin_ahreflisting extends Savant2_Plugin {
	
	function plugin($link, $link_name, $attr=null, $show=null, $visit=0)
	{
		global $Itemid, $mtconf;
		// $mt_link_new, $mt_link_popular, $mt_user_allowdelete, $mt_user_allowmodify, 

		$my = &JFactory::getUser();

		# Setup default value for $show
		if (!isset($show["link"])) $show["link"] = true;
		if (!isset($show["new"])) $show["new"] = true;
		if (!isset($show["featured"])) $show["featured"] = true;
		if (!isset($show["popular"])) $show["popular"] = true;
		if (!isset($show["edit"])) $show["edit"] = true;
		if (!isset($show["delete"])) $show["delete"] = true;
		if (!isset($show["pending_approval"])) $show["pending_approval"] = true;
		// End of default values
		
		$html = '';
		
		# Editable?
		if ($show["edit"] <> false) {
			if ( $my->id == $link->user_id && $mtconf->get('user_allowmodify') == 1 && $my->id > 0 ) {
				$html .= '<a href="';
				$html .= JRoute::_('index.php?option=com_mtree&task=editlisting&link_id='.$link->link_id);
				$html .= '" class="actionlink">';
				$html .= JText::_( 'Edit' );
				$html .= '</a> ';
			}
		}

		# Delete?
		if ($show["delete"] <> false) {
			if ( $my->id == $link->user_id && $mtconf->get('user_allowdelete') == 1 && $my->id > 0 ) {
				$html .= '<a href="';
				$html .= JRoute::_('index.php?option=com_mtree&task=deletelisting&link_id='.$link->link_id.'&Itemid='.$Itemid);
				$html .= '" class="actionlink"">';
				$html .= JText::_( 'Delete listing' );
				$html .= '</a> ';
			}
		}

		if ( $show["link"] <> false && $link->link_published == 1 && $link->link_approved == 1 ) {
			$html .= '<a href="';

			if ( $visit ) {
				$html .= JRoute::_("index.php?option=com_mtree&task=visit&link_id=".$link->link_id);
			} else {
				$html .= JRoute::_("index.php?option=com_mtree&task=viewlink&link_id=".$link->link_id."&Itemid=".$Itemid);
			}

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
			
			# set the listing text, close the tag
			$html .= '>';
			// if(strlen($link_name) > 55) {
			// 	$html .= substr($link_name,0,50);
			// 	$html .= '...';
			// } else {
				$html .= htmlspecialchars($link_name);
			// }
			$html .= '</a> ';
		
		} else {
			$html .= $link_name.' ';
		}

		if( $show["pending_approval"] && !$link->link_published && !$link->link_approved ) {
			$html .= '<span class="pendingapproval">' . JText::_( 'Pending Approval' ) . '</span>';
		}
		
		if( !$link->link_published && $link->link_approved ) {
			$html .= '<span class="unpublished">' . JText::_( 'Unpublished' ) . '</span>';
		}
		
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

		# Return the listing link
		return $html;
	}

	function datediff($start_date) {
		$jdate		= JFactory::getDate();
		$start = strtotime($start_date);
		$end = $jdate->toUnix();
		if ($start > $end) {
			$temp = $start;
			$start = $end;
			$end = $temp;
		}
		return intval(($end-$start)/(86400));
	}

}
?>