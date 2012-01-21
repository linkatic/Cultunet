<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class CPrivacyHelper
{
	/**
	 * Translate Permission Value to Text
	 * 
	 * @param	int		$permissions
	 * @return	string	Permission type
	 */
	static public function formatPermissions( $permissions = null )
	{
		switch ((int) $permissions)
		{
			case '0':
				$nicePermissions = JText::_('CC PRIVACY PUBLIC');				
				break;
			case '20':
				$nicePermissions = JText::_('CC PRIVACY SITE MEMBERS');				
				break;
			case '30':
				$nicePermissions = JText::_('CC PRIVACY FRIENDS');
				break;
			case '40':
				$nicePermissions = JText::_('CC PRIVACY ME');
				break;
			default:
				$nicePermissions = JText::_('CC PERMISSIONS NOT DEFINED');
		}
		
		return $nicePermissions;
	}
}

/**
 * Deprecated since 1.8
 */
function cFormatPermissions($permissions = null)
{
	return CPrivacyHelper::formatPermissions( $permissions );	
}