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
	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
//++++++++++++++++++++++++++++++++ New Class HTML_HELPERS_m4j +++++++++++++++++++++++++++++++ //
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	class HTML_HELPERS_m4j{

	function dbError($error)
			{
			echo "<script type='text/javascript'>alert('".$error."');</script>";
			}//EOF dbERROR
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function image($image="",$link=null,$alt=null,$width=null,$height=null,$border=0,$add_remember=null)
			{	
				$out="";
				if($link) 
				{
				$out.='<a href="'.$link;
				if($add_remember==null) $out .= M4J_REMEMBER_CID_QUERY;
				$out .= '">';
				}
				$out.='<img src="'.M4J_IMAGES.$image.'" border="'.$border.'" ';
				if($alt) $out.='alt="'.$alt.'" title="'.$alt.'" ';
				if($width) $out.='width="'.$width.'" ';
				if($height) $out.='height="'.$height.'" ';
				$out.=' />';
				if($link) $out.='</a>';
				return $out;
			}//EOF IMAGE
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function spacer($width='16px',$height='16px')
			{
			return $this->image('spacer.png',null,null,$width,$height);
			}//EOF CAPTION
		
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function caption($caption,$right=null,$m4j_breadcrump=null)
			{
			if(!right)
			echo'<h1>'.$caption.'</h1>';
			else
			echo'<table width="100%" border="0" cellpadding="0" cellspacing="0"><tbody><tr>
				 <td><h1 class="m4j_toLeft">'.$caption.'</h1></td>
				 <td><div class="m4j_toRight">'.$right.'</div></td>
				 </tr></tbody></table>';
			if($m4j_breadcrump!=null)
				echo'<span class="m4j_breadcrump">'.$m4j_breadcrump.'</span>';
			}//EOF CAPTION
	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		

	function init_table($head=null)
			{
			$this-> table_even = true;
			$this-> table_widthArray = $widthArray;
			echo '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="list"><tbody>';
			
			if($head)
					{
					echo'<tr>';
					$size = sizeof($head);
						for($t=0;$t<$size;$t++)
						echo'<th>'.$head[$t].'</th>';
					echo'</tr>';
					}
			}//EOF INIT TABLE
	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	function table_row($rowArray,$even,$widthArray)
			{
				if($even) echo '<tr class="even" onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);" valign="top" >';
					else  echo '<tr class="odd" onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,false);" valign="top" >'; 
					
				$size = sizeof($rowArray);	
				for($t=0;$t<$size;$t++)
					echo'<td width="'.$widthArray[$t].'">'.$rowArray[$t].'</td>';
				echo'</tr>';
			}//EOF TABLE_ROW
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function int2lang($number)
			{
				if($number==1) return M4J_LANG_YES;
				else return M4J_LANG_NO;
			}//EOF CLOSE TABLE
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	function close_table()
			{
				echo'</tbody></table>';
			}//EOF CLOSE TABLE
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function link($link="",$core="",$class=null, $id=null)
			{
			$add = "";	
			if ($class!=null) $add .= 'class="'.$class.'"';
			if ($id!=null) $add .= ' id="'.$id.'"';	
			return '<a href="'.$link.M4J_REMEMBER_CID_QUERY.'" '.$add.'>'.$core.'</a>';
			}//EOF LINK
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function delete_button($link,$id,$extra= NULL)
			{  
			if($extra == NULL)     
			return $this->image("remove.png",'javascript: confirm_delete(\'' . M4J_LANG_DELETE_CONFIRM . 
							                '\',\'' . $link.M4J_DELETE.M4J_REMEMBER_CID_QUERY."&id=".$id.'\');', M4J_LANG_DELETE,null,null,0,1);		
			else								
			return $this->image("remove.png",'javascript: confirm_delete(\'' . M4J_LANG_DELETE_CONFIRM. $extra . 
							                '\',\'' . $link.M4J_DELETE.M4J_REMEMBER_CID_QUERY."&id=".$id.'\');', M4J_LANG_DELETE,null,null,0,1);										
			}//EOF DELETE BUTTON
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function edit_button($link,$id,$alt = M4J_LANG_EDIT)
			{
			return $this->image("pen-small.png",$link.M4J_HIDE_BAR.M4J_EDIT."&id=".$id,$alt);		
			}//EOF EDIT BUTTON	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	function copy_button($link,$id,$alt = M4J_LANG_DO_COPY, $hide=null,$eid=null)
			{
			$eid_query = '';
			if ($eid) $eid_query = '&eid='.$eid;
			return $this->image("copy.png",$link.$hide.$eid_query.M4J_COPY."&id=".$id,$alt);		
			}//EOF COPY BUTTON	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	function up_down_button($link,$id,$sort_order)
			{
			return $this->image("up.png",$link.M4J_UP."&id=".$id."&sort_order=".$sort_order,M4J_LANG_UP).
				   $this->spacer('5px').
				   $this->image("down.png",$link.M4J_DOWN."&id=".$id."&sort_order=".$sort_order,M4J_LANG_DOWN);		
			}//EOF UP & DOWN BUTTON	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	function span($text='', $class=null)
			{
			$out = '<span ';
			if($class) $out .= 'class ="'.$class.'">'.$text.'</span>';
			return $out;		
			}//EOF SPAN	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
	function element_edit_button($link,$id,$eid,$form,$alt = M4J_LANG_EDIT)
			{
			return $this->image("pen-small.png",$link.M4J_HIDE_BAR.M4J_EDIT."&id=".$id."&eid=".$eid."&form=".$form,$alt);		
			}//EOF ELEMENT DELETE BUTTON	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function element_delete_button($link,$id,$eid)
			{
			return $this->image("remove.png",'javascript: confirm_delete(\'' . M4J_LANG_DELETE_CONFIRM . 
							                '\',\'' . $link.M4J_HIDE_BAR.M4J_DELETE .M4J_REMEMBER_CID_QUERY. "&id=".$id."&eid=".$eid.'\');', M4J_LANG_DELETE,null,null,0,1);			
			}//EOF ELEMENT DELETE BUTTON
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //			
	 function element_up_down_button($link,$id,$eid,$sort_order)
			{
			return $this->image("up.png",$link.M4J_HIDE_BAR.M4J_UP."&id=".$id."&eid=".$eid."&sort_order=".$sort_order,M4J_LANG_UP).
				   $this->spacer('5px').
				   $this->image("down.png",$link.M4J_HIDE_BAR.M4J_DOWN."&id=".$id."&eid=".$eid."&sort_order=".$sort_order,M4J_LANG_DOWN);		
			}//EOF ELEMENT UP & DOWN BUTTON	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	

	 function category_menu($rows=null,$selected=-2,$jump=null,$hide_all=null,$link=false)
			{
				global $database;
				$select_all = '';
				$select_no = '';
				if($selected==null) $selected =-2;
				if($hide_all && $selected==-2) $selected=-1; 
				if($selected ==-2) $select_all ='selected="selected"';
				if($selected ==-1) $select_no ='selected="selected"';		
				
				if($jump) $jump = 'onchange="javascript: jump(this,\''.$jump.M4J_REMEMBER_CID_QUERY.'&cid=\');" ';
				
				
				if(!$hide_all)	$out .= M4J_LANG_CATEGORY.': ';
				$out .= '<select id="m4j_category" name="m4j_category" '.$jump;
				if($hide_all) $out .= 'style="width:100%"';
				$out .= '>';
				if(!$hide_all)
					$out .= '<option value="-2" '.$select_all.'>'.M4J_LANG_ALL_FORMS.'</option>';
				$out .= '<option value="-1" '.$select_no.'>'.M4J_LANG_NO_CATEGORYS.'</option>';
				$out .= '<optgroup label="'.M4J_LANG_CATEGORY.'">';
				$name = '';				
				foreach ($rows as $row)
				{
				$out .= '<option value="'.$row->cid.'" ';
				if($selected==$row->cid) 
					{
					$out .= 'selected="selected" ';	
					$name = $row->name;
					}
				$out .= '>'.$row->name.'</option>';
				}
				$out .= '</optgroup></select>';
				if($link)
					$out .= $this->image("link2cat.png",M4J_LINK. "&id=-999".M4J_HIDE_BAR."&name=".$name, M4J_LANG_LINK_CAT_TO_MENU);
				return $out;
			}//EOF CATEGORY MENU
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	

	function active_button($link,$id,$active,$hide=null,$eid=null)
			{
			$eid_query ='';
			$hide_query ='';
			if($eid)
			$eid_query ='&eid='.$eid;
			if($hide)
			$hide_query =M4J_HIDE_BAR;
			
			
			if($active==1)
				return $this->image("active.png",$link.M4J_UNPUBLISH. "&id=".$id.$eid_query.$hide_query, M4J_LANG_HOVER_ACTIVE_ON);
			else 
				return $this->image("not_active.png",$link.M4J_PUBLISH. "&id=".$id.$eid_query.$hide_query, M4J_LANG_HOVER_ACTIVE_OFF);	
				
								
			}//EOF ACTIVE  BUTTON
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	function required_button($link,$id,$required,$hide=null,$eid=null)
			{
			$eid_query ='';
			$hide_query ='';
			if($eid)
			$eid_query ='&eid='.$eid;
			if($hide)
			$hide_query =M4J_HIDE_BAR;
			
			
			if($required==1)
				return $this->image("required.png",$link.M4J_NOT_REQUIRED. "&id=".$id.$eid_query.$hide_query, M4J_LANG_HOVER_REQUIRED_ON);
			else 
				return $this->image("not_required.png",$link.M4J_REQUIRED. "&id=".$id.$eid_query.$hide_query, M4J_LANG_HOVER_REQUIRED_OFF);	
				
								
			}//EOF ACTIVE  BUTTON
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	
function init_thickbox()
	{
	echo '
		 <script type="text/javascript" src="'.M4J_THICKBOX.'jquery.js"></script>
		 <script type="text/javascript" src="'.M4J_THICKBOX.'thickbox.js"></script>
		 <link rel="stylesheet" href="'.M4J_THICKBOX.'thickbox.css" type="text/css" media="screen" />
		 ';
	
	}//EOF init thickbox
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	

function replace_yes_no($html)
	{
	$html =  str_replace('{M4J_YES}',M4J_LANG_YES,$html);	
	return str_replace('{M4J_NO}',M4J_LANG_NO,$html);
	}//EOF replace_yes_no
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	

function link_button($link,$id,$form_name=null)
		{       
		return $this->image("link.png",$link.M4J_HIDE_BAR."&id=".$id."&name=".$form_name,M4J_LANG_LINK_TO_MENU);
			
		}//EOF DELETE BUTTON
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	

function get_menutype($params)
	{
	$param_array = explode("\n",$params);
	
	for($t=0; $t< sizeof($param_array); $t++)
		{
		$part = explode("=", $param_array[$t]);
		if($part[0]=='menutype') 
			{
			if($part[1] != null) return $part[1];
			else return null;
			}		
		}
	return null;
	} //EOF GET MENUTYPE
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
	function advice($text,$view=0)
		{
		switch($view)
			{
			case 0:
			echo $text;
			break;
			
			case 1:
			echo '<h1>'.$text.'</h1><br/>';
			break;
			
			case 2:
			echo '<h2>'.$text.'</h2><br/>';
			break;
			
			case 3:
			echo '<h3>'.$text.'</h3><br/>';
			break;
			
			case 4:
			echo '<p>'.$text.'</p>';
			break;	
			
			case 5:
			echo '<b>'.$text.'</b>';
			break;
					
			}
		
		}//EOF ADVICE
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	

	function config_feedback($action)
		{
		switch($action)
			{
			case 1:
			echo'<span class="m4j_success">'.M4J_LANG_CONFIG_RESET.'</span>';
			break;
			
			case 2:
			echo'<span class="m4j_success">'.M4J_LANG_CONFIG_SAVED.'</span>';
			break;
			}
		}



			
	}//EOF CLASS HTML_HELPERS_m4j
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
// +++++++++++++++++++++++++++++++ New Class HTML_m4j ++++++++++++++++++++++++++++++++++++++++//
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	class HTML_m4j{

	function error($error=null)
	{
		if($error)	echo'<p class="errorMessage">'.$error.'</p>';
	}


			
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function head($location,$error=null){
		
		global $mosConfig_live_site, $id; 
		
		echo '<link type="text/css" rel="stylesheet" href="'. M4J_CSS .'"/>
			  <script language="javascript"  type="text/javascript" src="'.M4J_JS.'mad4joomla.js"></script>';
		
		if(defined('_JEXEC')){
		echo '<script language="javascript" type="text/javascript">document.getElementById("toolbar-box").style.display ="none";</script>';
		}
		
		
		
		//BOF echo
		echo '
		<center>
		<div class="m4j_main">

		
		  <table width="980" border="0" cellspacing="0" cellpadding="0">
		    <tr>
		      <td width="18" valign="top"><img src="'.M4J_IMAGES.'round_left.png" width="18" height="64" /></td>
		      <td width="944" valign="top" class="m4j_toolbar_back">';
		//EOF echo
		
		//IF toolbar is enabled
		if(M4J_NOBAR!=1) 
		//BOF echo		  
			  echo'
				  <table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toLeft">
			  <tr>
				<td height="64" align="center" valign="top"><a href="'.M4J_JOBS.M4J_REMEMBER_CID_QUERY.'" class="m4j"><img src="'.M4J_IMAGES.'jobs.png" alt="'.M4J_LANG_FORMS.'" width="48" height="48" border="0" /><br/>'.M4J_LANG_FORMS.'</a></td>
				<td width="10"></td>
				<td height="64" align="center" valign="top"><a href="'.M4J_FORMS.M4J_REMEMBER_CID_QUERY.'" class="m4j"> <img src="'.M4J_IMAGES.'forms.png" alt="'.M4J_LANG_TEMPLATES.'" width="48" height="48" border="0" /><br/>'.M4J_LANG_TEMPLATES.'</a></td>
				<td width="10"></td>
				<td height="64" align="center" valign="top"><a href="'.M4J_CATEGORY.M4J_REMEMBER_CID_QUERY.'" class="m4j"> <img src="'.M4J_IMAGES.'category.png" alt="'.M4J_LANG_CATEGORY.'" width="48" height="48" border="0" /><br/>'.M4J_LANG_CATEGORY.'</a></td>
				<td width="10"></td>
				<td height="64" align="center" valign="top"><a href="'.M4J_CONFIG.M4J_REMEMBER_CID_QUERY.'" class="m4j"> <img src="'.M4J_IMAGES.'config.png" alt="'.M4J_LANG_CONFIG.'" width="48" height="48" border="0" /><br/>'.M4J_LANG_CONFIG.'</a></td>
				<td width="10"></td>
				<td height="64" align="center" valign="top"><a href="'.M4J_HELP.M4J_REMEMBER_CID_QUERY.'" class="m4j"> <img src="'.M4J_IMAGES.'help.png" alt="'.M4J_LANG_HELP.'" width="48" height="48" border="0" /><br/>'.M4J_LANG_HELP.'</a></td>
			  </tr>
				</table>';
		//EOF echo
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //			
		// ELSE toolbar is disabled
		else 
		{
		//BOF echo
		echo'
		  <table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toLeft">
			  <tr>
				<td height="64" align="center" valign="top">';
		//EOF echo
				
//++++++++++++++++++++++++++++++++ BOF Routing the CancelButton ++++++++++++++++++++++++++++++++++ //			
		switch($location)
		{	
				default:	
				echo'<a href="javascript:history.go(-1)" class="m4j">';
				break;
	
				case M4J_JOBS_NEW:
				echo '<a href="'.M4J_JOBS.M4J_REMEMBER_CID_QUERY.'" class="m4j">';
				break;
	
				
				case M4J_FORM_NEW:
				echo '<a href="'.M4J_FORMS.M4J_REMEMBER_CID_QUERY.'" class="m4j">';
				break;
				
				
				case M4J_FORM_ELEMENTS:
				echo '<a href="'.M4J_FORMS.M4J_REMEMBER_CID_QUERY.'" class="m4j">';
				echo '	   <img src="'.M4J_IMAGES.'back.png" alt="'.M4J_LANG_OVERVIEW.
				     '" width="48" height="48" border="0" /><br/>'.M4J_LANG_OVERVIEW.'</a>
						  </td>
			 			</tr>
		  			  </table>	
					 ';
				break;
				
				case M4J_CATEGORY_NEW:
				echo '<a href="'.M4J_CATEGORY.M4J_REMEMBER_CID_QUERY.'" class="m4j">';
				break;
				
				case M4J_ELEMENT:
				global $id;
				echo '<a href="'.M4J_FORM_ELEMENTS.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'&id='.$id.'" class="m4j">';
				break;
				
				case M4J_LINK:
				echo '<a href="'.M4J_JOBS.M4J_REMEMBER_CID_QUERY.'" class="m4j">';
				break;
				
		};
//++++++++++++++++++++++++++++++++ EOF Routing the CancelButton ++++++++++++++++++++++++++++++++++ //	
				
				
				
				
		//BOF echo	
		if($location!= M4J_FORM_ELEMENTS)				
		echo '	   <img src="'.M4J_IMAGES.'cancel.png" alt="'.M4J_LANG_CANCEL.'" width="48" height="48" border="0" /><br/>'.M4J_LANG_CANCEL.'</a>
				</td>
			 </tr>
		  </table>	
		';
		//EOF echo
		}//EOF ELSE
	
	//BOF echo	
	echo'
		<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
		  <tr>
		    <td width="250px" align="right" valign="top">';
	//EOF echo			
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //					
	//++++++++++++++++++++++++++++++ Routing headers right button ++++++++++++++++++++++++++++++++++ //			
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	
	switch($location){
					
//++++++++++++++++++++++++++++++ FORMS / DEFAULT ++++++++++++++++++++++++++++++++++ //						
					
					default:
					case M4J_JOBS:
		//BOF echo
		echo'
		<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
		  <tr>
			<td height="64" align="center" valign="top">
						<a href="'.M4J_JOBS_NEW.M4J_HIDE_BAR.M4J_NEW_JOB_CID_QUERY.M4J_REMEMBER_CID_QUERY.'" class="m4j">
							<img src="'.M4J_IMAGES.'new_job.png" alt="'.M4J_LANG_NEW_FORM.'" width="48" height="48" border="0" /><br />'.M4J_LANG_NEW_FORM.'</a>
				</td>
			  </tr>
			</table>
			';
		//EOF echo
			
			break;
			
//++++++++++++++++++++++++++++++ NEW/EDIT FORMS ++++++++++++++++++++++++++++++++++ //				
			case M4J_JOBS_NEW:
			if(M4J_EDITFLAG !=1)
			{
				//BOF echo
				echo'
				<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
				  <tr>
					<td height="64" align="center" valign="top">
								<a href="javascript:m4j_submit(\'new\')" class="m4j">
						<img src="'.M4J_IMAGES.'add.png" alt="'.M4J_LANG_ADD.'" width="48" height="48" border="0" />
						<br />'.M4J_LANG_ADD.'</a>
						</td>
					  </tr>
					</table>
					';
				//EOF echo
			}
			else
			{
			//BOF echo
			echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
				<a href="javascript:m4j_submit(\'update\')" class="m4j">
					<img src="'.M4J_IMAGES.'proceed.png" alt="'.M4J_LANG_SAVE.'" width="48" height="48" border="0" /><br />'.M4J_LANG_SAVE.'</a>
				</td>
			  </tr>
			</table>
			';
				//EOF echo
			}
			break;
			
			
//++++++++++++++++++++++++++++++ TEMPLATES ++++++++++++++++++++++++++++++++++ //			
			
			case M4J_FORMS:
			//BOF echo
			echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
				<a href="'.M4J_FORM_NEW.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'" class="m4j">
					<img src="'.M4J_IMAGES.'new.png" alt="'.M4J_LANG_NEW_TEMPLATE.'" width="48" height="48" border="0" />
					<br />'.M4J_LANG_NEW_TEMPLATE.'</a>
				</td>
			  </tr>
			</table>
			';
			//EOF echo
			
			
			
			break;

//++++++++++++++++++++++++++++++ NEW/EDIT TEMPLATE ++++++++++++++++++++++++++++++++++ //	
			case M4J_FORM_NEW:
		
			if(M4J_EDITFLAG !=1)
			{
			//BOF echo
						echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
				<a href="javascript:m4j_submit(\'new\')" class="m4j">
					<img src="'.M4J_IMAGES.'next.png" alt="'.M4J_LANG_PROCEED.'" class="button" />
					'.M4J_LANG_PROCEED.'</a>
				</td>
			  </tr>
			</table>
			';
			//EOF echo
			}
			else
			{
			//BOF echo
		echo'
		<div class="m4j_toRight">
		<table  height="64" border="0" cellpadding="0" cellspacing="0" >
		  <tr>
			<td height="64" align="center" valign="top">
						<a href="javascript:m4j_submit(\'update\')" class="m4j">
							<img src="'.M4J_IMAGES.'proceed.png" alt="'.M4J_LANG_SAVE.'" class="button" />
							'.M4J_LANG_SAVE.'</a>
				</td>
				<td height="64" align="center" valign="top">
					<a href="javascript:m4j_submit(\'updateproceed\')" class="m4j">
					<img src="'.M4J_IMAGES.'next.png" alt="'.M4J_LANG_UPDATE_PROCEED.'" class="button" align="center" />
					'.M4J_LANG_UPDATE_PROCEED.'</a>
				</td>
				
				
				
			  </tr>
			</table></div>
			';
		//EOF echo
			
			}
		
			break;
			
			
//++++++++++++++++++++++++++++++ TEMPLATE ELEMENTS ++++++++++++++++++++++++++++++++++ //
				
			case M4J_FORM_ELEMENTS:
			//BOF echo
			echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
							<a href="#TB_inline?height=550&width='.M4J_PREV_TABLE.'&inlineId=hiddenModalContent&modal=true" class="thickbox" >
								<img src="'.M4J_IMAGES.'preview.png" alt="'.M4J_LANG_PREVIEW.'" class="button" />
								'.M4J_LANG_PREVIEW.'</a>
					</td>
				  </tr>
				</table>
				';
			//EOF echo
			break;

//++++++++++++++++++++++++++++++ NEW/EDIT ELEMENT ++++++++++++++++++++++++++++++++++ //				
			case M4J_ELEMENT:
			if(M4J_EDITFLAG !=1)
			{
			//BOF echo
						echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
				<a href="javascript:m4j_submit(\'new\')" class="m4j">
					<img src="'.M4J_IMAGES.'add.png" alt="'.M4J_LANG_ADD.'" width="48" height="48" border="0" />
					<br />'.M4J_LANG_ADD.'</a>
				</td>
			  </tr>
			</table>
			';
			//EOF echo
			}
			else
			{
			//BOF echo
			echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top"><a href="javascript:m4j_submit(\'update\')" class="m4j"><img src="'.M4J_IMAGES.'proceed.png" alt="'.M4J_LANG_SAVE.'" width="48" height="48" border="0" /><br />'.M4J_LANG_SAVE.'</a>
				</td>
			  </tr>
			</table>
			';
				//EOF echo
			}
			break;
			
//++++++++++++++++++++++++++++++ CATEGORY ++++++++++++++++++++++++++++++++++ //				
			case M4J_CATEGORY:
						
			//BOF echo
			echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
				<a href="'.M4J_CATEGORY_NEW.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'" class="m4j">
					<img src="'.M4J_IMAGES.'new_category.png" alt="'.M4J_LANG_NEW_CATEGORY.'" width="48" height="48" border="0" />
					<br />'.M4J_LANG_NEW_CATEGORY.'</a>
				</td>
			  </tr>
			</table>
			';
			//EOF echo
			break;
			
//++++++++++++++++++++++++++++++ EDIT/NEW CATEGORY ++++++++++++++++++++++++++++++++++ //				

			case M4J_CATEGORY_NEW:
			if(M4J_EDITFLAG !=1)
			{
				//BOF echo
				echo'
				<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
				  <tr>
					<td height="64" align="center" valign="top">
								<a href="javascript:m4j_submit(\'new\')" class="m4j">
						<img src="'.M4J_IMAGES.'add.png" alt="'.M4J_LANG_ADD.'" width="48" height="48" border="0" />
						<br />'.M4J_LANG_ADD.'</a>
						</td>
					  </tr>
					</table>
					';
				//EOF echo
			}
			else
			{
			//BOF echo
			echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
				<a href="javascript:m4j_submit(\'update\')" class="m4j">
					<img src="'.M4J_IMAGES.'proceed.png" alt="'.M4J_LANG_SAVE.'" width="48" height="48" border="0" /><br />'.M4J_LANG_SAVE.'</a>
				</td>
			  </tr>
			</table>
			';
				//EOF echo
			}
			break;
			
//++++++++++++++++++++++++++++++ CONFIGURATION ++++++++++++++++++++++++++++++++++ //				
			case M4J_CONFIG:
					//BOF echo
			echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
				<a href="javascript:m4j_submit(\'update\')" class="m4j">
					<img src="'.M4J_IMAGES.'proceed.png" alt="'.M4J_LANG_SAVE.'" width="48" height="48" border="0" /><br />'.M4J_LANG_SAVE.'</a>
				</td>
			  </tr>
			</table>
			';
				//EOF echo
			
			
			break;
//++++++++++++++++++++++++++++++ HELP ++++++++++++++++++++++++++++++++++ //				
			case M4J_HELP:
			//BOF echo
			echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
				<a href="http://www.mad4media.de" target="_blank" class="m4j">
					<img src="'.M4J_IMAGES.'mad4media.png" alt="Mad4Media Home" width="64" height="64" border="0" /></a>
				</td>
			  </tr>
			</table>
			';
			//EOF echo
			break;
			
//++++++++++++++++++++++++++++++ LINK ++++++++++++++++++++++++++++++++++ //				
		  case M4J_LINK:
		  //BOF echo
			if(defined('M4J_LINK_FORM_READY'))
			echo'
			<table  height="64" border="0" cellpadding="0" cellspacing="0" class="m4j_toRight">
			  <tr>
				<td height="64" align="center" valign="top">
				<a href="javascript:m4j_submit(\'new\')" class="m4j">
					<img src="'.M4J_IMAGES.'add.png" alt="'.M4J_LANG_ADD.'" width="48" height="48" border="0" />
					<br />'.M4J_LANG_ADD.'</a>
				</td>
			  </tr>
			</table>
			';
			//EOF echo
		  break;			
		}//EOF Switch
		
	//BOF echo
		echo '</td>
			  </tr>
			</table>
			
			
			
			</td>
			
			      <td width="18" valign="top"><img src="'.M4J_IMAGES.'round_right.png" width="18" height="64" /></td>
			    </tr>
				<tr>
					<td  height="4px" colspan="3" valign="top" align="left"><img src="'.M4J_IMAGES.'red_decor.png" width="980" height="4" border="0" /></td>
				</tr>
				
			    <tr>
			      <td width="18" height="400" class="m4j_left_shadow">&nbsp;</td>
			      <td width="944" valign="top">
				  <div class="m4j_content">
				  
	';//*EOF echo
	
	HTML_m4j::error($error);	
	}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	function footer(){
		global $helpers;
		echo '	  </div>
	  </td>
      <td width="18" valign="top" class="m4j_right_shadow">&nbsp;</td>
    </tr>
  </table>
</div>

  <p>'.$helpers->image("copyleft.png").' <a href="http://www.mad4media.de">mad4media </a></p></center>
';
	}	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	
	function new_form($name="",$desc="",$id=-1,$qwidth=300,$awidth=400,$use_help=1){
	
	$yes_query ="";
	$no_query = "";
	if ($use_help==1) $yes_query = 'selected="selected"'; else $no_query = 'selected="selected"';
	
	
		echo'
		<form id="m4jForm" name="m4jForm" method="post" action="'.M4J_FORM_NEW.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'">
        
		
		  '.M4J_LANG_TEMPLATE_NAME.'<br />
		    <input name="name" type="text" id="name" size="80" maxlength="80"  value="'.$name.'" />
            <br /><br/>
		  	'.M4J_LANG_TEMPLATE_DESCRIPTION.'<br />
		  <textarea name="description" cols="80" rows="5" id="description">'.$desc.'</textarea>
		  <p>&nbsp;</p>
		  '.M4J_LANG_Q_WIDTH.'<br />
		    <input name="qwidth" type="text" id="qwidth" size="80" maxlength="80"  value="'.$qwidth.'" />
            <br /><br/>
		 	'.M4J_LANG_A_WIDTH.'<br />
		    <input name="awidth" type="text" id="awidth" size="80" maxlength="80"  value="'.$awidth.'" />
            <br /><br/>
			'.M4J_LANG_USE_HELP.'<br />
			<select name="use_help" id="use_help">
			<option value="1" '.$yes_query.' >'.M4J_LANG_YES.
			'</option><option value="0" '.$no_query.' >'.M4J_LANG_NO.
			'</option></select>
		
		  <p>
		    <input name="task" type="hidden" id="task" />
			<input name="editID" type="hidden" id="editID" value='.$id.' />
	        </p>
	    </form>
		';
	}//EOF new_form
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
		function new_category($name="",$email="",$id=-1,$active=1,$intro_value=null){
	
		echo'
			<form id="m4jForm" name="m4jForm" method="post" action="'.M4J_CATEGORY_NEW.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'">
        
		
		  '.M4J_LANG_CATEGORY_NAME.'<br />
		    <input name="name" type="text" id="name" size="80" maxlength="80" value="'.$name.'" />
            <br />
          '.M4J_LANG_EMAIL_ADRESS.'<br />
          <input name="email" type="text" id="email" size="80" maxlength="80"  value="'.$email.'"/>
          <br />
		  '.M4J_LANG_ACTIVE.'<br />
		  <select name="active" id="active">';
		  
		  if($active==1) echo' <option value="1" selected="selected">'.M4J_LANG_YES.'</option>
		  					  <option value="0">'.M4J_LANG_NO.'</option>';
		  else  echo' <option value="1" >'.M4J_LANG_YES.'</option>
		  			  <option value="0" selected="selected" >'.M4J_LANG_NO.'</option>';
	
			
		    echo '</select>
		 
		  <p>
		    <input name="task" type="hidden" id="task" />
			<input name="editID" type="hidden" id="editID" value='.$id.' />
          '.M4J_LANG_CATEGORY_INTRO_LONG.'</p>
		  <div align="left">';
		editorArea('intro',$intro_value,'intro','934','240','75','30');    
	    echo'</div><br /></form>';
	}//EOF new_category	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //			
		
	function form_elements_table_head(){
	   echo '<table width="100%">
	   		 <tbody>
			 <tr>
			 <td valign="top">';
	}//EOF formelements table head	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //			

	function form_elements_menu($fid,$template_name){
	
	echo '</td>
		  <td width="190px" valign="top"><span class="m4j_add_item">'.M4J_LANG_ADD_NEW_ITEM.'</span>';
   	echo'<a href="'.M4J_ELEMENT.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'&id='.$fid.'&form=1&template_name='.$template_name.'" class="m4j_element_button">
		 <span class="m4j_add_element_text"><b>'.M4J_LANG_CHECKBOX.'</b><br />'.M4J_LANG_CHECKBOX_DESC.'</span></a><br/ >';
	echo'<a href="'.M4J_ELEMENT.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'&id='.$fid.'&form=10&template_name='.$template_name.'" class="m4j_element_button">
		 <span class="m4j_add_element_text"><b>'.M4J_LANG_DATE.'</b><br />'.M4J_LANG_DATE_DESC.'</span></a><br/ >';
    echo'<a href="'.M4J_ELEMENT.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'&id='.$fid.'&form=20&template_name='.$template_name.'" class="m4j_element_button">
		 <span class="m4j_add_element_text"><b>'.M4J_LANG_TEXTFIELD.'</b><br />'.M4J_LANG_TEXTFIELD_DESC.'</span></a><br/ >';
	echo'<a href="'.M4J_ELEMENT.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'&id='.$fid.'&form=30&template_name='.$template_name.'" class="m4j_element_button">
		 <span class="m4j_add_element_text"><b>'.M4J_LANG_OPTIONS.'</b><br />'.M4J_LANG_OPTIONS_DESC.'</span></a><br/ >';
	echo'<a href="'.M4J_ELEMENT.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'&id='.$fid.'&form=40&template_name='.$template_name.'" class="m4j_element_button">
		 <span class="m4j_add_element_text"><b>'.M4J_LANG_ATTACHMENT.'</b><br />'.M4J_LANG_ATTACHMENT_DESC.'</span></a><br/ >';
     echo '</td>
	       </tr>
		   <tbody>
		   </table>';
	}//EOF formelements menu
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	
		function element_form_head($question='',$required=null,$active=1,$help=null){
	
		$width='500px';
		$is_required ='';
		$not_required ='';
		if($required==1) $is_required=' selected="selected" ';
		else $not_required =' selected="selected" ';
		$is_active ='';
		$not_active ='';
		if($active==1) $is_active=' selected="selected" ';
		else $not_active =' selected="selected" ';
		
		echo'
		<form id="m4jForm" name="m4jForm" method="post" action="'.M4J_ELEMENT.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'">
		  <table width="100%" border="0" cellpadding="0" cellspacing="0"> </tbody>
		   <tr> <td width="'.$width.'" valign="top">
		
			  <table width="'.$width.'" border="0" cellpadding="0" cellspacing="0"><tbody><tr>
			  <td colspan="2">
			  '.M4J_LANG_YOUR_QUESTION.'<br />
			  <input class="m4j_textfield" name="question" type="text" id="question" size="80" maxlength="80" value="'.$question.'" /><br />
			  </td></tr>
			  <tr>
				<td width="50%">
					'.M4J_LANG_ACTIVE.'<br />
					<select name="active" id="active">
						<option value="1"'.$is_active.'>'.M4J_LANG_YES.'</option>
						<option value="0"'.$not_active.'>'.M4J_LANG_NO.'</option>
					</select>		  
				</td>
				<td width="50%">
					'.M4J_LANG_REQUIRED_LONG.'<br />
					<select name="required" id="required">
						<option value="1"'.$is_required.'>'.M4J_LANG_YES.'</option>
						<option value="0"'.$not_required.'>'.M4J_LANG_NO.'</option>
					</select>		
				</td>
			  </tr>
			  <tr>
			  	<td colspan="2"><br/>
				   '.M4J_LANG_HELP_TEXT.'
				   	<textarea class="m4j_textarea" name="help" cols="80" rows="5" id="help">'.$help.'</textarea>
				</td>
			   </tr>
		</tbody></table>
		';
		} //EOF element_form_head	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //			  
		  
	 function element_form_footer($id=null, $eid=null, $template_name=null,$right_column=null){	
	 	
		echo '</td><td valign="top" align="center">';
		echo $right_column;
		echo '</td></tr></tbody></table>';
		echo '		     
		    <input name="task" type="hidden" id="task" />
			<input name="template_name" type="hidden" id="template_name" value="'.$template_name.'"/>
		    <input name="id" type="hidden" id="id" value="'.$id.'" />
			<input name="eid" type="hidden" id="eid" value="'.$eid.'" />
	    </form>
		';
	}//EOF element_form_footer
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //				
	
	function element_yes_no($form,$checked=1){?>
	
	 <table width="500" border="0" cellpadding="0" cellspacing="0">
	 <tr><td colspan="3"><br/><?PHP echo M4J_LANG_TYPE_OF_CHECKBOX; ?><br/></td></tr>
	     <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
	       <td valign="top"><label>
	         <input type="radio" name="form" value="1" <?php if($form==1) echo'checked ';?> />
	         <b><?PHP echo M4J_LANG_ITEM_CHECKBOX; ?></b></label></td><td align="right"><?PHP echo M4J_LANG_EXAMPLE; ?>:</td><td width="100px">
	           <div align="right">
	             <input name="demo" type="checkbox" id="demo" value="demo" checked="checked" />
                   </div></td>
         </tr>
	     <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
	       <td valign="top"><label>
	         <input type="radio" name="form" value="2" <?php if($form==2) echo'checked ';?> />
	         <b><?PHP echo M4J_LANG_YES_NO_MENU; ?></b></label></td><td align="right"><?PHP echo M4J_LANG_EXAMPLE; ?>:</td><td>
	           <div align="right">
	             <select name="demo" id="demo">
	               <option selected="selected"><?PHP echo M4J_LANG_YES; ?></option>
	               <option><?PHP echo M4J_LANG_NO; ?></option>
                 </select>
                    </div></td>
         </tr>
		 
		 	 <tr><td colspan="3"><br/><?PHP echo M4J_LANG_INIT_VALUE; ?><br/></td></tr>
	     <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
	       <td valign="top" colspan="3"><label>
	         <input type="radio" name="checked" value="1" <?php if($checked==1) echo'checked ';?> />
	         <b><?PHP echo M4J_LANG_YES_ON; ?></b></label></td>
         </tr>
	     <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
	       <td valign="top" colspan="3"><label>
	         <input type="radio" name="checked" value="0" <?php if($checked==0) echo'checked ';?> />
	         <b><?PHP echo M4J_LANG_NO_OFF; ?></b></label></td>
         </tr>
		 
		 
       </table>
	<?PHP
	}//EOF element_yes_no
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	
	function element_date(){
	echo'
		<input name="form" type="hidden" id="form" value="10" />
		';
	
	}//EOF element_date
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	
	function element_text($form,$maxchars=60,$element_rows=3,$width='100%'){?>
	
	 <table width="500" border="0" cellpadding="0" cellspacing="1">
	 <tr><td colspan="3"><br/><?PHP echo M4J_LANG_TYPE_OF_TEXTFIELD; ?><br/><br/></td></tr>
	     <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
	       <td valign="top"  align="left"><label>
	         <input type="radio" name="form" value="20" <?php if($form==20) echo'checked ';?> />
	         <b><?PHP echo M4J_LANG_ITEM_TEXTFIELD; ?></b></label></td><td align="right" valign="top"><?PHP echo M4J_LANG_EXAMPLE; ?>:</td><td width="100px" valign="top">
	           <div align="right">
	             <input class="m4j_demo_field" name="demo" type="text" size="18" />
                   </div></td>
         </tr>
	     <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
	       <td valign="top"  align="left"><label>
	         <input type="radio" name="form" value="21" <?php if($form==21) echo'checked ';?> />
	         <b><?PHP echo M4J_LANG_ITEM_TEXTAREA; ?></b></label></td><td align="right" valign="top"><?PHP echo M4J_LANG_EXAMPLE; ?>:</td><td valign="top" align="right">
	           <div align="right">
	             <textarea class="m4j_demo_field" name="demo" cols="15" rows="3"></textarea>
                    </div></td>
         </tr>
		 <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
		 <td  valign="top" align="left"><b>
		 <?PHP echo M4J_LANG_MAXCHARS_LONG; ?></b>
		 <td>
		 <td valign="top"> <div align="right">
		 <input class="m4j_demo_field" name="maxchars" type="text" id="maxchars" value="<?PHP echo $maxchars;?>" />
		 </div></td>
		 </tr>
		 <tr height="8px"><td colspan="3"></td></tr>
		 <tr><td colspan="3"><br/><?PHP echo M4J_LANG_OPTICAL_ALIGNMENT; ?><br/><br/></td></tr> 
		 
		 <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
		 <td  valign="top" align="left">
		 	<?PHP echo M4J_LANG_ITEM_WIDTH_LONG; ?>
		 <td>
		 <td valign="top"> <div align="right">
		 <input class="m4j_demo_field" name="width" type="text" id="width" value="<?PHP echo $width;?>" />
		 </div></td>
		 </tr>
		 <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
		 <td  valign="top" align="left">
		 	<?PHP echo M4J_LANG_ROWS_TEXTAREA; ?>
		 <td>
		 <td valign="top"> <div align="right">
		 <input class="m4j_demo_field" name="element_rows" type="text" id="element_rows" value="<?PHP echo $element_rows;?>" />
		 </div></td>
		 </tr>
		 <tr height="12px"><td colspan="3"></td></tr>
       </table>
	<?PHP
	}//EOF element_text
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		

	function element_options($form,$element_rows=3,$width='100%',$alignment=1){?>
	
	 <table width="500" border="0" cellpadding="0" cellspacing="1">
	 <tr><td colspan="3"><br/><?PHP echo M4J_LANG_TYPE_OF_OPTIONS; ?><br/></td></tr>
	 <tr><td colspan="3"><br/><b><?PHP echo M4J_LANG_SINGLE_CHOICE_LONG; ?></b></td></tr>
	 <tr height="8px"><td colspan="3"></td></tr>
	     <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
	       <td valign="top"  align="left"><label>
	         <input type="radio" name="form" value="30" <?php if($form==30) echo'checked ';?> />
	         <?PHP echo M4J_LANG_DROP_DOWN; ?></label></td><td align="right" valign="top"><?PHP echo M4J_LANG_EXAMPLE; ?>:</td><td width="100px" valign="top">
	           <div align="right">
	             	     <select name="demo"  class="m4j_demo_field">
						   <option value="two">one</option>
						   <option value="three">two </option>
						   <option value="four">three</option>
						 </select>
                   </div></td>
         </tr>
	     <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
	       <td valign="top"  align="left"><label>
	         <input type="radio" name="form" value="31" <?php if($form==31) echo'checked ';?> />
	          <?PHP echo M4J_LANG_RADIOBUTTONS; ?></label></td><td align="right" valign="top"><?PHP echo M4J_LANG_EXAMPLE; ?>:</td><td valign="top" align="right">
	           <div align="right">
	                <table  class="m4j_demo_field">
					 <tr>
					   <td><label>
						 <input type="radio" name="demo" value="two" />
						 one</label></td>
					 </tr>
					 <tr>
					   <td><label>
						 <input type="radio" name="demo" value="three" />
						 two</label></td>
					 </tr>
					 <tr>
					   <td><label>
						 <input type="radio" name="demo" value="four" />
						 three</label></td>
					 </tr>
				   </table>
                    </div></td>
         </tr>
		 <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
			   <td valign="top"  align="left"><label>
				 <input type="radio" name="form" value="32" <?php if($form==32) echo'checked ';?> />
				 <?PHP echo M4J_LANG_LIST_SINGLE; ?></label></td><td align="right" valign="top"><?PHP echo M4J_LANG_EXAMPLE; ?>:</td><td width="100px" valign="top">
				   <div align="right">
						<select name="demo" size="3" class="m4j_demo_field">
						   <option value="two">one</option>
						   <option value="three">two </option>
						   <option value="four">three</option>
						 </select>
					   </div></td>
			 </tr>
			 
		<tr><td colspan="3"><br/><b><?PHP echo M4J_LANG_MULTIPLE_CHOICE_LONG; ?></b></td></tr>
		  <tr height="8px"><td colspan="3"></td></tr>
	     <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
	       <td valign="top"  align="left"><label>
	         <input type="radio" name="form" value="33" <?php if($form==33) echo'checked ';?> />
	         <?PHP echo M4J_LANG_CHECKBOX_GROUP; ?></label></td><td align="right" valign="top"><?PHP echo M4J_LANG_EXAMPLE; ?>:</td><td valign="top" align="right">
	           <div align="right">
	                <table  class="m4j_demo_field">
					 <tr>
					   <td><label>
						 <input type="checkbox"  name="demo" value="two" />
						 one</label></td>
					 </tr>
					 <tr>
					   <td><label>
						 <input type="checkbox"   name="demo" value="three" />
						 two</label></td>
					 </tr>
					 <tr>
					   <td><label>
						 <input type="checkbox"  name="demo" value="four" />
						 three</label></td>
					 </tr>
				   </table>
                    </div></td>
         </tr>
		 <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
			   <td valign="top"  align="left"><label>
				 <input type="radio" name="form" value="34" <?php if($form==34) echo'checked ';?> />
				 <?PHP echo M4J_LANG_LIST_MULTIPLE; ?></label></td><td align="right" valign="top"><?PHP echo M4J_LANG_EXAMPLE; ?>:</td><td width="100px" valign="top">
				   <div align="right">
						<select name="demo2[]" size="3" class="m4j_demo_field"  multiple="multiple">
						   <option value="two">one</option>
						   <option value="three">two </option>
						   <option value="four">three</option>
						 </select>
					   </div></td>
			 </tr>
		  <tr height="16px"><td colspan="3"></td></tr>
		 <tr><td colspan="3"><br/><?PHP echo M4J_LANG_OPTICAL_ALIGNMENT; ?><br/><br/></td></tr> 
		 <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
		 <td  valign="top" align="left">
		 	<?PHP echo M4J_LANG_ITEM_WIDTH_LONG; ?>
		 <td>
		 <td valign="top"> <div align="right">
		 <input class="m4j_demo_field" name="width" type="text" id="width" value="<?PHP echo $width;?>" />
		 </div></td>
		 </tr> 
		 <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
		 <td  valign="top" align="left">
		 	<?PHP echo M4J_LANG_ROWS_LIST; ?>
		 <td>
		 <td valign="top"> <div align="right">
		 <input class="m4j_demo_field" name="element_rows" type="text" id="element_rows" value="<?PHP echo $element_rows;?>" />
		 </div></td>
		 </tr>
		 <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
		 <td  valign="top" align="left">
		 	<?PHP echo M4J_LANG_ALIGNMENT_GROUPS; ?>
		 <td>
		 <td valign="top"> <div align="right">
		 <select name="alignment" id="alignment" class="m4j_demo_field">
						   <option value="1" <?PHP if($alignment==1) echo 'selected="selected"';?> ><?PHP echo M4J_LANG_VERTICAL;?></option>
						   <option value="0" <?PHP if($alignment==0) echo 'selected="selected"';?> ><?PHP echo M4J_LANG_HORIZONTAL;?></option>
		 </select>
		 </div></td>
		 </tr>
		 <tr height="12px"><td colspan="3"></td></tr>
       </table>
	<?PHP
	}//EOF element_options
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		

	function element_options_right($count=0,$options)
		{
			$o_array = explode(';',$options);
			$out='';
			if($count>0)
				$out='<br/><div class="m4j_option_field_wrapper">'.M4J_LANG_OPTIONS_VALUES_INTRO.'</div><br/>';
			for($t=0;$t<$count;$t++)
			{
				$value='';
				if($t<array_count) $value= $o_array[$t]; 
				$out.='<div class="m4j_option_field_wrapper"><input class="m4j_option_field" name="option'.'-'.$t.
					  '" type="text" id="option'.'-'.$t.'" size="80" maxlength="80" value="'.$o_array[$t].'" /></div>';
			}
	return $out;
	}//EOF element_options_right
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	
	function element_attachment($endings='',$maxsize='',$measure=1024){?>
	
	 <input name="form" type="hidden" id="form" value="40" />
	 <table width="500" border="0" cellpadding="0" cellspacing="1">
	 <tr><td colspan="3"><br/><?PHP echo M4J_LANG_TYPE_OF_ATTACHMENT; ?><br/><br/></td></tr>
	     
		 <tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
			 <td  valign="top" align="left">
				<b><?PHP echo M4J_LANG_ALLOWED_ENDINGS; ?></b>
			 </td>
			 <td valign="top"> 
				 <div align="right">
					 <textarea class="m4j_demo_field" style="width:300px;" name="endings" id="endings" rows="8"><?PHP echo $endings; ?></textarea>
				 </div>
			 </td>
		 </tr>
		
		<tr onmouseover="javascript:rowOver(this);" onmouseout="javascript:rowOut(this,true);">
			 <td  valign="top" align="left">
				<b><?PHP echo M4J_LANG_MAXSIZE; ?></b>
			 </td>
			 <td valign="top"> 
				 <div align="right">
				 	<table cellpadding="0" cellspacing="0" border="0">
						<tbody>
							<tr>
								<td>
									 <input class="m4j_demo_field" style="width:200px;" name="maxsize" type="text" id="maxsize" value="<?PHP echo $maxsize; ?>" />
								</td>
								<td>
									 <select name="measure" id="measure" class="m4j_demo_field"  style="width:100px;">
										   <option value="1" <?PHP if($measure==1) echo 'selected="selected"';?> ><?PHP echo M4J_LANG_BYTE;?></option>
										   <option value="1024" <?PHP if($measure==1024) echo 'selected="selected"';?> ><?PHP echo M4J_LANG_KILOBYTE;?></option>
										   <option value="1048576" <?PHP if($measure==1048576) echo 'selected="selected"';?> ><?PHP echo M4J_LANG_MEGABYTE;?></option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>					
				 </div>
			 </td>
		 </tr>
		 
		 <tr height="12px"><td colspan="3"></td></tr>
       </table>
	  
	   
	<?PHP
	}//EOF element_attachment
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	function element_attachment_right()
		{
		$out ='<div class="m4j_option_field_wrapper" align="left" style="padding-left:20px;">'.M4J_LANG_ELEMENT_ATTACHMENT_DESC.'
		

		<h4>'.M4J_LANG_IMAGES.' <a href="javascript: append(\'endings\',\'gif,jpg,jpeg,png,tif,tiff,bmp,rle,dib,raw,ico,icon,cdr,psd,pdd\');"> [ '.M4J_LANG_ALL.' ] </a></h4>
		        <a href="javascript: append(\'endings\',\'gif\');"> [ GIF ] </a> 
		        <a href="javascript: append(\'endings\',\'jpg,jpeg\');"> [ JPG ] </a> 
			    <a href="javascript: append(\'endings\',\'png\');"> [ PNG ] </a> 		
		        <a href="javascript: append(\'endings\',\'tif,tiff\');"> [ TIFF ] </a> 
				<a href="javascript: append(\'endings\',\'bmp,rle,dib\');"> [ BMP ] </a>
				<a href="javascript: append(\'endings\',\'raw\');"> [ RAW ] </a>  
		        <a href="javascript: append(\'endings\',\'ico,icon\');"> [ ICO ] </a> 
			    <a href="javascript: append(\'endings\',\'cdr\');"> [ Corel Draw ] </a> 		
		        <a href="javascript: append(\'endings\',\'psd,pdd\');"> [ Photoshop ] </a>		
		
		<br/>
		
		<h4>'.M4J_LANG_DOCS.' <a href="javascript: append(\'endings\',\'doc,pdf,rtf,ps,lwp,txt,text,wps,htm,html,odt,ods,odp,pps,ppt\');"> [ '.M4J_LANG_ALL.' ] </a></h4>
		        <a href="javascript: append(\'endings\',\'doc\');"> [ MS WORD ] </a> 
		        <a href="javascript: append(\'endings\',\'pdf\');"> [ PDF ] </a> 
			    <a href="javascript: append(\'endings\',\'rtf\');"> [ RICH TEXT FORMAT ] </a> 		
		        <a href="javascript: append(\'endings\',\'ps\');"> [ POSTSCRIPT ] </a> 
				<a href="javascript: append(\'endings\',\'lwp\');"> [ LOTUS WORD PRO ] </a><br/>
				<a href="javascript: append(\'endings\',\'txt,text\');"> [ TEXT ] </a>  
		        <a href="javascript: append(\'endings\',\'wps\');"> [ MS WORKS ] </a> 
			    <a href="javascript: append(\'endings\',\'htm,html\');"> [ HTML ] </a> 		
		        <a href="javascript: append(\'endings\',\'odt,ods,odp\');"> [ OPEN DOCUMENT ] </a>	
				<a href="javascript: append(\'endings\',\'pps,ppt\');"> [ POWERPOINT ] </a> 	

		<br/>
		
		<h4>'.M4J_LANG_AUDIO.' <a href="javascript: append(\'endings\',\'mp3,ra,ram,wav,wave,wma,au,mpa,m3u,aif,iff,mid,midi\');"> [ '.M4J_LANG_ALL.' ] </a></h4>
				<a href="javascript: append(\'endings\',\'mp3\');"> [ MP3 ] </a>
		        <a href="javascript: append(\'endings\',\'ra,ram\');"> [ REAL AUDIO ] </a> 
			    <a href="javascript: append(\'endings\',\'wav,wave\');"> [ WAV ] </a> 		
		        <a href="javascript: append(\'endings\',\'wma\');"> [ WMA ] </a>	
				<a href="javascript: append(\'endings\',\'au\');"> [ AU ] </a> 
				<a href="javascript: append(\'endings\',\'mpa\');"> [ MPA ] </a>  
			    <a href="javascript: append(\'endings\',\'m3u\');"> [ M3U ] </a> 	
		        <a href="javascript: append(\'endings\',\'aif\');"> [ AIF ] </a> 
		        <a href="javascript: append(\'endings\',\'iff\');"> [ IFF ] </a> 
		        <a href="javascript: append(\'endings\',\'mid,midi\');"> [ MIDI ] </a> 
		<br/>
		
		<h4>'.M4J_LANG_VIDEO.' <a href="javascript: append(\'endings\',\'avi,mov,movi,moov,qt,mp4,mpg,mpeg,rm,swf,\nwm,wmv,dvx,divx,flv,xvid\');"> [ '.M4J_LANG_ALL.' ] </a></h4>
				<a href="javascript: append(\'endings\',\'avi\');"> [ AVI ] </a>
		        <a href="javascript: append(\'endings\',\'mov,movi,moov,qt\');"> [ QUICKTIME ] </a> 
			    <a href="javascript: append(\'endings\',\'mp4\');"> [ MP4 ] </a> 		
		        <a href="javascript: append(\'endings\',\'mpg,mpeg\');"> [ MPG ] </a>	
				<a href="javascript: append(\'endings\',\'rm\');"> [ REAL MEDIA ] </a> 
				<a href="javascript: append(\'endings\',\'swf\');"> [ SHOCKWAVE ] </a>  <br/>
			    <a href="javascript: append(\'endings\',\'wm,wmv\');"> [ WINDOWS MEDIA ] </a> 	
		        <a href="javascript: append(\'endings\',\'dvx,divx\');"> [ DIVX ] </a> 
		        <a href="javascript: append(\'endings\',\'flv\');"> [ FLASH VIDEO ] </a> 
		        <a href="javascript: append(\'endings\',\'xvid\');"> [ XVID ] </a> 
		<br/>
		
		<h4>'.M4J_LANG_DATA.' <a href="javascript: append(\'endings\',\'xls,csv,dat,db,sql,mdb,xml,123\');"> [ '.M4J_LANG_ALL.' ] </a></h4>
				<a href="javascript: append(\'endings\',\'xls\');"> [ EXCEL ] </a>
		        <a href="javascript: append(\'endings\',\'csv\');"> [ CSV ] </a> 
			    <a href="javascript: append(\'endings\',\'dat\');"> [ DAT ] </a> 		
		        <a href="javascript: append(\'endings\',\'db\');"> [ DB ] </a>	
				<a href="javascript: append(\'endings\',\'sql\');"> [ SQL ] </a> 
				<a href="javascript: append(\'endings\',\'mdb\');"> [ MS ACCESS ] </a>  <br/>
			    <a href="javascript: append(\'endings\',\'xml\');"> [ XML ] </a> 	
		        <a href="javascript: append(\'endings\',\'123\');"> [ LOTUS SPREADSHEET ] </a> 
		<br/>
		
		<h4>'.M4J_LANG_COMPRESSED.' <a href="javascript: append(\'endings\',\'zip,rar,gz,gzip,deb,pkg,sea,sit,sitx,arg,lha\');"> [ '.M4J_LANG_ALL.' ] </a></h4>
				<a href="javascript: append(\'endings\',\'zip\');"> [ ZIP ] </a>
		        <a href="javascript: append(\'endings\',\'rar\');"> [ RAR ] </a> 
			    <a href="javascript: append(\'endings\',\'gz,gzip\');"> [ GZ ] </a> 		
		        <a href="javascript: append(\'endings\',\'deb\');"> [ DEB ] </a>	
				<a href="javascript: append(\'endings\',\'pkg\');"> [ PKG ] </a> 
				<a href="javascript: append(\'endings\',\'sea\');"> [ SEA ] </a>  
			    <a href="javascript: append(\'endings\',\'sit,sitx\');"> [ SIT ] </a> 
				<a href="javascript: append(\'endings\',\'arj\');"> [ ARJ ] </a>  
			    <a href="javascript: append(\'endings\',\'lha\');"> [ LHA ] </a> 

		<h4>'.M4J_LANG_OTHERS.' <a href="javascript: append(\'endings\',\'exe,ttf,otf,fon,fnt,fla,php,php4,php5,\nj,jav,java,class,eml,cfg,bin,iso,vcd\');"> [ '.M4J_LANG_ALL.' ] </a></h4>
				<a href="javascript: append(\'endings\',\'exe\');"> [ EXE ] </a>
		        <a href="javascript: append(\'endings\',\'ttf,otf,fon,fnt\');"> [ FONTS ] </a> 
			    <a href="javascript: append(\'endings\',\'fla\');"> [ FLASH ] </a> 		
		        <a href="javascript: append(\'endings\',\'php,php4,php5\');"> [ PHP ] </a>	
				<a href="javascript: append(\'endings\',\'j,jav,java,class\');"> [ JAVA ] </a> 
				<a href="javascript: append(\'endings\',\'eml\');"> [ EMAIL ] </a>  <br/>
			    <a href="javascript: append(\'endings\',\'cfg\');"> [ CFG ] </a> 	
		        <a href="javascript: append(\'endings\',\'bin\');"> [ BIN ] </a> 
		        <a href="javascript: append(\'endings\',\'iso\');"> [ ISO ] </a> 
		        <a href="javascript: append(\'endings\',\'vcd\');"> [ VCD ] </a>	
	
			 </div>';
	return $out;
	}//EOF element_attachment_right
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	
	function jobs_new($title=null,$email=null,$active=1,$captcha=1,$introtext=null,$maintext=null,$cid=null,$categories=null,$fid=null,$templates=null,$hidden=null,$editID=-1){
	global $helpers ;
	
	?>
	<form id="m4jForm" name="m4jForm" method="post" action="<?PHP echo M4J_JOBS_NEW.M4J_REMEMBER_CID_QUERY.M4J_HIDE_BAR ?>">
        
		
		  <table width="100%" border="0" cellspacing="0" cellpadding="6">
		  <tr>
		  	<td colspan="2">
			<?PHP echo M4J_LANG_TITLE_FORM; ?><br />
        		<input name="title" type="text" id="title" value="<?PHP echo $title; ?>" size="50" maxlength="60" style="width:100%"/>
			<br />
			</td>
		  </tr>
            <tr>
              <td width="50%" align="left" valign="top"><?PHP echo M4J_LANG_CATEGORY; ?><br />
                 <?PHP echo $helpers->category_menu($categories,$cid,null,1)?>
                <br/><br />
				<?PHP echo M4J_LANG_EMAIL; ?><br />
               <input name="email" type="text" id="email" style="width:100%" value="<?PHP echo $email; ?>" maxlength="60" />
			   <br/><br />
			   <table width="100%" cellpadding="0" cellspacing="0" border="0">
			   <tr>
				   <td>
					<?PHP echo M4J_LANG_ACTIVE; ?><br />
					  <select name="active" id="active" style="width:80px">
						<option value="1" <?PHP if($active==1) echo'selected="selected"'; ?>><?PHP echo M4J_LANG_YES; ?></option>
						<option value="0" <?PHP if($active==0) echo'selected="selected"'; ?>><?PHP echo M4J_LANG_NO; ?></option>
					</select>
					<br/>
					</td>
					<td>
						<?PHP echo M4J_LANG_CAPTCHA; ?><br />
					  <select name="captcha" id="captcha" style="width:80px">
						<option value="1" <?PHP if($captcha==1) echo'selected="selected"'; ?>><?PHP echo M4J_LANG_YES; ?></option>
						<option value="0" <?PHP if($captcha==0) echo'selected="selected"'; ?>><?PHP echo M4J_LANG_NO; ?></option>
					</select>
					<br/>
					</td>
				</tr>
				</table>
              </td>
              <td width="50%"><?PHP echo M4J_LANG_TEMPLATE; ?><br />
                <select name="fid" size="10" id="fid" style="width:100%">
				<?PHP 
				$first = false;
				if($fid==null) $first=true;
				foreach($templates as $template)
					{
					echo'<option value="'.$template->fid.'"';
					if($first) 
						{
						echo'selected="selected"';
						$first = false;
						}
					if($template->fid==$fid) echo'selected="selected"';
					echo' >'.$template->name.'</option>';
					}
				?>
                </select>
					
              </td>
            </tr>
          </table>
		 <?PHP 
		 echo M4J_LANG_INTROTEXT.':<br/>';
		 editorArea('introtext',$introtext,'introtext','934','240','75','30');  
		 echo'<br/><br/>'.M4J_LANG_MAINTEXT.':<br/>';
		 editorArea('maintext',$maintext,'maintext','934','400','75','30');		 
		 ?>
		 <br/> <br/>
		 <?PHP echo M4J_LANG_EMAIL_HIDDEN_TEXT; ?>
		 <br/>
		 <textarea style="width:100%" name="hidden" id="hidden" rows="6" ><?PHP echo $hidden;?></textarea>
		    <input name="task" type="hidden" id="task" />
          	<input name="id" type="hidden" id="id" value="<?PHP echo $editID; ?>" />
			<input name="former_cid" type="hidden" id="former_cid" value="<?PHP echo $cid; ?>" />
	    </form>
	 <br/>
	<?PHP
	}//EOF jobs_new
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	

	function link_menu($rows,$jid,$name=null)
		{
		global $helpers;
		foreach ($rows as $row)
			{	

			if(defined('_JEXEC')){
				$menutype = $row->menutype;
			}else{
				$menutype = $helpers->get_menutype($row->params);
			}	
			
			echo $helpers->link(M4J_LINK.M4J_HIDE_BAR.M4J_MENUTYPE.$menutype.'&id='.$jid.'&name='.$name.'&title='.$row->title,$row->title,'m4j_menu').'<br/>';	
			}
		
	}//EOF element_options_right
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	
	function link_form($jid,$parent,$access,$published, $menutype,$name=null,$title=null)
		{ ?>
		<form id="m4jForm" name="m4jForm" method="post" action="<?PHP 
		
		$link = M4J_LINK.M4J_HIDE_BAR.M4J_REMEMBER_CID_QUERY.'&menutype='.$menutype.'&title='.$title.'&id='.$jid; 
		if($name) $link .='&name='.$name;
		echo $link;
		?>">
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="50%" align="left" valign="top" >
		  <?PHP echo M4J_LANG_LINK_NAME; ?><br />  
		  <input name="link_name" type="text" id="link_name" size="80" maxlength="100" /> 
		  <br />
		  <br />
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="50%" align="left" valign="top"> <?PHP echo M4J_LANG_ACCESS_LEVEL; ?><br />
                
				<?PHP echo str_replace('name="access"','name="access" style="width:90%" ',$access); ?>								
              <br /></td>
              <td width="50%" align="left" valign="top"><?PHP echo M4J_LANG_PUBLISHED; ?><br />
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="60"><?PHP echo $published;?></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
      </td>
	  
      <td width="50%" align="left" valign="top" ><?PHP echo M4J_LANG_PARENT_LINK; ?><br />
		<?PHP echo  str_replace('name="parent"','name="parent" style="width:100%" ',$parent); ?>
      </td>
    </tr>
  </table>
  <input name="task" type="hidden" id="task" />
</form>
		<?PHP }//EOF link_form
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	

	function legend($mask=null)
		{
		global $helpers;
		
		switch($mask)
			{	
			case 'jobs':
				echo'<br/>'.M4J_LANG_LEGEND.'<br/>';
				echo '<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0"><tbody>';
				echo '<tr valign="top"><td height="16px" valign="top" width="16px" >'.$helpers->image('active.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_FORM.M4J_LANG_IS_VISIBLE.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('not_active.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_FORM.M4J_LANG_IS_HIDDEN.'</td>';
				echo '<td  height="16px" valign="top" width="40px" >'.$helpers->image('up.png').
					 ' '.$helpers->image('down.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_ASSIGN_ORDER.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('copy.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_DO_COPY.'</td></tr><tr>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('remove.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_DELETE.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('pen-small.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_EDIT.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('link.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_FORM.' '.M4J_LANG_LINK_TO_MENU.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('link2cat.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_CATEGORY.' '.M4J_LANG_LINK_TO_MENU.'</td></tr>';				
				echo '</tbody></table>';
			break;
			
			case 'cat':
				echo'<br/>'.M4J_LANG_LEGEND.'<br/>';
				echo '<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0"><tbody>';
				echo '<tr valign="top"><td height="16px" valign="top" width="16px" >'.$helpers->image('active.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_CATEGORY.M4J_LANG_IS_VISIBLE.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('not_active.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_CATEGORY.M4J_LANG_IS_HIDDEN.'</td>';
				echo '<td  height="16px" valign="top" width="40px" >'.$helpers->image('up.png').
					 ' '.$helpers->image('down.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_ASSIGN_ORDER.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('remove.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_DELETE.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('pen-small.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_EDIT.'</td>';
				echo '</tbody></table>';
			break;	
			
			case 'forms':
				echo'<br/>'.M4J_LANG_LEGEND.'<br/>';
				echo '<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0"><tbody>';
			
				echo '<tr><td  height="16px" valign="top" width="16px" >'.$helpers->image('copy.png').
					 '</td><td height="16px" valign="top" width="100px"  >'.M4J_LANG_DO_COPY.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('remove.png').
					 '</td><td height="16px" valign="top" width="70px" >'.M4J_LANG_DELETE.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('pen-small.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_EDIT_MAIN_DATA.'</td></tr>';
				echo '</tbody></table>';
			break;
			case 'formelements':
				echo'<br/>'.M4J_LANG_LEGEND.'<br/>';
				echo '<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0"><tbody>';
				echo '<tr valign="top"><td height="16px" valign="top" width="16px" >'.$helpers->image('active.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_ITEM.M4J_LANG_IS_VISIBLE.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('not_active.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_ITEM.M4J_LANG_IS_HIDDEN.'</td>';
				echo '<td height="16px" valign="top" width="16px" >'.$helpers->image('required.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_IS_REQUIRED.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('not_required.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_IS_NOT_REQUIRED.'</td></tr>';
				echo '<tr><td  height="16px" valign="top" width="40px" >'.$helpers->image('up.png').
					 ' '.$helpers->image('down.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_ASSIGN_ORDER.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('copy.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_DO_COPY.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('remove.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_DELETE.'</td>';
				echo '<td  height="16px" valign="top" width="16px" >'.$helpers->image('pen-small.png').
					 '</td><td height="16px" valign="top" >'.M4J_LANG_EDIT.'</td></tr>';
				echo '</tbody></table>';
			break;
			
			}
		
		}//EOF legend
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
	function config_list_wrap($area,$name,$value,$desc,$even,$width='250px')
		{?>
		<tr class="<?PHP echo (($even)?'even':'odd') ?>">
		  <td width="<?PHP echo $width; ?>"><?PHP echo $area; ?></td>
		  <td width ="28%">
		  	<input name="<?PHP echo $name; ?>" type="text" id="<?PHP echo $name; ?>" style="width:200px" value="<?PHP echo $value; ?>" maxlength="50">
		  </td>
		  <td><?PHP echo $desc; ?></td>
		</tr>		
		<?PHP }//EOF config_list_wrap
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
	function configuration()
		{ 
		global $mosConfig_live_site, $helpers;
		?>
		
	<form id="m4jForm" name="m4jForm" method="post" action="<?PHP echo M4J_CONFIG; ?>">
	<h3><?PHP echo M4J_LANG_MAIN_CONFIG;?></h3>
    <?PHP echo M4J_LANG_MAIN_CONFIG_DESC;?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="list">
  <tbody>
    <tr>
	  <th width="16%"><?PHP echo M4J_LANG_ADJUSTMENT;?></th>
      <th ><?PHP echo M4J_LANG_VALUE;?></th>
      <th width="56%"><?PHP echo M4J_LANG_DESCRIPTION;?></th>
    </tr>
<?PHP 	
$value = array(M4J_EMAIL_ROOT,M4J_MAX_OPTIONS,M4J_PREVIEW_WIDTH,M4J_PREVIEW_HEIGHT,M4J_CAPTCHA_DURATION,M4J_FROM_NAME,M4J_FROM_EMAIL,M4J_MAIL_ISO); 

$name = array( 'email_root','max_options','preview_width','preview_height','captcha_duration','from_name','from_email','mail_iso' );

$area = array(M4J_LANG_EMAIL_ROOT,M4J_LANG_MAX_OPTIONS,M4J_LANG_PREVIEW_WIDTH,M4J_LANG_PREVIEW_HEIGHT,M4J_LANG_CAPTCHA_DURATION,M4J_LANG_FROM_NAME,M4J_LANG_FROM_EMAIL,M4J_LANG_MAIL_ISO); 

$desc = array(M4J_LANG_EMAIL_ROOT_DESC,M4J_LANG_MAX_OPTIONS_DESC,M4J_LANG_PREVIEW_WIDTH_DESC,M4J_LANG_PREVIEW_HEIGHT_DESC,M4J_LANG_CAPTCHA_DURATION_DESC,M4J_LANG_FROM_NAME_DESC,M4J_LANG_FROM_EMAIL_DESC,M4J_LANG_MAIL_ISO_DESC);

$even = true;			  
for($t=0;$t<sizeof($value);$t++)
	{	
		HTML_m4j::config_list_wrap($area[$t],$name[$t],$value[$t],$desc[$t],$even);		  
		$even = !$even;
	}

?>	  
    <tr  class="odd">
	  <td  width="250"><?PHP echo M4J_LANG_HELP_ICON; ?></td>
		<td width ="28%">
		<?PHP
		$break = 0;
		for($t=0; $t<7;$t++)
			{
			echo'<label><input type="radio" name="help_icon" value="'.$t.'"';
			if(M4J_HELP_ICON==$t) echo 'checked';
			echo'><img src="'.$mosConfig_live_site.'/components/com_mad4joomla/images/help'.$t.'.png" /></label>
			';
			if($break++==4) {$break=0; echo'<br/>';}
			}
		
		?>                   
		</td>
		<td><?PHP echo M4J_LANG_HELP_ICON_DESC; ?></td>
	</tr>
	
	<tr class="even">
 	  <td  width="250"><?PHP echo M4J_LANG_HTML_MAIL; ?></td>
      <td width ="28%"><select name="html_mail">
        <option value="true" <?PHP if(M4J_HTML_MAIL) echo 'selected="selected"'; ?> ><?PHP echo M4J_LANG_YES; ?></option>
        <option value="false" <?PHP if(!M4J_HTML_MAIL) echo 'selected="selected"'; ?> ><?PHP echo M4J_LANG_NO; ?></option>
      </select>
      </td>
	  <td><?PHP echo M4J_LANG_HTML_MAIL_DESC; ?></td>
	</tr>
    
	<tr  class="odd">
	  <td width="250"><?PHP echo M4J_LANG_SHOW_LEGEND; ?></td>
		<td width ="28%"><select name="show_legend">
          <option value="true" <?PHP if(M4J_SHOW_LEGEND) echo 'selected="selected"'; ?> ><?PHP echo M4J_LANG_YES; ?></option>
          <option value="false" <?PHP if(!M4J_SHOW_LEGEND) echo 'selected="selected"'; ?> ><?PHP echo M4J_LANG_NO; ?></option>
        </select></td>
		<td><?PHP echo M4J_LANG_SHOW_LEGEND_DESC; ?></td>
	</tr>
	<tr  class="even">
	  <td width="250"><?PHP echo M4J_LANG_CHOOSE_CAPTCHA; ?></td>
		<td width ="28%"><select name="captcha">
          <option value="CSS" <?PHP if(M4J_CAPTCHA=="CSS") echo 'selected="selected"'; ?> ><?PHP echo M4J_LANG_CSS; ?></option>
		  <option value="SPECIAL" <?PHP if(M4J_CAPTCHA=="SPECIAL") echo 'selected="selected"'; ?> ><?PHP echo M4J_LANG_SPECIAL; ?></option>
          <option value="SIMPLE" <?PHP if(M4J_CAPTCHA=="SIMPLE") echo 'selected="selected"'; ?> ><?PHP echo M4J_LANG_SIMPLE; ?></option>

        </select></td>
		<td><?PHP echo M4J_LANG_CAPTCHA_DESC; ?></td>
	</tr>
  </tbody>
</table>
  <h3><?PHP echo M4J_LANG_CSS_CONFIG;?></h3>
    <?PHP echo M4J_LANG_CSS_CONFIG_DESC;?>
  <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="list">
  <tbody>
    <tr>
	  <th width="16%"><?PHP echo M4J_LANG_AREA;?></th>
      <th>CSS</th>
      <th width="56%"><?PHP echo M4J_LANG_DESCRIPTION;?></th>
    </tr>
	
<?PHP 
$value = array(M4J_CLASS_HEADING, M4J_CLASS_HEADER_TEXT, M4J_CLASS_LIST_WRAP, M4J_CLASS_LIST_HEADING, M4J_CLASS_LIST_INTRO, 
			   M4J_CLASS_FORM_WRAP, M4J_CLASS_FORM_TABLE, M4J_CLASS_ERROR, M4J_CLASS_SUBMIT_WRAP, M4J_CLASS_SUBMIT, M4J_CLASS_REQUIRED);

$name = array(
					'class_heading','class_header_text','class_list_wrap','class_list_heading','class_list_intro','class_form_wrap',                       
					'class_form_table','class_error','class_submit_wrap','class_submit','class_required'                       
					  );
					  
$area = array(M4J_LANG_CLASS_HEADING, M4J_LANG_CLASS_HEADER_TEXT, M4J_LANG_CLASS_LIST_WRAP, M4J_LANG_CLASS_LIST_HEADING,
			  M4J_LANG_CLASS_LIST_INTRO, M4J_LANG_CLASS_FORM_WRAP, M4J_LANG_CLASS_FORM_TABLE, M4J_LANG_CLASS_ERROR,
			  M4J_LANG_CLASS_SUBMIT_WRAP, M4J_LANG_CLASS_SUBMIT, M4J_LANG_CLASS_REQUIRED); 
	
$desc = array(M4J_LANG_CLASS_HEADING_DESC, M4J_LANG_CLASS_HEADER_TEXT_DESC, M4J_LANG_CLASS_LIST_WRAP_DESC, 
			  M4J_LANG_CLASS_LIST_HEADING_DESC, M4J_LANG_CLASS_LIST_INTRO_DESC, M4J_LANG_CLASS_FORM_WRAP_DESC, 
			  M4J_LANG_CLASS_FORM_TABLE_DESC, M4J_LANG_CLASS_ERROR_DESC, M4J_LANG_CLASS_SUBMIT_WRAP_DESC, 
			  M4J_LANG_CLASS_SUBMIT_DESC, M4J_LANG_CLASS_REQUIRED_DESC);

$even = true;			  
for($t=0;$t<sizeof($value);$t++)
	{	
		HTML_m4j::config_list_wrap($area[$t],$name[$t],$value[$t],$desc[$t],$even);		  
		$even = !$even;
	}
?>
  </tbody>
</table>
<br />
<input type="submit" name="reset" value="<?PHP echo M4J_LANG_RESET; ?>" />
<br />
<br />
<input name="task" type="hidden" id="task" />
</form>
		
		
		<?PHP }
	}//EOF HTML_m4j Class 
	
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //		
//++++++++++++++++++++++++++++++++ New Class HTML_m4j_preview +++++++++++++++++++++++++++++++ //
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //	
	class HTML_m4j_preview
	{
	
		var $output =  "";
		
		
		function HTML_m4j_preview()
			{
			$this->output = "<h1>".M4J_LANG_PREVIEW."</h1><br /><table width='".M4J_PREV_TABLE."' border='0' cellpadding='0' cellspacing='0' ><tbody>";
			}
		
		
		
		
		function add($question=null,$answer=null)
			{
			global $helpers;
			$this->output .= '<tr>
								<td valign="top" width="'.M4J_TABLE_QWIDTH.'">'.$question.'</td>
								<td valign="top" width="'.M4J_TABLE_AWIDTH.'">';
			$this->output .= $helpers->replace_yes_no(str_replace("\\","",$answer));
			$this->output .= '</td></tr>';
			}
			
		function make($top=null,$bottom=null)
			{
			$this->output .= '</tbody></table>
								<p style="text-align: center;">
									<input id="Login" type="submit" onclick="tb_remove()" value="'.M4J_LANG_CLOSE_PREVIEW.'"/>
								</p>';
			
			echo'<div id="hiddenModalContent" style="display:none"">'.$top.$this->output.$bottom.'</div>';
			}
	
	
	
	
	}//EOF HTML_preview CLASS	
	
	
	
	
	
?>
