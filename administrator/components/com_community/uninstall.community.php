<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Dont allow direct linking
defined( '_JEXEC' ) or die('Restricted access');

function com_uninstall() 
{
	$db =& JFactory::getDBO();	
	
	//remove jomsocialuser plugin during uninstall to prevent error during login/logout of joomla.
	$query = 'DELETE FROM ' 
			. $db->nameQuote('#__plugins') . ' '
		 	. 'WHERE ' . $db->nameQuote('element') . '=' . $db->quote('jomsocialuser') . ' AND '
		 	. $db->nameQuote('folder') . '=' . $db->quote('user');

	$db->setQuery($query);
	$db->query();
	
	if(JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'user'.'jomsocialuser.php'))
	{
		JFile::delete(JPATH_ROOT.DS.'plugins'.DS.'user'.'jomsocialuser.php');
	}
	
	if(JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'user'.'jomsocialuser.xml'))
	{
		JFile::delete(JPATH_ROOT.DS.'plugins'.DS.'user'.'jomsocialuser.xml');
	}
	
	return true;   
}