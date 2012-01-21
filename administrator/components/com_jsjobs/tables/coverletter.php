<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Joom Shark by Al-Barr Technologies
 + Contact:		www.joomshark.com, ahmad@joomshark.com, www.al-barr.com , info@al-barr.com
 * Created on:	May 30, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/coverletter.php
 ^ 
 * Description: Table for a company
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableCoverLetter extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $title=null;
	var $description=null;
	var $hits=null;
	var $published=null;
	var $searchable=null;
	var $status=null;
	var $created=null;

	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_coverletters', 'id' , $db );
	}
	
	/** 
	 * Validation
	 * 
	 * @return boolean True if buffer is valid
	 * 
	 */
	 function check()
	 {
/*	    if (trim( $this->title) == '') {
	      $this->_error = "Title cannot be empty.";
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
