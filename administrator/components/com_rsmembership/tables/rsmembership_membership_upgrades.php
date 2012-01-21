<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMembership_Membership_Upgrades extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $membership_from_id = null;
	var $membership_to_id = null;
	var $price = 0;
	
	var $published = 1;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMembership_Membership_Upgrades(& $db)
	{
		parent::__construct('#__rsmembership_membership_upgrades', 'id', $db);
	}
}