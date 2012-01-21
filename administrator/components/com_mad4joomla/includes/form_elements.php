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
  if($id==-1) mosRedirect(M4J_FORMS);
  include_once(M4J_INCLUDE_FUNCTIONS);
  include_once(M4J_INCLUDE_FORMFACTORY);
  include_once(M4J_INCLUDE_CALENDAR);
  //init_calendar();
  
  $template_name = '';	
  $eid = mosGetParam( $_REQUEST,'eid');
  $sort_order = mosGetParam($_REQUEST, 'sort_order');
  $query = "SELECT * FROM #__m4j_forms WHERE fid = ".$id;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();	
  if($rows[0]->question_width==null) $rows[0]->question_width = 450;				
  if($rows[0]->answer_width==null) $rows[0]->answer_width = 450;
  				
  define(M4J_TABLE_QWIDTH,intval($rows[0]->question_width));
  define(M4J_TABLE_AWIDTH,intval($rows[0]->answer_width));
  define(M4J_PREV_TABLE,(M4J_TABLE_QWIDTH+M4J_TABLE_AWIDTH));
  
  $helpers->init_thickbox();
   
  $preview = new HTML_m4j_preview(); 
  
  switch($task)
	{
		case 'delete':
			if($eid)
					{
					$query = "DELETE FROM #__m4j_formelements WHERE eid = ".$eid;
					$database->setQuery($query);
					if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
					}
		 break;
		 
		case 'up':
			if($sort_order)
			{
				$query = "SELECT * FROM #__m4j_formelements WHERE fid = ".$id." AND sort_order < ". $sort_order ." ORDER BY sort_order DESC LIMIT 1 ";
				$database->setQuery( $query );
				$rows = $database->loadObjectList();	
				if($rows)
					{
					$prev_id = $rows[0]->eid;
					$prev_sort_order = $rows[0]->sort_order;
					if($eid)
						{
							$query = "UPDATE #__m4j_formelements"
							. "\n SET"
							. "\n sort_order = ".$prev_sort_order." "
							. "\n WHERE eid = ".$eid;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							
							$query = "UPDATE #__m4j_formelements"
							. "\n SET"
							. "\n sort_order = ".$sort_order." "
							. "\n WHERE eid = ".$prev_id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}//EOF $eid exist
					 }//EOF $rows exist	
			}//EOF $sort_order exist
		break; 
	
	
		case 'down':
			if($sort_order)
			{
				$query = "SELECT * FROM #__m4j_formelements WHERE fid = ".$id." AND sort_order > ". $sort_order ." ORDER BY sort_order ASC LIMIT 1 ";
				$database->setQuery( $query );
				$rows = $database->loadObjectList();	
				if($rows)
						{
						$next_id = $rows[0]->eid;
						$next_sort_order = $rows[0]->sort_order;
						if($eid)
							{
								$query = "UPDATE #__m4j_formelements"
								. "\n SET"
								. "\n sort_order = ".$next_sort_order." "
								. "\n WHERE eid = ".$eid;
								$database->setQuery($query);
								if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
								
								$query = "UPDATE #__m4j_formelements"
								. "\n SET"
								. "\n sort_order = ".$sort_order." "
								. "\n WHERE eid = ".$next_id;
								$database->setQuery($query);
								if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							}//EOF $eid exist
						 }//EOF $rows exist	
			}//EOF $sort_order exist
		break; 
	
		case'publish':
		if($eid)
						{
							$query = "UPDATE #__m4j_formelements"
							. "\n SET"
							. "\n active = 1 "
							. "\n WHERE eid = ".$eid;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}
		break;
		
		case'unpublish':
		if($eid)
						{
							$query = "UPDATE #__m4j_formelements"
							. "\n SET"
							. "\n active = 0 "
							. "\n WHERE eid = ".$eid;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}
		break;	
		
		case'required':
		if($eid)
						{
							$query = "UPDATE #__m4j_formelements"
							. "\n SET"
							. "\n required = 1 "
							. "\n WHERE eid = ".$eid;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}
		break;
		
		case'not_required':
		if($eid)
						{
							$query = "UPDATE #__m4j_formelements"
							. "\n SET"
							. "\n required = 0 "
							. "\n WHERE eid = ".$eid;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
						}
		break; 
		
	  case'copy':
	  if($eid)
	  	{
				$max_sort = null;
				$query = "SELECT MAX(sort_order) AS max_sort FROM #__m4j_formelements WHERE fid=".$id;

				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				$max_sort = $rows[0]->max_sort;
		
				$query = "SELECT * FROM #__m4j_formelements WHERE eid = ".$eid;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();	
				
				$query = "INSERT INTO #__m4j_formelements"
					. "\n ( fid, required, active , question , form , parameters , options , help , sort_order )"
					. "\n VALUES"
					. "\n ( '".$id."', '".$rows[0]->required."', '".$rows[0]->active."', '".$rows[0]->question." ".M4J_LANG_COPY."', '".$rows[0]->form.
					    "', '".$rows[0]->parameters."', '".addslashes($rows[0]->options)."', '".$rows[0]->help."', '".($max_sort+1)."' )";
					$database->setQuery($query);
					if (!$database->query()) $helpers->dbError($database->getErrorMsg());
					$insert_eid =  $database->insertid();
					
					$query = "UPDATE #__m4j_formelements "
					. "\n SET"
					. "\n html = '".$ff->get_html($rows[0]->form,$insert_eid,parameters($rows[0]->parameters),$rows[0]->options)."' "	
					. "\n WHERE eid = ".$insert_eid;			
					$database->setQuery($query);
					if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 	
		}
	  break;
		
		
	}	

  
  
  		
	  
  HTML_m4j::head(M4J_FORM_ELEMENTS);
  HTML_m4j::form_elements_table_head(); 
	
   // DB Query Getting the Name of the Template
		$query = "SELECT * FROM #__m4j_forms WHERE fid = ".$id;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows) $template_name = $rows[0]->name;
		
		$helpers->caption(M4J_LANG_TEMPLATE_ELEMENTS.$helpers->span($template_name,'m4j_green'),null,M4J_LANG_TEMPLATES.' > '.M4J_LANG_ITEMS);
	
	    $head = array( M4J_LANG_ACTIVE , M4J_LANG_REQUIRED , M4J_LANG_QUESTION , M4J_LANG_TYPE , M4J_LANG_POSITION, '', '' , '' );
	    $helpers->init_table($head);
	  
	  	// DB Query Drawing the Table
		$query = "SELECT * FROM #__m4j_formelements WHERE fid = ".$id." ORDER BY sort_order ASC";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
	
		$even=true;
		foreach($rows as $row)
		{
		
	    $widthArray = array('16px', '16px', '','150px','45px','16px','16px','16px');
		$rowArray = array (
							$helpers->active_button(M4J_FORM_ELEMENTS,$id,$row->active,1,$row->eid),
							$helpers->required_button(M4J_FORM_ELEMENTS,$id,$row->required,1,$row->eid),
							$row->question,
							$m4j_lang_elements[$row->form],
							$helpers->element_up_down_button(M4J_FORM_ELEMENTS,$id,$row->eid,$row->sort_order),
							$helpers->copy_button(M4J_FORM_ELEMENTS,$id,M4J_LANG_DO_COPY,M4J_HIDE_BAR,$row->eid),
							$helpers->element_delete_button(M4J_FORM_ELEMENTS,$id,$row->eid),
							$helpers->element_edit_button(M4J_ELEMENT.'&template_name='.$template_name,$id,$row->eid,$row->form,M4J_LANG_EDIT)	
						  );
		
			$helpers->table_row($rowArray,$even,$widthArray);
			$even = !$even; 
			if($row->active==1)
				$preview->add($row->question,$row->html);
			
			
		} //EOF foreach
		//EOF DB Query	

	  $helpers->close_table();
  	  if(M4J_SHOW_LEGEND) HTML_m4j::legend('formelements');	
  HTML_m4j::form_elements_menu($id,$template_name);	   
  HTML_m4j::footer();
  
?>
