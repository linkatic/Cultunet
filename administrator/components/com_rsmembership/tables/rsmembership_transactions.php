<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMembership_Transactions extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $user_id = null;
	var $user_email = '';
	var $user_data = '';
	var $type = 'new';
	var $params = '';
	var $date = 0;
	var $ip = '';
	var $price = 0;
	var $coupon = '';
	var $currency = 'EUR';
	var $hash = '';
	var $custom = '';
	var $gateway = '';
	var $status = '';
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMembership_Transactions(& $db)
	{
		parent::__construct('#__rsmembership_transactions', 'id', $db);
	}
}