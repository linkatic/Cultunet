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
class TableUserField extends JTable
{
	var $id=null;
	var $name=null;
	var $title=null;
	var $description=null;
	var $type=null;
	var $maxlength=null;
	var $size=null;
	var $required=null;
	var $ordering=null;
	var $cols=null;
	var $rows=null;
	var $value=null;
	var $default=null;
	var $published=null;
	var $fieldfor=null;
	var $readonly=null;
	var $calculated=null;
	var $sys=null;
	var $params=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_userfields', 'id' , $db );
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
