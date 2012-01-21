<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMembership_Membership_Attachments extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $membership_id = null;
	var $path = '';
	
	var $published = 1;
	var $ordering = null;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMembership_Membership_Attachments(& $db)
	{
		parent::__construct('#__rsmembership_membership_attachments', 'id', $db);
	}
}