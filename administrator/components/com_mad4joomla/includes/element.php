<?PHP
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
   if($id==-1) mosRedirect(M4J_FORMS.M4J_REMEMBER_CID_QUERY);
   
   if(!$form = mosGetParam( $_REQUEST,'form'))  mosRedirect(M4J_FORM_ELEMENTS.M4J_REMEMBER_CID_QUERY.M4J_HIDE_BAR.'&id='.$id);
   else $form = intval($form);
   
   include_once(M4J_INCLUDE_FUNCTIONS);
   include_once(M4J_INCLUDE_FORMFACTORY);
      
   $errror = null;
   
   $question = mosGetParam( $_REQUEST,'question');
   $help= mosGetParam( $_REQUEST,'help');
   $active = 1;
   
   $template_name = mosGetParam( $_REQUEST,'template_name');
   $option_count = 0;

   $p_array=null;
   
   $width = '';
   $maxchars = 60;
   $checked = 1;
   $alignment = 1;
   $maxchars = 60;
   $element_rows = 3;
   $html ='';
   $endings='';
   $maxsize='';
   $measure = 1024;
   
   
   $parameters = '';
   $options = '';
  
  if(!$required= mosGetParam( $_REQUEST,'required')) $required=0;
  else $required = intval($required);
  
  if(!$eid = mosGetParam( $_REQUEST,'eid')) $eid=-1;
  else $eid = intval($eid);
  
  if($eid==-1 && ($task=='update' || $task=='edit')) mosRedirect(M4J_FORMS.M4J_REMEMBER_CID_QUERY);
  

  
   $max_sort = null;
		$query = "SELECT MAX(sort_order) AS max_sort FROM #__m4j_formelements WHERE fid=".$id;

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$max_sort = $rows[0]->max_sort;
  
  
  
  
  	switch($task)
	{
	
		case 'new':
			
			
   			  $active = mosGetParam( $_REQUEST,'active');
			
			  switch($form)
				  {
				  
				  case ($form<10):
				  $parameters .= make_param('checked'); 				  
				  break;
			
				  case ($form>=10 && $form<20):

				  break;	  
			
				  case ($form>=20 && $form<30):
				  			  
				  $parameters .= make_param('maxchars'); 
				  $parameters .= make_param('element_rows'); 
				  $parameters .= make_param('width');
				  
				  break;
				  
				  case ($form>=30 && $form<40):
					  for($t=0;$t<M4J_MAX_OPTIONS;$t++)
					  {
						$option = mosGetParam( $_REQUEST,'option-'.$t);
						if($option) $options .= $option.";";
					  }
					  $parameters .= make_param('element_rows'); 
					  $parameters .= make_param('width');
					  $parameters .= make_param('alignment');
				  break;  
				  
				  case ($form>=40 && $form<50):
				  					  
					  $parameters .= make_param('endings'); 
					  $parameters .= make_param('maxsize');
					  $parameters .= make_param('measure');
				  break; 
				  
				  }//EOF SWITCH FORM
	
			if($question !=null)
				{
				$query = "INSERT INTO #__m4j_formelements"
						. "\n ( fid, required, active, question, form, parameters,options, help, sort_order )"
						. "\n VALUES"
						. "\n ( '".$id."', '".$required."', '".$active.
						        "', '".$question."','".$form."','".$parameters.
								"','".$options."','".$help."','".($max_sort+1)."' )";
								
						$database->setQuery($query);
						if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 
							
				$insert_id = $database->insertid();			
				$query = "UPDATE #__m4j_formelements "
						. "\n SET"
						. "\n html = '".$ff->get_html($form,$insert_id,parameters($parameters),$options)."' "	
						. "\n WHERE eid = ".$insert_id;
								
						$database->setQuery($query);
						if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 	
						
					 mosRedirect(M4J_FORM_ELEMENTS.M4J_REMEMBER_CID_QUERY.M4J_HIDE_BAR.'&id='.$id.'&template_name='.$template_name);
				} 
			else 
				{
				$error .= M4J_LANG_ELEMENT_NO_QUESTION_ERROR;
				}	
		break;
		//EOF NEW
		
		case 'edit':
		if($eid>-1)
			{
				$query = "SELECT * FROM #__m4j_formelements WHERE eid = ".$eid;
				$database->setQuery( $query );
				$rows = $database->loadObjectList();
				
				$question = $rows[0]->question;
   				$help= $rows[0]->help;
				$active= $rows[0]->active;
				$required = $rows[0]->required;
				$p_array = parameters($rows[0]->parameters);
				$options = $rows[0]->options;
	
				
				switch($form)
					  {
					  
					  case ($form<10):
					  if($p_array) $checked = $p_array['checked'];
					  break;
				
					  case ($form>=10 && $form<20):
					   
					  break;	  
				
					  case ($form>=20 && $form<30):
					  if($p_array) 
					  {
					  $maxchars = $p_array['maxchars'];
					  $element_rows = $p_array['element_rows'];
					  $width = $p_array['width'];
					  }
					  break;
					  
					  case ($form>=30 && $form<40):
					   if($p_array) 
					  {
					  $element_rows = $p_array['element_rows'];
					  $width = $p_array['width'];
					  $alignment = $p_array['alignment'];
					  }
					  case ($form>=40 && $form<50):
					   if($p_array) 
					  {
					  $endings = $p_array['endings'];
 				      $maxsize = intval($p_array['maxsize']);
					  $measure = intval($p_array['measure']);
					  }
					  break;  
					  }//EOF switch form
			}//EOF eid>-1
		
		break;
		//EOF EDIT
		
		case 'update':
		if($eid>-1)
			{
			$active = mosGetParam( $_REQUEST,'active');
			
			  switch($form)
				  {
				  
				  case ($form<10):
				  $parameters .= make_param('checked'); 				  
				  break;
			
				  case ($form>=10 && $form<20):

				  break;	  
			
				  case ($form>=20 && $form<30):
				  			  
				  $parameters .= make_param('maxchars'); 
				  $parameters .= make_param('element_rows'); 
				  $parameters .= make_param('width');
				  
				  break;
				  
				  case ($form>=30 && $form<40):
					  for($t=0;$t<M4J_MAX_OPTIONS;$t++)
					  {
						$option = mosGetParam( $_REQUEST,'option-'.$t);
						if($option) $options .= $option.";";
					  }
					  $parameters .= make_param('element_rows'); 
					  $parameters .= make_param('width');
					  $parameters .= make_param('alignment');
				  break;  
				  
				  case ($form>=40 && $form<50):
				  					  
					  $parameters .= make_param('endings'); 
					  $parameters .= make_param('maxsize');
					  $parameters .= make_param('measure');
				  break; 
				  
				  }//EOF SWITCH FORM
			  if($question!=null)
			  	{
				$query = "UPDATE #__m4j_formelements "
						. "\n SET"
						. "\n required = '".$required."', "
						. "\n active = '".$active."', "
						. "\n question = '".$question."', "
						. "\n form = '".$form."', "
						. "\n parameters = '".$parameters."', "
						. "\n options = '".$options."', "
						. "\n help = '".$help."', "
						. "\n html = '".$ff->get_html($form,$eid,parameters($parameters),$options)."' "	
						. "\n WHERE eid = ".$eid;
								
						$database->setQuery($query);
						if (!$database->query()) $helpers->dbError($database->getErrorMsg()); 	
						
					 mosRedirect(M4J_FORM_ELEMENTS.M4J_REMEMBER_CID_QUERY.M4J_HIDE_BAR.'&id='.$id.'&template_name='.$template_name);
				}// EOF question is not null
			else
				{
				$error .= M4J_LANG_ELEMENT_NO_QUESTION_ERROR;
				}//EOF question equals null
			  
			  
			  
			  }//EOF eid>-1  
		break;
		//EOF UPDATE
		
	}//EOF Switch Task
  
  HTML_m4j::head(M4J_ELEMENT,$error);
  $m4j_breadcrump = M4J_LANG_TEMPLATES.' > '.M4J_LANG_ITEMS.' > ';
  
    switch($form)
	  {
	  
	  case ($form<10):
	  $m4j_breadcrump .= M4J_LANG_CHECKBOX  ; 
	  break;

	  case ($form>=10 && $form<20):
	  $m4j_breadcrump .= M4J_LANG_DATE ;  
	  break;	  

	  case ($form>=20 && $form<30):
	  $m4j_breadcrump .= M4J_LANG_TEXTFIELD ;  
	  break;
	  
	  case ($form>=30 && $form<40):
	  $m4j_breadcrump .= M4J_LANG_OPTIONS ; 
	  break;  
	  
	  case ($form>=40 && $form<50):
	  $m4j_breadcrump .= M4J_LANG_ATTACHMENT ; 
	  break; 
	  }

  if(M4J_EDITFLAG==1) $helpers->caption(M4J_LANG_EDIT_ELEMENT.$helpers->span($template_name,'m4j_green'),null, $m4j_breadcrump.' > '. M4J_LANG_EDIT_ITEM);
	else $helpers->caption(M4J_LANG_NEW_ELEMENT_LONG.$helpers->span($template_name,'m4j_green'),null,$m4j_breadcrump.' > '. M4J_LANG_NEW_ITEM);		
	
  HTML_m4j::element_form_head($question,$required,$active,$help);
  
  
  switch($form)
	  {
	  
	  case ($form<10):
	  HTML_m4j::element_yes_no($form,$checked); 
	  break;

	  case ($form>=10 && $form<20):
	  HTML_m4j::element_date(); 
	  break;	  

	  case ($form>=20 && $form<30):
	  HTML_m4j::element_text($form,$maxchars,$element_rows,$width); 
	  break;
	  
	  case ($form>=30 && $form<40):
	  HTML_m4j::element_options($form,$element_rows,$width,$alignment);
	  $option_count= M4J_MAX_OPTIONS; 
	  break;  
	  
	  case ($form>=40 && $form<50):
	  HTML_m4j::element_attachment($endings,$maxsize,$measure); 
	  break;
	  }
	  
	if($form>=40 && $form<50) HTML_m4j::element_form_footer($id, $eid,$template_name,HTML_m4j::element_attachment_right()); 
	else HTML_m4j::element_form_footer($id, $eid,$template_name,HTML_m4j::element_options_right($option_count,$options)); 

  HTML_m4j::footer();


?>
