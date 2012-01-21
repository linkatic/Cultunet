<?php
/**
 * @category	Events
 * @package		JomSocial
 * @copyright (C) 2010 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CWallTrigger
{
	public function onAfterWallDelete($id)
	{
		$db=& JFactory::getDBO();
			
		$sql = "DELETE FROM #__community_activities "
			." WHERE `params` LIKE ". $db->Quote('%wallid='. $id .'%');
		$db->setQuery($sql);
		$db->query();
	}
	
	public function onWallDisplay( $row )
	{
		CFactory::load( 'helpers' , 'string' );
		CError::assert( $row->comment, '', '!empty', __FILE__ , __LINE__ );
		
		// @rule: Only nl2br text that doesn't contain html tags
		if( !CStringHelper::isHTML( $row->comment ) )
		{
			$row->comment	= CStringHelper::nl2br( $row->comment );
		}
	}

}