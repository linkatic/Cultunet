<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/state.php
 ^ 
 * Description: Table for a state
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableState extends JTable
{
	var $id=null;
	var $loc=null;
	var $code=null;
	var $name=null;
	var $enabled='N';
	var $countrycode=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_states', 'id' , $db );
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
