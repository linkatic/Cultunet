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
   
   class form_factory{
   
	   function yes_no($form,$eid,$checked)
	   		{
			   $out=null;
			   switch($form)
				{
				case 1:
				$out .='  <input name=\'m4j-'.$eid.'\' type=\'checkbox\' id=\'m4j-'.$eid.'\' value= \'1\' ';
				($checked==1) ? $out.='checked=\'checked\' />' : $out.= ' />';
				break;
				
				case 2:
				$out .='<select style=\'width:70px;\' name=\'m4j-'.$eid.'\' id=\'m4j-'.$eid.'\'>
						   <option ';
				if ($checked==1) $out.= 'selected=\'selected\'';
				$out.= '>{M4J_YES}</option>
						   <option ';
				if ($checked!=1) $out.= 'selected=\'selected\'';
				$out .=  '>{M4J_NO}</option>
						</select>';
				break;
				}
				
			return $out;
	   		}//EOF yes_no
 
 	   function date($eid,$value='')
	   		{
			   $out=null;
			   $out  = '<input id="m4j-'.$eid.'" type="text" size="30" name="m4j-'.$eid.'" value="'.$value.'"/>
			   			<input type="reset" onclick="return showCalendar(\'m4j-'.$eid.'\');" value=" ... "/>';



			return $out;
	   		}//EOF yes_no
			  
 	function text($form,$eid,$maxchars=60,$element_rows=3,$value='',$width='100%')
			{
			$out=null;
			$add='';
			if( substr($width, -1, 1)=='%') $add='%';
			else $add='px';
			$width = intval($width);
			if ($width == 0 || $width == NULL) $width = "100%";
			else $width .= $add;
			
			   switch($form)
				{
				case 20:
				$out .= '<input style=\'width: '.$width.';\' id=\'m4j-'.$eid.'\' name=\'m4j-'.$eid.'\' type=\'text\' size=\'18\' maxlength=\''.$maxchars.'\' value= \''.$value.'\' />';
				break;
				
				case 21:
				$out .= '<textarea style=\'width: '.$width.';\' id=\'m4j-'.$eid.'\' name=\'m4j-'.$eid.'\' maxlength=\''.$maxchars.'\' rows=\''.$element_rows.'\'>'.$value.'</textarea>';
				break;
				}
			
			return $out;
			}//EOF text
   
 	function options($form,$eid,$options=null,$element_rows=3,$width='100%',$alignment=1)
			{
			$out=null;
			if($width==null || intval($width)==0) $width='100%';
			$add='';
			if( substr($width, -1, 1)=='%') $add='%';
			else $add='px';
			$width = intval($width);
			if ($width == 0 || $width == NULL) $width = "100%";
			else $width .= $add;
						
			if($options!=null)
				{
				$option = explode(';',$options);
				
				
				$count = sizeof($option)-1;
				
				   switch($form)
					{
					case 30:
					$out .= '<select id=\'m4j-'.$eid.'\' name=\'m4j-'.$eid.'\'style=\'width: '.$width.';\' >';
					for($t=0;$t<$count;$t++)
						$out .='<option value="'.$option[$t].'" {'.$eid.'-'.$t.'}>'.$option[$t].'</option>';
					$out .='</select>';
					break;
					
					case 31:
					if($alignment==1)
						{
						$out .= '<table style=\'width: '.$width.';\' ><tbody>';
						for($t=0;$t<$count;$t++)
							$out .= '<tr><td align=\'left\' valign=\'top\'>
											<label>
											<input type=\'radio\' id=\'m4j-'.$eid.'-'.$t.'\' name=\'m4j-'.$eid.'\' value="'.$option[$t].'" {'.$eid.'-'.$t.'} />'.$option[$t].'
											</label>
									</td></tr>';
									
						$out .='</tbody></table>';
						}
					else
						{
						$out .= '<div style=\'width:'.$width.';\'>';
						for($t=0;$t<$count;$t++)
							$out .= '<label  style=\'padding-right: 4px;\' class=\'m4j_toLeft\'>
										<input type=\'radio\' id=\'m4j-'.$eid.'-'.$t.'\' name=\'m4j-'.$eid.'\' value="'.$option[$t].'" {'.$eid.'-'.$t.'} />'.$option[$t].'
									</label>';
						$out .= '</div>';
						}
					break;
					
					case 32:
					$out .= '<select id=\'m4j-'.$eid.'\' name=\'m4j-'.$eid.'\' size=\''.$element_rows.'\' style=\'width: '.$width.';\'>';
					for($t=0;$t<$count;$t++)
						$out .='<option value="'.$option[$t].'" {'.$eid.'-'.$t.'} >'.$option[$t].'</option>';
					$out .='</select>';
					break;
					
					case 33:
					if($alignment==1)
						{
						$out .= '<table style=\'width: '.$width.';\' ><tbody>';
	
						for($t=0;$t<$count;$t++)
							$out .= '<tr><td align=\'left\' valign = \'top\'>
											 <label>
											 <input type=\'checkbox\' id=\'m4j-'.$eid.'-'.$t.'\' name=\'m4j-'.$eid.'[]\' value="'.$option[$t].'" {'.$eid.'-'.$t.'} />'.$option[$t].'
											 </label>
									</td></tr>';	
						
						 $out .='</tbody></table>';
						 }
					else
						{
						$out .= '<div style=\'width:'.$width.';\'>';
						for($t=0;$t<$count;$t++)
							$out .= '<label style=\'padding-right: 4px;\'  class=\'m4j_toLeft\'>
										 <input type=\'checkbox\' id=\'m4j-'.$eid.'-'.$t.'\' name=\'m4j-'.$eid.'[]\' value="'.$option[$t].'" {'.$eid.'-'.$t.'} />'.$option[$t].'
									 </label>';
						$out .= '</div>';
						}
					break;
					
					case 34:
					$out .= '<select id=\'m4j-'.$eid.'\' name=\'m4j-'.$eid.'[]\' size=\''.$element_rows.'\' multiple=\'multiple\' style=\'width: '.$width.';\' >';
					for($t=0;$t<$count;$t++)
						$out .='<option value="'.$option[$t].'" {'.$eid.'-'.$t.'} >'.$option[$t].'</option>';
					$out .='</select>';
					break;
					}					
				}
			return $out;
			}//EOF options 
   
     
    function attachment($eid,$value='')
	   		{
			   $out=null;
			   $out  = '<input id="m4j-'.$eid.'" type="file"  name="m4j-'.$eid.'" />';



			return $out;
	   		}//EOF attachment
			 
   function get_html($form,$eid,$parameter=null,$options=null)
		   {
		   $html = null;
			switch($form)
					  {
					  
					  case ($form<10):
					  $html .= $this->yes_no($form,$eid,$parameter['checked']);
					  break;
				
					  case ($form>=10 && $form<20):
					  $html .= $this->date($eid,'{'.$eid.'}');
					  break;	  
				
					  case ($form>=20 && $form<30):
					  $html .= $this->text($form,$eid,$parameter['maxchars'],$parameter['element_rows'],'{'.$eid.'}',$parameter['width']);
					  break;
					  
					  case ($form>=30 && $form<40):
					  $html .= $this->options($form,$eid,$options,$parameter['element_rows'],$parameter['width'],intval($parameter['alignment']));
					  break;  
					  
					  case ($form>=40 && $form<50):
					  $html .= $this->attachment($eid,'{'.$eid.'}');
					  break;  
					  
					  }//EOF switch form
			 global $database;
			 return $database->getEscaped($html);
		  	 }

   }//EOF Class form_factory
   

	//* creating a form_factory object
   $ff = new form_factory();
  $GLOBALS['ff'] = $ff;
?>