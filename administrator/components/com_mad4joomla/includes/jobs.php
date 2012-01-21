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
  
  include(M4J_INCLUDE_FUNCTIONS);
  include_once(M4J_INCLUDE_FORMFACTORY);
  include_once(M4J_INCLUDE_CALENDAR);


  $sort_order = mosGetParam($_REQUEST, 'sort_order');

  // Check cid if is legal
  $legal_check = null;
  $cid = mosGetParam($_REQUEST, 'cid');
  if($cid) $legal_check = $cid;
  else $legal = mosGetParam($_REQUEST, 'remember_cid');
 
  if($legal!=null && $legal !=-1)
  	{
	  $query = "SELECT name FROM #__m4j_category WHERE cid=".$legal." AND active = 1";
	  $database->setQuery( $query );
	  $is_active = $database->loadObjectList();
	  if(!$is_active) $cid =-2;  
  	}
  
  $cid= remember_cid($cid);
  
  $caption = M4J_LANG_FORMS.' ';
  if($cid==-2) $caption = M4J_LANG_ALL_FORMS;
  if($cid==-1) $caption .= M4J_LANG_NO_CATEGORYS;
  if($cid>-1)
  	{
		$query = "SELECT name FROM #__m4j_category WHERE cid=".$cid;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$caption = M4J_LANG_FORMS_OF_CATEGORY . $helpers->span($rows[0]->name,'m4j_green');	
	}
	
  define('M4J_NEW_JOB_CID_QUERY','&cid='.$cid);
  	 
	switch($task)
		{
		
		case 'delete':
			if($id)
					{
					$query = "DELETE FROM #__m4j_jobs WHERE jid = ".$id;
					$database->setQuery($query);
					if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
					}
		 break;
		 
		case 'up':
			if($sort_order && $cid !=-2 )
			{
				$query = "SELECT * FROM #__m4j_jobs WHERE cid = ".$cid." AND sort_order < ". $sort_order ." ORDER BY sort_order DESC LIMIT 1 ";
				$database->setQuery( $query );
				$rows = $database->loadObjectList();	
				if($rows)
					{
					$prev_id = $rows[0]->jid;
					$prev_sort_order = $rows[0]->sort_order;
					if($id)
						{
							$query = "UPDATE #__m4j_jobs"
							. "\n SET"
							. "\n sort_order = ".$prev_sort_order." "
							. "\n WHERE jid = ".$id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							
							$query = "UPDATE #__m4j_jobs"
							. "\n SET"
							. "\n sort_order = ".$sort_order." "
							. "\n WHERE jid = ".$prev_id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}//EOF $id exist
					 }//EOF $rows exist	
			}//EOF $sort_order exist
		break; 
	
	
		case 'down':
			if($sort_order && $cid !=-2 )
			{
				$query = "SELECT * FROM #__m4j_jobs WHERE cid = ".$cid." AND sort_order > ". $sort_order ." ORDER BY sort_order ASC LIMIT 1 ";
				$database->setQuery( $query );
				$rows = $database->loadObjectList();	
				if($rows)
						{
						$next_id = $rows[0]->jid;
						$next_sort_order = $rows[0]->sort_order;
						if($id)
							{
								$query = "UPDATE #__m4j_jobs"
								. "\n SET"
								. "\n sort_order = ".$next_sort_order." "
								. "\n WHERE jid = ".$id;
								$database->setQuery($query);
								if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
								
								$query = "UPDATE #__m4j_jobs"
								. "\n SET"
								. "\n sort_order = ".$sort_order." "
								. "\n WHERE jid = ".$next_id;
								$database->setQuery($query);
								if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							}//EOF $id exist
						 }//EOF $rows exist	
			}//EOF $sort_order exist
		break; 
		
				case'publish':
		if($id)
						{
							$query = "UPDATE #__m4j_jobs"
							. "\n SET"
							. "\n active = 1 "
							. "\n WHERE jid = ".$id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}
		break;
		
		case'unpublish':
		if($id)
						{
							$query = "UPDATE #__m4j_jobs"
							. "\n SET"
							. "\n active = 0 "
							. "\n WHERE jid = ".$id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}
		break;	
		
		case'copy':
		  if($id)
			{
					$max_sort = null;
					$query = "SELECT MAX(sort_order) AS max_sort ".
						  "\n FROM #__m4j_jobs WHERE cid=".
						  "\n ( SELECT cid FROM #__m4j_jobs WHERE jid=".$id.")";

					$database->setQuery( $query );
					$rows = $database->loadObjectList();
					$max_sort = $rows[0]->max_sort;
			
					$query = "SELECT * FROM #__m4j_jobs WHERE jid = ".$id;
					$database->setQuery( $query );
					$rows = $database->loadObjectList();	
					
					$query = "INSERT INTO #__m4j_jobs"
						. "\n ( title, hidden, introtext , maintext , active , fid , cid , email , captcha , sort_order )"
						. "\n VALUES"
						. "\n ( '".$rows[0]->title." ".M4J_LANG_COPY."', '".$rows[0]->hidden."', '".
								   $rows[0]->introtext."', '".$rows[0]->maintext."', '".$rows[0]->active."', '".
								   $rows[0]->fid."', '".$rows[0]->cid."', '".$rows[0]->email."', '".$rows[0]->captcha."', '".($max_sort+1)."' )";
						$database->setQuery($query);
						if (!$database->query()) $helpers->dbError($database->getErrorMsg());
					
			}
		  break;
		}//EOF SWITCH TASK



  HTML_m4j::head(M4J_JOBS);
  
  $query = "SELECT cid,name FROM #__m4j_category ORDER BY sort_order ASC";
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				
  $helpers->caption($caption,$helpers->category_menu($rows,$cid,M4J_JOBS,null,true)); 

	
 if($cid!=-2) 
 	$head = array( M4J_LANG_ACTIVE , M4J_LANG_TITLE , M4J_LANG_EMAIL ,M4J_LANG_CATEGORY, M4J_LANG_TEMPLATES , M4J_LANG_POSITION , '', '' , '', '' );
 else
 	$head = array( M4J_LANG_ACTIVE , M4J_LANG_TITLE , M4J_LANG_EMAIL ,M4J_LANG_CATEGORY, M4J_LANG_TEMPLATES ,'', '', '' , '', '' ); 

	  $helpers->init_table($head);
	  
	  	// DB Query
		if($cid==-2)
		$query = "SELECT a.active as active, a.title as title, a.email as email, b.name as category, a.jid as jid, c.name as template , a.sort_order as sort_order  ".
			  "\n FROM #__m4j_jobs AS a LEFT JOIN #__m4j_category AS b ON (a.cid=b.cid) LEFT JOIN #__m4j_forms AS c ON (a.fid=c.fid) ".
			  "\n WHERE  (a.cid = b.cid OR a.cid = -1) AND a.fid = c.fid AND a.public = 1 ORDER BY a.cid ASC, a.sort_order ASC";
		else
		$query = "SELECT a.active as active, a.title as title, a.email as email, b.name as category, a.jid as jid, c.name as template ,  a.sort_order as sort_order  ".
			  "\n FROM #__m4j_jobs AS a LEFT JOIN #__m4j_category AS b ON (a.cid=b.cid) LEFT JOIN #__m4j_forms AS c ON (a.fid=c.fid) ".
			  "\n WHERE ( (a.cid = b.cid OR a.cid = -1) AND a.cid = ".$cid.") AND a.fid = c.fid AND a.public = 1 ORDER BY a.sort_order ASC";
		

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
	
		$even=true;
		foreach($rows as $row)
		{
		($row->category)?$cat = $row->category:$cat=M4J_LANG_NO_CATEGORYS;
		$widthArray = array('16px','','100px','150px', '150px', '45px','16px','16px','16px','16px');
		
		$position = "";
		if($cid!=-2) $position = $helpers->up_down_button(M4J_JOBS.M4J_NEW_JOB_CID_QUERY,$row->jid,$row->sort_order);
		
				$rowArray = array (
									$helpers->active_button(M4J_JOBS.M4J_NEW_JOB_CID_QUERY,$row->jid,$row->active),
									$row->title ,
									$row->email ,
									$cat,
									$row->template,
									$position,
									$helpers->copy_button(M4J_JOBS.M4J_NEW_JOB_CID_QUERY,$row->jid),
									$helpers->delete_button(M4J_JOBS.M4J_NEW_JOB_CID_QUERY,$row->jid),
									$helpers->edit_button(M4J_JOBS_NEW.M4J_NEW_JOB_CID_QUERY,$row->jid,M4J_LANG_EDIT),
									$helpers->link_button(M4J_LINK,$row->jid,$row->title)
								  );			 
		
			$helpers->table_row($rowArray,$even,$widthArray);
			$even = !$even; 
		} //EOF foreach
		//EOF DB Query	
		

	  $helpers->close_table();	
	  if($cid==-2) $helpers->advice(M4J_LANG_ASSIGN_ORDER_HINT);
	  if(M4J_SHOW_LEGEND) HTML_m4j::legend('jobs');	
	 
 
  HTML_m4j::footer();
   

?>
