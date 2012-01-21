<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMembership_Users extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $user_id = null;
	
	var $address = '';
	var $city = '';
	var $state = '';
	var $zip = '';
	var $country = '';
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMembership_Users(& $db)
	{
		parent::__construct('#__rsmembership_users', 'user_id', $db);
	}
}