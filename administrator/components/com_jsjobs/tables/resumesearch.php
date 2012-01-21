<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Joom Shark by Al-Barr Technologies
 + Contact:		www.joomshark.com, ahmad@joomshark.com, www.al-barr.com , info@al-barr.com
 * Created on:	May 30, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/resumesearch.php
 ^ 
 * Description: Table for a company
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableResumeSearch extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $searchname=null;
	var $application_title=null;	
	var $nationality=null;
	var $gender=null;
	var $iamavailable=null;	
	var $category=null;
	var $jobtype=null;
	var $education=null;	
	var $salaryrange=null;
	var $experience=null;	
	var $created=null;
	var $status=null;

	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_resumesearches', 'id' , $db );
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
