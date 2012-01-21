<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Joom Shark by Al-Barr Technologies
 + Contact:		www.joomshark.com, ahmad@joomshark.com, www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/jobposting.php
 ^ 
 * Description: Table for a job posting
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableJob extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $companyid=null;
	var $title=null;
	var $jobcategory=null;
	var $jobtype=null;
	var $jobstatus=null;
	var $jobsalaryrange=null;
	var $hidesalaryrange=0;
	var $description=null;
	var $qualifications=null;
	var $prefferdskills=null;
	var $applyinfo=null;
	var $company=null;
	var $country=null;
	var $state=null;
	var $county=null;
	var $city=null;
	var $zipcode=null;
	var $address1=null;
	var $address2=null;
	var $companyurl=null;
	var $contactname=null;
	var $contactphone=null;
	var $contactemail=null;
	var $showcontact=null;
	var $noofjobs=null;
	var $reference=null;
	var $duration=null;
	var $heighestfinisheducation=null;
	var $created=null;
	var $created_by=null;
	var $modified=null;
	var $modified_by=null;

	var $hits=null;
	var $experience=null;
	var $startpublishing=null;
	var $stoppublishing=null;
	var $department=null;
	var $shift=null;
	var $sendemail=0;
	var $metadescription=null;
	var $metakeywords=null;

	var $ordering=null;
	var $status=null;

	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_jobs', 'id' , $db );
	}
	
	/** 
	 * Validation
	 * 
	 * @return boolean True if buffer is valid
	 * 
	 */
	 function check()
	 {
	    if (trim( $this->title ) == '') {
	      $this->_error = "Title cannot be empty.";
	      return false;
	    }else if (trim( $this->description ) == '') {
	      $this->_error = "Description cannot be empty.";
	      return false;
	    }

		return true;
	 }
	 	 
}

?>
