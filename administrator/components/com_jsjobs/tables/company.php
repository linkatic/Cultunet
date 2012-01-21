<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Joom Shark by Al-Barr Technologies
 + Contact:		www.joomshark.com, ahmad@joomshark.com, www.al-barr.com , info@al-barr.com
 * Created on:	May 30, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/company.php
 ^ 
 * Description: Table for a company
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableCompany extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $companyid=null;
	var $category=null;
	var $name=null;
	var $url=null;
	/** @var blob */
	var $logofilename=null;
	var $logoisfile=null;
	var $logo=null;
	var $smalllogofilename=null;
	var $smalllogoisfile=null;
	var $smalllogo=null;
	var $aboutcompanyfilename=null;
	var $aboutcompanyisfile=null;
	var $aboutcompanyfilesize=null;
	var $aboutcompany=null;
	var $contactname=null;
	var $contactphone=null;
	var $companyfax=null;
	var $contactemail=null;
	var $since=null;
	var $companysize=null;
	var $income=null;
	var $description=null;
	var $country=null;
	var $state=null;
	var $county=null;
	var $city=null;
	var $zipcode=null;
	var $address1=null;
	var $address2=null;
	var $created=null;
	var $modified=null;
	var $hits=null;
	var $metadescription=null;
	var $metakeywords=null;
	var $status=null;

	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_companies', 'id' , $db );
	}
	
	/** 
	 * Validation
	 * 
	 * @return boolean True if buffer is valid
	 * 
	 */
	 function check()
	 {
/*	    if (trim( $this->name ) == '') {
	      $this->_error = "Name cannot be empty.";
	      return false;
	    }else if (trim( $this->description ) == '') {
	      $this->_error = "Description cannot be empty.";
	      return false;
	    }
*/
		return true;
	 }
	 	 
}

?>
