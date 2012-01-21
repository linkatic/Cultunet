<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jun 14, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/userfielddata.php
 ^ 
 * Description: Table for a category
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableUserRole extends JTable
{
	var $id=null;
	var $uid=null;
	var $role=null;
	var $dated=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_userroles', 'id' , $db );
	}
	
	/** 
	 * Validation
	 * 
	 * @return boolean True if buffer is valid
	 * 
	 */
	 function check()
	 {
	 	return true;
	 }
	 	 
}

?>
