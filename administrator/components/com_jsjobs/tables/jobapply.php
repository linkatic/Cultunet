<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/jobapply.php
 ^ 
 * Description: Table for job apply
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableJobApply extends JTable
{
/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $jobid=null;
	var $cvid=null;
	var $apply_date=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_jobapply', 'id' , $db );
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
