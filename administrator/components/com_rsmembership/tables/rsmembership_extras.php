<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMembership_Extras extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $name = '';
	var $description = '';
	var $type = 'dropdown';
	
	var $published = 1;
	var $ordering = null;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMembership_Extras(& $db)
	{
		parent::__construct('#__rsmembership_extras', 'id', $db);
	}
}