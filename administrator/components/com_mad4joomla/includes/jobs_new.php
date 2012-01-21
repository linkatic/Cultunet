<?php
	/**
	* @version $Id: mad4joomla 10041 2008-03-18 21:48:13Z fahrettinkutyol $
	* @package joomla
	* @copyright Copyright (C) 2008 Mad4Media. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
	* Joomla! is free software. This version may have been modified pursuant
	* to the GNU General Public License, and as distributed it includes or
	* is derivative of works licensed under the GNU General Public License or
	* other free or open source software licenses.
	* See COPYRIGHT.php for copyright notices and details.
	* @copyright (C) mad4media , www.mad4media.de
	*/
	
  defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

  remember_cid();

  //if($id==-1 && ($task=='update' || $task=='edit')) mosRedirect(M4J_JOBS.M4J_REMEMBER_CID_QUERY);
  
  require_once(M4J_INCLUDE_VALIDATE);
  require_once(M4J_INCLUDE_FUNCTIONS);

  $error= null;
  $title = mosGetParam($_REQUEST, 'title');
  $email = mosGetParam($_REQUEST, 'email');
  $introtext =  mosGetParam($_REQUEST, 'introtext',null,_MOS_ALLOWHTML);
  $maintext =  mosGetParam($_REQUEST, 'maintext',null,_MOS_ALLOWHTML);
  $hidden = mosGetParam($_REQUEST, 'hidden');
  $fid = mosGetParam($_REQUEST, 'fid');
  $active =  mosGetParam($_REQUEST, 'active');
  $captcha =  mosGetParam($_REQUEST, 'captcha',1);
  if($active==null) $active = 1;
  $cid = mosGetParam($_REQUEST, 'cid');
  $m4j_category = mosGetParam($_REQUEST, 'm4j_category');
  if($m4j_category) $cid = $m4j_category;
  if ($cid==-2) $cid=-1;
  
  
  $legal_email = $validate->multipleEmail($email);

  $max_sort = null;
		$query = "SELECT MAX(sort_order) AS max_sort FROM #__m4j_jobs WHERE cid=".$cid;

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$max_sort = $rows[0]->max_sort;
  
  
  switch($task)
	{
	
		case 'new':
			if($title!=null && ($legal_email|| $email==null) )
				{
				$query = "INSERT INTO #__m4j_jobs"
						. "\n ( title, hidden, introtext, maintext ,active, fid, cid , email , captcha, sort_order )"
						. "\n VALUES"
						. "\n ( '".$title."', '".$hidden."', '".$introtext."', '".$maintext."', '".
								   $active."', '".$fid."', '".$cid."', '".$email."', '".$captcha."', '".($max_sort+1)."' )";
						$database->setQuery($query);
						if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							
					 mosRedirect(M4J_JOBS.M4J_REMEMBER_CID_QUERY);
				} 
			else 
				{
				  if(!$legal_email && $email!=null) $error = M4J_LANG_NONE_LEGAL_EMAIL;
				  if(!$title) $error .= M4J_LANG_ERROR_NO_TITLE;
				}	
		break;
		//EOF NEW
		

		case 'edit':
			if($id>=0)
			{
				$query = "SELECT * FROM #__m4j_jobs WHERE jid = ".$id;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				$title = $rows[0]->title ;
  				$email = $rows[0]->email ;
		  	    $introtext =  $rows[0]->introtext ;
				$maintext =  $rows[0]->maintext ;
				$hidden = $rows[0]->hidden ;
				$fid = $rows[0]->fid ;
				$active = $rows[0]->active ;
				$cid = $rows[0]->cid ;
				$captcha = $rows[0]->captcha;
				  
			}
		break;
		//EOF EDIT
	
		case 'update':
		
		$former_cid =  mosGetParam($_REQUEST, 'former_cid');
		if($title!=null && ($legal_email|| $email==null) )
				{
				if($former_cid==$cid)
					{
					$query = "UPDATE #__m4j_jobs"
						. "\n SET" 
						. "\n title = '".$title."', "
						. "\n hidden = '".$hidden."', "
						. "\n introtext = '".$introtext."', "
						. "\n maintext = '".$maintext."', "
						. "\n active = ".$active.", "
						. "\n fid = ".$fid.", "
						. "\n cid = ".$cid.", "
						. "\n email = '".$email."', "
						. "\n captcha = '".$captcha."' "
						. "\n WHERE jid = ".$id;
						$database->setQuery($query);
						if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
					mosRedirect(M4J_JOBS.M4J_REMEMBER_CID_QUERY);
					}// CID EQUALS FORMER CID
				else
					{
					$query = "UPDATE #__m4j_jobs"
						. "\n SET" 
						. "\n title = '".$title."', "
						. "\n hidden = '".$hidden."', "
						. "\n introtext = '".$introtext."', "
						. "\n maintext = '".$maintext."', "
						. "\n active = ".$active.", "
						. "\n fid = ".$fid.", "
						. "\n cid = ".$cid.", "
						. "\n sort_order = ".($max_sort+1).", "
						. "\n email = '".$email."', "
						. "\n captcha = '".$captcha."' "
						. "\n WHERE jid = ".$id;
						$database->setQuery($query);
						if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
					
					// Refactor sort_order of Old Category Items
					
					$query = "SELECT jid FROM #__m4j_jobs WHERE cid = ".$former_cid.' ORDER BY sort_order ASC';
						$database->setQuery( $query );
						$rows = $database->loadObjectList();
						
						$sort_order = 1;
						foreach($rows as $row)
							{
							$query = "UPDATE #__m4j_jobs"
							. "\n SET" 
							. "\n sort_order = ".$sort_order++." "
							. "\n WHERE jid = ".$row->jid;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							}
					
					
					}
					mosRedirect(M4J_JOBS.M4J_REMEMBER_CID_QUERY);
				    }
		else 
			{
			  if(!$legal_email && $email!=null) $error = M4J_LANG_NONE_LEGAL_EMAIL;
			  if(!$title) $error .= M4J_LANG_ERROR_NO_TITLE;
			  define("M4J_EDITFLAG",1);
			 
			}
		
		
		
		
			
		break;
		//EOF UPDATE
		
		default:
		$active = 1;
		break;
	}	
	
  	   
  HTML_m4j::head(M4J_JOBS_NEW,$error);
  if(M4J_EDITFLAG==1) $helpers->caption(M4J_LANG_EDIT_FORM,null,M4J_LANG_FORMS.' > '.M4J_LANG_EDIT);
	else $helpers->caption(M4J_LANG_NEW_FORM,null,M4J_LANG_FORMS.' > '.M4J_LANG_NEW_FORM);
  
  
  // Get Category Names
  $query = "SELECT cid,name FROM #__m4j_category ORDER BY sort_order ASC";
			$database->setQuery( $query );
			$categories = $database->loadObjectList();
			
  // Get Template Names
  $query = "SELECT fid,name FROM #__m4j_forms  ORDER BY name ASC";
			$database->setQuery( $query );
			$templates = $database->loadObjectList(); 

  // Eval template ID
   if($fid && $task!='edit')
		{
		$query = "SELECT fid FROM #__m4j_forms  WHERE fid=".$fid;
				$database->setQuery( $query );
				$legal_fid = $database->loadObjectList();
		if(!$legal_fid) $fid = null;
		}	
		
			
  HTML_m4j::jobs_new($title,$email,$active,$captcha,$introtext,$maintext,$cid,$categories,$fid,$templates,$hidden,$id);  
   
   
   
  HTML_m4j::footer();
?>
