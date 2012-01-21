<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

class modPhotoCommentsHelper
{
	function getList(&$params)
	{
		$db		=& JFactory::getDBO();
		
		$limit	= $params->get( 'count' , 5 );

		$query	= "SELECT a.*,b.*,c.type as phototype,c.groupid FROM #__community_wall AS a "
				. "INNER JOIN #__community_photos AS b "
				. "ON a.contentid=b.id "
				. "INNER JOIN #__community_photos_albums AS c "
				. "ON b.albumid=c.id "
				. "WHERE a.type ='photos' "
				. "ORDER BY a.date DESC "
				. "LIMIT " . $limit;
		$db->setQuery( $query );
		
		$comments	= $db->loadObjectList();

		return $comments; 
	}
}
