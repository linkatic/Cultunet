<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class COwnerHelper
{
	// Check if the given id is the same and not a guest
	static public function isMine($id1, $id2)
	{
		return ($id1 == $id2) && (($id1 != 0) || ($id2 != 0) );
	}
	
	static public function isRegisteredUser()
	{
		$my		=& JFactory::getUser();
		return (($my->id != 0) && ($my->block !=1));
	}
	
	/**
	 *  Determines if the currently logged in user is a super administrator
	 **/
	static public function isSuperAdministrator()
	{
		return COwnerHelper::isCommunityAdmin();
	}
	
	/**
	 * Check if a user can administer the community
	 */ 
	static public function isCommunityAdmin($userid = null)
	{
		$my	= CFactory::getUser($userid);		
		return ( $my->usertype == 'Super Administrator' || $my->usertype == 'Administrator' || $my->usertype == 'Manager' );
	}
}

/**
 * Deprecated since 1.8
 */
function isMine($id1, $id2)
{
	return COwnerHelper::isMine( $id1, $id2 );
}

/**
 * Deprecated since 1.8
 */
function isRegisteredUser()
{
	return COwnerHelper::isRegisteredUser();
}

/**
 * Deprecated since 1.8
 */
function isSuperAdministrator()
{
	return COwnerHelper::isCommunityAdmin();
}

/**
 * Deprecated since 1.8
 */
function isCommunityAdmin($userid = null)
{
	return COwnerHelper::isCommunityAdmin();
}