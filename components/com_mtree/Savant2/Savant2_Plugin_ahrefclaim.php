<?php
/**
* Mosets Tree 
*
* @package Mosets Tree 2.0
* @copyright (C) 2004-2009 Lee Cher Yeong
* @url http://www.mosets.com/
* @author Lee Cher Yeong <mtree@mosets.com>
**/
defined('_JEXEC') or die('Restricted access');

JLoader::register('JTableUser', JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'user.php');

//Base plugin class.
require_once JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'Savant2'.DS.'Plugin.php';

class Savant2_Plugin_ahrefclaim extends Savant2_Plugin {
	
	function plugin( $link, $attr=null )
	{
		global $Itemid, $mtconf;

		$database	=& JFactory::getDBO();
		
		# Load Parameters
		$params = new JParameter( $link->attribs );
		$params->def( 'show_claim', $mtconf->get('show_claim') );
		
		$owner = new JTableUser( $database );
		$owner->load( $link->user_id );

		if ( $params->get( 'show_claim' ) == 1 && strpos(strtolower($owner->usertype),'administrator') !== false) {

			$html = '';
			// $html = '<img src="images/M_images/indent1.png" width="9" height="9" />';
			$html .= '<a href="';
			$html .= JRoute::_( 'index.php?option=com_mtree&task=claim&link_id='.$link->link_id);
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
			
			$html .= '>'.JText::_( 'Claim' )	."</a>";

			# Return the claim listing link
			return $html;
		}

	}

}
?>