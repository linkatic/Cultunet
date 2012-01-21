<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMembership_Membership_Users extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $user_id = null;
	var $membership_id = null;
	var $membership_start = 0;
	var $membership_end = 0;
	var $price = 0;
	var $currency = 'EUR';
	var $status = 0;
	var $extras = '';
	var $notes = '';
	
	var $from_transaction_id = 0;
	var $last_transaction_id = 0;
	// these are added for custom use by the payment plugins
	var $custom_1 = '';
	var $custom_2 = '';
	var $custom_3 = '';
	
	var $notified = 0;
	
	var $published = 1;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMembership_Membership_Users(& $db)
	{
		parent::__construct('#__rsmembership_membership_users', 'id', $db);
	}
}