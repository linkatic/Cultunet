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
	include_once(M4J_INCLUDE_FORMFACTORY);
	include_once(M4J_INCLUDE_FUNCTIONS);
	$error= null;
	
	switch($task)
	{
		case 'delete':
			if($id)
					{
					
					$query = "SELECT COUNT(*) as summe FROM #__m4j_forms";

					$database->setQuery( $query );
					$rows = $database->loadObjectList();
					if( $rows[0]->summe >1)
						{
							$query = "DELETE FROM #__m4j_forms WHERE fid = ".$id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							
							$query = "DELETE FROM #__m4j_formelements WHERE fid = ".$id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg());		
		
							$query = "DELETE FROM #__m4j_jobs WHERE fid = ".$id;
							$database->setQuery($query);
							if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 		
						}
					else $error = M4J_LANG_AT_LEAST_ONE;
					}
		 break;
		 
		 case'copy':
		 if($id>=0)
			{
			
				$query = "SELECT * FROM #__m4j_forms WHERE fid = ".$id;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
					$name = $rows[0]->name.' '.M4J_LANG_COPY;
					$desc = $rows[0]->description;
					$question_width = $rows[0]->question_width;
					$answer_width = $rows[0]->answer_width;
					
				$query = "INSERT INTO #__m4j_forms"
				. "\n ( name, description, question_width, answer_width )"
				. "\n VALUES"
				. "\n ( '".$name."', '".$desc."', '".$question_width."', '".$answer_width."' )";
				$database->setQuery($query);
				if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
				$insert_id = $database->insertid();
				
				$query = "SELECT * FROM #__m4j_formelements WHERE fid = ".$id ;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				foreach($rows as $row)
					{
					$query = "INSERT INTO #__m4j_formelements"
					. "\n ( fid, required, active , question , form , parameters , options , help , sort_order )"
					. "\n VALUES"
					. "\n ( '".$insert_id."', '".$row->required."', '".$row->active."', '".$row->question."', '".$row->form.
					    "', '".$row->parameters."', '".addslashes($row->options)."', '".$row->help."', '".$row->sort_order."' )";
					$database->setQuery($query);
					if (!$database->query()) $helpers->dbError($database->getErrorMsg());
					$insert_eid =  $database->insertid();
					
					$query = "UPDATE #__m4j_formelements "
					. "\n SET"
					. "\n html = '".$ff->get_html($row->form,$insert_eid,parameters($row->parameters),$row->options)."' "	
					. "\n WHERE eid = ".$insert_eid;			
					$database->setQuery($query);
					if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 	
					}//EOF foreach
				
				
			}
		 break;
		 
		 
	}	

	  HTML_m4j::head(M4J_FORMS,$error);
	  $helpers->caption(M4J_LANG_TEMPLATES);

	  $head = array( M4J_LANG_NAME,M4J_LANG_SHORTDESCRIPTION,'','','',M4J_LANG_ITEMS );
	  $helpers->init_table($head);
	  
	  	// DB Query
		$query = "SELECT a.name AS name, a.description AS description, a.fid AS fid, count(*) AS total
				  FROM #__m4j_forms AS a LEFT JOIN #__m4j_formelements AS b ON (a.fid=b.fid)
                  WHERE a.fid = b.fid AND a.public = 1 GROUP BY name";


		$database->setQuery( $query );
		$rows = $database->loadObjectList();
	
		$even=true;
		foreach($rows as $row)
		{
		
	    $widthArray = array('','500px','16px','16px','16px','125px');
		$rowArray = array (
							$row->name,
							$row->description,
							$helpers->copy_button(M4J_FORMS,$row->fid,M4J_LANG_DO_COPY),
							$helpers->delete_button(M4J_FORMS,$row->fid,M4J_LANG_TEMPLATE_DELETE_CAUTION),
							$helpers->edit_button(M4J_FORM_NEW,$row->fid,M4J_LANG_EDIT_MAIN_DATA),
							$helpers->link(M4J_FORM_ELEMENTS.M4J_HIDE_BAR.M4J_EDIT."&id=".$row->fid,$row->total.' '.M4J_LANG_EDIT_TEMPLATE_ITEMS)		
						  );
		
			$helpers->table_row($rowArray,$even,$widthArray);
			$even = !$even; 
		} //EOF foreach
		//EOF DB Query	
		
	  $helpers->close_table();
  	  if(M4J_SHOW_LEGEND) HTML_m4j::legend('forms');	
	  HTML_m4j::footer();
?>
