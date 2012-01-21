<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

if( !function_exists('getLatestMember') )
{
	function getLatestMember($limit = 15, $updated_avatar_only)
	{
		$db	 =& JFactory::getDBO();
		
		if($updated_avatar_only)
		{
			$condition = 'AND b.' . $db->nameQuote( 'avatar' ) . ' != ' . $db->Quote( 'components/com_community/assets/default.jpg' ) . ' ';
		}
		else
		{
			$condition = ' ';
		}
		
		$query	= 'SELECT * FROM ' . $db->nameQuote( '#__users' ) . ' AS a ' 
				. 'INNER JOIN ' . $db->nameQuote( '#__community_users' ) . ' AS b ON a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'userid' ) . ' '
				. 'WHERE a.' . $db->nameQuote( 'block' ) . ' = ' . $db->Quote( 0 ) . ' '
				. $condition
				. 'ORDER BY a.' . $db->nameQuote( 'registerDate' ) . ' '
				. 'DESC LIMIT ' . $limit;
		$db->setQuery( $query );
		
		$result = $db->loadObjectList();
	
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result;
	}
}

?>
