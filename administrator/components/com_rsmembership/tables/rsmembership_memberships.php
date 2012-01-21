<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMembership_Memberships extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $category_id = 0;
	var $name = '';
	var $description = "<p>{extras}</p>\n<p>Price: {price}</p>\n<p>Click here to {buy}.</p>";
	var $term_id = 0;
	var $thumb = '';
	var $thumb_w = 100;
	var $sku = '';
	var $price = 0;
	var $use_renewal_price = 0;
	var $renewal_price = 0;
	var $use_coupon = 0;
	var $coupon = '';
	var $coupon_price = 0;
	var $recurring = 0;
	var $share_redirect = '';
	var $period = 30; // '0' for unlimited
	var $period_type = 'd'; // 'h' => 'hour', 'd' => day, 'm' => month (30 days), 'y' => year
	var $use_trial_period = 0;
	var $trial_period = 30; // '0' for unlimited
	var $trial_period_type = 'd'; // 'h' => 'hour', 'd' => day, 'm' => month (30 days), 'y' => year
	var $trial_price = 0;
	var $unique = 0;
	var $no_renew = 0;
	var $stock = 0; // '0' for unlimited
	var $activation = '1'; // '0' => manual, '1' => automatic, '2' => instant
	var $action = '0'; // '0' => thank you, '1' => redirect
	var $thankyou = 'Thank you for purchasing {membership}!';
	var $redirect = '';
	var $user_email_mode = 1;
	var $user_email_from = '';
	var $user_email_from_addr = '';
	
	var $user_email_new_subject = '';
	var $user_email_new_text = '';
	var $user_email_approved_subject = '';
	var $user_email_approved_text = '';
	var $user_email_renew_subject = '';
	var $user_email_renew_text = '';
	var $user_email_upgrade_subject = '';
	var $user_email_upgrade_text = '';
	var $user_email_addextra_subject = '';
	var $user_email_addextra_text = '';
	var $user_email_expire_subject = '';
	var $user_email_expire_text = '';
	var $expire_notify_interval = 3;
	
	var $admin_email_mode = 1;
	var $admin_email_to_addr = '';
	var $admin_email_subject = '';
	var $admin_email_text = '';
	var $custom_code = null;
	
	var $gid_enable = 0;
	var $gid_subscribe = 18;
	var $gid_expire = 18;
	var $disable_expired_account = 0;
	
	var $published = 1;
	var $ordering = null;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMembership_Memberships(& $db)
	{
		parent::__construct('#__rsmembership_memberships', 'id', $db);
	}
}