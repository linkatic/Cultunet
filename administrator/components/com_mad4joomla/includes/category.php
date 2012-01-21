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
	
	$error = null;
	$sort_order = mosGetParam($_REQUEST, 'sort_order');
	 
	switch($task)
	{
		case 'delete':
			if($id)
					{
					$query = "DELETE FROM #__m4j_category WHERE cid = ".$id;
					$database->setQuery($query);
					if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
					
					// Refactor all Forms with the deleted cid
					$max_sort = null;
					$query = "SELECT MAX(sort_order) AS max_sort FROM #__m4j_jobs WHERE cid = -1";
					$database->setQuery( $query );
					$rows = $database->loadObjectList();
					$max_sort = $rows[0]->max_sort + 1;
					
					$query = "SELECT jid FROM #__m4j_jobs WHERE cid = ". $id ." ORDER BY sort_order ASC" ;
					$database->setQuery( $query );
					$rows = $database->loadObjectList();	
					foreach($rows as $row)
						{
						$query = "UPDATE #__m4j_jobs"
						. "\n SET" 
						. "\n sort_order = ".$max_sort++." , "
						. "\n cid = -1 "
						. "\n WHERE jid = ".$row->jid;
						$database->setQuery($query);
						if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}
					// EOF Refactor
					}
		 break;
		 
		case 'up':
			if($sort_order)
			{
				$query = "SELECT * FROM #__m4j_category WHERE sort_order < ". $sort_order ." ORDER BY sort_order DESC LIMIT 1 ";
				$database->setQuery( $query );
				$rows = $database->loadObjectList();	
				if($rows)
					{
					$prev_id = $rows[0]->cid;
					$prev_sort_order = $rows[0]->sort_order;
					if($id)
						{
							$query = "UPDATE #__m4j_category"
							. "\n SET"
							. "\n sort_order = ".$prev_sort_order." "
							. "\n WHERE cid = ".$id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							
							$query = "UPDATE #__m4j_category"
							. "\n SET"
							. "\n sort_order = ".$sort_order." "
							. "\n WHERE cid = ".$prev_id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}//EOF $id exist
					 }//EOF $rows exist	
			}//EOF $sort_order exist
		break; 
	
	
		case 'down':
			if($sort_order)
			{
				$query = "SELECT * FROM #__m4j_category WHERE sort_order > ". $sort_order ." ORDER BY sort_order ASC LIMIT 1 ";
				$database->setQuery( $query );
				$rows = $database->loadObjectList();	
				if($rows)
						{
						$next_id = $rows[0]->cid;
						$next_sort_order = $rows[0]->sort_order;
						if($id)
							{
								$query = "UPDATE #__m4j_category"
								. "\n SET"
								. "\n sort_order = ".$next_sort_order." "
								. "\n WHERE cid = ".$id;
								$database->setQuery($query);
								if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
								
								$query = "UPDATE #__m4j_category"
								. "\n SET"
								. "\n sort_order = ".$sort_order." "
								. "\n WHERE cid = ".$next_id;
								$database->setQuery($query);
								if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							}//EOF $id exist
						 }//EOF $rows exist	
			}//EOF $sort_order exist
		break; 
	
	    case'publish':
			if($id)
							{
								$query = "UPDATE #__m4j_category"
								. "\n SET"
								. "\n active = 1 "
								. "\n WHERE cid = ".$id;
								$database->setQuery($query);
								if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							}
		break;
			
		case'unpublish':
			if($id)
							{
								$query = "UPDATE #__m4j_category"
								. "\n SET"
								. "\n active = 0 "
								. "\n WHERE cid = ".$id;
								$database->setQuery($query);
								if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							}
		break;		 
		}	

	 
	 
	 
	 
	 
	  
  	HTML_m4j::head(M4J_CATEGORY,$error);
  
 	$helpers->caption(M4J_LANG_CATEGORY);
  
  	  $head = array( M4J_LANG_ACTIVE , M4J_LANG_NAME , M4J_LANG_EMAIL , M4J_LANG_POSITION , '' , '' );
	  $helpers->init_table($head);
	  
	  	// DB Query
		$query = "SELECT * FROM #__m4j_category ORDER BY sort_order ASC";

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
	
		$even=true;
		foreach($rows as $row)
		{
		
	    $widthArray = array('45px','','150px','45px','16px','16px');
		$rowArray = array (
							$helpers->active_button(M4J_CATEGORY,$row->cid,$row->active),
							$row->name ,
							$row->email ,
							$helpers->up_down_button(M4J_CATEGORY,$row->cid,$row->sort_order),
							$helpers->delete_button(M4J_CATEGORY,$row->cid),
							$helpers->edit_button(M4J_CATEGORY_NEW,$row->cid,M4J_LANG_EDIT)	
						  );
		
			$helpers->table_row($rowArray,$even,$widthArray);
			$even = !$even; 
		} //EOF foreach
		//EOF DB Query	
		
	  $helpers->close_table();
  
    if(M4J_SHOW_LEGEND) HTML_m4j::legend('cat');	
  
   
  HTML_m4j::footer();
	
?>
