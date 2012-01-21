<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Joom Shark by Al-Barr Technologies
 + Contact:		www.joomshark.com, ahmad@joomshark.com, www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/emailtemplate.php
 ^ 
 * Description: Table for a email template
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableEmailTemplate extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $templatefor=null;
	var $title=null;
	var $subject=null;
	var $body=null;
	var $created=null;
	var $status=null;

	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_emailtemplates', 'id' , $db );
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
