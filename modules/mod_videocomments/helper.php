<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class modVideoCommentsHelper
{
	function getList(&$params)
	{
		$db		=& JFactory::getDBO();
		
		$limit	= $params->get( 'count' , 5 );

		$query	= "SELECT * FROM #__community_wall AS a "
				. "INNER JOIN #__community_videos AS b "
				. "ON a.contentid=b.id "
				. "WHERE a.type ='videos' "
				. "ORDER BY a.date DESC "
				. "LIMIT " . $limit;
				
		$db->setQuery( $query );
		
		$comments	= $db->loadObjectList();

		return $comments; 
	}
}
