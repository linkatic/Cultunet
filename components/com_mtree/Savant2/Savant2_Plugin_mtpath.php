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


class Savant2_Plugin_mtpath extends Savant2_Plugin {
	
function plugin( $cat_id, $attr = null)	{

	require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_mtree'.DS.'admin.mtree.class.php');

	$mtPathWay = new mtPathWay( $cat_id );
	$cat_ids = $mtPathWay->getPathWay();
	$cat_ids[] = $cat_id;

	$cat_names = array();

	if ( empty($cat_ids[0]) ) {
		$cat_names[] = JText::_( 'Root' );
	}

	foreach( $cat_ids AS $cid ) {
		// Do not add 'Root' name since its done above already
		if ( $cid > 0 ) {
			$cat_names[] = $mtPathWay->getCatName($cid);
		}
	}

	$html = '<a href="';
	$html .= JRoute::_('index.php?option=com_mtree&task=listcats&cat_id='.$cat_id);
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
	$html .= '>' . htmlspecialchars( implode(JText::_( 'Arrow' ), $cat_names) ) . '</a> ';

	return $html;

	}
}
?>