<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMembership_Extra_Values extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $extra_id = null;
	var $name = '';
	var $description = '';
	var $sku = '';
	var $price = 0;
	var $share_redirect = '';
	var $checked = 0;
	
	var $published = 1;
	var $ordering = null;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMembership_Extra_Values(& $db)
	{
		$cid = JRequest::getVar('extra_id', 0);
		if (is_array($cid))
			$cid = $cid[0];
		
		$this->extra_id = $cid;
		
		parent::__construct('#__rsmembership_extra_values', 'id', $db);
	}
}