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
	
	 $error= null;
	 $name = mosGetParam($_REQUEST, 'name');
	 $desc = mosGetParam($_REQUEST, 'description');
	 $qwidth =  mosGetParam($_REQUEST, 'qwidth');
	 $awidth =  mosGetParam($_REQUEST, 'awidth');
	 $use_help =  mosGetParam($_REQUEST, 'use_help');
	
	switch($task){
		
		case 'new':
		if($name!=null && $name!="") 
			{			
			$query = "INSERT INTO #__m4j_forms"
				. "\n ( name, description, question_width, answer_width, use_help  )"
				. "\n VALUES"
				. "\n ( '".$name."', '".$desc."', '".$qwidth."', '".$awidth."', '".$use_help."' )";
				$database->setQuery($query);
				if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
			
				
			 mosRedirect(M4J_FORM_ELEMENTS.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'&id='.$database->insertid());
	
			}
		else $error= M4J_LANG_TEMPLATE_NAME_REQUIRED;
		break;
		//EOF NEW
		
		default:
		case 'edit':
	
		if($id>=0)
		{
			$query = "SELECT * FROM #__m4j_forms WHERE fid = ".$id;
			$database->setQuery( $query );
			$rows = $database->loadObjectList();
				$name = $rows[0]->name;
				$desc = $rows[0]->description;			
				$qwidth = $rows[0]->question_width;
				$awidth = $rows[0]->answer_width;	
				$use_help = $rows[0]->use_help;			
		
		}
		break;
		//EOF EDIT
		
		case 'update':
		case 'updateproceed':
				
		$editID = mosGetParam($_REQUEST, 'editID');
		if($name!=null && $name!="" && $editID>=0) 
			{
			$query = "UPDATE #__m4j_forms"
				. "\n SET"
				. "\n name = '".$name."', "
				. "\n description = '".$desc."', "
				. "\n question_width = '".$qwidth."', "
				. "\n answer_width = '".$awidth."', "
				. "\n use_help = '".$use_help."'"
				. "\n WHERE fid = ".$editID;
				$database->setQuery($query);
			 if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
			if ($task=='update') mosRedirect(M4J_FORMS.M4J_REMEMBER_CID_QUERY); else mosRedirect(M4J_FORM_ELEMENTS.M4J_REMEMBER_CID_QUERY.M4J_HIDE_BAR.'&id='.$editID);
			}
		else 
			{
			$error= M4J_LANG_TEMPLATE_NAME_REQUIRED;	
			define("M4J_EDITFLAG",1);
			if($editID) $id = $editID;
			}
		break;
		//EOF UPDATE
	} 
	  
  HTML_m4j::head(M4J_FORM_NEW,$error);
	
	if(M4J_EDITFLAG==1) $helpers->caption(M4J_LANG_EDIT_NAME,null,M4J_LANG_TEMPLATES.' > '.M4J_LANG_EDIT);
	else $helpers->caption(M4J_LANG_NEW_TEMPLATE_LONG,null,M4J_LANG_TEMPLATES.' > '.M4J_LANG_NEW_TEMPLATE);			

  HTML_m4j::new_form($name,$desc,$id,$qwidth,$awidth,$use_help); 
   
  HTML_m4j::footer();

?>
