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
 

  require_once(M4J_INCLUDE_VALIDATE);
  require_once(M4J_INCLUDE_FUNCTIONS);
  
  $error= null;
  $name= mosGetParam($_REQUEST, 'name');
  $email= mosGetParam($_REQUEST, 'email');
  $active = mosGetParam($_REQUEST, 'active');
  $intro = mosGetParam($_REQUEST, 'intro',null,_MOS_ALLOWHTML);	

	$legal_email = $validate->multipleEmail($email);


  $max_sort = null;
		$query = "SELECT MAX(sort_order) AS max_sort FROM #__m4j_category";

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$max_sort = $rows[0]->max_sort;




	switch($task)
	{
	
		case 'new':
			if($name!=null && ($legal_email|| $email==null) )
				{
				$query = "INSERT INTO #__m4j_category"
						. "\n ( name, active, email, introtext ,sort_order )"
						. "\n VALUES"
						. "\n ( '".$name."', '".$active."', '".$email."', '".$intro."', '".($max_sort+1)."' )";
						$database->setQuery($query);
						if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							
					 mosRedirect(M4J_CATEGORY.M4J_REMEMBER_CID_QUERY);
				} 
			else 
				{
				  if(!$legal_email && $email!=null) $error = M4J_LANG_NONE_LEGAL_EMAIL;
				  if(!$name) $error .= M4J_LANG_CATEGORY_NAME_ERROR;
				}	
		break;
		//EOF NEW
		

		case 'edit':
			if($id!=null)
			{
				$query = "SELECT * FROM #__m4j_category WHERE cid = ".$id;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				  $name= $rows[0]->name;
  				  $email= $rows[0]->email;
  				  $active = intval($rows[0]->active);
				  $intro = 	$rows[0]->introtext;
			}
		break;
		//EOF EDIT
	
		case 'update':
		$editID = mosGetParam($_REQUEST, 'editID');
		if($name!=null && ($legal_email|| $email==null) )
				{
				$query = "UPDATE #__m4j_category"
					. "\n SET"
					. "\n active = ".$active.", "
					. "\n name = '".$name."', "
					. "\n email = '".$email."', "
					. "\n introtext = '".$intro."'"
					. "\n WHERE cid = ".$editID;
					$database->setQuery($query);
					if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
				 mosRedirect(M4J_CATEGORY.M4J_REMEMBER_CID_QUERY);
				 }
		else 
				{
				  if(!$legal_email && $email!=null) $error = M4J_LANG_NONE_LEGAL_EMAIL;
				  if(!$name) $error .= M4J_LANG_CATEGORY_NAME_ERROR;
				  define("M4J_EDITFLAG",1);
				  $id = $editID;
				}	
		break;
		//EOF UPDATE
		
		default:
		$active = 1;
		break;
	}	
	
	
	
	
	
  HTML_m4j::head(M4J_CATEGORY_NEW,$error);
  	if(M4J_EDITFLAG==1) $helpers->caption(M4J_LANG_EDIT_CATEGORY,null,M4J_LANG_CATEGORY.' > '.M4J_LANG_EDIT);
	else $helpers->caption(M4J_LANG_NEW_CATEGORY,null,M4J_LANG_CATEGORY.' > '.M4J_LANG_NEW_CATEGORY);	
	  
  HTML_m4j::new_category($name,$email,$id,$active,$intro); 
   
  HTML_m4j::footer();
	
	
?>