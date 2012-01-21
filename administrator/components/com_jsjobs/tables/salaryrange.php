<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/tables/salaryrange.php
 ^ 
 * Description: Table for salary range
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableSalaryRange extends JTable
{
/** @var int Primary key */
	var $id=null;
/** @var string */
	var $rangevalue=null;
/** @var string */
	var $rangestart=null;
/** @var string */
	var $rangeend=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_salaryrange', 'id' , $db );
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
