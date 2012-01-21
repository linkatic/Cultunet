<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class modGroupWallshelper
{
	function getWalls( &$params )
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT a.*, b.name AS groupname, b.thumb AS thumbnail FROM ' . $db->nameQuote( '#__community_wall' ) . ' AS a '
				. 'INNER JOIN '.  $db->nameQuote( '#__community_groups' ) . ' AS b '
				. 'ON b.id=a.contentid AND b.approvals=' . $db->Quote( 0 ) . ' '
				. 'WHERE ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'groups' ) . ' '
				. 'ORDER BY a.date DESC '
				. 'LIMIT ' . $params->get( 'count' );
		$db->setQuery( $query );
		$rows	= $db->loadObjectList();
		return $rows;
	}
}
