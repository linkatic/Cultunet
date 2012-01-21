<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class TableRSMembership_Files extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $path = '';
	var $name = '';
	var $description = '';
	var $term_id = null;
	var $visits = 0;
	var $downloads = 0;
	var $thumb = '';
	var $thumb_w = 100;
		
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableRSMembership_Files(& $db)
	{
		parent::__construct('#__rsmembership_files', 'id', $db);
	}
}