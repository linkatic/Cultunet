<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/tables/empapp.php
 ^ 
 * Description: Table for a emplyment application
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableResume extends JTable
{
	var $id = null;
	var $uid = null;
	var $create_date = null;
	var $modified_date = null;
	var $published = null;
	var $hits = null;
	
	var $application_title = null;
	var $first_name = null;
	var $last_name = null;
	var $middle_name = null;
	var $gender = null;

	var $email_address = null;
	var $home_phone = null;
	var $work_phone = null;
	var $cell = null;
	var $nationality = null;
	var $iamavailable = 0;
	var $searchable = 1; //default is 1
	var $photo = null;
	
	var $job_category = null;
	var $jobsalaryrange = null;
	var $jobtype = null;
	var $heighestfinisheducation = null;
	
	var $address_country = null;
	var $address_state = null;
	var $address_county = null;
	var $address_city = null;
	var $address_zipcode = null;
	var $address = null;
	var $address1_country = null;
	var $address1_state = null;
	var $address1_county = null;
	var $address1_city = null;
	var $address1_zipcode = null;
	var $address1 = null;
	var $address2_country = null;
	var $address2_state = null;
	var $address2_county = null;
	var $address2_city = null;
	var $address2_zipcode = null;
	var $address2 = null;

	var $filename = null;
	var $filetype = null;
	var $filesize = null;
	var $filecontent = null;

	var $field1 = null;
	var $field2 = null;
	var $field3 = null;
	var $status = null;
	var $resume = null;

	var $institute = null;
	var $institute_country = null;
	var $institute_state = null;
	var $institute_county = null;
	var $institute_city = null;
	var $institute_address = null;
	var $institute_certificate_name = null;
	var $institute_study_area = null;
	var $institute1 = null;
	var $institute1_country = null;
	var $institute1_state = null;
	var $institute1_county = null;
	var $institute1_city = null;
	var $institute1_address = null;
	var $institute1_certificate_name = null;
	var $institute1_study_area = null;
	var $institute2 = null;
	var $institute2_country = null;
	var $institute2_state = null;
	var $institute2_county = null;
	var $institute2_city = null;
	var $institute2_address = null;
	var $institute2_certificate_name = null;
	var $institute2_study_area = null;
	var $institute3 = null;
	var $institute3_country = null;
	var $institute3_state = null;
	var $institute3_county = null;
	var $institute3_city = null;
	var $institute3_address = null;
	var $institute3_certificate_name = null;
	var $institute3_study_area = null;

	var $employer = null;
	var $employer_position = null;
	var $employer_resp = null;
	var $employer_pay_upon_leaving = null;
	var $employer_supervisor = null;
	var $employer_from_date = null;
	var $employer_to_date = null;
	var $employer_leave_reason = null;
	var $employer_country = null;
	var $employer_state = null;
	var $employer_county = null;
	var $employer_city = null;
	var $employer_zip = null;
	var $employer_phone = null;
	var $employer_address = null;
	var $employer1 = null;
	var $employer1_position = null;
	var $employer1_resp = null;
	var $employer1_pay_upon_leaving = null;
	var $employer1_supervisor = null;
	var $employer1_from_date = null;
	var $employer1_to_date = null;
	var $employer1_leave_reason = null;
	var $employer1_country = null;
	var $employer1_state = null;
	var $employer1_county = null;
	var $employer1_city = null;
	var $employer1_zip = null;
	var $employer1_phone = null;
	var $employer1_address = null;
	
	var $employer2 = null;
	var $employer2_position = null;
	var $employer2_resp = null;
	var $employer2_pay_upon_leaving = null;
	var $employer2_supervisor = null;
	var $employer2_from_date = null;
	var $employer2_to_date = null;
	var $employer2_leave_reason = null;
	var $employer2_country = null;
	var $employer2_state = null;
	var $employer2_county = null;
	var $employer2_city = null;
	var $employer2_zip = null;
	var $employer2_phone = null;
	var $employer2_address = null;
	
	var $employer3 = null;
	var $employer3_position = null;
	var $employer3_resp = null;
	var $employer3_pay_upon_leaving = null;
	var $employer3_supervisor = null;
	var $employer3_from_date = null;
	var $employer3_to_date = null;
	var $employer3_leave_reason = null;
	var $employer3_country = null;
	var $employer3_state = null;
	var $employer3_county = null;
	var $employer3_city = null;
	var $employer3_zip = null;
	var $employer3_phone = null;
	var $employer3_address = null;
	
	var $language = null;
	var $language_reading = null;
	var $language_writing = null;
	var $language_understanding = null;
	var $language_where_learned = null;
	
	var $language1 = null;
	var $language1_reading = null;
	var $language1_writing = null;
	var $language1_understanding = null;
	var $language1_where_learned = null;
	
	var $language2 = null;
	var $language2_reading = null;
	var $language2_writing = null;
	var $language2_understanding = null;
	var $language2_where_learned = null;
	
	var $language3 = null;
	var $language3_reading = null;
	var $language3_writing = null;
	var $language3_understanding = null;
	var $language3_where_learned = null;
	
	var $date_start = null;
	var $can_work = null;
	var $availbale = null;
	var $unavailable = null;
	var $total_experience = null;
	var $skills = null;
	var $driving_license = null;
	var $license_no = null;
	var $license_country = null;

	var $reference = null;
	var $reference_name = null;
	var $reference_country = null;
	var $reference_state = null;
	var $reference_county = null;
	var $reference_city = null;
	var $reference_zipcode = null;
	var $reference_address = null;
	var $reference_phone = null;
	var $reference_relation = null;
	var $reference_years = null;

	var $reference1 = null;
	var $reference1_name = null;
	var $reference1_country = null;
	var $reference1_state = null;
	var $reference1_county = null;
	var $reference1_city = null;
	var $reference1_zipcode = null;
	var $reference1_address = null;
	var $reference1_phone = null;
	var $reference1_relation = null;
	var $reference1_years = null;

	var $reference2 = null;
	var $reference2_name = null;
	var $reference2_country = null;
	var $reference2_state = null;
	var $reference2_county = null;
	var $reference2_city = null;
	var $reference2_zipcode = null;
	var $reference2_address = null;
	var $reference2_phone = null;
	var $reference2_relation = null;
	var $reference2_years = null;

	var $reference3 = null;
	var $reference3_name = null;
	var $reference3_country = null;
	var $reference3_state = null;
	var $reference3_county = null;
	var $reference3_city = null;
	var $reference3_zipcode = null;
	var $reference3_address = null;
	var $reference3_phone = null;
	var $reference3_relation = null;
	var $reference3_years = null;

	function __construct(&$db)
	{
		parent::__construct( '#__js_job_resume', 'id' , $db );
	}
	
	/** 
	 * Validation
	 * 
	 * @return boolean True if buffer is valid
	 * 
	 */
	 function check()
	 {
	    if (trim( $this->application_title ) == '') {
	      $this->_error = "Application Title cannot be empty.";
	      return false;
	    }else if (trim( $this->first_name ) == '') {
	      $this->_error = "First Name cannot be empty.";
	      return false;
	    }else if (trim( $this->last_name ) == '') {
	      $this->_error = "Last Name cannot be empty.";
	      return false;
	    }else if (trim( $this->email_address ) == '') {
	      $this->_error = "Email cannot be empty.";
	      return false;
	    }

		return true;
	 }
	 	 
}

?>
